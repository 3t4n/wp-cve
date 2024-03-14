<?php
class Ripplethemes_Toolset_Hooks {


	private $hook_suffix;

	private $theme_author = 'ripplethemes';

	public static function instance() {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new self();
            update_option( '__gutentor_do_redirect', false );

        }

		return $instance;
	}

	public function __construct() {     }

	public function import_menu() {
		if ( ! class_exists( 'Advanced_Import' ) ) {
			$this->hook_suffix[] = add_theme_page( esc_html__( 'Demo Import ', 'ripplethemes-toolset' ), esc_html__( 'Demo Import', 'ripplethemes-toolset' ), 'manage_options', 'advanced-import', array( $this, 'demo_import_screen' ) );
		}
	}

	public function enqueue_styles( $hook_suffix ) {
		if ( ! is_array( $this->hook_suffix ) || ! in_array( $hook_suffix, $this->hook_suffix ) ) {
			return;
		}
		wp_enqueue_style( RIPPLETHEMES_TOOLSET_PLUGIN_NAME, RIPPLETHEMES_TOOLSET_URL . 'assets/ripplethemes-toolset.css', array( 'wp-admin', 'dashicons' ), RIPPLETHEMES_TOOLSET_VERSION, 'all' );
	}

	public function enqueue_scripts( $hook_suffix ) {
		if ( ! is_array( $this->hook_suffix ) || ! in_array( $hook_suffix, $this->hook_suffix ) ) {
			return;
		}

		wp_enqueue_script( RIPPLETHEMES_TOOLSET_PLUGIN_NAME, RIPPLETHEMES_TOOLSET_URL . 'assets/ripplethemes-toolset.js', array( 'jquery' ), RIPPLETHEMES_TOOLSET_VERSION, true );
		wp_localize_script(
			RIPPLETHEMES_TOOLSET_PLUGIN_NAME,
			'ripplethemes_toolset',
			array(
				'btn_text' => esc_html__( 'Processing...', 'ripplethemes-toolset' ),
				'nonce'    => wp_create_nonce( 'ripplethemes_toolset_nonce' ),
			)
		);
	}

	public function demo_import_screen() {      ?>
<div id="ads-notice">
    <div class="ads-container">
        <img class="ads-screenshot" src="<?php echo esc_url( ripplethemes_toolset_get_theme_screenshot() ); ?>" />
        <div class="ads-notice">
            <h2>
                <?php
						printf(
							esc_html__( 'Welcome! Thank you for choosing %1$s! To get started with ready-made starter site templates. Install the Advanced Import plugin and install Demo Starter Site within a single click', 'ripplethemes-toolset' ),
							'<strong>' . wp_get_theme()->get( 'Name' ) . '</strong>'
						);
						?>
            </h2>

            <p class="plugin-install-notice">
                <?php esc_html_e( 'Clicking the button below will install and activate the Advanced Import plugin.', 'ripplethemes-toolset' ); ?>
            </p>

            <a class="ads-gsm-btn button button-primary button-hero" href="#" data-name="" data-slug=""
                aria-label="<?php esc_html_e( 'Get started with the Theme', 'ripplethemes-toolset' ); ?>">
                <?php esc_html_e( 'Get Started', 'ripplethemes-toolset' ); ?>
            </a>
        </div>
    </div>
</div>
<?php

	}

	public function install_advanced_import() {
		check_ajax_referer( 'ripplethemes_toolset_nonce', 'security' );

		$slug   = 'advanced-import';
		$plugin = 'advanced-import/advanced-import.php';

		$status             = array(
			'install' => 'plugin',
			'slug'    => sanitize_key( wp_unslash( $slug ) ),
		);
		$status['redirect'] = admin_url( '/themes.php?page=advanced-import&browse=all&at-gsm-hide-notice=welcome' );

		if ( is_plugin_active_for_network( $plugin ) || is_plugin_active( $plugin ) ) {
			// Plugin is activated
			wp_send_json_success( $status );
		}

		if ( ! current_user_can( 'install_plugins' ) ) {
			$status['errorMessage'] = __( 'Sorry, you are not allowed to install plugins on this site.', 'ripplethemes-toolset' );
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
			$status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'ripplethemes-toolset' );

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
		if ( ripplethemes_toolset_get_current_theme_author() != $this->theme_author ) {
			return $current_demo_list;
		}

		$theme_slug = ripplethemes_toolset_get_current_theme_slug();

		switch ( $theme_slug ) :
			case 'stunning':
                $templates = array(
                    
                    'demo1' =>array(
                        'title' => __( 'Demo 1', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => false,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/demo-1/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
					),
				
                    'demo9' =>array(
                        'title' => __( 'Transparent Background', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => false,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.'stunning-pro/nobg-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.'stunning-pro/nobg-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.'stunning-pro/nobg-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.'stunning-pro/nobg-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-nobg/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
                    ),
                    'demo8' =>array(
                        'title' => __( 'Main demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/main-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/main-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/main-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/main-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-pro/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
                    ),
                    'demo2' =>array(
                        'title' => __( 'Full Slider Demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/full-slider-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/full-slider-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/full-slider-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/full-slider-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-full-slider/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
					),
					'demo3' =>array(
                        'title' => __( 'List View Demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/list-view-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/list-view-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/list-view-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/list-view-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-list-view/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
					),
					'demo4' =>array(
                        'title' => __( 'Masonry Demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/masonry-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/masonry-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/masonry-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/masonry-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-mansonry/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
					),
					'demo5' =>array(
                        'title' => __( 'No Sidebar Demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/no-sidebar-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/no-sidebar-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/no-sidebar-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/no-sidebar-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-no-sidebar/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
					),
					'demo6' =>array(
                        'title' => __( 'RTL Demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/rtl-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/rtl-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/rtl-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/rtl-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-rtl/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
					),
					'demo7' =>array(
                        'title' => __( 'Gutentor Demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/gutentor-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/gutentor-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/gutentor-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/gutentor-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-gutentor/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
                    ),
                );
                break;
                
			case 'stunning-pro':
                $templates = array(
                    'demo1' =>array(
                        'title' => __( 'Main demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/main-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/main-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/main-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/main-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-pro/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
                    ),
                    'demo2' =>array(
                        'title' => __( 'Full Slider Demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/full-slider-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/full-slider-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/full-slider-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/full-slider-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-full-slider/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
					),
					'demo3' =>array(
                        'title' => __( 'List View Demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/list-view-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/list-view-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/list-view-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/list-view-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-list-view/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
					),
					'demo4' =>array(
                        'title' => __( 'Masonry Demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/masonry-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/masonry-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/masonry-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/masonry-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-mansonry/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
					),
					'demo5' =>array(
                        'title' => __( 'No Sidebar Demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/no-sidebar-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/no-sidebar-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/no-sidebar-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/no-sidebar-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-no-sidebar/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
					),
					'demo6' =>array(
                        'title' => __( 'RTL Demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/rtl-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/rtl-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/rtl-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/rtl-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-rtl/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
					),
					'demo7' =>array(
                        'title' => __( 'Gutentor Demo', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/gutentor-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/gutentor-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/gutentor-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/gutentor-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-gutentor/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
                    ),
                    'demo8' =>array(
                        'title' => __( 'Transparent Background', 'ripplethemes-toolset' ),/*Title*/
                        'is_pro' => true,/*Is Premium*/
                        'type' => 'normal',/*Optional eg gutentor, elementor or other page builders or type*/
                        'author' => __( 'Ripple Themes', 'ripplethemes-toolset' ),/*Author Name*/
                        'keywords' => array( 'blog' ),/*Search keyword*/
                        'categories' => array( 'blog' ),/*Categories*/
                        'template_url' => array(
                            'content' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/nobg-demo/content.json',
                            'options' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/nobg-demo/options.json',
                            'widgets' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/nobg-demo/widgets.json'
                        ),
                        'screenshot_url' => RIPPLETHEMES_TOOLSET_TEMPLATE_URL.$theme_slug.'/nobg-demo/screenshot.jpg',
                        'demo_url' => 'https://demo.ripplethemes.com/stunning-nobg/',
                        'plugins' => array(
                            array(
                                'name'      => __( 'Gutentor', 'ripplethemes-toolset' ),
                                'slug'      => 'gutentor',
                            ),
                        )
                    ),
                );
                break;

            case 'dashy-pro':
                include(RIPPLETHEMES_TOOLSET_PATH.'demosArrays/dashydemo.php');
                $templates = $demoo;
            break;
            case 'dashy':
                include(RIPPLETHEMES_TOOLSET_PATH.'demosArrays/dashydemo.php');
                $templates = $demoo;
            break;
            case 'dashy-blog':
                include(RIPPLETHEMES_TOOLSET_PATH.'demosArrays/dashyblogdemo.php');
                $templates = $demoo;
            break;
            case 'dashy-blog-pro':
                include(RIPPLETHEMES_TOOLSET_PATH.'demosArrays/dashyblogdemo.php');
                $templates = $demoo;
            break;
            
            case 'public-blog':
                $dir_name='public-blog';
                include(RIPPLETHEMES_TOOLSET_PATH.'demosArrays/publicblogdemo.php');
                $templates = $demoo;
            break;
            case 'public-blog-pro':
                $dir_name='public-blog';
                include(RIPPLETHEMES_TOOLSET_PATH.'demosArrays/publicblogdemo.php');
                $templates = $demoo;
            break;
            
			default:
				$templates = array();
		endswitch;

		return array_merge( $current_demo_list, $templates );

	}

	public function replace_term_ids( $replace_term_ids ) {
		$theme_slug = ripplethemes_toolset_get_current_theme_slug();

		switch ( $theme_slug ) :
			case 'stunning':
				/*Terms IDS*/
				$term_ids = array(
					'stunning-slider-category',
				);
				break;
			default:
				$term_ids = array();
				break;
		endswitch;

		return array_merge( $replace_term_ids, $term_ids );
	}
}

/**
 * Begins execution of the hooks.
 *
 * @since    1.0.0
 */
function ripplethemes_toolset_hooks() {
	 return Ripplethemes_Toolset_Hooks::instance();
}