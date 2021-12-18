<?php

class Material {
    public $materialId;
    public $linkId;
    public $materialCode; //Numeric reference number
    public $materialCategory; //Chlorine, etc
    public $materialUnit; //Gallons, etc
    public $materialQuantity; //Numeric amount
    public $materialLocation; 
    public $status;
    public $createdBy;
    public $createdDate;
    public $modifiedBy;
    public $modifiedDate;

    public function __construct($materialId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        
        if(is_null($materialId)){
            //Instantiate empty object but don't set any values yet
            return;
        } else {
            $lov = LOV::getInstance()->getLOV();

            $sql = "SELECT * FROM material WHERE material.materialid = ? AND status = 'Active'";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$materialId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $this->materialId = $row['materialid'];
                    $this->linkId = $row['linkid'];
                    $this->materialCode = $row['materialcode'];
                    $this->materialCategory = $row['materialcategory'];
                    $this->materialUnit = $row['materialunit'];
                    $this->materialQuantity = $row['materialquantity'];
                    //$this->materialLocation = $lov->fetchLOVLabel("MaterialLocation", $row['materiallocation']);
                    $this->status = $row['status'];
                    $this->createdBy = $row['createdby'];
                    $this->createdDate = $row['createddate'];
                }
            }
        }
    }

    public function addMaterial(){
        $sql = "INSERT INTO material 
        (materialid,linkid,materialcode,materialcategory,materialunit,materialquantity,status,createdby,createddate) VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->materialId, $this->linkId, $this->materialCode, $this->materialCategory, $this->materialUnit, $this->materialQuantity, $this->status, $this->createdBy, $this->createdDate]);
    }

    public function loadMaterialDetails(){
        $sql = "SELECT category,unit FROM materialdetails WHERE materialcode = ? AND status = 'Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->materialCode]);
        $row = $stmt->fetch();

        $this->materialCategory = $row['category'];
        $this->materialUnit = $row['unit'];
    }
}

?>