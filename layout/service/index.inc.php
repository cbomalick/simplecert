<?php

switch($action){
    default:
        if(!$session->loggedInUser->validatePermissions("ServiceView")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('service.list.inc.php');
        }
    break;

    case"add":
        if(!$session->loggedInUser->validatePermissions("ServiceAdd")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('service.add.inc.php');
        }
    break;
    
    case"view":
        if(!$session->loggedInUser->validatePermissions("ServiceView")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('service.view.inc.php');
        }
    break;

    case"edit":
        if(!$session->loggedInUser->validatePermissions("ServiceEdit")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('service.edit.inc.php');
        }
    break;

    case"cancel":
        if(!$session->loggedInUser->validatePermissions("ServiceDelete")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('service.cancel.inc.php');
        }
    break;

    case"save":
        if(!$session->loggedInUser->validatePermissions("ServiceEdit")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('service.save.inc.php');
        }
    break;

    case"addnew":
        if(!$session->loggedInUser->validatePermissions("ServiceAdd")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('service.addnew.inc.php');
        }
    break;

}

?>