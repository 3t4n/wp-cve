<?php

class CleanLogin_Settings
{
    function load()
    {
        add_action('after_setup_theme', array($this, 'maybe_remove_admin_bar'));
        add_action('admin_init', array($this, 'maybe_block_dashboard_access'), 1);
        add_action('admin_menu', array($this, 'menu'));
    }

    function menu()
    {
        add_options_page('Clean Login Options', 'Clean Login', apply_filters('clean_login_admin_capability', 'manage_options'), 'clean_login_menu', array($this, 'render'));
    }

    function maybe_remove_admin_bar()
    {
        $remove_adminbar_roles = get_option('cl_adminbar_roles');
        $remove_adminbar = get_option('cl_adminbar');

        if( $remove_adminbar_roles === false ){ // retro compatibility
            if ($remove_adminbar && !current_user_can(apply_filters('clean_login_admin_capability', 'manage_options')))
                show_admin_bar(false);
        }
        else{
            if( !$remove_adminbar )
                return;

            $user_roles = CleanLogin_Roles::get_current_user_roles();
            $remove_adminbar_roles = ( is_array( $remove_adminbar_roles ) ? $remove_adminbar_roles : array() );

            $result = array_intersect( $user_roles, $remove_adminbar_roles );

            if( count( $result ) > 0 )
                show_admin_bar(false);
        }
    }

    function maybe_block_dashboard_access()
    {
        $block_dashboard = get_option('cl_dashboard');

        if ($block_dashboard && !current_user_can(apply_filters('clean_login_admin_capability', 'manage_options')) && (!defined('DOING_AJAX') || !DOING_AJAX)) {
            wp_redirect(home_url());
            exit;
        }
    }

