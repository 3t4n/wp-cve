<?php
/**
 * Settings class file.
 *
 * @package Bricksable/Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings class.
 */
class Bricksable_Settings {

	/**
	 * The single instance of Bricksable_Settings.
	 *
	 * @var     object
	 * @access  private
	 * @since   1.0.0
	 */
	private static $instance = null;

	/**
	 * The main plugin object.
	 *
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 *
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	/**
	 * Constructor function.
	 *
	 * @param object $parent Parent object.
	 */
	public function __construct( $parent ) {
		$this->parent = $parent;

		$this->base = 'bricksable_';
		// Check if Bricks Builder is installed.
		if ( 'bricks' !== wp_get_theme()->get( 'Template' ) ) {
			if ( 'Bricks' !== wp_get_theme()->get( 'Name' ) ) {
				add_action(
					'admin_notices',
					function () {
						$message = sprintf(
							/* translators: 1: Theme name 2: Bricksable */
							esc_html__( '%1$s requires %2$s to be installed and activated.', 'bricksable' ),
							'<strong>Bricksable</strong>',
							'<strong>Bricks Builder</strong>'
						);
						$html = sprintf( '<div class="notice notice-warning">%s</div>', wpautop( $message ) );
						echo wp_kses_post( $html );
					}
				);
				return;
			}
		}

		// Initialise settings.
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings.
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add settings page to menu.
		add_action( 'admin_menu', array( $this, 'add_menu_item' ), 11 );

		// Review.
		new Bricksable_Review(
			array(
				'slug'       => 'bricksable',
				'name'       => __( 'Bricksable', 'bricksable' ),
				'time_limit' => intval( '864000' ),
			)
		);

		// Add settings link to plugins page.
		$bricksable_file = isset( $this->parent->file ) ? $this->parent->file : false;

		add_filter(
			'plugin_action_links_' . plugin_basename( $bricksable_file ),
			array(
				$this,
				'add_settings_link',
			)
		);

		// Configure placement of plugin settings page. See readme for implementation.
		add_filter( $this->base . 'menu_settings', array( $this, 'configure_settings' ) );

		// Enable JSON Uploads.
		if ( ! empty( get_option( 'bricksable_json_uploads' ) ) ) {
			add_filter( 'upload_mimes', array( $this, 'bricksable_file_mime_types' ) );
		}

		// Register custom elements.
		add_action(
			'init',
			function () {
				if ( class_exists( '\Bricks\Elements' ) ) {
					if ( 'on' === get_option( 'bricksable_all_elements' ) || false === get_option( 'bricksable_all_elements' ) ) {
						$file_names = $this->bricksable_load_elements();
					} else {
						$file_names = $this->bricksable_check_elements();
					}

					foreach ( $file_names as $file_name ) {
						$file = __DIR__ . "/elements/$file_name/element-$file_name.php";
						// Register all element in builder and frontend.
						\Bricks\Elements::register_element( $file );
					}
				}
			},
			11
		);

		add_filter(
			'bricks/builder/i18n',
			function ( $i18n ) {
				$i18n['bricksable'] = esc_html__( 'Bricksable', 'bricksable' );

				return $i18n;
			}
		);

		// Save Messages.
		if ( ! empty( get_option( 'bricksable_bricks_builder_filter_save_messages' ) ) ) {
			add_filter(
				'bricks/builder/save_messages',
				function ( $messages ) {
					$messages = explode( ',', get_option( 'bricksable_bricks_builder_filter_save_messages' ) );
					return $messages;
				}
			);
		}
	}

