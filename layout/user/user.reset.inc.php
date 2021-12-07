<?php

if(empty($loggedInName)){
    $loggedInName = "Not logged in";
}

//Generate javascript warnings for required fields
$lov = LOV::getInstance()->getLOV();
$requiredFields = [
    "emailAddress" => "Enter Email Address"
];
$lov->requiredFields($requiredFields);

Echo "<h2>Forgot Password</h2>
    <div class=\"boxwrapper\">
        <div class=\"box mediumbox\">
        <div class=\"boxcontent\">
            <p class=\"text-left\">
                If you've forgotten your password you can enter your e-mail and we'll send you instructions on how to reset it.
            </p>
        <form method=\"post\" action=\"user\\resetsub\">
            <p class=\"header\">Email Address</p>
                <p class=\"text-left\"><input name=\"emailAddress\" id=\"emailAddress\" value=\"\">
                <span id=\"emailAddressWarning\" class=\"requiredfield\"></span></p>
            <p><button class=\"button\" id=\"submit\">Reset Password</button>
            <button type=\"button\" class=\"button\" onclick=\"window.location.href = 'login.html';\">Go Back</button></p>
        </form>
        </div>
    </div>
</div>";

?>