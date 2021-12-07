<?php

$dashboard = new Dashboard();

Echo"<h2>Dashboard</h2>"; //Must be a separate echo from the one below otherwise header will be below the alert box. Side effect of engine's nonlinear processing
Echo"<div class=\"alerts\">
{$dashboard->displayActiveAlerts()}
</div>";

Echo"
<div class=\"boxwrapper\">";

// if($loggedInUser->validatePermissions("ServiceView")){
    Echo"<div class=\"box largebox\">
    <div class=\"boxheader\">
        <h2>Site Details</h2>
    </div>
        <div class=\"boxcontent\">";
        
        Echo"<pre>";
        var_dump($site);
        Echo"</pre>";

        Echo"</div>
    </div>";

    Echo"<div class=\"box largebox\">
    <div class=\"boxheader\">
        <h2>Current User</h2>
    </div>
    <div class=\"boxcontent\">";

    Echo"<pre>";
    var_dump($session->loggedInUser);
    Echo"</pre>";

    //$dashboard->printRecentRecords("Service", $loggedInUser->allowedCompanies);
        Echo"</div>
    </div>";

    Echo"<div class=\"box largebox\">
    <div class=\"boxheader\">
        <h2>Session Details</h2>
    </div>
        <div class=\"boxcontent\">";
        
        Echo"<pre>";
        var_dump($_SESSION);
        Echo"</pre>";

        Echo"</div>
    </div>";

// }

    //$audit = new AuditLog("Update", "Test", "Test");

Echo"</div>";

?>