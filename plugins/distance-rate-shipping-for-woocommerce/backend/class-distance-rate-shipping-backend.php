<?php
/**
 * Distance_Rate_Shipping_Backend
 * This class is core class of plugin's backend.
 * It defines all the required code that will work in backend.
 * 
 * @package Distance_Rate_Shipping
 * @subpackage Distance_Rate_Shipping/includes
 * @author tusharknovator
 * @since 1.0.0
 */
class Distance_Rate_Shipping_Backend{

    /**
     * Store the name of the plugin
     * @access protected
     * @var string $plugin_name
     */
    protected $plugin_name;

    /**
     * Store the version of the plugin
     * @access protected
     * @var string $plugin_version
     */
    protected $plugin_version;

    /**
     * Store the config instance of the plugin
     * @access protected
     * @var string $plugin_config
     */
    protected $plugin_config;

    /**
     * Store the prefix string
     * @access protected
     * @var string $prefix
     */
    protected $prefix;
    
    /**
     * __constructor function
     * To initiate class variables and functions.
     * It runs on creation of class instance/object.
     * @since 1.0.0
     */
    public function __construct($plugin_name, $plugin_version, $plugin_config){
        $this->plugin_name = $plugin_name;
        $this->plugin_version = $plugin_version;
        $this->plugin_config = $plugin_config;
        $this->prefix = str_replace('-', '_', $plugin_name);
    }
    
    /**
     * enquque_styles function
     * To register and enqueue style files into plugin.
     * @since 1.0.0
     */
    public function enquque_styles(){
        wp_enqueue_style( $this->plugin_name.'-backend', plugin_dir_url( __FILE__ ) .'css/distance-rate-shipping-backend.css' , '', $this->plugin_version, 'all');
    }

    /**
     * enqueue_scripts function
     * To register and enqueue scripts files into plugin.
     * @since 1.0.0
     */
    public function enqueue_scripts(){
        wp_enqueue_script( $this->plugin_name.'-backend', plugin_dir_url( __FILE__ ) .'js/distance-rate-shipping-backend.js', ['jquery'], $this->plugin_version, false );
    }

