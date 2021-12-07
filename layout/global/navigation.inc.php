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
            if(!isset($session->loggedInUser)){
                //Dashboard module
                //All users have access, used as initial landing page
                Echo"<li><a href=\"/\">Dashboard</a></li>";

                //Build Customers module
                $customerLinks = ''; //Begin with empty menu and append screens the user has permissions for
                $displayCustomerModule = FALSE; //Hide module by default
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

                //Build Billing module
                $billingLinks = ''; //Begin with empty menu and append screens the user has permissions for
                $displayBillingModule = FALSE; //Hide module by default
                if($session->loggedInUser->validatePermissions("BillingView")){
                    $billingLinks .= "<li><a href=\"/aging\">Bill Aging</a></li>";
                    $displayBillingModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("BillingView")){
                    $billingLinks .= "<li><a href=\"/viewbills\">View Bills</a></li>";
                    $displayBillingModule = TRUE;
                }
                if($session->loggedInUser->validatePermissions("BillingView")){
                    $billingLinks .= "<li><a href=\"/genbills\">Generate Bills</a></li>";
                    $displayBillingModule = TRUE;
                }

                //Print Billing module
                if($displayBillingModule){
                    Echo"<li>Billing
                            <ul>
                                {$billingLinks}
                            </ul>
                        </li>";
                }

            } else {
                Echo"<li>&nbsp;</li>";
            }
            
            
            ?>
        </ul>
    </div>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <?php  
            if(isset($_SESSION['userId'])){
                Echo"<h3>Favorites</h3>";
                Echo"<a class=\"navbaritem navbarbutton\" href=\"\">Employees</a>";

                Echo"<h3>Account Settings</h3>";
                Echo"<a class=\"navbaritem navbarbutton\" href=\"user/preferences\">Preferences</a>";
                Echo"<a class=\"navbaritem navbarbutton\" href=\"logout.php\">Log Out</a>";
            } else {
                Echo"<h3>Not Logged In</h3>";
            }
        ?>
    </div>

    <script>
    function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    }
    function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    }
    </script>
</div>