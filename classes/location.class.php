<?php

class Location {

    public function __construct($locationId = NULL){
        $this->connect = Database::getInstance()->getConnection();

        if(is_null($locationId)){
            return;
        } else {
            $this->locationId = $locationId;
            $lov = LOV::getInstance()->getLOV();

            $sql = "SELECT * FROM location WHERE locationid = ?";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$this->locationId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $this->locationName = $row["locationname"];
                    $this->locationRateType = $row["locationratetype"];
                    $this->locationCategory = $row["locationcategory"];
                    $this->capa = $row["locationname"];
                    $this->locationName = $row["locationname"];
                    $this->capacity = $row["capacity"];
                    $this->capacityLabel = $row["capacitylabel"];
                    $this->capacityLabelName = $lov->fetchLOVLabel("CapacityLabel", $this->capacityLabel);
                    $this->status = $row["status"];

                    $this->fullAddress = (new Address($locationId))->getFullAddress();
                    // $this->formattedAddress 
                }
            } else {
                $this->locationName = "Error: Not Found";
            }
        }
    }

    public function getLocationName(){
        return $this->locationName;
    }

    public function getLocationAddress(){
        $this->address = new Address($this->locationId);
        return $this->address;
    }

    public function getLocationRateType(){
        return $this->locationRateType;
    }

    public function getLocationCategory(){
        return $this->locationCategory;
    }

    // public function saveChanges(){
    //     //TODO: if (employee->isValid($loggedInEmployee)){ execute } else { error }
    //     $sql = "UPDATE location SET locationname = ?, locationratetype = ?, locationcategory = ?, capacity = ?, capacitylabel = ?, status = ?, modifiedby = ?, modifieddate = ? WHERE locationid = ? AND status = 'Active'";

    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$this->locationName, $this->locationRateType, $this->locationCategory, $this->capacity, $this->capacityLabel, $this->status, $this->modifiedBy, $this->modifiedDate, $this->locationId]);
    // }

    // public function addNew(){
    //     //TODO: if (employee->isValid($loggedInEmployee)){ execute } else { error }

    //     //Person details
    //     $sql = "INSERT INTO location 
    //     (locationid,customerid,locationname,locationratetype,locationcategory,capacity,capacitylabel,status,createdby,createddate) VALUES 
    //     (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
    //     $stmt = $this->connect->prepare($sql);
    //     $stmt->execute([$this->locationId, $this->customerId, $this->locationName, $this->locationRateType, $this->locationCategory, $this->capacity, $this->capacityLabel, $this->status, $this->createdBy, $this->createdDate]);
    // }
}
