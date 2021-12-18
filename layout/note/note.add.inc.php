<?php
$lov = LOV::getInstance()->getLOV();
//Generate javascript warnings for required fields
$requiredFields = [
    "noteText" => "Enter Note"
];
$lov->requiredFields($requiredFields);

//Decide which page to return user back to. If record ID does not match a valid type, kick back to dashboard
$recordType = explode("-",$id);
if($recordType[0] == "SVC"){
    $backURL = "/service/view/{$id}";
} else if($recordType[0] == "EVT"){
    $backURL = "/event/view/{$id}";
} else {
    $backURL = "/";
}

    Echo"<h2>Add Note</h2>";

    Echo"
    <div class=\"boxwrapper\">
        <div class=\"box mediumbox\">
            <div class=\"boxcontent\">
            <p class=\"header\">Please enter note below</p>
            <form action=\"note/save/{$id}\" method=\"post\" enctype=\"multipart/form-data\">
                <textarea name=\"noteText\"></textarea>
            </div>
        </div>
    </div>
    <p><button class=\"button\">Save</button> <button type=\"button\" class=\"button\" onclick=\"window.location.href = '{$backURL}';\">Go Back</button></p>
    </form>";

?>