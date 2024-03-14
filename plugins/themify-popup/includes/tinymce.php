<?php

class Themify_Popup_TinyMCE {

	static function init() {
		add_filter( 'mce_external_plugins', array( __CLASS__, 'mce_external_plugins' ) );
		add_filter( 'mce_buttons', array( __CLASS__, 'mce_buttons' ) );
	}

	/**
	 * Add plugin JS file to list of external plugins.
	 *
	 * @param array $mce_external_plugins
	 * @return mixed
	 */
	static function mce_external_plugins( $mce_external_plugins ) {
            $mce_external_plugins['mceThemifyPopup'] = themify_metabox_enque(THEMIFY_POPUP_URI . 'assets/tinymce.js');
            self::tinymce_localize();
            return $mce_external_plugins;
	}

	static function mce_buttons( $mce_buttons ) {
		$mce_buttons[] = 'separator';
		$mce_buttons[] = 'mceThemifyPopup';
		return $mce_buttons;
	}

	private static function get_manual_popup_list() {
		$list = array();
		$args = array(
			'post_type' => 'themify_popup',
			'post_status' => 'publish',
			'posts_per_page' => -1,
                        'no_found_rows'=>true,
			'nopaging' => true,
			'meta_query' => array(
				array(
					'key' => 'popup_trigger',
					'value' => 'manual',
					'compare' => '=',
				),
			)
		);
		if (class_exists('SitePress')) {
			/*
			* This will insure that only wpml current language related popups are shown in shortcode popup list.
			*/
			$args['suppress_filters'] = '0';
		}
		$query = get_posts( $args );
		if( ! empty( $query ) ) {
			foreach( $query as $popup ) {
				$list[] = array( 'text' => $popup->post_title, 'value' => $popup->ID );
			}
		}

		return $list;
	}

	private static function tinymce_localize() {
		$fields = array(
			'label' => __( 'Button', 'themify-popup' ),
			'fields' => array(
				array(
					'name' => 'link',
					'type' => 'listbox',
					'values' => self::get_manual_popup_list(),
					'label' => __( 'Link Button To', 'themify-popup' ),
				),
				array(
					'name' => 'link_description',
					'type' => 'container',
					'html' => sprintf( __( 'Add new popups at <a href="#">Themify Popups > Add new</a>.<br> The popup must select "Manual launch" in order to be launched manually.', 'themify-popup' ), admin_url( 'post-new.php?post_type=themify_popup' ) ) . '<hr style="border-bottom: 1px solid #ccc; margin: 5px 0;">',
				),
				array(
					'name' => 'label',
					'type' => 'textbox',
					'label' => __( 'Button Text', 'themify-popup' ),
					'value' => __( 'Launch Popup', 'themify-popup' ),
				),
				array(
					'name' => 'color',
					'type' => 'listbox',
					'values' => array(
						array( 'value' => '', 'text' => '' ),
						array( 'value' => 'blue', 'text' => __( 'Blue', 'themify-popup' ) ),
						array( 'value' => 'green', 'text' => __( 'Green', 'themify-popup' ) ),
						array( 'value' => 'red', 'text' => __( 'Red', 'themify-popup' ) ),
						array( 'value' => 'purple', 'text' => __( 'Purple', 'themify-popup' ) ),
						array( 'value' => 'yellow', 'text' => __( 'Yellow', 'themify-popup' ) ),
						array( 'value' => 'orange', 'text' => __( 'Orange', 'themify-popup' ) ),
						array( 'value' => 'pink', 'text' => __( 'Pink', 'themify-popup' ) ),
						array( 'value' => 'lavender', 'text' => __( 'Lavender', 'themify-popup' ) ),
						array( 'value' => 'gray', 'text' => __( 'Gray', 'themify-popup' ) ),
						array( 'value' => 'black', 'text' => __( 'Black', 'themify-popup' ) ),
						array( 'value' => 'light-yellow', 'text' => __( 'Light Yellow', 'themify-popup' ) ),
						array( 'value' => 'light-blue', 'text' => __( 'Light Blue', 'themify-popup' ) ),
						array( 'value' => 'light-green', 'text' => __( 'Light Green', 'themify-popup' ) ),
					),
					'label' => __( 'Button Color', 'themify-popup' ),
				),
				array(
					'name' => 'size',
					'type' => 'listbox',
					'values' => array(
						array( 'value' => '', 'text' => __( 'Normal', 'themify-popup' ) ),
						array( 'value' => 'small', 'text' => __( 'Small', 'themify-popup' ) ),
						array( 'value' => 'large', 'text' => __( 'Large', 'themify-popup' ) ),
						array( 'value' => 'xlarge', 'text' => __( 'xLarge', 'themify-popup' ) ),
					),
					'label' => __( 'Button Size', 'themify-popup' ),
				),
				array(
					'name' => 'custom_color',
					'type' => 'colorbox',
					'value' => '',
					'label' => __( 'Custom Background Color', 'themify-popup' ),
					'tooltip' => __( 'Enter color in hexadecimal format. For example, #ddd.', 'themify-popup' )
				),
				array(
					'name' => 'custom_text_color',
					'type' => 'colorbox',
					'label' => __( 'Custom Button Text Color', 'themify-popup' ),
					'tooltip' => __( 'Enter color in hexadecimal format. For example, #000.', 'themify-popup' )
				),
				array(
					'name' => 'block',
					'type' => 'checkbox',
					'label' => __( 'Fullwidth Button', 'themify-popup' ),
				),
				array(
					'name' => 'style',
					'type' => 'textbox',
					'label' => __( 'Additional Styles', 'themify-popup' ),
					'tooltip' => __( 'Additional button styles. You can enter one or more of: outline, gradient, flat, rounded, embossed; or a custom CSS classname.', 'themify-popup' )
				),
			),
			'template' => '[tf_popup<# if ( data.style = [ data.size, data.color, data.style, ( data.block ) ? "block" : "" ].filter( Boolean ).join( " " ) ) { #> style="{{data.style}}"<# } #> link="{{data.link}}"<# if ( data.custom_color ) { #> color="{{data.custom_color}}"<# } #><# if ( data.custom_text_color ) { #> text="{{data.custom_text_color}}"<# } #>]{{{data.label}}}[/tf_popup]',
		);

		wp_localize_script( 'editor', 'mceThemifyPopup', array(
			'fields' => $fields,
			'labels' => array(
				'menuName' => __( 'Themify Popup', 'themify-popup' )
			)
		));
	}
}
Themify_Popup_TinyMCE::init();