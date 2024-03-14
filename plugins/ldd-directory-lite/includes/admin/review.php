<?php

class LDD_Nag {
	/**
	 * Assign Current User
	 */
	private $current_user;
	/**
	 * Assign Current DateTime
	 */
	private $datetime_now;
	/**
	 * Constructor for class
	 */
	public function __construct() {
		$this->current_user = wp_get_current_user();
		$this->datetime_now = new DateTime();
	}

	/**
	 * Setup the class
	 */
	public function setup() {

		// catch nag hide
		$this->catch_delay_notice();
		$this->catch_remove_perm();

		// bind nag
		$this->bind();

		add_action( 'admin_footer', array( $this, 'script' ) );
		add_action( 'wp_ajax_ldd_clicked_review', array( $this, 'catch_hide_notice' ) );
	}

	/**
	 * Catch the hide nag request
	 */
	private function catch_delay_notice() {
		if ( isset( $_GET[ LDDLITE_DELAY_NOTICE_KEY ] ) && current_user_can( 'install_plugins' ) ) {
			// Add user meta

			$date_string  = $this->datetime_now->format( 'Y-m-d' );
			update_user_meta( $this->current_user->ID, LDDLITE_DELAY_NOTICE_KEY, '1' );
			update_user_meta( $this->current_user->ID, 'lddlite-dalay-notice-date', $date_string );

			// Build redirect URL
			$query_params = $this->get_admin_querystring_array();
			unset( $query_params[ LDDLITE_DELAY_NOTICE_KEY ] );
			$query_string = http_build_query( $query_params );
			if ( $query_string != '' ) {
				$query_string = '?' . $query_string;
			}

			$redirect_url = 'http';
			if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {
				$redirect_url .= 's';
			}
			$redirect_url .= esc_url('://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . $query_string);

			// Redirect
			wp_redirect( $redirect_url );
			exit;
		}
	}

/*
* Remove notice permenantly
*/
	private function catch_remove_perm() {
		if ( isset( $_GET['LDDLITE_REMOVE_NOTICE_KEY' ] ) && current_user_can( 'install_plugins' ) ) {
			// Add user meta

		
			update_user_meta( $this->current_user->ID, 'LDDLITE_REMOVE_NOTICE_KEY', '1' );
			

			// Build redirect URL
			$query_params = $this->get_admin_querystring_array();
			unset( $query_params[ LDDLITE_REMOVE_NOTICE_KEY ] );
			$query_string = http_build_query( $query_params );
			if ( $query_string != '' ) {
				$query_string = '?' . $query_string;
			}

			$redirect_url = 'http';
			if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {
				$redirect_url .= 's';
			}
			$redirect_url .= esc_url('://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . $query_string);

			// Redirect
			wp_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * Dismiss the notice when the review link is clicked
	 */
	public function catch_hide_notice(){
		if ( isset( $_POST[ LDDLITE_HIDE_NOTICE_KEY ] ) && current_user_can( 'install_plugins' ) ) {

			add_user_meta( $this->current_user->ID, LDDLITE_HIDE_NOTICE_KEY, '1', true );
		}
		wp_die();
	}

	/**
	 * Bind nag message
	 */
	private function bind() {
		// Is admin notice hidden?
		$hide_notice  = get_user_meta( $this->current_user->ID, LDDLITE_HIDE_NOTICE_KEY, true );
		$delay_notice = get_user_meta( $this->current_user->ID, LDDLITE_DELAY_NOTICE_KEY, true );
		$romove_notice = get_user_meta( $this->current_user->ID, 'LDDLITE_REMOVE_NOTICE_KEY', true );

		// Check if we need to display the notice
		if ( current_user_can( 'install_plugins' ) && $romove_notice !=1 ) { //good
			// Get installation date
			$datetime_install = $this->get_install_date();
			$datetime_past    = new DateTime( '-7 days' );

			if ( $delay_notice ){

				$datetime_delay      = $this->get_delay_date();
				$datetime_delay_past = new DateTime( '-14 days' );

				if ( $datetime_delay_past >= $datetime_delay  && $romove_notice !=1) {
					// 14 or more days ago, show admin notice
					add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
				}
			}elseif ( $datetime_past >= $datetime_install  && '' == $hide_notice) {
				// 7 or more days ago, show admin notice
				add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
			}
		}
	}

	/**
	 * Echo the JS script in the admin footer
	 */
	public function script() { ?>

		<script>
			jQuery(document).ready(function($) {
				$('#ldd-review').on('click', lddDismiss);
				function lddDismiss() {
					var data = {
						action: 'ldd_clicked_review',
						'<?php echo LDDLITE_HIDE_NOTICE_KEY; ?>': '1'
					};
					jQuery.ajax({
						type:'POST',
						url: ajaxurl,
						data: data,
						success:function( data ){
							console.log(data);
						}
					});
				}
			});
		</script>

	<?php }

	/**
	 * Get the install data
	 *
	 * @return DateTime
	 */
	private function get_install_date() {
		$date_string = get_site_option( LDDLITE_INSTALL_DATE, '' );
		if ( $date_string == '' ) {
			// There is no install date, plugin was installed before version 1.2.0. Add it now.
			$date_string = $this->insert_install_date();
		}

		return new DateTime( $date_string );
	}

	/**
	 * Get the install data
	 *
	 * @return DateTime
	 */
	private function get_delay_date() {
		$date_string = get_user_meta( $this->current_user->ID, 'lddlite-dalay-notice-date', true );
		return new DateTime( $date_string );
	}

	/**
	 * Parse the admin query string
	 *
	 * @return array
	 */
	private function get_admin_querystring_array() {
		parse_str( sanitize_text_field($_SERVER['QUERY_STRING']), $params );

		return $params;
	}

	/**
	 * Insert the install date
	 *
	 * @return string
	 */
	public function insert_install_date() {

		$date_string  = $this->datetime_now->format( 'Y-m-d' );
		add_site_option( LDDLITE_INSTALL_DATE, $date_string, '', 'no' );

		return $date_string;
	}

	/**
	 * Display the admin notice
	 */
	public function display_admin_notice() {

		$query_params = $this->get_admin_querystring_array();
		$query_string = '?' . http_build_query( array_merge( $query_params, array( LDDLITE_DELAY_NOTICE_KEY => '1' ) ) );

		$hide_string = '?'. http_build_query( array_merge( $query_params, array( 'LDDLITE_REMOVE_NOTICE_KEY' => '1' ) ) );

		echo wp_kses_post('<div class="notice is-dismissible" style="border-left: 15px solid #1C346F;">');
		echo wp_kses_post('<img src="'.LDDLITE_URL.'/public/images/ldd-logo-review.png" style="float: left;    margin: 1.5em 5em 1.5em 0;">');
		printf( __(wp_kses_post('<h3 style="color:#1C346F;margin:1em 0 0;">Thank you for using the LDD Directory Lite</h3><span style="font-size: 15px;">Please consider taking a moment to rate or review this plugin.</span> <br /><br /><a id="ldd-review" class="button" style="background: #1C346F;border-color: #1C346F;-webkit-box-shadow: 0 1px 0 #1C346F;box-shadow: 0 1px 0 #1C346F;text-shadow: none;color: #fff;font-weight: bold;" href="%1$s" target="_blank">Rate this Plugin</a>    <a class="button" style="background: #bdcdec;border-color: #bdcdec;-webkit-box-shadow: 0 1px 0 #bdcdec;box-shadow: 0 1px 0 #bdcdec;text-shadow: none;color: #1C346F;font-weight: bold;margin-left: 5em;" href="%2$s">Not Now - Maybe Later</a>     <a id="ldd-review-remove" class="button" style="border-color: #bdcdec;-webkit-box-shadow: 0 1px 0 #bdcdec;box-shadow: 0 1px 0 #bdcdec;text-shadow: none;color: #bdcdec;font-weight: bold;float:right;background:#fff" href="%3$s" target="">No thanks (don\'t ask again)</a><br />', 'ldd-directory-lite')), esc_url('https://wordpress.org/support/plugin/ldd-directory-lite/reviews/#new-topic-0'), $query_string,$hide_string ).'';
		echo wp_kses_post("<div class='clear'></div></div>");
	}
}