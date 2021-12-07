<?php

    /** To use within a class: */
    /** $lov = LOV::getInstance()->getLOV(); */

class LOV {

    protected static $_instance;
    
    protected function __construct(){
        $this->connect = Database::getInstance()->getConnection();
        $this->session = Session::getInstance()->getSession();
        //$this->timeHandler = new TimeHandler($this->session->loggedInUser->preferences["timeZone"] ?? $this->session->site->defaultTimeZone);

        return $this;
    }

    public function getLOV(){
        return $this;
    }

    public static function getInstance(){
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function __clone(){
        //** Disabled */
    }

    public function lovDropdown($fieldCode, $compare = NULL, $name = NULL, $id = NULL, $style = "SINGLE"){
        if($style == "SINGLE" || $style == NULL){
            $class = "js-example-basic-single";
            $multiple = "";
        } else if ($style == "MULTI") {
            $class = "js-example-basic-multiple";
            $multiple = "multiple";
        }

        Echo"<select {$multiple} name = \"{$name}\" id = \"{$id}\"class=\"{$class}\">
            <option value=\"\"></option>";
        
        $this->lovDropdownOptions($fieldCode, $compare);

        Echo"</select>";
    }

    public function lovDropdownOptions($fieldCode, $compare = NULL){
        $sql = "SELECT lov.value, lov.label, lovheader.defaultvalue from lov JOIN lovheader ON lov.field = lovheader.fieldcode WHERE lov.field = :fieldCode AND lov.visible = 'Y' ORDER BY printorder,label ASC";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute(['fieldCode' => $fieldCode]);
        $row = $stmt->fetchAll();

        if(!empty($row)){
            Echo"";
                foreach($row as $row){
                    $selected = "";
                    if($compare == "DEFAULT"){
                        $selectedValue = $row["defaultvalue"];
                    } else {
                        $selectedValue = $compare;
                    }

                    $value = $row["value"];
                    $label = $row["label"];
                    
                    if ($selectedValue == $value){
                        $selected = 'selected';
                    } else if (is_array($selectedValue)){
                        if (in_array($value, $selectedValue)){
                            $selected = 'selected';
                        }
                    }
                    
                    Echo"<option name = \"{$fieldCode}\" value=\"{$value}\" {$selected}>{$label}</option>";
                    unset($selected);
                }
        }
    }

    public function fetchLOVLabel($fieldCode, $value){
        $sql = "SELECT label from lov WHERE field = :fieldCode AND value = :value";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute(['fieldCode' => $fieldCode, 'value' => $value]);
        $row = $stmt->fetchAll();

        if(!empty($row)){
            foreach ($row as $row){
                $label = $row['label'];
            }
        } else {
            $label = "Error: Not Found";
        }
        return $label;
    }

    public function generateListDropDown($recordId){
        $page = "";
        $recordType = explode("-",$recordId);

        if($recordType[0] == "SVC"){
            $page = "service";
        } else if($recordType[0] == "EVT"){
            $page = "event";
        }

        $dropdown = "
        <select name = \"dropdown\" class=\"js-example-basic-single\" onchange=\"if (this.value) window.location.href=this.value\">
            <option value=\"\"></option>
            <option value=\"{$page}/view/{$recordId}\">View</option>
            <option value=\"{$page}/edit/{$recordId}\">Edit</option>
            <option value=\"{$page}/cancel/{$recordId}\">Cancel</option>
        </select>";

        $dropdown = "
            <select name = \"dropdown\" class=\"js-example-basic-single\" onchange=\"if (this.value) window.location.href=this.value\">
            <option value=\"\"></option>";

            if($this->session->loggedInUser->validatePermissions("ServiceView") || $this->session->loggedInUser->validatePermissions("EventView")){
                $dropdown .= "<option value=\"{$page}/view/{$recordId}\">View</option>";
            }
            if($this->session->loggedInUser->validatePermissions("ServiceEdit") || $this->session->loggedInUser->validatePermissions("EventEdit")){
                $dropdown .= "<option value=\"{$page}/edit/{$recordId}\">Edit</option>";
            }
            if($this->session->loggedInUser->validatePermissions("ServiceDelete") || $this->session->loggedInUser->validatePermissions("EventDelete")){
                $dropdown .= "<option value=\"{$page}/cancel/{$recordId}\">Cancel</option>";
            }

        $dropdown .= "</select>";

        return $dropdown;
    }


    //** General Purpose Dropdowns */

    public function employeeDropdown($compare = NULL){
        $sql = "SELECT * FROM person WHERE persontype = 'Employee' AND status ='Active' ORDER BY lastname ASC";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll();

        if(!empty($row)){
            Echo"<select name = \"employee\" id = \"employee\" class=\"js-example-basic-single\">";

            foreach($row as $row){
                $personId = $row["personid"];
                $fullName = $row["lastname"] . ", " . $row["firstname"];
                
                if ($compare == $personId){
                    $selected = 'selected';
                } else {
                $selected = "";
                }
                
                Echo"<option value=\"{$personId}\" {$selected}>{$fullName}</option>";
                unset($selected);
            }
            Echo"</select>";
        }
    }

    public function locationDropdown($customerId = NULL, $compare = NULL){
        //If personId is provided, list that person's locations. If no personId is provided, list all customer/location pairs
        if (!is_null($customerId)){
            $sql = "SELECT * FROM location WHERE customerid = ? AND status ='Active'";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$customerId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                Echo"<select name = \"location\" id = \"location\" class=\"js-example-basic-single\">";

                foreach($row as $row){
                    $locationId = $row["locationid"];
                    $locationName = $row["locationname"];
                    
                    if ($compare == $locationId){
                        $selected = 'selected';
                    } else {
                    $selected = "";
                    }
                    
                    Echo"<option value=\"{$locationId}\" {$selected}>{$locationName}</option>";
                    unset($selected);
                }
                Echo"</select>";
            }
        } else {
            $sql = "SELECT location.locationid, location.locationname, location.customerid, customer.name FROM location JOIN customer ON location.customerid = customer.customerid WHERE location.status = 'Active' AND customer.status = 'Active'";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetchAll();

            if(!empty($row)){
                Echo"<select name = \"customerLocation\" id = \"customerLocation\" class=\"js-example-basic-single\">";

                foreach($row as $row){
                    $locationId = $row["locationid"];
                    $locationName = $row["locationname"];
                    $customerId = $row["customerid"];
                    $customerName = $row["name"];
                    
                    if ($compare == $locationId){
                        $selected = 'selected';
                    } else {
                    $selected = "";
                    }
                    
                    Echo"<option value=\"{$customerId}|{$locationId}\" {$selected}>{$customerName} - {$locationName}</option>";
                    unset($selected);
                }
                Echo"</select>";
            }

        }
    }

