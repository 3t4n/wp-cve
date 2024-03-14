<?php


$settings = array(

	array(
		'callback' 		=> 'select',
		'section_id' 	=> 'sy_popup',
		'id'			=> 'popup-pos',
		'title' 		=> 'Position',
		'default' 		=> 'middle',
		'args'			=> array(
			'options' => array(
				'top'  => 'Top',
				'middle' => 'Middle',
			)	
		)
	),


	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'sy_popup',
		'id'			=> 'popup-width',
		'title' 		=> 'Width',
		'default' 		=> '800',
		'desc'			=> 'Size in px'
	),


	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'sy_popup',
		'id'			=> 'popup-height',
		'title' 		=> 'Height',
		'default' 		=> '600',
		'desc'			=> 'Size in px'
	),



	array(
		'callback' 		=> 'upload',
		'section_id' 	=> 'sy_popup',
		'id'			=> 'popup-sidebar-img',
		'title' 		=> 'Sidebar Image',
		'default' 		=> XOO_WL_URL.'/assets/images/popup-sidebar.jpg',
		'desc'			=> 'Supported format: JPEG,PNG',
		'args'			=> array(
			'upload_type' => 'image'
		)
	),

	array(
		'callback' 		=> 'select',
		'section_id' 	=> 'sy_popup',
		'id'			=> 'popup-sidebar-pos',
		'title' 		=> 'Sidebar Position',
		'default' 		=> 'left',
		'args'			=> array(
			'options' => array('left','right')	
		)
	),

	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'sy_popup',
		'id'			=> 'popup-sidebar-width',
		'title' 		=> 'Sidebar Width',
		'default' 		=> '40',
		'desc'			=> 'Width in percentage'
	),


	/** Button **/
	array(	
		'callback' 		=> 'color',
		'section_id' 	=> 'sy_button',
		'id'			=> 'btn-bgcolor',
		'title' 		=> 'Background Color',
		'default' 		=> '#333'
	),

	array(
		'callback' 		=> 'color',
		'section_id' 	=> 'sy_button',
		'id'			=> 'btn-txtcolor',
		'title' 		=> 'Text Color',
		'default' 		=> '#fff'
	),


	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'sy_button',
		'id'			=> 'btn-form-width',
		'title' 		=> 'Submit Button Width',
		'default' 		=> '300',
		'desc'			=> 'Width in px'
	),


	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'sy_button',
		'id'			=> 'btn-open-width',
		'title' 		=> 'Open Button Width',
		'default' 		=> '300',
		'desc'			=> 'Width in px'
	),


	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'sy_button',
		'id'			=> 'btn-padding',
		'title' 		=> 'Padding',
		'default' 		=> '20',
		'desc'			=> 'Padding in px'
	),

);

return apply_filters( 'xoo_wl_admin_settings', $settings, 'style' );

?>