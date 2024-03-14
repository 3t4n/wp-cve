<?php
 
add_filter( 'adfoin_action_providers', 'adfoin_vtiger_actions', 10, 1 );
 
function adfoin_vtiger_actions( $actions ) {
 
    $actions['vtiger'] = array(
        'title' => __( 'Vtiger CRM', 'advanced-form-integration' ),
        'tasks' => array(
            'add_fields' => __( 'Add Organization, Contact', 'advanced-form-integration' )
        )
    );
 
    return $actions;
}
 
add_filter( 'adfoin_settings_tabs', 'adfoin_vtiger_settings_tab', 10, 1 );
 
function adfoin_vtiger_settings_tab( $providers ) {
    $providers['vtiger'] = __( 'Vtiger CRM', 'advanced-form-integration' );
 
    return $providers;
}
 
add_action( 'adfoin_settings_view', 'adfoin_vtiger_settings_view', 10, 1 );
 
function adfoin_vtiger_settings_view( $current_tab ) {
    if( $current_tab != 'vtiger' ) {
        return;
    }
 
    $nonce      = wp_create_nonce( 'adfoin_vtiger_settings' );
    $baseurl    = get_option( 'adfoin_vtiger_baseurl' ) ? get_option( 'adfoin_vtiger_baseurl' ) : '';
    $username   = get_option( 'adfoin_vtiger_username' ) ? get_option( 'adfoin_vtiger_username' ) : '';
    $access_key = get_option( 'adfoin_vtiger_access_key' ) ? get_option( 'adfoin_vtiger_access_key' ) : '';
    ?>
 
    <form name="vtiger_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
        method="post" class="container">
 
        <input type="hidden" name="action" value="adfoin_save_vtiger_api_token">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>
 
        <table class="form-table">
        <tr valign="top">
                <th scope="row"> <?php _e( 'Base URL', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_vtiger_baseurl"
                        value="<?php echo esc_attr( $baseurl ); ?>" placeholder="<?php _e( 'Please enter yout crm baseurl', 'advanced-form-integration' ); ?>"
                        class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Login to Vtiger account and copy the main URL, e.g., https://xxxxxx.odxx.vtiger.com', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php _e( 'Username', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_vtiger_username"
                        value="<?php echo esc_attr( $username ); ?>" placeholder="<?php _e( 'Please enter username', 'advanced-form-integration' ); ?>"
                        class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Go to Settings > My Preferences > User information. Copy username and access key.', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php _e( 'Access Key', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_vtiger_access_key"
                        value="<?php echo esc_attr( $access_key ); ?>" placeholder="<?php _e( 'Please enter access key', 'advanced-form-integration' ); ?>"
                        class="regular-text"/>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
 
    <?php
}
 
add_action( 'admin_post_adfoin_save_vtiger_api_token', 'adfoin_save_vtiger_api_token', 10, 0 );
 
function adfoin_save_vtiger_api_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_vtiger_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
 
    $baseurl    = sanitize_text_field( $_POST['adfoin_vtiger_baseurl'] );
    $username   = sanitize_text_field( $_POST['adfoin_vtiger_username'] );
    $access_key = sanitize_text_field( $_POST['adfoin_vtiger_access_key'] );
 
    // Save tokens
    update_option( 'adfoin_vtiger_baseurl', $baseurl );
    update_option( 'adfoin_vtiger_username', $username );
    update_option( 'adfoin_vtiger_access_key', $access_key );
 
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=vtiger" );
}
 
add_action( 'adfoin_action_fields', 'adfoin_vtiger_action_fields', 10, 1 );
 
