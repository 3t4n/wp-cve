<?php

namespace WunderAuto\Types\Parameters\Data;

use Exception;
use WunderAuto\JSONPath\JSONPath;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class JsonParser
 */
class JsonParser extends BaseParameter
{
    /**
     * @var object|null
     */
    private $data = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->title       = 'Json';
        $this->group       = 'internal';
        $this->description = __('Internal json parser', 'wunderauto');
        $this->objects     = '*';
    }

    /**
     * @param string $rawJson
     *
     * @return void
     */
    public function setRawJson($rawJson)
    {
        $this->data = json_decode($rawJson);
    }

    /**
     * @param object    $object
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($object, $modifiers)
    {
        $path = isset($modifiers->path) ? $modifiers->path : null;
        if (!$path) {
            return false;
        }
        $jsonPath = new JSONPath($this->data);
        try {
            $result = $jsonPath->find($path)->data();
        } catch (Exception $e) {
            return false;
        }

        return $jsonPath->getFirstElement($result);
    }
}
