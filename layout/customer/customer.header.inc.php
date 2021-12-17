<?php

$customer = new Customer($id);

// Echo "<pre>";
// var_dump($customer);
// Echo "</pre>";

//Header
Echo"<h2>View Customer</h2>";

//Buttons
Echo"<div class=\"utilities\">
        <div class=\"listbuttons\">";

            if(!$session->loggedInUser->validatePermissions("CustomerEdit")){
                $editDisabled = "disabled title=\"Insufficient permissions\"";
            } else {
                $editDisabled = "";
            }

            if(!$session->loggedInUser->validatePermissions("ContactAdd")){
                $contactDisabled = "disabled title=\"Insufficient permissions\"";
            } else {
                $contactDisabled = "";
            }

            if(!$session->loggedInUser->validatePermissions("NoteAdd")){
                $noteDisabled = "disabled title=\"Insufficient permissions\"";
            } else {
                $noteDisabled = "";
            }

            if(!$session->loggedInUser->validatePermissions("CustomerDelete")){
                $deleteDisabled = "disabled title=\"Insufficient permissions\"";
            } else {
                $deleteDisabled = "";
            }
            
            Echo"<button class=\"button\" onclick=\"window.location.href = '/customer/edit/{$customer->customerId}';\" {$editDisabled}>Edit Customer</button>
            <button class=\"button\" onclick=\"window.location.href = '/contact/add/{$customer->customerId}';\" {$contactDisabled}>Add Contact</button>
            <button class=\"button\" onclick=\"window.location.href = '/note/add/{$customer->customerId}';\" {$noteDisabled}>Add Note</button>
            <button class=\"button red\" onclick=\"window.location.href = 'customer/cancel/{$customer->customerId}'\" {$deleteDisabled}>Inactivate</button> ";
    Echo"</div>
        <div class=\"tools\">
            <!--<a href=\"download/pdf/{$customer->customerId}\"><img src=\"images/print.png\" /></a>-->
        </div>
    </div>";

//Customer Box
Echo"<div class=\"boxwrapper metricbox\">
        <div class=\"box metricbox\">
            <div class=\"boxcontent \">
                <div class=\"metric nametile\">
                    <h4>{$customer->accountName}</h4>
                    <p>Account Name</p>
                </div>
                <div class=\"metric\">
                    <h4>{$customer->primaryContactName}</h4>
                    <p>Primary Contact</p>
                </div>";

                if(!empty($customer->primaryPhone)){
                Echo"<div class=\"metric\">
                    <h4>{$customer->primaryPhone}</h4>
                    <p>Primary Phone</p>
                </div>";
                }
                
                if(!empty($customer->primaryEmail)){
                    Echo"<div class=\"metric\">
                    <h4><a href=\"mailto:{$customer->primaryEmail}\" class=\"clickable\">{$customer->primaryEmail}</a></h4>
                    <p>Primary Email</p>
                    </div>";
                }

            Echo"</div>
        </div>
    </div>";

?>