<?php


$settings = array(

	/** MAIN **/
	array(
		'callback' 		=> 'links',
		'title' 		=> 'Manage',
		'id' 			=> 'fake',
		'section_id' 	=> 'gl_main',
		'args' 			=> array(
			'options' 	=> array(
				admin_url('admin.php?page=xoo-wl-fields') 			=> 'Fields',
				admin_url('admin.php?page=xoo-wl-view-waitlist') 	=> 'Waiting List',
				admin_url('admin.php?page=xoo-wl-email-history') 	=> 'Email Log',
			)
		)
	),


	array(
		'callback' 		=> 'select',
		'title' 		=> 'Waitlist Form Type',
		'id' 			=> 'm-form-type',
		'section_id' 	=> 'gl_main',
		'args'			=> array(
			'options' => array(
				'popup' 		=> 'Popup',
				'inline'  		=> 'Inline',
				'inline_toggle' => 'Inline Toggle'
			)
		),
		'default' 		=> 'popup'
	),

	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Enable Guest',
		'id' 			=> 'm-en-guest',
		'section_id' 	=> 'gl_main',
		'default' 		=> 'yes'
	),


	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Show on Archive/Shop',
		'id' 			=> 'm-en-shop',
		'section_id' 	=> 'gl_main',
		'default' 		=> 'yes'
	),

	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Show on Backorders',
		'id' 			=> 'm-en-bod',
		'section_id' 	=> 'gl_main',
		'default' 		=> 'no'
	),



	array(
		'callback' 		=> 'text',
		'section_id' 	=> 'gl_texts',
		'id'			=> 'txt-btn',
		'title' 		=> 'Button Text',
		'default' 		=> __( 'Email me when available', 'waitlist-woocommerce' ),
	),


	array(
		'callback' 		=> 'text',
		'section_id' 	=> 'gl_texts',
		'id'			=> 'txt-head',
		'title' 		=> 'Form Heading',
		'default' 		=> __( 'Join Waitlist', 'waitlist-woocommerce' ),
	),


	array(
		'callback' 		=> 'textarea',
		'section_id' 	=> 'gl_texts',
		'id'			=> 'txt-subhead',
		'title' 		=> 'Form Sub-Head',
		'default' 		=> __( 'We will inform you when the product arrives in stock. Please leave your valid email address below.', 'waitlist-woocommerce' ),
	),


	array(
		'callback' 		=> 'textarea',
		'section_id' 	=> 'gl_texts',
		'id'			=> 'txt-success-notice',
		'title' 		=> 'Success Notice',
		'default' 		=> __( 'You are now in waitlist. We will inform you as soon as we are back in stock.', 'waitlist-woocommerce' ),
	)


);

return apply_filters( 'xoo_wl_admin_settings', $settings, 'general' );

?>