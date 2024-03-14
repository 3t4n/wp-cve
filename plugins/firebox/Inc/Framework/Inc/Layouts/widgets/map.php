<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
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
$latitude = $this->data->get('latitude', '');
$longitude = $this->data->get('longitude', '');
if (empty($latitude) || empty($longitude))
{
	return;
}

if ($this->data->get('load_css_vars') && !empty($this->data->get('custom_css')))
{
	wp_register_style('fpframework-widget-custom-' . $this->data->get('id'), false, ['fpframework-widget']);
	wp_enqueue_style('fpframework-widget-custom-' . $this->data->get('id'));
	wp_add_inline_style('fpframework-widget-custom-' . $this->data->get('id'), $this->data->get('custom_css'));
}

$options = [
	'provider' => $this->data->get('provider'),
	'latitude' => $latitude,
	'longitude' => $longitude,
	'markers' => $this->data->get('markers'),
	'scale' => $this->data->get('scale'),
	'zoomControl' => $this->data->get('zoomControl'),
	'zoom' => $this->data->get('zoom')
];
?>
<div class="fpf-widget fpf-map-widget<?php echo esc_attr($this->data->get('css_class')); ?>" data-options="<?php esc_attr_e(wp_json_encode($options)); ?>"><div class="inner"></div></div>