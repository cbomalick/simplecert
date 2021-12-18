<?php

require_once('customer.header.inc.php');

Echo"
<div class=\"boxwrapper\">
    <div class=\"customertabs full\" style=\"padding-top: 0px;\">
        <ul>
            <li><a href=\"customer/view/{$id}\">Overview</a></li>
            <li class=\"customertabcurrent\"><a href=\"customer/locations/{$id}\">Locations</a></li>
            <li><a href=\"customer/contacts/{$id}\">Contacts</a></li>
            <li><a href=\"customer/notes/{$id}\">Notes</a></li>
            <li><a href=\"customer/notes/{$id}\">Ledger</a></li>
            <li><a href=\"customer/notes/{$id}\">Recurring Fees</a></li>
            </ul>
    </div>
</div>";

Echo"<div class=\"boxwrapper\">";

$locationList = new LocationList($customer->customerId);
$lov = LOV::getInstance()->getLOV();

foreach($locationList->locationList as $location){
    Echo"<div class=\"box smallbox\">
    <h2>{$location->locationName}</h2>";

    Echo"<div class=\"boxcontent\">
    <p class=\"header\">Service Type</p>
    <p class=\"text-left\">".$lov->fetchLOVLabel("LocationType", $location->locationRateType)."</p>

    <p class=\"header\">Category</p>
    <p class=\"text-left\">".$lov->fetchLOVLabel("LocationCategory", $location->locationCategory)."</p>

    <p class=\"header\">Capacity</p>
    <p class=\"text-left\">{$location->capacity} {$location->capacityLabelName}</p>

    <p class=\"header\">Address</p>
    <p class=\"text-left\">{$location->fullAddress}</p>
    </div>";

    if(!$session->loggedInUser->validatePermissions("LocationEdit")){
        $editDisabled = "disabled title=\"Insufficient permissions\"";
    } else {
        $editDisabled = "";
    }

    if(!$session->loggedInUser->validatePermissions("LocationDelete")){
        $deleteDisabled = "disabled title=\"Insufficient permissions\"";
    } else {
        $deleteDisabled = "";
    }
    
    Echo"<h2></h2>
        <p><button class=\"button\" onclick=\"window.location.href = 'location/edit/{$location->locationId}';\" {$editDisabled}>Edit Location</button></p>
        <p><button class=\"button red\" onclick=\"window.location.href = 'location/delete/{$location->locationId}';\" {$deleteDisabled}>Delete Location</button></p>";

    Echo"</div>";
}

Echo"</div>";

?>