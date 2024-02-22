<?php
/* 
admin
HOW TO USE
 * 1) Create 2 array in your file $cropSizeArray and $resizeArray
 *      Note: You Should pass both array every time . You have no size then send it to blank
 * 
 * 2) Pass $imageMode in manageImage() function call. There are 3 mode: resize,crop and both,
 
 * 3) Declare 2 variable for watermark image $resizeImageWaterMarkLogo , $cropImageWatermarkLogo and
 pass in *   function , if you won't to add watermark in image then pass black variable.
 * 
 *  - $cropSizeArray -> This is declare with crop size in order of high to low size
 *                      Ex., $cropSizeArray = array(500 => 500, 200=>200); // (width => height)
 *  - $resizeArray   -> This is declare with resize width and height in order of high to low
 *                      Ex., $cropSizeArray = array(800 => 800, 600 => 600); // (width => height)
 * 
 *  - resize - Only resize image based on the given size in $resizeArray(You can set multiple size).
 *  - crop   - Only crop image based on the given size in $cropSizeArray(You can set multiple size).
 *  - both   - This mode work for both resize and crop.
 *             Note: You can set only one size for crop image in $cropSizeArray. 
 *                   If you want to crop multiple size image then use crop mode.
 * 
 * Function formate:
 manageImage(temp_image_name,destination,file_type,image_name,file_extansion,image_mode,crop_size_array,resize_array,rezise_image_watermark_logo,crop_image_watermark_logo)
 */

$resizedImageArray = array();
$croppedImageArray = array();
$resizeWaterMark = '';
$cropWatermarkLogo = '';

function manageImage($tempImageName,$path,$fileType,$imageName,$fileext,$imageMode,$cropSizeArray,$resizeArray,$resizeImageWaterMarkLogo,$cropImageWatermarkLogo)
{  
    //echo '</br>line 35 $fileType ::: '.$fileType;
    global $resizeWaterMark,$cropWatermarkLogo;
    $resizeWaterMark = $resizeImageWaterMarkLogo;
    $cropWatermarkLogo = $cropImageWatermarkLogo;
	//echo "<br>Image mode >> ".$imageMode;
    //echo"<br>Resize array >>";print_r($resizeArray);
    //echo"<br>Crop array >>";print_r($cropSizeArray);
	//exit;*/
    if($imageMode == 'resize')// both = crop and resize
    {
        $imageCount = 1;
        $keyArray = array_keys($resizeArray);
        foreach ($resizeArray as $resizeWidth => $resizeHeight)
        {;
            $fileName = $imageName;//time();
            $tempFilePath = $imageCount.'_';
            $filePath = $path.$tempFilePath.$fileName.$fileext;
            
            $isUploaded = resizeImage($resizeWidth, $resizeHeight,$tempImageName,$filePath,$fileType,$resizeImageWaterMarkLogo);
            if(!empty($resizeImageWaterMarkLogo))
            {
                if($imageCount == count($keyArray))
                    addWaterMarkLogo();
            }
            $imageCount++;
        } // End foreach
        
    }
    else if($imageMode == 'crop') // both = crop and resize
    {
       // echo "water mark logo :::: ".$cropImageWatermarkLogo;
        $k = 1;
        
        $keyArray = array_keys($cropSizeArray);
        foreach ($cropSizeArray as $croppedWidth => $croppedHeight)
        {
            $fileName = $imageName;//time();
            $tempFilePath = $k."_thumb"."_"; 
            $filePath = $path.$tempFilePath.$fileName.$fileext;
            $isUploaded = cropImage($croppedWidth,$croppedHeight,$tempImageName,$filePath,$fileType,$cropImageWatermarkLogo);
            
            if(!empty($cropImageWatermarkLogo))
            {
                //echo "</br>k ::::: ".$k.' ==== '.count($keyArray);
                if($k == count($keyArray))
                    addWaterMarkLogo();
            }
            $k++;
        } // End foreach
    }
    else if($imageMode == 'both')
    {
        $k = 1;
        $keyArray = array_keys($resizeArray);
        foreach ($resizeArray as $resizeWidth => $resizeHeight)
        {
            $fileName = $imageName;//time();
            $tempFilePath = $k.'_';
            $filePath = $path.$tempFilePath.$fileName.$fileext;
            $resizeFilePath = $filePath;
            $isUploaded = resizeImage($resizeWidth, $resizeHeight,$tempImageName,$filePath,$fileType,$resizeImageWaterMarkLogo);
            if($k == 1)
            {
                $fileName = $imageName;//time();
                $tempFilePath = $k."_thumb"."_";
                $filePath = $path.$tempFilePath.$fileName.$fileext;
                
                $cropKeyArray = array_keys($cropSizeArray);
                $croppedWidth = $cropKeyArray[0];
                $croppedHeight = $cropSizeArray[$croppedWidth];
                $isUploaded = cropImage($croppedWidth,$croppedHeight,$resizeFilePath,$filePath,$fileType,$cropImageWatermarkLogo);
            }
            //echo "<br>compare: $k == ".count($keyArray);
            
            if(!empty($resizeImageWaterMarkLogo) || !empty($cropImageWatermarkLogo))
            {
                if($k == count($keyArray))
                    addWaterMarkLogo();
            }
            $k++;
        } // End foreach
    }
    //echo " isUploaded :::: ".$isUploaded;
	
	//exit;
	return $isUploaded;
}

