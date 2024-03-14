<?php

namespace WunderAuto\Types\Internal;

class BaseState extends BaseInternalType
{
    /**
     * @var string
     */
    public $guid = '';

    /**
     * @var string
     */
    private $originalGuid;

    /**
     * @param \stdClass|array<int, mixed>|null $state
     */
    public function __construct($state = null)
    {
        $this->originalGuid = $this->generateGuid();
        $this->guid         = $this->originalGuid;

        parent::__construct($state);
    }

    /**
     * Was a new guid generated when this object was instantiated
     *
     * @return bool
     */
    public function newGuidCreated()
    {
        return $this->originalGuid === $this->guid;
    }

    /**
     * Create a unique guid
     *
     * @return string
     */
    private function generateGuid()
    {
        return substr(md5(uniqid() . microtime(true)), 0, 12);
    }
}
