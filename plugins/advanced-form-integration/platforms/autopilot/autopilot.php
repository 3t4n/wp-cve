<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_autopilot_actions',
    10,
    1
);
function adfoin_autopilot_actions( $actions )
{
    $actions['autopilot'] = array(
        'title' => __( 'Autopilot', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe' => __( 'Add/Update Contact', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_autopilot_settings_tab',
    10,
    1
);
function adfoin_autopilot_settings_tab( $providers )
{
    $providers['autopilot'] = __( 'Autopilot', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_autopilot_settings_view',
    10,
    1
);
function adfoin_autopilot_settings_view( $current_tab )
{
    if ( $current_tab != 'autopilot' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_autopilot_settings" );
    $api_key = ( get_option( 'adfoin_autopilot_api_key' ) ? get_option( 'adfoin_autopilot_api_key' ) : "" );
    ?>

    <form name="autopilot_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_autopilot_save_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_autopilot_api_key"
                           value="<?php 
    echo  esc_attr( $api_key ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Key', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php 
    _e( 'Please go to Settings > Autopilot API to get the API Key', 'advanced-form-integration' );
    ?></a></p>
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
    'admin_post_adfoin_autopilot_save_api_key',
    'adfoin_save_autopilot_api_key',
    10,
    0
);
function adfoin_save_autopilot_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_autopilot_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = sanitize_text_field( $_POST["adfoin_autopilot_api_key"] );
    // Save tokens
    update_option( "adfoin_autopilot_api_key", $api_key );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=autopilot" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_autopilot_js_fields',
    10,
    1
);
function adfoin_autopilot_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_autopilot_action_fields' );
function adfoin_autopilot_action_fields()
{
    ?>
    <script type="text/template" id="autopilot-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Contact Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Contact List', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId" required="required">
                        <option value=""> <?php 
    _e( 'Select List...', 'advanced-form-integration' );
    ?> </option>
                        <option v-for="(item, index) in fielddata.list" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
            <?php 
    
    if ( adfoin_fs()->is_not_paying() ) {
        ?>
                    <tr valign="top" v-if="action.task == 'subscribe'">
                        <th scope="row">
                            <?php 
        esc_attr_e( 'Go Pro', 'advanced-form-integration' );
        ?>
                        </th>
                        <td scope="row">
                            <span><?php 
        printf( __( 'To unlock custom fields, consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
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

add_action(
    'wp_ajax_adfoin_get_autopilot_list',
    'adfoin_get_autopilot_list',
    10,
    0
);
/*
 * Get subscriber lists
 */
function adfoin_get_autopilot_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = get_option( "adfoin_autopilot_api_key" );
    if ( !$api_key ) {
        return array();
    }
    $url = "https://api2.autopilothq.com/v1/lists";
    $args = array(
        'headers' => array(
        'Content-Type'    => 'application/json',
        'autopilotapikey' => $api_key,
    ),
    );
    $data = wp_remote_request( $url, $args );
    if ( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body = json_decode( $data["body"] );
    $lists = wp_list_pluck( $body->lists, 'title', 'list_id' );
    wp_send_json_success( $lists );
}

/*
 * Saves connection mapping
 */
function adfoin_autopilot_save_integration()
{
    $params = array();
    parse_str( adfoin_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data = ( isset( $_POST["triggerData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["triggerData"] ) : array() );
    $action_data = ( isset( $_POST["actionData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["actionData"] ) : array() );
    $field_data = ( isset( $_POST["fieldData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["fieldData"] ) : array() );
    $integration_title = ( isset( $trigger_data["integrationTitle"] ) ? $trigger_data["integrationTitle"] : "" );
    $form_provider_id = ( isset( $trigger_data["formProviderId"] ) ? $trigger_data["formProviderId"] : "" );
    $form_id = ( isset( $trigger_data["formId"] ) ? $trigger_data["formId"] : "" );
    $form_name = ( isset( $trigger_data["formName"] ) ? $trigger_data["formName"] : "" );
    $action_provider = ( isset( $action_data["actionProviderId"] ) ? $action_data["actionProviderId"] : "" );
    $task = ( isset( $action_data["task"] ) ? $action_data["task"] : "" );
    $type = ( isset( $params["type"] ) ? $params["type"] : "" );
    $all_data = array(
        'trigger_data' => $trigger_data,
        'action_data'  => $action_data,
        'field_data'   => $field_data,
    );
    global  $wpdb ;
    $integration_table = $wpdb->prefix . 'adfoin_integration';
    if ( $type == 'new_integration' ) {
        $result = $wpdb->insert( $integration_table, array(
            'title'           => $integration_title,
            'form_provider'   => $form_provider_id,
            'form_id'         => $form_id,
            'form_name'       => $form_name,
            'action_provider' => $action_provider,
            'task'            => $task,
            'data'            => json_encode( $all_data, true ),
            'status'          => 1,
        ) );
    }
    
    if ( $type == 'update_integration' ) {
        $id = esc_sql( trim( $params['edit_id'] ) );
        if ( $type != 'update_integration' && !empty($id) ) {
            return;
        }
        $result = $wpdb->update( $integration_table, array(
            'title'         => $integration_title,
            'form_provider' => $form_provider_id,
            'form_id'       => $form_id,
            'form_name'     => $form_name,
            'data'          => json_encode( $all_data, true ),
        ), array(
            'id' => $id,
        ) );
    }
    
    
    if ( $result ) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }

}

add_action(
    'adfoin_autopilot_job_queue',
    'adfoin_autopilot_job_queue',
    10,
    1
);
function adfoin_autopilot_job_queue( $data )
{
    adfoin_autopilot_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Autopilot Journeys API
 */
function adfoin_autopilot_send_data( $record, $posted_data )
{
    $api_key = ( get_option( 'adfoin_autopilot_api_key' ) ? get_option( 'adfoin_autopilot_api_key' ) : "" );
    if ( !$api_key ) {
        return;
    }
    $record_data = json_decode( $record["data"], true );
    if ( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if ( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if ( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }
    $data = $record_data["field_data"];
    $task = $record["task"];
    
    if ( $task == "subscribe" ) {
        $sequence_id = $data["listId"];
        $email = ( empty($data["email"]) ? "" : adfoin_get_parsed_values( $data["email"], $posted_data ) );
        $first_name = ( empty($data["firstName"]) ? "" : adfoin_get_parsed_values( $data["firstName"], $posted_data ) );
        $last_name = ( empty($data["lastName"]) ? "" : adfoin_get_parsed_values( $data["lastName"], $posted_data ) );
        $twitter = ( empty($data["twitter"]) ? "" : adfoin_get_parsed_values( $data["twitter"], $posted_data ) );
        $salutation = ( empty($data["salutation"]) ? "" : adfoin_get_parsed_values( $data["salutation"], $posted_data ) );
        $company = ( empty($data["company"]) ? "" : adfoin_get_parsed_values( $data["company"], $posted_data ) );
        $numberofemployees = ( empty($data["numberOfEmployees"]) ? "" : adfoin_get_parsed_values( $data["numberOfEmployees"], $posted_data ) );
        $title = ( empty($data["title"]) ? "" : adfoin_get_parsed_values( $data["title"], $posted_data ) );
        $industry = ( empty($data["industry"]) ? "" : adfoin_get_parsed_values( $data["industry"], $posted_data ) );
        $phone = ( empty($data["phone"]) ? "" : adfoin_get_parsed_values( $data["phone"], $posted_data ) );
        $mobilephone = ( empty($data["mobilePhone"]) ? "" : adfoin_get_parsed_values( $data["mobilePhone"], $posted_data ) );
        $fax = ( empty($data["fax"]) ? "" : adfoin_get_parsed_values( $data["fax"], $posted_data ) );
        $website = ( empty($data["website"]) ? "" : adfoin_get_parsed_values( $data["website"], $posted_data ) );
        $mailingstreet = ( empty($data["mailingStreet"]) ? "" : adfoin_get_parsed_values( $data["mailingStreet"], $posted_data ) );
        $mailingcity = ( empty($data["mailingCity"]) ? "" : adfoin_get_parsed_values( $data["mailingCity"], $posted_data ) );
        $mailingstate = ( empty($data["mailingState"]) ? "" : adfoin_get_parsed_values( $data["mailingState"], $posted_data ) );
        $mailingpostalcode = ( empty($data["mailingPostalCode"]) ? "" : adfoin_get_parsed_values( $data["mailingPostalCode"], $posted_data ) );
        $mailingcountry = ( empty($data["mailingCountry"]) ? "" : adfoin_get_parsed_values( $data["mailingCountry"], $posted_data ) );
        $leadsource = ( empty($data["leadSource"]) ? "" : adfoin_get_parsed_values( $data["leadSource"], $posted_data ) );
        $status = ( empty($data["status"]) ? "" : adfoin_get_parsed_values( $data["status"], $posted_data ) );
        $linkedin = ( empty($data["linkedIn"]) ? "" : adfoin_get_parsed_values( $data["linkedIn"], $posted_data ) );
        $notify = ( empty($data["notify"]) ? "" : adfoin_get_parsed_values( $data["notify"], $posted_data ) );
        $url = "https://api2.autopilothq.com/v1/contact";
        $data = array(
            "contact" => array(
            "Email"             => $email,
            "FirstName"         => $first_name,
            "LastName"          => $last_name,
            "Twitter"           => $twitter,
            "Salutation"        => $salutation,
            "Company"           => $company,
            "NumberOfEmployees" => $numberofemployees,
            "Title"             => $title,
            "Industry"          => $industry,
            "Phone"             => $phone,
            "MobilePhone"       => $mobilephone,
            "Fax"               => $fax,
            "Website"           => $website,
            "MailingStreet"     => $mailingstreet,
            "MailingCity"       => $mailingcity,
            "MailingState"      => $mailingstate,
            "MailingPostalCode" => $mailingpostalcode,
            "MailingCountry"    => $mailingcountry,
            "LeadSource"        => $leadsource,
            "LinkedIn"          => $linkedin,
        ),
        );
        $data = array_filter( $data );
        if ( $sequence_id ) {
            $data["contact"]["_autopilot_list"] = $sequence_id;
        }
        $args = array(
            'headers' => array(
            'Content-Type'    => 'application/json',
            'autopilotapikey' => $api_key,
        ),
            'body'    => json_encode( $data ),
        );
        $return = wp_remote_post( $url, $args );
        adfoin_add_to_log(
            $return,
            $url,
            $args,
            $record
        );
    }
    
    return;
}
