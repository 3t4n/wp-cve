<?php

add_filter( 'adfoin_action_providers', 'adfoin_revue_actions', 10, 1 );

function adfoin_revue_actions( $actions ) {

    $actions['revue'] = array(
        'title' => __( 'Revue', 'advanced-form-integration' ),
        'tasks' => array(
            'subscribe'   => __( 'Create Subscriber', 'advanced-form-integration' )
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_revue_settings_tab', 10, 1 );

function adfoin_revue_settings_tab( $providers ) {
    $providers['revue'] = __( 'Revue', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_revue_settings_view', 10, 1 );

function adfoin_revue_settings_view( $current_tab ) {
    if( $current_tab != 'revue' ) {
        return;
    }

    $nonce      = wp_create_nonce( "adfoin_revue_settings" );
    $api_key    = get_option( 'adfoin_revue_api_key' ) ? get_option( 'adfoin_revue_api_key' ) : "";
    ?>

    <form name="revue_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_revue_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Key', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_revue_api_key"
                           value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Please enter API Key', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Go to Account Settings > Integrations', 'advanced-form-integration' ); ?></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_save_revue_api_key', 'adfoin_save_revue_api_key', 10, 0 );

function adfoin_save_revue_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_revue_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key = sanitize_text_field( $_POST["adfoin_revue_api_key"] );

    // Save tokens
    update_option( "adfoin_revue_api_key", $api_key );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=revue" );
}

add_action( 'adfoin_add_js_fields', 'adfoin_revue_js_fields', 10, 1 );

function adfoin_revue_js_fields( $field_data ) {}

add_action( 'adfoin_action_fields', 'adfoin_revue_action_fields' );

function adfoin_revue_action_fields() {
    ?>
    <script type="text/template" id="revue-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe'">
                <th scope="row">
                    <?php esc_attr_e( 'Subscriber Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Disable Double Opt-In', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox" name="fieldData[doptin]" value="true" v-model="fielddata.doptin">
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>
    <?php
}

/*
 * Handles sending data to Revue API
 */
function adfoin_revue_send_data( $record, $posted_data ) {

    $api_key    = get_option( 'adfoin_revue_api_key' ) ? get_option( 'adfoin_revue_api_key' ) : "";

    if( !$api_key ) {
        return;
    }

    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $data = $record_data["field_data"];
    $task = $record["task"];

    if( $task == "subscribe" ) {
        $email      = empty( $data["email"] ) ? "" : adfoin_get_parsed_values( $data["email"], $posted_data );
        $first_name = empty( $data["firstName"] ) ? "" : adfoin_get_parsed_values( $data["firstName"], $posted_data );
        $last_name  = empty( $data["lastName"] ) ? "" : adfoin_get_parsed_values( $data["lastName"], $posted_data );
        $doptin     = $data["doptin"];

        $data = array(
            'email'      => $email,
            'first_name' => $first_name,
            'last_name'  => $last_name
        );

        if("true" == $doptin) {
            $data['double_opt_in'] = false;
        }

        $url = "https://www.getrevue.co/api/v2/subscribers";

        $args = array(

            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Token token="' . $api_key . '"'
            ),
            'body' => json_encode( $data )
        );

        $return = wp_remote_post( $url, $args );

        adfoin_add_to_log( $return, $url, $args, $record );
    }

    return;
}