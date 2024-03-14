<?php

namespace WunderAuto\Types\Parameters;

/**
 * Class RefererUrl
 */
class RefererUrl extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'general';
        $this->title       = 'referer_url';
        $this->description = __('Referer URL', 'wunderauto');
        $this->objects     = '*';
    }

    /**
     * @param object    $object
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($object, $modifiers)
    {
        return $this->formatField(wp_get_referer(), $modifiers);
    }
}
