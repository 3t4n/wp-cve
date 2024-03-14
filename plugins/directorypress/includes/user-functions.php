<?php

add_filter('manage_users_columns', 'directorypress_user_columns_head');
add_action('manage_users_custom_column', 'directorypress_user_columns_content', 15, 3);
add_action('wp_dashboard_setup', 'directorypress_activeusers_metabox');
add_action('init', 'directorypress_users_status_init');
add_action('admin_init', 'directorypress_users_status_init');
add_action('user_register', 'directorypress_user_meta');   

function directorypress_users_status_init(){
	$logged_in_users = get_transient('users_status'); 
	$user = wp_get_current_user();
	if ( !isset($logged_in_users[$user->ID]['last']) || $logged_in_users[$user->ID]['last'] <= time()-900 ){
		$logged_in_users[$user->ID] = array(
			'id' => $user->ID,
			'username' => $user->user_login,
			'last' => time(),
		);
		set_transient('users_status', $logged_in_users, 900);
	}
}

function directorypress_is_user_online($id){	
	$logged_in_users = get_transient('users_status');
	
	return isset($logged_in_users[$id]['last']) && $logged_in_users[$id]['last'] > time()-900;
}

function directorypress_user_last_online($id){
	$logged_in_users = get_transient('users_status');
	if ( isset($logged_in_users[$id]['last']) ){
		return $logged_in_users[$id]['last'];
	} else {
		return false;
	}
}

function directorypress_user_columns_head($defaults){
    $defaults['status'] = 'Status';
    return $defaults;
}

function directorypress_user_columns_content($value, $column_name, $id){
    if ( $column_name == 'status' ){
		if ( directorypress_is_user_online($id) ){
			return '<strong style="color: green;">Online Now</strong>';
		} else {
			return ( directorypress_user_last_online($id) )? '<small>Last Seen: <br /><em>' . date('M j, Y @ g:ia', directorypress_user_last_online($id)) . '</em></small>' : ''; //Return the user's "Last Seen" date, or return empty if that user has never logged in.
		}
	}
}

function directorypress_activeusers_metabox(){
	global $wp_meta_boxes;
	wp_add_dashboard_widget('directorypress_activeusers', 'Active Users', 'directorypress_dashboard_activeusers');
}

function directorypress_dashboard_activeusers(){
		$user_count = count_users();
		$users_plural = ( $user_count['total_users'] == 1 )? 'User' : 'Users'; //Determine singular/plural tense
		echo '<div><a href="users.php">' . esc_html($user_count['total_users']) . ' ' . esc_html($users_plural) . '</a> <small>(' . esc_attr(directorypress_online_users('count')) . ' currently active)</small></div>';
}

function directorypress_online_users($return='count'){
	$logged_in_users = get_transient('users_status');
	
	
	if ( empty($logged_in_users) ){
		return ( $return == 'count' )? 0 : false; 
	}
	
	$user_online_count = 0;
	$online_users = array();
	foreach ( $logged_in_users as $user ){
		if ( !empty($user['username']) && isset($user['last']) && $user['last'] > time()-900 ){ 
			$online_users[] = $user;
			$user_online_count++;
		}
	}
	return ( $return == 'count' )? $user_online_count : $online_users; //Return either an integer count, or an array of all online user data.

}
  
function directorypress_user_meta( $user_id ) {    
         //  update_user_meta( $user_id, 'user_phone', $_POST['user_phone'] ); 
}

