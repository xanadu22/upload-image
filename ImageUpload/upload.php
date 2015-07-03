<!DOCTYPE html>
<html>
    <head></head>
    <body> 
        <?PHP
        
        // Establish some constants.
        $MAX_FILE_SIZE = 2000000;
        $MAX_FILE_SIZE_STR = '2MB';
        $ACCEPTED_FILE_TYPES = array(IMAGETYPE_JPEG, IMAGETYPE_PNG);
        $ACCEPTED_FILE_TYPES_STR = 'JPG, PNG';
        $IMG_FRAME_WIDTH = 400;
        $IMG_FRAME_HEIGHT = 300;
        $IMG_FRAME_ASPECT = $IMG_FRAME_WIDTH / $IMG_FRAME_HEIGHT;
        
        // Get the file name and some info.
        $tempFileLocation = $_FILES['uploadedFile']['tmp_name'];
        $imageInfo = getimagesize($tempFileLocation);
        
        // Verify that the file size is less than 2mb.
        $fileSize = $_FILES['uploadedFile']['size'];
        if ($fileSize > $MAX_FILE_SIZE) {
            echo "<p>File is too large.</p>";
            echo "<p>Maximum accepted file size is {$MAX_FILE_SIZE_STR}.</p>";
            exit();
        }

        // Verify that the file type is jpeg or png.
        $imageFileType = $imageInfo[2];
        if (!in_array($imageFileType, $ACCEPTED_FILE_TYPES)) {
            echo "<p>Invalid file type.</p>";
            echo "<p>Accepted file types are: {$ACCEPTED_FILE_TYPES_STR}</p>";
            exit();
        }

        // Move the file to the upload location.
        $targetFileName = $_FILES['uploadedFile']['name'];
        $targetFileLocation = "uploads/{$targetFileName}";
        $moveSuccess = move_uploaded_file($tempFileLocation, $targetFileLocation);
        if (!$moveSuccess) {
            echo "<p>An error occurred while uploading the file.</p>";
            exit();
        }

        // Fit the image inside a 400x300 frame, but keep the aspect ratio.
        // Note: I chose to expand smaller images to fit the frame.
        $imageWidth = $imageInfo[0];
        $imageHeight = $imageInfo[1];
        $imageAspectRatio = $imageWidth / $imageHeight;

        if ($imageAspectRatio >= $IMG_FRAME_ASPECT) {
            $imageSizeAttr = "width='$IMG_FRAME_WIDTH'";
        } elseif ($imageAspectRatio < $IMG_FRAME_ASPECT) {
            $imageSizeAttr = "height='$IMG_FRAME_HEIGHT'";
        }

        $imageFrame = <<<HTML
                <div style='width:{$IMG_FRAME_WIDTH}px; max-width:{$IMG_FRAME_WIDTH}px; height:{$IMG_FRAME_HEIGHT}px; border-style:solid;'>
                <img src='$targetFileLocation' alt='Uploaded Image' {$imageSizeAttr}>
                </div>
HTML;
        echo $imageFrame;

        ?>
    </body>
</html>
