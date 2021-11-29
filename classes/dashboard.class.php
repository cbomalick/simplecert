<?php

class Dashboard {
    public function __construct(){
        $this->connect = Database::getInstance()->getConnection();
        $this->lov = new LOV();
    }

    // public function printRecentRecords($recordType, $companyList){
    //     $in  = str_repeat('?,', count($companyList) - 1) . '?';

    //     $sql = "SELECT recordid,customerid,employeeid,recorddatetime FROM recordheader WHERE recordtype = ? AND status = 'Active'
    //     AND customerid in (SELECT customerid FROM person WHERE company IN ($in) AND persontype = 'Customer' AND status = 'Active')
    //     ORDER BY recorddatetime DESC";
    //     $stmt = $this->connect->prepare($sql);
    //     $params = array_merge([$recordType], $companyList);
    //     $stmt->execute($params);
    //     $row = $stmt->fetchAll();

    //     if(!empty($row)){
    //         foreach($row as $row){
    //             if($recordType == "Service"){
    //                 $record = new Service($row['recordid']);
    //                 $record->recordDateTime = date("m/d/Y g:i a", strtotime($record->recordDateTime));
    //                 $record->serviceName = $this->lov->fetchLOVLabel("ServiceName", $record->recordCode);
    //             } else if($recordType == "Event"){
    //                 $record = new Event($row['recordid']);
    //                 $record->recordDateTime = date("m/d/Y g:i a", strtotime($record->recordDateTime));
    //                 $record->eventName = $this->lov->fetchLOVLabel("EventName", $record->recordCode);
    //             }

    //             $objList[] = $record;
    //         }
    //     }

    //     if($recordType == "Service"){
    //         $headers = array(
    //             "Customer" => "customerName",
    //             "Service Type" => "serviceName",
    //             "Date" => "recordDateTime",
    //             "Employee" => "employeeName");
    //     } else if($recordType == "Event"){
    //         $headers = array(
    //             "Customer" => "customerName",
    //             "Date" => "recordDateTime",
    //             "Event Type" => "eventName",
    //             "Employee" => "employeeName",
    //             "Urgency" => "urgencyName");
    //     }
        
    //         //CSS Styling for columns
    //         $classes = "events:text-center,action:short-input";
    
    //         $table = new Table($headers, $objList, $classes);

    // }
}

?>