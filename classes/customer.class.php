<?php

class Customer {
    public function __construct($customerId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        if(is_null($customerId)){
            return;
        } else {
            $sql = "SELECT * from customer WHERE customerid = ?";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$customerId]);
            $row = $stmt->fetchAll();

            foreach($row as $row){
                $this->companyId = $row['company'];
                $this->customerId = $row['customerid'];
                $this->startDate = $row['startdate'];
                $this->accountName = $row['accountname'];
                $this->primaryContact = $row['primarycontact'];
                $this->status = $row['status'];
                
                $this->primaryContactName = (new Person($row['primarycontact']))->getFullName();
                $this->primaryPhone = (new Phone())->getPhone($this->primaryContact);
                $this->primaryEmail = (new Email())->getEmail($this->primaryContact);
            }
        }
    }
}

?>