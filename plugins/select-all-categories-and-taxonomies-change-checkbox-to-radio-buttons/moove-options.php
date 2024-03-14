<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
/**
 * Moove_Radioselect_Options File Doc Comment
 *
 * @category Moove_Radioselect_Options
 * @package   moove-radio-select
 * @author    Moove Agency
 */

/**
 * Moove_Radioselect_Options Class Doc Comment
 *
 * @category Class
 * @package  Moove_Radioselect_Options
 * @author   Moove Agency
 */
class Moove_Radioselect_Options {
	/**
	 * Global options
	 *
	 * @var array
	 */
	private $options;
	/**
	 * Construct
	 */
	function __construct() {
		add_action( 'admin_menu', array( &$this, 'moove_radioselect_admin_menu' ) );
		add_action( 'admin_init', array( &$this, 'moove_radioselect_page_init' ) );
		add_action( 'update_option_moove_radioselect', array( &$this, 'moove_radioselect_check_settings' ) );
	}
	/**
	 * Callback function after settings page saved. If there is any changes,
	 * it change the selected post type posts by the settings page value.
	 *
	 * @param  mixt $old_value Old value.
	 * @param  mixt $new_value New value.
	 * @return  void
	 */
	function moove_radioselect_check_settings() {


	}

	/**
	 * Register settings page
	 *
	 * @return void
	 */
	function moove_radioselect_page_init() {
		register_setting(
			'moove_radio_select', // Option group.
			'moove_radioselect' // Option name.
		);
		add_settings_section(
			'post_type_radioselect', // ID.
			'Taxonomy Buttons Settings', // Title.
			array( &$this, 'moove_radioselect_print_section_info' ), // Callback.
			'moove-taxonomy-settings' // Page.
		);
		$post_types = get_post_types( array( 'public' => true ) );
		unset( $post_types['attachment'] );

		foreach ( $post_types as &$post_type ) :
			$taxonomies = get_object_taxonomies( $post_type, 'objects' );
			unset( $taxonomies['post_format'] );
			if ( ! empty( $taxonomies ) ) :
				add_settings_field(
					$post_type,
					ucfirst( str_replace( '_', ' ', preg_replace( '/_cpt$/', '', $post_type ) ) ),
					array( &$this, 'moove_radioselect_setting_callback' ),
					'moove-taxonomy-settings',
					'post_type_radioselect',
					array(
						'post_type' 	=>	$post_type,
						'taxonomies'	=>	json_encode( $taxonomies )
					)
				);
			endif;
		endforeach;
	}

	/**
	 * Moove feed importer page added to settings
	 *
	 * @return  void
	 */
	function moove_radioselect_admin_menu() {
		add_options_page(
			'Set up the taxonomies',
			'Moove taxonomy buttons',
			'manage_options',
			'moove-taxonomy-settings',
			array( &$this, 'moove_radioselect_settings_page' )
		);
	}
	/**
	 * Print settings page secion info
	 *
	 * @return string Message
	 */
	function moove_radioselect_print_section_info() {
		return _e( 'Select the right layout to your taxonomy meta box.' , 'moove' );
	}
	/**
	 * Settings callback function
	 *
	 * @param  array $args Data array to view.
	 * @return void
	 */
	function moove_radioselect_setting_callback( $args ) {
		echo Moove_Radioselect_View::load(
			'moove.admin.settings.post_type',
			array(
				'post_type'     => 	esc_attr( $args['post_type'] ),
				'taxonomies'	=>	$args['taxonomies'],
				'options'       => 	$this->options,
			)
		);
	}
	/**
	 * Settings page registration
	 *
	 * @return void
	 */
	function moove_radioselect_settings_page() {
		$this->options = get_option( 'moove_radioselect' );
		$post_types = get_post_types( array( 'public' => true ) );
		unset( $post_types['attachment'] );
		$data = array();
		if ( count( $post_types ) ) :
			foreach ( $post_types as $cpt ) :
				$taxonomies = get_object_taxonomies( $cpt, 'object' );
				$data[ $cpt ] = array(
					'post_type'		=> $cpt,
					'taxonomies'	=> $taxonomies,
				);
			endforeach;
		endif;
		echo Moove_Radioselect_View::load( 'moove.admin.settings.settings_page', $data );
	}
}
$moove_radioselect_options_provider = new Moove_Radioselect_Options();
