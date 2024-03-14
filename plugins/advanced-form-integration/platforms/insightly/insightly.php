<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_insightly_actions',
    10,
    1
);
function adfoin_insightly_actions( $actions )
{
    $actions['insightly'] = array(
        'title' => __( 'Insightly', 'advanced-form-integration' ),
        'tasks' => array(
        'add_contact' => __( 'Create New Organisation, Contact, Opportunity', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_insightly_settings_tab',
    10,
    1
);
function adfoin_insightly_settings_tab( $providers )
{
    $providers['insightly'] = __( 'Insightly', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_insightly_settings_view',
    10,
    1
);
function adfoin_insightly_settings_view( $current_tab )
{
    if ( $current_tab != 'insightly' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_insightly_settings" );
    $api_key = ( get_option( 'adfoin_insightly_api_key' ) ? get_option( 'adfoin_insightly_api_key' ) : "" );
    $api_url = ( get_option( 'adfoin_insightly_api_url' ) ? get_option( 'adfoin_insightly_api_url' ) : "" );
    ?>

    <form name="insightly_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_insightly_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_insightly_api_key"
                           value="<?php 
    echo  esc_attr( $api_key ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Token', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                           <p class="description" id="code-description"><?php 
    _e( 'Go to User Settings to get the API Key and URL', 'advanced-form-integration' );
    ?></a></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API URL', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_insightly_api_url"
                           value="<?php 
    echo  esc_attr( $api_url ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API URL', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
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
    'admin_post_adfoin_save_insightly_api_key',
    'adfoin_save_insightly_api_key',
    10,
    0
);
function adfoin_save_insightly_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_insightly_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = sanitize_text_field( $_POST["adfoin_insightly_api_key"] );
    $api_url = sanitize_text_field( $_POST["adfoin_insightly_api_url"] );
    // Save tokens
    update_option( "adfoin_insightly_api_key", $api_key );
    update_option( "adfoin_insightly_api_url", $api_url );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=insightly" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_insightly_js_fields',
    10,
    1
);
function adfoin_insightly_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_insightly_action_fields' );
function adfoin_insightly_action_fields()
{
    ?>
    <script type="text/template" id="insightly-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_contact'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Contact Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_contact'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Owner', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[owner]" v-model="fielddata.owner">
                        <option value=""> <?php 
    _e( 'Select Owner...', 'advanced-form-integration' );
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
        printf( __( 'To unlock custom fields and tags consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
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

function adfoin_insightly_get_keys()
{
    $key = ( get_option( 'adfoin_insightly_api_key' ) ? get_option( 'adfoin_insightly_api_key' ) : '' );
    $url = ( get_option( 'adfoin_insightly_api_url' ) ? get_option( 'adfoin_insightly_api_url' ) : '' );
    return array(
        'key' => $key,
        'url' => $url,
    );
}

add_action(
    'wp_ajax_adfoin_get_insightly_owner_list',
    'adfoin_get_insightly_owner_list',
    10,
    0
);
/*
 * Get Insightly Owner list
 */
function adfoin_get_insightly_owner_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_keys = adfoin_insightly_get_keys();
    if ( !$api_keys['key'] || !$api_keys['url'] ) {
        return;
    }
    $headers = array(
        'Authorization' => 'Basic ' . base64_encode( $api_keys['key'] . ':' . '' ),
        'Content-Type'  => 'application/json',
        'Accept'        => 'application/json',
    );
    $url = $api_keys['url'] . '/v3.0/Users';
    $args = array(
        "headers" => $headers,
    );
    $data = wp_remote_get( $url, $args );
    if ( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body = json_decode( wp_remote_retrieve_body( $data ) );
    $users = wp_list_pluck( $body, 'FIRST_NAME', 'USER_ID' );
    wp_send_json_success( $users );
}

function adfoin_insightly_get_pipelines()
{
    $api_keys = adfoin_insightly_get_keys();
    if ( !$api_keys['key'] || !$api_keys['url'] ) {
        return;
    }
    $headers = array(
        'Authorization' => 'Basic ' . base64_encode( $api_keys['key'] . ':' . '' ),
        'Content-Type'  => 'application/json',
        'Accept'        => 'application/json',
    );
    $pipeline_url = $api_keys['url'] . '/v3.0/Pipelines';
    $stage_url = $api_keys['url'] . '/v3.0/PipelineStages';
    $args = array(
        "headers" => $headers,
    );
    $pipeline_data = wp_remote_get( $pipeline_url, $args );
    $stage_data = wp_remote_get( $stage_url, $args );
    if ( is_wp_error( $pipeline_data ) || is_wp_error( $stage_data ) ) {
        return;
    }
    $pipeline_body = json_decode( wp_remote_retrieve_body( $pipeline_data ) );
    $pipelines = wp_list_pluck( $pipeline_body, 'PIPELINE_NAME', 'PIPELINE_ID' );
    $stage_body = json_decode( wp_remote_retrieve_body( $stage_data ) );
    $pipeline_string = array();
    foreach ( $stage_body as $stage ) {
        $pipeline_string[] = $pipelines[$stage->PIPELINE_ID] . '/' . $stage->STAGE_NAME . ': ' . $stage->PIPELINE_ID . '_' . $stage->STAGE_ID;
    }
    return implode( ', ', $pipeline_string );
}

add_action(
    'wp_ajax_adfoin_get_insightly_all_fields',
    'adfoin_get_insightly_all_fields',
    10,
    0
);
/*
 * Get Insightly data
 */
function adfoin_get_insightly_all_fields()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_keys = adfoin_insightly_get_keys();
    if ( !$api_keys['key'] || !$api_keys['url'] ) {
        return;
    }
    // $pipelines = adfoin_insightly_get_pipelines();
    $com_fields = array(
        array(
        'key'         => 'com_name',
        'value'       => 'Name [Organization]',
        'description' => 'Required only for creating a Company',
    ),
        array(
        'key'         => 'com_billingstreet',
        'value'       => 'Billing Street [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_billingpostcode',
        'value'       => 'Billing Postcode [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_billingstate',
        'value'       => 'Billing State [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_billingcity',
        'value'       => 'Billing City [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_billingcountry',
        'value'       => 'Billing Country [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_shippingstreet',
        'value'       => 'Shipping Street [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_shippingpostcode',
        'value'       => 'Shipping Postcode [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_shippingstate',
        'value'       => 'Shipping State [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_shippingcity',
        'value'       => 'Shipping City [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_shippingcountry',
        'value'       => 'Shipping Country [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_phone',
        'value'       => 'Phone [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_fax',
        'value'       => 'Fax [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_emaildomain',
        'value'       => 'Email Domain [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_website',
        'value'       => 'Website [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_linkedin',
        'value'       => 'LinkedIn [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_facebook',
        'value'       => 'Facebook [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_twitter',
        'value'       => 'Twitter [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_background',
        'value'       => 'Background [Organization]',
        'description' => '',
    ),
        array(
        'key'         => 'com_tags',
        'value'       => 'Tags [Organization]',
        'description' => 'Use comma for multiple tags',
    )
    );
    $per_fields = array(
        array(
            'key'         => 'per_prefix',
            'value'       => 'Prefix [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_firstname',
            'value'       => 'First Name [Contact]',
            'description' => 'Required only for creating a Person',
        ),
        array(
            'key'         => 'per_lastname',
            'value'       => 'Last Name [Contact]',
            'description' => '',
        ),
        // array( 'key' => 'per_occupation', 'value' => 'Occupation [Contact]', 'description' => '' ),
        array(
            'key'         => 'per_email',
            'value'       => 'Email [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_phone',
            'value'       => 'Phone [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_homephone',
            'value'       => 'Home Phone [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_mobilephone',
            'value'       => 'Mobile Phone [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_fax',
            'value'       => 'Fax [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_assistantname',
            'value'       => 'Assistant Name [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_assistantphone',
            'value'       => 'Assistant Phone [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_facebook',
            'value'       => 'Facebook [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_linkedin',
            'value'       => 'LinkedIn [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_twitter',
            'value'       => 'Twitter [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_street',
            'value'       => 'Street [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_city',
            'value'       => 'City [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_state',
            'value'       => 'State [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_postcode',
            'value'       => 'Post Code [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_country',
            'value'       => 'Country [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_dob',
            'value'       => 'Date Of Birth [Contact]',
            'description' => '',
        ),
        array(
            'key'         => 'per_background',
            'value'       => 'Background [Contact]',
            'description' => '',
        ),
    );
    $deal_fields = array(
        array(
            'key'         => 'deal_name',
            'value'       => 'Name [Opportunity]',
            'description' => 'Required only for creating a Deal',
        ),
        array(
            'key'         => 'deal_description',
            'value'       => 'Description [Opportunity]',
            'description' => '',
        ),
        array(
            'key'         => 'deal_closedate',
            'value'       => 'Close Date [Opportunity]',
            'description' => '',
        ),
        // array( 'key' => 'deal_pipeline', 'value' => 'Pipeline_Stage ID [Opportunity]', 'description' => $pipelines ),
        array(
            'key'         => 'deal_value',
            'value'       => 'Value [Opportunity]',
            'description' => '',
        ),
        array(
            'key'         => 'deal_winpercentage',
            'value'       => 'Win Percentage [Opportunity]',
            'description' => '',
        ),
    );
    $final_data = array_merge( $com_fields, $per_fields, $deal_fields );
    wp_send_json_success( $final_data );
}

add_action(
    'adfoin_insightly_job_queue',
    'adfoin_insightly_job_queue',
    10,
    1
);
function adfoin_insightly_job_queue( $data )
{
    adfoin_insightly_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Insightly API
 */
function adfoin_insightly_send_data( $record, $posted_data )
{
    $api_keys = adfoin_insightly_get_keys();
    if ( !$api_keys['key'] || !$api_keys['url'] ) {
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
    $owner = $data["owner"];
    $com_id = "";
    $per_id = "";
    $deal_id = "";
    
    if ( $task == "add_contact" ) {
        $holder = array();
        $com_data = array();
        $per_data = array();
        $deal_data = array();
        foreach ( $data as $key => $value ) {
            $holder[$key] = adfoin_get_parsed_values( $data[$key], $posted_data );
        }
        foreach ( $holder as $key => $value ) {
            
            if ( substr( $key, 0, 4 ) == 'com_' && $value ) {
                $key = substr( $key, 4 );
                $com_data[$key] = $value;
            }
            
            
            if ( substr( $key, 0, 4 ) == 'per_' && $value ) {
                $key = substr( $key, 4 );
                $per_data[$key] = $value;
            }
            
            
            if ( substr( $key, 0, 5 ) == 'deal_' && $value ) {
                $key = substr( $key, 5 );
                $deal_data[$key] = $value;
            }
        
        }
        $headers = array(
            'Authorization' => 'Basic ' . base64_encode( $api_keys['key'] . ':' . '' ),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        );
        
        if ( $com_data['name'] ) {
            $com_url = $api_keys['url'] . '/v3.0/Organisations';
            $com_body = array(
                'ORGANISATION_NAME' => $com_data['name'],
            );
            if ( $owner ) {
                $com_body['OWNER_USER_ID'] = $owner;
            }
            if ( isset( $com_data['background'] ) && $com_data['background'] ) {
                $com_body['BACKGROUND'] = $com_data['background'];
            }
            if ( isset( $com_data['billingstreet'] ) && $com_data['billingstreet'] ) {
                $com_body['ADDRESS_BILLING_STREET'] = $com_data['billingstreet'];
            }
            if ( isset( $com_data['billingpostcode'] ) && $com_data['billingpostcode'] ) {
                $com_body['ADDRESS_BILLING_POSTCODE'] = $com_data['billingpostcode'];
            }
            if ( isset( $com_data['billingstate'] ) && $com_data['billingstate'] ) {
                $com_body['ADDRESS_BILLING_STATE'] = $com_data['billingstate'];
            }
            if ( isset( $com_data['billingcity'] ) && $com_data['billingcity'] ) {
                $com_body['ADDRESS_BILLING_CITY'] = $com_data['billingcity'];
            }
            if ( isset( $com_data['billingcountry'] ) && $com_data['billingcountry'] ) {
                $com_body['ADDRESS_BILLING_COUNTRY'] = $com_data['billingcountry'];
            }
            if ( isset( $com_data['shippingstreet'] ) && $com_data['shippingstreet'] ) {
                $com_body['ADDRESS_SHIPPING_STREET'] = $com_data['shippingstreet'];
            }
            if ( isset( $com_data['shippingpostcode'] ) && $com_data['shippingpostcode'] ) {
                $com_body['ADDRESS_SHIPPING_POSTCODE'] = $com_data['shippingpostcode'];
            }
            if ( isset( $com_data['shippingstate'] ) && $com_data['shippingstate'] ) {
                $com_body['ADDRESS_SHIPPING_STATE'] = $com_data['shippingstate'];
            }
            if ( isset( $com_data['shippingcity'] ) && $com_data['shippingcity'] ) {
                $com_body['ADDRESS_SHIPPING_CITY'] = $com_data['shippingcity'];
            }
            if ( isset( $com_data['shippingcountry'] ) && $com_data['shippingcountry'] ) {
                $com_body['ADDRESS_SHIPPING_COUNTRY'] = $com_data['shippingcountry'];
            }
            if ( isset( $com_data['phone'] ) && $com_data['phone'] ) {
                $com_body['PHONE'] = $com_data['phone'];
            }
            if ( isset( $com_data['fax'] ) && $com_data['fax'] ) {
                $com_body['PHONE_FAX'] = $com_data['fax'];
            }
            if ( isset( $com_data['emaildomain'] ) && $com_data['emaildomain'] ) {
                $com_body['EMAIL_DOMAIN'] = $com_data['emaildomain'];
            }
            if ( isset( $com_data['website'] ) && $com_data['website'] ) {
                $com_body['WEBSITE'] = $com_data['website'];
            }
            if ( isset( $com_data['linkedin'] ) && $com_data['linkedin'] ) {
                $com_body['SOCIAL_LINKEDIN'] = $com_data['linkedin'];
            }
            if ( isset( $com_data['facebook'] ) && $com_data['facebook'] ) {
                $com_body['SOCIAL_FACEBOOK'] = $com_data['facebook'];
            }
            if ( isset( $com_data['twitter'] ) && $com_data['twitter'] ) {
                $com_body['SOCIAL_TWITTER'] = $com_data['twitter'];
            }
            
            if ( isset( $com_data['tags'] ) && $com_data['tags'] ) {
                $com_tags = explode( ',', $com_data['tags'] );
                $com_body['TAGS'] = array();
                foreach ( $com_tags as $com_tag ) {
                    $com_body['TAGS'][] = array(
                        'TAG_NAME' => $com_tag,
                    );
                }
            }
            
            $com_args = array(
                "headers" => $headers,
                "body"    => json_encode( $com_body ),
            );
            $com_response = wp_remote_post( $com_url, $com_args );
            adfoin_add_to_log(
                $com_response,
                $com_url,
                $com_args,
                $record
            );
            $com_body = json_decode( wp_remote_retrieve_body( $com_response ) );
            if ( $com_response['response']['code'] == 200 ) {
                $com_id = $com_body->ORGANISATION_ID;
            }
        }
        
        
        if ( $per_data['firstname'] ) {
            $per_url = $api_keys['url'] . '/v3.0/Contacts';
            $per_body = array(
                'FIRST_NAME' => $per_data['firstname'],
            );
            if ( $owner ) {
                $per_body['OWNER_USER_ID'] = $owner;
            }
            if ( $com_id ) {
                $per_body['ORGANISATION_ID'] = $com_id;
            }
            if ( isset( $per_data['prefix'] ) && $per_data['prefix'] ) {
                $per_body['SALUTATION'] = $per_data['prefix'];
            }
            if ( isset( $per_data['lastname'] ) && $per_data['lastname'] ) {
                $per_body['LAST_NAME'] = $per_data['lastname'];
            }
            // if( isset( $per_data['occupation'] ) && $per_data['occupation'] ) { $per_body['title'] = $per_data['occupation']; }
            if ( isset( $per_data['email'] ) && $per_data['email'] ) {
                $per_body['EMAIL_ADDRESS'] = $per_data['email'];
            }
            if ( isset( $per_data['phone'] ) && $per_data['phone'] ) {
                $per_body['PHONE'] = $per_data['phone'];
            }
            if ( isset( $per_data['homephone'] ) && $per_data['homephone'] ) {
                $per_body['PHONE_HOME'] = $per_data['homephone'];
            }
            if ( isset( $per_data['mobilephone'] ) && $per_data['mobilephone'] ) {
                $per_body['PHONE_MOBILE'] = $per_data['mobilephone'];
            }
            if ( isset( $per_data['fax'] ) && $per_data['fax'] ) {
                $per_body['PHONE_FAX'] = $per_data['fax'];
            }
            if ( isset( $per_data['assistantname'] ) && $per_data['assistantname'] ) {
                $per_body['ASSISTANT_NAME'] = $per_data['assistantname'];
            }
            if ( isset( $per_data['assistantphone'] ) && $per_data['assistantphone'] ) {
                $per_body['PHONE_ASSISTANT'] = $per_data['assistantphone'];
            }
            if ( isset( $per_data['street'] ) && $per_data['street'] ) {
                $per_body['ADDRESS_MAIL_STREET'] = $per_data['street'];
            }
            if ( isset( $per_data['city'] ) && $per_data['city'] ) {
                $per_body['ADDRESS_MAIL_CITY'] = $per_data['city'];
            }
            if ( isset( $per_data['state'] ) && $per_data['state'] ) {
                $per_body['ADDRESS_MAIL_STATE'] = $per_data['state'];
            }
            if ( isset( $per_data['postcode'] ) && $per_data['postcode'] ) {
                $per_body['ADDRESS_MAIL_POSTCODE'] = $per_data['postcode'];
            }
            if ( isset( $per_data['country'] ) && $per_data['country'] ) {
                $per_body['ADDRESS_MAIL_COUNTRY'] = $per_data['country'];
            }
            if ( isset( $per_data['dob'] ) && $per_data['dob'] ) {
                $per_body['DATE_OF_BIRTH'] = $per_data['dob'];
            }
            if ( isset( $per_data['facebook'] ) && $per_data['facebook'] ) {
                $per_body['SOCIAL_FACEBOOK'] = $per_data['facebook'];
            }
            if ( isset( $per_data['linkedin'] ) && $per_data['linkedin'] ) {
                $per_body['SOCIAL_LINKEDIN'] = $per_data['linkedin'];
            }
            if ( isset( $per_data['twitter'] ) && $per_data['twitter'] ) {
                $per_body['SOCIAL_TWITTER'] = $per_data['twitter'];
            }
            if ( isset( $com_data['background'] ) && $com_data['background'] ) {
                $com_body['BACKGROUND'] = $com_data['background'];
            }
            $per_args = array(
                "headers" => $headers,
                "body"    => json_encode( $per_body ),
            );
            $per_response = wp_remote_post( $per_url, $per_args );
            adfoin_add_to_log(
                $per_response,
                $per_url,
                $per_args,
                $record
            );
            $per_body = json_decode( wp_remote_retrieve_body( $per_response ) );
            if ( $per_response['response']['code'] == 200 ) {
                $per_id = $per_body->CONTACT_ID;
            }
        }
        
        
        if ( $deal_data['name'] ) {
            $deal_url = $api_keys['url'] . '/v3.0/Opportunities';
            $deal_body = array(
                'OPPORTUNITY_NAME' => $deal_data['name'],
            );
            
            if ( $owner ) {
                $deal_body['OWNER_USER_ID'] = $owner;
                $deal_body['RESPONSIBLE_USER_ID'] = $owner;
            }
            
            if ( $com_id ) {
                $deal_body['ORGANISATION_ID'] = $com_id;
            }
            if ( $per_id ) {
            }
            if ( isset( $deal_data['closedate'] ) && $deal_data['closedate'] ) {
                $deal_body['FORECAST_CLOSE_DATE'] = $deal_data['closedate'];
            }
            if ( isset( $deal_data['description'] ) && $deal_data['description'] ) {
                $deal_body['OPPORTUNITY_DETAILS'] = $deal_data['description'];
            }
            if ( isset( $deal_data['winpercentange'] ) && $deal_data['winpercentange'] ) {
                $deal_body['PROBABILITY'] = $deal_data['winpercentange'];
            }
            if ( isset( $deal_data['value'] ) && $deal_data['value'] ) {
                $deal_body['OPPORTUNITY_VALUE'] = $deal_data['value'];
            }
            // if( isset( $deal_data['pipeline'] ) && $deal_data['pipeline'] ) {
            //     $pipeline_stage = explode( '_', $deal_data['pipeline'] );
            //     if( count( $pipeline_stage ) == 2 ) {
            //         $deal_body['PIPELINE_ID'] = $pipeline_stage[0];
            //         $deal_body['STAGE_ID']    = $pipeline_stage[1];
            //     }
            // }
            $deal_args = array(
                "headers" => $headers,
                "body"    => json_encode( $deal_body ),
            );
            $deal_response = wp_remote_post( $deal_url, $deal_args );
            adfoin_add_to_log(
                $deal_response,
                $deal_url,
                $deal_args,
                $record
            );
            $deal_body = json_decode( wp_remote_retrieve_body( $deal_response ) );
            if ( $deal_response['response']['code'] == 200 ) {
                $deal_id = $deal_body->OPPORTUNITY_ID;
            }
        }
    
    }
    
    return;
}
