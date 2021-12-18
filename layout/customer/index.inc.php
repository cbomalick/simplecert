<?php

switch($action){
    default:
        if(!$session->loggedInUser->validatePermissions("CustomerView")){
            Echo"<p>&nbsp;</p>";
            $alert = (new Alert())->permissionError();
            Echo"<p><button type=\"button\" class=\"button\" onclick=\"window.location.href = '/';\">Go Back</button></p> ";
        break;
        } else {
            require_once('customer.list.inc.php');
        }
    break;

    // case"add":
    //     if(!$session->loggedInUser->validatePermissions("CustomerAdd")){
    //         Echo"Error: You do not have permission to access this feature.";
    //     break;
    //     } else {
    //         require_once('customer.add.inc.php');
    //     }
    // break;

    // case"addnew":
    //     if(!$session->loggedInUser->validatePermissions("CustomerAdd")){
    //         Echo"Error: You do not have permission to access this feature.";
    //     break;
    //     } else {
    //         require_once('customer.addnew.inc.php');
    //     }
    // break;
    
    case"view":
        if(!$session->loggedInUser->validatePermissions("CustomerView")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('customer.view.inc.php');
        }
    break;

    case"contacts":
        if(!$session->loggedInUser->validatePermissions("CustomerView")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('customer.contacts.inc.php');
        }
    break;

    case"locations":
        if(!$session->loggedInUser->validatePermissions("CustomerView")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('customer.locations.inc.php');
        }
    break;

    case"notes":
        if(!$session->loggedInUser->validatePermissions("CustomerView")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('customer.notes.inc.php');
        }
    break;

    // case"edit":
    //     if(!$session->loggedInUser->validatePermissions("CustomerEdit")){
    //         Echo"Error: You do not have permission to access this feature.";
    //     break;
    //     } else {
    //         require_once('customer.edit.inc.php');
    //     }
    // break;

    // case"cancel":
    //     if(!$session->loggedInUser->validatePermissions("CustomerDelete")){
    //         Echo"Error: You do not have permission to access this feature.";
    //     break;
    //     } else {
    //         require_once('customer.cancel.inc.php');
    //     }
    // break;

    // case"save":
    //     if(!$session->loggedInUser->validatePermissions("CustomerEdit")){
    //         Echo"Error: You do not have permission to access this feature.";
    //     break;
    //     } else {
    //         require_once('customer.save.inc.php');
    //     }
    // break;

}

?>