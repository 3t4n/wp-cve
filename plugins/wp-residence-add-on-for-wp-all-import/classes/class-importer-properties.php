<?php
if ( ! class_exists( 'WPAI_WP_Residence_Property_Importer' ) ) {
    class WPAI_WP_Residence_Property_Importer extends WPAI_WP_Residence_Add_On_Importer {

        protected $add_on;
        public $helper;
        public $logger = null;

        public function __construct( RapidAddon $addon_object ) {
            $this->add_on = $addon_object;
            $this->helper = new WPAI_WP_Residence_Add_On_Helper();
        }

        public function import_text_image_custom_details( $post_id, $data, $import_options, $article ) {
            $fields = array(
                'property_price',
                'property_year_tax',
                'property_hoa',
                'property_label_before',
                'property_label',
                'property_size',
                'property_lot_size',
                'property_rooms',
                'property_bedrooms',
                'property_bathrooms',
                'embed_video_type',
                'embed_video_id',
                'property_address',
                'property_zip',
                'property_country',
                'hidden_address',
                'energy_index',
                'energy_class',
                'page_header_image_full_screen',
                'page_header_image_back_type',
                'page_header_title_over_image',
                'page_header_subtitle_over_image',
                'page_header_image_height',
                'page_header_overlay_color',
                'page_header_overlay_val',
                'page_show_adv_search',
                'page_use_float_search',
                'page_wp_estate_float_form_top',
                'embed_virtual_tour',
                'page_header_video_full_screen',
                'page_header_title_over_video',
                'page_header_subtitle_over_video',
                'page_header_video_height',
                'page_header_overlay_color_video',
                'page_header_overlay_val_video'
            );

            // image fields
            $image_fields = array( 
                'page_custom_image',
                'property_custom_video',
                'page_custom_video',
                'page_custom_video_webbm',
                'page_custom_video_ogv',
                'page_custom_video_cover_image'
            );

            $custom_details_fields = $this->get_custom_details_fields();

            $fields = array_merge( $fields, $custom_details_fields, $image_fields );

            // update everything in fields arrays
            foreach ( $fields as $field ) {
                if ( ! array_key_exists( $field, $data ) ) {
                    continue;
                }
                if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( $field, $import_options ) ) {
                    if ( in_array( $field, $image_fields ) ) {
                        if ( empty( $article['ID'] ) or $this->add_on->can_update_image( $import_options ) ) {
                            $id = $data[ $field ]['attachment_id'];
                            $url = wp_get_attachment_url( $id );
                            $this->helper->update_meta( $post_id, $field, $url );
                        }
                    } elseif ( in_array( $field, $custom_details_fields ) ) {
                        $key = substr( $field, 16 );
                        
                        if ( function_exists( 'wpestate_limit45' ) ) $key = wpestate_limit45( sanitize_title( $key ) );
                        
                        $key = strtolower( sanitize_key( $key ) );
                        
                        if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $key, $import_options ) ) {
                            $this->helper->update_meta( $post_id, $key, $data[ $field ] );
                        }
                    } elseif ( $field == 'hidden_address' ) {						
                        /*
                        *  Create a temporary custom field to indicate
                        *   that the 'hidden_address' field is to be updated
                        */
                        $this->helper->update_meta( $post_id, 'temp_is_update_hidden_address', 'yes' );
                    } else {
                        $this->helper->update_meta( $post_id, $field, $data[$field] );
                    }
                }
            }

            $this->import_enable_subunits( $post_id, $data, $import_options, $article );
            $this->import_features( $post_id, $data, $import_options, $article );
            $this->floorplans( $post_id, $data, $import_options, $article );

            // After post save actions
			add_action( 'pmxi_saved_post', array( $this, 'save_in_slider' ), 1, 3 );
			add_action( 'pmxi_saved_post', array( $this, 'update_hidden_address' ), 1, 3 );

			if ( function_exists( 'estate_save_postdata' ) ) {
				add_action( 'pmxi_saved_post', 'estate_save_postdata', 1, 1 );
			}
        }

        public function floorplans( $post_id, $data, $import_options, $article ) {
            $field = 'use_floor_plans';

		    if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( $field, $import_options ) ) {
		        $this->helper->update_meta( $post_id, $field, 0 );
            }
        }

        public function import_enable_subunits( $post_id, $data, $import_options, $article ) {
            $field = 'enable_subunits';

            if ( $data[ $field ] == 'yes' && !empty( $data[ 'property_subunits_list'] ) ) {

                if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( 'property_subunits_list', $import_options ) ) {

                    $this->helper->update_meta( $post_id, 'property_subunits_list', explode( ",", $data['property_subunits_list'] ) );

                }

                if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( 'property_subunits_list_manual', $import_options ) ) {

                    $this->helper->update_meta( $post_id, 'property_subunits_list_manual', $data['property_subunits_list'] );

                }

                if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( 'property_has_subunits', $import_options ) ) {

                    $this->helper->update_meta( $post_id, 'property_has_subunits', '1' );

                }

            } elseif ( $data[ $field ] == 'no' ) {

                if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( 'property_has_subunits', $import_options ) ) {

                    $this->helper->update_meta( $post_id, 'property_has_subunits', '' );

                }

                if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( 'property_subunits_list_manual', $import_options ) ) {

                    $this->helper->update_meta( $post_id, 'property_subunits_list_manual', '' );

                }

            }
        }

        public function import_features( $post_id, $data, $import_options, $article ) {
            // add empty features
		    $fields = explode(',',get_option( 'wp_estate_feature_list' ));
		    
		    $features = array();

		    foreach ($fields as $field) {

		    	if ( !empty( $field ) ) {
			    
			    	$field = trim($field);

			    	$features[] = $field;

			    	$field = sanitize_title(str_replace(' ', '_', $field));

			    	if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( $field, $import_options ) ) {

			            update_post_meta( $post_id, $field, '' );

			        }
			    }
		    }

            if ( array_key_exists( 'property_features', $data ) ) {
                // add imported features
                $fields = explode(',', $data['property_features']);

                $this->helper->log( 'Updating Features and Amenities' );

                foreach ($fields as $field) {

                    $field = trim($field);

                    $field_ = sanitize_title(str_replace(' ', '_', $field));

                    if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( $field_, $import_options ) ) {

                        update_post_meta( $post_id, $field_, 1 );

                        // add new features to features list
                        if ( !in_array( $field, $features ) ) {

                            $this->helper->log( '- <b>WARNING:</b> Existing feature "' . $field . '" not found, adding to database and assigning to property' );

                            $features[] = $field;

                        }
                    }
                }

                // replace wp_estate_feature_list option with features list
                $features = implode(', ', $features);

                update_option( 'wp_estate_feature_list', $features );
            }
        }

        public function import_advanced_options( $post_id, $data, $import_options, $article ) {
            // All text fields
            $fields = array(
                'property_google_view',
                'google_camera_angle',
                'prop_featured',
                'owner_notes',
                'post_show_title',
                'header_type',
                'rev_slider',
                'min_height',
                'max_height',
                'keep_min',
                'header_transparent',
                'sidebar_agent_option',
                'local_pgpr_slider_type',
                'local_pgpr_content_type',
                'sidebar_option',
                'sidebar_select'
            );

            foreach ( $fields as $field ) {
                if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( $field, $import_options ) ) {
                    $this->helper->update_meta( $post_id, $field, $data[$field] );
                }
            }

            // Import page_custom_zoom
            $field = 'page_custom_zoom';

		    if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( $field, $import_options ) ) {
		    	$zoom = ( empty( $data[$field] ) ? 15 : $data[$field] );
		        $this->helper->update_meta( $post_id, $field, $zoom );
            }

            // Import property_theme_slider
            $field = 'property_theme_slider';

            if ( empty ( $article['ID'] ) or $this->add_on->can_update_meta( $field, $import_options ) ) {

                update_option( 'wpresaddon_can_update_slider', '1' );
                
                $this->helper->update_meta( $post_id, 'property_theme_slider', $data[ $field ] );

            }

            // Import custom image
            $fields = array( 'page_custom_image' );

		    if ( empty( $article['ID'] ) or $this->add_on->can_update_image( $import_options ) ) {
		    	foreach ($fields as $field) {
			    	delete_post_meta($post_id, $field);
                }
            }

		    foreach ( $fields as $field ) {

	            if ( empty( $article['ID'] ) or $this->add_on->can_update_image( $import_options ) ) {

	                $id = $data[ $field ]['attachment_id'];

	                $url = wp_get_attachment_url( $id );

	                $this->helper->update_meta( $post_id, $field, $url );

	            }
	        }

            // Import property_page_desing_local
            $this->import_property_page_desing_local( $post_id, $data, $import_options, $article );

            // Import statuses
            $this->import_statuses( $post_id, $data, $import_options, $article );            

            // Import Agents
            $this->import_agent( $post_id, $data, $import_options, $article );
            $this->import_secondary_agent( $post_id, $data, $import_options, $article );

            // Import property user
            $this->import_property_user( $post_id, $data, $import_options, $article );
        }

        public function import_property_user( $post_id, $data, $import_options, $article ) {
            $field = 'property_user';
            $id = false;

			$this->helper->log( 'Assign Property to User' );

			if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( $field, $import_options ) ) {

				$user = get_user_by( 'id', $data[$field] );

				if ( $user === false ) {

					$user = get_user_by( 'slug', $data[$field] );

				}

				if ( $user === false ) {

					$user = get_user_by( 'email', $data[$field] );

				}

				if ( $user === false ) {

					$user = get_user_by( 'login', $data[$field] );

				}

				if ( $user != false ) {

					$id = $user->ID;

				    $this->helper->log( '- User found, assigning property to ' . $user->data->user_nicename );

		            $this->helper->update_meta( $post_id, $field, $id );

				} else {

					$users = get_super_admins();

					if ( $user = get_user_by( 'login', $users[0] ) ) {

						$id = $user->ID;

			    		$this->helper->log( '- <b>WARNING:</b> No user found searching for "'. $data[$field] . '", assigning property to ' . $user->data->user_nicename );

                        $this->helper->update_meta( $post_id, $field, $id );
					}

				}

		        // change author to property_user
		        // in this case $id refers to the property_user $id from above
		        $current_id = wpsestate_get_author( $post_id );
		        
		        if( $id !== false && $current_id != $id ){

		            $post = array(
		                'ID'            => $post_id,
		                'post_author'   => $id
		            );

		            wp_update_post( $post ); 
                }
            }
        }

        public function import_statuses( $post_id, $data, $import_options, $article ) {            
            // Import property status
            $field = 'property_status';
            if ( ! array_key_exists( $field, $data ) ) {
                return;
            }

			if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( $field, $import_options ) ) {
			    $this->helper->log( 'Updating Property Status' );
			    $statuses = explode( ',', get_option( 'wp_estate_status_list' ) );
			    $trimmed_statuses = array();

			    if ( ! empty( $statuses ) ) {

				    foreach ( $statuses as $status ) {

				    	$status = trim($status);
						$trimmed_statuses[] = $status;
						
					}
					
				}

			    $statuses = $trimmed_statuses;

			    if ( in_array( $data[ $field ], $statuses ) ) {
				    $this->helper->log( '- Existing property status found, setting property status to "' . $data[$field] . '"' );
					$this->helper->update_meta( $post_id, $field, $data[ $field ] );
			    } else {
				    $this->helper->log( '- <b>WARNING:</b> Existing property status not found, adding new status "' . $data[$field] . '" and assigning to property' );
					$this->helper->update_meta( $post_id, $field, $data[ $field ] );
					$statuses[] = $data[ $field ];
					$statuses = implode( ', ', $statuses );
					update_option( 'wp_estate_status_list', $statuses );
			    }
            }
        }

        public function import_property_page_desing_local( $post_id, $data, $import_options, $article ) {
            $field = 'property_page_desing_local';

			if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field, $import_options ) ) {
                global $wpdb;

                if ( empty( $data[ $field ] ) ) {
                    $this->helper->update_meta( $post_id, $field, '' );
                } else {
                    $query = $wpdb->prepare( "
                    SELECT * FROM " . $wpdb->posts . " WHERE (
                        `post_title` = '%s' OR
                        `post_name` = '%s' OR
                        `ID` = '%d' ) AND 
                        `post_type` = 'page'
                        ",
                        $data[ $field ],
                        $data[ $field ],
                        $data[ $field ]
                    );

                    $found_posts = $wpdb->get_row( $query );

                    if ( ! empty( $found_posts ) ) {
                        $meta = get_post_meta( $found_posts->ID, '_wp_page_template', true );

                        if ( $meta == 'page_property_design' || $meta == 'page_property_design.php' ) {
                            $this->helper->update_meta( $post_id, $field, $found_posts->ID );
                        }
                    }
                }
            }
        }

        public function import_agent( $post_id, $data, $import_options, $article ) {
            // Agent
            $field = 'property_agent';
            $type = $data['property_agent_or_agency'];
            if ( empty( $type ) || ( $type != 'estate_agent' && $type != 'estate_agency' ) ) {
                $type = 'estate_agent';
            }
            $display_type = 'Agent/Agency';
            $created_type = str_replace( array( 'estate_agent', 'estate_agency' ), array( 'Agent', 'Agency' ), $type );

            $post_type = array( 'estate_agent', 'estate_agency' );

            if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( $field, $import_options ) ) {
                $this->helper->log( 'Assign Property to Responsible ' . $display_type );
                
                if ( empty( $data[ $field ] ) ) {
                    $this->helper->log( ' - <b>WARNING:</b> ' . $display_type . ' field is empty in import. No ' . $display_type . ' will be assigned.' );
                    $this->helper->update_meta( $post_id, $field, null );
                } else {

                    $post = get_page_by_title( $data[ $field ], 'OBJECT', $post_type );

                    if ( !empty( $post ) ) {

                    $this->helper->log( '- Existing ' . $display_type . ' found. ' . $data[$field] . ' is now responsible for this property.' );

                    $this->helper->update_meta( $post_id, $field, $post->ID );

                    } else {                        

                        // insert title and attach to property
                        $postarr = [
                            'post_content'   => '',
                            'post_name'      => $data[ $field ],
                            'post_title'     => $data[ $field ],
                            'post_type'      => $type,
                            'post_status'    => 'publish',
                            'post_excerpt'   => ''
                        ];

                        wp_insert_post( $postarr );

                        $post = get_page_by_title( $data[$field], 'OBJECT', $post_type );
                        $this->helper->log( '- <b>WARNING:</b> Existing ' . $created_type . ' not found. ' . $created_type . ' ' . $data[$field] . ' added and is now responsible for this property.' );
                        $this->helper->update_meta( $post_id, $field, $post->ID );
                    }
                }
            }
        }

        public function import_secondary_agent( $post_id, $data, $import_options, $article ) {
            // Secondary Agent
            $field = 'property_agent_secondary';
            $post_type = 'estate_agent';

            if ( empty( $article['ID'] ) or $this->add_on->can_update_meta( $field, $import_options ) ) {
                $this->helper->log( 'Assign Secondary Agents for Property' );
                
                if ( empty( $data[ $field ] ) ) {
                    $this->helper->log( ' - <b>WARNING:</b> Secondary Agents field is empty in import. No agent will be assigned.' );
                    $this->helper->update_meta( $post_id, $field, null );
                } else {

                    $agents = explode( ',', $data[ $field ] );

                    $all_secondary_agents = array();

                    foreach ( $agents as $agent ) {

                        $agent = trim( $agent );

                        $post = get_page_by_title( $agent, 'OBJECT', $post_type );

                        if ( !empty( $post ) ) {

                            $this->helper->log( '- Existing secondary agent ' . $agent . ' found.' );

                            $all_secondary_agents[] = $post->ID;

                        } else {

                            // insert title and attach to property
                            $postarr = [
                                'post_content'   => '',
                                'post_name'      => $agent,
                                'post_title'     => $agent,
                                'post_type'      => $post_type,
                                'post_status'    => 'publish',
                                'post_excerpt'   => ''
                            ];

                            wp_insert_post( $postarr );

                            $post = get_page_by_title( $agent, 'OBJECT', $post_type );

                            $this->helper->log( '- <b>WARNING:</b> Existing secondary agent ' . $agent . ' not found. Agent created and assigned as a secondary agent for the property.' );

                            $all_secondary_agents[] = $post->ID;

                        }

                    }

                    if ( ! empty( $all_secondary_agents ) ) {
                        $this->helper->update_meta( $post_id, $field, $all_secondary_agents );
                    }
                }
            }
        }

        public function import_property_location( $post_id, $data, $import_options, $article ) {
            $location_importer = new WPAI_WP_Residence_Location_Importer( $this->add_on );
            $location_importer->import( $post_id, $data, $import_options, $article );
        }

        public function save_in_slider( $post_id, $xml, $update ) {
            $option_key = 'wp_estate_theme_slider';
            $admin_option_key = 'wpresidence_admin';			
            $can_update = get_option( 'wpresaddon_can_update_slider' );
            
            $current_slider = get_option( $option_key );
            if ( empty( $current_slider ) ) {
                $current_slider = array();
            }
            $current_slider = maybe_unserialize( $current_slider );

            $admin_options = get_option( $admin_option_key );
            if ( empty( $admin_options ) ) {
                $admin_options = array( $admin_option_key => array() );
            }
            $admin_options = maybe_unserialize( $admin_options );

		
			if ( $can_update == '1' ) {		
                $setting = get_post_meta( $post_id, 'property_theme_slider', true );
				
				if ( $setting == '1' ) {
		
					if ( ! in_array( $post_id, $current_slider ) ) {		
						$current_slider[] = $post_id;		
						update_option( $option_key, $current_slider );		
                    }
                    
                    if ( ! in_array( $post_id, $admin_options[ $option_key ] ) ) {
                        $admin_options[ $option_key ][] = $post_id;
                        update_option( $admin_option_key, $admin_options );
                    }
				} elseif ( $setting == '0' || empty( $setting ) ) {
		
					if ( is_array( $current_slider ) && ( $i = array_search( $post_id, $current_slider ) ) !== false ) {						
						unset( $current_slider[ $i ] );		
                    }		
                    update_option( $option_key, $current_slider );
	
                    
                    if ( is_array( $admin_options[ $option_key ] ) ) {
                        $found_key = array_search( $post_id, $admin_options[ $option_key ] );
                        if ( false !== $found_key ) {
                            unset( $admin_options[ $option_key ][ $found_key ] );
                            update_option( $admin_option_key, $admin_options );
                        }
                    }
				}		
			}		
			delete_option( 'wpresaddon_can_update_slider' );		
        }
        
        public function update_hidden_address( $post_id, $xml, $update ) {	
			$is_update = get_post_meta($post_id, 'temp_is_update_hidden_address', true);			
			if ( empty( $is_update ) ) return;
			
			// Clearing the cache before getting terms, needed later for 'property_county_state' taxonomy
			wp_cache_flush();
			
			// Begin with an empty string
			$hidden_address = '';
			
			// Property address info
			$address = get_post_meta( $post_id, 'property_address', true );
			
			if ( !empty( $address ) ) {
				$hidden_address .= $address . ', ';
			} else {
				$hidden_address .= ', ';
			}
		
			// Property zip info
			$zip = get_post_meta( $post_id, 'property_zip', true );
			
			if ( !empty( $zip ) ) {
				$hidden_address .= $zip . ', ';
			} else {
				$hidden_address .= ', ';
			}
		
			// Property neighborhood info
			$neighborhood = get_the_terms( $post_id, 'property_area' );
			
			if ( !empty( $neighborhood ) && ! is_wp_error( $neighborhood ) ) {
				$hidden_address .= $neighborhood[0]->name . ', ';
			} else {
				$hidden_address .= ', ';
			}
			
			// Property city info
			$city = get_the_terms( $post_id, 'property_city' );
			
			if ( !empty( $city ) && ! is_wp_error( $city ) ) {
				$hidden_address .= $city[0]->name . ', ';
			} else {
				$hidden_address .= ', ';
			}
			
			// Property county/state info
			$county_state = get_the_terms( $post_id, 'property_county_state' );
			
			if ( !empty( $county_state ) && ! is_wp_error( $county_state ) ) {
				$hidden_address .= $county_state[0]->name . ', ';
			} else {
				$hidden_address .= ', ';
			}
			
			// Update the 'hidden_address' with the string constructed here
			$this->helper->update_meta( $post_id, 'hidden_address', $hidden_address );
			
			// Remove the temporary field we created earlier to note that 'hidden_address' should be updated
			delete_post_meta( $post_id, 'temp_is_update_hidden_address' );
		
		}

        public function get_custom_details_fields() {
            $helper = new WPAI_WP_Residence_Add_On_Helper();
            return $helper->get_custom_details_fields();
        }
    }

    if ( ! function_exists( 'property_images' ) ) {
        function property_images( $post_id, $attachment_id, $image_filepath, $import_options ) {
            // page_custom_image
            $wpresidence_addon = new WPAI_WP_Residence_Add_On_Helper();
            /*$importer = new wpai_wpres_fields_class();
            $importer->import( 'property_images', $this->post_type, $post_id, $attachment_id, $image_filepath, $import_options );			*/
        
            // Get the current list of images
            $current_images = get_post_meta( $post_id, 'image_to_attach', true );
        
            // Turn it into an array
            $current_images = explode( ",", $current_images );
            
            // Add the new image
            $current_images[] = $attachment_id;
        
            $current_images = array_filter($current_images);
            
            // image count
            $count = 1;
        
            // let user know that we are attaching images to given property and that the same image can only be attached to one
        // should probably set it up to use translations later
            $logger = function($m) {printf("<div class='progress-msg'>[%s] $m</div>\n", date("H:i:s"));flush();};
            call_user_func($logger, "<b>Images Attached to this property:</b> Each image can only be attached to one property.  Uncheck 'Search through the Media Library for existing images before importing new images' if you need to import the same image for multiple properties.");
        
            // ensure images are attached to this post and set the menu order so that they are shown correctly
            $set_menu_order = apply_filters( 'wpai_wp_res_is_set_menu_order', true );
            if ( $set_menu_order === true ) {
            // ensure images are attached to this post and set the menu order so that they are shown correctly
                foreach ( $current_images as $image ) {
                    $gallery_post = wp_update_post( array(
                        'ID'            => $image,
                        'post_parent'   => $post_id,
                        'menu_order'    => $count,
                    ), true );
                    if( is_wp_error( $gallery_post ) ) {
                            error_log( print_r( $gallery_post, 1 ) );
                    }
                    
                    $count++;                
                }
            }
        
            // Turn it back into a string
            $current_images = implode( ",", $current_images );
        
            // Update the image field while removing any commas before or after the image IDs
            $wpresidence_addon->update_meta( $post_id, 'image_to_attach', trim( $current_images, "," ) );
        }
    }
}
