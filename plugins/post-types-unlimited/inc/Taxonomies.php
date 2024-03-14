<?php

namespace PTU;

\defined( 'ABSPATH' ) || exit;

/**
 * Taxonomies.
 */
class Taxonomies {

	/**
	 * Admin Post Type.
	 *
	 * @var string
	 */
	public const ADMIN_TYPE = 'ptu_tax';

	/**
	 * Have we registered post types yet?
	 *
	 * @var bool
	 */
	protected static $registration_complete = false;

	/**
	 * Array of registered taxonomies.
	 *
	 * @var array
	 */
	protected static $registered_items = [];

	/**
	 * PosStyles Constructor.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Add new submenu item under "Tools" for accessing the ptu_tax post type.
		\add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Register the ptu_tax post type used for the admin interface - @todo can we hook into admin_init instead?
		\add_action( 'init', array( $this, 'admin_type' ) );

		// Add custom metabox with post type settings.
		\add_filter( 'admin_init', array( $this, 'add_meta_box' ) );

		// Register saved custom taxonomies.
		\add_action( 'init', array( $this, 'register_taxonomies' ) );

		// Custom admin columns.
		\add_filter( 'manage_edit-' . self::ADMIN_TYPE . '_columns', array( $this, 'edit_columns' ) );
		\add_action( 'manage_' . self::ADMIN_TYPE . '_posts_custom_column', array( $this, 'column_display' ), 10, 2 );

	}

	/**
	 * Add new submenu item under "Tools" for accessing the ptu_tax post type.
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	public function admin_menu(): void {
		\add_submenu_page(
			'edit.php?post_type=ptu',
			\__( 'Taxonomies', 'post-types-unlimited' ),
			\__( 'Taxonomies', 'post-types-unlimited' ),
			'manage_options',
			'edit.php?post_type=' . self::ADMIN_TYPE
		);
	}

	/**
	 * Register the ptu_tax post type used for the admin interface.
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	public function admin_type(): void {
		\register_post_type( self::ADMIN_TYPE, array(
			'labels' => array(
				'name'               => \__( 'Post Types Unlimited Taxonomies', 'post-types-unlimited' ),
				'singular_name'      => \__( 'Taxonomies', 'post-types-unlimited' ),
				'add_new'            => \__( 'Add New' , 'post-types-unlimited' ),
				'add_new_item'       => \__( 'Add New Taxonomy' , 'post-types-unlimited' ),
				'edit_item'          => \__( 'Edit Taxonomy' , 'post-types-unlimited' ),
				'new_item'           => \__( 'New Taxonomy' , 'post-types-unlimited' ),
				'view_item'          => \__( 'View Taxonomy', 'post-types-unlimited' ),
				'search_items'       => \__( 'Search Taxonomies', 'post-types-unlimited' ),
				'not_found'          => \__( 'No Taxonomies found', 'post-types-unlimited' ),
				'not_found_in_trash' => \__( 'No Taxonomies found in Trash', 'post-types-unlimited' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'_builtin'        => false,
			'capability_type' => 'page',
			'hierarchical'    => false,
			'rewrite'         => false,
			'query_var'       => self::ADMIN_TYPE,
			'supports'        => array(
				'title'
			),
			'show_in_menu'    => false,
		) );
	}

	/**
	 * Add custom metabox with post type settings
	 *
	 * @since  1.2
	 * @access void
	 */
	public function add_meta_box(): void {
		new Metaboxes( array(
			'id'       => self::ADMIN_TYPE . '_metabox',
			'title'    => \__( 'Taxonomy Settings', 'post-types-unlimited' ),
			'screen'   => array( self::ADMIN_TYPE ),
			'context'  => 'normal',
			'priority' => 'high',
			'tabs'     => [ $this, 'get_meta_box_tabs' ],
		) );
	}

