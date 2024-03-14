<?php
if (! class_exists('KcSeoOutput')):
	class KcSeoOffer {
		public function __construct() {
			add_action(
				'admin_init',
				function () {
					$current = time();
					if (mktime(0, 0, 0, 11, 17, 2022) <= $current && $current <= mktime(0, 0, 0, 12, 3, 2022)) {
						if (get_option('kcseo_bf_2022') != '1') {
							if (! isset($GLOBALS['kcseo_bf_2022_notice'])) {
								$GLOBALS['kcseo_bf_2022_notice'] = 'kcseo_bf_2022';
								self::notice();
							}
						}
					}
				}
			);
		}

		/**
		 * Undocumented function.
		 *
		 * @return void
		 */
		public static function notice() {
			add_action(
				'admin_enqueue_scripts',
				function () {
					wp_enqueue_script('jquery');
				}
			);

			add_action(
				'admin_notices',
				function () {
					global $KcSeoWPSchema;

					$plugin_name   = 'WP SEO Structured Data Schema Pro';
					$download_link = 'https://wpsemplugins.com/downloads/wordpress-schema-plugin/'; ?>
					<div class="notice notice-info is-dismissible" data-kcseodismissable="kcseo_bf_2022"
						style="display:grid;grid-template-columns: 100px auto;padding-top: 25px; padding-bottom: 22px;">
						<img alt="<?php echo esc_attr($plugin_name); ?>"
							src="<?php echo $KcSeoWPSchema->assetsUrl . 'images/icon-128x128.png'; ?>" width="74px"
							height="74px" style="grid-row: 1 / 4; align-self: center;justify-self: center"/>
						<h3 style="margin:0;"><?php echo sprintf('%s Black Friday Deal!!', $plugin_name); ?></h3>

						<p style="margin:0 0 2px;">
							<?php echo esc_html__("Don't miss out on our biggest sale of the year! Get your.", 'wp-seo-structured-data-schema'); ?>
							<b><?php echo esc_html($plugin_name); ?> plan</b> with <b>FLAT 50% OFF</b>! Limited time offer expires on December 2.
						</p>

						<p style="margin:0;">
							<a class="button button-primary" href="<?php echo esc_url($download_link); ?>" target="_blank">Buy Now</a>
							<a class="button button-dismiss" href="#">Dismiss</a>
						</p>
					</div>
						<?php
				}
			);

			add_action(
				'admin_footer',
				function () {
					?>
					<script type="text/javascript">
						(function ($) {
							$(function () {
								setTimeout(function () {
									$('div[data-kcseodismissable] .notice-dismiss, div[data-kcseodismissable] .button-dismiss')
										.on('click', function (e) {
											e.preventDefault();
											$.post(ajaxurl, {
												'action': 'kcseo_dismiss_admin_notice',
												'nonce': <?php echo json_encode(wp_create_nonce('kcseo-dismissible-notice')); ?>
											});
											$(e.target).closest('.is-dismissible').remove();
										});
								}, 1000);
							});
						})(jQuery);
					</script>
						<?php
				}
			);

			add_action(
				'wp_ajax_kcseo_dismiss_admin_notice',
				function () {
					check_ajax_referer('kcseo-dismissible-notice', 'nonce');

					update_option('kcseo_bf_2022', '1');
					wp_die();
				}
			);
		}
	}
endif;
