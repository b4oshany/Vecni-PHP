<?php
namespace controllers\user\property;

use libs\vecni\libs\NamespaceTrait;
use libs\mysql\PDOConnector;
use controllers\property\Property;
use controllers\user\User;

class ViewProperty extends Property{
  use NamespaceTrait;

  /** @var string database table name. */
  protected static $tb_name = "properties_viewed_by_users";

  /** @var int $property_id Id of property in the favourite list. */
  public $property_id;
  /** @var int $user_id Id of user. */
  public $user_id;
  /** @var int $count Overall number of viewed properties. */
  private $count;

  public function __construct($user_id, $property_id=null){
    $this->user_id = $user_id;
    $this->property_id = $property_id;
  }

  /**
   * Add a property to the list of property a user viewed.
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
    $sql = "INSERT INTO $table(user_id, property_id) values($user_id, $property_id) "
        ."ON DUPLICATE KEY UPDATE num_searches = num_searches + 1;";
    $stmt = PDOConnector::$db->prepare($sql);
    if($stmt->execute()){
      return true;
    }
    return false;
  }

  /**
   * Remove a property from the list of property a user viewed.
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
    $table = self::$tb_name;
    $sql = "DELETE FROM $table where user_id = $user_id and property_id = $property_id";
    $stmt = PDOConnector::$db->prepare($sql);
    if($stmt->execute()){
      return true;
    }
    return false;
   }

   /**
    * Get list of property a user viewed.
    * @param int $user_id Id of user.
    * @param int $limit Limit the number of properties to return.
    * @return self[] List of viewed properties.
    */
    public static function getAll($user_id=null, $limit=20){
        $table = self::$tb_name;
      $sql = "Select upf.*, pi.property_title, owner_id, profile_pic, first_name, "
      ."last_name, exhibition_cost, user_pic from $table as upf left join property_information as "
      ."pi on pi.property_id = upf.property_id where upf.user_id = $user_id limit $limit";
      $stmt = PDOConnector::$db->prepare($sql);
      if($stmt->execute()){
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $viewed = array();

        foreach($results as $row){
          $property = new self($row["user_id"], $row["property_id"]);
            
          $property->property_title = $row["property_title"];
          $property->profile_pic = $row["profile_pic"];
          $property->exhibition_cost = $row["exhibition_cost"];

          $owner = new User();
          $owner->owner_id = $row["owner_id"];
          $owner->user_id = $row["owner_id"];
          $owner->profile_pic = $row["user_pic"];
          $owner->first_name = $row["first_name"];
          $owner->last_name = $row["last_name"];

          $property->owner = $owner;
          array_push($viewed, $property);
        }
        return $viewed;
      }
      return false;
    }

    /**
     * Get the number of viewed properties for a user.
     * @return int Number of viewed properties.
     */
    public function count(){
      if(!empty($this->count))
        return $this->count;
      $table = self::$tb_name;
      $user_id = $this->user_id;
      $sql = "SELECT COUNT(property_id) as num_viewed from $table where user_id = $user_id";
      $stmt = PDOConnector::$db->prepare($sql);
      if($stmt->execute()){
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->count = $result["num_viewed"];
        return $this->count;
      }
      return 0;
    }

   /**
    * Get property.
    * @return controllers\property\Property The viewed property.
    */
  public function get_property(){
    return parent::get_by_id($this->property_id);
  }

}


?>