<?php
// Exit if accessed directly.
if(!defined('ABSPATH')) exit;

if(!class_exists('RTCORE_Redux')):

/**
 * RTCORE Redux Class.
 *
 * @class RTCORE_Redux
 * @version	1.0.0
 */
class RTCORE_Redux extends RTCORE_Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        add_filter('upload_mimes', array($this, 'upload_mimes'));
    }

    /**
     * Adds the appropriate mime types to WordPress
     * @param array $existing_mimes
     * @return array
     */
    function upload_mimes($existing_mimes = array())
    {
        $existing_mimes['redux'] = 'application/redux';
        return $existing_mimes;
    }
}

endif;