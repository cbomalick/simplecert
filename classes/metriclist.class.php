<?php

class MetricList{
    public function __construct($recordId = NULL){
        $this->connect = Database::getInstance()->getConnection();
        if(is_null($recordId)){
            //Instantiate empty object but don't set any values yet
            return;
        } else {
            $sql = "SELECT 
            metrics.metricid, metrics.recordid, metrics.metriccode, metrics.value, metrics.type,
            metrics.status, metrics.createdby, metrics.createddate, metrics.modifiedby, metrics.modifieddate, metrics.metricdate, metriccodes.shortname, metriccodes.floor, metriccodes.ceiling
            FROM metrics 
            LEFT JOIN metriccodes on metrics.metriccode = metriccodes.metriccode
            WHERE metrics.recordid = ? AND metrics.status = 'Active' ORDER BY metriccodes.priority ASC";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$recordId]);
            $row = $stmt->fetchAll();
            //$this->timeHandler = new TimeHandler($_SESSION['timeZone']);

            if(!empty($row)){
                foreach ($row as $row){
                    //Pull metric details
                    $metric = new Metric($row['metricid']);

                    //Add to array
                    $this->metricList[] = $metric;
                }
                
                //Generate list of unique metric date groups
                $this->uniqueMetricDates = array();
                foreach($this->metricList as $metric){
                    if(!in_array($metric->metricDate, $this->uniqueMetricDates)){
                        $this->uniqueMetricDates[] = $metric->metricDate;
                    }
                }
    
                //If more than 1 group exists, get the interval between them
                if(count($this->uniqueMetricDates) > 1){
                    $this->getInterval();
                }
                
                return $this->metricList;
            } else {
                return null;
            }
        }
        
    }

    public function rawMetrics(){
            $sql = "SELECT metriccode FROM metriccodes WHERE status = 'Active'";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetchAll();

            foreach($row as $row){
                $metricCodes[] = $row["metriccode"];
            }

            return $metricCodes;
    }

    public function printMetricBoxes(){
        if(!empty($this->metricList)){
            //For each unique date, echo any metrics that meet that date
            foreach($this->uniqueMetricDates as $date){
                Echo "<h4 style=\"font-weight: bold;\">" . date("m/d/Y g:i a", strtotime($date)) . "</h4>";
                Echo "<div class=\"boxwrapper\">";
                foreach($this->metricList as $metric){
                    if ($metric->metricDate == $date){
                        if(is_numeric($metric->value)){
                            if($metric->value < $metric->floor){
                                $color = "bad";
                                $boxWarning = "boxwarning";
                                $metricWarning = "<p class=\"readingboxchange\"><img src=\"images/triangle-down-red.png\" /> too low</p>";
                            } else if($metric->value > $metric->ceiling) {
                                $color = "bad";
                                $boxWarning = "boxwarning";
                                $metricWarning = "<p class=\"readingboxchange\"><img src=\"images/triangle-up-red.png\" /> too high</p>";
                            } else {
                                $color = "good";
                                $metricWarning = "<p class=\"readingboxchange\"></p>";
                                $boxWarning = "";
                            }
                        } else {
                            $color = "";
                            $metricWarning = "";
                            $boxWarning = "";
                        }

                        Echo "
                            <div class=\"box minibox {$boxWarning} hardborder\">
                                <span class=\"readingboxheader {$color}\">{$metric->value}</span><br />
                                <span class=\"readingboxname\">{$metric->shortName}</span>
                                {$metricWarning}
                            </div>
                            ";
                    }
                }
                Echo "</div>";
            }
        } else {
            Echo "<p style=\"font-style: italic; text-align: center;\">No metrics</p>";
        }
        
    }