function directorypress_login_form($args = array()) {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$defaults = array(
			'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // Default redirect is back to the current page
			'form_id' => 'loginform',
			'label_username' => __( 'Username', 'DIRECTORYPRESS' ),
			'label_password' => __( 'Password', 'DIRECTORYPRESS' ),
			'label_remember' => __( 'Remember Me', 'DIRECTORYPRESS' ),
			'label_log_in' => __( 'Login', 'DIRECTORYPRESS' ),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'remember' => true,
			'value_username' => '',
			'value_remember' => false, // Set this to true to default the "Remember me" checkbox to checked
	);
	$args = wp_parse_args($args, apply_filters( 'login_form_defaults', $defaults));
	
	echo '<div class="directorypress-default-login-form">';
	
	echo '<form name="' . esc_attr($args['form_id']) . '" id="' . esc_attr($args['form_id']) . '" action="' . esc_url( site_url( 'wp-login.php', 'login_post' ) ) . '" method="post" class="directorypress_login_form" role="form">
			' . apply_filters( 'login_form_top', '', $args ) . '
			<p class="form-group">
				<input type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="form-control" value="' . esc_attr( $args['value_username'] ) . '" placeholder="' . esc_html( $args['label_username'] ) . '" />
				<i class="fas fa-user"></i>
			</p>
			<p class="form-group login-password">
				<input type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="form-control" value="" placeholder="' . esc_html( $args['label_password'] ) . '" />
				<i class="fas fa-lock"></i>
			</p>
			' . apply_filters( 'login_form_middle', '', $args ) . '
			' . ( $args['remember'] ? '<p class="checkbox"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></p>' : '' ) . '
			<p class="login-submit">
				<a id="' . esc_attr( $args['id_submit'] ) . '" class="btn directorypress-login-button" href="#">' . esc_html( $args['label_log_in'] ) . '</a>
				<input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
			</p>
			' . apply_filters( 'login_form_bottom', '', $args ) . '
			'. wp_nonce_field('directorypress_login_request', 'directorypress_login_request') .'
		</form>';
	echo '<p id="nav">';
	if (get_option('users_can_register')){
		$url = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_registration_page']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_registration_page'])? get_permalink($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_registration_page']): wp_registration_url();
		echo '<a href="' . esc_url( $url ) . '" rel="nofollow">' . __('Register', 'DIRECTORYPRESS') . '</a> | ';
	}
	$reset_url = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_password_reset_page']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_password_reset_page'])? get_permalink($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_password_reset_page']): wp_lostpassword_url();
	echo '<a title="' . esc_attr__('Password Lost and Found', 'DIRECTORYPRESS') . '" href="' . esc_url( $reset_url ) . '">' . __('Reset your password?', 'DIRECTORYPRESS') . '</a>';
	echo '</p>';
	echo '<div class="directorypress-login-response"></div>';
	echo '</div>';
}
function directorypress_ajax_login($args = array()) {
	global $current_user;
	$response = array();
	//Validations        
    $do_check = check_ajax_referer('directorypress_login_request', 'directorypress_login_request', false);
    if ($do_check == false) {
        $response['type'] = 'error';
        $response['message'] = esc_html__('Security token validation failed!', 'DIRECTORYPRESS');
        wp_send_json($response);            
    }
	
	$info = array();
    $info['user_login'] = sanitize_text_field($_POST['log']);
    $info['user_password'] = sanitize_text_field($_POST['pwd']);
    $info['remember'] = sanitize_text_field($_POST['rememberme']);

	$login_request = wp_signon( $info, false );
    if ( is_wp_error($login_request) ){
		$response['type'] = 'error';
        $response['message'] = $login_request->get_error_message();
    } else {
		$response['type'] = 'success';
		$response['message'] = esc_html__('login successful!', 'DIRECTORYPRESS');
		if(isset($_POST['redirect_to'])){	
			$response['redirect_to'] = sanitize_url($_POST['redirect_to']);
		}else{
			$response['redirect_to'] = home_url('/');
		}
	}
	
	wp_send_json($response); 
}
add_action('wp_ajax_directorypress_ajax_login', 'directorypress_ajax_login');
add_action('wp_ajax_nopriv_directorypress_ajax_login', 'directorypress_ajax_login');

function directorypress_registration_function() {
    $username = $password = $email = $website = $first_name = $last_name = $nickname = $bio = '';
	if (isset($_POST['submit'])) {
        directorypress_registration_validation(
        $_POST['username'],
        $_POST['password'],
        $_POST['email'],
        $_POST['website'],
        $_POST['fname'],
        $_POST['lname'],
        $_POST['nickname'],
        $_POST['bio']
		);
		
		// sanitize user form input
        global $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
        $username	= 	sanitize_user($_POST['username']);
        $password 	= 	sanitize_text_field($_POST['password']);
        $email 		= 	sanitize_email($_POST['email']);
        $website 	= 	sanitize_url($_POST['website']);
        $first_name = 	sanitize_text_field($_POST['fname']);
        $last_name 	= 	sanitize_text_field($_POST['lname']);
        $nickname 	= 	sanitize_text_field($_POST['nickname']);
        $bio 		= 	sanitize_textarea_field($_POST['bio']);

		// call @function complete_registration to create the user
		// only when no WP_error is found
        directorypress_complete_registration(
        $username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio
		);
    }

    directorypress_registration_form(
    	$username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio
		);
}