    public function customerDropdown($compare = NULL){
        $sql = "SELECT * FROM customer WHERE status ='Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll();

        if(!empty($row)){
            Echo"<select name = \"customer\" id = \"customer\" class=\"js-example-basic-single\">";

            foreach($row as $row){
                $customerId = $row["customerid"];
                $customerName = $row["name"];
                
                if ($compare == $customerId){
                    $selected = 'selected';
                } else {
                $selected = "";
                }
                
                Echo"<option value=\"{$customerId}\" {$selected}>{$customerName}</option>";
                unset($selected);
            }
            Echo"</select>";
        }
    }

    public function materialDropdown($personId, $compare = NULL){
        $sql = "SELECT * FROM location WHERE personId = :personId AND status ='Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute(['personId' => $personId]);
        $row = $stmt->fetchAll();

        if(!empty($row)){
            Echo"<select name = \"material\" id = \"material\" class=\"js-example-basic-single\">";

            foreach($row as $row){
                $locationId = $row["locationid"];
                $locationName = $row["locationname"];
                
                if ($compare == $locationId){
                    $selected = 'selected';
                } else {
                $selected = "";
                }
                
                Echo"<option value=\"{$locationId}\" {$selected}>{$locationName}</option>";
                unset($selected);
            }
            Echo"</select>";
        }
    }

