<?php

add_filter( 'adfoin_action_providers', 'adfoin_flowlu_actions', 10, 1 );
 
function adfoin_flowlu_actions( $actions ) {

    $actions['flowlu'] = array(
        'title' => __( 'Flowlu (Beta)', 'advanced-form-integration' ),
        'tasks' => array(
            'add_record' => __( 'Add Records', 'advanced-form-integration' )
        )
    );

    return $actions;
}
 
add_filter( 'adfoin_settings_tabs', 'adfoin_flowlu_settings_tab', 10, 1 );

function adfoin_flowlu_settings_tab( $providers ) {
    $providers['flowlu'] = __( 'Flowlu', 'advanced-form-integration' );

    return $providers;
}
 
add_action( 'adfoin_settings_view', 'adfoin_flowlu_settings_view', 10, 1 );

function adfoin_flowlu_settings_view( $current_tab ) {
    if( $current_tab != 'flowlu' ) {
        return;
    }

    $nonce     = wp_create_nonce( 'adfoin_flowlu_settings' );
    $api_token = get_option( 'adfoin_flowlu_api_token' ) ? get_option( 'adfoin_flowlu_api_token' ) : '';
    $subdomain = get_option( 'adfoin_flowlu_subdomain' ) ? get_option( 'adfoin_flowlu_subdomain' ) : '';
    ?>

    <form name="flowlu_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
        method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_flowlu_api_token">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
        <tr valign="top">
                <th scope="row"> <?php _e( 'Subdomain', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_flowlu_subdomain"
                        value="<?php echo esc_attr( $subdomain ); ?>" placeholder="<?php _e( 'Please enter subdomain', 'advanced-form-integration' ); ?>"
                        class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'The subdomain part in your URL, before flowlu.com', 'advanced-form-integration' ); ?></a></p>
                </td>

            </tr>
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Key', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_flowlu_api_token"
                        value="<?php echo esc_attr( $api_token ); ?>" placeholder="<?php _e( 'Please enter API Key', 'advanced-form-integration' ); ?>"
                        class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Go to Portal Settings > API Settings > Create a new API Key > Mark all apps > Save', 'advanced-form-integration' ); ?></a></p>
                </td>

            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}
 
add_action( 'admin_post_adfoin_save_flowlu_api_token', 'adfoin_save_flowlu_api_token', 10, 0 );

function adfoin_save_flowlu_api_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_flowlu_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_token = sanitize_text_field( $_POST["adfoin_flowlu_api_token"] );
    $subdomain = sanitize_text_field( $_POST["adfoin_flowlu_subdomain"] );

    // Save tokens
    update_option( "adfoin_flowlu_api_token", $api_token );
    update_option( "adfoin_flowlu_subdomain", $subdomain );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=flowlu" );
}
 
add_action( 'adfoin_action_fields', 'adfoin_flowlu_action_fields', 10, 1 );

