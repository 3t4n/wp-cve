<?php
/**
 * Adds settings to the permalinks admin settings page
 *
 * @class       Blossom_Recipe_Maker_Permalink_Settings
 * @category    Admin
 * @package     Blossom_Recipe_Maker/admin
 * @version     1.0.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Blossom_Recipe_Maker_Permalink_Settings', false ) ) :

	/**
	 * Blossom_Recipe_Maker_Permalink_Settings Class.
	 */
	class Blossom_Recipe_Maker_Permalink_Settings {

		/**
		 * Permalink settings.
		 *
		 * @var array
		 */
		private $permalinks = array();
		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;

		/**
		 * class constructor.
		 */
		public function __construct() {

			if ( defined( 'BLOSSOM_RECIPE_MAKER_VERSION' ) ) {
				$this->version = BLOSSOM_RECIPE_MAKER_VERSION;
			} else {
				$this->version = '1.0.0';
			}
			$this->plugin_name = 'blossom-recipe-maker';

			add_action( 'admin_init', array( $this, 'settings_init' ) );
			add_action( 'admin_init', array( $this, 'settings_save' ) );

		}

		/**
		 * Init our settings.
		 */
		public function settings_init() {

			$plugin_admin = new Blossom_Recipe_Maker_Admin( $this->plugin_name, $this->version );

			// Add our settings
			add_settings_field(
				'blossom_recipe_maker_slug',            // id
				__( 'Blossom Recipes base', 'blossom-recipe-maker' ),   // setting title
				array( $this, 'recipes_slug_input' ),  // display callback
				'permalink',                        // settings page
				'optional'                          // settings section
			);
			add_settings_field(
				'blossom_recipe_category_slug',            // id
				__( 'Recipe Category base', 'blossom-recipe-maker' ),   // setting title
				array( $this, 'recipe_category_slug_input' ),  // display callback
				'permalink',                        // settings page
				'optional'                          // settings section
			);
			add_settings_field(
				'blossom_recipe_cuisine_slug',            // id
				__( 'Recipe Cuisine base', 'blossom-recipe-maker' ),   // setting title
				array( $this, 'recipe_cuisine_slug_input' ),  // display callback
				'permalink',                        // settings page
				'optional'                          // settings section
			);
			add_settings_field(
				'blossom_recipe_cooking_method_slug',            // id
				__( 'Recipe Cooking Method base', 'blossom-recipe-maker' ),   // setting title
				array( $this, 'recipe_cooking_method_slug_input' ),  // display callback
				'permalink',                        // settings page
				'optional'                          // settings section
			);
			add_settings_field(
				'blossom_recipe_tags_slug',            // id
				__( 'Recipe Tags base', 'blossom-recipe-maker' ),   // setting title
				array( $this, 'recipe_tags_slug_input' ),  // display callback
				'permalink',                        // settings page
				'optional'                          // settings section
			);
			$this->permalinks = $plugin_admin->blossom_recipe_get_permalink_structure();
		}

		/**
		 * Show a slug input box.
		 */
		public function recipes_slug_input() {

			?>
		<input name="blossom_recipe_base" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['blossom_recipe_base'] ); ?>" placeholder="<?php echo esc_attr_x( 'recipes', 'slug', 'blossom-recipe-maker' ); ?>" />
			<?php
		}

		/**
		 * Show a slug input box.
		 */
		public function recipe_category_slug_input() {

			?>
		<input name="blossom_recipe_category_base" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['blossom_recipe_category_base'] ); ?>" placeholder="<?php echo esc_attr_x( 'recipe-category', 'slug', 'blossom-recipe-maker' ); ?>" />
			<?php
		}

		/**
		 * Show a slug input box.
		 */
		public function recipe_cuisine_slug_input() {

			?>
		<input name="blossom_recipe_cuisine_base" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['blossom_recipe_cuisine_base'] ); ?>" placeholder="<?php echo esc_attr_x( 'recipe-cuisine', 'slug', 'blossom-recipe-maker' ); ?>" />
			<?php
		}

		/**
		 * Show a slug input box.
		 */
		public function recipe_cooking_method_slug_input() {

			?>
		<input name="blossom_recipe_cooking_method_base" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['blossom_recipe_cooking_method_base'] ); ?>" placeholder="<?php echo esc_attr_x( 'recipe-cooking-method', 'slug', 'blossom-recipe-maker' ); ?>" />
			<?php
		}

		/**
		 * Show a slug input box.
		 */
		public function recipe_tags_slug_input() {

			?>
		<input name="blossom_recipe_tags_base" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['blossom_recipe_tags_base'] ); ?>" placeholder="<?php echo esc_attr_x( 'recipe-tag', 'slug', 'blossom-recipe-maker' ); ?>" />
			<?php
		}

		/**
		 * Save the settings.
		 */
		public function settings_save() {
			if ( ! is_admin() ) {
				return;
			}

			$submitted_post_data = blossom_recipe_maker_get_submitted_data( 'post' );

			// We need to save the options ourselves; settings api does not trigger save for the permalinks page.
			if ( isset( $submitted_post_data['permalink_structure'] ) ) {

				$permalinks                                       = (array) get_option( 'blossom_recipe_maker_permalinks', array() );
				$permalinks['blossom_recipe_base']                = trim( $submitted_post_data['blossom_recipe_base'] );
				$permalinks['blossom_recipe_category_base']       = trim( $submitted_post_data['blossom_recipe_category_base'] );
				$permalinks['blossom_recipe_cuisine_base']        = trim( $submitted_post_data['blossom_recipe_cuisine_base'] );
				$permalinks['blossom_recipe_cooking_method_base'] = trim( $submitted_post_data['blossom_recipe_cooking_method_base'] );
				$permalinks['blossom_recipe_tags_base']           = trim( $submitted_post_data['blossom_recipe_tags_base'] );

				update_option( 'blossom_recipe_maker_permalinks', $permalinks );
			}
		}
	}

endif;

return new Blossom_Recipe_Maker_Permalink_Settings();
