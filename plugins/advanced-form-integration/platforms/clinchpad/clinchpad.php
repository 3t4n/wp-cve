<?php

add_filter( 'adfoin_action_providers', 'adfoin_clinchpad_actions', 10, 1 );

function adfoin_clinchpad_actions( $actions ) {

    $actions['clinchpad'] = array(
        'title' => __( 'ClinchPad', 'advanced-form-integration' ),
        'tasks' => array(
            'add_contact'   => __( 'Create New Contact', 'advanced-form-integration' )
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_clinchpad_settings_tab', 10, 1 );

function adfoin_clinchpad_settings_tab( $providers ) {
    $providers['clinchpad'] = __( 'ClinchPad', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_clinchpad_settings_view', 10, 1 );

function adfoin_clinchpad_settings_view( $current_tab ) {
    if( $current_tab != 'clinchpad' ) {
        return;
    }

    $nonce     = wp_create_nonce( 'adfoin_clinchpad_settings' );
    $api_token = get_option( 'adfoin_clinchpad_api_token' ) ? get_option( 'adfoin_clinchpad_api_token' ) : '';
    ?>

    <form name="clinchpad_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_clinchpad_api_token">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Key', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_clinchpad_api_token"
                           value="<?php echo esc_attr( $api_token ); ?>" placeholder="<?php _e( 'Please enter API Token', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Please go to Settings > API to get API Key', 'advanced-form-integration' ); ?></a></p>
                </td>

            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_save_clinchpad_api_token', 'adfoin_save_clinchpad_api_token', 10, 0 );

function adfoin_save_clinchpad_api_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_clinchpad_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_token = sanitize_text_field( $_POST['adfoin_clinchpad_api_token'] );

    // Save tokens
    update_option( 'adfoin_clinchpad_api_token', $api_token );

    advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration-settings&tab=clinchpad' );
}

add_action( 'adfoin_add_js_fields', 'adfoin_clinchpad_js_fields', 10, 1 );

function adfoin_clinchpad_js_fields( $field_data ) {}

add_action( 'adfoin_action_fields', 'adfoin_clinchpad_action_fields' );

function adfoin_clinchpad_action_fields() {
    ?>
    <script type="text/template" id="clinchpad-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_contact'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_contact'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( ' Select User', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[userId]" v-model="fielddata.userId" required="required">
                        <option value=""> <?php _e( 'Select User...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.userList" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': userLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_contact'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( ' Select Pipeline', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[pipelineId]" v-model="fielddata.pipelineId" @change="getStage"  required="required">
                        <option value=""> <?php _e( 'Select Pipeline...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.pipelineList" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': pipelineLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_contact'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Select Stage', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <select name="fieldData[stageId]" v-model="fielddata.stageId" required="required">
                            <option value=""> <?php _e( 'Select Stage...', 'advanced-form-integration' ); ?> </option>
                            <option v-for="(item, index) in fielddata.stages" :value="index" > {{item}}  </option>
                        </select>
                        <div class="spinner" v-bind:class="{'is-active': stageLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>
    <?php
}

function adfoin_clinchpad_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {

    $api_token = get_option( 'adfoin_clinchpad_api_token' ) ? get_option( 'adfoin_clinchpad_api_token' ) : '';
    $base_url  = 'https://www.clinchpad.com/api/v1/';
    $url       = $base_url . $endpoint;

    $args = array(
        'timeout' => 30,
        'method'  => $method,
        'headers' => array(
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json; charset=utf-8',
            'Authorization' => 'Basic ' . base64_encode( 'api-key' . ':' . $api_token ),
        ),
    );

    if ('POST' == $method || 'PUT' == $method) {
        if( $data ) {
            $args['body'] = json_encode( $data );
        }
        
    }

    $response = wp_remote_request($url, $args);

    if ($record) {
        adfoin_add_to_log($response, $url, $args, $record);
    }

    return $response;
}

add_action( 'wp_ajax_adfoin_get_clinchpad_user', 'adfoin_get_clinchpad_user' );

function adfoin_get_clinchpad_user(){

      // Security Check
      if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $users    = array();
    $endpoint = 'users';
    $data     = adfoin_clinchpad_request( $endpoint );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body = json_decode( wp_remote_retrieve_body( $data ), true );

    if ( !empty( $body ) ) {
        $users = wp_list_pluck( $body, 'name', '_id' );
    }

    wp_send_json_success( $users );

}

add_action( 'wp_ajax_adfoin_get_clinchpad_pipeline', 'adfoin_get_clinchpad_pipeline' );

function adfoin_get_clinchpad_pipeline(){

      // Security Check
      if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $pipeline = array();
    $endpoint = 'pipelines';
    $data     = adfoin_clinchpad_request($endpoint);

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body = json_decode( wp_remote_retrieve_body( $data ), true );

    if ( !empty( $body ) ) {
        $pipeline = wp_list_pluck( $body, 'name', '_id' );
    }

    wp_send_json_success( $pipeline );

}

add_action( 'wp_ajax_adfoin_get_clinchpad_stage', 'adfoin_get_clinchpad_stage' );

function adfoin_get_clinchpad_stage(){

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
      die( __( 'Security check Failed', 'advanced-form-integration' ) );
  }

  $pipeline_id = isset( $_POST['pipelineId'] ) ? $_POST['pipelineId'] : '';
  $stage       = array();
  $endpoint    = "pipelines/{$pipeline_id}/stages";
  $data        = adfoin_clinchpad_request($endpoint);

  if( is_wp_error( $data ) ) {
      wp_send_json_error();
  }

  $body = json_decode( wp_remote_retrieve_body( $data ), true );

  if ( !empty( $body ) ) {
      $stage = wp_list_pluck( $body, 'name', '_id' );
  }

  wp_send_json_success( $stage );

}

function adfoin_create_lead( $lead, $record ){
    $endpoint = 'leads';
    $response = adfoin_clinchpad_request( $endpoint, 'POST', $lead, $record );

    if( is_wp_error( $response ) ) {
        wp_send_json_error();
    }
  
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
  
    if ( isset( $body['_id'] ) ) {
        $lead_id = $body['_id'];
    }
    return $lead_id;

}

function adfoin_clinchpad_create_contact( $contact, $record ){

    $endpoint = 'contacts';
    $response = adfoin_clinchpad_request( $endpoint, 'POST', $contact, $record );

    if( is_wp_error( $response ) ) {
        wp_send_json_error();
    }
  
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
  
    if ( isset( $body['_id'] ) ) {
        $contact_id = $body['_id'];
    }
    return $contact_id;

} 

function adfoin_clinchpad_create_organization( $organization, $record ){

    $endpoint = 'organizations';
    $response = adfoin_clinchpad_request( $endpoint, 'POST', $organization, $record );

    if( is_wp_error( $response ) ) {
        wp_send_json_error();
    }
  
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
  
    if ( isset( $body['_id'] ) ) {
        $org_id = $body['_id'];
    }
    return $org_id;
} 

function adfoin_clinchpad_create_product( $product, $record ){
    $endpoint = 'products';
    $response = adfoin_clinchpad_request( $endpoint, 'POST', $product, $record );

    if( is_wp_error( $response ) ) {
        wp_send_json_error();
    }
  
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
  
    if ( isset( $body['_id'] ) ) {
        $product_id = $body['_id'];
    }
    return $product_id;

}

function adfoin_clinchpad_create_note( $endpoint, $note_data, $record ){
    $response = adfoin_clinchpad_request( $endpoint, 'POST', $note_data, $record );

    if( is_wp_error( $response ) ) {
        wp_send_json_error();
    }
  
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
  
    if ( isset( $body['_id'] ) ) {
        $source_id = $body['_id'];
    }
    return $source_id;

}

function adfoin_clinchpad_create_lead( $lead, $record ){
    $endpoint = 'leads';
    $response = adfoin_clinchpad_request( $endpoint, 'POST', $lead, $record );

    if( is_wp_error( $response ) ) {
        wp_send_json_error();
    }
  
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
  
    if ( isset( $body['_id'] ) ) {
        $lead_id = $body['_id'];
    }

    return $lead_id;

}

function adfoin_clinchpad_add_stage_to_lead( $lead_id, $stage, $record ){
    $endpoint = "leads/{$lead_id}";

    $response = adfoin_clinchpad_request( $endpoint, 'PUT', $stage, $record );

    if( is_wp_error( $response ) ) {
        wp_send_json_error();
    }
  
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
  
    if ( isset( $body['_id'] ) ) {
        $id = $body['_id'];
    }
    return ;

}

add_action( 'adfoin_clinchpad_job_queue', 'adfoin_clinchpad_job_queue', 10, 1 );

function adfoin_clinchpad_job_queue( $data ) {
    adfoin_clinchpad_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to ClinchPad API
 */
function adfoin_clinchpad_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record['data'], true );

    if( array_key_exists( 'cl', $record_data['action_data']) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data        = $record_data['field_data'];
    $pipeline_id = $data['pipelineId'];
    $stage_id    = $data['stageId'];
    $user        = $data['userId'];
    $task        = $record['task'];
    $lead_id     = '';
    $contact_id  = '';
    $org_id      = '';
    $product_id  = '';

    if( $task == 'add_contact' ) {
        $lead_name     = empty( $data['lead'] ) ? '' : adfoin_get_parsed_values( $data['lead'], $posted_data );
        $value         = empty( $data['value'] ) ? '' : adfoin_get_parsed_values( $data['value'], $posted_data );
        $note          = empty( $data['note'] ) ? '' : adfoin_get_parsed_values( $data['note'], $posted_data );
        $email         = empty( $data['email'] ) ? '' : trim( adfoin_get_parsed_values( $data['email'], $posted_data ) );
        $name          = empty( $data['name'] ) ? '' : adfoin_get_parsed_values( $data['name'], $posted_data );
        $designation   = empty( $data['designation'] ) ? '' : adfoin_get_parsed_values( $data['designation'], $posted_data );
        $phone         = empty( $data['phone'] ) ? '' : adfoin_get_parsed_values( $data['phone'], $posted_data );
        $address       = empty( $data['address'] ) ? '' : adfoin_get_parsed_values( $data['address'], $posted_data );
        $organization  = empty( $data['organization'] ) ? '' : adfoin_get_parsed_values( $data['organization'], $posted_data );
        $org_email     = empty( $data['org_email'] ) ? '' : adfoin_get_parsed_values( $data['org_email'], $posted_data );
        $org_phone     = empty( $data['org_phone'] ) ? '' : adfoin_get_parsed_values( $data['org_phone'], $posted_data );
        $website       = empty( $data['website'] ) ? '' : adfoin_get_parsed_values( $data['website'], $posted_data );
        $org_address   = empty( $data['org_address'] ) ? '' : adfoin_get_parsed_values( $data['org_address'], $posted_data );
        $product_name  = empty( $data['product_name'] ) ? '' : adfoin_get_parsed_values( $data['product_name'], $posted_data );
        $product_price = empty( $data['product_price'] ) ? '' : adfoin_get_parsed_values( $data['product_price'], $posted_data );

        if( $lead_name ){
            $lead_data = array(
                'name'        => $lead_name,
                'pipeline_id' => $pipeline_id,
                'size'        => $value,
            );

            if( $user ) {
                $lead_data['user_id'] = $user;
            }

            $lead_id = adfoin_clinchpad_create_lead( array_filter( $lead_data ), $record );

            if( $lead_id && $stage_id ){
                $stage_data = array(
                    'lead_id'  => $lead_id,
                    'stage_id' => $stage_id
                );

                adfoin_clinchpad_add_stage_to_lead( $lead_id, $stage_data, $record );
            }

        }

        if ( $organization ){
            $org_data = array(
                'name'        => $organization,
                'email'       => $org_email,
                'phone'       => $org_phone,
                'address'     => $org_address,
                'website'     => $website
            );
    
            $org_id = adfoin_clinchpad_create_organization( array_filter( $org_data ), $record );
        }
        
        if ( $name ){
            $contact_data = array(
                'name'            => $name,
                'email'           => $email,
                'designation'     => $designation,
                'phone'           => $phone,
                'address'         => $address
            );

            if( $org_id ) {
                $contact_data['organization_id'] = $org_id;
            }

            $contact_id = adfoin_clinchpad_create_contact( array_filter( $contact_data ), $record );

            if( $lead_id && $contact_id ){
                $endpoint = "leads/{$lead_id}/contacts/{$contact_id}";
                $cont_response = adfoin_clinchpad_request( $endpoint, 'PUT', array(), $record);
            }
        }

        if ( $product_name ){
            $product = array(
                'name'  => $product_name,
                'price' => $product_price,
                
            );

            $product_id = adfoin_clinchpad_create_product( array_filter( $product ), $record );

            if( $lead_id && $product_id ){
                $add_product = array(
                    'quantity' => 1,
                    'discount' => '0'
                );
                $endpoint = "leads/{$lead_id}/products/{$product_id}";
                $prod_response = adfoin_clinchpad_request( $endpoint, 'PUT', $add_product, $record);
            }

        }

        if ( $note ){
            $note_data = array(
                'content' => $note
            );

            if( $user ) {
                $note_data['user_id'] = $user;
            }    

            if( $lead_id ) {
                $endpoint = "leads/{$lead_id}/notes";
                $note_id = adfoin_clinchpad_create_note( $endpoint, $note_data, $record );
            }
        }
    }

    return;
}