<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options;
if (isset($foxtool_options['goo-log1'])){
# add google api
require_once( FOXTOOL_DIR . 'link/google-api/vendor/autoload.php');
# add api google
$urlgoogleset = home_url() .'/wp-admin/admin-ajax.php?action=foxtool_login_google';
$setClientId = !empty($foxtool_options['goo-log11']) ? $foxtool_options['goo-log11'] : '123456789';
$setClientSecret = !empty($foxtool_options['goo-log12']) ? $foxtool_options['goo-log12'] : '123456789';
$gClient = new Google_Client();
$gClient->setClientId($setClientId);
$gClient->setClientSecret($setClientSecret);
$gClient->setApplicationName("Google login");
$gClient->setRedirectUri($urlgoogleset);
$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
# login URL
$login_url = $gClient->createAuthUrl();
# Tạo shorcode đăng nhập
function foxtool_login_shortcode_google() {
    global $login_url;
    $btnContent = '
	    <style>
			.foxtool-google a {
				font-weight: bold;
				display: block;
				margin: 0 auto;
				background: #f3f3f3;
				padding: 10px;
				border-radius: 10px;
				text-align: center;
				text-decoration: none;
				margin-bottom: 20px;
				color: #333 !important;
			}
			.foxtool-google a:hover{opacity:0.6}
            .foxtool-google a img{
				width:30px !important;
				vertical-align: middle;
				margin-right:15px;
			}
        </style>
	';
    if (!is_user_logged_in()) {
        return $btnContent . '<div class="foxtool-google"><a title="Google login" href="' . esc_url($login_url) . '"><img alt="Gooogle login" src="'. FOXTOOL_URL . 'img/google.svg' .'"/> '. __('Sign in with Google', 'fox') .'</a></div>';
    } else {
        return $btnContent . '<div class="foxtool-google"><a href="' . esc_url(wp_logout_url()) . '">'. __('Log out', 'fox') .'</a></div>';
    }
}
add_shortcode('google-login', 'foxtool_login_shortcode_google');
# cau hinh nhan thong tin
function foxtool_login_google() {
    global $gClient, $foxtool_options;
    if (isset($_GET['code'])) {
        $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
        if (!isset($token["error"])) {
            $oAuth = new Google_Service_Oauth2($gClient);
            $userData = $oAuth->userinfo_v2_me->get();
        }
        if (!email_exists($userData['email'])) {
            $random_password = wp_generate_password();
            $user_login = sanitize_user(strstr($userData['email'], '@', true));
			$roleuser = !empty($foxtool_options['goo-role1']) ? $foxtool_options['goo-role1'] : 'subscriber';
            $new_user_id = wp_insert_user(array(
                'user_login'        => $user_login,
                'user_pass'         => $random_password,
                'user_email'        => $userData['email'],
                'first_name'        => $userData['givenName'],
                'last_name'         => $userData['familyName'],
                'user_registered'   => date('Y-m-d H:i:s'),
                'role'              => $roleuser,
            ));
            if (!is_wp_error($new_user_id)) {
                wp_new_user_notification($new_user_id);
                wp_set_current_user($new_user_id);
                wp_set_auth_cookie($new_user_id, true);
                wp_safe_redirect(home_url());
                exit;
            }
        } else {
            $user = get_user_by('email', $userData['email']);
            if ($user) {
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID, true);
                wp_safe_redirect(home_url());
                exit;
            }
        }
        // Handle error or redirect to homepage
        wp_safe_redirect(home_url());
        exit;
    } else {
        // Handle error or redirect to homepage
        wp_safe_redirect(home_url());
        exit;
    }
}
add_action('wp_ajax_foxtool_login_google', 'foxtool_login_google');
add_action('wp_ajax_nopriv_foxtool_login_google', 'foxtool_login_google');
# hien thị vao form dang nhap mac dinh
if (isset($foxtool_options['goo-log13'])){
function foxtool_login_form_social_login() {
   echo do_shortcode( '[google-login]' );
}
add_action('login_form', 'foxtool_login_form_social_login');
}
}
# Thêm nút đăng nhập Google vào form đăng nhập của WooCommerce
if (isset($foxtool_options['goo-log14'])){
function foxtool_google_login_to_woocommerce_login_form() {
    echo do_shortcode( '[google-login]' );
}
add_action('woocommerce_login_form', 'foxtool_google_login_to_woocommerce_login_form');
}
# add font captcha v2 vao form login
if (isset($foxtool_options['goo-cap1']) && $foxtool_options['goo-cap1'] == 'V2'){
function foxtool_authentication_login_form(){
	global $foxtool_options;
	$site_key = !empty($foxtool_options['goo-cap11']) ? $foxtool_options['goo-cap11'] : NULL;
    ?>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
	<style>.login form .g-recaptcha{margin-left:-15px;margin-bottom:10px;} form.woocommerce-form.woocommerce-form-login.login .g-recaptcha, form.woocommerce-form.woocommerce-form-register.register .g-recaptcha{margin-bottom:10px;}</style>
    <?php
}
add_action( 'login_form', 'foxtool_authentication_login_form' );
add_action( 'login_form_middle', 'foxtool_authentication_login_form' );
add_action('register_form', 'foxtool_authentication_login_form');
// xu ly woo
add_action('woocommerce_login_form', 'foxtool_authentication_login_form');
add_action('woocommerce_register_form', 'foxtool_authentication_login_form');
// xu ly dang nhap
function foxtool_authentication_login_verify( $user, $username ) {
	global $foxtool_options;
    $secret_key = !empty($foxtool_options['goo-cap12']) ? $foxtool_options['goo-cap12'] : NULL;
    if ( isset( $_POST['g-recaptcha-response'] ) ) {
        $recaptcha_response = $_POST['g-recaptcha-response'];
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $secret_key,
            'response' => $recaptcha_response,
        );
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query( $data ),
                'ignore_errors' => true, // Bỏ qua lỗi SSL
            ),
        );
        $context = stream_context_create( $options );
        $result = file_get_contents( $url, false, $context );
        if ( $result !== false ) {
            $result = json_decode( $result );
            if ( isset( $result->success ) && $result->success ) {
                return $user;
            } else {
                $error = new WP_Error();
                $error->add( 'recaptcha_error', __('The reCAPTCHA is invalid', 'foxtool') );
                return $error;
            }
        } else {
            $error = new WP_Error();
            $error->add( 'recaptcha_error', __('reCAPTCHA verification failed. Please try again', 'foxtool') );
            return $error;
        }
    } else {
        $error = new WP_Error();
        $error->add( 'recaptcha_error', __('The reCAPTCHA error is mandatory', 'foxtool') );
        return $error;
    }
}
add_filter( 'wp_authenticate_user', 'foxtool_authentication_login_verify', 10, 2 );
// xu ly dang ky
function foxtool_authentication_register_verify($errors) {
    global $foxtool_options;
    $secret_key = !empty($foxtool_options['goo-cap12']) ? $foxtool_options['goo-cap12'] : NULL;
    if (empty($_POST['g-recaptcha-response'])) {
        $errors->add('recaptcha_error', __('reCAPTCHA is required', 'foxtool'));
    } else {
        $recaptcha_response = $_POST['g-recaptcha-response'];
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $secret_key,
            'response' => $recaptcha_response,
        );
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
                'ignore_errors' => true, // Bỏ qua lỗi SSL
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result !== false) {
            $result = json_decode($result);
            if (!isset($result->success) || !$result->success) {
                $errors->add('recaptcha_error', __('The reCAPTCHA is invalid', 'foxtool'));
            }
        } else {
            $errors->add('recaptcha_error', __('reCAPTCHA error. Please try again', 'foxtool'));
        }
    }
    return $errors;
}
add_filter('registration_errors', 'foxtool_authentication_register_verify');
add_filter('woocommerce_process_registration_errors', 'foxtool_authentication_register_verify', 10, 3);
}
# add font captcha v3 vao form login
if (isset($foxtool_options['goo-cap1']) && $foxtool_options['goo-cap1'] == 'V3'){
function foxtool_authentication_login_form_v3() {
	global $foxtool_options;
	$site_key = !empty($foxtool_options['goo-cap11']) ? $foxtool_options['goo-cap11'] : NULL;
    ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $site_key; ?>"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo $site_key; ?>', {action: 'login'})
            .then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
            });
        });
    </script>
    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
    <?php
}
add_action('login_form', 'foxtool_authentication_login_form_v3');
add_action('login_form_middle', 'foxtool_authentication_login_form_v3');
add_action('register_form', 'foxtool_authentication_login_form_v3');
// xu ly woo
add_action('woocommerce_login_form', 'foxtool_authentication_login_form_v3');
// xu ly dang nhap
function foxtool_authentication_login_verify_v3($user, $username) {
    global $foxtool_options;
    $secret_key = !empty($foxtool_options['goo-cap12']) ? $foxtool_options['goo-cap12'] : NULL;
    $recaptcha_response = isset($_POST['g-recaptcha-response']) ? sanitize_text_field($_POST['g-recaptcha-response']) : '';
    if (empty($recaptcha_response)) {
        $error = new WP_Error();
        $error->add('recaptcha_error', __('Please complete the reCAPTCHA to verify that you are not a robot', 'foxtool'));
        return $error;
    }
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secret_key,
        'response' => $recaptcha_response,
    );
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result !== false) {
        $result = json_decode($result);

        if ($result && isset($result->score) && $result->score >= 0.5) {
            return $user;
        } else {
            $error = new WP_Error();
            $error->add('recaptcha_error', __('The reCAPTCHA is invalid', 'foxtool'));
            return $error;
        }
    } else {
        $error = new WP_Error();
        $error->add('recaptcha_error', __('reCAPTCHA error. Please try again', 'foxtool'));
        return $error;
    }
}
add_filter('wp_authenticate_user', 'foxtool_authentication_login_verify_v3', 10, 2);
// xu ly dang ky
function foxtool_authentication_register_verify_v3($errors) {
    global $foxtool_options;
    $secret_key = !empty($foxtool_options['goo-cap12']) ? $foxtool_options['goo-cap12'] : NULL;
    $recaptcha_response = isset($_POST['g-recaptcha-response']) ? sanitize_text_field($_POST['g-recaptcha-response']) : '';
    if (empty($recaptcha_response)) {
        $errors->add('recaptcha_error', __('Please complete the reCAPTCHA to verify that you are not a robot', 'foxtool'));
    }
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secret_key,
        'response' => $recaptcha_response,
    );
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result !== false) {
        $result = json_decode($result);

        if ($result && isset($result->score) && $result->score >= 0.5) {
            return $errors;
        } else {
            $errors->add('recaptcha_error', __('The reCAPTCHA is invalid', 'foxtool'));
        }
    } else {
        $errors->add('recaptcha_error', __('reCAPTCHA error. Please try again', 'foxtool'));
    }
    return $errors;
}
add_filter('registration_errors', 'foxtool_authentication_register_verify_v3');
}







