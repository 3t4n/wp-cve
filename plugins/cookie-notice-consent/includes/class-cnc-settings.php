<?php

defined( 'ABSPATH' ) || die();

class Cookie_Notice_Consent_Settings {
	
	/**
	 * Constructor
	 */
	public function __construct( $instance ) {
		
		$this->cnc = $instance;
		$this->option_groups = $this->cnc->helper->get_option_groups();
		$this->defaults = $this->get_default_options();
		
		// Load options late to allow for get_option filtering by other plugins (WPML / Polylang)
		add_action( 'plugins_loaded', array( $this, 'load_options' ), 15 );
		
		// Initialize settings in admin
		add_action( 'admin_init', array( $this, 'init_settings' ) );
		
	}
	
	/**
	 * Load the database options
	 */
	public function load_options() {
		foreach( $this->option_groups as $slug => $title ) {
			$this->options[$slug] = get_option( "cookie_notice_consent_$slug" );
		}
	}
	
	/**
	 * Get the default options
	 */
	private function get_default_options() {
		return array(
			'general_settings' => array(
				'notice_text' => __( 'We use cookies to measure marketing efforts and improve our services. Please review the cookie settings and confirm your choice.', 'cookie-notice-consent' ),
				'accept_button_label' => __( 'Accept cookies', 'cookie-notice-consent' ),
				'confirm_choice_button_label' => __( 'Confirm choice', 'cookie-notice-consent' ),
				'reject_button_label' => __( 'Reject cookies', 'cookie-notice-consent' ),
				'privacy_policy_button_label' => __( 'Privacy policy', 'cookie-notice-consent' ),
				'revoke_consent_button_label' => __( 'Revoke consent', 'cookie-notice-consent' ),
				'block_embeds' => '0',
				'respect_dnt' => '0',
				'respect_gpc' => '0'
			),
			'design_settings' => array(
				'theme' => 'default'
			),
			'category_essential' => array(
				'label' => __( 'Essential Cookies', 'cookie-notice-consent' ),
				'description' => __( 'Cookies that are essential for the functionality of the website. They can not be rejected.', 'cookie-notice-consent' )
			),
			'category_functional' => array(
				'label' => __( 'Functional Cookies', 'cookie-notice-consent' ),
				'description' => __( 'Cookies that provide basic functionality and enhance the user experience.', 'cookie-notice-consent' )
			),
			'category_marketing' => array(
				'label' => __( 'Marketing Cookies', 'cookie-notice-consent' ),
				'description' => __( 'Cookies that provide functionality for marketing, advertising and targeting.', 'cookie-notice-consent' )
			),
			'consent_settings' => array(
				'log_consents' => '1',
				'anonymize_consent_log_ips' => '1',
				'auto_purge_consents_interval' => '6 months',
				'revoke_delete_all' => '1'
			)
		);
	}
	
	/**
	 * Get saved or default option value
	 */
	public function get_option( $group, $option ) {
		if( !empty( $this->options[$group] ) && array_key_exists( $option, $this->options[$group] ) )
			return $this->options[$group][$option];
		if( array_key_exists( $option, $this->defaults[$group] ) )
			return $this->defaults[$group][$option];
		return NULL;
	}
	
	/**
	 * Render a single settings field checkbox
	 */
	private function render_settings_field_checkbox( $group, $name, $label, $description = '' ) {
		?>
		<input type="hidden" name="cookie_notice_consent_<?php echo $group; ?>[<?php echo $name; ?>]" value="0"><?php /* Workaround for 0-value checkboxes when unchecked instead of removal of option */ ?>
		<input type="checkbox" name="cookie_notice_consent_<?php echo $group; ?>[<?php echo $name; ?>]" id="cookie_notice_consent_<?php echo $group; ?>[<?php echo $name; ?>]" value="1" <?php checked( $this->get_option( $group, $name ), 1 ); ?>>
		<label for="cookie_notice_consent_<?php echo $group; ?>[<?php echo $name; ?>]"><?php echo $label; ?></label>
		<?php
		if( !empty( $description ) ) {
		?>
		<p class="description"><?php echo $description; ?></p>
		<?php
		}
	}

