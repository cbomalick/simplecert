<?php
class Note {

    public function __construct($noteId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        if(is_null($noteId)){
            // $idNumber = new IdNumber();
            // $this->noteId = $idNumber->generateID("NTE");
            // $this->status = "Active";
            // $this->createdDate = date("Y-m-d H:i:s");
            return;
        } else {
            $sql = "SELECT * from notes WHERE noteid = ? AND status = 'Active'";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$noteId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $this->noteId = $row['noteid'];
                    $this->linkId = $row['linkid'];
                    $this->noteType = $row['notetype'];
                    $this->noteText = $row['notetext'];
                    $this->noteDate = $row['notedate'];
                    $this->status = $row['status'];
                    $this->createdBy = $row['createdby'];
                }
            }
        }
    }

    public function addNew(){
        //TODO: if (employee->isValid($loggedInEmployee)){ execute } else { error }

        $sql = "INSERT INTO notes 
        (noteid,linkid,notetext,notedate,status,createdby,createddate) VALUES 
        (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->noteId, $this->linkId, $this->noteText, $this->noteDate, $this->status, $this->createdBy, $this->createdDate]);
    }

    public function saveChanges(){
        //TODO: if (employee->isValid($loggedInEmployee)){ execute } else { error }

        $sql = "UPDATE notes SET notetext = ?, notedate = ?, status = ?, modifiedby = ?, 
        modifieddate = ? WHERE noteid = ?";

        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->noteText, $this->noteDate, $this->status, $this->modifiedBy, $this->modifiedDate, $this->noteId]);
    }


}
?>