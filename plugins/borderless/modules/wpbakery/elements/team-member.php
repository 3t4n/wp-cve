<?php

defined( 'ABSPATH' ) || exit;

/*-----------------------------------------------------------------------------------*/
/*	Team Member
/*-----------------------------------------------------------------------------------*/

class WPBakeryShortCode_borderless_wpbakery_team_member extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'picture' => null,
			'picture_size' => null,
			'name' => null,
			'name_tag' => 'h3',
			'job_position' => null,
			'job_position_tag' => 'h4',
			'description' => null,
			'behance' => null,
			'dribbble' => null,
			'facebook' => null,
			'github' => null,
			'instagram' => null,
			'linkedin' => null,
			'medium' => null,
			'pinterest' => null,
			'reddit' => null,
			'snapchat' => null,
			'tiktok' => null,
			'twitch' => null,
			'twitter' => null,
			'vimeo' => null,
			'wechat' => null,
			'whatsapp' => null,
			'youtube' => null,
			'open_in_new_window' => null,
			'add_nofollow' => null,
			'color' => null,
			'custom_color' => null,
			//Static
			'el_id' => null,
			'el_class' => null,
			'css' => null,
			'css_animation' => ''
		), $atts ) );
		$output = '';

		// Assets.
		wp_enqueue_style(
			'borderless-wpbakery-style',
			BORDERLESS__STYLES . 'wpbakery.min.css', 
			false, 
			BORDERLESS__VERSION
		);


		// Retrieve data from the database.
		$options = get_option( 'borderless' );


		// Set default values
		$borderless_primary_color = isset( $options['primary_color'] ) ? $options['primary_color'] : '#3379fc'; //Primary Color
		$borderless_secondary_color = isset( $options['secondary_color'] ) ? $options['secondary_color'] : '#3379fc'; //Secondary Color
		$borderless_text_color = isset( $options['text_color'] ) ? $options['text_color'] : ''; //Text Color
		$borderless_accent_color = isset( $options['accent_color'] ) ? $options['accent_color'] : '#3379fc'; //Accent Color

		// Picture
		$picture_url = isset($picture) ? wp_get_attachment_image_src( $picture, $picture_size) : '';
		$picture = isset( $picture_url[0] ) ? $picture_url[0] : vc_asset_url( 'vc/no_image.png' );

		// Target Blank
		$open_in_new_window = isset($open_in_new_window) ? 'target="_blank"' : '';
		$add_nofollow = isset($add_nofollow) ? 'rel="nofollow"' : '';

		// Color
		if ($color == 'primary_color') {
			$color = 'style="color:'.$borderless_primary_color.';"';
		} else if ($color == 'secondary_color') {
			$color = 'style="color:'.$borderless_secondary_color.';"';
		} else {
			$color = isset($custom_color) ? 'style="color:'.$custom_color.';"' : 'style="color:'.$borderless_primary_color.';"';
		}
		 

		// Default Extra Class, CSS and CSS animation
		$css = isset( $atts['css'] ) ? $atts['css'] : '';
		$el_id = isset( $atts['el_id'] ) ? 'id="' . esc_attr( $el_id ) . '"' : '';
		$el_class = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
		if ( '' !== $css_animation ) {
			wp_enqueue_script( 'waypoints' );
			$css_animation_style = ' wpb_animate_when_almost_visible wpb_' . $css_animation;
		}
		$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

		// Output
		$output .= '<div '.$el_id.' class="borderless-wpbakery-team-member '.$css_class.'">';
		$output .= '<img class="borderless-wpbakery-team-member-picture" src="'.$picture.'" >';

		$output .= '<div class="borderless-wpbakery-team-content">';
		
		$output .= isset($name) ? '<'.$name_tag.'>'.$name.'</'.$name_tag.'>' : '';

		$output .= isset($job_position) ? '<'.$job_position_tag.'>'.$job_position.'</'.$job_position_tag.'>' : '';

		$output .= '<ul class="borderless-wpbakery-team-member-social-profiles">';
		
		$output .= isset($behance) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$behance.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Behance"><i class="vi vi-behance"></i></a></li>' : '';
		
		$output .= isset($dribbble) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$dribbble.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Dribbble"><i class="vi vi-dribbble"></i></a></li>' : '';
		
		$output .= isset($facebook) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$facebook.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Facebook"><i class="vi vi-facebook"></i></a></li>' : '';
		
		$output .= isset($github) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$github.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Github"><i class="vi vi-github"></i></a></li>' : '';
		
		$output .= isset($instagram) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$instagram.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Instagram"><i class="vi vi-instagram"></i></a></li>' : '';

		$output .= isset($linkedin) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$linkedin.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Linkedin"><i class="vi vi-linkedin"></i></a></li>' : '';

		$output .= isset($medium) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$medium.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Medium"><i class="vi vi-medium"></i></a></li>' : '';

		$output .= isset($pinterest) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$pinterest.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Pinterest"><i class="vi vi-pinterest"></i></a></li>' : '';

		$output .= isset($reddit) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$reddit.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Reddit"><i class="vi vi-reddit"></i></a></li>' : '';

		$output .= isset($snapchat) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$snapchat.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Snapchat"><i class="vi vi-snapchat"></i></a></li>' : '';

		$output .= isset($tiktok) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$tiktok.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Tiktok"><i class="vi vi-tiktok"></i></a></li>' : '';

		$output .= isset($twitch) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$twitch.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Twitch"><i class="vi vi-twitch"></i></a></li>' : '';

		$output .= isset($twitter) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$twitter.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Twitter"><i class="vi vi-twitter"></i></a></li>' : '';

		$output .= isset($vimeo) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$vimeo.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Vimeo"><i class="vi vi-vimeo"></i></a></li>' : '';

		$output .= isset($wechat) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$wechat.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Wechat"><i class="vi vi-wechat"></i></a></li>' : '';

		$output .= isset($whatsapp) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$whatsapp.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Whatsapp"><i class="vi vi-whatsapp"></i></a></li>' : '';

		$output .= isset($youtube) ? '<li class="borderless-wpbakery-team-member-social-profile"><a href="'.$youtube.'" '.$open_in_new_window.' '.$add_nofollow.' '.$color.' title="Youtube"><i class="vi vi-youtube"></i></a></li>' : '';

		$output .= '</ul>';

		$output .= isset($description) ? '<p>'.$description.'</p>' : '';

		$output .= '</div>';

		$output .= '</div>';
		
		return $output;
	}
}