function adfoin_vtiger_action_fields() {
    ?>
    <script type="text/template" id="vtiger-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_fields'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">
                </td>
            </tr>
 
            <tr valign="top" class="alternate" v-if="action.task == 'add_fields'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Owner', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[owner]" v-model="fielddata.owner" required="required">
                        <option value=""> <?php _e( 'Select Owner...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.ownerList" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': ownerLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>
 
            <tr valign="top" class="alternate" v-if="action.task == 'add_fields'">
            <td scope="row-title">
                <label for="tablecell">
                    <?php esc_attr_e( 'Entities', 'advanced-form-integration' ); ?>
                </label>
            </td>
            <td>
                <div class="object_selection" style="display: inline;">
                    <input type="checkbox" id="organization__chosen" value="true" v-model="fielddata.organization__chosen" name="fieldData[organization__chosen]">
                    <label style="margin-right:10px;" for="organization__chosen">Organization</label>
                    <input type="checkbox" id="contact__chosen" value="true" v-model="fielddata.contact__chosen" name="fieldData[contact__chosen]">
                    <label style="margin-right:10px;" for="contact__chosen">Contact</label>
                    <input type="checkbox" id="deal__chosen" value="true" v-model="fielddata.deal__chosen" name="fieldData[deal__chosen]">
                    <label style="margin-right:10px;" for="deal__chosen">Deal</label>
                    <!-- <input type="checkbox" id="case__chosen" value="true" v-model="fielddata.case__chosen" name="fieldData[case__chosen]">
                    <label style="margin-right:10px;" for="case__chosen">Case</label>
                    <input type="checkbox" id="task__chosen" value="true" v-model="fielddata.task__chosen" name="fieldData[task__chosen]">
                    <label style="margin-right:10px;" for="task__chosen">Task</label> -->
                </div>
               
                <button class="button-secondary" @click.stop.prevent="getFields">Get Fields</button>
                <div class="spinner" v-bind:class="{'is-active': fieldsLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
               
            </td>
        </tr>
 
            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>
    <?php
}
 
 /*
 * Vtiger CRM API Request
 */
function adfoin_vtiger_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {
 
    $baseurl    = get_option( 'adfoin_vtiger_baseurl' ) ? get_option( 'adfoin_vtiger_baseurl' ) : '';
    $username   = get_option( 'adfoin_vtiger_username' ) ? get_option( 'adfoin_vtiger_username' ) : '';
    $access_key = get_option( 'adfoin_vtiger_access_key' ) ? get_option( 'adfoin_vtiger_access_key' ) : '';
    $url        = $baseurl . '/restapi/v1/vtiger/default/' . $endpoint;
 
    $args = array(
        'method'  => $method,
        'headers' => array(
            'Content-Type'  => 'application/x-www-form-urlencoded',
            'Accept'        => 'application/json',
            'Authorization' => 'Basic ' . base64_encode( $username . ':' . $access_key )
        ),
    );
 
    $response = wp_remote_request( $url, $args );
 
    if ($record) {
        adfoin_add_to_log( $response, $url, $args, $record );
    }
 
    return $response;
}
 
add_action( 'wp_ajax_adfoin_get_vtiger_owner_list', 'adfoin_get_vtiger_owner_list', 10, 0 );
 
/*
* Get Vtiger Owner list
*/
function adfoin_get_vtiger_owner_list() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
 
    $users    = adfoin_get_vtiger_users();
    $groups   = adfoin_get_vtiger_groups();
    $combined = array_merge( $users, $groups );
   
    wp_send_json_success( $combined );
}
 
//Get User list
function adfoin_get_vtiger_users() {
    $data  = adfoin_vtiger_request( 'query?query=SELECT * FROM Users;' );
    $body  = json_decode( wp_remote_retrieve_body( $data ) );
    $users = wp_list_pluck( $body->result, 'userlabel', 'id' );
 
    return $users;
}
 
//Get User list
function adfoin_get_vtiger_groups() {
    $data   = adfoin_vtiger_request( 'query?query=SELECT * FROM Groups;' );
    $body   = json_decode( wp_remote_retrieve_body( $data ) );
    $groups = wp_list_pluck( $body->result, 'groupname', 'id' );
 
    return $groups;
}
 
add_action( 'wp_ajax_adfoin_get_vtiger_all_fields', 'adfoin_get_vtiger_all_fields', 10, 0 );
 
