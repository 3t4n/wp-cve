<?php

// Get forms list
function adfoin_breakdance_get_forms( $form_provider ) {

    if( $form_provider != 'breakdance' ) {
        return;
    }

    global $wpdb;

    $posts = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID, post_title FROM $wpdb->posts
                LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
                WHERE $wpdb->posts.post_status = 'publish' 
                    AND ($wpdb->posts.post_type = 'post' 
                        OR $wpdb->posts.post_type = 'page' 
                        OR $wpdb->posts.post_type = 'breakdance_footer') 
                    AND $wpdb->postmeta.meta_key = 'breakdance_data'"
        )
    );

    if( empty( $posts ) || !is_array( $posts ) ) {
        return;
    }

    $all_forms = array();

    foreach ( $posts as $post ) {
        $forms = array();
        $content = get_post_meta( $post->ID, 'breakdance_data', true );

        if( empty( $content ) ) {
            continue;
        }

        $content = json_decode( $content );

        if( empty( $content ) ) {
            continue;
        }

        $forms = adfoin_breakdance_extract_all_forms( $content, $post->ID );

        if( empty( $forms ) ) {
            continue;
        }

        $all_forms = array_merge( $all_forms, $forms );
    }

    return $all_forms;
}

function adfoin_breakdance_extract_all_forms( $data, $post_id ) {
    $new_form = array();
    $all_forms = array();

    foreach ( $data as $keys => $element ) {
        if( $keys === 'id' ) {
            $form_id = "{$element}-{$post_id}";
        }

        if( is_object( $element ) && property_exists( $element, 'type' ) && $element->type === 'EssentialElements\\FormBuilder' ) {
            $new_form = $element->properties->content->form;
            $all_forms[] = array_merge( (array) $new_form, array( 'form_id' => $form_id ) );
        }

        if( $keys == 'children' ) {
            if( is_array( $element ) && !empty( $element ) ) {
                foreach ( $element as $second_layer ) {
                    if( property_exists( $second_layer, 'children' ) ) {
                        foreach ( $second_layer->children as $s_keys => $s_value ) {
                            $all_forms = array_merge( $all_forms, adfoin_breakdance_extract_all_forms( $s_value, $post_id ) );
                        }
                    }
                }
            }
        }
    }

    return $all_forms;
}

// Get form fields
function adfoin_breakdance_get_form_fields( $form_provider, $form_id ) {

    if( $form_provider != 'breakdance' ) {
        return;
    }

    $form_id_main = explode( '_', $form_id )[0];
    $post_id = intval( explode( '_', $form_id )[0] );
    $forms = array();
    $content = get_post_meta( $post_id, 'breakdance_data', true );

    if( empty( $content ) ) {
        return;
    }

    $content = json_decode( $content );

    if( empty( $content ) ) {
        return;
    }

    $forms = adfoin_breakdance_extract_all_forms( $content, $post_id );

    if( empty( $forms ) ) {
        return;
    }

    $fields = array();

    foreach ($forms as $form) {
        if ($form_id == explode('-', $form['form_id'])[0]) {
            foreach ( $form['fields'] as $field ) {
                $fields[$field->advanced->id] = $field->label;
            }
        }
    }

    return $fields;
}