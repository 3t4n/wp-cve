<?php

class ContactListPublicHelpers
{
    public static function pagination( $wp_query )
    {
        $html = '';
        if ( $wp_query->max_num_pages <= 1 ) {
            return;
        }
        $paged = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
        $max = intval( $wp_query->max_num_pages );
        if ( $paged >= 1 ) {
            $links[] = $paged;
        }
        /** Add the pages around the current page to the array */
        
        if ( $paged >= 3 ) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }
        
        
        if ( $paged + 2 <= $max ) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }
        
        $html .= '<div class="contact-list-navigation"><ul>' . "\n";
        if ( get_previous_posts_link() ) {
            $html .= sprintf( '<li>%s</li>' . "\n", get_previous_posts_link( sanitize_text_field( __( 'Previous contacts', 'contact-list' ) ) ) );
        }
        
        if ( !in_array( 1, $links ) ) {
            $class = ( 1 == $paged ? ' class="active"' : '' );
            $html .= sprintf(
                '<li%s><a href="%s">%s</a></li>' . "\n",
                $class,
                esc_url_raw( get_pagenum_link( 1 ) ),
                '1'
            );
            if ( !in_array( 2, $links ) ) {
                $html .= '<li>…</li>';
            }
        }
        
        sort( $links );
        foreach ( (array) $links as $link ) {
            $class = ( $paged == $link ? ' class="active"' : '' );
            $html .= sprintf(
                '<li%s><a href="%s">%s</a></li>' . "\n",
                $class,
                esc_url_raw( get_pagenum_link( $link ) ),
                $link
            );
        }
        
        if ( !in_array( $max, $links ) ) {
            if ( !in_array( $max - 1, $links ) ) {
                $html .= '<li>…</li>' . "\n";
            }
            $class = ( $paged == $max ? ' class="active"' : '' );
            $html .= sprintf(
                '<li%s><a href="%s">%s</a></li>' . "\n",
                $class,
                esc_url_raw( get_pagenum_link( $max ) ),
                $max
            );
        }
        
        if ( get_next_posts_link( null, 999999999 ) ) {
            $html .= sprintf( '<li>%s</li>' . "\n", get_next_posts_link( sanitize_text_field( __( 'Next contacts', 'contact-list' ), 999999999 ) ) );
        }
        $html .= '</ul></div>' . "\n";
        return $html;
    }
    
    public static function proFeaturePublicMarkup()
    {
        $html = '';
        $html .= '<div class="contact-list-public-pro-feature">';
        $html .= '<span class="contact-list-public-pro-feature-title">';
        $html .= sanitize_text_field( __( 'This feature is available in the paid plans.', 'contact-list' ) );
        $html .= '</span>';
        $html .= '<span>';
        $html .= sanitize_text_field( __( 'You can use the shortcodes', 'contact-list' ) ) . ' [[contact_list]] ' . sanitize_text_field( __( 'and', 'contact-list' ) ) . ' [[contact_list_simple]].';
        $html .= '</span>';
        $html .= '<span>';
        $html .= sanitize_text_field( __( 'More info on shortcodes', 'contact-list' ) ) . ' <a href="' . esc_url( get_admin_url( null, 'edit.php?post_type=contact&page=contact-list-shortcodes' ) ) . '" target="_blank">here</a>.';
        $html .= '</span>';
        $html .= '</div>';
        return $html;
    }
    
    public static function searchFormMarkup(
        $atts,
        $s,
        $exclude = array(),
        $elem_class = ''
    )
    {
        $html = '';
        $group_slug = '';
        $filter_active = 0;
        $wp_query_for_filter = new WP_Query( array(
            'post_type'      => 'contact',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'post__not_in'   => $exclude,
        ) );
        $form_class = 'contact-list-ajax-form';
        $html .= '<form method="get" action="./" class="' . $form_class . '" data-elem-class="' . $elem_class . '">';
        if ( isset( $atts ) ) {
            $html .= '<input type="hidden" name="cl_atts" value=\'' . ContactListHelpers::sanitize_attr_value( serialize( $atts ) ) . '\'>';
        }
        
        if ( isset( $s['show_country_select_in_search'] ) && $s['show_country_select_in_search'] ) {
            $countries = [];
            $states = [];
            while ( $wp_query_for_filter->have_posts() ) {
                $wp_query_for_filter->the_post();
                $c = get_post_custom();
                
                if ( isset( $c['_cl_country'][0] ) && $c['_cl_country'][0] && is_array( $countries ) && !in_array( $c['_cl_country'][0], $countries ) ) {
                    $countries[] = sanitize_text_field( $c['_cl_country'][0] );
                    $countries_for_dd[] = sanitize_text_field( $c['_cl_country'][0] );
                }
                
                
                if ( isset( $c['_cl_country'][0] ) && $c['_cl_country'][0] ) {
                    $country_temp = sanitize_text_field( $c['_cl_country'][0] );
                    $country = ( isset( $countries[$country_temp] ) ? $countries[$country_temp] : [] );
                    if ( isset( $c['_cl_state'][0] ) && $c['_cl_state'][0] && is_array( $country ) && !in_array( $c['_cl_state'][0], $country ) ) {
                        $countries[$country_temp][] = sanitize_text_field( $c['_cl_state'][0] );
                    }
                    
                    if ( isset( $c['_cl_state'][0] ) && $c['_cl_state'][0] && isset( $c['_cl_city'][0] ) && $c['_cl_city'][0] ) {
                        $state = sanitize_text_field( $c['_cl_state'][0] );
                        $city = sanitize_text_field( $c['_cl_city'][0] );
                        
                        if ( !in_array( $c['_cl_state'][0], $states ) ) {
                            $states[] = $state;
                            $states[$state] = [];
                        }
                        
                        if ( !in_array( $city, $states[$state] ) ) {
                            $states[$state][] = $city;
                        }
                    }
                
                }
            
            }
            $link_country_and_state = 0;
            if ( isset( $countries_for_dd ) && is_array( $countries_for_dd ) ) {
                sort( $countries_for_dd );
            }
            $html .= '<select name="' . CONTACT_LIST_CAT1 . '" class="' . ContactListHelpers::getSearchDropdownClass() . ' contact-list-cat1-sel" data-link-country-and-state="' . intval( $link_country_and_state ) . '">';
            $html .= '<option value="">' . ContactListHelpers::getText( 'text_select_country', __( 'Select country', 'contact-list' ) ) . '</option>';
            if ( isset( $countries_for_dd ) && is_array( $countries_for_dd ) ) {
                foreach ( $countries_for_dd as $country ) {
                    $html .= '<option value="' . ContactListHelpers::sanitize_attr_value( $country ) . '" ' . (( isset( $_GET[CONTACT_LIST_CAT1] ) && $_GET[CONTACT_LIST_CAT1] == $country ? 'selected="selected"' : '' )) . '>' . sanitize_text_field( $country ) . '</option>';
                }
            }
            $html .= '</select>';
            $filter_active = 1;
        }
        
        
        if ( isset( $s['show_state_select_in_search'] ) && $s['show_state_select_in_search'] ) {
            $states = [];
            while ( $wp_query_for_filter->have_posts() ) {
                $wp_query_for_filter->the_post();
                $c = get_post_custom();
                if ( isset( $c['_cl_state'] ) && $c['_cl_state'] && is_array( $states ) && !in_array( $c['_cl_state'][0], $states ) ) {
                    $states[] = sanitize_text_field( $c['_cl_state'][0] );
                }
            }
            if ( isset( $states ) && is_array( $states ) ) {
                sort( $states );
            }
            $html .= '<select name="' . CONTACT_LIST_CAT2 . '" class="' . ContactListHelpers::getSearchDropdownClass() . ' contact-list-cat2-sel" data-select-value="' . ContactListHelpers::getText( 'text_select_state', __( 'Select state', 'contact-list' ) ) . '" data-current-value="' . (( isset( $_GET[CONTACT_LIST_CAT2] ) ? ContactListHelpers::sanitize_attr_value( $_GET[CONTACT_LIST_CAT2] ) : '' )) . '">';
            
            if ( !isset( $s['link_country_and_state'] ) ) {
                $html .= '<option value="">' . ContactListHelpers::getText( 'text_select_state', __( 'Select state', 'contact-list' ) ) . '</option>';
                if ( isset( $states ) && is_array( $states ) ) {
                    foreach ( $states as $state ) {
                        $html .= '<option value="' . ContactListHelpers::sanitize_attr_value( $state ) . '" ' . (( isset( $_GET[CONTACT_LIST_CAT2] ) && $_GET[CONTACT_LIST_CAT2] == $state ? 'selected="selected"' : '' )) . '>' . sanitize_text_field( $state ) . '</option>';
                    }
                }
            }
            
            $html .= '</select>';
            $filter_active = 1;
        }
        
        
        if ( isset( $s['show_city_select_in_search'] ) && $s['show_city_select_in_search'] ) {
            $cities = [];
            while ( $wp_query_for_filter->have_posts() ) {
                $wp_query_for_filter->the_post();
                $c = get_post_custom();
                if ( isset( $c['_cl_city'] ) && $c['_cl_city'] && is_array( $cities ) && !in_array( $c['_cl_city'][0], $cities ) ) {
                    $cities[] = sanitize_text_field( $c['_cl_city'][0] );
                }
            }
            if ( isset( $cities ) && is_array( $cities ) ) {
                sort( $cities );
            }
            $html .= '<select name="' . CONTACT_LIST_CAT3 . '" class="' . ContactListHelpers::getSearchDropdownClass() . ' contact-list-cat3-sel" data-select-value="' . ContactListHelpers::getText( 'text_select_city', __( 'Select city', 'contact-list' ) ) . '" data-current-value="' . (( isset( $_GET[CONTACT_LIST_CAT3] ) ? ContactListHelpers::sanitize_attr_value( $_GET[CONTACT_LIST_CAT3] ) : '' )) . '">';
            
            if ( !isset( $s['link_country_and_state'] ) ) {
                $html .= '<option value="">' . ContactListHelpers::getText( 'text_select_city', __( 'Select city', 'contact-list' ) ) . '</option>';
                if ( isset( $cities ) && is_array( $cities ) ) {
                    foreach ( $cities as $city ) {
                        $html .= '<option value="' . ContactListHelpers::sanitize_attr_value( $city ) . '" ' . (( isset( $_GET[CONTACT_LIST_CAT3] ) && $_GET[CONTACT_LIST_CAT3] == $city ? 'selected="selected"' : '' )) . '>' . sanitize_text_field( $city ) . '</option>';
                    }
                }
            }
            
            $html .= '</select>';
            $filter_active = 1;
        }
        
        
        if ( isset( $s['show_category_select_in_search'] ) && $s['show_category_select_in_search'] ) {
            $category_select_shown = 0;
            
            if ( !$category_select_shown && (ContactListHelpers::isPremium() == 0 || isset( $s['simpler_category_dropdown'] )) ) {
                $exclude_groups = [];
                $groups = get_terms( array(
                    'taxonomy'   => 'contact-group',
                    'hide_empty' => true,
                    'exclude'    => $exclude_groups,
                ) );
                $html .= '<select name="cl_cat" class="' . esc_attr( ContactListHelpers::getSearchDropdownClass() ) . '">';
                $html .= '<option value="">' . ContactListHelpers::getText( 'text_select_category', __( 'Select category', 'contact-list' ) ) . '</option>';
                foreach ( $groups as $g ) {
                    $t_id = intval( $g->term_id );
                    $custom_fields = get_option( "taxonomy_term_{$t_id}" );
                    if ( !isset( $custom_fields['hide_group'] ) ) {
                        $html .= '<option value="' . sanitize_title( $g->slug ) . '" ' . (( isset( $_GET['cl_cat'] ) && $_GET['cl_cat'] == $g->slug ? 'selected="selected"' : '' )) . '>' . sanitize_text_field( $g->name ) . '</option>';
                    }
                }
                $html .= '</select>';
            } elseif ( !$category_select_shown ) {
            }
            
            $filter_active = 1;
        }
        
        $html .= '<hr class="clear" /></form>';
        return $html;
    }

}