<?php

switch($action){
    default:
        
    break;

    case"add":
        if(!$session->loggedInUser->validatePermissions("NoteAdd")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('note.add.inc.php');
        }
    break;

    case"save":
        if(!$session->loggedInUser->validatePermissions("NoteAdd")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('note.save.inc.php');
        }
    break;

    case"edit":
        if(!$session->loggedInUser->validatePermissions("NoteEdit")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('note.edit.inc.php');
        }
    break;

    case"delete":
        if(!$session->loggedInUser->validatePermissions("NoteDelete")){
            Echo"Error: You do not have permission to access this feature.";
        break;
        } else {
            require_once('note.delete.inc.php');
        }
    break;

}

?>