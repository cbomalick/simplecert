<?php

class Service {
    public $events;

    public function __construct($serviceId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        if(is_null($serviceId)){
            //Instantiate empty object but don't set any values yet
            return;
        } else {

            //Load utilities. Should be consolidated better for universal usage
            //$lov = LOV::getInstance()->getLOV();
            $session = Session::getInstance()->getSession();
            $timeHandler = new TimeHandler($session->loggedInUser->preferences["timeZone"]);
            
            //Query data
            $sql = "SELECT * from serviceheader WHERE serviceid = ?";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$serviceId]);
            $row = $stmt->fetchAll();

            foreach($row as $row){
                //Set core values for the service that will be used almost every time a service is referenced
                $this->serviceId = $row['serviceid'];
                $this->customerId = $row['customerid'];
                $this->locationId = $row['locationid'];
                $this->utcServiceDateTime = $row['servicedatetime']; 
                $this->serviceDateTime = $timeHandler->displayUserDateTime($this->utcServiceDateTime);
                $this->employeeId = $row['employeeid'];
                $this->status = $row['status'];
                $this->createdBy = $row['createdby'];
                $this->createdDate = $row['createddate'];
                $this->serviceCode = $row['servicecode']; //Name of service performed
                $this->responseCode = $row['responsecode'];

                //Get employee details
                $employee = new Employee($this->employeeId);
                $this->employeeName = $employee->getFullName();

                //Get Location details
                $this->location = new Location($this->locationId);
                $this->locationName = $this->location->getLocationName();

                //Get Customer details
                $customer = new Customer($this->customerId);
                $this->customerName = $customer->getName();
                //$this->companyId = $customer->companyId;

                //Generate metrics list
                $this->metricList = new MetricList($this->serviceId);
                $this->metrics = $this->metricList->listInlineBoxes();

                //Generate events icon
                $eventList = new EventList();
                $objArray = $eventList->newLinkedEventList($this->serviceId);
                $this->events = $eventList->eventIcon($objArray); //TODO: Move to servicelist

                // $this->responseText = $lov->fetchLOVLabel("ResponseCode", $row['responsecode']);
            }
        }
    }

    public function getFullDetails(){
        //Load additional event details
        $this->addEvents(); //Loads any linked events for the service
        
        //Load location address details
        $this->location->getLocationAddress();
        $this->location->formattedAddress = $this->location->address->getFormattedAddress();
        
        //Load image list
        $this->imageList = new ImageList($this->serviceId);

        //Load notes
        $this->notes = new NoteList($this->serviceId);

        //Load materials
        $this->materials = new MaterialList($this->serviceId);
    }

    public function addEvents(){
        $eventList = new EventList();
        $this->events = $eventList->newLinkedEventList($this->serviceId);
    }
    
    public function printLinkedEvents(){
        
        $headers = array(
            "Event Name" => "eventName",
            "Event Type" => "eventTypeName",
            "Recommended Action" => "recommendedActionName",
            "Urgency" => "urgencyName");
            
            //CSS Styling for columns
            $classes = "";
            
            $table = new Table($headers, $this->events, $classes);
    }

    public function printMaterialList(){
                
        $headers = array(
            "Material" => "materialName",
            "Category" => "materialCategoryName",
            "Quantity" => "materialQuantity",
            "Unit" => "materialUnitName");
            
            //CSS Styling for columns
            $classes = "materialQuantity:text-right";
            
            $table = new Table($headers, $this->materials->materialList, $classes);
    }



}

?>