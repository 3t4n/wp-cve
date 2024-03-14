<?php
if ( ! class_exists( 'WPAI_WP_Residence_Property_Fields' ) ) {
    class WPAI_WP_Residence_Property_Fields {
        
        protected $add_on;

        public $helper;

        public $theme_version;

        public function __construct( RapidAddon $addon_object ) {
            $this->add_on = $addon_object;
            $this->helper = new WPAI_WP_Residence_Add_On_Helper();
            $this->theme_version = $this->helper->get_theme_version();
        }

        public function add_field( $field_type ) {
            switch( $field_type ) {
                case 'property_images':
                    $this->image_field();
                    break;

                case 'text_image_custom_details':
                    $this->text_image_custom_details();
                    break;

                case 'property_location':
                    $this->property_location();
                    break;

                case 'advanced_options':
                    $this->advanced_options();
                    break;
            }
        }

        public function image_field() {
            $this->add_on->disable_default_images();
            $this->add_on->import_images( 'property_images', 'Property Images' );
        }

        public function text_image_custom_details() {
            $this->add_on->add_field( 'property_price', 'Price', 'text', null, 'Only digits, example: 435000' );
            $this->add_on->add_field( 'property_year_tax', 'Yearly Tax Rate', 'text' );
            $this->add_on->add_field( 'property_hoa', 'Homeowners Association Fee (monthly)', 'text' );
            $this->add_on->add_field( 'property_label_before', 'Before Price Label', 'text', null, 'Example: Per Month' );
            $this->add_on->add_field( 'property_label', 'After Price Label', 'text', null, 'Example: Per Month' );
            $this->add_on->add_field( 'property_size', 'Size', 'text' );
            $this->add_on->add_field( 'property_lot_size', 'Lot Size', 'text' );
            $this->add_on->add_field( 'property_rooms', 'Rooms', 'text' );
            $this->add_on->add_field( 'property_bedrooms', 'Bedrooms', 'text' );
            $this->add_on->add_field( 'property_bathrooms', 'Bathrooms', 'text' );            
            $this->add_on->add_field( 'energy_index', 'Energy Index in kWh/m2a', 'text');
            
            $energy_options = array(
                'A+' => 'A+',
                'A' => 'A',
                'B' => 'B',
                'C' => 'C',
                'D' => 'D',
                'E' => 'E',
                'F' => 'F',
                'G' => 'G'
            );

            $this->add_on->add_field( 'energy_class', 'Energy Class', 'radio', $energy_options );
            
            if ( version_compare( $this->theme_version, '2.0', '<' ) ) {
                $this->add_on->add_field( 'property_features', 'Features and Amenities', 'text', null, 'Comma delimited list of features and amenities' );
            }            
            
            $this->add_on->add_title( 'Property Subunits' );

            $subunits_options = array(
                'no' => 'No',
                'yes' => array(
                    'Yes',
                    $this->add_on->add_field( 'property_subunits_list', 'Subunit IDs', 'text', null, 'Comma delimited list of subunit IDs' )
                )
            );

            $this->add_on->add_field( 'enable_subunits', 'Enable Subunits?', 'radio', $subunits_options );
            
            $this->add_on->add_title( 'Custom Property Details' );
            $this->add_on->add_text( 'To edit existing and add new custom property details go to Appearance > WP Residence Options > Listings Customs Fields. Make sure that the imported values conform to the requirements of the selected field type designated on the WP Residence options page.' );

            // New option that contains custom fields in WP Residence 1.50.1 +
            $custom_details = get_option( 'wpestate_custom_fields_list' );
            $custom_details = ( empty( $custom_details ) ? array() : $custom_details );

            if ( ! empty( $custom_details ) ) {
                $custom_details = maybe_unserialize( $custom_details );

                if ( array_key_exists( 'add_field_name', $custom_details ) ) {
                    foreach ( $custom_details['add_field_name'] as $cd_key => $cd ) {
                        $key = str_replace( ' ', '-', $cd );
                        $label = ( array_key_exists( $cd_key, $custom_details['add_field_label'] ) ? $custom_details['add_field_label'][ $cd_key ] : $cd );
                        $this->add_on->add_field( '_custom_details_' . $key, $label, 'text' );
                    }
                }
            } else {
                // get all the custom details
                $custom_details = get_option( 'wp_estate_custom_fields' );
                $custom_details = ( empty( $custom_details ) ? array() : $custom_details );

                if ( !empty( $custom_details ) ) {
                    // build the key array for the UI and the field array for the actual import
                    foreach ($custom_details as $custom_detail) {
                        $key = $custom_detail[0];
                        $key = str_replace( ' ', '-', $key );
                        $label = $custom_detail[1] . ' (' . $custom_detail[2] . ')';
                        $this->add_on->add_field( '_custom_details_' . $key, $label, 'text' );
                    }
                }
            }

            $this->add_on->add_title( 'Property Video' );

            $embed_options = array(
                'youtube' => 'YouTube',
                'vimeo' => 'Vimeo'
            );

            $this->add_on->add_field( 'embed_video_type', 'Video from:', 'radio', $embed_options );
            $this->add_on->add_field( 'embed_video_id', 'Embed Video ID', 'text', null, 'Embed ID from http://www.youtube.com/watch?v=dQw4w9WgXcQ would be: dQw4w9WgXcQ' );
            $this->add_on->add_field( 'property_custom_video', 'Video Placeholder Image', 'image' );
            $this->add_on->add_field( 'embed_virtual_tour', 'Virtual Tour', 'text' );

            $this->add_on->add_title( 'Property Location' );
            $this->add_on->add_field( 'property_address', 'Address', 'text', null, 'Building number and street name, example: 1206 King St' );
            $this->add_on->add_field( 'property_zip', 'Zip', 'text' );
            $this->add_on->add_field( 'property_country', 'Country', 'text' );
        }

        public function property_location() {
            $this->add_on->add_field(
                'location_settings',
                'Map Location',
                'radio', 
                array(
                    'search_by_address' => array(
                        'Search by Address',
                            $this->add_on->add_field(
                                '_property_location_search',
                                'Property Address',
                                'text'
                            )
                    ), // end Search by Address radio field
                    'search_by_coordinates' => array(
                        'Enter Coordinates',
                        $this->add_on->add_field(
                            '_property_latitude', 
                            'Latitude', 
                            'text', 
                            null, 
                            'Example: 34.0194543'
                        ),
                        $this->add_on->add_field(
                            '_property_longitude',
                            'Longitude',
                            'text',
                            null, 
                            'Example: -118.4911912'
                        ) // end coordinates Option panel
                    ) // end Search by Coordinates radio field
                ) // end Property Location radio field
            );

            $this->add_on->add_options( null, 'Google Geocode API Settings', array(
                    $this->add_on->add_field(
                        'address_geocode',
                        'Request Method',
                        'radio',
                        array(
                            'address_google_developers' => array(
                                'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Get free API key</a>',
                                $this->add_on->add_field(
                                    'address_google_developers_api_key', 
                                    'API Key', 
                                    'text'
                                ),
                                'Up to 2,500 requests per day and 5 requests per second.'
                            ),
                            'address_google_for_work' => array(
                                'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business">Sign up for Google for Work</a>',
                                $this->add_on->add_field(
                                    'address_google_for_work_client_id', 
                                    'Google for Work Client ID', 
                                    'text'
                                ), 
                                $this->add_on->add_field(
                                    'address_google_for_work_digital_signature', 
                                    'Google for Work Digital Signature', 
                                    'text'
                                ),
                                'Up to 100,000 requests per day and 10 requests per second'
                            )
                        ) // end Request Method options array
                    ) // end Request Method nested radio field 
                )
            );
        }

        public function advanced_options() {
            $advanced_options = array(
                $this->add_on->add_field( 'property_agent', 'Agent/Agency Responsible', 'text', null, 'Match by Agent/Agency name. If no match found, a new Agent/Agency will be created.' ),
                $this->add_on->add_field( 'property_agent_or_agency', 'If no Agent/Agency found, WP All Import should auto-create a new:', 'radio', array(
                    'estate_agent' => 'Agent',
                    'estate_agency' => 'Agency'
                ) ),    
                $this->add_on->add_field( 'property_agent_secondary', 'Secondary Agents', 'text', null, 'Match by Agent name. Separate multiple agents with commas. If existing ones are not found, new ones will be created.' ),    
                $this->add_on->add_field( 'property_user', 'Assign Property to User', 'text', null, 'Match by user ID, email, login, or slug' ),    
                $this->add_on->add_field( 'property_google_view', 'Enable Google Street View', 'radio', array(
                    '1' => 'Yes',
                    '' => 'No'
                ) ),    
                $this->add_on->add_field( 'google_camera_angle', 'Google Street View Camera Angle', 'text'),    
                $this->add_on->add_field( 'page_custom_zoom', 'Zoom Level for map (1-20)', 'text'),    
                'property_status' => null,    
                $this->add_on->add_field( 'prop_featured', 'Featured Property', 'radio',
                    array(
                        '0' => 'No',
                        '1' => 'Yes'
                ) ),    
                $this->add_on->add_field( 'property_theme_slider', 'Property in theme Slider', 'radio',
                    array(
                        '0' => 'No',
                        '1' => 'Yes'
                ) ),    
                $this->add_on->add_field( 'owner_notes', 'Owner/Agent Notes', 'text', null, 'Not visible on the front end' ),    
                $this->add_on->add_field( 'post_show_title', 'Show Title', 'radio',
                    array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    ) ),    
                $this->add_on->add_field( 'property_page_desing_local', 'Use a custom property page template', 'text', null, 'Must be template title, slug, or ID' ),    
                $this->add_on->add_field( 'header_type', 'Header Type', 'radio', 	
                    array(
                        '0' => 'Global',
                        '1' => 'None',
                        '2' => array(
                            'Image',
                            $this->add_on->add_field( 'page_custom_image', 'Header Image', 'image' ),
                            $this->add_on->add_options( null, 'Additional Image Fields', array(
                                $this->add_on->add_field( 'page_header_image_full_screen', 'Full Screen?', 'radio', array(
                                    'yes' => 'Yes',
                                    'no'  => 'No'
                                ) ),
                                $this->add_on->add_field( 'page_header_image_back_type', 'Full Screen Background Type?', 'radio', array(
                                    'cover' => 'Cover',
                                    'contain' => 'Contain'
                                ) ),
                                $this->add_on->add_field( 'page_header_title_over_image', 'Title Over Image', 'text' ),
                                $this->add_on->add_field( 'page_header_subtitle_over_image', 'SubTitle Over Image', 'text' ),
                                $this->add_on->add_field( 'page_header_image_height', 'Image Height', 'text', null, 'Example: <em>700</em>. Default: <em>580</em>px' ),
                                $this->add_on->add_field( 'page_header_overlay_color', 'Overlay Color', 'text', null, 'Example: 48ba92'),
                                $this->add_on->add_field( 'page_header_overlay_val', 'Overlay Opacity', 'text', null, 'Between 0 and 1. Example: <em>0.5</em>. Default: <em>0.6</em>' )
                            ) )
                        ),
                        '3' => 'Theme Slider',
                        '4' => array(
                            'Revolution Slider',
                            $this->add_on->add_field( 'rev_slider', 'Revolution Slider Name', 'text' ),
                        ),
                        '5' => array(
                            'Google Map',
                            $this->add_on->add_field( 'min_height', 'Map height when closed', 'text', null, 'In pixels, example: 200' ),
                            $this->add_on->add_field( 'max_height', 'Map height when open', 'text', null, 'In pixels, example: 600' ),
                            $this->add_on->add_field( 'keep_min', 'Force the map closed', 'radio', array(
                                'no' => 'No',
                                'yes' => 'Yes'	
                            ) )
                        ),
                        '6' => array(
                            'Video Header',
                            $this->add_on->add_field( 'page_custom_video', 'Video MP4 version', 'file' ),
                            $this->add_on->add_field( 'page_custom_video_webbm', 'Video WEBM version', 'file' ),
                            $this->add_on->add_field( 'page_custom_video_ogv', 'Video OGV version', 'file' ),
                                $this->add_on->add_options( null, 'Additional Video Options', array(
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
                            ) )
                        )
                ) ),    
                $this->add_on->add_field( 'header_transparent', 'Use transparent header?', 'radio', 
                array(
                    'global' => 'Global',
                    'no' => 'No',
                    'yes' => 'Yes'
                ) ),
                $this->add_on->add_field( 'page_show_adv_search', 'Show Advanced Search?', 'radio', array(
                    'global' => 'Global',
                    'no' => 'No',
                    'yes' => 'Yes' 
                ) ),
                $this->add_on->add_field( 'page_use_float_search', 'Use Float Search Form?', 'radio', array(
                    'global' => 'Global',
                    'no' => 'No',
                    'yes' => 'Yes' 
                ) ),
                $this->add_on->add_field( 'page_wp_estate_float_form_top', 'Distance in % between search form and the top margin.', 'text' ),    
                $this->add_on->add_field( 'sidebar_agent_option', 'Show Agent in Sidebar', 'radio', 
                array(
                    'global' => 'Global',
                    'no' => 'No',
                    'yes' => 'Yes'
                ) ),    
                $this->add_on->add_field( 'local_pgpr_slider_type', 'Slider Type', 'radio', 
                array(
                    'global' => 'Global',
                    'vertical' => 'Vertical',
                    'horizontal' => 'Horizontal'
                ) ),    
                $this->add_on->add_field( 'local_pgpr_content_type', 'Show Content As', 'radio', 
                array(
                    'global' => 'Global',
                    'accordion' => 'Accordion',
                    'tabs' => 'Tabs'
                ) ),    
                $this->add_on->add_field( 'sidebar_option', 'Where to Show the Sidebar', 'radio', 
                array(
                    'right' => 'Right',
                    'left' => 'Left',
                    'none' => 'None'
                ) ),    
                $this->add_on->add_field( 'sidebar_select', 'Select the Sidebar', 'radio', 
                array(
                    'primary-widget-area' => 'Primary Widget Area',
                    'secondary-widget-area' => 'Secondary Widget Area',
                    'first-footer-widget-area' => 'First Footer Widget Area',
                    'second-footer-widget-area' => 'Second Footer Widget Area',
                    'third-footer-widget-area' => 'Third Footer Widget Area',
                    'fourth-footer-widget-area' => 'Fourth Footer Widget Area',
                    'top-bar-left-widget-area' => 'Top Bar Left Widget Area',
                    'top-bar-right-widget-area' => 'Top Bar Right Widget Area'
                ) )   
            );

            if ( version_compare( $this->theme_version, '2.0', '<' ) ) {
                $advanced_options['property_status'] = $this->add_on->add_field( 'property_status', 'Property Status', 'text' );
            } else {
                unset( $advanced_options['property_status'] );
            }

            $this->add_on->add_options( null, 'Advanced Options', $advanced_options );            
        }
    }
}
