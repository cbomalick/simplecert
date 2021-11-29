<div class="navigationbar" >
    <div class="logo">
        <!-- Display logo -->
    </div>
    <div class="user">
        <img src="images/photo.jpg" onclick="openNav()"/>
    </div>
    <div class="navigation">
        <ul>
            <?php
            $customerLinks = '';
            
            //Dashboard
            //All users have access, used as initial landing page
            Echo"<li><a href=\"/\">Dashboard</a></li>";

            //Build Customers module
            if($loggedInUser->validatePermissions("CustomerView")){
                $customerLinks .= "<li><a href=\"/customer\">Customers</a></li>";
                $displayCustomerModule = TRUE;
            }

            if($loggedInUser->validatePermissions("EventView")){
                $customerLinks .= "<li><a href=\"/event\">Events</a></li>";
                $displayCustomerModule = TRUE;
            }

            if($loggedInUser->validatePermissions("ServiceView")){
                $customerLinks .= "<li><a href=\"/service\">Services</a></li>";
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

            $employeeLinks = '';
            //Build Employee module
            if($loggedInUser->validatePermissions("EmployeeView")){
                $employeeLinks .= "<li><a href=\"/employee\">Employees</a></li>";
                $displayEmployeeModule = TRUE;
            }

            if($loggedInUser->validatePermissions("NotificationView")){
                $employeeLinks .= "<li><a href=\"/notification\">Notifications</a></li>";
                $displayEmployeeModule = TRUE;
            }

            if($loggedInUser->validatePermissions("RouteView")){
                $employeeLinks .= "<li><a href=\"/route\">Routes</a></li>";
                $displayEmployeeModule = TRUE;
            }

            //Print Employee module
            if($displayEmployeeModule){
                Echo"<li>Employee
                    <ul>
                        {$employeeLinks}
                    </ul>
                    </li>";
            }

            //Billing Module
            // Echo"<li>Billing
            // <ul>
            //     <li><a href=\"servicelist.php\">Balance Adjustment</a></li>
            //     <li><a href=\"servicelist.php\">Bank Deposits</a></li>
            //     <li><a href=\"servicelist.php\">Generate Bills</a></li>
            // </ul>
            // </li>";
            
            // //Accounting Module
            // Echo"<li>Accounting
            // <ul>
            //     <li><a href=\"servicelist.php\">Balance Summary</a></li>
            //     <li><a href=\"servicelist.php\">Payments Received</a></li>
            //     <li><a href=\"servicelist.php\">Revenue Summary</a></li>
            //     <li><a href=\"servicelist.php\">View Bills</a></li>
            // </ul>
            // </li>";

            // //Reports Module
            // Echo"<li>Reports
            // <ul>
            //     <li><a href=\"servicelist.php\">Mailing List</a></li>
            //     <li><a href=\"servicelist.php\">Referral Sources</a></li>
            // </ul>
            // </li>";
            ?>
        </ul>
    </div>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <?php           
            Echo"<h3>Favorites</h3>";
            Echo"<a class=\"navbaritem navbarbutton\" href=\"\">Employees</a>";

            Echo"<h3>Account Settings</h3>";
            Echo"<a class=\"navbaritem navbarbutton\" href=\"preferences.php\">Preferences</a>";
            Echo"<a class=\"navbaritem navbarbutton\" href=\"logout.php\">Log Out</a>";
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