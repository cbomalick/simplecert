<?php

class User {

    public function __construct($userId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        if(is_null($userId)){
        return;
        } else {
            // $sql = "SELECT users.*, userpreferences.preference, userpreferences.value FROM users JOIN userpreferences ON users.userid = userpreferences.userid WHERE users.userid = ?";
            $sql = "SELECT * FROM users WHERE users.userid = ?";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$userId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $this->userId = $row["userid"];
                    // $this->userRoles = explode(",", $row["roles"]);
                    // $this->tokens = $this->fetchTokens();
                    // $this->preferences[$row["preference"]] = $row["value"];
                }
            }
        }
    }

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

        $sql = "SELECT tokens FROM userroles WHERE role IN ($in) AND status = 'Active'";
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

    public function addNew(){
        //Verify email hasn't been used
        $sql = "SELECT count(username) FROM users WHERE username = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->emailAddress]);
        $count = $stmt->fetch();

        if($count['count(username)'] > 0){
            Echo"Error: This email has already been associated with a different account";
            exit;
        } 
        
        //Create users record (Username(email), password, userid, companylist, roles status, forcereset)
        $id = new IdNumber();
        $this->userId = $id->generateId("USR");
        $collapsedCompanies = '';
        $collapsedRoles = '';
        
        //Collapse array values into comma separated string
        foreach($this->companyList as $company){
            $collapsedCompanies .= $company . ",";
        }

        foreach($this->accessTokens as $role){
            $collapsedRoles .= $role . ",";
        }

        $sql = "INSERT INTO users 
        (username,userid,forcereset,primarycompany,companylist,roles,status,createdby,createddate) VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->emailAddress, $this->userId, 'Y', $this->primaryCompany, $collapsedCompanies, $collapsedRoles, $this->status, $this->createdBy, $this->createdDate]);

        $sql = "INSERT INTO userpreferences 
        (userid,preference,value,status,createdby,createddate) VALUES 
        (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->userId, 'timeZone', 'America/Chicago', 'Active', $this->createdBy, $this->createdDate]);

        //Create employee record (Employeeid, payrollid, hiredate, position, department)
        $sql = "INSERT INTO employee 
        (company,employeeid,hiredate,department,position,salary,status,createdby,createddate) VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->primaryCompany, $this->userId, $this->hireDate, $this->department, $this->position, $this->salary, $this->status, $this->createdBy, $this->createdDate]);

        //Create person record (personid, persontype, contacttype, firstname lastname)
        $sql = "INSERT INTO person 
            (company,personid,persontype,firstname,lastname,status,createdby,createddate) VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$this->primaryCompany, $this->userId, 'Employee', $this->firstName, $this->lastName, $this->status, $this->createdBy, $this->createdDate]);

        //Email
        $sql = "INSERT INTO email 
        (personid,emailaddress,status,createdby,createddate) VALUES 
        (?, ?, ?, ?, ?)";
        
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->userId, $this->emailAddress, $this->status, $this->createdBy, $this->createdDate]);

        //Phone
        $sql = "INSERT INTO phone 
        (personid,phone,status,createdby,createddate) VALUES 
        (?, ?, ?, ?, ?)";
        
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->userId, $this->phoneNumber, $this->status, $this->createdBy, $this->createdDate]);
        
        //Generate password request
        $this->verificationString = $id->generatePasswordString();
        $hashString = md5($this->verificationString);
        $CurrentDateTime = date("Y-m-d H:i:s");
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        $sql = "INSERT INTO password_change_requests 
        (email,time,hashstring,ipaddress) VALUES 
        (?,?,?,?)";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->emailAddress, $CurrentDateTime, $hashString, $ipAddress]);

        //Send introduction email with link to set password
        $this->sendIntro();
    }

    public function sendIntro(){
        //Send verification email
        $site = new Site();
        $to = $this->emailAddress;
        $subject = "{$site->siteName} - Please verify your account";
        $message = "You have been added as a {$site->siteName} user! Please use the link below to verify your account and set your password. \n \n http://{$site->webAddress}/user/verify/{$this->verificationString}";
        $headers = "From: noreply@{$site->domain}" . "\r\n" .
            "Reply-To: noreply@{$site->domain}" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
        mail($to, $subject, $message, $headers);

        Echo"<div class=\"requestform\">
            <h2>Add Staff</h2>
                <p style=\"text-align: center;\">Account created for {$this->firstName} {$this->lastName}</p>
        </div>";

    }

    public function passwordVerify($key){
        $hashString = md5($key);
        $sql = "SELECT COUNT(seqno) FROM password_change_requests WHERE status != 'Used' AND hashstring = ? AND time >= now() - INTERVAL 1 DAY";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$hashString]);
        $row = $stmt->fetch();

        $sql = "";
        if($row['COUNT(seqno)'] >= 1){
            return true;
        } else {
            return false;
        }
    }

    public function updatePassword($email,$key,$password,$loggedInName){
        $newPassword = password_hash($password, PASSWORD_DEFAULT);
        $hashString = md5($key);
        $CurrentDateTime = date("Y-m-d H:i:s");

        //Mark reset key as Used
        $sql = "UPDATE password_change_requests set status = 'Used' WHERE email = ? AND status != 'Used' AND hashstring = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$email, $hashString]);

        //Update password
        $sql = "UPDATE users set password = ?, forcereset = 'N', lastmodifiedby = ?, lastmodifieddate = ? WHERE username = ? AND status ='Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$newPassword, $loggedInName, $CurrentDateTime, $email]);
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

    // public function threeStrikes($email,$reason){
    //     //User users will become locked if incorrect login is attempted 3x without success
    //     $CurrentDateTime = date("Y-m-d H:i:s");
    //     $ipAddress = $_SERVER['REMOTE_ADDR'];
    //     $interval = 30;

    //     $sql = "INSERT INTO failed_login 
    //     (email,reason,createdtime,ip,status) VALUES 
    //     (?,?,?,?,'Active')";
    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$email, $reason, $CurrentDateTime, $ipAddress]);

    //     //Pull in staffid associated with email
    //     $sql = "SELECT staffid FROM staff WHERE email = ?";
    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$email]);
    //     $staffRow = $stmt->fetch();
    //     $staffId = $staffRow[0]["staffid"];

    //     //Get count of failed attempts. If more than 3, lock account
    //     $sql = "SELECT COUNT(seqno) FROM failed_login WHERE email = ? AND status = 'Active' AND createdtime  <= now() - INTERVAL ? MINUTE";
    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$email, $interval]);
    //     $strikes = $stmt->fetch();

    //     $staff = new Staff($staffId);
    //     if($strikes > 3){
    //         $staff->lockStaff($staffId);
    //         Echo "Your account has been locked from too many failed attempts";
    //     }
    // }

    // public function lockStaff($staffId){
    //     $CurrentDateTime = date("Y-m-d H:i:s");

    //     //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
    //     //Revoke account access
    //     $sql = "UPDATE users SET status = 'Locked' WHERE staffid = ?";
    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$staffId]);

    //     $sql = "UPDATE staff SET status = 'Locked' WHERE staffid = ?";
    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$staffId]);
    // }

    // public function unlockStaff($staffId){
    //     $CurrentDateTime = date("Y-m-d H:i:s");

    //     //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
    //     //Give back account access
    //     $sql = "UPDATE users SET status = 'Active' WHERE staffid = ?";
    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$staffId]);

    //     $sql = "UPDATE staff SET status = 'Active' WHERE staffid = '$staffId'";
    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$staffId]);

    //     $staff = new Staff($staffId);
    //     $sql = "UPDATE failed_login SET status = 'Expired' WHERE email = ? AND status = 'Active'";
    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$staff->email]);
    // }

    // public function deactivateStaff($staffId,$loggedInEmployee){
    //     $CurrentDateTime = date("Y-m-d H:i:s");

    //     //TODO: if ($employee->isValid($loggedInEmployee)){ execute } else { error }
    //     //Remove employee profile
    //     $sql = "UPDATE staff SET status = 'InActive', lastmodifieddate = ?, lastmodifiedby = ? WHERE staffid = ?";
    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$CurrentDateTime, $loggedInEmployee, $staffId]);

    //     //Revoke account access
    //     $sql = "UPDATE users SET status = 'InActive', lastmodifieddate = ?, lastmodifiedby = ? WHERE staffid = ?";
    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$CurrentDateTime, $loggedInEmployee, $staffId]);
    // }

    public function expireAllResets(){
        //When a new password is requested, expire any previously requested hashes that haven't been used
        $sql = "UPDATE password_change_requests SET status = 'Used' WHERE email = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->emailAddress]);
    }

    // public function isValid($loggedInEmployee){
    // return true;
    // }

    // public function checkPagePermission($checkLevel){
    // //Can be inserted in any page to verify that user has access. If they do not, they cannot access the screen/section
    //     if($this->userLevel < $checkLevel){
    //         Echo "Error: Insufficient access rights<br /></br />If problem persists, please contact your system administrator";
    //         exit;
    //     }

    // }

}

?>