function adfoin_flowlu_action_fields() {
    ?>
    <script type="text/template" id="flowlu-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_record'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_record'">
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

            <tr valign="top" class="alternate" v-if="action.task == 'add_record'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Objects', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <div class="object_selection" style="display: inline;">
                        <input type="checkbox" id="organization__chosen" value="true" v-model="fielddata.organization__chosen" name="fieldData[organization__chosen]">
                        <label style="margin-right:10px;" for="organization__chosen">Organization</label>
                        <input type="checkbox" id="contact__chosen" value="true" v-model="fielddata.contact__chosen" name="fieldData[contact__chosen]">
                        <label style="margin-right:10px;" for="contact__chosen">Contact</label>
                        <input type="checkbox" id="opportunity__chosen" value="true" v-model="fielddata.opportunity__chosen" name="fieldData[opportunity__chosen]">
                        <label style="margin-right:10px;" for="opportunity__chosen">Opportunity</label>
                        <input type="checkbox" id="case__chosen" value="true" v-model="fielddata.case__chosen" name="fieldData[case__chosen]">
                        <label style="margin-right:10px;" for="case__chosen">Case</label>
                        <input type="checkbox" id="task__chosen" value="true" v-model="fielddata.task__chosen" name="fieldData[task__chosen]">
                        <label style="margin-right:10px;" for="task__chosen">Task</label>
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
 * Capsule CRM API Request
 */
function adfoin_flowlu_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {

    $subdomain = get_option( 'adfoin_flowlu_subdomain' );
    $api_token = get_option( 'adfoin_flowlu_api_token' );

    $base_url = "https://{$subdomain}.flowlu.com/api/v1/module/";
    $url      = $base_url . $endpoint;
    $url      = add_query_arg( 'api_key', $api_token, $url );

    $args = array(
        'method'  => $method,
        'headers' => array(
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept'       => 'application/json'
        ),
    );

    if ( 'POST' == $method || 'PUT' == $method || 'PATCH' == $method ) {
        $args['body'] = $data;
    }

    $response = wp_remote_request($url, $args);

    if ($record) {
        adfoin_add_to_log($response, $url, $args, $record);
    }

    return $response;
}
 
add_action( 'wp_ajax_adfoin_get_flowlu_owner_list', 'adfoin_get_flowlu_owner_list', 10, 0 );

/*
* Get flowlu Owner list
*/
function adfoin_get_flowlu_owner_list() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $users = array();
    $data  = adfoin_flowlu_request( 'core/user/list' );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body = json_decode( wp_remote_retrieve_body( $data ), true );

    if( isset( $body['response'], $body['response']['items'] ) && is_array( $body['response']['items'] ) ) {
        $users = wp_list_pluck( $body['response']['items'], 'first_name', 'id' );
    }

    wp_send_json_success( $users );
}

function adfoin_get_flowlu_account_categories() {
    $result = adfoin_flowlu_request( 'crm/account_category/list' );
    $body   = json_decode( wp_remote_retrieve_body( $result ), true );
    $categories = array();
    
    if( isset( $body['response'], $body['response']['items'] ) && is_array( $body['response']['items'] ) ) {
        foreach( $body['response']['items'] as $category ) {
            $categories[] = $category['name'] . ': ' . $category['id'];
        }
    }

    return implode( ', ', $categories );
}

function adfoin_get_flowlu_account_industries() {
    $result = adfoin_flowlu_request( 'crm/industry/list' );
    $body   = json_decode( wp_remote_retrieve_body( $result ), true );
    $industries = array();
    
    if( isset( $body['response'], $body['response']['items'] ) && is_array( $body['response']['items'] ) ) {
        foreach( $body['response']['items'] as $category ) {
            $industries[] = $category['name'] . ': ' . $category['id'];
        }
    }

    return implode( ', ', $industries );
}

function adfoin_get_flowlu_honorific_titles() {
    $result = adfoin_flowlu_request( 'crm/honorific_title/list' );
    $body   = json_decode( wp_remote_retrieve_body( $result ), true );
    $titles = array();
    
    if( isset( $body['response'], $body['response']['items'] ) && is_array( $body['response']['items'] ) ) {
        foreach( $body['response']['items'] as $category ) {
            $titles[] = $category['name'] . ': ' . $category['id'];
        }
    }

    return implode( ', ', $titles );
}

function adfoin_get_flowlu_pipeline_stages() {
    $result = adfoin_flowlu_request( 'crm/pipeline_stage/list' );
    $body   = json_decode( wp_remote_retrieve_body( $result ), true );
    $stages = array();
    
    if( isset( $body['response'], $body['response']['items'] ) && is_array( $body['response']['items'] ) ) {
        foreach( $body['response']['items'] as $stage ) {
            $stages[] = $stage['name'] . ': ' . $stage['id'];
        }
    }

    return implode( ', ', $stages );
}

