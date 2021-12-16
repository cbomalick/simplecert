<?php

class PersonList{

    public function __construct($linkid = NULL){
        $this->personList = [];
        
        if(is_null($linkid)){
            return;
        } else {
            $this->connect = Database::getInstance()->getConnection();

            $sql = "SELECT * FROM person WHERE linkid = ? AND status != 'Cancelled'";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$linkid]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $person = new Person($row['personid']);
                    $personList[] = $person;
                }
                $this->personList = $personList;
            }
        }
    }

}

?>