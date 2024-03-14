<?php

namespace WunderAuto\Types\Parameters;

class ProParameter extends BaseParameter
{
    /**
     * @var bool
     */
    public $isPro = true;

    /**
     * @var bool
     */
    public $usesUrlEncode = false;

    /**
     * @var bool
     */
    public $usesEscapeNewLines = false;

    /**
     * @var bool
     */
    public $usesDefault = false;

    /**
     * @param object    $object
     * @param \stdClass $modifiers
     *
     * @return null
     */
    public function getValue($object, $modifiers)
    {
        return null;
    }
}
