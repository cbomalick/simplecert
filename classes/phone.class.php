<?php

class Phone {

    public function __construct(){
        $this->connect = Database::getInstance()->getConnection();
    }

    public function getPhone($personId){
        $sql = "SELECT phone from phone WHERE personid = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$personId]);
        $row = $stmt->fetch();

        if(!empty($row)){
            return $row['phone'];
        } else {
            return "No phone number found";
        }
    }    
}

?>