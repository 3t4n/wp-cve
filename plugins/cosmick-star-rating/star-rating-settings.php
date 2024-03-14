<?php
add_action('admin_menu', 'csr_add_admin_menu');

function csr_add_admin_menu() {
    add_options_page('Cosmick Star Rating', 'Cosmick Star Rating', 'manage_options', 'cosmick_star_rating', 'cosmick_star_rating_settings_page');
    add_action('admin_init', 'csr_settings_init');
}

function csr_settings_init() {
    register_setting('csr-settings', 'csr_frm_title');
    register_setting('csr-settings', 'csr_frm_label_name');
    register_setting('csr-settings', 'csr_frm_label_review');
    register_setting('csr-settings', 'csr_frm_info');
    register_setting('csr-settings', 'csr_frm_label_select_rating');
    register_setting('csr-settings', 'csr_frm_label_submit');
    register_setting('csr-settings', 'csr_frm_success_message');
    register_setting('csr-settings', 'csr_frm_failure_message');
}

function cosmick_star_rating_settings_page() {
    ?>
    <div class="wrap">
        <h2>Cosmick Star Rating</h2>
        <p>Add Rating Form Settings</p>
        <form method="post" action="options.php">
            <?php settings_fields('csr-settings'); ?>
            <?php do_settings_sections('csr-settings'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Form Title</th>
                    <td>
                        <input type="text" name="csr_frm_title" class="regular-text" value="<?php echo esc_attr(get_option('csr_frm_title', 'Leave Us Your Testimonial')); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Form Information</th>
                    <td>
                        <textarea style="width: 25em;height: 10em;" name="csr_frm_info"><?php echo esc_attr(get_option('csr_frm_info', 'If you are a past, present, or future customer of ours, we value your opinion and please take a second to leave us your feedback.')); ?></textarea>          
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Company Name Label</th>
                    <td>
                        <input type="text" class="regular-text" name="csr_frm_label_name" value="<?php echo esc_attr(get_option('csr_frm_label_name', 'Name or Company')); ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Review Label</th>
                    <td>
                        <input type="text" class="regular-text" name="csr_frm_label_review" value="<?php echo esc_attr(get_option('csr_frm_label_review', 'Testimonial')); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Select Rating Label</th>
                    <td>
                        <input type="text" class="regular-text" name="csr_frm_label_select_rating" value="<?php echo esc_attr(get_option('csr_frm_label_select_rating', 'Select Your Rating')); ?>" />
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Submit Button Label</th>
                    <td>
                        <input type="text" class="regular-text" name="csr_frm_label_submit" value="<?php echo esc_attr(get_option('csr_frm_label_submit', 'Submit')); ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Success Message.</th>
                    <td><input type="text" class="regular-text" name="csr_frm_success_message" value="<?php echo esc_attr(get_option('csr_frm_success_message', 'Your review submitted successfully')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Failure Message.</th>
                    <td><input type="text" class="regular-text" name="csr_frm_failure_message" value="<?php echo esc_attr(get_option('csr_frm_failure_message', 'Please try again after sometime.!!!')); ?>" /></td>
                </tr>
            </table>

            <?php submit_button(); ?>

        </form>
    </div>
<?php } ?>