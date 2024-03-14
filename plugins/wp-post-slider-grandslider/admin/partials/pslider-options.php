<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Metabox of the PAGE.
// Set a unique slug-like ID.
//
$prefix_page_opts = '_prefix_slider_options';

//
// Create a metabox.
//
WPPSGS::createMetabox(
	$prefix_page_opts,
	array(
		'title'     => '<img src="https://ps.w.org/wp-post-slider-grandslider/assets/icon-128x128.png">GrandSlider',
		'post_type' => 'wppsgs_slider',
		'theme'     => 'light',
		'class'     => 'wppsgs--shortcode-postbox',
	)
);

//
// Create a section.
//
WPPSGS::createSection(
	$prefix_page_opts,
	array(
		'title'  => 'Layout Setup',
		'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M264.5 5.2c14.9-6.9 32.1-6.9 47 0l218.6 101c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 149.8C37.4 145.8 32 137.3 32 128s5.4-17.9 13.9-21.8L264.5 5.2zM476.9 209.6l53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 277.8C37.4 273.8 32 265.3 32 256s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0l152-70.2zm-152 198.2l152-70.2 53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 405.8C37.4 401.8 32 393.3 32 384s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0z"/></svg>',
		'fields' => array(

			array(
				'type'    => 'heading',
				'content' => 'Layout Setup',
			),
			array(
				'id'      => 'wppsgs-layout-select',
				'type'    => 'image_select',
				'title'   => 'Select Layout',
				'options' => array(
					'slider-just'   => WPPSGS_URL . 'admin/img/slider-just.png',
					'carousel-just' => WPPSGS_URL . 'admin/img/carousel-just.png',
				),
				'default' => 'slider-just',
				'class'   => 'wppsgs-layout-select',
			),
			array(
				'id'          => 'wppsgs-layout-dimensions',
				'type'        => 'dimensions',
				'title'       => 'Slider Dimensions',
				'width_icon'  => 'width',
				'height_icon' => 'height',
				'default'     => array(
					'width'  => '100',
					'height' => '100',
					'unit'   => '%',
				),
			),
			array(
				'id'         => 'wppsgs-slider-per-page',
				'type'       => 'number',
				'title'      => 'Slide Per Page',
				'default'    => 3,
				'dependency' => array( 'wppsgs-layout-select', '==', 'carousel-just' ),
			),
			array(
				'id'         => 'wppsgs-carousel-gap',
				'type'       => 'number',
				'title'      => 'Gap between slides',
				'default'    => 20,
				'dependency' => array( 'wppsgs-layout-select', '==', 'carousel-just' ),
			),
			array(
				'id'      => 'wppsgs-has-post-image',
				'type'    => 'switcher',
				'title'   => 'Post Featured Image',
				'default' => true,
			),
			array(
				'id'      => 'wppsgs-has-post-title',
				'type'    => 'switcher',
				'title'   => 'Post Title',
				'default' => true,
			),
			array(
				'id'          => 'wppsgs-has-post-title-tag',
				'type'        => 'select',
				'title'       => 'Select',
				'placeholder' => 'Select an option',
				'options'     => array(
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				),
				'default'     => 'h2',
				'dependency'  => array( 'wppsgs-has-post-title', '==', 'true' ),
			),
			array(
				'id'      => 'wppsgs-has-post-excerpt',
				'type'    => 'switcher',
				'title'   => 'Post Excerpt',
				'default' => true,
			),
			array(
				'id'      => 'wppsgs-has-post-readmore-btn',
				'type'    => 'switcher',
				'title'   => 'Post Read More Button',
				'default' => true,
			),
			array(
				'id'      => 'wppsgs-has-post-cat',
				'type'    => 'switcher',
				'title'   => 'Post Category',
				'default' => true,
			),
			array(
				'id'      => 'wppsgs-has-layer',
				'type'    => 'switcher',
				'title'   => 'Content Layer',
				'default' => true,
			),

			array(
				'type'    => 'heading',
				'content' => 'Query Settings',
			),
			array(
				'id'      => 'wppsgs-post-types-select',
				'type'    => 'select',
				'title'   => 'Select a Post Type',
				'options' => 'post_types',
				'class'   => 'wppsgs-disable-field',
			),
			array(
				'id'      => 'wppsgs-post-from',
				'type'    => 'button_set',
				'title'   => 'Post From',
				'options' => array(
					'all'      => 'All',
					'category' => 'Category',
					'selected' => 'Only Selected Posts',
				),
				'default' => 'all',
			),
			array(
				'id'          => 'wppsgs-category-select',
				'type'        => 'select',
				'title'       => 'Select with categories',
				'placeholder' => 'Select a category',
				'chosen'      => true,
				'ajax'        => true,
				'multiple'    => true,
				'options'     => 'categories',
				'dependency'  => array( 'wppsgs-post-from', '==', 'category' ),
			),
			array(
				'id'          => 'wppsgs-post-selected',
				'type'        => 'select',
				'title'       => 'Select Only Posts',
				'placeholder' => 'Select a page',
				'chosen'      => true,
				'ajax'        => true,
				'multiple'    => true,
				'sortable'    => true,
				'placeholder' => 'Select a post',
				'options'     => 'posts',
				'dependency'  => array( 'wppsgs-post-from', '==', 'selected' ),
			),
			array(
				'id'          => 'wppsgs-post-excluded',
				'type'        => 'select',
				'title'       => 'Exclude Posts',
				'placeholder' => 'Select a page',
				'chosen'      => true,
				'ajax'        => true,
				'multiple'    => true,
				'sortable'    => true,
				'placeholder' => 'Exclude a post',
				'options'     => 'posts',
				'dependency'  => array( 'wppsgs-post-from', '!=', 'selected' ),
			),
			array(
				'id'      => 'wppsgs-post-orderby',
				'type'    => 'radio',
				'title'   => 'Sort retrieved posts by',
				'options' => array(
					'none'          => 'None (No order)',
					'ID'            => 'ID (Order by post id)',
					'author'        => 'Order by author.',
					'title'         => 'Order by title.',
					'date'          => 'Order by date.',
					'modified'      => 'Order by last modified date.',
					'rand'          => 'Random order.',
					'comment_count' => 'Order by number of comments.',
				),
				'default' => 'date',
			),
			array(
				'id'      => 'wppsgs-post-order',
				'type'    => 'button_set',
				'title'   => 'Order',
				'options' => array(
					'ASC'  => 'Ascending',
					'DESC' => 'Descending',
				),
				'default' => 'DESC',
			),
		),
	)
);

