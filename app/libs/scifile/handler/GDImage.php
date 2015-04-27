<?php
/**
* Image procesing, resizing and croping.
* Images can be renamed, copied, create a thumbnail (resized) version as well as changing
* pixel density.
*
* @author Jarrod Oberto
* @editor Oshane Bailey
* @version 1.0
* @see "http://php.net/manual/en/book.image.php" Requires GD Library (installed by default
* in PHP5)
*/

#  Date:      17-Jan-10
#  Purpose:   Resizes and saves image
#  Usage Example:
#                     include("../../classes/resize_class.php");
#                     $resizeObj = new resize('images/cars/large/input.jpg');
#                     $resizeObj -> resizeImage(150, 100, 0);
#                     $resizeObj -> saveImage('images/cars/large/output.jpg', 100);
namespace libs\scifile\handler;


/**
* Resize, crop and process iamge.
*/
class GDImage    {
    /** @var string $image Path to image. */
    private $image;
    /** @var string $width Current width of image. */
    private $width;
    /** @var string $height Current height of image. */
    private $height;
    /** @var string $modImage The path to the modified image. */
    private $modImage;
    /** @var string $extension Image extension. */
    private $extension;
    /** @var string $name Image name. */
    private $name;
    /** @var string $dir Image director */
    private $dir;

    /**
    * Get the image and its information.
    *
    * @param string $fileName File name or path.
    */
    function __construct($fileName)
    {
        // *** Open up the file
        $this->extension = strtolower(strrchr($fileName, '.'));
        $this->name = $this->getImageName($fileName);
        $this->image = $this->openImage($fileName);

        // *** Get width and height
        $this->width  = imagesx($this->image);
        $this->height = imagesy($this->image);
    }

    /**
    * Open the image.
    *
    * @param string @file File path.
    * @return Image.
    */
    private function openImage($file)
    {
        // *** Get extension
        $extension = $this->extension;

        switch($extension)
        {
            case '.jpg':
            case '.jpeg':
            $img = @imagecreatefromjpeg($file);
            break;
            case '.gif':
            $img = @imagecreatefromgif($file);
            break;
            case '.png':
            $img = @imagecreatefrompng($file);
            break;
            default:
            $img = false;
            break;
        }
        return $img;
    }

    /**
    * Rename the image.
    *
    * @param string $fileName Image name.
    * @param string $newName Intended name of the imamge.
    * @return string File path of the renamed image.
    */
    public static function renameImage($fileName, $newName){
        $extension = strtolower(strrchr($fileName, '.'));
        if(strripos($fileName,'/') != false){
            $dir = substr($fileName, strripos($fileName,'/'));
            $name = $dir.''.$newName.''.$extension;
        }else{
            $name = $newName.''.$extension;
        }
        return $name;
    }

    /**
    * Get the image name.
    *
    * @param string $fileName Image name.
    * @return string Image name without its extension.
    */
    public function getImageName($fileName){
        if(strripos($fileName,'/') != false){
            $name = substr($fileName, strripos($fileName,'/')+1, (strlen($fileName) - strripos($fileName,'.') + 1));
        }else{
            $name = substr($fileName, 0,  strripos($fileName,'.'));
        }
        return $name;
    }

    /**
    * Resize the image.
    * 
    * @param int $newWidth The desired width of the image.
    * @param int $newHeight The desired height of the image.
    * @param string $option The type of resizing to be done. The resized options are as
    * followed:
    *   exact - resize image to the exact desired height and width without any further modifications.
    *   protraint - resize image based on the width only, the height is fixed.
    *   landscape - resize image based on the height only, the width is fixed.
    *   crop - crop the image to the desired size.
    *   auto - get the optimal resize image without losing much quality.     
    */
    public function resizeImage($newWidth, $newHeight, $option="auto")
    {
        // *** Get optimal width and height - based on $option
        $optionArray = $this->getDimensions($newWidth, $newHeight, $option);

        $optimalWidth  = $optionArray['optimalWidth'];
        $optimalHeight = $optionArray['optimalHeight'];


        // *** Resample - create image canvas of x, y size
        $this->modImage = imagecreatetruecolor($optimalWidth, $optimalHeight);
        imagecopyresampled($this->modImage, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);


        // *** if option is 'crop', then crop too
        if ($option == 'crop') {
            $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
        }
    }