function adfoin_get_flowlu_opportunity_sources() {
    $result = adfoin_flowlu_request( 'crm/source/list' );
    $body   = json_decode( wp_remote_retrieve_body( $result ), true );
    $sources = array();
    
    if( isset( $body['response'], $body['response']['items'] ) && is_array( $body['response']['items'] ) ) {
        foreach( $body['response']['items'] as $source ) {
            $sources[] = $source['name'] . ': ' . $source['id'];
        }
    }

    return implode( ', ', $sources );
}


add_action( 'wp_ajax_adfoin_get_flowlu_all_fields', 'adfoin_get_flowlu_all_fields', 10, 0 );
 
/*
* Get Capsule CRM All Fields
*/
function adfoin_get_flowlu_all_fields() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $final_data       = array();
    $selected_objects = isset( $_POST['selectedObjects'] ) ? adfoin_sanitize_text_or_array_field( $_POST['selectedObjects'] ) : array();

    if( in_array( 'organization', $selected_objects ) || in_array( 'contact', $selected_objects ) ) {
        $account_categories = adfoin_get_flowlu_account_categories();
        $account_industries = adfoin_get_flowlu_account_industries();

        if( in_array( 'organization', $selected_objects ) ) {
            $org_fields = array(
                array( 'key' => 'org_name', 'value' => 'Name [Organization]', 'description' => 'Required for creating an organization' ),
                array( 'key' => 'org_name_legal', 'value' => 'Legal Name [Organization]', 'description' => '' ),
                array( 'key' => 'org_name_legal_full', 'value' => 'Full Legal Name [Organization]', 'description' => '' ),
                array( 'key' => 'org_account_category_id', 'value' => 'Category ID [Organization]', 'description' => $account_categories ),
                array( 'key' => 'org_industry_id', 'value' => 'Industry ID [Organization]', 'description' => $account_industries ),
                array( 'key' => 'org_web', 'value' => 'Website [Organization]', 'description' => '' ),
                array( 'key' => 'org_email', 'value' => 'Email [Organization]', 'description' => '' ),
                array( 'key' => 'org_phone', 'value' => 'Phone [Organization]', 'description' => '' ),
                array( 'key' => 'org_description', 'value' => 'Description [Organization]', 'description' => '' ),
                array( 'key' => 'org_vat', 'value' => 'Tax ID [Organization]', 'description' => '' ),
                array( 'key' => 'org_vat1', 'value' => 'Additional Tax ID [Organization]', 'description' => '' ),
                array( 'key' => 'org_vat2', 'value' => 'Other ID [Organization]', 'description' => '' ),
                array( 'key' => 'org_bank_details', 'value' => 'Bank Details [Organization]', 'description' => '' ),
                array( 'key' => 'org_billing_address_line_1', 'value' => 'Billing Address Line 1 [Organization]', 'description' => '' ),
                array( 'key' => 'org_billing_address_line_2', 'value' => 'Billing Address Line 2 [Organization]', 'description' => '' ),
                array( 'key' => 'org_billing_address_line_3', 'value' => 'Billing Address Line 3 [Organization]', 'description' => '' ),
                array( 'key' => 'org_billing_zip', 'value' => 'Billing ZIP [Organization]', 'description' => '' ),
                array( 'key' => 'org_billing_city', 'value' => 'Billing City [Organization]', 'description' => '' ),
                array( 'key' => 'org_billing_state', 'value' => 'Billing State [Organization]', 'description' => '' ),
                array( 'key' => 'org_billing_country', 'value' => 'Billing Country [Organization]', 'description' => '' ),
                array( 'key' => 'org_shipping_address_line_1', 'value' => 'Shipping Address Line 1 [Organization]', 'description' => '' ),
                array( 'key' => 'org_shipping_address_line_2', 'value' => 'Shipping Address Line 2 [Organization]', 'description' => '' ),
                array( 'key' => 'org_shipping_address_line_3', 'value' => 'Shipping Address Line 3 [Organization]', 'description' => '' ),
                array( 'key' => 'org_shipping_zip', 'value' => 'Shipping ZIP [Organization]', 'description' => '' ),
                array( 'key' => 'org_shipping_city', 'value' => 'Shipping City [Organization]', 'description' => '' ),
                array( 'key' => 'org_shipping_state', 'value' => 'Shipping State [Organization]', 'description' => '' ),
                array( 'key' => 'org_shipping_country', 'value' => 'Shipping Country [Organization]', 'description' => '' ),
                array( 'key' => 'org_link_facebook', 'value' => 'Facebook [Organization]', 'description' => '' ),
                array( 'key' => 'org_link_linkedin', 'value' => 'LinkedIn [Organization]', 'description' => '' ),
                array( 'key' => 'org_link_google', 'value' => 'Telegram [Organization]', 'description' => '' ),
                array( 'key' => 'org_skype', 'value' => 'Skype [Organization]', 'description' => '' )
            );
    
            $final_data = array_merge( $final_data, $org_fields );
        }

        if( in_array( 'contact', $selected_objects ) ) {
            $honorific_titles  = adfoin_get_flowlu_honorific_titles();
    
            $contact_fields = array(
                array( 'key' => 'contact_honorific_title_id', 'value' => 'Title [Contact]', 'description' => $honorific_titles ),
                array( 'key' => 'contact_first_name', 'value' => 'First Name [Contact]', 'description' => 'Required for creating a contact' ),
                array( 'key' => 'contact_middle_name', 'value' => 'Middle Name [Contact]', 'description' => '' ),
                array( 'key' => 'contact_last_name', 'value' => 'Last Name [Contact]', 'description' => '' ),
                array( 'key' => 'contact_account_category_id', 'value' => 'Category ID [Contact]', 'description' => $account_categories ),
                array( 'key' => 'contact_industry_id', 'value' => 'Industry ID [Contact]', 'description' => $account_industries ),
                array( 'key' => 'contact_description', 'value' => 'Description [Contact]', 'description' => '' ),
                array( 'key' => 'contact_web', 'value' => 'Website [Contact]', 'description' => '' ),
                array( 'key' => 'contact_phone', 'value' => 'Phone [Contact]', 'description' => '' ),
                array( 'key' => 'contact_email', 'value' => 'Email [Contact]', 'description' => '' ),
                array( 'key' => 'contact_billing_address_line_1', 'value' => 'Billing Address Line 1 [Contact]', 'description' => '' ),
                array( 'key' => 'contact_billing_address_line_2', 'value' => 'Billing Address Line 2 [Contact]', 'description' => '' ),
                array( 'key' => 'contact_billing_address_line_3', 'value' => 'Billing Address Line 3 [Contact]', 'description' => '' ),
                array( 'key' => 'contact_billing_zip', 'value' => 'Billing ZIP [Contact]', 'description' => '' ),
                array( 'key' => 'contact_billing_city', 'value' => 'Billing City [Contact]', 'description' => '' ),
                array( 'key' => 'contact_billing_state', 'value' => 'Billing State [Contact]', 'description' => '' ),
                array( 'key' => 'contact_billing_country', 'value' => 'Billing Country [Contact]', 'description' => '' ),
                array( 'key' => 'contact_shipping_address_line_1', 'value' => 'Shipping Address Line 1 [Contact]', 'description' => '' ),
                array( 'key' => 'contact_shipping_address_line_2', 'value' => 'Shipping Address Line 2 [Contact]', 'description' => '' ),
                array( 'key' => 'contact_shipping_address_line_3', 'value' => 'Shipping Address Line 3 [Contact]', 'description' => '' ),
                array( 'key' => 'contact_shipping_zip', 'value' => 'Shipping ZIP [Contact]', 'description' => '' ),
                array( 'key' => 'contact_shipping_city', 'value' => 'Shipping City [Contact]', 'description' => '' ),
                array( 'key' => 'contact_shipping_state', 'value' => 'Shipping State [Contact]', 'description' => '' ),
                array( 'key' => 'contact_shipping_country', 'value' => 'Shipping Country [Contact]', 'description' => '' ),
                array( 'key' => 'contact_link_facebook', 'value' => 'Facebook [Contact]', 'description' => '' ),
                array( 'key' => 'contact_link_linkedin', 'value' => 'LinkedIn [Contact]', 'description' => '' ),
                array( 'key' => 'contact_link_google', 'value' => 'Telegram [Contact]', 'description' => '' ),
                array( 'key' => 'contact_skype', 'value' => 'Skype [Contact]', 'description' => '' )
            );
    
            $final_data = array_merge( $final_data, $contact_fields );
        }
    }

    if( in_array( 'opportunity', $selected_objects ) ) {
        $stages  = adfoin_get_flowlu_pipeline_stages();
        $sources = adfoin_get_flowlu_opportunity_sources();

        $opportunity_fields = array(
            array( 'key' => 'opportunity_name', 'value' => 'Name [Opportunity]', 'description' => 'Required if you want to create an opportunity, otherwise leave empty' ),
            array( 'key' => 'opportunity_budget', 'value' => 'Amount [Opportunity]', 'description' => '' ),
            array( 'key' => 'opportunity_source_id', 'value' => 'Source ID [Opportunity]', 'description' => $sources ),
            array( 'key' => 'opportunity_pipeline_stage_id', 'value' => 'Pipeline Stage ID [Opportunity]', 'description' => $stages ),
            array( 'key' => 'opportunity_start_date', 'value' => 'Start Date [Opportunity]', 'description' => '' ),
            array( 'key' => 'opportunity_deadline', 'value' => 'End Date [Opportunity]', 'description' => '' ),
            array( 'key' => 'opportunity_description', 'value' => 'Description [Opportunity]', 'description' => '' ),
        );

        $final_data = array_merge( $final_data, $opportunity_fields );
    }

    wp_send_json_success( $final_data );
}