    public function printEditBoxes(){
        if(!empty($this->metricList)){
            //For each unique date, echo any metrics that meet that date
            foreach($this->uniqueMetricDates as $date){
                $dateInt = strtotime($date);
                $deleteButton = "<button type = \"button\" class=\"button badgebutton red\" onclick=\"window.location.href = 'metric/cancel/{$this->metricList[0]->recordId}-{$dateInt}'\">Delete</button>";

                Echo "<h4 style=\"font-weight: bold;\">" . date("m/d/Y g:i a", strtotime($date)) . " {$deleteButton}</h4>
                <p class=\"smallinput\"><input name=\"{$dateInt}\" class=\"datetimepicker\" value=\"{$date}\"></p>";
                Echo "<div class=\"boxwrapper\">";
                foreach($this->metricList as $metric){
                    if ($metric->metricDate == $date){

                        Echo "
                        <div class=\"box minibox hardborder\">
                            <p><input name=\"{$metric->metricId}\" value=\"{$metric->value}\"></p>
                            <span class=\"readingboxname\">{$metric->shortName}</span>
                        </div>
                            ";
                    }
                }
                Echo "</div>";
            }
        } else {
            Echo "<p style=\"font-style: italic; text-align: center;\">No metrics</p>";
        }
        
    }

    public function printNewMetricBoxes(){
        $connect = Database::getInstance()->getConnection();

        $sql = "SELECT * FROM metriccodes WHERE status = 'Active'";
        $stmt = $connect->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll();

        if(!empty($row)){
            foreach ($row as $row){
                $metricCode = $row['metriccode'];
                $shortName = $row['shortname'];

                Echo "
                <div class=\"box minibox hardborder\">
                    <p><input name=\"{$metricCode}\" value=\"\"></p>
                    <span class=\"readingboxname\">{$shortName}</span>
                </div>
                    ";
            }
            
        }
    }
    
    private function getInterval(){
            //Sort in ascending order (most recent first)
            arsort($this->uniqueMetricDates);
            
            $firstDate = new DateTime(reset($this->uniqueMetricDates));
            $lastDate = new DateTime(end($this->uniqueMetricDates));

            $interval = $lastDate->diff($firstDate);
            $intervalDays = (int) $interval->format('%a');
            $intervalHours = (int) $interval->format('%H');
            $intervalMinutes = (int) $interval->format('%I');
            
            
            if($intervalDays == 1){
                $dayText = $intervalDays . " day, ";
            } else if($intervalDays > 1){
                $dayText = $intervalDays . " days, ";
            } else {
                $dayText = "";
            }

            if($intervalHours > 0 && $intervalMinutes < 1){
                $comma = "";
            } else {
                $comma = ",";
            }
 
            if($intervalHours == 1){
                $hourText = $intervalHours . " hour{$comma} ";
            } else if($intervalHours > 1){
                $hourText = $intervalHours . " hours{$comma} ";
            } else {
                $hourText = "";
            }

            if($intervalMinutes == 1){
                $minuteText = $intervalMinutes . " minute";
            } else if($intervalMinutes > 1){
                $minuteText = $intervalMinutes . " minutes";
            } else {
                if($intervalHours < 1){
                    $minuteText = "0 minutes";
                } else {
                    $minuteText = "";
                }
            }


            $this->intervalText = "{$dayText} {$hourText} {$minuteText}";
    }

    public function listInlineBoxes(){
        $fullList = "";

        if(!empty($this->metricList)){
            //Grab most recent date from uniqueMetricDates
            arsort($this->uniqueMetricDates);

            //Loop through and print if date = uniqueMetricDate
            $color = "";
            foreach($this->metricList as $metric){
                if($metric->metricDate == reset($this->uniqueMetricDates)){
                    if(is_numeric($metric->value)){
                        if($metric->value < $metric->floor || $metric->value > $metric->ceiling){
                            $color = "bad";
                            } else {
                                $color = "";
                            }
                    }

                    $fullList = $fullList . "<div class=\"metric-inline {$color}\">
                    <h4>{$metric->value}</h4>
                    <p>{$metric->shortName}</p>
                    </div>";
                }
                
            }
            
        }
        return $fullList;
        }
        
    }

?>