<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       test.com
 * @since      1.0.0
 *
 * @package    Blossom_Recipe_Maker
 * @subpackage Blossom_Recipe_Maker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blossom_Recipe_Maker
 * @subpackage Blossom_Recipe_Maker/admin
 * @author     Blossom <test@test.com>
 */
class Blossom_Recipe_Maker_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		add_image_size( 'recipe-maker-single-size', 1170, 650, true );
		add_image_size( 'recipe-maker-thumbnail-size', 470, 313, true );
		add_image_size( 'recipe_maker_gallery_size', 250, 250, true );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blossom_Recipe_Maker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blossom_Recipe_Maker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blossom-recipe-admin.css', array(), $this->version, 'all' );
		wp_register_style( 'dashicons-blossom-recipe', plugin_dir_url( __FILE__ ) . 'images/blossom-recipe/style.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'dashicons-blossom-recipe' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blossom_Recipe_Maker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blossom_Recipe_Maker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blossom-recipe-admin.js', array( 'jquery' ), $this->version, true );

		wp_enqueue_script( $this->plugin_name . 'tab-navs-handler', plugin_dir_url( __FILE__ ) . 'js/blossom-navs-handler.js', array( 'jquery' ), $this->version, true );

		wp_enqueue_script( $this->plugin_name . 'ingredient-functions-handler', plugin_dir_url( __FILE__ ) . 'js/blossom-ingredient-functions.js', array( 'jquery', 'jquery-ui-sortable' ), $this->version, true );

		wp_localize_script(
			$this->plugin_name . 'ingredient-functions-handler',
			'RecipeIngredients',
			array(
				'delete_warning'         => __( 'Are you sure you want to delete this ingredient?', 'blossom-recipe-maker' ),

				'heading_delete_warning' => __( 'Are you sure you want to delete this Section Heading?', 'blossom-recipe-maker' ),
			)
		);

		wp_enqueue_script( $this->plugin_name . 'instruction-functions-handler', plugin_dir_url( __FILE__ ) . 'js/blossom-instruction-functions.js', array( 'jquery', 'jquery-ui-sortable' ), $this->version, true );

		wp_localize_script(
			$this->plugin_name . 'instruction-functions-handler',
			'RecipeInstructions',
			array(
				'delete_warning'         => __( 'Are you sure you want to delete this instruction?', 'blossom-recipe-maker' ),
				'heading_delete_warning' => __( 'Are you sure you want to delete this Section Heading?', 'blossom-recipe-maker' ),
				'add_image'              => __( 'Add Image', 'blossom-recipe-maker' ),

				'change_image'           => __( 'Change Image', 'blossom-recipe-maker' ),

			)
		);

		wp_enqueue_script( $this->plugin_name . 'recipe-gallery-metabox', plugin_dir_url( __FILE__ ) . 'js/recipe-gallery-metabox.js', array( 'jquery' ), $this->version, true );

		wp_localize_script(
			$this->plugin_name . 'recipe-gallery-metabox',
			'RecipeGallery',
			array(
				'delete_warning' => __( 'Are you sure you want to delete this image?', 'blossom-recipe-maker' ),
				'remove_image'   => __( 'Remove Image', 'blossom-recipe-maker' ),

				'change_image'   => __( 'Change Image', 'blossom-recipe-maker' ),
				'gallery'        => 'recipe-gallery-size',

			)
		);

		wp_enqueue_script( 'all', plugin_dir_url( __FILE__ ) . 'js/fontawesome/all.min.js', array( 'jquery' ), '5.14.0', true );

		wp_enqueue_script( $this->plugin_name . 'comment-ratings', plugin_dir_url( __FILE__ ) . 'js/blossom-admin-comment-rating.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Register post types.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function brm_register_recipe_post_types() {

		$permalink = $this->blossom_recipe_get_permalink_structure();

		$labels = array(
			'name'                  => _x( 'Recipes', 'Post Type General Name', 'blossom-recipe-maker' ),
			'singular_name'         => _x( 'Blossom Recipe', 'Post Type Singular Name', 'blossom-recipe-maker' ),
			'menu_name'             => __( 'Blossom Recipes', 'blossom-recipe-maker' ),
			'name_admin_bar'        => __( 'Blossom Recipe', 'blossom-recipe-maker' ),
			'archives'              => __( 'Blossom Recipe Archives', 'blossom-recipe-maker' ),
			'attributes'            => __( 'Blossom Recipe Attributes', 'blossom-recipe-maker' ),
			'parent_item_colon'     => __( 'Parent Blossom Recipe:', 'blossom-recipe-maker' ),
			'all_items'             => __( 'All Recipes', 'blossom-recipe-maker' ),
			'add_new_item'          => __( 'Add New Recipe', 'blossom-recipe-maker' ),
			'add_new'               => __( 'Add New', 'blossom-recipe-maker' ),
			'new_item'              => __( 'New Blossom Recipe', 'blossom-recipe-maker' ),
			'edit_item'             => __( 'Edit Blossom Recipe', 'blossom-recipe-maker' ),
			'update_item'           => __( 'Update Blossom Recipe', 'blossom-recipe-maker' ),
			'view_item'             => __( 'View Blossom Recipe', 'blossom-recipe-maker' ),
			'view_items'            => __( 'View Blossom Recipes', 'blossom-recipe-maker' ),
			'search_items'          => __( 'Search Blossom Recipe', 'blossom-recipe-maker' ),
			'not_found'             => __( 'Not found', 'blossom-recipe-maker' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'blossom-recipe-maker' ),
			'featured_image'        => __( 'Featured Image', 'blossom-recipe-maker' ),
			'set_featured_image'    => __( 'Set featured image', 'blossom-recipe-maker' ),
			'remove_featured_image' => __( 'Remove featured image', 'blossom-recipe-maker' ),
			'use_featured_image'    => __( 'Use as featured image', 'blossom-recipe-maker' ),
			'insert_into_item'      => __( 'Insert into Blossom Recipe', 'blossom-recipe-maker' ),
			'uploaded_to_this_item' => __( 'Uploaded to Blossom Recipe', 'blossom-recipe-maker' ),
			'items_list'            => __( 'Blossom Recipes list', 'blossom-recipe-maker' ),
			'items_list_navigation' => __( 'Blossom Recipes list navigation', 'blossom-recipe-maker' ),
			'filter_items_list'     => __( 'Filter Blossom Recipes list', 'blossom-recipe-maker' ),
		);
		$args   = array(
			'label'               => __( 'Blossom Recipe', 'blossom-recipe-maker' ),
			'description'         => '',
			'labels'              => $labels,
			'taxonomies'          => array( 'recipe-category', 'recipe-cuisine', 'recipe-cooking-method', 'recipe-tag' ),
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'menu_icon'           => 'dashicons-blossom-receipe',
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'rewrite'             => array(
				'slug'       => $permalink['blossom_recipe_base'],
				'with_front' => true,
			),
		);
		register_post_type( 'blossom-recipe', $args );
		flush_rewrite_rules();

	}

	/**
	 * Register a taxonomy, post_types_categories for the post types.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */

	function brm_create_categories_type_taxonomies() {

		$permalink = $this->blossom_recipe_get_permalink_structure();

		// Add new taxonomy, make it hierarchical
				$labels = array(
					'name'              => _x( 'Recipe Categories', 'taxonomy general name', 'blossom-recipe-maker' ),
					'singular_name'     => _x( 'Recipe Category', 'taxonomy singular name', 'blossom-recipe-maker' ),
					'search_items'      => __( 'Search Recipe Categories', 'blossom-recipe-maker' ),
					'all_items'         => __( 'All Categories', 'blossom-recipe-maker' ),
					'parent_item'       => __( 'Parent Categories', 'blossom-recipe-maker' ),
					'parent_item_colon' => __( 'Parent Categories:', 'blossom-recipe-maker' ),
					'edit_item'         => __( 'Edit Categories', 'blossom-recipe-maker' ),
					'update_item'       => __( 'Update Categories', 'blossom-recipe-maker' ),
					'add_new_item'      => __( 'Add New Category', 'blossom-recipe-maker' ),
					'new_item_name'     => __( 'New Category Name', 'blossom-recipe-maker' ),
					'menu_name'         => __( 'Categories', 'blossom-recipe-maker' ),
				);

				$args = array(
					'hierarchical'      => true,
					'labels'            => $labels,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'rewrite'           => array(
						'slug'         => $permalink['blossom_recipe_category_base'],
						'hierarchical' => true,
					),
				);
				register_taxonomy( 'recipe-category', array( 'blossom-recipe' ), $args );
	}

	/**
	 * Register a taxonomy, post_types_cuisine for the post types.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */

	function brm_create_cuisine_type_taxonomies() {

		$permalink = $this->blossom_recipe_get_permalink_structure();

		// Add new taxonomy, make it hierarchical
				$labels = array(
					'name'              => _x( 'Recipe Cuisines', 'taxonomy general name', 'blossom-recipe-maker' ),
					'singular_name'     => _x( 'Recipe Cuisine', 'taxonomy singular name', 'blossom-recipe-maker' ),
					'search_items'      => __( 'Search Recipe Cuisines', 'blossom-recipe-maker' ),
					'all_items'         => __( 'All Cuisines', 'blossom-recipe-maker' ),
					'parent_item'       => __( 'Parent Cuisines', 'blossom-recipe-maker' ),
					'parent_item_colon' => __( 'Parent Cuisines:', 'blossom-recipe-maker' ),
					'edit_item'         => __( 'Edit Cuisines', 'blossom-recipe-maker' ),
					'update_item'       => __( 'Update Cuisines', 'blossom-recipe-maker' ),
					'add_new_item'      => __( 'Add New Cuisine', 'blossom-recipe-maker' ),
					'new_item_name'     => __( 'New Cuisine Name', 'blossom-recipe-maker' ),
					'menu_name'         => __( 'Cuisines', 'blossom-recipe-maker' ),
				);

				$args = array(
					'hierarchical'      => true,
					'labels'            => $labels,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'rewrite'           => array(
						'slug'         => $permalink['blossom_recipe_cuisine_base'],
						'hierarchical' => true,
					),
				);
				register_taxonomy( 'recipe-cuisine', array( 'blossom-recipe' ), $args );
	}

	/**
	 * Register a taxonomy, post_types_cooking_method for the post types.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */

	function brm_create_cooking_method_type_taxonomies() {

		$permalink = $this->blossom_recipe_get_permalink_structure();

		// Add new taxonomy, make it hierarchical
				$labels = array(
					'name'              => _x( 'Recipe Cooking Methods', 'taxonomy general name', 'blossom-recipe-maker' ),
					'singular_name'     => _x( 'Recipe Cooking Method', 'taxonomy singular name', 'blossom-recipe-maker' ),
					'search_items'      => __( 'Search Recipe Cooking Methods', 'blossom-recipe-maker' ),
					'all_items'         => __( 'All Cooking Methods', 'blossom-recipe-maker' ),
					'parent_item'       => __( 'Parent Cooking Methods', 'blossom-recipe-maker' ),
					'parent_item_colon' => __( 'Parent Cooking Methods:', 'blossom-recipe-maker' ),
					'edit_item'         => __( 'Edit Cooking Methods', 'blossom-recipe-maker' ),
					'update_item'       => __( 'Update Cooking Methods', 'blossom-recipe-maker' ),
					'add_new_item'      => __( 'Add New Cooking Method', 'blossom-recipe-maker' ),
					'new_item_name'     => __( 'New Cooking Method Name', 'blossom-recipe-maker' ),
					'menu_name'         => __( 'Cooking Methods', 'blossom-recipe-maker' ),
				);

				$args = array(
					'hierarchical'      => true,
					'labels'            => $labels,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'rewrite'           => array(
						'slug'         => $permalink['blossom_recipe_cooking_method_base'],
						'hierarchical' => true,
					),
				);
				register_taxonomy( 'recipe-cooking-method', array( 'blossom-recipe' ), $args );
	}

	/**
	 * Register a taxonomy, post_types_tags for the post types.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */

	function brm_create_recipe_tags_taxonomies() {

		$permalink = $this->blossom_recipe_get_permalink_structure();

		// Add new taxonomy, make it hierarchical
				$labels = array(
					'name'              => _x( 'Recipe Tags', 'taxonomy general name', 'blossom-recipe-maker' ),
					'singular_name'     => _x( 'Recipe Tag', 'taxonomy singular name', 'blossom-recipe-maker' ),
					'search_items'      => __( 'Search Recipe Tags', 'blossom-recipe-maker' ),
					'all_items'         => __( 'All Tags', 'blossom-recipe-maker' ),
					'parent_item'       => null,
					'parent_item_colon' => null,
					'edit_item'         => __( 'Edit Tags', 'blossom-recipe-maker' ),
					'update_item'       => __( 'Update Tags', 'blossom-recipe-maker' ),
					'add_new_item'      => __( 'Add New Tag', 'blossom-recipe-maker' ),
					'new_item_name'     => __( 'New Tag Name', 'blossom-recipe-maker' ),
					'menu_name'         => __( 'Tags', 'blossom-recipe-maker' ),
				);

				$args = array(
					'hierarchical'      => false,
					'labels'            => $labels,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'rewrite'           => array(
						'slug'         => $permalink['blossom_recipe_tags_base'],
						'hierarchical' => false,
					),
				);
				register_taxonomy( 'recipe-tag', array( 'blossom-recipe' ), $args );
	}

	// ADD SHORTCODE COLUMN
	function set_blossom_recipe_columns( $columns ) {

		$columns['shortcode'] = __( 'Recipe Shortcode', 'blossom-recipe-maker' );

		return $columns;
	}

	// Set Content in Shortcode Column
	function set_blossom_recipe_columns_content( $column, $post_id ) {

		if ( $column == 'shortcode' ) {

			if ( get_post_status( $post_id ) == 'publish' ) {

				$recipe_shortcode = '[recipe-maker id=' . "'" . $post_id . "'" . ']';

				?>
				<input type="text" name="shortcode" value="<?php echo esc_html( $recipe_shortcode ); ?>" readonly onClick="this.setSelectionRange(0, this.value.length)"/>
				<?php

			} else {
				echo esc_html( '—' );
			}
		}

	}

	/**
	 * Registers settings page for Blossom Recipe.
	 *
	 * @since 1.0.0
	 */
	public function brm_register_settings_page() {

		add_submenu_page( 'edit.php?post_type=blossom-recipe', __( 'Blossom Recipe Maker Admin Settings', 'blossom-recipe-maker' ), __( 'Settings', 'blossom-recipe-maker' ), 'manage_options', basename( __FILE__ ), array( $this, 'brm_settings_page_callback_function' ) );
	}

	/**
	 * Registers settings for Blossom Recipe.
	 *
	 * @since 1.0.0
	 */
	public function brm_register_settings() {
		// The third parameter is a function that will validate input values.
		register_setting( 'br_recipe_settings', 'br_recipe_settings', '' );
	}

	/**
	 *
	 * Retrives saved settings from the database if settings are saved. Else, displays fresh forms    for settings.
	 *
	 * @since 1.0.0
	 */
	function brm_settings_page_callback_function() {

		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossom-recipe-settings.php';
		$Blossom_Recipe_Settings = new Blossom_Recipe_Maker_Settings();
		$Blossom_Recipe_Settings->br_recipe_backend_settings();
		$option = get_option( 'br_recipe_settings' );

	}

	/**
	 * Recipe Listing templates.
	 */
	function brm_recipe_listing_template( $template ) {
		$post          = get_post();
		$page_template = get_post_meta( $post->ID, '_wp_page_template', true );

		if ( $page_template == 'templates/template-recipe-category.php' ) {
			if ( $theme_file = locate_template( 'templates/template-recipe-category.php' ) ) {
				return $theme_file;
			} else {
				return BLOSSOM_RECIPE_MAKER_BASE_PATH . '/includes/templates/template-recipe-category.php';
			}
		}
		if ( $page_template == 'templates/template-recipe-cuisine.php' ) {
			if ( $theme_file = locate_template( 'templates/template-recipe-cuisine.php' ) ) {
				return $theme_file;
			} else {
				return BLOSSOM_RECIPE_MAKER_BASE_PATH . '/includes/templates/template-recipe-cuisine.php';
			}
		}
		if ( $page_template == 'templates/template-recipe-cooking-method.php' ) {
			if ( $theme_file = locate_template( 'templates/template-recipe-cooking-method.php' ) ) {
				return $theme_file;
			} else {
				return BLOSSOM_RECIPE_MAKER_BASE_PATH . '/includes/templates/template-recipe-cooking-method.php';
			}
		}

		return $template;
	}

	function brm_recipe_admin_page_templates( $templates ) {
		$templates['templates/template-recipe-category.php']       = __( 'Category Template', 'blossom-recipe-maker' );
		$templates['templates/template-recipe-cuisine.php']        = __( 'Cuisine Template', 'blossom-recipe-maker' );
		$templates['templates/template-recipe-cooking-method.php'] = __( 'Cooking Method Template', 'blossom-recipe-maker' );

		return $templates;

	}

	/**
	 * Add column Thumbnail.
	 *
	 * @since    1.0.0
	 */
	function blossom_recipe_taxonomy_columns( $columns ) {
		$new_columns = array(
			'thumb_id' => __( 'Thumbnail', 'blossom-recipe-maker' ),
		);
		return array_merge( $columns, $new_columns );
	}

	/**
	 * Show thumbnail.
	 *
	 * @since    1.0.0
	 */
	function blossom_recipe_taxonomy_columns_content( $column, $term_id, $tid ) {
		$image_id = get_term_meta( $tid, 'taxonomy-thumbnail-id', true );
		if ( $image_id ) {
			$img_size = apply_filters( 'brm_tax_col_img_size', 'thumbnail' );
			echo wp_kses_post( wp_get_attachment_image( $image_id, $img_size ) );
		} else {
			echo '—';
		}
	}

	function filter_recipes_by_taxonomies( $post_type, $which ) {

		$submitted_get_data = blossom_recipe_maker_get_submitted_data( 'get' );

		// Apply this only on a specific post type
		if ( 'blossom-recipe' !== $post_type ) {
			return;
		}

		// A list of taxonomy slugs to filter by
		$taxonomies = array( 'recipe-category', 'recipe-cuisine', 'recipe-cooking-method' );

		foreach ( $taxonomies as $taxonomy_slug ) {

			// Retrieve taxonomy data
			$taxonomy_obj  = get_taxonomy( $taxonomy_slug );
			$taxonomy_name = $taxonomy_obj->labels->name;

			// Retrieve taxonomy terms
			$terms = get_terms( $taxonomy_slug );

			// Display filter HTML
			echo '<select name="' . esc_attr( $taxonomy_slug ) . '" id="' . esc_attr( $taxonomy_slug ) . '" class="postform">';
			echo '<option value="">' . sprintf( esc_html__( 'All %s', 'blossom-recipe-maker' ), esc_html( $taxonomy_name ) ) . '</option>';
			foreach ( $terms as $term ) {
				printf(
					'<option value="%1$s" %2$s>%3$s </option>',
					esc_attr( $term->slug ),
					( ( isset( $submitted_get_data[$taxonomy_slug] ) && ( $submitted_get_data[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
					esc_html( $term->name )
				);
			}
			echo '</select>';

		}

	}

	function brm_recipe_tax_terms() {

		$submitted_post_data = blossom_recipe_maker_get_submitted_data( 'post' );

		$taxonomy = $submitted_post_data['taxonomy'];
		$ran      = $submitted_post_data['random'];
		ob_start();

		?>
			<label for="brm-categories-select-<?php echo esc_attr( $ran ); ?>"><?php esc_html_e( 'Choose Terms: (Press Ctrl to select Multiple Terms)', 'blossom-recipe-maker' ); ?></label>

			<?php
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
				)
			);

			if ( empty( $terms ) ) {
				$taxonomy = preg_replace( '#[-]+#', ' ', $taxonomy );
				$taxonomy = ucwords( $taxonomy );
				?>
				<span class="brm-terms-error-note">
					<?php
					$bold      = '<b>';
					$boldclose = '</b>';
					printf( wp_kses_post( __( 'No Terms available. To set terms for %1$s, go to %2$sBlossom Recipes > %3$s%4$s and %5$sAdd the %6$s%7$s.', 'blossom-recipe-maker' ) ), esc_html( $taxonomy ), wp_kses_post( $bold ), esc_html( $taxonomy ), wp_kses_post( $boldclose ), wp_kses_post( $bold ), esc_html( $taxonomy ), wp_kses_post( $boldclose ) );
					?>
					</span>

				<?php
			} else {
				?>
				<select name="" class="brm-cat-select brm-categories-select-<?php echo esc_attr( $ran ); ?>" id="brm-categories-select-<?php echo esc_attr( $ran ); ?>" multiple style="width:350px;" tabindex="4">
					<?php
					$categories = get_categories( 'taxonomy=' . $taxonomy );

					foreach ( $categories as $category ) {
						printf(
							'<option value="%1$s">%2$s</option>',
							esc_html( $category->term_id ),
							esc_html( $category->name )
						);
					}
					?>
				</select>
				<?php
			}

			$output = ob_get_clean();
			echo wp_kses_post( $output );
			exit;

	}

	function brm_recipe_slider_tax_terms() {

		$submitted_post_data = blossom_recipe_maker_get_submitted_data( 'post' );

		$taxonomy = $submitted_post_data['taxonomy'];
		$ran      = $submitted_post_data['random'];
		ob_start();

		?>
			<label for="brm-categories-term-select-<?php echo esc_attr( $ran ); ?>"><?php esc_html_e( 'Choose Taxonomy Term:', 'blossom-recipe-maker' ); ?></label>

			<?php
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
				)
			);

			if ( empty( $terms ) ) {
				$taxonomy = preg_replace( '#[-]+#', ' ', $taxonomy );
				$taxonomy = ucwords( $taxonomy );
				?>
					<span class="brm-terms-error-note">
					<?php
					printf( wp_kses_post( __( 'No Terms available. To set terms for %1$s, go to %2$sBlossom Recipes > %3$s%4$s and %5$sAdd the %6$s%7$s.', 'blossom-recipe-maker' ) ), esc_html( $taxonomy ), '<b>', esc_html( $taxonomy ), '</b>', '<b>', esc_html( $taxonomy ), '</b>' );
					?>
					</span>

					<?php
			} else {
				?>
					<select name="" class="brm-terms-select brm-categories-term-select-<?php echo esc_attr( $ran ); ?>" id="brm-categories-term-select-<?php echo esc_attr( $ran ); ?>" style="width:100%;">
					<?php
					$categories = get_categories( 'taxonomy=' . $taxonomy );

					foreach ( $categories as $category ) {
						printf(
							'<option value="%1$s">%2$s</option>',
							esc_html( $category->term_id ),
							esc_html( $category->name )
						);
					}
					?>
					</select>
				<?php
			}

			$output = ob_get_clean();
			echo wp_kses_post( $output );
			exit;

	}

	/**
	 * Get permalink settings for Blossom Recipe Maker.
	 *
	 * @since  2.2.4
	 * @return array
	 */
	function blossom_recipe_get_permalink_structure() {

		$permalinks = wp_parse_args(
			(array) get_option( 'blossom_recipe_maker_permalinks', array() ),
			array(
				'blossom_recipe_base'                => '',
				'blossom_recipe_category_base'       => '',
				'blossom_recipe_cuisine_base'        => '',
				'blossom_recipe_cooking_method_base' => '',
				'blossom_recipe_tags_base'           => '',
			)
		);

		$permalinks['blossom_recipe_base']                = untrailingslashit( empty( $permalinks['blossom_recipe_base'] ) ? 'recipes' : $permalinks['blossom_recipe_base'] );
		$permalinks['blossom_recipe_category_base']       = untrailingslashit( empty( $permalinks['blossom_recipe_category_base'] ) ? 'recipe-category' : $permalinks['blossom_recipe_category_base'] );
		$permalinks['blossom_recipe_cuisine_base']        = untrailingslashit( empty( $permalinks['blossom_recipe_cuisine_base'] ) ? 'recipe-cuisine' : $permalinks['blossom_recipe_cuisine_base'] );
		$permalinks['blossom_recipe_cooking_method_base'] = untrailingslashit( empty( $permalinks['blossom_recipe_cooking_method_base'] ) ? 'recipe-cooking-method' : $permalinks['blossom_recipe_cooking_method_base'] );
		$permalinks['blossom_recipe_tags_base']           = untrailingslashit( empty( $permalinks['blossom_recipe_tags_base'] ) ? 'recipe-tag' : $permalinks['blossom_recipe_tags_base'] );

		return $permalinks;
	}

}
