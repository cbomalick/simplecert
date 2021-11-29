<?php

class Person {

    public function __construct($personId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        if(is_null($personId)){
            return;
        } else {
            $sql = "SELECT * FROM person WHERE personid = :personId";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute(['personId' => $personId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $this->personId = $row["personid"];
                    $this->firstName = $row["firstname"];
                    $this->lastName = $row["lastname"];
                    $this->fullName = $this->firstName . " " . $this->lastName;
                    $this->status = $row["status"];
                }
            } else {
                $this->fullName = "None";
            }
        }
    }

    public function getFirstName(){
        return $this->firstName;
    }
    
    public function getFullName(){
        return $this->fullName;
    }

    // public function addNew(){
    //     //TODO: if (employee->isValid($loggedInEmployee)){ execute } else { error }

    //     //TODO: Split out
        
    //     //Person details
    //     $sql = "INSERT INTO person 
    //     (company,customerid,personid,persontype,contacttype,firstname,lastname,status,createdby,createddate) VALUES 
    //     (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$this->companyId, $this->customerId, $this->personId, $this->personType, $this->contactType, $this->firstName, $this->lastName, $this->status, $this->createdBy, $this->createdDate]);

    //     //Email
    //         $sql = "INSERT INTO email 
    //         (personid,emailaddress,status,createdby,createddate) VALUES 
    //         (?, ?, ?, ?, ?)";
            
    //         $stmt = $this->connect->prepare($sql);
    //         $stmt->execute([$this->personId, $this->emailAddress, $this->status, $this->createdBy, $this->createdDate]);

    //     //Phone
    //         $sql = "INSERT INTO phone 
    //         (personid,phone,status,createdby,createddate) VALUES 
    //         (?, ?, ?, ?, ?)";
            
    //         $stmt = $this->connect->prepare($sql);
    //         $stmt->execute([$this->personId, $this->phoneNumber, $this->status, $this->createdBy, $this->createdDate]);
    // }

    // public function saveChanges(){
    //     //TODO: if (employee->isValid($loggedInEmployee)){ execute } else { error }

    //     $sql = "UPDATE person SET contacttype = ?, firstname = ?, lastname = ?, status = ?, modifiedby = ?, modifieddate = ? WHERE personid = ?";

    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$this->contactType, $this->firstName, $this->lastName, $this->status, $this->modifiedBy, $this->modifiedDate, $this->personId]);
    // }
}

?>