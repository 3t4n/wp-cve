<?php

add_filter( 'adfoin_action_providers', 'adfoin_capsulecrm_actions', 10, 1 );
 
function adfoin_capsulecrm_actions( $actions ) {

    $actions['capsulecrm'] = array(
        'title' => __( 'Capsule CRM', 'advanced-form-integration' ),
        'tasks' => array(
            'add_party' => __( 'Add Party, Opportunity, Case, Task', 'advanced-form-integration' )
        )
    );

    return $actions;
}
 
add_filter( 'adfoin_settings_tabs', 'adfoin_capsulecrm_settings_tab', 10, 1 );

function adfoin_capsulecrm_settings_tab( $providers ) {
    $providers['capsulecrm'] = __( 'Capsule CRM', 'advanced-form-integration' );

    return $providers;
}
 
add_action( 'adfoin_settings_view', 'adfoin_capsulecrm_settings_view', 10, 1 );

function adfoin_capsulecrm_settings_view( $current_tab ) {
    if( $current_tab != 'capsulecrm' ) {
        return;
    }

    $nonce     = wp_create_nonce( 'adfoin_capsulecrm_settings' );
    $api_token = get_option( 'adfoin_capsulecrm_api_token' ) ? get_option( 'adfoin_capsulecrm_api_token' ) : '';
    ?>

    <form name="capsulecrm_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
        method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_capsulecrm_api_token">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Authentication Token', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_capsulecrm_api_token"
                        value="<?php echo esc_attr( $api_token ); ?>" placeholder="<?php _e( 'Please enter API Token', 'advanced-form-integration' ); ?>"
                        class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Go to My Preferences > API Authentication Tokens > Generate New API Token', 'advanced-form-integration' ); ?></a></p>
                </td>

            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}
 
add_action( 'admin_post_adfoin_save_capsulecrm_api_token', 'adfoin_save_capsulecrm_api_token', 10, 0 );

function adfoin_save_capsulecrm_api_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_capsulecrm_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_token = sanitize_text_field( $_POST["adfoin_capsulecrm_api_token"] );

    // Save tokens
    update_option( "adfoin_capsulecrm_api_token", $api_token );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=capsulecrm" );
}
 
add_action( 'adfoin_action_fields', 'adfoin_capsulecrm_action_fields', 10, 1 );

