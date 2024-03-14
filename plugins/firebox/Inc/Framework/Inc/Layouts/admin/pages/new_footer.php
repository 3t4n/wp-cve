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
$plugin = $this->data->get('plugin', '');
$plugin_lc = strtolower($plugin);
$plugin_version = $this->data->get('plugin_version', '');
$show_copyright = $this->data->get('show_copyright', false);

$wp_directory_plugin_url = 'https://wordpress.org/support/plugin/' . esc_attr($plugin_lc);
$fp_plugin_page = esc_url(FPF_SITE_URL) . esc_attr($plugin_lc);
?>
<div class="flex flex-col gap-2 items-center text-gray-500 dark:text-grey-3 mt-10">
	<?php if ($show_copyright) { ?>
		<div class="flex gap-[2px] flex-wrap items-center hover:text-black dark:hover:text-white">
			<?php echo sprintf(fpframework()->_('FPF_LIKE_PLUGIN'), $plugin); ?>
			<a href="<?php echo esc_url($wp_directory_plugin_url); ?>/reviews/?filter=5#new-post" class="no-underline text-current" target="_blank"><?php echo esc_html(fpframework()->_('FPF_WRITE_REVIEW')); ?></a>
			<a href="<?php echo esc_url($wp_directory_plugin_url); ?>/reviews/?filter=5#new-post" target="_blank" class="flex gap-[2px] text-orange-400 no-underline text-base">
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
			</a>
		</div>
		<div class="flex gap-1 flex-wrap items-center">
			<a href="https://www.facebook.com/fireboxwp" target="_blank" class="no-underline text-current hover:text-black dark:hover:text-gray-300">
				<span class="dashicons dashicons-facebook-alt"></span>
			</a>
			<a href="https://www.twitter.com/fireboxwp" target="_blank" class="no-underline text-current hover:text-black dark:hover:text-gray-300">
				<span class="dashicons dashicons-twitter"></span>
			</a>
			<a href="https://www.linkedin.com/company/fireboxwp" target="_blank" class="no-underline text-current hover:text-black dark:hover:text-gray-300">
				<span class="dashicons dashicons-linkedin"></span>
			</a>
		</div>
	<?php } ?>
	<div class="footer-version"><?php echo esc_html($plugin); ?> v<?php echo esc_html($plugin_version); ?></div>
</div>