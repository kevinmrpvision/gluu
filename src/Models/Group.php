<?php

/**
 * Created by PhpStorm.
 * User: darrell.breeden
 * Date: 4/26/2017
 * Time: 10:22 AM
 */

namespace Mrpvision\Gluu\Models;

/**
 * Class Group
 * @package shairozan\sciph
 * @property array $schemas URI / URNs of schemas applicable to this object
 * @property $id GUID / UID of this object
 * @property string $displayName The Viewable / Human Readable name of this object
 * @property array $members Array of GroupMember objects
 * @property Meta $meta Meta data for this object
 * @property array $users Array of User Objects associated with this group. Facilitates programming
 */
class Group {

    public $schemas;
    public $id;
    public $displayName;
    public $members;
    public $meta;
    public $users;
    public $value;

    public static function map($userData) {
        $name_obj = new Group();
        $reflector = new \ReflectionClass(new Group());
        $namespace = $reflector->getNamespaceName();
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($userData as $name => $data) {
            $name_obj->{$name} = $data;
        }
        return $name_obj;
    }

    public function arrayFromObject($full = true) {
        $array_data = [];
        $reflector = new \ReflectionClass($this);
        $namespace = $reflector->getNamespaceName();
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $name) {
            $name = $name->name;
            if ($full || $this->{$name}) {
                $array_data[$name] = $this->{$name};
            }
        }
        return $array_data;
    }

}
