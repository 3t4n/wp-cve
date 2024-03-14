<form action="" method="post">
    <input type="hidden" name="current-tab" value="2"/>

    <h3 class="title"><?php _e("New order admin template", $this->plugin_slug); ?></h3>

    <table class="form-table">
        <tbody>

        <tr valign="top">
            <th scope="row"><label for="subject-admin"><?php _e("Subject", $this->plugin_slug); ?></label></th>
            <td>
                <input type="text" class="regular-text" value="<?php echo isset($email_template_new_order_admin['subject']) ? esc_attr($email_template_new_order_admin['subject']) : ''; ?>" id="subject-admin" name="subject-admin">
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="message-admin"><?php _e("Message", $this->plugin_slug); ?></label></th>
            <td>
                <textarea rows="10" class="large-text" id="message-admin" name="message-admin"><?php echo isset($email_template_new_order_admin['message']) ? esc_textarea($email_template_new_order_admin['message']) : ''; ?></textarea>
                <br>
                <span class="description">
                    <?php _e("You can use the following keywords:", $this->plugin_slug); ?>
                    &lt;first_name&gt;,
                    &lt;last_name&gt;,
                    &lt;email&gt;,
                    &lt;phone&gt;,
                    &lt;notes&gt;,
                    &lt;date&gt;,
                    &lt;code&gt;,
                    &lt;places&gt;,
                    &lt;total_price&gt;,
                    &lt;status&gt;,
                    &lt;event_name&gt;,
                    &lt;event_start&gt;,
                    &lt;event_end&gt;,
                    &lt;scheme_name&gt;,
                    &lt;order_url&gt;
                </span>
            </td>
        </tr>

        </tbody>
    </table>

    <h3 class="title"><?php _e("New order customer template", $this->plugin_slug); ?></h3>

    <table class="form-table">
        <tbody>

        <tr valign="top">
            <th scope="row"><label for="subject-user"><?php _e("Subject", $this->plugin_slug); ?></label></th>
            <td>
                <input type="text" class="regular-text" value="<?php echo isset($email_template_new_order_user['subject']) ? esc_attr($email_template_new_order_user['subject']) : ''; ?>" id="subject-user" name="subject-user">
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="message-user"><?php _e("Message", $this->plugin_slug); ?></label></th>
            <td>
                <textarea rows="10" class="large-text" id="message-user" name="message-user"><?php echo isset($email_template_new_order_user['message']) ? esc_textarea($email_template_new_order_user['message']) : ''; ?></textarea>
                <br>
                <span class="description">
                    <?php _e("You can use the following keywords:", $this->plugin_slug); ?>
                    &lt;first_name&gt;,
                    &lt;last_name&gt;,
                    &lt;email&gt;,
                    &lt;phone&gt;,
                    &lt;notes&gt;,
                    &lt;date&gt;,
                    &lt;code&gt;,
                    &lt;places&gt;,
                    &lt;total_price&gt;,
                    &lt;status&gt;,
                    &lt;event_name&gt;,
                    &lt;event_start&gt;,
                    &lt;event_end&gt;,
                    &lt;scheme_name&gt;
                </span>
            </td>
        </tr>

        </tbody>
    </table>


    <p class="submit">
        <input type="submit" value="<?php _e("Save Changes", $this->plugin_slug); ?>" class="button button-primary" id="submit" name="submit">
    </p>
</form>