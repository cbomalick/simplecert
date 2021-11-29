<?php
    $user = new User("NEW");
    $verificationKey = $_POST['verificationKey'];
    $password = $_POST['newpass1'];
    $passwordConfirm = $_POST['newpass2'];
    $emailAddress = $_POST['emailAddress'];

    if(empty($loggedInName)){
        $loggedInName = "Not logged in";
    }

    Echo "<h2>Update Password</h2>";

    if($user->passwordVerify($verificationKey)){
        if($password != $passwordConfirm){
            Echo"<p style=\"text-align: center;\">Error: Passwords did not match.</p>";
            Echo"<p style=\"text-align: center;\"><button type=\"button\" class=\"button\" onclick=\"window.location.href = 'user/verify/{$verificationKey}';\">Go Back</button></p>";
            exit;
        } else if (strlen($password) < 8){
            //Require 8 character minimum length per 2020 Microsoft password policy recommendations https://docs.microsoft.com/en-us/microsoft-365/admin/misc/password-policy-recommendations?view=o365-worldwide
            Echo"<p style=\"text-align: center;\">Error: Password must be 8 or more characters.</p>";
            Echo"<p style=\"text-align: center;\"><button type=\"button\" class=\"button\" onclick=\"window.location.href = 'user/verify/{$verificationKey}';\">Go Back</button></p>";
            exit;
        }

        $user->updatePassword($emailAddress,$verificationKey,$password,$loggedInName);
        Echo "<p style=\"text-align: center;\">Password updated successfully</p>";
        Echo"<p style=\"text-align: center;\"><button type=\"button\" class=\"button\" onclick=\"window.location.href = 'dashboard';\">Continue</button></p>";
    } else {
        Echo "<p style=\"text-align: center;\">Sorry, this code is no longer valid</p>";
    }

?>