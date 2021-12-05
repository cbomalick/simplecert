<?php

class Alert{
    
    public function __construct($alertId = NULL){
        $this->connect = Database::getInstance()->getConnection();

        //If alertId is provided, display alert. If no ID is provided, display all currently active alerts (for example on a dashboard)
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
                    $this->createdBy = $row['createdby'];
                    $this->createdDate = $row['createddate'];
                    //$this->badge = "<span class=\"badge white-text {$this->color}\">{$this->subject}</span>";
                }
            }
        }
    }

    //TODO: Instead of if/else, make this->color the array key and match value
    public function display(){
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
            //TODO: Log error if invalid color given
        }

        Echo"<div class=\"box alertbox center {$styles}\">
                <div class=\"alerticon\">
                    <img src=\"images/exclamation.png\" />
                </div>
                <div class=\"boxcontent\">
                    <p><b>{$this->subject}: </b>{$this->body}</p>
                </div>
            </div>";
    }
}

?>