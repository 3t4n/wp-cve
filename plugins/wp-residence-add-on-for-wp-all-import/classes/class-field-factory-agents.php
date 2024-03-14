<?php
if ( ! class_exists( 'WPAI_WP_Residence_Agents_Field_Factory' ) ) {	
	class WPAI_WP_Residence_Agents_Field_Factory {
		
		protected $add_on;
		
		public function __construct( RapidAddon $addon_object ) {
			$this->add_on = $addon_object;
		}
		
		public function add_field( $field_type ) {
			switch ( $field_type ) {
				case 'agent_text_fields':
					$this->agent_text_fields();
				break;
				
				case 'agent_options_field':
					$this->agent_options_field();
				break;
			}
		}
		
		public function agent_text_fields() {            
			$text_fields = array(
				'first_name'        => 'Agent First Name',
				'last_name'         => 'Agent Last Name',
				'agent_position'    => 'Position',
				'agent_email'       => 'Email',
				'agent_phone'       => 'Phone',
				'agent_mobile'      => 'Mobile',
				'agent_skype'       => 'Skype',
				'agent_member'      => 'Member of',
				'agent_facebook'    => 'Facebook',
				'agent_twitter'     => 'Twitter',
				'agent_linkedin'    => 'LinkedIn',
				'agent_pinterest'   => 'Pinterest',
				'agent_instagram'   => 'Instagram',
				'agent_website'     => 'Website (without http)',
				'user_meda_id'      => 'The user id for this profile',
				'owner_author_id'   => 'The Agency id/Developer USER ID that has this agent'
			);
			
			foreach ( $text_fields as $key => $field ) {
				$this->add_on->add_field( $key, $field, 'text' );
			}
			
		}
		
		public function agent_options_field() {
			$options = array(
				$this->add_on->add_field( 'header_type', 'Header Type', 'radio', array(
					'0' => 'Global',
					'1' => 'None',
					'2' => array(
						'Image',
						$this->add_on->add_field( 'page_custom_image', 'Header Image', 'image' ),
						$this->add_on->add_options( null, 'Additional Image Fields', array(
							$this->add_on->add_field( 'page_header_image_full_screen', 'Full Screen?', 'radio', array(
								'no'  => 'No',
								'yes' => 'Yes'
							) ),
							$this->add_on->add_field( 'page_header_image_back_type', 'Full Screen Background Type?', 'radio', array(
								'cover'   => 'Cover',
								'contain' => 'Contain'
							) ),
							$this->add_on->add_field( 'page_header_title_over_image', 'Title over image', 'text' ),
							$this->add_on->add_field( 'page_header_subtitle_over_image', 'SubTitle Over Image', 'text' ),
							$this->add_on->add_field( 'page_header_image_height', 'Image Height', 'text', null, 'Example: <em>700</em>. Default: <em>580px</em>.' ),
							$this->add_on->add_field( 'page_header_overlay_color', 'Overlay Color', 'text', null, 'Hex color code without #.' ),
							$this->add_on->add_field( 'page_header_overlay_val', 'Overlay Opacity', 'text', null, 'Between 0 and 1. Example: <em>0.5</em>. Default: <em>0.6</em>.' )
						)
					) ),
					'3' => 'Theme Slider',
					'4' => array(
					'Revolution Slider',
						$this->add_on->add_field( 'rev_slider', 'Revolution Slider Name', 'text' )
					),
					'5' => array(
						'Google Map',
						$this->add_on->add_field( 'page_custom_lat', 'Map - Center point Latitude', 'text' ),
						$this->add_on->add_field( 'page_custom_long', 'Map - Center point Longitude', 'text' ),
						$this->add_on->add_field( 'page_custom_zoom', 'Zoom Level for map (1-20)', 'text' ),
						$this->add_on->add_field( 'min_height', 'Height of the map when closed', 'text', null, 'In pixels, example: 200' ),
						$this->add_on->add_field( 'max_height', 'Height of map when open', 'text', null, 'In pixels, example: 600' ),
						$this->add_on->add_field( 'keep_min', 'Force the map closed', 'radio', array(
							'no'  => 'No',
							'yes' => 'Yes'
						) ),
						$this->add_on->add_field( 'keep_max', 'Force Google Map at the full screen size?', 'radio', array(
							'no'   => 'No',
							'yes'  => 'Yes'
						) )
					),
					'6' => array(
						'Video Header',
						$this->add_on->add_field( 'page_custom_video', 'Video MP4 version', 'file' ),
						$this->add_on->add_field( 'page_custom_video_webbm', 'Video WEBM version', 'file' ),
						$this->add_on->add_field( 'page_custom_video_ogv', 'Video OGV version', 'file' ),
						$this->add_on->add_field( 'page_custom_video_cover_image', 'Cover Image', 'image' ),
						$this->add_on->add_field( 'page_header_video_full_screen', 'Full Screen?', 'radio', array(
							'no'  => 'No',
							'yes' => 'Yes'
						) ),
						$this->add_on->add_field( 'page_header_title_over_video', 'Title Over Image', 'text' ),
						$this->add_on->add_field( 'page_header_subtitle_over_video', 'SubTitle Over Image', 'text' ),
						$this->add_on->add_field( 'page_header_video_height', 'Video Height', 'text', null, 'Example: <em>700</em>. Default: <em>580px</em>' ),
						$this->add_on->add_field( 'page_header_overlay_color_video', 'Overlay Color', 'text', null, 'Hex color code without #.' ),
						$this->add_on->add_field( 'page_header_overlay_val_video', 'Overlay Opacity', 'text', null, 'Between 0 and 1. Example: <em>0.5</em>. Default: <em>0.6</em>.' ),
					)
				) ),
				$this->add_on->add_field( 'header_transparent', 'Use transparent header', 'radio', array(
					'global' => 'Global',
					'no'     => 'No',
					'yes'    => 'Yes'
				) ),
				$this->add_on->add_field( 'page_show_adv_search', 'Show Advanced Search?', 'radio', array(
					'global' => 'Global',
					'no'     => 'No',
					'yes'    => 'Yes'
				) ),
				$this->add_on->add_field( 'page_use_float_search', 'Use Float Search Form?', 'radio', array(
					'global' => 'Global',
					'no'     => 'No',
					'yes'    => 'Yes'
				) ),
				$this->add_on->add_field( 'page_wp_estate_float_form_top', 'Distance in % between search form and the top margin.', 'text' ),
				$this->add_on->add_field( 'sidebar_option', 'Where to Show the Sidebar', 'radio', 
				array(
					'right' => 'Right',
					'left'  => 'Left',
					'none'  => 'None'
				) ),
				$this->add_on->add_field( 'sidebar_select', 'Select the Sidebar', 'radio', array(
					'primary-widget-area'       => 'Primary Widget Area',
					'secondary-widget-area'     => 'Secondary Widget Area',
					'first-footer-widget-area'  => 'First Footer Widget Area',
					'second-footer-widget-area' => 'Second Footer Widget Area',
					'third-footer-widget-area'  => 'Third Footer Widget Area',
					'fourth-footer-widget-area' => 'Fourth Footer Widget Area',
					'top-bar-left-widget-area'  => 'Top Bar Left Widget Area',
					'top-bar-right-widget-area' => 'Top Bar Right Widget Area'
				) )
			);

			$this->add_on->add_options( null, 'Appearance Options', $options );
		}
	}
}
