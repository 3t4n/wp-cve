<?php

class arforms_import_export_settings{

    function __construct(){

        add_action( 'admin_init', array( $this, 'arflite_export_form_data' ) );
        add_action( 'wp_ajax_arforms_check_export_form_data_entry', array( $this, 'arforms_check_export_form_data_entry_func' ) );
        add_action( 'wp_ajax_arf_change_entries_separator', array( $this, 'arf_changes_export_entry_separator' ) );
    }

    function arf_changes_export_entry_separator() {

		if ( isset( $_POST['_wpnonce_arforms'] ) && '' != $_POST['_wpnonce_arforms'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arforms'] ), 'arforms_wp_nonce' ) ) {
            echo esc_attr( 'security_error' );
            die;
		} 

		$separator = !empty( $_REQUEST['separator']) ? sanitize_text_field( $_REQUEST['separator'] ) : '';
		update_option( 'arf_form_entry_separator', $separator );
	}

    function arforms_import_form_data(){

        global $current_user, $arfliteformhelper,$arflite_installed_field_types,$arfliterecordcontroller,$arfliteformcontroller, $arformsmain, $arfliteformcontroller;
        
        $arf_import_export_useragent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
        $browser_info = $arfliterecordcontroller->arflitegetBrowser( $arf_import_export_useragent );
        $allowed_html = arflite_retrieve_attrs_for_wp_kses();

        @ini_set( 'max_execution_time', 0 );

        $upload_dir   = ARFLITE_UPLOAD_DIR . '/css/';
        $main_css_dir = ARFLITE_UPLOAD_DIR . '/maincss/';

        $xml = !empty( $_REQUEST['arf_import_textarea'] ) ? html_entity_decode( base64_decode( sanitize_text_field( $_REQUEST['arf_import_textarea'] ) ) ) : '';

        $outside_fields = apply_filters( 'arflite_installed_fields_outside', $arflite_installed_field_types );

        libxml_use_internal_errors( true );

        $xml = simplexml_load_string( $xml );

        if ( $xml === false ) {
            $xml = !empty($_REQUEST['arf_import_textarea']) ? base64_decode( sanitize_text_field( $_REQUEST['arf_import_textarea'] ) ) : '';

            $outside_fields = apply_filters( 'arflite_installed_fields_outside', $arflite_installed_field_types );

            libxml_use_internal_errors( true );

            $xml = simplexml_load_string( $xml );
        }

        $invalid_file_ext = array( 'php', 'php3', 'php4', 'php5', 'pl', 'py', 'jsp', 'asp', 'exe', 'cgi' );
        $valid_file_ext   = array( 'jpg', 'png', 'gif', 'jpeg', 'svg', 'webp' );

        if( !isset( $_REQUEST['arf_import_form_nonce'] ) || ( isset(  $_REQUEST['arf_import_form_nonce'] ) && !wp_verify_nonce( $_REQUEST['arf_import_form_nonce'], 'arf_import_form' ) ) ){ //phpcs:ignore ?>
                <div id="error_message" class="arf_error_message" data-id="arflite_import_export_error_msg">
                    <div class="message_descripiton">
                        <div class="arffloatmargin" id=""><?php echo esc_html__( 'Sorry, You are not an authorized person to perform this action.', 'arforms-form-builder' ); ?></div>
                        <div class="message_svg_icon">
                            <svg class="arfheightwidth14"><path fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></svg>
                        </div>
                    </div>
                </div>
            <?php

        } else {

            global $arflitefield, $arfliteform, $ARFLiteMdlDb, $wpdb, $WP_Filesystem, $arflitemainhelper, $arflitefieldhelper, $arfliteformhelper, $arflitesettingcontroller, $arfliterecordmeta, $arflite_db_record, $tbl_arf_forms, $tbl_arf_fields;
            if ( isset( $xml->arformslite ) ) {

                $ik = 0;
                foreach ( $xml->children() as $formxml ) {
                    foreach ( $formxml->children() as $key_main => $val_main ) {
                        $attr                         = $val_main->attributes();
                        $old_form_id                  = $attr['id'];
                        $submit_bg_img_fnm            = '';
                        $arfmainform_bg_img_fnm       = '';
                        $arfmainform_bg_hover_img_fnm = '';

                        $submit_bg_img             = trim( $val_main->submit_bg_img );
                        $arfmainform_bg_img        = trim( $val_main->arfmainform_bg_img );
                        $submit_hover_bg_img       = trim( $val_main->submit_hover_bg_img );
                        $xml_arf_version           = trim( $val_main->arf_db_version );
                        $exported_site_uploads_dir = trim( $val_main->exported_site_uploads_dir );

                        $imageupload_dir = ARFLITE_UPLOAD_DIR . '/';

                        $imageupload_url = ARFLITE_UPLOAD_URL . '/';

                        if ( $submit_bg_img != '' ) {
                            $submit_bg_img_filenm = basename( $submit_bg_img );

                            $submit_bg_img_fnm = time() . '_' . $ik . '_' . $submit_bg_img_filenm;
                            $ik++;

                            $submit_btn_img_ext = explode( '.', $submit_bg_img );
                            $file_ext           = end( $submit_btn_img_ext );
                            if ( ! in_array( $file_ext, $invalid_file_ext ) && in_array( $file_ext, $valid_file_ext ) ) {
                                if ( ! $arfliteformcontroller->arflite_upload_file_function( $submit_bg_img, $imageupload_dir . $submit_bg_img_fnm ) ) {
                                    $submit_bg_img_fnm = '';
                                }
                            }
                        }

                        if ( $arfmainform_bg_img != '' ) {
                            $arfmainform_bg_img_filenm = basename( $arfmainform_bg_img );

                            $arfmainform_bg_img_fnm = time() . '_' . $ik . '_' . $arfmainform_bg_img_filenm;
                            $ik++;


                            $arflitefilecontroller = new arflitefilecontroller( $arfmainform_bg_img, true );

                            $arflitefilecontroller->check_cap    = true;
                            $arflitefilecontroller->capabilities = array( 'arfchangesettings' );

                            $arflitefilecontroller->check_nonce  = true;
                            $arflitefilecontroller->nonce_action = 'arf_import_form';
                            $arflitefilecontroller->nonce_data   = isset( $_REQUEST['arf_import_form_nonce'] ) ? sanitize_text_field( $_REQUEST['arf_import_form_nonce'] ) : '';

                            $arflitefilecontroller->check_only_image = true;

                            $destination = $imageupload_dir . $arfmainform_bg_img_fnm;

                            if ( ! $arflitefilecontroller->arflite_process_upload( $destination ) ) {
                                $arfmainform_bg_img_fnm = '';
                            }
                        }
                        if ( $submit_hover_bg_img != '' ) {
                            $submit_hover_bg_img_filenm = basename( $submit_hover_bg_img );


                            $arfmainform_bg_hover_img_fnm = time() . '_' . $ik . '_' . $submit_hover_bg_img_filenm;
                            $ik++;

                            $arflitefilecontroller = new arflitefilecontroller( $submit_hover_bg_img, true );

                            $arflitefilecontroller->check_cap    = true;
                            $arflitefilecontroller->capabilities = array( 'arfchangesettings' );

                            $arflitefilecontroller->check_nonce  = true;
                            $arflitefilecontroller->nonce_action = 'arf_import_form';
                            $arflitefilecontroller->nonce_data   = isset( $_REQUEST['arf_import_form_nonce'] ) ? sanitize_text_field( $_REQUEST['arf_import_form_nonce'] ) : '';

                            $arflitefilecontroller->check_only_image = true;

                            $destination = $imageupload_dir . $arfmainform_bg_hover_img_fnm;

                            if ( ! $arflitefilecontroller->arflite_process_upload( $destination ) ) {
                                $arfmainform_bg_hover_img_fnm = '';
                            }
                        }

                        $val                    = '';
                        $old_field_orders       = $new_field_order = array();
                        $old_field_resize_width = $new_field_resize_width = array();
                        $old_field_order_type   = $new_field_order_type = array();
                        foreach ( $val_main->general_options->children() as $key => $val ) {
                            if ( $key == 'options' ) {
                                $options_arr = '';
                                $options_key = '';
                                $options_val = '';
                                unset( $option_arr_new );
                                $option_string = '';

                                $options_arr = arflite_json_decode( trim( $val ), true );

                                if ( ! is_array( $options_arr ) ) {
                                    $options_arr = json_decode( $options_arr, true );
                                }


                                foreach ( $options_arr as $options_key => $options_val ) {
                                    if ( ! is_array( $options_val ) ) {
                                        $options_val = str_replace( '[ENTERKEY]', '<br>', $options_val );
                                        $options_val = str_replace( '[AND]', '&', $options_val );
                                    }

                                    if ( $options_key == 'before_html' ) {
                                        $option_arr_new[ $options_key ] = $arfliteformhelper->arflite_get_default_html( 'before' );
                                    } elseif ( $options_key == 'ar_email_subject' ) {
                                            $_SESSION['ar_email_subject_org'] = $options_val;
                                        $option_arr_new[ $options_key ]    = $options_val;
                                    } elseif ( $options_key == 'ar_email_message' ) {
                                            $_SESSION['ar_email_message_org'] = $options_val;
                                        $option_arr_new[ $options_key ]    = $options_val;
                                    } elseif ( $options_key == 'ar_admin_email_message' ) {
                                            $_SESSION['ar_admin_email_message_org'] = $options_val;
                                        $option_arr_new[ $options_key ]          = $options_val;
                                    } elseif ( $options_key == 'ar_email_to' ) {
                                            $_SESSION['ar_email_to_org']   = $options_val;
                                        $option_arr_new[ $options_key ] = $options_val;
                                    } elseif ( $options_key == 'ar_admin_from_email' ) {
                                            $_SESSION['ar_admin_from_email'] = $options_val;
                                        $option_arr_new[ $options_key ]   = $options_val;
                                    } elseif ( $options_key == 'ar_user_from_email' ) {
                                            $_SESSION['ar_user_from_email'] = $options_val;
                                        $option_arr_new[ $options_key ]  = $options_val;
                                    } elseif ( $options_key == 'ar_admin_from_name' ) {
                                            $_SESSION['arf_admin_from_name'] = $options_val;
                                        $option_arr_new[ $options_key ]   = $options_val;
                                    } elseif ( $options_key == 'admin_email_subject' ) {
                                            $_SESSION['admin_email_subject'] = $options_val;
                                        $option_arr_new[ $options_key ]   = $options_val;
                                    } elseif ( $options_key == 'reply_to' ) {
                                            $_SESSION['reply_to']          = $options_val;
                                        $option_arr_new[ $options_key ] = $options_val;
                                    } elseif ( $options_key == 'arf_pre_dup_field' ) {
                                            $_SESSION['arf_pre_dup_field'] = $options_val;
                                        $option_arr_new[ $options_key ] = $options_val;
                                    } elseif ( $options_key == 'arf_field_order' ) {
                                        $old_field_orders               = json_decode( $options_val, true );
                                        $option_arr_new[ $options_key ] = $options_val;
                                    } elseif ( $options_key == 'arf_field_resize_width' ) {
                                        $option_arr_new[ $options_key ] = $options_val;
                                        $old_field_resize_width         = json_decode( $options_val, true );
                                    } else {
                                        $option_arr_new[ $options_key ] = $options_val;
                                    }
                                }
                                $option_string = maybe_serialize( $option_arr_new );

                                $general_option[ $key ] = $option_string;

                                $general_op = $option_string;
                            } elseif ( $key == 'form_css' ) {
                                $form_css_arr = arflite_json_decode( trim( $val ), true );

                                if ( ! isset( $form_css_arr['prefix_suffix_bg_color'] ) || $form_css_arr['prefix_suffix_bg_color'] == '' ) {
                                    $form_css_arr['prefix_suffix_bg_color'] = '#e7e8ec';
                                }

                                if ( ! isset( $form_css_arr['prefix_suffix_icon_color'] ) || $form_css_arr['prefix_suffix_icon_color'] == '' ) {
                                    $form_css_arr['prefix_suffix_icon_color'] = '#808080';
                                }

                                if ( ! isset( $form_css_arr['arfsubmitboxxoffsetsetting'] ) || $form_css_arr['arfsubmitboxxoffsetsetting'] == '' ) {
                                    $form_css_arr['arfsubmitboxxoffsetsetting'] = '1';
                                }

                                if ( ! isset( $form_css_arr['arfsubmitboxyoffsetsetting'] ) || $form_css_arr['arfsubmitboxyoffsetsetting'] == '' ) {
                                    $form_css_arr['arfsubmitboxyoffsetsetting'] = '2';
                                }

                                if ( ! isset( $form_css_arr['arfsubmitboxblursetting'] ) || $form_css_arr['arfsubmitboxblursetting'] == '' ) {
                                    $form_css_arr['arfsubmitboxblursetting'] = '3';
                                }

                                if ( ! isset( $form_css_arr['arfsubmitboxshadowsetting'] ) || $form_css_arr['arfsubmitboxshadowsetting'] == '' ) {
                                    $form_css_arr['arfsubmitboxshadowsetting'] = '0';
                                }

                                foreach ( $form_css_arr as $form_css_key => $form_css_val ) {
                                    if ( $form_css_key == 'submit_bg_img' ) {
                                        if ( $submit_bg_img_fnm == '' ) {
                                            $form_css_arr_new['submit_bg_img']    = '';
                                            $form_css_arr_new_db['submit_bg_img'] = '';
                                        } else {


                                            $form_css_arr_new['submit_bg_img']    = $imageupload_url . $submit_bg_img_fnm;
                                            $form_css_arr_new_db['submit_bg_img'] = $imageupload_url . $submit_bg_img_fnm;
                                        }
                                    } elseif ( $form_css_key == 'arfmainform_bg_img' ) {
                                        if ( $arfmainform_bg_img_fnm == '' ) {
                                            $form_css_arr_new[ $form_css_key ]    = '';
                                            $form_css_arr_new_db[ $form_css_key ] = '';
                                        } else {

                                            $form_css_arr_new[ $form_css_key ]    = $imageupload_url . $arfmainform_bg_img_fnm;
                                            $form_css_arr_new_db[ $form_css_key ] = $imageupload_url . $arfmainform_bg_img_fnm;
                                        }
                                    } elseif ( $form_css_key == 'submit_hover_bg_img' ) {
                                        if ( $arfmainform_bg_hover_img_fnm == '' ) {
                                            $form_css_arr_new[ $form_css_key ]    = '';
                                            $form_css_arr_new_db[ $form_css_key ] = '';
                                        } else {

                                            $form_css_arr_new[ $form_css_key ]    = $imageupload_url . $arfmainform_bg_hover_img_fnm;
                                            $form_css_arr_new_db[ $form_css_key ] = $imageupload_url . $arfmainform_bg_hover_img_fnm;
                                        }
                                    } elseif ( $form_css_key == 'arf_checked_checkbox_icon' || $form_css_key == 'arf_checked_radio_icon' ) {
                                        $form_css_arr_new[ $form_css_key ]    = $arflitemainhelper->arflite_update_fa_font_class( $form_css_val );
                                        $form_css_arr_new_db[ $form_css_key ] = $arflitemainhelper->arflite_update_fa_font_class( $form_css_val );
                                    } else {
                                        $form_css_arr_new[ $form_css_key ]    = $form_css_val;
                                        $form_css_arr_new_db[ $form_css_key ] = $form_css_val;
                                    }
                                }

                                $final_val                      = maybe_serialize( $form_css_arr_new );
                                $final_val_db                   = maybe_serialize( $form_css_arr_new_db );
                                $general_option[ $key ]         = $final_val;
                                $general_option[ $key . '_db' ] = $final_val_db;
                            } else {
                                $general_option[ $key ] = trim( $val );
                            }
                        }

                        $general_option['is_importform'] = 'Yes';

                        $general_option['form_key'] = '';
                        unset( $general_option['id'] );
                        $form_id = $arfliteform->arflitecreate( $general_option );

                        $cssoptions = $general_option['form_css'];

                        $cssoptions_db = $general_option['form_css_db'];


                        $type_array    = array();
                        $content_array = array();
                        $value_array   = array();
                        $new_id_array  = array();
                        $allfieldstype = array();
                        $allfieldsarr  = array();
                        $i             = 0;

                        $is_checkbox_img_enable  = 0;
                        $is_radio_img_enable     = 0;
                        $is_prefix_suffix_enable = false;

                        foreach ( $val_main->fields->children() as $key_fields => $val_fields ) {

                            if ( ! in_array( $val_fields->type, $outside_fields ) ) {
                                continue;
                            }

                            $fields_option = array();

                            foreach ( $val_fields as $key_field => $val_field ) {

                                if ( $key_field == 'form_id' ) {
                                    $fields_option[ $key_field ] = $form_id;
                                } elseif ( $key_field == 'field_key' ) {

                                } elseif ( $key_field == 'options' && ( $val_fields->type == 'radio' || $val_fields->type == 'checkbox' ) ) {

                                    if ( ! is_array( $val_field ) ) {

                                        $temp_radio_val = stripslashes( trim( $val_field ) );
                                        $temp_radio_val = rtrim( $temp_radio_val, '"' );
                                        $temp_radio_val = ltrim( $temp_radio_val, '"' );

                                        $val_field_radio = json_decode( trim( $temp_radio_val ), true );
                                        if ( json_last_error() != JSON_ERROR_NONE ) {
                                            $val_field_radio = maybe_unserialize( trim( $val_field ) );
                                        }
                                    }

                                    if ( is_array( $val_field_radio ) ) {
                                        foreach ( $val_field_radio as $key => $value ) {
                                            $image_path = '';
                                            if ( is_array( $value ) ) {
                                                if ( isset( $value['label_image'] ) && $value['label_image'] != '' ) {
                                                    $image_path = $value['label_image'];

                                                    $arflitefilecontroller = new arflitefilecontroller( $image_path, true );

                                                    $arflitefilecontroller->check_cap    = true;
                                                    $arflitefilecontroller->capabilities = array( 'arfchangesettings' );

                                                    $arflitefilecontroller->check_nonce  = true;
                                                    $arflitefilecontroller->nonce_action = 'arf_import_form';
                                                    $arflitefilecontroller->nonce_data   = isset( $_REQUEST['arf_import_form_nonce'] ) ? sanitize_text_field( $_REQUEST['arf_import_form_nonce'] ) : '';

                                                    $arflitefilecontroller->check_only_image = true;

                                                    $destination = $imageupload_dir . $key . '_' . basename( $image_path );

                                                    if ( ! $arflitefilecontroller->arflite_process_upload( $destination ) ) {
                                                        $val_field_radio[ $key ]['label_image'] = '';
                                                    } else {
                                                        $val_field_radio[ $key ]['label_image'] = $imageupload_url . $key . '_' . basename( $image_path );

                                                        if ( $val_fields->type == 'radio' ) {
                                                            $is_radio_img_enable = true;
                                                        } elseif ( $val_fields->type == 'checkbox' ) {
                                                            $is_checkbox_img_enable = true;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    $fields_option[ $key_field ] = json_encode( $val_field_radio );
                                } else {

                                    if ( $key_field == 'field_options' ) {

                                        $fields_option[ $key_field ] = trim( json_encode( arflite_json_decode( trim( $val_field ), true ) ) );

                                        $fields_option[ $key_field ] = str_replace( '[ENTERKEY]', '<br>', $fields_option[ $key_field ] );

                                    } elseif ( 'options' == $key_field ) {
                                        $temp_val                    = stripslashes( trim( $val_field ) );
                                        $temp_val                    = rtrim( $temp_val, '"' );
                                        $temp_val                    = ltrim( $temp_val, '"' );
                                        $fields_option[ $key_field ] = $temp_val;
                                    } else {
                                        $fields_option[ $key_field ] = trim( $val_field );
                                    }
                                }
                                $all_field_data = '';
                                $field_name     = '';

                                if ( isset( $fields_option['field_options'] ) ) {
                                    $all_field_data = arflite_json_decode( $fields_option['field_options'] );

                                    if ( isset( $all_field_data->name ) ) {
                                        $field_name           = str_replace( '[ENTERKEY]', ' ', $all_field_data->name );
                                        $all_field_data->name = $field_name;
                                    }
                                    $fields_option['field_options'] = trim( json_encode( $all_field_data ) );

                                }
                                if ( $key_field == 'field_options' ) {
                                    $arf_field_options = arflite_json_decode( trim( $fields_option[ $key_field ] ), true );

                                    if ( isset( $arf_field_options['arf_prefix_icon'] ) && $arf_field_options['arf_prefix_icon'] != '' ) {
                                        $arf_field_options['arf_prefix_icon'] = $arflitemainhelper->arflite_update_fa_font_class( $arf_field_options['arf_prefix_icon'] );

                                        $is_prefix_suffix_enable = true;
                                    }

                                    if ( isset( $arf_field_options['arf_suffix_icon'] ) && $arf_field_options['arf_suffix_icon'] != '' ) {
                                        $arf_field_options['arf_suffix_icon'] = $arflitemainhelper->arflite_update_fa_font_class( $arf_field_options['arf_suffix_icon'] );

                                        $is_prefix_suffix_enable = true;
                                    }

                                    if ( $val_fields->type == 'phone' && 1 == $arf_field_options['phonetype'] ) {
                                        $is_prefix_suffix_enable = true;
                                    }
                                    $fields_option[ $key_field ] = trim( json_encode( $arf_field_options ) );
                                }
                            }

                            $res_field_id                = $fields_option['id'];
                            $type_array[ $res_field_id ] = $fields_option['type'];


                            $new_field_id = $arflitefield->arflitecreate( $fields_option, true, true, $res_field_id );
                            if ( $val_fields->type != 'html' ) {
                                $new_id_array[ $i ]['old_id'] = $res_field_id;
                                $new_id_array[ $i ]['new_id'] = $new_field_id;
                                $new_id_array[ $i ]['name']   = $fields_option['name'];
                                $new_id_array[ $i ]['type']   = $fields_option['type'];
                            }
                            if ( $fields_option['type'] == 'html' || $fields_option['type'] == 'select' ) {
                                $value_array                                    = json_decode( $fields_option['field_options'], true );
                                $content_array[ $new_field_id ]['html_content'] = str_replace( '[ENTERKEY]', "\n", $value_array['description'] );
                            }
                            if ( $fields_option['type'] != 'hidden' ) {
                                if ( isset( $old_field_orders[ $res_field_id ] ) ) {
                                    $new_field_order[ $new_field_id ]      = $old_field_orders[ $res_field_id ];
                                    $old_field_order_type[ $res_field_id ] = $fields_option['type'];
                                    $new_field_order_type[ $new_field_id ] = $fields_option['type'];
                                }
                            }

                            $ar_email_subject = isset( $ar_email_subject ) ? $ar_email_subject : '';
                            if ( $ar_email_subject == '' ) {
                                $ar_email_subject = esc_html( $_SESSION['ar_email_subject_org'] );
                            } else {
                                $ar_email_subject = $ar_email_subject;
                            }

                            $ar_email_subject = str_replace( '[' . $res_field_id . ']', '[' . $new_field_id . ']', $ar_email_subject );
                            $ar_email_subject = $arfliteformhelper->arflite_replace_field_shortcode_import( $ar_email_subject, $res_field_id, $new_field_id );

                            $ar_email_message = isset( $ar_email_message ) ? $ar_email_message : '';
                            if ( $ar_email_message == '' ) {
                                $ar_email_message = isset( $_SESSION['ar_email_message_org'] ) ? wp_kses( $_SESSION['ar_email_message_org'], $allowed_html ) : '';
                            } else {
                                $ar_email_message = $ar_email_message;
                            }

                            $ar_email_message = str_replace( '[' . $res_field_id . ']', '[' . $new_field_id . ']', $ar_email_message );
                            $ar_email_message = $arfliteformhelper->arflite_replace_field_shortcode_import( $ar_email_message, $res_field_id, $new_field_id );

                            $arf_pre_dup_field = isset( $arf_pre_dup_field ) ? $arf_pre_dup_field : '';
                            if ( $arf_pre_dup_field == '' ) {
                                $arf_pre_dup_field = isset( $_SESSION['arf_pre_dup_field'] ) ? esc_html( $_SESSION['arf_pre_dup_field'] ) : '';
                            } else {
                                $arf_pre_dup_field = $arf_pre_dup_field;
                            }

                            $arf_pre_dup_field = str_replace( $res_field_id, $new_field_id, $arf_pre_dup_field );


                            $ar_admin_email_message = isset( $ar_admin_email_message ) ? $ar_admin_email_message : '';
                            if ( $ar_admin_email_message == '' ) {
                                $ar_admin_email_message = isset( $_SESSION['ar_admin_email_message_org'] ) ? wp_kses( $_SESSION['ar_admin_email_message_org'], $allowed_html ) : '';
                            } else {
                                $ar_admin_email_message = $ar_admin_email_message;
                            }
                            $ar_admin_email_message = str_replace( '[' . $res_field_id . ']', '[' . $new_field_id . ']', $ar_admin_email_message );
                            $ar_admin_email_message = $arfliteformhelper->arflite_replace_field_shortcode_import( $ar_admin_email_message, $res_field_id, $new_field_id );


                            $ar_admin_from_name = isset( $ar_admin_from_name ) ? $ar_admin_from_name : '';
                            if ( $ar_admin_from_name == '' ) {
                                $ar_admin_from_name = isset( $_SESSION['arf_admin_from_name'] ) ? esc_html( $_SESSION['arf_admin_from_name'] ) : '';
                            } else {
                                $ar_admin_from_name = $ar_admin_from_name;
                            }
                            $ar_admin_from_name = str_replace( '[' . $res_field_id . ']', '[' . $new_field_id . ']', $ar_admin_from_name );
                            $ar_admin_from_name = $arfliteformhelper->arflite_replace_field_shortcode_import( $ar_admin_from_name, $res_field_id, $new_field_id );

                            $admin_email_subject = isset( $admin_email_subject ) ? $admin_email_subject : '';
                            if ( $admin_email_subject == '' ) {
                                $admin_email_subject = isset( $_SESSION['admin_email_subject'] ) ? esc_html( $_SESSION['admin_email_subject'] ) : '';
                            } else {
                                $admin_email_subject = $admin_email_subject;
                            }
                            $admin_email_subject = str_replace( '[' . $res_field_id . ']', '[' . $new_field_id . ']', $admin_email_subject );
                            $admin_email_subject = $arfliteformhelper->arflite_replace_field_shortcode_import( $admin_email_subject, $res_field_id, $new_field_id );


                            $reply_to = isset( $reply_to ) ? $reply_to : '';
                            if ( $reply_to == '' ) {
                                $reply_to = isset( $_SESSION['reply_to'] ) ? esc_html( $_SESSION['reply_to'] ) : '';
                            } else {
                                $reply_to = $reply_to;
                            }
                            $reply_to = str_replace( '[' . $res_field_id . ']', '[' . $new_field_id . ']', $reply_to );
                            $reply_to = $arfliteformhelper->arflite_replace_field_shortcode_import( $reply_to, $res_field_id, $new_field_id );

                            $ar_email_to = isset( $ar_email_to ) ? $ar_email_to : '';
                            if ( $ar_email_to == '' ) {
                                $ar_email_to = isset( $_SESSION['ar_email_to_org'] ) ? esc_html( $_SESSION['ar_email_to_org'] ) : '';
                            } else {
                                $ar_email_to = $ar_email_to;
                            }

                            $ar_admin_from_email = isset( $ar_admin_from_email ) ? $ar_admin_from_email : '';
                            if ( $ar_admin_from_email == '' ) {
                                $ar_admin_from_email = isset( $_SESSION['ar_admin_from_email'] ) ? esc_html( $_SESSION['ar_admin_from_email'] ) : '';
                            } else {
                                $ar_admin_from_email = $ar_admin_from_email;
                            }

                            $ar_admin_from_email = str_replace( '[' . $res_field_id . ']', '[' . $new_field_id . ']', $ar_admin_from_email );
                            $ar_admin_from_email = $arfliteformhelper->arflite_replace_field_shortcode_import( $ar_admin_from_email, $res_field_id, $new_field_id );

                            $ar_user_from_email = isset( $ar_user_from_email ) ? $ar_user_from_email : '';
                            if ( $ar_user_from_email == '' ) {
                                $ar_user_from_email = isset( $_SESSION['ar_user_from_email'] ) ? esc_html( $_SESSION['ar_user_from_email'] ) : '';
                            } else {
                                $ar_user_from_email = $ar_user_from_email;
                            }

                            $ar_user_from_email = str_replace( '[' . $res_field_id . ']', '[' . $new_field_id . ']', $ar_user_from_email );
                            $ar_user_from_email = $arfliteformhelper->arflite_replace_field_shortcode_import( $ar_user_from_email, $res_field_id, $new_field_id );

                            unset( $field_values );
                            $i++;
                        }

                        $result_diff = array_diff( $old_field_orders, $new_field_order );
                        foreach ( $result_diff as $key => $value ) {
                            $new_field_order[ $key ] = $value;
                        }


                        $result_type_diff = array_diff( $old_field_order_type, $new_field_order_type );
                        foreach ( $result_type_diff as $key => $value ) {
                            $new_field_order_type[ $key ] = $value;
                        }
                        $final_field_order = array();
                        $new_temp_field    = array();
                        foreach ( $new_field_order as $key => $value ) {
                            if ( strpos( $key, '_confirm' ) !== false ) {

                                $field_ext_extract                         = explode( '_', $key );
                                $old_value                                 = $old_field_orders[ $field_ext_extract[0] ];
                                $new_id                                    = array_search( $old_value, $new_field_order );
                                $final_field_order[ $new_id . '_confirm' ] = $value;
                                $fleld_data_confirm                        = $wpdb->get_results( $wpdb->prepare( 'SELECT field_options FROM ' . $tbl_arf_fields . ' WHERE id=%d', $new_id ) );//phpcs:ignore
                                $fleld_data_confirm_options                = json_decode( $fleld_data_confirm[0]->field_options, 1 );
                                if ( $fleld_data_confirm_options['type'] == 'email' ) {
                                    $new_temp_field[ 'confirm_email_' . $new_id ]                        = array();
                                    $new_temp_field[ 'confirm_email_' . $new_id ]['key']                 = $fleld_data_confirm_options['key'];
                                    $new_temp_field[ 'confirm_email_' . $new_id ]['order']               = $value;
                                    $new_temp_field[ 'confirm_email_' . $new_id ]['parent_field_id']     = $new_id;
                                    $new_temp_field[ 'confirm_email_' . $new_id ]['confirm_inner_class'] = $fleld_data_confirm_options['confirm_email_inner_classes'];


                                }
                            } else {
                                $final_field_order[ $key ] = $value;
                            }
                        }


                        $getForm = $wpdb->get_results( $wpdb->prepare( 'SELECT options FROM `' . $tbl_arf_forms . '` WHERE id = %d', $form_id ) ); //phpcs:ignore
                        $formOpt = maybe_unserialize( $getForm[0]->options );

                        $newOpt = maybe_unserialize( $general_option['options'] );

                        $newOpt['arf_field_order'] = json_encode( $final_field_order );

                        $general_option['options'] = maybe_serialize( $newOpt );

                        $new_values = array();



                        foreach ( maybe_unserialize( $cssoptions ) as $k => $v ) {
                            if ( ( preg_match( '/color/', $k ) || in_array( $k, array( 'arferrorbgsetting', 'arferrorbordersetting', 'arferrortextsetting' ) ) ) && ! in_array( $k, array( 'arfcheckradiocolor' ) ) ) {
                                $new_values[ $k ] = str_replace( '#', '', $v );
                            } else {
                                $new_values[ $k ] = $v;
                            }
                        }
                        $new_values1 = maybe_serialize( $new_values );


                        if ( ! empty( $new_values ) ) {
                            $query_results = $wpdb->query( $wpdb->prepare( 'update ' . $tbl_arf_forms . " set form_css = '%s' where id = '%d'", $cssoptions_db, $form_id ) ); //phpcs:ignore 

                            $use_saved = $saving = true;
                            $arfssl    = ( is_ssl() ) ? 1 : 0;

                            $loaded_field = $type_array;

                            $filename = ARFLITE_FORMPATH . '/core/arflite_css_create_main.php';

                            $target_path = ARFLITE_UPLOAD_DIR . '/maincss';

                            $css = $warn = '/* WARNING: Any changes made to this file will be lost when your ARForms lite settings are updated */';

                            $css .= "\n";
                            if ( ob_get_length() ) {
                                ob_end_flush();
                            }

                            ob_start();

                            include $filename;

                            $css .= ob_get_contents();

                            ob_end_clean();



                            $css     .= "\n " . $warn;
                            $css_file = $target_path . '/maincss_' . $form_id . '.css';

                            $css = str_replace( '##', '#', $css );
                            if ( ! file_exists( $css_file ) ) {

                                WP_Filesystem();
                                global $wp_filesystem;
                                $wp_filesystem->put_contents( $css_file, $css, 0777 );
                            } elseif ( is_writable( $css_file ) ) {

                                WP_Filesystem();
                                global $wp_filesystem;
                                $wp_filesystem->put_contents( $css_file, $css, 0777 );
                            } else {
                                $arfliteerror = __( 'File Not writable', 'arforms-form-builder' );
                            }

                            $filename1 = ARFLITE_FORMPATH . '/core/arflite_css_create_materialize.php';

                            $target_path1 = ARFLITE_UPLOAD_DIR . '/maincss';

                            $css1 = $warn1 = '/* WARNING: Any changes made to this file will be lost when your ARForms lite settings are updated */';

                            $css1 .= "\n";
                            if ( ob_get_length() ) {
                                ob_end_flush();
                            }

                            ob_start();

                            include $filename1;

                            $css1 .= ob_get_contents();

                            ob_end_clean();



                            $css1     .= "\n " . $warn1;
                            $css_file1 = $target_path1 . '/maincss_materialize_' . $form_id . '.css';

                            $css1 = str_replace( '##', '#', $css1 );
                            if ( ! file_exists( $css_file1 ) ) {

                                WP_Filesystem();
                                global $wp_filesystem;
                                $wp_filesystem->put_contents( $css_file1, $css1, 0777 );
                            } elseif ( is_writable( $css_file1 ) ) {

                                WP_Filesystem();
                                global $wp_filesystem;
                                $wp_filesystem->put_contents( $css_file1, $css1, 0777 );
                            } else {
                                $arfliteerror = __( 'File Not writable', 'arforms-form-builder' );
                            }
                        } else {

                            $query_results = true;
                        }

                        ob_start();

                        $wpdb->update(
                            $tbl_arf_forms,
                            array(
                                'options' => $general_option['options'],
                                'temp_fields' => maybe_serialize( $new_temp_field ),
                                'arf_is_lite_form' => 1,
                            ),
                            array( 'id' => $form_id )
                        );

                        $sel_rec = $wpdb->prepare( 'select options from ' . $tbl_arf_forms . ' where id = %d', $form_id ); //phpcs:ignore

                        $res_rec = $wpdb->get_results( $sel_rec, 'ARRAY_A' ); //phpcs:ignore

                        $opt                     = $res_rec[0]['options'];
                        $arf_formfield_other_css = $option_arr_new['arf_form_other_css'];
                        foreach ( $new_id_array as $id_info_arr ) {
                            $arf_formfield_other_css = stripslashes( str_replace( $id_info_arr['old_id'], $id_info_arr['new_id'], $arf_formfield_other_css ) );

                            if ( $ar_email_to == $id_info_arr['old_id'] ) {
                                $ar_email_to = $id_info_arr['new_id'];
                            }
                        }
                        $arf_form_other_css = stripslashes( str_replace( $old_form_id, $form_id, $arf_formfield_other_css ) );
                        $form_custom_css    = stripslashes( str_replace( $old_form_id, $form_id, $val_main->form_custom_css ) );

                        $form_custom_css = str_replace( '[REPLACE_SITE_URL]', site_url(), $form_custom_css );

                        $form_custom_css = str_replace( '[ENTERKEY]', '<br>', $form_custom_css );

                        $option_arr_new = maybe_unserialize( $opt );

                        $option_arr_new['form_custom_css'] = $form_custom_css;

                        $option_arr_new['arf_form_other_css'] = $arf_form_other_css;

                        $option_arr_new['ar_email_subject'] = isset( $ar_email_subject ) ? $ar_email_subject : '';

                        $option_arr_new['ar_email_message'] = isset( $ar_email_message ) ? $ar_email_message : '';

                        $option_arr_new['ar_admin_email_message'] = isset( $ar_admin_email_message ) ? $ar_admin_email_message : '';

                        $option_arr_new['ar_email_to'] = isset( $ar_email_to ) ? $ar_email_to : '';

                        $option_arr_new['ar_admin_from_email'] = isset( $ar_admin_from_email ) ? $ar_admin_from_email : '';

                        $option_arr_new['ar_user_from_email'] = isset( $ar_user_from_email ) ? $ar_user_from_email : '';

                        $option_arr_new['ar_admin_from_name'] = isset( $ar_admin_from_name ) ? $ar_admin_from_name : '';

                        $option_arr_new['admin_email_subject'] = isset( $admin_email_subject ) ? $admin_email_subject : '';

                        $option_arr_new['arf_pre_dup_field'] = isset( $arf_pre_dup_field ) ? $arf_pre_dup_field : '';

                        $option_arr_new['reply_to'] = ! empty( $reply_to ) ? $reply_to : '';

                        if ( $val_main->site_url != site_url() ) {
                            $option_arr_new['success_action'] = isset( $option_arr_new['success_action'] ) ? $option_arr_new['success_action'] : '';
                            if ( $option_arr_new['success_action'] == 'page' ) {
                                $option_arr_new['success_action'] = 'message';
                            }
                        }


                        $option_arr_new = maybe_serialize( $option_arr_new );

                        $wpdb->update( $tbl_arf_forms, array( 'options' => $option_arr_new ), array( 'id' => $form_id ) );
                        $frm_id = $form_id;
                        global $wpdb, $ARFLiteMdlDb, $tbl_arf_entries, $tbl_arf_fields;

                        if ( isset( $val_main->form_entries ) && count( $val_main->form_entries->children() ) > 0 ) {

                            include_once ARFLITE_FORMPATH . '/js/filedrag/simple_image.php';
                            global $user_ID, $wpdb;
                            $entry_values            = array();
                            $entry_values_new        = array();
                            $vls                     = array();
                            $entry_values['form_id'] = $frm_id;
                            if ( $user_ID ) {
                                $entry_values['user_id'] = $user_ID;
                            }

                            foreach ( $val_main->form_entries->children() as $key_fields => $val_fields ) {
                                $entry_values['entry_key'] = $arflitemainhelper->arflite_get_unique_key( '', $tbl_arf_entries, 'entry_key' );

                                foreach ( $val_fields as $key_field => $val_field ) {


                                    $field_nm = str_replace( '_ARF_', ' ', (string) $val_field['field_label'] );
                                    $field_nm = str_replace( '_ARF_SLASH_', '/', $field_nm );

                                    if ( $field_nm == 'Browser' ) {
                                        $entry_values['browser_info'] = (string) $val_field;
                                    } elseif ( $field_nm == 'Country' ) {
                                        $entry_values['country'] = (string) $val_field;
                                    } elseif ( $field_nm == 'Created Date' ) {
                                        $entry_values['created_date'] = (string) $val_field;
                                    } elseif ( $field_nm == 'IP Address' ) {
                                        $entry_values['ip_address'] = (string) $val_field;
                                    } elseif ( $field_nm == 'Submit Type' ) {

                                        $vls['form_display_type'] = (string) trim( $val_field );
                                    } else {
                                        $field_data = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $tbl_arf_fields . ' WHERE form_id = %d', $frm_id ) ); //phpcs:ignore
                                        foreach ( $field_data as $k => $v ) {

                                            if ( $v->name == $field_nm ) {
                                                $field_type  = $val_field->attributes();
                                                $entry_value = array();

                                                if ( strtolower( $field_type ) == 'checkbox' ) {
                                                    $values                                  = explode( '^|^', (string) $val_field );
                                                    $entry_values_new['item_meta'][ $v->id ] = array_map( 'trim', $values );
                                                } else {
                                                    $entry_values_new['item_meta'][ $v->id ] = (string) trim( $val_field );
                                                }
                                            }
                                        }
                                    }
                                    $referrerinfo                 = $arflitemainhelper->arflite_get_referer_info();
                                    $entry_values['browser_info'] = isset( $entry_values['browser_info'] ) ? $entry_values['browser_info'] : '';
                                    $entry_values['description']  = maybe_serialize(
                                        array(
                                            'browser'  => $entry_values['browser_info'],
                                            'referrer' => $referrerinfo,
                                        )
                                    );
                                }

                                $create_entry = true;
                                if ( $create_entry ) {
                                    $query_results = $wpdb->insert( $tbl_arf_entries, $entry_values );
                                }
                                if ( isset( $query_results ) && $query_results ) {
                                    $entry_id = $wpdb->insert_id;
                                    global $arflitesavedentries;
                                    $arflitesavedentries[] = (int) $entry_id;
                                    if ( isset( $vls['form_display_type'] ) && $vls['form_display_type'] != '' ) {
                                        global $wpdb;
                                        $arf_meta_insert = array(
                                            'entry_value' => sanitize_text_field( $vls['form_display_type'] ),
                                            'field_id' => intval( 0 ),
                                            'entry_id' => intval( $entry_id ),
                                            'created_date' => current_time( 'mysql' ),
                                        );
                                        $wpdb->insert( $wpdb->prefix . 'arf_entry_values', $arf_meta_insert, array( '%s', '%d', '%d', '%s' ) );

                                    }

                                    if ( isset( $entry_values_new['item_meta'] ) ) {
                                        $arfliterecordmeta->arflite_update_entry_metas( $entry_id, $entry_values_new['item_meta'] );
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
                <div id="success_message" class="arf_success_message" data-id="arflite_import_export_success_msg">
                    <div class="message_descripiton">
                        <div class="arffloatmargin"><?php echo esc_html__( 'Form is imported successfully.', 'arforms-form-builder' ); ?></div>
                        <div class="message_svg_icon">
                            <svg class="arfheightwidth14"><path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M6.075,14.407l-5.852-5.84l1.616-1.613l4.394,4.385L17.181,0.411
                                                                                l1.616,1.613L6.392,14.407H6.075z"></path></svg>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div id="error_message" class="arf_error_message" data-id="arflite_import_export_error_msg">
                    <div class="message_descripiton">
                        <div class="arffloatmargin" id=""><?php echo esc_html__( 'File is not proper.', 'arforms-form-builder' ); ?></div>
                        <div class="message_svg_icon">
                            <svg class="arfheightwidth14"><path fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></svg>
                        </div>
                    </div>
                </div>

                <?php
            }
        }
    }

    function arforms_check_export_form_data_entry_func() {
		global $wpdb, $tbl_arf_entries;

		if ( !isset( $_POST['_wpnonce_arforms'] ) || ( isset( $_POST['_wpnonce_arforms'] ) && '' != $_POST['_wpnonce_arforms'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arforms'] ), 'arforms_wp_nonce' ) ) ) {
			echo esc_attr( 'security_error' );
			die;
		}

		$arforms_all_form_id     = !empty( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : '';
		$arforms_all_form_id_arr = explode( ',', $arforms_all_form_id );

		foreach ( $arforms_all_form_id_arr as $arforms_all_form_id_arr_key => $arforms_all_form_id_arr_val ) {

			$arfdatefrom = !empty( $_POST['date_from'] ) ? sanitize_text_field( $_POST['date_from'] ) : '';
			$arfdateto = !empty( $_POST['date_to'] ) ? sanitize_text_field( $_POST['date_to'] ) : '';

			$date_from = date( 'Y-m-d 00:00:00', strtotime( $arfdatefrom ) );
			$date_to   = date( 'Y-m-d 23:59:59', strtotime( $arfdateto ) );

			$arforms_form_entry_ids = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM `' . $tbl_arf_entries . '` WHERE form_id = %d AND created_date BETWEEN %s AND %s ', $arforms_all_form_id_arr_val, $date_from, $date_to ) );//phpcs:ignore

			if ( count( $arforms_form_entry_ids ) > 0 ) {
				echo 1;
			} else {
				echo 0;
			}
		}
		die();
	}

    function arflite_export_form_data() {

        if ( isset( $_POST['s_action'] ) && ! in_array( sanitize_text_field( $_POST['s_action'] ), array( 'arf_opt_export_form', 'arf_opt_export_both' ) ) ) { //phpcs:ignore
            return false;
        }

        global $wpdb, $arflite_submit_bg_img, $arflitemainform_bg_img, $arflite_form_custom_css, $WP_Filesystem, $arflite_submit_hover_bg_img, $ARFLiteMdlDb,$arfliteformcontroller, $tbl_arf_forms, $tbl_arf_fields, $arformsmain;

        if( $arformsmain->arforms_is_pro_active() ){

            
            if( class_exists('arforms_pro_import_export') ){

                global $arforms_pro_import_export;
                $arforms_pro_import_export->arforms_pro_import_export_form();
            }
        }

        $arf_db_version = get_option( 'arflite_db_version' );

        $upload_dir     = ARFLITE_UPLOAD_DIR;
        $upload_baseurl = ARFLITE_UPLOAD_URL;

        $form_id_req    = ( isset( $_REQUEST['is_single_form'] ) && intval( $_REQUEST['is_single_form'] ) == 1 ) ? ( isset($_REQUEST['frm_add_form_id_name']) ? intval( $_REQUEST['frm_add_form_id_name'] ) : '' ) : ( isset( $_REQUEST['frm_add_form_id'] ) ? intval( $_REQUEST['frm_add_form_id'] ) : '' );

        if ( isset( $_REQUEST['export_button'] ) ) {

            if ( ! current_user_can( 'arfimportexport' ) ) {
                return false;
            }

            if ( !isset( $_REQUEST['_wpnonce_arforms'] ) || (isset( $_REQUEST['_wpnonce_arforms'] ) && '' != $_REQUEST['_wpnonce_arforms'] && ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_wpnonce_arforms'] ), 'arforms_wp_nonce' )) ) {
                return false;
            }
            
            if ( ! empty( $form_id_req ) ) {

                if ( $_REQUEST['is_single_form'] == 1 ) {
                    $form_ids = !empty( $_REQUEST['frm_add_form_id_name']) ? intval( $_REQUEST['frm_add_form_id_name'] ) : '';
                } else {
                    $arflite_request_formid = !empty( $_REQUEST['frm_add_form_id']) ? $_REQUEST['frm_add_form_id'] : array(); //phpcs:ignore
                    $arf_frm_add_form_id  = array_map( 'intval', $arflite_request_formid ); 
                    $arf_frm_add_form_ids = array();
                    if ( is_array( $arf_frm_add_form_id ) && count( $arf_frm_add_form_id ) > 0 ) {
                        foreach ( $arf_frm_add_form_id as $arf_frm_add_form_id_key => $arf_frm_add_form_id_value ) {
                            if ( $arf_frm_add_form_id_value != '' ) {
                                $arf_frm_add_form_ids[] = $arf_frm_add_form_id_value;
                            }
                        }
                    }
                    $form_ids = ( count( $arf_frm_add_form_ids ) > 0 ) ? implode( ',', $arf_frm_add_form_ids ) : '';
                }

                $res = $wpdb->get_results( 'SELECT * FROM ' . $tbl_arf_forms . ' WHERE id in (' . $form_ids . ')' ); //phpcs:ignore

                if ( ! is_array( $form_ids ) && empty( $res ) ) {

                }

                $file_name = 'ARFormslite_' . time();

                $filename = $file_name . '.txt';

                $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

                $xml .= "<forms>\n";

                foreach ( $res as $key => $result_array ) {

                    $form_id = $res[ $key ]->id;

                    $xml .= "<arformslite>\n";

                    $xml .= "\t<form id='" . $res[ $key ]->id . "'>\n";

                    $xml .= "\t<site_url>" . site_url() . "</site_url>\n";

                    $xml .= "\t<exported_site_uploads_dir>" . $upload_baseurl . "</exported_site_uploads_dir>\n";

                    $xml .= "\t<arf_db_version>" . $arf_db_version . "</arf_db_version>\n";

                    $xml .= "\t\t<general_options>\n";

                    foreach ( $result_array as $key => $value ) {

                        if ( $key == 'options' ) {
                            foreach ( maybe_unserialize( $value ) as $ky => $vl ) {
                                if ( $ky != 'before_html' ) {
                                    if ( ! is_array( $vl ) ) {
                                        if ( $ky == 'success_url' ) {
                                            $new_field[ $ky ] = $vl;

                                            $new_field[ $ky ] = str_replace( '&amp;', '[AND]', $new_field[ $ky ] );
                                        } elseif ( $ky == 'form_custom_css' ) {
                                            $arflite_form_custom_css = str_replace( site_url(), '[REPLACE_SITE_URL]', $vl );

                                            $arflite_form_custom_css = str_replace( '&lt;br /&gt;', '[ENTERKEY]', str_replace( '&lt;br/&gt;', '[ENTERKEY]', str_replace( '&lt;br&gt;', '[ENTERKEY]', str_replace( '<br />', '[ENTERKEY]', str_replace( '<br/>', '[ENTERKEY]', str_replace( '<br>', '[ENTERKEY]', trim( preg_replace( '/\s\s+/', '[ENTERKEY]', $arflite_form_custom_css ) ) ) ) ) ) ) );
                                        } elseif ( $ky == 'arf_form_other_css' ) {
                                            $new_field[ $ky ] = str_replace( '&lt;br /&gt;', '[ENTERKEY]', str_replace( '&lt;br/&gt;', '[ENTERKEY]', str_replace( '&lt;br&gt;', '[ENTERKEY]', str_replace( '<br />', '[ENTERKEY]', str_replace( '<br/>', '[ENTERKEY]', str_replace( '<br>', '[ENTERKEY]', trim( preg_replace( '/\s\s+/', '[ENTERKEY]', str_replace( site_url(), '[REPLACE_SITE_URL]', $vl ) ) ) ) ) ) ) ) );
                                        } else {
                                            $string = ( ( is_array( $vl ) && count( $vl ) > 0 ) ? $vl : str_replace( '&lt;br /&gt;', '[ENTERKEY]', str_replace( '&lt;br/&gt;', '[ENTERKEY]', str_replace( '&lt;br&gt;', '[ENTERKEY]', str_replace( '<br />', '[ENTERKEY]', str_replace( '<br/>', '[ENTERKEY]', str_replace( '<br>', '[ENTERKEY]', trim( preg_replace( '/\s\s+/', '[ENTERKEY]', $vl ) ) ) ) ) ) ) ) );

                                            $new_field[ $ky ] = $string;
                                        }
                                    } else {
                                        $new_field[ $ky ] = $vl;
                                    }
                                } else {
                                    $vl2              = '[REPLACE_BEFORE_HTML]';
                                    $new_field[ $ky ] = $vl2;
                                }
                            }
                            $value1 = json_encode( $new_field );

                            $value1 = '<![CDATA[' . $value1 . ']]>';

                            $xml .= "\t\t\t<$key>";

                            $xml .= "$value1";

                            $xml .= "</$key>\n";

                        } elseif ( $key == 'form_css' ) {

                            $form_css_arry = maybe_unserialize( $value );

                            foreach ( $form_css_arry as $form_css_key => $form_css_val ) {

                                if ( $form_css_key == 'submit_bg_img' ) {
                                    $arflite_submit_bg_img = $form_css_val;
                                } elseif ( $form_css_key == 'submit_hover_bg_img' ) {
                                    $arflite_submit_hover_bg_img = $form_css_val;
                                } elseif ( $form_css_key == 'arfmainform_bg_img' ) {
                                    $arflitemainform_bg_img = $form_css_val;
                                }
                            }

                            $xml .= "\t\t\t<$key>";

                            $new_form_css_val = json_encode( $form_css_arry );

                            $xml .= '<![CDATA[' . $new_form_css_val . ']]>';

                            $xml .= "</$key>\n";

                        } elseif ( $key == 'description' || $key == 'name' ) {

                            $value = '<![CDATA[' . $value . ']]>';

                            $xml .= "\t\t\t<$key>";

                            $xml .= "$value";

                            $xml .= "</$key>\n";

                        } elseif ( 'columns_list' == $key ) {

                            $xml .= "\t\t\t<$key>";

                            $xml .= '<![CDATA[' . $value . ']]>';

                            $xml .= "</$key>\n";

                        } else {
                            $xml .= "\t\t\t<$key>";

                            $xml .= "$value";

                            $xml .= "</$key>\n";
                        }
                    }
                    $xml .= "\t\t</general_options>\n";

                    $xml .= "\t\t<fields>\n";

                    $res_fields = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $tbl_arf_fields . ' WHERE form_id = %d', $result_array->id ) ); //phpcs:ignore

                    foreach ( $res_fields as $key_fields => $result_field_array ) {
                        $xml                .= "\t\t\t<field>\n";
                        $field_options_array = array();
                        $new_field1          = array();
                        foreach ( $result_field_array as $key_field => $value_field ) {
                            if ( $key_field == 'field_options' ) {
                                $field_options_array = json_decode( $value_field );
                                if ( json_last_error() == JSON_ERROR_NONE ) {

                                } else {
                                    $field_options_array = maybe_unserialize( $value_field );
                                }

                                foreach ( $field_options_array as $ky => $vl ) {
                                    if ( $ky != 'custom_html' ) {
                                        if ( is_object( $vl ) ) {
                                            $vl = $arfliteformcontroller->arfliteObjtoArray( $vl );
                                        }
                                        $vl = ( ( is_array( $vl ) ) ? $vl : str_replace( '&lt;br /&gt;', '[ENTERKEY]', str_replace( '&lt;br/&gt;', '[ENTERKEY]', str_replace( '&lt;br&gt;', '[ENTERKEY]', str_replace( '<br />', '[ENTERKEY]', str_replace( '<br/>', '[ENTERKEY]', str_replace( '<br>', '[ENTERKEY]', trim( preg_replace( '/\s\s+/', '[ENTERKEY]', $vl ) ) ) ) ) ) ) ) );

                                        $new_field1[ $ky ] = $vl;
                                    }
                                }
                                $value_field_ser = json_encode( $new_field1 );

                                $value_field_ser = '<![CDATA[' . $value_field_ser . ']]>';

                                $xml .= "\t\t\t\t<$key_field>";

                                $xml .= "$value_field_ser";

                                $xml .= "</$key_field>\n";

                            } else {
                                if ( $key_field == 'description' || $key_field == 'name' || $key_field == 'default_value' ) {
                                    $vl1 = '<![CDATA[' . stripslashes_deep( $value_field ) . ']]>';
                                } elseif ( $key_field == 'options' && $result_field_array->type == 'radio' ) {
                                    $vl1 = '<![CDATA[' . trim( json_encode( $value_field ), '"' ) . ']]>';
                                } elseif ( $key_field == 'options' ) {
                                    $vl1 = '<![CDATA[' . json_encode( $value_field ) . ']]>';
                                } else {
                                    $vl1 = $value_field;
                                }

                                $xml .= "\t\t\t\t<$key_field>";

                                $xml .= "$vl1";

                                $xml .= "</$key_field>\n";
                            }
                        }
                        $xml .= "\t\t\t</field>\n";
                    }
                    $xml .= "\t\t</fields>\n";

                    $xml .= "\t\t<submit_bg_img>";

                    $xml .= "$arflite_submit_bg_img";

                    $xml .= "</submit_bg_img>\n";

                    $xml .= "\t\t<submit_hover_bg_img>";

                    $xml .= "$arflite_submit_hover_bg_img";

                    $xml .= "</submit_hover_bg_img>\n";

                    $xml .= "\t\t<arfmainform_bg_img>";

                    $xml .= "$arflitemainform_bg_img";

                    $xml .= "</arfmainform_bg_img>\n";

                    $xml .= "\t\t<form_custom_css>";

                    $xml .= "$arflite_form_custom_css";

                    $xml .= "</form_custom_css>\n";

                    if ( !empty( $_REQUEST['arf_opt_export'] ) && sanitize_text_field( $_REQUEST['arf_opt_export'] ) == 'arf_opt_export_both' ) {

                        global $wpdb, $arfliteform, $arflitefield, $arflite_db_record, $arflite_style_settings, $arflitemainhelper, $arflitefieldhelper, $arfliterecordhelper,$ARFLiteMdlDb, $tbl_arf_entries;

                        $form = $arfliteform->arflitegetOne( $form_id );

                        $form_name = sanitize_title_with_dashes( $form->name );

                        $form_cols = $arflitefield->arflitegetAll( "fi.type not in ('captcha', 'html') and fi.form_id=" . $form->id, 'ORDER BY id' );

                        $entry_id = $arflitemainhelper->arflite_get_param( 'entry_id', false );

                        if ( ! empty( $_REQUEST['datepicker_from2'] ) || ! empty( $_REQUEST['datepicker_to2'] ) ) {

                            $date_from = date( 'Y-m-d', strtotime( sanitize_text_field( $_REQUEST['datepicker_from2'] ) ) );
                            $date_to   = date( 'Y-m-d', strtotime( sanitize_text_field( $_REQUEST['datepicker_to2'] ) ) );

                            $form_entry_ids = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM `' . $tbl_arf_entries . '` WHERE form_id = %d AND created_date BETWEEN %s AND %s ', $form_id, $date_from, $date_to ) ); //phpcs:ignore
                        } else {
                            $form_entry_ids = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM `' . $tbl_arf_entries . '` WHERE form_id = %d', $form_id ) ); //phpcs:ignore
                        }

                        $entry_id = '';
                        foreach ( $form_entry_ids as $frm_entry_id ) {
                            $entry_id .= $frm_entry_id->id . ',';
                        }
                        $entry_id = rtrim( $entry_id, ',' );

                        $where_clause = 'it.form_id=' . (int) $form_id;

                        $wp_date_format = apply_filters( 'arflitecsvdateformat', 'Y-m-d H:i:s' );

                        if ( $entry_id ) {

                            $where_clause .= ' and it.id in (';

                            $entry_ids = explode( ',', $entry_id );

                            foreach ( (array) $entry_ids as $k => $it ) {

                                if ( $k ) {
                                    $where_clause .= ',';
                                }

                                $where_clause .= $it;

                                unset( $k );

                                unset( $it );
                            }

                            $where_clause .= ')';
                        } elseif ( ! empty( $search ) ) {
                            $where_clause = $arfliterecordcontroller->arflite_get_search_str( $where_clause, $search, $form_id, $fid );
                        }

                        $where_clause = apply_filters( 'arflitecsvwhere', $where_clause, compact( 'form_id' ) );

                        $entries = $arflite_db_record->arflitegetAll( $where_clause, '', '', true, false );

                        $form_cols   = apply_filters( 'arflitepredisplayformcols', $form_cols, $form->id );
                        $entries     = apply_filters( 'arflitepredisplaycolsitems', $entries, $form->id );
                        $to_encoding = isset( $arflite_style_settings->csv_format ) ? $arflite_style_settings->csv_format : 'UTF-8';

                        $xml .= "\n\t\t<form_entries>\n";

                        foreach ( $entries as $entry ) {

                            global $wpdb, $ARFLiteMdlDb, $tbl_arf_entries, $tbl_arf_entry_values;

                            $get_form_submit_type = $wpdb->get_results( $wpdb->prepare( 'SELECT entry_value FROM ' . $tbl_arf_entry_values . ' WHERE entry_id = %d and field_id = %d', $entry->id, 0 ), 'ARRAY_A' ); //phpcs:ignore

                            $form_submit_type = $get_form_submit_type[0]['entry_value'];

                            $res_data = $wpdb->get_results( $wpdb->prepare( 'SELECT country, browser_info FROM ' . $tbl_arf_entries . ' WHERE id = %d', $entry->id ), 'ARRAY_A' ); //phpcs:ignore

                            $entry->country = $res_data[0]['country'];
                            $entry->browser = $res_data[0]['browser_info'];

                            $i                 = 0;
                            $size_of_form_cols = count( $form_cols );

                            $list = '';

                            $xml .= "\n\t\t\t<form_entry>\n";

                            foreach ( $form_cols as $col ) {

                                $field_value = isset( $entry->metas[ $col->id ] ) ? $entry->metas[ $col->id ] : false;

                                if ( ! $field_value && $entry->attachment_id ) {

                                    $col->field_options = maybe_unserialize( $col->field_options );
                                }

                                if ( $col->type == 'date' ) {

                                    $field_value = $arflitefieldhelper->arfliteget_date( $field_value, $wp_date_format );
                                } else {

                                    $checked_values = maybe_unserialize( $field_value );

                                    $checked_values = apply_filters( 'arflitecsvvalue', $checked_values, array( 'field' => $col ) );

                                    if ( is_array( $checked_values ) ) {

                                        if ( in_array( $col->type, array( 'checkbox', 'radio', 'select' ) ) ) {
                                            $field_value = implode( '^|^', $checked_values );
                                        } else {
                                            $field_value = implode( ',', $checked_values );
                                        }
                                    } else {

                                        $field_value = $checked_values;
                                    }

                                    $charset = get_option( 'blog_charset' );

                                    $field_value = $arfliterecordhelper->arflite_encode_value( $field_value, $charset, $to_encoding );

                                    $field_value = str_replace( '"', '""', stripslashes( $field_value ) );
                                }

                                $field_value = str_replace( array( "\r\n", "\r", "\n" ), ' <br />', $field_value );

                                if ( $size_of_form_cols == $i ) {
                                    $list .= $field_value;
                                } else {
                                    $list .= $field_value . ',';
                                }

                                $col_name = str_replace( ' ', '_ARF_', $col->name );

                                $col_name = str_replace( '/', '_ARF_SLASH_', $col_name );

                                $col_name = str_replace( '&', '&amp;', $col_name );

                                $col_name = str_replace( '"', '&quot;', $col_name );

                                $xml .= "\t\t\t\t<ARF_Field field_label=\"" . $col_name . "\" field_type='$col->type'>";

                                $xml .= '<![CDATA[' . $field_value . ']]>';

                                $xml .= "</ARF_Field>\n";

                                unset( $col );
                                unset( $field_value );

                                $i++;
                            }
                            $formatted_date = date( $wp_date_format, strtotime( $entry->created_date ) );
                            $xml           .= "\t\t\t\t<ARF_Field field_label='Created_ARF_Date'><![CDATA[{$formatted_date}]]></ARF_Field>";
                            $xml           .= "\n\t\t\t\t<ARF_Field field_label='IP_ARF_Address'><![CDATA[{$entry->ip_address}]]></ARF_Field>";
                            $xml           .= "\n\t\t\t\t<ARF_Field field_label='Entry_ARF_id'><![CDATA[{$entry->id}]]></ARF_Field>";
                            $xml           .= "\n\t\t\t\t<ARF_Field field_label='Country'><![CDATA[{$entry->country}]]></ARF_Field>";
                            $xml           .= "\n\t\t\t\t<ARF_Field field_label='Browser'><![CDATA[{$entry->browser}]]></ARF_Field>";

                            $xml .= "\n\t\t\t</form_entry>";
                            unset( $entry );
                        }

                        $xml .= "\n\t\t</form_entries>\n";
                    }

                        $xml .= "\t</form>\n\n";

                    $xml .= '</arformslite>';
                }
                $xml .= '</forms>';

                $xml = base64_encode( $xml );

                ob_start();
                ob_clean();
                header( 'Content-Type: plain/text' );
                header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
                header( 'Pragma: no-cache' );
                print( $xml ); //phpcs:ignore
                exit;
            }
        }
    }
}

global $arforms_import_export_settings;
$arforms_import_export_settings = new arforms_import_export_settings();