//
// Section: Slider Settings.
//
WPPSGS::createSection(
	$prefix_page_opts,
	array(
		'title'  => 'Slider Settings',
		'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M0 416c0-17.7 14.3-32 32-32l54.7 0c12.3-28.3 40.5-48 73.3-48s61 19.7 73.3 48L480 384c17.7 0 32 14.3 32 32s-14.3 32-32 32l-246.7 0c-12.3 28.3-40.5 48-73.3 48s-61-19.7-73.3-48L32 448c-17.7 0-32-14.3-32-32zm192 0c0-17.7-14.3-32-32-32s-32 14.3-32 32s14.3 32 32 32s32-14.3 32-32zM384 256c0-17.7-14.3-32-32-32s-32 14.3-32 32s14.3 32 32 32s32-14.3 32-32zm-32-80c32.8 0 61 19.7 73.3 48l54.7 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-54.7 0c-12.3 28.3-40.5 48-73.3 48s-61-19.7-73.3-48L32 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l246.7 0c12.3-28.3 40.5-48 73.3-48zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32s32-14.3 32-32s-14.3-32-32-32zm73.3 0L480 64c17.7 0 32 14.3 32 32s-14.3 32-32 32l-214.7 0c-12.3 28.3-40.5 48-73.3 48s-61-19.7-73.3-48L32 128C14.3 128 0 113.7 0 96S14.3 64 32 64l86.7 0C131 35.7 159.2 16 192 16s61 19.7 73.3 48z"/></svg>',
		'fields' => array(

			array(
				'type'    => 'heading',
				'content' => 'Slider Settings',
			),
			array(
				'id'      => 'wppsgs-slider-total',
				'type'    => 'number',
				'title'   => 'Total Slider',
				'default' => '10',
			),
			array(
				'id'      => 'wppsgs-slider-type',
				'type'    => 'button_set',
				'title'   => 'Slider Type',
				'options' => array(
					'slide' => 'Slide',
					'loop'  => 'Loop',
					'fade'  => 'Fade',
				),
				'default' => 'slide',
			),
			array(
				'id'      => 'wppsgs-slider-speed',
				'type'    => 'number',
				'title'   => 'Speed',
				'default' => '400',
			),
			array(
				'id'      => 'wppsgs-slider-per-move',
				'type'    => 'number',
				'title'   => 'Slide Per Move',
				'default' => '1',
			),
			array(
				'id'      => 'wppsgs-slider-arrows',
				'type'    => 'switcher',
				'title'   => 'Arrows',
				'default' => true,
			),
			array(
				'id'      => 'wppsgs-slider-pagination',
				'type'    => 'switcher',
				'title'   => 'Pagination',
				'default' => true,
			),
			array(
				'id'      => 'wppsgs-slider-autoplay',
				'type'    => 'switcher',
				'title'   => 'Autoplay',
				'default' => true,
			),
			array(
				'id'         => 'wppsgs-slider-autoplay-interval',
				'type'       => 'number',
				'title'      => 'Slide Per Move',
				'default'    => 5000,
				'dependency' => array( 'wppsgs-slider-autoplay', '==', 'true' ),
			),
			array(
				'id'         => 'wppsgs-slider-pauseonhover',
				'type'       => 'switcher',
				'title'      => 'Pause On Hover',
				'default'    => true,
				'dependency' => array( 'wppsgs-slider-autoplay', '==', 'true' ),
			),
			array(
				'id'      => 'wppsgs-slider-lazyLoad',
				'type'    => 'button_set',
				'title'   => 'LazyLoad',
				'desc'    => 'nearby = Starts loading only images around the active slide (page) <br>
								sequential = Loads images sequentially',
				'options' => array(
					'false'      => 'Off',
					'nearby'     => 'Nearby',
					'sequential' => 'Sequential',
				),
				'default' => 'false',
			),

		),
	)
);

