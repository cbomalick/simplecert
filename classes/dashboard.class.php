<?php

class Dashboard {
    public function __construct(){
        $this->connect = Database::getInstance()->getConnection();
    }

    public function displayActiveAlerts(){
        $sql = "SELECT alertid FROM alerts WHERE (startdate <= CURDATE() AND enddate >= CURDATE()) and status = 'Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll();

        if(!empty($row)){
            foreach($row as $row){
                $alert = new Alert($row['alertid']);
                $alert->display();
            }
        }
    }
}

?>