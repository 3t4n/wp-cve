<?php
namespace LightGallery;

/**
 * Enqueue necessary scripts in the Admin Page.
 */
function lightgallerywp_settings_admin_enqueue_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'lightgallerywp-admin-script', plugins_url( '../assets/js/lightgallery-admin.js', dirname( __FILE__ ) ), [], '1.0', 'all' );
	wp_enqueue_style( 'lightgallerywp-admin-styles', plugins_url( '../assets/css/lightgallery-admin.css', dirname( __FILE__ ) ), [], '1.0', 'all' );
}

/**
 * Register the LightGallery Gutenberg Settings page and create the necessary settings fields.
 */
add_action(
	'plugins_loaded',
	function() {
		$boolean_options = [
			[
				'text'  => 'Yes',
				'value' => 'true',
			],
			[
				'text'  => 'No',
				'value' => 'false',
			],
		];
		new SmartlogixSettingsWrapper(
			[
				'menu_name'          => 'Gutenberg Settings',
				'page_name'          => 'Gutenberg Settings',
				'menu_parent'        => 'edit.php?post_type=lightgalleries',
				'settings_name'      => 'lightgallerywp_default_gallery_settings',
				'callback_functions' => [
					'admin_enqueue_scripts' => 'LightGallery\lightgallerywp_settings_admin_enqueue_scripts',
				],
				'metaboxes'          => [
					'light_gallery_settings' => 'LightGallery Settings',
				],
				'controls'           => apply_filters(
					'lightgallerywp_settings_controls',
					array_merge(
						[
							[
								'metabox' => 'light_gallery_settings',
								'section' => 'Gallery Basics',
								'type'    => 'toggle',
								'label'   => 'Enable Lightgallery for all Images',
								'id'      => 'enable_indivigual_images_ignore',
								'info'    => 'All image links on the site will appear in a Lightgallery',
							],
							[
								'metabox' => 'light_gallery_settings',
								'section' => 'Gallery Basics',
								'type'    => 'toggle',
								'label'   => 'Enable Lightgallery for all Galleries',
								'id'      => 'enable_gutenberg_gallery_ignore',
								'info'    => 'All galleries on the site will have Lightgallery functionality',
							],
						],
						lightgallerywp_get_basic_settings( $boolean_options ),
						lightgallerywp_get_gutenberg_layout_settings( $boolean_options ),
						lightgallerywp_get_pro_upsell_settings( $boolean_options )
					),
					$boolean_options
				),
			]
		);
	}
);

