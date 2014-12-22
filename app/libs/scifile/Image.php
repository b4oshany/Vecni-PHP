<?php
namespace libs\scifile;

class Image extends File{
    public $image_id;
    public $description;
    public $category;
    public $title;
    public $date_created;
    public $thumb_url;
    public $photo_url;

    private $thumbnail_extension = "-resized";

    public function get_location($thumbnail=false){
        if($thumbnail)
            return $this->thumb_url;
        return $this->photo_url;
    }

    public function get($format="xml"){
        $thumbnail = $this->get_location(true);
        $fullsize = $this->get_location(false);
        $name = $this->name;
        $title = $this->title;
        switch($format){
            default:
                return "<img src='$thumbnail' data-target='$fullsize' alt='$title' title='$title' />";
        }
    }
}

?>
