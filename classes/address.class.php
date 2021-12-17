<?php

class Address {

    public function __construct($linkId = NULL){
        $this->connect  = Database::getInstance()->getConnection();
        if(is_null($linkId)){
            //Instantiate empty object but don't set any values yet
            return;
        } else {

            $sql = "SELECT * FROM address WHERE linkid = ?";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$linkId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $this->addressId = $row["addressid"];
                    $this->linkId = $row["linkid"];
                    $this->address1 = $row["address1"];
                    $this->address2 = $row["address2"];
                    $this->city = $row["city"];
                    $this->state = $row["state"];
                    $this->zip = $row["zip"];
                    $this->status = $row["status"];
                }
            } else {
                $this->fullAddress = "None";
            }
        }
    }

    public function getFullAddress(){
        //Returns full address in single line
        if(isset($this->fullAddress)){
            return $this->fullAddress;
        }

        if(!empty($this->address2)){
            $spacer = ", ";
        } else {
            $spacer = "";
        }

        if(!empty($this->address1)){
            $fullAddress = $this->address1 . $spacer . $this->address2 . ", <span style=\"white-space:nowrap\">" . $this->city . ", " . $this->state . "</span> " . $this->zip;
        } else {
            $fullAddress = "None";
        }
        
        return $fullAddress;
    }

    public function getFormattedAddress(){
        //Returns full address with line break before city
        if($this->fullAddress == "None"){
            return $this->fullAddress;
        }

        if(!empty($this->address2)){
            $spacer = ", ";
        } else {
            $spacer = "";
        }

        $fullAddress = "<p>" . $this->address1 . $spacer . $this->address2 . "</p><p>" . $this->city . ", " . $this->state . " " . $this->zip . "</p>";
        return $fullAddress;
    }

}
?>