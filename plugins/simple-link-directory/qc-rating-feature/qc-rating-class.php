<?php
/*
* QuantumCloud Promo + Support Page
* Revised On: 06-01-2017
*/

/*******************************
 * Main Class to Display Support
 * form and the promo pages
 *******************************/
if( !class_exists('Qcrating') ){
	
	class Qcrating{
		
		public $plugin_name = "sld"; //Without spaces
		public $plugin_full_name = "Simple Link Directory";
		public $logo_url;
		
		public $plugin_rating_url = "https://wordpress.org/support/view/plugin-reviews/simple-link-directory?rate=5#postform";
		
		public function __construct(){
			$this->logo_url = QCOPD_IMG_URL . "/logo.png";
		}
		
		function run(){
			add_action('admin_init', array($this, 'qc_admin_notice_rating'));
		}
		
		/**
		 *	Check and Dismiss review message.
		 *
		 */
		private function review_dismissal() {

			
		
			//delete_site_option( 'wp_analytify_review_dismiss' );
			if ( ! is_admin() ||
				! isset( $_GET['_wpnonce'] ) ||
				! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'qc-'.$this->plugin_name.'-rating-nonce' ) ||
				! isset( $_GET['qc_'.$this->plugin_name.'_rating_dismiss'] ) ) {

				return;
			}

			
			update_option( 'qc_'.$this->plugin_name.'_rating_dismiss', 'yes' );
			
		}
		
		/**
		 * Set time to current so review notice will popup after X days
		 */
		function review_prending() {

			// delete_site_option( 'wp_analytify_review_dismiss' );
			if ( ! is_admin() ||
				! isset( $_GET['_wpnonce'] ) ||
				! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'qc-'.$this->plugin_name.'-rating-nonce' ) ||
				! isset( $_GET['qc_'.$this->plugin_name.'_rating_later'] ) ) {

				return;
			}

			// Reset Time to current time.
			update_option( 'qc_'.$this->plugin_name.'_rating_active_time', time() );

		}
		
		public function qc_admin_notice_rating(){
			
			$this->review_dismissal();
			$this->review_prending();
			
			$activation_time 	= get_option( 'qc_'.$this->plugin_name.'_rating_active_time' );
			$review_dismissal	= get_option( 'qc_'.$this->plugin_name.'_rating_dismiss' );
			//echo $review_dismissal;exit;
			if ( 'yes' == $review_dismissal ) {
				return;
			}

			if ( ! $activation_time ) {

				$activation_time = time();
				add_option( 'qc_'.$this->plugin_name.'_rating_active_time', $activation_time );
			}
			
			// 604800 = 7 Days in seconds.
			if ( time() - $activation_time > 604800 ) {
				add_action( 'admin_enqueue_scripts', array($this, 'qc_load_rating_style') );
				add_action( 'admin_notices' , array( $this, 'qc_rating_notice_message' ) );
			}
		}
		
		public function qc_load_rating_style(){
			wp_enqueue_style( 'qc_rating_stylesheet', plugin_dir_url(__FILE__)."css/style.css");
		}
		
		public function qc_rating_notice_message(){
			
			/*if ( ! is_admin() ||
				! current_user_can( 'manage_options' ) ) {
				return;
			}*/
			
			$scheme      = (parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY )) ? '&' : '?';
			
			$url         = $_SERVER['REQUEST_URI'] . $scheme . 'qc_'.$this->plugin_name.'_rating_dismiss=yes';
			
			$dismiss_url = wp_nonce_url( $url, 'qc-'.$this->plugin_name.'-rating-nonce' );

			$_later_link = $_SERVER['REQUEST_URI'] . $scheme . 'qc_'.$this->plugin_name.'_rating_later=yes';
			
			$later_url   = wp_nonce_url( $_later_link, 'qc-'.$this->plugin_name.'-rating-nonce' );
			
		?>
			<div class="qc-review-notice">
				<div class="qc-review-thumbnail">
					<img src="<?php echo esc_url($this->logo_url); ?>" alt="">
				</div>
				
				<div class="qc-review-text">
				
					<h3><?php _e( 'Leave A Review for Simple Link Directory?', 'qc-sld' ) ?></h3>
					
					<p><?php _e( 'We hope you\'ve enjoyed using <b>Simple Link Directory</b>! Would you consider leaving us a review on WordPress.org?', 'qc-opd' ) ?></p>
					
					<ul class="qc-review-ul">
					
						<li><a href="<?php echo esc_url($this->plugin_rating_url); ?>" target="_blank"><span class="dashicons dashicons-star-filled"></span><?php _e( 'Leave A Review', 'qc-sld' ) ?></a></li>
						 <li><a href="<?php echo esc_url($dismiss_url) ?>"><span class="dashicons dashicons-yes"></span><?php _e( 'I\'ve already left a review', 'qc-sld' ) ?></a></li>
						 <li><a href="<?php echo esc_url($later_url) ?>"><span class="dashicons dashicons-calendar"></span><?php _e( 'Maybe Later', 'qc-sld' ) ?></a></li>
						 <li><a href="<?php echo esc_url($dismiss_url) ?>"><span class="dashicons dashicons-no"></span><?php _e( 'Never show this again', 'qc-sld' ) ?></a></li>
			 
					</ul>
				</div>
			</div>
		<?php
		}
		
	}
}
$qc_sld_rating = new Qcrating();

$qc_sld_rating->plugin_name = 'sld';

$qc_sld_rating->run();
