<?php

if (!isset($_GET['id']) || empty($_GET['id'])) {
    Echo'Error: Invalid verification code';
    exit;
} else {
    $verificationKey = $_GET['id'];
}

// Echo"<pre>";
// var_dump($_GET);
// Echo"</pre>";

$lov = LOV::getInstance()->getLOV();

//Generate javascript warnings for required fields
$requiredFields = [
    "newpass1" => "Enter New Password",
    "newpass2" => "Confirm New Password",
    "emailAddress" => "Enter Email Address"
];
$lov->requiredFields($requiredFields);

$user = new User();
if($user->passwordVerify($verificationKey)){
    Echo "<h2>Update Password</h2>
    <div class=\"boxwrapper\">
        <div class=\"box mediumbox\">
        <div class=\"boxcontent\">
        <p class=\"text-left\">
                Password must be 8 or more characters
            </p>
        <form method=\"post\" action=\"user\\verifysub\\$verificationKey\">
            <input name=\"verificationKey\" id=\"verificationKey\" value=\"$verificationKey\" hidden>
            <p class=\"header\">Email Address</p>
                <p class=\"text-left\"><input name=\"emailAddress\" id=\"emailAddress\" value=\"\">
                <span id=\"emailAddressWarning\" class=\"requiredfield\"></span></p>
            <p class=\"header\">New Password</p>
                <p class=\"text-left\"><input type=\"password\" name=\"newpass1\" id=\"newpass1\" value=\"\">
                <span id=\"newpass1Warning\" class=\"requiredfield\"></span></p>
            <p class=\"header\">Confirm New Password</p>
                <p class=\"text-left\"><input type=\"password\" name=\"newpass2\" id=\"newpass2\" value=\"\">
                <span id=\"newpass2Warning\" class=\"requiredfield\"></span></p>
            <p><button class=\"button\" id=\"submit\">Update Password</button></p>
        </form>
        </div>
    </div>
</div>";
} else {
    Echo "<p style=\"text-align: center;\">Sorry, this code is no longer valid</p>";
}

?>