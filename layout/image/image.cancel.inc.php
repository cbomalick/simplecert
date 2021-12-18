<?php

/** Validate inputs */

if (!isset($id) || empty($id)) {
	Echo'Error: ID not provided';
	exit;
}

$image = new Image($id);
$image->modifiedBy = $loggedInUser->personId;
$image->modifiedDate = $CurrentDateTime;
$image->cancelImage();

$recordId = explode("-", $image->recordId); 
if($recordId[0] == "SVC"){
	$recordType = "Service";
} else if ($recordId[0] == "EVT"){
	$recordType = "Event";
} else {
    $recordType = "";
}

$recordType = strtolower($recordType);
Echo"
Changes saved.

<script type=\"text/javascript\">
window.location.href = '{$recordType}/edit/{$image->recordId}';
</script>";

?>