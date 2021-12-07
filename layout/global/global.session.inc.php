<?php

//Initialize PHP session and session helper class
session_start();
$session = Session::getInstance()->getSession();

//Exempt pages from login requirement
$exempted = ["reset", "verify", "resetsub", "verifysub", "error"];
if(!in_array(htmlspecialchars($_GET['action'] ?? ""), $exempted)){
    //Check if session cookie is set
    if (isset($session->sessionId)) {
        //Validate user's session. If invalid, flush and redirect to login page
        if($session->validateSession() != TRUE){  
            session_destroy();
            setcookie('PHPSESSID', '', time() - 3600, '/');
            header('Location: /login.html');
        }
    } else {
        session_destroy();
        setcookie('PHPSESSID', '', time() - 3600, '/');
        header('Location: /login.html');
        exit;
    }
}

//Set timezone from user preferences
$_SESSION['timeZone'] = $session->loggedInUser->preferences['timeZone'] ?? 'America/Chicago';

?>