function resizeImage($resizeWidth, $resizeHeight,$tempImageName,$filePath,$fileType,$resizeImageWaterMarkLogo)
{ 
    //echo '</br>line 124 $fileType ::: '.$fileType;
    if($fileType == 'image/png')
        $imageQuality = 9;
    else
        $imageQuality = 40;
    
    //echo "type >> ".$fileType." quality >> ".$imageQuality;
    global $resizedImageArray;
    //echo "<br>========== Resize image function call: $filePath==========<br>";
    list($width, $height) = getimagesize($tempImageName);
    $originalImageWidth =  $width;
    $originalImageHeight =  $height;
    
    $widthDifference = $originalImageWidth - $resizeWidth;
    $percentage = ($widthDifference/$originalImageWidth) * 100;
    $width = round ($originalImageWidth - (($percentage * $originalImageWidth)/ 100));
    $height = round ($originalImageHeight - (($percentage * $originalImageHeight)/ 100));
		
    /* read binary data from image file */
    $imgString = file_get_contents($tempImageName);
     
    switch ($fileType)
    {
        case 'image/png':
            $image = imagecreatefrompng($tempImageName);
            break;
        case 'image/jpeg': 
            $image = imagecreatefromjpeg($tempImageName); //(image identfier,file_name,quality(0-100))
            break;
        case 'image/jpg':
            $image = imagecreatefromjpeg($tempImageName); //(image identfier,file_name,quality(0-100))
            break;
        case 'image/gif':
            $image = imagecreatefromgif($tempImageName);
            break;
        default:
            $image = imagecreatefromstring($imgString); // rerurn identifier representing a black image
            break;
    }
    
    $tmp = imagecreatetruecolor($resizeWidth, $height); // return iamge identifier
    $result = imagecopyresampled($tmp, $image,0, 0,0, 0,$resizeWidth, $height,$originalImageWidth, $originalImageHeight);
    
    /* Save image */
    switch ($fileType)
    {
        case 'image/png':
                imagepng($tmp, $filePath,$imageQuality);
                break;
        case 'image/jpeg':
                imagejpeg($tmp, $filePath,$imageQuality); //(image identfier,file_name,quality(0-100))
                break;
        case 'image/jpg':
                imagejpeg($tmp, $filePath,$imageQuality); //(image identfier,file_name,quality(0-100))
                break;
        case 'image/gif':
                imagegif($tmp, $filePath,$imageQuality);
                break;
        default:
                echo "Not Uploaded";
                exit;
                break;
    }
    //echo "at image function line 234";
    array_push($resizedImageArray,$filePath);
    //imagedestroy($tempImageName);
    return true;
}

