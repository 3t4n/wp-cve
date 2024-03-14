<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    LaStudio Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'LaStudio_Kit_Settings' ) ) {

	/**
	 * Define LaStudio_Kit_Settings class
	 */
	class LaStudio_Kit_Settings {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * [$key description]
		 * @var string
		 */
		public $key = 'lastudio-kit-settings';

		/**
		 * Access Token transient option key
		 *
		 * @var string
		 */
		private $insta_updated_access_token_key = 'lastudio_kit_instagram_updated_access_token';

		/**
		 * [$builder description]
		 * @var object
		 */
		public $builder  = null;

		/**
		 * [$settings description]
		 * @var array
		 */
		public $settings = null;

		/**
		 * Available Widgets array
		 *
		 * @var array
		 */
		public $available_widgets = [];

		/**
		 * [$default_available_extensions description]
		 * @var array
		 */
		public $default_available_extensions = [
			'motion_effects'        => 'true',
			'floating_effects'      => 'true',
			'css_transform'         => 'true',
			'wrapper_link'          => 'true',
			'element_visibility'    => 'true',
			'custom_css'            => 'true',
			'disable_wp_default_widgets'  => 'false',
			'album_content_type'    => 'false',
			'event_content_type'    => 'false',
			'portfolio_content_type'  => 'false',
		];

		/**
		 * [$settings_page_config description]
		 * @var array
		 */
		public $settings_page_config = [];

		/**
		 * Available Widgets Slugs
		 *
		 * @var array
		 */
		public $available_widgets_slugs = [];

        public function __set(string $name, mixed $value): void {
            $this->{$name} = $value;
        }

		/**
		 * Init page
		 */
		public function init() {

            $available_widgets = [];
            $available_widgets_slugs = [];

			foreach ( glob( lastudio_kit()->plugin_path( 'includes/addons/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class' => 'Class', 'name' => 'Name', 'slug' => 'Slug' ) );

				$slug = basename( $file, '.php' );
                $available_widgets[$slug] = $data['name'];
                $available_widgets_slugs[] = $data['slug'];
			}
            $this->available_widgets = $available_widgets;
            $this->available_widgets_slugs = $available_widgets_slugs;

			// Refresh Instagram Access Token
			add_action( 'admin_init', array( $this, 'refresh_instagram_access_token' ) );
		}

		/**
		 * [generate_frontend_config_data description]
		 * @return [type] [description]
		 */
		public function generate_frontend_config_data() {

			$default_active_widgets = [];
            $available_widgets = [];

			foreach ( $this->available_widgets as $slug => $name ) {

				$available_widgets[] = [
					'label' => $name,
					'value' => $slug,
				];

				$default_active_widgets[ $slug ] = 'true';
			}

			$active_widgets = $this->get( 'avaliable_widgets', $default_active_widgets );

			$available_extensions = [
				[
					'label' => esc_html__( 'Motion Effects Extension', 'lastudio-kit' ),
					'value' => 'motion_effects',
				],
                [
					'label' => esc_html__( 'Floating Effects Extension', 'lastudio-kit' ),
					'value' => 'floating_effects',
				],
                [
					'label' => esc_html__( 'CSS Transform Extension', 'lastudio-kit' ),
					'value' => 'css_transform',
				],
                [
					'label' => esc_html__( 'Wrapper Links', 'lastudio-kit' ),
					'value' => 'wrapper_link',
				],
                [
					'label' => esc_html__( 'Element Visibility Logic', 'lastudio-kit' ),
					'value' => 'element_visibility',
				],
                [
					'label' => esc_html__( 'Custom CSS', 'lastudio-kit' ),
					'value' => 'custom_css',
				],
                [
					'label' => esc_html__( 'Portfolio Content Type', 'lastudio-kit' ),
					'value' => 'portfolio_content_type',
				],
                [
					'label' => esc_html__( 'Events Content Type', 'lastudio-kit' ),
					'value' => 'event_content_type',
				],
                [
					'label' => esc_html__( 'Album Content Type', 'lastudio-kit' ),
					'value' => 'album_content_type',
				],
                [
					'label' => esc_html__( 'Disable WP Default Widgets', 'lastudio-kit' ),
					'value' => 'disable_wp_default_widgets',
				],
			];

			$active_extensions = $this->get( 'avaliable_extensions', $this->default_available_extensions );

            $active_extensions = array_merge(
                [
                    'album_content_type'    => 'false',
                    'event_content_type'    => 'false',
                    'portfolio_content_type'  => 'false'
                ],
                $active_extensions
            );

			$rest_api_url = apply_filters( 'lastudio-kit/rest/frontend/url', get_rest_url() );

            $breadcrumbs_taxonomy_options = [];

            $post_types = get_post_types( array( 'public' => true ), 'objects' );

            $perpage_value = $this->get('posts_per_page_manager', []);

            $posts_perpage_context = [
                [
                    'label'     => esc_html__('Default', 'lastudio-kit'),
                    'desc'      => esc_html__('Default posts per page', 'lastudio-kit'),
                    'name'      => 'is_blog',
                ],
                [
                    'label'     => esc_html__('Tags', 'lastudio-kit'),
                    'desc'      => esc_html__('Number posts per page for Tags', 'lastudio-kit'),
                    'name'      => 'is_tags',
                ],
                [
                    'label'     => esc_html__('Category', 'lastudio-kit'),
                    'desc'      => esc_html__('Number posts per page for Category', 'lastudio-kit'),
                    'name'      => 'is_category',
                ]
            ];

            $tax_deny_list = array( 'product_shipping_class' );

            $perpage_deny_post_types = array( 'post', 'page', 'attachment', 'e-landing-page', 'elementor_library' );

            if ( is_array( $post_types ) && ! empty( $post_types ) ) {

                foreach ( $post_types as $post_type ) {
                    $taxonomies = get_object_taxonomies( $post_type->name, 'objects' );
                    if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {

                        $options = [
                            [
                                'label' => esc_html__( 'None', 'lastudio-kit' ),
                                'value' => '',
                            ]
                        ];

                        foreach ( $taxonomies as $tax ) {

                            if ( ! $tax->public ) {
                                continue;
                            }

                            if( in_array( $tax->name, $tax_deny_list) ){
                                continue;
                            }

                            $options[] = [
                                'label' => $tax->labels->singular_name,
                                'value' => $tax->name,
                            ];
                        }

                        $breadcrumbs_taxonomy_options[ 'breadcrumbs_taxonomy_' . $post_type->name ] = array(
                            'value'   => $this->get( 'breadcrumbs_taxonomy_' . $post_type->name, ( 'post' === $post_type->name ) ? 'category' : '' ),
                            'options' => $options,
                        );
                    }

                    if(!in_array($post_type->name, $perpage_deny_post_types)){
                        $posts_perpage_context[] = [
                            'label' => $post_type->label,
                            'desc' => sprintf('Number of items displayed in the %1$s archive.', $post_type->label),
                            'name' => 'post_type__' . $post_type->name
                        ];
                    }
                }
            }

            $settingsData = [
                'disable-gutenberg-block' => [
                    'value' => $this->get( 'disable-gutenberg-block', '' ),
                ],
                'svg-uploads'             => [
                    'value' => $this->get( 'svg-uploads', 'enabled' ),
                ],
                'template-cache'             => [
                    'value' => $this->get( 'template-cache', '' ),
                ],
                'lastudio_kit_templates'           => [
                    'value' => $this->get( 'lastudio_kit_templates', 'enabled' ),
                ],
                'gmap_api_key'                 => [
                    'value' => $this->get( 'gmap_api_key', '' ),
                ],
                'gmap_backend_api_key'                 => [
                    'value' => $this->get( 'gmap_backend_api_key', '' ),
                ],
                'disable_gmap_api_js'          => [
                    'value' => $this->get( 'disable_gmap_api_js', false ),
                ],
                'mailchimp-api-key'       => [
                    'value' => $this->get( 'mailchimp-api-key', '' ),
                ],
                'mailchimp-list-id'       => [
                    'value' => $this->get( 'mailchimp-list-id', '' ),
                ],
                'mailchimp-double-opt-in' => [
                    'value' => $this->get( 'mailchimp-double-opt-in', false ),
                ],
                'insta_access_token'      => [
                    'value' => $this->get( 'insta_access_token', '' ),
                ],
                'insta_business_access_token' => [
                    'value' => $this->get( 'insta_business_access_token', '' ),
                ],
                'insta_business_user_id' => [
                    'value' => $this->get( 'insta_business_user_id', '' ),
                ],
                'weather_api_key'         => [
                    'value' => $this->get( 'weather_api_key', '' ),
                ],
                'portfolio_per_page'         => [
                    'value' => $this->get( 'portfolio_per_page', '' ),
                ],
                'avaliable_widgets'       => [
                    'value'   => $active_widgets,
                    'options' => $available_widgets,
                ],
                'avaliable_extensions'    => [
                    'value'   => $active_extensions,
                    'options' => $available_extensions,
                ],
                'single_post_template' => [
                    'value'   => $this->get( 'single_post_template', 'default' ),
                    'options' => $this->prepare_options_list( $this->get_single_post_templates() ),
                ],
                'single_page_template' => [
                    'value'   => $this->get( 'single_page_template', 'default' ),
                    'options' => $this->prepare_options_list( $this->get_single_page_templates() ),
                ],
                'custom_fonts'          => [
                    'i18n'    => [
                        'new_font'                  => esc_html__('New Font', 'lastudio-kit'),
                        'new_variation'             => esc_html__('New Variation', 'lastudio-kit'),
                        'add_new_font'              => esc_html__('Add New Font', 'lastudio-kit'),
                        'add_new_font_variation'    => esc_html__('Add New Variation', 'lastudio-kit'),
                    ],
                    'value'   => $this->get( 'custom_fonts', [] )
                ],
                'head_code' => [
                    'value' => $this->get( 'head_code', '' ),
                ],
                'custom_css' => [
                    'value' => $this->get( 'custom_css', '' ),
                ],
                'footer_code' => [
                    'value' => $this->get( 'footer_code', '' ),
                ],
            ];

            if( function_exists('wc_get_attribute_taxonomies') ){
                $settingsData['swatches__is_disable'] = [
                    'value' => $this->get( 'swatches__is_disable', '' ),
                ];
                $settingsData['swatches_threshold'] = [
                    'value' => $this->get( 'swatches_threshold', '30' ),
                ];
                $settingsData['swatches_swatches_size_width'] = [
                    'value' => $this->get( 'swatches_swatches_size_width', '40' ),
                ];
                $settingsData['swatches_swatches_size_height'] = [
                    'value' => $this->get( 'swatches_swatches_size_height', '40' ),
                ];
                $settingsData['swatches_swatches_variation_form'] = [
                    'value' => $this->get( 'swatches_swatches_variation_form', '' ),
                ];
                $settingsData['swatches_swatches_max_item'] = [
                    'value' => $this->get( 'swatches_swatches_max_item', '5' ),
                ];
                $settingsData['swatches_swatches_more_text'] = [
                    'value' => $this->get( 'swatches_swatches_more_text', '' ),
                ];

                $wc_tax_opts = [];
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                if ( ! empty( $attribute_taxonomies ) ) {
                    foreach ( $attribute_taxonomies as $tax ) {
                        if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
                            $wc_tax_opts[] = [
                                'label' => $tax->attribute_label,
                                'value' => $tax->attribute_name
                            ];
                        }
                    }
                }

                $settingsData['swatches_swatches_attribute_in_list'] = [
                    'value' => $this->get( 'swatches_swatches_attribute_in_list', [] ),
                    'options' => $wc_tax_opts
                ];
            }

            $settingsData = array_merge($settingsData, $breadcrumbs_taxonomy_options);

            $perpage_value_tmp = [];
            foreach ($posts_perpage_context as $item){
                $perpage_value_tmp[$item['name']] = $perpage_value[$item['name']] ?? '';
            }
            $posts_per_page_manager = [
                'options' => $posts_perpage_context,
                'value' => $perpage_value_tmp
            ];
            $settingsData['posts_per_page_manager']   = $posts_per_page_manager;

			$recaptchav3 = $this->get('recaptchav3', []);
			$settingsData['recaptchav3'] = [
				'value' => [
					'disable'       => !empty($recaptchav3['disable']),
					'site_key'      => $recaptchav3['site_key'] ?? '',
					'secret_key'    => $recaptchav3['secret_key'] ?? ''
				]
			];

			$this->settings_page_config = [
				'messages' => [
					'saveSuccess' => esc_html__( 'Saved', 'lastudio-kit' ),
					'saveError'   => esc_html__( 'Error', 'lastudio-kit' ),
				],
				'settingsApiUrl' => $rest_api_url . 'lastudio-kit-api/v1/plugin-settings',
				'settingsData' => apply_filters('lastudio-kit/settings/data', $settingsData)
			];

			return $this->settings_page_config;
		}

		/**
		 * Return settings page URL
		 *
		 * @param  string $subpage
		 * @return string
		 */
		public function get_settings_page_link( $subpage = 'general' ) {

			return add_query_arg(
				array(
					'page'    => 'lastudio-kit-dashboard-settings-page',
					'subpage' => 'lastudio-kit-' . $subpage . '-settings',
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		/**
		 * [get description]
		 * @param  [type]  $setting [description]
		 * @param  boolean $default [description]
		 * @return [type]           [description]
		 */
		public function get( $setting, $default = false ) {
			return $this->get_option( $setting, $default );
		}

		/**
		 * [get description]
		 * @param  [type]  $setting [description]
		 * @param  boolean $default [description]
		 * @return [type]           [description]
		 */
		public function get_option( $setting, $default = false ) {

			if ( null === $this->settings ) {
				$this->settings = get_option( $this->key, array() );
			}

			return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : $default;

		}

		/**
		 * Refresh Instagram Access Token
		 *
		 * @return void
		 */
		public function refresh_instagram_access_token() {
			$access_token = $this->get( 'insta_access_token' );
			$access_token = trim( $access_token );

			if ( empty( $access_token ) ) {
				return;
			}

			$updated = get_transient( $this->insta_updated_access_token_key );

			if ( ! empty( $updated ) ) {
				return;
			}

			$url = add_query_arg(
				array(
					'grant_type'   => 'ig_refresh_token',
					'access_token' => $access_token,
				),
				'https://graph.instagram.com/refresh_access_token'
			);

			$response = wp_remote_get( $url );

			if ( ! $response || is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				set_transient( $this->insta_updated_access_token_key, 'error', DAY_IN_SECONDS );
				return;
			}

			$body = wp_remote_retrieve_body( $response );

			if ( ! $body ) {
				set_transient( $this->insta_updated_access_token_key, 'error', DAY_IN_SECONDS );
				return;
			}

			$body = json_decode( $body, true );

			if ( empty( $body['access_token'] ) || empty( $body['expires_in'] ) ) {
				set_transient( $this->insta_updated_access_token_key, 'error', DAY_IN_SECONDS );
				return;
			}

			set_transient( $this->insta_updated_access_token_key, 'updated', 30 * DAY_IN_SECONDS );
		}

        /**
         * Get single post templates.
         *
         * @return array
         */
        public function get_single_post_templates() {
            $default_template = array( 'default' => apply_filters( 'default_page_template_title', esc_html__( 'Default Template', 'lastudio-kit' ) ) );

            if ( ! function_exists( 'get_page_templates' ) ) {
                return $default_template;
            }

            $post_templates = get_page_templates( null, 'post' );

            ksort( $post_templates );

            $templates = array_combine(
                array_values( $post_templates ),
                array_keys( $post_templates )
            );

            $templates = array_merge( $default_template, $templates );

            return $templates;
        }

        /**
         * Get single page templates.
         *
         * @return array
         */
        public function get_single_page_templates() {
            $default_template = array( 'default' => apply_filters( 'default_page_template_title', esc_html__( 'Default Template', 'lastudio-kit' ) ) );

            if ( ! function_exists( 'get_page_templates' ) ) {
                return $default_template;
            }

            $post_templates = get_page_templates( null );

            ksort( $post_templates );

            $templates = array_combine(
                array_values( $post_templates ),
                array_keys( $post_templates )
            );

            $templates = array_merge( $default_template, $templates );

            return $templates;
        }

        /**
         * Prepare options list
         *
         * @param  array $options
         * @return array
         */
        public function prepare_options_list( $options = array() ) {

            $result = array();

            foreach ( $options as $slug => $label ) {
                $result[] = array(
                    'value' => $slug,
                    'label' => $label,
                );
            }

            return $result;
        }

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		public function is_combine_js_css(){
			return false;
		}
	}
}

/**
 * Returns instance of LaStudio_Kit_Settings
 *
 * @return object
 */
function lastudio_kit_settings() {
	return LaStudio_Kit_Settings::get_instance();
}

lastudio_kit_settings()->init();
