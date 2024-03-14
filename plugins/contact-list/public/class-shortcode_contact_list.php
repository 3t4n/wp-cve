<?php

class ShortcodeContactList
{
    public static function shortcodeContactListMarkup( $atts )
    {
        $s = get_option( 'contact_list_settings' );
        $layout = ContactListHelpers::getLayout( $s, $atts );
        $elem_class = ContactListHelpers::createElemClass();
        $embed_id = ( isset( $atts['embed_id'] ) ? sanitize_title( $atts['embed_id'] ) : 'default' );
        $pagination_active = 0;
        $get_params_active = 0;
        $hide_contacts_first = 0;
        
        if ( isset( $_GET['_paged'] ) && $_GET['_paged'] == $embed_id ) {
            $pagination_active = 1;
        } elseif ( get_query_var( 'paged' ) ) {
            $pagination_active = 1;
        }
        
        $exclude = [];
        $group_slug = '';
        $html = '';
        if ( !isset( $s['hide_send_email_button'] ) ) {
            $html .= ContactListHelpers::modalSendMessageMarkup();
        }
        $html .= '<div class="contact-list-container ' . sanitize_html_class( $elem_class ) . ' ' . (( $layout ? 'contact-list-' . sanitize_html_class( $layout ) : '' )) . '" data-elem-class="' . $elem_class . '">';
        
        if ( isset( $atts['contact'] ) || isset( $_GET['contact_id'] ) ) {
            $contact = ( isset( $atts['contact'] ) ? (int) $atts['contact'] : (int) $_GET['contact_id'] );
            
            if ( $contact ) {
                $html .= '<div id="contact-list-search">';
                $html .= '<ul id="all-contacts">';
                $wpb_all_query = new WP_Query( array(
                    'post_type'      => CONTACT_LIST_CPT,
                    'post_status'    => 'publish',
                    'posts_per_page' => 1,
                    'p'              => $contact,
                ) );
                
                if ( $wpb_all_query->have_posts() ) {
                    while ( $wpb_all_query->have_posts() ) {
                        $wpb_all_query->the_post();
                        $id = intval( get_the_id() );
                        $html .= ContactListPublicHelpersDefault::singleContactMarkup( $id, 0, $atts );
                    }
                } else {
                    $html .= '<p style="background: #f00; color: #fff; padding: 1rem; text-align: center;">' . sanitize_text_field( __( 'Contact not found', 'contact-list' ) ) . ' (ID: ' . intval( $contact ) . ')</p>';
                }
                
                $html .= '</ul>';
                $html .= '</div>';
            } else {
                $html .= '<p style="background: #f00; color: #fff; padding: 1rem; text-align: center;">' . sanitize_text_field( __( 'Contact not found', 'contact-list' ) ) . ' (ID: ' . intval( $contact ) . ')</p>';
            }
        
        } else {
            $html .= '<div class="contact-list-text-contact" style="display: none;">' . ContactListHelpers::getText( 'text_sr_contact', __( 'contact', 'contact-list' ) ) . '</div>';
            $html .= '<div class="contact-list-text-contacts" style="display: none;">' . ContactListHelpers::getText( 'text_sr_contacts', __( 'contacts', 'contact-list' ) ) . '</div>';
            $html .= '<div class="contact-list-text-found" style="display: none;">' . ContactListHelpers::getText( 'text_sr_found', __( 'found', 'contact-list' ) ) . '</div>';
            $meta_query = array(
                'relation' => 'AND',
            );
            $meta_query[] = array(
                'last_name_clause' => array(
                'key'     => '_cl_last_name',
                'compare' => 'EXISTS',
            ),
            );
            $order_by = array(
                'menu_order'       => 'ASC',
                'last_name_clause' => 'ASC',
                'title'            => 'ASC',
            );
            
            if ( isset( $atts['order_by'] ) && $atts['order_by'] == 'first_name' ) {
                $meta_query[] = array(
                    'first_name_clause' => array(
                    'key'     => '_cl_first_name',
                    'compare' => 'EXISTS',
                ),
                );
                $order_by = array(
                    'menu_order'        => 'ASC',
                    'first_name_clause' => 'ASC',
                    'title'             => 'ASC',
                );
            } elseif ( CONTACT_LIST_ORDER_BY != '_cl_last_name' && !isset( $atts['order_by'] ) ) {
                $meta_query[] = array(
                    'custom_clause' => array(
                    'key'     => CONTACT_LIST_ORDER_BY,
                    'compare' => 'EXISTS',
                ),
                );
                $order_by = array(
                    'menu_order'    => 'ASC',
                    'custom_clause' => 'ASC',
                    'title'         => 'ASC',
                );
            }
            
            
            if ( isset( $_GET[CONTACT_LIST_CAT1] ) && $_GET[CONTACT_LIST_CAT1] ) {
                $meta_query[] = array(
                    'key'     => '_cl_country',
                    'value'   => sanitize_text_field( $_GET[CONTACT_LIST_CAT1] ),
                    'compare' => 'LIKE',
                );
                $get_params_active = 1;
            }
            
            
            if ( isset( $_GET[CONTACT_LIST_CAT2] ) && $_GET[CONTACT_LIST_CAT2] ) {
                $meta_query[] = array(
                    'key'     => '_cl_state',
                    'value'   => sanitize_text_field( $_GET[CONTACT_LIST_CAT2] ),
                    'compare' => 'LIKE',
                );
                $get_params_active = 1;
            }
            
            
            if ( isset( $_GET[CONTACT_LIST_CAT3] ) && $_GET[CONTACT_LIST_CAT3] ) {
                $meta_query[] = array(
                    'key'     => '_cl_city',
                    'value'   => sanitize_text_field( $_GET[CONTACT_LIST_CAT3] ),
                    'compare' => 'LIKE',
                );
                $get_params_active = 1;
            }
            
            $tax_query = [
                'relation' => 'AND',
            ];
            
            if ( isset( $_GET['cl_cat'] ) && $_GET['cl_cat'] ) {
                $tax_query[] = array(
                    'taxonomy' => 'contact-group',
                    'field'    => 'slug',
                    'terms'    => sanitize_title( $_GET['cl_cat'] ),
                );
                $get_params_active = 1;
            } elseif ( $group_slug ) {
            }
            
            $paged = 1;
            if ( $pagination_active ) {
                
                if ( isset( $_GET['_page'] ) && $_GET['_page'] ) {
                    $paged = (int) $_GET['_page'];
                } elseif ( get_query_var( 'paged' ) ) {
                    $paged = absint( get_query_var( 'paged' ) );
                }
            
            }
            $posts_per_page = -1;
            if ( isset( $s['contacts_per_page'] ) && $s['contacts_per_page'] ) {
                $posts_per_page = intval( $s['contacts_per_page'] );
            }
            $wp_query = new WP_Query( array(
                'post_type'      => 'contact',
                'post_status'    => 'publish',
                'posts_per_page' => $posts_per_page,
                'paged'          => $paged,
                'post__not_in'   => $exclude,
                'meta_query'     => $meta_query,
                'tax_query'      => $tax_query,
                'orderby'        => $order_by,
            ) );
            $wp_query_for_filter = new WP_Query( array(
                'post_type'      => 'contact',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'post__not_in'   => $exclude,
                'meta_query'     => $meta_query,
                'tax_query'      => $tax_query,
                'orderby'        => $order_by,
            ) );
            
            if ( !isset( $atts['hide_search'] ) ) {
                $search_contacts_class = 'contact-list-search-contacts';
                $placeholder = ( isset( $s['search_contacts'] ) && $s['search_contacts'] ? ContactListHelpers::sanitize_attr_value( $s['search_contacts'] ) : ContactListHelpers::sanitize_attr_value( __( 'Search contacts...', 'contact-list' ) ) );
                $html .= '<input type="text" id="search-contacts" class="' . $search_contacts_class . '" placeholder="' . $placeholder . '" data-elem-class="' . $elem_class . '" />';
            }
            
            if ( !isset( $atts['hide_filters'] ) ) {
                $html .= ContactListPublicHelpers::searchFormMarkup(
                    $atts,
                    $s,
                    $exclude,
                    $elem_class
                );
            }
            $container_class = 'contact-list-search-results-container';
            $html .= '<div class="' . $container_class . '">';
            
            if ( $wp_query_for_filter->have_posts() ) {
                $html .= '<div class="contact-list-basic-nothing-found">';
                $html .= ContactListHelpers::getText( 'text_sr_no_contacts_found', __( 'No contacts found.', 'contact-list' ) );
                $html .= '</div>';
                $html .= '<div class="contact-list-basic-all-contacts-container">';
                $html .= '<div class="contact-list-basic-contacts-found"></div>';
                $html .= ContactListPublicHelpersDefault::contactListMarkup(
                    $wp_query_for_filter,
                    0,
                    $atts,
                    1
                );
                $html .= '</div>';
            }
            
            
            if ( $wp_query->have_posts() ) {
                $html .= '<div class="contact-list-ajax-results">';
                $default_contact_id = 0;
                
                if ( $hide_contacts_first && !$get_params_active && $default_contact_id ) {
                    $html .= '<div id="contact-list-search">';
                    $html .= '<ul id="all-contacts">';
                    $wpb_all_query = new WP_Query( array(
                        'post_type'      => CONTACT_LIST_CPT,
                        'post_status'    => 'publish',
                        'posts_per_page' => 1,
                        'p'              => $default_contact_id,
                    ) );
                    
                    if ( $wpb_all_query->have_posts() ) {
                        while ( $wpb_all_query->have_posts() ) {
                            $wpb_all_query->the_post();
                            $id = intval( get_the_id() );
                            $html .= ContactListPublicHelpersDefault::singleContactMarkup( $id, 0, $atts );
                        }
                    } else {
                        $html .= '<p style="background: #f00; color: #fff; padding: 1rem; text-align: center;">' . sanitize_text_field( __( 'Contact not found', 'contact-list' ) ) . ' (ID: ' . intval( $default_contact_id ) . ')</p>';
                    }
                    
                    $html .= '</ul>';
                    $html .= '</div>';
                } elseif ( $hide_contacts_first && !$get_params_active ) {
                    // ...
                } else {
                    $html .= ContactListPublicHelpersDefault::contactListMarkup(
                        $wp_query,
                        0,
                        $atts,
                        0
                    );
                }
                
                $html .= '</div>';
                
                if ( $hide_contacts_first && !$get_params_active ) {
                    // ...
                } else {
                    $html .= ContactListPublicPagination::getPagination( 1, $wp_query, 'default' );
                }
            
            }
            
            if ( $wp_query->found_posts == 0 ) {
                $html .= '<p>' . ContactListHelpers::getText( 'text_sr_no_contacts_found', __( 'No contacts found.', 'contact-list' ) ) . '</p>';
            }
            $html .= '</div>';
        }
        
        $html .= '</div>';
        wp_reset_postdata();
        return $html;
    }

}