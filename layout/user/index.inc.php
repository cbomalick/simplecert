<?php

switch($action){
    default:
        
    break;

    case"reset":
        require_once('user.reset.inc.php');
    break;

    case"resetsub":
        require_once('user.resetsub.inc.php');
    break;

    case"preferences":
        require_once('user.preferences.inc.php');
    break;

    case"prefsub":
        require_once('user.prefsub.inc.php');
    break;

    case"error":
        require_once('user.error.inc.php');
    break;

}

?>