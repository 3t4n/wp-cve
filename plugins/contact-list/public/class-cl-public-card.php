<?php

class ContactListCard
{
    public static function getMarkup(
        $id = 0,
        $fields_string = '',
        $atts = array(),
        $is_modal = 0,
        $column = ''
    )
    {
        $s = get_option( 'contact_list_settings' );
        $c = get_post_custom( $id );
        $html = '';
        
        if ( $fields_string ) {
            $fields_arr = explode( ' ', $fields_string );
            $fields_arr_new = [];
            foreach ( $fields_arr as $field ) {
                $fields_arr_new[] = '[[' . $field . ']]';
            }
            $fields_string = implode( ' ', $fields_arr_new );
        }
        
        $available_fields = array(
            'edit_contact_button',
            'full_name',
            'job_title',
            'email',
            'send_message_button',
            'phone_numbers',
            'groups',
            'address',
            'custom_fields',
            'additional_info',
            'some_icons',
            'show_contact_button',
            'featured_image',
            'map',
            'name_prefix',
            'first_name',
            'middle_name',
            'last_name',
            'name_suffix',
            'phone',
            'linkedin_url',
            'twitter_url',
            'facebook_url',
            'address_line_1',
            'address_line_2',
            'address_line_3',
            'address_line_4',
            'custom_field_1',
            'custom_field_2',
            'custom_field_3',
            'custom_field_4',
            'custom_field_5',
            'custom_field_6',
            'groups',
            'country',
            'state',
            'city',
            'zip_code',
            'instagram_url',
            'phone_2',
            'phone_3'
        );
        $active_fields_arr = explode( ' ', $fields_string );
        foreach ( $active_fields_arr as $f ) {
            $f = ltrim( $f, '[[' );
            $f = rtrim( $f, ']]' );
            
            if ( in_array( $f, $available_fields ) ) {
                $search_for = "[[" . $f . "]]";
                
                if ( strpos( $fields_string, $search_for ) !== false ) {
                    $markup = ContactListCard::getSingleMarkup(
                        $id,
                        $f,
                        $atts,
                        $is_modal
                    );
                    $fields_string = str_replace( $search_for, $markup, $fields_string );
                }
            
            }
        
        }
        $html = $fields_string;
        return $html;
    }
    
