<?php

class LocationList{

    public function __construct($customerId = NULL){
        $this->locationList = [];

        if(is_null($customerId)){
            //Instantiate empty object but don't set any values yet
            return;
        } else {
            $this->connect = Database::getInstance()->getConnection();

            $sql = "SELECT * FROM location WHERE linkid = ? AND status != 'Cancelled'";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$customerId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $location = new Location($row['locationid']);
                    $locationList[] = $location;
                }
                $this->locationList = $locationList;
            }
        }
    }

}

?>