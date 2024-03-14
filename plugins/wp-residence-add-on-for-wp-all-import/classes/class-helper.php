<?php
if ( ! class_exists( 'WPAI_WP_Residence_Add_On_Helper' ) ) {
    
    class WPAI_WP_Residence_Add_On_Helper {
        public function get_theme_version() {
            $version = false;
            $theme   = wp_get_theme();
            
            if ( ! empty( $theme->parent() ) ) {
                $version = $theme->parent()->get( 'Version' );
            } else {
                $version = $theme->get( 'Version' );
            }

            return $version;
        }

        public function get_post_type() {
            global $argv;
            $import_id = false;
            /**
            * Show fields based on post type
            **/
        
            $custom_type = false;

            if ( ! empty( $argv ) ) {
                if ( isset( $argv[3] ) ) {
                    $import_id = $argv[3];
                }
            }
        
            if ( ! $import_id ) {
                // Get import ID from URL or set to 'new'
                if ( isset( $_GET['import_id'] ) ) {
                    $import_id = $_GET['import_id'];
                } elseif ( isset( $_GET['id'] ) ) {
                    $import_id = $_GET['id'];
                }
            
                if ( empty( $import_id ) ) {
                    $import_id = 'new';
                }
            }
        
            // Declaring $wpdb as global to access database
            global $wpdb;
        
            // Get values from import data table
            $imports_table = $wpdb->prefix . 'pmxi_imports';
        
            // Get import session from database based on import ID or 'new'
            $import_options = $wpdb->get_row( $wpdb->prepare("SELECT options FROM $imports_table WHERE id = %d", $import_id), ARRAY_A );
        
            // If this is an existing import load the custom post type from the array
            if ( ! empty($import_options) )	{
                $import_options_arr = unserialize($import_options['options']);
                $custom_type = $import_options_arr['custom_type'];
            } else {
                // If this is a new import get the custom post type data from the current session
                $import_options = $wpdb->get_row( $wpdb->prepare("SELECT option_name, option_value FROM $wpdb->options WHERE option_name = %s", '_wpallimport_session_' . $import_id . '_'), ARRAY_A );				
                $import_options_arr = empty($import_options) ? array() : unserialize($import_options['option_value']);
                $custom_type = empty($import_options_arr['custom_type']) ? '' : $import_options_arr['custom_type'];		
            }
            return $custom_type;
        }

        public function save_in_slider( $post_id, $xml, $update ) {

            $can_update = get_option( 'wpresaddon_can_update_slider' );
        
            if ( $can_update == '1' ) {
        
                $setting = get_post_meta( $post_id, 'property_theme_slider', true );
                
                if ( $setting == '1' ) {
        
                    $current_slider = get_option( 'wp_estate_theme_slider' );
        
                    $current_slider = maybe_unserialize( $current_slider );
        
                    if ( !in_array( $post_id, $current_slider ) ) {
        
                        $current_slider[] = $post_id;
        
                        update_option( 'wp_estate_theme_slider', $current_slider );
        
                    }
        
                } elseif ( $setting == '0' ) {
        
                    // remove from slider
        
                    $current_slider = get_option( 'wp_estate_theme_slider' );
                    
                    $current_slider = maybe_unserialize( $current_slider );
        
                    if ( is_array($current_slider) && ( $i = array_search( $post_id, $current_slider ) ) !== false ) {
                        
                        unset( $current_slider[ $i ] );
        
                    }
        
                    update_option( 'wp_estate_theme_slider', $current_slider );
        
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
            
            if ( !empty( $neighborhood ) ) {
                $hidden_address .= $neighborhood[0]->name . ', ';
            } else {
                $hidden_address .= ', ';
            }
            
            // Property city info
            $city = get_the_terms( $post_id, 'property_city' );
            
            if ( !empty( $city ) ) {
                $hidden_address .= $city[0]->name . ', ';
            } else {
                $hidden_address .= ', ';
            }
            
            // Property county/state info
            $county_state = get_the_terms( $post_id, 'property_county_state' );
            
            if ( !empty( $county_state ) ) {
                $hidden_address .= $county_state[0]->name . ', ';
            } else {
                $hidden_address .= ', ';
            }
            
            // Update the 'hidden_address' with the string constructed here
            update_post_meta( $post_id, 'hidden_address', $hidden_address );
            
            // Remove the temporary field we created earlier to note that 'hidden_address' should be updated
            delete_post_meta( $post_id, 'temp_is_update_hidden_address' );
        
        }
        
        public function get_user( $data, $return_field ) {
            if ( $user = get_user_by( 'login', $data ) ) {
                return $user->$return_field;
            } elseif ( $user = get_user_by( 'email', $data ) ) {
                return $user->$return_field;
            } elseif ( $user = get_user_by( 'ID', $data ) ) {
                return $user->$return_field;
            } else {
                return FALSE;
            }
        }

        public function get_custom_details_fields() {
            // an array for custom detail field postmeta keys
			$custom_details_fields = array();			
			$custom_details = get_option( 'wpestate_custom_fields_list' );

			if ( ! empty( $custom_details ) ) {
				$custom_details = maybe_unserialize( $custom_details );

				if ( array_key_exists( 'add_field_name', $custom_details ) ) {

					foreach ( $custom_details['add_field_name'] as $cd_key => $cd ) {	
						$key = $cd;
						$key = str_replace( ' ', '-', $key );							
						$custom_details_fields[] = '_custom_details_' . $key;	
					}	
				}
			} else {
				// get all the custom details
				$custom_details = get_option( 'wp_estate_custom_fields' );

				// build the key array for the UI and the field array for the actual import
				if ( !empty( $custom_details ) ) {

					foreach ($custom_details as $custom_detail) {
						$key = $custom_detail[0];
						$key = str_replace( ' ', '-', $key );							
						$custom_details_fields[] = '_custom_details_' . $key;
					}
				}
            }
            
            return $custom_details_fields;
        }
        
        public function update_meta( $id, $field, $data, $type = 'post' ) {
            $enable_logs_settings = array( 
                'enable'                 => true, 
                'include_empty_updates'  => false,
                'include_failed_updates' => false 
            );

            $enable_logs = apply_filters( 'wpai_wpresidence_addon_enable_logs', $enable_logs_settings );

            if ( $type == 'post' ) {

                $update = update_post_meta( $id, $field, $data );
                $data = maybe_serialize( $data );
                $data = wp_strip_all_tags( $data );

                if ( $update !== false ) {
                    if ( $enable_logs['enable'] === true ) {
                        if ( $enable_logs['include_empty_updates'] === false ) {
                            if ( $field !== '' && $data !== '' ) {                                
                                $this->log( '<strong>WP Residence Add-On:</strong> Successfully imported value "<em>' . $data . '</em>" into field: <em>' . $field . '</em>.' );
                            }
                        } else {
                            $this->log( '<strong>WP Residence Add-On:</strong> Successfully imported value "<em>' . $data . '</em>" into field: <em>' . $field . '</em>.' );
                        }
                    }
                } else {
                    if ( $enable_logs['enable'] === true && $enable_logs['include_failed_updates'] === true )  {
                        $this->log( '<strong>WP Residence Add-On:</strong> failed to import value "<em>' . $data . '</em>" into field: <em>' . $field . '</em>.' );
                    }
                }
            }
        }

        public function log( $message = 'empty' ){

            if ( $message !== 'empty' ) {            
                $logger = function( $m = '' ) {
                    $date    = date("H:i:s");
                    $m = str_replace( '%', '%%', $m );
                    printf( "<div class='progress-msg'>[%s] $m</div>\n", $date ); 
                    flush(); 
                };
                call_user_func( $logger, $message );
            }

		}
        
    }

}