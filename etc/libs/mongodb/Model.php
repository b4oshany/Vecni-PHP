<?php
namespace libs\mongodb;
require ".autoload.php";
use libs\vecni\Object;


/**
* class Models
* @package mongodb
* @static setUp() - initiate the database connection for the current object
* @static collectionSettings() - initiate the settings and constraint on the current database collection
*/

abstract class Model extends Object{
    protected static $model;                            // current scoped collection
    protected static $mongodb;                          // mongodb connection
    public static $collection = "default";           // collection name
    public static $db = "vecni_init";
    public static $connection;
    protected $updates = array();

    /**
    * __callstatic is triggered when invoking inaccessible methods in an static context
    * This method will initiate the mongodb connection and select the desired database
    * @uses subclass - use to set collection in subclasses
    */
    public static function setUp(){
        if(!isset(self::$mongodb)){
            if(!isset(self::$connection)){
                self::$connection = new \MongoClient();
            }
            $db = self::$db;
            self::$mongodb = self::$connection->$db;
        }
    }

    /**
    * Set a new class variable for the current object
    * @param string $name, name of the class variable to be set
    * @param string $value, value to be set to the new class variable
    */
    public function __set($name, $value) {
        $this->$name = $value;
        if(isset($this->_id)){
            $this->updates[$name] = $value;
        }
    }

    /**
    * Save object data in mongodb or perform an update on the changes made
    * @return string|bool - document data id if the mongodb insertion was successful, else return false
    */
    public function save(){
        static::setUp();
        $data = get_object_vars($this);
        if(isset($this->_id)){
            $this->update(array("_id" => $this->_id), $this->updates);
            $this->updates = array();
        }else{
            unset($data["updates"]);
            if(static::$model->insert($data)){
                $this->id = (string) $data["_id"];
                return $this->id;
            }else{
                return false;
            }
        }
    }

    /**
    * Update the mongodb object data based on the query_for and updates
    * @param array $query_for - creteria to find the object to be updated
    * @param array $updates - mongodb data to be updated
    * @return bool - true if the update is successful else false
    */
    public function update(array $updates, array $query_for = null){
        static::setUp();
        if(!isset($query_for)){
            $query_for = array("_id"=>$this->get_MongoId());
        }
        if(static::$model->update($query_for, $updates)){
            return true;
        }
        return false;
    }

    /**
    * Update the mongodb object data based on the collection, query and updates.
    * @param string $collection - name of the collection to perform the updates on.
    * @param array $query - creteria to find the object to be updated.
    * @param array $updates - mongodb data to be updated.
    * @return bool - true if the update is successful else false
    */
    public static function update_for(string $collection, array $query, array $updates){
        if(self::$mongodb->$collection->update($query, $updates)){
            return true;
        }
        return false;
    }

    /**
    * Enforce data type or any additional constraint after the obejct has been
    * populated
    */
    public function enforce_constraints(){
        // data conversion and constraint
    }

    /**
    * Convert array of data to the object under the current scope
    * @param array $data - array to be converted to the current scoped object
    * @return object - object of the current scope
    */
    public static function load(array $data){
        $object = new static();
        $object->populate($data);
        $object->enforce_constraints();
        $object->id = (string) $object->_id;
        return $object;
    }

    /**
    * Find a list of model objects based on the query array given
    * @param array $query - mongodb query array for finding objects based on given creteria
    * @return array - array of objects based on the model being used based on the find query
    */
    public static function find($query = array(), array $projection = null){
        static::setUp();
        if(!empty($projection)){
            $objects_data = static::$model->find($query, $projection);
        }
        $objects_data = static::$model->find($query);
        $objects = array();
        if(!empty($objects_data)){
            foreach($objects_data as $object_data){
                $object = static::load($object_data);
                $object->id = (string) $object->_id;
                array_push($objects, $object);
            }
        }
        return $objects;
    }

    /**
    * Find a model objects based on the query array given
    * @param array $query - mongodb query array for finding objects based on given creteria
    * @return object - object of the current scope
    */
    public static function find_one(array $query, array $projection = null){
        static::setUp();
        if(!empty($projection)){
            $object = static::$model->findOne($query, $projection);
        }
        $object = static::$model->findOne($query);
        if(!empty($object)){
            return static::load($object);
        }
        return false;
    }

    /**
    * Get a model object based on the given mongodb object id
    * @param string $object_id, mongodb query array for finding object based on given univerity_id
    * @return array which consist of model data, else return false
    */
    public static function get_by_id($object_id){
        $object_data = static::find_by_id($object_id);
        if(!empty($object_data)){
            return static::load($object_data);
        }
        return false;
    }

    /**
    * Converts mongoid string to Mong ObjectId
    * @param string $object_id - mongodb query array for finding object based on given univerity_id
    * @return MongoId - Mongo ObjectId
    */
    public static function create_MongoId($object_id){
        return new \MongoId($object_id);
    }

    /**
    * Get the MongoId for the current or given id
    * @return MongoId - for the current object Mongo ObjectId
    */
    public function get_MongoId(){
        return static::create_MongoId($this->id);
    }

    /**
    * Create a DBRef for the current or given id
    * @param string $object_id - object id of the given mongodb document
    * @return MongoDBRef - for the current object Mongo ObjectId and collection
    */
    public static function create_DBRef(\MongoId $object_id){
        return \MongoDBRef::create(static::$collection, $object_id);
    }

    /**
    * Get the DBRef for the current or given id
    * @return MongoDBRef - for the current object Mongo ObjectId and collection
    */
    public function get_DBRef(){
        return \MongoDBRef::create(static::$collection, $this->get_MongoId());
    }

    /**
    * Create a new object with only the object id set.
    * @return Object - return the new created object.
    */
    public static function create_object(\MongoId $object_id){
        $object = new static();
        $object->id = (string) $object_id;
        return $object;
    }

    /**
    * Fetch the object data based on the id
    */
    public function get(){
        $data = static::find_by_id($this->id);
        $this->populate($data);
    }

    /**
    * Converts mongoid string to Mong ObjectId
    * @param string|MongoId $object_id - mongodb query array for finding object
    * based on given object id. if the object id is string it (will) be converted into a MongoId
    * else it will be used as how it is
    * @return MonogoObject - mongodb object data
    */
    public static function find_by_id($object_id){
        static::setUp();
        if($object_id instanceof \MongoId){
            $mongoid = $object_id;
        }else{
            $mongoid = static::create_MongoId($object_id);
        }
        return static::$model->findOne(array('_id'=> $mongoid));
    }
}
?>
