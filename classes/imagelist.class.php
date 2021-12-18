<?php

class ImageList {
    //pulls all image attachments associated with linkid
    //Attachments stored in /attachments/linkid/ directory

    public function __construct($linkId = NULL){
        $this->connect = Database::getInstance()->getConnection();

        $sql = "SELECT * from attachment WHERE linkid = ? AND filetype = 'Image' AND status = 'Active' ORDER BY createddate ASC";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$linkId]);
        $row = $stmt->fetchAll();

        if(!empty($row)){
            foreach ($row as $row){
                $image = new Image();
                $image->attachmentId = $row["attachmentid"];
                $image->linkId = $row["linkid"];
                $image->customerId = $row["customerid"];
                $image->fileName = $row["filename"];

                //Generate thumbnail filename
                $image->thumbnail = "th_" . $image->fileName;
                
                //Add to array
                $this->imageList[] = $image;
            }
            return $this->imageList;
        } else {
            return null;
        }
    }

    public function printImages(){
        $count = 0;
        Echo"<div class=\"box smallbox photobox\">
        <div class=\"boxcontent\"><p>";

    if(!empty($this->imageList)){
        foreach($this->imageList as $image){
            if($count == 0){
                Echo"<p><a href=\"download/image/{$image->attachmentId}\"><img src=\"/attachments/{$image->customerId}/{$image->linkId}/{$image->thumbnail}\" class=\"servicephoto\" title=\"Download\"></a></p>";
                $count++;
            } else {
                Echo"<a href=\"download/image/{$image->attachmentId}\"><img src=\"/attachments/{$image->customerId}/{$image->linkId}/{$image->thumbnail}\" class=\"servicephoto secondaryphoto\" title=\"Download\"></a> ";
            }
        }
    } else {
        Echo"<p><img src=\"/images/image-placeholder.png\" class=\"servicephoto\"></p>";
    }

        Echo"</p></div>
        </div>";
    }

    public function printEditImages(){
        Echo "<div class=\"boxwrapper\">";

        if(!empty($this->imageList)){
            foreach($this->imageList as $image){
                Echo "
                <div class=\"box minibox hardborder\">
                <img src=\"attachments/{$image->customerId}/{$image->linkId}/{$image->fileName}\" class=\"servicephoto secondaryphoto\">
                <p><b>Added:</b> {$image->createdDate}</p>
                <p><button type=\"button\" class=\"button badgebutton red\" onclick=\"window.location.href = 'image/cancel/{$image->attachmentId}'\">Delete</button></p>
                </div>";
            }
        } else {
            Echo"<p class=\"text-center text-italic\">No images</p>";
        }

        Echo"</div>";

    }
}

?>