<?php

/**
 * Description of class-mycred-learndash-settings
 *
 * @author soha
 */
class MyCred_LearnDash_Settings {

    protected $options;

    public function __construct() {
        $this->options = get_option('allow_buy_course_pts');
        add_action('admin_head', array($this, 'mycred_learndash_custom_js'));
        add_action('admin_init', array($this, 'check_learndash_plugin'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_filter('learndash_admin_tabs', array($this, 'admin_tabs'), 10, 1);
        add_filter('learndash_admin_tabs_on_page', array($this, 'admin_tabs_on_page'), 10, 3);
    }

    public function mycred_learndash_custom_js() {
        global $current_screen;
        ?>
        <script>
            jQuery(document).ready(function ($) {
                $("#pt-type-exchange").hide();
                if ($("#allow_buy_course_pts").is(":checked") == true) {
                    $("#pt-type-exchange").show();
                }

                $('.help-text').hide();

                $('.custom-option').each(function () {
                    $(this).find('.help-button').on('click', function () {
                        $(this).parent().parent().find('.help-text').toggle();
                    });
                });


                $("#allow_buy_course_pts").change(function () {
                    if (this.checked) {
                        $("#pt-type-exchange").show();
                    } else {
                        $("#pt-type-exchange").hide();
                    }
                });

            });
        </script>
        <?php
    }

    /**
     * Check if LearnDash plugin is active
     */
    public function check_learndash_plugin() {
        if (!is_plugin_active('sfwd-lms/sfwd_lms.php')) {
            add_action('admin_notices', array($this, 'admin_notices'));
            unset($_GET['activate']);
        }
    }

    /**
     * Display admin notice when LearnDash plugin is not activated
     */
    public function admin_notices() {
       $mycred_check_notice = '<div class="error"><p>' . __('LearnDash plugin is required to activate MyCred LearnDash add-on plugin. Please activate it first.', 'mycred') . '</p></div>';
       echo wp_kses_post( $mycred_check_notice ); 
    }

    public function register_settings() {
        register_setting('learndash_mycred_settings_group', 'allow_buy_course_pts');
        register_setting('learndash_mycred_settings_group', 'learndash_mycred_exchange_rate');
        register_setting('learndash_mycred_settings_group', 'learndash_point_type');
        register_setting('learndash_mycred_settings_group', 'learndash_allow_leaderboard');
    }

    /**
     * Add submenu page for settings page
     */
    public function admin_menu() {
        add_submenu_page('edit.php?post_type=sfwd-courses', __('MyCred Settings', 'mycred'), __('MyCred Settings', 'mycred'), 'manage_options', 'admin.php?page=learndash-mycred-settings', array($this, 'mycred_settings_page'));

        add_submenu_page('learndash-lms-non-existant', __('MyCred Settings', 'learndash-mycred'), __('MyCred Settings', 'mycred'), 'manage_options', 'learndash-mycred-settings', array($this, 'mycred_settings_page'));
    }

    /**
     * Add admin tabs for settings page
     * @param  array $tabs Original tabs
     * @return array       New modified tabs
     */
    public function admin_tabs($tabs) {
        $tabs['mycred'] = array(
            'link' => 'admin.php?page=learndash-mycred-settings',
            'name' => __('MyCred Settings', 'mycred'),
            'id' => 'admin_page_learndash-mycred-settings',
            'menu_link' => 'edit.php?post_type=sfwd-courses&page=sfwd-lms_sfwd_lms.php_post_type_sfwd-courses',
        );

        return $tabs;
    }

    /**
     * Display active tab on settings page
     * @param  array $admin_tabs_on_page Original active tabs
     * @param  array $admin_tabs         Available admin tabs
     * @param  int 	 $current_page_id    ID of current page
     * @return array                     Currenct active tabs
     */
    public function admin_tabs_on_page($admin_tabs_on_page, $admin_tabs, $current_page_id) {

        

        foreach ($admin_tabs as $key => $value) {
            if ($value['id'] == $current_page_id && $value['menu_link'] == 'edit.php?post_type=sfwd-courses&page=sfwd-lms_sfwd_lms.php_post_type_sfwd-courses') {

                $admin_tabs_on_page[$current_page_id][] = 'mycred';
                return $admin_tabs_on_page;
            }
        }

        return $admin_tabs_on_page;
    }

    /**
     * Output settings page
     */
    public function mycred_settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html_e('Cheatin huh?', 'learndash-stripe'));
        }
        ?>
        <div class="wrap">
            <h2 class="learndash-mycred-settings-header"><?php esc_html_e('MyCred Settings', 'learndash-mycred'); ?></h2>
            <form method="post" action="options.php">
                <div class="sfwd_options_wrapper sfwd_settings_left">
                    <div id="advanced-sortables" class="meta-box-sortables">
                        <div id="sfwd-courses_metabox" class="postbox learndash-mycred-settings-postbox">
                            <div class="handlediv" title="<?php esc_html_e('Click to toggle', 'learndash-mycred'); ?>"><br></div>
                            <div class="inside">
                                <?php settings_fields('learndash_mycred_settings_group'); ?>

