<?php

/**
 * Description of class-mycred-learndash-course-settings
 * 
 * @author soha
 */
class MyCred_LearnDash_Course_Settings {

    protected $options;

    public function __construct() {
        $this->options = get_option('allow_buy_course_pts');
        if ($this->options) {
            add_action('admin_head', array($this, 'mycred_learndash_custom_js'));
            add_action('add_meta_boxes', array($this, 'mycred_learndash_override_buy_pts'));
            add_action('save_post', array($this, 'mycred_learndash_override_data_buy_pts'));
        }
    }

    public function mycred_learndash_custom_js() {
        global $current_screen;
        if ($current_screen->post_type == 'sfwd-courses') {
            ?>
            <script>
                jQuery(document).ready(function ($) {
                    $("select[name=sfwd-courses_course_price_type]").change(function () {
                        var price_type = $(this).val();
                        if (price_type == "open" || price_type == "free" || price_type == "closed") {
                            $("#mycred_learndash_override_course").css("display", "none");
                        } else {
                            $("#mycred_learndash_override_course").css("display", "block");
                        }
                    });
                });
            </script>
            <?php
        }
    }

    public function mycred_learndash_override_buy_pts() {
        add_meta_box('mycred_learndash_override_course', __('Allow Buying By Points', "mycred"), array($this, 'mycred_learndash_override_data'), 'sfwd-courses', 'normal');
    }

    public function mycred_learndash_override_data($post) {
        $allow = get_post_meta($post->ID, 'allow_buy_course_pts', true);
        ?>  
        <div class="custom-option">
            <label>
                <img class="help-button" alt="" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/assets/images/question.png'); ?>">
                <?php esc_html_e("Dont Allow using myCred points in buying this course?", "mycred"); ?>
            </label>
            <div class="option-wrapper">
                <div class="form-element two-cols">
                    <input type="checkbox" name="allow_buy_course_pts" value="1" <?php checked($allow, true); ?> /><?php echo esc_html_e('Yes', 'mycred'); ?>     
                </div>
                <div class="help-text"><?php esc_html_e('dont allow users to enrolling in this course using their point(s) balance(s)', 'mycred'); ?></div>
            </div>
        </div>

        <?php
    }

    /* Save overide data of Mycred */

    public function mycred_learndash_override_data_buy_pts($post_id) {

        // Save logic goes here. Don't forget to include nonce checks!
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST['allow_buy_course_pts']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['allow_buy_course_pts']))) != "") {
            // Update myCred_point
            update_post_meta($post_id, 'allow_buy_course_pts', sanitize_text_field(wp_unslash($_POST['allow_buy_course_pts'])));
        } else {
            // delete myCred_point
            delete_post_meta($post_id, 'allow_buy_course_pts');
        }
    }

}

new MyCred_LearnDash_Course_Settings;
