<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_salesflare_actions',
    10,
    1
);
function adfoin_salesflare_actions( $actions )
{
    $actions['salesflare'] = array(
        'title' => __( 'Salesflare', 'advanced-form-integration' ),
        'tasks' => array(
        'add_data' => __( 'Add Account, Contact, Opportunity, Task', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_salesflare_settings_tab',
    10,
    1
);
function adfoin_salesflare_settings_tab( $providers )
{
    $providers['salesflare'] = __( 'Salesflare', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_salesflare_settings_view',
    10,
    1
);
function adfoin_salesflare_settings_view( $current_tab )
{
    if ( $current_tab != 'salesflare' ) {
        return;
    }
    $nonce = wp_create_nonce( 'adfoin_salesflare_settings' );
    $api_token = ( get_option( 'adfoin_salesflare_api_token' ) ? get_option( 'adfoin_salesflare_api_token' ) : '' );
    ?>

    <form name="salesflare_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
        method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_salesflare_api_token">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_salesflare_api_token"
                        value="<?php 
    echo  esc_attr( $api_token ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Key', 'advanced-form-integration' );
    ?>"
                        class="regular-text"/>
                    <p class="description" id="code-description"><?php 
    _e( 'Go to Settings > API keys > Click on the plus button and generate a new API key', 'advanced-form-integration' );
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
    'admin_post_adfoin_save_salesflare_api_token',
    'adfoin_save_salesflare_api_token',
    10,
    0
);
function adfoin_save_salesflare_api_token()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_salesflare_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_token = sanitize_text_field( $_POST["adfoin_salesflare_api_token"] );
    // Save tokens
    update_option( "adfoin_salesflare_api_token", $api_token );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=salesflare" );
}

add_action(
    'adfoin_action_fields',
    'adfoin_salesflare_action_fields',
    10,
    1
);
function adfoin_salesflare_action_fields()
{
    ?>
    <script type="text/template" id="salesflare-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_data'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Map Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_data'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Owner', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[owner]" v-model="fielddata.owner" required="required">
                        <option value=""> <?php 
    _e( 'Select Owner...', 'advanced-form-integration' );
    ?> </option>
                        <option v-for="(item, index) in fielddata.ownerList" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': ownerLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_data'">
            <td scope="row-title">
                <label for="tablecell">
                    <?php 
    esc_attr_e( 'Entities', 'advanced-form-integration' );
    ?>
                </label>
            </td>
            <td>
                <div class="object_selection" style="display: inline;">
                    <input type="checkbox" id="account__chosen" value="true" v-model="fielddata.account__chosen" name="fieldData[account__chosen]">
                    <label style="margin-right:10px;" for="account__chosen">Account</label>
                    <input type="checkbox" id="contact__chosen" value="true" v-model="fielddata.contact__chosen" name="fieldData[contact__chosen]">
                    <label style="margin-right:10px;" for="contact__chosen">Contact</label>
                    <input type="checkbox" id="opportunity__chosen" value="true" v-model="fielddata.opportunity__chosen" name="fieldData[opportunity__chosen]">
                    <label style="margin-right:10px;" for="opportunity__chosen">Opportunity</label>
                    <input type="checkbox" id="task__chosen" value="true" v-model="fielddata.task__chosen" name="fieldData[task__chosen]">
                    <label style="margin-right:10px;" for="task__chosen">Task</label>
                </div>
                
                <button class="button-secondary" @click.stop.prevent="getFields">Get Fields</button>
                <div class="spinner" v-bind:class="{'is-active': fieldsLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                
            </td>
        </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>

            <?php 
    
    if ( adfoin_fs()->is_not_paying() ) {
        ?>
                    <tr valign="top" v-if="action.task == 'add_data'">
                        <th scope="row">
                            <?php 
        esc_attr_e( 'Go Pro', 'advanced-form-integration' );
        ?>
                        </th>
                        <td scope="row">
                            <span><?php 
        printf( __( 'To unlock tags and custom fields, consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
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
 * Salesflare API Request
 */
function adfoin_salesflare_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $api_token = ( get_option( 'adfoin_salesflare_api_token' ) ? get_option( 'adfoin_salesflare_api_token' ) : '' );
    $base_url = 'https://api.salesflare.com/';
    $url = $base_url . $endpoint;
    $args = array(
        'method'  => $method,
        'headers' => array(
        'Content-Type'  => 'application/json',
        'Accept'        => 'application/json',
        'Authorization' => 'Bearer ' . $api_token,
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
    'wp_ajax_adfoin_get_salesflare_owner_list',
    'adfoin_get_salesflare_owner_list',
    10,
    0
);
/*
* Get salesflare Owner list
*/
function adfoin_get_salesflare_owner_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $data = adfoin_salesflare_request( 'users' );
    if ( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body = json_decode( wp_remote_retrieve_body( $data ), true );
    $users = wp_list_pluck( $body, 'name', 'id' );
    wp_send_json_success( $users );
}

//Get Opportunity stages
function adfoin_get_salesflare_opportunity_stages()
{
    $stages = array();
    $data = adfoin_salesflare_request( 'stages' );
    $body = json_decode( wp_remote_retrieve_body( $data ), true );
    if ( is_array( $body ) ) {
        foreach ( $body as $stage ) {
            $stages[] = $stage['pipeline']['name'] . '/' . $stage['name'] . ': ' . $stage['id'];
        }
    }
    return $stages;
}

add_action(
    'wp_ajax_adfoin_get_salesflare_all_fields',
    'adfoin_get_salesflare_all_fields',
    10,
    0
);
/*
* Get Salesflare All Fields
*/
function adfoin_get_salesflare_all_fields()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $final_data = array();
    $selected_objects = ( isset( $_POST['selectedObjects'] ) ? adfoin_sanitize_text_or_array_field( $_POST['selectedObjects'] ) : array() );
    
    if ( in_array( 'account', $selected_objects ) ) {
        $acc_fields = array(
            array(
            'key'         => 'acc_name',
            'value'       => 'Name [Account]',
            'description' => 'Required for creating an account, otherwise leave empty',
        ),
            array(
            'key'         => 'acc_website',
            'value'       => 'Website [Account]',
            'description' => '',
        ),
            array(
            'key'         => 'acc_description',
            'value'       => 'Description [Account]',
            'description' => '',
        ),
            array(
            'key'         => 'acc_size',
            'value'       => 'Size [Account]',
            'description' => '',
        ),
            array(
            'key'         => 'acc_email',
            'value'       => 'Email [Account]',
            'description' => '',
        ),
            array(
            'key'         => 'acc_phone',
            'value'       => 'Phone [Account]',
            'description' => '',
        ),
            array(
            'key'         => 'acc_social',
            'value'       => 'Social Profile URL [Account]',
            'description' => 'Use comma for multiple social profile URL.',
        ),
            array(
            'key'         => 'acc_street',
            'value'       => 'Street [Account]',
            'description' => '',
        ),
            array(
            'key'         => 'acc_zip',
            'value'       => 'Zip [Account]',
            'description' => '',
        ),
            array(
            'key'         => 'acc_city',
            'value'       => 'City [Account]',
            'description' => '',
        ),
            array(
            'key'         => 'acc_state',
            'value'       => 'State/Region [Account]',
            'description' => '',
        ),
            array(
            'key'         => 'acc_country',
            'value'       => 'Country [Account]',
            'description' => '',
        )
        );
        $final_data = array_merge( $final_data, $acc_fields );
    }
    
    
    if ( in_array( 'contact', $selected_objects ) ) {
        $contact_fields = array(
            array(
            'key'         => 'contact_title',
            'value'       => 'Prefix/Title [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_firstName',
            'value'       => 'First Name [Contact]',
            'description' => 'Required if you want to create a contact, otherwise leave empty',
        ),
            array(
            'key'         => 'contact_middleName',
            'value'       => 'Middle Name [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_lastName',
            'value'       => 'Last Name [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_suffix',
            'value'       => 'suffix [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_email',
            'value'       => 'Email [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_workPhone',
            'value'       => 'Work Phone [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_homePhone',
            'value'       => 'Home Phone [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_mobilePhone',
            'value'       => 'Mobile Phone [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_addressType',
            'value'       => 'Address Type [Contact]',
            'description' => 'Home | Postal | Office | Billing | Shipping',
        ),
            array(
            'key'         => 'contact_address',
            'value'       => 'Address [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_city',
            'value'       => 'City [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_state',
            'value'       => 'State [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_zip',
            'value'       => 'Zip [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_country',
            'value'       => 'Country [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_social',
            'value'       => 'Social Profile URL [Contact]',
            'description' => 'Use comma for multiple social profile URL.',
        ),
            array(
            'key'         => 'contact_role',
            'value'       => 'Role [Contact]',
            'description' => '',
        ),
            array(
            'key'         => 'contact_organisation',
            'value'       => 'Organisation [Contact]',
            'description' => '',
        )
        );
        $final_data = array_merge( $final_data, $contact_fields );
    }
    
    
    if ( in_array( 'opportunity', $selected_objects ) ) {
        $stages = adfoin_get_salesflare_opportunity_stages();
        $opportunity_fields = array(
            array(
            'key'         => 'opportunity_name',
            'value'       => 'Name [Opportunity]',
            'description' => '',
        ),
            array(
            'key'         => 'opportunity_stage',
            'value'       => 'Stage ID [Opportunity]',
            'description' => implode( ', ', $stages ),
        ),
            array(
            'key'         => 'opportunity_owner',
            'value'       => 'Owner [Opportunity]',
            'description' => '',
        ),
            array(
            'key'         => 'opportunity_value',
            'value'       => 'Value [Opportunity]',
            'description' => '',
        ),
            array(
            'key'         => 'opportunity_expectedCloseOn',
            'value'       => 'Expected Close Date [Opportunity]',
            'description' => '',
        )
        );
        $final_data = array_merge( $final_data, $opportunity_fields );
    }
    
    
    if ( in_array( 'task', $selected_objects ) ) {
        $task_fields = array( array(
            'key'         => 'task_description',
            'value'       => 'Description [Task]',
            'description' => 'Required if you want to create a tasks, otherwise leave empty',
        ), array(
            'key'         => 'task_reminderDate',
            'value'       => 'Reminder Date [Task]',
            'description' => '',
        ), array(
            'key'         => 'task_assignee',
            'value'       => 'Assignee [Task]',
            'description' => '',
        ) );
        $final_data = array_merge( $final_data, $task_fields );
    }
    
    wp_send_json_success( $final_data );
}

add_action(
    'adfoin_salesflare_job_queue',
    'adfoin_salesflare_job_queue',
    10,
    1
);
function adfoin_salesflare_job_queue( $data )
{
    adfoin_salesflare_send_data( $data['record'], $data['posted_data'] );
}

/*
* Handles sending data to Salesflare API
*/
function adfoin_salesflare_send_data( $record, $posted_data )
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
    $owner = $data['owner'];
    $acc_id = '';
    $contact_id = '';
    $opportunity_id = '';
    $case_id = '';
    
    if ( $task == "add_data" ) {
        $acc_data = array();
        $contact_data = array();
        $opportunity_data = array();
        $task_data = array();
        foreach ( $data as $key => $value ) {
            
            if ( substr( $key, 0, 4 ) == 'acc_' && $value ) {
                $key = substr( $key, 4 );
                $acc_data[$key] = $value;
            }
            
            
            if ( substr( $key, 0, 8 ) == 'contact_' && $value ) {
                $key = substr( $key, 8 );
                $contact_data[$key] = $value;
            }
            
            
            if ( substr( $key, 0, 12 ) == 'opportunity_' && $value ) {
                $key = substr( $key, 12 );
                $opportunity_data[$key] = $value;
            }
            
            
            if ( substr( $key, 0, 5 ) == 'task_' && $value ) {
                $key = substr( $key, 5 );
                $task_data[$key] = $value;
            }
        
        }
        
        if ( isset( $acc_data['name'] ) && $acc_data['name'] ) {
            $endpoint = 'accounts';
            $method = 'POST';
            $acc_holder = array();
            $acc_holder['name'] = adfoin_get_parsed_values( $acc_data['name'], $posted_data );
            if ( isset( $acc_data['website'] ) && $acc_data['website'] ) {
                $acc_holder['website'] = adfoin_get_parsed_values( $acc_data['website'], $posted_data );
            }
            if ( isset( $acc_data['description'] ) && $acc_data['description'] ) {
                $acc_holder['description'] = adfoin_get_parsed_values( $acc_data['description'], $posted_data );
            }
            if ( isset( $acc_data['email'] ) && $acc_data['email'] ) {
                $acc_holder['email'] = adfoin_get_parsed_values( $acc_data['email'], $posted_data );
            }
            if ( isset( $acc_data['phone'] ) && $acc_data['phone'] ) {
                $acc_holder['phone_number'] = adfoin_get_parsed_values( $acc_data['phone'], $posted_data );
            }
            if ( isset( $acc_data['size'] ) && $acc_data['size'] ) {
                $acc_holder['size'] = adfoin_get_parsed_values( $acc_data['size'], $posted_data );
            }
            if ( isset( $acc_data['social'] ) && $acc_data['social'] ) {
                $acc_holder['social_profiles'] = array( adfoin_get_parsed_values( $acc_data['social'], $posted_data ) );
            }
            
            if ( $acc_data['social'] ) {
                $acc_holder['social_profiles'] = array();
                $acc_social = explode( ',', $acc_data['social'] );
                foreach ( $acc_social as $social ) {
                    array_push( $acc_holder['social_profiles'], $social );
                }
            }
            
            if ( $owner ) {
                $acc_holder['owner'] = (int) $owner;
            }
            if ( isset( $acc_data['street'] ) || isset( $acc_data['zip'] ) || isset( $acc_data['city'] ) || isset( $acc_data['state'] ) || isset( $acc_data['country'] ) ) {
                $acc_holder['addresses'] = array( array() );
            }
            if ( isset( $acc_data['street'] ) && $acc_data['street'] ) {
                $acc_holder['addresses'][0]['street'] = adfoin_get_parsed_values( $acc_data['street'], $posted_data );
            }
            if ( isset( $acc_data['zip'] ) && $acc_data['zip'] ) {
                $acc_holder['addresses'][0]['zip'] = adfoin_get_parsed_values( $acc_data['zip'], $posted_data );
            }
            if ( isset( $acc_data['city'] ) && $acc_data['city'] ) {
                $acc_holder['addresses'][0]['city'] = adfoin_get_parsed_values( $acc_data['city'], $posted_data );
            }
            if ( isset( $acc_data['state'] ) && $acc_data['state'] ) {
                $acc_holder['addresses'][0]['state'] = adfoin_get_parsed_values( $acc_data['state'], $posted_data );
            }
            if ( isset( $acc_data['country'] ) && $acc_data['country'] ) {
                $acc_holder['addresses'][0]['country'] = adfoin_get_parsed_values( $acc_data['country'], $posted_data );
            }
            $acc_id = adfoin_salesflare_item_exists( 'accounts', 'name', $acc_holder['name'] );
            
            if ( $acc_id ) {
                $endpoint = "accounts/{$acc_id}";
                $method = 'PUT';
            }
            
            $acc_holder = array_filter( $acc_holder );
            $acc_response = adfoin_salesflare_request(
                $endpoint,
                $method,
                $acc_holder,
                $record
            );
            $acc_body = json_decode( wp_remote_retrieve_body( $acc_response ), true );
            if ( isset( $acc_body['id'] ) ) {
                $acc_id = $acc_body['id'];
            }
        }
        
        
        if ( isset( $contact_data['email'] ) && $contact_data['email'] ) {
            $endpoint = 'contacts';
            $method = 'POST';
            $contact_holder = array();
            if ( isset( $contact_data['email'] ) && $contact_data['email'] ) {
                $contact_holder['email'] = adfoin_get_parsed_values( $contact_data['email'], $posted_data );
            }
            if ( isset( $contact_data['title'] ) && $contact_data['title'] ) {
                $contact_holder['prefix'] = adfoin_get_parsed_values( $contact_data['title'], $posted_data );
            }
            if ( isset( $contact_data['firstName'] ) && $contact_data['firstName'] ) {
                $contact_holder['firstname'] = adfoin_get_parsed_values( $contact_data['firstName'], $posted_data );
            }
            if ( isset( $contact_data['middleName'] ) && $contact_data['middleName'] ) {
                $contact_holder['middle'] = adfoin_get_parsed_values( $contact_data['middleName'], $posted_data );
            }
            if ( isset( $contact_data['lastName'] ) && $contact_data['lastName'] ) {
                $contact_holder['lastname'] = adfoin_get_parsed_values( $contact_data['lastName'], $posted_data );
            }
            if ( isset( $contact_data['suffix'] ) && $contact_data['suffix'] ) {
                $contact_holder['suffix'] = adfoin_get_parsed_values( $contact_data['suffix'], $posted_data );
            }
            if ( isset( $contact_data['birthdate'] ) && $contact_data['birthdate'] ) {
                $contact_holder['birth_date'] = adfoin_get_parsed_values( $contact_data['birthdate'], $posted_data );
            }
            if ( isset( $contact_data['workPhone'] ) && $contact_data['workPhone'] ) {
                $contact_holder['phone_number'] = adfoin_get_parsed_values( $contact_data['workPhone'], $posted_data );
            }
            if ( isset( $contact_data['homePhone'] ) && $contact_data['homePhone'] ) {
                $contact_holder['home_phone_number'] = adfoin_get_parsed_values( $contact_data['homePhone'], $posted_data );
            }
            if ( isset( $contact_data['mobilePhone'] ) && $contact_data['mobilePhone'] ) {
                $contact_holder['mobile_phone_number'] = adfoin_get_parsed_values( $contact_data['mobilePhone'], $posted_data );
            }
            
            if ( $contact_data['social'] ) {
                $contact_holder['social_profiles'] = array();
                $contact_social = explode( ',', $contact_data['social'] );
                foreach ( $contact_social as $social ) {
                    array_push( $contact_holder['social_profiles'], $social );
                }
            }
            
            if ( $owner ) {
                $contact_holder['owner'] = (int) $owner;
            }
            if ( isset( $contact_data['street'] ) || isset( $contact_data['zip'] ) || isset( $contact_data['city'] ) || isset( $contact_data['state'] ) || isset( $contact_data['country'] ) ) {
                $contact_holder['address'] = array();
            }
            if ( isset( $contact_data['addressType'] ) && $contact_data['addressType'] ) {
                $contact_holder['address']['type'] = adfoin_get_parsed_values( $contact_data['addressType'], $posted_data );
            }
            if ( isset( $contact_data['street'] ) && $contact_data['street'] ) {
                $contact_holder['address']['street'] = adfoin_get_parsed_values( $contact_data['street'], $posted_data );
            }
            if ( isset( $contact_data['zip'] ) && $contact_data['zip'] ) {
                $contact_holder['address']['zip'] = adfoin_get_parsed_values( $contact_data['zip'], $posted_data );
            }
            if ( isset( $contact_data['city'] ) && $contact_data['city'] ) {
                $contact_holder['address']['city'] = adfoin_get_parsed_values( $contact_data['city'], $posted_data );
            }
            if ( isset( $contact_data['state'] ) && $contact_data['state'] ) {
                $contact_holder['address']['state'] = adfoin_get_parsed_values( $contact_data['state'], $posted_data );
            }
            if ( isset( $contact_data['country'] ) && $contact_data['country'] ) {
                $contact_holder['address']['country'] = adfoin_get_parsed_values( $contact_data['country'], $posted_data );
            }
            if ( isset( $contact_data['role'] ) && isset( $contact_data['organisation'] ) ) {
                $contact_holder['position'] = array();
            }
            if ( isset( $contact_data['role'] ) && $contact_data['role'] ) {
                $contact_holder['position']['role'] = adfoin_get_parsed_values( $contact_data['role'], $posted_data );
            }
            if ( isset( $contact_data['organisation'] ) && $contact_data['organisation'] ) {
                $contact_holder['position']['organisation'] = adfoin_get_parsed_values( $contact_data['organisation'], $posted_data );
            }
            if ( $acc_id ) {
                $contact_holder['account'] = (int) $acc_id;
            }
            $contact_id = adfoin_salesflare_item_exists( 'contacts', 'email', $contact_holder['email'] );
            
            if ( $contact_id ) {
                $endpoint = "contacts/{$contact_id}";
                $method = 'PUT';
            }
            
            $contact_holder = array_filter( $contact_holder );
            $contact_response = adfoin_salesflare_request(
                $endpoint,
                $method,
                $contact_holder,
                $record
            );
            $contact_body = json_decode( wp_remote_retrieve_body( $contact_response ), true );
            if ( isset( $contact_body['id'] ) ) {
                $contact_id = $contact_body['id'];
            }
        }
        
        
        if ( isset( $opportunity_data['name'] ) && $opportunity_data['name'] ) {
            $endpoint = 'opportunities';
            $method = 'POST';
            $opportunity_holder = array();
            $opportunity_holder['name'] = adfoin_get_parsed_values( $opportunity_data['name'], $posted_data );
            if ( $acc_id ) {
                $opportunity_holder['account'] = (int) $acc_id;
            }
            
            if ( $owner ) {
                $opportunity_holder['owner'] = (int) $owner;
                $opportunity_holder['assignee'] = (int) $owner;
            }
            
            if ( isset( $opportunity_data['stage'] ) && $opportunity_data['stage'] ) {
                $opportunity_holder['stage'] = (int) adfoin_get_parsed_values( $opportunity_data['stage'], $posted_data );
            }
            if ( isset( $opportunity_data['value'] ) && $opportunity_data['value'] ) {
                $opportunity_holder['value'] = (int) adfoin_get_parsed_values( $opportunity_data['value'], $posted_data );
            }
            if ( isset( $opportunity_data['expectedCloseOn'] ) && $opportunity_data['expectedCloseOn'] ) {
                $opportunity_holder['close_date'] = adfoin_get_parsed_values( $opportunity_data['expectedCloseOn'], $posted_data );
            }
            if ( $acc_id ) {
                $opportunity_holder['account'] = (int) $acc_id;
            }
            $opportunity_holder = array_filter( $opportunity_holder );
            $opportunity_response = adfoin_salesflare_request(
                $endpoint,
                $method,
                $opportunity_holder,
                $record
            );
            $opportunity_body = json_decode( wp_remote_retrieve_body( $opportunity_response ), true );
            if ( isset( $opportunity_body['id'] ) ) {
                $opportunity_id = $opportunity_body['id'];
            }
        }
        
        
        if ( isset( $task_data['description'] ) && $task_data['description'] ) {
            $endpoint = 'tasks';
            $method = 'POST';
            $task_holder = array();
            $task_holder['description'] = adfoin_get_parsed_values( $task_data['description'], $posted_data );
            if ( $acc_id ) {
                $task_holder['account'] = (int) $acc_id;
            }
            if ( isset( $task_data['reminderDate'] ) && $task_data['reminderDate'] ) {
                $task_holder['reminder_date'] = adfoin_get_parsed_values( $task_data['reminderDate'], $posted_data );
            }
            if ( isset( $task_data['assignee'] ) && $task_data['assignee'] ) {
                $task_holder['assignees'] = array( (int) adfoin_get_parsed_values( $task_data['assignee'], $posted_data ) );
            }
            $task_holder = array_filter( $task_holder );
            $task_response = adfoin_salesflare_request(
                $endpoint,
                $method,
                $task_holder,
                $record
            );
            $task_body = json_decode( wp_remote_retrieve_body( $task_response ), true );
        }
    
    }
    
    return;
}

/*
* Checks if Item exists
* @returns: Item ID if exists
*/
function adfoin_salesflare_item_exists( $endpoint, $key, $value )
{
    $query_args = array(
        $key => $value,
    );
    $endpoint = add_query_arg( $query_args, $endpoint );
    $response = adfoin_salesflare_request( $endpoint, 'GET' );
    $response_code = wp_remote_retrieve_response_code( $response );
    $item_id = '';
    
    if ( 200 == $response_code ) {
        $response_body = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( is_array( $response_body ) && $response_body ) {
            if ( isset( $response_body[0], $response_body[0]['id'] ) ) {
                $item_id = $response_body[0]['id'];
            }
        }
    }
    
    
    if ( $item_id ) {
        return $item_id;
    } else {
        return false;
    }

}
