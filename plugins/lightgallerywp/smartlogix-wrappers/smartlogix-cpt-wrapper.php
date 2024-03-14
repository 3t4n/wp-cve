<?php
namespace LightGallery;

/**
 * Wrapper Class for Custom Post Type registration and fields handling.
 */
class SmartlogixCPTWrapper {
	/**
	 * Custom post type name.
	 *
	 * @var string $name
	 */
	private $name;

	/**
	 * Custom post type singular name.
	 *
	 * @var string $singular_name
	 */
	private $singular_name;

	/**
	 * Custom post type plural name.
	 *
	 * @var string $plural_name
	 */
	private $plural_name;

	/**
	 * Array of supported features for the custom post type.
	 *
	 * @var array $supports
	 */
	private $supports;

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
	 * Array of labels for the Custom post type.
	 *
	 * @var array $cpt_labels
	 */
	private $cpt_labels;

	/**
	 * Array of parameters for the Custom post type.
	 *
	 * @var array $cpt_args
	 */
	private $cpt_args;

	/**
	 * Initialize class.
	 *
	 * @param array $args Arguments for creating the Custom post type and related fields.
	 */
	public function __construct( $args ) {
		$this->name          = ( isset( $args['name'] ) ? $args['name'] : 'cpt_name' );
		$this->singular_name = ( isset( $args['singular_name'] ) ? $args['singular_name'] : 'CPT Singular Name' );
		$this->plural_name   = ( isset( $args['plural_name'] ) ? $args['plural_name'] : 'CPT Plural Name' );
		$this->supports      = ( ( isset( $args['supports'] ) && is_array( $args['supports'] ) ) ? $args['supports'] : [ 'title' ] );

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

		$this->cpt_labels = [
			'name'               => $this->plural_name,
			'singular_name'      => $this->singular_name,
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New ' . $this->singular_name,
			'edit_item'          => 'Edit ' . $this->singular_name,
			'new_item'           => 'New ' . $this->singular_name,
			'view_item'          => 'View ' . $this->singular_name,
			'search_items'       => 'Search ' . $this->plural_name,
			'not_found'          => 'No ' . $this->plural_name . ' found',
			'not_found_in_trash' => 'No ' . $this->plural_name . ' found in Trash',
			'parent_item_colon'  => 'Parent ' . $this->plural_name . ':',
			'menu_name'          => $this->plural_name,
		];
		if ( isset( $args['labels'] ) && is_array( $args['labels'] ) ) {
			$this->cpt_labels = array_merge( $this->cpt_labels, $args['labels'] );
		}

		$this->cpt_args = [
			'labels'               => $this->cpt_labels,
			'hierarchical'         => true,
			'description'          => $this->plural_name,
			'supports'             => $this->supports,
			'public'               => true,
			'show_ui'              => true,
			'menu_position'        => 50,
			'register_meta_box_cb' => [ $this, 'register_meta_box' ],
			'show_in_nav_menus'    => true,
			'publicly_queryable'   => true,
			'exclude_from_search'  => true,
			'has_archive'          => false,
			'query_var'            => true,
			'can_export'           => true,
			'rewrite'              => true,
			'capability_type'      => 'post',
		];

		if ( isset( $args['args'] ) && is_array( $args['args'] ) ) {
			$this->cpt_args = array_merge( $this->cpt_args, $args['args'] );
		}

		add_action( 'init', [ $this, 'init' ] );
		add_filter( 'post_updated_messages', [ $this, 'post_updated_messages' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( 'save_post', [ $this, 'save_post' ] );
	}

	/**
	 * The init action callback.
	 */
	public function init() {
		register_post_type( $this->name, $this->cpt_args );

		if ( isset( $this->callback_functions ) && is_array( $this->callback_functions ) && isset( $this->callback_functions['init'] ) && is_callable( $this->callback_functions['init'] ) ) {
			call_user_func( $this->callback_functions['init'] );
		}
	}

	/**
	 * The post_updated_messages callback action.
	 *
	 * @param array $messages The Custom post type messages.
	 */
	public function post_updated_messages( $messages ) {
		$post      = get_post();
		$post_type = get_post_type( $post );

		$messages[ $this->name ] = [
			0  => '',
			1  => $this->singular_name . ' updated.',
			2  => 'Custom field updated.',
			3  => 'Custom field deleted.',
			4  => $this->singular_name . ' updated.',
			5  => 'Restored to revision.',
			6  => $this->singular_name . ' published.',
			7  => $this->singular_name . ' saved.',
			8  => $this->singular_name . ' submitted.',
			9  => sprintf( $this->singular_name . ' scheduled for: <strong>%1$s</strong>.', date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) ) ),
			10 => $this->singular_name . ' draft updated.',
		];

		if ( isset( $this->callback_functions ) && is_array( $this->callback_functions ) && isset( $this->callback_functions['post_updated_messages'] ) && is_callable( $this->callback_functions['post_updated_messages'] ) ) {
			call_user_func( $this->callback_functions['post_updated_messages'], $messages );
		}

		return $messages;
	}

