<?php

namespace WunderAuto\Types\Parameters;

/**
 * Class DateTime
 */
class DateTime extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'general';
        $this->title       = 'datetime';
        $this->description = __('Date and time for when the trigger is fired', 'wunderauto');
        $this->objects     = '*';

        $this->usesDateFormat = true;
    }

    /**
     * @param object    $object
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($object, $modifiers)
    {
        $date = $this->formatDate(time(), $modifiers);
        return $this->formatField($date, $modifiers);
    }
}
