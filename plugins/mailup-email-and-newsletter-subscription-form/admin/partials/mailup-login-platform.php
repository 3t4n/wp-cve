<?php declare(strict_types=1);

/**
 * Provide a admin area view for the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @see  https://mailup.it
 * @since 1.2.6
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="mailup-plugin-container">
    <div class="mailup-masthead">
        <div class="mailup-masthead__inside-container">
            <div class="mailup-masthead__logo-container">
                <a href="<?php _e('https://www.mailup.com', 'mailup'); ?>" target="_blank"
                    class="mailup-masthead__logo-link">
                    <img class="mailup-masthead__logo"
                        src="<?php echo plugin_dir_url(__DIR__).'/images/logo_vector.png'; ?>" alt="MailUp">
                </a>
            </div>
        </div>
    </div>
    <div class="mailup-lower">
        <div class="mailup-boxes">
            <div class="mailup-box">
                <div class="centered mailup-box-header">
                    <h2><?php _e('Create your integrated MailUp form!', 'mailup'); ?>
                    </h2>
                </div>
                <div class="mailup-setup-instructions">
                    <p><?php _e('Connect your account with MailUp to activate the plugin', 'mailup'); ?>
                    </p>
                    <div class="mailup_activate">
                        <a href="<?php echo $url_logon; ?>"
                            class="mailup-connect-button button button-primary"><?php _e('Connect your account', 'mailup'); ?></a>
                    </div>
                </div>
            </div>
            <br>
            <div class="mailup-box">
                <div class="mailup-trial-box centered">
                    <span><?php _e('Don\'t have an account?', 'mailup'); ?></span>
                    <a href="<?php _e('https://lp.mailup.com/en/free-trial/', 'mailup'); ?>"
                        target="_blank"><?php _e('Create a free trial', 'mailup'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>