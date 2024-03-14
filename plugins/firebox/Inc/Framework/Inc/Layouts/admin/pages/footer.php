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
<div class="fpf-admin-page-footer">
	<?php if ($show_copyright) { ?>
	<a href="https://www.fireplugins.com" class="logo" target="_blank" title="<?php echo esc_attr(fpframework()->_('FPF_GO_TO_FP_SITE')); ?>"><img src="<?php echo esc_url(FPF_MEDIA_URL . 'admin/images/logo.svg'); ?>" alt="FirePlugins logo"></a>
	<div class="creator"><?php echo esc_html(fpframework()->_('FPF_MADE_WITH_LOVE_BY_FP')); ?></div>
	<ul class="footer-nav">
		<?php do_action('fpframework/admin/template/footer/' . $plugin_lc . '/nav_links'); ?>
		<li><a href="<?php echo esc_url($fp_plugin_page); ?>/roadmap" target="_blank"><?php echo esc_html(fpframework()->_('FPF_ROADMAP')); ?></a></li>
		<li><a href="https://www.fireplugins.com/contact/" target="_blank"><?php echo esc_html(fpframework()->_('FPF_SUPPORT')); ?></a></li>
		<li><a href="https://www.fireplugins.com/docs/<?php echo esc_attr($plugin_lc); ?>" target="_blank"><?php echo esc_html(fpframework()->_('FPF_DOCS')); ?></a></li>
	</ul>
	<div class="footer-review">
		<?php echo sprintf(fpframework()->_('FPF_LIKE_PLUGIN'), $plugin); ?>&nbsp;<a href="<?php echo esc_url($wp_directory_plugin_url); ?>/reviews/?filter=5#new-post" class="text" target="_blank"><?php echo esc_html(fpframework()->_('FPF_WRITE_REVIEW')); ?></a> 
		<a href="<?php echo esc_url($wp_directory_plugin_url); ?>/reviews/?filter=5#new-post" target="_blank" class="stars">
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
		</a>
	</div>
	<ul class="footer-social">
		<li>
			<a href="https://www.facebook.com/fireboxwp" target="_blank">
				<span class="dashicons dashicons-facebook-alt"></span>
			</a>
		</li>
		<li>
			<a href="https://www.twitter.com/fireboxwp" target="_blank">
				<span class="dashicons dashicons-twitter"></span>
			</a>
		</li>
	</ul>
	<?php } ?>
	<div class="footer-version"><?php echo esc_html($plugin); ?> v<?php echo esc_html($plugin_version); ?></div>
</div>