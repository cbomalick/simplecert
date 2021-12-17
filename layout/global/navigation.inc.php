<div class="navigationbar" >
    <div class="logo">
        <!-- Display logo -->
    </div>
    <div class="user">
        <img src="images/photo.jpg" onclick="openNav()" alt="<?php Echo"{$site->siteName} Logo"; ?>"/>
    </div>
    <div class="navigation">
        <ul>
            <?php
            if(isset($session->loggedInUser->userId)){
                //Dashboard module
                //All users have access, used as initial landing page
                Echo"<li><a href=\"/\">Dashboard</a></li>";

                //Build Customers module
                $customerLinks = ''; //Begin with empty menu and append screens the user has permissions for
                $displayCustomerModule = FALSE; //Hide module by default
                if($session->loggedInUser->validatePermissions("ServiceView")){
                    $customerLinks .= "<li><a href=\"/service\">Services</a></li>";
                    $displayCustomerModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("EventView")){
                    $customerLinks .= "<li><a href=\"/event\">Events</a></li>";
                    $displayCustomerModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("CustomerView")){
                    $customerLinks .= "<li><a href=\"/customer\">Customers</a></li>";
                    $displayCustomerModule = TRUE;
                }

                //Print Customers module
                if($displayCustomerModule){
                    Echo"<li>Customer
                            <ul>
                                {$customerLinks}
                            </ul>
                        </li>";
                }

                //Build Billings module
                $billingLinks = ''; //Begin with empty menu and append screens the user has permissions for
                $displayBillingModule = FALSE; //Hide module by default
                if($session->loggedInUser->validatePermissions("BillingView")){
                    $billingLinks .= "<li><a href=\"/billing\">Aging Summary</a></li>";
                    $displayBillingModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("BillingView")){
                    $billingLinks .= "<li><a href=\"/billing\">Generate Bills</a></li>";
                    $displayBillingModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("BillingView")){
                    $billingLinks .= "<li><a href=\"/billing\">Deposit Slip</a></li>";
                    $displayBillingModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("BillingView")){
                    $billingLinks .= "<li><a href=\"/billing\">Charge Import</a></li>";
                    $displayBillingModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("BillingView")){
                    $billingLinks .= "<li><a href=\"/billing\">Charge Card</a></li>";
                    $displayBillingModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("BillingView")){
                    $billingLinks .= "<li><a href=\"/billing\">Cancel Bills</a></li>";
                    $displayBillingModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("BillingView")){
                    $billingLinks .= "<li><a href=\"/billing\">Credit/Debit</a></li>";
                    $displayBillingModule = TRUE;
                }

                //Print Billings module
                if($displayBillingModule){
                    Echo"<li>Billing
                            <ul>
                                {$billingLinks}
                            </ul>
                        </li>";
                }

                //Build Accounting module
                $accountingLinks = ''; //Begin with empty menu and append screens the user has permissions for
                $displayAccountingModule = FALSE; //Hide module by default
                if($session->loggedInUser->validatePermissions("AccountingView")){
                    $accountingLinks .= "<li><a href=\"/accounting\">Revenue Report</a></li>";
                    $displayAccountingModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("AccountingView")){
                    $accountingLinks .= "<li><a href=\"/accounting\">Bills for a Period</a></li>";
                    $displayAccountingModule = TRUE;
                }

                //Print Accountings module
                if($displayAccountingModule){
                    Echo"<li>Accounting
                            <ul>
                                {$accountingLinks}
                            </ul>
                        </li>";
                }

                //Build Employees module
                $employeeLinks = ''; //Begin with empty menu and append screens the user has permissions for
                $displayEmployeeModule = FALSE; //Hide module by default
                if($session->loggedInUser->validatePermissions("EmployeeView")){
                    $employeeLinks .= "<li><a href=\"/employee\">Employees</a></li>";
                    $displayEmployeeModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("EmployeeView")){
                    $employeeLinks .= "<li><a href=\"/employee\">Notifications</a></li>";
                    $displayEmployeeModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("EmployeeView")){
                    $employeeLinks .= "<li><a href=\"/employee\">Routes</a></li>";
                    $displayEmployeeModule = TRUE;
                }

                //Print Employees module
                if($displayEmployeeModule){
                    Echo"<li>Employee
                            <ul>
                                {$employeeLinks}
                            </ul>
                        </li>";
                }

                //Build Setup module
                $setupLinks = ''; //Begin with empty menu and append screens the user has permissions for
                $displaySetupModule = FALSE; //Hide module by default
                if($session->loggedInUser->validatePermissions("SetupView")){
                    $setupLinks .= "<li><a href=\"/setup\">LOV</a></li>";
                    $displaySetupModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("SetupView")){
                    $setupLinks .= "<li><a href=\"/setup\">Inventory</a></li>";
                    $displaySetupModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("SetupView")){
                    $setupLinks .= "<li><a href=\"/setup\">Reading Codes</a></li>";
                    $displaySetupModule = TRUE;
                }

                //Print Setups module
                if($displaySetupModule){
                    Echo"<li>Setup
                            <ul>
                                {$setupLinks}
                            </ul>
                        </li>";
                }


            } else {
                Echo"<li>&nbsp;</li>";
            }
            ?>
        </ul>
    </div>
    <?php
    //Sidebar
        if(isset($session->loggedInUser->userId)){
            Echo'<div id="mySidenav" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>';
                
                Echo"<p>Welcome<br />
                {$session->loggedInUser->fullName}</p>";

                Echo"<h3>Account Settings</h3>";
                Echo"<a class=\"navbaritem navbarbutton\" href=\"user/preferences\">Preferences</a>";
                Echo"<a class=\"navbaritem navbarbutton\" href=\"logout.php\">Log Out</a>";
               
            Echo'</div>

            <script>
            function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            }
            function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            }
            </script>';
        }
    ?>
    
</div>