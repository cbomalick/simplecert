<?php
session_start([
    //'cookie_lifetime' => 30, //lifetime of the cookie in seconds which is sent to the browser. Default is until browser is closed
    //'read_and_close'  => true, //will result in the session being closed immediately after being read, avoiding locking if the session data won't be changed
    'cookie_httponly'  => 1, //cookie accessible only through the HTTP protocol. cookie won't be accessible by scripting languages such as JavaScript
    'cookie_secure'  => 1, //cookies should only be sent over secure connections
]);

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Import classes
require_once('classes/classes.inc.php');

date_default_timezone_set('UTC');
$CurrentDateTime = date("Y-m-d H:i:s");

/** Authentication workflow */
//User accesses index.php. If session is not set, header redirect to index.html
//Upon successful log in, PHPSESSID is generated
//PHPSESSID is saved to cookie, and in DB along with the userid, time, and IP
//Each time user loads page, compare the cookie string to the session table
//If they don't match, terminate session

//Verify username and password were submitted
if (!isset($_POST['username'], $_POST['password'])){
	die ('Invalid username or password');
} else {
    $username = $_POST['username'];
}

//Match submitted details to database
	$session = Session::getInstance()->getSession();
	$sql = "SELECT username,password,userid FROM accounts WHERE username = ? AND status = 'Active'";
	$stmt = $session->connect->prepare($sql);
	$stmt->execute([$username]);
	
	if (count($stmt) > 0){
		$row = $stmt->fetch();
		$username = $row['username'];
		$password = $row['password'];
		$userId = $row['userid'];

		//If Account exists, verify password
		if (password_verify($_POST['password'],$password)){
			//Upon success, set values and forward user to index
            $loggedInUser = new User($userId);
            $_SESSION['timeZone'] = $loggedInUser->preferences['timeZone'];
            $_SESSION['userId'] = $loggedInUser->userId;

            $session->userId = $userId;
            $session->establishedTime = $CurrentDateTime;
            $session->createSession();

            header("Location: /");
			//AuditLog('Logged In','Desktop Login',$employeeid);
			//$staff->unlockStaff($staffId);
			exit();
		} else {
			Echo "<p style=\"text-align: center;\">Incorrect username and/or password</p>";
			//$Username = FilterInput($_POST['email']);
			//AuditLog('Failed Login','Incorrect Password Attempted',$Username);
			//$staff->threeStrikes($email,"Incorrect Password Attempted");
		}
	} else {
		Echo "<p style=\"text-align: center;\">Incorrect username and/or password</p>";
			//$Username = FilterInput($_POST['email']);
			//AuditLog('Failed Login','Incorrect Username Attempted',$Username);
			//$staff->threeStrikes($email,"Incorrect Username Attempted");
	}

?>