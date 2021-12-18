<?php

/** Populate search with last searched details */
/** If $_POST is not set, check $_SESSION. If session is not set, use default value */

$timeHandler = new TimeHandler($session->loggedInUser->preferences["timeZone"]);

//Date From
if (!isset($_POST['dateFrom']) || empty($_POST['dateFrom'])) {
    if(isset($_SESSION['lastSearchedFromDate'])){
        $dateFrom = $_SESSION['lastSearchedFromDate'];
    } else {
        $date = new DateTime($timeHandler->displayUserDateTime($CurrentDateTime));
        $dateFrom = $date->format('Y-m-d 00:00:00');
    }
} else {
    $date = new DateTime($_POST['dateFrom']);
    $dateFrom = $date->format('Y-m-d 00:00:00');
    $_SESSION['lastSearchedFromDate'] = $dateFrom;
}

//Date To
if (!isset($_POST['dateTo']) || empty($_POST['dateTo'])) {
    if(isset($_SESSION['lastSearchedToDate'])){
        $dateTo = $_SESSION['lastSearchedToDate'];
    } else {
        $date = new DateTime($timeHandler->displayUserDateTime($CurrentDateTime));
        $dateTo = $date->format('Y-m-d 23:59:59');
    }
} else {
    $date = new DateTime($_POST['dateTo']);
    $dateTo = $date->format('Y-m-d 23:59:59');
    $_SESSION['lastSearchedToDate'] = $dateTo;
}

//Customr Name
if (!isset($_POST['customerName'])) {
    if(isset($_SESSION['serviceLastSearchedCustomerName'])){
        $customerName = $_SESSION['serviceLastSearchedCustomerName'];
    } else {
        $customerName = "";
    }
} else {
    $customerName = $_POST['customerName'];
    $_SESSION['serviceLastSearchedCustomerName'] = $customerName;
}

//Company
if (!isset($_POST['companyId']) || empty($_POST['companyId'])) {
    if(isset($_SESSION['lastSearchedCompany'])){
        $lastSearchedCompany = $_SESSION['lastSearchedCompany'];
    } else {
        $lastSearchedCompany = $session->loggedInUser->primaryCompany;
    }
} else {
    $lastSearchedCompany = $_POST['companyId'];
    $_SESSION['lastSearchedCompany'] = $lastSearchedCompany;
}

//Status
if (!isset($_POST['status']) || empty($_POST['status'])) {
    if(isset($_SESSION['serviceLastSearchedStatus'])){
        $status = $_SESSION['serviceLastSearchedStatus'];
    } else {
        $status = array("Active");
    }
} else {
    $status = $_POST['status'];
    $_SESSION['serviceLastSearchedStatus'] = $status;
}

$lov = LOV::getInstance()->getLOV();

//Header
Echo"<h2>Services</h2>";

//Print Search
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

    $lov->lovDropdown("ServiceStatus", $status, "status[]", "status", "MULTI");

    Echo"|	
<b>Company: </b>";

$lov->companyDropdown($session->loggedInUser->allowedCompanies, $lastSearchedCompany);

Echo"</p>
</form>
</div>
</div>";

//Generate Buttons
if(!$session->loggedInUser->validatePermissions("ServiceAdd")){
    $addDisabled = "disabled title=\"Insufficient permissions\"";
} else {
    $addDisabled = "";
}

Echo"
<div class=\"utilities\">
    <div class=\"listbuttons\">
        <button class=\"button\" onclick=\"window.location.href = '/service/add'\" {$addDisabled}>Add Service</button>
    </div>
    <div class=\"tools\">
        <!--<a href=\"\"><img src=\"images/excel.png\" /></a>
        <a href=\"\"><img src=\"images/print.png\" /></a>-->
    </div>
</div>";

//Generate Table or Content
$serviceList = new ServiceList();
$objArray = $serviceList->newServiceList($status, $timeHandler->convertToUTC($dateFrom), $timeHandler->convertToUTC($dateTo), $lastSearchedCompany, $customerName);
$serviceList->printServiceList($objArray);

// Echo "<pre>";
// var_dump(get_defined_vars());
// Echo "</pre>";

?>