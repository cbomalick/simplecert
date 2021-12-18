<?php

class Attachment {
    CONST FILEPATH = "";
    CONST MAX_ATTACHMENTS = 3;

    public function __construct($attachmentId = NULL){

        if(is_null($attachmentId)){
            //Instantiate empty object but don't set any values yet
            return;
        } else {
            $this->connect = Database::getInstance()->getConnection();

            $sql = "SELECT * FROM attachment WHERE attachmentid = :attachmentId AND status = 'Active'";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute(['attachmentId' => $attachmentId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $this->attachmentId = $row["attachmentid"];
                    $this->recordId = $row["recordid"];
                    $this->customerId = $row["customerid"];
                    $this->fileName = $row["filename"];
                    $this->fileType = $row["filetype"];
                    $this->status = $row["status"];
                }
            }
        }
    }

    public function addAttachment($recordId, $fileName, $fileType){

    }

    public function getLinkId($recordId){
        //Attachments can be linked to several kinds of records (services,events,employees,customers,etc).
        //This helper function will consolidate this lookup into one place

        $recordType = explode("-",$recordId);
        if($recordType[0] == "SVC"){
            $record = new Service($recordId);
            return $record->customerId;
        } else if($recordType[0] == "EVT"){
            $record = new Event($recordId);
            return $record->customerId;
        } else {
            return null;
        }

    }

}

?>