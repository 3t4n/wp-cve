<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/fdsus-global/wrapper-content-end.php.
 *
 * @package     FetchDesigns
 * @subpackage  Sign_Up_Sheets
 * @see         https://www.fetchdesigns.com/sign-up-sheets-pro-overriding-templates-in-your-theme/
 * @since       2.1.4 (plugin version)
 * @version     1.0.0 (template file version)
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$template = strtolower(get_option('template'));

switch ($template) {
    case 'twentytwenty':
        echo '</div><!-- .post-inner -->';
        break;

    // 3rd Party
    case 'virtue':
        echo '</div><!-- .col-lg-9 -->';
        break;
}