<?php

function adfoin_metform_get_forms( $form_provider )
{
    if ( $form_provider != 'metform' ) {
        return;
    }
    $args = array(
        'posts_per_page' => -1,
        'post_type'      => 'metform-form',
        'post_status'    => 'publish',
    );
    $forms = get_posts( $args );
    if ( empty($forms) ) {
        return;
    }
    $triggers = array();
    foreach ( $forms as $form ) {
        $triggers[$form->ID] = $form->post_title;
    }
    return $triggers;
}

function adfoin_metform_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'metform' ) {
        return;
    }
    $input_widgets = \Metform\Widgets\Manifest::instance()->get_input_widgets();
    $widget_input_data = get_post_meta( $form_id, '_elementor_data', true );
    $widget_input_data = json_decode( $widget_input_data );
    $all_fields = \MetForm\Core\Entries\Map_El::data( $widget_input_data, $input_widgets )->get_el();
    $fields = array();
    foreach ( $all_fields as $key => $field ) {
        if ( adfoin_fs()->is_not_paying() ) {
            if ( in_arraY( $field->widgetType, array( 'mf-text', 'mf-email' ) ) ) {
                $fields[$key] = $field->mf_input_label;
            }
        }
    }
    return $fields;
}

add_action(
    'metform_pro_form_data_for_pro_integrations',
    'adfoin_metform_handle_pro_form_submission',
    10,
    3
);
function adfoin_metform_handle_pro_form_submission( $form_setting, $form_data, $email_name )
{
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'metform', $form_data['id'] );
    if ( empty($saved_records) ) {
        return;
    }
    $posted_data = array();
    foreach ( $form_data as $key => $field ) {
        $posted_data[$key] = $field;
    }
    $integration->send( $saved_records, $posted_data );
}

add_action(
    'metform_after_store_form_data',
    'adfoin_metform_handle_form_submission',
    10,
    3
);
function adfoin_metform_handle_form_submission( $form_id, $form_data, $settings )
{
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'metform', $form_id );
    if ( empty($saved_records) ) {
        return;
    }
    $posted_data = array();
    foreach ( $form_data as $key => $field ) {
        $posted_data[$key] = $field;
    }
    $integration->send( $saved_records, $posted_data );
}

if ( adfoin_fs()->is_not_paying() ) {
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_metform_trigger_fields' );
}
function adfoin_metform_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'metform'" is="metform" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( 'adfoin_trigger_templates', 'adfoin_metform_trigger_template' );
function adfoin_metform_trigger_template()
{
    ?>
        <script type="text/template" id="metform-template">
            <tr valign="top" class="alternate" v-if="trigger.formId">
                <td scope="row-title">
                    <label for="tablecell">
                        <span class="dashicons dashicons-info-outline"></span>
                    </label>
                </td>
                <td>
                    <p>
                        <?php 
    esc_attr_e( 'The basic AFI plugin supports single line and email fields only', 'advanced-form-integration' );
    ?>
                    </p>
                </td>
            </tr>
        </script>
    <?php 
}
