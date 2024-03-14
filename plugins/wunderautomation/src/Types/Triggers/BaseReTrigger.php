<?php

namespace WunderAuto\Types\Triggers;

/**
 * Class BaseReTrigger
 */
class BaseReTrigger extends BaseTrigger
{
    /**
     * @param object $object
     *
     * @return array<string, \stdClass>|false
     */
    public function getObjects($object)
    {
        return [];
    }
}
