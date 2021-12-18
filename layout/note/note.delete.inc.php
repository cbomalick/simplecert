<?php

/** Validate inputs */

if (!isset($id) || empty($id)) {
	Echo'Error: ID not provided';
	exit;
}

$note = new Note($id);
$note->status = "Deleted";
$note->modifiedBy = $loggedInUser->personId;
$note->modifiedDate = $CurrentDateTime;
$note->saveChanges();
$noteType = strtolower($note->noteType);

Echo"
Changes saved.

<script type=\"text/javascript\">
window.location.href = '{$noteType}/view/{$note->recordId}';
</script>";

?>