                                <div class="custom-option">
                                    <label>
                                        <img class="help-button" alt=""
                                             src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/assets/images/question.png'); ?>">
                                             <?php echo esc_html_e('Allow using myCred points in buying courses', 'learndash-mycred'); ?>
                                    </label>
                                    <div class="option-wrapper">
                                        <div class="form-element two-cols">
                                            <input id="allow_buy_course_pts" type="checkbox" name="allow_buy_course_pts" value="1"
                                                   <?php echo get_option('allow_buy_course_pts') ? "checked" : "" ?>> <?php echo esc_html_e('Yes', 'learndash-mycred'); ?>
                                        </div>
                                        <div class="help-text"><?php echo 'allow user to enrolling in courses using their point(s) balance(s)' ?></div>
                                    </div>
                                </div>

                                <?php $this->exchange_rate_settings(); ?>

                                <div class="custom-option">
                                    <label>
                                        <img class="help-button" alt=""
                                             src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/assets/images/question.png'); ?>">
                                             <?php echo esc_html_e('Allow creating leaderboards based on myCred points ?', 'learndash-mycred'); ?>
                                    </label>
                                    <div class="option-wrapper">
                                        <div class="form-element two-cols">
                                            <input id="learndash_allow_leaderboard" type="checkbox" name="learndash_allow_leaderboard"
                                                   value="1"
                                                   <?php echo get_option('learndash_allow_leaderboard') ? "checked" : "" ?>> <?php echo esc_html_e('Yes', 'learndash-mycred'); ?>
                                        </div>
                                        <div class="help-text"><?php // echo 'Message test'   ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="submit" style="clear: both;">
                    <?php submit_button(__('Update Options »', 'learndash-mycred'), 'primary', 'submit', false); ?>
                </p>
            </form>
        </div>
        <?php
    }

    public function exchange_rate_settings() {
        $rate = get_option('learndash_mycred_exchange_rate');
        $pt_type = get_option('learndash_point_type');
        if (mycred_get_types()) {
            ?>
            <div class="custom-option" id="pt-type-exchange">
                <label>
                    <img class="help-button" alt=""
                         src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/assets/images/question.png'); ?>">
                         <?php echo esc_html_e('Exchange Rate for points types', 'learndash-mycred'); ?>
                </label>
                <div class="option-wrapper">
                    <div class="form-element two-cols">
                        <select name="learndash_point_type">
                            <?php foreach (mycred_get_types() as $k => $value) { ?>
                                <option value="<?php echo esc_attr($k) ?>" <?php echo $pt_type == $k ? 'selected' : '' ?>><?php echo esc_html($value) ?></option>
                            <?php } ?>
                        </select>
                        <input type="text" name="learndash_mycred_exchange_rate" value="<?php echo esc_attr($rate); ?>"
                               class="regular-text"><?php echo '$' ?>
                    </div>
                    <div class="help-text"><?php echo esc_html_e('Enter exchange rate to convert currency into points', 'learndash-mycred') ?></div>

                </div>

            </div>
            <?php
        }
    }

}
