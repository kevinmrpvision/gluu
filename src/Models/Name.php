<?php 
namespace Mrpvision\Gluu\Models;

class Name {

    public $formatted;
    public $givenName;
    public $familyName;
    public $middleName;
    public $honorificPrefix;
    public $honorificSuffix;

//    public function __construct($name) {
//        foreach($name as $key=>$data){
//            $this->{$key} = $data;
//        }
//    }
    
    public function getGivenName() {
        return $this->givenName;
    }

    public function getFamilyName() {
        return $this->familyName;
    }

    public function getMiddleName() {
        return $this->middleName;
    }

    public function getHonorificPrefix() {
        return $this->honorificPrefix;
    }

    public function getHonorificSuffix() {
        return $this->honorificSuffix;
    }

    public function setGivenName($givenName) {
        $this->givenName = $givenName;
        return $this;
    }

    public function setFamilyName($familyName) {
        $this->familyName = $familyName;
        return $this;
    }

    public function setMiddleName($middleName) {
        $this->middleName = $middleName;
        return $this;
    }

    public function setHonorificPrefix($honorificPrefix) {
        $this->honorificPrefix = $honorificPrefix;
        return $this;
    }

    public function setHonorificSuffix($honorificSuffix) {
        $this->honorificSuffix = $honorificSuffix;
        return $this;
    }

public function __call($name, $arguments)
    {

    }
    
    public static function map($userData)
    {
        $name_obj = new Name();
        $reflector = new \ReflectionClass(new self());
        $namespace = $reflector->getNamespaceName();
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach($userData as $name=>$data){
            $name_obj->{$name} = $data;
        }
        return $name_obj;
    }
    
    public function arrayFromObject()
    {
        $array_data = [];
        $reflector = new \ReflectionClass($this);
        $namespace = $reflector->getNamespaceName();
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach($properties as $name){
            $name = $name->name;
            $array_data[$name] = $this->{$name};
        }
        return $array_data;
    }
}
