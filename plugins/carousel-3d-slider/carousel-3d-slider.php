<?php
/*
Plugin Name: Carousel 3D Slider
Plugin URI: https://tishonator.com/plugins/carousel-3d-slider
Description: Configure a Responsive 3D jQuery Carousel Slider and Insert it in any Page or Post as a Shortcode. Admin slide fields for title, text, image.
Author: tishonator
Version: 1.0.1
Author URI: http://tishonator.com/
Contributors: tishonator
Text Domain: carousel-3d-slider
*/

if ( !class_exists('tishonator_Carousel3dSliderPlugin') ) :

    /**
     * Register the plugin.
     *
     * Display the administration panel, insert JavaScript etc.
     */
    class tishonator_Carousel3dSliderPlugin {
        
    	/**
    	 * Instance object
    	 *
    	 * @var object
    	 * @see get_instance()
    	 */
    	protected static $instance = NULL;

        /**
         * an array with all Slider settings
         */
        private $settings = array();

        /**
         * Constructor
         */
        public function __construct() {}

        /**
         * Setup
         */
        public function setup() {

            register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );

            if ( is_admin() ) { // admin actions

                add_action('admin_menu', array(&$this, 'add_admin_page'));

                add_action('admin_enqueue_scripts', array(&$this, 'admin_scripts'));
            }

            add_action( 'init', array(&$this, 'register_shortcode') );
        }

        public function register_shortcode() {

            add_shortcode( 'carousel-3d-slider', array(&$this, 'display_shortcode') );
        }

        public function display_shortcode($atts) {

            $result = '';

            $options = get_option( 'carousel_3d_slider_options' );
            
            if ( ! $options )
                return $result;

            // Add jquery.resize.js
            wp_register_script('carousel-3d-sldr-jquery-resize',
                plugins_url('js/jquery.resize.js', __FILE__), array('jquery') );

            wp_enqueue_script('carousel-3d-sldr-jquery-resize',
                    plugins_url('js/jquery.resize.js', __FILE__), array('jquery') );

            // Add jquery.waitforimages.js
            wp_register_script('carousel-3d-sldr-waitforimages',
                plugins_url('js/jquery.waitforimages.js', __FILE__), array('jquery') );

            wp_enqueue_script('carousel-3d-sldr-waitforimages',
                    plugins_url('js/jquery.waitforimages.js', __FILE__), array('jquery') );

            // Add modernizr.js
            wp_register_script('carousel-3d-sldr-modernizr',
                plugins_url('js/modernizr.js', __FILE__), array('jquery') );

            wp_enqueue_script('carousel-3d-sldr-modernizr',
                    plugins_url('js/modernizr.js', __FILE__), array('jquery') );

            // Add jquery.carousel-3d.js
            wp_register_script('carousel-3d-sldr-slider-js',
                plugins_url('js/jquery.carousel-3d.js', __FILE__),
                array('jquery', 'carousel-3d-sldr-modernizr', 
                    'carousel-3d-sldr-waitforimages', 'carousel-3d-sldr-jquery-resize') );

            wp_enqueue_script('carousel-3d-sldr-slider-js',
                    plugins_url('js/jquery.carousel-3d.js', __FILE__), array('jquery') );

            // CSS
            wp_register_style('carousel-3d-slider_css',
                plugins_url('css/carousel-3d-slider.css', __FILE__), true);

            wp_enqueue_style( 'carousel-3d-slider_css',
                plugins_url('css/carousel-3d-slider.css', __FILE__), array() );

            $result .= '<div data-carousel-3d="true">';

            for ($j = 0; $j < 2; ++$j) {
                for ( $slideNumber = 1; $slideNumber <= 3; ++$slideNumber ) {

                    $slideTitle = array_key_exists('slide_' . $slideNumber . '_title', $options)
                                    ? $options[ 'slide_' . $slideNumber . '_title' ] : '';

                    $slideText = array_key_exists('slide_' . $slideNumber . '_text', $options)
                                    ? $options[ 'slide_' . $slideNumber . '_text' ] : '';

                    $slideImage = array_key_exists('slide_' . $slideNumber . '_image', $options)
                                    ? $options[ 'slide_' . $slideNumber . '_image' ] : '';

                    if ( $slideImage ) :

                        $result .= '<div class="slide" style="background-image: url(' .
                                    "'" . esc_attr($slideImage) . "'" . ');">';

                        if ($slideTitle != '') {
                            $result .= '<h2><span>' . esc_attr($slideTitle) . '</span></h2>';
                        }

                        $result .= '<p>' . esc_attr($slideText) . '</p>';
                        $result .= '</div>'; // .slide

                    endif;
                }
            }

            $result .= '</div>'; // data-carousel-3d="true"

            return $result;
        }

        public function admin_scripts($hook) {

            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');

            wp_register_script('carousel_3d_slider_upload_media', plugins_url('js/upload-media.js', __FILE__), array('jquery'));
            wp_enqueue_script('carousel_3d_slider_upload_media');

            wp_enqueue_style('thickbox');
        }

    	/**
    	 * Used to access the instance
         *
         * @return object - class instance
    	 */
    	public static function get_instance() {

    		if ( NULL === self::$instance ) {
                self::$instance = new self();
            }

    		return self::$instance;
    	}

        /**
         * Unregister plugin settings on deactivating the plugin
         */
        public function deactivate() {

            unregister_setting('carousel_3d_slider', 'carousel_3d_slider_options');
        }

        /** 
         * Print the Section text
         */
        public function print_section_info() {}

        public function admin_init_settings() {
            
            register_setting('carousel_3d_slider', 'carousel_3d_slider_options');

            // add separate sections for each of Sliders
            add_settings_section( 'carousel_3d_slider_section',
                __( 'Slider Settings', 'carousel-3d-slider' ),
                array(&$this, 'print_section_info'),
                'carousel_3d_slider' );

            for ( $i = 1; $i <= 3; ++$i ) {

                // Slide Title
                add_settings_field(
                    'slide_' . $i . '_title',
                    sprintf( __( 'Slide %s Title', 'carousel-3d-slider' ), $i ),
                    array(&$this, 'input_callback'),
                    'carousel_3d_slider',
                    'carousel_3d_slider_section',
                    [ 'id' => 'slide_' . $i . '_title',
                      'page' =>  'carousel_3d_slider_options' ]
                );

                // Slide Navigation Title
                add_settings_field(
                    'slide_' . $i . '_text',
                    sprintf( __( 'Slide %s Content', 'carousel-3d-slider' ), $i ),
                    array(&$this, 'textarea_callback'),
                    'carousel_3d_slider',
                    'carousel_3d_slider_section',
                    [ 'id' => 'slide_' . $i . '_text',
                      'page' =>  'carousel_3d_slider_options' ]
                );

                // Slide Image
                add_settings_field(
                    'slide_' . $i . '_image',
                    sprintf( __( 'Slide %s Image', 'carousel-3d-slider' ), $i ),
                    array(&$this, 'image_callback'),
                    'carousel_3d_slider',
                    'carousel_3d_slider_section',
                    [ 'id' => 'slide_' . $i . '_image',
                      'page' =>  'carousel_3d_slider_options' ]
                );
            }
        }

        public function textarea_callback($args) {

            // get the value of the setting we've registered with register_setting()
            $options = get_option( $args['page'] );
 
            // output the field

            $fieldValue = $options && $args['id'] && array_key_exists(esc_attr( $args['id'] ), $options)
                                ? $options[ esc_attr( $args['id'] ) ] : '';
            ?>

            <textarea id="<?php echo esc_attr( $args['page'] . '[' . $args['id'] . ']' ); ?>"
                name = "<?php echo esc_attr( $args['page'] . '[' . $args['id'] . ']' ); ?>"
                rows="10" cols="39"><?php echo esc_attr($fieldValue); ?></textarea>
            <?php
        }

        public function input_callback($args) {

            // get the value of the setting we've registered with register_setting()
            $options = get_option( $args['page'] );
 
            // output the field
            $fieldValue = ($options && $args['id'] && array_key_exists(esc_attr( $args['id'] ), $options))
                                ? $options[ esc_attr( $args['id'] ) ] : 
                                    (array_key_exists('default_val', $args) ? $args['default_val'] : '');
            ?>

            <input type="text" id="<?php echo esc_attr( $args['page'] . '[' . $args['id'] . ']' ); ?>"
                name="<?php echo esc_attr( $args['page'] . '[' . $args['id'] . ']' ); ?>"
                class="regular-text"
                value="<?php echo esc_attr( $fieldValue ); ?>" />
<?php
        }

        public function image_callback($args) {

            // get the value of the setting we've registered with register_setting()
            $options = get_option( $args['page'] );
 
            // output the field

            $fieldValue = $options && $args['id'] && array_key_exists(esc_attr( $args['id'] ), $options)
                                ? $options[ esc_attr( $args['id'] ) ] : '';
            ?>

            <input type="text" id="<?php echo esc_attr( $args['page'] . '[' . $args['id'] . ']' ); ?>"
                name="<?php echo esc_attr($args['page'] . '[' . $args['id'] . ']' ); ?>"
                class="regular-text"
                value="<?php echo esc_attr( $fieldValue ); ?>" />
            <input class="upload_image_button button button-primary" type="button"
                   value="<?php _e('Change Image', 'carousel-3d-slider'); ?>" />

            <p><img class="slider-img-preview" <?php if ( $fieldValue ) : ?> src="<?php echo esc_attr($fieldValue); ?>" <?php endif; ?> style="max-width:300px;height:auto;" /><p>
<?php         
        }

        public function add_admin_page() {

            add_menu_page( __('Carousel 3D Slider Settings', 'carousel-3d-slider'),
                __('Carousel 3D Slider', 'carousel-3d-slider'), 'manage_options',
                'carousel-3d-slider.php', array(&$this, 'show_settings'),
                'dashicons-format-gallery', 6 );

            //call register settings function
            add_action( 'admin_init', array(&$this, 'admin_init_settings') );
        }

        /**
         * Display the settings page.
         */
        public function show_settings() { ?>

            <div class="wrap">
                <div id="icon-options-general" class="icon32"></div>

                <div class="notice notice-info"> 
                    <p><strong><?php _e('Upgrade to Carousel 3D Slider PRO Plugin', 'carousel-3d-slider'); ?>:</strong></p>
                    <ul>
                        <li><?php _e('Configure Up to 10 Different Sliders', 'carousel-3d-slider'); ?></li>
                        <li><?php _e('Insert Up to 10 Slides per Slider', 'carousel-3d-slider'); ?></li>
                        <li><?php _e('Height and Delay Options', 'carousel-3d-slider'); ?></li>
                    </ul>
                    <a href="https://tishonator.com/plugins/carousel-3d-slider" class="button-primary">
                        <?php _e('Upgrade to Carousel 3D Slider PRO Plugin', 'carousel-3d-slider'); ?>
                    </a>
                    <p></p>
                </div>

                <h2><?php _e('Carousel 3D Slider Settings', 'carousel-3d-slider'); ?></h2>

                <form action="options.php" method="post">
                    <?php settings_fields('carousel_3d_slider'); ?>
                    <?php do_settings_sections('carousel_3d_slider'); ?>
                    
                    <h3>
                      Usage
                    </h3>
                    <p>
                        <?php _e('Use the shortcode', 'carousel-3d-slider'); ?> <code>[carousel-3d-slider]</code> <?php echo _e( 'to display Slider to any page or post.', 'carousel-3d-slider' ); ?>
                    </p>
                    <?php submit_button(); ?>
              </form>
            </div>
    <?php
        }
    }

endif; // tishonator_Carousel3dSliderPlugin

add_action('plugins_loaded', array( tishonator_Carousel3dSliderPlugin::get_instance(), 'setup' ), 10);
