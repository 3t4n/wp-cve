<?php

namespace WunderAuto\Types\Parameters;

/**
 * Class SiteUrl
 */
class SiteUrl extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'general';
        $this->title       = 'site_url';
        $this->description = __('The Site address (URL)', 'wunderauto');
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
        return $this->formatField(get_bloginfo('url'), $modifiers);
    }
}
