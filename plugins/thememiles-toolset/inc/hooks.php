<?php
class Thememiles_Toolset_Hooks {

    private $hook_suffix;

    private $theme_author = 'thememiles';

    public static function instance() {

        static $instance = null;

        if ( null === $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    public function __construct() {}

    public function import_menu() {

        if( !class_exists('Advanced_Import')){
            $this->hook_suffix[] = add_theme_page( esc_html__( 'One Click Demo Import ','thememiles-toolset' ), esc_html__( 'One Click Demo Import','thememiles-toolset'  ), 'manage_options', 'advanced-import', array( $this, 'demo_import_screen' ) );
        }
    }

  
    public function enqueue_styles( $hook_suffix ) {

        if ( !is_array($this->hook_suffix) || !in_array( $hook_suffix, $this->hook_suffix )){
            return;
        }

        wp_enqueue_style( THEMEMILES_TOOLSET_PLUGIN_NAME, THEMEMILES_TOOLSET_URL . 'assets/thememiles-toolset.css',array( 'wp-admin', 'dashicons' ), THEMEMILES_TOOLSET_VERSION, 'all' );
    }

    public function enqueue_scripts( $hook_suffix ) {

        if ( !is_array($this->hook_suffix) || !in_array( $hook_suffix, $this->hook_suffix )){
            return;
        }

        wp_enqueue_script( THEMEMILES_TOOLSET_PLUGIN_NAME, THEMEMILES_TOOLSET_URL . 'assets/thememiles-toolset.js', array( 'jquery'), THEMEMILES_TOOLSET_VERSION, true );

        wp_localize_script( THEMEMILES_TOOLSET_PLUGIN_NAME, 'thememiles_toolset', array(
            'btn_text' => esc_html__( 'Processing...', 'thememiles-toolset' ),
            'nonce'    => wp_create_nonce( 'thememiles_toolset_nonce' )
        ) );
    }

    public function demo_import_screen() {
        ?>
        <div id="ads-notice">
            <div class="ads-container">
                <img class="ads-screenshot" src="<?php echo esc_url(thememiles_toolset_get_theme_screenshot() )?>" />
                <div class="ads-notice">
                    <h2>
                        <?php
                        printf(
                            esc_html__( 'Welcome! Thank you for choosing %1$s! To get started with ready-made starter site templates. Install the Advanced Import plugin and install Demo Starter Site within a single click', 'thememiles-toolset' ), '<strong>'. wp_get_theme()->get('Name'). '</strong>');
                        ?>
                    </h2>

                    <p class="plugin-install-notice"><?php esc_html_e( 'Clicking the button below will install and activate the Advanced Import plugin.', 'thememiles-toolset' ); ?></p>

                    <a class="ads-gsm-btn button button-primary button-hero" href="#" data-name="" data-slug="" aria-label="<?php esc_html_e( 'Get started with the Theme', 'thememiles-toolset' ); ?>">
                        <?php esc_html_e( 'Get Started', 'thememiles-toolset' );?>
                    </a>
                </div>
            </div>
        </div>
        <?php

    }

    public function install_advanced_import() {

        check_ajax_referer( 'thememiles_toolset_nonce', 'security' );

        $slug   = 'advanced-import';
        $plugin = 'advanced-import/advanced-import.php';

        $status = array(
            'install' => 'plugin',
            'slug'    => sanitize_key( wp_unslash( $slug ) ),
        );
        $status['redirect'] = admin_url( '/themes.php?page=advanced-import&browse=all&at-gsm-hide-notice=welcome' );

        if ( is_plugin_active_for_network( $plugin ) || is_plugin_active( $plugin ) ) {
            // Plugin is activated
            wp_send_json_success($status);
        }


        if ( ! current_user_can( 'install_plugins' ) ) {
            $status['errorMessage'] = __( 'Sorry, you are not allowed to install plugins on this site.', 'thememiles-toolset' );
            wp_send_json_error( $status );
        }

        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

        // Looks like a plugin is installed, but not active.
        if ( file_exists( WP_PLUGIN_DIR . '/' . $slug ) ) {
            $plugin_data          = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
            $status['plugin']     = $plugin;
            $status['pluginName'] = $plugin_data['Name'];

            if ( current_user_can( 'activate_plugin', $plugin ) && is_plugin_inactive( $plugin ) ) {
                $result = activate_plugin( $plugin );

                if ( is_wp_error( $result ) ) {
                    $status['errorCode']    = $result->get_error_code();
                    $status['errorMessage'] = $result->get_error_message();
                    wp_send_json_error( $status );
                }

                wp_send_json_success( $status );
            }
        }

        $api = plugins_api(
            'plugin_information',
            array(
                'slug'   => sanitize_key( wp_unslash( $slug ) ),
                'fields' => array(
                    'sections' => false,
                ),
            )
        );

        if ( is_wp_error( $api ) ) {
            $status['errorMessage'] = $api->get_error_message();
            wp_send_json_error( $status );
        }

        $status['pluginName'] = $api->name;

        $skin     = new WP_Ajax_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader( $skin );
        $result   = $upgrader->install( $api->download_link );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $status['debug'] = $skin->get_upgrade_messages();
        }

        if ( is_wp_error( $result ) ) {
            $status['errorCode']    = $result->get_error_code();
            $status['errorMessage'] = $result->get_error_message();
            wp_send_json_error( $status );
        } elseif ( is_wp_error( $skin->result ) ) {
            $status['errorCode']    = $skin->result->get_error_code();
            $status['errorMessage'] = $skin->result->get_error_message();
            wp_send_json_error( $status );
        } elseif ( $skin->get_errors()->get_error_code() ) {
            $status['errorMessage'] = $skin->get_error_messages();
            wp_send_json_error( $status );
        } elseif ( is_null( $result ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            WP_Filesystem();
            global $wp_filesystem;

            $status['errorCode']    = 'unable_to_connect_to_filesystem';
            $status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'thememiles-toolset' );

            // Pass through the error from WP_Filesystem if one was raised.
            if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
                $status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
            }

            wp_send_json_error( $status );
        }

        $install_status = install_plugin_install_status( $api );

        if ( current_user_can( 'activate_plugin', $install_status['file'] ) && is_plugin_inactive( $install_status['file'] ) ) {
            $result = activate_plugin( $install_status['file'] );

            if ( is_wp_error( $result ) ) {
                $status['errorCode']    = $result->get_error_code();
                $status['errorMessage'] = $result->get_error_message();
                wp_send_json_error( $status );
            }
        }

        wp_send_json_success( $status );

    }

