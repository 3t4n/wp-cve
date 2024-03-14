<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_mautic_actions',
    10,
    1
);
function adfoin_mautic_actions( $actions )
{
    $actions['mautic'] = array(
        'title' => __( 'Mautic', 'advanced-form-integration' ),
        'tasks' => array(
        'add_contact' => __( 'Add or Update Contact', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_mautic_settings_tab',
    10,
    1
);
function adfoin_mautic_settings_tab( $providers )
{
    $providers['mautic'] = __( 'Mautic', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_mautic_settings_view',
    10,
    1
);
function adfoin_mautic_get_credentials()
{
    $credentials = maybe_unserialize( get_option( 'adfoin_mautic_api_key' ) );
    return $credentials;
}

function adfoin_mautic_settings_view( $current_tab )
{
    if ( $current_tab != 'mautic' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_mautic_settings" );
    $credentials = adfoin_mautic_get_credentials();
    ?>

    <form name="mautic_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_mautic_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
        <tr valign="top">
                <th scope="row"> <?php 
    _e( 'Instructions', 'advanced-form-integration' );
    ?></th>
                <td>
                    <p class="description" id="code-description">Go to settings > Configuration > API Settings and enable both API and basic auth. Hit Save button.</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'Mautic Account URL', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_mautic_url"
                           value="<?php 
    echo  esc_attr( $credentials['url'] ) ;
    ?>" placeholder="<?php 
    _e( 'Enter account URL', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description">Enter full Mautic accout URL, e.g. http://email.example.com</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'Username', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_mautic_username"
                           value="<?php 
    echo  esc_attr( $credentials['username'] ) ;
    ?>" placeholder="<?php 
    _e( 'Username', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                           <p class="description" id="code-description">Enter Mautic account username.</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'Password', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_mautic_password"
                           value="<?php 
    echo  esc_attr( $credentials['password'] ) ;
    ?>" placeholder="<?php 
    _e( 'Password', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                           <p class="description" id="code-description">Enter Mautic account password.</p>
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
    'admin_post_adfoin_save_mautic_api_key',
    'adfoin_save_mautic_api_key',
    10,
    0
);
function adfoin_save_mautic_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_mautic_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $url = ( isset( $_POST['adfoin_mautic_url'] ) ? sanitize_text_field( $_POST['adfoin_mautic_url'] ) : '' );
    $username = ( isset( $_POST['adfoin_mautic_username'] ) ? sanitize_text_field( $_POST['adfoin_mautic_username'] ) : '' );
    $password = ( isset( $_POST['adfoin_mautic_password'] ) ? sanitize_text_field( $_POST['adfoin_mautic_password'] ) : '' );
    $credentials = array(
        'url'      => $url,
        'username' => $username,
        'password' => $password,
    );
    // Save tokens
    update_option( "adfoin_mautic_api_key", maybe_serialize( $credentials ) );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=mautic" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_mautic_js_fields',
    10,
    1
);
function adfoin_mautic_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_mautic_action_fields' );
function adfoin_mautic_action_fields()
{
    ?>
    <script type="text/template" id="mautic-action-template">
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
        printf( __( 'To unlock custom fields consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
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
    'adfoin_mautic_job_queue',
    'adfoin_mautic_job_queue',
    10,
    1
);
function adfoin_mautic_job_queue( $data )
{
    adfoin_mautic_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Mautic API
 */
function adfoin_mautic_send_data( $record, $posted_data )
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
    
    if ( $task == 'add_contact' ) {
        $email = ( empty($data['email']) ? '' : adfoin_get_parsed_values( $data['email'], $posted_data ) );
        $firstname = ( empty($data['firstname']) ? '' : adfoin_get_parsed_values( $data['firstname'], $posted_data ) );
        $lastname = ( empty($data['lastname']) ? '' : adfoin_get_parsed_values( $data['lastname'], $posted_data ) );
        $title = ( empty($data['title']) ? '' : adfoin_get_parsed_values( $data['title'], $posted_data ) );
        $mobile = ( empty($data['mobile']) ? '' : adfoin_get_parsed_values( $data['mobile'], $posted_data ) );
        $phone = ( empty($data['phone']) ? '' : adfoin_get_parsed_values( $data['phone'], $posted_data ) );
        $fax = ( empty($data['fax']) ? '' : adfoin_get_parsed_values( $data['fax'], $posted_data ) );
        $company = ( empty($data['company']) ? '' : adfoin_get_parsed_values( $data['company'], $posted_data ) );
        $position = ( empty($data['position']) ? '' : adfoin_get_parsed_values( $data['position'], $posted_data ) );
        $address1 = ( empty($data['address1']) ? '' : adfoin_get_parsed_values( $data['address1'], $posted_data ) );
        $address2 = ( empty($data['address2']) ? '' : adfoin_get_parsed_values( $data['address2'], $posted_data ) );
        $city = ( empty($data['city']) ? '' : adfoin_get_parsed_values( $data['city'], $posted_data ) );
        $state = ( empty($data['state']) ? '' : adfoin_get_parsed_values( $data['state'], $posted_data ) );
        $zipcode = ( empty($data['zipcode']) ? '' : adfoin_get_parsed_values( $data['zipcode'], $posted_data ) );
        $country = ( empty($data['country']) ? '' : adfoin_get_parsed_values( $data['country'], $posted_data ) );
        $website = ( empty($data['website']) ? '' : adfoin_get_parsed_values( $data['website'], $posted_data ) );
        $facebook = ( empty($data['facebook']) ? '' : adfoin_get_parsed_values( $data['facebook'], $posted_data ) );
        $instagram = ( empty($data['instagram']) ? '' : adfoin_get_parsed_values( $data['instagram'], $posted_data ) );
        $linkedin = ( empty($data['linkedin']) ? '' : adfoin_get_parsed_values( $data['linkedin'], $posted_data ) );
        $twitter = ( empty($data['twitter']) ? '' : adfoin_get_parsed_values( $data['twitter'], $posted_data ) );
        $data = array(
            'email'     => trim( $email ),
            'firstname' => $firstname,
            'lastname'  => $lastname,
            'title'     => $title,
            'mobile'    => $mobile,
            'phone'     => $phone,
            'fax'       => $fax,
            'company'   => $company,
            'postion'   => $position,
            'address1'  => $address1,
            'address2'  => $address2,
            'city'      => $city,
            'state'     => $state,
            'zipcode'   => $zipcode,
            'country'   => $country,
            'website'   => $website,
            'facebook'  => $facebook,
            'instagram' => $instagram,
            'linkedin'  => $linkedin,
            'twitter'   => $twitter,
        );
        $data = array_filter( $data );
        $return = adfoin_mautic_request(
            '/api/contacts/new',
            'POST',
            $data,
            $record
        );
    }
    
    return;
}

function adfoin_mautic_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $credentials = adfoin_mautic_get_credentials();
    $base_url = ( isset( $credentials['url'] ) ? $credentials['url'] : '' );
    $username = ( isset( $credentials['username'] ) ? $credentials['username'] : '' );
    $password = ( isset( $credentials['password'] ) ? $credentials['password'] : '' );
    $url = $base_url . $endpoint;
    $args = array(
        'method'  => $method,
        'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password ),
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
