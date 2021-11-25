<?php

//Initialize PHP session and session helper class
session_start();
$session = Session::getInstance()->getSession();

//Exempt pages from login requirement
$exempted = ["reset", "verify", "resetsub", "verifysub", "wiki"];

if(!in_array(htmlspecialchars($_GET['action'] ?? ""), $exempted)){
    //Check if session cookie is set
    if (isset($session->sessionId)) {
        //Validate user's session. If invalid, flush and redirect to login page
        if($session->validateSession() != TRUE){  
            session_destroy();
            setcookie('PHPSESSID', '', time() - 3600, '/');
            header('Location: /projects/simplecert//login.html');
        }
    } else {
        session_destroy();
        setcookie('PHPSESSID', '', time() - 3600, '/');
        header('Location: /projects/simplecert/login.html');
        exit;
    }
}

$loggedInUser = $session->loggedInUser;
$timeHandler = new TimeHandler($loggedInUser->preferences['timeZone'] ?? 'America/Chicago');
$_SESSION['timeZone'] = $loggedInUser->preferences['timeZone'] ?? 'America/Chicago';

?>