    public function add_demo_lists( $current_demo_list ) {

        if( thememiles_toolset_get_current_theme_author() != $this->theme_author ){
            return  $current_demo_list;
        }

        $theme_slug = thememiles_toolset_get_current_theme_slug();

        switch ($theme_slug):
            case "log-book":
                $templates = array(
                    array(
                        'title' => __( 'Main Demo', 'thememiles-toolset' ),/*Title*/
                        'is_premium' => false,/*Premium*/
                        'type' => 'normal',
                        'author' => __( 'Thememiles', 'thememiles-toolset' ),/*Author Name*/
                        'keywords' => array( 'main', 'demo' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/content.json',
                            'options' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/options.json',
                            'widgets' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/widgets.json'
                        ),
                        'screenshot_url' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/demo.jpg',/*Screenshot of block*/
                        'demo_url' => 'https://demo.thememiles.com/log-book/',/*Demo Url*/
                        'plugins' => array(
                            array(
                                'name'      => 'Everest Forms',
                                'slug'      => 'everest-forms',

                            ),

                            array(
                                'name'      => 'Getwid – Gutenberg Blocks',
                                'slug'      => 'getwid',
                            ),

                            array(
                                'name'      => 'WooCommerce',
                                'slug'      => 'woocommerce',
                            ),

                            array(
                                'name'      => 'Elementor',
                                'slug'      => 'elementor',
                            ),

                            array(
                                'name'      => 'Mailchimp',
                                'slug'      => 'mailchimp-for-wp',
                            ),

                            array(
                                'name'      => 'Instagram Feed',
                                'slug'      => 'instagram-feed',
                            ),

                          
                        )
                    ),
                );
                break;
            case "web-log":
                $templates = array(
                    array(
                        'title' => __( 'Main Demo', 'thememiles-toolset' ),/*Title*/
                        'is_premium' => false,/*Premium*/
                        'type' => 'normal',
                        'author' => __( 'Thememiles', 'thememiles-toolset' ),/*Author Name*/
                        'keywords' => array( 'main', 'demo' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/content.json',
                            'options' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/options.json',
                            'widgets' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/widgets.json'
                        ),
                        'screenshot_url' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/demo.jpg',/*Screenshot of block*/
                        'demo_url' => 'https://demo.thememiles.com/web-log/',/*Demo Url*/
                        'plugins' => array(

                            array(
                                'name'      => 'Getwid – Gutenberg Blocks',
                                'slug'      => 'getwid',
                            ),

                           array(
                                'name'      => 'Mailchimp',
                                'slug'      => 'mailchimp-for-wp',
                            ),

                            array(
                                'name'      => 'Contact Form by WPForms',
                                'slug'      => 'wpforms-lite',
                            ),
                            
                            array(
                                'name'      => 'Instagram Feed',
                                'slug'      => 'instagram-feed',
                            ),
                            
                        )
                    ),
                );
                break;

                 case "business-trade": 
                $templates = array(
                    array(
                        'title' => __( 'Main Demo', 'thememiles-toolset' ),/*Title*/
                        'is_premium' => false,/*Premium*/
                        'type' => 'normal',
                        'author' => __( 'Thememiles', 'thememiles-toolset' ),/*Author Name*/
                        'keywords' => array( 'main', 'demo' ),/*Search keyword*/
                        'categories' => array( 'business' ),/*Categories*/
                        'template_url' => array(
                            'content' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/content.json',
                            'options' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/options.json',
                            'widgets' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/widgets.json'
                        ),
                        'screenshot_url' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/demo.jpg',/*Screenshot of block*/
                        'demo_url' => 'https://demo.thememiles.com/business-trade/',/*Demo Url*/
                        'plugins' => array(

                            array(
                                'name'      => 'Gutentor',
                                'slug'      => 'gutentor',
                            ),
                           array(
                                'name'      => 'Mailchimp',
                                'slug'      => 'mailchimp-for-wp',
                            ),

                            array(
                                'name'     => esc_html__( 'Everest Forms', 'business-trade' ),
                                'slug'     => 'everest-forms',
                                'required' => false,
                            ),

                            array(
                                    'name'     => esc_html__( 'Elementor', 'business-trade' ),
                                    'slug'     => 'elementor',
                                    'required' => false,
                                ),
                        )
                    ),

                    array(
                        'title' => __( 'Agency Demo', 'thememiles-toolset' ),/*Title*/
                        'is_premium' => false,/*Premium*/
                        'type' => 'gutentor',
                        'author' => __( 'Thememiles', 'thememiles-toolset' ),/*Author Name*/
                        'keywords' => array( 'gutenberg', 'demo' ),/*Search keyword*/
                        'categories' => array( 'gutenberg' ),/*Categories*/
                        'template_url' => array(
                            'content' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-2/content.json',
                            'options' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-2/options.json',
                            'widgets' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-2/widgets.json'
                        ),
                        'screenshot_url' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-2/demo.jpg',/*Screenshot of block*/
                        'demo_url' => 'https://demo.thememiles.com/business-trade-gutenberg/',/*Demo Url*/
                        'plugins' => array(
                            
                            array(
                                'name'      => 'Gutentor',
                                'slug'      => 'gutentor',
                            ),

                           array(
                                'name'      => 'Mailchimp',
                                'slug'      => 'mailchimp-for-wp',
                            ),

                            array(
                                'name'     => esc_html__( 'Everest Forms', 'business-trade' ),
                                'slug'     => 'everest-forms',
                                'required' => false,
                            ),

                            array(
                                    'name'     => esc_html__( 'Elementor', 'business-trade' ),
                                    'slug'     => 'elementor',
                                    'required' => false,
                                ),
                        )
                    ),

                     array(
                        'title' => __( 'Gutentor Demo', 'thememiles-toolset' ),/*Title*/
                        'is_premium' => false,/*Premium*/
                        'type' => 'gutentor',
                        'author' => __( 'Thememiles', 'thememiles-toolset' ),/*Author Name*/
                        'keywords' => array( 'gutenberg', 'demo' ),/*Search keyword*/
                        'categories' => array( 'gutentor' ),/*Categories*/
                        'template_url' => array(
                            'content' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-3/content.json',
                            'options' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-3/options.json',
                            'widgets' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-3/widgets.json'
                        ),
                        'screenshot_url' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-3/demo.jpg',/*Screenshot of block*/
                        'demo_url' => 'https://demo.thememiles.com/gutentor-demo/',/*Demo Url*/
                        'plugins' => array(
                            
                            array(
                                'name'      => 'Gutentor',
                                'slug'      => 'gutentor',
                            ),

                           array(
                                'name'      => 'Mailchimp',
                                'slug'      => 'mailchimp-for-wp',
                            ),

                            array(
                                'name'     => esc_html__( 'Everest Forms', 'business-trade' ),
                                'slug'     => 'everest-forms',
                                'required' => false,
                            ),

                            array(
                                    'name'     => esc_html__( 'Elementor', 'business-trade' ),
                                    'slug'     => 'elementor',
                                    'required' => false,
                                ),
                        )
                    ),
                );
                break;

                  case "lili-blog": 
                $templates = array(
                    array(
                        'title' => __( 'Main Demo', 'thememiles-toolset' ),/*Title*/
                        'is_premium' => false,/*Premium*/
                        'type' => 'normal',
                        'author' => __( 'Thememiles', 'thememiles-toolset' ),/*Author Name*/
                        'keywords' => array( 'main', 'demo' ),/*Search keyword*/
                        'categories' => array( 'lili-blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/content.json',
                            'options' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/options.json',
                            'widgets' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/widgets.json'
                        ),
                        'screenshot_url' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/demo.jpg',/*Screenshot of block*/
                        'demo_url' => 'https://demo.thememiles.com/lili-blog/',/*Demo Url*/
                        'plugins' => array(
                                
                                 array(
                                'name'      => 'Mailchimp',
                                'slug'      => 'mailchimp-for-wp',
                            ),


                        )
                    ),

                    array(
                        'title' => __( 'Demo Two', 'thememiles-toolset' ),/*Title*/
                        'is_premium' => false,/*Premium*/
                        'type' => 'demo-two',
                        'author' => __( 'Thememiles', 'thememiles-toolset' ),/*Author Name*/
                        'keywords' => array( 'demo-two', 'demo' ),/*Search keyword*/
                        'categories' => array( 'demo-two' ),/*Categories*/
                        'template_url' => array(
                            'content' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-2/content.json',
                            'options' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-2/options.json',
                            'widgets' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-2/widgets.json'
                        ),
                        'screenshot_url' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-2/demo.jpg',/*Screenshot of block*/
                        'demo_url' => 'https://demo.thememiles.com/lili-blog-demo2/',/*Demo Url*/
                        'plugins' => array(
                            
                           
                           array(
                                'name'      => 'Mailchimp',
                                'slug'      => 'mailchimp-for-wp',
                            ),

                           
                        )
                    ),

                     array(
                        'title' => __( 'Demo Three', 'thememiles-toolset' ),/*Title*/
                        'is_premium' => false,/*Premium*/
                        'type' => 'demo-three',
                        'author' => __( 'Thememiles', 'thememiles-toolset' ),/*Author Name*/
                        'keywords' => array( 'demo-three', 'demo' ),/*Search keyword*/
                        'categories' => array( 'demo-three' ),/*Categories*/
                        'template_url' => array(
                            'content' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-3/content.json',
                            'options' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-3/options.json',
                            'widgets' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-3/widgets.json'
                        ),
                        'screenshot_url' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-3/demo.jpg',/*Screenshot of block*/
                        'demo_url' => 'https://demo.thememiles.com/lili-blog-demo3/',/*Demo Url*/
                        'plugins' => array(
                            
                           
                           array(
                                'name'      => 'Mailchimp',
                                'slug'      => 'mailchimp-for-wp',
                            ),

                           
                        )
                    ),
                );
                break;

                 case "wellbeing-hospital": 
                $templates = array(
                    array(
                        'title' => __( 'Main Demo', 'thememiles-toolset' ),/*Title*/
                        'is_premium' => false,/*Premium*/
                        'type' => 'normal',
                        'author' => __( 'Thememiles', 'thememiles-toolset' ),/*Author Name*/
                        'keywords' => array( 'main', 'demo' ),/*Search keyword*/
                        'categories' => array( 'Medical' ),/*Categories*/
                        'template_url' => array(
                            'content' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/content.json',
                            'options' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/options.json',
                            'widgets' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/widgets.json'
                        ),
                        'screenshot_url' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/demo.png',/*Screenshot of block*/
                        'demo_url' => 'https://demo.thememiles.com/wellbeing/',/*Demo Url*/
                        'plugins' => array(

                            array(
                                'name'      => 'Gutentor',
                                'slug'      => 'gutentor',
                            ),
                            array(
                                'name'     => esc_html__( 'Everest Forms', 'wellbeing-hospital' ),
                                'slug'     => 'everest-forms',
                                'required' => false,
                            ),

                            
                        )
                    ),
                );
                break;

                 case "blog-web":
                $templates = array(
                    array(
                        'title' => __( 'Main Demo', 'thememiles-toolset' ),/*Title*/
                        'is_premium' => false,/*Premium*/
                        'type' => 'normal',
                        'author' => __( 'thememiles', 'thememiles-toolset' ),/*Author Name*/
                        'keywords' => array( 'main', 'demo' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/content.json',
                            'options' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/options.json',
                            'widgets' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/widgets.json'
                        ),
                        'screenshot_url' => THEMEMILES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/demo.jpg',/*Screenshot of block*/
                        'demo_url' => 'https://demo.thememiles.com/blog-web/',/*Demo Url*/
                        'plugins' => array(

                            

                           array(
                                'name'      => 'Mailchimp',
                                'slug'      => 'mailchimp-for-wp',
                            ),

                            array(
                                'name'     => esc_html__( 'Everest Forms', 'blog-web' ),
                                'slug'     => 'everest-forms',
                                'required' => false,
                            ),
                            
                            array(
                                'name'      => 'Instagram Feed',
                                'slug'      => 'instagram-feed',
                            ),
                            
                        )
                    ),
                );
                break;

            default:
                $templates = array();
        endswitch;

        return array_merge( $current_demo_list, $templates );

    }
    public function replace_term_ids( $replace_term_ids ){

       $theme_slug = thememiles_toolset_get_current_theme_slug();
        /*Terms IDS*/
        
         switch ($theme_slug):
            case "wellbeing-hospital":
                $term_ids = array(
                    'wellbeing_hospital_slider_cat',/*service main*/
                    'wellbeing_hospital_testimonial_cat',
                    'wellbeing_hospital_blog_cat',

                );
                break;
            default:
               $term_ids = array();
                break;

        endswitch;

        return array_merge( $replace_term_ids, $term_ids );
    }

	public function replace_post_ids( $replace_post_ids ){

		$theme_slug = thememiles_toolset_get_current_theme_slug();

		switch ($theme_slug):
			case "business-trade":
				$post_ids = array(
					'main',/*service main*/
					'page_ids',/*service page_ids*/
				);
			    break;

                case "wellbeing-hospital":
                $post_ids = array(
                    'wellbeing_hospital_feature_section1',
                    'wellbeing_hospital_feature_section2',
                    'wellbeing_hospital_feature_section3',/*service page_ids*/
                    'wellbeing_hospital_welcome_page',
                    'wellbeing_hospital_contact_page',
                   

                );
                break;
            default:
	            $post_ids = array();
	            break;

        endswitch;
		return array_merge( $replace_post_ids, $post_ids );
	}

 
}

/**
 * Begins execution of the hooks.
 *
 * @since    1.0.0
 */
function thememiles_toolset_hooks( ) {
    return Thememiles_Toolset_Hooks::instance();
}