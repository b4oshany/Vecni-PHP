<?php
namespace libs\scifile\handler;
use libs\scifile\File;

class ImageUpload{
    protected $image, $accept_types, $type, $min_size, $max_size;
    public $fullsize, $thumbnail;

    /**
    * @param string $upload_file - the name of image being uploaded.
    * @param string $rename_to - rename the uploaded to the image to the name given.
    *   If no rename string was given, then do not perform the renaming of the uploaded image.
    * @param array $accept_types - list of accepted types.
    * @param int $min_size - the accepted minimuim size of images.
    * @param int $max_size - the accepted maximuim size of images.
    */
    public function __construct($upload_file, $rename_to = false,
     $accept_types = array(".gif", ".jpeg", ".jpg", ".png"),
     $min_size = 0, $max_size = 5000000){
        $this->image = $upload_file;
        $this->accept_types = $accept_types;
        $this->min_size = $min_size;
        $this->max_size = $max_size;
        if($rename_to != false){
            $this->image["name"] = GDImage::renameImage($this->image["name"], $rename_to);
        }
        $this->type = strtolower(strrchr($upload_file['name'], '.'));
    }

    /**
    * Check if the type of uploaded image is accepted based on its type.
    * @return bool - true if type uploaded image type is ok, else false.
    */
    private function accept_type(){
        $type = $this->type;
        $accept_types = $this->accept_types;
        if(in_array($type, $accept_types)){return true;}else{return false;}
    }

    /**
    * Check if the type of uploaded image is accepted based on its size.
    * @return bool - true if type uploaded image size is ok, else false.
    */
    private function accept_size(){
        $image = $this->image;
        $max = $this->max_size;
        $min = $this->min_size;
        return ($image["size"] < $max && $image["size"] > $min)? true:false;
    }

    /**
    * Check if the type of uploaded image has any error.
    * @return bool - true if type uploaded image has error, else false.
    */
    private function error_check(){
        $image = $this->image;
        return ($image["error"] > 0)? true : false;
    }

    /**
    * Process the uploaded image and create a thumbnail image, where applicable.
    * @param string $dir - directory to save the uploaded image to.
    * @param bool $create_thumbnail - create a thumbnail image if true.
    * @param int $thmb_height - height of the thumbnail image.
    * @param int $thmb_width - width of the thumbnail image.
    */
    public function process_upload($dir = '', $create_thumbnail = false, $thmb_height = 200, $thmb_width = 200){
        $type_status = $this->accept_type();
        $size_status = $this->accept_size();
        if($size_status && $type_status && File::mkdir($dir)){
            $img = ($dir == '')? ''.$dir.$this->image['name'] : ''.$dir.'/'.$this->image['name'];
            if(move_uploaded_file($this->image['tmp_name'], $img)){
                if($create_thumbnail){
                    $this->thumbnail = GDImage::create_thumbnail($img, $dir, $thmb_width, $thmb_height);
                }
                $this->fullsize = $img;
                return  'success';
            }else{
                return 'failed';
            }
        }else{
            return (!$size_status)? 'invalid size '.$this->accept_size() :'invalid type';
        }
    }
}

?>

