<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* BEGIN Section: SMTP */
CSF::createSection(
		$prefix,
		[
			'title'  => __( 'SMTP Settings', 'w2w' ),
			'id'     => 'w2w-smtp',
			'icon'   => 'fas fa-mail-bulk',
			'fields' => [
				[
					'type'    => 'subheading',
					'content' => __( 'SMTP Settings', 'w2w' ),
				],
				[
					'type'    => 'submessage',
					'style'   => 'info',
					'content' => __( 'You can request your hosting provider for the SMTP details of your site. Use the SMTP details provided by your hosting provider to configure the following settings.', 'w2w' ),
				],
				[	
					'id'    => 'txtFromEmail',
					'type'  => 'text',
					'title' => __( 'From Email','w2w'),	
					'validate' => 'csf_validate_email',
					'attributes'    => [
						'placeholder'	=> __('Ex: info@your-domain','w2w'),
					],
					
				],
				[	
					'id'    => 'txtFromName',
					'type'  => 'text',
					'title' => __( 'From Name','w2w'),
					'attributes'    => [
						'placeholder'	=> __('WordPress','w2w'),
					],
					
				],
				[
					'id'        => 'opt-image-select-1',
					'type'      => 'image_select',
					'title'     => 'SMTP Mailer',
					'class'		=> 'w2w-smtp-mailer',
					'options'   => [
						'smtp' => W2W_URL . 'admin/assets/images/smtp.svg',
						/*'outlook' => W2W_URL . 'admin/assets/images/microsoft.svg',*/
					],
					'default'   => 'smtp'
				],
				[	
					'id'    => 'txtSmtpHost',
					'type'  => 'text',
					'title' => __( 'SMTP Host','w2w'),
					'class'		=> 'w2w-smtp-host',
					'attributes'    => [
						'placeholder'	=> __('Ex: smtp.gmail.com','w2w'),
					],
					
				],
				[
					'id'         => 'opt-encryption',
					'type'       => 'radio',
					'title'      => __('SMTP Encryption','w2w'),
					'class'		 => 'w2w-smtp-encryption',
					'options'    => [
						'none' => 'None',
						'ssl' => 'SSL/TLS',
						'tls' => 'STARTTLS',
					],
					'default'    => '',
					'desc'		=> __('For most servers SSL/TLS is the recommended option','w2w'),
					'inline'	=> true,								
				],
				[	
					'id'    => 'txtSmtpPort',
					'type'  => 'text',
					'title' => __('SMTP Port','w2w'),
					'class'		 => 'w2w-smtp-port',
					'default'    => 25,
					'attributes' => [
							'readonly' => 'readonly',
						],								
				],
				[
					'id'         => 'opt-smtp-authentication',
					'type'       => 'radio',
					'title'      => __('SMTP Authentication','w2w'),
					'class'		 => 'w2w-smtp-authentication',
					'options'    => [
						'no' => __('No','w2w'),
						'yes' => __('Yes','w2w'),
					],
					'default'    => 'yes',
					'desc'		=> __('This options should always be checked \'Yes\'','w2w'),
					'inline'	=> true,								
				],
				[	
					'id'    => 'txtSmtpUserName',
					'type'  => 'text',
					'title' => __( 'SMTP Username','w2w'),
					'validate' => 'csf_validate_email',
					'attributes'    => [
						'placeholder'	=> __('Ex: info@your-domain','w2w'),
					],
				],
				[	
					'id'    => 'txtSmtpPassword',
					'type'  => 'text',
					'title' => __( 'SMTP Password','w2w'),
					'attributes'  => [
						'type'      => 'password',
					],
				],
				[
					'type'    => 'subheading',
					'content' => __( 'Email Test', 'w2w' ),
				],
				[
					'type'    => 'submessage',
					'style'   => 'info',
					'class' => 'hidden smtpTestMessage',
					'content' => __( '', 'w2w' ),					
				],
				[	
					'id'    => 'txtYourEmail',
					'type'  => 'text',
					'title' => __( 'Your Email','w2w'),
					'validate' => 'csf_validate_email',
					'attributes'    => [
						'class'		=> 'txtYourEmail',
						'placeholder'	=> __('Ex: info@your-domain','w2w'),
					],
				],
				[	
					'id'    => 'btnSendTestEmail',
					'type'  => 'button',
					'icon'   => 'fas fa-mail-bulk',
					'value'	=> __('Send Test','w2w'),
					'attributes' => [
						'class' => 'button button-primary btnSendTestEmail',
					]
				],
				
			],
		]
);
/* END Section: SMTP */