<?php
namespace LightGallery;

/**
 * Wrapper Class for creating a Settings page and associated fields.
 */
class SmartlogixSettingsWrapper {
	/**
	 * Settings page menu name.
	 *
	 * @var string $menu_name
	 */
	private $menu_name;

	/**
	 * Settings page title.
	 *
	 * @var string $page_name
	 */
	private $page_name;

	/**
	 * Settings page identifier.
	 *
	 * @var string $page_identifier
	 */
	private $page_identifier;

	/**
	 * Settings page capability required for access.
	 *
	 * @var string $capability
	 */
	private $capability;

	/**
	 * Position in the menu.
	 *
	 * @var integer $position
	 */
	private $position;

	/**
	 * Primary name for saving the settings fields in database.
	 *
	 * @var string $settings_name
	 */
	private $settings_name;

	/**
	 * Parent menu name for creating settings page as a sub menu item.
	 *
	 * @var string $menu_parent
	 */
	private $menu_parent;

	/**
	 * Menu identifier.
	 *
	 * @var string $menu_hook;
	 */
	private $menu_hook;

	/**
	 * Array of registered metaboxes.
	 *
	 * @var array $metaboxes
	 */
	private $metaboxes;

	/**
	 * Array of registered controls.
	 *
	 * @var array $controls
	 */
	private $controls;

	/**
	 * Array of registered callback functions.
	 *
	 * @var array $callback_functions
	 */
	private $callback_functions;

	/**
	 * Initialize class.
	 *
	 * @param array $args Arguments for creating the Settings page and associated fields.
	 */
	public function __construct( $args ) {
		$this->menu_name       = ( isset( $args['menu_name'] ) ? $args['menu_name'] : 'Settings Menu' );
		$this->page_name       = ( isset( $args['page_name'] ) ? $args['page_name'] : 'Settings Page Title' );
		$this->page_identifier = ( isset( $args['page_identifier'] ) ? $args['page_identifier'] : str_replace( ' ', '_', strtolower( $this->menu_name ) ) );
		$this->capability      = ( isset( $args['capability'] ) ? $args['capability'] : 'manage_options' );
		$this->menu_parent     = ( isset( $args['menu_parent'] ) ? $args['menu_parent'] : 'root' );
		$this->position        = ( isset( $args['position'] ) ? $args['position'] : 100 );
		$this->settings_name   = ( isset( $args['settings_name'] ) ? $args['settings_name'] : $this->page_identifier . '_settings' );

		if ( isset( $args['metabox'] ) && ( '' !== $args['metabox'] ) ) {
			$this->metaboxes = [
				str_replace( ' ', '_', strtolower( $args['metabox'] ) ) => $args['metabox'],
			];
		} else {
			if ( isset( $args['metaboxes'] ) && is_array( $args['metaboxes'] ) ) {
				$this->metaboxes = $args['metaboxes'];
			}
		}

		$this->controls = ( ( isset( $args['controls'] ) && is_array( $args['controls'] ) ) ? $args['controls'] : [] );

		$this->callback_functions = ( ( isset( $args['callback_functions'] ) && is_array( $args['callback_functions'] ) ) ? $args['callback_functions'] : [] );

		add_filter( 'admin_init', [ $this, 'admin_init' ] );
		add_filter( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_filter( 'screen_layout_columns', [ $this, 'screen_layout_columns' ], 10, 2 );
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
	}

	/**
	 * The admin_init callback.
	 */
	public function admin_init() {
		register_setting( $this->settings_name, $this->settings_name );
	}

	/**
	 * The admin_menu callback.
	 */
	public function admin_menu() {
		if ( 'root' === $this->menu_parent ) {
			$this->menu_hook = add_menu_page( $this->menu_name, $this->menu_name, $this->capability, $this->page_identifier, [ $this, 'settings_page_content' ], $this->position );
		} else {
			$this->menu_hook = add_submenu_page( $this->menu_parent, $this->menu_name, $this->menu_name, $this->capability, $this->page_identifier, [ $this, 'settings_page_content' ], $this->position );
		}
	}

	/**
	 * The admin_enqueue_scripts callback action.
	 *
	 * @param string $hook The page name.
	 */
	public function admin_enqueue_scripts( $hook ) {
		if ( $hook === $this->menu_hook ) {
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'wp-lists' );
			wp_enqueue_script( 'postbox' );
			if ( isset( $this->callback_functions ) && is_array( $this->callback_functions ) && isset( $this->callback_functions['admin_enqueue_scripts'] ) && is_callable( $this->callback_functions['admin_enqueue_scripts'] ) ) {
				call_user_func( $this->callback_functions['admin_enqueue_scripts'] );
			}
		}
	}

	/**
	 * The screen_layout_columns callback action.
	 *
	 * @param array  $columns The columns data.
	 * @param string $screen The screen name.
	 */
	public function screen_layout_columns( $columns, $screen ) {
		if ( $screen === $this->menu_hook ) {
			$columns[ $this->page_identifier ] = 2;
		}
		return $columns;
	}

