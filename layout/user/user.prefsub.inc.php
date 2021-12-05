<?php

    if (!isset($_POST['timeZone']) || empty($_POST['timeZone'])) {
        Echo'Error: Time Zone not provided';
        exit;
    } else {
        $timeZone = $_POST['timeZone'];
    }

    $session->loggedInUser->preferences['timeZone'] = $timeZone;
    $session->loggedInUser->updatePreferences();

    Echo "<h2>User Preferences</h2>";

    Echo "<p style=\"text-align: center;\">Preferences updated successfully</p>";
    Echo"<p style=\"text-align: center;\"><button type=\"button\" class=\"button\" onclick=\"window.location.href = 'dashboard';\">Continue</button></p>";

?>