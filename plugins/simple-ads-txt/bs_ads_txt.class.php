<?php



class BS_Ads_txt {

    /**
     * Constructor for scanner object.
     *
     * @access 	public
     * @return 	void
     */
    function __construct() {
        add_action( 'admin_menu', array( &$this, 'BS_Ads_txt_menu' ));
		add_action('admin_init', array($this, 'bs_ads_txt_settings_page_init'));
		add_filter('init', array($this, 'BS_ads_txt_template_include'), 1, 1); 
    }
    
    /**
     * Functions that catches ads.txt file request.
     *
     * @access 	public
     * @return 	void
     */
	 public function BS_ads_txt_template_include($template){
		$request = esc_url_raw( $_SERVER['REQUEST_URI'] );
		if ( '/ads.txt' === $request ) {
			$val = get_option( 'bs_ads_txt_settings_option_name' );
			$ads = isset( $val['bs_ads_txt'] ) ? esc_html( $val['bs_ads_txt'] ) : '';
			if(trim($ads) != ""){
				header( "Content-Type: text/plain" );
				echo $ads;
				die();
			}
		}
	}
	
    /**
     * Functions that creates settings options fields.
     *
     * @access 	public
     * @return 	void
     */
	 public function bs_ads_txt_settings_page_init()
	{
		register_setting(
			'bs_ads_txt_settings_option_group', // option_group
			'bs_ads_txt_settings_option_name', // option_name
			array($this, 'bs_ads_txt_settings_sanitize') // sanitize_callback
		);
		add_settings_section(
			'bs_ads_txt_settings_setting_section', // id
			'ads.txt content', // title
			array($this, 'bs_ads_txt_settings_section_info'), // callback
			'bs_ads_txt_settings' // page
		);
		add_settings_field(
			'bs_ads_txt', // id
			'', // title
			array($this, 'bs_ads_txt_settings_callback'), // callback
			'bs_ads_txt_settings', // page
			'bs_ads_txt_settings_setting_section' // section
		);
	}
	
    /**
     * Functions that creates additional information html on settings page.
     *
     * @access 	public
     * @return 	void
     */
	 public function bs_ads_txt_settings_section_info()
	{ ?>
		<style>.form-table td{padding: 0;}</style>
		<p>
		<b style="color: red">Important.</b> If you have ads.txt file in your root directory please delete or rename it, else your changes will not been shown.
		</p>
	<?php
	}

 
    /**
     * Functions that creates ads.txt field html on settings page.
     *
     * @access 	public
     * @return 	void
     */
	 public function bs_ads_txt_settings_callback()
	{
		$val = get_option( 'bs_ads_txt_settings_option_name' );
		printf(
			'<textarea class="large-text" rows="5" name="bs_ads_txt_settings_option_name[bs_ads_txt]">%s</textarea>',
			 isset( $val['bs_ads_txt'] ) ? esc_attr( $val['bs_ads_txt']) : ''
		);
	}
	
    /**
     * Functions that sanitizes settings fields.
     *
     * @access 	public
     * @return 	array
     */
	public function bs_ads_txt_settings_sanitize($input)
	{
		$sanitary_values = array();
		if (isset($input['bs_ads_txt'])) {
			$sanitary_values['bs_ads_txt'] = sanitize_textarea_field($input['bs_ads_txt']);
		}
		return $sanitary_values;
	}
    /**
     * Functions that creates settings page and menu item.
     *
     * @access 	public
     * @return 	void
     */
    function BS_Ads_txt_menu() {
    	add_options_page(
			'ads.txt editor', // page_title
			'ads.txt editor', // menu_title
			'manage_options', // capability
			'bs_ads_txt_settings', // menu_slug
			array($this, 'bs_ads_txt_settings') // function
		);

    }
    /**
     * Functions that creates settings page html.
     *
     * @access 	public
     * @return 	void
     */
    function bs_ads_txt_settings() {	
            require_once(plugin_dir_path( __FILE__ ).'/templates/settings_page.php');
    }
   
}
