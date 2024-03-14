<?php

class Register_Update_Password
{

    public function __construct()
    {
        add_action('init', array($this, 'update_password_validate'));
    }

    public function update_password_validate()
    {
        if (isset($_POST['option']) and sanitize_text_field($_POST['option']) == "rpws_user_update_password") {

            if (!wp_verify_nonce($_POST['wprp_5q5rt78'], 'wprp_action')) {
                wp_die('Error');
            }

            start_session_if_not_started();
            global $post;
            $error = false;

            if (!is_user_logged_in()) {
                $msg = __('Login to update profile!', 'wp-register-profile-with-shortcode');
                $error = true;
            }

            if ($_POST['user_new_password'] == '') {
                $msg = __('Password can\'t be empty.', 'wp-register-profile-with-shortcode');
                $error = true;
            }

            if (isset($_POST['user_new_password']) and ($_POST['user_new_password'] != $_POST['user_retype_password'])) {
                $msg = __('Your new password don\'t match with retype password!', 'wp-register-profile-with-shortcode');
                $error = true;
            }

            if (!$error) {
                $user_id = get_current_user_id();
                wp_set_password($_POST['user_new_password'], $user_id);

                $_SESSION['reg_error_msg'] = __('Your password updated successfully. Please login again.', 'wp-register-profile-with-shortcode');
                $_SESSION['reg_msg_class'] = 'reg_success';

            } else {
                $_SESSION['reg_error_msg'] = $msg;
                $_SESSION['reg_msg_class'] = 'reg_error';
            }

            if (!empty($_POST['redirect'])) {
                $redirect = sanitize_text_field($_POST['redirect']);
                wp_redirect($redirect);
                exit;
            }
        }
    }

    public function load_script()
    {?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery('#update-password').validate({ errorClass: "rw-error" });
			});
		</script>
	<?php }

    public function update_password_form()
    {
        global $post;
        if (is_user_logged_in()) {
            echo '<div id="reg_forms" class="reg_forms">';
            $this->load_script();
            do_action('wprp_before_update_pass_form_start');
            $this->error_message();
            include WPRPWS_DIR_PATH . '/view/frontend/update-password.php';
            do_action('wprp_after_update_pass_form_end');
            echo '</div>';
        }
    }

    public function error_message()
    {
        start_session_if_not_started();
        if (isset($_SESSION['reg_error_msg']) and $_SESSION['reg_error_msg']) {
            echo '<div class="' . $_SESSION['reg_msg_class'] . '">' . $_SESSION['reg_error_msg'] . '</div>';
            unset($_SESSION['reg_error_msg']);
            unset($_SESSION['reg_msg_class']);
        }
    }

}