<?php

class User {

    public function __construct($userId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        if(is_null($userId)){
        return;
        } else {
            $sql = "SELECT users.*, user_preferences.preference, user_preferences.value FROM users JOIN user_preferences ON users.userid = user_preferences.userid WHERE users.userid = ?";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$userId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $this->userId = $row["userid"];
                    $this->personId = $row["personid"];
                    $this->fullName = (new Person($this->personId))->getFullName();
                    $this->primaryCompany = $row["primarycompany"];
                    $this->companyList = $row["companylist"];
                    $this->allowedCompanies = explode(",", $this->companyList);
                    $this->userRoles = explode(",", $row["roles"]);
                    $this->tokens = $this->fetchTokens();
                    $this->preferences[$row["preference"]] = $row["value"];
                }
            }
        }
    }

    //** Check user permissions */
    public function validatePermissions($requiredToken){
        //Check to make sure user has appropriate permissions for task they are performing
        if(in_array($requiredToken, $this->tokens)){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function fetchTokens(){
        $in  = str_repeat('?,', count($this->userRoles) - 1) . '?';
        $sql = "SELECT tokens FROM user_roles WHERE role IN ($in) AND status = 'Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute($this->userRoles);
        $row = $stmt->fetchAll();

        $objArray = [];
        if(!empty($row)){
            foreach($row as $row){
                $array = explode(",", $row['tokens']);
                $objArray = array_merge ($objArray, $array);
            }
        }
        return array_unique($objArray);
    }

    //** Password reset process */
    public function passwordVerify($key){
        $hashString = md5($key);
        $sql = "SELECT COUNT(seqno),email FROM password_change_requests WHERE status != 'Used' AND hashstring = ? AND time >= now() - INTERVAL 1 DAY";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$hashString]);
        $row = $stmt->fetch();

        if($row['COUNT(seqno)'] >= 1){
            $this->email = $row['email'];
            return true;
        } else {
            return false;
        }
    }

    public function updatePassword($email,$key,$password){
        $newPassword = password_hash($password, PASSWORD_DEFAULT);
        $hashString = md5($key);
        $CurrentDateTime = date("Y-m-d H:i:s");

        //Mark reset key as Used
        $sql = "UPDATE password_change_requests set status = 'Used' WHERE email = ? AND status != 'Used' AND hashstring = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$email, $hashString]);

        //Update password
        $sql = "UPDATE user_auth set password = ?, setdate = ? WHERE username = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$newPassword, $CurrentDateTime, $email]);
    }

    public function forgotPassword($emailAddress){
        //Check if account exists
        $sql = "SELECT count(username) FROM users WHERE username = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$emailAddress]);
        $count = $stmt->fetch();

        if($count['count(username)'] > 0){
            $CurrentDateTime = date("Y-m-d H:i:s");
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $ID = new IdNumber;

            //Expire any previous password requests
            $this->emailAddress = $emailAddress;
            $this->expireAllResets();
            
            //Generates a 32-character number and then hashes with md5
            //User gets an email with the number, and the hash is stored in the DB
            //Verification checks the hash of the number in email link. If it matches, password can be set
            $verificationString = $ID->generatePasswordString();
            $hashString = md5($verificationString);

            $sql = "INSERT INTO password_change_requests 
            (email,time,hashstring,ipaddress) VALUES 
            (?,?,?,?)";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$emailAddress, $CurrentDateTime, $hashString, $ipAddress]);

            //Send verification email
            $site = new Site();
            $to = $emailAddress;
            $subject = "{$site->siteName} - Forgot your password?";
            $message = "A password reset has been requested for your {$site->siteName} account by {$ipAddress}. Please use the link below to verify your account and set your new password. \n \n http://{$site->webAddress}/user/verify/{$verificationString} \n \n If you did not request this change you can ignore this email.";
            $headers = "From: noreply@{$site->domain}" . "\r\n" .
                "Reply-To: noreply@{$site->domain}" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();
            mail($to, $subject, $message, $headers);
        }
    }

    public function expireAllResets(){
        //When a new password is requested, expire any previously requested hashes that haven't been used
        $sql = "UPDATE password_change_requests SET status = 'Used' WHERE email = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->emailAddress]);
    }

    //** User Preferences */
    public function updatePreferences(){
        //Package all update methods into this carrier method
        //Allows for expansion of preferences options later
        $this->updateTimeZone();
        $audit = new AuditLog("Update", "User Preferences", "Updated User Preferences");
    }

    private function updateTimeZone(){
        $sql = "UPDATE user_preferences SET value = ? WHERE preference = 'timeZone' AND userid = ? AND status = 'Active'";

        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->preferences["timeZone"], $this->userId]);
    }

    //** Lock user after 3 incorrect passwords */
    public function threeStrikes($email,$reason){
        //User accounts will become locked if incorrect login is attempted 3x without success
        $CurrentDateTime = date("Y-m-d H:i:s");
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        $sql = "INSERT INTO failed_login 
        (email,reason,createdtime,ip,status) VALUES 
        (?,?,?,?,'Active')";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$email, $reason, $CurrentDateTime, $ipAddress]);

        //Is this really needed since locking now just looks at # of attempts within 30mins?
        // if($this->isLocked($email)){
        //     $this->lockUser($email);
        // }
    }

    public function isLocked($email){
        $interval = 30; //30 minute default timespan
        $strikes = 3; //3 incorrect password attempts within 30mins will result in lockout

        //Get count of failed attempts. If more than 3, lock account
        $sql = "SELECT COUNT(email) FROM failed_login WHERE email = ? AND status = 'Active' AND createdtime  <= (now() - INTERVAL ? MINUTE)";
        //SELECT COUNT(email) FROM failed_login WHERE email = 'example@gmail.com' AND status = 'Active' AND createdtime > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
        //??? WHY IS THIS NOT WORKING
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$email, $interval]);
        $row = $stmt->fetch();

        if($row['COUNT(email)'] > $strikes){
            return TRUE;
        } else {
            return FALSE;
        }   
    }

    private function lockUser($email){
        $sql = "UPDATE users SET status = 'Locked' WHERE username = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$email]);

        $audit = new AuditLog("Update", "User Locked", "Locked {$email}");
    }

    private function unlockUser($email){
    $sql = "UPDATE users SET status = 'Active' WHERE username = ?";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute([$email]);

    $audit = new AuditLog("Update", "User Unlocked", "Unlocked {$email}");
    }

    public function expireFailures($email){
        $sql = "UPDATE failed_login SET status = 'Expired' WHERE email = ? AND status = 'Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$email]);
    }
}

?>