return array(
	'name' => __( 'Team Member', 'borderless' ),
	'base' => 'borderless_wpbakery_team_member',
	'icon' => plugins_url('../images/team-member.png', __FILE__),
	'show_settings_on_create' => true,
	'category' => __( 'Borderless', 'borderless' ),
	'description' => __( 'Create a stylish team member with the impressive options', 'borderless' ),
	'params' => array(

		array(
			'type' => 'attach_image',
			'heading' => __( 'Picture', 'borderless' ),
			'param_name' => 'picture',
			'description' => __( 'Upload team member picture.', 'borderless' ),
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Picture Size', 'borderless' ),
			'param_name' => 'picture_size',
			'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'borderless' ),
		),


		/*-----------------------------------------------------------------------------------*/
		/*	Content
		/*-----------------------------------------------------------------------------------*/

		array(
			'type' => 'textfield',
			'heading' => __( 'Name', 'borderless' ),
			'param_name' => 'name',
			'group' => 'Content',
			'edit_field_class' => 'vc_col-sm-8',
		),

		array(
			'type'             => 'dropdown',
			'class'            => '',
			'heading'          => __( 'Tag', 'borderless' ),
			'param_name'       => 'name_tag',
			'value'            => array(
				__( 'Default', 'borderless' )  => 'h3',
				__( 'H1', 'borderless' )  => 'h1',
				__( 'H2', 'borderless' )  => 'h2',
				__( 'H4', 'borderless' )  => 'h4',
				__( 'H5', 'borderless' )  => 'h5',
				__( 'H6', 'borderless' )  => 'h6',
				__( 'Div', 'borderless' )  => 'div',
				__( 'p', 'borderless' )  => 'p',
				__( 'span', 'borderless' )  => 'span',
			),
			'description'      => __( 'Default is H3', 'borderless' ),
			'group'            => 'Content',
			'edit_field_class' => 'vc_col-sm-4 borderless-wpbakery-remove-padding',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Job Position', 'borderless' ),
			'param_name' => 'job_position',
			'group' => 'Content',
			'edit_field_class' => 'vc_col-sm-8',
		),

		array(
			'type'             => 'dropdown',
			'class'            => '',
			'heading'          => __( 'Tag', 'borderless' ),
			'param_name'       => 'job_position_tag',
			'value'            => array(
				__( 'Default', 'borderless' )  => 'h4',
				__( 'H1', 'borderless' )  => 'h1',
				__( 'H2', 'borderless' )  => 'h2',
				__( 'H3', 'borderless' )  => 'h3',
				__( 'H5', 'borderless' )  => 'h5',
				__( 'H6', 'borderless' )  => 'h6',
				__( 'Div', 'borderless' )  => 'div',
				__( 'p', 'borderless' )  => 'p',
				__( 'span', 'borderless' )  => 'span',
			),
			'description'      => __( 'Default is H4', 'borderless' ),
			'group'            => 'Content',
			'edit_field_class' => 'vc_col-sm-4',
		),

		array(
			'type' => 'textarea',
			'heading' => __( 'Description', 'borderless' ),
			'holder' => 'div',
			'param_name' => 'description',
			'group' => 'Content',
		),


		/*-----------------------------------------------------------------------------------*/
		/*	Social Profiles
		/*-----------------------------------------------------------------------------------*/

		array(
			'type' => 'textfield',
			'heading' => __( 'Behance', 'borderless' ),
			'param_name' => 'behance',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Dribbble', 'borderless' ),
			'param_name' => 'dribbble',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Facebook', 'borderless' ),
			'param_name' => 'facebook',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Github', 'borderless' ),
			'param_name' => 'github',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Instagram', 'borderless' ),
			'param_name' => 'instagram',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Linkedin', 'borderless' ),
			'param_name' => 'linkedin',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Medium', 'borderless' ),
			'param_name' => 'medium',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Pinterest', 'borderless' ),
			'param_name' => 'pinterest',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Reddit', 'borderless' ),
			'param_name' => 'reddit',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Snapchat', 'borderless' ),
			'param_name' => 'snapchat',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'TikTok', 'borderless' ),
			'param_name' => 'tiktok',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Twitch', 'borderless' ),
			'param_name' => 'twitch',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Twitter', 'borderless' ),
			'param_name' => 'twitter',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Vimeo', 'borderless' ),
			'param_name' => 'vimeo',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'WeChat', 'borderless' ),
			'param_name' => 'wechat',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'WhatsApp', 'borderless' ),
			'param_name' => 'whatsapp',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'textfield',
			'heading' => __( 'Youtube', 'borderless' ),
			'param_name' => 'youtube',
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'checkbox',
			'heading' => __( 'Open in new window', 'borderless' ),
			'param_name' => 'open_in_new_window',
			'value' => __( '', 'borderless' ),
			'group' => 'Social Profiles',
		),

		array(
			'type' => 'checkbox',
			'heading' => __( 'Add nofollow', 'borderless' ),
			'param_name' => 'add_nofollow',
			'value' => __( '', 'borderless' ),
			'group' => 'Social Profiles',
		),


		/*-----------------------------------------------------------------------------------*/
		/*	Style
		/*-----------------------------------------------------------------------------------*/

		array(
			'type'             => 'dropdown',
			'class'            => '',
			'heading'          => __( 'Color', 'borderless' ),
			'param_name'       => 'color',
			'value'            => array(
				__( 'Primary Color', 'borderless' )  => 'primary_color',
				__( 'Secondary Color', 'borderless' )  => 'secondary_color',
				__( 'Custom Color', 'borderless' )  => 'custom_color',
			),
			'description'      => __( 'Default is Primary Color', 'borderless' ),
			'group'            => 'Style',
		),

		array(
			'type' => 'colorpicker',
			'heading' => __( 'Custom Color', 'borderless' ),
			'param_name' => 'custom_color',
			'description' => __( 'Select custom color.', 'borderless' ),
			'group'            => 'Style',
			'dependency' => array(
				'element' => 'color',
				'value' => array( 'custom_color' ),
			),
		),
		
		// Animation
		vc_map_add_css_animation(),
		
		array(
			'type' => 'el_id',
			'heading' => __( 'Element ID', 'borderless' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to %sw3c specification%s).', 'borderless' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>' ),
			),
			
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'borderless' ),
				'param_name' => 'el_class',
				'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'borderless' ),
			),
			
			array(
				'type' => 'css_editor',
				'heading' => __( 'CSS box', 'borderless' ),
				'param_name' => 'css',
				'group' => __( 'Design Options', 'borderless' ),
			),
		),
	);
	