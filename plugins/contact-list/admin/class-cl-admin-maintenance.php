<?php

class ContactListAdminMaintenance
{
    public function update_db_check_v2()
    {
        $s = get_option( 'contact_list_settings' );
        // START METADATA UPDATE
        $metadata_check_field_name = 'contact_list_metadata_fixed_1000';
        $contact_list_metadata_fixed = get_option( $metadata_check_field_name );
        
        if ( !$contact_list_metadata_fixed ) {
            ContactListHelpers::writeLog( 'START contact metadata update check (save empty values) - ' . $metadata_check_field_name, '' );
            update_option( $metadata_check_field_name, 1 );
            $wp_query = new WP_Query( array(
                'post_type'      => 'contact',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
            ) );
            $sortable_fields = [
                '_cl_first_name',
                '_cl_custom_field_1',
                '_cl_custom_field_2',
                '_cl_custom_field_3',
                '_cl_custom_field_4',
                '_cl_custom_field_5',
                '_cl_custom_field_6'
            ];
            $cnt = 0;
            
            if ( $wp_query->have_posts() ) {
                ContactListHelpers::writeLog( 'Total contacts: ' . sanitize_text_field( $wp_query->post_count ), '' );
                while ( $wp_query->have_posts() ) {
                    $wp_query->the_post();
                    $id = intval( get_the_id() );
                    foreach ( $sortable_fields as $field_name ) {
                        $field_value = sanitize_text_field( get_post_meta( $id, $field_name, true ) );
                        if ( !$field_value ) {
                            update_post_meta( $id, $field_name, '' );
                        }
                    }
                    $cnt++;
                }
                wp_reset_postdata();
            }
            
            ContactListHelpers::writeLog( 'Verified contacts: ' . sanitize_text_field( $cnt ), '' );
            //      ContactListHelpers::writeLog('Verified fields: ' . print_r( $sortable_fields, true ), '');
            ContactListHelpers::writeLog( 'END contact metadata update check (save empty values) - ' . $metadata_check_field_name, '' );
        }
        
        // END METADATA UPDATE
    }
    
    public function actions()
    {
        $rand_code = sanitize_text_field( md5( uniqid( rand(), true ) ) );
        if ( !get_option( 'contact-list-sc' ) ) {
            add_option(
                'contact-list-sc',
                $rand_code,
                '',
                'yes'
            );
        }
        $s = get_option( 'contact_list_settings' );
        
        if ( $s === false ) {
            $default_settings = [
                'layout'                               => '2-cards-on-the-same-row',
                'card_border'                          => 'black',
                'contact_image_style'                  => 'sepia',
                'contact_image_shadow'                 => 'on',
                'card_background'                      => 'white',
                'show_country_select_in_search'        => 'on',
                'show_state_select_in_search'          => 'on',
                'show_city_select_in_search'           => 'on',
                'show_category_select_in_search'       => 'on',
                'show_contact_images_always'           => 'on',
                'pagination_type'                      => 'improved',
                'simple_list_show_titles_for_columns'  => 'on',
                'simple_list_show_send_message'        => 'on',
                'af_show_name_prefix'                  => 'on',
                'af_show_middle_name'                  => 'on',
                'af_show_name_suffix'                  => 'on',
                'af_show_organization'                 => 'on',
                'af_show_department'                   => 'on',
                'simple_list_preserve_table_on_mobile' => 'on',
            ];
            add_option( 'contact_list_settings', $default_settings );
            update_option( 'contact_list_how_to_show_notice', 1, false );
        }
    
    }

}