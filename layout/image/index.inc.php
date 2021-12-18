<?php

switch($action){
    default:
        
    break;

    case"add":
        if(!$session->loggedInUser->validatePermissions("ImageAdd")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('image.add.inc.php');
        }
    break;

    case"save":
        if(!$session->loggedInUser->validatePermissions("ImageAdd")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('image.save.inc.php');
        }
    break;

    case"cancel":
        if(!$session->loggedInUser->validatePermissions("ImageDelete")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('image.cancel.inc.php');
        }
    break;

}

?>