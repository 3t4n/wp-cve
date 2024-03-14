<?php

class ContactListPublicHelpersSimple
{
    public static function contactListSimpleMarkup(
        $wp_query,
        $include_children = 0,
        $atts = array(),
        $generate_modals = 0
    )
    {
        $s = get_option( 'contact_list_settings' );
        $html = '';
        $extra_class = '';
        if ( isset( $s['simple_list_preserve_table_on_mobile'] ) ) {
            $extra_class = ' contact-list-mobile-table';
        }
        $html .= '<div class="contact-list-search">';
        $html .= '<div class="contact-list-simple-list-container">';
        $html .= '<div class="contact-list-simple-list' . $extra_class . '">';
        if ( isset( $s['simple_list_show_titles_for_columns'] ) ) {
            $html .= ContactListPublicHelpersSimple::contactListSimpleMarkupTitles( $atts );
        }
        if ( $wp_query->have_posts() ) {
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                $id = get_the_id();
                $html .= ContactListPublicHelpersSimple::singleContactSimpleMarkup( $id, 0, $atts );
            }
        }
        $html .= '</div>';
        $html .= '</div><hr class="clear" />';
        $html .= '<div class="contact-list-simple-nothing-found">';
        $html .= ContactListHelpers::getText( 'text_sr_no_contacts_found', __( 'No contacts found.', 'contact-list' ) );
        $html .= '</div>';
        $html .= '</div>';
        wp_reset_postdata();
        return $html;
    }
    
    public static function contactListSimpleMarkupTitles( $atts )
    {
        $s = get_option( 'contact_list_settings' );
        $html = '';
        $html .= '<div class="contact-list-simple-list-row">';
        $simple_list_fields_default_titles = [
            'full_name'      => sanitize_text_field( __( 'Full name', 'contact-list' ) ),
            'name_prefix'    => sanitize_text_field( __( 'Prefix', 'contact-list' ) ),
            'first_name'     => sanitize_text_field( __( 'First name', 'contact-list' ) ),
            'middle_name'    => sanitize_text_field( __( 'Middle name', 'contact-list' ) ),
            'last_name'      => sanitize_text_field( __( 'Last Name', 'contact-list' ) ),
            'name_suffix'    => sanitize_text_field( __( 'Suffix', 'contact-list' ) ),
            'job_title'      => sanitize_text_field( __( 'Job title', 'contact-list' ) ),
            'phone'          => sanitize_text_field( __( 'Phone 1', 'contact-list' ) ),
            'phone_2'        => sanitize_text_field( __( 'Phone 2', 'contact-list' ) ),
            'phone_3'        => sanitize_text_field( __( 'Phone 3', 'contact-list' ) ),
            'email'          => sanitize_text_field( __( 'Email', 'contact-list' ) ),
            'some_icons'     => sanitize_text_field( __( 'Some icons', 'contact-list' ) ),
            'country'        => sanitize_text_field( __( 'Country', 'contact-list' ) ),
            'state'          => sanitize_text_field( __( 'State', 'contact-list' ) ),
            'city'           => sanitize_text_field( __( 'City', 'contact-list' ) ),
            'zip_code'       => sanitize_text_field( __( 'ZIP Code', 'contact-list' ) ),
            'address_line_1' => sanitize_text_field( __( 'Address Line 1', 'contact-list' ) ),
            'address_line_2' => sanitize_text_field( __( 'Address Line 2', 'contact-list' ) ),
            'address_line_3' => sanitize_text_field( __( 'Address Line 3', 'contact-list' ) ),
            'address_line_4' => sanitize_text_field( __( 'Address Line 4', 'contact-list' ) ),
            'custom_field_1' => sanitize_text_field( __( 'Custom field 1', 'contact-list' ) ),
            'custom_field_2' => sanitize_text_field( __( 'Custom field 2', 'contact-list' ) ),
            'custom_field_3' => sanitize_text_field( __( 'Custom field 3', 'contact-list' ) ),
            'custom_field_4' => sanitize_text_field( __( 'Custom field 4', 'contact-list' ) ),
            'custom_field_5' => sanitize_text_field( __( 'Custom field 5', 'contact-list' ) ),
            'custom_field_6' => sanitize_text_field( __( 'Custom field 6', 'contact-list' ) ),
            'custom_url_1'   => sanitize_text_field( __( 'Custom URL 1', 'contact-list' ) ),
            'custom_url_2'   => sanitize_text_field( __( 'Custom URL 2', 'contact-list' ) ),
            'description'    => sanitize_text_field( __( 'Additional information', 'contact-list' ) ),
            'category'       => sanitize_text_field( __( 'Category', 'contact-list' ) ),
            'send_message'   => sanitize_text_field( __( 'Send message', 'contact-list' ) ),
        ];
        $simple_list_fields = [];
        
        if ( sizeof( $simple_list_fields ) > 1 ) {
        } else {
            $contact_fullname = '';
            $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-name contact-list-simple-list-col-title"><span>' . ContactListHelpers::getText( 'name_title', __( 'Name', 'contact-list' ) ) . '</span></div>';
            
            if ( !isset( $s['simple_list_hide_job_title'] ) ) {
                $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-title"><span>';
                $html .= ContactListHelpers::getText( 'job_title_title', __( 'Job title', 'contact-list' ) );
                $html .= '</span></div>';
            }
            
            
            if ( !isset( $s['simple_list_hide_email'] ) ) {
                $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-title"><span>';
                $html .= ContactListHelpers::getText( 'email_title', __( 'Email', 'contact-list' ) );
                $html .= '</span></div>';
            }
            
            
            if ( !isset( $s['simple_list_hide_phone_1'] ) ) {
                $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-title"><span>';
                $html .= ContactListHelpers::getText( 'phone_title', __( 'Phone', 'contact-list' ) );
                $html .= '</span></div>';
                
                if ( isset( $s['simple_list_show_extra_call_button'] ) ) {
                    $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-title contact-list-simple-list-col-title-phone-call-button"><span>';
                    $html .= '</span></div>';
                }
            
            }
            
            
            if ( isset( $s['simple_list_show_address_line_1'] ) ) {
                $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-title"><span>';
                $html .= ContactListHelpers::getText( 'address_title', __( 'Address', 'contact-list' ) );
                $html .= '</span></div>';
            }
            
            
            if ( isset( $s['simple_list_show_city'] ) ) {
                $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-title"><span>';
                $html .= ContactListHelpers::getText( 'city_title', __( 'City', 'contact-list' ) );
                $html .= '</span></div>';
            }
            
            
            if ( isset( $s['simple_list_show_zip_code'] ) ) {
                $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-title"><span>';
                $html .= ContactListHelpers::getText( 'zip_code_title', __( 'ZIP Code', 'contact-list' ) );
                $html .= '</span></div>';
            }
            
            
            if ( !isset( $s['simple_list_hide_some_links'] ) ) {
                $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-some contact-list-simple-list-col-title"><span>';
                $html .= sanitize_text_field( __( 'Social media', 'contact-list' ) );
                $html .= '</span></div>';
            }
            
            $custom_fields_cnt = 1 + 1;
            for ( $n = 1 ;  $n < $custom_fields_cnt ;  $n++ ) {
                
                if ( isset( $s['simple_list_show_custom_field_' . $n] ) ) {
                    $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-title"><span>';
                    $cf_field_title = ( isset( $s['custom_field_' . $n . '_title'] ) && $s['custom_field_' . $n . '_title'] ? $s['custom_field_' . $n . '_title'] : '' );
                    $html .= sanitize_text_field( $cf_field_title );
                    $html .= '</span></div>';
                }
            
            }
            
            if ( isset( $s['simple_list_show_category'] ) ) {
                $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-title"><span>';
                $html .= ContactListHelpers::getText( 'category_title', __( 'Category', 'contact-list' ) );
                $html .= '</span></div>';
            }
            
            
            if ( isset( $s['simple_list_show_send_message'] ) ) {
                $html .= '<div class="contact-list-simple-list-col cl-align-right"><span>';
                $html .= '';
                $html .= '</span></div>';
            }
        
        }
        
        $html .= '</div>';
        return $html;
    }
    
    public static function singleContactSimpleMarkup( $id, $showGroups = 0, $atts = array() )
    {
        $id = intval( $id );
        $s = get_option( 'contact_list_settings' );
        $c = get_post_custom( $id );
        $html = '';
        $html .= '<div class="contact-list-simple-list-row contact-list-simple-list-row-data">';
        $contact_fullname = '';
        
        if ( isset( $s['last_name_before_first_name'] ) ) {
            $contact_fullname = $c['_cl_last_name'][0] . (( isset( $c['_cl_first_name'] ) ? ' ' . $c['_cl_first_name'][0] : '' ));
            $contact_fullname = sanitize_text_field( $contact_fullname );
            $html .= '<div class="contact-list-hidden-name">' . (( isset( $c['_cl_first_name'] ) ? sanitize_text_field( $c['_cl_first_name'][0] ) . ' ' : '' )) . sanitize_text_field( $c['_cl_last_name'][0] ) . '</div>';
        } else {
            $contact_fullname = (( isset( $c['_cl_first_name'] ) ? $c['_cl_first_name'][0] . ' ' : '' )) . $c['_cl_last_name'][0];
            $contact_fullname = sanitize_text_field( $contact_fullname );
            $html .= '<div class="contact-list-hidden-name">' . sanitize_text_field( $c['_cl_last_name'][0] ) . (( isset( $c['_cl_first_name'] ) ? ' ' . sanitize_text_field( $c['_cl_first_name'][0] ) : '' )) . '</div>';
        }
        
        $simple_list_fields = explode( ' ', 'full_name job_title email phone phone_2 phone_3 address_line_1 city zip_code some_icons custom_field_1 custom_field_2 custom_field_3 custom_field_4 custom_field_5 custom_field_6 category send_message' );
        $override = 0;
        if ( sizeof( $simple_list_fields ) > 0 ) {
            foreach ( $simple_list_fields as $f ) {
                $f = sanitize_title( $f );
                $field_name = $f;
                $field_name_db = '_cl_' . $f;
                switch ( $field_name ) {
                    case 'full_name':
                        $simple_list_name_link_active = 0;
                        $simple_list_name_link = '';
                        
                        if ( !$simple_list_name_link_active ) {
                            $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-name contact-list-simple-list-col-' . $field_name . '"><span>';
                            $html .= sanitize_text_field( $contact_fullname );
                            $html .= '</span></div>';
                        }
                        
                        break;
                    case 'job_title':
                        $show_data = 1;
                        if ( isset( $s['simple_list_hide_job_title'] ) ) {
                            $show_data = 0;
                        }
                        
                        if ( $override || $show_data ) {
                            $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-' . $field_name . '"><span>';
                            if ( isset( $c[$field_name_db] ) ) {
                                $html .= sanitize_text_field( $c[$field_name_db][0] );
                            }
                            $html .= '</span></div>';
                        }
                        
                        break;
                    case 'phone':
                        $show_data = 1;
                        if ( isset( $s['simple_list_hide_phone_1'] ) ) {
                            $show_data = 0;
                        }
                        
                        if ( $override || $show_data ) {
                            $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-' . $field_name . '"><span>';
                            
                            if ( isset( $c['_cl_phone'] ) && $c['_cl_phone'][0] ) {
                                $phone_org = sanitize_text_field( $c['_cl_phone'][0] );
                                $phone_href = preg_replace( '/[^0-9\\,]/', '', $phone_org );
                                
                                if ( isset( $s['simple_list_call_button'] ) ) {
                                    $call_title = sanitize_text_field( __( 'Call', 'contact-list' ) );
                                    if ( isset( $s['simple_list_call_button_title'] ) ) {
                                        $call_title = sanitize_text_field( $s['simple_list_call_button_title'] );
                                    }
                                    $html .= '<a href="tel:' . $phone_href . '" class="contact-list-simple-list-call-button">' . $call_title . '</a>';
                                } else {
                                    $html .= '<a href="tel:' . $phone_href . '">' . $phone_org . '</a>';
                                }
                            
                            }
                            
                            $html .= '</span></div>';
                        }
                        
                        
                        if ( isset( $s['simple_list_show_extra_call_button'] ) ) {
                            $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-' . $field_name . '-call-button"><span>';
                            if ( $override || $show_data ) {
                                
                                if ( isset( $c['_cl_phone'] ) && $c['_cl_phone'][0] ) {
                                    $call_title = sanitize_text_field( __( 'Call', 'contact-list' ) );
                                    if ( isset( $s['simple_list_call_button_title'] ) ) {
                                        $call_title = sanitize_text_field( $s['simple_list_call_button_title'] );
                                    }
                                    $html .= '<a href="tel:' . $phone_href . '" class="contact-list-simple-list-call-button contact-list-simple-list-call-button-extra">' . $call_title . '</a>';
                                }
                            
                            }
                            $html .= '</span></div>';
                        }
                        
                        break;
                    case 'phone_2':
                        break;
                    case 'phone_3':
                        break;
                    case 'email':
                        $show_data = 1;
                        if ( isset( $s['simple_list_hide_email'] ) ) {
                            $show_data = 0;
                        }
                        
                        if ( $override || $show_data ) {
                            $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-' . $field_name . '"><span>';
                            
                            if ( isset( $c['_cl_email'][0] ) ) {
                                $mailto = sanitize_email( $c['_cl_email'][0] );
                                $mailto_obs = antispambot( $mailto );
                                if ( isset( $c['_cl_email'][0] ) && is_email( $c['_cl_email'][0] ) && !isset( $s['hide_contact_email'] ) ) {
                                    $html .= ( $c['_cl_email'][0] ? '<a href="mailto:' . sanitize_text_field( $mailto_obs ) . '">' . sanitize_text_field( $mailto_obs ) . '</a>' : '' );
                                }
                            }
                            
                            $html .= '</span></div>';
                        }
                        
                        break;
                    case 'some_icons':
                        $show_data = 1;
                        if ( isset( $s['simple_list_hide_some_links'] ) ) {
                            $show_data = 0;
                        }
                        
                        if ( $override || $show_data ) {
                            $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-some contact-list-simple-list-col-' . $field_name . '"><span>';
                            $html .= '<div class="contact-list-simple-list-some-icons-container">';
                            $html .= '<div class="contact-list-simple-list-some-icons">';
                            if ( isset( $c['_cl_facebook_url'] ) ) {
                                $html .= ( $c['_cl_facebook_url'][0] ? '<a href="' . esc_url_raw( $c['_cl_facebook_url'][0] ) . '" target="_blank"><img src="' . esc_url_raw( plugins_url( '../img/facebook.svg', __FILE__ ) ) . '" alt="' . ContactListHelpers::sanitize_attr_value( 'Facebook', 'contact-list' ) . '" /></a>' : '' );
                            }
                            if ( isset( $c['_cl_instagram_url'] ) ) {
                                $html .= ( $c['_cl_instagram_url'][0] ? '<a href="' . esc_url_raw( $c['_cl_instagram_url'][0] ) . '" target="_blank"><img src="' . esc_url_raw( plugins_url( '../img/instagram.svg', __FILE__ ) ) . '" alt="' . ContactListHelpers::sanitize_attr_value( 'Instagram', 'contact-list' ) . '" /></a>' : '' );
                            }
                            if ( isset( $c['_cl_twitter_url'] ) ) {
                                $html .= ( $c['_cl_twitter_url'][0] ? '<a href="' . esc_url_raw( $c['_cl_twitter_url'][0] ) . '" target="_blank"><img src="' . esc_url_raw( plugins_url( '../img/x.svg', __FILE__ ) ) . '" alt="' . ContactListHelpers::sanitize_attr_value( 'X', 'contact-list' ) . '" /></a>' : '' );
                            }
                            if ( isset( $c['_cl_linkedin_url'] ) ) {
                                $html .= ( $c['_cl_linkedin_url'][0] ? '<a href="' . esc_url_raw( $c['_cl_linkedin_url'][0] ) . '" target="_blank"><img src="' . esc_url_raw( plugins_url( '../img/linkedin.svg', __FILE__ ) ) . '" alt="' . ContactListHelpers::sanitize_attr_value( 'LinkedIn', 'contact-list' ) . '" /></a>' : '' );
                            }
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</span></div>';
                        }
                        
                        break;
                    case 'city':
                        $show_data = 1;
                        if ( !isset( $s['simple_list_show_city'] ) ) {
                            $show_data = 0;
                        }
                        
                        if ( $override || $show_data ) {
                            $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-' . $field_name . '"><span>';
                            if ( isset( $c['_cl_city'] ) ) {
                                $html .= sanitize_text_field( $c['_cl_city'][0] );
                            }
                            $html .= '</span></div>';
                        }
                        
                        break;
                    case 'zip_code':
                        $show_data = 1;
                        if ( !isset( $s['simple_list_show_zip_code'] ) ) {
                            $show_data = 0;
                        }
                        
                        if ( $override || $show_data ) {
                            $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-' . $field_name . '"><span>';
                            if ( isset( $c['_cl_zip_code'] ) ) {
                                $html .= sanitize_text_field( $c['_cl_zip_code'][0] );
                            }
                            $html .= '</span></div>';
                        }
                        
                        break;
                    case 'address_line_1':
                        $show_data = 1;
                        if ( !isset( $s['simple_list_show_address_line_1'] ) ) {
                            $show_data = 0;
                        }
                        
                        if ( $override || $show_data ) {
                            $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-' . $field_name . '"><span>';
                            if ( isset( $c['_cl_address_line_1'] ) ) {
                                $html .= sanitize_text_field( $c['_cl_address_line_1'][0] );
                            }
                            $html .= '</span></div>';
                        }
                        
                        break;
                    case 'custom_field_1':
                        $show_data = 1;
                        if ( !isset( $s['simple_list_show_custom_field_1'] ) ) {
                            $show_data = 0;
                        }
                        
                        if ( $override || $show_data ) {
                            $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-' . $field_name . '"><span>';
                            $html .= ContactListPublicHelpersSimple::singleContactSimpleCustomFieldMarkup( 1, $c );
                            $html .= '</span></div>';
                        }
                        
                        break;
                    case 'custom_field_2':
                        break;
                    case 'custom_field_3':
                        break;
                    case 'custom_field_4':
                        break;
                    case 'custom_field_5':
                        break;
                    case 'custom_field_6':
                        break;
                    case 'custom_field_7':
                        break;
                    case 'custom_field_8':
                        break;
                    case 'custom_field_9':
                        break;
                    case 'custom_field_10':
                        break;
                    case 'custom_field_11':
                        break;
                    case 'custom_field_12':
                        break;
                    case 'custom_field_13':
                        break;
                    case 'custom_field_14':
                        break;
                    case 'custom_field_15':
                        break;
                    case 'custom_field_16':
                        break;
                    case 'custom_field_17':
                        break;
                    case 'custom_field_18':
                        break;
                    case 'custom_field_19':
                        break;
                    case 'custom_field_20':
                        break;
                    case 'custom_url_1':
                        break;
                    case 'custom_url_2':
                        break;
                    case 'category':
                        $show_data = 1;
                        if ( !isset( $s['simple_list_show_category'] ) ) {
                            $show_data = 0;
                        }
                        
                        if ( $override || $show_data ) {
                            $terms = get_the_terms( $id, 'contact-group' );
                            $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-' . $field_name . '"><span>';
                            $simple_list_group_link = '';
                            
                            if ( $terms ) {
                                $html .= '<div class="contact-list-simple-contact-groups">';
                                foreach ( $terms as $term ) {
                                    $t_id = intval( $term->term_id );
                                    $custom_fields = get_option( "taxonomy_term_{$t_id}" );
                                    
                                    if ( !isset( $custom_fields['hide_group'] ) ) {
                                        $simple_list_group_link_active = 0;
                                        if ( !$simple_list_group_link_active ) {
                                            $html .= '<span>' . sanitize_text_field( $term->name ) . '</span>';
                                        }
                                    }
                                
                                }
                                $html .= '</div>';
                            }
                            
                            $html .= '</span></div>';
                        }
                        
                        break;
                    case 'send_message':
                        $show_data = 1;
                        
                        if ( !isset( $s['simple_list_show_send_message'] ) ) {
                            $show_data = 0;
                        } elseif ( isset( $s['hide_send_email_button'] ) ) {
                            $show_data = 0;
                        }
                        
                        
                        if ( $override || $show_data ) {
                            $html .= '<div class="contact-list-simple-list-col cl-align-right contact-list-simple-list-col-' . $field_name . '"><span>';
                            if ( isset( $c['_cl_email'][0] ) && is_email( $c['_cl_email'][0] ) || isset( $c['_cl_notify_emails'] ) && $c['_cl_notify_emails'] ) {
                                $html .= '<span class="contact-list-send-email contact-list-simple-send-email cl-dont-print"><a href="" data-id="' . ContactListHelpers::sanitize_attr_value( $id ) . '" data-name="' . ContactListHelpers::sanitize_attr_value( $contact_fullname ) . '">' . ContactListHelpers::getTextV2( 'text_send_message', 'Send message' ) . ' &raquo;</a></span>';
                            }
                            $html .= '</span></div>';
                        }
                        
                        break;
                    case 'description':
                        $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-' . $field_name . '"><span>';
                        if ( isset( $c[$field_name_db] ) ) {
                            $html .= wp_kses_post( $c[$field_name_db][0] );
                        }
                        $html .= '</span></div>';
                    default:
                        $html .= '<div class="contact-list-simple-list-col contact-list-simple-list-col-' . $field_name . '"><span>';
                        if ( isset( $c[$field_name_db] ) ) {
                            $html .= sanitize_text_field( $c[$field_name_db][0] );
                        }
                        $html .= '</span></div>';
                }
            }
        }
        $html .= '</div>';
        return $html;
    }
    
    public static function singleContactSimpleCustomFieldMarkup( $n, $c )
    {
        $html = '';
        
        if ( isset( $c['_cl_custom_field_' . $n] ) ) {
            $s = get_option( 'contact_list_settings' );
            $url = '@(http)?(s)?(://)?(([a-zA-Z])([-\\w]+\\.)+([^\\s\\.]+[^\\s]*)+[^,.\\s])@';
            $cf_value = sanitize_text_field( $c['_cl_custom_field_' . $n][0] );
            
            if ( is_email( $cf_value ) ) {
                $mailto = $cf_value;
                $mailto_obs = antispambot( $mailto );
                $cf_value = '<a href="mailto:' . sanitize_text_field( $mailto_obs ) . '">' . sanitize_text_field( $mailto_obs ) . '</a>';
            } else {
                $link_title = '';
                if ( isset( $s['custom_field_' . $n . '_link_text'] ) && $s['custom_field_' . $n . '_link_text'] ) {
                    $link_title = sanitize_text_field( $s['custom_field_' . $n . '_link_text'] );
                }
                
                if ( $link_title ) {
                    $cf_value = preg_replace( $url, '<a href="http$2://$4" target="_blank" title="$0">' . $link_title . '</a>', $cf_value );
                } else {
                    $cf_value = preg_replace( $url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $cf_value );
                }
            
            }
            
            
            if ( isset( $s['custom_field_' . $n . '_icon'] ) && $s['custom_field_' . $n . '_icon'] && $cf_value ) {
                $html .= '<div class="contact-list-custom-field-simple-list contact-list-custom-field-' . $n . ' contact-list-custom-field-with-icon">';
                $html .= '<i class="fa ' . sanitize_html_class( $s['custom_field_' . $n . '_icon'] ) . '" aria-hidden="true"></i><span>' . balanceTags( wp_kses_post( $cf_value ) ) . '</span>';
                $html .= '</div>';
            } else {
                $html .= '<div class="contact-list-custom-field-simple-list contact-list-custom-field-' . $n . '">';
                $html .= balanceTags( wp_kses_post( $cf_value ) );
                $html .= '</div>';
            }
        
        }
        
        return $html;
    }

}