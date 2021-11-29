<?php

//$dashboard = new Dashboard();

Echo"<h2>Dashboard</h2>";

Echo"
<div class=\"boxwrapper\">";

        // if($loggedInUser->validatePermissions("ServiceView")){
            Echo"<div class=\"box half\">
            <div class=\"boxheader\">
                <h2>Recent Services</h2>
            </div>
            <div class=\"boxcontent table\">";

            //$dashboard->printRecentRecords("Service", $loggedInUser->allowedCompanies);
                Echo"</div>
            </div>";

        // }

        Echo"<div class=\"box mediumbox\">
            <div class=\"boxheader\">
                <h2>Recent Services</h2>
            </div>
            <div class=\"boxcontent table\">
                <p>";
                    
                // Echo"Time in UTC<br>";
                // Echo $CurrentDateTime . "<br><br>";

                // Echo"Time in {$session->loggedInUser->preferences["timeZone"]} <br>";
                // $timeHandler = new TimeHandler($session->loggedInUser->preferences["timeZone"]);
                // Echo $timeHandler->displayUserDateTime($CurrentDateTime);

                Echo"</p>
            </div>
            </div>";
            
            //$audit = new AuditLog("Update", "Test", "Test");

Echo"</div>";

?>