function directorypress_registration_form( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio ) {

   
	echo '<div class="directorypress-default-registration-form">';
		echo '<h4>'. esc_html__('Register', 'DIRECTORYPRESS') .'</h4>';
		echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">';
			echo '<p class="form-group">';
				echo '<input class="form-control" placeholder="'. esc_attr__('Username', 'DIRECTORYPRESS') .'" type="text" name="username" value="' . (isset($_POST['username']) ? $username : '') . '">';
			echo '</p>';
			echo '<p class="form-group">';
				echo '<input class="form-control" placeholder="'. esc_attr__('Email', 'DIRECTORYPRESS') .'" type="text" name="email" value="' . (isset($_POST['email']) ? $email : '') . '">';
			echo '</p>';
			echo '<p class="form-group">';
				echo '<input class="form-control" placeholder="'. esc_attr__('First Name', 'DIRECTORYPRESS') .'" type="text" name="fname" value="' . (isset($_POST['fname']) ? $first_name : '') . '">';
			echo '</p>';
			echo '<p class="form-group">';
				echo '<input class="form-control" placeholder="'. esc_attr__('Last Name', 'DIRECTORYPRESS') .'" type="text" name="lname" value="' . (isset($_POST['lname']) ? $last_name : '') . '">';
			echo '</p>';
			echo '<p class="form-group">';
				echo '<input class="form-control" placeholder="'. esc_attr__('Nickname', 'DIRECTORYPRESS') .'" type="text" name="nickname" value="' . (isset($_POST['nickname']) ? $nickname : '') . '">';
			echo '</p>';
			echo '<p class="form-group">';
				echo '<input class="form-control" placeholder="'. esc_attr__('Website', 'DIRECTORYPRESS') .'" type="text" name="website" value="' . (isset($_POST['website']) ? $website : '') . '">';
			echo '</p>';
			echo '<p class="form-group">';
				echo '<input class="form-control" placeholder="'. esc_attr__('Password', 'DIRECTORYPRESS') .'" type="password" name="password" value="' . (isset($_POST['password']) ? $password :'') . '">';
			echo '</p>';
			echo '<p class="register-submit">';
				echo '<input class="directorypress-register-button" type="submit" name="submit" value="'. esc_attr__('Register', 'DIRECTORYPRESS') .'"/>';
		echo '</form>';
	echo '</div>';
}

function directorypress_registration_validation( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio )  {
    global $reg_errors;
    $reg_errors = new WP_Error;

    if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
        $reg_errors->add('field', esc_html__('Required form field is missing', 'DIRECTORYPRESS'));
    }

    if ( strlen( $username ) < 4 ) {
        $reg_errors->add('username_length', esc_html__('Username too short. At least 4 characters is required', 'DIRECTORYPRESS'));
    }

    if ( username_exists( $username ) )
        $reg_errors->add('user_name', esc_html__('Sorry, that username already exists!', 'DIRECTORYPRESS'));

    if ( !validate_username( $username ) ) {
        $reg_errors->add('username_invalid', esc_html__('Sorry, the username you entered is not valid', 'DIRECTORYPRESS'));
    }

    if ( strlen( $password ) < 5 ) {
        $reg_errors->add('password', esc_html__('Password length must be greater than 5', 'DIRECTORYPRESS'));
    }

    if ( !is_email( $email ) ) {
        $reg_errors->add('email_invalid', esc_html__('Email is not valid', 'DIRECTORYPRESS'));
    }

    if ( email_exists( $email ) ) {
        $reg_errors->add('email', esc_html__('Email Already in use', 'DIRECTORYPRESS'));
    }
    
    if ( !empty( $website ) ) {
        if ( !filter_var($website, FILTER_VALIDATE_URL) ) {
            $reg_errors->add('website', esc_html__('Website is not a valid URL', 'DIRECTORYPRESS'));
        }
    }

    if ( is_wp_error( $reg_errors ) ) {

        foreach ( $reg_errors->get_error_messages() as $error ) {
            echo '<div>';
            echo '<strong>'. esc_html__('ERROR', 'DIRECTORYPRESS') .'</strong>:';
            echo wp_kses_post($error) . '<br/>';

            echo '</div>';
        }
    }
}

function directorypress_complete_registration() {
    global $reg_errors, $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
    if ( count($reg_errors->get_error_messages()) < 1 ) {
        $userdata = array(
        'user_login'	=> 	$username,
        'user_email' 	=> 	$email,
        'user_pass' 	=> 	$password,
        'user_url' 		=> 	$website,
        'first_name' 	=> 	$first_name,
        'last_name' 	=> 	$last_name,
        'nickname' 		=> 	$nickname,
        'description' 	=> 	$bio,
		);
        $user = wp_insert_user( $userdata );
        echo esc_html__('Registration completed.', 'DIRECTORYPRESS');   
	}
}

// Register a new shortcode: [cr_custom_registration]
add_shortcode('directorypress_registration', 'directorypress_registration_shortcode');

// The callback function that will replace [book]
function directorypress_registration_shortcode() {
    ob_start();
    directorypress_registration_function();
    return ob_get_clean();
}
