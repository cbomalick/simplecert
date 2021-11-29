<?php

//$dashboard = new Dashboard();

Echo"<h2>Dashboard</h2>";

Echo"
<div class=\"boxwrapper\">";

        // if($loggedInUser->validatePermissions("ServiceView")){
            Echo"<div class=\"box mediumbox\">
            <div class=\"boxheader\">
                <h2>Site Details</h2>
            </div>
                <div class=\"boxcontent\">";
                
                $site = new Site();
                Echo"<pre>";
                var_dump($site);
                Echo"</pre>";

                Echo"</div>
            </div>";

            Echo"<div class=\"box mediumbox\">
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

        // }

            Echo"</p>
            </div>
            </div>";
            
            //$audit = new AuditLog("Update", "Test", "Test");

Echo"</div>";

?>