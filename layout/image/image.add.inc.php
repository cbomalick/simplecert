<?php

//Decide which page to return user back to. If record ID does not match a valid type, kick back to dashboard
$recordType = explode("-",$id);
if($recordType[0] == "SVC"){
    $backURL = "/service/view/{$id}";
} else if($recordType[0] == "EVT"){
    $backURL = "/event/view/{$id}";
} else {
    $backURL = "/";
}

$record = new Service($id);
$lov = LOV::getInstance()->getLOV();

//Generate javascript warnings for required fields
$requiredFields = [
    "fileToUpload" => "Choose Image"
];
$lov->requiredFields($requiredFields);

Echo"<h2>Add Image</h2>";

Echo"
<div class=\"boxwrapper\">
    <div class=\"box mediumbox\">
        <div class=\"boxcontent\">
        <form action=\"image/save/{$id}\" method=\"post\" enctype=\"multipart/form-data\">
        <p class=\"header\">Please select an image to attach</p><br />
        <p><input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\" accept=\"image/jpeg\"></p>
        <p class=\"text-left\"><span id=\"fileToUploadWarning\" class=\"requiredfield\"></span></p>
        </div>
    </div>
</div>
<p><button id=\"submit\" class=\"button\">Save</button> <button type=\"button\" class=\"button\" onclick=\"window.location.href = '{$backURL}';\">Go Back</button></p>
</form>";

?>