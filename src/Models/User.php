<?php

namespace Mrpvision\Gluu\Models;

class User {

    public $id;
    public $schemas = [];
    public $externalId;
    public $userName;
    public $name;
    public $displayName;
    public $nickName;
    public $profileUrl;
    public $emails = [];
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
    public $groups;

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

    public static function map($userData) {
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

    public function arrayFromObject($full = true) {
        $array_data = [];
        $reflector = new \ReflectionClass($this);
        $namespace = $reflector->getNamespaceName();
        $properties = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $name) {
            $name = $name->name;
            if ($this->is_sub_object($name)) {

                $array_data[$name] = $this->{'get' . ucfirst($name) . 'Array'}($full);
            } elseif ($name == 'extensionGluuUser' and $this->extensionGluuUser) {
                $array_data[Constant::USER_EXTENSION_SCHEMA] = $this->extensionGluuUser->arrayFromObject();
            } elseif ($full || $this->{$name}) {
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
        $return = [];
        foreach ($emails as $email) {
            $return[] = Email::map($email);
        }
        return $return;
    }

    private function getGroupsObject($groups) {
        $return = [];
        foreach ($groups as $group) {
            $return[] = Group::map($group);
        }
        return $return;
    }

    private function getNameArray($full) {
        if ($this->name instanceof \Mrpvision\Gluu\Models\Name)
            return $this->name->arrayFromObject($full);
        return $this->name;
    }

    private function getEmailsArray($full) {
        $return = [];
        foreach ($this->emails as $email) {
            if ($email instanceof \Mrpvision\Gluu\Models\Email)
                $return[] = $email->arrayFromObject($full);
            else
                $return[] = $email;
        }
        return $return;
    }

    private function getGroupsArray($full) {
        $return = [];
        foreach ($this->groups as $key => $group) {
            if ($group instanceof \Mrpvision\Gluu\Models\Group)
                $return[] = ($group) ? $group->arrayFromObject($full) : null;
            else
                $return[] = $group;
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

    public static function fill($input) {
        $requiredKeys = [ 'first_name', 'sso_username', 'last_name', 'password', 'email', 'sso_group', 'kronos_username'];
        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $input)) {
                throw new \Mrpvision\Gluu\Models\Exception\UserException(sprintf('Missing key "%s" is required to fill user model.', $requiredKey));
            }
        }
        $user = new \Mrpvision\Gluu\Models\User();
        $schemas[] = \Mrpvision\Gluu\Models\Constant::USER_SCHEMA;
        $schemas[] = \Mrpvision\Gluu\Models\Constant::USER_EXTENSION_SCHEMA;
        $user->schemas = $schemas;
        $user->name = $input['first_name'];
        $user->userName = $input['sso_username'];
        $user->displayName = $input['first_name'] . ' ' . $input['last_name'];
        $user->nickName = $input['first_name'];
        $user->name = self::fill_name($input);
        $user->emails = self::fill_email($input);
        $user->groups = self::fill_group($input);
        $user->extensionGluuUser = self::fill_extention($input);
        $user->password = $input['password'];
        $user->preferredLanguage = "en-us";
        $user->locale = "en_US";
        $user->active = true;
        return $user;
    }

    private static function fill_name(array $input) {

        $name = new \Mrpvision\Gluu\Models\Name();
        $name->familyName = $input['last_name'];
        $name->givenName = $input['first_name'];
        $name->formatted = $input['first_name'] . ' ' . $input['last_name'];
        if (isset($input['middle_name'])) {
            $name->middleName = $input['middle_name'];
        }
        return $name;
    }

    private static function fill_email($input) {
        $email = new \Mrpvision\Gluu\Models\Email();
        $email->primary = true;
        $email->value = $input['email'];
        $email->type = 'other';
        $emails[] = $email;
        return $emails;
    }

    private static function fill_group($input) {
        if (is_array($input['sso_group'])) {
            foreach ($input['sso_group'] as $grp) {
                $group['value'] = $grp;
                $groups[] = $group;
            }
        } else {
            $group['value'] = $input['sso_group'];
            $groups[] = $group;
        }
        return $groups;
    }

    private static function fill_extention($input) {
        $extensionGluuUser = new \Mrpvision\Gluu\Models\ExtensionGluuUser();
        $extensionGluuUser->kronoscustomattribute = $input['kronos_username'];
        $extensionGluuUser->mobile = isset($input['mobile'])?$input['mobile']:'';
        $extensionGluuUser->phone_number_verified = isset($input['mobile_verified'])?$input['mobile_verified']:'';
        return $extensionGluuUser;
    }
    
    public function update(\Mrpvision\Gluu\GluuClient $gluu){
        return $gluu->updateUser($this->id, $this);
    }
    public function save(\Mrpvision\Gluu\GluuClient $gluu){
       return $gluu->CreateUser($this);
    }
    public function delete(\Mrpvision\Gluu\GluuClient $gluu){
       return $gluu->DeleteUser($this->id);
    }

}

?>