function adfoin_capsulecrm_action_fields() {
    ?>
    <script type="text/template" id="capsulecrm-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_party'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_party'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Owner & Team', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[owner]" v-model="fielddata.owner" required="required">
                        <option value=""> <?php _e( 'Select Owner/Team...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.ownerList" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': ownerLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_party'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Entities', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <div class="object_selection" style="display: inline;">
                        <input type="checkbox" id="organisation__chosen" value="true" v-model="fielddata.organisation__chosen" name="fieldData[organisation__chosen]">
                        <label style="margin-right:10px;" for="organisation__chosen">Organisation</label>
                        <input type="checkbox" id="person__chosen" value="true" v-model="fielddata.person__chosen" name="fieldData[person__chosen]">
                        <label style="margin-right:10px;" for="person__chosen">Person</label>
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
function adfoin_capsulecrm_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {

    $api_token = get_option( 'adfoin_capsulecrm_api_token' ) ? get_option( 'adfoin_capsulecrm_api_token' ) : '';

    $base_url = 'https://api.capsulecrm.com/api/v2/';
    $url      = $base_url . $endpoint;

    $args = array(
        'method'  => $method,
        'headers' => array(
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $api_token,
        ),
    );

    if ('POST' == $method || 'PUT' == $method) {
        $args['body'] = json_encode($data);
    }

    $response = wp_remote_request($url, $args);

    if ($record) {
        adfoin_add_to_log($response, $url, $args, $record);
    }

    return $response;
}
 
add_action( 'wp_ajax_adfoin_get_capsulecrm_owner_list', 'adfoin_get_capsulecrm_owner_list', 10, 0 );

/*
* Get Capsulecrm Owner list
*/
function adfoin_get_capsulecrm_owner_list() {
// Security Check
if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
    die( __( 'Security check Failed', 'advanced-form-integration' ) );
}

$combined = array();
$users    = adfoin_get_capsulecrm_users();
$teams    = adfoin_get_capsulecrm_teams();

foreach( $users as $user ) {
    $combined['user__' . $user['id']] = $user['name'];
}

foreach( $teams as $team ) {
    $combined['team__' . $team['id']] = $team['name'];
}

wp_send_json_success( $combined );
}


//Get users list
function adfoin_get_capsulecrm_users() {
$users = array();
$data  = adfoin_capsulecrm_request( 'users' );

if( is_wp_error( $data ) ) {
    wp_send_json_error();
}

$body = json_decode( wp_remote_retrieve_body( $data ), true );

if( isset( $body['users'] ) ) {
    $users = $body['users'];
}

return $users;
}

//Get team list
function adfoin_get_capsulecrm_teams() {
    $teams = array();
    $data  = adfoin_capsulecrm_request( 'teams' );
 
    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body = json_decode( wp_remote_retrieve_body( $data ), true );

    if( isset( $body['teams'] ) ) {
        $teams = $body['teams'];
    }

    return $teams;
}

 //Get Opportunity Milestones
 function adfoin_get_capsulecrm_opportunity_milestones() {
    $milestones = array();
    $data       = adfoin_capsulecrm_request( 'milestones' );
    $body       = json_decode( wp_remote_retrieve_body( $data ), true );

    if( isset( $body['milestones'] ) && is_array( $body['milestones'] ) ) {
        foreach( $body['milestones'] as $milestone ) {
            $milestones[] = $milestone['pipeline']['name'] . '/' . $milestone['name'] . ': ' . $milestone['id'];
        }
    }

    $merged = implode( ', ', $milestones );

    return $merged;
 }
 
 add_action( 'wp_ajax_adfoin_get_capsulecrm_all_fields', 'adfoin_get_capsulecrm_all_fields', 10, 0 );
 
/*
* Get Capsule CRM All Fields
*/
function adfoin_get_capsulecrm_all_fields() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $final_data       = array();
    $selected_objects = isset( $_POST['selectedObjects'] ) ? adfoin_sanitize_text_or_array_field( $_POST['selectedObjects'] ) : array();

    if( in_array( 'organisation', $selected_objects ) ) {
        $org_fields = array(
            array( 'key' => 'org_name', 'value' => 'Name [Organization]', 'description' => 'Required if you want to create an organization, otherwise leave empty' ),
            array( 'key' => 'org_phone', 'value' => 'Phone [Organization]', 'description' => '' ),
            array( 'key' => 'org_email', 'value' => 'Email [Organization]', 'description' => '' ),
            array( 'key' => 'org_website', 'value' => 'Website [Organization]', 'description' => '' ),
            array( 'key' => 'org_twitter', 'value' => 'Twitter [Organization]', 'description' => '' ),
            array( 'key' => 'org_linkedin', 'value' => 'LinkedIn [Organization]', 'description' => '' ),
            array( 'key' => 'org_facebook', 'value' => 'Facebook [Organization]', 'description' => '' ),
            array( 'key' => 'org_youtube', 'value' => 'YouTube [Organization]', 'description' => '' ),
            array( 'key' => 'org_instagram', 'value' => 'Instagram [Organization]', 'description' => '' ),
            array( 'key' => 'org_address', 'value' => 'Address [Organization]', 'description' => '' ),
            array( 'key' => 'org_city', 'value' => 'City [Organization]', 'description' => '' ),
            array( 'key' => 'org_state', 'value' => 'State [Organization]', 'description' => '' ),
            array( 'key' => 'org_zip', 'value' => 'Zip [Organization]', 'description' => '' ),
            array( 'key' => 'org_country', 'value' => 'Country [Organization]', 'description' => '' ),
        );

        $final_data = array_merge( $final_data, $org_fields );
    }

    if( in_array( 'person', $selected_objects ) ) {
        $person_fields = array(
            array( 'key' => 'person_title', 'value' => 'Title [Person]', 'description' => '' ),
            array( 'key' => 'person_firstName', 'value' => 'First Name [Person]', 'description' => 'Required if you want to create a person, otherwise leave empty' ),
            array( 'key' => 'person_lastName', 'value' => 'Last Name [Person]', 'description' => '' ),
            array( 'key' => 'person_jobTitle', 'value' => 'Job Title [Person]', 'description' => '' ),
            array( 'key' => 'person_workPhone', 'value' => 'Work Phone [Person]', 'description' => '' ),
            array( 'key' => 'person_homePhone', 'value' => 'Home Phone [Person]', 'description' => '' ),
            array( 'key' => 'person_mobilePhone', 'value' => 'Mobile Phone [Person]', 'description' => '' ),
            array( 'key' => 'person_email', 'value' => 'Email [Person]', 'description' => '' ),
            array( 'key' => 'person_website', 'value' => 'Website [Person]', 'description' => '' ),
            array( 'key' => 'person_twitter', 'value' => 'Twitter [Person]', 'description' => '' ),
            array( 'key' => 'person_linkedin', 'value' => 'LinkedIn [Person]', 'description' => '' ),
            array( 'key' => 'person_facebook', 'value' => 'Facebook [Person]', 'description' => '' ),
            array( 'key' => 'person_youtube', 'value' => 'YouTube [Person]', 'description' => '' ),
            array( 'key' => 'person_instagram', 'value' => 'Instagram [Person]', 'description' => '' ),
            array( 'key' => 'person_addressType', 'value' => 'Address Type [Person]', 'description' => 'Home | Postal | Office | Billing | Shipping' ),
            array( 'key' => 'person_address', 'value' => 'Address [Person]', 'description' => '' ),
            array( 'key' => 'person_city', 'value' => 'City [Person]', 'description' => '' ),
            array( 'key' => 'person_state', 'value' => 'State [Person]', 'description' => '' ),
            array( 'key' => 'person_zip', 'value' => 'Zip [Person]', 'description' => '' ),
            array( 'key' => 'person_country', 'value' => 'Country [Person]', 'description' => '' ),
        );

        $final_data = array_merge( $final_data, $person_fields );
    }

    if( in_array( 'opportunity', $selected_objects ) ) {
        $milestones = adfoin_get_capsulecrm_opportunity_milestones();

        $opportunity_fields = array(
            array( 'key' => 'opportunity_name', 'value' => 'Name [Opportunity]', 'description' => 'Required if you want to create an opportunity, otherwise leave empty' ),
            array( 'key' => 'opportunity_description', 'value' => 'Description [Opportunity]', 'description' => '' ),
            array( 'key' => 'opportunity_milestone_id', 'value' => 'Milestone ID [Opportunity]', 'description' => $milestones ),
            array( 'key' => 'opportunity_currency', 'value' => 'Currency [Opportunity]', 'description' => 'e.g. USD, GBP' ),
            array( 'key' => 'opportunity_value', 'value' => 'Value [Opportunity]', 'description' => '' ),
            array( 'key' => 'opportunity_probability', 'value' => 'Probability [Opportunity]', 'description' => '' ),
            array( 'key' => 'opportunity_expectedCloseOn', 'value' => 'Expected Close Date [Opportunity]', 'description' => '' ),
            array( 'key' => 'opportunity_durationBasis', 'value' => 'Payment Terms [Opportunity]', 'description' => 'FIXED | HOUR | DAY | WEEK | MONTH | QUARTER | YEAR' ),
            array( 'key' => 'opportunity_duration', 'value' => 'Payment Duration [Opportunity]', 'description' => '' ),
        );

        $final_data = array_merge( $final_data, $opportunity_fields );
    }

    if( in_array( 'case', $selected_objects ) ) {

        $case_fields = array(
            array( 'key' => 'case_name', 'value' => 'Name [Case]', 'description' => 'Required if you want to create a case, otherwise leave empty' ),
            array( 'key' => 'case_description', 'value' => 'Description [Case]', 'description' => '' ),
            array( 'key' => 'case_expectedCloseOn', 'value' => 'Exptected Clsoe Date [Case]', 'description' => '' ),
        );

        $final_data = array_merge( $final_data, $case_fields );
    }

    if( in_array( 'task', $selected_objects ) ) {
        $task_fields = array(
            array( 'key' => 'task_description', 'value' => 'Description [Task]', 'description' => 'Required if you want to create a task, otherwise leave empty' ),
            array( 'key' => 'task_dueOn', 'value' => 'Due Date [Task]', 'description' => '' ),
            array( 'key' => 'task_dueTime', 'value' => 'Due Time [Task]', 'description' => '' ),
            array( 'key' => 'task_category', 'value' => 'Category [Task]', 'description' => '' ),
        );

        $final_data = array_merge( $final_data, $task_fields );
    }

    wp_send_json_success( $final_data );
}