add_action( 'adfoin_flowlu_job_queue', 'adfoin_flowlu_job_queue', 10, 1 );

function adfoin_flowlu_job_queue( $data ) {
    adfoin_flowlu_send_data( $data['record'], $data['posted_data'] );
}
  
/*
* Handles sending data to Capsule CRM API
*/
function adfoin_flowlu_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record['data'], true );

    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data           = $record_data['field_data'];
    $task           = $record['task'];
    $owner          = $data['owner'];
    $org_id         = '';
    $contact_id     = '';
    $opportunity_id = '';

    if( $task == "add_record" ) {

        $org_data         = array();
        $contact_data     = array();
        $opportunity_data = array();

        foreach( $data as $key => $value ) {
            if( substr( $key, 0, 4 ) == 'org_' && $value ) {
                $key = substr( $key, 4 );

                $org_data[$key] = $value;
            }

            if( substr( $key, 0, 8 ) == 'contact_' && $value ) {
                $key = substr( $key, 8 );

                $contact_data[$key] = $value;
            }

            if( substr( $key, 0, 12 ) == 'opportunity_' && $value ) {
                $key = substr( $key, 12 );

                $opportunity_data[$key] = $value;
            }
        }

        if( isset( $org_data['name'] ) && $org_data['name'] ) {
            $endpoint           = 'crm/account/create';
            $method             = 'POST';
            $org_holder         = array();
            $org_holder['type'] = 1;

            if( $owner ) {
                $org_holder['owner_id'] = $owner;
            }

            foreach( $org_data as $key => $value ) {
                $org_holder[$key] = adfoin_get_parsed_values( $value, $posted_data );
            }
            
            $org_id = adfoin_flowlu_record_exists( 'crm/account', 'name', $org_holder['name'] );

            if( $org_id ) {
                $endpoint = "crm/account/update/{$org_id}";
            }

            $org_holder   = array_filter( $org_holder );
            $org_response = adfoin_flowlu_request( $endpoint, $method, $org_holder, $record );
            $org_body     = json_decode( wp_remote_retrieve_body( $org_response ), true );

            if( isset( $org_body['response'], $org_body['response']['id'] ) ) {
                $org_id = $org_body['response']['id'];
            }
        }

        if( isset( $contact_data['first_name'] ) && $contact_data['first_name'] ) {
            $endpoint       = 'crm/account/create';
            $method         = 'POST';
            $contact_holder = array();
            $contact_holder['type'] = 2;

            if( $owner ) {
                $contact_holder['owner_id'] = $owner;
            }

            foreach( $contact_data as $key => $value ) {
                $contact_holder[$key] = adfoin_get_parsed_values( $value, $posted_data );
            }
            
            if( isset( $contact_holder['email'] ) ) {
                $contact_id = adfoin_flowlu_record_exists( 'crm/account', 'email', $contact_holder['email'] );
            }

            if( $contact_id ) {
                $endpoint = "crm/account/update/{$contact_id}";
            }

            $contact_holder   = array_filter( $contact_holder );
            $contact_response = adfoin_flowlu_request( $endpoint, $method, $contact_holder, $record );
            $contact_body     = json_decode( wp_remote_retrieve_body( $contact_response ), true );

            if( isset( $contact_body['response'], $contact_body['response']['id'] ) ) {
                $contact_id = $contact_body['response']['id'];
            }
        }

        if( $org_id && $contact_id ) {
            $relation = adfoin_flowlu_request(
                'crm/relation/create',
                'POST',
                array(
                    'parent_acc_id' => $org_id,
                    'child_acc_id'  => $contact_id,
                    'type'          => 1
                ),
                $record
            );
        }

        if( isset( $opportunity_data['name'] ) && $opportunity_data['name'] ) {
            $endpoint           = 'crm/lead/create';
            $method             = 'POST';
            $opportunity_holder = array();

            if( $owner ) {
                $opportunity_holder['owner_id'] = $owner;
            }

            foreach( $opportunity_data as $key => $value ) {
                $opportunity_holder[$key] = adfoin_get_parsed_values( $value, $posted_data );
            }

            if( $contact_id ) {
                $opportunity_holder['contact_id'] = $contact_id;
            }

            if( $org_id ) {
                $opportunity_holder['contact_id'] = $org_id;
            }

            $opportunity_holder   = array_filter( $opportunity_holder );
            $opportunity_response = adfoin_flowlu_request( $endpoint, $method, $opportunity_holder, $record );
            $opportunity_body     = json_decode( wp_remote_retrieve_body( $opportunity_response ), true );

            if( isset( $opportunity_body['response'], $opportunity_body['response']['id'] ) ) {
                $opportunity_id = $opportunity_body['response']['id'];
            }
        }
    }

    return;
}
 
/*
* Checks if Record exists
* @returns: Record ID if exists
*/

function adfoin_flowlu_record_exists( $module, $key, $value ) {
 
    $endpoint = "{$module}/list";

    $query_args = array(
        "filter[{$key}]" => $value
    );

    $endpoint      = add_query_arg( $query_args, $endpoint );
    $response      = adfoin_flowlu_request( $endpoint, 'GET' );
    $response_code = wp_remote_retrieve_response_code( $response );
    $record_id     = '';
    
    if( 200 == $response_code ) {
        $response_body = json_decode( wp_remote_retrieve_body( $response ), true );

        if( isset( $response_body['response'], $response_body['response']['items'] ) && is_array( $response_body['response']['items'] ) ) {
            if( count( $response_body['response']['items'] ) > 0 ) {
                $record_id = $response_body['response']['items'][0]['id'];
            }
        }
    }

    if( $record_id ) {
        return $record_id;
    } else{
        return false;
    }
}