<?php
/**
 * ECAE Options Page
 *
 * @package ECAE
 */

add_action( 'admin_init', 'tonjoo_ecae_options_init' );
add_action( 'admin_menu', 'tonjoo_ecae_options_page' );

/**
 * Init plugin options to white list our options
 */
function tonjoo_ecae_options_init() {
	register_setting( 'tonjoo_options', 'tonjoo_ecae_options' );
}

/**
 * Load up the menu page
 */
function tonjoo_ecae_options_page() {
	add_menu_page(
		__( 'Easy Custom Auto Excerpt Options Page', 'easy-custom-auto-excerpt' ),
		'Excerpt',
		'moderate_comments', // editor.
		'tonjoo_excerpt',
		'tonjoo_ecae_options_do_page',
		'dashicons-text'
	);
	add_submenu_page(
		'tonjoo_excerpt',
		'Settings',
		'Settings',
		'moderate_comments',
		'tonjoo_excerpt',
		'tonjoo_ecae_options_do_page'
	);
	add_submenu_page(
		'tonjoo_excerpt',
		'About ECAE',
		'About ECAE',
		'moderate_comments',
		'ecae_about_page',
		'ecae_about_page_callback'
	);
	function ecae_about_page_callback() {
		require_once plugin_dir_path( __FILE__ ) . 'about-page.php';
	}
}

/**
 * Create the options page
 */
