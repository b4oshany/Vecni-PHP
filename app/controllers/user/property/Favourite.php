<?php
namespace controllers\user\property;

use libs\vecni\libs\NamespaceTrait;
use libs\mysql\PDOConnector;
use controllers\property\Property;

class Favourite{
  use NamespaceTrait;

  /** @var string database table name. */
  protected static $tb_name = "users_favourite_properties";

  /** @var int $property_id Id of property in the favourite list. */
  public $property_id;
  /** @var int $user_id Id of user. */
  public $user_id;
  /** @var int $count Overall number of favourite properties. */
  private $count;

  public function __construct($user_id, $property_id=null){
    $this->user_id = $user_id;
    $this->property_id = $property_id;
  }

  /**
   * Add a property to a user's favourites list.
   * @param int $property_id Id of property.
   * @return boolean True if addition was successful else false.
   */
  public function add($property_id=null){
    if(empty($property_id)){
       if(empty($this->property_id)){
          return false; 
       }
       $property_id = $this->property_id;
    }
    $table = self::$tb_name;
    $user_id = $this->user_id;
    $sql = "INSERT INTO $table(user_id, property_id) values($user_id, $property_id)";
    $stmt = PDOConnector::$db->prepare($sql);
    if($stmt->execute()){
      return true;
    }
    return false;
  }

  /**
   * Remove a property from a user's favourites list.
   * @param int $property_id Id of property.
   * @return boolean True if addition was successful else false.
   */
   public function remove($property_id=null){
    if(empty($property_id)){
       if(empty($this->property_id)){
          return false; 
       }
       $property_id = $this->property_id;
    }
    $user = $this->user;
    $sql = "DELETE FROM $table where user_id = $user_id and property_id = $property_id";
    $stmt = PDOConnector::$db->prepare($sql);
    if($stmt->execute()){
      return true;
    }
    return false;
   }

   /**
    * Get favourite properties.
    * @param int $user_id Id of user.
    * @param int $limit Limit the number of properties to return.
    * @return self[] List of favourite properties.
    */
    public static function getAll($user_id, $limit=20){
      $sql = "Select upf.*, pi.property_title, owner_id, profile_pic, first_name, "
      ."last_name, user_pic from $table as upf left join property_information as "
      ."pi on pi.property_id = upf.property_id where upf.user_id = $user_id limit $limit";
      $stmt = PDOConnector::$db->prepare($sql);
      if($stmt->execute()){
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $favourites = array();

        foreach($results as $row){
          $favourite_property = self($row["user_id"], $row["property_id"]);

          $property = new Property($row["property_id"]);
          $property->property_title = $row["property_title"];
          $property->profile_pic = $row["profile_pic"];

          $owner = new User();
          $owner->owner_id = $data["owner_id"];
          $owner->user_id = $data["owner_id"];
          $owner->profile_pic = $data["user_pic"];
          $owner->first_name = $data["first_name"];
          $owner->last_name = $data["last_name"];

          $favourite_property->owner = $owner;
          $favroute_property->property = $property;
          array_push($favourites, $favourite_property);
        }
        return $favourites;
      }
      return false;
    }

    /**
     * Get the number of favourite property for a user.
     * @return int Number of favourite properties.
     */
    public function count(){
      if(!empty($this->count))
        return $this->count;
      $table = self::$tb_name;
      $user_id = $this->user_id;
      $sql = "SELECT COUNT(property_id) as num_favourites from $table where user_id = $user_id";
      $stmt = PDOConnector::$db->prepare($sql);
      if($stmt->execute()){
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->count = $result["num_favourites"];
        return $this->count;
      }
      return 0;
    }

   /**
    * Get property.
    * @return controllers\property\Property The favoured property.
    */
  public function get_property(){
    return Property::get_by_id($this->property_id);
  }

}


?>