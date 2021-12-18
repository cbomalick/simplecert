<?php

class Metric {
    public function __construct($metricId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        //$this->timeHandler = new TimeHandler($_SESSION['timeZone']);
        if(is_null($metricId)){
            //Instantiate empty object but don't set any values yet
            return;
        } else {
            $sql = "SELECT 
            metrics.metricid, metrics.recordid, metrics.metricdate, metrics.metriccode, metrics.value, metrics.type,
            metrics.status, metrics.createdby, metrics.createddate, metriccodes.fullname, metriccodes.shortname, metriccodes.floor, metriccodes.ceiling
            FROM metrics 
            LEFT JOIN metriccodes on metrics.metriccode = metriccodes.metriccode
            WHERE metrics.metricid = ?";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$metricId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $this->metricId = $row['metricid'];
                    $this->recordId = $row['recordid'];
                    $this->metricDate = $row['metricdate'];
                    $this->metricCode = $row['metriccode'];
                    $this->shortName = $row['shortname'];
                    $this->fullName = $row['fullname'];
                    $this->value = $row['value'];
                    $this->floor = $row['floor'];
                    $this->ceiling = $row['ceiling'];
                    $this->type = $row['type'];
                    $this->status = $row['status'];
                    $this->createdBy = $row['createdby'];
                    $this->createdDate = $row['createddate'];
                    $this->modifiedBy = $row['createddate'];
                    $this->modifiedDate = $row['createddate'];
                }
            }
        }
    }

    public function addMetric(){
        $sql = "INSERT INTO metrics 
        (metricid,recordid,metricdate,metriccode,value,status,createdby,createddate) VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->metricId, $this->recordId, $this->metricDate, $this->metricCode, $this->value, $this->status, $this->createdBy, $this->createdDate]);
    }

    public function updateMetric(){
        $sql = "UPDATE metrics SET metricDate = ?, value = ?, status = ?, modifiedby = ?, modifieddate = ? WHERE metricid = ?";
        
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->metricDate, $this->value, $this->status, $this->modifiedBy, $this->modifiedDate, $this->metricId]);
    }

    public function cancelCluster($dateInt){
        $metricDate = date("Y-m-d H:i:s", $dateInt);
        $utcMetricDate = $this->timeHandler->convertToUTC($metricDate);

        $sql = "UPDATE metrics SET status = ?, modifiedby = ?, modifieddate = ? WHERE recordid = ? AND metricdate = ?";

        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->status, $this->modifiedBy, $this->modifiedDate, $this->recordId, $utcMetricDate]);
    }
}

?>