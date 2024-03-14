<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_wealthbox_actions',
    10,
    1
);
function adfoin_wealthbox_actions( $actions )
{
    $actions['wealthbox'] = array(
        'title' => __( 'Wealthbox', 'advanced-form-integration' ),
        'tasks' => array(
        'add_contact' => __( 'Add Contact', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_wealthbox_settings_tab',
    10,
    1
);
function adfoin_wealthbox_settings_tab( $providers )
{
    $providers['wealthbox'] = __( 'Wealthbox', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_wealthbox_settings_view',
    10,
    1
);
function adfoin_wealthbox_settings_view( $current_tab )
{
    if ( $current_tab != 'wealthbox' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_wealthbox_settings" );
    $api_token = ( get_option( 'adfoin_wealthbox_api_token' ) ? get_option( 'adfoin_wealthbox_api_token' ) : "" );
    ?>

    <form name="wealthbox_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_wealthbox_api_token">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">

            <tr valign="top">
                <th scope="row"><?php 
    _e( 'API Access Token', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_wealthbox_api_token" value="<?php 
    echo  esc_attr( $api_token ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Key', 'advanced-form-integration' );
    ?>" class="regular-text"/>
                    <p>
                    Go to Settings > API Access > Click Create Access Token.
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
    'admin_post_adfoin_save_wealthbox_api_token',
    'adfoin_save_wealthbox_api_token',
    10,
    0
);
function adfoin_save_wealthbox_api_token()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_wealthbox_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_token = sanitize_text_field( $_POST["adfoin_wealthbox_api_token"] );
    // Save tokens
    update_option( "adfoin_wealthbox_api_token", $api_token );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=wealthbox" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_wealthbox_js_fields',
    10,
    1
);
function adfoin_wealthbox_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_wealthbox_action_fields' );
function adfoin_wealthbox_action_fields()
{
    ?>
    <script type="text/template" id="wealthbox-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_contact'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Map Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_contact'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Assigned To', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[owner]" v-model="fielddata.owner">
                        <option value=""> <?php 
    _e( 'Select User...', 'advanced-form-integration' );
    ?> </option>
                        <option v-for="(item, index) in fielddata.ownerList" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': ownerLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>
            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>

            <?php 
    
    if ( adfoin_fs()->is_not_paying() ) {
        ?>
                    <tr valign="top" v-if="action.task == 'add_contact'">
                        <th scope="row">
                            <?php 
        esc_attr_e( 'Go Pro', 'advanced-form-integration' );
        ?>
                        </th>
                        <td scope="row">
                            <span><?php 
        printf( __( 'To unlock tags & custom fields consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
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
    'wp_ajax_adfoin_get_wealthbox_owner_list',
    'adfoin_get_wealthbox_owner_list',
    10,
    0
);
/*
 * Get Wealthbox Owner list
 */
function adfoin_get_wealthbox_owner_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $data = adfoin_wealthbox_request( 'users' );
    if ( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body = json_decode( wp_remote_retrieve_body( $data ) );
    $users = wp_list_pluck( $body->users, 'name', 'id' );
    wp_send_json_success( $users );
}

/*
 * Wealthbox API Request
 */
function adfoin_wealthbox_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $api_token = ( get_option( 'adfoin_wealthbox_api_token' ) ? get_option( 'adfoin_wealthbox_api_token' ) : '' );
    if ( !$api_token ) {
        return;
    }
    $base_url = 'https://api.crmworkspace.com/v1/';
    $url = $base_url . $endpoint;
    $args = array(
        'method'  => $method,
        'headers' => array(
        'Content-Type' => 'application/json',
        'ACCESS_TOKEN' => $api_token,
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
    'adfoin_wealthbox_job_queue',
    'adfoin_wealthbox_job_queue',
    10,
    1
);
function adfoin_wealthbox_job_queue( $data )
{
    adfoin_wealthbox_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to wealthbox API
 */
function adfoin_wealthbox_send_data( $record, $posted_data )
{
    $record_data = json_decode( $record["data"], true );
    if ( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if ( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if ( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }
    $data = $record_data['field_data'];
    $task = $record['task'];
    $owner = $data["owner"];
    
    if ( $task == 'add_contact' ) {
        $prefix = ( empty($data['prefix']) ? '' : adfoin_get_parsed_values( $data['prefix'], $posted_data ) );
        $firstName = ( empty($data['firstName']) ? '' : adfoin_get_parsed_values( $data['firstName'], $posted_data ) );
        $middleName = ( empty($data['middleName']) ? '' : adfoin_get_parsed_values( $data['middleName'], $posted_data ) );
        $lastName = ( empty($data['lastName']) ? '' : adfoin_get_parsed_values( $data['lastName'], $posted_data ) );
        $suffix = ( empty($data['suffix']) ? '' : adfoin_get_parsed_values( $data['suffix'], $posted_data ) );
        $nickname = ( empty($data['nickname']) ? '' : adfoin_get_parsed_values( $data['nickname'], $posted_data ) );
        $twitterName = ( empty($data['twitterName']) ? '' : adfoin_get_parsed_values( $data['twitterName'], $posted_data ) );
        $linkedinUrl = ( empty($data['linkedinUrl']) ? '' : adfoin_get_parsed_values( $data['linkedinUrl'], $posted_data ) );
        $contactSource = ( empty($data['contactSource']) ? '' : adfoin_get_parsed_values( $data['contactSource'], $posted_data ) );
        $contactType = ( empty($data['contactType']) ? '' : adfoin_get_parsed_values( $data['contactType'], $posted_data ) );
        $status = ( empty($data['status']) ? '' : adfoin_get_parsed_values( $data['status'], $posted_data ) );
        $maritalStatus = ( empty($data['maritalStatus']) ? '' : adfoin_get_parsed_values( $data['maritalStatus'], $posted_data ) );
        $jobTitle = ( empty($data['jobTitle']) ? '' : adfoin_get_parsed_values( $data['jobTitle'], $posted_data ) );
        $companyName = ( empty($data['companyName']) ? '' : adfoin_get_parsed_values( $data['companyName'], $posted_data ) );
        $backgroundInfo = ( empty($data['backgroundInfo']) ? '' : adfoin_get_parsed_values( $data['backgroundInfo'], $posted_data ) );
        $gender = ( empty($data['gender']) ? '' : adfoin_get_parsed_values( $data['gender'], $posted_data ) );
        $householdTitle = ( empty($data['householdTitle']) ? '' : adfoin_get_parsed_values( $data['householdTitle'], $posted_data ) );
        $householdName = ( empty($data['householdName']) ? '' : adfoin_get_parsed_values( $data['householdName'], $posted_data ) );
        $personalemail = ( empty($data['personalEmail']) ? '' : adfoin_get_parsed_values( $data['personalEmail'], $posted_data ) );
        $workemail = ( empty($data['workEmail']) ? '' : adfoin_get_parsed_values( $data['workEmail'], $posted_data ) );
        $emailType = ( empty($data['emailType']) ? '' : adfoin_get_parsed_values( $data['emailType'], $posted_data ) );
        $primaryemail = ( empty($data['primaryemail']) ? '' : adfoin_get_parsed_values( $data['primaryemail'], $posted_data ) );
        $mobile = ( empty($data['mobile']) ? '' : adfoin_get_parsed_values( $data['mobile'], $posted_data ) );
        $workPhone = ( empty($data['workPhone']) ? '' : adfoin_get_parsed_values( $data['workPhone'], $posted_data ) );
        $homePhone = ( empty($data['homePhone']) ? '' : adfoin_get_parsed_values( $data['homePhone'], $posted_data ) );
        $phoneType = ( empty($data['phoneType']) ? '' : adfoin_get_parsed_values( $data['phoneType'], $posted_data ) );
        $primaryPhoneNo = ( empty($data['primaryPhoneNo']) ? '' : adfoin_get_parsed_values( $data['primaryPhoneNo'], $posted_data ) );
        $tags = ( empty($data['tags']) ? '' : adfoin_get_parsed_values( $data['tags'], $posted_data ) );
        $birthDate = ( empty($data['birthDate']) ? '' : adfoin_get_parsed_values( $data['birthDate'], $posted_data ) );
        $addressLine1 = ( empty($data['addressLine1']) ? '' : adfoin_get_parsed_values( $data['addressLine1'], $posted_data ) );
        $addressLine2 = ( empty($data['addressLine2']) ? '' : adfoin_get_parsed_values( $data['addressLine2'], $posted_data ) );
        $city = ( empty($data['city']) ? '' : adfoin_get_parsed_values( $data['city'], $posted_data ) );
        $state = ( empty($data['state']) ? '' : adfoin_get_parsed_values( $data['state'], $posted_data ) );
        $country = ( empty($data['country']) ? '' : adfoin_get_parsed_values( $data['country'], $posted_data ) );
        $zipCode = ( empty($data['zipCode']) ? '' : adfoin_get_parsed_values( $data['zipCode'], $posted_data ) );
        $kind = ( empty($data['kind']) ? '' : adfoin_get_parsed_values( $data['kind'], $posted_data ) );
        $webAddress = ( empty($data['webAddress']) ? '' : adfoin_get_parsed_values( $data['webAddress'], $posted_data ) );
        $webType = ( empty($data['webType']) ? '' : adfoin_get_parsed_values( $data['webType'], $posted_data ) );
        $taskName = ( empty($data['taskName']) ? '' : adfoin_get_parsed_values( $data['taskName'], $posted_data ) );
        $dueDate = ( empty($data['dueDate']) ? '' : adfoin_get_parsed_values( $data['dueDate'], $posted_data ) );
        $category = ( empty($data['category']) ? '' : adfoin_get_parsed_values( $data['category'], $posted_data ) );
        $priority = ( empty($data['priority']) ? '' : adfoin_get_parsed_values( $data['priority'], $posted_data ) );
        $description = ( empty($data['description']) ? '' : adfoin_get_parsed_values( $data['description'], $posted_data ) );
        $linkedTo = ( empty($data['linkedTo']) ? '' : adfoin_get_parsed_values( $data['linkedTo'], $posted_data ) );
        $assignedTo = ( empty($data['assignedTo']) ? '' : adfoin_get_parsed_values( $data['assignedTo'], $posted_data ) );
        $repeats = ( empty($data['repeats']) ? '' : adfoin_get_parsed_values( $data['repeats'], $posted_data ) );
        $request_data = array(
            "prefix"                 => $prefix,
            "first_name"             => $firstName,
            "middle_name"            => $middleName,
            "last_name"              => $lastName,
            "suffix"                 => $suffix,
            "nickname"               => $nickname,
            "twitter_name"           => $twitterName,
            "linkedin_url"           => $linkedinUrl,
            "contact_source"         => $contactSource,
            "contact_type"           => $contactType,
            "status"                 => $status,
            "marital_status"         => $maritalStatus,
            "job_title"              => $jobTitle,
            "company_name"           => $companyName,
            "background_information" => $backgroundInfo,
            "birth_date"             => $birthDate,
            "household"              => array(
            "name"  => $householdName,
            "title" => $householdTitle,
        ),
            "gender"                 => $gender,
        );
        if ( $addressLine1 || $addressLine2 || $city || $state || $zipCode || $country ) {
            $request_data['street_addresses'] = array( array(
                "street_line_1" => $addressLine1,
                "street_line_2" => $addressLine2,
                "city"          => $city,
                "state"         => $state,
                "zip_code"      => $zipCode,
                "country"       => $country,
                "kind"          => $kind,
            ) );
        }
        
        if ( $personalemail || $workemail ) {
            $request_data['email_addresses'] = array();
            if ( $personalemail ) {
                $request_data["email_addresses"][] = array(
                    "address" => $personalemail,
                    "kind"    => 'personal',
                );
            }
            if ( $workemail ) {
                $request_data["email_addresses"][] = array(
                    "address" => $workemail,
                    "kind"    => 'work',
                );
            }
        }
        
        
        if ( $mobile || $workPhone || $homePhone ) {
            $request_data['phone_numbers'] = array();
            if ( $mobile ) {
                $request_data['phone_numbers'][] = array(
                    'address' => $mobile,
                    'kind'    => 'Mobile',
                );
            }
            if ( $homePhone ) {
                $request_data['phone_numbers'][] = array(
                    'address' => $homePhone,
                    'kind'    => 'Home',
                );
            }
            if ( $workPhone ) {
                $request_data['phone_numbers'][] = array(
                    'address' => $workPhone,
                    'kind'    => 'Work',
                );
            }
        }
        
        // if ($phoneNo) {
        //     $request_data["phone_numbers"][] = array(
        //         array(
        //             "address"   => $phoneNo,
        //             "principal" => $primaryPhoneNo,
        //             "extension" => "",
        //             "kind"      => $phoneType,
        //         ),
        //     );
        // }
        if ( $webAddress ) {
            $request_data["websites"] = array( array(
                "address" => $webAddress,
                "kind"    => $webType,
            ) );
        }
        if ( $owner ) {
            $request_data['assigned_to'] = $owner;
        }
        $request_data = array_filter( $request_data );
        $return = adfoin_wealthbox_request(
            'contacts',
            'POST',
            $request_data,
            $record
        );
    }
    
    return;
}
