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
<a href="#" class="fpf-button primary GeoIPUpdateDbButton" data-task="update"><i class="dashicons dashicons-image-rotate"></i><?php echo esc_html(fpframework()->_('FPF_GEOIP_UPDATE_DB')); ?></a>
<?php wp_nonce_field('fpf-geo-lookup-nonce', 'fpf-geo-lookup-nonce-name'); ?>