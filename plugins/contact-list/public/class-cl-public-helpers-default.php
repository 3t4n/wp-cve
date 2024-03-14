<?php

class ContactListPublicHelpersDefault
{
    public static function contactListMarkup(
        $wp_query,
        $include_children = 0,
        $atts = array(),
        $output_modals = 0
    )
    {
        $html = '';
        $html .= '<div id="contact-list-search">';
        $html .= '<ul id="all-contacts">';
        if ( $wp_query->have_posts() ) {
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                $id = intval( get_the_id() );
                $html .= ContactListPublicHelpersDefault::singleContactMarkup( $id, 0, $atts );
            }
        }
        $html .= '</ul><hr class="clear" />';
        $html .= '<div id="contact-list-nothing-found">';
        $html .= ContactListHelpers::getText( 'text_sr_no_contacts_found', __( 'No contacts found.', 'contact-list' ) );
        $html .= '</div>';
        $html .= '</div>';
        wp_reset_postdata();
        return $html;
    }
    
    public static function singleContactFullname( $c )
    {
        $s = get_option( 'contact_list_settings' );
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
                $field_value = sanitize_text_field( $c[$field_name][0] );
                $contact_fullname = str_replace( $search_for, $field_value, $contact_fullname );
            }
        
        }
        return $contact_fullname;
    }
    
    public static function singleContactMarkup(
        $id,
        $showGroups = 0,
        $atts = array(),
        $is_modal = 0
    )
    {
        $s = get_option( 'contact_list_settings' );
        $c = get_post_custom( $id );
        $featured_img_url = esc_url_raw( get_the_post_thumbnail_url( $id, 'contact-list-contact' ) );
        $hide_right_column = 1;
        $contact_card_class = 'contact-list-card-full-width';
        if ( $featured_img_url || isset( $s['contact_card_right_column'] ) && $s['contact_card_right_column'] ) {
            $contact_card_class = '';
        }
        $file = CONTACT_LIST_PATH . '/templates/contact-card.php';
        // buffer the output
        ob_start();
        include $file;
        return ob_get_clean();
    }

}