add_action( 'adfoin_capsulecrm_job_queue', 'adfoin_capsulecrm_job_queue', 10, 1 );

function adfoin_capsulecrm_job_queue( $data ) {
    adfoin_capsulecrm_send_data( $data['record'], $data['posted_data'] );
}
  
/*
* Handles sending data to Capsule CRM API
*/
function adfoin_capsulecrm_send_data( $record, $posted_data ) {

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
    $person_id      = '';
    $opportunity_id = '';
    $case_id        = '';

    if( $task == "add_party" ) {

        $org_data         = array();
        $person_data      = array();
        $deal_data        = array();
        $opportunity_data = array();
        $case_data        = array();
        $task_data        = array();

        foreach( $data as $key => $value ) {
            if( substr( $key, 0, 4 ) == 'org_' && $value ) {
                $key = substr( $key, 4 );

                $org_data[$key] = $value;
            }

            if( substr( $key, 0, 7 ) == 'person_' && $value ) {
                $key = substr( $key, 7 );

                $person_data[$key] = $value;
            }

            if( substr( $key, 0, 12 ) == 'opportunity_' && $value ) {
                $key = substr( $key, 12 );

                $opportunity_data[$key] = $value;
            }

            if( substr( $key, 0, 5 ) == 'case_' && $value ) {
                $key = substr( $key, 5 );

                $case_data[$key] = $value;
            }

            if( substr( $key, 0, 5 ) == 'task_' && $value ) {
                $key = substr( $key, 5 );

                $task_data[$key] = $value;
            }
        }

        if( isset( $org_data['name'] ) && $org_data['name'] ) {
            $endpoint           = 'parties';
            $method             = 'POST';
            $org_holder         = array();
            $org_holder['type'] = 'organisation';
            $org_holder['name'] = adfoin_get_parsed_values( $org_data['name'], $posted_data );

            if( $owner ) {
                if( substr( $owner, 0, 6 ) == 'user__' ) {
                    $user_id = substr( $owner, 6 );
                    
                    $org_holder['owner'] = array(
                        'id' => (int)$user_id
                    );
                }

                if( substr( $owner, 0, 6 ) == 'team__' ) {
                    $team_id = substr( $owner, 6 );
                    
                    $org_holder['team'] = array(
                        'id' => (int)$team_id
                    );
                }
            }

            if( isset( $org_data['phone'] ) && $org_data['phone'] ) {
                $work_phone = adfoin_get_parsed_values( $org_data['phone'], $posted_data );

                if( $work_phone ) {
                    $org_holder['phoneNumbers'] = array(
                        array(
                            'type'    => 'Work',
                            'number' => $work_phone
                        )
                    );
                }
            }

            if( isset( $org_data['email'] ) && $org_data['email'] ) {
                $email_address = adfoin_get_parsed_values( $org_data['email'], $posted_data );

                if( $email_address ) {
                    $org_holder['emailAddresses'] = array(
                        array(
                        'type'    => 'Work',
                        'address' => $email_address
                        )
                    );
                }
            }

            if( isset( $org_data['website'] ) 
            || isset( $org_data['twitter'] )
            || isset( $org_data['linkedin'] )
            || isset( $org_data['facebook'] )
            || isset( $org_data['youtube'] )
            || isset( $org_data['instagram'] ) ) {
                $org_holder['websites'] = array();

                if( isset( $org_data['website'] ) && $org_data['website'] ) {
                    array_push( $org_holder['websites'], array( 'service' => 'URL', 'address' => adfoin_get_parsed_values( $org_data['website'], $posted_data ) ) );
                }

                if( isset( $org_data['twitter'] ) && $org_data['twitter'] ) {
                    array_push( $org_holder['websites'], array( 'service' => 'TWITTER', 'address' => adfoin_get_parsed_values( $org_data['twitter'], $posted_data ) ) );
                }

                if( isset( $org_data['linkedin'] ) && $org_data['linkedin'] ) {
                    array_push( $org_holder['websites'], array( 'service' => 'LINKEDIN', 'address' => adfoin_get_parsed_values( $org_data['linkedin'], $posted_data ) ) );
                }

                if( isset( $org_data['facebook'] ) && $org_data['facebook'] ) {
                    array_push( $org_holder['websites'], array( 'service' => 'FACEBOOK', 'address' => adfoin_get_parsed_values( $org_data['facebook'], $posted_data ) ) );
                }

                if( isset( $org_data['youtube'] ) && $org_data['youtube'] ) {
                    array_push( $org_holder['websites'], array( 'service' => 'YOUTUBE', 'address' => adfoin_get_parsed_values( $org_data['youtube'], $posted_data ) ) );
                }

                if( isset( $org_data['instagram'] ) && $org_data['instagram'] ) {
                    array_push( $org_holder['websites'], array( 'service' => 'INSTAGRAM', 'address' => adfoin_get_parsed_values( $org_data['instagram'], $posted_data ) ) );
                }
            }

            if( isset( $org_data['address'] ) 
            || isset( $org_data['city'] )
            || isset( $org_data['state'] )
            || isset( $org_data['zip'] )
            || isset( $org_data['country'] ) ) {
                $org_holder['addresses'] = array(
                    array(
                        'type' => 'Office'
                    )
                );

                if( isset( $org_data['address'] ) && $org_data['address'] ) {
                    $org_holder['addresses'][0]['street'] = adfoin_get_parsed_values( $org_data['address'], $posted_data );
                }

                if( isset( $org_data['city'] ) && $org_data['city'] ) {
                    $org_holder['addresses'][0]['city'] = adfoin_get_parsed_values( $org_data['city'], $posted_data );
                }

                if( isset( $org_data['state'] ) && $org_data['state'] ) {
                    $org_holder['addresses'][0]['state'] = adfoin_get_parsed_values( $org_data['state'], $posted_data );
                }

                if( isset( $org_data['zip'] ) && $org_data['zip'] ) {
                    $org_holder['addresses'][0]['zip'] = adfoin_get_parsed_values( $org_data['zip'], $posted_data );
                }

                if( isset( $org_data['country'] ) && $org_data['country'] ) {
                    $org_holder['addresses'][0]['country'] = adfoin_get_parsed_values( $org_data['country'], $posted_data );
                }
            }

            $org_id = adfoin_capsulecrm_party_exists( $org_holder['name'] );

            if( $org_id ) {
                $endpoint = "parties/{$org_id}";
                $method   = 'PUT';
            }

            $org_holder   = array_filter( $org_holder );
            $org_response = adfoin_capsulecrm_request( $endpoint, $method, array( 'party' => $org_holder ), $record );
            $org_body     = json_decode( wp_remote_retrieve_body( $org_response ), true );

            if( isset( $org_body['party'], $org_body['party']['id'] ) ) {
                $org_id = $org_body['party']['id'];
            }
        }

        if( isset( $person_data['firstName'] ) && $person_data['firstName'] ) {
            $endpoint           = 'parties';
            $method             = 'POST';
            $person_holder         = array();
            $person_holder['type'] = 'person';
            $person_holder['title'] = adfoin_get_parsed_values( $person_data['title'], $posted_data );
            $person_holder['firstName'] = adfoin_get_parsed_values( $person_data['firstName'], $posted_data );
            $person_holder['lastName'] = adfoin_get_parsed_values( $person_data['lastName'], $posted_data );
            $person_holder['jobTitle'] = adfoin_get_parsed_values( $person_data['jobTitle'], $posted_data );

            if( $owner ) {
                if( substr( $owner, 0, 6 ) == 'user__' ) {
                    $user_id = substr( $owner, 6 );
                    
                    $person_holder['owner'] = array(
                        'id' => (int)$user_id
                    );
                }

                if( substr( $owner, 0, 6 ) == 'team__' ) {
                    $team_id = substr( $owner, 6 );
                    
                    $person_holder['team'] = array(
                        'id' => (int)$team_id
                    );
                }
            }

            if( isset( $person_data['workPhone'] ) || $person_data['homePhone'] || $person_data['mobilePhone'] ) {
                $work_phone   = adfoin_get_parsed_values( $person_data['workPhone'], $posted_data );
                $home_phone   = adfoin_get_parsed_values( $person_data['homePhone'], $posted_data );
                $mobile_phone = adfoin_get_parsed_values( $person_data['mobilePhone'], $posted_data );

                if( $work_phone || $home_phone || $mobile_phone ) {
                    $person_holder['phoneNumbers'] = array();
                }

                if( $work_phone ) {
                    array_push( $person_holder['phoneNumbers'],
                        array(
                            'type'    => 'Work',
                            'number' => $work_phone
                        )
                    );
                }

                if( $home_phone ) {
                    array_push( $person_holder['phoneNumbers'],
                        array(
                            'type'    => 'Home',
                            'number' => $home_phone
                        )
                    );
                }

                if( $mobile_phone ) {
                    array_push( $person_holder['phoneNumbers'],
                        array(
                            'type'    => 'Mobile',
                            'number' => $mobile_phone
                        )
                    );
                }
            }

            if( isset( $person_data['email'] ) && $person_data['email'] ) {
                $email_address = adfoin_get_parsed_values( $person_data['email'], $posted_data );

                if( $email_address ) {
                    $person_holder['emailAddresses'] = array(
                        array(
                        'type'    => 'Work',
                        'address' => $email_address
                        )
                    );
                }
            }

            if( $org_id ) {
                $person_holder['organisation'] = array( 'id' => $org_id );
            }

            if( isset( $person_data['website'] ) 
            || isset( $person_data['twitter'] )
            || isset( $person_data['linkedin'] )
            || isset( $person_data['facebook'] )
            || isset( $person_data['youtube'] )
            || isset( $person_data['instagram'] ) ) {
                $person_holder['websites'] = array();

                if( isset( $person_data['website'] ) && $person_data['website'] ) {
                    array_push( $person_holder['websites'], array( 'service' => 'URL', 'address' => adfoin_get_parsed_values( $person_data['website'], $posted_data ) ) );
                }

                if( isset( $person_data['twitter'] ) && $person_data['twitter'] ) {
                    array_push( $person_holder['websites'], array( 'service' => 'TWITTER', 'address' => adfoin_get_parsed_values( $person_data['twitter'], $posted_data ) ) );
                }

                if( isset( $person_data['linkedin'] ) && $person_data['linkedin'] ) {
                    array_push( $person_holder['websites'], array( 'service' => 'LINKEDIN', 'address' => adfoin_get_parsed_values( $person_data['linkedin'], $posted_data ) ) );
                }

                if( isset( $person_data['facebook'] ) && $person_data['facebook'] ) {
                    array_push( $person_holder['websites'], array( 'service' => 'FACEBOOK', 'address' => adfoin_get_parsed_values( $person_data['facebook'], $posted_data ) ) );
                }

                if( isset( $person_data['youtube'] ) && $person_data['youtube'] ) {
                    array_push( $person_holder['websites'], array( 'service' => 'YOUTUBE', 'address' => adfoin_get_parsed_values( $person_data['youtube'], $posted_data ) ) );
                }

                if( isset( $person_data['instagram'] ) && $person_data['instagram'] ) {
                    array_push( $person_holder['websites'], array( 'service' => 'INSTAGRAM', 'address' => adfoin_get_parsed_values( $person_data['instagram'], $posted_data ) ) );
                }
            }

            if( isset( $person_data['address'] ) 
            || isset( $person_data['city'] )
            || isset( $person_data['state'] )
            || isset( $person_data['zip'] )
            || isset( $person_data['country'] ) ) {
                $person_holder['addresses'] = array(
                    array(
                        'type' => $person_data['addressType'] ? $person_data['addressType'] : 'Home'
                    )
                );

                if( isset( $person_data['address'] ) && $person_data['address'] ) {
                    $person_holder['addresses'][0]['street'] = adfoin_get_parsed_values( $person_data['address'], $posted_data );
                }

                if( isset( $person_data['city'] ) && $person_data['city'] ) {
                    $person_holder['addresses'][0]['city'] = adfoin_get_parsed_values( $person_data['city'], $posted_data );
                }

                if( isset( $person_data['state'] ) && $person_data['state'] ) {
                    $person_holder['addresses'][0]['state'] = adfoin_get_parsed_values( $person_data['state'], $posted_data );
                }

                if( isset( $person_data['zip'] ) && $person_data['zip'] ) {
                    $person_holder['addresses'][0]['zip'] = adfoin_get_parsed_values( $person_data['zip'], $posted_data );
                }

                if( isset( $person_data['country'] ) && $person_data['country'] ) {
                    $person_holder['addresses'][0]['country'] = adfoin_get_parsed_values( $person_data['country'], $posted_data );
                }
            }

            $person_id = adfoin_capsulecrm_party_exists( $person_holder['email'] );

            if( $person_id ) {
                $endpoint = "parties/{$person_id}";
                $method   = 'PUT';
            }

            $person_holder   = array_filter( $person_holder );
            $person_response = adfoin_capsulecrm_request( $endpoint, $method, array( 'party' => $person_holder ), $record );
            $person_body     = json_decode( wp_remote_retrieve_body( $person_response ), true );

            if( isset( $person_body['party'], $person_body['party']['id'] ) ) {
                $person_id = $person_body['party']['id'];
            }
        }

        if( isset( $opportunity_data['name'] ) && $opportunity_data['name'] ) {
            $endpoint                          = 'opportunities';
            $method                            = 'POST';
            $opportunity_holder                = array();
            $opportunity_holder['name']        = adfoin_get_parsed_values( $opportunity_data['name'], $posted_data );
            $opportunity_holder['description'] = adfoin_get_parsed_values( $opportunity_data['description'], $posted_data );

            if( $opportunity_data['milestone_id'] && $opportunity_data['milestone_id'] ) {
                $opportunity_holder['milestone'] = array(
                    'id' => (int)$opportunity_data['milestone_id']
                );
            }

            if( $owner ) {
                if( substr( $owner, 0, 6 ) == 'user__' ) {
                    $user_id = substr( $owner, 6 );
                    
                    $opportunity_holder['owner'] = array(
                        'id' => (int)$user_id
                    );
                }

                if( substr( $owner, 0, 6 ) == 'team__' ) {
                    $team_id = substr( $owner, 6 );
                    
                    $opportunity_holder['team'] = array(
                        'id' => (int)$team_id
                    );
                }
            }

            if( $org_id ) {
                $opportunity_holder['party'] = array( 'id' => $org_id );
            }

            if( $person_id ) {
                $opportunity_holder['party'] = array( 'id' => $person_id );
            }

            if( isset( $opportunity_data['value'] ) && $opportunity_data['value'] ) {
                $opportunity_holder['value'] = array(
                    'amount' => (float)$opportunity_data['value']
                );

                if( isset( $opportunity_data['currency'] ) && $opportunity_data['currency'] ) {
                    $opportunity_holder['value']['currency'] = $opportunity_data['currency'];
                }
            }

            if( isset( $opportunity_data['probability'] ) && $opportunity_data['probability'] ) {
                $opportunity_holder['probability'] = adfoin_get_parsed_values( $opportunity_data['probability'], $posted_data );
            }

            if( isset( $opportunity_data['expectedCloseOn'] ) && $opportunity_data['expectedCloseOn'] ) {
                $opportunity_holder['expectedCloseOn'] = adfoin_get_parsed_values( $opportunity_data['expectedCloseOn'], $posted_data );
            }

            if( isset( $opportunity_data['durationBasis'] ) && $opportunity_data['durationBasis'] ) {
                $opportunity_holder['durationBasis'] = adfoin_get_parsed_values( $opportunity_data['durationBasis'], $posted_data );
            }

            if( isset( $opportunity_data['duration'] ) && $opportunity_data['duration'] ) {
                $opportunity_holder['duration'] = adfoin_get_parsed_values( $opportunity_data['duration'], $posted_data );

                if( isset( $opportunity_holder['durationBasis'] ) && 'FIXED' == $opportunity_holder['durationBasis'] ) {
                    $opportunity_holder['duration'] = null;
                }
            }

            $opportunity_holder   = array_filter( $opportunity_holder );
            $opportunity_response = adfoin_capsulecrm_request( $endpoint, $method, array( 'opportunity' => $opportunity_holder ), $record );
            $opportunity_body     = json_decode( wp_remote_retrieve_body( $opportunity_response ), true );

            if( isset( $opportunity_body['opportunity'], $opportunity_body['opportunity']['id'] ) ) {
                $opportunity_id = $opportunity_body['opportunity']['id'];
            }
        }

        if( isset( $case_data['name'] ) && $case_data['name'] ) {
            $endpoint                   = 'kases';
            $method                     = 'POST';
            $case_holder                = array();
            $case_holder['name']        = adfoin_get_parsed_values( $case_data['name'], $posted_data );
            $case_holder['description'] = adfoin_get_parsed_values( $case_data['description'], $posted_data );

            if( $owner ) {
                if( substr( $owner, 0, 6 ) == 'user__' ) {
                    $user_id = substr( $owner, 6 );
                    
                    $case_holder['owner'] = array(
                        'id' => (int)$user_id
                    );
                }

                if( substr( $owner, 0, 6 ) == 'team__' ) {
                    $team_id = substr( $owner, 6 );
                    
                    $case_holder['team'] = array(
                        'id' => (int)$team_id
                    );
                }
            }

            if( $org_id ) {
                $case_holder['party'] = array( 'id' => $org_id );
            }

            if( $person_id ) {
                $case_holder['party'] = array( 'id' => $person_id );
            }

            if( $opportunity_id ) {
                $case_holder['opportunity'] = array( 'id' => $opportunity_id );
            }

            if( isset( $case_data['expectedCloseOn'] ) && $case_data['expectedCloseOn'] ) {
                $case_holder['expectedCloseOn'] = adfoin_get_parsed_values( $case_data['expectedCloseOn'], $posted_data );
            }

            $case_holder   = array_filter( $case_holder );
            $case_response = adfoin_capsulecrm_request( $endpoint, $method, array( 'kase' => $case_holder ), $record );
            $case_body     = json_decode( wp_remote_retrieve_body( $case_response ), true );

            if( isset( $case_body['kase'], $case_body['kase']['id'] ) ) {
                $case_id = $case_body['kase']['id'];
            }
        }

        if( isset( $task_data['description'] ) && $task_data['description'] ) {
            $endpoint                   = 'tasks';
            $method                     = 'POST';
            $task_holder                = array();
            $task_holder['description'] = adfoin_get_parsed_values( $task_data['description'], $posted_data );

            if( $owner ) {
                if( substr( $owner, 0, 6 ) == 'user__' ) {
                    $user_id = substr( $owner, 6 );
                    
                    $task_holder['owner'] = array(
                        'id' => (int)$user_id
                    );
                }

                if( substr( $owner, 0, 6 ) == 'team__' ) {
                    $team_id = substr( $owner, 6 );
                    
                    $task_holder['team'] = array(
                        'id' => (int)$team_id
                    );
                }
            }

            if( $org_id ) {
                $task_holder['party'] = array( 'id' => $org_id );
            }

            if( $person_id ) {
                $task_holder['party'] = array( 'id' => $person_id );
            }

            if( isset( $task_data['dueOn'] ) && $task_data['dueOn'] ) {
                $task_holder['dueOn'] = adfoin_get_parsed_values( $task_data['dueOn'], $posted_data );
            }

            if( isset( $task_data['dueTime'] ) && $task_data['dueTime'] ) {
                $task_holder['dueTime'] = adfoin_get_parsed_values( $task_data['dueTime'], $posted_data );
            }

            if( isset( $task_data['category'] ) && $task_data['category'] ) {
                $task_holder['category'] = array(
                    'name' => $task_data['category']
                );
            }

            $task_holder   = array_filter( $task_holder );
            $task_response = adfoin_capsulecrm_request( $endpoint, $method, array( 'task' => $task_holder ), $record );
            $task_body     = json_decode( wp_remote_retrieve_body( $task_response ), true );

            if( isset( $task_body['kase'], $task_body['kase']['id'] ) ) {
                $task_id = $task_body['kase']['id'];
            }
        }
    }

    return;
}
 
/*
* Checks if Party exists
* @returns: Party ID if exists
*/

function adfoin_capsulecrm_party_exists( $name ) {
 
    $endpoint = 'parties/search';

    $query_args = array(
        'q' => $name
    );

    $endpoint      = add_query_arg( $query_args, $endpoint );
    $response      = adfoin_capsulecrm_request( $endpoint, 'GET' );
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