	/**
	 * Initialise settings
	 *
	 * @return void
	 */
	public function init_settings() {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 *
	 * @return void
	 */
	public function add_menu_item() {

		$args = $this->menu_settings();

		// Do nothing if wrong location key is set.
		if ( is_array( $args ) && isset( $args['location'] ) && function_exists( 'add_' . $args['location'] . '_page' ) ) {
			switch ( $args['location'] ) {
				case 'options':
				case 'submenu':
					$page = add_submenu_page( $args['parent_slug'], $args['page_title'], $args['menu_title'], $args['capability'], $args['menu_slug'], $args['function'] );
					break;
				case 'menu':
					$page = add_menu_page( $args['page_title'], $args['menu_title'], $args['capability'], $args['menu_slug'], $args['function'], $args['icon_url'], $args['position'] );
					break;
				default:
					return;
			}
			add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
		}
	}

	/**
	 * Prepare default settings page arguments
	 *
	 * @return mixed|void
	 */
	private function menu_settings() {
		return apply_filters(
			$this->base . 'menu_settings',
			array(
				'location'    => 'submenu', // Possible settings: options, menu, submenu.
				'parent_slug' => 'bricks',
				'page_title'  => __( 'Bricksable', 'bricksable' ),
				'menu_title'  => __( 'Bricksable', 'bricksable' ),
				'capability'  => 'manage_options',
				'menu_slug'   => $this->parent->_token . '_settings',
				'function'    => array( $this, 'settings_page' ),
				'icon_url'    => '',
				'position'    => 99,
			)
		);
	}

	/**
	 * Container for settings page arguments
	 *
	 * @param array $settings Settings array.
	 *
	 * @return array
	 */
	public function configure_settings( $settings = array() ) {
		return $settings;
	}

	/**
	 * Load settings JS & CSS
	 *
	 * @return void
	 */
	public function settings_assets() {

		// We're including the farbtastic script & styles here because they're needed for the colour picker
		// If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the wpt-admin-js script below.
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );

		// We're including the WP media scripts here because they're needed for the image upload field.
		// If you're not including an image upload then you can leave this function call out.
		// wp_enqueue_media().

		wp_register_script( $this->parent->_token . '-settings', $this->parent->assets_url . 'js/settings' . $this->parent->script_suffix . '.js', array( 'farbtastic', 'jquery' ), '1.0.0', true );
		wp_enqueue_script( $this->parent->_token . '-settings' );
	}

	/**
	 * Add settings link to plugin list table
	 *
	 * @param  array $links Existing links.
	 * @return array        Modified links.
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'bricksable' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	/**
	 * Get Elements
	 */
	public function get_elements() {
		$elements     = array();
		$elements_dir = __DIR__ . '/elements';
		$scan         = scandir( $elements_dir );
		foreach ( $scan as $result ) {
			if ( '.' === $result || '..' === $result ) {
				continue;
			}
			if ( is_dir( $elements_dir . '/' . $result ) ) {
				$elements[] = $result;
			}
		}
		return $elements;
	}

