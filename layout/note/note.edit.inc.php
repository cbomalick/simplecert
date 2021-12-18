<?php

$note = new Note($id);
$lov = LOV::getInstance()->getLOV();
//Generate javascript warnings for required fields
$requiredFields = [
    "noteText" => "Enter Note"
];
$lov->requiredFields($requiredFields);

// Echo"<pre>";
// var_dump($note);
// Echo"</pre>";

$prefix = explode("-", $note->linkId);

if($prefix[0] == "CUS"){
    $customer = new Customer($note->linkId);

    Echo"<div class=\"boxwrapper\">
        <div class=\"box smallbox\">
            <div class=\"boxheader\">
                <h2>Customer Details</h2>
            </div>
            <div class=\"boxcontent\">
            <p class=\"header\">Customer Name</p>
            <p class=\"text-left\">{$customer->customerName}</p>
            <p class=\"header\">Primary Contact</p>
            <p class=\"text-left\">{$customer->primaryContactName}</p>
            <p class=\"header\">Category</p>
            <p class=\"text-left\">{$customer->categoryName}</p>
            <p class=\"header\">Customer Since</p>
            <p class=\"text-left\">".date("m/d/Y", strtotime($customer->startDate))."</p>
        </div>
        </div>
        <div class=\"box largebox\">
            <div class=\"boxheader\">
                <h2>Edit Note</h2>
            </div>
            <div class=\"boxcontent\">
            <form action=\"note/save/{$id}\" method=\"post\" enctype=\"multipart/form-data\">
            <textarea name=\"noteText\" id=\"noteText\" required>{$note->noteText}</textarea>
                <span id=\"noteTextWarning\" class=\"requiredfield\"></span>
                <p><button id=\"submit\" class=\"button\">Save</button> <button type=\"button\" class=\"button\" onclick=\"window.location.href = '/customer/notes/{$note->linkId}';\">Go Back</button></p>
            </form>
            </div>
        </div>
    </div>";
} else {
    $record = new Record($note->linkId);
    $recordType = strtolower($record->recordType);

    Echo"<div class=\"boxwrapper\">
        <div class=\"box smallbox\">
            <div class=\"boxheader\">
                <h2>{$record->recordType} Details</h2>
            </div>
            <div class=\"boxcontent\">
                <p class=\"header\">Customer</p>
                <p class=\"text-left\">{$record->customerName}</p>
                <p class=\"header\">Location</p>
                <p class=\"text-left\">{$record->locationName}</p>
                <p class=\"header\">Date</p>
                <p class=\"text-left\">{$record->recordDateTime}</p>
                <p class=\"header\">Added By</p>
                <p class=\"text-left\">{$record->employeeName}</p>
                <p class=\"header\"></p>
                <p class=\"text-left\"></p>
            </div>
        </div>
        <div class=\"box largebox\">
            <div class=\"boxheader\">
                <h2>Edit Note</h2>
            </div>
            <div class=\"boxcontent\">
            <form action=\"note/save/{$id}\" method=\"post\" enctype=\"multipart/form-data\">
                <textarea name=\"noteText\">{$note->noteText}</textarea>
                <p><button class=\"button\">Save</button> <button type=\"button\" class=\"button\" onclick=\"window.location.href = '/{$recordType}/view/{$record->linkId}';\">Go Back</button></p>
            </form>
            </div>
        </div>
    </div>";
}

?>