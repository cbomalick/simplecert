<?php

/** Populate search with last searched details */
/** If $_POST is not set, check $_SESSION. If session is not set, use default value */

//Date From
if (!isset($_POST['dateFrom']) || empty($_POST['dateFrom'])) {
    if(isset($_SESSION['customerLastSearchedFromDate'])){
        $dateFrom = $_SESSION['customerLastSearchedFromDate'];
    } else {
        $dateFrom = "2010-01-01 00:00:00";
    }
} else {
    $dateFrom = $_POST['dateFrom'];
    $_SESSION['customerLastSearchedFromDate'] = $dateFrom;
}

//Date To
if (!isset($_POST['dateTo']) || empty($_POST['dateTo'])) {
    if(isset($_SESSION['customerLastSearchedToDate'])){
        $dateTo = $timeHandler->displayUserDateTime($_SESSION['customerLastSearchedToDate']);
    } else {
        $date = new DateTime($timeHandler->displayUserDateTime($CurrentDateTime));
        $dateTo = $date->format('Y-m-d 23:59:59');
    }
} else {
    $date = new DateTime($_POST['dateTo']);
    $dateTo = $date->format('Y-m-d 23:59:59');
    $_SESSION['customerLastSearchedToDate'] = $dateTo;
}

//Customer Name
if (!isset($_POST['customerName'])) {
    if(isset($_SESSION['customerLastSearchedCustomerName'])){
        $customerName = $_SESSION['customerLastSearchedCustomerName'];
    } else {
        $customerName = "";
    }
} else {
    $customerName = $_POST['customerName'];
    $_SESSION['customerLastSearchedCustomerName'] = $customerName;
}

// //Company
// if (!isset($_POST['companyId']) || empty($_POST['companyId'])) {
//     if(isset($_SESSION['lastSearchedCompany'])){
//         $lastSearchedCompany = $_SESSION['lastSearchedCompany'];
//     } else {
//         $lastSearchedCompany = $session->loggedInUser->primaryCompany;
//     }
// } else {
//     $lastSearchedCompany = $_POST['companyId'];
//     $_SESSION['lastSearchedCompany'] = $lastSearchedCompany;
// }

//Status
if (!isset($_POST['status']) || empty($_POST['status'])) {
    if(isset($_SESSION['lastSearchedStatus'])){
        $status = $_SESSION['lastSearchedStatus'];
    } else {
        $status = array("Active");
    }
} else {
    $status = $_POST['status'];
    $_SESSION['lastSearchedStatus'] = $status;
}

$lov = LOV::getInstance()->getLOV();

//Header
Echo"<h2>Customers</h2>";

//Generate Search
Echo"<div class=\"boxwrapper\">
<div class=\"search\">
<form action=\"\" method=\"post\">
<b>Date:</b>
<input name=\"dateFrom\" class=\"datepicker\" value=\"{$dateFrom}\"> to 
<input name=\"dateTo\" class=\"datepicker\" value=\"{$dateTo}\">
|
<b>Customer Name:</b> 
<input name=\"customerName\" class=\"\" value=\"{$customerName}\">
<button class=\"button\">Search</button>
<button type=\"button\" class=\"button expand-search\"> + </button>
<p class=\"adv-search\">
    <b>Status: </b>";

    $lov->lovDropdown("CustomerStatus", $status, "status[]", "status", "MULTI");

Echo"|	
<b>Company: </b>";

$lov->companyDropdown($session->loggedInUser->allowedCompanies, $lastSearchedCompany);

Echo"</p>
</form>
</div>
</div>";

//Generate Buttons
if(!$session->loggedInUser->validatePermissions("CustomerAdd")){
    $addDisabled = "disabled title=\"Insufficient permissions\"";
} else {
    $addDisabled = "";
}

Echo"
<div class=\"utilities\">
    <div class=\"listbuttons\">
        <button class=\"button\" onclick=\"window.location.href = '/customer/add'\"{$addDisabled}>Add Customer</button>
    </div>
    <div class=\"tools\">
        <!--<a href=\"\"><img src=\"images/excel.png\" /></a>
        <a href=\"\"><img src=\"images/print.png\" /></a>-->
    </div>
</div>
    ";

// //Generate Table or Content
// $customerList = new CustomerList();
// $objArray = $customerList->newCustomerList($status, $dateFrom, $dateTo, $lastSearchedCompany, $customerName);
// $customerList->printCustomerList($objArray);
 

?>