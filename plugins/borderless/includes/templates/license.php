<?php

// don't load directly
defined( 'ABSPATH' ) || exit;


/*-----------------------------------------------------------------------------------*/
/*  *.  Borderless License
/*-----------------------------------------------------------------------------------*/

 class BorderlessLicense {
	private $borderless_license_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'borderless_license_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'borderless_license_page_init' ) );
	}

	public function borderless_license_add_plugin_page() {
		add_submenu_page(
            'borderless.php',                            // parent slug
            esc_html__( 'License', 'borderless' ),   // page title
            esc_html__( 'License', 'borderless' ),   // menu title
            'manage_options',                            // capability
            'borderless-license.php',                    // slug
            array( $this, 'borderless_license_create_admin_page' )  // function
        );
	}

	public function borderless_license_create_admin_page() {
		$this->borderless_license_options = get_option( 'borderless_license_option_name' ); ?>

		<div class="wrap">
			<h2><?php echo esc_html( 'License', 'borderless' ) ?></h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'borderless_license_option_group' );
					do_settings_sections( 'borderless-license-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function borderless_license_page_init() {
		register_setting(
			'borderless_license_option_group', // option_group
			'borderless_license_option_name', // option_name
			array( $this, 'borderless_license_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'borderless_license_setting_section', // id
			'', // title
			array( $this, 'borderless_license_section_info' ), // callback
			'borderless-license-admin' // page
		);

		add_settings_field(
			'borderless_license_key', // id
			'Borderless License Key', // title
			array( $this, 'borderless_license_key_callback' ), // callback
			'borderless-license-admin', // page
			'borderless_license_setting_section' // section
		);
	}

	public function borderless_license_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['borderless_license_key'] ) ) {
			$sanitary_values['borderless_license_key'] = sanitize_text_field( htmlspecialchars( $input['borderless_license_key'] ) );
		}

		return $sanitary_values;
	}

	public function borderless_license_section_info() {
		
	}

	public function borderless_license_key_callback() {

        $borderless_license = isset( $this->borderless_license_options['borderless_license_key'] ) ? $this->borderless_license_options['borderless_license_key'] : '';

		printf(
			'<input class="regular-text" type="text" name="borderless_license_option_name[borderless_license_key]" id="borderless_license_key" value="%s">',
			isset( $borderless_license ) ? esc_attr( $borderless_license ) : ''
		);

        if (strlen($borderless_license) == 40 && preg_match('/\d/', $borderless_license) && preg_match('/[a-zA-Z]/', $borderless_license)) {
            echo "<p>".esc_html( 'Activated', 'borderless' )."</p>";
        } else {
			echo "<p>".esc_html( 'Deactivated', 'borderless' )."</p>";
        }

	}

}
if ( is_admin() )
	$license = new BorderlessLicense();
