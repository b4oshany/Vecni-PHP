<?php
namespace libs\vecni;

class Object{
    /**
    * Set a new class variable for the current object
    *
    * @param string $name, name of the class variable to be set
    * @param string $value, value to be set to the new class variable
    */
    public function __set($name, $value) {
        $this->{$name} = $value;
    }

    /**
    * Get values of the object
    *
    * @return mixed, value that is stored in a class variable
    */
    public function __get($name) {
        if(isset($this->$name)){
            return $this->$name;
        }
        return null;
    }


    /**  As of PHP 5.1.0  */
    public function __isset($name){
        return isset($this->$name);
    }

    /**  As of PHP 5.1.0  */
    public function __unset($name){
        if(isset($this->$name))
            unset($this->$name);
    }

    public function to_array(){
        return get_object_vars($this);
    }

    /**
    * Cast an object to another class, keeping the properties, but changing the methods
    *
    * @param string $class_name - Class name
    * @param array $data - array to be converted to the current scoped object
    * @return object - object of the current scope
    */
    public static function object_cast(array $data){
        $object = new static();
        foreach($data as $key => $value){
            $object->$key = $data[$key];
        }
        return $object;
    }

    /**
    * Cast an object to another class, keeping the properties, but changing the methods
    *
    * @param string $class_name - Class name
    * @param array $data - array to be converted to the current scoped object
    * @return object - object of the current scope
    */
    public static function cast($class_name, $object){
        return unserialize(sprintf(
        'O:%d:"%s"%s',
        strlen($class_name),
        $class_name,
        strstr(serialize($object), ':')
    ));
    }

   /**
     * Cast an object to another class, keeping the properties, but changing the methods
     *
     * @param string $class  Class name
     * @param object $object
     * @return object
     */
    public function populate($object){
        foreach($object as $key => $value){
            $this->$key = $value;
        }
    }
}
?>