//
// Section: Colors.
//
WPPSGS::createSection(
	$prefix_page_opts,
	array(
		'title'  => 'Colors',
		'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M41.4 9.4C53.9-3.1 74.1-3.1 86.6 9.4L168 90.7l53.1-53.1c28.1-28.1 73.7-28.1 101.8 0L474.3 189.1c28.1 28.1 28.1 73.7 0 101.8L283.9 481.4c-37.5 37.5-98.3 37.5-135.8 0L30.6 363.9c-37.5-37.5-37.5-98.3 0-135.8L122.7 136 41.4 54.6c-12.5-12.5-12.5-32.8 0-45.3zm176 221.3L168 181.3 75.9 273.4c-4.2 4.2-7 9.3-8.4 14.6H386.7l42.3-42.3c3.1-3.1 3.1-8.2 0-11.3L277.7 82.9c-3.1-3.1-8.2-3.1-11.3 0L213.3 136l49.4 49.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0zM512 512c-35.3 0-64-28.7-64-64c0-25.2 32.6-79.6 51.2-108.7c6-9.4 19.5-9.4 25.5 0C543.4 368.4 576 422.8 576 448c0 35.3-28.7 64-64 64z"/></svg>',
		'class'  => 'wppsgs-border-after-fields',
		'fields' => array(

			array(
				'type'    => 'heading',
				'content' => 'Colors',
			),
			array(
				'id'      => 'wppsgs-color-slider-bg',
				'type'    => 'color',
				'title'   => 'Slider Background',
				'default' => '#000',
			),
			array(
				'id'      => 'wppsgs-slider-bg-opacity',
				'type'    => 'slider',
				'title'   => 'Slider Background Opacity',
				'min'     => 0,
				'max'     => 100,
				'step'    => 5,
				'unit'    => '%',
				'default' => '30',
			),
			array(
				'id'      => 'wppsgs-color-content-bg',
				'type'    => 'color',
				'title'   => 'Slider Content Background',
				'default' => 'rgba(255,255,255,0.5)',
			),
			array(
				'id'      => 'wppsgs-color-post-category',
				'type'    => 'color_group',
				'title'   => 'Post Category',
				'options' => array(
					'txt' => 'Text',
					'bg'  => 'Background',
				),
				'default' => array(
					'txt' => '#ffc107',
					'bg'  => '#222222',
				),
			),
			array(
				'id'      => 'wppsgs-color-post-title',
				'type'    => 'color',
				'title'   => 'Post Title',
				'default' => '#000',
			),
			array(
				'id'      => 'wppsgs-color-post-excerpt',
				'type'    => 'color',
				'title'   => 'Post Excerpt',
				'default' => '#000',
			),
			array(
				'id'      => 'wppsgs-color-readmore-btn',
				'type'    => 'color_group',
				'title'   => 'Readmore Button',
				'options' => array(
					'txt'       => 'Text',
					'bg'        => 'Background',
					'txt-hover' => 'Text on Hover',
					'bg-hover'  => 'Background on Hover',
				),
				'default' => array(
					'txt'       => '#5027af',
					'bg'        => '#fff',
					'txt-hover' => '#fff',
					'bg-hover'  => '#5027af',
				),
			),
			array(
				'id'      => 'wppsgs-color-slider-arrow',
				'type'    => 'color_group',
				'title'   => 'Slider Arrow',
				'options' => array(
					'icon'       => 'Icon',
					'bg'         => 'Background',
					'icon-hover' => 'Icon on Hover',
					'bg-hover'   => 'Background on Hover',
				),
				'default' => array(
					'icon'       => '#5027af',
					'bg'         => '#fff',
					'icon-hover' => '#fff',
					'bg-hover'   => '#5027af',
				),
			),
			array(
				'id'      => 'wppsgs-color-slider-pagination',
				'type'    => 'color_group',
				'title'   => 'Slider Pagination',
				'options' => array(
					'bullet'       => 'Bullet',
					'bg'           => 'Background',
					'bullet-hover' => 'Bullet on Hover',
					'bg-hover'     => 'Background on Hover',
				),
				'default' => array(
					'bullet'       => '#5027af',
					'bg'           => '#fff',
					'bullet-hover' => '#fff',
					'bg-hover'     => '#5027af',
				),
			),
			array(
				'id'    => '',
				'type'  => '',
				'title' => '',
			),
		),
	)
);

