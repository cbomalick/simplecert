<?php

/** Validate inputs */

if (!isset($id) || empty($id)) {
	Echo'Error: ID not provided';
	exit;
}

//Check if file was submitted
if(is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
	$idNumber = new IdNumber();
	$attachmentId = $idNumber->generateID("IMG");

	$image = new Image($attachmentId);
	$target_dir = Image::UPLOAD_DIRECTORY;

	$image->customerId = $image->getLinkId($id); //Customer ID is used in directory structure
	$image->linkId = $id;
	$image->attachmentId = $attachmentId;
	$image->createdBy = $session->loggedInUser->employeeId;
	$image->createdDate = $CurrentDateTime;
	
	if (!isset($image->customerId) || empty($image->customerId)) {
		Echo'Invalid record';
		exit;
	}

	$image->fileToUpload = $_FILES['fileToUpload'];
	
	if($image->validImage($image->fileToUpload)){
		$image->uploadImage();
	}
} else {
	die("File not provided");
}


//Decide which page to return user back to. If record ID does not match a valid type, kick back to dashboard
$recordType = explode("-",$id);
if($recordType[0] == "SVC"){
    $backURL = "/service/view/{$id}";
} else if($recordType[0] == "EVT"){
    $backURL = "/event/view/{$id}";
} else {
    $backURL = "/";
}

Echo"Image saved. Click <a href=\"{$backURL}\" class=\"clickable\">here</a> if you are not automatically redirected.";
Echo"
<script type=\"text/javascript\">
window.location.href = '{$backURL}';
</script>";

?>