	/**
	 * Build settings fields
	 *
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields() {
		$settings['general'] = array(
			'title'  => __( 'General', 'bricksable' ),
			'fields' => array(
				array(
					'id'          => 'json_uploads',
					'label'       => __( 'JSON Uploads', 'bricksable' ),
					'description' => __( 'Please be careful about uploading Lottie JSON files from an unknown source. JSON files may potentially include malicious content. You should therefore download JSON files only from trusted sources, and only enable JSON uploads for user roles that you trust to follow this rule.', 'bricksable' ),
					'type'        => 'checkbox_multi',
					'options'     => $this->get_editable_roles(),
				),
			),
		);

		$fields = array(
			array(
				'id'          => 'all_elements',
				'description' => __( 'Check all Bricks Custom Elements.', 'bricksable' ),
				'type'        => 'checkbox',
				'default'     => 'on',
			),
		);

		$elements = $this->get_elements();
		foreach ( $elements as $element ) {
			$label    = ucwords( str_replace( '-', ' ', $element ) );
			$fields[] = array(
				'id'          => $element,
				'label'       => esc_attr( $label ),
				/* translators: %s: enable elements */
				'description' => sprintf( __( 'Enable %1$s Elements', 'bricksable' ), esc_html( $label ) ),
				'type'        => 'checkbox',
				'default'     => 'on',
			);
		}
		$settings['elements'] = array(
			'title'  => __( 'Elements', 'bricksable' ),
			'fields' => $fields,
		);

		$settings['bricksbuilder'] = array(
			'title'  => __( 'Bricks Builder', 'bricksable' ),
			'fields' => array(
				array(
					'id'          => 'bricks_builder_filter_save_messages',
					'label'       => __( 'Save Messages', 'bricksable' ),
					'description' => __( 'You can customize the Bricks Save Messages by replacing them with your own personal messages. Make sure to separate each message using commas.', 'bricksable' ),
					'type'        => 'textarea',
					'placeholder' => __( 'Your work has been saved!, Securely saved!, Your work is safe and sound!', 'bricksable' ),
				),
			),
		);

		$settings['misc'] = array(
			'title'  => __( 'Misc', 'bricksable' ),
			'fields' => array(
				array(
					'id'          => 'uninstall_on_delete',
					'label'       => __( 'Remove Data on Uninstall?', 'bricksable' ),
					'description' => __( 'Check this box if you would like Bricksable to completely remove all of its data when the plugin is deleted.', 'bricksable' ),
					'type'        => 'checkbox',
				),
			),
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 *
	 * @return void
	 */
	public function register_settings() {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab.
			//phpcs:disable
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = sanitize_text_field( wp_unslash( $_POST['tab'] ) );
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
				}
			}
			//phpcs:enable

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section !== $section ) {
					continue;
				}

				// Add section to page.
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field.
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field.
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page.
					add_settings_field(
						$field['id'],
						isset( $field['label'] ) ? $field['label'] : '',
						array( $this->parent->admin, 'display_field' ),
						$this->parent->_token . '_settings',
						$section,
						array(
							'field'  => $field,
							'prefix' => $this->base,
						)
					);
				}

				if ( ! $current_section ) {
					break;
				}
			}
		}
	}

	/**
	 * Settings section.
	 *
	 * @param array $section Array of section ids.
	 * @return void
	 */
	public function settings_section( $section ) {
		$html = '';
		if ( isset( $this->settings[ $section['id'] ]['description'] ) ) {
			$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		}
		echo wp_kses( $html, $this->allowed_htmls );
	}

	/**
	 * Load settings page content.
	 *
	 * @return void
	 */
	public function settings_page() {

		// Build page HTML.
		$html      = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
			$html .= '<h2>' . __( 'Bricksable Settings', 'bricksable' ) . '</h2>' . "\n";

			$tab = '';

			$nonce_name = 'bricksable_settings_nonce';
			$nonce      = sanitize_text_field( wp_create_nonce( $nonce_name ) );

		if ( isset( $_POST['tab'] ) ) {
			if ( wp_verify_nonce( $nonce, $nonce_name ) ) {
				$current_section = sanitize_text_field( wp_unslash( $_POST['tab'] ) );
			}
		} elseif ( isset( $_GET['tab'] ) && sanitize_text_field( wp_unslash( $_GET['tab'] ) ) ) {
				$current_section = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
		}

		// Show page tabs.
		if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

			$html .= '<h2 class="nav-tab-wrapper">' . "\n";

			$c = 0;
			foreach ( $this->settings as $section => $data ) {

				// Set tab class.
				$class = 'nav-tab';
				if ( ! isset( $_GET['tab'] ) ) {
					if ( 0 === $c ) {
						$class .= ' nav-tab-active';
					}
				} elseif ( isset( $_GET['tab'] ) && $section === $_GET['tab'] ) {
						$tab    = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
						$class .= ' nav-tab-active';
				}

				// Set tab link.
				$tab_link = add_query_arg(
					array(
						'tab'       => $section,
						$nonce_name => $nonce,
					)
				);

				if ( isset( $_GET['settings-updated'] ) ) {
					$updated = sanitize_text_field( wp_unslash( $_GET['settings-updated'] ) );

					$tab_link = remove_query_arg( 'settings-updated', $tab_link );
				}

				// Output tab.
				$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

				++$c;
			}

			$html .= '</h2>' . "\n";
		}

			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

				// Get settings fields.
				ob_start();
				settings_fields( $this->parent->_token . '_settings' );
				do_settings_sections( $this->parent->_token . '_settings' );
				$html .= ob_get_clean();

				$html     .= '<p class="submit">' . "\n";
					$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings', 'bricksable' ) ) . '" />' . "\n";
				$html     .= '</p>' . "\n";
			$html         .= '</form>' . "\n";
		$html             .= '</div>' . "\n";

		echo wp_kses( $html, $this->allowed_htmls );
	}

	/**
	 * Main Bricksable_Settings Instance
	 *
	 * Ensures only one instance of Bricksable_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Bricksable()
	 * @param object $parent Object instance.
	 * @return object Bricksable_Settings instance
	 */
	public static function instance( $parent ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $parent );
		}
		return self::$instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cloning of Bricksable_API is forbidden.' ) ), esc_attr( $this->parent->_version ) );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Unserializing instances of Bricksable_API is forbidden.' ) ), esc_attr( $this->parent->_version ) );
	} // End __wakeup()

	/**
	 * Allowed html for output.
	 *
	 * @var array
	 */
	public $allowed_htmls = array(
		'a'        => array(
			'href'  => array(),
			'title' => array(),
			'class' => array(),
		),
		'h1'       => array(
			'href'  => array(),
			'title' => array(),
			'class' => array(),
		),
		'h2'       => array(
			'href'  => array(),
			'title' => array(),
			'class' => array(),
		),
		'h3'       => array(
			'href'  => array(),
			'title' => array(),
			'class' => array(),
		),
		'h4'       => array(
			'href'  => array(),
			'title' => array(),
			'class' => array(),
		),
		'input'    => array(
			'id'                  => array(),
			'type'                => array(),
			'name'                => array(),
			'placeholder'         => array(),
			'value'               => array(),
			'class'               => array(),
			'checked'             => array(),
			'style'               => array(),
			'data-uploader_title' => array(),
			'data-uploader_text'  => array(),
		),
		'select'   => array(
			'id'          => array(),
			'type'        => array(),
			'name'        => array(),
			'placeholder' => array(),
			'value'       => array(),
			'multiple'    => array(),
			'style'       => array(),
		),
		'option'   => array(
			'id'          => array(),
			'type'        => array(),
			'name'        => array(),
			'placeholder' => array(),
			'value'       => array(),
			'multiple'    => array(),
			'selected'    => array(),
		),
		'label'    => array(
			'for'   => array(),
			'title' => array(),
		),
		'span'     => array(
			'class' => array(),
			'title' => array(),
		),
		'table'    => array(
			'scope' => array(),
			'title' => array(),
			'class' => array(),
			'role'  => array(),
		),
		'tbody'    => array(
			'scope' => array(),
			'title' => array(),
			'class' => array(),
			'role'  => array(),
		),
		'th'       => array(
			'scope' => array(),
			'title' => array(),
		),
		'form'     => array(
			'method'      => array(),
			'type'        => array(),
			'name'        => array(),
			'placeholder' => array(),
			'value'       => array(),
			'multiple'    => array(),
			'selected'    => array(),
			'action'      => array(),
			'enctype'     => array(),
		),
		'div'      => array(
			'class' => array(),
			'id'    => array(),
		),
		'img'      => array(
			'class' => array(),
			'id'    => array(),
			'src'   => array(),
		),
		'textarea' => array(
			'class'       => array(),
			'id'          => array(),
			'rows'        => array(),
			'cols'        => array(),
			'name'        => array(),
			'placeholder' => array(),
			'spellcheck'  => array(),
		),
		'tr'       => array(),
		'td'       => array(),
		'p'        => array(),
		'br'       => array(),
		'em'       => array(),
		'strong'   => array(),
		'th'       => array(),
	);

	/**
	 * Get WP Roles
	 *
	 * @return array Exclude subscriber and other very limited roles
	 */
	public function get_editable_roles() {
		$roles     = wp_roles()->get_names();
		$has_roles = array();
		foreach ( $roles as $key => $label ) {
			$role = get_role( $key );
			if ( ! $role->has_cap( 'edit_posts' ) ) {
				continue;
			}
			$has_roles[ $key ] = $label;
		}
		return $has_roles;
	}

	/**
	 * Mine Types
	 *
	 * @param return $mimes Check WP roles and allow json upload.
	 */
	public function bricksable_file_mime_types( $mimes ) {
		$user            = wp_get_current_user();
		$current_options = get_option( 'bricksable_json_uploads' );
		if ( array_intersect( $current_options, $user->roles ) ) {
			$mimes['json'] = 'application/json';
		}
		return $mimes;
	}

	/**
	 * Bricksable Elements
	 */
	public function bricksable_load_elements() {
		$file_names = $this->get_elements();
		return $file_names;
	}

	/**
	 * Bricksable Check Elements
	 */
	public function bricksable_check_elements() {
		$file_names = array();
		$elements   = $this->get_elements();

		foreach ( $elements as $element ) {
			if ( 'on' === get_option( 'bricksable_' . $element ) || false === get_option( 'bricksable_' . $element ) ) {
				$file_names[] = $element;
			}
		}

		return $file_names;
	}
}
