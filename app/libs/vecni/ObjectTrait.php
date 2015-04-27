<?php
namespace libs\vecni;

trait ObjectTrait{
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

    /**
    * Set a new class variable for the current object
    *
    * @param string $name, name of the class variable to be set
    * @param string $value, value to be set to the new class variable
    */
    public static function setStatic($name, $value) {
        static::$$name = $value;
    }

    /**
    * Get values of the object
    *
    * @return mixed, value that is stored in a class variable
    */
    public static function getStatic($name) {
        if(isset(static::$$name)){
            return static::$$name;
        }
        return null;
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
    public static function quick_cast(array $data, $strictness = false){
        $object = new static();
        foreach($data as $key => $value){
            if(!$strictness || property_exists($object, $key))
                $object->$key = $value;
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
    public function populate($object, $strictness = false){
        foreach($object as $key => $value){
            if(!$strictness || property_exists($this, $key))
                $this->$key = $value;
        }
    }
    
    /**
    * Get function parameters.
    * @param string $funcName Function name.
    * @return string[] List of parameter names.
    */
    public static function get_params($funcName) {
        $f = new \ReflectionFunction($funcName);
        $result = array();
        foreach ($f->getParameters() as $param) {
            $result[] = $param->name;   
        }
        return $result;
    }
}
?>
