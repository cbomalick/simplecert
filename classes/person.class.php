<?php

class Person {

    public function __construct($personId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        if(is_null($personId)){
            return;
        } else {
            $sql = "SELECT personid,firstname,lastname FROM person WHERE personid = ?";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$personId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $this->personId = $row["personid"];
                    $this->firstName = $row["firstname"];
                    $this->lastName = $row["lastname"];
                    $this->fullName = $this->firstName . " " . $this->lastName;

                    $this->emailAddress = (new Email())->getEmail($this->personId);
                    $this->phoneNumber = (new Phone())->getPhone($this->personId);
                    $this->address = new Address($this->personId);
                }
            } else {
                $this->fullName = "Not Found";
            }
        }
    }

    public function getFirstName(){
        return $this->firstName;
    }
    
    public function getFullName(){
        return $this->fullName;
    }

}

?>