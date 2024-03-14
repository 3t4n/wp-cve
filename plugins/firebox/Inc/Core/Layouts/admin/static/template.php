<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}
$showcopyright  	  = $this->data->get('settings.showcopyright');
$navigation     	  = $this->data->get('navigation');
$current_page   	  = $this->data->get('current_page');
$plugin_version		  = $this->data->get('plugin_version');
$plugin_name		  = $this->data->get('plugin_name');
$plugin_slug		  = $this->data->get('plugin_slug');
$call_to_action_label = $this->data->get('call_to_action_label');
?>
<?php firebox()->renderer->admin->render('static/header'); ?>
<?php firebox()->renderer->admin->render('static/sidebar', [
	'navigation' => $navigation,
	'current_page' => $current_page,
	'call_to_action_label' => $call_to_action_label,
	'plugin_version' => $plugin_version,
	'plugin_name' => $plugin_name,
	'plugin_slug' => $plugin_slug
]); ?>
<?php firebox()->renderer->admin->render('static/content', [
	'showcopyright' => $showcopyright
]); ?>
<?php firebox()->renderer->admin->render('static/footer'); ?>