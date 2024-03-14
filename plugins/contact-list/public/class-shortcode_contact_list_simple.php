<?php

class ShortcodeContactListSimple
{
    public static function view( $atts )
    {
        $s = get_option( 'contact_list_settings' );
        $embed_id = ( isset( $atts['embed_id'] ) ? sanitize_title( $atts['embed_id'] ) : 'default' );
        $pagination_active = 0;
        
        if ( isset( $_GET['_paged'] ) && $_GET['_paged'] == $embed_id ) {
            $pagination_active = 1;
        } elseif ( get_query_var( 'paged' ) ) {
            $pagination_active = 1;
        }
        
        $exclude = [];
        $html = '';
        $generate_send_message_modal_markup = 0;
        
        if ( isset( $s['simple_list_name_link'] ) && $s['simple_list_name_link'] == 'contact-card-lightbox' ) {
            $generate_send_message_modal_markup = 1;
        } elseif ( isset( $s['simple_list_show_send_message'] ) ) {
            $generate_send_message_modal_markup = 1;
        }
        
        if ( $generate_send_message_modal_markup ) {
            $html .= ContactListHelpers::modalSendMessageMarkup();
        }
        $html .= '<div class="contact-list-simple-container" />';
        $html .= '<div class="contact-list-simple-text-contact" style="display: none;">' . ContactListHelpers::getText( 'text_sr_contact', __( 'contact', 'contact-list' ) ) . '</div>';
        $html .= '<div class="contact-list-simple-text-contacts" style="display: none;">' . ContactListHelpers::getText( 'text_sr_contacts', __( 'contacts', 'contact-list' ) ) . '</div>';
        $html .= '<div class="contact-list-simple-text-found" style="display: none;">' . ContactListHelpers::getText( 'text_sr_found', __( 'found', 'contact-list' ) ) . '</div>';
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
        
        if ( CONTACT_LIST_ORDER_BY != '_cl_last_name' && !isset( $atts['order_by'] ) ) {
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
        }
        if ( isset( $_GET[CONTACT_LIST_CAT2] ) && $_GET[CONTACT_LIST_CAT2] ) {
            $meta_query[] = array(
                'key'     => '_cl_state',
                'value'   => sanitize_text_field( $_GET[CONTACT_LIST_CAT2] ),
                'compare' => 'LIKE',
            );
        }
        if ( isset( $_GET[CONTACT_LIST_CAT3] ) && $_GET[CONTACT_LIST_CAT3] ) {
            $meta_query[] = array(
                'key'     => '_cl_city',
                'value'   => sanitize_text_field( $_GET[CONTACT_LIST_CAT3] ),
                'compare' => 'LIKE',
            );
        }
        $tax_query = [];
        $group_slug = '';
        $group_name = '';
        $show_group_title = 0;
        
        if ( $show_group_title ) {
            $html .= '<div class="contact-list-simple-back-link-container"><a href="javascript:history.go(-1)">' . ContactListHelpers::getText( 'back_link_title', __( '<< Back', 'contact-list' ) ) . '</a></div>';
            $html .= '<h2 class="contact-list-simple-group-title">' . $group_name . '</h2>';
        }
        
        
        if ( isset( $_GET['cl_cat'] ) && $_GET['cl_cat'] ) {
            $include_children = 1;
            $tax_query = [
                'relation' => 'AND',
            ];
            $tax_query[] = [
                'taxonomy'         => 'contact-group',
                'field'            => 'slug',
                'terms'            => sanitize_title( $_GET['cl_cat'] ),
                'include_children' => $include_children,
            ];
            
            if ( isset( $atts['exclude_groups'] ) && $atts['exclude_groups'] ) {
                $terms = explode( ',', sanitize_text_field( $atts['exclude_groups'] ) );
                $tax_query[] = [
                    'taxonomy' => 'contact-group',
                    'terms'    => $terms,
                    'field'    => 'slug',
                    'operator' => 'NOT IN',
                ];
            }
        
        } elseif ( $group_slug ) {
            $include_children = 1;
            $tax_query = [
                'relation' => 'AND',
            ];
            $tax_query[] = [
                'taxonomy'         => 'contact-group',
                'field'            => 'slug',
                'terms'            => $group_slug,
                'include_children' => $include_children,
            ];
        } elseif ( isset( $atts['exclude_groups'] ) && $atts['exclude_groups'] ) {
            $terms = explode( ',', sanitize_text_field( $atts['exclude_groups'] ) );
            $tax_query = [
                'relation' => 'AND',
            ];
            $tax_query[] = [
                'taxonomy' => 'contact-group',
                'terms'    => $terms,
                'field'    => 'slug',
                'operator' => 'NOT IN',
            ];
        }
        
        $paged = 1;
        if ( $pagination_active ) {
            
            if ( isset( $_GET['_page'] ) && $_GET['_page'] ) {
                $paged = (int) $_GET['_page'];
            } elseif ( get_query_var( 'paged' ) ) {
                $paged = absint( get_query_var( 'paged' ) );
            }
        
        }
        //    $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
        $posts_per_page = -1;
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
        $extra_class = '';
        $html .= '<form method="get" action="./" class="contact-list-ajax-form-simple' . $extra_class . '">';
        if ( !isset( $atts['hide_search'] ) ) {
            $html .= '<input type="text" class="contact-list-simple-search-contacts' . $extra_class . '" placeholder="' . (( isset( $s['search_contacts'] ) && $s['search_contacts'] ? ContactListHelpers::sanitize_attr_value( $s['search_contacts'] ) : ContactListHelpers::sanitize_attr_value( __( 'Search contacts...', 'contact-list' ) ) )) . '">';
        }
        $html .= '<hr class="clear" /></form>';
        
        if ( !isset( $atts['ajax'] ) ) {
            $wp_query_for_filter = new WP_Query( array(
                'post_type'      => 'contact',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'post__not_in'   => $exclude,
                'meta_query'     => $meta_query,
                'tax_query'      => $tax_query,
                'orderby'        => $order_by,
            ) );
            
            if ( $wp_query_for_filter->have_posts() ) {
                $html .= '<div class="contact-list-simple-all-contacts-container">';
                $html .= '<div class="contact-list-simple-contacts-found"></div>';
                $html .= ContactListPublicHelpersSimple::contactListSimpleMarkup(
                    $wp_query_for_filter,
                    0,
                    $atts,
                    1
                );
                $html .= '</div>';
            }
        
        }
        
        
        if ( $wp_query->have_posts() ) {
            $generate_modals = 0;
            $html .= '<div class="contact-list-simple-paginated-container contact-list-simple-ajax-results">';
            $html .= ContactListPublicHelpersSimple::contactListSimpleMarkup(
                $wp_query,
                0,
                $atts,
                $generate_modals
            );
            $html .= '</div>';
        }
        
        $html .= '</div>';
        wp_reset_postdata();
        return $html;
    }

}