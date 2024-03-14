<?php
if (!defined('ABSPATH')) {
	exit;
}
?>

<div class="notice notice-error">
	<p>
		<?php echo ngg_download_gallery_external_link(
				sprintf(esc_html__('NextGEN Download Gallery requires PHP %1$s or higher; your website has PHP %2$s which is {{a}}old, obsolete, and unsupported{{/a}}.', 'nextgen-download-gallery'),
					esc_html(NGG_DLGALL_PLUGIN_MIN_PHP), esc_html(PHP_VERSION)),
				'https://www.php.net/supported-versions.php'
			); ?>
	</p>
	<p><?php printf(esc_html__('Please upgrade your website hosting. At least PHP %s is recommended.', 'nextgen-download-gallery'), '7.2'); ?></p>
</div>