    /**
    * Get the desired dimensions of the image based on the desired height and width.
    * 
    * @param int $newWidth The desired width of the image.
    * @param int $newHeight The desired height of the image.
    * @param string $option The type of resizing to be done.
    * @uses self::getSizedFixedHeight() to get the optimal height.
    * @uses self::getSizedFixedWidth() to get the optimal width.
    * @uses self::getOptimalCropt() to get the optimal cropped dimension.
    * @uses self::resizeImage() to resize the image.
    */
    private function getDimensions($newWidth, $newHeight, $option)
    {

        switch ($option)
        {
            case 'exact':
            $optimalWidth = $newWidth;
            $optimalHeight= $newHeight;
            break;
            case 'portrait':
            $optimalWidth = $this->getSizeByFixedHeight($newHeight);
            $optimalHeight= $newHeight;
            break;
            case 'landscape':
            $optimalWidth = $newWidth;
            $optimalHeight= $this->getSizeByFixedWidth($newWidth);
            break;
            case 'auto':
            $optionArray = $this->getSizeByAuto($newWidth, $newHeight);
            $optimalWidth = $optionArray['optimalWidth'];
            $optimalHeight = $optionArray['optimalHeight'];
            break;
            case 'crop':
            $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
            $optimalWidth = $optionArray['optimalWidth'];
            $optimalHeight = $optionArray['optimalHeight'];
            break;
        }
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    /**
    * Get the width of the image if the width is fixed.
    *
    * @param int $newHeight Image width.
    * @return int New image width.
    */
    private function getSizeByFixedHeight($newHeight)
    {
        $ratio = $this->width / $this->height;
        $newWidth = $newHeight * $ratio;
        return $newWidth;
    }

    /**
    * Get the height of the image if the width is fixed.
    *
    * @param int $newWidth Image width.
    * @return int New image height.
    */
    private function getSizeByFixedWidth($newWidth)
    {
        $ratio = $this->height / $this->width;
        $newHeight = $newWidth * $ratio;
        return $newHeight;
    }

    /**
    * Get the optimal size of the image.
    *
    * @param int $newWidth Desired image width.
    * @param int $newHeight Desired image height.
    * @return int[] The optimal image demension.
    * @uses self::getSizedFixedHeight() to get the optimal height.
    * @uses self::getSizedFixedWidth() to get the optimal width.
    */
    private function getSizeByAuto($newWidth, $newHeight)
    {
        if ($this->height < $this->width)
            // *** Image to be resized is wider (landscape)
        {
            $optimalWidth = $newWidth;
            $optimalHeight= $this->getSizeByFixedWidth($newWidth);
        }
        elseif ($this->height > $this->width)
            // *** Image to be resized is taller (portrait)
        {
            $optimalWidth = $this->getSizeByFixedHeight($newHeight);
            $optimalHeight= $newHeight;
        }
        else
            // *** Image to be resizerd is a square
        {
            if ($newHeight < $newWidth) {
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
            } else if ($newHeight > $newWidth) {
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
            } else {
                // *** Sqaure being resized to a square
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
            }
        }

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    /**
    * Get the optimal level of cropping an image.
    * 
    * @param int $newWidth Desired image width.
    * @param int $newHeight Desired image height.
    * @return int[] The optimal image dimension when cropped.
    */
    private function getOptimalCrop($newWidth, $newHeight)
    {

        $heightRatio = $this->height / $newHeight;
        $widthRatio  = $this->width /  $newWidth;

        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }

        $optimalHeight = $this->height / $optimalRatio;
        $optimalWidth  = $this->width  / $optimalRatio;

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    /**
    * Crop a image to the optimal size.
    *
    * @param int $optimalWidth The optimal width of the image.
    * @param int $optimalHeight The optimal height of the image.
    * @param int $newWidth Image width.
    * @param int $newHeight Image height.
    */
    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
    {
        // *** Find center - this will be used for the crop
        $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
        $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );

        $crop = $this->modImage;
        //imagedestroy($this->modImage);

        // *** Now crop from center to exact requested size
        $this->modImage = imagecreatetruecolor($newWidth , $newHeight);
        imagecopyresampled($this->modImage, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
    }

    /**
    * Save the new image.
    *
    * @param string $dir Directory to save the image in.
    * @param string $imageQuality The desired amount of image quality to rename in the image.
    * @return string Path of the saved image.
    */
    public function saveImage($dir = '' , $imageQuality="100")
    {
        $savePath = $dir.'/'.$this->name.'-resized'.$this->extension;
        // *** Get extension
        $extension = $this->extension;

        switch($extension)
        {
            case '.jpg':
            case '.jpeg':
            if (imagetypes() & IMG_JPG) {
                imagejpeg($this->modImage, $savePath, $imageQuality);
            }
            break;

            case '.gif':
            if (imagetypes() & IMG_GIF) {
                imagegif($this->modImage, $savePath);
            }
            break;

            case '.png':
            // *** Scale quality from 0-100 to 0-9
            $scaleQuality = round(($imageQuality/100) * 9);

            // *** Invert quality setting as 0 is best, not 9
            $invertScaleQuality = 9 - $scaleQuality;

            if (imagetypes() & IMG_PNG) {
                imagepng($this->modImage, $savePath, $invertScaleQuality);
            }
            break;

            // ... etc

            default:
            // *** No extension - No save.
            break;
        }

        imagedestroy($this->modImage);
        return $savePath;
    }

    /**
    * Create an image thumbnail.
    *
    * @param string $img Image path.
    * @param string $dir Directory to save the image.
    * @param int $width The desired thumbnail width.
    * @param int $height The desired thumbnail height.
    * @param string $resize The type of resizing to be done. The resized options are as
    * followed:
    *   exact - resize image to the exact desired height and width without any further modifications.
    *   protraint - resize image based on the width only, the height is fixed.
    *   landscape - resize image based on the height only, the width is fixed.
    *   crop - crop the image to the desired size.
    *   auto - get the optimal resize image without losing much quality.
    * @uses self::resizeImage() to resize the image.
    */
    public static function createThumbnail($img, $dir = '', $width = 200, $height = 200, $resize = 'crop'){
        // *** 1) Initialise / load image
        $resizeObj = new self($img);
        // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
        $resizeObj -> resizeImage($width, $height, $resize);
        // *** 3) Save image
        return $resizeObj -> saveImage($dir, 100);
    }


    ## --------------------------------------------------------
}
?>
