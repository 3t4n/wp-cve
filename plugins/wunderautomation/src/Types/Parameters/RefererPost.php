<?php

namespace WunderAuto\Types\Parameters;

/**
 * Class RefererPost
 */
class RefererPost extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'general';
        $this->title       = 'referer_postid';
        $this->description = __('Referer post id', 'wunderauto');
        $this->objects     = '*';

        $this->usesDefault = false;
    }

    /**
     * @param object    $object
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($object, $modifiers)
    {
        $url = wp_get_referer();
        if ($url === false) {
            return '';
        }

        return $this->formatField(url_to_postid($url), $modifiers);
    }
}
