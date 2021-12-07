<?php

Echo "<p>&nbsp;</p>
<div class=\"boxwrapper\">";

switch($id){
    default:
    break;

    case"password":
        Echo'<div class="boxwrapper">
        <div class="box alertbox error">
            <div class="alerticon">
                <img src="images/exclamation.png" />
            </div>
            <div class="boxcontent">
                <p><b>Error: </b> The username or password you entered was incorrect. Please try again or contact your system administrator for assistance.</p>
            </div>
        </div>
    </div>';
    break;

    case"locked":
        Echo'<div class="boxwrapper">
        <div class="box alertbox error">
            <div class="alerticon">
                <img src="images/exclamation.png" />
            </div>
            <div class="boxcontent">
                <p><b>Error: </b> Your account has been locked due to too many incorrect login attempts. Please contact your system administrator for assistance.</p>
            </div>
        </div>
    </div>';
    break;
}

Echo "</div>";
Echo "<button type=\"button\" class=\"button\" onclick=\"window.location.href = '/';\">Go Back</button>";

?>