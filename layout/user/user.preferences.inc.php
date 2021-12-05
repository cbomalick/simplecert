<?php

//Generate javascript warnings for required fields
$lov = LOV::getInstance()->getLOV();
$requiredFields = [
    "timeZone" => "Select Time Zone"
];
$lov->requiredFields($requiredFields);

Echo "<h2>User Preferences</h2>
    <div class=\"boxwrapper\">
        <div class=\"box mediumbox\">
        <div class=\"boxcontent\">
            <p class=\"text-left\">
                Change your profile and account information
            </p>
        <form method=\"post\" action=\"user\\prefsub\">
            <p class=\"header\">Time Zone</p>
                <p class=\"text-left\">";
                $lov->generateTimeZoneDropdown($session->loggedInUser->preferences['timeZone']);
                Echo"<span id=\"timeZoneWarning\" class=\"requiredfield\"></span></p>
            <p><button class=\"button\" id=\"submit\">Save</button>
            <button type=\"button\" class=\"button\" onclick=\"window.location.href = '/';\">Go Back</button></p>
        </form>
        </div>
    </div>
</div>";

?>