<?php

$fonts = array(
'Arial', 'Arial Black', 'Tahoma', 'Trebuchet MS', 'Verdana', 'Courier', 'Courier New', 'Georgia', 'Times', 'Times New Roman', 'MS Serif', 'New York', 'Palatino', 'Palatino Linotype', 'Courier', 'Courier New', 'Lucida Console', 'Monaco', 'Roboto'
);

$fonts = array_combine( array_values( $fonts ), array_values( $fonts ) );

$settings = array(


	array(
		'callback' 		=> 'color',
		'section_id' 	=> 'emsy_container',
		'id'			=> 'c-outbgcolor',
		'title' 		=> 'Outer Container BG Color',
		'default' 		=> '#f0f0f0'
	),


	array(
		'callback' 		=> 'color',
		'section_id' 	=> 'emsy_container',
		'id'			=> 'c-inbgcolor',
		'title' 		=> 'Inner Container BG Color',
		'default' 		=> '#ffffff'
	),


	array(
		'callback' 		=> 'color',
		'section_id' 	=> 'emsy_container',
		'id'			=> 'c-txtcolor',
		'title' 		=> 'Text Color',
		'default' 		=> '#000000'
	),


	array(
		'callback' 		=> 'color',
		'section_id' 	=> 'emsy_container',
		'id'			=> 'c-bdcolor',
		'title' 		=> 'Border Color',
		'default' 		=> '#f0f0f0'
	),


	array(
		'callback' 		=> 'select',
		'section_id' 	=> 'emsy_container',
		'id'			=> 'c-font-family',
		'title' 		=> 'Safe Fonts',
		'default' 		=> 'Courier',
		'args'			=> array(
			'options' => $fonts
		),
		'desc' 			=> 'Email clients do not support modern fonts. These are the fonts which are widely supported'
	),


	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'emsy_container',
		'id'			=> 'c-fsize',
		'title' 		=> 'Font Size',
		'default' 		=> '16',
		'desc'			=> 'Size in px. (This will be overriden by child font size if set)'
	),


	array(
		'callback' 		=> 'text',
		'section_id' 	=> 'emsy_container',
		'id'			=> 'c-cont-padding',
		'title' 		=> 'Content Padding',
		'default' 		=> '20px 30px',
		'desc'			=> 'Top-Bottom Left-Right ( Default 20px 30px )'
	),


	array(	
		'callback' 		=> 'color',
		'section_id' 	=> 'emsy_button',
		'id'			=> 'btn-bgcolor',
		'title' 		=> 'Background Color',
		'default' 		=> '#333'
	),

	array(
		'callback' 		=> 'color',
		'section_id' 	=> 'emsy_button',
		'id'			=> 'btn-txtcolor',
		'title' 		=> 'Text Color',
		'default' 		=> '#fff'
	),


	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'emsy_button',
		'id'			=> 'btn-hpadding',
		'title' 		=> 'Padding( Left & right )',
		'default' 		=> '40',
		'desc'			=> 'padding in px'
	),

	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'emsy_button',
		'id'			=> 'btn-vpadding',
		'title' 		=> 'Padding( Top & Bottom )',
		'default' 		=> '10',
		'desc'			=> 'padding in px'
	),


	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'emsy_button',
		'id'			=> 'btn-fsize',
		'title' 		=> 'Font Size',
		'default' 		=> '16',
		'desc'			=> 'Size in px'
	),



	array(
		'callback' 		=> 'text',
		'section_id' 	=> 'emsy_footer',
		'id'			=> 'ftc-padding',
		'title' 		=> 'Padding',
		'default' 		=> '30px 30px',
		'desc'			=> 'Top-Bottom Left-Right ( Default 30px 30px )'
	),


	array(
		'callback' 		=> 'color',
		'section_id' 	=> 'emsy_footer',
		'id'			=> 'ftc-bgcolor',
		'title' 		=> 'Background Color',
		'default' 		=> '#dcdcdc'
	),


	array(
		'callback' 		=> 'color',
		'section_id' 	=> 'emsy_footer',
		'id'			=> 'ftc-txtcolor',
		'title' 		=> 'Text Color',
		'default' 		=> '#777777'
	),


	array(
		'callback' 		=> 'select',
		'section_id' 	=> 'emsy_footer',
		'id'			=> 'ft-text-align',
		'title' 		=> 'Text Align',
		'default' 		=> 'center',
		'args'			=> array(
			'options' => array(
				'center' 	=> 'Center',
				'left' 		=> 'Left',
				'right' 	=> 'Right'
			)
		),
	),


	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'emsy_footer',
		'id'			=> 'ftc-fsize',
		'title' 		=> 'Font Size',
		'default' 		=> '16',
		'desc'			=> 'Size in px.'
	),


	array(
		'callback' 		=> 'color',
		'section_id' 	=> 'emsy_bis',
		'id'			=> 'bis-heading-color',
		'title' 		=> 'Heading Color',
		'default' 		=> '#000000'
	),

	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'emsy_bis',
		'id'			=> 'bis-heading-fsize',
		'title' 		=> 'Heading Font Size',
		'default' 		=> '19',
		'desc'			=> 'Size in px.'
	),


	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'emsy_bis',
		'id'			=> 'bis-pimg-width',
		'title' 		=> 'Product Image Width',
		'default' 		=> '200',
		'desc'			=> 'Size in px.'
	),



	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'emsy_bis',
		'id'			=> 'bis-pimg-height',
		'title' 		=> 'Product Image Height',
		'default' 		=> '0',
		'desc'			=> 'For auto height, leave it 0'
	),


	array(
		'callback' 		=> 'checkbox',
		'section_id' 	=> 'emsy_bis',
		'id' 			=> 'bis-en-buy',
		'title' 		=> 'Enable Buy Now Button',
		'default' 		=> 'yes'
	),


	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'emsy_un',
		'id'			=> 'un-fsize',
		'title' 		=> 'Font Size',
		'default' 		=> '25',
		'desc'			=> 'Size in px.'
	),


	array(
		'callback' 		=> 'number',
		'section_id' 	=> 'emsy_an',
		'id'			=> 'an-fsize',
		'title' 		=> 'Font Size',
		'default' 		=> '25',
		'desc'			=> 'Size in px.'
	),


	


);

return apply_filters( 'xoo_wl_admin_settings', $settings, 'email-style' );

?>