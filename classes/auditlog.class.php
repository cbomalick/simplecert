<?php

class AuditLog{
    /** To use within a class: */
    /** $audit = new AuditLog("audit type", "screen or field", "text to log"); */
    /** $audit = new AuditLog("Update", "User Preferences", "Updated User Preferences"); */
    
    //Audit Types:
    ////Create - Used any time new data is inserted
    ////Update - Used any time new data is modified
    ////Delete - Used any time new data is deleted
    ////Error - Used any time an error is logged
    
    public function __construct($auditType, $field, $log){
        $enabled = TRUE; //Toggle entire audit log system on/off

        $this->connect = Database::getInstance()->getConnection();
        $session = Session::getInstance()->getSession();
        $id = new IdNumber();

        $this->auditId = $id->generateSessionId();
        $this->auditType = $auditType;
        $this->url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $this->field = $field;
        $this->log = $log;
        $this->changeMadeBy = $session->loggedInUser->userId ?? "Not logged in";
        $this->timpstamp = date("Y-m-d H:i:s");
        $this->ipAddress = $_SERVER['REMOTE_ADDR'];
        
        if($enabled){
            $this->saveLog();
        }
    }

    private function saveLog(){
        $sql = "INSERT INTO auditlog 
        (auditid,type,url,field,log,personid,timestamp,ipaddress) VALUES 
        (?, ?, ?, ?,?, ?, ?, ?)";
        
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->auditId, $this->auditType, $this->url, $this->field,
                        $this->log, $this->changeMadeBy, $this->timpstamp, $this->ipAddress]);
    }

}


?>