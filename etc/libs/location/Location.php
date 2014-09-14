<?php
namespace libs\location;
require_once ".autoload.php";
use configs\Vecni;
use libs\Model;
class Location extends Model{
    public $latitude;
    public $longitude;
    public $tags;
    public $street, $town, $parish, $country;
    public $location_for;

   public static function setUp(){
        parent::setUp();
        self::$model = self::$mongodb->location;//Need to create this db
    }

    public function update_address(){
        $data = get_object_vars($this);
        $updates = array();
        foreach($data as $key => value){
            if($value != "" ){
                $updates = array_push($updatess, array($key=>$value));
            }
        }
        if(self::$model->update(array("_id"=>$this->_id), array('$set'=>$updates))){
            return true;
        }else{
            return false;
        }
    }

    public function post_request_address_details(){
        if(!empty($_POST["street"])){
            $this->street = $_POST["street"];
        }
        if(!empty($_POST["town"])){
            $this->town = $_POST["town"];
        }
        if(!empty($_POST["parish"])){
            $this->parish = $_POST["parish"];
        }
        if(!empty($_POST["country"])){
            $this->country = $_POST["country"];
        }
    }

    public function post_request_cordinates(){
        if(!empty($_POST["latitude"])){
            $this->latitude = $_POST["latitude"];
        }
        if(!empty($_POST["longitude"])){
            $this->longitude = $_POST["longitude"];
        }
    }
}

Location::setUp();
?>
