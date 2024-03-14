<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
	/* BEGIN Section: Contact Button */
	CSF::createSection(
		$prefix,
		[
			'title'  => __( 'Contact Express', 'w2w' ),
			'id'     => 'w2w-contact-button',
			'icon'   => 'fas fa-id-card',
			'fields' => [
				[
					'type'    => 'subheading',
					'content' => __( 'Kích hoạt nút liên hệ', 'w2w' ),
				],
				[
					'type'    => 'submessage',
					'style'   => 'info',
					'content' => sprintf(__( 'Nút liên hệ nhanh cho website. Xem hướng dẫn %1$stại đây%2$s.', 'w2w' ),'<a href="https://wiki.shost.vn/kb/cach-su-dung-plugin-all-in-one-tools-aio-tools/">','</a>'),
				],
				[
					'id'      => 'opt-enable-contact-button',
					'type'    => 'switcher',
					'title'   => __( 'Kích hoạt', 'w2w' ),
					'default' => false,
					'desc'	  => __( 'Bật để kích hoạt nút liên hệ.', 'w2w' ),
				],
				[
					'id'     => 'fs-style',
					'type'   => 'fieldset',
					'title'  => __( 'Kiểu nút liên hệ', 'w2w'),
					'fields' => [
						[
							'id'      => 'opt-icon',
							'type'    => 'icon',
							'default' => 'fas fa-phone-alt'
						],
						[
							'id'	  => 'opt-button-color',
							'type'	  => 'color_group',
							'title'	  => '',
							'options'   => [
								'color-1' => __( 'Màu 1', 'w2w'),
								'color-2' => __( 'Màu 2', 'w2w'),
							],
							'default'   => [
								'color-1' => '#fd5581',
								'color-2' => '#fd8b55',
							  ]
						],
						[
						  'id'          => 'opt-button-style',
						  'type'        => 'select',
						  'title'       => '',
						  'desc'       => __( 'Vị trí mặc định Bên phải.', 'w2w'),
						  'placeholder' => __( 'Chọn vị trí', 'w2w'),
						  'options'     => [
							'left'  => __( 'Bên trái', 'w2w'),
							'right'  => __( 'Bên phải', 'w2w'),
						  ],
						  //'default'     => 'right'
						],
						/*[
							'id'	  => 'opt-button-style',
							'type'	  => 'attribute',
							'title'	  => '',									
						],*/
					],
					'dependency' => [ 'opt-enable-contact-button', '==', 'true', '', 'visible' ],
				],						
				[
					'type'    => 'subheading',
					'content' => __( 'Cài đặt nút liên hệ', 'w2w' ),
				],
				[
					'id'        => 'gr-phone',
					'type'      => 'group',
					'title'     => __( 'Điện thoại', 'w2w'),
					'fields'    => [
						[
							'id'    => 'txtName',
							'type'  => 'text',
							'title' => __( 'Tên vị trí', 'w2w'),
							'attributes'    => [
								'placeholder'	=> __('Hotline, Sales, Technic...','w2w')
							],
						],
						[	
							'id'    => 'txtPhoneNumber',
							'type'  => 'text',
							'title' => __( 'Số điện thoại','w2w'),
							'validate' => 'csf_validate_numeric',
							'attributes'    => [
								'placeholder'	=> 'Nhập số điện thoại...'
							]								
						],
					],
					'dependency' => [ 'opt-enable-contact-button', '==', 'true', '', 'visible' ],
					'min'     	=> 1,
					'max'     	=> 1,
					'default'   => [
							[
							  'txtName'     	=> '',
							  'txtPhoneNumber'	=> '',
							],
						],
					'button_title' => '<i class="fas fa-plus"></i>',
				],
				[	
					'id'    => 'txtFBMessenger',
					'type'  => 'text',
					'title' => __( 'Messenger','w2w'),
					'attributes'    => [
						'placeholder'	=> __('Nhập địa chỉ Messenger...','w2w'),
					],
					'desc'	  => __( 'Ví dụ: https://m.me/[ten-fb], chỉ nhập [ten-fb]', 'w2w' ),
					'dependency' => [ 'opt-enable-contact-button', '==', 'true', '', 'visible' ],
				],			
				[	
					'id'    => 'txtZalo',
					'type'  => 'text',
					'title' => __( 'Zalo Chat','w2w'),
					'validate' => 'csf_validate_numeric',
					'attributes'    => [
						'placeholder'	=> __('Số điện thoại kết nối Zalo...','w2w'),
					],
					'dependency' => [ 'opt-enable-contact-button', '==', 'true', '', 'visible' ],
				],
				[	
					'id'    => 'txtEmail',
					'type'  => 'text',
					'title' => __( 'Email','w2w'),	
					'validate' => 'csf_validate_email',
					'attributes'    => [
						'placeholder'	=> __('Địa chỉ Email...','w2w'),
					],
					'dependency' => [ 'opt-enable-contact-button', '==', 'true', '', 'visible' ],
				],
			],
		]);
	/* END Section: Contact Button */