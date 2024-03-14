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
?>
<div class="fpf-geo-lookup-wrapper">
	<div class="fpf-side-by-side-items">
		<div class="fpf-item">
			<input type="text" id="fpf-control-input-item_<?php echo esc_attr($this->data->get('name_key')); ?>" value="33.33.33.1" class="fpf-field-item fpf-control-input-item geoip-ip-address" />
		</div>
		<div class="fpf-item">
			<a href="#" class="GeoIpLookupButton fpf-button"><?php echo fpframework()->_('FPF_LOOKUP'); ?></a>
		</div>
		<input type="hidden" class="nonce_hidden" value="<?php echo wp_create_nonce( 'fpf-geo-lookup-nonce' ); ?>" />
	</div>
	<ul class="fpf-geoip-lookup-results" style="margin-top:20px; display:none;">
		<li><?php echo fpframework()->_('FPF_CONTINENT'); ?>: <span class="continent"></span></li>
		<li><?php echo fpframework()->_('FPF_COUNTRY'); ?>: <span class="country"></span></li>
		<li><?php echo fpframework()->_('FPF_COUNTRY_CODE'); ?>: <span class="country_code"></span></li>
		<li><?php echo fpframework()->_('FPF_CITY'); ?>: <span class="city"></span></li>
	</ul>
</div>