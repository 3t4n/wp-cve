<?php
/**
 * User update Page
 *
 * @package     Username_updater page
 * @since       1.0.5
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
{
    exit;
}
use \EasyUserNameUpdater\EasyUsernameUpdater;

function eup_user_update()
{
    if (isset($_REQUEST['update']))
    {
        global $wpdb;

        $id = (isset($_REQUEST['update']) && $_REQUEST['update'] != '' && is_numeric($_REQUEST['update'])) ? (int) trim($_REQUEST['update']) : false;

        $user_info = get_userdata($id);
        $result = $wpdb->get_results($wpdb->prepare("SELECT * from $wpdb->users WHERE ID = %d", $id));
        foreach ($result as $user)
        {
            $username = $user->user_login;
        }
        if (isset($_POST['_csrfToken']))
        {
            if (!wp_verify_nonce($_POST['_csrfToken'], 'eup_nonce_action'))
            {
                $errorMsg = "Invalid form submission.";
            }
            else
            {
                $eup = new EasyUsernameUpdater();
                $name = sanitize_user($_POST["user_login"]);
                if (empty($name))
                {
                    $errorMsg = "Error : You can not enter an empty username.";
                }
                elseif (username_exists($name))
                {
                    $errorMsg = "Error: This username ($name) is already exist.";
                }
                else
                {
                    $eup->eup_update($id, $name);
                    echo '<div class="updated"><p><strong>Username Updated!</strong></p></div>';
                    if (isset($_POST['user_notification']))
                    {
                        require_once (plugin_dir_path(__FILE__) . 'mail.php');
                    }
                }
            }
        }
?>
            <div class="wrap">
              <h1><?php echo esc_html( __('Update Username')) ?></h1>
              <?php if (isset($errorMsg))
                {
                    echo "<div class='error'><p><strong>" . esc_html($errorMsg) . "</strong></p></div>";
                } ?>
            </div>
            <form method="post" id="user_udate" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
                <table class="form-table">
                    <tr class="user-user-login-wrap">
                        <th><label for="olduser_login"><?php echo esc_html( __('Old Username'))  ?></label></th>
                        <td><strong><?php echo esc_html($username); ?></strong></td>
                    </tr>
                    <tr class="user-user-login-wrap">
                        <th><label for="user_login"><?php echo esc_html( __('New Username')) ?></label></th>
                        <td><input type="text" name="user_login" class="regular-text" id="user_login" value="<?php if (!empty($_POST["user_login"])) echo esc_html($name); ?>"/></td>
                    </tr>
                    <tr>
                        <th><?php echo esc_html( __('Send User Notification')) ?></th>
                        <td><label for="user_notification"><input type="checkbox" name="user_notification" id="user_notification" value="yes" <?php if (isset($_POST['user_notification'])) echo "checked='checked'"; ?>> <?php echo esc_html( __('Send the user an email about their updated username.')) ?></label></td>
                    </tr>
                </table>
                <?php wp_nonce_field('eup_nonce_action', '_csrfToken'); ?>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Update Username">
            </form>
             <p><a href="<?php echo esc_url(admin_url('admin.php?page=easy_username_updater')); ?>"><-<?php _e('Go Back', 'easy_username_updater') ?></a></p>
    <?php
    }
    else
    { ?>
    <script>
      window.location='<?php echo esc_url(admin_url('admin.php?page=easy_username_updater')); ?>'
    </script>
    <?php
    }
}
