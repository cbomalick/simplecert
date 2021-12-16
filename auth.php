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

// Echo"<pre>";
// var_dump($_SESSION);
// Echo"</pre>";

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
$sql = "SELECT users.username,users.userid,user_auth.password FROM users
JOIN user_auth ON users.username = user_auth.username WHERE users.username = ? AND status != 'Disabled'";
$stmt = $session->connect->prepare($sql);
$stmt->execute([$username]);
$row = $stmt->fetch();

//Check if account exists
if($stmt->rowCount() > 0){
	$password = $row['password'] ?? NULL;
	$userId = $row['userid'] ?? NULL;

	//If account exists, verify it is not locked
	if($session->loggedInUser->isLocked($username)){
		header("Location: /user/error/locked");
		exit();
	} else if(password_verify($_POST['password'],$password)){ //If Account is not locked, verify password
		//Upon success, set values and forward user to index
		$session->userId = $userId;
		$session->establishedTime = $CurrentDateTime;
		$_SESSION['establishedTime'] = $CurrentDateTime;
		$_SESSION['sessionId'] = session_id();
		$_SESSION['ipAddress'] = $_SERVER['REMOTE_ADDR'];
		$session->createSession();
		$audit = new AuditLog("Logged In", "Login", "Logged In");
		$session->loggedInUser->expireFailures($username);
		header("Location: /");
	 } else {
		//If password is incorrect log the attempt, add a strike, and forward to password error page
		$audit = new AuditLog("Error", "Login", "Incorrect Password Attempted ({$_POST['password']})");
		$session->loggedInUser->threeStrikes($username,"Incorrect Password Attempted");
		header("Location: /user/error/password");
	 }
	
} else {
	header("Location: /user/error/password");
	$audit = new AuditLog("Error", "Login", "Incorrect Username Attempted ({$username})");
}


?>