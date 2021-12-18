<?php

class MaterialList{
    public $materialList;

    public function __construct($linkId = NULL){
        if(is_null($linkId)){
            //Instantiate empty object but don't set any values yet
            return;
        } else {
        $lov = LOV::getInstance()->getLOV();
        $this->connect = Database::getInstance()->getConnection();

        //$sql = "SELECT * from material WHERE linkid = '$linkId' AND status ='Active'";

        $sql = "SELECT * FROM material WHERE linkid = ? AND status = 'Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$linkId]);
        $row = $stmt->fetchAll();

        if(!empty($row)){
            foreach ($row as $row){
                //Pull metric details
                $material = new Material();
                $material->materialId = $row['materialid'];
                $material->linkId = $row['linkid'];
                $material->materialName = $lov->fetchLOVLabel("MaterialName", $row['materialcode']);
                $material->materialCode = $row['materialcode'];
                $material->materialCategory = $row['materialcategory'];
                $material->materialUnit = $row['materialunit'];
                $material->materialQuantity = $row['materialquantity'];
                $material->materialCategoryName = $lov->fetchLOVLabel("MaterialCategory", $row['materialcategory']);
                $material->materialUnitName = $lov->fetchLOVLabel("MaterialUnit", $row['materialunit']);
                //$material->materialLocation = $lov->fetchLOVLabel("MaterialLocation", $row['materiallocation']);
                $material->status = $row['status'];
                $material->createdBy = $row['createdby'];
                $material->createdDate = $row['createddate'];
                
                //Add to array
                $this->materialList[] = $material;
            }
            return $this->materialList;
        } else {
            return null;
        }
    }
    }

    public function materialWidget(){
        $lov = LOV::getInstance()->getLOV();
        
        Echo"
        <div class=\"table\">
        <table class=\"table padded\" id=\"materialtable\">
        <thead>
            <tr>
                <th>Material</th>
                <th>Quantity</th>
                <th></th>
            </tr>
        </thead>
        <tbody>";
        
        if(!empty($this->materialList)){
        foreach($this->materialList as $material){
            Echo"<tr>
            <td>{$material->materialName}</td>
            <td>{$material->materialQuantity}</td>
            <td><button class=\"button badgebutton red\" onclick=\"window.location.href = '/material/delete/{$material->materialId}'\">Delete</button></td>
            </tr>";
        }
    }

        Echo' <tr>
        <td>
        <span id="addMaterialtext0" class="addMaterialtext"></span>
        <select name ="addMaterial[]" id="addMaterial0" class="js-example-basic-single">
        <option value=""></option>';

            $lov->lovDropdownOptions("MaterialName", "DEFAULT");
        Echo'</select></td>
            <td class="short-input">
            <span id="addQuantitytext0" class="addQuantitytext"></span>
            <input name="addQuantity[]" id="addQuantity0" value=""></td>
            <td class="short-input"></td>
        </tr>';

        Echo"</tbody>
        </table>
        <p class=\"text-center\"><button type=\"button\" id=\"insert-more\" value=\"Add More\" class=\"button\">Add More</button></p>
        </div>";

        Echo'<span id="output" hidden>0</span>
        <script>
        $(document).ready(function() {
        
        $("#insert-more").click(function (e) {
             
        var counter = $("#output").html();
        
        var quantity = $("#addQuantity"+counter+"").val();
        if ((quantity == "")) {
        e.preventDefault();
        $("#quantitywarning").empty();
        $("#quantitywarning").append("<br />Enter Quantity");
        
        } else {
        $("#quantitywarning").empty();
        }
        
        var chems = $("#addMaterial"+counter+"").val();
        if ((chems == "")) {
        e.preventDefault();
        $("#chemicalwarning").empty();
        $("#chemicalwarning").append("<br />Select Item Name");
        
        } else {
        $("#chemicalwarning").empty();
        
        if(chems != "" && quantity != ""){
        $("#chemicalwarning").empty();
        $( "#addMaterial"+counter+"" ).removeClass( "js-example-basic-single" )
        $( "#addMaterial"+counter+"" ).hide();
        $( "#addQuantity"+counter+"" ).hide();
        $( ".select2-selection--single").hide();
        
        $("#materialtable").each(function () {
        
        var itemname = $("#addMaterial"+counter+" option:selected").text();
        $( "#addMaterialtext"+counter ).replaceWith( itemname );
        
        var chemicalvalue = $("#addQuantity"+counter).val();
        $( "#addQuantitytext"+counter ).replaceWith( chemicalvalue );
        
        counter++;
        $("#materialtable > tbody:last-child").append(\'<tr><td><span id="addMaterialtext\'+counter+\'"></span><select name ="addMaterial[]" id="addMaterial\'+counter+\'" class="js-example-basic-single"><option value=""></option>'; $lov->lovDropdownOptions("MaterialName", "DEFAULT"); Echo'</select></td><td><span id="addQuantitytext\'+counter+\'"></span><input name="addQuantity[]" id="addQuantity\'+counter+\'" value=""></td><td></td></tr>\');
        $(".js-example-basic-single").select2({
            theme: "trident"
        });
        });
        $(\'#output\').html(function(i, val) { return val*1+1 });
        }
        } 
        
        });
        });
        
        </script>';
    }
        
}

?>