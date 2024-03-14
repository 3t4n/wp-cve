<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */


add_filter( 'rwmb_meta_boxes', 'tx_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * @return void
 */
function tx_register_meta_boxes( $meta_boxes )
{
	/**
	 * Prefix of meta keys (optional)
	 * Use underscore (_) at the beginning to make keys hidden
	 * Alt.: You also can make prefix empty to disable it
	 */
	// Better has an underscore as last sign
	$prefix = 'tx_';
	
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id' => 'itrans-slider',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => __( 'itrans Slide Meta', 'ispirit' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'itrans-slider' ),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(

			// name
			array(
				'name'  => __( 'Slide Button Text', 'tx' ),
				'id'    => "{$prefix}slide_link_text",
				'type'  => 'text',
				'std'   => __( '', 'tx' ),
				'desc' => __('Enter the slide link button text.', 'tx'),
			),

			// designation
			array(
				'name'  => __( 'Lide Link URL', 'tx' ),
				'id'    => "{$prefix}slide_link_url",
				'type'  => 'text',
				'std'   => __( '', 'tx' ),
				'desc' => __('Enter the slide link url', 'tx'),
			),
					
		)
	);	
	
	
	
	return $meta_boxes;
}