function cropImage($croppedWidth,$croppedHeight,$tempImageName,$filePath,$fileType,$cropImageWatermarkLogo)
{ 
   //echo '</br>line 194 $fileType ::: '.$fileType;
	global $croppedImageArray;
	/*http://php.net/manual/en/function.imagepng.php*/
	if($fileType == 'image/png' )
            $imageQuality = 9;
	else
            $imageQuality = 40;

	list($width, $height) = getimagesize($tempImageName);
	$originalImageWidth =  $width;
	$originalImageHeight =  $height;

	//echo "<br> cropped width: $croppedWidth AND cropped height: $croppedHeight";
	//echo "<br> Image width: $originalImageWidth AND Image height: $originalImageHeight";
	
	/*if($originalImageWidth > $originalImageHeight)
	{
		$y_offset = 0;
		$x_offset = ($originalImageWidth - $originalImageHeight) / 2;
		$s_size = $originalImageWidth - ($x_offset * 2);
	}
	else
	{
		$x_offset = 0;
		$y_offset = ($originalImageHeight - $originalImageWidth) / 2;
		$s_size = $originalImageHeight - ($y_offset * 2);
	}
	// Source(src) and destination(dst) X & Y
	$dstX = $dstY = 0;
	$srcX = $x_offset;
	$srcY = $y_offset;

	// Source(src) and destination(dst) Width & Height
	/*$srcW = $srcH = $s_size;
	$dstW = $croppedWidth;
	$dstH = $croppedHeight;*/

	$croppedImage = imagecreatetruecolor($croppedWidth, $croppedHeight);
	$imgString = file_get_contents($tempImageName);

	switch ($fileType)
	{
		case 'image/jpeg':
			$image = imagecreatefromjpeg($tempImageName); //(image identfier,file_name,quality(0-100))
			break;
		case 'image/png':
			$image = imagecreatefrompng($tempImageName);
			break;
		case 'image/gif':
			$image = imagecreatefromgif($tempImageName);
			break;
		default:
			$image = imagecreatefromstring($imgString); // rerurn identifier  representing a black image
			break;
	}   

	//Syntax: bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
	// echo "<br> imagecopyresampled >> $dstX, $dstY ,$srcX ,$srcY ,$dstW ,$dstH ,$srcW ,$srcH";

	//imagecopyresampled ($croppedImage, $image, $dstX, $dstY ,$srcX ,$srcY ,$dstW ,$dstH ,$srcW ,$srcH);
	// Now actually apply the crop and resize!
	
	    $src_w = imagesx($image);
  		$src_h = imagesy($image);
	    $src_ratio = $src_w/$src_h;

		if ($croppedWidth/$croppedHeight > $src_ratio) 
		{
			$new_h = $croppedWidth/$src_ratio;
			$new_w = $croppedWidth;
		} 
		else
		{
			$new_w = $croppedHeight*$src_ratio;
			$new_h = $croppedHeight;
		}
		$x_mid = $new_w/2;
		$y_mid = $new_h/2;
	
		$srcX = ($x_mid-($croppedWidth/2));
		$srcY = ($y_mid-($croppedHeight/2));
	
	  $newpic = imagecreatetruecolor(round($new_w), round($new_h));
	  imagecopyresampled($newpic, $image, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);
	  $final = imagecreatetruecolor($croppedWidth, $croppedHeight);
	  imagecopyresampled($final, $newpic, 0,0,$srcX, $srcY, $croppedWidth, $croppedHeight,$croppedWidth, $croppedHeight);
        //  echo '</br> $fileType ::: '.$fileType;
	switch ($fileType)
	{
            case 'image/jpeg':
                    imagejpeg($final, $filePath,$imageQuality); //(image identfier,file_name,quality(0-100))
                    break;
            case 'image/jpg':
                imagejpeg($final, $filePath,$imageQuality); //(image identfier,file_name,quality(0-100))
                break;
            case 'image/png':
                    $return = imagepng($final, $filePath,$imageQuality);
                    break;
            case 'image/gif':
                    imagegif($final, $filePath,$imageQuality);
                    break;
            default:
                echo "Not Uploaded";
                exit;
                break;
	}

	array_push($croppedImageArray ,$filePath);
	return true;
    
} // cropImage function end

function addWaterMarkLogo()
{
    //echo "inside watermark logo";
    global $resizedImageArray,$resizeWaterMark,$croppedImageArray,$cropWatermarkLogo;
    
    for($i = 0; $i < count($resizedImageArray); $i++)
    {
        $filePath = $resizedImageArray[$i];
        $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION)); //image/jpg';
        list($width, $height) = getimagesize($filePath);
        createImageWithWatermark($fileType,$filePath,$resizeWaterMark,$width,$height);
    }
    for($j = 0; $j < count($croppedImageArray); $j++)
    {
        $filePath = $croppedImageArray[$j];
        $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION)); //image/jpg';
        list($width, $height) = getimagesize($filePath);
        createImageWithWatermark($fileType,$filePath,$cropWatermarkLogo,$width,$height);
    }
    return true;
}

