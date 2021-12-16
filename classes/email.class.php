<?php

class Email {

    public function __construct(){
        $this->connect = Database::getInstance()->getConnection();
    }

    public function getEmail($personId){
        $sql = "SELECT emailaddress from email WHERE personid = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$personId]);
        $row = $stmt->fetch();

        if(!empty($row)){
            return $row['emailaddress'];
        } else {
            return "No email found";
        }
    }
}

?>