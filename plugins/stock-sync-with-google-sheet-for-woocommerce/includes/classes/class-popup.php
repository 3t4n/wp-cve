<?php


/**
 * Handles the POPUP after pluginn acitvation
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since 1.3.2
 */

// Namespace.
namespace StockSyncWithGoogleSheetForWooCommerce;

// Exit if accessed directly.
defined('ABSPATH') || exit;

if ( ! class_exists('\StockSyncWithGoogleSheetForWooCommerce\Popup') ) {

	/**
	 * Class Popup.
	 * Handles the plugin activation and deactivation process and admin notices for Stock Sync with Google Sheet for WooCommerce.
	 *
	 * @param int    $ssgs_install_time time of banner install.
	 * @param string $current_dir directory to access.
	 * @package StockSyncWithGoogleSheetForWooCommerce
	 **/
	class Popup {

			/**
			 * Time of banner install.
			 *
			 * @var int
			 */
		protected $ssgs_install_time;

		/**
		 * Directory to access.
		 *
		 * @var string
		 */
		protected $current_dir;

		/**
		 * Initialize the constructor class.
		 *
		 * @return void
		 */
		public function __construct() {
			$this->ssgs_install_time = get_option('ssgsw_install_times');
			$this->current_dir = dirname(__DIR__);
			add_action('admin_init', array( $this, 'ssgsw_show_popup' ));
			add_action('wp_ajax_ssgsw_popup_handle', array( $this, 'handle_popup' ));
		}


		/**
		 * Handle review rating popup
		 *
		 * @return void
		 */
		public function handle_popup() {
			if ( isset($_POST) ) {
				$security = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
				if ( ! isset($security) || ! wp_verify_nonce($security, 'ssgsw_nonce2') ) {
					wp_die(-1, 403);
				}
				$value = isset($_POST['value']) ? sanitize_text_field(wp_unslash($_POST['value'])) : '0';
				update_option('ssgsw_days_count', $value);
				update_option('ssgsw_install_times', time());
				wp_send_json_success([
					'days_count' => esc_attr($value),
					'time' => time(),
				]);
			}
			wp_die();
		}
		/**
		 * SSGSW rating popup
		 *
		 * @return void
		 */
		public function ssgsw_show_popup() {
			$ssgsw_install_time = get_option('ssgsw_install_times');
			$ssgsw_update_notice = get_option('ssgsw_update_notice');
			$days_count = get_option('ssgsw_days_count');

			if ( empty($ssgsw_install_time) ) {
				update_option('ssgsw_install_times', time());
				update_option('ssgsw_update_notice', time());
				update_option('ssgsw_days_count', 7);
			} else {
				$days_elapsed = floor(( time() - $ssgsw_install_time ) / ( 60 * 60 * 24 ));
				$days_update_elapsed = floor(( time() - absint($ssgsw_update_notice) ) / ( 60 * 60 * 24 ));
				if ( ( $days_elapsed >= intval($days_count) && ( '0' === $days_count || '7' === $days_count || '14' === $days_count ) ) ) {
					add_action('admin_footer', array( $this, 'show_popup_rating_popup' ));
				}

				if ( ( $days_update_elapsed >= 10 ) ) {
					add_action('admin_footer', array( $this, 'show_upgrade_banner' ));
				}

				if ( ( $days_update_elapsed >= 14 ) ) {
					add_action('admin_footer', array( $this, 'show_influencer_banner' ));
				}
			}
		}

		/**
		 * Show rating popup
		 *
		 * @return void
		 */
		public function show_popup_rating_popup() {
			?>
			<div class="ssgs-rating-banner">
				<img class="ssgs-image-icon" src="<?php echo esc_url(plugins_url('public/images/top-banner/rating-left-star.svg', $this->current_dir)); ?>" alt="">
				<span class="ssgs-rating-close"></span>
				<span class="ssgs-already-rated"><?php esc_html_e('I already did it', 'stock-sync-with-google-sheet-for-woocommerce'); ?></span>
				<div class="ssgs-rating-wrapper">
					<h3><?php esc_html_e('Seems like ', 'stock-sync-with-google-sheet-for-woocommerce'); ?> <span style="font-weight: 600; font-size:inherit; line-height:inherit;"><?php esc_html_e('Stock Sync with Google Sheet ', 'stock-sync-with-google-sheet-for-woocommerce'); ?></span><?php esc_html_e('is bringing you value 🥳', 'stock-sync-with-google-sheet-for-woocommerce'); ?></h3>
					<p><?php esc_html_e('Hi there! You\'ve been using Stock Sync with Google Sheet for a while. Would you consider leaving us a 😍 5-star review?', 'stock-sync-with-google-sheet-for-woocommerce'); ?></br>
					<?php esc_html_e('Your feedback will help us to develop better features and spread the word.', 'stock-sync-with-google-sheet-for-woocommerce'); ?></p>
					<span><?php esc_html_e('Please Rate Us:', 'stock-sync-with-google-sheet-for-woocommerce'); ?></span>
					<div class="rating-container">
						<span class="ssgs-yellow-icon"></span>
						<span class="ssgs-yellow-icon"></span>
						<span class="ssgs-yellow-icon"></span>
						<span class="ssgs-yellow-icon"></span>
						<span class="ssgs-yellow-icon"></span>
					</div>
				</div>
			</div>
			<div id="popup1" class="ssgsw_popup-container" style="display: none;">
				<div class="ssgsw_popup-content" style="display: none;">
					<a href="#" target="_blank" class="close ssgsw_close_button">&times;</a>
					<div class="ssgsw_first_section2" style="display:none">
						<div class="ssgsw_popup_wrap">
							<h4>Would you like to be remind in the future?</h4>
						</div>
						<div class="ssgsw_select-wrapper">
							<span class="remind-title">Remind Me After: </span>
							<div class="ssgw-days-dropdown">
								<div class="selected-option" data-days="7"><?php esc_html_e('7 Days', 'stock-sync-with-google-sheet-for-woocommerce'); ?></div>
								<ul class="options">
									<li data-value="7"><?php esc_html_e('7 Days', 'stock-sync-with-google-sheet-for-woocommerce'); ?></li>
									<li data-value="14"><?php esc_html_e('14 Days', 'stock-sync-with-google-sheet-for-woocommerce'); ?></li>
									<li data-value="1"><?php esc_html_e('Remind me never', 'stock-sync-with-google-sheet-for-woocommerce'); ?></li>
								</ul>
							</div>
							<div class="ssgsw_button-wrapper">
								<button class="ssgsw_custom-button ssgsw_submit_button2"><?php esc_html_e('Ok', 'stock-sync-with-google-sheet-for-woocommerce'); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * SSGSW upgrade banner
		 *
		 * @return void
		 */
		public function show_upgrade_banner() {
			?>
			<div class="ssgs-upgrade-banner" data-time="<?php echo absint($this->ssgs_install_time); ?>">
				<img class="ssgs-image-icon" src="<?php echo esc_url( plugins_url('public/images/top-banner/pink-diamond.svg', $this->current_dir)); ?>" alt="">
				<span class="ssgs-upgrade-close"></span>
				<div class="content">
					<h3><?php esc_html_e('Unlimited stock sync and many more premium features are available in ', 'stock-sync-with-google-sheet-for-woocommerce'); ?> <span><?php esc_html_e('Stock Sync with Google Sheet Ultimate ', 'stock-sync-with-google-sheet-for-woocommerce'); ?></span> <?php esc_html_e('plugin 😍', 'stock-sync-with-google-sheet-for-woocommerce'); ?></h3>
					<a href="<?php echo esc_url('https://go.wppool.dev/Zf3a'); ?>" class="upgrade-button"><?php esc_html_e('Upgrade Now', 'stock-sync-with-google-sheet-for-woocommerce'); ?> <span></span></a>
				</div>
			</div>
			<?php
		}

		/**
		 * SSGSW influencer banner
		 *
		 * @return void
		 */
		public function show_influencer_banner() {
			?>
			<div class="ssgs-influencer-banner" data-time="<?php echo absint($this->ssgs_install_time); ?>">
				<img class="ssgs-image-icon" src="<?php echo esc_url(plugins_url('public/images/top-banner/purple-thumbs-up.svg', $this->current_dir)); ?>" alt="">
				<span class="ssgs-influencer-close"></span>
				<div class="ssgs-influencer-wrapper">
					<h3><?php esc_html_e('Hey! Enjoying the Stock Sync with Google Sheet plugin? 😍 Join our ', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
					<span><?php printf('<a style="text-decoration:none; color:#7C3AED; font-family:inherit; cursor: pointer;" href="%s" target="_blank">%s</a>', esc_url('https://go.wppool.dev/6fp2'), esc_html('Influencer Program ', 'stock-sync-with-google-sheet-for-woocommerce')); ?></span>
					<?php esc_html_e('to make money from your social media content. You can also check our', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
					<span><?php printf('<a style="text-decoration:none; color:#7C3AED; font-family:inherit; cursor:pointer;" href="%s" target="_blank">%s</a>', esc_url('https://go.wppool.dev/yfoQ'), esc_html('Affiliate Program ', 'stock-sync-with-google-sheet-for-woocommerce')); ?></span> 
					<?php esc_html_e('to get a ', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
					<span style="font-weight:600; font-size:inherit; color: #1f2937"><?php esc_html_e('25% commission ', 'stock-sync-with-google-sheet-for-woocommerce'); ?></span>
					<?php esc_html_e('on every sale!', 'stock-sync-with-google-sheet-for-woocommerce'); ?>
				
				</h3>
					<div class="link-wrapper">
						<a href="<?php echo esc_url('https://go.wppool.dev/yfoQ'); ?>" target="_blank" class="affiliate-button"><?php esc_html_e('Affiliate Program', 'stock-sync-with-google-sheet-for-woocommerce'); ?></a>
						<a href="<?php echo esc_url('https://go.wppool.dev/6fp2'); ?>" target="_blank" class="influencer-button" style=""><?php esc_html_e('Influencer Program', 'stock-sync-with-google-sheet-for-woocommerce'); ?> <span></span></a>
					</div>
				</div>
			</div>
			<?php
		}
	}
	new Popup();
}
