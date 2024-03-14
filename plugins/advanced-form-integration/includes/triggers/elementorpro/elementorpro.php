<?php

function adfoin_elementorpro_get_forms( $form_provider )
{
    if ( $form_provider != 'elementorpro' ) {
        return;
    }
    global  $wpdb, $only_forms ;
    $result = $wpdb->get_results( "SELECT * FROM {$wpdb->postmeta} WHERE meta_key = '_elementor_data' AND  meta_value LIKE '%form_name%'", ARRAY_A );
    foreach ( $result as $single_post ) {
        if ( wp_is_post_revision( $single_post['post_id'] ) ) {
            continue;
        }
        $elementor_data = json_decode( $single_post['meta_value'], true );
        if ( is_array( $elementor_data ) ) {
            
            if ( count( $elementor_data ) > 1 ) {
                foreach ( $elementor_data as $element ) {
                    adfoin_elementorpro_find_element_recursive( array( $element ), $single_post['post_id'] );
                }
            } else {
                adfoin_elementorpro_find_element_recursive( $elementor_data, $single_post['post_id'] );
            }
        
        }
    }
    $only_forms = array_filter( $only_forms );
    $form_list = array();
    if ( $only_forms ) {
        foreach ( $only_forms as $single ) {
            $form_list[$single['post_id'] . '_' . $single['id']] = $single['post_id'] . ' ' . $single['settings']['form_name'];
        }
    }
    return $form_list;
}

function adfoin_elementorpro_find_element_recursive( $elements, $post_id )
{
    global  $only_forms ;
    foreach ( $elements as $element ) {
        if ( isset( $element['widgetType'] ) ) {
            
            if ( 'form' === $element['widgetType'] ) {
                $element['post_id'] = $post_id;
                $only_forms[] = $element;
            }
        
        }
        
        if ( !empty($element['elements']) ) {
            adfoin_elementorpro_find_element_recursive( $element['elements'], $post_id );
            // if ( $element ) {
            //     return $element;
            // }
        }
    
    }
}

function adfoin_elementorpro_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'elementorpro' ) {
        return;
    }
    $ids = explode( '_', $form_id );
    $elementor = \ElementorPro\Plugin::elementor();
    $meta = $elementor->documents->get( $ids[0] )->get_elements_data();
    $form = \ElementorPro\Modules\Forms\Module::find_element_recursive( $meta, $ids[1] );
    $form_fields = $form['settings']['form_fields'];
    $fields = array();
    foreach ( $form_fields as $single ) {
        $field_label = '';
        if ( isset( $single['field_label'] ) ) {
            $field_label = $single['field_label'];
        }
        if ( !$field_label ) {
            if ( isset( $single['placeholder'] ) ) {
                $field_label = $single['placeholder'];
            }
        }
        if ( !$field_label ) {
            if ( isset( $single['field_type'] ) ) {
                $field_label = $single['field_type'];
            }
        }
        if ( adfoin_fs()->is_not_paying() ) {
            
            if ( isset( $single['field_type'] ) ) {
                if ( $single['field_type'] == 'email' ) {
                    $fields[$single['custom_id']] = $field_label;
                }
            } else {
                $fields[$single['custom_id']] = $field_label;
            }
        
        }
    }
    $special_tags = adfoin_get_special_tags();
    if ( is_array( $fields ) && is_array( $special_tags ) ) {
        $fields = $fields + $special_tags;
    }
    return $fields;
}

function adfoin_elementorpro_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != 'elementorpro' ) {
        return;
    }
    $ids = explode( '_', $form_id );
    $elementor = \ElementorPro\Plugin::elementor();
    $meta = $elementor->documents->get( $ids[0] )->get_elements_data();
    $form = \ElementorPro\Modules\Forms\Module::find_element_recursive( $meta, $ids[1] );
    return $form['settings']['form_name'];
}

add_action(
    'elementor_pro/forms/new_record',
    'adfoin_elementorpro_submission',
    10,
    10
);
function adfoin_elementorpro_submission( $record, $form )
{
    if ( !isset( $_POST['post_id'] ) || !isset( $_POST['form_id'] ) ) {
        return;
    }
    global  $wpdb ;
    $post_id = ( isset( $_POST['post_id'] ) ? sanitize_text_field( $_POST['post_id'] ) : '' );
    $form_id = ( isset( $_POST['form_id'] ) ? sanitize_text_field( $_POST['form_id'] ) : '' );
    $sql = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'elementorpro' AND form_id = %s", $post_id . '_' . $form_id );
    $saved_records = $wpdb->get_results( $sql, ARRAY_A );
    if ( empty($saved_records) ) {
        return;
    }
    $posted_data = array();
    $fields = $record->get( 'fields' );
    foreach ( $fields as $field ) {
        if ( adfoin_fs()->is_not_paying() ) {
            if ( 'text' == $field['type'] || 'email' == $field['type'] ) {
                $posted_data[$field['id']] = adfoin_sanitize_text_or_array_field( $field['value'] );
            }
        }
    }
    $post = get_post( $post_id, 'OBJECT' );
    $special_tag_values = adfoin_get_special_tags_values( $post );
    if ( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
        $posted_data = $posted_data + $special_tag_values;
    }
    $posted_data['submission_date'] = date( 'Y-m-d H:i:s' );
    $posted_data['user_ip'] = adfoin_get_user_ip();
    $posted_data['form_id'] = $form_id;
    $posted_data['post_id'] = $post_id;
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );
    foreach ( $saved_records as $record ) {
        $action_provider = $record['action_provider'];
        
        if ( $job_queue ) {
            as_enqueue_async_action( "adfoin_{$action_provider}_job_queue", array(
                'data' => array(
                'record'      => $record,
                'posted_data' => $posted_data,
            ),
            ) );
        } else {
            call_user_func( "adfoin_{$action_provider}_send_data", $record, $posted_data );
        }
    
    }
    return;
}

if ( adfoin_fs()->is_not_paying() ) {
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_elementorpro_trigger_fields' );
}
function adfoin_elementorpro_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'elementorpro'" is="elementorpro" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( "adfoin_trigger_templates", "adfoin_elementorpro_trigger_template" );
function adfoin_elementorpro_trigger_template()
{
    ?>
        <script type="text/template" id="elementorpro-template">
            <tr valign="top" class="alternate" v-if="trigger.formId">
                <td scope="row-title">
                    <label for="tablecell">
                        <span class="dashicons dashicons-info-outline"></span>
                    </label>
                </td>
                <td>
                    <p>
                        <?php 
    esc_attr_e( 'The basic AFI plugin supports name and email fields only', 'advanced-form-integration' );
    ?>
                    </p>
                </td>
            </tr>
        </script>
    <?php 
}