/*
* Get Vtiger CRM All Fields
*/
function adfoin_get_vtiger_all_fields() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
 
    $final_data       = array();
    $selected_objects = isset( $_POST['selectedObjects'] ) ? adfoin_sanitize_text_or_array_field( $_POST['selectedObjects'] ) : array();
  
    if( in_array( 'contact', $selected_objects ) ) {
        $contact_fields = array( 
            array( 'key' => 'contact_salutation', 'value' => 'Salutation [Contact]', 'description' => '' ),
            array( 'key' => 'contact_firstName', 'value' => 'First Name [Contact]', 'description' => 'Required if you want to create a contact, otherwise leave empty' ),
            array( 'key' => 'contact_lastName', 'value' => 'Last Name [Contact]', 'description' => '' ),
            array( 'key' => 'contact_description', 'value' => 'Description [Contact]', 'description' => '' ),            
            array( 'key' => 'contact_stage', 'value' => 'Lifecycle Stage [Contact]', 'description' => 'Lead | Marketing Qualified Lead | Sales Qualified Lead | Customer | Competitor | Partner | Analyst | Others' ),
            array( 'key' => 'contact_status', 'value' => 'Status [Contact]', 'description' => 'Cold | Warm | Hot | Inactive' ),                     
            array( 'key' => 'contact_officePhone', 'value' => 'Office Phone [Contact]', 'description' => '' ),
            array( 'key' => 'contact_homePhone', 'value' => 'Home Phone [Contact]', 'description' => '' ),
            array( 'key' => 'contact_mobilePhone', 'value' => 'Mobile Phone [Contact]', 'description' => '' ),
            array( 'key' => 'contact_email', 'value' => 'Email [Contact]', 'description' => '' ),
            array( 'key' => 'contact_twitter', 'value' => 'Twitter [Contact]', 'description' => '' ),
            array( 'key' => 'contact_address', 'value' => 'Address [Contact]', 'description' => '' ),
            array( 'key' => 'contact_city', 'value' => 'City [Contact]', 'description' => '' ),
            array( 'key' => 'contact_state', 'value' => 'State [Contact]', 'description' => '' ),
            array( 'key' => 'contact_zip', 'value' => 'Zip [Contact]', 'description' => '' ),
            array( 'key' => 'contact_country', 'value' => 'Country [Contact]', 'description' => '' ),
        );
 
        $final_data = array_merge( $final_data, $contact_fields );
    }

    if( in_array( 'organization', $selected_objects ) ) {
      $org_fields = array(
          array( 'key' => 'org_name', 'value' => 'Name [Organization]', 'description' => 'Required if you want to create an organization, otherwise leave empty' ),
          array( 'key' => 'org_website', 'value' => 'Website [Organization]', 'description' => '' ),
          array( 'key' => 'org_description', 'value' => 'Description [Organization]', 'description' => '' ),          
          array( 'key' => 'org_email', 'value' => 'Email [Organization]', 'description' => '' ),
          array( 'key' => 'org_phone', 'value' => 'Phone [Organization]', 'description' => '' ),
          array( 'key' => 'org_type', 'value' => 'Type [Organization]', 'description' => 'Lead | Sales Qualified Lead | Customer | Competitor | Partner | Analyst | Vendor' ),
          array( 'key' => 'org_status', 'value' => 'Organization Status [Organization]', 'description' => 'Cold | Warm | Hot | Inactive' ),
          array( 'key' => 'org_addressType', 'value' => 'Address Type [Organization]', 'description' => 'Billing | Shipping' ),
          array( 'key' => 'org_address', 'value' => 'Address [Organization]', 'description' => '' ),
          array( 'key' => 'org_city', 'value' => 'City [Organization]', 'description' => '' ),
          array( 'key' => 'org_state', 'value' => 'State [Organization]', 'description' => '' ),
          array( 'key' => 'org_zip', 'value' => 'Zip [Organization]', 'description' => '' ),
          array( 'key' => 'org_country', 'value' => 'Country [Organization]', 'description' => 'Only ISO Alpha-2 country code accepted example US, FR, UK' ),
    );

      $final_data = array_merge( $final_data, $org_fields );
    }

    if( in_array( 'deal', $selected_objects ) ) {
     
      $deal_fields = array(
          array( 'key' => 'deal_name', 'value' => 'Name [Deal]', 'description' => 'Required if you want to create an deal, otherwise leave empty' ),
          array( 'key' => 'deal_description', 'value' => 'Description [Deal]', 'description' => '' ),
          array( 'key' => 'deal_amount', 'value' => 'Amount [Deal]', 'description' => '' ),
          array( 'key' => 'deal_expectedCloseOn', 'value' => 'Expected Close Date [Deal]', 'description' => '' ),
          array( 'key' => 'deal_pipeline', 'value' => 'Pipeline [Deal]', 'description' => '' ),
          array( 'key' => 'deal_stage', 'value' => 'Sales Stage [Deal]', 'description' => '' ),
          array( 'key' => 'deal_type', 'value' => 'Type [Deal]', 'description' => '' ),
          array( 'key' => 'deal_source', 'value' => 'Lead Source [Deal]', 'description' => '' ),
          array( 'key' => 'deal_probability', 'value' => 'Probability [Deal]', 'description' => '' ),
      );

      
      $final_data = array_merge( $final_data, $deal_fields );
  }
 
    wp_send_json_success( $final_data );
}

add_action( 'adfoin_vtiger_job_queue', 'adfoin_vtiger_job_queue', 10, 1 );

