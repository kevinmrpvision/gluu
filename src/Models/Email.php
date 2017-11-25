<?php
namespace Mrpvision\Gluu\Models;

 class Email {
     
    public $value;

    public $type;

    public $primary;

//    public function __construct(array $emails) {
//        foreach($emails as $key=>$data){
//            $this->{'set'.ucfirst($key)}($data);
//        }
//    }
    
    public function getValue() {
        return $this->value;
    }

    public function getType() {
        return $this->type;
    }

    public function getPrimary() {
        return $this->primary;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setPrimary($primary) {
        $this->primary = $primary;
        return $this;
    }
public function __call($name, $arguments)
    {

    }
public static function map($jsonString)
    {
        $userData = $jsonString;
//        if (null === $userData && JSON_ERROR_NONE !== json_last_error()) {
//            $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : json_last_error();
//            throw new \Mrpvision\Gluu\Models\Exception\UserException(sprintf('unable to decode JSON from storage: %s', $errorMsg));
//        }
        $email = new Email();
        $reflector = new \ReflectionClass(new self());
        $namespace = $reflector->getNamespaceName();
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach($userData as $name=>$data){
            $email->{$name} = $data;
        }
        return $email;
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
