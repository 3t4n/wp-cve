<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_clickup_actions',
    10,
    1
);
function adfoin_clickup_actions( $actions )
{
    $actions['clickup'] = array(
        'title' => __( 'Clickup', 'advanced-form-integration' ),
        'tasks' => array(
        'create_task' => __( 'Create Task', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_clickup_settings_tab',
    10,
    1
);
function adfoin_clickup_settings_tab( $providers )
{
    $providers['clickup'] = __( 'Clickup', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_clickup_settings_view',
    10,
    1
);
function adfoin_clickup_settings_view( $current_tab )
{
    if ( $current_tab != 'clickup' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_clickup_settings" );
    $api_token = ( get_option( 'adfoin_clickup_api_token' ) ? get_option( 'adfoin_clickup_api_token' ) : "" );
    ?>

    <form name="clickup_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>" method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_clickup_api_token">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Token', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_clickup_api_token"
                           value="<?php 
    echo  esc_attr( $api_token ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter the API Token', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p>
                        Go to My Settings > Apps. Generate API Token and copy it.
                    </p>
                </td>
            </tr>
        </table>
        <?php 
    submit_button();
    ?>
    </form>

    <?php 
}

add_action(
    'admin_post_adfoin_save_clickup_api_token',
    'adfoin_save_clickup_api_token',
    10,
    0
);
function adfoin_save_clickup_api_token()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_clickup_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_token = sanitize_text_field( $_POST["adfoin_clickup_api_token"] );
    // Save tokens
    update_option( "adfoin_clickup_api_token", $api_token );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=clickup" );
}

add_action( 'adfoin_action_fields', 'adfoin_clickup_action_fields' );
function adfoin_clickup_action_fields()
{
    ?>

    <script type="text/template" id="clickup-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'create_task'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Task Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>
            <tr class="alternate" v-if="action.task == 'create_task'">
                <td>
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Workspace', 'advanced-form-integration' );
    ?>
                    </label>
                </td>

                <td>
                    <select name="fieldData[workspaceId]" v-model="fielddata.workspaceId" required="true" @change="getSpaces">
                        <option value=""><?php 
    _e( 'Select...', 'advanced-form-integration' );
    ?></option>
                        <option v-for="(item, index) in fielddata.workspaces" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': workspaceLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr class="alternate" v-if="action.task == 'create_task'">
                <td>
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Space', 'advanced-form-integration' );
    ?>
                    </label>
                </td>

                <td>
                    <select name="fieldData[spaceId]" v-model="fielddata.spaceId" required="true" @change="getFolders">
                        <option value=""><?php 
    _e( 'Select...', 'advanced-form-integration' );
    ?></option>
                        <option v-for="(item, index) in fielddata.spaces" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': spaceLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr class="alternate" v-if="action.task == 'create_task'">
                <td>
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Folder', 'advanced-form-integration' );
    ?>
                    </label>
                </td>

                <td>
                    <select name="fieldData[folderId]" v-model="fielddata.folderId" @change="getLists">
                        <option value=""><?php 
    _e( 'Select...', 'advanced-form-integration' );
    ?></option>
                        <option v-for="(item, index) in fielddata.folders" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': folderLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr class="alternate" v-if="action.task == 'create_task'">
                <td>
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'List', 'advanced-form-integration' );
    ?>
                    </label>
                </td>

                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId">
                        <option value=""><?php 
    _e( 'Select...', 'advanced-form-integration' );
    ?></option>
                        <option v-for="(item, index) in fielddata.lists" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
            <?php 
    
    if ( adfoin_fs()->is_not_paying() ) {
        ?>
                    <tr valign="top" v-if="action.task == 'create_task'">
                        <th scope="row">
                            <?php 
        esc_attr_e( 'Go Pro', 'advanced-form-integration' );
        ?>
                        </th>
                        <td scope="row">
                            <span><?php 
        printf( __( 'To unlock tags & custom fields, consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
        ?></span>
                        </td>
                    </tr>
                    <?php 
    }
    
    ?>
        </table>
    </script>

    <?php 
}

/*
 * Clickup API Request
 */
function adfoin_clickup_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $api_token = ( get_option( 'adfoin_clickup_api_token' ) ? get_option( 'adfoin_clickup_api_token' ) : '' );
    $base_url = 'https://api.clickup.com/api/v2/';
    $url = $base_url . $endpoint;
    $args = array(
        'method'  => $method,
        'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => $api_token,
    ),
    );
    if ( 'POST' == $method || 'PUT' == $method ) {
        $args['body'] = json_encode( $data );
    }
    $response = wp_remote_request( $url, $args );
    if ( $record ) {
        adfoin_add_to_log(
            $response,
            $url,
            $args,
            $record
        );
    }
    return $response;
}

add_action(
    'wp_ajax_adfoin_get_clickup_workspaces',
    'adfoin_get_clickup_workspaces',
    10,
    0
);
/*
 * Get Clickup Workspaces
 */
function adfoin_get_clickup_workspaces()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $return = adfoin_clickup_request( 'team' );
    
    if ( !is_wp_error( $return ) ) {
        $body = json_decode( wp_remote_retrieve_body( $return ) );
        $workspaces = wp_list_pluck( $body->teams, 'name', 'id' );
        wp_send_json_success( $workspaces );
    } else {
        wp_send_json_error();
    }

}

add_action(
    'wp_ajax_adfoin_get_clickup_spaces',
    'adfoin_get_clickup_spaces',
    20,
    0
);
/*
 * Get Clickup spaces
 */
function adfoin_get_clickup_spaces()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $workspace_id = ( $_POST['workspaceId'] ? sanitize_text_field( $_POST['workspaceId'] ) : '' );
    $return = adfoin_clickup_request( 'team/' . $workspace_id . '/space' );
    
    if ( !is_wp_error( $return ) ) {
        $body = json_decode( wp_remote_retrieve_body( $return ) );
        $spaces = wp_list_pluck( $body->spaces, 'name', 'id' );
        wp_send_json_success( $spaces );
    } else {
        wp_send_json_error();
    }

}

add_action(
    'wp_ajax_adfoin_get_clickup_folders',
    'adfoin_get_clickup_folders',
    20,
    0
);
/*
 * Get Clickup folders
 */
function adfoin_get_clickup_folders()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $space_id = ( $_POST['spaceId'] ? sanitize_text_field( $_POST['spaceId'] ) : '' );
    $return = adfoin_clickup_request( 'space/' . $space_id . '/folder' );
    
    if ( !is_wp_error( $return ) ) {
        $body = json_decode( wp_remote_retrieve_body( $return ) );
        $folders = wp_list_pluck( $body->folders, 'name', 'id' );
        wp_send_json_success( $folders );
    } else {
        wp_send_json_error();
    }

}

add_action(
    'wp_ajax_adfoin_get_clickup_lists',
    'adfoin_get_clickup_lists',
    20,
    0
);
/*
 * Get Clickup lists
 */
function adfoin_get_clickup_lists()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $space_id = ( isset( $_POST['spaceId'] ) && $_POST['spaceId'] ? sanitize_text_field( $_POST['spaceId'] ) : '' );
    $folder_id = ( isset( $_POST['folderId'] ) && $_POST['folderId'] ? sanitize_text_field( $_POST['folderId'] ) : '' );
    if ( $space_id ) {
        $return = adfoin_clickup_request( 'space/' . $space_id . '/list' );
    }
    if ( $folder_id ) {
        $return = adfoin_clickup_request( 'folder/' . $folder_id . '/list' );
    }
    
    if ( !is_wp_error( $return ) ) {
        $body = json_decode( wp_remote_retrieve_body( $return ) );
        $lists = wp_list_pluck( $body->lists, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }

}

add_action(
    'adfoin_clickup_job_queue',
    'adfoin_clickup_job_queue',
    10,
    1
);
function adfoin_clickup_job_queue( $data )
{
    adfoin_clickup_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to ClickUp API
 */
function adfoin_clickup_send_data( $record, $posted_data )
{
    $record_data = json_decode( $record['data'], true );
    if ( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if ( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if ( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }
    $data = $record_data['field_data'];
    $task = $record['task'];
    $workspace_id = ( empty($data['workspaceId']) ? '' : $data['workspaceId'] );
    $space_id = ( empty($data['spaceId']) ? '' : $data['spaceId'] );
    $folder_id = ( empty($data['folderId']) ? '' : $data['folderId'] );
    $list_id = ( empty($data['listId']) ? '' : $data['listId'] );
    $name = ( empty($data['name']) ? '' : adfoin_get_parsed_values( $data['name'], $posted_data ) );
    $description = ( empty($data['description']) ? '' : adfoin_get_parsed_values( $data['description'], $posted_data ) );
    $start_date = ( empty($data['startDate']) ? '' : adfoin_get_parsed_values( $data['startDate'], $posted_data ) );
    $due_date = ( empty($data['dueDate']) ? '' : adfoin_get_parsed_values( $data['dueDate'], $posted_data ) );
    $due_on_x = ( empty($data["dueOnX"]) ? "" : adfoin_get_parsed_values( $data["dueOnX"], $posted_data ) );
    $priority_id = ( empty($data['priorityId']) ? '' : adfoin_get_parsed_values( $data['priorityId'], $posted_data ) );
    $assignees = ( empty($data['assignees']) ? '' : adfoin_get_parsed_values( $data['assignees'], $posted_data ) );
    
    if ( $task == 'create_task' ) {
        $task_data = array(
            'name'        => $name,
            'description' => $description,
        );
        
        if ( $start_date ) {
            $timezone = wp_timezone();
            $date = date_create( $start_date, $timezone );
            $start_timestamp = date_format( $date, "U" );
            $start_timestamp_ms = (int) $start_timestamp * 1000;
            if ( $start_timestamp_ms ) {
                $task_data['start_date'] = $start_timestamp_ms;
            }
        }
        
        
        if ( $due_date ) {
            $timezone = wp_timezone();
            $date = date_create( $due_date, $timezone );
            $due_timestamp = date_format( $date, "U" );
            $due_timestamp_ms = (int) $due_timestamp * 1000;
            if ( $due_timestamp_ms ) {
                $task_data['due_date'] = $due_timestamp_ms;
            }
        }
        
        
        if ( isset( $due_on_x ) && $due_on_x ) {
            $after_days = (int) $due_on_x;
            
            if ( $after_days ) {
                $timezone = wp_timezone();
                $date = date_create( '+' . $after_days . ' days', $timezone );
                $formatted_date = date_format( $date, 'Y-m-d' );
                $task_data['due_date'] = $formatted_date;
            }
        
        }
        
        if ( $priority_id ) {
            $task_data['priority'] = (int) $priority_id;
        }
        
        if ( $assignees ) {
            $assignee_ids = adfoin_get_clickup_assignee_ids( $workspace_id, $assignees );
            if ( $assignee_ids && is_array( $assignee_ids ) ) {
                $task_data['assignees'] = $assignee_ids;
            }
        }
        
        $response = adfoin_clickup_request(
            'list/' . $list_id . '/task',
            'POST',
            $task_data,
            $record
        );
    }
    
    return;
}

function adfoin_get_clickup_assignee_ids( $workspace_id, $emails )
{
    $assignee_ids = array();
    $space_data = adfoin_clickup_request( 'team/' . $workspace_id );
    $body = json_decode( wp_remote_retrieve_body( $space_data ), true );
    // $members      = wp_list_pluck( $body->team->members, 'id', 'email' );
    $emails = explode( ',', $emails );
    if ( isset( $body['team'] ) && $body['team'] ) {
        if ( isset( $body['team']['members'] ) && $body['team']['members'] ) {
            foreach ( $body['team']['members'] as $user ) {
                foreach ( $emails as $email ) {
                    if ( $user['user']['email'] == trim( $email ) ) {
                        $assignee_ids[] = $user['user']['id'];
                    }
                }
            }
        }
    }
    return $assignee_ids;
}
