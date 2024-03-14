<?php

return array(
	'themify_author_box' => array(
		'label' => __( 'Author Box', 'themify-shortcodes' ),
		'fields' => array(
			array(
				'name' => 'avatar',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Author profile\'s avatar:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = yes', 'themify-shortcodes' )
			),
			array(
				'name' => 'avatar_size',
				'type' => 'textbox',
				'label' => __( 'Avatar image size:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = 48.', 'themify-shortcodes' )
			),
			array(
				'name' => 'author_link',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show author profile link:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = no', 'themify-shortcodes' )
			),
			array(
				'name' => 'color',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'blue', 'text' => __( 'Blue', 'themify-shortcodes' ) ),
					array( 'value' => 'green', 'text' => __( 'Green', 'themify-shortcodes' ) ),
					array( 'value' => 'red', 'text' => __( 'Red', 'themify-shortcodes' ) ),
					array( 'value' => 'purple', 'text' => __( 'Purple', 'themify-shortcodes' ) ),
					array( 'value' => 'yellow', 'text' => __( 'Yellow', 'themify-shortcodes' ) ),
					array( 'value' => 'orange', 'text' => __( 'Orange', 'themify-shortcodes' ) ),
					array( 'value' => 'pink', 'text' => __( 'Pink', 'themify-shortcodes' ) ),
					array( 'value' => 'lavender', 'text' => __( 'Lavender', 'themify-shortcodes' ) ),
					array( 'value' => 'gray', 'text' => __( 'Gray', 'themify-shortcodes' ) ),
					array( 'value' => 'black', 'text' => __( 'Black', 'themify-shortcodes' ) ),
					array( 'value' => 'light-yellow', 'text' => __( 'Light Yellow', 'themify-shortcodes' ) ),
					array( 'value' => 'light-blue', 'text' => __( 'Light Blue', 'themify-shortcodes' ) ),
					array( 'value' => 'light-green', 'text' => __( 'Light Green', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Color', 'themify-shortcodes' ),
			),
			array(
				'name' => 'icon',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'announcement', 'text' => __( 'Announcement', 'themify-shortcodes' ) ),
					array( 'value' => 'comment', 'text' => __( 'Comment', 'themify-shortcodes' ) ),
					array( 'value' => 'question', 'text' => __( 'Question', 'themify-shortcodes' ) ),
					array( 'value' => 'upload', 'text' => __( 'Upload', 'themify-shortcodes' ) ),
					array( 'value' => 'download', 'text' => __( 'Download', 'themify-shortcodes' ) ),
					array( 'value' => 'highlight', 'text' => __( 'Highlight', 'themify-shortcodes' ) ),
					array( 'value' => 'map', 'text' => __( 'Map', 'themify-shortcodes' ) ),
					array( 'value' => 'warning', 'text' => __( 'Warning', 'themify-shortcodes' ) ),
					array( 'value' => 'info', 'text' => __( 'Info', 'themify-shortcodes' ) ),
					array( 'value' => 'note', 'text' => __( 'Note', 'themify-shortcodes' ) ),
					array( 'value' => 'contact', 'text' => __( 'Contact', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Icon', 'themify-shortcodes' ),
			),
			array(
				'name' => 'style',
				'type' => 'textbox',
				'label' => __( 'Additional Styles', 'themify-shortcodes' ),
			),
		),
		'template' => '[themify_author_box<# if ( data.avatar ) { #> avatar="{{data.avatar}}"<# } #><# if ( data.avatar_size ) { #> avatar_size="{{data.avatar_size}}"<# } #><# if ( data.author_link ) { #> author_link="{{data.author_link}}"<# } #><# if ( data.color ) { #> color="{{data.color}}"<# } #><# if ( data.icon ) { #> icon="{{data.icon}}"<# } #><# if ( data.style ) { #> style="{{data.style}}"<# } #>]'
	),
	'themify_box' => array(
		'label' => __( 'Box', 'themify-shortcodes' ),
		'fields' => array(
			array(
				'name' => 'color',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'blue', 'text' => __( 'Blue', 'themify-shortcodes' ) ),
					array( 'value' => 'green', 'text' => __( 'Green', 'themify-shortcodes' ) ),
					array( 'value' => 'red', 'text' => __( 'Red', 'themify-shortcodes' ) ),
					array( 'value' => 'purple', 'text' => __( 'Purple', 'themify-shortcodes' ) ),
					array( 'value' => 'yellow', 'text' => __( 'Yellow', 'themify-shortcodes' ) ),
					array( 'value' => 'orange', 'text' => __( 'Orange', 'themify-shortcodes' ) ),
					array( 'value' => 'pink', 'text' => __( 'Pink', 'themify-shortcodes' ) ),
					array( 'value' => 'lavender', 'text' => __( 'Lavender', 'themify-shortcodes' ) ),
					array( 'value' => 'gray', 'text' => __( 'Gray', 'themify-shortcodes' ) ),
					array( 'value' => 'black', 'text' => __( 'Black', 'themify-shortcodes' ) ),
					array( 'value' => 'light-yellow', 'text' => __( 'Light Yellow', 'themify-shortcodes' ) ),
					array( 'value' => 'light-blue', 'text' => __( 'Light Blue', 'themify-shortcodes' ) ),
					array( 'value' => 'light-green', 'text' => __( 'Light Green', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Color', 'themify-shortcodes' ),
			),
			array(
				'name' => 'icon',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'announcement', 'text' => __( 'Announcement', 'themify-shortcodes' ) ),
					array( 'value' => 'comment', 'text' => __( 'Comment', 'themify-shortcodes' ) ),
					array( 'value' => 'question', 'text' => __( 'Question', 'themify-shortcodes' ) ),
					array( 'value' => 'upload', 'text' => __( 'Upload', 'themify-shortcodes' ) ),
					array( 'value' => 'download', 'text' => __( 'Download', 'themify-shortcodes' ) ),
					array( 'value' => 'highlight', 'text' => __( 'Highlight', 'themify-shortcodes' ) ),
					array( 'value' => 'map', 'text' => __( 'Map', 'themify-shortcodes' ) ),
					array( 'value' => 'warning', 'text' => __( 'Warning', 'themify-shortcodes' ) ),
					array( 'value' => 'info', 'text' => __( 'Info', 'themify-shortcodes' ) ),
					array( 'value' => 'note', 'text' => __( 'Note', 'themify-shortcodes' ) ),
					array( 'value' => 'contact', 'text' => __( 'Contact', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Icon', 'themify-shortcodes' ),
			),
			array(
				'name' => 'style',
				'type' => 'textbox',
				'label' => __( 'Additional Styles', 'themify-shortcodes' ),
			),
		),
		'template' => '[themify_box<# if ( data.color ) { #> color="{{data.color}}"<# } #><# if ( data.icon ) { #> icon="{{data.icon}}"<# } #><# if ( data.style ) { #> style="{{data.style}}"<# } #>]{{{data.selectedContent}}}[/themify_box]',
	),
	'themify_button' => array(
		'label' => __( 'Button', 'themify-shortcodes' ),
		'fields' => array(
			array(
				'name' => 'label',
				'type' => 'textbox',
				'label' => __( 'Button Text:', 'themify-shortcodes' ),
				'ignore' => true,
			),
			array(
				'name' => 'bgcolor',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'blue', 'text' => __( 'Blue', 'themify-shortcodes' ) ),
					array( 'value' => 'green', 'text' => __( 'Green', 'themify-shortcodes' ) ),
					array( 'value' => 'red', 'text' => __( 'Red', 'themify-shortcodes' ) ),
					array( 'value' => 'purple', 'text' => __( 'Purple', 'themify-shortcodes' ) ),
					array( 'value' => 'yellow', 'text' => __( 'Yellow', 'themify-shortcodes' ) ),
					array( 'value' => 'orange', 'text' => __( 'Orange', 'themify-shortcodes' ) ),
					array( 'value' => 'pink', 'text' => __( 'Pink', 'themify-shortcodes' ) ),
					array( 'value' => 'lavender', 'text' => __( 'Lavender', 'themify-shortcodes' ) ),
					array( 'value' => 'gray', 'text' => __( 'Gray', 'themify-shortcodes' ) ),
					array( 'value' => 'black', 'text' => __( 'Black', 'themify-shortcodes' ) ),
					array( 'value' => 'light-yellow', 'text' => __( 'Light Yellow', 'themify-shortcodes' ) ),
					array( 'value' => 'light-blue', 'text' => __( 'Light Blue', 'themify-shortcodes' ) ),
					array( 'value' => 'light-green', 'text' => __( 'Light Green', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Color', 'themify-shortcodes' ),
			),
			array(
				'name' => 'size',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'small', 'text' => __( 'Small', 'themify-shortcodes' ) ),
					array( 'value' => 'large', 'text' => __( 'Large', 'themify-shortcodes' ) ),
					array( 'value' => 'xlarge', 'text' => __( 'xLarge', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Size', 'themify-shortcodes' ),
			),
			array(
				'name' => 'nofollow',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => 'no', 'text' => 'No' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ))
				),
				'label' => __( 'Nofollow', 'themify-shortcodes' ),
			),
			array(
				'name' => 'link',
				'type' => 'textbox',
				'value' => 'http://',
				'label' => __( 'Button Link:', 'themify-shortcodes' )
			),
			array(
				'name' => 'target',
				'type' => 'textbox',
				'label' => __( 'Link Target:', 'themify-shortcodes' ),
				'tooltip' => sprintf( __( 'Entering %s will open link in a new window (leave blank for default).', 'themify-shortcodes' ), '<strong>_blank</strong>' )
			),
			array(
				'name' => 'color',
				'type' => 'colorbox',
				'value' => '',
				'label' => __( 'Custom Background Color:', 'themify-shortcodes' ),
				'tooltip' => __( 'Enter color in hexadecimal format. For example, #ddd.', 'themify-shortcodes' )
			),
			array(
				'name' => 'text',
				'type' => 'colorbox',
				'label' => __( 'Custom Button Text Color:', 'themify-shortcodes' ),
				'tooltip' => __( 'Enter color in hexadecimal format. For example, #000.', 'themify-shortcodes' )
			),
			array(
				'name' => 'block',
				'type' => 'checkbox',
				'label' => __( 'Block Style', 'themify-shortcodes' ),
			),
			array(
				'name' => 'style',
				'type' => 'textbox',
				'label' => __( 'Additional Styles', 'themify-shortcodes' ),
			),
		),
		'template' => '[themify_button<# if ( data.bgcolor ) { #> bgcolor="{{data.bgcolor}}"<# } #><# if ( data.size ) { #> size="{{data.size}}"<# } #><# if ( data.nofollow ) { #> nofollow="{{data.nofollow}}"<# } #><# if ( data.link ) { #> link="{{data.link}}"<# } #><# if ( data.target ) { #> target="{{data.target}}"<# } #><# if ( data.color ) { #> color="{{data.color}}"<# } #><# if ( data.text ) { #> text="{{data.text}}"<# } #><# if ( data.block ) { #> block="{{data.block}}"<# } #><# if ( data.style ) { #> style="{{data.style}}"<# } #>]{{{data.label}}}[/themify_button]',
	),
	'themify_columns' => array(
		'label' => __( 'Columns', 'themify-shortcodes' ),
		'menu' => array(
			'equal-half' => array(
				'label' => __( 'Equal Half', 'themify-shortcodes' ),
				'fields' => array(),
				'template' => '[themify_col grid="2-1 first"]{{{data.selectedContent}}}[/themify_col] [themify_col grid="2-1"][/themify_col]',
			),
			'equal-third' => array(
				'label' => __( 'Equal Third', 'themify-shortcodes' ),
				'fields' => array(),
				'template' => '[themify_col grid="3-1 first"]{{{data.selectedContent}}}[/themify_col] [themify_col grid="3-1"][/themify_col][themify_col grid="3-1"][/themify_col]',
			),
			'equal-four' => array(
				'label' => __( 'Equal Four', 'themify-shortcodes' ),
				'fields' => array(),
				'template' => '[themify_col grid="4-1 first"]{{{data.selectedContent}}}[/themify_col] [themify_col grid="4-1"][/themify_col] [themify_col grid="4-1"][/themify_col] [themify_col grid="4-1"][/themify_col]',
			),
			'double-n-halves' => array(
				'label' => __( 'Double and Halves', 'themify-shortcodes' ),
				'fields' => array(),
				'template' => '[themify_col grid="4-2 first"]{{{data.selectedContent}}}[/themify_col] [themify_col grid="4-1"][/themify_col] [themify_col grid="4-1"][/themify_col]',
			)
		),
	),
	'themify_slider' => array(
		'label' => __( 'Custom Slider', 'themify-shortcodes' ),
		'fields' => array(
			array(
				'name' => 'visible',
				'type' => 'textbox',
				'label' => __( 'Number of items visible at the same time:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = 1.', 'themify-shortcodes' )
			),
			array(
				'name' => 'scroll',
				'type' => 'textbox',
				'label' => __( 'Number of items to scroll:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = 1.', 'themify-shortcodes' )
			),
			array(
				'name' => 'auto',
				'type' => 'textbox',
				'label' => __( 'Auto play slider in number of seconds:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = 0, the slider will not auto play.', 'themify-shortcodes' )
			),
			array(
				'name' => 'wrap',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Wrap slider:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = yes, the slider will loop back to the first item', 'themify-shortcodes' )
			),
			array(
				'name' => 'speed',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'normal', 'text' => __( 'Normal', 'themify-shortcodes' ) ),
					array( 'value' => 'slow', 'text' => __( 'Slow', 'themify-shortcodes' ) ),
					array( 'value' => 'fast', 'text' => __( 'Fast', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Animation speed:', 'themify-shortcodes' )
			),
			array(
				'name' => 'slider_nav',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show slider navigation:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = yes.', 'themify-shortcodes' )
			),
			array(
				'name' => 'class',
				'type' => 'textbox',
				'label' => __( 'Custom CSS class name:', 'themify-shortcodes' )
			),
		),
		'template' => '[themify_slider<# if ( data.visible ) { #> visible="{{data.visible}}"<# } #><# if ( data.scroll ) { #> scroll="{{data.scroll}}"<# } #><# if ( data.auto ) { #> auto="{{data.auto}}"<# } #><# if ( data.wrap ) { #> wrap="{{data.wrap}}"<# } #><# if ( data.speed ) { #> speed="{{data.speed}}"<# } #><# if ( data.slider_nav ) { #> slider_nav="{{data.slider_nav}}"<# } #><# if ( data.class ) { #> class="{{data.class}}"<# } #>][themify_slide]{{{data.selectedContent}}}[/themify_slide][/themify_slider]',
	),
	'themify_hr' => array(
		'label' => __( 'Horizontal Rule', 'themify-shortcodes' ),
		'fields' => array(
			array(
				'name' => 'color',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'pink', 'text' => __( 'Pink', 'themify-shortcodes' ) ),
					array( 'value' => 'red', 'text' => __( 'Red', 'themify-shortcodes' ) ),
					array( 'value' => 'light-gray', 'text' => __( 'Light Gray', 'themify-shortcodes' ) ),
					array( 'value' => 'dark-gray', 'text' => __( 'Dark Gray', 'themify-shortcodes' ) ),
					array( 'value' => 'black', 'text' => __( 'Black', 'themify-shortcodes' ) ),
					array( 'value' => 'orange', 'text' => __( 'Orange', 'themify-shortcodes' ) ),
					array( 'value' => 'yellow', 'text' => __( 'Yellow', 'themify-shortcodes' ) ),
					array( 'value' => 'white', 'text' => __( 'White', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Rule Color:', 'themify-shortcodes' ),
			),
			array(
				'name' => 'width',
				'type' => 'textbox',
				'label' => __( 'Horizontal Width (in px or %):', 'themify-shortcodes' ),
				'tooltip' => __( 'Example: 50px or 50%.', 'themify-shortcodes' )
			),
			array(
				'name' => 'border_width',
				'type' => 'textbox',
				'label' => __( 'Border Width (in px):', 'themify-shortcodes' ),
				'tooltip' => __( 'Example: 1px.', 'themify-shortcodes' )
			)
		),
		'template' => '[themify_hr<# if ( data.color ) { #> color="{{data.color}}"<# } #><# if ( data.width ) { #> width="{{data.width}}"<# } #><# if ( data.border_width ) { #> border_width="{{data.border_width}}"<# } #>]',
	),
	'themify_icon' => array(
		'label' => __( 'Icon', 'themify-shortcodes' ),
		'fields' => array(
			array(
				'name'    => 'icon',
				'type'  => 'iconpicker',
				'text' => __( 'Pick', 'themify-shortcodes' ),
				'label' => __( 'Icon:', 'themify-shortcodes' )
			),
			array(
				'name'    => 'label',
				'type'  => 'textbox',
				'label' => __( 'Label:', 'themify-shortcodes' )
			),
			array(
				'name'    => 'link',
				'type'  => 'textbox',
				'value' => 'http://',
				'label' => __( 'Link:', 'themify-shortcodes' )
			),
			array(
				'name'    => 'style',
				'type'  => 'textbox',
				'label' => __( 'Style:', 'themify-shortcodes' ),
				'tooltip'  => __( 'Combine rounded, squared, small and large.', 'themify-shortcodes' ),
			),
			array(
				'name'    => 'icon_color',
				'type'  => 'colorbox',
				'label' => __( 'Icon Color:', 'themify-shortcodes' ),
				'tooltip'  => __( 'Enter color in hexadecimal format. For example, #ddd.', 'themify-shortcodes' )
			),
			array(
				'name'    => 'icon_bg',
				'type'  => 'colorbox',
				'label' => __( 'Background Color:', 'themify-shortcodes' ),
				'tooltip'  => __( 'Enter color in hexadecimal format. For example, #ddd.', 'themify-shortcodes' )
			),
		),
		'template' => '[themify_icon<# if ( data.icon ) { #> icon="{{data.icon}}"<# } #><# if ( data.label ) { #> label="{{data.label}}"<# } #><# if ( data.link ) { #> link="{{data.link}}"<# } #><# if ( data.style ) { #> style="{{data.style}}"<# } #><# if ( data.icon_color ) { #> icon_color="{{data.icon_color}}"<# } #><# if ( data.icon_bg ) { #> icon_bg="{{data.icon_bg}}"<# } #>]',
	),
	'themify_list' => array(
		'label' => __( 'Icon List', 'themify-shortcodes' ),
		'fields' => array(
			array(
				'name'    => 'icon',
				'type'  => 'iconpicker',
				'text' => __( 'Pick', 'themify-shortcodes' ),
				'label' => __( 'Icon:', 'themify-shortcodes' )
			),
			array(
				'name'    => 'icon_color',
				'type'  => 'colorbox',
				'label' => __( 'Icon Color:', 'themify-shortcodes' ),
				'tooltip'  => __( 'Enter color in hexadecimal format. For example, #ddd.', 'themify-shortcodes' )
			),
			array(
				'name'    => 'icon_bg',
				'type'  => 'colorbox',
				'label' => __( 'Background Color:', 'themify-shortcodes' ),
				'tooltip'  => __( 'Enter color in hexadecimal format. For example, #ddd.', 'themify-shortcodes' )
			),
			array(
				'name'    => 'style',
				'type'  => 'textbox',
				'label' => __( 'Style:', 'themify-shortcodes' ),
			),
		),
		'template' => '[themify_list<# if ( data.icon ) { #> icon="{{data.icon}}"<# } #><# if ( data.icon_color ) { #> icon_color="{{data.icon_color}}"<# } #><# if ( data.icon_bg ) { #> icon_bg="{{data.icon_bg}}"<# } #><# if ( data.style ) { #> style="{{data.style}}"<# } #>]<ul><li><# if ( ! data.selectedContent ) { data.selectedContent = "&nbsp;"; } #>{{{data.selectedContent}}}</li></ul>[/themify_list]',
	),
	'themify_is_guest' => array(
		'label' => __( 'Is Guest', 'themify-shortcodes' ),
		'fields' => array(),
		'template' => '[themify_is_guest]{{{data.selectedContent}}}[/themify_is_guest]',
	),
	'themify_is_logged_in' => array(
		'label' => __( 'Is Logged In', 'themify-shortcodes' ),
		'fields' => array(),
		'template' => '[themify_is_logged_in]{{{data.selectedContent}}}[/themify_is_logged_in]',
	),
	'themify_list_posts' => array(
		'label' => __( 'List Posts', 'themify-shortcodes' ),
		'fields' => array(
			array(
				'name' => 'style',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'list-post', 'text' => __( 'Post list', 'themify-shortcodes' ) ),
					array( 'value' => 'grid4', 'text' => __( 'Grid 4', 'themify-shortcodes' ) ),
					array( 'value' => 'grid3', 'text' => __( 'Grid 3', 'themify-shortcodes' ) ),
					array( 'value' => 'grid2', 'text' => __( 'Grid 2', 'themify-shortcodes' ) ),
					array( 'value' => 'grid2-thumb', 'text' => __( 'Grid 2 Thumb', 'themify-shortcodes' ) ),
					array( 'value' => 'list-thumb-image', 'text' => __( 'List Thumb', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Layout Style:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = list-post.', 'themify-shortcodes' )
			),
			array(
				'name' => 'limit',
				'type' => 'textbox',
				'label' => __( 'Number of Posts to Query:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = 5', 'themify-shortcodes' )
			),
			array(
				'name' => 'category',
				'type' => 'textbox',
				'label' => __( 'Categories to include', 'themify-shortcodes' ),
				'tooltip' => __( 'Enter the category ID numbers (eg. 2,5,6) or leave blank for default (all categories). Use minus number to exclude category (eg. category=-1 will exclude category 1).', 'themify-shortcodes' )
			),
			array(
				'name' => 'order',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'DESC', 'text' => __( 'Descending', 'themify-shortcodes' ) ),
					array( 'value' => 'ASC', 'text' => __( 'Ascending', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Post Order:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = descending.', 'themify-shortcodes' )
			),
			array(
				'name' => 'orderby',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'date', 'text' => __( 'Date', 'themify-shortcodes' ) ),
					array( 'value' => 'title', 'text' => __( 'Title', 'themify-shortcodes' ) ),
					array( 'value' => 'rand', 'text' => __( 'Random', 'themify-shortcodes' ) ),
					array( 'value' => 'author', 'text' => __( 'Author', 'themify-shortcodes' ) ),
					array( 'value' => 'comment_count', 'text' => __( 'Comments number', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Sort Posts By:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = date.', 'themify-shortcodes' )
			),
			array(
				'name' => 'image',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show Post Image:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = yes', 'themify-shortcodes' )
			),
			array(
				'name' => 'image_w',
				'type' => 'textbox',
				'label' => __( 'Post Image Width:', 'themify-shortcodes' ),
				'tooltip' => __( 'Example: 400px or 94%.', 'themify-shortcodes' )
			),
			array(
				'name' => 'image_h',
				'type' => 'textbox',
				'label' => __( 'Post Image Height:', 'themify-shortcodes' ),
				'tooltip' => __( 'Example: 400px or 94%.', 'themify-shortcodes' )
			),
			array(
				'name' => 'image_size',
				'type' => 'listbox',
				'values' => array(
					array( 'text' => '', 'value' => '' ),
					array( 'text' => __( 'Thumbnail', 'themify-shortcodes' ), 'value' => 'thumbnail' ),
					array( 'text' => __( 'Medium', 'themify-shortcodes' ), 'value' => 'medium' ),
					array( 'text' => __( 'Large', 'themify-shortcodes' ), 'value' => 'large' ),
					array( 'text' => __( 'Original', 'themify-shortcodes' ), 'value' => 'full' ),
				),
				'label' => __( 'Post Image Size:', 'themify-shortcodes' ),
				'tooltip' => __( 'Use this if you have disabled the image script', 'themify-shortcodes' )
			),
			array(
				'name' => 'title',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show Post Title:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = yes', 'themify-shortcodes' )
			),
			array(
				'name' => 'display',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'content', 'text' => __( 'Content', 'themify-shortcodes' ) ),
					array( 'value' => 'excerpt', 'text' => __( 'Excerpt', 'themify-shortcodes' ) ),
					array( 'value' => 'none', 'text' => __( 'None', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show Post Text:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = none, neither content or excerpt are displayed.', 'themify-shortcodes' )
			),
			array(
				'name' => 'post_meta',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show Post Meta:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = no.', 'themify-shortcodes' )
			),
			array(
				'name' => 'more_text',
				'type' => 'textbox',
				'label' => __( 'More Text:', 'themify-shortcodes' ),
				'tooltip' => __( 'Only available if display=content and post has more tag.', 'themify-shortcodes' )
			),
			array(
				'name' => 'post_date',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show Post Date:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = no.', 'themify-shortcodes' )
			),
		),
		'template' => '[themify_list_posts<# if ( data.style ) { #> style="{{data.style}}"<# } #><# if ( data.limit ) { #> limit="{{data.limit}}"<# } #><# if ( data.category ) { #> category="{{data.category}}"<# } #><# if ( data.order ) { #> order="{{data.order}}"<# } #><# if ( data.orderby ) { #> orderby="{{data.orderby}}"<# } #><# if ( data.image ) { #> image="{{data.image}}"<# } #><# if ( data.image_w ) { #> image_w="{{data.image_w}}"<# } #><# if ( data.image_h ) { #> image_h="{{data.image_h}}"<# } #><# if ( data.image_size ) { #> image_size="{{data.image_size}}"<# } #><# if ( data.title ) { #> title="{{data.title}}"<# } #><# if ( data.display ) { #> display="{{data.display}}"<# } #><# if ( data.post_meta ) { #> post_meta="{{data.post_meta}}"<# } #><# if ( data.more_text ) { #> more_text="{{data.more_text}}"<# } #><# if ( data.post_date ) { #> post_date="{{data.post_date}}"<# } #>]',
	),
	'themify_map' => array(
		'label' => __( 'Map', 'themify-shortcodes' ),
		'fields' => array(
			array(
				'name' => 'address',
				'type' => 'textbox',
				'label' => __( 'Location Address:', 'themify-shortcodes' ),
				'tooltip' => __( 'Example: 238 Street Ave., Toronto, Ontario, Canada', 'themify-shortcodes' )
			),
			array(
				'name' => 'width',
				'type' => 'textbox',
				'label' => __( 'Map Width (in px or %):', 'themify-shortcodes' ),
				'tooltip' => __( 'Example: 400px or 94%.', 'themify-shortcodes' )
			),
			array(
				'name' => 'height',
				'type' => 'textbox',
				'label' => __( 'Map Height (in px):', 'themify-shortcodes' ),
				'tooltip' => __( 'Example: 400px.', 'themify-shortcodes' )
			),
			array(
				'name' => 'zoom',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => '1', 'text' => '1' ),
					array( 'value' => '2', 'text' => '2' ),
					array( 'value' => '3', 'text' => '3' ),
					array( 'value' => '4', 'text' => '4' ),
					array( 'value' => '5', 'text' => '5' ),
					array( 'value' => '6', 'text' => '6' ),
					array( 'value' => '7', 'text' => '7' ),
					array( 'value' => '8', 'text' => '8' ),
				),
				'label' => __( 'Map Zoom Level:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = 8', 'themify-shortcodes' )
			),
			array(
				'name' => 'type',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'roadmap', 'text' => __( 'Road map', 'themify-shortcodes' ) ),
					array( 'value' => 'satellite', 'text' => __( 'Satellite', 'themify-shortcodes' ) ),
					array( 'value' => 'hybrid', 'text' => __( 'Hybrid', 'themify-shortcodes' ) ),
					array( 'value' => 'terrain', 'text' => __( 'Terrain', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Map Type:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = Road Map', 'themify-shortcodes' ),
			),
		),
		'template' => '[themify_map<# if ( data.address ) { #> address="{{data.address}}"<# } #><# if ( data.width ) { #> width="{{data.width}}"<# } #><# if ( data.height ) { #> height="{{data.height}}"<# } #><# if ( data.zoom ) { #> zoom="{{data.zoom}}"<# } #><# if ( data.type ) { #> type="{{data.type}}"<# } #>]',
	),
	'themify_post_slider' => array(
		'label' => __( 'Post Slider', 'themify-shortcodes' ),
		'fields' => array(
			array(
				'name' => 'limit',
				'type' => 'textbox',
				'label' => __( 'Number of Posts to Query:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = 5', 'themify-shortcodes' )
			),
			array(
				'name' => 'category',
				'type' => 'textbox',
				'label' => __( 'Categories to include', 'themify-shortcodes' ),
				'tooltip' => __( 'Enter the category ID numbers (eg. 2,5,6) or leave blank for default (all categories). Use minus number to exclude category (eg. category=-1 will exclude category 1).', 'themify-shortcodes' )
			),
			array(
				'name' => 'order',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'ASC', 'text' => __( 'Descending', 'themify-shortcodes' ) ),
					array( 'value' => 'DESC', 'text' => __( 'Ascending', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Post Order:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = descending.', 'themify-shortcodes' )
			),
			array(
				'name' => 'orderby',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'date', 'text' => __( 'Date', 'themify-shortcodes' ) ),
					array( 'value' => 'title', 'text' => __( 'Title', 'themify-shortcodes' ) ),
					array( 'value' => 'rand', 'text' => __( 'Random', 'themify-shortcodes' ) ),
					array( 'value' => 'author', 'text' => __( 'Author', 'themify-shortcodes' ) ),
					array( 'value' => 'comment_count', 'text' => __( 'Comments number', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Sort Posts By:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = date.', 'themify-shortcodes' )
			),
			array(
				'name' => 'image',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show Post Image:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = yes', 'themify-shortcodes' )
			),
			array(
				'name' => 'image_w',
				'type' => 'textbox',
				'label' => __( 'Post Image Width:', 'themify-shortcodes' ),
				'tooltip' => __( 'Example: 400px or 94%.', 'themify-shortcodes' )
			),
			array(
				'name' => 'image_h',
				'type' => 'textbox',
				'label' => __( 'Post Image Height:', 'themify-shortcodes' ),
				'tooltip' => __( 'Example: 400px or 94%.', 'themify-shortcodes' )
			),
			array(
				'name' => 'image_size',
				'type' => 'listbox',
				'values' => array(
					array( 'text' => '', 'value' => '' ),
					array( 'text' => __( 'Thumbnail', 'themify-shortcodes' ), 'value' => 'thumbnail' ),
					array( 'text' => __( 'Medium', 'themify-shortcodes' ), 'value' => 'medium' ),
					array( 'text' => __( 'Large', 'themify-shortcodes' ), 'value' => 'large' ),
					array( 'text' => __( 'Original', 'themify-shortcodes' ), 'value' => 'full' ),
				),
				'label' => __( 'Post Image Size:', 'themify-shortcodes' ),
				'tooltip' => __( 'Use this if you have disabled the image script', 'themify-shortcodes' )
			),
			array(
				'name' => 'title',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show Post Title:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = yes', 'themify-shortcodes' )
			),
			array(
				'name' => 'display',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'content', 'text' => __( 'Content', 'themify-shortcodes' ) ),
					array( 'value' => 'excerpt', 'text' => __( 'Excerpt', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show Post Text:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = none, neither content or excerpt are displayed.', 'themify-shortcodes' )
			),
			array(
				'name' => 'post_meta',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show Post Meta:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = no.', 'themify-shortcodes' )
			),
			array(
				'name' => 'more_text',
				'type' => 'textbox',
				'label' => __( 'More Text:', 'themify-shortcodes' ),
				'tooltip' => __( 'Only available if display=content and post has more tag.', 'themify-shortcodes' )
			),
			array(
				'name' => 'visible',
				'type' => 'textbox',
				'label' => __( 'Number of posts visible at the same time:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = 1.', 'themify-shortcodes' )
			),
			array(
				'name' => 'scroll',
				'type' => 'textbox',
				'label' => __( 'Number of items to scroll:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = 1.', 'themify-shortcodes' )
			),
			array(
				'name' => 'auto',
				'type' => 'textbox',
				'label' => __( 'Auto play slider in number of seconds:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = 0, the slider will not auto play.', 'themify-shortcodes' )
			),
			array(
				'name' => 'wrap',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Wrap slider posts:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = yes, the slider will loop back to the first item', 'themify-shortcodes' )
			),
			array(
				'name' => 'speed',
				'type' => 'listbox',
				'options' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'normal', 'text' => __( 'Normal', 'themify-shortcodes' ) ),
					array( 'value' => 'slow', 'text' => __( 'Slow', 'themify-shortcodes' ) ),
					array( 'value' => 'fast', 'text' => __( 'Fast', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Animation speed:', 'themify-shortcodes' )
			),
			array(
				'name' => 'slider_nav',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show slider navigation:', 'themify-shortcodes' ),
				'tooltip' => __( 'Default = yes.', 'themify-shortcodes' )
			),
			array(
				'name' => 'width',
				'type' => 'textbox',
				'label' => __( 'Slider div tag width:', 'themify-shortcodes' )
			),
			array(
				'name' => 'height',
				'type' => 'textbox',
				'label' => __( 'Slider div tag height:', 'themify-shortcodes' )
			),
			array(
				'name' => 'class',
				'type' => 'textbox',
				'label' => __( 'Custom CSS class name:', 'themify-shortcodes' )
			),
		),
		'template' => '[themify_post_slider<# if ( data.limit ) { #> limit="{{data.limit}}"<# } #><# if ( data.more_text ) { #> more_text="{{data.more_text}}"<# } #><# if ( data.height ) { #> height="{{data.height}}"<# } #><# if ( data.width ) { #> width="{{data.width}}"<# } #><# if ( data.slider_nav ) { #> slider_nav="{{data.slider_nav}}"<# } #><# if ( data.speed ) { #> speed="{{data.speed}}"<# } #><# if ( data.wrap ) { #> wrap="{{data.wrap}}"<# } #><# if ( data.auto ) { #> auto="{{data.auto}}"<# } #><# if ( data.scroll ) { #> scroll="{{data.scroll}}"<# } #><# if ( data.visible ) { #> visible="{{data.visible}}"<# } #><# if ( data.post_meta ) { #> post_meta="{{data.post_meta}}"<# } #><# if ( data.category ) { #> category="{{data.category}}"<# } #><# if ( data.display ) { #> display="{{data.display}}"<# } #><# if ( data.title ) { #> title="{{data.title}}"<# } #><# if ( data.image_size ) { #> image_size="{{data.image_size}}"<# } #><# if ( data.image_h ) { #> image_h="{{data.image_h}}"<# } #><# if ( data.image_w ) { #> image_w="{{data.image_w}}"<# } #><# if ( data.image ) { #> image="{{data.image}}"<# } #><# if ( data.orderby ) { #> orderby="{{data.orderby}}"<# } #><# if ( data.order ) { #> order="{{data.order}}"<# } #><# if ( data.class ) { #> class="{{data.class}}"<# } #>]',
	),
	'themify_quote' => array(
		'label' => __( 'Quote', 'themify-shortcodes' ),
		'fields' => array(),
		'template' => '[themify_quote]{{{data.selectedContent}}}[/themify_quote]',
	),
	'themify_twitter' => array(
		'label' => __( 'Twitter Stream', 'themify-shortcodes' ),
		'fields' => array(
			array(
				'name' => 'username',
				'type' => 'textbox',
				'label' => __( 'Twitter username:', 'themify-shortcodes' ),
				'tooltip' => __( 'Example: themify', 'themify-shortcodes' )
			),
			array(
				'name' => 'show_count',
				'type' => 'textbox',
				'label' => __( 'Number of tweets to show:', 'themify-shortcodes' )
			),
			array(
				'name' => 'show_timestamp',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show tweet date and time:', 'themify-shortcodes' )
			),
			array(
				'name' => 'show_follow',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => '', 'text' => '' ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-shortcodes' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-shortcodes' ) ),
				),
				'label' => __( 'Show a link to your account:', 'themify-shortcodes' )
			),
			array(
				'name' => 'follow_text',
				'type' => 'textbox',
				'label' => __( 'Text linked to your Twitter account:', 'themify-shortcodes' )
			)
		),
		'template' => '[themify_twitter<# if ( data.username ) { #> username="{{data.username}}"<# } #><# if ( data.show_count ) { #> show_count="{{data.show_count}}"<# } #><# if ( data.show_timestamp ) { #> show_timestamp="{{data.show_timestamp}}"<# } #><# if ( data.show_follow ) { #> show_follow="{{data.show_follow}}"<# } #><# if ( data.follow_text ) { #> follow_text="{{data.follow_text}}"<# } #>]',
	),
);