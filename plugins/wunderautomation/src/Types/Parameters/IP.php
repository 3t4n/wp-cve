<?php

namespace WunderAuto\Types\Parameters;

/**
 * Class IP
 */
class IP extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'general';
        $this->title       = 'remote_ip';
        $this->description = __('IP address of the remote client', 'wunderauto');
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
        $ret = '';
        $ret = isset($_SERVER['REMOTE_ADDR']) ?
            sanitize_text_field($_SERVER['REMOTE_ADDR']) :
            $ret;

        $ret = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ?
            sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']) :
            $ret;

        return $this->formatField($ret, $modifiers);
    }
}
