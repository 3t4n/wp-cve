<div class="wrap">
    <h1><?php _e("BuddyPress Registration Options"); ?></h1>
    <hr /><br />
    <form method="post" action="options.php">
        <?php settings_fields('buddy-ct-group'); ?>
        <?php do_settings_sections('buddy-ct-group'); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php _e("Member Cover Image"); ?></th>
                    <td>
                        <input id="buddy_member_cover" name="buddy_member_cover" type="checkbox" value="1" <?php if (esc_attr(get_option('buddy_member_cover')) == 1) { ?> checked="checked" <?php } ?>>
                        <label for="buddy_member_cover"><?php _e("Disable member cover images upload throughout the website"); ?></label>

                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Group Cover Image"); ?></th>
                    <td>
                        <input id="buddy_group_cover" name="buddy_group_cover" type="checkbox" value="1" <?php if (esc_attr(get_option('buddy_group_cover')) == 1) { ?> checked="checked" <?php } ?>>
                        <label for="buddy_group_cover"><?php _e("Disable Group cover images throughout the website"); ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Profile Image"); ?></th>
                    <td>
                        <input id="buddy_profile_image" name="buddy_profile_image" type="checkbox" value="1" <?php if (esc_attr(get_option('buddy_profile_image')) == 1) { ?> checked="checked" <?php } ?>>
                        <label for="buddy_profile_image"><?php _e("Allow users to use/browse website without the need for uploading profile pic"); ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Activity Tab"); ?></th>
                    <td>
                        <input id="buddy_hide_activity_tab" name="buddy_hide_activity_tab" type="checkbox" value="1" <?php if (esc_attr(get_option('buddy_hide_activity_tab')) == 1) { ?> checked="checked" <?php } ?>>
                        <label for="buddy_hide_activity_tab"><?php _e("Remove the activity tab from user profile for all users"); ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Custom Widget Template"); ?></th>
                    <td>
                        <select name="buddy_custom_widget_template" id="buddy_custom_widget_template" aria-describedby="buddy_custom_widget_template">
                            <option value="no" <?php if (esc_attr(get_option('buddy_custom_widget_template')) == "no") { ?> selected="selected" <?php } ?>>No</option>
                            <option value="yes" <?php if (esc_attr(get_option('buddy_custom_widget_template')) == "yes") { ?> selected="selected" <?php } ?>>Yes</option>                        
                        </select>
                        <p id="buddy_custom_widget_template" class="description">
                            <?php _e("Select 'YES' option only if you want to customize the Widget form/fields/layout. If you select 'Yes' option then copy the 'form-template.php' from 'wp-content/plugins/buddy-registration-widget/templates/' to 'wp-content/plugins/buddy-registration-widget/templates/custom' using a file manager like File Zilla or any appropriate tool. After copying you can do customization in the copied file and widget will use this customized template in frontend. Doing this will ensure that your changes will not be overwritten by future updates. "); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Custom Shortcode Template"); ?></th>
                    <td>
                        <select name="buddy_custom_shortcode_template" id="buddy_custom_shortcode_template" aria-describedby="buddy_custom_shortcode_template">
                            <option value="no" <?php if (esc_attr(get_option('buddy_custom_shortcode_template')) == "no") { ?> selected="selected" <?php } ?>>No</option>
                            <option value="yes" <?php if (esc_attr(get_option('buddy_custom_shortcode_template')) == "yes") { ?> selected="selected" <?php } ?>>Yes</option>   
                        </select>
                        <p id="buddy_custom_shortcode_template" class="description">
                            <?php _e("Select 'YES' option only if you want to customize the shortcode form/fields/layout. If you select 'Yes' option then copy the 'shortcode-form-template.php' from 'wp-content/plugins/buddy-registration-widget/templates/' to 'wp-content/plugins/buddy-registration-widget/templates/custom' using a file manager like File Zilla or any appropriate tool. After copying you can do customization in the copied file and shortcode will use this customized template in frontend. Doing this will ensure that your changes will not be overwritten by future updates. "); ?>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?>
    </form>
    <hr /><br />
</div>