//
// Section: Typography.
//
WPPSGS::createSection(
	$prefix_page_opts,
	array(
		'title'  => 'Typography',
		'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M32 32C14.3 32 0 46.3 0 64v64c0 17.7 14.3 32 32 32s32-14.3 32-32V96H192l0 128H176c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H256l0-128H384v32c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32H224 32zM9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3l64 64c9.2 9.2 22.9 11.9 34.9 6.9s19.8-16.6 19.8-29.6V416H320v32c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l64-64c12.5-12.5 12.5-32.8 0-45.3l-64-64c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6v32H128V320c0-12.9-7.8-24.6-19.8-29.6s-25.7-2.2-34.9 6.9l-64 64z"/></svg>',
		'fields' => array(

			array(
				'type'    => 'heading',
				'content' => 'Coming Soon..',
			),
		),
	)
);

//
// Metabox of the PAGE.
// Set a unique slug-like ID.
//
$prefix_page_opts2 = '_prefix_page_options2';

//
// Create a metabox
//
WPPSGS::createMetabox(
	$prefix_page_opts2,
	array(
		'title'     => 'Custom Page Options',
		'post_type' => 'wppsgs_slider',
		'class'     => 'wppsgs-postbox-shortcode-display',
		'context'   => 'side',
	)
);

if ( isset( $_GET['post'] ) ) {

	WPPSGS::createSection(
		$prefix_page_opts2,
		array(
			'title'  => '',
			'fields' => array(
				array(
					'type'  => 'shortcode',
					'class' => 'wppsgs--shortcode-field',
				),
			),
		)
	);

} else {

	WPPSGS::createSection(
		$prefix_page_opts2,
		array(
			'fields' => array(
				array(
					'type'    => 'content',
					'content' => 'Shortcode will appear here after publish the slider.',
				),

			),
		)
	);
}
