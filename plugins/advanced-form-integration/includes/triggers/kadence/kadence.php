<?php
function adfoin_kadence_get_forms( $form_provider ) {
    if( $form_provider != 'kadence' ) {
        return;
    }

    $triggers = array();

    global $wpdb;

    $posts = $wpdb->get_results("select id,post_title,post_content from {$wpdb->posts} where ( post_content like '%<!-- wp:kadence/form%' or post_content like '%<!-- wp:kadence/advanced-form%' ) and post_status = 'publish'");


    foreach( $posts as $post ) {
        $post_id = $post->id;
        $post_title = $post->post_title;
        $post_content = $post->post_content;

        $pattern = '/<!--\s*wp:kadence\/form\s*({.*?})\s*-->/s';
        preg_match_all( $pattern, $post_content, $matches );
        
        if( !empty( $matches[1] ) ) {
            foreach( $matches[1] as $match ) {
                $form_array = json_decode( $match, true );
                if( $form_array !== null ) {
                    $unique_id = $form_array['uniqueID'];
                    $form_title = strlen( $post_title ) > 7 ? substr( $post_title, 0, 7 ) . '...' : $post_title;
                    $form_value = $unique_id . ' - ' . $form_title;
                    $triggers[$unique_id] = $form_value;
                }
            }
        }

        $advanced_pattern = '/<!--\s*wp:kadence\/advanced-form\s*({.*?})\s*\/-->/s';
        preg_match_all( $advanced_pattern, $post_content, $advanced_matches );

        if( !empty( $advanced_matches[1] ) ) {
            foreach( $advanced_matches[1] as $match ) {
                $form_array = json_decode( $match, true );
                if( $form_array !== null ) {
                    $unique_id = isset( $form_array['uniqueID'] ) ? $form_array['uniqueID'] : '';
                    if( !empty( $unique_id ) ) {
                        $key = 'adv_' . $form_array['id'];
                        $form_title = strlen( $post_title ) > 7 ? substr( $post_title, 0, 7 ) . '...' : $post_title;
                        $form_value = 'Adv ' . $unique_id . ' - ' . $form_title;
                        $triggers[$key] = $form_value;
                    }
                }
            }
        }
    }

    return $triggers;
}

function adfoin_kadence_get_form_fields( $form_provider, $form_id ) {
    if( $form_provider != 'kadence' ) {
        return;
    }

    global $wpdb;

    if( str_contains( $form_id, 'adv_' ) ) {
        $form_id = explode( 'adv_', $form_id );
        $post_id = $form_id[1];

        $post = $wpdb->get_results("select id,post_content from {$wpdb->posts} where id = {$post_id}");

        if( empty( $post ) ) {
            return;
        }

        $form_content = $post[0]->post_content;
        $fields = array();
        $lines = explode( "\n", $form_content );
        $pattern = '/\{.*\}/s';

        foreach( $lines as $line ) {
            preg_match_all( $pattern, $line, $matches );

            if( !empty( $matches[0] ) ) {
                foreach( $matches[0] as $match ) {
                    $form_array = json_decode( $match, true );
                    if( isset( $form_array['uniqueID'], $form_array['formID'], $form_array['label'] ) ) {
                        $fields['field' . $form_array['uniqueID']] = $form_array['label'];
                    }
                }
            }
        }

        return $fields;
    }

    $post_id = explode( '_', $form_id );
    $post_id = $post_id[0];

    $post = $wpdb->get_results("select id,post_content from {$wpdb->posts} where id = {$post_id}");

    if( empty( $post ) ) {
        return;
    }

    $form_content = $post[0]->post_content;
    $contentArray = explode('<!--', $form_content);
        $fields = [];
        foreach ($contentArray as $key => $value) {
            $tmpStr = ' wp:kadence/form {"uniqueID":"' . $form_id . '","postID":"' . $post_id . '"';
            if (str_contains($value, $tmpStr)) {
                $temp = str_replace(' wp:kadence/form', '', $value);

                $temp1 = explode('><', $temp);

                $cnt = 0;
                foreach ($temp1 as $key1 => $value1) {
                    if (str_contains($value1, 'data-type')) {
                        $regularExpressionName = '/name\s*=\s*"([^"]+)"/';
                        preg_match($regularExpressionName, $value1, $fieldName);
                        $regularExpressionId = '/id\s*=\s*"([^"]+)"/';
                        preg_match($regularExpressionId, $value1, $fieldId);
                        $regularExpressionDataLabel = '/data-label\s*=\s*"([^"]+)"/';
                        preg_match($regularExpressionDataLabel, $value1, $fieldDataLabel);
                        $regularExpressionDataType = '/data-type\s*=\s*"([^"]+)"/';
                        preg_match($regularExpressionDataType, $value1, $fieldDataType);

                        $fields[$fieldName[1]] = isset($fieldDataLabel[1]) ? $fieldDataLabel[1] : $fieldId[1];
                    }
                }
            }
        }
        return $fields;
}

add_action( 'kadence_blocks_form_submission', 'adfoin_kadence_handle_form_submission', 10, 3 );

function adfoin_kadence_handle_form_submission( $form_args, $fields, $form_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'kadence', $form_id );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();

    foreach( $fields as $key => $field ) {
        $posted_data['kb_field_' . $key] = $field['value'];
    }

    $integration->send( $saved_records, $posted_data );
}

add_action( 'kadence_blocks_advanced_form_submission', 'adfoin_kadence_handle_advanced_form_submission', 10, 3 );

function adfoin_kadence_handle_advanced_form_submission( $form_args, $fields, $form_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'kadence', $form_id );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();

    foreach( $fields as $key => $field ) {
        $posted_data[$field['name']] = $field['value'];
    }

    $integration->send( $saved_records, $posted_data );
}