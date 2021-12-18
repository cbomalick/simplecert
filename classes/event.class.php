<?php

class Event {
    public function __construct($eventId = NULL){
        $this->connect = Database::getInstance()->getConnection();

        if(is_null($eventId)){
            //Instantiate empty object but don't set any values yet
            return;
        } else {

            $lov = LOV::getInstance()->getLOV();

            //Query data
            $sql = "SELECT * from eventheader WHERE eventid = ?";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$eventId]);
            $row = $stmt->fetchAll();

            foreach($row as $row){
                //Set core values for the service that will be used almost every time an event is referenced
                $this->eventId = $row['eventid'];
                $this->eventCode = $row['eventcode'];
                $this->eventName = $lov->fetchLOVLabel("EventName", $row['eventcode']);
                $this->customerId = $row['customerid'];
                $this->locationId = $row['locationid'];
                $this->eventDateTime = $row['eventdatetime'];
            }
        }
        
    }

    public function getFullDetails(){
        $lov = LOV::getInstance()->getLOV();
        if(!is_null($this->eventCode)){
            //Pull in additional event details such as event type, recommended action, and urgency
            $sql = "SELECT * from eventdetails WHERE eventcode = ? AND status = 'Active'";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$this->eventCode]);
            $details = $stmt->fetchAll();

            foreach($details as $detail){
                $this->eventType = $detail['eventtype'];
                $this->eventTypeName = $lov->fetchLOVLabel("EventType", $detail['eventtype']);
                $this->recommendedAction = $detail['recommendedaction'];
                $this->recommendedActionName = $lov->fetchLOVLabel("RecommendedAction", $detail['recommendedaction']);
                $this->urgency = $detail['urgency'];
                $this->urgencyName = $lov->fetchLOVLabel("Urgency", $detail['urgency']);
            }
        }
    }

}

?>