<?php

namespace Mrpvision\Gluu\Models;

class Collection {

    public $totalResults;
    public $itemsPerPage;
    public $startIndex;
    public $schemas = [];
    public $resources;
    private $type;

    public function __construct(array $collection,$type = 'USER') {
        $requiredKeys = ['totalResults', 'itemsPerPage', 'Resources', 'startIndex'];
        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $collection)) {
                throw new \Mrpvision\Gluu\Models\Exception\UserException(sprintf('missing key "%s"', $requiredKey));
            }
        }
        $this->type = $type;
        foreach ($collection as $key => $data) {
            $this->{'set' . ucfirst($key)}($data);
        }
//        $this->totalResults = $collection['totalResults'];
//        $this->itemsPerPage = $collection['itemsPerPage'];
//        $this->startIndex = $collection['startIndex'];
//        $this->setResources($collection['Resources']);
    }

    public static function fromJson($jsonString,$type) {
        $resourcesData = json_decode($jsonString, true);
        if (null === $resourcesData && JSON_ERROR_NONE !== json_last_error()) {
            $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : json_last_error();
            throw new \Mrpvision\Gluu\Models\Exception\UserException(sprintf('unable to decode JSON from storage: %s', $errorMsg));
        }

        return new self($resourcesData,$type);
    }

    private function setResources(array $resources = []) {
        foreach ($resources as $resource) {
            if($this->type == 'USER')
                $this->resources[] = User::map($resource);
        }
    }

    public function getResources() {
        return $this->resources;
    }

    public function getTotalResults() {
        return $this->totalResults;
    }

    public function getItemsPerPage() {
        return $this->itemsPerPage;
    }

    public function getStartIndex() {
        return $this->startIndex;
    }

    public function getSchemas() {
        return $this->schemas;
    }

    public function setTotalResults($totalResults) {
        $this->totalResults = $totalResults;
    }

    public function setItemsPerPage($itemsPerPage) {
        $this->itemsPerPage = $itemsPerPage;
    }

    public function setStartIndex($startIndex) {
        $this->startIndex = $startIndex;
    }

    public function setSchemas($schemas) {
        $this->schemas = $schemas;
    }

}

?>
