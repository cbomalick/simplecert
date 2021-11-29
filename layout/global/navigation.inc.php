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