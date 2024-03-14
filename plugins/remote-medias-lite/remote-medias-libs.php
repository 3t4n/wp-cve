<?php
/*
Plugin Name: Remote Media Libraries
Plugin URI: https://www.onecodeshop.com/
Description: Integrates 3rd party media sources to WP media manager
Version: 1.6.3
Author: Team OneCodeShop.com
Author URI: https://www.onecodeshop.com/
*/

function ocsRmlPhpNotice()
{
    echo '<div class="message error"><p>'.sprintf(__('%s <strong>Requirements failed.</strong> PHP version must <strong>at least %s</strong>. You are using version '. PHP_VERSION, 'remote-medias-lite'), 'Remote Media Libraries', '5.3.3').'</p></div>';
}

function ocsRmlInit()
{
    require __DIR__.DIRECTORY_SEPARATOR.'bootstrap.php';
}
/*
 * Unfortunately, while grand daddy PHP 5.2 is still hanging around this check avoid PHP error upon PHP 5.2 activation.
 */
if (version_compare(PHP_VERSION, '5.3.3', '>=')) {
    add_action('plugins_loaded', 'ocsRmlInit', 5);
} else {
    add_action('admin_notices', 'ocsRmlPhpNotice');
}
