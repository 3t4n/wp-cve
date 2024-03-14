<?php
if ( ! class_exists( 'WPAI_WP_Residence_Agents_Importer' ) ) {
    class WPAI_WP_Residence_Agents_Importer extends WPAI_WP_Residence_Add_On_Importer {

		protected $add_on;
		public $helper;

        public function __construct( RapidAddon $addon_object ) {
			$this->add_on = $addon_object;
			$this->helper = new WPAI_WP_Residence_Add_On_Helper();
        }

        public function import_text_fields( $post_id, $data, $import_options, $article ) {
            $fields = array(
				'first_name',
				'last_name',
				'agent_position',
				'agent_email',
				'agent_phone',
				'agent_mobile',
				'agent_skype',
				'agent_facebook',
				'agent_twitter',
				'agent_linkedin',
				'agent_pinterest',
				'agent_instagram',
				'agent_website',
				'header_type',
				'header_transparent',
				'rev_slider',
				'min_height',
				'max_height',
				'keep_min',
				'keep_max',
				'sidebar_select',
				'sidebar_option',
				'page_custom_lat',
				'page_custom_long',
				'page_header_image_full_screen',
				'page_header_title_over_image',
				'page_header_image_back_type',
				'page_header_subtitle_over_image',
				'page_header_image_height',
				'page_header_overlay_color',
				'page_header_overlay_val',
				'agent_member',
				'user_meda_id',
				'page_header_video_full_screen',
				'page_header_title_over_video',
				'page_header_subtitle_over_video',
				'page_header_video_height',
				'page_header_overlay_color_video',
				'page_header_overlay_val_video',
				'header_transparent',
				'page_show_adv_search',
				'page_use_float_search',
				'page_wp_estate_float_form_top',
            );

            foreach ( $fields as $field ) {

		        if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field, $import_options ) ) {

		        	$this->helper->update_meta( $post_id, $field, $data[ $field ], 'post' );

		        }

			}
			
			// Video fields and image field
			$fields = array(
				'page_custom_video',
				'page_custom_video_webbm',
				'page_custom_video_ogv',
				'page_custom_video_cover_image'
			);

			foreach ( $fields as $field ) {
				if ( ! array_key_exists( $field, $data ) ) {
					continue;
				}
				if ( ! empty( $article['ID'] || $this->add_on->can_update_meta( $field, $import_options ) ) ) {
					$id = $data[ $field ]['attachment_id'];
					$url = wp_get_attachment_url( $id );
					$this->helper->update_meta( $post_id, $field, $url, 'post' );
				}
			}

            // Page custom image
            $fields = array( 'page_custom_image' );

		    foreach ( $fields as $field ) {

	            if ( empty( $article['ID'] ) || $this->add_on->can_update_image( $import_options ) ) {

	                $id = $data[ $field ]['attachment_id'];

	                $url = wp_get_attachment_url( $id );

	                $this->helper->update_meta( $post_id, $field, $url, 'post' );

	            }

            }

            // set map zoom
		    $field = 'page_custom_zoom';

		    if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field, $import_options ) ) {

		    	$zoom = ( empty( $data[$field] ) ? 15 : $data[$field] );

		        $this->helper->update_meta( $post_id, $field, $zoom, 'post' );

		    }
			
			// Import the post author
            $field = 'owner_author_id';

			if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field, $import_options ) ) {
				
				if ( is_numeric( $data[ $field ] ) ) {					
					$args = array(
						'ID' => $post_id,
						'post_author' => $data[ $field ]
					);			
				
					$update = wp_update_post( $args );
					if ( is_wp_error( $update ) ) {
						$this->helper->log( '<strong>WP Residence Add-On:</strong> Failed to assign ID ' . $data[ $field ] . ' as post author. Error: ' . $update->get_error_message() );
					} else {
						$this->helper->log( '<strong>WP Residence Add-On:</strong> Successfully assigned ID ' . $data[ $field ] . ' as post author' );
					}
				}
			}            
        }
    }
}
