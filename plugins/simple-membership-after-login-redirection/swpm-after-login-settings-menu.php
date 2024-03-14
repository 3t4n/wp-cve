<?php

add_action('swpm_after_main_admin_menu', 'swpm_alr_do_admin_menu');

function swpm_alr_do_admin_menu($menu_parent_slug) {
    add_submenu_page($menu_parent_slug, __("Login Redirection", 'simple-membership'), __("Login Redirection", 'simple-membership'), 'manage_options', 'swpm-login-redirection', 'swpm_alr_admin_interface');
}

function swpm_alr_admin_interface() {
    echo '<div class="wrap">';
    echo '<h1>After Login Redirection Settings</h1>';

    echo '<div id="poststuff"><div id="post-body">';

    if (isset($_POST['swpm_alr_save_settings'])) {
        $options = array(
            'redirect_to_last_page_enabled' => isset($_POST["redirect_to_last_page_enabled"]) ? '1' : '',
            'allow_custom_redirections' => isset($_POST["allow_custom_redirections"]) ? '1' : '',
        );
        update_option('swpm_alr_settings', $options); //store the results in WP options table
        echo '<div id="message" class="updated fade">';
        echo '<p>Settings Saved!</p>';
        echo '</div>';
    }

    $swpm_alr_settings = get_option('swpm_alr_settings');
    if(empty($swpm_alr_settings['redirect_to_last_page_enabled'])){
        $swpm_alr_settings['redirect_to_last_page_enabled'] = '';
    }
    $redirect_to_last_page_enabled = $swpm_alr_settings['redirect_to_last_page_enabled'];

    if(empty($swpm_alr_settings['allow_custom_redirections'])){
        $swpm_alr_settings['allow_custom_redirections'] = '';
    }
    $allow_custom_redirections = $swpm_alr_settings['allow_custom_redirections'];

    ?>

    <p style="background: #fff6d5; border: 1px solid #d1b655; color: #3f2502; margin: 10px 0;  padding: 5px 5px 5px 10px;">
        Read the <a href="https://simple-membership-plugin.com/configure-login-redirection-members/" target="_blank">usage documentation</a> to learn how to use the after login redirection addon.
    </p>

    <form action="" method="POST">

        <div class="postbox">
            <h3 class="hndle"><label for="title">After Login Redirection Settings</label></h3>
            <div class="inside">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Enable Redirect to Last Page</th>
                        <td>
                            <input name="redirect_to_last_page_enabled" type="checkbox"<?php if ($redirect_to_last_page_enabled != '') echo ' checked="checked"'; ?> value="1"/>
                            <p class="description">If enabled, the plugin will redirect the members to the last page (where they clicked on the login link) after the login. This will override any other after login redirection configured in the membership level.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Allow Custom Redirections</th>
                        <td>
                            <input name="allow_custom_redirections" type="checkbox"<?php if ($allow_custom_redirections != '') echo ' checked="checked"'; ?> value="1"/>
                            <p class="description">If enabled, the plugin will use the value of the parameter 'swpm_redirect_to' as redirection URL (if present). This will allow custom redirections, and this will override any other redirection configured in this plugin.</p>
                        </td>
                    </tr>
                </table>
            </div></div>
        <input type="submit" name="swpm_alr_save_settings" value="Save" class="button-primary" />

    </form>


    <?php
    echo '</div></div>'; //end of poststuff and post-body
    echo '</div>'; //end of wrap
}