    public static function getSingleMarkup(
        $id,
        $field,
        $atts,
        $is_modal
    )
    {
        $s = get_option( 'contact_list_settings' );
        $c = get_post_custom( $id );
        $html = '';
        $f = sanitize_title( $field );
        $field_name = $f;
        $field_name_db = '_cl_' . $f;
        switch ( $field ) {
            case 'full_name':
                $contact_fullname = '';
                
                if ( isset( $s['contact_card_title'] ) && $s['contact_card_title'] ) {
                    $fields = array(
                        'name_prefix',
                        'first_name',
                        'middle_name',
                        'last_name',
                        'name_suffix',
                        'job_title',
                        'email',
                        'phone',
                        'linkedin_url',
                        'twitter_url',
                        'facebook_url',
                        'address_line_1',
                        'address_line_2',
                        'address_line_3',
                        'address_line_4',
                        'custom_field_1',
                        'custom_field_2',
                        'custom_field_3',
                        'custom_field_4',
                        'custom_field_5',
                        'custom_field_6',
                        'groups',
                        'country',
                        'state',
                        'city',
                        'zip_code',
                        'instagram_url',
                        'phone_2',
                        'phone_3'
                    );
                    $contact_card_title = sanitize_text_field( $s['contact_card_title'] );
                    $contact_fullname = $contact_card_title;
                    foreach ( $fields as $f ) {
                        $search_for = '[' . $f . ']';
                        
                        if ( strpos( $contact_card_title, $search_for ) !== false ) {
                            $field_name = '_cl_' . $f;
                            $field_value = '';
                            if ( isset( $c[$field_name][0] ) && $c[$field_name][0] ) {
                                $field_value = sanitize_text_field( $c[$field_name][0] );
                            }
                            $contact_fullname = str_replace( $search_for, $field_value, $contact_fullname );
                        }
                    
                    }
                } elseif ( isset( $s['last_name_before_first_name'] ) ) {
                    $prefix = '';
                    $first_name = '';
                    $middle_name = '';
                    $last_name = '';
                    $suffix = '';
                    if ( isset( $c['_cl_name_prefix'][0] ) && $c['_cl_name_prefix'][0] ) {
                        $prefix = sanitize_text_field( $c['_cl_name_prefix'][0] ) . ' ';
                    }
                    if ( isset( $c['_cl_first_name'][0] ) && $c['_cl_first_name'][0] ) {
                        $first_name = sanitize_text_field( $c['_cl_first_name'][0] ) . ' ';
                    }
                    if ( isset( $c['_cl_middle_name'][0] ) && $c['_cl_middle_name'][0] ) {
                        $middle_name = sanitize_text_field( $c['_cl_middle_name'][0] ) . ' ';
                    }
                    if ( isset( $c['_cl_last_name'][0] ) && $c['_cl_last_name'][0] ) {
                        $last_name = sanitize_text_field( $c['_cl_last_name'][0] ) . ' ';
                    }
                    if ( isset( $c['_cl_name_suffix'][0] ) && $c['_cl_name_suffix'][0] ) {
                        $suffix = sanitize_text_field( $c['_cl_name_suffix'][0] );
                    }
                    $contact_fullname = rtrim( $prefix . $last_name . $first_name . $middle_name . $suffix );
                    $text = rtrim( $prefix . $first_name . $middle_name . $last_name . $suffix );
                    $html .= '<div class="contact-list-hidden-name">' . sanitize_text_field( $text ) . '</div>';
                } else {
                    $prefix = '';
                    $first_name = '';
                    $middle_name = '';
                    $last_name = '';
                    $suffix = '';
                    if ( isset( $c['_cl_name_prefix'][0] ) && $c['_cl_name_prefix'][0] ) {
                        $prefix = sanitize_text_field( $c['_cl_name_prefix'][0] ) . ' ';
                    }
                    if ( isset( $c['_cl_first_name'][0] ) && $c['_cl_first_name'][0] ) {
                        $first_name = sanitize_text_field( $c['_cl_first_name'][0] ) . ' ';
                    }
                    if ( isset( $c['_cl_middle_name'][0] ) && $c['_cl_middle_name'][0] ) {
                        $middle_name = sanitize_text_field( $c['_cl_middle_name'][0] ) . ' ';
                    }
                    if ( isset( $c['_cl_last_name'][0] ) && $c['_cl_last_name'][0] ) {
                        $last_name = sanitize_text_field( $c['_cl_last_name'][0] ) . ' ';
                    }
                    if ( isset( $c['_cl_name_suffix'][0] ) && $c['_cl_name_suffix'][0] ) {
                        $suffix = sanitize_text_field( $c['_cl_name_suffix'][0] );
                    }
                    $contact_fullname = rtrim( $prefix . $first_name . $middle_name . $last_name . $suffix );
                    $text = rtrim( $prefix . $last_name . $first_name . $middle_name . $suffix );
                    $html .= '<div class="contact-list-hidden-name">' . sanitize_text_field( $text ) . '</div>';
                }
                
                $html .= '<span class="contact-list-contact-name">' . sanitize_text_field( $contact_fullname ) . '</span>';
                break;
            case 'edit_contact_button':
                break;
            case 'job_title':
                if ( isset( $c[$field_name_db] ) ) {
                    $html .= '<span class="contact-list-job-title">' . sanitize_text_field( $c[$field_name_db][0] ) . '</span>';
                }
                break;
            case 'email':
                
                if ( isset( $c['_cl_email'][0] ) && is_email( $c['_cl_email'][0] ) ) {
                    $mailto = sanitize_email( $c['_cl_email'][0] );
                    if ( isset( $c['_cl_email'][0] ) && is_email( $c['_cl_email'][0] ) && !isset( $s['hide_contact_email'] ) ) {
                        $html .= '<span class="contact-list-email">' . (( $c['_cl_email'][0] ? '<a href="' . esc_url_raw( 'mailto:' . antispambot( $mailto ) ) . '">' . sanitize_text_field( antispambot( $mailto ) ) . '</a>' : '' )) . '</span>';
                    }
                }
                
                break;
            case 'send_message_button':
                $email_valid = isset( $c['_cl_email'][0] ) && is_email( $c['_cl_email'][0] );
                $notify_emails = isset( $c['_cl_notify_emails'] ) && $c['_cl_notify_emails'][0];
                
                if ( $email_valid || $notify_emails ) {
                    $contact_fullname = '';
                    
                    if ( isset( $s['contact_card_title'] ) && $s['contact_card_title'] ) {
                        $fields = array(
                            'first_name',
                            'last_name',
                            'job_title',
                            'email',
                            'phone',
                            'linkedin_url',
                            'twitter_url',
                            'facebook_url',
                            'address_line_1',
                            'address_line_2',
                            'address_line_3',
                            'address_line_4',
                            'custom_field_1',
                            'custom_field_2',
                            'custom_field_3',
                            'custom_field_4',
                            'custom_field_5',
                            'custom_field_6',
                            'groups',
                            'country',
                            'state',
                            'city',
                            'zip_code',
                            'instagram_url',
                            'phone_2',
                            'phone_3'
                        );
                        $contact_card_title = sanitize_text_field( $s['contact_card_title'] );
                        $contact_fullname = $contact_card_title;
                        foreach ( $fields as $f ) {
                            $search_for = '[' . $f . ']';
                            
                            if ( strpos( $contact_card_title, $search_for ) !== false ) {
                                $field_name = '_cl_' . $f;
                                $field_value = sanitize_text_field( $c[$field_name][0] );
                                $contact_fullname = str_replace( $search_for, $field_value, $contact_fullname );
                            }
                        
                        }
                    } elseif ( isset( $s['last_name_before_first_name'] ) ) {
                        $contact_fullname = $c['_cl_last_name'][0] . (( isset( $c['_cl_first_name'][0] ) ? ' ' . $c['_cl_first_name'][0] : '' ));
                    } else {
                        $contact_fullname = (( isset( $c['_cl_first_name'][0] ) ? $c['_cl_first_name'][0] . ' ' : '' )) . $c['_cl_last_name'][0];
                    }
                    
                    if ( !isset( $s['hide_send_email_button'] ) ) {
                        $html .= '<span class="contact-list-send-email cl-dont-print"><a href="" data-id="' . $id . '" data-name="' . ContactListHelpers::sanitize_attr_value( $contact_fullname ) . '">' . ContactListHelpers::getTextV2( 'text_send_message', 'Send message' ) . ' &raquo;</a></span>';
                    }
                }
                
                break;
            case 'phone_numbers':
                $hide_phone_numbers = 0;
                if ( !$hide_phone_numbers ) {
                    
                    if ( isset( $c['_cl_phone'][0] ) && $c['_cl_phone'][0] ) {
                        $phone_org = sanitize_text_field( $c['_cl_phone'][0] );
                        $phone_href = preg_replace( '/[^0-9\\,]/', '', $phone_org );
                        $html .= '<span class="contact-list-phone contact-list-phone-1">';
                        if ( isset( $s['show_titles_above_phone_numbers'] ) ) {
                            $html .= '<span class="contact-list-phone-title">' . ContactListHelpers::getText( 'phone_title', __( 'Phone', 'contact-list' ) ) . '</span>';
                        }
                        $html .= '<a href="tel:' . $phone_href . '">' . $phone_org . '</a>';
                        $html .= '</span>';
                    }
                
                }
                break;
            case 'groups':
                
                if ( isset( $s['contact_show_groups'] ) ) {
                    $terms = get_the_terms( $id, 'contact-group' );
                    
                    if ( $terms ) {
                        $html .= '<span class="contact-list-contact-groups-v2-title">' . ContactListHelpers::getText( 'contact_groups_title', __( 'Groups', 'contact-list' ) ) . '</span>';
                        $html .= '<div class="contact-list-contact-groups-v2">';
                        foreach ( $terms as $term ) {
                            $t_id = intval( $term->term_id );
                            $custom_fields = get_option( "taxonomy_term_{$t_id}" );
                            if ( !isset( $custom_fields['hide_group'] ) ) {
                                $html .= '<span class="contact-list-card-group">' . sanitize_text_field( $term->name ) . '</span>';
                            }
                        }
                        $html .= '</div>';
                    }
                
                }
                
                break;
            case 'address':
                
                if ( isset( $c['_cl_address_line_1'][0] ) || isset( $c['_cl_country'][0] ) || isset( $c['_cl_state'][0] ) ) {
                    $html .= '<div class="contact-list-address">';
                    if ( !isset( $s['hide_address_title'] ) ) {
                        $html .= '<span class="contact-list-address-title">' . (( isset( $s['address_title'] ) && $s['address_title'] ? sanitize_text_field( $s['address_title'] ) : sanitize_text_field( __( 'Address', 'contact-list' ) ) )) . '</span>';
                    }
                    if ( isset( $c['_cl_address_line_1'][0] ) && $c['_cl_address_line_1'][0] ) {
                        $html .= '<span class="contact-list-address-line-1">' . sanitize_text_field( $c['_cl_address_line_1'][0] ) . '</span>';
                    }
                    if ( isset( $c['_cl_address_line_2'][0] ) && $c['_cl_address_line_2'][0] ) {
                        $html .= '<span class="contact-list-address-line-2">' . sanitize_text_field( $c['_cl_address_line_2'][0] ) . '</span>';
                    }
                    if ( isset( $c['_cl_address_line_3'][0] ) && $c['_cl_address_line_3'][0] ) {
                        $html .= '<span class="contact-list-address-line-3">' . sanitize_text_field( $c['_cl_address_line_3'][0] ) . '</span>';
                    }
                    if ( isset( $c['_cl_address_line_4'][0] ) && $c['_cl_address_line_4'][0] ) {
                        $html .= '<span class="contact-list-address-line-4">' . sanitize_text_field( $c['_cl_address_line_4'][0] ) . '</span>';
                    }
                    
                    if ( isset( $c['_cl_country'][0] ) && $c['_cl_country'][0] || isset( $c['_cl_state'][0] ) && $c['_cl_state'][0] || isset( $c['_cl_city'][0] ) && $c['_cl_city'][0] || isset( $c['_cl_zip_code'][0] ) && $c['_cl_zip_code'][0] ) {
                        $html .= '<span class="contact-list-address-country-and-state">';
                        $zip_code_first = 0;
                        $zip_code_last = 0;
                        
                        if ( !isset( $s['move_zip_after_state'] ) && isset( $c['_cl_zip_code'][0] ) && $c['_cl_zip_code'][0] ) {
                            $html .= sanitize_text_field( $c['_cl_zip_code'][0] );
                            $zip_code_first = 1;
                        }
                        
                        
                        if ( isset( $c['_cl_city'][0] ) && $c['_cl_city'][0] ) {
                            if ( $zip_code_first ) {
                                $html .= ' ';
                            }
                            $html .= sanitize_text_field( $c['_cl_city'][0] );
                        }
                        
                        
                        if ( isset( $c['_cl_state'][0] ) && $c['_cl_state'][0] ) {
                            if ( isset( $c['_cl_city'][0] ) && $c['_cl_city'][0] ) {
                                $html .= ', ';
                            }
                            $html .= sanitize_text_field( $c['_cl_state'][0] );
                        }
                        
                        
                        if ( isset( $s['move_zip_after_state'] ) && isset( $c['_cl_zip_code'][0] ) && $c['_cl_zip_code'][0] ) {
                            if ( isset( $c['_cl_state'][0] ) && $c['_cl_state'][0] ) {
                                $html .= ' ';
                            }
                            $html .= sanitize_text_field( $c['_cl_zip_code'][0] );
                            $zip_code_last = 1;
                        }
                        
                        
                        if ( isset( $c['_cl_country'][0] ) && $c['_cl_country'][0] ) {
                            
                            if ( isset( $c['_cl_state'][0] ) && $c['_cl_state'][0] ) {
                                $html .= ', ';
                            } elseif ( isset( $c['_cl_city'][0] ) && $c['_cl_city'][0] ) {
                                $html .= ', ';
                            }
                            
                            $html .= sanitize_text_field( $c['_cl_country'][0] );
                        }
                        
                        $html .= '</span>';
                    }
                    
                    $html .= '</div>';
                }
                
                break;
            case 'custom_fields':
                $custom_fields_cnt = 1 + 1;
                $html .= '<div class="contact-list-custom-fields-container">';
                for ( $n = 1 ;  $n < $custom_fields_cnt ;  $n++ ) {
                    if ( isset( $s['custom_field_' . $n . '_hide_from_contact_card'] ) ) {
                        continue;
                    }
                    $url = '@(http)?(s)?(://)?(([a-zA-Z])([-\\w]+\\.)+([^\\s\\.]+[^\\s]*)+[^,.\\s])@';
                    
                    if ( isset( $c['_cl_custom_field_' . $n][0] ) && $c['_cl_custom_field_' . $n][0] ) {
                        $cf_value = sanitize_text_field( $c['_cl_custom_field_' . $n][0] );
                        
                        if ( is_email( $cf_value ) ) {
                            $mailto = sanitize_email( $cf_value );
                            $mailto_obs = antispambot( $mailto );
                            $cf_value = '<a href="mailto:' . sanitize_text_field( $mailto_obs ) . '">' . sanitize_text_field( $mailto_obs ) . '</a>';
                        } else {
                            $link_title = '';
                            if ( isset( $s['custom_field_' . $n . '_link_text'] ) && $s['custom_field_' . $n . '_link_text'] ) {
                                $link_title = sanitize_text_field( $s['custom_field_' . $n . '_link_text'] );
                            }
                            $disable_automatic_linking = 0;
                            if ( !$disable_automatic_linking ) {
                                
                                if ( $link_title ) {
                                    $cf_value = preg_replace( $url, '<a href="http$2://$4" target="_blank" title="$0">' . $link_title . '</a>', $cf_value );
                                } else {
                                    $cf_value = preg_replace( $url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $cf_value );
                                }
                            
                            }
                        }
                        
                        
                        if ( isset( $s['custom_field_' . $n . '_icon'] ) && $s['custom_field_' . $n . '_icon'] ) {
                            $html .= '<div class="contact-list-custom-field-' . $n . ' contact-list-custom-field-with-icon">';
                            $html .= '<i class="fa ' . sanitize_html_class( $s['custom_field_' . $n . '_icon'] ) . '" aria-hidden="true"></i><span>' . wp_kses_post( $cf_value ) . '</span>';
                            $html .= '</div>';
                        } else {
                            $html .= '<div class="contact-list-custom-field-' . $n . '">';
                            $html .= ( isset( $s['custom_field_' . $n . '_title'] ) && $s['custom_field_' . $n . '_title'] ? '<strong>' . sanitize_text_field( $s['custom_field_' . $n . '_title'] ) . '</strong>' : '' );
                            $html .= wp_kses_post( $cf_value );
                            $html .= '</div>';
                        }
                    
                    }
                
                }
                $html .= '</div>';
                break;
            case 'additional_info':
                if ( isset( $c['_cl_description'][0] ) && $c['_cl_description'][0] ) {
                    
                    if ( isset( $s['contact_card_additional_info_only_in_modal'] ) && !$is_modal ) {
                        // Don't show
                    } else {
                        $html .= '<div class="contact-list-description">';
                        if ( !isset( $s['hide_additional_info_title'] ) ) {
                            $html .= '<span class="contact-list-description-title">' . (( isset( $s['additional_info_title'] ) && $s['additional_info_title'] ? sanitize_text_field( $s['additional_info_title'] ) : sanitize_text_field( __( 'Additional information', 'contact-list' ) ) )) . '</span>';
                        }
                        $html .= wp_kses_post( $c['_cl_description'][0] ) . '</div>';
                    }
                
                }
                break;
            case 'some_icons':
                $html .= '<div class="contact-list-some-elements">';
                if ( isset( $c['_cl_facebook_url'][0] ) && $c['_cl_facebook_url'][0] ) {
                    $html .= ( $c['_cl_facebook_url'][0] ? '<a href="' . esc_url_raw( $c['_cl_facebook_url'][0] ) . '" target="_blank"><img src="' . esc_url_raw( plugins_url( '../img/facebook.svg', __FILE__ ) ) . '"  alt="' . ContactListHelpers::sanitize_attr_value( __( 'Facebook', 'contact-list' ) ) . '" /></a>' : '' );
                }
                if ( isset( $c['_cl_instagram_url'][0] ) && $c['_cl_instagram_url'][0] ) {
                    $html .= ( $c['_cl_instagram_url'][0] ? '<a href="' . esc_url_raw( $c['_cl_instagram_url'][0] ) . '" target="_blank"><img src="' . esc_url_raw( plugins_url( '../img/instagram.svg', __FILE__ ) ) . '" alt="' . ContactListHelpers::sanitize_attr_value( __( 'Instagram', 'contact-list' ) ) . '" /></a>' : '' );
                }
                if ( isset( $c['_cl_twitter_url'][0] ) && $c['_cl_twitter_url'][0] ) {
                    $html .= ( $c['_cl_twitter_url'][0] ? '<a href="' . esc_url_raw( $c['_cl_twitter_url'][0] ) . '" target="_blank"><img src="' . esc_url_raw( plugins_url( '../img/x.svg', __FILE__ ) ) . '" alt="' . ContactListHelpers::sanitize_attr_value( __( 'X', 'contact-list' ) ) . '" /></a>' : '' );
                }
                if ( isset( $c['_cl_linkedin_url'][0] ) && $c['_cl_linkedin_url'][0] ) {
                    $html .= ( $c['_cl_linkedin_url'][0] ? '<a href="' . esc_url_raw( $c['_cl_linkedin_url'][0] ) . '" target="_blank"><img src="' . esc_url_raw( plugins_url( '../img/linkedin.svg', __FILE__ ) ) . '" alt="' . ContactListHelpers::sanitize_attr_value( __( 'LinkedIn', 'contact-list' ) ) . '" /></a>' : '' );
                }
                $html .= '<hr class="clear" /></div>';
                break;
            case 'show_contact_button':
                if ( !$is_modal && isset( $s['contact_card_show_modal_button'] ) && isset( $c['_cl_description'][0] ) && $c['_cl_description'][0] ) {
                    $html .= '<a href="" class="contact-list-show-contact contact-list-show-contact-button cl-dont-print" data-contact-id="' . intval( $id ) . '">' . esc_html( ContactListHelpers::getTextV2( 'text_contact_card_modal_button', __( 'More info', 'contact-list' ) ) ) . '</a>';
                }
                break;
            case 'featured_image':
                $featured_img_url = esc_url_raw( get_the_post_thumbnail_url( $id, 'contact-list-contact' ) );
                
                if ( $featured_img_url ) {
                    $featured_img_id = intval( get_post_thumbnail_id( $id ) );
                    $featured_img_alt = sanitize_text_field( get_post_meta( $featured_img_id, '_wp_attachment_image_alt', true ) );
                    $html .= '<div class="contact-list-image ' . (( isset( $s['contact_image_style'] ) && $s['contact_image_style'] ? 'contact-list-image-' . ContactListHelpers::sanitize_attr_value( $s['contact_image_style'] ) : '' )) . ' ' . (( isset( $s['contact_image_shadow'] ) && $s['contact_image_shadow'] ? 'contact-list-image-shadow' : '' )) . '"><img src="' . esc_url_raw( $featured_img_url ) . '" alt="' . ContactListHelpers::sanitize_attr_value( $featured_img_alt ) . '" /></div>';
                }
                
                break;
            case 'map':
                
                if ( isset( $c['_cl_map_iframe'][0] ) && $c['_cl_map_iframe'][0] ) {
                    $iframe_code = $c['_cl_map_iframe'][0];
                    $iframeRegex = '/<iframe[^>]*>(.*?)<\\/iframe>/si';
                    $strippedHtml = '';
                    if ( preg_match( $iframeRegex, $iframe_code, $matches ) ) {
                        $strippedHtml = $matches[0];
                    }
                    
                    if ( $strippedHtml ) {
                        $html .= '<div class="contact-list-map-container">';
                        $html .= $strippedHtml;
                        $html .= '</div>';
                    }
                
                }
                
                break;
            default:
                $field = sanitize_title( $field );
                $fields = array(
                    'name_prefix',
                    'first_name',
                    'middle_name',
                    'last_name',
                    'name_suffix',
                    'job_title',
                    'email',
                    'phone',
                    'linkedin_url',
                    'twitter_url',
                    'facebook_url',
                    'address_line_1',
                    'address_line_2',
                    'address_line_3',
                    'address_line_4',
                    'custom_field_1',
                    'custom_field_2',
                    'custom_field_3',
                    'custom_field_4',
                    'custom_field_5',
                    'custom_field_6',
                    'groups',
                    'country',
                    'state',
                    'city',
                    'zip_code',
                    'instagram_url',
                    'phone_2',
                    'phone_3'
                );
                
                if ( in_array( $field, $fields ) ) {
                    $field_name = '_cl_' . $field;
                    if ( isset( $c[$field_name][0] ) && $c[$field_name][0] ) {
                        
                        if ( $field == 'phone' || $field == 'phone_2' || $field == 'phone_3' ) {
                            $n = 0;
                            $field_title = '';
                            
                            if ( $field == 'phone' ) {
                                $n = 1;
                                $field_title = ContactListHelpers::getText( 'phone_title', __( 'Phone', 'contact-list' ) );
                            } elseif ( $field == 'phone_2' ) {
                                $n = 2;
                                $field_title = ContactListHelpers::getText( 'phone_2_title', __( 'Phone 2', 'contact-list' ) );
                            } elseif ( $field == 'phone_3' ) {
                                $n = 3;
                                $field_title = ContactListHelpers::getText( 'phone_3_title', __( 'Phone 3', 'contact-list' ) );
                            }
                            
                            
                            if ( isset( $c[$field_name][0] ) && $c[$field_name][0] ) {
                                $phone_org = sanitize_text_field( $c[$field_name][0] );
                                $phone_href = preg_replace( '/[^0-9\\,]/', '', $phone_org );
                                $html .= '<span class="contact-list-phone contact-list-phone-' . $n . '">';
                                if ( isset( $s['show_titles_above_phone_numbers'] ) ) {
                                    $html .= '<span class="contact-list-phone-title">' . $field_title . '</span>';
                                }
                                $html .= '<a href="tel:' . $phone_href . '">' . $phone_org . '</a>';
                                $html .= '</span>';
                            }
                        
                        } elseif ( strpos( $field, 'custom_field_' ) !== false ) {
                            $n = (int) filter_var( $field, FILTER_SANITIZE_NUMBER_INT );
                            if ( isset( $s['custom_field_' . $n . '_hide_from_contact_card'] ) ) {
                                break;
                            }
                            $html .= '<div class="contact-list-custom-field-' . $n . '-container">';
                            $url = '@(http)?(s)?(://)?(([a-zA-Z])([-\\w]+\\.)+([^\\s\\.]+[^\\s]*)+[^,.\\s])@';
                            
                            if ( isset( $c['_cl_custom_field_' . $n][0] ) && $c['_cl_custom_field_' . $n][0] ) {
                                $cf_value = sanitize_text_field( $c['_cl_custom_field_' . $n][0] );
                                
                                if ( is_email( $cf_value ) ) {
                                    $mailto = sanitize_email( $cf_value );
                                    $mailto_obs = antispambot( $mailto );
                                    $cf_value = '<a href="mailto:' . sanitize_text_field( $mailto_obs ) . '">' . sanitize_text_field( $mailto_obs ) . '</a>';
                                } else {
                                    $link_title = '';
                                    if ( isset( $s['custom_field_' . $n . '_link_text'] ) && $s['custom_field_' . $n . '_link_text'] ) {
                                        $link_title = sanitize_text_field( $s['custom_field_' . $n . '_link_text'] );
                                    }
                                    $disable_automatic_linking = 0;
                                    if ( !$disable_automatic_linking ) {
                                        
                                        if ( $link_title ) {
                                            $cf_value = preg_replace( $url, '<a href="http$2://$4" target="_blank" title="$0">' . $link_title . '</a>', $cf_value );
                                        } else {
                                            $cf_value = preg_replace( $url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $cf_value );
                                        }
                                    
                                    }
                                }
                                
                                
                                if ( isset( $s['custom_field_' . $n . '_icon'] ) && $s['custom_field_' . $n . '_icon'] ) {
                                    $html .= '<div class="contact-list-custom-field-' . $n . ' contact-list-custom-field-with-icon">';
                                    $html .= '<i class="fa ' . sanitize_html_class( $s['custom_field_' . $n . '_icon'] ) . '" aria-hidden="true"></i><span>' . wp_kses_post( $cf_value ) . '</span>';
                                    $html .= '</div>';
                                } else {
                                    $html .= '<div class="contact-list-custom-field-' . $n . '">';
                                    $html .= ( isset( $s['custom_field_' . $n . '_title'] ) && $s['custom_field_' . $n . '_title'] ? '<strong>' . sanitize_text_field( $s['custom_field_' . $n . '_title'] ) . '</strong>' : '' );
                                    $html .= wp_kses_post( $cf_value );
                                    $html .= '</div>';
                                }
                            
                            }
                            
                            $html .= '</div>';
                        } else {
                            $html .= '<div class="contact-list-card-' . $field . '-value">' . sanitize_text_field( $c[$field_name][0] ) . '</div>';
                        }
                    
                    }
                }
                
                break;
        }
        return $html;
    }

}