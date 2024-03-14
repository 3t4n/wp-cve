<?php

add_filter( 'adfoin_action_providers', 'adfoin_trello_actions', 10, 1 );

function adfoin_trello_actions( $actions ) {

    $actions['trello'] = array(
        'title' => __( 'Trello', 'advanced-form-integration' ),
        'tasks' => array(
            'add_card'   => __( 'Add New Card', 'advanced-form-integration' ),
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_trello_settings_tab', 10, 1 );

function adfoin_trello_settings_tab( $providers ) {
    $providers['trello'] = __( 'Trello', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_trello_settings_view', 10, 1 );

function adfoin_trello_settings_view( $current_tab ) {
    if( $current_tab != 'trello' ) {
        return;
    }

    $nonce     = wp_create_nonce( 'adfoin_trello_settings' );
    $api_key   = adfoin_trello_get_api_key();
    $api_token = get_option( 'adfoin_trello_api_token' ) ? get_option( 'adfoin_trello_api_token' ) : '';
    $url       = "https://trello.com/1/authorize?expiration=never&name=Advanced%20Form%20Integration&scope=read%2Cwrite%2Caccount&response_type=token&key={$api_key}";
    ?>

    <form name="trello_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_trello_save_api_token">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Token', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_trello_api_token"
                           value="<?php echo esc_attr( $api_token ); ?>" placeholder="<?php _e( 'Please enter API Token', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                           <p class="description" id="code-description"><a
                                href="<?php echo esc_url( $url ); ?>"
                                target="_blank" rel="noopener noreferrer"><?php _e( 'Click here to get token', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_trello_save_api_token', 'adfoin_save_trello_api_token', 10, 0 );

function adfoin_save_trello_api_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_trello_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_token = sanitize_text_field( $_POST["adfoin_trello_api_token"] );

    // Save tokens
    update_option( "adfoin_trello_api_token", $api_token );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=trello" );
}

add_action( 'adfoin_action_fields', 'adfoin_trello_action_fields' );

function adfoin_trello_action_fields() {
    ?>
    <script type="text/template" id="trello-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_card'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_card'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Select Trello Board', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[boardId]" v-model="fielddata.boardId" required="required" @change="getLists">
                        <option value=""> <?php _e( 'Select Board...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.boards" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': boardLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_card'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Select Trello List', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId" required="required">
                        <option value=""> <?php _e( 'Select List...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.lists" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>

        </table>
    </script>
    <?php
}

function adfoin_trello_get_api_key() {
    return '13b9118a04aaece3faae1eda7e424edc';
}

add_action( 'wp_ajax_adfoin_get_trello_boards', 'adfoin_get_trello_boards', 10, 0 );
/*
 * Get Kalviyo add_cardr lists
 */
function adfoin_get_trello_boards() {
    
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key   = adfoin_trello_get_api_key();
    $api_token = get_option( 'adfoin_trello_api_token' ) ? get_option( 'adfoin_trello_api_token' ) : '';

    if( !$api_key || !$api_token ) {
        return array();
    }

    $args = array(
        'headers' => array(
            'Content-Type'  => 'application/json'
        )
    );

    $url = "https://api.trello.com/1/members/me/boards?&filter=open&key={$api_key}&token={$api_token}";

    $result = wp_remote_get( $url, $args);

    if( is_wp_error( $result ) || '200' != $result['response']['code'] ) {
        wp_send_json_error();
    }

    $body = json_decode( wp_remote_retrieve_body( $result ) );
    $boards = wp_list_pluck( $body, 'name', 'id' );

    wp_send_json_success( $boards );
}

add_action( 'wp_ajax_adfoin_get_trello_lists', 'adfoin_get_trello_lists', 10, 0 );

function adfoin_get_trello_lists() {
    
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key   = adfoin_trello_get_api_key();
    $api_token = get_option( 'adfoin_trello_api_token' ) ? get_option( 'adfoin_trello_api_token' ) : '';
    $board_id  = isset( $_POST['boardId'] ) ? $_POST['boardId'] : '';

    if( !$api_key || !$api_token || !$board_id ) {
        return array();
    }

    $args = array(
        'headers' => array(
            'Content-Type'  => 'application/json'
        )
    );

    $url = "https://api.trello.com/1/boards/{$board_id}/lists?filter=open&key={$api_key}&token={$api_token}";

    $result = wp_remote_get( $url, $args);

    if( is_wp_error( $result ) || '200' != $result['response']['code'] ) {
        wp_send_json_error();
    }
    
    $body = json_decode( wp_remote_retrieve_body( $result ) );
    $lists = wp_list_pluck( $body, 'name', 'id' );

    wp_send_json_success( $lists );
}

/*
 * Saves connection mapping
 */
function adfoin_trello_save_integration() {
    $params = array();
    parse_str( adfoin_sanitize_text_or_array_field( $_POST['formData'] ), $params );

    $trigger_data = isset( $_POST["triggerData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
    $action_data  = isset( $_POST["actionData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["actionData"] ) : array();
    $field_data   = isset( $_POST["fieldData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["fieldData"] ) : array();

    $integration_title = isset( $trigger_data["integrationTitle"] ) ? $trigger_data["integrationTitle"] : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"] ) ? $trigger_data["formProviderId"] : "";
    $form_id           = isset( $trigger_data["formId"] ) ? $trigger_data["formId"] : "";
    $form_name         = isset( $trigger_data["formName"] ) ? $trigger_data["formName"] : "";
    $action_provider   = isset( $action_data["actionProviderId"] ) ? $action_data["actionProviderId"] : "";
    $task              = isset( $action_data["task"] ) ? $action_data["task"] : "";
    $type              = isset( $params["type"] ) ? $params["type"] : "";



    $all_data = array(
        'trigger_data' => $trigger_data,
        'action_data'  => $action_data,
        'field_data'   => $field_data
    );

    global $wpdb;

    $integration_table = $wpdb->prefix . 'adfoin_integration';

    if ( $type == 'new_integration' ) {

        $result = $wpdb->insert(
            $integration_table,
            array(
                'title'           => $integration_title,
                'form_provider'   => $form_provider_id,
                'form_id'         => $form_id,
                'form_name'       => $form_name,
                'action_provider' => $action_provider,
                'task'            => $task,
                'data'            => json_encode( $all_data, true ),
                'status'          => 1
            )
        );

    }

    if ( $type == 'update_integration' ) {

        $id = esc_sql( trim( $params['edit_id'] ) );

        if ( $type != 'update_integration' &&  !empty( $id ) ) {
            return;
        }

        $result = $wpdb->update( $integration_table,
            array(
                'title'           => $integration_title,
                'form_provider'   => $form_provider_id,
                'form_id'         => $form_id,
                'form_name'       => $form_name,
                'data'            => json_encode( $all_data, true ),
            ),
            array(
                'id' => $id
            )
        );
    }

    if ( $result ) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}

add_action( 'adfoin_trello_job_queue', 'adfoin_trello_job_queue', 10, 1 );

function adfoin_trello_job_queue( $data ) {
    adfoin_trello_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Trello API
 */
function adfoin_trello_send_data( $record, $posted_data ) {

    $api_key   = adfoin_trello_get_api_key();
    $api_token = get_option( 'adfoin_trello_api_token' ) ? get_option( 'adfoin_trello_api_token' ) : '';

    if( !$api_key || !$api_token ) {
        return;
    }

    $record_data    = json_decode( $record["data"], true );

    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $data    = $record_data["field_data"];
    $task    = $record["task"];

    if( $task == "add_card" ) {

        $board_id    = $data["boardId"];
        $list_id     = $data["listId"];
        $name        = empty( $data["name"] ) ? "" : adfoin_get_parsed_values( $data["name"], $posted_data );
        $description = empty( $data["description"] ) ? "" : adfoin_get_parsed_values( $data["description"], $posted_data );
        $url         = "https://api.trello.com/1/cards?key={$api_key}&token={$api_token}&idList={$list_id}";
        $pos         = empty( $data["pos"] ) ? "" : adfoin_get_parsed_values( $data["pos"], $posted_data );

        $body = array(
            'name' => $name,
            'desc' => $description,
            'pos'  => $pos
        );

        $args = array(

            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode( $body )
        );

        $return = wp_remote_post( $url, $args );

        adfoin_add_to_log( $return, $url, $args, $record );
    }

    return;
}