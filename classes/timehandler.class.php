<?php

class TimeHandler {

    public function __construct($timePreference){

        $this->timeZone = $timePreference;

    }


    public function displayUserDate($inputTime){
        //Accept input in UTC and output in user's timezone
        $dateTime = new DateTime($inputTime, new DateTimeZone("UTC"));
        $dateTime->setTimezone(new DateTimeZone($this->timeZone));
        return $dateTime->format('Y-m-d');
    }

    public function displayUserTime($inputTime){
        //Accept input in UTC and output in user's timezone
        $dateTime = new DateTime($inputTime, new DateTimeZone("UTC"));
        $dateTime->setTimezone(new DateTimeZone($this->timeZone));
        return $dateTime->format('H:i:s');
    }

    public function displayUserDateTime($inputTime){
        //Accept input in UTC and output in user's timezone
        $dateTime = new DateTime($inputTime, new DateTimeZone("UTC"));
        $dateTime->setTimezone(new DateTimeZone($this->timeZone));
        return $dateTime->format('Y-m-d H:i:s');
    }

    public function convertToUTC($inputTime){
        //Accept input in UTC and output in user's timezone
        $dateTime = new DateTime($inputTime, new DateTimeZone($this->timeZone));
        $dateTime->setTimezone(new DateTimeZone("UTC"));
        return $dateTime->format('Y-m-d H:i:s');
    }

    public function printSecondTimer(){
        //Debug utility to print a simple MM:SS counter
        Echo"
        <script>
        var sec = 0;
        function pad ( val ) { return val > 9 ? val : \"0\" + val; }
        setInterval( function(){
            $(\"#seconds\").html(pad(++sec%60));
            $(\"#minutes\").html(pad(parseInt(sec/60,10)));
        }, 1000);
        </script>
        
        <span id=\"minutes\"></span>:<span id=\"seconds\"></span>";
    }
}

?>