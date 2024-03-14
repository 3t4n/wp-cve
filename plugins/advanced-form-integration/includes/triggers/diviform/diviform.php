<?php

// Get forms list
function adfoin_diviform_get_forms( $form_provider ) {

    if( $form_provider != 'diviform' ) {
        return;
    }

    global $wpdb;

    $posts = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID, post_title, post_content FROM $wpdb->posts
                LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
                    WHERE $wpdb->posts.post_status = 'publish' AND ($wpdb->posts.post_type = 'post' OR $wpdb->posts.post_type = 'page' OR $wpdb->posts.post_type = 'et_footer_layout' OR $wpdb->posts.post_type = 'et_header_layout' OR $wpdb->posts.post_type ='et_body_layout') AND $wpdb->postmeta.meta_key = '_et_pb_ab_current_shortcode'"
        )
    );

    if( empty( $posts ) && !is_array( $posts ) ) {
        return;
    }

    $all_forms = array();

    foreach ( $posts as $post ) {
        $forms = array();
        $content_array = explode('][', $post->post_content);

        foreach ( $content_array as $line ) {
            $line_array = explode( ' ', $line );

            if ( $line_array[0] == 'et_pb_contact_form' ) {
                $regex = '/unique_id\s*=\s*"([^"]+)"/';
                preg_match($regex, $line, $unique_id );

                $title_match = '/title\s*=\s*"([^"]+)"/';
                preg_match($title_match, $line, $title);

                if (empty($unique_id[1])) {
                    continue;
                }
                $forms[$unique_id[1]] =  !empty($title[1]) ? $title[1] : 'Untitled Form';
            }

            $postfix = 0;

            foreach( $forms as $key => $form) {
                $all_forms[$post->ID . '_' . $key . '_' . $postfix] = sprintf( 'Post ID: %s - %s', $post->ID, $form );
                $postfix++;
            }
        }
    }

    return $all_forms;
}

// Get form fields
function adfoin_diviform_get_form_fields( $form_provider, $form_id ) {

    if( $form_provider != 'diviform' ) {
        return;
    }

    $post_id = intval( explode( '_', $form_id )[0] );

    if( empty( $post_id ) ) {
        return;
    }

    $post_content = get_post_field( 'post_content', $post_id );

    if( empty( $post_content ) ) {
        return;
    }

    $content_array = explode('][', $post_content);

    $fields = array();

    foreach ( $content_array as $line ) {
        $line_array = explode( ' ', $line );

        if ( $line_array[0] == 'et_pb_contact_field' ) {
            $regex = '/field_id\s*=\s*"([^"]+)"/';
            preg_match($regex, $line, $field_id );

            $title_match = '/field_title\s*=\s*"([^"]+)"/';
            preg_match($title_match, $line, $title);

            if (empty($field_id[1])) {
                continue;
            }
            $fields[strtolower( $field_id[1] )] = !empty( $title[1] ) ? $title[1] : 'Untitled Field';
        }
    }

    return $fields;
}

// Get form name
function adfoin_diviform_get_form_name( $form_id ) {
    $post_id = intval( explode( '_', $form_id )[0] );

    if( empty( $post_id ) ) {
        return;
    }

    $post_title = get_the_title( $post_id );

    if( empty( $post_title ) ) {
        return;
    }

    return $post_title;
}

add_action( 'et_pb_contact_form_submit', 'adfoin_diviform_submission', 10, 3 );

function adfoin_diviform_submission( $et_pb_contact_form_submit, $et_contact_error, $contact_form_info ) {

    $form_id = $contact_form_info['post_id'] . '_' . $contact_form_info['contact_form_unique_id'] . '_' . $contact_form_info['contact_form_number'];
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'diviform', $form_id );

    if( empty( $saved_records ) ) {
        return;
    }

    global $post;

    $posted_data = array();

    if( is_array( $et_pb_contact_form_submit ) ) {
        foreach( $et_pb_contact_form_submit as $key => $value ) {
            $posted_data[$key] = $value['value'];
        }
    }

    $special_tag_values = adfoin_get_special_tags_values( $post );

    if( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
        $posted_data = $posted_data + $special_tag_values;
    }

    $integration->send( $saved_records, $posted_data );
}