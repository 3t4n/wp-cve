<div class="row">
    <div style="width:70%;float:left">
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <label><?php _e("Global Login Redirect", $this->plugin_name); ?></label>
                </th>
                <td>

                    <?php
                    $selected = (empty($wpmp_settings['wpmp_login_redirect']) || $wpmp_settings['wpmp_login_redirect'] == '-1') ? '' : $wpmp_settings['wpmp_login_redirect'];
                    wp_dropdown_pages($args = array('name' => 'wpmp_login_redirect', 'selected' => $selected, 'show_option_none' => 'Same page', 'option_none_value' => '-1'));
                    ?>
                    <br>
                    <em><?php _e("Redirect the user to a specific page after login (Default: Same page keeps the user on the same page after login).", $this->plugin_name); ?></em>


                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label><?php _e("Global Logout Redirect", $this->plugin_name); ?></label>
                </th>
                <td>
                    <?php
                    $selected = (empty($wpmp_settings['wpmp_logout_redirect']) || $wpmp_settings['wpmp_logout_redirect'] == '-1') ? '' : $wpmp_settings['wpmp_logout_redirect'];
                    wp_dropdown_pages($args = array('name' => 'wpmp_logout_redirect', 'selected' => $selected, 'show_option_none' => 'Same page', 'option_none_value' => '-1'));
                    ?>
                    <br>
                    <em><?php _e("Redirect the user to a specific page after logout (Default: Same page keeps the user on the same page on logout).", $this->plugin_name); ?></em>


                </td>
            </tr>
        </table>
    </div>
    <div style="width: 23%;float:right;padding: 15px;border: 1px solid #777;background: #fff;">
        <h1 style="text-align: center;font-size: 38px;padding-bottom: 30px;"> Shortcodes</h1>
        <p style=""><strong style="font-size: 18px;">Registration Form:</strong>
            <code style="font-size: 16px;">[wpmp_register_form]</code></p>
        <p> <strong style="font-size: 20px;">Login Form:</strong>
            <code style="font-size: 17px;">[wpmp_login_form]</code></p>
        <p> <strong style="font-size: 20px;">Profile Page:</strong>
            <code style="font-size: 17px;">[wpmp_user_profile]</code></p>
    </div>
</div>