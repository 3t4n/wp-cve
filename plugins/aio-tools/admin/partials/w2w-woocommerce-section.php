<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
use AIOTools\W2W_Utils;
/* BEGIN Section: Woocommerce */
if(W2W_Utils::is_plugin_active( 'woocommerce/woocommerce.php' )){
	CSF::createSection(
		$prefix,
		[
			'title'  => __( 'WooCommerce', 'w2w' ),
			'id'     => 'w2w-woocommerce',
			'icon'   => 'fas fa-cart-plus',
			'fields' => [
				[
					'type'    => 'subheading',
					'content' => __( 'Kích hoạt WooCommerce', 'w2w' ),
				],
				[
					'type'    => 'submessage',
					'style'   => 'info',
					'content' => __( 'Dễ dàng bật/tắt các ô nhập liệu tại trang thanh toán. Bao gồm thông tin thanh toán và vận chuyển,...', 'w2w' ),
				],
				[
					'id'      => 'opt-enable-wc',
					'type'    => 'switcher',
					'title'   => __( 'Kích hoạt', 'w2w' ),
					'default' => false,
					'desc'	  => __( 'Bật để kích hoạt tính năng tùy chỉnh thanh toán.', 'w2w' ),
				],
				[
					'type'    => 'subheading',
					'content' => __( 'Tùy chỉnh thanh toán', 'w2w' ),
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ],
				],
				/*[
					'id'      => 'opt-first-name',
					'type'    => 'switcher',
					'title'   => __( 'First Name', 'w2w' ),
					'default' => false,
					'dependency' => ['opt-enable-wc', '==', 'true', '', 'visible' ],
				],
				[
					'id'      => 'opt-last-name',
					'type'    => 'switcher',
					'title'   => __( 'Last Name', 'w2w' ),
					'default' => false,
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ],
				],*/
				[
					'id'      => 'opt-company',
					'type'    => 'switcher',
					'title'   => __( 'Công ty', 'w2w' ),
					'default' => false,
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ]
				],
				[
					'id'      => 'opt-address-1',
					'type'    => 'switcher',
					'title'   => __( 'Dòng địa chỉ 1', 'w2w' ),
					'default' => false,
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ]
				],
				[
					'id'      => 'opt-address-2',
					'type'    => 'switcher',
					'title'   => __( 'Dòng địa chỉ 2', 'w2w' ),
					'default' => false,
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ]
				],
				[
					'id'      => 'opt-city',
					'type'    => 'switcher',
					'title'   => __( 'Thành phố', 'w2w' ),
					'default' => false,
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ]
				],
				[
					'id'      => 'opt-postcode',
					'type'    => 'switcher',
					'title'   => __( 'Mã bưu điện', 'w2w' ),
					'default' => false,
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ]
				],
				[
					'id'      => 'opt-country',
					'type'    => 'switcher',
					'title'   => __( 'Quốc gia', 'w2w' ),
					'default' => false,
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ]
				],
				[
					'id'      => 'opt-state',
					'type'    => 'switcher',
					'title'   => __( 'Tỉnh', 'w2w' ),
					'default' => false,
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ]
				],
				[
					'id'      => 'opt-phone',
					'type'    => 'switcher',
					'title'   => __( 'Điện thoại', 'w2w' ),
					'default' => false,
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ]
				],
				[
					'id'      => 'opt-email',
					'type'    => 'switcher',
					'title'   => __( 'Email', 'w2w' ),
					'default' => false,
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ]
				],
				[
					'type'    => 'subheading',
					'content' => __( 'Tùy chỉnh thanh toán danh cho Việt Nam', 'w2w' ),
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ],
				],
				[
					'type'    => 'submessage',
					'style'   => 'warning',
					'content' => __( 'Tùy chỉnh thông tin thanh toán phù hợp tại Việt Nam với tỉnh/thành phố, quận huyện, xã/phường/thị trấn,...', 'w2w' ),
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ]
				],
				[
					'id'      => 'opt-enable-vn-checkout',
					'type'    => 'switcher',
					'title'   => __( 'Kích hoạt', 'w2w' ),
					'default' => false,
					'desc'	  => __( 'Bật để kích hoạt tính năng tùy chỉnh thanh toán.', 'w2w' ),
					'dependency' => [ 'opt-enable-wc', '==', 'true', '', 'visible' ]
				],
			],
		]
	);
}
/* END Section: Woocommerce */