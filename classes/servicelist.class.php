<?php

class ServiceList {
    const ALLOWED_STATUSES = array("Active","Cancelled","Deleted","Scheduled","Completed");

    public function __construct(){
        $this->connect = Database::getInstance()->getConnection();
    }
    
    public function newServiceList(Array $statuses, String $fromTime = "2010-01-01 00:00:00", String $toTime = "2050-01-01 00:00:00", $companyId = NULL, $customerName = NULL){
        //Check submitted Status against allowed values. If status is not valid, it is removed from the list
        foreach($statuses as $status){
            if(!in_array($status, SELF::ALLOWED_STATUSES)){
                $key = array_search($status, $statuses);
                unset($statuses[$key]);
            }
        }

        //Allows multiple statuses to be selected by building IN('','') query
        $statusList = "''";
        foreach($statuses as $status){
            $statusList = $statusList . ",'$status'";
        }

        //Allows searching for any name containing the provided string
        $customerName = "%".$customerName."%";

        //Pull data
        $in  = str_repeat('?,', count($statuses) - 1) . '?';
        $sql = "SELECT serviceheader.serviceid,customer.company, customer.name from serviceheader 
        JOIN customer ON serviceheader.customerid = customer.customerid WHERE serviceheader.status IN ($in) 
        AND serviceheader.servicedatetime BETWEEN ? AND ? AND customer.company = ? AND customer.name LIKE ? ORDER BY serviceheader.servicedatetime DESC";
        $stmt = $this->connect->prepare($sql);
        $params = array_merge($statuses, [$fromTime, $toTime, $companyId, $customerName]);
        $stmt->execute($params);
        $serviceList = $stmt->fetchAll();

        if(!empty($serviceList)){
            foreach ($serviceList as $service){
                //Get basic service details
                $service = new Service($service['serviceid']);
                $lov = LOV::getInstance()->getLOV();
                $service->serviceName = $lov->fetchLOVLabel("ServiceName", $service->serviceCode);

                //Generate dropdown for list
                //TODO: LOV->generateActionList("ServiceAction", $service->serviceId)
                $service->action = $lov->generateListDropDown($service->serviceId);

                //Convert timestamp to readable format
                $service->serviceDateTime = date("m/d/Y g:i a", strtotime($service->serviceDateTime));
                
                //Add to array
                $objList[] = $service;
            }
            return $objList;
        } else {
            return null;
        }
    }
    
    public function printServiceList($inputList){
        $headers = array(
        "Customer" => "customerName",
        "Location" => "locationName",
        "Date" => "serviceDateTime",
        "Service" => "serviceName",
        "Metrics" => "metrics",
        "Events" => "events",
        "Added By" => "employeeName",
        "Action" => "action");

        //CSS Styling for columns
        $classes = "events:text-center,action:short-input";

        $table = new Table($headers, $inputList, $classes);
    }
}

?>