    public function contactDropdown($customerId = NULL){
        $sql = "SELECT person.personid,person.firstname,person.lastname,customer.primarycontact FROM person JOIN customer ON person.customerid = customer.customerid WHERE person.customerid = ? AND person.status ='Active' ORDER BY person.lastname,person.firstname ASC";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$customerId]);
        $row = $stmt->fetchAll();

        Echo"<select name = \"contact\" id = \"contact\" class=\"js-example-basic-single\">";

        if(!empty($row)){

            foreach($row as $row){
                $contactId = $row["personid"];
                $firstName = $row["firstname"];
                $lastName = $row["lastname"];
                $primaryContact = $row["primarycontact"];
                
                if ($primaryContact == $contactId){
                    $selected = 'selected';
                } else {
                $selected = "";
                }
                
                Echo"<option value=\"{$contactId}\" {$selected}>{$lastName}, {$firstName}</option>";
                unset($selected);
            }
        }
        Echo"</select>";
        
    }

    public function linkedRecordDropdown($locationId, $linkedRecord = NULL){
        //If personId is provided, list that person's locations. If no personId is provided, list all customer/location pairs
        if (!is_null($locationId)){
            $sql = "SELECT * FROM recordheader WHERE locationid = ? AND recordtype != 'Event' AND status = 'Active' ORDER BY recorddatetime DESC LIMIT 15";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$locationId]);
            $row = $stmt->fetchAll();

            Echo"<select name = \"linkedRecord\" id = \"linkedRecord\" class=\"js-example-basic-single\">
                <option value=\"\"></option>";

            if(!empty($row)){
                foreach($row as $row){
                    $recordId = $row["recordid"];
                    $recordDateTime = $row["recorddatetime"];
                    
                    if ($linkedRecord == $recordId){
                        $selected = 'selected';
                    } else {
                    $selected = "";
                    }
                    
                    $recordDateTime = $this->timeHandler->displayUserDateTime($recordDateTime);
                    
                    Echo"<option value=\"{$recordId}\" {$selected}>".date("m/d/Y g:i a", strtotime($recordDateTime))."</option>";
                    unset($selected);
                }
            }

            Echo"</select>";
        }
    }

    public function companyDropdown($allowedCompanies, $compare, $style = "SINGLE"){
        if($style == "SINGLE" || $style == NULL){
            $class = "js-example-basic-single";
            $multiple = "";
            $name = "companyId";
        } else if ($style == "MULTI") {
            $class = "js-example-basic-multiple";
            $multiple = "multiple";
            $name = "multiCompanyId[]";
        }

        if (!is_null($allowedCompanies)){
            $in  = str_repeat('?,', count($allowedCompanies) - 1) . '?';
            $sql = "SELECT * FROM companies WHERE companyid IN ($in) AND status = 'Active' ORDER BY shortname ASC";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute($allowedCompanies);
            $row = $stmt->fetchAll();

            Echo"<select {$multiple} name = \"{$name}\" id=\"{$name}\" class=\"{$class}\">
            <option></option>";

            if(!empty($row)){
                foreach($row as $row){

                    $companyId = $row["companyid"];
                    $shortName = $row["shortname"];
                    
                    if ($companyId == $compare){
                        $selected = 'selected';
                    } else {
                        $selected = "";
                    }
                    
                    Echo"<option value=\"{$companyId}\" {$selected}>{$shortName}</option>";
                    unset($selected);
                }
            }

            Echo"</select>";
        }
    }

    public function requiredFields($requiredFields){
        Echo"
        <script>
            $(document).ready(function() {";
            
            foreach($requiredFields as $key=>$value){
                Echo"
                $(\"#submit\").click(function(e) {
                var {$key} = $(\"#{$key}\").val();
                if (!({$key} == '')) {
                $(\"#{$key}Warning\").empty();
                } else {
                e.preventDefault();
                $(\"#{$key}Warning\").empty();
                $(\"#{$key}Warning\").append(\"<br />{$value}\");
                }
                });
                ";
            }
            
            
            Echo"});
        </script>";
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

        Echo"<select name = \"timeZone\" id = \"timeZone\" class=\"js-example-basic-single\">";

        foreach($timeArray as $key => $value){
            if ($value == $timeZone){
                $Selected = 'Selected';
            } else {
                $Selected = "";
            }

            Echo"<option value=\"{$value}\" {$Selected}>{$key}</option>";
            unset($Selected);
        }
        Echo"</select>";
     }

}
?>