//https://www.sujeetkrsingh.com/php-script-to-add-watermark-to-all-images-in-a-folder
function createImageWithWatermark($fileType,$filePath,$waterMarkImage,$width,$height)
{
    if($fileType == 'png' )
        $imageQuality = 9;
    else
        $imageQuality = 40;
    /*echo "file type::".$fileType;
    echo "<br> file path::".$filePath;
    echo "<br>water mark image :::: ".$waterMarkImage;
    echo "<br>image width::".$width;
    echo "<br>image height::".$height."<br>";*/
//    exit;
    //Creating an image object of watermark image
    $watermark=imagecreatefrompng($waterMarkImage);
    
    list($watermarkImageWidth, $watermarkImageHeight) = getimagesize($waterMarkImage);
    
    //Margin of watermark from right and bottom of the main image
    /* For watermark middle in image */
    $margin_right = ( $width / 2 ) - ($watermarkImageWidth / 2);    
    $margin_bottom = ( $height / 2) - ($watermarkImageHeight / 2);

    //Height ($sy) and Width ($sx) of watermark image
    $sx = imagesx($watermark);
    $sy = imagesy($watermark);
    
    //Create image object of main image
    if($fileType == 'jpg' || $fileType == 'jpeg')
    {
        $img = imagecreatefromjpeg($filePath);
    }
    else if($fileType == 'png')
    {
        $img = imagecreatefrompng($filePath);
    }
    else if($fileType == 'gif')
    {
        $img = imagecreatefromgif($filePath);
    }
    //$img = imagecreatefromjpeg($filePath);
	
	//echo "<br> image val::".$img;
	//echo "<br> water mark::".$watermark;
    //Copying watermark image into the main image
    $flagVal = imagecopy($img, $watermark, imagesx($img) - $sx - $margin_right, imagesy($img) - $sy - $margin_bottom, 0, 0, $sx, $sy);
    //imagecopy($img, $watermark, (imagesx($img)) - $sx - $margin_right, (imagesy($img)) - $sy - $margin_bottom, 0, 0, $sx, $sy);

    //echo "<br> watewr mark flag::".$flagVal."<br>";
    
    //Saving the merged image into the destination folder
    if($fileType == 'jpg' || $fileType == 'jpeg')
    {
        imagejpeg($img, $filePath,$imageQuality);
    }
    else if($fileType == 'png')
    {
        imagepng($img, $filePath,$imageQuality);
    }
    else if($fileType == 'gif')
    {
        imagegif($img, $filePath,$imageQuality);
    }
    
    //Destroying the main image object
    imagedestroy($img);
}


function getImageOrientation($imageOrientation, $uploadedFileName, $binaryData)
{
	$data = $binaryData;
	
	if(isset($imageOrientation) && ($imageOrientation!='')) 
	{
		  //echo "oreintation=>".$imageOrientation."<br>";
		  //exit;
		  if($imageOrientation != 1)
		  {

			//echo "<br>inside if orientation val=>".$imageOrientation;
			
			//case '0':"Undefined";
			//case '1':"Top-Left";
			//case '2':"Top-Right";
			//case '3':"Bottom-Right";
			//case '4':"Bottom-Left";
			//case '5':"Left-Top";
			//case '6':"Right-Top";
			//case '7':"Right-Bottom";
			//case '8':"Left-Bottom";
			
			$img = imagecreatefromjpeg($data);
			//$img = imagecreatefromjpg($data);
			$deg = 0;
			switch ($imageOrientation)
			{

			  case 3:
				$deg = 180;//ORIENTATION_BOTTOMRIGHT,180 rotate left
				break;
			  case 6:
				$deg = 270;//ORIENTATION_RIGHTTOP,90 rotate right
				//echo "deg value=>".$deg;
				break;
			  case 8:
				$deg = 90;//ORIENTATION_LEFTBOTTOM,90 rotate left
				break;
			}
			//echo"<br>deg value=>".$deg;
			//exit;
			if ($deg)
			{
			  $img = imagerotate($img, $deg, 0);       
			  //echo "image value=>".$img;

			  //exit;
			}

			// then rewrite the rotated image back to the disk as $filename
			imagejpeg($img, $uploadedFileName, 95);
			
			//echo"<br>File Move successfully";
		 } // if there is some rotation necessary  
		 else
		 {
			/*$full_path = file_put_contents('review_image/'.$lastInsertedReviewId.'/'.$imgaeName, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data)));
					
			$respons['ACK']   = 'SUCCESS';
			$respons['imageName'] = $imgaeName;*/
		 }
	}
}
?>
