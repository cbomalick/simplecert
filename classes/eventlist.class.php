<?php

class EventList{

    public function __construct(){
        $this->connect = Database::getInstance()->getConnection();
    }

    public function newLinkedEventList($linkId){
        $param = "%". $linkId ."%";
        $sql = "SELECT * from eventheader WHERE linkid LIKE ? AND status = 'Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$param]);
        $row = $stmt->fetchAll();
        //Construct with parent and then add extra event stuff on top

        if(!empty($row)){
            foreach ($row as $row){
                $event = new Event($row['eventid']);

                //Pull in additional event details such as event type, recommended action, and urgency
                $event->getFullDetails();

                //Add to array
                $objList[] = $event;
            }
            return $objList;
        } else {
            return null;
        }
    }

    public function eventIcon($eventList){
        $eventTitle = "";
        
        if(!empty($eventList)){
            foreach($eventList as $event){
                $eventTitle = $eventTitle . $event->eventName . "\n";
            }

        $icon = "<img src=\"images/alert.png\" title=\"{$eventTitle}\" />";
        return $icon;
        }
    }

    public function printEventList($inputList){
        $headers = array(
        "Customer" => "customerName",
        "Location" => "locationName",
        "Date" => "eventDateTime",
        "Event" => "eventName",
        "Event Type" => "eventTypeName",
        "Urgency" => "urgencyName",
        "Added By" => "employeeName",
        "Action" => "action");

        //CSS Styling for columns
        $classes = "events:text-center,action:short-input";

        $table = new Table($headers, $inputList, $classes);
    }
        
    }

?>