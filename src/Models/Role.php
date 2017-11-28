<?php

namespace Mrpvision\Gluu\Models;

class Role {

    private $value;

    public function __construct() {
        
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function __call($name, $arguments) {
        
    }

}