	/**
	 * Returns the metabox tabs.
	 *
	 * @since  1.2
	 * @access public
	 * @return $array
	 */
	public function get_meta_box_tabs(): array {
		$tabs[] = array(
			'id'     => 'main',
			'title'  => \__( 'Main', 'post-types-unlimited' ),
			'fields' => array(
				array(
					'name'              => \__( 'Name (required)', 'post-types-unlimited' ),
					'id'                => 'name',
					'type'              => 'text',
					'sanitize_callback' => array( $this, 'sanitize_tax_name' ),
					'required'          => true,
					'maxlength'         => '32',
					'placeholder'       => \__( '(e.g. style)', 'post-types-unlimited' ),
					'desc'              => \__( 'The name of the taxonomy. Name should only contain lowercase letters and the underscore character, and not be more than 32 characters long (database structure restriction).', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Label', 'post-types-unlimited' ),
					'id'          => 'label',
					'type'        => 'text',
				//	'maxlength'   => '20',
					'placeholder' => \__( '(e.g. Styles)', 'post-types-unlimited' ),
					'desc'        => \__( 'A plural descriptive name for the taxonomy marked for translation.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Singular Name', 'post-types-unlimited' ),
					'id'          => 'singular_name',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Style)', 'post-types-unlimited' ),
					'desc'        => \__( 'Name for one object of this taxonomy.', 'post-types-unlimited' ),
				),
				array(
					'name' => \__( 'Description (optional)', 'post-types-unlimited' ),
					'id'   => 'description',
					'type' => 'textarea',
					'desc' => \__( 'Include a description of the taxonomy.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Menu Name', 'post-types-unlimited' ),
					'id'          => 'menu_name',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Styles)', 'post-types-unlimited' ),
					'desc'        => \__( 'The menu name text. This string is the name to give menu items. If not set, defaults to value of name label.', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'Post Type Support', 'post-types-unlimited' ),
					'id'      => 'object_type',
					'type'    => 'multi_select',
					'default' => array( 'post' ),
					'desc'    => \__( '(default: post) Select the post types you want this taxonomy to be supported by. You must select at least one post type. Only public post types are available by default.', 'post-types-unlimited' ),
					'choices' => $this->get_registered_types(),
				),
				array(
					'name'    => \__( 'Public', 'post-types-unlimited' ),
					'id'      => 'public',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => \__( 'Whether a taxonomy is intended for use publicly either via the admin interface or by front-end users.', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'Publicly Queryable', 'post-types-unlimited' ),
					'id'      => 'publicly_queryable',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => \__( '(default: true) Whether the taxonomy is publicly queryable.', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'Show UI', 'post-types-unlimited' ),
					'id'      => 'show_ui',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => \__( '(default: true) Whether to generate a default UI for managing this taxonomy.', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'Show in Menu', 'post-types-unlimited' ),
					'id'      => 'show_in_menu',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => \__( '(default: true) Where to show the taxonomy in the admin menu. Show UI must be true.', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'Show in Nav Menus', 'post-types-unlimited' ),
					'id'      => 'show_in_nav_menus',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => \__( '(default: true) Makes this taxonomy available for selection in navigation menus.', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'Show in REST API', 'post-types-unlimited' ),
					'id'      => 'show_in_rest',
					'type'    => 'checkbox',
					'default' => false,
					'desc'    => \__( '(default: false) Whether to include the taxonomy in the REST API. Enable to show this taxonomy when editing posts via the Gutenberg editor.', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'Show in TagCloud', 'post-types-unlimited' ),
					'id'      => 'show_tagcloud',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => \__( '(default: true) To change the base url of REST API route.', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'Show in Quick Edit', 'post-types-unlimited' ),
					'id'      => 'show_in_quick_edit',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => \__( '(default: true) Whether to show the taxonomy in the quick/bulk edit panel.', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'Show Admin Column', 'post-types-unlimited' ),
					'id'      => 'show_admin_column',
					'type'    => 'checkbox',
					'default' => false,
					'desc'    => \__( '(default: false) Whether to allow automatic creation of taxonomy columns on associated post-types table.', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'Hierarchical', 'post-types-unlimited' ),
					'id'      => 'hierarchical',
					'type'    => 'checkbox',
					'default' => false,
					'desc'    => \__( '(default: false) Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags.', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'Rewrite', 'post-types-unlimited' ),
					'id'      => 'rewrite',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => \__( '(default: true) Set to false to prevent automatic URL rewriting a.k.a. "pretty permalinks".', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Custom Rewrite Slug', 'post-types-unlimited' ),
					'id'          => 'slug',
					'type'        => 'text',
					'placeholder' => \__( '(default: taxonomy name)', 'post-types-unlimited' ),
					'desc'        => \__( 'Used as pretty permalink text (i.e. /tag/).', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'With Front', 'post-types-unlimited' ),
					'id'      => 'with_front',
					'type'    => 'checkbox',
					'default' => true,
					'desc'    => \__( '(default: true) Allowing permalinks to be prepended with front base.', 'post-types-unlimited' ),
				),
				array(
					'name'    => \__( 'Hierarchical URL\'s', 'post-types-unlimited' ),
					'id'      => 'rewrite_hierarchical',
					'type'    => 'checkbox',
					'default' => false,
					'desc'    => \__( '(default: false) Allow hierarchical urls.', 'post-types-unlimited' ),
				),
			),
		);

		$tabs[] = array(
			'id'     => 'labels',
			'title'  => \__( 'Labels', 'post-types-unlimited' ),
			'fields' => array(
				array(
					'name'        => \__( 'All Items', 'post-types-unlimited' ),
					'id'          => 'labels_all_items',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. All Styles)', 'post-types-unlimited' ),
					'desc'        => \__( 'The all items text.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Edit Item', 'post-types-unlimited' ),
					'id'          => 'labels_edit_item',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Edit Style)', 'post-types-unlimited' ),
					'desc'        => \__( 'The edit item text.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'View Item', 'post-types-unlimited' ),
					'id'          => 'labels_view_item',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. View Style)', 'post-types-unlimited' ),
					'desc'        => \__( 'The view item text.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Update Item', 'post-types-unlimited' ),
					'id'          => 'labels_update_item',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Update Style)', 'post-types-unlimited' ),
					'desc'        => \__( 'The update item text.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Add New Item', 'post-types-unlimited' ),
					'id'          => 'labels_add_new_item',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Add New Style)', 'post-types-unlimited' ),
					'desc'        => \__( 'The add new item text.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Add New Item Name', 'post-types-unlimited' ),
					'id'          => 'labels_new_item_name',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. New Style Name)', 'post-types-unlimited' ),
					'desc'        => \__( 'The add new item name text.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Parent Item', 'post-types-unlimited' ),
					'id'          => 'labels_parent_item',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Parent Style)', 'post-types-unlimited' ),
					'desc'        => \__( 'The parent item text. This string is not used on non-hierarchical taxonomies.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Parent Item Colon', 'post-types-unlimited' ),
					'id'          => 'labels_parent_item_colon',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Parent Style:)', 'post-types-unlimited' ),
					'desc'        => \__( 'The same as Parent Item but with a colon in the end.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Search Items', 'post-types-unlimited' ),
					'id'          => 'labels_search_items',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Search Styles)', 'post-types-unlimited' ),
					'desc'        => \__( 'The search items text.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Popular Items', 'post-types-unlimited' ),
					'id'          => 'labels_popular_items',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Popular Styles)', 'post-types-unlimited' ),
					'desc'        => \__( 'The popular items text. This string is not used on hierarchical taxonomies.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Separate Items With Commas', 'post-types-unlimited' ),
					'id'          => 'labels_separate_items_with_commas',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Separate styles with commas)', 'post-types-unlimited' ),
					'desc'        => \__( 'The separate item with commas text used in the taxonomy meta box. This string is not used on hierarchical taxonomies.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Add or Remove Items', 'post-types-unlimited' ),
					'id'          => 'labels_add_or_remove_items',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Add or remove types)', 'post-types-unlimited' ),
					'desc'        => \__( 'The add or remove items text and used in the meta box when JavaScript is disabled. This string is not used on hierarchical taxonomies.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Choose from Most Used', 'post-types-unlimited' ),
					'id'          => 'labels_choose_from_most_used',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Choose from the most used types)', 'post-types-unlimited' ),
					'desc'        => \__( 'The choose from most used text used in the taxonomy meta box. This string is not used on hierarchical taxonomies.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Not Found', 'post-types-unlimited' ),
					'id'          => 'labels_not_found',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. No styles found)', 'post-types-unlimited' ),
					'desc'        => \__( 'The text displayed via clicking \'Choose from the most used {taxonomy}\' in the taxonomy meta box when no {taxonomies} are available and the text used in the terms list table when there are no items for a taxonomy.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Back to Items', 'post-types-unlimited' ),
					'id'          => 'labels_back_to_items',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. Back to styles)', 'post-types-unlimited' ),
					'desc'        => \__( 'The text displayed after a term has been updated for a link back to main index.', 'post-types-unlimited' ),
				),
			)
		);

		$tabs[] = array(
			'id'     => 'advanced',
			'title'  => \__( 'Advanced', 'post-types-unlimited' ),
			'fields' => array(
				array(
					'name'        => \__( 'Custom Meta Box Callback', 'post-types-unlimited' ),
					'id'          => 'meta_box_cb',
					'type'        => 'text',
					'placeholder' => \__( '(e.g. my_custom_taxonomy_meta_box_cb)', 'post-types-unlimited' ),
					'desc'        => \__( '(default: null) Provide a callback function that will be called when setting up the meta boxes for the edit form.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Custom REST API Base', 'post-types-unlimited' ),
					'id'          => 'rest_base',
					'type'        => 'text',
					'placeholder' => \__( '(default: taxonomy name)', 'post-types-unlimited' ),
					'desc'        => \__( 'To change the base url of REST API route.', 'post-types-unlimited' ),
				),
				array(
					'name'        => \__( 'Custom REST Controller Class', 'post-types-unlimited' ),
					'id'          => 'rest_controller_class',
					'type'        => 'text',
					'placeholder' => \__( '(default: WP_REST_Terms_Controller)', 'post-types-unlimited' ),
					'desc'        => \__( 'REST API Controller class name.', 'post-types-unlimited' ),
				),
			)
		);

		/**
		 * Check the deprecated ptu_metaboxes filter.
		 */
		$custom_metaboxes = (array) \apply_filters( 'ptu_metaboxes', [] );

		if ( $custom_metaboxes ) {
			foreach ( $custom_metaboxes as $custom_metabox ) {
				$screen = $custom_metabox['screen'] ?? '';
				if ( self::ADMIN_TYPE === $screen || ( is_array( $screen ) && in_array( self::ADMIN_TYPE, $screen ) ) ) {
					if ( ! empty( $custom_metabox['id'] ) && ! empty( $custom_metabox['fields'] ) ) {
						$tabs[] = array(
							'id'     => $custom_metabox['id'],
							'title'  => $custom_metabox['title'] ?? '',
							'fields' => (array) $custom_metabox['fields'],
						);
					}
				}
			}
		}

		/**
		 * Filters the taxonomy meta box tabs.
		 *
		 * @var array $tabs Array of tabs.
		 */
		$tabs = apply_filters( 'ptu/taxonomies/meta_box_tabs', $tabs );

		return (array) $tabs;
	}

	/**
	 * Sanitize taxonomy name to make sure it's valid.
	 * Should only contain lowercase letters and the underscore character, and not be more than 32 characters long (database structure restriction)
	 *
	 * @since  1.0
	 * @access public
	 * @return $value
	 */
	public function sanitize_tax_name( $field, $value ): string {
		$value = \sanitize_key( $value );
		$value = ( \strlen( $value ) > 32 ) ? \substr( $value, 0, 32 ) : $value; // max 20 characters
		return \sanitize_text_field( $value );
	}

	/**
	 * Return array of registered taxonomies for multi_select.
	 *
	 * @since  1.0
	 * @access public
	 * @return $value
	 */
	public function get_registered_types(): array {
		$choices = array();
		$post_types = \get_post_types( array(
			'public' => true,
		), 'objects', 'and' );
		if ( $post_types ) {
			foreach( $post_types as $post_type ) {
				$choices[ $post_type->name ] = $post_type->label;
			}
		}
		$choices = \apply_filters( '\PTU\Taxonomies\get_registered_types', $choices ); // @todo deprecate.
		return (array) \apply_filters( 'ptu_taxonomies_object_type_choices', $choices );
	}

	/**
	 * Register saved custom taxonomies.
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	public function register_taxonomies(): void {
		$custom_taxes = \get_posts( array(
			'numberposts' 	   => -1,
			'post_type' 	   => self::ADMIN_TYPE,
			'post_status'      => 'publish',
			'suppress_filters' => false,
			'fields'           => 'ids',
		) );

		// If we have custom taxonomies, lets try and register them.
		if ( $custom_taxes ) {

			// Loop through all custom taxonomies and register them.
			foreach( $custom_taxes as $tax_id ) {

				// Get custom post type meta
				$meta = \get_post_meta( $tax_id, '', false );

				// Check custom post type name.
				$name = \array_key_exists( '_ptu_name', $meta ) ? $meta['_ptu_name'][0] : '';

				// Custom post type name is required.
				if ( ! $name ) {
					continue;
				}

				// Get custom labels.
				$label                      = $meta['_ptu_label'][0] ?? $name;
				$singular_name              = $meta['_ptu_singular_name'][0] ?? $label;
				$search_items               = $meta['_ptu_labels_search_items'][0] ?? \sprintf( \_x( 'Search %s', 'taxonomy label', 'post-types-unlimited' ), $label );
				$all_items                  = $meta['_ptu_labels_all_items'][0] ?? \sprintf( \_x( 'All %s', 'taxonomy label', 'post-types-unlimited' ), $label );
				$parent_item                = $meta['_ptu_labels_parent_item'][0] ?? \sprintf( \_x( 'Parent %s', 'taxonomy label', 'post-types-unlimited' ), $singular_name );
				$parent_item_colon          = $meta['_ptu_labels_parent_item_colon'][0] ?? \sprintf( \_x( 'Parent %s:', 'taxonomy label', 'post-types-unlimited' ), $singular_name );
				$view_item                  = $meta['_ptu_labels_parent_view_item'][0] ?? \sprintf( \_x( 'View %s', 'taxonomy label', 'post-types-unlimited' ), $singular_name );
				$edit_item                  = $meta['_ptu_labels_parent_edit_item'][0] ?? \sprintf( \_x( 'Edit %s', 'taxonomy label', 'post-types-unlimited' ), $singular_name );
				$update_item                = $meta['_ptu_labels_update_item'][0] ?? \sprintf( \_x( 'Update %s', 'taxonomy label', 'post-types-unlimited' ), $singular_name );
				$add_new_item               = $meta['_ptu_labels_add_new_item'][0] ?? \sprintf( \_x( 'Add New %s', 'taxonomy label', 'post-types-unlimited' ), $singular_name );
				$add_new_item_name          = $meta['_ptu_labels_add_new_item_name'][0] ?? \sprintf( \_x( 'New %s', 'taxonomy label', 'post-types-unlimited' ), $singular_name );
				$popular_items              = $meta['_ptu_labels_popular_items'][0] ?? \sprintf( \_x( 'Popular %s', 'taxonomy label', 'post-types-unlimited' ), $label );
				$separate_items_with_commas = $meta['_ptu_labels_separate_items_with_commas'][0] ?? \sprintf( \_x( 'Separate %s with commas', 'taxonomy label', 'post-types-unlimited' ), $label );
				$add_or_remove_items        = $meta['_ptu_labels_add_or_remove_items'][0] ?? \sprintf( \_x( 'Add or remove %s', 'taxonomy label', 'post-types-unlimited' ), $label );
				$choose_from_most_used      = $meta['_ptu_labels_choose_from_most_used'][0] ?? \sprintf( \_x( 'Choose from the most used %s', 'taxonomy label', 'post-types-unlimited' ), $label );
				$choose_not_found           = $meta['_ptu_labels_choose_from_most_used'][0] ?? \sprintf( \_x( 'Choose from the most used %s', 'taxonomy label', 'post-types-unlimited' ), $label );
				$not_found                  = $meta['_ptu_labels_not_found'][0] ?? \sprintf( \_x( 'Choose from the most used %s', 'taxonomy label', 'post-types-unlimited' ), $label );
				$back_to_items              = $meta['_ptu_labels_back_to_items'][0] ?? \sprintf( \_x( 'Back to %s', 'taxonomy label', 'post-types-unlimited' ), $label );

				// labels array.
				$labels = array(
					'name'                       => \_x( $label, 'taxonomy general name', 'post-types-unlimited' ),
					'singular_name'              => \_x( $singular_name, 'taxonomy singular name', 'post-types-unlimited' ),
					'all_items'                  => $all_items,
					'edit_item'                  => $edit_item,
					'view_item'                  => $view_item,
					'update_item'                => $update_item,
					'add_new_item'               => $add_new_item,
					'new_item_name'              => $add_new_item_name,
					'parent_item'                => $parent_item,
					'parent_item_colon'          => $parent_item_colon,
					'search_items'               => $search_items,
					'popular_items'              => $popular_items,
					'separate_items_with_commas' => $separate_items_with_commas,
					'add_or_remove_items'        => $add_or_remove_items,
					'choose_from_most_used'      => $choose_from_most_used,
					'not_found'                  => $not_found,
					'back_to_items'              => $back_to_items,
				);

				if ( array_key_exists( '_ptu_menu_name', $meta ) ) {
					$labels['menu_name'] = $meta['_ptu_menu_name'][0];
				}

				// Define taxonomy arguments.
				$args = array(
					'labels'                => $labels,
					'description'           => \sanitize_text_field( $meta['_ptu_description'][0] ?? '' ),
					'public'                => \wp_validate_boolean( $meta['_ptu_public'][0] ?? true ),
					'publicly_queryable'    => \wp_validate_boolean( $meta['_ptu_publicly_queryable'][0] ?? true ),
					'hierarchical'          => \wp_validate_boolean( $meta['_ptu_hierarchical'][0] ?? false ),
					'show_ui'               => \wp_validate_boolean( $meta['_ptu_show_ui'][0] ?? true ),
					'show_in_nav_menus'     => \wp_validate_boolean( $meta['_ptu_show_in_nav_menus'][0] ?? true ),
					'show_in_menu'          => \wp_validate_boolean( $meta['_ptu_show_in_menu'][0] ?? true ),
					'show_admin_column'     => \wp_validate_boolean( $meta['_ptu_show_admin_column'][0] ?? true ),
					'query_var'             => \wp_validate_boolean( $meta['_ptu_query_var'][0] ?? true ),
					'show_in_rest'          => \wp_validate_boolean( $meta['_ptu_show_in_rest'][0] ?? false ),

				);

				$rewrite = \wp_validate_boolean( $meta['_ptu_rewrite'][0] ?? true );

				if ( $rewrite ) {
					$args['rewrite'] = array(
						'slug'         => $meta['_ptu_slug'][0] ?? '',
						'with_front'   => \wp_validate_boolean( $meta['_ptu_with_front'][0] ?? true ),
						'hierarchical' => \wp_validate_boolean( $meta['_ptu_with_front'][0] ?? false ),
					);
				} else {
					$args['rewrite'] = false;
				}

				if ( \array_key_exists( '_ptu_rest_base', $meta ) ) {
					$labels['rest_base'] = $meta['_ptu_rest_base'][0];
				}

				if ( \array_key_exists( '_ptu_rest_controller_class', $meta ) ) {
					$labels['rest_controller_class'] = $meta['_ptu_rest_controller_class'][0];
				}

				$object_type = \get_post_meta( $tax_id, '_ptu_object_type', true );
				$object_type = \is_array( $object_type ) ? $object_type : array( 'post' );

				\register_taxonomy( $name, $object_type, $args );

				self::$registered_items[ $name ] = $tax_id;

			}

			self::$registration_complete = true;

		}

	}

	/**
	 * Helper Function returns taxonomies.
	 *
	 * @since  1.1
	 * @access public
	 * @return array $registered_items
	 */
	public static function get_registered_items(): array {
		return self::$registered_items;
	}

	/**
	 * Register new admin columns for the ptu type.
	 *
	 * @since 1.0.3
	 */
	public function edit_columns( $columns ) {
		unset( $columns['date'] ); // no need for date.
		$columns['slug']               = \__( 'Slug', 'post-types-unlimited' );
		$columns['public']             = \__( 'Public', 'post-types-unlimited' );
		$columns['publicly_queryable'] = \__( 'Publicly Queryable', 'post-types-unlimited' );
		$columns['object_type']        = \__( 'Assigned To', 'post-types-unlimited' );
		return $columns;
	}


	/**
	 * Display new admin columns for the ptu type.
	 *
	 * @since 1.0.3
	 */
	public function column_display( $column, $post_id ): void {
		switch ( $column ) :

			case 'slug':

				$slug = \get_post_meta( $post_id, '_ptu_slug', true );

				if ( empty( $slug ) ) {
					$slug = \get_post_meta( $post_id, '_ptu_name', true );
				}

				if ( ! empty( $slug ) ) {
					echo \esc_html( $slug );
				} else {
					echo '&#8212;';
				}

				break;

			case 'public':

				$public = \get_post_meta( $post_id, '_ptu_public', true );

				if ( ! empty( $public ) ) {
					echo '<span class="screen-reader-text">' . \esc_html__( 'yes', 'post-types-unlimited' ) . '</span>';
					echo '<span class="dashicons dashicons-yes" aria-hidden="true"><span>';
				} else {
					echo '<span class="screen-reader-text">' . \esc_html__( 'no', 'post-types-unlimited' ) . '</span>';
					echo '<span class="dashicons dashicons-no-alt" aria-hidden="true"><span>';
				}

				break;

			case 'publicly_queryable':

				$publicly_queryable = \get_post_meta( $post_id, '_ptu_publicly_queryable', true );

				if ( ! empty( $publicly_queryable ) ) {
					echo '<span class="screen-reader-text">' . \esc_html__( 'yes', 'post-types-unlimited' ) . '</span>';
					echo '<span class="dashicons dashicons-yes" aria-hidden="true"><span>';
				} else {
					echo '<span class="screen-reader-text">' . \esc_html__( 'no', 'post-types-unlimited' ) . '</span>';
					echo '<span class="dashicons dashicons-no-alt" aria-hidden="true"><span>';
				}

				break;

			case 'object_type':

				$object_type = \get_post_meta( $post_id, '_ptu_object_type', true );
				$object_type = \is_array( $object_type ) ? $object_type : array( 'post' );

				if ( $object_type ) {
					foreach( $object_type as $type ) {
						echo '<code style="display:inline-block;margin:0 5px 5px 0;">' . \esc_html( $type ) . '</code>';
					}
				}

				break;

		endswitch;
	}

}

new Taxonomies;