	/**
	 * Render a single settings field text input
	 */
	private function render_settings_field_text( $group, $name, $description = '' ) {
		?>
		<input type="text" name="cookie_notice_consent_<?php echo $group; ?>[<?php echo $name; ?>]" id="cookie_notice_consent_<?php echo $group; ?>[<?php echo $name; ?>]" value="<?php echo esc_html( $this->get_option( $group, $name ) ); ?>" class="regular-text">
		<?php
		if( !empty( $description ) ) {
		?>
		<p class="description"><?php echo $description; ?></p>
		<?php
		}
	}

	/**
	 * Render a single settings field textarea
	 */
	private function render_settings_field_textarea( $group, $name, $description = '', $code = false) {
		?>
		<textarea name="cookie_notice_consent_<?php echo $group; ?>[<?php echo $name; ?>]" id="cookie_notice_consent_<?php echo $group; ?>[<?php echo $name; ?>]" class="large-text<?php echo $code ? ' code' : ''; ?>" rows="<?php echo $code ? '10' : '4'; ?>"><?php echo $this->get_option( $group, $name ); ?></textarea>
		<?php
		if( !empty( $description ) ) {
		?>
		<p class="description"><?php echo $description; ?></p>
		<?php
		}
	}
	
	/**
	 * Initialize the settings sections and fields
	 */
	public function init_settings() {
		
		foreach( $this->option_groups as $slug => $title ) {
			register_setting( 'cookie_notice_consent_' . $slug . '_group', 'cookie_notice_consent_' . $slug );
		}
		
		/******************************************************************
		********* GENERAL SETTINGS ****************************************
		******************************************************************/
		
		add_settings_section(
			'section_general_notice',
			__( 'Notice Settings', 'cookie-notice-consent' ),
			array( $this, 'cb_settings_section_general_notice_settings' ),
			'cookie_notice_consent_general_settings_group'
		);
		
		add_settings_field(
			'notice_text',
			__( 'Notice Text', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_notice_text' ),
			'cookie_notice_consent_general_settings_group',
			'section_general_notice'
		);
		
		add_settings_field(
			'accept_button_label',
			__( 'Accept Button Text', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_accept_button_label' ),
			'cookie_notice_consent_general_settings_group',
			'section_general_notice'
		);
		
		add_settings_field(
			'confirm_choice_button_label',
			__( 'Confirm Choice Button Text', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_confirm_choice_button_label' ),
			'cookie_notice_consent_general_settings_group',
			'section_general_notice'
		);
		
		add_settings_field(
			'reject_button_label',
			__( 'Reject Button Text', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_reject_button_label' ),
			'cookie_notice_consent_general_settings_group',
			'section_general_notice'
		);
		
		add_settings_field(
			'privacy_policy_button_label',
			__( 'Privacy Policy Button Text', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_privacy_policy_button_label' ),
			'cookie_notice_consent_general_settings_group',
			'section_general_notice'
		);
		
		add_settings_field(
			'revoke_consent_button_label',
			__( 'Revoke Consent Button Text', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_revoke_consent_button_label' ),
			'cookie_notice_consent_general_settings_group',
			'section_general_notice'
		);
		
		add_settings_field(
			'reload_on_set',
			__( 'Reload Page', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_reload_on_set' ),
			'cookie_notice_consent_general_settings_group',
			'section_general_notice'
		);
		
		add_settings_section(
			'section_general_privacy',
			__( 'Privacy Settings', 'cookie-notice-consent' ),
			array( $this, 'cb_settings_section_general_privacy_settings' ),
			'cookie_notice_consent_general_settings_group'
		);
		
		add_settings_field(
			'respect_dnt',
			__( 'Respect DNT', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_respect_dnt' ),
			'cookie_notice_consent_general_settings_group',
			'section_general_privacy'
		);
		
		add_settings_field(
			'respect_gpc',
			__( 'Respect GPC', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_respect_gpc' ),
			'cookie_notice_consent_general_settings_group',
			'section_general_privacy'
		);
		
		add_settings_section(
			'section_general_embeds',
			__( 'Embed Settings', 'cookie-notice-consent' ),
			array( $this, 'cb_settings_section_general_embeds_settings' ),
			'cookie_notice_consent_general_settings_group'
		);
		
		add_settings_field(
			'block_embeds',
			__( 'Block embeds', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_block_embeds' ),
			'cookie_notice_consent_general_settings_group',
			'section_general_embeds'
		);
		
		add_settings_section(
			'section_general_plugin',
			__( 'Plugin Settings', 'cookie-notice-consent' ),
			array( $this, 'cb_settings_section_general_plugin_settings' ),
			'cookie_notice_consent_general_settings_group'
		);
		
		add_settings_field(
			'delete_options_on_deactivation',
			__( 'Delete Options', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_delete_options_on_deactivation' ),
			'cookie_notice_consent_general_settings_group',
			'section_general_plugin'
		);
		
		/******************************************************************
		********* DESIGN SETTINGS *****************************************
		******************************************************************/
		
		add_settings_section(
			'section_design',
			__( 'Design Settings', 'cookie-notice-consent' ),
			array( $this, 'cb_settings_section_design_settings' ),
			'cookie_notice_consent_design_settings_group'
		);
		
		add_settings_field(
			'theme',
			__( 'Theme', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_theme' ),
			'cookie_notice_consent_design_settings_group',
			'section_design'
		);
		
		add_settings_field(
			'color_accent',
			__( 'Color Accent', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_color_accent' ),
			'cookie_notice_consent_design_settings_group',
			'section_design'
		);
		
		add_settings_field(
			'show_category_descriptions',
			__( 'Category Descriptions', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_show_category_descriptions' ),
			'cookie_notice_consent_design_settings_group',
			'section_design'
		);
		
		/******************************************************************
		********* CATEGORIES – LOOPED *************************************
		******************************************************************/
		
		$registered_categories = $this->cnc->helper->get_registered_cookie_categories();
		
		foreach( $registered_categories as $slug => $title ) {
			
			add_settings_section(
				'section_' . $slug,
				$title,
				array( $this, 'cb_settings_section_' . $slug ),
				'cookie_notice_consent_' . $slug . '_group'
			);
			
			add_settings_field(
				'active',
				__( 'Status', 'cookie-notice-consent' ),
				array( $this, 'cb_setting_' . $slug . '_active' ),
				'cookie_notice_consent_' . $slug . '_group',
				'section_' . $slug
			);
			
			add_settings_field(
				'label',
				__( 'Label', 'cookie-notice-consent' ),
				array( $this, 'cb_setting_' . $slug . '_label' ),
				'cookie_notice_consent_' . $slug . '_group',
				'section_' . $slug
			);
			
			add_settings_field(
				'description',
				__( 'Description', 'cookie-notice-consent' ),
				array( $this, 'cb_setting_' . $slug . '_description' ),
				'cookie_notice_consent_' . $slug . '_group',
				'section_' . $slug
			);
			
			add_settings_field(
				'code',
				__( 'Code', 'cookie-notice-consent' ),
				array( $this, 'cb_setting_' . $slug . '_code' ),
				'cookie_notice_consent_' . $slug . '_group',
				'section_' . $slug
			);
			
		}
		
		/******************************************************************
		********* CONSENT SETTINGS ****************************************
		******************************************************************/
		
		add_settings_section(
			'section_consent',
			__( 'Consent Settings', 'cookie-notice-consent' ),
			array( $this, 'cb_settings_section_consent_settings' ),
			'cookie_notice_consent_consent_settings_group'
		);
		
		add_settings_field(
			'log_consents',
			__( 'Log Consents', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_log_consents' ),
			'cookie_notice_consent_consent_settings_group',
			'section_consent'
		);
		
		add_settings_field(
			'anonymize_consent_log_ips',
			__( 'Anonymize IPs', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_anonymize_consent_log_ips' ),
			'cookie_notice_consent_consent_settings_group',
			'section_consent'
		);
		
		add_settings_field(
			'auto_purge_consent',
			__( 'Auto-Purge Consents', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_auto_purge_consents' ),
			'cookie_notice_consent_consent_settings_group',
			'section_consent'
		);
		
		add_settings_field(
			'delete_all_cookies_on_revoke',
			__( 'Purge cookies on revoke', 'cookie-notice-consent' ),
			array( $this, 'cb_setting_delete_all_cookies_on_revoke' ),
			'cookie_notice_consent_consent_settings_group',
			'section_consent'
		);
		
	}

	/******************************************************************
	********* GENERAL SETTINGS CALLBACKS ******************************
	******************************************************************/

	public function cb_settings_section_general_notice_settings() {
		echo '<p>' . __( 'Manage general settings for the cookie notice like text and buttons.', 'cookie-notice-consent' ) . '</p>';
	}
	
	public function cb_setting_notice_text() {
		$this->render_settings_field_textarea(
			'general_settings',
			'notice_text',
			__( 'Notice text to be shown on the cookie banner (basic HTML allowed).', 'cookie-notice-consent' )
		);
	}
	
	public function cb_setting_accept_button_label() {
		$this->render_settings_field_text(
			'general_settings',
			'accept_button_label',
			__( 'Button for accepting all cookie categories.', 'cookie-notice-consent' )
		);
	}
	
	public function cb_setting_confirm_choice_button_label() {
		$this->render_settings_field_text(
			'general_settings',
			'confirm_choice_button_label',
			__( 'Button for accepting the selected cookie categories. Will only be shown if any optional categories are active.', 'cookie-notice-consent' )
		);
	}
	
	public function cb_setting_reject_button_label() {
		$this->render_settings_field_text(
			'general_settings',
			'reject_button_label',
			__( 'Button for rejecting all cookie categories.', 'cookie-notice-consent' )
		);
	}
	
	public function cb_setting_privacy_policy_button_label() {
		$this->render_settings_field_text(
			'general_settings',
			'privacy_policy_button_label',
			__( 'Button for visiting the privacy policy. Will only be shown if a Privacy Policy page is set.', 'cookie-notice-consent' )
		);
	}
	
	public function cb_setting_revoke_consent_button_label() {
		$this->render_settings_field_text(
			'general_settings',
			'revoke_consent_button_label',
			__( 'Button for revoking a previously given consent (output via shortcode).', 'cookie-notice-consent' )
		);
	}
	
	public function cb_setting_reload_on_set() {
		$this->render_settings_field_checkbox(
			'general_settings',
			'reload',
			__( 'Reload page after cookie choice has been saved', 'cookie-notice-consent' )
		);
	}
	
	public function cb_settings_section_general_privacy_settings() {
		echo '<p>' . __( 'Manage general privacy settings.', 'cookie-notice-consent' ) . '</p>';
	}
	
	public function cb_setting_respect_dnt() {
		$this->render_settings_field_checkbox(
			'general_settings',
			'respect_dnt',
			__( 'Respect the Do Not Track (DNT) signal that the visitor might send', 'cookie-notice-consent' ),
			__( 'If the client sends the corresponding request, the consent banner will not be output at all.', 'cookie-notice-consent' )
		);
	}
	
	public function cb_setting_respect_gpc() {
		$this->render_settings_field_checkbox(
			'general_settings',
			'respect_gpc',
			__( 'Respect the Global Privacy Control (GPC) signal that the visitor might send', 'cookie-notice-consent' ),
			__( 'If the client sends the corresponding request, the consent banner will not be output at all.', 'cookie-notice-consent' )
		);
	}
	
	public function cb_settings_section_general_embeds_settings() {
		echo '<p>' . __( 'Manage settings for automatic oEmbed blocking.', 'cookie-notice-consent' ) . '</p>';
	}
	
	public function cb_setting_block_embeds() {
		$this->render_settings_field_checkbox(
			'general_settings',
			'block_embeds',
			__( 'EXPERIMENTAL / BETA: Block embedded external content until consent is given (via Functional Cookies)', 'cookie-notice-consent' ),
			__( 'This is a beta feature. It is based on WP filters and might not work in all situations right now (e.g. page builders).', 'cookie-notice-consent' )
		);
	}
	
	public function cb_settings_section_general_plugin_settings() {
		echo '<p>' . __( 'Manage general plugin settings.', 'cookie-notice-consent' ) . '</p>';
	}
	
	public function cb_setting_delete_options_on_deactivation() {
		$this->render_settings_field_checkbox(
			'general_settings',
			'delete_options_on_deactivation',
			__( 'Delete all plugin options from the database when the plugin gets deactivated', 'cookie-notice-consent' ),
			__( 'Please note that this does not include consent logs. Please trash those prior to deactivating/deleting the plugin.', 'cookie-notice-consent' )
		);
	}
	
	/******************************************************************
	********* DESIGN SETTINGS CALLBACKS *******************************
	******************************************************************/

	public function cb_settings_section_design_settings() {
		echo '<p>' . __( 'Manage design and layout settings for the cookie notice banner.', 'cookie-notice-consent' ) . '</p>';
	}
	
	public function cb_setting_theme() {
		?>
		<div class="cnc-option__theme__container">
		<?php
		$themes = $this->cnc->helper->get_registered_themes();
		foreach( $themes as $slug => $label ) {
		?>
		<div class="cnc-option__theme__item">
			<input class="cnc-option__theme__radio" type="radio" name="cookie_notice_consent_design_settings[theme]" id="cookie_notice_consent_design_settings[theme][<?php echo $slug; ?>]" value="<?php echo $slug ?>" <?php checked( $this->get_option( 'design_settings', 'theme' ), $slug, 1 ); ?>>
			<label class="cnc-option__theme__label" for="cookie_notice_consent_design_settings[theme][<?php echo $slug; ?>]"><?php echo $label; ?></label>
			<label class="cnc-option__theme__image" for="cookie_notice_consent_design_settings[theme][<?php echo $slug; ?>]">
				<img src="<?php echo plugin_dir_url( CNC_PLUGIN_FILE ); ?>/img/theme-<?php echo $slug; ?>.jpg"/>
			</label>
		</div>
		<?php
		}
		?>
		</div>
		<p class="description"><?php _e( 'Choose a pre-defined cookie banner theme. Select \'Default / None\' if you plan to add your own styles via CSS.', 'cookie-notice-consent' ); ?></p>
		<?php
	}
	
	public function cb_setting_color_accent() {
		?>
		<input class="cnc-color-picker" type="text" name="cookie_notice_consent_design_settings[color_accent]" id="cookie_notice_consent_design_settings[color_accent]" value="<?php echo $this->get_option( 'design_settings', 'color_accent' ); ?>">
		<p class="description"><?php _e( 'Define the theme\'s accent color. Leave empty to use default (green-ish, varies between themes).', 'cookie-notice-consent' ); ?></p>
		<?php
	}
	
	public function cb_setting_show_category_descriptions() {
		$this->render_settings_field_checkbox(
			'design_settings',
			'show_category_descriptions',
			__( 'Show category descriptions within the cookie notice banner', 'cookie-notice-consent' )
		);
	}
	
	/******************************************************************
	********* ESSENTIAL CATEGORY CALLBACKS ****************************
	******************************************************************/

	public function cb_settings_section_category_essential() {
		echo '<p>' . $this->defaults['category_essential']['description'] . '</p>';
	}
	
	public function cb_setting_category_essential_active() {
		$this->render_settings_field_checkbox(
			'category_essential',
			'active',
			__( 'Activate this cookie category and output its code if accepted', 'cookie-notice-consent' )
		);
	}
	
	public function cb_setting_category_essential_label() {
		$this->render_settings_field_text(
			'category_essential',
			'label'
		);
	}
	
	public function cb_setting_category_essential_description() {
		$this->render_settings_field_text(
			'category_essential',
			'description'
		);
	}
	
	public function cb_setting_category_essential_code() {
		$this->render_settings_field_textarea(
			'category_essential',
			'code',
			__( 'Define scripts and code that will be output if the category gets the user\'s consent.', 'cookie-notice-consent' ),
			true
		);
	}
	
	/******************************************************************
	********* FUNCTIONAL CATEGORY CALLBACKS ***************************
	******************************************************************/

	public function cb_settings_section_category_functional() {
		echo '<p>' . $this->defaults['category_functional']['description'] . '</p>';
	}
	
	public function cb_setting_category_functional_active() {
		$this->render_settings_field_checkbox(
			'category_functional',
			'active',
			__( 'Activate this cookie category and output its code if accepted', 'cookie-notice-consent' )
		);
	}
	
	public function cb_setting_category_functional_label() {
		$this->render_settings_field_text(
			'category_functional',
			'label'
		);
	}
	
	public function cb_setting_category_functional_description() {
		$this->render_settings_field_text(
			'category_functional',
			'description'
		);
	}
	
	public function cb_setting_category_functional_code() {
		$this->render_settings_field_textarea(
			'category_functional',
			'code',
			__( 'Define scripts and code that will be output if the category gets the user\'s consent.', 'cookie-notice-consent' ),
			true
		);
	}
	
	/******************************************************************
	********* MARKETING CATEGORY CALLBACKS ****************************
	******************************************************************/

	public function cb_settings_section_category_marketing() {
		echo '<p>' . $this->defaults['category_marketing']['description'] . '</p>';
	}
	
	public function cb_setting_category_marketing_active() {
		$this->render_settings_field_checkbox(
			'category_marketing',
			'active',
			__( 'Activate this cookie category and output its code if accepted', 'cookie-notice-consent' )
		);
	}
	
	public function cb_setting_category_marketing_label() {
		$this->render_settings_field_text(
			'category_marketing',
			'label'
		);
	}
	
	public function cb_setting_category_marketing_description() {
		$this->render_settings_field_text(
			'category_marketing',
			'description'
		);
	}
	
	public function cb_setting_category_marketing_code() {
		$this->render_settings_field_textarea(
			'category_marketing',
			'code',
			__( 'Define scripts and code that will be output if the category gets the user\'s consent.', 'cookie-notice-consent' ),
			true
		);
	}
	
	/******************************************************************
	********* CONSENT SETTINGS CALLBACKS ******************************
	******************************************************************/

	public function cb_settings_section_consent_settings() {
		echo '<p>' . __( 'Manage settings related to consents and logging.', 'cookie-notice-consent' ) . '</p>';
	}
	
	public function cb_setting_log_consents() {
		$this->render_settings_field_checkbox(
			'consent_settings',
			'log_consents',
			__( 'Log cookie consents', 'cookie-notice-consent' ),
			__( 'This allows you to prove consents, especially if that\'s required by law.', 'cookie-notice-consent' )
		);
	}
	
	public function cb_setting_anonymize_consent_log_ips() {
		$this->render_settings_field_checkbox(
			'consent_settings',
			'anonymize_consent_log_ips',
			__( 'Anonymize IP address of saved logs', 'cookie-notice-consent' ),
			__( 'This will remove the last section using default WordPress privacy functions.', 'cookie-notice-consent' )
		);
	}
	
	public function cb_setting_auto_purge_consents() {
		?>
		<input type="checkbox" name="cookie_notice_consent_consent_settings[auto_purge_consents]" id="cookie_notice_consent_consent_settings[auto_purge_consents]" value="1" <?php checked( $this->get_option( 'consent_settings', 'auto_purge_consents' ), 1 ); ?>>
		<label for="cookie_notice_consent_consent_settings[auto_purge_consents]"><?php _e( 'Automatically delete cookie consent logs older than', 'cookie-notice-consent' ); ?></label>
		<label for="cookie_notice_consent_consent_settings[auto_purge_consents_interval]">
			<select name="cookie_notice_consent_consent_settings[auto_purge_consents_interval]" id="cookie_notice_consent_consent_settings[auto_purge_consents_interval]" value="<?php echo $this->get_option( 'consent_settings', 'auto_purge_consents_interval' ); ?>">
				<?php
				$intervals = array(
					'1 month' => sprintf( _n( '%s month', '%s months', 1, 'cookie-notice-consent' ), 1 ),
					'3 months' => sprintf( _n( '%s month', '%s months', 3, 'cookie-notice-consent' ), 3 ),
					'6 months' => sprintf( _n( '%s month', '%s months', 6, 'cookie-notice-consent' ), 6 ),
					'1 year' => sprintf( _n( '%s year', '%s years', 1, 'cookie-notice-consent' ), 1 ),
					'2 years' => sprintf( _n( '%s year', '%s years', 2, 'cookie-notice-consent' ), 2 ),
					'5 years' => sprintf( _n( '%s year', '%s years', 5, 'cookie-notice-consent' ), 5 ),
				);
				foreach( $intervals as $value => $label ) {
				?>
				<option value="<?php echo $value; ?>"<?php echo $value == $this->get_option( 'consent_settings', 'auto_purge_consents_interval' ) ? 'selected="selected"' : ''; ?>><?php echo $label; ?></option>
				<?php
				}
				?>
			</select>
		</label>
		<p class="description"><?php _e( 'This will initially trash logs, from where they will be permanently deleted according to your WordPress settings.', 'cookie-notice-consent' ); ?></p>
		<?php
	}
	
	public function cb_setting_delete_all_cookies_on_revoke() {
		$this->render_settings_field_checkbox(
			'consent_settings',
			'revoke_delete_all',
			__( 'Delete all saved cookies when using the \'Revoke consent\' button', 'cookie-notice-consent' ),
			__( 'This will make the \'Revoke consent\' button clear all currently set cookies, excluding WordPress login cookies.', 'cookie-notice-consent' )
		);
	}
	
}
