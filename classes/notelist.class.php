<?php

class NoteList{
    
    public function __construct($linkId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        $this->session = Session::getInstance()->getSession();

        $sql = "SELECT noteid from notes WHERE linkid = ? AND status = 'Active' ORDER BY notedate ASC";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$linkId]);
        $row = $stmt->fetchAll();

        $this->timeHandler = new TimeHandler($this->session->loggedInUser->preferences["timeZone"]);

        if(!empty($row)){
            foreach ($row as $row){
                //Pull metric details
                $note = new Note($row['noteid']);
                $note->noteDate = date("m/d/Y g:i a", strtotime($this->timeHandler->displayUserDateTime($note->noteDate)));
                $note->createdByName = (new Person($note->createdBy))->getFullName();
                
                //Add to array
                $this->noteList[] = $note;
            }
            return $this->noteList;
        } else {
            return null;
        }
    }

    public function printNoteBoxes(){
        if(!empty($this->noteList)){
            $noteList = $this->noteList;
            foreach ($noteList as $note){
                Echo"
                    <div class=\"boxwrapper\" style=\"margin-top: 10px;\">
                        <div class=\"box metricbox largebox\">
                            <div class=\"boxcontent\">
                                <div class=\"metric notebox\">
                                    <h4>{$note->noteText}</h4>
                                    <p>Added on {$note->noteDate} by {$note->createdByName} <br />";

                                    if($this->session->loggedInUser->validatePermissions("NoteEdit")){
                                        Echo"<a href=\"note/edit/{$note->noteId}\" class=\"clickable\">Edit</a>";
                                    }

                                    if($this->session->loggedInUser->validatePermissions("NoteDelete")){
                                        Echo" | <a href=\"note/delete/{$note->noteId}\" class=\"clickable\">Delete</a>";
                                    }
                                
                                        Echo"</p></div>
                            </div>
                        </div>
                    </div>
                ";

            }
        }
    }
    
        
}

?>