function tonjoo_ecae_options_do_page() {
	global $select_options, $radio_options, $disable_premium;

	if ( ! function_exists( 'is_ecae_premium_exist' ) ) {
		$disable_premium = 'disabled';
	} else {
		$disable_premium = '';
	}

	require_once plugin_dir_path( __FILE__ ) . 'walker_dropdown_multiple.php';

	if ( ! isset( $_REQUEST['settings-updated'] ) ) {
		$_REQUEST['settings-updated'] = false;
	}

	/**
	 * Save options
	 */
	if ( isset( $_POST['tonjoo_ecae_options'] ) && isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'tonjoo_options-options' ) ) { // input var okay.

		$opt['excerpt_method']           = isset( $_POST['tonjoo_ecae_options']['excerpt_method'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['excerpt_method'] ) : ''; // input var okay.
		$opt['width']                    = isset( $_POST['tonjoo_ecae_options']['width'] ) ? absint( $_POST['tonjoo_ecae_options']['width'] ) : ''; // input var okay.
		$opt['strip_shortcode']          = isset( $_POST['tonjoo_ecae_options']['strip_shortcode'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['strip_shortcode'] ) : ''; // input var okay.
		$opt['strip_empty_tags']         = isset( $_POST['tonjoo_ecae_options']['strip_empty_tags'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['strip_empty_tags'] ) : ''; // input var okay.
		$opt['disable_on_feed']          = isset( $_POST['tonjoo_ecae_options']['disable_on_feed'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['disable_on_feed'] ) : ''; // input var okay.
		$opt['special_method']           = isset( $_POST['tonjoo_ecae_options']['special_method'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['special_method'] ) : ''; // input var okay.
		$opt['justify']                  = isset( $_POST['tonjoo_ecae_options']['justify'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['justify'] ) : ''; // input var okay.
		$opt['font_size']                  = isset( $_POST['tonjoo_ecae_options']['font_size'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['font_size'] ) : ''; // input var okay.
		$opt['show_image']               = isset( $_POST['tonjoo_ecae_options']['show_image'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['show_image'] ) : ''; // input var okay.
		$opt['clickable_image']               = isset( $_POST['tonjoo_ecae_options']['clickable_image'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['clickable_image'] ) : ''; // input var okay.
		$opt['image_position']           = isset( $_POST['tonjoo_ecae_options']['image_position'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['image_position'] ) : ''; // input var okay.
		$opt['image_width_type']         = isset( $_POST['tonjoo_ecae_options']['image_width_type'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['image_width_type'] ) : ''; // input var okay.
		$opt['image_width']              = isset( $_POST['tonjoo_ecae_options']['image_width'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['image_width'] ) : ''; // input var okay.
		$opt['image_padding_top']        = isset( $_POST['tonjoo_ecae_options']['image_padding_top'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['image_padding_top'] ) : ''; // input var okay.
		$opt['image_padding_right']      = isset( $_POST['tonjoo_ecae_options']['image_padding_right'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['image_padding_right'] ) : ''; // input var okay.
		$opt['image_padding_bottom']     = isset( $_POST['tonjoo_ecae_options']['image_padding_bottom'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['image_padding_bottom'] ) : ''; // input var okay.
		$opt['image_padding_left']       = isset( $_POST['tonjoo_ecae_options']['image_padding_left'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['image_padding_left'] ) : ''; // input var okay.
		$opt['image_thumbnail_size']     = isset( $_POST['tonjoo_ecae_options']['image_thumbnail_size'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['image_thumbnail_size'] ) : ''; // input var okay.
		$opt['location_settings_type']   = isset( $_POST['tonjoo_ecae_options']['location_settings_type'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['location_settings_type'] ) : ''; // input var okay.
		$opt['home']                     = isset( $_POST['tonjoo_ecae_options']['home'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['home'] ) : ''; // input var okay.
		$opt['front_page']               = isset( $_POST['tonjoo_ecae_options']['front_page'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['front_page'] ) : ''; // input var okay.
		$opt['archive']                  = isset( $_POST['tonjoo_ecae_options']['archive'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['archive'] ) : ''; // input var okay.
		$opt['search']                   = isset( $_POST['tonjoo_ecae_options']['search'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['search'] ) : ''; // input var okay.
		$opt['advanced_home']            = isset( $_POST['tonjoo_ecae_options']['advanced_home'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['advanced_home'] ) : ''; // input var okay.
		$opt['advanced_home_width']      = isset( $_POST['tonjoo_ecae_options']['advanced_home_width'] ) ? absint( $_POST['tonjoo_ecae_options']['advanced_home_width'] ) : ''; // input var okay.
		$opt['advanced_frontpage']       = isset( $_POST['tonjoo_ecae_options']['advanced_frontpage'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['advanced_frontpage'] ) : ''; // input var okay.
		$opt['advanced_frontpage_width'] = isset( $_POST['tonjoo_ecae_options']['advanced_frontpage_width'] ) ? absint( $_POST['tonjoo_ecae_options']['advanced_frontpage_width'] ) : ''; // input var okay.
		$opt['advanced_archive']         = isset( $_POST['tonjoo_ecae_options']['advanced_archive'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['advanced_archive'] ) : ''; // input var okay.
		$opt['advanced_archive_width']   = isset( $_POST['tonjoo_ecae_options']['advanced_archive_width'] ) ? absint( $_POST['tonjoo_ecae_options']['advanced_archive_width'] ) : ''; // input var okay.
		$opt['advanced_search']          = isset( $_POST['tonjoo_ecae_options']['advanced_search'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['advanced_search'] ) : ''; // input var okay.
		$opt['advanced_search_width']    = isset( $_POST['tonjoo_ecae_options']['advanced_search_width'] ) ? absint( $_POST['tonjoo_ecae_options']['advanced_search_width'] ) : ''; // input var okay.
		$opt['advanced_page_main']       = isset( $_POST['tonjoo_ecae_options']['advanced_page_main'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['advanced_page_main'] ) : ''; // input var okay.
		$opt['advanced_page_main_width'] = isset( $_POST['tonjoo_ecae_options']['advanced_page_main_width'] ) ? absint( $_POST['tonjoo_ecae_options']['advanced_page_main_width'] ) : ''; // input var okay.
		$opt['button_display_option']    = isset( $_POST['tonjoo_ecae_options']['button_display_option'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['button_display_option'] ) : ''; // input var okay.
		$opt['read_more']                = isset( $_POST['tonjoo_ecae_options']['read_more'] ) ? sanitize_text_field( wp_unslash( $_POST['tonjoo_ecae_options']['read_more'] ) ) : ''; // input var okay.
		$opt['text_after_content']       = isset( $_POST['tonjoo_ecae_options']['text_after_content'] ) ? sanitize_text_field( wp_unslash( $_POST['tonjoo_ecae_options']['text_after_content'] ) ) : ''; // input var okay.
		$opt['readmore_inline']          = isset( $_POST['tonjoo_ecae_options']['readmore_inline'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['readmore_inline'] ) : ''; // input var okay.
		$opt['read_more_align']          = isset( $_POST['tonjoo_ecae_options']['read_more_align'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['read_more_align'] ) : ''; // input var okay.
		$opt['button_skin']              = isset( $_POST['tonjoo_ecae_options']['button_skin'] ) ? sanitize_text_field( wp_unslash( $_POST['tonjoo_ecae_options']['button_skin'] ) ) : ''; // input var okay.
		$opt['button_font']              = isset( $_POST['tonjoo_ecae_options']['button_font'] ) ? sanitize_text_field( wp_unslash( $_POST['tonjoo_ecae_options']['button_font'] ) ) : ''; // input var okay.
		$opt['button_font_size']         = isset( $_POST['tonjoo_ecae_options']['button_font_size'] ) ? sanitize_key( $_POST['tonjoo_ecae_options']['button_font_size'] ) : ''; // input var okay.
		$opt['read_more_text_before']    = isset( $_POST['tonjoo_ecae_options']['read_more_text_before'] ) ? sanitize_text_field( wp_unslash( $_POST['tonjoo_ecae_options']['read_more_text_before'] ) ) : ''; // input var okay.
		$opt['custom_html']              = isset( $_POST['tonjoo_ecae_options']['custom_html'] ) ? wp_kses_post( wp_unslash( $_POST['tonjoo_ecae_options']['custom_html'] ) ) : ''; // input var okay.
		$opt['custom_css']               = isset( $_POST['tonjoo_ecae_options']['custom_css'] ) ? wp_strip_all_tags( wp_unslash( $_POST['tonjoo_ecae_options']['custom_css'] ) ) : ''; // input var okay.

		/**
		 * Excerpt in page
		 */
		$excerpt_in_page_dump = '';
		if ( isset( $_POST['excerpt_in_page'] ) ) { // input var okay.
			$excerpt_in_page = array_map( 'absint', $_POST['excerpt_in_page'] ); // input var okay.
			foreach ( $excerpt_in_page as $key => $value ) {
				$excerpt_in_page_dump .= $value . '|';
			}
		}

		$opt['excerpt_in_page'] = $excerpt_in_page_dump;

		/**
		 * Advanced Post Page Excerpt
		 */
		$opt['home_post_type'] = '';
		if ( isset( $_POST['home_post_type'] ) ) { // input var okay.
			$home_post_type        = array_map( 'sanitize_key', $_POST['home_post_type'] ); // input var okay.
			$opt['home_post_type'] = serialize( $home_post_type );
		}

		$opt['home_category'] = '';
		if ( isset( $_POST['home_category'] ) ) { // input var okay.
			$home_category        = array_map( 'absint', $_POST['home_category'] ); // input var okay.
			$opt['home_category'] = serialize( $home_category );
		}

		$opt['frontpage_post_type'] = '';
		if ( isset( $_POST['frontpage_post_type'] ) ) { // input var okay.
			$frontpage_post_type        = array_map( 'sanitize_key', $_POST['frontpage_post_type'] ); // input var okay.
			$opt['frontpage_post_type'] = serialize( $frontpage_post_type );
		}

		$opt['frontpage_category'] = '';
		if ( isset( $_POST['frontpage_category'] ) ) { // input var okay.
			$frontpage_category        = array_map( 'absint', $_POST['frontpage_category'] ); // input var okay.
			$opt['frontpage_category'] = serialize( $frontpage_category );
		}

		$opt['archive_post_type'] = '';
		if ( isset( $_POST['archive_post_type'] ) ) { // input var okay.
			$archive_post_type        = array_map( 'sanitize_key', $_POST['archive_post_type'] ); // input var okay.
			$opt['archive_post_type'] = serialize( $archive_post_type );
		}

		$opt['archive_category'] = '';
		if ( isset( $_POST['archive_category'] ) ) { // input var okay.
			$archive_category        = array_map( 'absint', $_POST['archive_category'] ); // input var okay.
			$opt['archive_category'] = serialize( $archive_category );
		}

		$opt['search_post_type'] = '';
		if ( isset( $_POST['search_post_type'] ) ) { // input var okay.
			$search_post_type        = array_map( 'sanitize_key', $_POST['search_post_type'] ); // input var okay.
			$opt['search_post_type'] = serialize( $search_post_type );
		}

		$opt['search_category'] = '';
		if ( isset( $_POST['search_category'] ) ) { // input var okay.
			$search_category        = array_map( 'absint', $_POST['search_category'] ); // input var okay.
			$opt['search_category'] = serialize( $search_category );
		}

		$opt['excerpt_in_page_advanced'] = '';
		if ( isset( $_POST['excerpt_in_page_advanced'] ) ) { // input var okay.
			$excerpt_in_page_advanced        = array_map( 'absint', $_POST['excerpt_in_page_advanced'] ); // input var okay.
			$opt['excerpt_in_page_advanced'] = serialize( $excerpt_in_page_advanced );
		}

		$opt['advanced_page'] = '';
		if ( isset( $_POST['advanced_page'] ) ) { // input var okay.
			$advanced_page        = array_map( 'absint', $_POST['advanced_page'] ); // input var okay.
			$opt['advanced_page'] = serialize( $advanced_page );
		}

		$opt['page_post_type'] = '';
		if ( isset( $_POST['page_post_type'] ) ) { // input var okay.
			$page_post_type = $_POST['page_post_type'];
			if ( is_array( $page_post_type ) ) {
				foreach ( $page_post_type as &$v ) {
					$v = array_map( 'sanitize_key', $v );
				}
			}
			$opt['page_post_type'] = serialize( $page_post_type );
		}

		$opt['page_category'] = '';
		if ( isset( $_POST['page_category'] ) ) { // input var okay.
			$page_category = $_POST['page_category'];
			if ( is_array( $page_category ) ) {
				foreach ( $page_category as &$v ) {
					$v = array_map( 'sanitize_key', $v );
				}
			}
			$opt['page_category'] = serialize( $page_category );
		}

		$new_opt['tonjoo_ecae_options'] = $opt;

		/**
		 * Update options
		 */
		update_option( 'tonjoo_ecae_options', $new_opt['tonjoo_ecae_options'] );

		/**
		 * WMPL if active :: register strings for translation
		 */
		if ( function_exists( 'icl_object_id' ) && isset( $opt['read_more'] ) && isset( $opt['read_more_text_before'] ) ) {
			do_action( 'wpml_register_single_string', 'easy-custom-auto-excerpt', 'Readmore text', $opt['read_more'] );
			do_action( 'wpml_register_single_string', 'easy-custom-auto-excerpt', 'Before readmore link', $opt['read_more_text_before'] );
		}
	}

	$pro_label = '';
	if ( ! function_exists( 'is_ecae_premium_exist' ) ) {
		$pro_label = ' <span>(PRO)</span>';
	}
	?>

	<div class="wrap">
		<?php echo '<h2>' . esc_html__( 'Easy Custom Auto Excerpt Options', 'easy-custom-auto-excerpt' ) . '</h2>'; ?>

		<br>

		<?php esc_html_e( 'Easy Custom Auto Excerpt by', 'easy-custom-auto-excerpt' ); ?>
		<a href='https://tonjoostudio.com' target="_blank">Tonjoo Studio</a> ~
		<a href='http://wordpress.org/support/view/plugin-reviews/easy-custom-auto-excerpt?filter=5' target="_blank"><?php esc_html_e( 'Please Rate :)', 'easy-custom-auto-excerpt' ); ?></a> |
		<a href='http://wordpress.org/extend/plugins/easy-custom-auto-excerpt/' target="_blank"><?php esc_html_e( 'Comment', 'easy-custom-auto-excerpt' ); ?></a> |
		<a href='https://forum.tonjoostudio.com/thread-category/easy-custom-auto-excerpt/' target="_blank"><?php esc_html_e( 'Forum', 'easy-custom-auto-excerpt' ); ?></a> |
		<a href='https://tonjoostudio.com/addons/easy-custom-auto-excerpt/#faq' target="_blank"><?php esc_html_e( 'FAQ', 'easy-custom-auto-excerpt' ); ?></a>

		<?php if ( ! function_exists( 'is_ecae_premium_exist' ) ) : ?>
			<span style="width:10px;display:inline-block;"></span>
			<a class="button btn-upgrade" href="https://tonjoostudio.com/product/easy-custom-auto-excerpt-premium/?utm_source=wp_ecae&utm_medium=setting_header&utm_campaign=upsell"><?php esc_html_e( 'Upgrade to Pro', 'easy-custom-auto-excerpt' ); ?></a>
		<?php endif; ?>

		<br>
		<br>

		<?php if ( isset( $_REQUEST['settings-updated'] ) && $_REQUEST['settings-updated'] == true ) { ?>
			<div id="message" class="updated">
				<p><strong><?php esc_html_e( 'Settings saved.', 'easy-custom-auto-excerpt' ); ?></strong></p>
			</div>
		<?php } ?>

		<form method="post" action="">
			<?php
				settings_fields( 'tonjoo_options' );
				$options = get_option( 'tonjoo_ecae_options' );
				tonjoo_ecae_load_default( $options );
			?>

			<h2 class="nav-tab-wrapper">
				<a class="nav-tab" id="opt-general-tab" href="#opt-general"><?php esc_html_e( 'General Options', 'easy-custom-auto-excerpt' ); ?></a>
				<a class="nav-tab" id="opt-location-tab" href="#opt-location"><?php esc_html_e( 'Excerpt Location', 'easy-custom-auto-excerpt' ); ?></a>
				<a class="nav-tab" id="opt-readmore-tab" href="#opt-readmore"><?php esc_html_e( 'Read More Button', 'easy-custom-auto-excerpt' ); ?></a>
			</h2>

			<div class="metabox-holder columns-2" style="margin-right: 300px;">

				<!-- Extra style for options -->
				<style>
					.form-table td {
						vertical-align: top;
					}

					.form-table th {
						width: 175px;
					}

					.form-table input[type=text], .form-table input[type=number], .form-table select {
						width: 200px;
						margin-right: 10px;
					}

					label.error{
						margin-left: 5px;
						color: red;
					}

					.form-table tr th {
						text-align: left;
						font-weight: normal;
					}

					.meta-subtitle {
						margin: 0px -22px !important;
						border-top: 1px solid #EEE;
						border-bottom: 1px solid #EEE;
						background-color: #F6F6F6;
						padding: 10px;
						font-size: 14px;
					}

					@media (max-width: 767px) {
						.meta-subtitle {
							margin-left: -12px !important;
						}
					}

					label{
						vertical-align: top
					}
				</style>

				<!-- GENERAL OPTIONS -->
				<div id='opt-general' class="postbox-container group" style="width: 100%;min-width: 463px;float: left; ">
					<div class="meta-box-sortables ui-sortable">
						<div id="adminform">
							<?php require_once plugin_dir_path( __FILE__ ) . 'options-page-general.php'; ?>
						</div>
					</div>
				</div>


				<!-- LOCATION OPTIONS -->
				<div id='opt-location' class="postbox-container group" style="width: 100%;min-width: 463px;float: left; ">
					<div class="meta-box-sortables ui-sortable">
						<div id="adminform" class="postbox">
							<h3 class="hndle"><span><?php esc_html_e( 'Excerpt Location Options', 'easy-custom-auto-excerpt' ); ?></span></h3>
							<div class="inside" style="z-index:1;">
								<table class="form-table">
									<?php require_once plugin_dir_path( __FILE__ ) . 'options-page-location.php'; ?>
								</table>
							</div>
						</div>
					</div>
				</div>


				<!-- READMORE OPTIONS -->
				<div id="opt-readmore" class="postbox-container group" style="width: 100%;min-width: 463px;float: left; ">
					<div class="meta-box-sortables ui-sortable">
						<div id="adminform">
							<?php require_once plugin_dir_path( __FILE__ ) . 'options-page-readmore.php'; ?>
						</div>
					</div>
				</div>


				<!-- SIDEBAR -->
				<div class="postbox-container" style="float: right;margin-right: -300px;width: 280px;">
					<div class="metabox-holder" style="padding-top:0px;">
						<div class="meta-box-sortables ui-sortable">
							<div id="email-signup" class="postbox">
								<h3 class="hndle"><span><?php esc_html_e( 'Save Options', 'easy-custom-auto-excerpt' ); ?></span></h3>
								<div class="inside" style="padding-top:10px;">
									<?php esc_html_e( 'Save your changes to apply the options', 'easy-custom-auto-excerpt' ); ?>
									<br>
									<br>
									<input type="submit" class="button-primary" value="<?php esc_html_e( 'Save Options', 'easy-custom-auto-excerpt' ); ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

	<?php
}
