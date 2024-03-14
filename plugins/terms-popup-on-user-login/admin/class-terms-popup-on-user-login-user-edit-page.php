<?php

use WpLHLAdminUi\Forms\AdminForm;
use WpLHLAdminUi\LicenseKeys\LicenseKeyHandler;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.lehelmatyus.com
 * @since      1.0.0
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/admin
 * @author     Lehel Matyus <contact@lehelmatyus.com>
 */
class Terms_Popup_On_User_Login_User_Edit {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Is License Key active
     */
    private $license_is_active;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $license_key_handler = new LicenseKeyHandler(new TPUL_LicsenseKeyDataProvider());
        $this->license_is_active = $license_key_handler->is_active();
    }

    // function __has_accepted_terms($user_id, $bool_answer = false) {


    //     /**
    //      * Detailed answer with Label and checkmark etc.
    //      */

    //     return $this->__get_has_accepted_terms_labels($user_accepted_check);
    // }


    /**
     * Adds a Checkbox to the user profile edit page for admins to see if the curent user they are looking at accepted or not the terms
     */
    function show_user_profile_accepted_field($user) {

        $user_state_manager = new TPUL_User_State($user->ID);
        $user_accepted_date  = $user_state_manager->get_user_accepted_date();
        $user_accepted_latest_check = $user_state_manager->did_accept_latest_terms();
        $user_accepted_check = $user_state_manager->has_accepted_terms(true);

?>
        <h3><?php _e("Terms Popup on User Login", "terms-popup-on-user-login"); ?></h3>

        <table class="form-table">
            <tr>
                <th></th>
                <td>
                    <input class="tpul_user_accepted_check" type="checkbox" id="tpul_user_accepted_checkbox" name="tpul_user_accepted_checkbox" aria-describedby="tpul_user_accepted_checkbox_desc" value="on" <?php checked(1, $user_accepted_check, true) ?> disabled>
                    <label for="tpul_user_accepted_checkbox"><?php _e("User Accepted Terms at Login ", 'terms-popup-on-user-login'); ?></label>
                    <br />
                    <p class="description"><?php _e("Checked means this user logged in at some point and accepted user agreement.", 'terms-popup-on-user-login'); ?></p>
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <input class="tpul_user_accepted_latest_check" type="checkbox" id="tpul_user_accepted_latest_checkbox" name="tpul_user_accepted_latest_checkbox" aria-describedby="tpul_user_accepted_latest_checkbox_desc" value="on" <?php checked(1, $user_accepted_latest_check, true) ?> disabled>
                    <label for="tpul_user_accepted_latest_checkbox"><?php _e("User Accepted Latest Terms at Login ", 'terms-popup-on-user-login'); ?></label>
                    <br />
                    <p class="description"><?php _e("Checked means user accepted latest terms, even after the latest terms or user reset.", 'terms-popup-on-user-login'); ?></p>
                </td>
            </tr>

            <tr>
                <th><label for="tpul_user_accepted_date"><?php _e("User Accepted Terms date ", 'terms-popup-on-user-login'); ?></label></th>
                <td>
                    <?php if ($this->license_is_active) : ?>
                        <input class="tpul_user_accepted_date" type="text" id="tpul_user_accepted_date" name="tpul_user_accepted_date" aria-describedby="tpul_user_accepted_date_desc" value="<?php echo $user_accepted_date; ?>" disabled>
                        <br />
                    <?php endif; ?>
                    <p class="description">
                        <?php

                        if (!$this->license_is_active) {
                            _e(' This is a premium feature.', 'terms-popup-on-user-login');
                            echo ' <a href="/wp-admin/options-general.php?page=terms_popup_on_user_login_options&tab=general_options">';
                            _e('Activate Key.', 'terms-popup-on-user-login');
                            echo "</a>";
                        } else {
                            _e("Latest date and time when user last accepted the terms.", 'terms-popup-on-user-login');
                        }
                        ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th><label for="tpul_user_accepted_reset_User"><?php _e("Reset Terms for this user", 'terms-popup-on-user-login'); ?></label></th>
                <td>
                    <?php
                    $reset_disabled = false;
                    if (!$this->license_is_active) {
                        $reset_disabled = true;
                    }

                    $reset_link_text = __("Reset Terms for this user", 'terms-popup-on-user-login');
                    $onclick_event_name = "resetSingleUser";
                    AdminForm::button__active_key_required(
                        $this->license_is_active,
                        [],
                        $reset_link_text,
                        "tpul_reset_single_user_btn",
                        $reset_disabled,
                        $onclick_event_name,
                        [
                            'data-user-id = ' . $user->ID
                        ]
                    );

                    ?>
                    <p class="description"><?php _e("Clicking this button will reset the user's state. He will be prompted to accept terms again.", 'terms-popup-on-user-login'); ?></p>
                    <?php if (!$this->license_is_active) : ?>
                        <p class="description"><?php _e('"Reset Terms for this user" is apremium feature.', 'terms-popup-on-user-login'); ?></p>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <th><label for="tpul_user_accepted_date"><?php _e("User Action Log Preview", 'terms-popup-on-user-login'); ?></label></th>
                <td>
                    <?php

                    $output = "";
                    if ($this->license_is_active) {

                        $loggin_turned_on = get_option('tpul_addv_logging');

                        if ($loggin_turned_on) {

                            $user_log = termspul\Tpul_DB::fetch($user->ID);
                            $user_log = array_slice($user_log, 0, 20);

                            $date_format = get_option('date_format');
                            $time_format = get_option('time_format');

                            $output .= __('Advanced logging is turned on. ', 'terms-popup-on-user-login');
                            if (count($user_log)) {

                                $output .= "<table class='tpul__report_table'>";
                                $output .= "<tr>";
                                $output .= "<th>Date</th>";
                                $output .= "<th>Action</th>";
                                $output .= "</tr>";
                                foreach ($user_log as $key => $log_item) {

                                    $user_accepted_date = mysql2date("{$date_format} {$time_format}", $log_item->created_at);

                                    $output .= "<tr>";
                                    $output .= "<td>{$user_accepted_date}</td>";
                                    $output .= "<td>{$log_item->user_action}</td>";
                                    $output .= "</tr>";
                                }
                                $output .= "</table>";
                            } else {
                                $output = __('No data yet.', 'terms-popup-on-user-login');
                            }
                        } else {
                            $output = __('Advanced logging needs to be turned on for this feature. ', 'terms-popup-on-user-login');

                            $output .= ' <a href="/wp-admin/options-general.php?page=terms_popup_on_user_login_options&tab=reset_users_options">';
                            $output .= __('Turn On advanced logging.', 'terms-popup-on-user-login');
                            $output .= "</a>";
                        }
                    } else {
                        $output = __('This is a premium feature.', 'terms-popup-on-user-login');
                    }


                    echo $output;

                    ?>
                    <style>
                        .tpul__report_table th,
                        .tpul__report_table td {
                            padding: 5px 0;
                        }
                    </style>

                </td>

            </tr>

        </table>
<?php
    }


    function add_accepted_column_to_userlist($column_headers) {

        if ($this->license_is_active) {
            $column_title = __('Terms accepted', 'terms-popup-on-user-login');
        } else {
            $column_title = __('Terms accepted <a href="/wp-admin/options-general.php?page=terms_popup_on_user_login_options&tab=general_options"><span class="dashicons dashicons-editor-help"></span></a>', 'terms-popup-on-user-login');
        }

        $column_headers['tpul_accepted_terms_col'] = $column_title;
        return $column_headers;
    }

    function add_accepted_column_data($value, $column_name, $user_id) {

        if ('tpul_accepted_terms_col' == $column_name) {
            if ($this->license_is_active) {
                $user = get_userdata($user_id);

                $user_state_manager = new TPUL_User_State($user->ID);
                $user_accepted_date  = $user_state_manager->get_user_accepted_date();

                $data = "";
                $data .= $user_state_manager->get_has_accepted_terms_labels();
                $data .= "<br> &nbsp;&nbsp;&nbsp;&nbsp;";
                $the_date = $user_accepted_date;
                if (!empty($the_date)) {
                    $data .= $user_accepted_date . "";
                }

                return $data;
            } else {
                $value = __('License key needed <br> for this feature.', 'terms-popup-on-user-login');
            }
        }

        return $value;
    }
}
