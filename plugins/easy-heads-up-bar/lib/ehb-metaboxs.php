<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category Easy Heads Up Bar
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'ehb_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function ehb_metaboxes( array $meta_boxes ) {
	global $ehb_meta_prefix;
	// Start with an underscore to hide fields from custom fields list
	$prefix = $ehb_meta_prefix;

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['ehb_metabox'] = array(
		'id'         => 'heads_up_bar_metabox',
		'title'      => __( 'Bar Options', 'ehb_lang' ),
		'pages'      => array( 'heads_up_bar', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __( 'Start Date', 'ehb_lang' ),
				'desc' => __( 'The date the bar will start displaying (optional)', 'ehb_lang' ),
				'id'   => $prefix . 'start_date',
				'type' => 'text_date',
			),
			array(
				'name' => __( 'End Date', 'ehb_lang' ),
				'desc' => __( 'The date the bar will stop displaying (optional)', 'ehb_lang' ),
				'id'   => $prefix . 'end_date',
				'type' => 'text_date',
			),

			array(
				'name' => __( 'Bar Content', 'ehb_lang' ),
				'id'   => $prefix . 'bar_content',
				'type' => 'wysiwyg', // 'textarea_small' //
				//'options' => array( 'textarea_rows' => 5, ),
			),
			array(
				'name' => __( 'Heads Up Bar Options', 'ehb_lang' ),
				'desc' => __( 'Choose the look of your bar', 'ehb_lang' ),
				'id'   => $prefix . 'bar_styles',
				'type' => 'title',
			),
			array(
				'name'    => __( 'Background', 'ehb_lang' ),
				'desc'    => __( 'This is the background color of your bar', 'ehb_lang' ),
				'id'      => $prefix . 'bar_bg_color',
				'type'    => 'colorpicker',
				'default' => '#000000'
			),
			array(
				'name'    => __( 'Border Color', 'ehb_lang' ),
				'desc'    => __( 'If you don\'t want a visible border set its color to be same as the background color of your bar', 'ehb_lang' ),
				'id'      => $prefix . 'bar_border_color',
				'type'    => 'colorpicker',
				'default' => '#000000'
			),
			array(
				'name'    => __( 'Text', 'ehb_lang' ),
				'desc'    => __( 'Choose a color that contrasts well against the background', 'ehb_lang' ),
				'id'      => $prefix . 'text_color',
				'type'    => 'colorpicker',
				'default' => '#ffffff'
			),
			array(
				'name'    => __( 'Link', 'ehb_lang' ),
				'desc'    => __( 'Choose a color, for your text that contrasts well against the background', 'ehb_lang' ),
				'id'      => $prefix . 'link_color',
				'type'    => 'colorpicker',
				'default' => '#ffffff'
			),
			array(
				'name'    => __( 'Show bar on', 'ehb_lang' ),
				// 'desc'    => __( 'field description (optional)', 'ehb_lang' ),
				'id'      => $prefix . 'show_where',
				'type'    => 'radio_inline',
				'options' => array(
					'all' => __( 'All Pages', 'ehb_lang' ),
					'home'   => __( 'Just the Home Page', 'ehb_lang' ),
					'interior'     => __( 'Only Interior Pages', 'ehb_lang' ),
				),
				'default' => 'all',
			),
			array(
			    'name'    => __( 'Bar Content Width', 'ehb_lang' ),
			    'desc'    => __( 'This sets the width of the easy sign up bar\'s content.<br/>Very helpful for narrow websites', 'ehb_lang' ),
			    'id'      => $prefix . 'bar_content_width',
			    'type'    => 'select',
			    'options' => array(
			        '100' => __( '100 %', 'ehb_lang' ),
			        '90'  => __( '90 %', 'ehb_lang' ),
			        '80' 	=> __( '80 %', 'ehb_lang' ),
			        '70'	=> __( '70 %', 'ehb_lang' ),
			        '60' 	=> __( '60 %', 'ehb_lang' ),
			        '50' 	=> __( '50 %', 'ehb_lang' )
			    ),
			    'default' => '100',
			),
			array(
				'name'    => __( 'Bar Location', 'ehb_lang' ),
				// 'desc'    => __( 'field description (optional)', 'ehb_lang' ),
				'id'      => $prefix . 'bar_location',
				'type'    => 'radio_inline',
				'options' => array(
					'top' 		=> __( 'Top of the page', 'ehb_lang' ),
					'bottom'  => __( 'Bottom of the page', 'ehb_lang' ),
				),
				'default' => 'top',
			),
			array(
				'name'    => __( 'Bar Display', 'ehb_lang' ),
				'desc'    => __( 'It is not a good idea to choose <em>fixed</em> if you disable the <em>Hide Bar</em> option.', 'ehb_lang' ),
				'id'      => $prefix . 'bar_position',
				'type'    => 'radio_inline',
				'options' => array(
					'fixed' 		=> __( 'Fixed', 'ehb_lang' ),
					'relative'  => __( 'Default', 'ehb_lang' ),
				),
				'default' => 'relative',
			),
			array(
				'name'    => __( 'Hide Bar', 'ehb_lang' ),
				'desc'    => __( 'Allow the user to hide the bar, this will set a cookie that will ensure the bar stays hidden. <br> If your user wants to open the bar they can click on an unobtrusive tab.', 'ehb_lang' ),
				'id'      => $prefix . 'hide_bar',
				'type'    => 'radio_inline',
				'options' => array(
					'yes' 		=> __( 'Yes', 'ehb_lang' ),
					'no'  => __( 'No' , 'ehb_lang' ),
				),
				'default' => 'yes',
			),
		),
	);



	// Add other metaboxes as needed

	return $meta_boxes;
}

add_action( 'init', 'ehb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function ehb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'cmb/init.php';

}
