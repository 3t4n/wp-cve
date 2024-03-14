<?php
/**
 * (Orphan) Plugin Usage Tracking
 *
 * Borrowed from WordPress SEO.
 *
 * This file is temporarily disconnected. I was doing it wrong, and it's not mission critical at the moment while
 * I pursue a stable version of this plugin. All related files and functions have been condensed into this file until
 * such time as it's ready to be deployed again.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */

function ldl_tracking_additions($options) {

    $options['directory_lite'] = array(
        'directory_page'         => ldl()->get_option('directory_page'),
        'disable_bootstrap'      => ldl()->get_option('disable_bootstrap'),
        'google_maps'            => ldl()->get_option('google_maps'),
        'submit_use_tos'         => ldl()->get_option('submit_use_tos'),
        'submit_use_locale'      => ldl()->get_option('submit_use_locale'),
        'submit_locale'          => ldl()->get_option('submit_locale'),
        'submit_require_address' => ldl()->get_option('submit_require_address'),
    );

    return $options;
}

add_filter('lite_tracking_filters', 'ldl_tracking_additions');


