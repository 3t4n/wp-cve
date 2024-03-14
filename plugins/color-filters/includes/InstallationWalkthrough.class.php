<?php

/**
 * Class to handle everything related to the walk-through that runs on plugin activation
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

class ewduwcfInstallationWalkthrough {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_install_screen' ) );
		add_action( 'admin_head', array( $this, 'hide_install_screen_menu_item' ) );
		add_action( 'admin_init', array( $this, 'redirect' ), 9999 );

		add_action( 'admin_head', array( $this, 'admin_enqueue' ) );

		add_action( 'wp_ajax_ewd_uwcf_welcome_set_options', array( $this, 'set_options' ) );
		add_action( 'wp_ajax_ewd_uwcf_welcome_add_color', array( $this, 'create_color' ) );
		add_action( 'wp_ajax_ewd_uwcf_welcome_add_size', array( $this, 'create_size' ) );
	}

	/**
	 * On activation, redirect the user if they haven't used the plugin before
	 * @since 3.0.0
	 */
	public function redirect() {
		if ( ! get_transient( 'ewd-uwcf-getting-started' ) ) 
			return;
		
		delete_transient( 'ewd-uwcf-getting-started' );

		if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
			return;

		if ( taxonomy_exists( EWD_UWCF_PRODUCT_COLOR_TAXONOMY ) and ! empty( get_terms( array( 'taxonomy' => EWD_UWCF_PRODUCT_COLOR_TAXONOMY ) ) ) ) {
			return;
		}

		if ( taxonomy_exists( EWD_UWCF_PRODUCT_SIZE_TAXONOMY ) and ! empty( get_terms( array( 'taxonomy' => EWD_UWCF_PRODUCT_SIZE_TAXONOMY ) ) ) ) {
			return;
		}

		wp_safe_redirect( admin_url( 'index.php?page=ewd-uwcf-getting-started' ) );
		exit;
	}

	/**
	 * Create the installation admin page
	 * @since 3.0.0
	 */
	public function register_install_screen() {

		add_dashboard_page(
			esc_html__( 'Ultimate WooCommerce Filters - Welcome!', 'color-filters' ),
			esc_html__( 'Ultimate WooCommerce Filters - Welcome!', 'color-filters' ),
			'manage_options',
			'ewd-uwcf-getting-started',
			array($this, 'display_install_screen')
		);
	}

	/**
	 * Hide the installation admin page from the WordPress sidebar menu
	 * @since 3.0.0
	 */
	public function hide_install_screen_menu_item() {

		remove_submenu_page( 'index.php', 'ewd-uwcf-getting-started' );
	}

	/**
	 * Set the key options for the plugin
	 * @since 3.0.0
	 */
	public function set_options() {
		global $ewd_uwcf_controller;

		// Authenticate request
		if ( ! check_ajax_referer( 'ewd-uwcf-getting-started', 'nonce' ) ||  ! current_user_can( 'manage_options' ) ) {

			ewduwcfHelper::admin_nopriv_ajax();
		}

		$uwcf_options = get_option( 'ewd-uwcf-settings' );

		if ( isset( $_POST['table_mode'] ) ) { $uwcf_options['table-format'] = intval( $_POST['table_mode'] ); }
		if ( isset( $_POST['color_filtering'] ) ) { $uwcf_options['color-filtering'] = intval( $_POST['color_filtering'] ); }
		if ( isset( $_POST['size_filtering'] ) ) { $uwcf_options['size-filtering'] = intval( $_POST['size_filtering'] ); }
		if ( isset( $_POST['category_filtering'] ) ) { $uwcf_options['category-filtering'] = intval( $_POST['category_filtering'] ); }
		if ( isset( $_POST['tag_filtering'] ) ) { $uwcf_options['tag-filtering'] = intval( $_POST['tag_filtering'] ); }
		
		update_option( 'ewd-uwcf-settings', $uwcf_options );

		$ewd_uwcf_controller->settings->reset_to_database_settings();

		if ( $uwcf_options['color-filtering'] ) { $ewd_uwcf_controller->settings->check_for_wc_color_taxonomy(); }
		if ( $uwcf_options['size-filtering'] ) { $ewd_uwcf_controller->settings->check_for_wc_size_taxonomy(); }

		if ( $uwcf_options['table-format'] ) { $this->set_default_wc_table_fields_to_display(); }

		exit();
	}

	/**
	 * Create a color taxonomy term
	 * @since 3.0.0
	 */
	public function create_color() {

		// Authenticate request
		if ( ! check_ajax_referer( 'ewd-uwcf-getting-started', 'nonce' ) ||  ! current_user_can( 'manage_options' ) ) {

			ewduwcfHelper::admin_nopriv_ajax();
		}

		$color_name = isset( $_POST['color_name'] ) ? sanitize_text_field( $_POST['color_name'] ) : '';
		$color_description = isset( $_POST['color_description'] ) ? sanitize_textarea_field( $_POST['color_description'] ) : '';
		$color = ( ! empty( $_POST['color_image'] ) and $_POST['color_image'] != 'http://' ) ?  sanitize_text_field( $_POST['color_image'] ) : sanitize_text_field( $_POST['normal_fill'] );

		$color_term = wp_insert_term( $color_name, EWD_UWCF_PRODUCT_COLOR_TAXONOMY, array( 'description' => $color_description ) );

		if ( is_wp_error( $color_term ) ) { exit(); }

		update_term_meta( $color_term['term_id'], 'EWD_UWCF_Color', $color );

		$color_style = strpos( $color, 'http' ) === false ? 'style="background: ' . $color . ';"'  : 'style="background:url(\'' . $color . '\'); background-size: cover;"';

		echo json_encode ( array(
			'color_name' => $color_name,
			'term_id' => $color_term['term_id'],
			'color_style' => $color_style
		) );

		exit();
	}

	/**
	 * Create a color taxonomy term
	 * @since 3.0.0
	 */
	public function create_size() {

		// Authenticate request
		if ( ! check_ajax_referer( 'ewd-uwcf-getting-started', 'nonce' ) ||  ! current_user_can( 'manage_options' ) ) {

			ewduwcfHelper::admin_nopriv_ajax();
		}

		$size_name = isset( $_POST['size_name'] ) ? sanitize_text_field( $_POST['size_name'] ) : '';
		$size_description = isset( $_POST['size_description'] ) ? sanitize_textarea_field( $_POST['size_description'] ) : '';

		$size_term = wp_insert_term( $size_name, EWD_UWCF_PRODUCT_SIZE_TAXONOMY, array( 'description' => $size_description ) );

		if ( is_wp_error( $size_term ) ) { exit(); }

		echo json_encode ( array( 'size_name' => $size_name, 'term_id' => $size_term['term_id'] ) );

		exit();
	}

	/**
	 * Display a few fields by default in table mode
	 * @since 3.0.0
	 */
	public function set_default_wc_table_fields_to_display() {
		global $ewd_uwcf_controller;

		$ewd_uwcf_controller->settings->set_setting( 'wc-table-product-name-displayed', true );
		$ewd_uwcf_controller->settings->set_setting( 'wc-table-product-image-displayed', true );
		$ewd_uwcf_controller->settings->set_setting( 'wc-table-product-price-displayed', true );

		$ewd_uwcf_controller->settings->save_settings();
	}

	/**
	 * Enqueue the admin assets necessary to run the walk-through and display it nicely
	 * @since 3.0.0
	 */
	public function admin_enqueue() {

		if ( ! isset( $_GET['page'] ) or $_GET['page'] != 'ewd-uwcf-getting-started' ) { return; }

		wp_enqueue_media();

		wp_enqueue_style( 'ewd-uwcf-admin-spectrum-css', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/css/spectrum.css', array(), EWD_UWCF_VERSION );
		wp_enqueue_style( 'ewd-uwcf-admin-css', EWD_UWCF_PLUGIN_URL . '/assets/css/admin.css', array(), EWD_UWCF_VERSION );
		wp_enqueue_style( 'ewd-uwcf-sap-admin-css', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/css/admin.css', array(), EWD_UWCF_VERSION );
		wp_enqueue_style( 'ewd-uwcf-welcome-screen', EWD_UWCF_PLUGIN_URL . '/assets/css/ewd-uwcf-welcome-screen.css', array(), EWD_UWCF_VERSION );
		wp_enqueue_style( 'ewd-uwcf-admin-settings-css', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/css/admin-settings.css', array(), EWD_UWCF_VERSION );
		
		wp_enqueue_script( 'ewd-uwcf-admin-spectrum-js', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/js/spectrum.js', array( 'jquery' ), EWD_UWCF_VERSION );
		wp_enqueue_script( 'ewd-uwcf-getting-started', EWD_UWCF_PLUGIN_URL . '/assets/js/ewd-uwcf-welcome-screen.js', array( 'jquery' ), EWD_UWCF_VERSION );
		wp_enqueue_script( 'ewd-uwcf-admin-settings-js', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/js/admin-settings.js', array( 'jquery' ), EWD_UWCF_VERSION );

		wp_localize_script(
			'ewd-uwcf-getting-started',
			'ewd_uwcf_getting_started',
			array(
				'nonce' => wp_create_nonce( 'ewd-uwcf-getting-started' )
			)
		);
	}

	/**
	 * Output the HTML of the walk-through screen
	 * @since 3.0.0
	 */
	public function display_install_screen() { 
		global $ewd_uwcf_controller;

		$table_format = $ewd_uwcf_controller->settings->get_setting( 'table-format' );

		$color_filtering = $ewd_uwcf_controller->settings->get_setting( 'color-filtering' );
		$size_filtering = $ewd_uwcf_controller->settings->get_setting( 'size-filtering' );
		$category_filtering = $ewd_uwcf_controller->settings->get_setting( 'category-filtering' );
		$tag_filtering = $ewd_uwcf_controller->settings->get_setting( 'tag-filtering' );

		?>

		<div class='ewd-uwcf-welcome-screen'>
			
			<div class='ewd-uwcf-welcome-screen-header'>
				<h1><?php _e('Welcome to Ultimate WooCommerce Filters', 'color-filters'); ?></h1>
				<p><?php _e('Thanks for choosing Ultimate WooCommerce Filters! The following will help you get started with the setup by configuring a few key options, and creating some colors or sizes if selected.', 'color-filters'); ?></p>
			</div>

			<div class='ewd-uwcf-welcome-screen-box ewd-uwcf-welcome-screen-table-mode ewd-uwcf-welcome-screen-open' data-screen='table-mode'>
				<h2><?php _e('1. Use Table Mode', 'color-filters'); ?></h2>
				<div class='ewd-uwcf-welcome-screen-box-content'>
					<p><?php _e('Table mode displays your WooCommerce in a table. You can select what information is displayed about each product via the admin area of the plugin. The other way to use the plugin is by adding the plugin\'s widget to your shop page widget area.', 'color-filters'); ?></p>
					<table class='form-table'>
						<tr>
							<th scope='row'><?php _e('Turn On Table Mode', 'color-filters'); ?></th>
							<td class='ewd-uwcf-welcome-screen-option'>
								<fieldset>
									<div class="sap-admin-hide-radios">
										<input type='checkbox' name='table_mode' value='1' <?php if ( $table_format == '1' ) { echo 'checked'; } ?>>
									</div>
									<label class="sap-admin-switch">
										<input type="checkbox" class="sap-admin-option-toggle" data-inputname="table_mode" <?php if ( $table_format == '1' ) { echo 'checked'; } ?>>
										<span class="sap-admin-switch-slider round"></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>
		
					<div class='ewd-uwcf-welcome-screen-save-table-mode-button'><?php _e('Save Selection', 'color-filters'); ?></div>
					<div class='ewd-uwcf-welcome-clear'></div>
					<div class='ewd-uwcf-welcome-screen-next-button' data-nextaction='options'><?php _e('Next', 'color-filters'); ?></div>
					
					<div class='ewd-uwcf-clear'></div>
				</div>
			</div>

			<div class='ewd-uwcf-welcome-screen-box ewd-uwcf-welcome-screen-options' data-screen='options'>
				<h2><?php _e('2. Enable Filters', 'color-filters'); ?></h2>
				<div class='ewd-uwcf-welcome-screen-box-content'>
					<p><?php _e('You can turn on filtering for different terms below.', 'color-filters'); ?></p>
					<table class='form-table'>
						<tr>
							<th scope='row'><?php _e('Enable Color Filtering', 'color-filters'); ?></th>
							<td class='ewd-uwcf-welcome-screen-option'>
								<fieldset>
									<div class="sap-admin-hide-radios">
										<input type='checkbox' name='color_filtering' value='1' <?php if ( $color_filtering == '1' ) { echo 'checked'; } ?>>
									</div>
									<label class="sap-admin-switch">
										<input type="checkbox" class="sap-admin-option-toggle" data-inputname="color_filtering" <?php if ( $color_filtering == '1' ) { echo 'checked'; } ?>>
										<span class="sap-admin-switch-slider round"></span>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope='row'><?php _e('Enable Size Filtering', 'color-filters'); ?></th>
							<td class='ewd-uwcf-welcome-screen-option'>
								<fieldset>
									<div class="sap-admin-hide-radios">
										<input type='checkbox' name='size_filtering' value='1' <?php if ( $size_filtering == '1' ) { echo 'checked'; } ?>>
									</div>
									<label class="sap-admin-switch">
										<input type="checkbox" class="sap-admin-option-toggle" data-inputname="size_filtering" <?php if ( $size_filtering == '1' ) { echo 'checked'; } ?>>
										<span class="sap-admin-switch-slider round"></span>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope='row'><?php _e('Enable Category Filtering', 'color-filters'); ?></th>
							<td class='ewd-uwcf-welcome-screen-option'>
								<fieldset>
									<div class="sap-admin-hide-radios">
										<input type='checkbox' name='category_filtering' value='1' <?php if ( $category_filtering == '1' ) { echo 'checked'; } ?>> 
									</div>
									<label class="sap-admin-switch">
										<input type="checkbox" class="sap-admin-option-toggle" data-inputname="category_filtering" <?php if ( $category_filtering == '1' ) { echo 'checked'; } ?>>
										<span class="sap-admin-switch-slider round"></span>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope='row'><?php _e('Enable Tag Filtering', 'color-filters'); ?></th>
							<td class='ewd-uwcf-welcome-screen-option'>
								<fieldset>
									<div class="sap-admin-hide-radios">
										<input type='checkbox' name='tag_filtering' value='1' <?php if ( $tag_filtering == '1' ) { echo 'checked'; } ?>>
									</div>
									<label class="sap-admin-switch">
										<input type="checkbox" class="sap-admin-option-toggle" data-inputname="tag_filtering" <?php if ( $tag_filtering == '1' ) { echo 'checked'; } ?>>
										<span class="sap-admin-switch-slider round"></span>
									</label>
								</fieldset>
							</td>
						</tr>
					</table>
		
					<div class='ewd-uwcf-welcome-screen-save-options-button'><?php _e('Save Options', 'color-filters'); ?></div>
					<div class='ewd-uwcf-welcome-clear'></div>
					<div class='ewd-uwcf-welcome-screen-previous-button' data-previousaction='table-mode'><?php _e('Previous', 'color-filters'); ?></div>
					<div class='ewd-uwcf-welcome-screen-next-button' data-nextaction='taxonomies'><?php _e('Next', 'color-filters'); ?></div>
					
					<div class='ewd-uwcf-clear'></div>
				</div>
			</div>

			<div class='ewd-uwcf-welcome-screen-box ewd-uwcf-welcome-screen-taxonomies' data-screen='taxonomies'>
				<h2><?php _e('3. Taxonomies', 'color-filters'); ?></h2>
				<div class='ewd-uwcf-welcome-screen-box-content'>
					<div id='ewd-uwcf-welcome-screen-color-taxonomy' class='<?php echo ( ! $color_filtering ? 'ewd-uwcf-hidden' : '' ); ?>'>
						<h3><?php _e('Colors', 'color-filters'); ?></h3>
						<p><?php _e('Colors let you show what colors an item is available in.', 'color-filters'); ?></p>
						<table class='form-table ewd-uwcf-welcome-screen-created-colors'>
							<tr class='ewd-uwcf-welcome-screen-add-color-name ewd-uwcf-welcome-screen-box-content-divs'>
								<th scope='row'><?php _e( 'Color Name', 'color-filters' ); ?></th>
								<td class='ewd-uwcf-welcome-screen-option'>
									<input type='text'>
								</td>
							</tr>
							<tr class='ewd-uwcf-welcome-screen-add-color-description ewd-uwcf-welcome-screen-box-content-divs'>
								<th scope='row'><?php _e( 'Color Description', 'color-filters' ); ?></th>
								<td class='ewd-uwcf-welcome-screen-option'>
									<textarea></textarea>
								</td>
							</tr>
							<tr>
								<th scope='row'><?php _e( 'Color', 'color-filters' ); ?></th>
								<td>
									<div id="normal_fill_color_picker" class="colorSelector small-text"><div></div></div>		
									<input class="ewd-uwcf-color sap-spectrum" name="normal_fill" id="normal_fill_color" type="text">
								</td>
							</tr>
							<tr>
								<th scope='row'></th>
								<td class='ewd-uwcf-color-image-upload'>
									<label><?php _e("Or upload an image of the color pattern below:", 'color-filter'); ?></label>
									<input id="color_image" type="text" size="36" name="color_image" value="http://">
									<input id="color_image_button" class="button" type="button" value="Upload Image">
								</td>
							</tr>
							<tr>
								<th scope='row'></th>
								<td>
									<div class='ewd-uwcf-welcome-screen-add-color-button'><?php _e('Add Color', 'color-filters'); ?></div>
								</td>
							</tr>
							<tr></tr>
							<tr>
								<td colspan="2">
									<h3><?php _e('Created Colors', 'color-filters'); ?></h3>
									<table class='ewd-uwcf-welcome-screen-show-created-colors'>
										<tr>
											<th class='ewd-uwcf-welcome-screen-show-created-colors-name'><?php _e('Name', 'color-filters'); ?></th>
											<th class='ewd-uwcf-welcome-screen-show-created-colors-description'><?php _e('Description', 'color-filters'); ?></th>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>

					<div id='ewd-uwcf-welcome-screen-size-taxonomy' class='<?php echo ( ! $size_filtering ? 'ewd-uwcf-hidden' : '' ); ?>'>
						<h3><?php _e('Sizes', 'color-filters'); ?></h3>
						<p><?php _e('Sizes let you show what sizes an item is available in.', 'color-filters'); ?></p>
						<table class='form-table ewd-uwcf-welcome-screen-created-sizes'>
							<tr class='ewd-uwcf-welcome-screen-add-size-name ewd-uwcf-welcome-screen-box-content-divs'>
								<th scope='row'><?php _e( 'Size Name', 'color-filters' ); ?></th>
								<td class='ewd-uwcf-welcome-screen-option'>
									<input type='text'>
								</td>
							</tr>
							<tr class='ewd-uwcf-welcome-screen-add-size-description ewd-uwcf-welcome-screen-box-content-divs'>
								<th scope='row'><?php _e( 'Size Description', 'color-filters' ); ?></th>
								<td class='ewd-uwcf-welcome-screen-option'>
									<textarea></textarea>
								</td>
							</tr>
							<tr>
								<th scope='row'></th>
								<td>
									<div class='ewd-uwcf-welcome-screen-add-size-button'><?php _e('Add Size', 'color-filters'); ?></div>
								</td>
							</tr>
							<tr></tr>
							<tr>
								<td colspan="2">
									<h3><?php _e('Created Sizes', 'color-filters'); ?></h3>
									<table class='ewd-uwcf-welcome-screen-show-created-sizes'>
										<tr>
											<th class='ewd-uwcf-welcome-screen-show-created-sizes-name'><?php _e('Name', 'color-filters'); ?></th>
											<th class='ewd-uwcf-welcome-screen-show-created-sizes-description'><?php _e('Description', 'color-filters'); ?></th>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
					
					<div class='ewd-uwcf-welcome-screen-previous-button ewd-uwcf-welcome-screen-previous-button-not-top-margin' data-previousaction='options'><?php _e('Previous Step', 'color-filters'); ?></div>
					<div class='ewd-uwcf-welcome-screen-finish-button'><a href='admin.php?page=ewd-uwcf-settings'><?php _e('Finish', 'ultimate-faqs'); ?></a></div>
					<div class='clear'></div>
				</div>
			</div>
		
			<div class='ewd-uwcf-welcome-screen-skip-container'>
				<a href='admin.php?page=ewd-uwcf-settings'><div class='ewd-uwcf-welcome-screen-skip-button'><?php _e('Skip Setup', 'color-filters'); ?></div></a>
			</div>
		</div>

	<?php }
}


?>