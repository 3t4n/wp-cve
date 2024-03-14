<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
/* BEGIN Section: Header & Footer */
CSF::createSection(
	$prefix,
	[
		'title'  => __( 'Header & Footer', 'w2w' ),
		'id'     => 'w2w-header-footer',
		'icon'   => 'fas fa-file-code',
		'fields' => [
			[
				'type'    => 'subheading',
				/* translators: %s = WP Toolkit  */
				'content' => __( 'Cài đặt Header - Footer', 'w2w' ),
			],
			[
				'id'      => 'optHeader',
				'type'    => 'code_editor',
				'title'   => __( 'Header Scripts','w2w' ),
				'subtitle'	  => __( 'Thêm các tập lệnh tùy chỉnh vào trong thẻ HEAD.','w2w' ),							
				'sanitize'=> false,
			],
			[
				'id'      => 'optFooter',
				'type'    => 'code_editor',							
				'title'   => __( 'Footer Scripts','w2w' ),
				'subtitle'	  => __( 'Thêm các tập lệnh tùy chỉnh vào chân trang của thẻ FOOTER.','w2w' ),
				'sanitize'=> false,
			]
		],
	]
);
/* END Section: Header & Footer */