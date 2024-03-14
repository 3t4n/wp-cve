<?php
namespace WunderAuto\JSONPath\Filters;

/**
 * MIT License
 * Copyright (c) 2018 Flow Communications
 * https://github.com/FlowCommunications/JSONPath
 */

use WunderAuto\JSONPath\JSONPath;
use WunderAuto\JSONPath\JSONPathException;
use WunderAuto\JSONPath\JSONPathToken;

abstract class AbstractFilter
{
    /**
     * @var JSONPathToken
     */
    protected $token;

    /** @var  int */
    protected $options;

    /** @var  bool */
    protected $magicIsAllowed;

    public function __construct(JSONPathToken $token, $options = 0)
    {
        $this->token = $token;
        $this->options = $options;
        $this->magicIsAllowed = $this->options & JSONPath::ALLOW_MAGIC;
    }

    public function isMagicAllowed()
    {
        return $this->magicIsAllowed;
    }

    /**
     * @param $collection
     * @return array
     */
    abstract public function filter($collection);
}
