<?php

namespace Mrpvision\Gluu\Models;

class User {

    public $id = [];
    public $schemas = [];
    public $externalId;
    public $userName;
    public $name;
    public $displayName;
    public $nickName;
    public $profileUrl;
    public $emails;
    public $addresses;
    public $phoneNumbers = [];
    public $ims = [];
    public $userType;
    public $title;
    public $preferredLanguage;
    public $locale;
    public $active;
    public $password;
    public $roles = [];
    public $entitlements = [];
    public $extensionGluuUser;
    public $meta;

    public function __construct() {
        
    }

    public static function fromJson($jsonString) {
        $userData = json_decode($jsonString, true);
        if (null === $userData && JSON_ERROR_NONE !== json_last_error()) {
            $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : json_last_error();
            throw new \Mrpvision\Gluu\Models\Exception\UserException(sprintf('unable to decode JSON from storage: %s', $errorMsg));
        }
        return self::map($userData);
    }
    public static function map($jsonString) {
        $userData = $jsonString;
//        if (null === $userData && JSON_ERROR_NONE !== json_last_error()) {
//            $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : json_last_error();
//            throw new \Mrpvision\Gluu\Models\Exception\UserException(sprintf('unable to decode JSON from storage: %s', $errorMsg));
//        }
        $user = new User();
        $reflector = new \ReflectionClass(new self());
        $namespace = $reflector->getNamespaceName();
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($userData as $name => $data) {
            if ($user->is_sub_object($name)) {
                $user->{$name} = $user->{'get' . ucfirst($name) . 'Object'}($data);
            } elseif ($name == 'urn:ietf:params:scim:schemas:extension:gluu:2.0:User') {
                $user->extensionGluuUser = ExtensionGluuUser::map($data);
            } else {
                $user->{$name} = $data;
            }
        }
        return $user;
    }

    public function __call($name, $arguments) {
        
    }

    public function json() {
        return json_encode($this->arrayFromObject());
    }

    public function arrayFromObject() {
        $array_data = [];
        $reflector = new \ReflectionClass($this);
        $namespace = $reflector->getNamespaceName();
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $name) {
            $name = $name->name;
            if ($this->is_sub_object($name)) {

                $array_data[$name] = $this->{'get' . ucfirst($name) . 'Array'}();
            } elseif ($name == 'extensionGluuUser') {
                $array_data['urn:ietf:params:scim:schemas:extension:gluu:2.0:User'] = $this->extensionGluuUser->arrayFromObject();
            } else {
                $array_data[$name] = $this->{$name};
            }
        }
        return $array_data;
    }

    private function is_sub_object($name) {
        if (in_array($name, ['emails', 'name', 'groups']))//, 'name', 'phoneNumbers', 'addresses', 'groups'
            return true;
        return false;
    }

    private function getNameObject($name) {
        return Name::map($name);
    }

    private function getEmailsObject($emails) {
        $return = null;
        foreach ($emails as $email) {
            $return[] = Email::map($email);
        }
        return $return;
    }

    private function getGroupsObject($groups) {
        $return = null;
        foreach ($groups as $group) {
            $return[] = Group::map($group);
        }
        return $return;
    }

    private function getNameArray() {
        return ($this->name) ? $this->name->arrayFromObject() : null;
    }

    private function getEmailsArray() {
        $return = null;
        foreach ($this->emails as $email) {
            $return[] = ($email) ? $email->arrayFromObject() : null;
        }
        return $return;
    }

    private function getGroupsArray() {
        $return = null;
        foreach ($this->groups as $group) {
            $return[] = ($group) ? $group->arrayFromObject() : null;
        }
        return $return;
    }

    private function get_sub_object($namespace, $name, $data) {
        $return = null;
        if (class_exists($namespace . '\\' . ucwords($name))) {
            $class_name = $namespace . '\\' . ucwords($name);
            if (is_array($data)) {
                foreach ($data as $subdata)
                    $return[] = $class_name::map($subdata);
            } else {
                $return = $class_name::map($subdata);
            }
        }
        return $return;
    }

}

?>