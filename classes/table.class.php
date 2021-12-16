<?php

class Table {
    
    public function __construct(Array $headers, $records, $alignmentGroup = NULL){
        //Accepts a multidimensional $headers array, array of $records objects, string of CSS classes to apply to each header (header:class format)
        if(is_null($records)){
            $records = array();
        }

        //Breaks collapsed CSS into individual classes
        $alignmentArray = array();
        if(!empty($alignmentGroup)){
            $alignments = explode(",", $alignmentGroup);

            foreach ($alignments as $alignment){
                $pieces = explode(":", $alignment);
                $alignmentArray[$pieces[0]] = $pieces[1];
            }
        }

        //Create table
        Echo "<table class=\"table padded sortable\">";

        //Generate headers
        Echo"<thead><tr>";
        foreach($headers as $header=>$headerCode){
            //Apply CSS class if given
            if(array_key_exists($headerCode, $alignmentArray)){
                $class = $alignmentArray[$headerCode];
            } else {
                $class = "";
            }

            Echo "<th class=\"{$class}\">{$header}</th>";
        }
        Echo "</tr></thead><tbody>";

        if(count($records) < 1){
            $colspan = count($headers);
            Echo"
            <tr>
                <td colspan={$colspan} style=\"font-style: italic; text-align: center;\">
                    No results found
                </td>
            </tr>";
        }
        //Generate one row per object
        foreach($records as $record){
            Echo"<tr>";
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
                    Echo "<td class=\"{$class}\">{$record->$headerValue}</td>";
                } else {
                    Echo "<td></td>";
                }
                }
            Echo "</tr>";
        }

        Echo "</tbody></table>";

    }

}
?>