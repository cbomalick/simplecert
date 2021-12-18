<?php

/** Validate inputs */

if (!isset($id) || empty($id)) {
	Echo'Error: ID not provided';
	exit;
}

if (!isset($_POST['noteText']) || empty($_POST['noteText'])) {
	Echo'Error: Note not provided';
	exit;
} else {
    $noteText = $_POST['noteText'];
}

$recordPieces = explode("-", $id); 
if($recordPieces[0] == "NTE"){
	$note = new Note($id);
	$new = FALSE;
} else {
	$note = new Note();
	$note->linkId = $id;
	$note->noteDate = $CurrentDateTime;
	$note->createdBy = $session->loggedInUser->employeeId ?? "Unknown";
	$note->createdDate = $CurrentDateTime;
	$new = TRUE;
}

$note->noteText = $noteText;
$note->modifiedBy = $session->loggedInUser->employeeId;
$note->modifiedDate = $CurrentDateTime;

if($new == TRUE){
	$note->addNew();
} else {
	$note->saveChanges();
}

//Determine where to send the user after note is added
$recordType = explode("-",$id);
if($recordType[0] == "SVC"){
    $backURL = "/service/view/{$id}";
} else if($recordType[0] == "EVT"){
    $backURL = "/event/view/{$id}";
} else if($recordType[0] == "CUS"){
    $backURL = "customer/notes/{$id}";
} else {
    $backURL = "/";
}

Echo"
	Changes saved. Click <a href=\"{$backURL}\" class=\"clickable\">here</a> if you are not automatically redirected.";
	
	// Echo"<script type=\"text/javascript\">
	// window.location.href = '{$backURL}';
	// </script>";


?>