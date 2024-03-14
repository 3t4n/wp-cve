<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class WS_Force_Login_Page_admin {
    
    public function __construct(){
		add_action( 'admin_menu', array( $this, 'ws_force_login_settings_create_menu' ) );
		add_action( 'admin_init', array( $this, 'ws_force_login_settings_register' ) );
		add_filter( 'plugin_action_links_ws-force-login-page/ws-force-login-page.php', array( $this, 'ws_force_login_action_links' ) );
    }
    
    public function ws_force_login_settings_create_menu() {
        add_options_page( __( 'WS Force Login Page Settings', 'ws-force-login-page' ), 'WS Force Login', 'manage_options', 'ws-force-login-settings', array( $this, 'ws_force_login_settings_page' ) );
    }

    public function ws_force_login_settings_register() {
        register_setting( 'ws-force-login-settings-group', 'wsforce-login-active-option' );
        register_setting( 'ws-force-login-settings-group', 'wsforce-login-message-option' );
    }

    public function ws_force_login_settings_page() {
        ?>
        <div class="wrap">
            <h2><?php _e( 'WS Force Login Page Settings', 'ws-force-login-page' ); ?></h2>
        </div>

        <form method="post" action="options.php">
            <?php settings_fields( 'ws-force-login-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo _e( 'Active', 'ws-force-login-page' ); ?></th>
                    <td>
                        <select id="active_options" name="wsforce-login-active-option">
                            <option value="0"<?php if (get_option('wsforce-login-active-option') == '0') { echo ' selected'; } ?>><?php _e('Deactivated') ?></option>
                            <option value="1"<?php if (get_option('wsforce-login-active-option') == '1') { echo ' selected'; } ?>><?php _e('Activated') ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php echo _e( 'Message', 'ws-force-login-page' ); ?></th>
                    <td>
                        <input class="regular-text" type="text" name="wsforce-login-message-option" value="<?php echo get_option('wsforce-login-message-option'); ?>" placeholder="<?php echo esc_html_e( 'Message for those who trie to get access to site', 'ws-force-login-page' ); ?>" />
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>

        </form>
        <?php
    }

    public function ws_force_login_action_links( $links ) {
        $mylinks = array(
            '<a href="' . admin_url( 'options-general.php?page=ws-force-login-settings' ) . '">Settings</a>',
        );
        return array_merge( $links, $mylinks );
    }
}

$wpse_ws_force_login_page_plugin_admin = new WS_Force_Login_Page_admin();
?>