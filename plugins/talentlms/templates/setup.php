<div class="wrap">
    <h1><?php esc_html_e('Setup', 'talentlms'); ?></h1>

    <div id='action-message' class='<?php echo (isset($action_status)) ? esc_attr($action_status) : ''; ?> fade'>
        <p><?php echo (isset($action_message)) ? esc_html($action_message) : ''; ?></p>
    </div>

    <form name="talentlms-setup-form" method="post" action="<?php echo esc_url(admin_url('admin.php?page=talentlms-setup')); ?>">
        <input type="hidden" name="action" value="tlms-setup">

        <table class="form-table">
            <tr>
                <th scope="row" class="form-field form-required <?php echo esc_attr($domain_validation); ?>">
                    <label for="tlms-domain"><?php esc_html_e("TalentLMS Domain", 'talentlms'); ?> <span class="description">(<?php esc_html_e("Required", 'talentlms'); ?>)</span>:</label>
                </th>
                <td class="form-field form-required <?php echo esc_attr($domain_validation); ?>">
                    <input id="tlms-domain" name="tlms-domain" style="width: 25em;" value="<?php echo esc_attr(get_option('tlms-domain')); ?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row" class="form-field form-required <?php echo esc_attr($api_validation); ?>">
                    <label for="tlms-apikey"><?php esc_html_e("API Key", 'talentlms'); ?> <span class="description"><?php esc_html_e("Required", 'talentlms'); ?></span>:</label>
                </th>
                <td class="form-field form-required <?php echo esc_attr($api_validation); ?>">
                    <input id="tlms-apikey" name="tlms-apikey" style="width: 25em;" value="<?php echo esc_attr(get_option('tlms-apikey')); ?>"/>
                </td>
            </tr>
            <tr style="border-top: 1px dashed #c9c9c9">
                <th scope="row" class="form-field form-required <?php echo esc_attr($enroll_user_validation); ?>">
                    <label for="tlms-enroll-user-to-courses"><?php esc_html_e("Enroll user to courses", 'talentlms'); ?> <span class="description"><?php esc_html_e("(Required)", 'talentlms'); ?></span>:</label>
                </th>
                <td class="form-field form-required <?php echo esc_attr($enroll_user_validation); ?>">
                    <select name="tlms-enroll-user-to-courses">
                        <option value="submission" <?php if(get_option('tlms-enroll-user-to-courses') == 'submission'){ echo 'selected="Selected"';}else{ echo '';} ?> ><?php esc_html_e("Upon order submission", 'talentlms'); ?></option>
                        <option value="completion" <?php if(get_option('tlms-enroll-user-to-courses') == 'completion'){ echo 'selected="Selected"';}else{ echo '';} ?> ><?php esc_html_e("Upon order completion", 'talentlms'); ?></option>
                    </select>
                </td>
            </tr>
        </table>

        <hr/>

        <p class="submit">
            <input class="button-primary" type="submit" name="Submit" value="<?php esc_html_e('Submit', 'talentlms'); ?>"/>
        </p>
    </form>

</div>