	/**
	 * The add_meta_boxes callback.
	 */
	public function add_meta_boxes() {
		if ( isset( $this->metaboxes ) && is_array( $this->metaboxes ) ) {
			$data  = get_option( $this->settings_name );
			$index = 1;
			foreach ( $this->metaboxes as $key => $title ) {
				add_meta_box(
					$this->page_identifier . '_metabox_' . $index,
					$title,
					[ $this, 'meta_box_content' ],
					$this->page_identifier,
					'normal',
					'default',
					[
						'index'     => $index,
						'metaboxID' => $key,
						'data'      => $data,
					]
				);
				$index++;
			}
		}
		if ( isset( $this->callback_functions ) && is_array( $this->callback_functions ) && isset( $this->callback_functions['register_meta_box'] ) && is_callable( $this->callback_functions['register_meta_box'] ) ) {
			call_user_func( $this->callback_functions['register_meta_box'] );
		}
	}

	/**
	 * Content for the registered meta boxes.
	 *
	 * @param object $post The post object.
	 * @param array  $args The Custom post type parameters and controls.
	 */
	public function meta_box_content( $post = [], $args ) {
		$current_sections = [];
		if ( isset( $this->controls ) && is_array( $this->controls ) ) {
			foreach ( $this->controls as $control ) {
				if ( isset( $control['metabox'] ) && isset( $args['args']['metaboxID'] ) && ( $control['metabox'] === $args['args']['metaboxID'] ) ) {
					if ( isset( $control['section'] ) && ( '' !== $control['section'] ) ) {
						if ( ! isset( $current_sections[ $control['section'] ] ) ) {
							$current_sections[ $control['section'] ] = [];
						}
						$current_sections[ $control['section'] ][] = $control;
					}
				}
			}
		}

		if ( isset( $current_sections ) && is_array( $current_sections ) ) {
			echo '<div class="vtabs lg-tabs">';
				echo '<ul>';
			foreach ( $current_sections as $section_name => $section_controls ) {
				echo '<li>';
					echo '<a href="#tabs-' . esc_attr( str_replace( [ ' ', '-' ], '_', wp_strip_all_tags( $section_name ) ) ) . '">' . wp_kses( $section_name, SmartlogixControlsWrapper::get_allowed_html() ) . '</a>';
				echo '</li>';
			}
				echo '</ul>';
			foreach ( $current_sections as $section_name => $section_controls ) {
				echo '<div id="tabs-' . esc_attr( str_replace( [ ' ', '-' ], '_', wp_strip_all_tags( $section_name ) ) ) . '">';
					echo '<div class="lg-tab-content" style="margin: 0; padding: 0 15px; border: 1px solid #ddd; border-radius: 5px; position: relative;">';
						echo '<label style="font-weight: bold; position: absolute; left: 15px; top: -10px; background: #FFFFFF; padding: 0px 10px;">' . wp_kses( $section_name, SmartlogixControlsWrapper::get_allowed_html() ) . '</label>';
				if ( isset( $section_controls ) && is_array( $section_controls ) ) {
					foreach ( $section_controls as $section_control ) {
								echo wp_kses( SmartlogixControlsWrapper::get_control( $section_control['type'], $section_control['label'], $this->settings_name . '_' . $section_control['id'], $this->settings_name . '[' . $section_control['id'] . ']', SmartlogixControlsWrapper::get_value( $args['args']['data'], $section_control['id'] ), ( ( isset( $section_control['data'] ) ) ? $section_control['data'] : null ), ( ( isset( $section_control['info'] ) ) ? $section_control['info'] : null ), 'input widefat' . ( ( isset( $section_control['style'] ) ) ? ' ' . $section_control['style'] : '' ) ), SmartlogixControlsWrapper::get_allowed_html() );
					}
				}
					echo '</div>';
					echo '</div>';
			}
			echo '</div>';
		}
		if ( isset( $this->callback_functions ) && is_array( $this->callback_functions ) && isset( $this->callback_functions['meta_box_content'] ) && is_callable( $this->callback_functions['meta_box_content'] ) ) {
			call_user_func( $this->callback_functions['meta_box_content'], $args['args'] );
		}
		if ( 1 === $args['args']['index'] ) {
			//phpcs:disable
			// Echoing executable inline javascript.
			echo SmartlogixControlsWrapper::get_controls_js();
			//phpcs:enable
		}
	}

	/**
	 * Content for the Settings page.
	 */
	public function settings_page_content() {
		do_action( 'add_meta_boxes', $this->page_identifier, '' );
		echo '<div class="wrap">';
			echo '<h2>' . esc_attr( $this->page_name ) . '</h2>';
			settings_errors();
			echo '<div class="' . esc_attr( $this->page_identifier ) . '_wrap">';
				echo '<form id="' . esc_attr( $this->page_identifier ) . '_form" method="post" action="options.php">';
					settings_fields( $this->settings_name );
					wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
					wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
					echo '<div id="poststuff">';
						echo '<div id="post-body" class="metabox-holder columns-' . ( ( 1 === get_current_screen()->get_columns() ) ? '1' : '2' ) . '">';
							echo '<div id="postbox-container-1" class="postbox-container">';
								echo '<div id="submitpost" class="submitbox">';
									echo '<input type="submit" name="submit" id="submit" class="button button-primary" style="width: 100%;padding: 10px 15px;font-size: 28px;" value="Save / Update">';
								echo '</div>';
								do_meta_boxes( $this->page_identifier, 'side', null );
							echo '</div>';
							echo '<div id="postbox-container-2" class="postbox-container">';
								do_meta_boxes( $this->page_identifier, 'normal', null );
								do_meta_boxes( $this->page_identifier, 'advanced', null );
							echo '</div>';
							echo '<br class="clear">';
						echo '</div>';
						echo '<br class="clear">';
					echo '</div>';
				echo '</form>';
			echo '</div>';
		echo '</div>';
	}
}

