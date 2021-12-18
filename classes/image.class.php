<?php

class Image extends Attachment {

    const UPLOAD_DIRECTORY = "attachments";
    const WHITELIST = array("jpg","jpeg"); 
    
    public function __construct($imageId = NULL){
        parent::__construct($imageId);

        if(!empty($this->fileName)){
        //Generate thumbnail filename
        $this->thumbnail = "th_" . $this->fileName;
        }
        
    }
    
    private function resizeImage($sourceImage, $targetImage, $maxWidth, $maxHeight, $quality = 80)
    {
        // Obtain image from given source file.
        if (!$image = @imagecreatefromjpeg($sourceImage))
        {
            return false;
        }
    
        // Get dimensions of source image.
        list($origWidth, $origHeight) = getimagesize($sourceImage);
    
        if ($maxWidth == 0){
            $maxWidth  = $origWidth;
        }
    
        if ($maxHeight == 0){
            $maxHeight = $origHeight;
        }
    
        // Calculate ratio of desired maximum sizes and original sizes.
        $widthRatio = $maxWidth / $origWidth;
        $heightRatio = $maxHeight / $origHeight;
    
        // Ratio used for calculating new image dimensions.
        $ratio = min($widthRatio, $heightRatio);
    
        // Calculate new image dimensions.
        $newWidth  = (int) $origWidth  * $ratio;
        $newHeight = (int) $origHeight * $ratio;
    
        // Create final image with new dimensions.
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        imagejpeg($newImage, $targetImage, $quality);
    
        // Free up the memory.
        imagedestroy($image);
        imagedestroy($newImage);
    
        return true;
    }

    public function validImage($fileToUpload){
        //Restrict image to 5MB
        if ($fileToUpload["size"] > 5242880) {
            die("File is too large");
        }

        //Check extension
        $explode = explode(".", $fileToUpload["name"]);
        $extension = end($explode);

        if (!(in_array(strtolower($extension), Image::WHITELIST))) {
            die("Incorrect extension");
        }

        //If file has valid extension but is not valid image, error will be thrown. @ supresses error message
        if(@exif_imagetype($fileToUpload["tmp_name"]) == FALSE) {
            die ("Image is corrupt");
        }

        return true;
    }
    
    public function uploadImage(){
        $idNumber = new IdNumber();

        //Generate new filename
        $imageName = strtoupper($idNumber->random_text("alnum", 12));
        $fileName = $imageName . ".jpg";

        //Assign new locations
        $target_dir = Image::UPLOAD_DIRECTORY;
        $placeholderImage = "{$target_dir}/{$this->customerId}/{$this->linkId}/temp_{$fileName}";
        $newImage = "{$target_dir}/{$this->customerId}/{$this->linkId}/{$fileName}";
        $thumbnail = "{$target_dir}/{$this->customerId}/{$this->linkId}/th_{$fileName}";
        $this->tempFile = $this->fileToUpload["tmp_name"];

        //Create folder if it does not exist
        $folder = "{$target_dir}/{$this->customerId}/{$this->linkId}";
        if(!file_exists($folder)){
            mkdir($folder, 0755, TRUE);
        }

        //Check if new file name is already taken
        if (file_exists($newImage)) {
            Echo"Already exists";
            $imageName = strtoupper($idNumber->random_text("alnum", 12));
            $fileName = $imageName . ".jpg";
            $newImage = "{$target_dir}/{$this->customerId}/{$this->linkId}/{$fileName}";

            //Try again
            $placeholderImage = "{$target_dir}/{$this->customerId}/{$this->linkId}/temp_{$fileName}";
            $newImage = "{$target_dir}/{$this->customerId}/{$this->linkId}/{$fileName}";
            $thumbnail = "{$target_dir}/{$this->customerId}/{$this->linkId}/th_{$fileName}";
        }

        //Move file from tmp directory to image storage
        if (!move_uploaded_file($this->tempFile, $placeholderImage)) {
            die("Upload could not be completed");
        }

        //Resize original image to smaller filesize
        $this->resizeImage($placeholderImage, $newImage, 1500, 1500);

        //Resize thumbnail
        $this->resizeImage($placeholderImage, $thumbnail, 350, 350);

        //Delete original upload
        if(file_exists($placeholderImage)){
            unlink($placeholderImage);
        } 

        //Set values before saving
        $this->fileName = $fileName;
        $this->fileType = "Image";
        $this->status = "Active";

        //Save image to record
        $this->addImage();
    }

    private function addImage(){
        $sql = "INSERT INTO attachment 
        (attachmentid,linkid,customerid,filetype,filename,status,createdby,createddate) VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->attachmentId, $this->linkId, $this->customerId, $this->fileType,
        $this->fileName, $this->status, $this->createdBy, $this->createdDate]);
    }

    public function cancelImage(){
        $sql = "UPDATE attachment SET status = 'Cancelled', modifiedby = ?, 
        modifieddate = ? WHERE attachmentid = ?";

        $stmt = $this->connect->prepare($sql);
        $stmt->execute([$this->modifiedBy, $this->modifiedDate, $this->attachmentId]);
    }
}

?>