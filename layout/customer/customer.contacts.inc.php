<?php

require_once('customer.header.inc.php');

Echo"
<div class=\"boxwrapper\">
    <div class=\"customertabs full\" style=\"padding-top: 0px;\">
        <ul>
            <li><a href=\"customer/view/{$id}\">Overview</a></li>
            <li class=\"customertabcurrent\"><a href=\"customer/contacts/{$id}\">Contacts</a></li>
            <li><a href=\"customer/notes/{$id}\">Notes</a></li>
            <li><a href=\"customer/notes/{$id}\">Ledger</a></li>
            <li><a href=\"customer/notes/{$id}\">Recurring Fees</a></li>
            </ul>
    </div>
</div>";

Echo"<div class=\"boxwrapper\">";

$personList = new PersonList($customer->customerId);

foreach($personList->personList as $person){
    Echo"<div class=\"box smallbox\">
    <h2>{$person->fullName}</h2>
    
    <div class=\"boxcontent\">
    <p class=\"header\">Address</p>
    <p class=\"text-left\">{$person->address->getFullAddress()}</p>";
    
    //Display Phone
    Echo"<p class=\"header\">Phone Number</p>
    <p class=\"text-left\">{$person->phoneNumber}</p>";
    

    //Display Email
    if(empty($person->emailAddress)){
        $person->emailAddress = "None";
    }

    Echo"<p class=\"header\">Email Address</p>
    <p class=\"text-left\">{$person->emailAddress}</p>
    
    </div>";

    if(!$session->loggedInUser->validatePermissions("ContactEdit")){
        $editDisabled = "disabled title=\"Insufficient permissions\"";
    } else {
        $editDisabled = "";
    }

    if(!$session->loggedInUser->validatePermissions("ContactDelete")){
        $deleteDisabled = "disabled title=\"Insufficient permissions\"";
    } else {
        $deleteDisabled = "";
    }
    
    Echo"<h2></h2>
    <p><button class=\"button\" onclick=\"window.location.href = 'contact/edit/{$person->personId}';\" {$editDisabled}>Edit Contact</button></p>
    <p><button class=\"button red\" onclick=\"window.location.href = 'contact/delete/{$person->personId}';\" {$deleteDisabled}>Delete Contact</button></p>";

    Echo"</div>";
}

Echo"</div>";

?>