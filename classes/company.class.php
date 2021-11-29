<?php

class Company {

    public function __construct($entityId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        if(is_null($entityId)){
            //Instantiate empty object but don't set any values yet
            return;
        } else {
            $sql = "SELECT * FROM entity WHERE entityid = ? AND status = 'Active'";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$entityId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $this->entityId = $row["entityid"];
                    $this->longName = $row["longname"];
                    $this->shortName = $row["shortname"];
                    $this->ownerName = $row["ownername"];
                    $this->website = $row["website"];
                    $this->status = $row["status"];
                    
                    
                }
            }
        }
    }
}

?>