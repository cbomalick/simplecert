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

$site = new Site();
$timeHandler = new TimeHandler($session->loggedInUser->preferences["timeZone"] ?? $site->timeZone);
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
                
                /*Customer Tab*/
                case"customer":
                    require_once('layout/customer/index.inc.php');
                break;

                case"event":
                    require_once('layout/event/index.inc.php');
                break;

                case"service":
                    require_once('layout/service/index.inc.php');
                break;

                case"contact":
                    require_once('layout/contact/index.inc.php');
                break;

                case"location":
                    require_once('layout/location/index.inc.php');
                break;

                /*Billing Tab*/

                /* Employee Tab */
                case"employee":
                    require_once('layout/employee/index.inc.php');
                break;

                case"notification":
                    require_once('layout/notification/index.inc.php');
                break;

                case"route":
                    require_once('layout/route/index.inc.php');
                break;

                /*Accounting Tab*/

                /*Reports Tab*/

                /*System Functions*/
                case"image":
                    require_once('layout/image/index.inc.php');
                break;

                case"note":
                    require_once('layout/note/index.inc.php');
                break;

                case"metric":
                    require_once('layout/metric/index.inc.php');
                break;

                case"cancel":
                    require_once('layout/cancel/index.inc.php');
                break;

                case"user":
                    require_once('layout/user/index.inc.php');
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