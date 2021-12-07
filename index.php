<?php

//Display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

//All times are taken in UTC and displayed in user's timezone
date_default_timezone_set('UTC');
$CurrentDateTime = date("Y-m-d H:i:s");

//Import classes
require_once('classes/classes.inc.php');
require_once('layout/global/global.session.inc.php'); 

// Echo"<pre>";
// var_dump($_SESSION);
// Echo"</pre>";

//Check if page variables are submitted
if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = "";
}

if (isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
	$id = "";
}

if (isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = "";
}

$timeHandler = new TimeHandler($session->loggedInUser->preferences["timeZone"] ?? $this->session->site->defaultTimeZone);
$site = new Site();
$audit = new AuditLog("Page View", "Index", "Loaded Page");
?>

<!DOCTYPE html>
<html lang="en">
	<head>
        <title><?php Echo"{$site->siteName}"; ?></title>
        <base href="/" />
        <?php require_once('layout/global/global.scripts.inc.php'); ?>
	</head>
	<body>
        <?php require_once('layout/global/navigation.inc.php'); ?>
        <div class="panel">
            <div class="content">
                <?php
                switch($page){

                /*Dashboard Tab*/
                default:
                    require_once('layout/dashboard/index.inc.php');
                break;

                /*System*/
                case"user":
                    require_once('layout/user/index.inc.php');
                break;

                /*Customer*/
                case"customer":
                    require_once('layout/customer/index.inc.php');
                break;

                }
                ?>
            </div>
        </div>
        <?php 
        require_once('layout/global/footer.inc.php');
        ?>
	</body>
</html>