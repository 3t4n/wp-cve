<?php
	/* ----------------------------------------------------------------------------------------------------

		BEAMER SETTINGS
		Handles all the settings in the Beamer options page

	---------------------------------------------------------------------------------------------------- */

	// BEAMER SETTINGS CLASS
	class BeamerSettings {
		private $beamer_settings_options;
		// Construct
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'beamer_settings_add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'beamer_settings_page_init' ) );
		}

		// Add settings page
		public function beamer_settings_add_plugin_page() {
			add_options_page(
				'Beamer Settings', // page_title
				'Beamer Settings', // menu_title
				'manage_options', // capability
				'beamer-settings', // menu_slug
				array( $this, 'beamer_settings_create_admin_page' ) // function
			);
		}

		// Create settings page
		public function beamer_settings_create_admin_page() {
			$this->beamer_settings_options = get_option( 'beamer_settings_option_name' );
			include('beamer-settings-panel.php');
		}

		// Add setting page elements
		public function beamer_settings_page_init() {
			// Register settings
			register_setting(
				'beamer_settings_option_name', // option_group
				'beamer_settings_option_name', // option_name
				array( $this, 'beamer_settings_sanitize' ) // sanitize_callback
			);
			// Settings sections -------------------------------------------------------
				// Add general settings section
				add_settings_section(
					'beamer_settings_setting_section', // id
					'General Settings', // title
					array( $this, 'beamer_settings_section_info' ), // callback
					'beamer-settings-admin' // page
				);
				// Add menu settings section
				add_settings_section(
					'beamer_settings_menu_section', // id
					'Menu Options', // title
					array( $this, 'beamer_settings_menu_section_info' ), // callback
					'beamer-settings-admin' // page
				);
				// Add advanced settings section
				add_settings_section(
					'beamer_settings_advanced_section', // id
					'Advanced Options', // title
					array( $this, 'beamer_settings_advanced_section_info' ), // callback
					'beamer-settings-admin' // page
				);
				// Add master settings section
				add_settings_section(
					'beamer_settings_api_section', // id
					'Beamer API', // title
					array( $this, 'beamer_settings_api_section_info' ), // callback
					'beamer-settings-admin' // page
				);
				// Add user settings section
				add_settings_section(
					'beamer_settings_user_section', // id
					'User Options', // title
					array( $this, 'beamer_settings_user_section_info' ), // callback
					'beamer-settings-admin' // page
				);
				// Add master settings section
				add_settings_section(
					'beamer_settings_master_section', // id
					'Filter Options', // title
					array( $this, 'beamer_settings_master_section_info' ), // callback
					'beamer-settings-admin' // page
				);

			// Settings fields -------------------------------------------------------
				// Field: product-id
				add_settings_field(
					'product_id', // id
					'Product ID', // title
					array( $this, 'product_id_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_setting_section' // section
				);
				// Field: selector
				add_settings_field(
					'selector', // id
					'Selector', // title
					array( $this, 'selector_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_setting_section' // section
				);

				// Field: display
				add_settings_field(
					'display', // id
					'Display', // title
					array( $this, 'display_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_setting_section' // section
				);
				// Field: top
				add_settings_field(
					'top', // id
					'Top', // title
					array( $this, 'top_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_setting_section' // section
				);
				// Field: right
				add_settings_field(
					'right', // id
					'Right', // title
					array( $this, 'right_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_setting_section' // section
				);
				// Field: bottom
				add_settings_field(
					'bottom', // id
					'Bottom', // title
					array( $this, 'bottom_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_setting_section' // section
				);
				// Field: left
				add_settings_field(
					'left', // id
					'Left', // title
					array( $this, 'left_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_setting_section' // section
				);
				// Field: button_position
				add_settings_field(
					'button_position', // id
					'Button Position', // title
					array( $this, 'button_position_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_setting_section' // section
				);
				// Field: button_default
				add_settings_field(
					'button_default', // id
					'Default Button', // title
					array( $this, 'button_default_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_setting_section' // section
				);
				// Field: language (advanced)
				add_settings_field(
					'language', // id
					'Language', // title
					array( $this, 'language_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_advanced_section' // section
				);
				// Field: filters (advanced)
				add_settings_field(
					'filters', // id
					'Filter', // title
					array( $this, 'filters_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_advanced_section' // section
				);
				// Field: lazy (advanced; checkbox)
				add_settings_field(
					'lazy', // id
					'Lazy', // title
					array( $this, 'lazy_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_advanced_section' // section
				);
				// Field: alert (advanced; checkbox)
				add_settings_field(
					'alert', // id
					'Alert', // title
					array( $this, 'alert_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_advanced_section' // section
				);
				// Field: delay (advanced)
				add_settings_field(
					'delay', // id
					'Delay', // title
					array( $this, 'delay_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_advanced_section' // section
				);
				// Field: callback (advanced)
				add_settings_field(
					'callback', // id
					'Callback', // title
					array( $this, 'callback_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_advanced_section' // section
				);
				// Field: button_icon
				add_settings_field(
					'button_icon', // id
					'Button Icon', // title
					array( $this, 'button_icon_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_advanced_section' // section
				);
				// Field: unread_counter (checkbox)
				add_settings_field(
					'unread_counter', // id
					'Disable unread counter', // title
					array( $this, 'unread_counter_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_advanced_section' // section
				);
				// Field: push_prompt
				add_settings_field(
					'push_prompt', // id
					'Push permission prompt', // title
					array( $this, 'push_prompt_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_advanced_section' // section
				);
				// Field: user (user; checkbox)
				add_settings_field(
					'user', // id
					'Catch user data', // title
					array( $this, 'user_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_user_section' // section
				);
				// Field: user (user; checkbox)
				add_settings_field(
					'user', // id
					'Catch user data', // title
					array( $this, 'user_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_user_section' // section
				);
				// Field: user filter (user; checkbox)
				add_settings_field(
					'userfilter', // id
					'Enable user filter', // title
					array( $this, 'userfilter_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_user_section' // section
				);
				// Field: logged (master; checkbox)
				add_settings_field(
					'logged', // id
					'Logged users only', // title
					array( $this, 'logged_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_user_section' // section
				);

				// FILTER fields ---------------------------------------
				// Field: filter operator (master; checkbox)
				add_settings_field(
					'filterop', // id
					'', // title
					array( $this, 'filterop_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_master_section' // section
				);

				// Field: filter front (master; checkbox)
				add_settings_field(
					'nohome', // id
					'...the home page', // title
					array( $this, 'nohome_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_master_section' // section
				);
				// Field: filter front (master; checkbox)
				add_settings_field(
					'nofront', // id
					'...the front page', // title
					array( $this, 'nofront_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_master_section' // section
				);

				// Field: filter posts (master; checkbox)
				add_settings_field(
					'noposts', // id
					'...posts', // title
					array( $this, 'noposts_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_master_section' // section
				);
				// Field: filter pages (master; checkbox)
				add_settings_field(
					'nopages', // id
					'...pages', // title
					array( $this, 'nopages_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_master_section' // section
				);
				// Field: filter archive (master; checkbox)
				add_settings_field(
					'noarchive', // id
					'...archives', // title
					array( $this, 'noarchive_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_master_section' // section
				);
				// Field: post type (master)
				add_settings_field(
					'notype', // id
					'...these post types', // title
					array( $this, 'notype_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_master_section' // section
				);
				// Field: filter id (master)
				add_settings_field(
					'noid', // id
					'...posts with these IDs', // title
					array( $this, 'noid_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_master_section' // section
				);
				// Field: mobile (master; checkbox)
				add_settings_field(
					'mobile', // id
					'Disable for mobile', // title
					array( $this, 'mobile_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_master_section' // section
				);
				// Field: master (master; checkbox)
				add_settings_field(
					'master', // id
					'Master Switch', // title
					array( $this, 'master_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_master_section' // section
				);

				// API fields ---------------------------------------

				// Field: API set (api; checkbox)
				add_settings_field(
					'api_set', // id
					'Connect to API', // title
					array( $this, 'api_set_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_api_section' // section
				);
				// Field: API key (api)
				add_settings_field(
					'api_key', // id
					'API Key', // title
					array( $this, 'api_key_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_api_section' // section
				);
				// Field: API disable autopost
				add_settings_field(
					'api_autopost', // id
					'Disable default autopost', // title
					array( $this, 'api_autopost_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_api_section' // section
				);
				// Field: API disable autopost for already published posts
				add_settings_field(
					'api_autopostold', // id
					'Disable default autopost for published posts', // title
					array( $this, 'api_autopostold_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_api_section' // section
				);
				// Field: API pages
				add_settings_field(
					'api_page', // id
					'Enable for pages', // title
					array( $this, 'api_page_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_api_section' // section
				);
				// Field: API types (api; checkbox)
				add_settings_field(
					'api_types', // id
					'Enable for custom post types', // title
					array( $this, 'api_types_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_api_section' // section
				);
				// Field: API default type
				add_settings_field(
					'api_typedefault', // id
					'Disable default autopost for custom post types', // title
					array( $this, 'api_typedefault_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_api_section' // section
				);
				// Field: API default type
				add_settings_field(
					'api_typeonly', // id
					'Enable default autopost only for some post types', // title
					array( $this, 'api_typeonly_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_api_section' // section
				);
				// Field: API excerpt (api)
				add_settings_field(
					'api_excerpt', // id
					'Default excerpt length', // title
					array( $this, 'api_excerpt_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_api_section' // section
				);
				// Field: API readmore (api)
				add_settings_field(
					'api_readmore', // id
					'Default "Read More" text', // title
					array( $this, 'api_readmore_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_api_section' // section
				);
				// Field: API thumbnail (api; checkbox)
				add_settings_field(
					'api_thumbnail', // id
					'Ignore Thumbnail', // title
					array( $this, 'api_thumbnail_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_api_section' // section
				);

				// Field: lazy (advanced; checkbox)
				add_settings_field(
					'menu_custom', // id
					'Menu icon mode', // title
					array( $this, 'menu_custom_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_menu_section' // section
				);
				// Field: menu-icon
				add_settings_field(
					'menu_icon', // id
					'Menu icon', // title
					array( $this, 'menu_icon_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_menu_section' // section
				);
				// Field: menu-color
				add_settings_field(
					'menu_color', // id
					'Color (HEX) #', // title
					array( $this, 'menu_color_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_menu_section' // section
				);
				// Field: menu-hover
				add_settings_field(
					'menu_hover', // id
					'Hover color (HEX) #', // title
					array( $this, 'menu_hover_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_menu_section' // section
				);
				// Field: menu-font
				add_settings_field(
					'menu_font', // id
					'Icon size (px)', // title
					array( $this, 'menu_font_callback' ), // callback
					'beamer-settings-admin', // page
					'beamer_settings_menu_section' // section
				);
		}

		// Sanitize fields
		public function beamer_settings_sanitize($input) {
			$sanitary_values = array();
			if ( isset( $input['product_id'] ) ) {
				$sanitary_values['product_id'] = sanitize_text_field( $input['product_id'] );
			}
			if ( isset( $input['selector'] ) ) {
				$sanitary_values['selector'] = sanitize_text_field( $input['selector'] );
			}
			// Advenced
			if ( isset( $input['display'] ) ) {
				$sanitary_values['display'] = $input['display'];
			}
			if ( isset( $input['top'] ) ) {
				$sanitary_values['top'] = sanitize_text_field( $input['top'] );
			}
			if ( isset( $input['right'] ) ) {
				$sanitary_values['right'] = sanitize_text_field( $input['right'] );
			}
			if ( isset( $input['bottom'] ) ) {
				$sanitary_values['bottom'] = sanitize_text_field( $input['bottom'] );
			}
			if ( isset( $input['left'] ) ) {
				$sanitary_values['left'] = sanitize_text_field( $input['left'] );
			}
			if ( isset( $input['button_position'] ) ) {
				$sanitary_values['button_position'] = $input['button_position'];
			}
			if ( isset( $input['button_default'] ) ) {
				$sanitary_values['button_default'] = $input['button_default'];
			}
			if ( isset( $input['language'] ) ) {
				$sanitary_values['language'] = sanitize_text_field( $input['language'] );
			}
			if ( isset( $input['filters'] ) ) {
				$sanitary_values['filters'] = sanitize_text_field( $input['filters'] );
			}
			if ( isset( $input['lazy'] ) ) {
				$sanitary_values['lazy'] = $input['lazy'];
			}
			if ( isset( $input['alert'] ) ) {
				$sanitary_values['alert'] = $input['alert'];
			}
			if ( isset( $input['delay'] ) ) {
				$sanitary_values['delay'] = sanitize_text_field( $input['delay'] );
			}
			if ( isset( $input['callback'] ) ) {
				$sanitary_values['callback'] = sanitize_text_field( $input['callback'] );
			}
			if ( isset( $input['button_icon'] ) ) {
				$sanitary_values['button_icon'] = $input['button_icon'];
			}
			if ( isset( $input['unread_counter'] ) ) {
				$sanitary_values['unread_counter'] = $input['unread_counter'];
			}
			if ( isset( $input['push_prompt'] ) ) {
				$sanitary_values['push_prompt'] = $input['push_prompt'];
			}
			// User
			if ( isset( $input['user'] ) ) {
				$sanitary_values['user'] = $input['user'];
			}
			if ( isset( $input['userfilter'] ) ) {
				$sanitary_values['userfilter'] = $input['userfilter'];
			}
			if ( isset( $input['logged'] ) ) {
				$sanitary_values['logged'] = $input['logged'];
			}

			// Master
			if ( isset( $input['mobile'] ) ) {
				$sanitary_values['mobile'] = $input['mobile'];
			}
			if ( isset( $input['filterop'] ) ) {
				$sanitary_values['filterop'] = $input['filterop'];
			}
			if ( isset( $input['nofront'] ) ) {
				$sanitary_values['nofront'] = $input['nofront'];
			}
			if ( isset( $input['nohome'] ) ) {
				$sanitary_values['nohome'] = $input['nohome'];
			}
			if ( isset( $input['noposts'] ) ) {
				$sanitary_values['noposts'] = $input['noposts'];
			}
			if ( isset( $input['nopages'] ) ) {
				$sanitary_values['nopages'] = $input['nopages'];
			}
			if ( isset( $input['noarchive'] ) ) {
				$sanitary_values['noarchive'] = $input['noarchive'];
			}
			if ( isset( $input['noid'] ) ) {
				$sanitary_values['noid'] = sanitize_text_field( $input['noid'] );
			}
			if ( isset( $input['notype'] ) ) {
				$sanitary_values['notype'] = sanitize_text_field( $input['notype'] );
			}
			if ( isset( $input['master'] ) ) {
				$sanitary_values['master'] = $input['master'];
			}
			if ( isset( $input['api_set'] ) ) {
				$sanitary_values['api_set'] = $input['api_set'];
			}
			if ( isset( $input['api_key'] ) ) {
				$sanitary_values['api_key'] = sanitize_text_field( $input['api_key'] );
			}
			if ( isset( $input['api_autopost'] ) ) {
				$sanitary_values['api_autopost'] = $input['api_autopost'];
			}
			if ( isset( $input['api_autopostold'] ) ) {
				$sanitary_values['api_autopostold'] = $input['api_autopostold'];
			}
			if ( isset( $input['api_types'] ) ) {
				$sanitary_values['api_types'] = $input['api_types'];
			}
			if ( isset( $input['api_typedefault'] ) ) {
				$sanitary_values['api_typedefault'] = sanitize_text_field( $input['api_typedefault'] );
			}
			if ( isset( $input['api_typeonly'] ) ) {
				$sanitary_values['api_typeonly'] = sanitize_text_field( $input['api_typeonly'] );
			}
			if ( isset( $input['api_page'] ) ) {
				$sanitary_values['api_page'] = $input['api_page'];
			}
			if ( isset( $input['api_excerpt'] ) ) {
				$sanitary_values['api_excerpt'] = sanitize_text_field( $input['api_excerpt'] );
			}
			if ( isset( $input['api_readmore'] ) ) {
				$sanitary_values['api_readmore'] = sanitize_text_field( $input['api_readmore'] );
			}
			if ( isset( $input['api_thumbnail'] ) ) {
				$sanitary_values['api_thumbnail'] = $input['api_thumbnail'];
			}

			// Menu values
			if ( isset( $input['menu_custom'] ) ) {
				$sanitary_values['menu_custom'] = $input['menu_custom'];
			}
			if ( isset( $input['menu_icon'] ) ) {
				$sanitary_values['menu_icon'] = $input['menu_icon'];
			}
			if ( isset( $input['menu_color'] ) ) {
				$sanitary_values['menu_color'] = sanitize_text_field( $input['menu_color'] );
			}
			if ( isset( $input['menu_hover'] ) ) {
				$sanitary_values['menu_hover'] = sanitize_text_field( $input['menu_hover'] );
			}
			if ( isset( $input['menu_font'] ) ) {
				$sanitary_values['menu_font'] = sanitize_text_field( $input['menu_font'] );
			}

			return $sanitary_values;
		}

			// Beamer Sections Info
			public function beamer_settings_section_info() {
				echo('<div class="bmrNotice">Your <strong>product ID</strong> is the code that appears at the top of the screen in your <a href="'.bmr_url('home', 'app', false).'" target="_blank" rel="nofollow">Beamer Dashboard</a></div><div>To set your <b>Beamer embed</b> just add your <strong>Product ID.</strong> You can customize your embed with the advanced parameters. For more information please read our <a href="'.bmr_url('docs', 'www').'" target="_blank">Documentation.</a></div>');
			}

			// Beamer Menu Info
			public function beamer_settings_menu_section_info() {
				echo('<div>If you have a customized <b>menu item</b> instead of the default button, you can change its appearance here. If you don\'t have a custom menu item and you want to add one click <a href="nav-menus.php" target="_self">here</a>.</div>');
			}

			// Beamer Advanced Info
			public function beamer_settings_advanced_section_info() {
				echo('<div>Customize the <b>Beamer embed</b>. For more information on each parameter and customization option please read our <a href="'.bmr_url('docs', 'www').'" target="_blank">Documentation.</a></div>');
			}

			// Beamer User Info
			public function beamer_settings_user_section_info() {
				echo('<div><b>Beamer</b> can track the users info (name, surname and email) as long as they are logged in their Wordpress accounts (recommended only for Wordpress sites that have subscribers).</div>');
				echo('<div class="bmrNotice warning">This feature uses the <code>is_user_logged_in()</code> Wordpress function. If you are using other log in method it may not work properly.</div>');
			}

			// Beamer Master Info
			public function beamer_settings_master_section_info() {
				echo('<div><b>Beamer</b> can be disabled in some devices or pages using general and specific filters.</div>');
				echo('<div class="demo"><span class="heading">Where will Beamer show up?</span><div class="operator">');
				// HERE!!!


				echo('</div></div>');
			}

			// Beamer API Info
			public function beamer_settings_api_section_info() {
				echo('<div>The <b>Beamer API</b> will connect to your Wordpress site. Each post you publish in Wordpress will be also published in your Beamer feed. You can change the specific settings for each post and pick what to add and what to ignore during editing.</div>');
			}

		// Callbacks
			// Product ID
			public function product_id_callback() {
				printf(
					'<input class="regular-text" type="text" name="beamer_settings_option_name[product_id]" id="bmr-product_id" value="%s"><div class="bmrTip">This code identifies your product. <span>Required</span></div>',
					isset( $this->beamer_settings_options['product_id'] ) ? sanitize_text_field( $this->beamer_settings_options['product_id'] ) : ''
				);
			}
			// Selector
			public function selector_callback() {
				printf(
					'<input class="regular-text" type="text" name="beamer_settings_option_name[selector]" id="bmr-selector" value="%s"><div class="bmrTip">HTML id for the DOM element to be used as a trigger to show the panel. <span>Optional</span></div>',
					isset( $this->beamer_settings_options['selector'] ) ? sanitize_text_field( $this->beamer_settings_options['selector']) : ''
				);
			}
			// Button Position
			public function display_callback() {
				?> <select name="beamer_settings_option_name[display]" id="bmr-display">
					<?php $selected = (isset( $this->beamer_settings_options['display'] ) && $this->beamer_settings_options['display'] === 'right') ? 'selected' : '' ; ?>
					<option value="right" <?php echo $selected; ?>>Right</option>
					<?php $selected = (isset( $this->beamer_settings_options['display'] ) && $this->beamer_settings_options['display'] === 'left') ? 'selected' : '' ; ?>
					<option value="left" <?php echo $selected; ?>>Left</option>
					<?php $selected = (isset( $this->beamer_settings_options['display'] ) && $this->beamer_settings_options['display'] === 'popup') ? 'selected' : '' ; ?>
					<option value="popup" <?php echo $selected; ?>>Popup</option>
				</select> <div class="bmrTip">Side on which the Beamer panel will be shown in your site. <span>Optional</span></div> <?php
			}
			// Top
			public function top_callback() {
				printf(
					'<input class="regular-text" type="number" name="beamer_settings_option_name[top]" id="bmr-top" value="%s" placeholder="0" style="width:100px;"> <div class="bmrTip">Top position offset for the notification bubble.</div>',
					isset( $this->beamer_settings_options['top'] ) ? sanitize_text_field( $this->beamer_settings_options['top'] ) : ''
				);
			}
			// Right
			public function right_callback() {
				printf(
					'<input class="regular-text" type="number" name="beamer_settings_option_name[right]" id="bmr-right" value="%s" placeholder="0" style="width:100px;"> <div class="bmrTip">Right position offset for the notification bubble. <span>Optional</span></div>',
					isset( $this->beamer_settings_options['right'] ) ? sanitize_text_field( $this->beamer_settings_options['right'] ) : ''
				);
			}
			// Bottom
			public function bottom_callback() {
				printf(
					'<input class="regular-text" type="number" name="beamer_settings_option_name[bottom]" id="bmr-bottom" value="%s" placeholder="0" style="width:100px;"> <div class="bmrTip">Bottom position offset for the notification bubble. <span>Optional</span></div>',
					isset( $this->beamer_settings_options['bottom'] ) ? sanitize_text_field( $this->beamer_settings_options['bottom'] ) : ''
				);
			}
			// Left
			public function left_callback() {
				printf(
					'<input class="regular-text" type="number" name="beamer_settings_option_name[left]" id="bmr-left" value="%s" placeholder="0" style="width:100px;"> <div class="bmrTip">Left position offset for the notification bubble. <span>Optional</span></div>',
					isset( $this->beamer_settings_options['left'] ) ? sanitize_text_field( $this->beamer_settings_options['left'] ) : ''
				);
			}
			// Button Position
			public function button_position_callback() {
				?> <select name="beamer_settings_option_name[button_position]" id="bmr-button_position">
					<?php $selected = (isset( $this->beamer_settings_options['button_position'] ) && $this->beamer_settings_options['button_position'] === 'bottom-right') ? 'selected' : '' ; ?>
					<option value="bottom-right" <?php echo $selected; ?>>Bottom Right</option>
					<?php $selected = (isset( $this->beamer_settings_options['button_position'] ) && $this->beamer_settings_options['button_position'] === 'bottom-left') ? 'selected' : '' ; ?>
					<option value="bottom-left" <?php echo $selected; ?>>Bottom Left</option>
					<?php $selected = (isset( $this->beamer_settings_options['button_position'] ) && $this->beamer_settings_options['button_position'] === 'top-left') ? 'selected' : '' ; ?>
					<option value="top-left" <?php echo $selected; ?>>Top Left</option>
					<?php $selected = (isset( $this->beamer_settings_options['button_position'] ) && $this->beamer_settings_options['button_position'] === 'top-right') ? 'selected' : '' ; ?>
					<option value="top-right" <?php echo $selected; ?>>Top Right</option>
				</select> <div class="bmrTip">Position for the notification button (which opens the Beamer panel) that shows up when the selector parameter is not set. </div> <?php
			}
			// Button Default
			public function button_default_callback() {
				?> <select name="beamer_settings_option_name[button_default]" id="bmr-button_default">
					<?php $selected = (isset( $this->beamer_settings_options['button_default'] ) && $this->beamer_settings_options['button_default'] === 'on') ? 'selected' : '' ; ?>
					<option value="on" <?php echo $selected; ?>>ON</option>
					<?php $selected = (isset( $this->beamer_settings_options['button_default'] ) && $this->beamer_settings_options['button_default'] === 'off') ? 'selected' : '' ; ?>
					<option value="off" <?php echo $selected; ?>>OFF</option>
				</select> <div class="bmrTip">If this option is <b>turned off</b> the default button will not show up, even if there's no selector or trigger present.</div> <?php
			}
			// Language
			public function language_callback() {
				printf(
					'<input class="regular-text" type="text" name="beamer_settings_option_name[language]" id="bmr-language" value="%s" placeholder="EN"> <div class="bmrTip">Retrieve only posts that have a translation in this language. <span>Optional</span></div>',
					isset( $this->beamer_settings_options['language'] ) ? sanitize_text_field( $this->beamer_settings_options['language']) : ''
				);
			}
			// Filters
			public function filters_callback() {
				printf(
					'<input class="regular-text" type="text" name="beamer_settings_option_name[filters]" id="bmr-filters" value="%s"> <div class="bmrTip">If there is a value in this field Beamer will retrieve only the posts with a <b>Segment filter</b> or tag that matches or includes that value. If there\'s no value in this field all posts will be retrieved. If you add multiple values separate them with semicolons (;). <a href="https://www.getbeamer.com/help/how-to-use-segmentation/" target="_blank">Learn more here</a> <span>Optional</span></div>',
					isset( $this->beamer_settings_options['filters'] ) ? sanitize_text_field( $this->beamer_settings_options['filters']) : ''
				);
			}
			// Lazy
			public function lazy_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[lazy]" id="bmr-lazy" value="lazy" %s> <label for="lazy">If <b>checked</b>, the Beamer plugin won’t be initialized until the method Beamer.init is called.</label>',
					( isset( $this->beamer_settings_options['lazy'] ) && $this->beamer_settings_options['lazy'] === 'lazy' ) ? 'checked' : ''
				);
			}
			// Alert
			public function alert_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[alert]" id="bmr-alert" value="alert" %s> <label for="alert">If <b>checked</b>, the selector parameter will be ignored and it won\'t open the panel when clicked (Beamer will activate only with the methods Beamer.show and Beamer.hide)</label>',
					( isset( $this->beamer_settings_options['alert'] ) && $this->beamer_settings_options['alert'] === 'alert' ) ? 'checked' : ''
				);
			}
			// Delay
			public function delay_callback() {
				printf(
					'<input class="regular-text" type="number" name="beamer_settings_option_name[delay]" id="bmr-delay" value="%s" style="width:100px;" placeholder="0"> <div class="bmrTip">Delay (in milliseconds) before initializing Beamer.</div>',
					isset( $this->beamer_settings_options['delay'] ) ? sanitize_text_field( $this->beamer_settings_options['delay']) : ''
				);
			}
			// Callbacks
			public function callback_callback() {
				printf(
					'<input class="regular-text" type="text" name="beamer_settings_option_name[callback]" id="bmr-callback" value="%s"> <div class="bmrTip">Function to be called once the plugin is initialized. Learn more in our <a href="https://www.getbeamer.com/docs/" target="_blank">documentation page</a></div>',
					isset( $this->beamer_settings_options['callback'] ) ? sanitize_text_field( $this->beamer_settings_options['callback']) : ''
				);
			}
			// Button Icon
			public function button_icon_callback() {
				?> <select name="beamer_settings_option_name[button_icon]" id="bmr-button_icon">
					<?php $selected = (isset( $this->beamer_settings_options['button_icon'] ) && $this->beamer_settings_options['button_icon'] === 'flame') ? 'selected' : '' ; ?>
					<option value="flame" <?php echo $selected; ?>>Flame</option>
					<?php $selected = (isset( $this->beamer_settings_options['button_icon'] ) && $this->beamer_settings_options['button_icon'] === 'flame_alt') ? 'selected' : '' ; ?>
					<option value="flame_alt" <?php echo $selected; ?>>Flame Alternative</option>
					<?php $selected = (isset( $this->beamer_settings_options['button_icon'] ) && $this->beamer_settings_options['button_icon'] === 'bell_lines') ? 'selected' : '' ; ?>
					<option value="bell_lines" <?php echo $selected; ?>>Bell Outline</option>
					<?php $selected = (isset( $this->beamer_settings_options['button_icon'] ) && $this->beamer_settings_options['button_icon'] === 'bell_full') ? 'selected' : '' ; ?>
					<option value="bell_full" <?php echo $selected; ?>>Bell Full</option>
					<?php $selected = (isset( $this->beamer_settings_options['button_icon'] ) && $this->beamer_settings_options['button_icon'] === 'thumbtack') ? 'selected' : '' ; ?>
					<option value="thumbtack" <?php echo $selected; ?>>Thumbtack</option>
					<?php $selected = (isset( $this->beamer_settings_options['button_icon'] ) && $this->beamer_settings_options['button_icon'] === 'alert_circle') ? 'selected' : '' ; ?>
					<option value="alert_circle" <?php echo $selected; ?>>Alert Circle</option>
					<?php $selected = (isset( $this->beamer_settings_options['button_icon'] ) && $this->beamer_settings_options['button_icon'] === 'alert_bubble') ? 'selected' : '' ; ?>
					<option value="alert_bubble" <?php echo $selected; ?>>Alert Bubble</option>
					<?php $selected = (isset( $this->beamer_settings_options['button_icon'] ) && $this->beamer_settings_options['button_icon'] === 'bullhorn') ? 'selected' : '' ; ?>
					<option value="bullhorn" <?php echo $selected; ?>>Bullhorn</option>
				</select> <div class="bmrTip">Select the icon of the default Beamer button.</div> <?php
			}
			//
			public function unread_counter_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[unread_counter]" id="bmr-unread_counter" value="unread_counter" %s> <label for="unread_counter">If <b>checked</b>, the unread message counter will not be shown ever.</label>',
					( isset( $this->beamer_settings_options['unread_counter'] ) && $this->beamer_settings_options['unread_counter'] === 'unread_counter' ) ? 'checked' : ''
				);
			}
			// Push Prompt
			public function push_prompt_callback() {
				?> <select name="beamer_settings_option_name[push_prompt]" id="bmr-push_prompt">
					<?php $selected = (isset( $this->beamer_settings_options['push_prompt'] ) && $this->beamer_settings_options['push_prompt'] === 'default') ? 'selected' : '' ; ?>
					<option value="default" <?php echo $selected; ?>>Default</option>
					<?php $selected = (isset( $this->beamer_settings_options['push_prompt'] ) && $this->beamer_settings_options['push_prompt'] === 'disabled') ? 'selected' : '' ; ?>
					<option value="disabled" <?php echo $selected; ?>>Disabled</option>
					<?php $selected = (isset( $this->beamer_settings_options['push_prompt'] ) && $this->beamer_settings_options['push_prompt'] === 'popup') ? 'selected' : '' ; ?>
					<option value="popup" <?php echo $selected; ?>>Dialog</option>
					<?php $selected = (isset( $this->beamer_settings_options['push_prompt'] ) && $this->beamer_settings_options['push_prompt'] === 'sidebar') ? 'selected' : '' ; ?>
					<option value="sidebar" <?php echo $selected; ?>>Sidebar</option>
				</select> <div class="bmrTip">If you have enabled <b>push notifications</b> in your Beamer Dashboard you can pick how the notification prompt will be displayed. If you leave it as <i>Default</i> it will be displayed the same as you have it set on your Beamer Dashboard (just for Pro and Enterprise users).</div> <?php
			}
			// User
			public function user_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[user]" id="bmr-user" value="user" %s> <label for="user">If <b>checked</b>, the Beamer plugin will register the user\'s name, surname and email (as long as they are logged) to be shown in your accounts statistics</label>',
					( isset( $this->beamer_settings_options['user'] ) && $this->beamer_settings_options['user'] === 'user' ) ? 'checked' : ''
				);
			}
			public function userfilter_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[userfilter]" id="bmr-userfilter" value="userfilter" %s> <label for="user">If <b>checked</b>, Beamer will send the post filter <code class="small"><b>wp_logged</b></code> if your user is logged in.</label><br><br><label>It will also send a filter based on the user role (only for default Wordpress roles). <code class="small">wp_admin, wp_editor, wp_author, wp_colab, wp_sub</code></label>',
					( isset( $this->beamer_settings_options['userfilter'] ) && $this->beamer_settings_options['userfilter'] === 'userfilter' ) ? 'checked' : ''
				);
			}
			// Logged
			public function logged_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[logged]" id="bmr-logged" value="logged" %s> <label for="mobile">If <b>checked</b>, the Beamer feed will only be called for <b>logged in</b> users</label>',
					( isset( $this->beamer_settings_options['logged'] ) && $this->beamer_settings_options['logged'] === 'logged' ) ? 'checked' : ''
				);
			}

// FILTERS ------------------------------------------------------------------------------------

			// Only Front
			public function filterop_callback() {
				?> <b>Show Beamer widget</b> <select name="beamer_settings_option_name[filterop]" id="bmr-filterop">
					<?php $selected = (isset( $this->beamer_settings_options['filterop'] ) && $this->beamer_settings_options['filterop'] === 'not') ? 'selected' : '' ; ?>
					<option value="not" <?php echo $selected; ?>>not</option>
					<?php $selected = (isset( $this->beamer_settings_options['filterop'] ) && $this->beamer_settings_options['filterop'] === 'only') ? 'selected' : '' ; ?>
					<option value="only" <?php echo $selected; ?>>only</option>
				</select> <b>in...</b> <?php
			}
			// No Home
			public function nohome_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[nohome]" id="bmr-nohome" value="nohome" %s> <label for="nohome">This is your blog\'s home page</label> <code class="small">is_home()</code>',
					( isset( $this->beamer_settings_options['nohome'] ) && $this->beamer_settings_options['nohome'] === 'nohome' ) ? 'checked' : ''
				);
			}
			// No Front
			public function nofront_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[nofront]" id="bmr-nofront" value="nofront" %s> <label for="nofront">This is your blog\'s home page or your static front page if you picked one</label> <code class="small">is_front_page()</code>',
					( isset( $this->beamer_settings_options['nofront'] ) && $this->beamer_settings_options['nofront'] === 'nofront' ) ? 'checked' : ''
				);
			}
			// No Posts
			public function noposts_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[noposts]" id="bmr-noposts" value="noposts" %s> <label for="noposts">These are all your <b>posts</b> (including custom post types) except attachments and pages</label> <code class="small">is_single()</code>',
					( isset( $this->beamer_settings_options['noposts'] ) && $this->beamer_settings_options['noposts'] === 'noposts' ) ? 'checked' : ''
				);
			}
			// No Pages
			public function nopages_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[nopages]" id="bmr-nopages" value="nopages" %s> <label for="nopages">These are all your <b>pages</b> (posts with the custom post type \'page\')</label> <code class="small">is_page()</code>',
					( isset( $this->beamer_settings_options['nopages'] ) && $this->beamer_settings_options['nopages'] === 'nopages' ) ? 'checked' : ''
				);
			}
			// No Archive
			public function noarchive_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[noarchive]" id="bmr-noarchive" value="noarchive" %s> <label for="noarchive">These are your <b>archive</b> pages</label> <code class="small">is_archive()</code>',
					( isset( $this->beamer_settings_options['noarchive'] ) && $this->beamer_settings_options['noarchive'] === 'noarchive' ) ? 'checked' : ''
				);
			}
			// No ID
			public function noid_callback() {
				printf(
					'<input class="regular-text" type="text" name="beamer_settings_option_name[noid]" id="bmr-noid" value="%s"> <div class="bmrTip">Add IDs separated by commas (Remember that IDs are not the same as URLs)</div>',
					isset( $this->beamer_settings_options['noid'] ) ? sanitize_text_field( $this->beamer_settings_options['noid']) : ''
				);
			}
			// No Type
			public function notype_callback() {
				$types = get_post_types(array(), 'object');
				$public = array();
				foreach($types as $type){
					if($type->public == 1){
						$public[] = $type->name;
					}
				}
				$alltypes = implode(', ', $public);
				printf(
					'<input class="regular-text" type="text" name="beamer_settings_option_name[notype]" id="bmr-notype" value="%s"> <div class="bmrTip">Add any <strong>post type</strong> separated by commas (including custom post types)<br><br><code class="small">Your current public post types are: <strong>'. $alltypes .'</strong></code></div>',
					isset( $this->beamer_settings_options['notype'] ) ? sanitize_text_field( $this->beamer_settings_options['notype'] ) : ''
				);
			}
			// Mobile
			public function mobile_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[mobile]" id="bmr-mobile" value="mobile" %s> <label for="mobile">If <b>checked</b>, the Beamer plugin will not be called on <b>mobile devices</b></label>',
					( isset( $this->beamer_settings_options['mobile'] ) && $this->beamer_settings_options['mobile'] === 'mobile' ) ? 'checked' : ''
				);
			}
			// Master
			public function master_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[master]" id="bmr-master" value="master" %s> <label for="master">Beamer will be disabled completely if this is <b>checked</b> (in all devices)</label>',
					( isset( $this->beamer_settings_options['master'] ) && $this->beamer_settings_options['master'] === 'master' ) ? 'checked' : ''
				);
			}

// API ------------------------------------------------------------------------------------

			// API Set
			public function api_set_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[api_set]" id="bmr-api_set" value="api_set" %s> <label for="api_set">If <b>checked</b>, the Beamer plugin will connect to the Beamer API and your new posts will be also published in the Beamer feed.</label>',
					( isset( $this->beamer_settings_options['api_set'] ) && $this->beamer_settings_options['api_set'] === 'api_set' ) ? 'checked' : ''
				);
			}
			// API Key
			public function api_key_callback() {
				printf(
					'<input class="regular-text" type="text" name="beamer_settings_option_name[api_key]" id="bmr-api_key" value="%s"><div class="bmrTip">This secret code identifies your calls to Beamer. You can get your API key in your <a href="'.bmr_url('settings#api', 'app', false).'" target="_blank" rel="nofollow">Beamer Dashboard > Settings > API</a> <span>Required</span></div>',
					isset( $this->beamer_settings_options['api_key'] ) ? sanitize_text_field( $this->beamer_settings_options['api_key']) : ''
				);
			}
			// API Autopost default
			public function api_autopost_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[api_autopost]" id="bmr-api_autopost" value="api_autopost" %s> <label for="api_autopost">If checked all posts (including post types) will have disabled by default the option to publish directly to Beamer via API. You can enable the feature manually for each individual posts in the edit screen. <b>This option will override other options in this page.</b></label>',
					( isset( $this->beamer_settings_options['api_autopost'] ) && $this->beamer_settings_options['api_autopost'] === 'api_autopost' ) ? 'checked' : ''
				);
			}
			// API Autopost default old
			public function api_autopostold_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[api_autopostold]" id="bmr-api_autopostold" value="api_autopostold" %s> <label for="api_autopostold">If checked all posts (including post types) that are already "published" will have disabled by default the option to publish directly to Beamer via API. You can enable the feature manually for each individual posts in the edit screen. <b>This option will override other options in this page.</b></label>',
					( isset( $this->beamer_settings_options['api_autopostold'] ) && $this->beamer_settings_options['api_autopostold'] === 'api_autopostold' ) ? 'checked' : ''
				);
			}
			// API Types
			public function api_types_callback() {
				$types = get_post_types(array(), 'object');
				$public = array();
				foreach($types as $type){
					if($type->public == 1 && $type->name != 'post' && $type->name != 'page' && $type->name != 'attachment' ){
						$public[] = $type->name;
					}
				}
				if( !empty( $public ) ){
					$allypes = implode(', ', $public);
				}else{
					$allypes = 'none';
				}
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[api_types]" id="bmr-api_types" value="api_types" %s> <label for="api_types">By default the Beamer API will only publish your <b>posts</b> in the Beamer feed (post_type = post). If <b>checked</b>, the Beamer plugin will also publish your <b>custom post types</b>. You can deactivate this individually each time before publishing in the editor view.</label><br><div class="bmrTip">Your current custom post types are: <b>'. $allypes .'</b></div>',
					( isset( $this->beamer_settings_options['api_types'] ) && $this->beamer_settings_options['api_types'] === 'api_types' ) ? 'checked' : ''
				);
			}
			// API Types
			public function api_typedefault_callback() {
				printf(
					'<input class="regular-text" type="text" name="beamer_settings_option_name[api_typedefault]" id="bmr-api_typedefault" value="%s"> <div class="bmrTip">If you enabled the previous option the API autopost will be enabled by default for each custom post of any type. You can write the name of any custom post type here (separated by commas) and the autopost feature will be disabled by default for them. You can manually enable it in the editor screen for each post.</div>',
					isset( $this->beamer_settings_options['api_typedefault'] ) ? sanitize_text_field( $this->beamer_settings_options['api_typedefault']) : ''
				);
			}
			// API Types
			public function api_typeonly_callback() {
				printf(
					'<input class="regular-text" type="text" name="beamer_settings_option_name[api_typeonly]" id="bmr-api_typeonly" value="%s"> <div class="bmrTip">Enable autopost by default <b>only for the post types</b> included in this field (separated by commas). This will override the previous filter. You can manually enable autopost in the editor screen for each post.</div>',
					isset( $this->beamer_settings_options['api_typeonly'] ) ? sanitize_text_field( $this->beamer_settings_options['api_typeonly']) : ''
				);
			}
			// API Page
			public function api_page_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[api_page]" id="bmr-api_page" value="api_page" %s> <label for="api_types">By default the Beamer API will only publish your <b>posts</b> in the Beamer feed (post_type = post). If <b>checked</b>, the Beamer plugin will also publish your <b>pages</b> <code>post type = \'page\'</code>. You can deactivate this individually each time before publishing in the editor view.</label>',
					( isset( $this->beamer_settings_options['api_page'] ) && $this->beamer_settings_options['api_page'] === 'api_page' ) ? 'checked' : ''
				);
			}
			// API Excerpt
			public function api_excerpt_callback() {
				printf(
					'<input class="regular-text" type="number" placeholder="160" name="beamer_settings_option_name[api_excerpt]" id="bmr-api_excerpt" value="%s" style="width:100px;"><div class="bmrTip">The default maximum length of the text that will be shared with your posts (You can change the length in each post\'s Advanced Options). <span>Optional</span></div>',
					isset( $this->beamer_settings_options['api_excerpt'] ) ? sanitize_text_field( $this->beamer_settings_options['api_excerpt']) : ''
				);
			}
			// API Read More
			public function api_readmore_callback() {
				printf(
					'<input class="regular-text" type="text" placeholder="Read more" name="beamer_settings_option_name[api_readmore]" id="bmr-api_readmore" value="%s"><div class="bmrTip">The default text of the link that will be shared with your posts (You can change the specific text in each post\'s Advanced Options). <span>Optional</span></div>',
					isset( $this->beamer_settings_options['api_readmore'] ) ? sanitize_text_field( $this->beamer_settings_options['api_readmore']) : ''
				);
			}
			// API Thumbnail
			public function api_thumbnail_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[api_thumbnail]" id="bmr-api_thumbnail" value="api_thumbnail" %s> <label for="api_thumb">If <b>checked</b>, the Beamer plugin will not use the Featured Image of your post (if it exists) and display only the post content.</label>',
					( isset( $this->beamer_settings_options['api_thumbnail'] ) && $this->beamer_settings_options['api_thumbnail'] === 'api_thumbnail' ) ? 'checked' : ''
				);
			}

			// Menu Icon Active
			public function menu_custom_callback() {
				printf(
					'<input type="checkbox" name="beamer_settings_option_name[menu_custom]" id="bmr-menu_custom" value="menu_custom" %s> <label for="menu_custom">If <b>checked</b> an you have a custom menu item (or selector) instead of the default button it will change to an icon.</label>',
					( isset( $this->beamer_settings_options['menu_custom'] ) && $this->beamer_settings_options['menu_custom'] === 'menu_custom' ) ? 'checked' : ''
				);
			}
			// Menu Icon
			public function menu_icon_callback() {
				?> <select name="beamer_settings_option_name[menu_icon]" id="bmr-menu_icon">
					<?php $selected = (isset( $this->beamer_settings_options['menu_icon'] ) && $this->beamer_settings_options['menu_icon'] === 'notifications') ? 'selected' : '' ; ?>
					<option value="notifications" <?php echo $selected; ?>>Default (Bell)</option>
					<?php $selected = (isset( $this->beamer_settings_options['menu_icon'] ) && $this->beamer_settings_options['menu_icon'] === 'feedback') ? 'selected' : '' ; ?>
					<option value="feedback" <?php echo $selected; ?>>Notifications</option>
					<?php $selected = (isset( $this->beamer_settings_options['menu_icon'] ) && $this->beamer_settings_options['menu_icon'] === 'announcement') ? 'selected' : '' ; ?>
					<option value="announcement" <?php echo $selected; ?>>Announcements</option>
					<?php $selected = (isset( $this->beamer_settings_options['menu_icon'] ) && $this->beamer_settings_options['menu_icon'] === 'whatshot') ? 'selected' : '' ; ?>
					<option value="whatshot" <?php echo $selected; ?>>Hot news</option>
				</select> <div class="bmrTip">If you have a custom menu and the icon mode is enabled you can <b>pick an icon here.</b></div> <?php
			}
			// Menu Icon Color
			public function menu_color_callback() {
				printf(
					'<input class="regular-text" type="text" name="beamer_settings_option_name[menu_color]" id="bmr-menu_color" value="%s" placeholder="inherit" style="width:100px;" maxlength="6"> <div class="bmrTip">If you have a custom menu and the icon mode is enabled you can pick a color for the icon here. It defaults to color of the menu.</div>',
					isset( $this->beamer_settings_options['menu_color'] ) ? sanitize_text_field( $this->beamer_settings_options['menu_color']) : ''
				);
			}
			// Menu Icon Hover
			public function menu_hover_callback() {
				printf(
					'<input class="regular-text" type="text" name="beamer_settings_option_name[menu_hover]" id="bmr-menu_hover" value="%s" placeholder="inherit" style="width:100px;" maxlength="6"> <div class="bmrTip">If you have a custom menu and the icon mode is enabled you can pick a hover color for the icon here. It defaults to the color of the menu.</div>',
					isset( $this->beamer_settings_options['menu_hover'] ) ? sanitize_text_field( $this->beamer_settings_options['menu_hover']) : ''
				);
			}
			// Menu Icon Size
			public function menu_font_callback() {
				printf(
					'<input class="regular-text" type="number" name="beamer_settings_option_name[menu_font]" id="bmr-menu_font" value="%s" placeholder="inherit" style="width:100px;"> <div class="bmrTip">Icon size in pixels. It defaults to the font-size of the menu.</div>',
					isset( $this->beamer_settings_options['menu_font'] ) ? sanitize_text_field( $this->beamer_settings_options['menu_font']) : ''
				);
			}
	}

	if ( is_admin() )
		$beamer_settings = new BeamerSettings();