function adfoin_vtiger_job_queue( $data ) {
    adfoin_vtiger_send_data( $data['record'], $data['posted_data'] );
}
 
/*
* Handles sending data to Vtiger CRM API
*/
function adfoin_vtiger_send_data( $record, $posted_data ) {
 
    $record_data = json_decode( $record['data'], true );
 
    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }
 
    $data       = $record_data['field_data'];
    $task       = $record['task'];
    $owner      = $data['owner'];
    $org_id     = '';
    $contact_id = '';
    $deal_id    ='';
 
 
    if( $task == 'add_fields' ) {
 
        $org_data         = array();
        $contact_data     = array();
        $deal_data        = array();
 
        foreach( $data as $key => $value ) {
            if( substr( $key, 0, 4 ) == 'org_' && $value ) {
                $key = substr( $key, 4 );
 
                $org_data[$key] = $value;
            }
 
            if( substr( $key, 0, 8 ) == 'contact_' && $value ) {
                $key = substr( $key, 8 );
 
                $contact_data[$key] = $value;
            }

            if( substr( $key, 0, 5 ) == 'deal_' && $value ) {
              $key = substr( $key, 5 );

              $deal_data[$key] = $value;
          }
        }
 
        if( isset( $org_data['name'] ) && $org_data['name'] ) {
            $endpoint           = 'create';
            $method             = 'POST';
            $org_holder         = array();
            $org_holder['accountname'] = adfoin_get_parsed_values( $org_data['name'], $posted_data );
            $org_holder['website']     = adfoin_get_parsed_values( $org_data['website'], $posted_data );

            if( isset( $org_data['description'] ) && $org_data['description'] ) { $org_holder['description'] = adfoin_get_parsed_values( $org_data['description'], $posted_data ); }
            if( isset( $org_data['email'] ) && $org_data['email'] ) { $org_holder['email1'] = adfoin_get_parsed_values( $org_data['email'], $posted_data ); }
            if( isset( $org_data['phone'] ) && $org_data['phone'] ) { $org_holder['phone'] = adfoin_get_parsed_values( $org_data['phone'], $posted_data ); }
            if( isset( $org_data['type'] ) && $org_data['type'] ) { $org_holder['accounttype'] = adfoin_get_parsed_values( $org_data['type'], $posted_data ); }
            if( isset( $org_data['status'] ) && $org_data['status'] ) { $org_holder['accountstatus'] = adfoin_get_parsed_values( $org_data['status'], $posted_data ); }

            if($org_data['addressType'] == 'Billing'){
                if( isset( $org_data['address'] ) && $org_data['address'] ) { $org_holder['bill_street'] = adfoin_get_parsed_values( $org_data['address'], $posted_data ); }
                if( isset( $org_data['country'] ) && $org_data['country'] ) { $org_holder['bill_country'] = adfoin_get_parsed_values( $org_data['country'], $posted_data ); }
                if( isset( $org_data['city'] ) && $org_data['city'] ) { $org_holder['bill_city'] = adfoin_get_parsed_values( $org_data['city'], $posted_data ); }
                if( isset( $org_data['state'] ) && $org_data['state'] ) { $org_holder['bill_state'] = adfoin_get_parsed_values( $org_data['state'], $posted_data ); }
                if( isset( $org_data['zip'] ) && $org_data['zip'] ) { $org_holder['bill_code'] = adfoin_get_parsed_values( $org_data['zip'], $posted_data ); }
            }

            if($org_data['addressType'] == 'Shipping'){
                if( isset( $org_data['address'] ) && $org_data['address'] ) { $org_holder['ship_street'] = adfoin_get_parsed_values( $org_data['address'], $posted_data ); }
                if( isset( $org_data['country'] ) && $org_data['country'] ) { $org_holder['ship_country'] = adfoin_get_parsed_values( $org_data['country'], $posted_data ); }
                if( isset( $org_data['city'] ) && $org_data['city'] ) { $org_holder['ship_city'] = adfoin_get_parsed_values( $org_data['city'], $posted_data ); }
                if( isset( $org_data['state'] ) && $org_data['state'] ) { $org_holder['ship_state'] = adfoin_get_parsed_values( $org_data['state'], $posted_data ); }
                if( isset( $org_data['zip'] ) && $org_data['zip'] ) { $org_holder['ship_code'] = adfoin_get_parsed_values( $org_data['zip'], $posted_data ); }
            }

            if( $owner ) {
                $org_holder['assigned_user_id'] = $owner;
            }
            $org_holder   = array_filter( $org_holder );
 
            $org_args = array(
                'elementType' => 'Accounts',
                'element'     => json_encode( $org_holder )
            );
 
            $endpoint = add_query_arg( $org_args, $endpoint );
 
            // $org_id = adfoin_vtiger_party_exists( $org_holder['name'] );
 
            // if( $org_id ) {
            //     $endpoint = "parties/{$org_id}";
            //     $method   = 'PUT';
            // }
 
           
            $org_response = adfoin_vtiger_request( $endpoint, $method, array(), $record );
            $org_body     = json_decode( wp_remote_retrieve_body( $org_response ), true );
 
            if( isset( $org_body['result'], $org_body['result']['id'] ) ) {
                $org_id = $org_body['result']['id'];
            }
        }

        if( isset( $contact_data['lastName'] ) && $contact_data['lastName'] ) {
            $endpoint           = 'create';
            $method             = 'POST';
            $contact_holder         = array();
            $contact_holder['lastname'] = adfoin_get_parsed_values( $contact_data['lastName'], $posted_data );
            
            if( isset( $contact_data['salutation'] ) && $contact_data['salutation'] ) { $contact_holder['salutationtype'] = adfoin_get_parsed_values( $contact_data['salutation'], $posted_data ); }            
            if( isset( $contact_data['firstName'] ) && $contact_data['firstName'] ) { $contact_holder['firstname'] = adfoin_get_parsed_values( $contact_data['firstName'], $posted_data ); }            
            if( isset( $contact_data['description'] ) && $contact_data['description'] ) { $contact_holder['description'] = adfoin_get_parsed_values( $contact_data['description'], $posted_data ); }
            if( isset( $contact_data['email'] ) && $contact_data['email'] ) { $contact_holder['email'] = adfoin_get_parsed_values( $contact_data['email'], $posted_data ); }
            if( isset( $contact_data['stage'] ) && $contact_data['stage'] ) { $contact_holder['contacttype'] = adfoin_get_parsed_values( $contact_data['stage'], $posted_data ); }
            if( isset( $contact_data['status'] ) && $contact_data['status'] ) { $contact_holder['contactstatus'] = adfoin_get_parsed_values( $contact_data['status'], $posted_data ); }
            if( isset( $contact_data['officePhone'] ) && $contact_data['officePhone'] ) { $contact_holder['phone'] = adfoin_get_parsed_values( $contact_data['officePhone'], $posted_data ); }
            if( isset( $contact_data['homePhone'] ) && $contact_data['homePhone'] ) { $contact_holder['homephone'] = adfoin_get_parsed_values( $contact_data['homePhone'], $posted_data ); }
            if( isset( $contact_data['mobilePhone'] ) && $contact_data['mobilePhone'] ) { $contact_holder['mobile'] = adfoin_get_parsed_values( $contact_data['mobilePhone'], $posted_data ); }
            if( isset( $contact_data['address'] ) && $contact_data['address'] ) { $contact_holder['mailingstreet'] = adfoin_get_parsed_values( $contact_data['address'], $posted_data ); }
            if( isset( $contact_data['country'] ) && $contact_data['country'] ) { $contact_holder['mailingcountry'] = adfoin_get_parsed_values( $contact_data['country'], $posted_data ); }
            if( isset( $contact_data['city'] ) && $contact_data['city'] ) { $contact_holder['mailingcity'] = adfoin_get_parsed_values( $contact_data['city'], $posted_data ); }
            if( isset( $contact_data['state'] ) && $contact_data['state'] ) { $contact_holder['mailingstate'] = adfoin_get_parsed_values( $contact_data['state'], $posted_data ); }
            if( isset( $contact_data['zip'] ) && $contact_data['zip'] ) { $contact_holder['mailingzip'] = adfoin_get_parsed_values( $contact_data['zip'], $posted_data ); }
            if( isset( $contact_data['twitter'] ) && $contact_data['twitter'] ) { $contact_holder['primary_twitter'] = adfoin_get_parsed_values( $contact_data['twitter'], $posted_data ); }
            

            if ($org_id){
                $contact_holder['account_id'] = $org_id;
            }

            if( $owner ) {
                $contact_holder['assigned_user_id'] = $owner;
            }
            $contact_holder   = array_filter( $contact_holder );
 
            $contact_args = array(
                'elementType' => 'Contacts',
                'element'     => json_encode( $contact_holder )
            );
 
            $endpoint = add_query_arg( $contact_args, $endpoint );
 
            // $org_id = adfoin_vtiger_party_exists( $org_holder['name'] );
 
            // if( $org_id ) {
            //     $endpoint = "parties/{$org_id}";
            //     $method   = 'PUT';
            // }
 
           
            $contact_response = adfoin_vtiger_request( $endpoint, $method, array(), $record );
            $contact_body     = json_decode( wp_remote_retrieve_body( $contact_response ), true );
 
            if( isset( $contact_body['result'], $contact_body['result']['id'] ) ) {
                $contact_id = $contact_body['result']['id'];
            }
        }

        if( isset( $deal_data['name'] ) && $deal_data['name'] ) {
            $endpoint           = 'create';
            $method             = 'POST';
            $deal_holder         = array();
            $deal_holder['potentialname'] = adfoin_get_parsed_values( $deal_data['name'], $posted_data );
            
            if( isset( $deal_data['description'] ) && $deal_data['description'] ) { $deal_holder['description'] = adfoin_get_parsed_values( $deal_data['description'], $posted_data ); }
            if( isset( $deal_data['amount'] ) && $deal_data['amount'] ) { $deal_holder['amount'] = adfoin_get_parsed_values( $deal_data['amount'], $posted_data ); }
            if( isset( $deal_data['stage'] ) && $deal_data['stage'] ) { $deal_holder['sales_stage'] = adfoin_get_parsed_values( $deal_data['stage'], $posted_data ); }
            if( isset( $deal_data['source'] ) && $deal_data['source'] ) { $deal_holder['leadsource'] = adfoin_get_parsed_values( $deal_data['source'], $posted_data ); }
            if( isset( $deal_data['expectedCloseOn'] ) && $deal_data['expectedCloseOn'] ) { $deal_holder['closingdate'] = adfoin_get_parsed_values( $deal_data['expectedCloseOn'], $posted_data ); }
            if( isset( $deal_data['pipeline'] ) && $deal_data['pipeline'] ) { $deal_holder['pipeline'] = adfoin_get_parsed_values( $deal_data['pipeline'], $posted_data ); }
            if( isset( $deal_data['type'] ) && $deal_data['type'] ) { $deal_holder['opportunity_type'] = adfoin_get_parsed_values( $deal_data['type'], $posted_data ); }
            if( isset( $deal_data['probability'] ) && $deal_data['probability'] ) { $deal_holder['probability'] = adfoin_get_parsed_values( $deal_data['probability'], $posted_data ); }
            
            if ($org_id){
                $deal_holder['related_to'] = $org_id;
            }

            if ($contact_id){
                $deal_holder['contact_id'] = $contact_id;
            }

            if( $owner ) {
                $deal_holder['assigned_user_id'] = $owner;
            }
            $deal_holder   = array_filter( $deal_holder );
 
            $deal_args = array(
                'elementType' => 'Potentials',
                'element'     => json_encode( $deal_holder )
            );
 
            $endpoint = add_query_arg( $deal_args, $endpoint );
 
            // $org_id = adfoin_vtiger_party_exists( $org_holder['name'] );
 
            // if( $org_id ) {
            //     $endpoint = "parties/{$org_id}";
            //     $method   = 'PUT';
            // }
 
           
            $deal_response = adfoin_vtiger_request( $endpoint, $method, array(), $record );
            $deal_body     = json_decode( wp_remote_retrieve_body( $deal_response ), true );
 
            if( isset( $deal_body['result'], $deal_body['result']['id'] ) ) {
                $deal_id = $deal_body['result']['id'];
            }
        }
 
    }
 
    return;
}
 
/*
* Checks if Party exists
* @returns: Party ID if exists
*/
 
function adfoin_vtiger_party_exists( $name ) {
 
    $endpoint = 'parties/search';
 
    $query_args = array(
        'q' => $name
    );
 
    $endpoint      = add_query_arg( $query_args, $endpoint );
    $response      = adfoin_vtiger_request( $endpoint, 'GET' );
    $response_code = wp_remote_retrieve_response_code( $response );
    $party_id      = '';
   
    if( 200 == $response_code ) {
        $response_body = json_decode( wp_remote_retrieve_body( $response ), true );
 
        if( isset( $response_body['parties'] ) && is_array( $response_body['parties'] ) ) {
            if( count( $response_body['parties'] ) > 0 ) {
                $party_id = $response_body['parties'][0]['id'];
            }
        }
    }
 
    if( $party_id ) {
        return $party_id;
    } else{
        return false;
    }
}