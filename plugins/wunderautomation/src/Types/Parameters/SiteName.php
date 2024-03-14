<?php

namespace WunderAuto\Types\Parameters;

/**
 * Class SiteName
 */
class SiteName extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'general';
        $this->title       = 'site_name';
        $this->description = __('Site name', 'wunderauto');
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
        return $this->formatField(get_bloginfo('name'), $modifiers);
    }
}