    function render_donation_box()
    {
?>
        <div class="card">
            <h3 class="title" id="like-donate-more" style="cursor: pointer;"><?php echo __('Do you like it?', 'clean-login'); ?> <span id="like-donate-arrow" class="dashicons dashicons-arrow-down"></span><span id="like-donate-smile" class="dashicons dashicons-smiley hidden"></span></h3>
            <div class="hidden" id="like-donate">
                <p>Hi there! We are <a href="https://twitter.com/fjcarazo" target="_blank" title="Javier Carazo">Javier Carazo</a> and <a href="https://twitter.com/ahornero" target="_blank" title="Alberto Hornero">Alberto Hornero</a> from <a href="http://codection.com">Codection</a>, developers of this plugin. We have been spending many hours to develop this plugin, we keep updating it and we always try do the best in the <a href="https://wordpress.org/support/plugin/clean-login">support forum</a>.</p>
                <p>If you like it, you can <strong>buy us a cup of coffee</strong> or whatever ;-)</p>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="HGAS22NVY7Q8N">
                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
                </form>
                <p>Sure! You can also <strong><a href="https://wordpress.org/support/view/plugin-reviews/clean-login?filter=5">rate our plugin</a></strong> and provide us your feedback. Thanks!</p>
            </div>
        </div>
    <?php
    }

    function render_used_shortcode_table()
    {
        $login_url = get_option('cl_login_url');
        $edit_url = get_option('cl_edit_url');
        $register_url = get_option('cl_register_url');
        $restore_url = get_option('cl_restore_url');
    ?>
        <h2><?php echo __('Clean Login status', 'clean-login'); ?></h2>

        <p><?php echo __('Below you can check the plugin status regarding the shortcodes usage and the pages/posts which contain  it.', 'clean-login'); ?></p>

        <table class="widefat importers">
            <tbody>
                <tr class="alternate">
                    <td class="import-system row-title"><a>[clean-login]</a></td>
                    <?php if (!$login_url) : ?>
                        <td class="desc"><?php echo __('Currently not used', 'clean-login'); ?></td>
                    <?php else : ?>
                        <td class="desc"><?php printf(__('Used <a href="%s">here</a>', 'clean-login'), $login_url); ?></td>
                    <?php endif; ?>
                    <td class="desc"><?php echo __('This shortcode contains login form and login information.', 'clean-login'); ?></td>
                </tr>
                <tr>
                    <td class="import-system row-title"><a>[clean-login-edit]</a></td>
                    <?php if (!$edit_url) : ?>
                        <td class="desc"><?php echo __('Currently not used', 'clean-login'); ?></td>
                    <?php else : ?>
                        <td class="desc"><?php printf(__('Used <a href="%s">here</a>', 'clean-login'), $edit_url); ?></td>
                    <?php endif; ?>
                    <td class="desc"><?php echo __('This shortcode contains the profile editor. If you include in a page/post a link will appear on your login preview. You can hide email field using attribute show_email with value false.', 'clean-login'); ?></td>
                </tr>
                <?php if (get_option('users_can_register')) : ?>
                    <tr class="alternate">
                        <td class="import-system row-title"><a>[clean-login-register]</a></td>
                        <?php if (!$register_url) : ?>
                            <td class="desc"><?php echo __('Currently not used', 'clean-login'); ?></td>
                        <?php else : ?>
                            <td class="desc"><?php printf(__('Used <a href="%s">here</a>', 'clean-login'), $register_url); ?></td>
                        <?php endif; ?>
                        <td class="desc"><?php echo __('This shortcode contains the register form. If you include in a page/post a link will appear on your login form.', 'clean-login'); ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td class="import-system row-title"><a>[clean-login-restore]</a></td>
                    <?php if (!$restore_url) : ?>
                        <td class="desc"><?php echo __('Currently not used', 'clean-login'); ?></td>
                    <?php else : ?>
                        <td class="desc"><?php printf(__('Used <a href="%s">here</a>', 'clean-login'), $restore_url); ?></td>
                    <?php endif; ?>
                    <td class="desc"><?php echo __('This shortcode contains the restore (lost password?) form. If you include in a page/post a link will appear on your login form.', 'clean-login'); ?></td>
                </tr>
            </tbody>
        </table>
    <?php
    }

    function maybe_update_options()
    {
        if (isset($_POST) && !empty($_POST)) {
            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'codection-security')) {
                wp_die('Security check');
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            update_option('cl_adminbar', isset($_POST['adminbar']));
            update_option('cl_adminbar_roles', $_POST['adminbar_roles']);
            update_option('cl_dashboard', isset($_POST['dashboard']));
            update_option('cl_antispam', isset($_POST['antispam']));
            update_option('cl_gcaptcha', isset($_POST['gcaptcha']));
            update_option('cl_gcaptcha_sitekey', isset($_POST['gcaptcha_sitekey']) ? $_POST['gcaptcha_sitekey'] : '');
            update_option('cl_gcaptcha_secretkey', isset($_POST['gcaptcha_secretkey']) ? $_POST['gcaptcha_secretkey'] : '');
            update_option('cl_standby', isset($_POST['standby']));
            update_option('cl_hideuser', isset($_POST['hideuser']));
            update_option('cl_passcomplex', isset($_POST['passcomplex']));
            update_option('cl_emailnotification', isset($_POST['emailnotification']));
            update_option('cl_emailnotificationcontent', sanitize_text_field($_POST['emailnotificationcontent']));
            update_option('cl_chooserole', isset($_POST['chooserole']));
            update_option('cl_newuserroles', isset($_POST['newuserroles']) ? $_POST['newuserroles'] : '');
            update_option('cl_termsconditions', isset($_POST['termsconditions']));
            update_option('cl_termsconditionsMSG', isset($_POST['termsconditionsMSG']) ? $_POST['termsconditionsMSG'] : '');
            update_option('cl_termsconditionsURL', isset($_POST['termsconditionsURL']) ? $_POST['termsconditionsURL'] : '');
            update_option('cl_email_username', isset($_POST['emailusername']));
            update_option('cl_single_password', isset($_POST['singlepassword']));
            update_option('cl_automatic_login', isset($_POST['automaticlogin']));
            update_option('cl_url_redirect', isset($_POST['automaticlogin']) && isset($_POST['urlredirect']) ? esc_url_raw($_POST['urlredirect']) : home_url());
            update_option('cl_nameandsurname', isset($_POST['nameandsurname']));
            update_option('cl_emailvalidation', isset($_POST['emailvalidation']));
            update_option('cl_enable_hash_in_login_page', isset($_POST['enable_hash_in_login_page']));
            update_option('cl_login_redirect', isset($_POST['loginredirect']));
            update_option('cl_login_redirect_url', isset($_POST['loginredirect']) && isset($_POST['loginredirect_url']) ? esc_url_raw($_POST['loginredirect_url']) : home_url());
            update_option('cl_logout_redirect', isset($_POST['logoutredirect']));
            update_option('cl_logout_redirect_url', isset($_POST['logoutredirect']) && isset($_POST['logoutredirect_url']) ? esc_url_raw($_POST['logoutredirect_url']) : home_url());
            update_option('cl_register_redirect', isset($_POST['registerredirect']));
            update_option('cl_register_redirect_url', isset($_POST['registerredirect']) && isset($_POST['registerredirect_url']) ? esc_url_raw($_POST['registerredirect_url']) : home_url());

            echo '<div class="updated"><p><strong>' . __('Settings saved.', 'clean-login') . '</strong></p></div>';
        }
    }

    function render()
    {
        if (!current_user_can(apply_filters('clean_login_admin_capability', 'manage_options'))) {
            wp_die(__('Admin area', 'clean-login'));
        }

        $roles_helper = new CleanLogin_Roles();
        $this->maybe_update_options();
    ?>
        <div class="wrap">
            <?php $this->render_donation_box(); ?>
            <br />
            <?php $this->render_used_shortcode_table(); ?>
            <h2><?php echo __('Options', 'clean-login'); ?></h2>

            <?php
            $adminbar = get_option('cl_adminbar', true);
            $adminbar_roles = is_array( get_option('cl_adminbar_roles', true) ) ? get_option('cl_adminbar_roles', true) : array();
            $dashboard = get_option('cl_dashboard');
            $antispam = get_option('cl_antispam');
            $gcaptcha = get_option('cl_gcaptcha');
            $gcaptcha_sitekey = get_option('cl_gcaptcha_sitekey');
            $gcaptcha_secretkey = get_option('cl_gcaptcha_secretkey');
            $standby = get_option('cl_standby');
            $hideuser = get_option('cl_hideuser');
            $passcomplex = get_option('cl_passcomplex');
            $emailnotification = get_option('cl_emailnotification');
            $emailnotificationcontent = get_option('cl_emailnotificationcontent');
            $chooserole = get_option('cl_chooserole');
            $newuserroles = get_option('cl_newuserroles');
            $termsconditions = get_option('cl_termsconditions');
            $termsconditionsMSG = get_option('cl_termsconditionsMSG');
            $termsconditionsURL = get_option('cl_termsconditionsURL');
            $emailusername = get_option('cl_email_username');
            $singlepassword = get_option('cl_single_password');
            $automaticlogin = get_option('cl_automatic_login', false) ? true : false;
            $urlredirect = get_option('cl_url_redirect', false) ? esc_url(get_option('cl_url_redirect')) : home_url();
            $nameandsurname = get_option('cl_nameandsurname', false) ? true : false;
            $emailvalidation = get_option('cl_emailvalidation', false) ? true : false;
            $enable_hash_in_login_page = get_option('cl_enable_hash_in_login_page', false) ? true : false;
            $loginredirect = get_option('cl_login_redirect', false) ? true : false;
            $loginredirect_url = get_option('cl_login_redirect_url', false) ? esc_url(get_option('cl_login_redirect_url')) : home_url();
            $logoutredirect = get_option('cl_logout_redirect', false) ? true : false;
            $logoutredirect_url = get_option('cl_logout_redirect_url', false) ? esc_url(get_option('cl_logout_redirect_url')) : home_url();
            $registerredirect = get_option('cl_register_redirect', false) ? true : false;
            $registerredirect_url = get_option('cl_register_redirect_url', false) ? esc_url(get_option('cl_register_redirect_url')) : home_url();
            ?>
            <form id="form1" name="form1" method="post" action="">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><?php echo __('Admin bar', 'clean-login'); ?></th>
                            <td>
                                <label><input name="adminbar" type="checkbox" id="adminbar" <?php checked($adminbar); ?>><?php echo __('Hide admin bar for some roles?', 'clean-login'); ?></label>
                                <div id="adminbar_roles">
                                    <p class="description"><?php echo __('Choose which will roles will have the admin bar hidden', 'clean-login'); ?></p>
                                    <label>
                                        <select name="adminbar_roles[]" multiple="multiple">
                                            <?php foreach ($roles_helper->get_non_admin_roles() as $slug => $name) : ?>
                                                <option value="<?php echo $slug; ?>" <?php if( in_array( $slug, $adminbar_roles ) ) echo 'selected="selected"'; ?>><?php echo $name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Dashboard access', 'clean-login'); ?></th>
                            <td>
                                <label><input name="dashboard" type="checkbox" id="dashboard" <?php checked($dashboard); ?>><?php echo __('Disable dashboard access for non-admin users?', 'clean-login'); ?></label>
                                <p class="description"><?php echo __('Please note that you can only log in through <strong>wp-login.php</strong> and this plugin. <strong>wp-admin</strong> permalink will be inaccessible.', 'clean-login'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Antispam protection', 'clean-login'); ?><p class="description">[Letters captcha]</p>
                            </th>
                            <td>
                                <label><input name="antispam" <?php if ($gcaptcha) echo 'disabled'; ?> type="checkbox" id="antispam" <?php checked($antispam); ?>><?php echo __('Enable captcha?', 'clean-login'); ?></label>
                                <p class="description"><?php echo __('Honeypot antispam detection is enabled by default.', 'clean-login'); ?></p>
                                <p class="description"><?php echo __('For captcha usage the PHP-GD library needs to be enabled in your server/hosting.', 'clean-login'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Antispam protection', 'clean-login'); ?><p class="description">[Google checkbox captcha]</p>
                            </th>
                            <td>
                                <label><input name="gcaptcha" <?php if ($antispam) echo 'disabled'; ?> type="checkbox" id="gcaptcha" <?php if ($gcaptcha) echo 'checked="checked"'; ?>><?php echo __('Enable Google reCaptcha?', 'clean-login'); ?></label>
                                <div style="color:red; display:none;" id="gcaptcha_error"><?php echo __('Google reCaptcha site key and secret key must not be empty', 'clean-login'); ?></div>
                                <div id="gcaptcha_sitekey-label" <?php if (!$gcaptcha) echo 'style="display:none;"'; ?>>
                                    <p class="description"><?php echo __('Google reCaptcha Site Key', 'clean-login'); ?></p>
                                    <label><input class="regular-text" value="<?php echo $gcaptcha_sitekey; ?>" name="gcaptcha_sitekey" type="text" id="gcaptcha_sitekey"></label>
                                </div>
                                <div id="gcaptcha_secretkey-label" <?php if (!$gcaptcha) echo 'style="display:none;"'; ?>>
                                    <p class="description"><?php echo __('Google reCaptcha Secret Key', 'clean-login'); ?></p>
                                    <label><input class="regular-text" value="<?php echo $gcaptcha_secretkey; ?>" name="gcaptcha_secretkey" type="text" id="gcaptcha_secretkey"></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('User role', 'clean-login'); ?></th>
                            <td>
                                <label><input name="standby" type="checkbox" id="standby" <?php checked($standby); ?>><?php echo __('Enable Standby role?', 'clean-login'); ?></label>
                                <p class="description"><?php _e('Standby role disables all the capabilities for new users, until the administrator changes. It usefull for site with restricted components.', 'clean-login'); ?></p>
                                <br>
                                <label><input name="chooserole" type="checkbox" id="chooserole" <?php checked($chooserole); ?>><?php echo __('Choose the role(s) in the registration form?', 'clean-login'); ?></label>
                                <p class="description"><?php _e('This feature allows you to choose the role from the frontend, with the selected roles you want to show. You can also define an standard predefined role through a shortcode parameter, e.g. [clean-login-register role="contributor"]. Anyway, you need to choose only the role(s) you want to accept to avoid security/infiltration issues.', 'clean-login'); ?></p>
                                <p>
                                    <select name="newuserroles[]" id="newuserroles" multiple size="5"><?php wp_dropdown_roles(); ?></select>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Hide username', 'clean-login'); ?></th>
                            <td>
                                <label><input name="hideuser" type="checkbox" id="hideuser" <?php checked($hideuser); ?>><?php echo __('Hide username?', 'clean-login'); ?></label>
                                <p class="description"><?php _e('Hide username from the preview form.', 'clean-login'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Password complexity', 'clean-login'); ?></th>
                            <td>
                                <label><input name="passcomplex" type="checkbox" id="passcomplex" <?php checked($passcomplex); ?>><?php echo __('Enable password complexity?', 'clean-login'); ?></label>
                                <p class="description"><?php echo __('Passwords must be eight characters including one upper/lowercase letter, one special/symbol character and alphanumeric characters. Passwords should not contain the user\'s username, email, or first/last name.', 'clean-login'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Email notification', 'clean-login'); ?></th>
                            <td>
                                <label><input name="emailnotification" type="checkbox" id="emailnotification" <?php checked($emailnotification); ?>><?php echo __('Enable email notification for new registered users?', 'clean-login'); ?></label>
                                <p><textarea name="emailnotificationcontent" id="emailnotificationcontent" placeholder="<?php echo __('Please use HMTL tags for all formatting. And also you can use:', 'clean-login') . ' {username} {password} {email}'; ?>" rows="8" cols="50" class="large-text code"><?php echo $emailnotificationcontent; ?></textarea></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Terms and conditions', 'clean-login'); ?></th>
                            <td>
                                <label><input name="termsconditions" type="checkbox" id="termsconditions" <?php if ($termsconditions) echo 'checked="checked"'; ?>><?php echo __('Accept terms / conditions in the registration form?', 'clean-login'); ?></label>
                                <p><input name="termsconditionsMSG" type="text" id="termsconditionsMSG" value="<?php echo $termsconditionsMSG; ?>" placeholder="<?php echo __('Terms and conditions message', 'clean-login'); ?>" class="regular-text"></p>
                                <p><input name="termsconditionsURL" type="url" id="termsconditionsURL" value="<?php echo $termsconditionsURL; ?>" placeholder="<?php echo __('Target URL', 'clean-login'); ?>" class="regular-text"></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Use Email as Username', 'clean-login'); ?></th>
                            <td>
                                <label><input name="emailusername" type="checkbox" id="emailusername" <?php checked($emailusername); ?>><?php echo __('Allow user to use email as username?', 'clean-login'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Single Password', 'clean-login'); ?></th>
                            <td>
                                <label><input name="singlepassword" type="checkbox" id="singlepassword" <?php checked($singlepassword); ?>><?php echo __('Only ask for password once on registration form?', 'clean-login'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Registration', 'clean-login'); ?></th>
                            <td>
                                <label><input name="automaticlogin" type="checkbox" id="automaticlogin" <?php if ($automaticlogin != '') echo 'checked="checked"'; ?>><?php echo __('Automatically Login after registration?', 'clean-login'); ?></label>
                                <div id="urlredirect">
                                    <p class="description"><?php echo __('URL after registration (if blank then homepage)', 'clean-login'); ?></p>
                                    <label><input class="regular-text" type="text" name="urlredirect" value="<?php echo $urlredirect; ?>"></label>
                                </div>
                                <br>
                                <label><input name="nameandsurname" type="checkbox" id="nameandsurname" <?php if ($nameandsurname != '') echo 'checked="checked"'; ?>><?php echo __('Add name and surname?', 'clean-login'); ?></label>
                                <br>
                                <label><input name="emailvalidation" type="checkbox" id="emailvalidation" <?php if ($emailvalidation != '') echo 'checked="checked"'; ?>><?php echo __('Validate user registration through an email?', 'clean-login'); ?></label>
                                <p class="description"><?php echo __('This feature cannot be used with the automatic login after registration', 'clean-login'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Login', 'clean-login'); ?></th>
                            <td>
                                <label><input name="enable_hash_in_login_page" type="checkbox" id="enable_hash_in_login_page" <?php checked($enable_hash_in_login_page); ?>><?php echo __('Enable timestamp GET parameter in login page to avoid problems with page cache', 'clean-login'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Redirections', 'clean-login'); ?></th>
                            <td>
                                <label><input name="loginredirect" type="checkbox" id="loginredirect" <?php if ($loginredirect != '') echo 'checked="checked"'; ?>><?php echo __('Redirect after log in?', 'clean-login'); ?></label>
                                <div id="loginredirect_url">
                                    <p class="description"><?php echo __('URL after login (if blank then homepage)', 'clean-login'); ?></p>
                                    <label><input class="regular-text" type="text" name="loginredirect_url" value="<?php echo $loginredirect_url; ?>"></label>
                                </div>
                                <br>
                                <label><input name="logoutredirect" type="checkbox" id="logoutredirect" <?php if ($logoutredirect != '') echo 'checked="checked"'; ?>><?php echo __('Redirect after log out?', 'clean-login'); ?></label>
                                <div id="logoutredirect_url">
                                    <p class="description"><?php echo __('URL after logout (if blank then homepage)', 'clean-login'); ?></p>
                                    <label><input class="regular-text" type="text" name="logoutredirect_url" value="<?php echo $logoutredirect_url; ?>"></label>
                                </div>
                                <br>
                                <label><input name="registerredirect" type="checkbox" id="registerredirect" <?php if ($registerredirect != '') echo 'checked="checked"'; ?>><?php echo __('Redirect after register?', 'clean-login'); ?></label>
                                <div id="registerredirect_url">
                                    <p class="description"><?php echo __('URL after redirect (if blank then homepage)', 'clean-login'); ?></p>
                                    <label><input class="regular-text" type="text" name="registerredirect_url" value="<?php echo $registerredirect_url; ?>"></label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <?php wp_nonce_field('codection-security'); ?>

                <p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php echo __('Save Changes', 'clean-login'); ?>" /></p>
            </form>

        </div>
        <script>
            jQuery( document ).ready( function( $ ) {
                $("#form1").on("submit", function(evt) {
                    if ($('#gcaptcha').is(':checked') &&
                        ($('#gcaptcha_sitekey').val() == '' || $('#gcaptcha_secretkey').val() == '')) {
                        evt.preventDefault();
                        $('#gcaptcha_error').show();
                        scrollToTop();
                    }
                });

                function scrollToTop() {
                    $('html, body').animate({
                        scrollTop: $("#form1").offset().top
                    }, 1000);
                }

                function displayInitCheck(){
                    if ($('#adminbar').is(':checked')) {
                        $('#adminbar_roles').show();
                    } else {
                        $('#adminbar_roles').hide();
                    }

                    if ($('#chooserole').is(':checked')) {
                        $('#newuserroles').show();
                    } else {
                        $('#newuserroles').hide();
                    }

                    if ($('#automaticlogin').is(':checked')) {
                        $('#urlredirect').show();
                        $('#emailvalidation').prop('checked', false);
                    } else {
                        $('#urlredirect').hide();
                    }

                    if ($('#registerredirect').is(':checked')) {
                        $('#registerredirect_url').show();
                    } else {
                        $('#registerredirect_url').hide();
                    }

                    if ($('#loginredirect').is(':checked')) {
                        $('#loginredirect_url').show();
                    } else {
                        $('#loginredirect_url').hide();
                    }

                    if ($('#logoutredirect').is(':checked')) {
                        $('#logoutredirect_url').show();
                    } else {
                        $('#logoutredirect_url').hide();
                    }

                    if ($('#emailnotification').is(':checked')) {
                        $('#emailnotificationcontent').show();
                    } else {
                        $('#emailnotificationcontent').hide();
                    }

                    if ($('#termsconditions').is(':checked')) {
                        $('#termsconditionsMSG').show();
                        $('#termsconditionsURL').show();
                    } else {
                        $('#termsconditionsMSG').hide();
                        $('#termsconditionsURL').hide();
                    }
                }

                $('#adminbar').click(function() {
                    $('#adminbar_roles').toggle();
                });

                //Antispam fields
                $('#gcaptcha').click(function() {
                    if ($(this).is(':checked')) {
                        $('#antispam').prop('checked', false);
                        $('#antispam').prop('disabled', true);
                        $('#gcaptcha_sitekey-label').show()
                        $('#gcaptcha_secretkey-label').show()
                    } else {
                        $('#antispam').prop('disabled', false);
                        $('#gcaptcha_sitekey-label').hide()
                        $('#gcaptcha_secretkey-label').hide()
                    }
                });

                $('#antispam').click(function() {
                    if ($(this).is(':checked')) {
                        $('#gcaptcha').prop('checked', false);
                        $('#gcaptcha').prop('disabled', true);
                    } else {
                        $('#gcaptcha').prop('disabled', false);
                    }
                });

                var selected_roles = <?php echo json_encode($newuserroles); ?>;
                $('select#newuserroles').find('option').each(function() {
                    if (jQuery.inArray($(this).val(), selected_roles) < 0)
                        $(this).attr('selected', false);
                    else
                        $(this).attr('selected', true);
                });

                $('#chooserole').click(function() {
                    $('#newuserroles').toggle();
                });

                $('#automaticlogin').click(function() {
                    $('#urlredirect').toggle();

                    if ($(this).is(':checked'))
                        $('#emailvalidation').prop('checked', false);
                });

                $('#emailvalidation').click(function() {
                    if ($(this).is(':checked')) {
                        $('#automaticlogin').prop('checked', false);
                        $('#urlredirect').hide();
                    }
                });

                $('#loginredirect').click(function() {
                    $('#loginredirect_url').toggle();
                });

                $('#logoutredirect').click(function() {
                    $('#logoutredirect_url').toggle();
                });

                $('#registerredirect').click(function() {
                    $('#registerredirect_url').toggle();
                });

                $('#emailnotification').click(function() {
                    if ($(this).is(':checked')) {
                        $('#emailnotificationcontent').show();
                    } else {
                        $('#emailnotificationcontent').hide();
                    }
                });              

                $('#termsconditions').click(function() {
                    if ($(this).is(':checked')) {
                        $('#termsconditionsMSG').show();
                        $('#termsconditionsURL').show();
                    } else {
                        $('#termsconditionsMSG').hide();
                        $('#termsconditionsURL').hide();
                    }
                });

                $('#like-donate-more').click(function() {
                    $('#like-donate').fadeToggle();
                    $('#like-donate-arrow').toggle();
                    $('#like-donate-smile').toggle();
                });

                displayInitCheck();
            });
        </script>
<?php
    }
}
