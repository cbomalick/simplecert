<?php
    $user = new User();
    $emailAddress = $_POST['emailAddress'];
    if(empty($loggedInName)){
        $loggedInName = "Not logged in";
    }

    //Send reset email
    $user->forgotPassword($emailAddress);

    Echo "<h2>Email Sent</h2>";
    Echo "
    <div class=\"boxwrapper\">
        <div class=\"box mediumbox\">
            <div class=\"boxcontent\">
                <p class=\"text-left\">If there is an account registered to <b>{$emailAddress}</b> we have sent instructions for how to reset your password.</p>
                <p><button type=\"button\" class=\"button\" onclick=\"window.location.href = 'login.html';\">Go Back</button></p>
            </div>
        </div>
    </div>";

?>