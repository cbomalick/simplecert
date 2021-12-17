<?php

class Customer {
    public function __construct($customerId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        $lov = LOV::getInstance()->getLOV();
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
                $this->startDate = date("m/d/Y", strtotime($row['startdate']));
                $this->accountName = $row['accountname'];
                $this->primaryContact = $row['primarycontact'];
                $this->category = $row['category'];
                $this->categoryName = $lov->fetchLOVLabel("CustomerCategory", $this->category);
                $this->billCycle = $row['billcycle'];
                $this->billCycleName = $lov->fetchLOVLabel("BillCycle", $this->billCycle);
                $this->status = $row['status'];
                
                $this->primaryContactName = (new Person($row['primarycontact']))->getFullName();
                $this->primaryPhone = (new Phone())->getPhone($this->primaryContact);
                $this->primaryEmail = (new Email())->getEmail($this->primaryContact);

                //Demo values
                $this->lastBillAmount = "132.00";
                $this->lastBillDate = "2021-12-16";
                $this->totalAmountDue = "0.00";
            }
        }
    }
}

?>