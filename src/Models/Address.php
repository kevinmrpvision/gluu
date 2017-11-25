<?php

namespace Mrpvision\Gluu\Models;

class Address {

    private $type;
    private $streetAddress;
    private $locality;
    private $region;
    private $postalCode;
    private $country;
    private $formatted;
    private $primary;

    public function __construct(array $address) {
        foreach ($address as $key => $data) {
            $this->{'set' . ucfirst($key)}($data);
        }
    }

    public function getType() {
        return $this->type;
    }

    public function getStreetAddress() {
        return $this->streetAddress;
    }

    public function getLocality() {
        return $this->locality;
    }

    public function getRegion() {
        return $this->region;
    }

    public function getPostalCode() {
        return $this->postalCode;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getFormatted() {
        return $this->formatted;
    }

    public function getPrimary() {
        return $this->primary;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setStreetAddress($streetAddress) {
        $this->streetAddress = $streetAddress;
        return $this;
    }

    public function setLocality($locality) {
        $this->locality = $locality;
        return $this;
    }

    public function setRegion($region) {
        $this->region = $region;
        return $this;
    }

    public function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function setCountry($country) {
        $this->country = $country;
        return $this;
    }

    public function setFormatted($formatted) {
        $this->formatted = $formatted;
        return $this;
    }

    public function setPrimary($primary) {
        $this->primary = $primary;
        return $this;
    }

    public function __call($name, $arguments) {
        
    }

}

?>
