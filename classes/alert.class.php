<?php

class Alert{
    
    public function __construct($alertId = NULL){
        $this->connect = Database::getInstance()->getConnection();

        if(is_null($alertId)){
            return;
        } else {
            $sql = "SELECT * FROM alerts WHERE alertId = ?";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$alertId]);
            $row = $stmt->fetchAll();

            if(!empty($row)){
                foreach($row as $row){
                    $this->alertId = $row["alertid"];
                    $this->color = $row["color"];
                    $this->subject = $row["subject"];
                    $this->body = $row["body"];
                    $this->startDate = $row["startdate"];
                    $this->endDate = $row["enddate"];
                    $this->status = $row["status"];
                    //$this->badge = "<span class=\"badge white-text {$this->color}\">{$this->subject}</span>";
                }
            }
        }
    }

    public function permissionError(){
        //Record that an invalid attempt occurred
        $audit = new AuditLog("Error", "Validate Permissions", "Attempted to access restricted page or feature");

        //Display an error message for the user
        $this->alertId = "permissionError";
        $this->color = "Error";
        $this->subject = "Error";
        $this->body = "Your account does not have permission to access this page or feature. Please contact your system administrator for further assistance.";
        $this->display();
    }

    public function display(){
        //CSS class names often match the description, but might not always follow that rule
        if($this->color == "Info"){
            $styles = "info";
        } else if($this->color == "Error"){
            $styles = "error";
        } else if($this->color == "Warning"){
            $styles = "warning";
        } else if($this->color == "Success"){
            $styles = "success";
        } else if($this->color == "System"){
            $styles = "system";
        } else {
            $styles = "info";
            //Default to Info colors, but log it for further review since this shouldn't be possible
            $audit = new AuditLog("Error", "Invalid Alert Color", "Alert color '{$this->color}' provided for {$this->alertId}");
        }

        Echo"<div class=\"boxwrapper\">
        <div class=\"box alertbox center {$styles}\">
                <div class=\"alerticon\">
                    <img src=\"images/exclamation.png\" />
                </div>
                <div class=\"boxcontent\">
                    <p><b>{$this->subject}: </b>{$this->body}</p>
                </div>
            </div>
        </div>";
    }
}

?>