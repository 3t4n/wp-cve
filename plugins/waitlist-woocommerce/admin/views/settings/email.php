<?php


$option_name = 'xoo-wl-email-options';

$email_content = 'You requested to be notified when [product_link] was back in stock and available for order.We are extremely pleased to announce that the product is now available for purchase. Please act fast, as the item may only be available in limited quantities.';

$footer_content = 'Company Name: '.get_option( 'blogname' ).'[new_line]Address: '.get_option( 'woocommerce_store_address' );


$settings = array(

	array(
		'callback' 		=> 'text',
		'section_id' 	=> 'em_sender',
		'id'			=> 's-email',
		'title' 		=> '"From" Email',
		'default' 		=> esc_attr( get_option( 'admin_email' ) ),
		'desc' 			=> __( 'How the sender email appears in outgoing emails.', 'waitlist-woocommerce' )
	),


	array(
		'callback' 		=> 'text',
		'section_id' 	=> 'em_sender',
		'id'			=> 's-name',
		'title' 		=> '"From" Name',
		'default' 		=> esc_attr( get_option( 'blogname' ) ),
		'desc' 			=> __( 'How the sender name appears in outgoing emails.', 'waitlist-woocommerce' )
	),


	array(
		'callback' 		=> 'upload',
		'section_id' 	=> 'em_general',
		'id'			=> 'gl-logo',
		'title' 		=> 'Header Logo',
	),



	array(
		'callback' 		=> 'textarea',
		'section_id' 	=> 'em_general',
		'id'			=> 'gl-ft-content',
		'title' 		=> 'Footer Content',
		'default' 		=> $footer_content,
		'desc' 			=> '<a href="#xoo-wl-placeholder-nfo">List of Placeholders</a>',
		'args' 			=> array(
			'rows' 	=> 8,
			'cols' 	=> 70
		),
		'pro' 			=> 'yes'
	),


	array(
		'callback' 		=> 'checkbox',
		'section_id' 	=> 'em_bis',
		'id' 			=> 'bis-show-pimg',
		'title' 		=> 'Show Product Image',
		'default' 		=> 'yes',
	),

	array(
		'callback' 		=> 'checkbox',
		'section_id' 	=> 'em_bis',
		'id' 			=> 'bis-send-once',
		'title' 		=> 'Send one email only',
		'default' 		=> 'no',
		'desc' 			=> "This will check if an email has been already sent to a user. If you mistakenly clicks twice on send button, it won't send another email. Useful in case if you have auto send email enabled.",
		'pro' 			=> 'yes'
	),



	array(
		'callback' 		=> 'checkbox',
		'section_id' 	=> 'em_bis',
		'id' 			=> 'bis-check-stock',
		'title' 		=> 'Force check product stock status',
		'default' 		=> 'no',
		'desc' 			=> 'Before sending back in stock email, this will check if the product is actually in stock or not.'
	),



	array(
		'callback' 		=> 'checkbox',
		'section_id' 	=> 'em_bis',
		'id' 			=> 'bis-auto-send',
		'title' 		=> 'Auto send email.',
		'default' 		=> 'no',
		'desc' 			=> 'Emails will be sent as soon as a product gets back in stock.',
		'pro' 			=> 'yes'
	),



	array(
		'callback' 		=> 'checkbox',
		'section_id' 	=> 'em_bis',
		'id' 			=> 'bis-keep-wl',
		'title' 		=> 'Keep waitlist after sending email.',
		'default' 		=> 'yes',
		'pro' 			=> 'yes'
	),



	array(
		'callback' 		=> 'textarea',
		'section_id' 	=> 'em_bis',
		'id'			=> 'bis-subject',
		'title' 		=> 'Subject',
		'default' 		=> 'The product you wanted is back in stock',
		'desc' 			=> '<a href="#xoo-wl-placeholder-nfo">List of Placeholders</a>',
		'args' 			=> array(
			'rows' 	=> 3,
			'cols' 	=> 70
		)
	),


	array(
		'callback' 		=> 'textarea',
		'section_id' 	=> 'em_bis',
		'id'			=> 'bis-heading',
		'title' 		=> 'Heading',
		'default' 		=> 'Your Product is Now In Stock.',
		'desc' 			=> '<a href="#xoo-wl-placeholder-nfo">List of Placeholders</a>',
		'args' 			=> array(
			'rows' 	=> 2,
			'cols' 	=> 70
		)
	),

	array(
		'callback' 		=> 'textarea',
		'section_id' 	=> 'em_bis',
		'id'			=> 'bis-content',
		'title' 		=> 'Content',
		'default' 		=> $email_content,
		'desc' 			=> '<a href="#xoo-wl-placeholder-nfo">List of Placeholders</a>',
		'args' 			=> array(
			'rows' 	=> 8,
			'cols' 	=> 70
		)
	),


	array(
		'callback' 		=> 'text',
		'section_id' 	=> 'em_bis',
		'id'			=> 'bis-buy-btn-txt',
		'title' 		=> 'Buy Now Button Text',
		'default' 		=> __( 'Buy Now', 'waitlist-woocommerce' ),
	),



	/** Admin Notification */

	array(
		'callback' 		=> 'checkbox',
		'section_id' 	=> 'em_an',
		'id' 			=> 'an-enable',
		'title' 		=> 'Send',
		'default' 		=> 'yes',
		'desc' 			=> 'Sends email notification to admin on joining waitlist',
		'pro' 			=> 'yes'
	),


	array(
		'callback' 		=> 'checkbox',
		'section_id' 	=> 'em_an',
		'id' 			=> 'an-send-once',
		'title' 		=> 'Notify once per user',
		'default' 		=> 'yes',
		'desc' 			=> 'If user updates his data by joining waitlist again, you won\'t receive another email.',
		'pro' 			=> 'yes'
	),



	array(
		'callback' 		=> 'textarea',
		'section_id' 	=> 'em_an',
		'id'			=> 'an-emails',
		'title' 		=> 'Notify admin email',
		'default' 		=> esc_attr( get_option( 'admin_email' ) ),
		'desc' 			=> __( 'When someone joins the waitlist, notify admin. (For mulitple emails. use comma )', 'waitlist-woocommerce' ),
		'args' 			=> array(
			'rows' 	=> 8
		),
		'pro' 			=> 'yes'
	),



	array(
		'callback' 		=> 'textarea',
		'section_id' 	=> 'em_an',
		'id'			=> 'an-subject',
		'title' 		=> 'Subject',
		'default' 		=> 'User has joined the waitlist for [product_name]',
		'desc' 			=> '<a href="#xoo-wl-placeholder-nfo">List of Placeholders</a>',
		'args' 			=> array(
			'rows' 	=> 3,
			'cols' 	=> 70
		),
		'pro' 			=> 'yes'
	),


	array(
		'callback' 		=> 'textarea',
		'section_id' 	=> 'em_an',
		'id'			=> 'an-content',
		'title' 		=> 'Content',
		'default' 		=> '[user_email] has joined the waitlist for [product_name].[new_line] Quantity requested: [quantity]',
		'desc' 			=> '<a href="#xoo-wl-placeholder-nfo">List of Placeholders</a>',
		'args' 			=> array(
			'rows' 	=> 8,
			'cols' 	=> 70
		),
		'pro' 			=> 'yes'
	),


	/** User Notification **/

	array(
		'callback' 		=> 'checkbox',
		'section_id' 	=> 'em_un',
		'id' 			=> 'un-enable',
		'title' 		=> 'Send',
		'default' 		=> 'yes',
		'desc' 			=> 'Sends email notification to user on joining waitlist',
		'pro' 			=> 'yes'
	),



	array(
		'callback' 		=> 'checkbox',
		'section_id' 	=> 'em_un',
		'id' 			=> 'un-send-once',
		'title' 		=> 'Notify once',
		'default' 		=> 'yes',
		'desc' 			=> 'If user updates his data by joining waitlist again, he will not be notified.',
		'pro' 			=> 'yes'
	),



	array(
		'callback' 		=> 'checkbox',
		'section_id' 	=> 'em_un',
		'id' 			=> 'un-en-logo',
		'title' 		=> 'Show Header Logo',
		'default' 		=> 'yes',
		'pro' 			=> 'yes'
	),


	array(
		'callback' 		=> 'checkbox',
		'section_id' 	=> 'em_un',
		'id' 			=> 'un-en-footer',
		'title' 		=> 'Show Footer Content',
		'default' 		=> 'yes',
		'pro' 			=> 'yes'
	),

	array(
		'callback' 		=> 'textarea',
		'section_id' 	=> 'em_un',
		'id'			=> 'un-subject',
		'title' 		=> 'Subject',
		'default' 		=> 'You have joined the waitlist.',
		'desc' 			=> '<a href="#xoo-wl-placeholder-nfo">List of Placeholders</a>',
		'args' 			=> array(
			'rows' 	=> 3,
			'cols' 	=> 70
		),
		'pro' 			=> 'yes'
	),

	array(
		'callback' 		=> 'textarea',
		'section_id' 	=> 'em_un',
		'id'			=> 'un-content',
		'title' 		=> 'Content',
		'default' 		=> 'Thank you for joining the [product_link] waitlist.[new_line]We will inform you as soon as we are back in stock.',
		'desc' 			=> '[unsubscribe] - Unusbscribe Link<br><a href="#xoo-wl-placeholder-nfo">List of Placeholders</a>',
		'args' 			=> array(
			'rows' 	=> 8,
			'cols' 	=> 70
		),
		'pro' 			=> 'yes'
	),
);

return apply_filters( 'xoo_wl_admin_settings', $settings, 'email' );

?>