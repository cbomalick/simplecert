<?php

require_once('customer.header.inc.php');

Echo"
<div class=\"boxwrapper\">
    <div class=\"customertabs full\" style=\"padding-top: 0px;\">
        <ul>
            <li><a href=\"customer/view/{$id}\">Overview</a></li>
            <li><a href=\"customer/contacts/{$id}\">Contacts</a></li>
            <li><a href=\"customer/locations/{$id}\">Locations</a></li>
            <li class=\"customertabcurrent\"><a href=\"customer/notes/{$id}\">Notes</a></li>
            </ul>
    </div>
</div>";

$noteList = new NoteList($customer->customerId);

if(!empty($noteList->noteList)){
    $noteList->printNoteBoxes();
}

// Echo "<pre>";
// var_dump($noteList);
// Echo "</pre>";
?>