<?php

class Table {
    
    public function __construct(Array $headers, Array $records, String $alignmentGroup = NULL){
    //Accepts a multidimensional $headers array, array of $records objects, string of CSS classes to apply to each header (header:class format)
        $alignmentArray = array();

        if(!empty($alignmentGroup)){
            $alignments = explode(",", $alignmentGroup);

            foreach ($alignments as $alignment){
                $pieces = explode(":", $alignment);
                $alignmentArray[$pieces[0]] = $pieces[1];
            }
        }
        //Establish table
        echo "<table class=\"table\">";

        //Establish headers
        echo"<thead><tr>";
        foreach($headers as $header=>$headerCode){
            //Apply CSS class if given
            if(array_key_exists($headerCode, $alignmentArray)){
                $class = $alignmentArray[$headerCode];
            } else {
                $class = "";
            }

            echo "<th class=\"{$class}\">{$header}</th>";
        }
        echo "</tr></thead>
        <tbody>";

        //Create one row per object
        foreach($records as $record){
            echo"<tr>";
            //Loop through headers
                foreach($headers as $headerKey=>$headerValue){
                //Apply CSS class if given
                if(array_key_exists($headerValue, $alignmentArray)){
                    $class = $alignmentArray[$headerValue];
                } else {
                    $class = "";
                }
                
                //Check key against input records. If a value is found, display value. Otherwise, cell will be blank
                if(!empty($record->$headerValue)){
                    echo "<td class=\"{$class}\">{$record->$headerValue}</td>";
                } else {
                    echo "<td></td>";
                }
                }
            echo "</tr>";
        }

        echo "</tbody></table>";

    }

}
?>