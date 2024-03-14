<?php
function gfee_generate_export( $export_name = 'Default', $start = '', $stop = '') {
    //= Do we need to update our settings?
    gfee_update_settings();
    
	$upload_arr = wp_upload_dir();
	$upload_dir = $upload_arr['basedir'];
	$upload_dir .= '/gfee';

	//= Does this folder exist
	if ( ! file_exists( $upload_dir ) ) {
		//= Make the folder
		mkdir( $upload_dir );
	}
	
	$upload_dir = apply_filters( 'gfee_upload_dir_filter', $upload_dir );
	$upload_url = $upload_arr['baseurl'];
	$upload_url .= '/gfee';
	$upload_url = apply_filters( 'gfee_upload_url_filter', $upload_url );

	$settings = get_option( 'gfee_settings', '' );

	//= if settings haven't been saved then return message
	if ( empty( $settings ) ) {
		return;
	}

	//= get settings for just this export
	$settings = $settings['exports'][ $export_name ];

	$site_name = get_bloginfo( 'name' );

	$site_name = preg_replace( "/[^A-Za-z0-9 ]/", '', $site_name );
	
	$file_name = __( 'Form entries for', 'gforms-export-entries' ) . ' ' . $site_name . ' ' . date( 'Y' ) . '-' . date( 'm' ) . '-' . date( 'd') . '-' . time() . '.xls';
	$file_name = apply_filters( 'gfee_filename_filter', $file_name );
	
	$file_path = trailingslashit( $upload_dir ) . $file_name;
	$file_url =  trailingslashit( $upload_url ) . $file_name;

	$excel = new ExcelWriter( $file_path ); 
	
	if ( $excel == false ) 
		echo $excel->error;

	$column_count = 0;

	if ( ! empty( $settings['file_header'] ) ) {
		if ( strpos( $settings['file_header'], ',' ) !== false ) {
			$header_row_arr = explode( ',', $settings['file_header'] );
			$column_count = count( $header_row_arr );
			$excel->writeLine($header_row_arr); 
		}
	}

	$forms = GFAPI::get_forms();

	//= create array to store form name and id
	$form_ids = array();
	$field_ids = array();

	foreach( $forms as $form ) {
		//= add form title and id to $form_ids array
		$form_ids[ gfee_clean_title( $form['title'] ) ] = $form['id'];
		$form['title'] = gfee_clean_title( $form['title'] );

		//= loop fields and add their label and id to $field_ids array
		foreach( $form['fields'] as $field=>$data ) {
			if ( is_array($data["inputs"] ) ) {

				foreach( $data["inputs"] as $input ) {
					if ( empty( $input["label"] ) ) continue;
					$field_ids[ $form['id'] ][ $input['label'] ] = $input['id'];
				}

			} else {

				if ( ! empty( $data["label"] ) ) {
					$field_ids[ $form['id'] ][ $data['label'] ] = $data['id'];
				}

			}
		}

	}

	$entry_criteria = array();
	if ( ! empty( $start ) && ! empty( $stop ) ) {

		//= reformat $start to yy-mm-dd
		$a = explode('-', $start);
		$start = $a[2].'-'.$a[0].'-'.$a[1];

		//= reformat $stop to yy-mm-dd
		$a = explode('-', $stop);
		$stop = $a[2].'-'.$a[0].'-'.$a[1];

		$entry_criteria['start_date'] = $start;
		$entry_criteria['end_date'] = $stop;
	} else {
		if ( isset( $settings['gfee_schedule_frequency'] ) ) {
			if ( ! empty( $settings['gfee_schedule_frequency'] ) ) {
				$y = date( 'y' );
				$m = date( 'm' );
				$d = date( 'd' );
				$stop = $y . '-' . $m . '-' . $d;
				if ( $settings['gfee_schedule_frequency'] == 'daily' ) {
					$start = date( 'y-m-d', strtotime( '-1 day', $time ) );
				} else if ( $settings['gfee_schedule_frequency'] == 'weekly' ) {
					$start = date( 'y-m-d', strtotime( '-1 week', $time ) );
				} else if ( $settings['gfee_schedule_frequency'] == 'gfee_monthly' ) {
					$start = date( 'y-m-d', strtotime( '-1 month', $time ) );
				}
				$entry_criteria['start_date'] = $start;
				$entry_criteria['end_date'] = $stop;
			}
		}
	}

	$sorting = null;
	$paging = array( 'offset' => 0, 'page_size' => 200 );

	if ( isset( $settings['forms'] ) && is_array( $settings['forms'] ) && count( $settings['forms'] ) > 0 ) {
		foreach( $settings['forms'] as $form=>$fields ) {
			//= Make sure all values for order as integers
			foreach( $fields as $field=>$order ) {
				$fields[ $field ] = (int)$order;
			}
			asort( $fields );
	
			$form_id = $form_ids[ gfee_clean_title( $form ) ];
            $form_id = $form;
			$entries = GFAPI::get_entries( $form_id, $entry_criteria, $sorting, $paging );
			if ( is_array( $entries ) ) {
                if ( count( $entries ) > 0 ) {
                    
                    $entries_form = GFAPI::get_form($form_id);
                    $confirmation_array = $entries_form["confirmations"];
                    $default_confirmation = '';
                    foreach ($confirmation_array as $confirmation) {
                        if ($confirmation['isDefault']) {
                            $default_confirmation = $confirmation;
                            break; // Exit the loop since we found the default confirmation
                        }
                    }
 
                    if ($default_confirmation) {
                        $confirmation_type = $default_confirmation["type"];
 
                        if ($confirmation_type == "page") {
                            $confirmation_page_id = $default_confirmation["pageId"];
                            $confirmation_page_title = get_the_title($confirmation_page_id);
                            $confirmation_page_url = get_permalink($confirmation_page_id);
                            $confirmation_data = $confirmation_page_title . " (" . $confirmation_page_url . ")";
                        } else if ($confirmation_type == "message") {
                            $confirmation_data = "Message: " . $default_confirmation["message"];
                        } else {
                            $confirmation_data = "Redirect to: " . $default_confirmation["url"];
                        }
                    } else {
                        $confirmation_data = "No default confirmation found";
                    }
                    
                    foreach( $entries as $entry_field=>$value ) {
                         if ($value['status'] == 'active') { 
                            $custom_fields = array();
                            $custom_fields = apply_filters( 'gfee_custom_columns_filter', $custom_fields, $form_id );
                            $entry_id = $value['id'];
                            $row = array();
                            $current_column = 0;
                            foreach( $fields as $field=>$order ) {
                                //= Handle missing columns
                                ++$current_column;
        
                                if ( $current_column !== (int)$order ) {
                                    while ( $current_column !== (int)$order && $current_column < (int)$order ) {
                                        $row[] = '';
                                        ++$current_column;
                                    }
                                }
        
                                $custom_row = '';
                                if ( $field == 'form_name' ) {
                                    $forminfo = RGFormsModel::get_form( $form );
                                    $form_title = $forminfo->title;
                                    $row[] = gfee_clean_title( $form_title );
                                } else if ( $field == 'form_id' ) {
                                    $row[] = $form_id;
                                } else if ( $field == 'entry_id' ) {
                                    $row[] = $entry_id;
                                } else if ( $field == 'form_date' ) {
                                    $row[] = $value[ 'date_created' ];
                                } else if ( $field == 'source_url' ) {
                                    $row[] = $value['source_url'];
                                } else if ( $field == 'user_ip' ) {
                                    $row[] = $value['ip'];
                                }else if ( $field == 'confirmation_page' ) {
                                    $row[] = $confirmation_data;
                                }
                                else {
                                    //= assign fields to array and save to a row
                                    if ( ! isset( $value[ trim( $field_ids[ gfee_clean_title( $form ) ][ $field ] ) ] ) ) {
                                        $id = $custom_fields[ $field ] + 1;
                                        if ( isset( $value[ $id ] ) ) {
                                            $row[] = $value[ $id ];
                                        } else {
                                            $row[] = '';
                                        }
                                    } else {
                                        $row[] = $value[ trim( $field_ids[ gfee_clean_title( $form ) ][ $field ] ) ];
                                    }
                                }
                            }
                         } 
                        // next line adds the gravity form entry id to last column for debugging
                        //$row[] = $value['id'];
                        //gfee_log( 'entry: ' . $value['id'] );
                        $excel->writeLine($row);
                    }
                    
                }
            }





			
		} //= end foreach( $settings['forms'] as $form=>$fields ) {
	} //= end if ( is_array( $settings['forms'] ) && count( $settings['forms'] ) > 0 )
	
	$excel->close();

	echo $file_url;
	return $file_path;
}

?>