    /**
     * render_view function
     * render view of page.
     * 
     * @param string $slug file name/page slug
     * @since 1.0.0
     */
    public function render_view($slug){
        ob_start();
        include_once plugin_dir_path( dirname( __FILE__ ) ) . 'backend/src/' . $slug . '.php';
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /**
     * add_backend_pages function
     * To register and add plugin pages in backend.
     * @since 1.0.0
     */
    public function add_backend_pages(){
        $text_domain = $this->plugin_config->get_text_domain();
        $main_page = $this->prefix;
        add_menu_page(
            __( 'Distance Rate Shipping', $text_domain ),
            __( 'Distance Rate Shipping', $text_domain ),
            'manage_options',
            $main_page,
            array($this, 'main_page_callback'),
            'dashicons-admin-page',
            10
        );

        $settings_page = $this->prefix . '_settings';
        add_submenu_page( 
            $main_page, 
            __( 'Distance Rate Shipping Settings', $text_domain ),
            __( 'Settings', $text_domain ),
            'manage_options',
            $settings_page, 
            array($this, 'settings_page_callback'),
            10,
        );
    }

    /**
     * main_page_callback function
     * return rendered html to main page.
     * @since 1.0.0
     */
    public function main_page_callback(){
        echo $this->render_view('main-page');
    }

    /**
     * settings_page_callback function
     * return rendered html to settings page.
     * @since 1.0.0
     */
    public function settings_page_callback(){
        echo $this->render_view('settings-page');
    }
    
    /**
     * register_backend_settings_group function
     * register settings, settings section and settings fields in WordPress
     * @since 1.0.0
     */
    public function register_backend_settings(){
        
        $text_domain = $this->plugin_config->get_text_domain();

        // register settings options in WordPress
        $option_group = $this->prefix;
        $option_name = $this->prefix . '_options';
        register_setting( $option_group, $option_name, array('type' => 'array') );
        
        // register settings page apikey section 
        add_settings_section( 
            $this->prefix . '_apikey_settings_section', // section id
            __('Distance Matrix API', $text_domain), // section title
            array($this, 'render_apikey_settings_section'), // section render function callback
            $this->prefix . '_settings' // settings page id
        );
        
        // register settings field assigned to settings apikey section 
        add_settings_field( 
            $this->prefix . '_apikey', // settings field id
            'Google Distance Matrix API', // settings field title
            array($this, 'render_settings_field_apikey'), //setting field render function callback
            $this->prefix . '_settings', // page id
            $this->prefix . '_apikey_settings_section', // section id
            array(
                'label_for' => $this->prefix . '_apikey',
            ), // extra arguments
        );
        
        // register settings page shipping method section 
        add_settings_section( 
            $this->prefix . '_shipping_method_settings_section', // section id
            __('Shipping Method', $text_domain), // section title
            array($this, 'render_shipping_method_settings_section'), // section render function callback
            $this->prefix . '_settings' // settings page id
        );

        // register settings field assigned to settings shipping method section 
        add_settings_field( 
            $this->prefix . '_measurement_standard', // settings field id
            'Choose shipping method distance type', // settings field title
            array($this, 'render_settings_field_measurement_standard'), //setting field render function callback
            $this->prefix . '_settings', // page id
            $this->prefix . '_shipping_method_settings_section', // section id
            array(
                'label_for' => $this->prefix . '_measurement_standard',
            ), // extra arguments
        );
    }
    
    /**
     * render_apikey_settings_section function
     * return apikey key settings section html
     * @since 1.0.0
     */
    function render_apikey_settings_section( $args ){
        echo "<p>Set distance matrix related settings here.</p>";
    }

    /**
     * shipping function
     * return shipping method section html
     * @since 1.0.0
     */
    function render_shipping_method_settings_section( $args ){
        echo "<p>Set shipping method related settings here.</p>";
    }

    /**
     * render_apikey_settings_field function
     * return apikey settings field html
     * @since 1.0.0
     */
    public function render_settings_field_apikey( $args ){

        $option_name = $this->prefix . '_options';        
        $settings_options = get_option( $option_name );
        $setting_id = $args['label_for'];
        $setting_name = $option_name."[". $args['label_for']."]";
        $setting_value = (!empty($settings_options[$args['label_for']])) ? $settings_options[$args['label_for']] : '';
        $gdm_url = "https://developers.google.com/maps/documentation/distance-matrix/overview";
        printf(
            '<input type="text" name="%1$s" id="%2$s" value="%3$s" />
            <p class="description">you can get google\'s distance matrix api key from <a href="%4$s" target="_blank" rel="nofollow">here</a></p>',
            esc_attr( $setting_name ),
            esc_attr( $setting_id ),
            esc_attr( $setting_value ),
            esc_url( $gdm_url ),
        );
    }

    /**
     * render_settings_field_measurement_standard function
     * return measurement standard settings field html
     * @since 1.0.0
     */
    public function render_settings_field_measurement_standard( $args ){

        $option_name = $this->prefix . '_options';        
        $settings_options = get_option( $option_name );
        $setting_id = $args['label_for'];
        $setting_name = $option_name."[". $args['label_for']."]";
        $setting_value = (!empty($settings_options[$args['label_for']])) ? $settings_options[$args['label_for']] : '';
        $gdm_url = "https://developers.google.com/maps/documentation/distance-matrix/overview";
        
        printf(
            '<select type="text" name="%1$s" id="%2$s">
                <option value="km" %3$s>Kilometer</option>
                <option value="mi" %4$s>Mile</option>
            </select>
            <p class="description">you can set measurement standard to calculate and show distance.</p>',
            esc_attr( $setting_name ),
            esc_attr( $setting_id ),
            ($setting_value === "km") ? esc_attr( 'selected' ) : esc_attr( '' ),
            ($setting_value === "mi") ? esc_attr( 'selected' ) : esc_attr( '' ),
        );
    }
}