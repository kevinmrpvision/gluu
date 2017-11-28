<?php
namespace Mrpvision\Gluu\Models;
class ExtensionGluuUser {

    public  $kronoscustomattribute;

    public static function map($mapdata)
    {
        $obj = new ExtensionGluuUser();
        $reflector = new \ReflectionClass(new ExtensionGluuUser());
        $namespace = $reflector->getNamespaceName();
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach($mapdata as $name=>$data){
            $obj->{$name} = $data;
        }
        return $obj;
    }
    
    public function arrayFromObject($full = true)
    {
        $array_data = [];
        $reflector = new \ReflectionClass($this);
        $namespace = $reflector->getNamespaceName();
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach($properties as $name){
            $name = $name->name;
            if($full || $this->{$name}){
                $array_data[$name] = $this->{$name};
            } 
        }
        return $array_data;
    }
}
