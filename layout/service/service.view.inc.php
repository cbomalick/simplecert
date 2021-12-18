<?php

$service = new Service($id); //Loads basic details and associated ID numbers
$service->getFullDetails(); //Loads full address, images, notes, and materials

// Echo"<pre>";
// var_dump($service->events);
// Echo"</pre>";

$lov = LOV::getInstance()->getLOV();

if ((!empty($service->imageList->imageList)) && count($service->imageList->imageList) >= Attachment::MAX_ATTACHMENTS){
    $disableImageButton = "disabled";
} else {
    $disableImageButton = "";
}

//Header
Echo"<h2>View Service</h2>";

//Buttons
if(!$session->loggedInUser->validatePermissions("ServiceEdit")){
    $editDisabled = "disabled title=\"Insufficient permissions\"";
} else {
    $editDisabled = "";
}

if(!$session->loggedInUser->validatePermissions("MetricAdd")){
    $metricDisabled = "disabled title=\"Insufficient permissions\"";
} else {
    $metricDisabled = "";
}

if(!$session->loggedInUser->validatePermissions("ImageAdd")){
    $imageDisabled = "disabled title=\"Insufficient permissions\"";
} else {
    $imageDisabled = "";
}

if(!$session->loggedInUser->validatePermissions("NoteAdd")){
    $noteDisabled = "disabled title=\"Insufficient permissions\"";
} else {
    $noteDisabled = "";
}

if(!$session->loggedInUser->validatePermissions("ServiceDelete")){
    $deleteDisabled = "disabled title=\"Insufficient permissions\"";
} else {
    $deleteDisabled = "";
}

Echo"<div class=\"utilities\">
        <div class=\"listbuttons\">
            <button class=\"button\" onclick=\"window.location.href = '/service/edit/{$service->serviceId}';\" {$editDisabled}>Edit Service</button>
            <button class=\"button\" onclick=\"window.location.href = 'metric/add/{$service->serviceId}'\" {$metricDisabled}>Add Metrics</button>
            <button {$disableImageButton} class=\"button\" onclick=\"window.location.href = 'image/add/{$service->serviceId}'\" {$imageDisabled}>Add Image</button>
            <button class=\"button\" onclick=\"window.location.href = 'note/add/{$service->serviceId}'\" {$noteDisabled}>Add Note</button>
            <button class=\"button red\" onclick=\"window.location.href = 'service/cancel/{$service->serviceId}'\" {$deleteDisabled}>Cancel Service</button>
        </div>
        <div class=\"tools\">
            <a href=\"download/pdf/{$service->serviceId}\"><img src=\"images/print.png\" /></a>
        </div>
    </div>";

//Customer Box
    Echo"<div class=\"boxwrapper metricbox\">
    <div class=\"box metricbox\">
        <div class=\"boxcontent \">
            <div class=\"metric nametile\">
                <h4><a href=\"customer/view/$service->customerId\" class=\"clickable\">{$service->customerName}</a></h4>
                <p>".date("m/d/Y g:i a", strtotime($service->serviceDateTime))."</p>
                <p>Created by {$service->employeeName} </p>
            </div>
            <div class=\"metric\">
                <h4>{$service->locationName}</h4>
                {$service->location->formattedAddress}
            </div>
            <div class=\"metric\">
                <h4>".$lov->fetchLOVLabel("LocationRateType", $service->location->locationRateType)."</h4>
                <p>Rate Type</p>
            </div>
            <div class=\"metric\">
                <h4>".$lov->fetchLOVLabel("ServiceName", $service->serviceCode)."</h4>
                <p>Service Provided</p>
            </div>";
            
            if(count($service->metricList->uniqueMetricDates) > 1){
                Echo"<div class=\"metric\">
                <h4>{$service->metricList->intervalText}</h4>
                <p>Time Spent</p>
            </div>";
            }

        Echo"</div>
    </div>
</div>";

//Content
Echo"<div class=\"boxwrapper\">";

$service->imageList->printImages();

Echo"<div class=\"box full\">
    <h2>Metrics</h2>
    <div class=\"boxcontent\">";
    
        $service->metricList->printMetricBoxes();

        //var_dump($service->notes);
        
        if(!empty($service->notes->noteList)){
            Echo"<h2>Notes</h2>";
            $service->notes->printNoteBoxes();
        }
        
    Echo"</div>
</div>
</div>";

    Echo"<div class=\"boxwrapper\">
    <div class=\"box half\">
        <div class=\"boxheader\">
            <h2>Linked Events</h2>
        </div>
        <div class=\"table\">";

        if(!empty($service->events)){
            $service->printLinkedEvents();
        } else {
            Echo "<p style=\"font-style: italic; text-align: center;\">No events</p>";
        }
        
        Echo"</div>
        
    </div>
<div class=\"box half\">
    <div class=\"boxheader\">
        <h2>Materials Used</h2>
    </div>
    <div class=\"table\">";

    if(!empty($service->materials->materialList)){
        $service->printMaterialList();
    } else {
        Echo "<p style=\"font-style: italic; text-align: center;\">No materials</p>";
    }
    

    Echo"</div>
</div>
</div>
<button class=\"button\" onclick=\"window.location.href = '/service';\">Go Back</button>";

    
// Echo "<pre>";
// var_dump(get_defined_vars());
// Echo "</pre>";

?>