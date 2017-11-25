<?php
namespace Mrpvision\Gluu\Models;

class Im {

    private $value;
    private $type;
    
    public function __construct($ims) {
        foreach($ims as $key=>$data){
            $this->{'set'.ucfirst($key)}($data);
        }
    }
    
    public function getValue() {
        return $this->value;
    }

    public function getType() {
        return $this->type;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }
public function __call($name, $arguments)
    {

    }

}
