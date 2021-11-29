<?php

class Site {

    public function __construct(){
        $this->connect = Database::getInstance()->getConnection();
        $sql = "SELECT name,timezone,domain,installpath,webaddress FROM site WHERE status = 'Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll();
        
        foreach($row as $row){
            $this->siteName = $row['name'];
            $this->timeZone = $row['timezone'];
            $this->domain = $row['domain'];
            $this->installPath = $row['installpath'];
            $this->webAddress = $row['webaddress'];
        }        
}

    public function generateTimeZoneDropdown($timeZone){
        $timeArray = array(
            "Eastern" => "America/New_York",
            "Central" => "America/Chicago",
            "Mountain" => "America/Denver",
            "Mountain no DST" => "America/Phoenix",
            "Pacific" => "America/Los_Angeles",
            "Alaska" => "America/Anchorage",
            "Hawaii" => "America/Adak",
            "Hawaii no DST" => "Pacific/Honolulu"
        );

        foreach($timeArray as $key => $value){
            if ($value == $timeZone){
                $Selected = 'Selected';
            } else {
                $Selected = "";
            }

            Echo"<option value=\"{$value}\" {$Selected}>{$key}</option>";
            unset($Selected);
        }
     }
    
}


?>