	/**
	 * The admin_enqueue_scripts callback action.
	 *
	 * @param string $hook The page name.
	 */
	public function admin_enqueue_scripts( $hook ) {
		if ( in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
			$screen = get_current_screen();
			if ( is_object( $screen ) && $this->name === $screen->post_type ) {
				if ( ! did_action( 'wp_enqueue_media' ) ) {
					wp_enqueue_media();
				}
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-tabs' );
				if ( isset( $this->callback_functions ) && is_array( $this->callback_functions ) && isset( $this->callback_functions['admin_enqueue_scripts'] ) && is_callable( $this->callback_functions['admin_enqueue_scripts'] ) ) {
					call_user_func( $this->callback_functions['admin_enqueue_scripts'] );
				}
			}
		}
	}

	/**
	 * The register_meta_box callback action.
	 */
	public function register_meta_box() {
		global $post;
		$data = get_post_meta( $post->ID, 'wp_' . $this->name . '_data', true );
		if ( isset( $this->metaboxes ) && is_array( $this->metaboxes ) ) {
			$index = 1;
			foreach ( $this->metaboxes as $key => $title ) {
				add_meta_box(
					'smartlogix_cpt_metabox_' . $index,
					$title,
					[ $this, 'meta_box_content' ],
					$this->name,
					'normal',
					'default',
					[
						'index'     => $index,
						'metaboxID' => $key,
						'post_id'   => $post->ID,
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
		if ( 1 === $args['args']['index'] ) {
			wp_nonce_field( plugin_basename( __FILE__ ), 'wp_' . $this->name . '_nonce' );
			//phpcs:disable
			// Echoing executable inline javascript.
			echo SmartlogixControlsWrapper::get_controls_js();
			//phpcs:enable
		}

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

		if ( isset( $current_sections ) && is_array( $current_sections ) && ( count( $current_sections ) > 0 ) ) {
			echo '<div class="vtabs lg-tabs">';
				echo '<ul id="lg-tabs">';
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
								echo wp_kses( SmartlogixControlsWrapper::get_control( $section_control['type'], $section_control['label'], 'wp_' . $this->name . '_data_' . $section_control['id'], 'wp_' . $this->name . '_data[' . $section_control['id'] . ']', SmartlogixControlsWrapper::get_value( $args['args']['data'], $section_control['id'], SmartlogixControlsWrapper::get_value( $section_control, 'default' ) ), ( ( isset( $section_control['data'] ) ) ? $section_control['data'] : null ), ( ( isset( $section_control['info'] ) ) ? $section_control['info'] : null ), 'input widefat' . ( ( isset( $section_control['style'] ) ) ? ' ' . $section_control['style'] : '' ) ), SmartlogixControlsWrapper::get_allowed_html() );
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
	}

	/**
	 * The save_post callback action.
	 *
	 * @param integer $post_id The post ID.
	 */
	public function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return; }
		if ( isset( $_POST[ 'wp_' . $this->name . '_nonce' ] ) && ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ 'wp_' . $this->name . '_nonce' ] ) ), plugin_basename( __FILE__ ) ) ) {
			return; }
		if ( isset( $_POST['post_type'] ) && ( $this->name === $_POST['post_type'] ) ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return; }
		} else {
			return; }
		if ( isset( $_POST[ 'wp_' . $this->name . '_data' ] ) ) {
			$sanitized_data = map_deep( wp_unslash( $_POST[ 'wp_' . $this->name . '_data' ] ), 'sanitize_text_field' );

			update_post_meta( $post_id, 'wp_' . $this->name . '_data', $sanitized_data );
		}
		if ( isset( $this->callback_functions ) && is_array( $this->callback_functions ) && isset( $this->callback_functions['save_post'] ) && is_callable( $this->callback_functions['save_post'] ) ) {
			call_user_func( $this->callback_functions['save_post'] );
		}
	}
}

