<?php

$options = get_option(BAP_OPTIONS);
$email_template_new_order_admin = get_option(BAP_EMAIL_NEW_ORDER_ADMIN);
$email_template_new_order_user = get_option(BAP_EMAIL_NEW_ORDER_USER);

$message = '';
$error = '';

if (isset($_POST['submit'])) {

    if (isset($_POST['current-tab']) && $_POST['current-tab'] === '1') {
        if (isset($_POST['email'])) {
            $options['email'] = strip_tags(stripslashes($_POST['email']));
        }

        if (isset($_POST['cart-expiration-time'])) {
            $options['cart-expiration-time'] = strip_tags(stripslashes($_POST['cart-expiration-time']));
            if ($options['cart-expiration-time'] < 1) {
                $options['cart-expiration-time'] = 1;
            }
        }

        if (isset($_POST['currency-symbol'])) {
            $options['currency-symbol'] = strip_tags(stripslashes($_POST['currency-symbol']));
        }

        $options_exist = get_option(BAP_OPTIONS);
        if ($options_exist !== false) {
            $cmp_email = strcmp($options_exist['email'], $options['email']);
            $cmp_cart_expiration_time = strcmp($options_exist['cart-expiration-time'], $options['cart-expiration-time']);
            $cmp_currency_symbol = strcmp($options_exist['currency-symbol'], $options['currency-symbol']);
            if ($cmp_email !== 0 || $cmp_cart_expiration_time !== 0 || $cmp_currency_symbol !== 0) {
                if (update_option(BAP_OPTIONS, $options) === false) {
                    $error = 'Error.';
                }
            }
        }

        if (!$error) {
            $message = __('General settings saved.', $this->plugin_slug);
        }
    }

    if (isset($_POST['current-tab']) && $_POST['current-tab'] === '2') {
        if (isset($_POST['subject-admin'])) {
            $email_template_new_order_admin['subject'] = stripslashes($_POST['subject-admin']);
        }

        if (isset($_POST['message-admin'])) {
            $email_template_new_order_admin['message'] = stripslashes($_POST['message-admin']);
        }

        if (isset($_POST['subject-user'])) {
            $email_template_new_order_user['subject'] = stripslashes($_POST['subject-user']);
        }

        if (isset($_POST['message-user'])) {
            $email_template_new_order_user['message'] = stripslashes($_POST['message-user']);
        }

        $email_template_new_order_admin_exist = get_option(BAP_EMAIL_NEW_ORDER_ADMIN);
        if ($email_template_new_order_admin_exist !== false) {
            $cmp_subject = strcmp($email_template_new_order_admin_exist['subject'], $email_template_new_order_admin['subject']);
            $cmp_message = strcmp($email_template_new_order_admin_exist['message'], $email_template_new_order_admin['message']);
            if ($cmp_subject !== 0 || $cmp_message !== 0) {
                if (update_option(BAP_EMAIL_NEW_ORDER_ADMIN, $email_template_new_order_admin) === false) {
                    $error = __('Error.', $this->plugin_slug);
                }
            }
        }

        $email_template_new_order_user_exist = get_option(BAP_EMAIL_NEW_ORDER_USER);
        if ($email_template_new_order_user_exist !== false) {
            $cmp_subject = strcmp($email_template_new_order_user_exist['subject'], $email_template_new_order_user['subject']);
            $cmp_message = strcmp($email_template_new_order_user_exist['message'], $email_template_new_order_user['message']);
            if ($cmp_subject !== 0 || $cmp_message !== 0) {
                if (update_option(BAP_EMAIL_NEW_ORDER_USER, $email_template_new_order_user) === false) {
                    $error = __('Error.', $this->plugin_slug);
                }
            }
        }

        if (!$error) {
            $message = __('E-mail templates saved.', $this->plugin_slug);
        }
    }
}

?>

    <div class="wrap">

        <?php screen_icon('options-general'); ?>
        <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

        <br>

        <?php if ($message || $error) : ?>
            <div class="<?php echo ($error) ? 'error' : 'updated' ?>" id="message">
                <p><?php echo $error . $message; ?></p>
            </div>
        <?php endif; ?>

        <div id="settings-tabs">

            <ul>
                <li><a href="#tabs-1"><?php _e("General", $this->plugin_slug); ?></a></li>
                <li><a href="#tabs-2"><?php _e("E-mail templates", $this->plugin_slug); ?></a></li>
            </ul>

            <div id="tabs-1">

                <?php include(BAP_DIR_PATH . 'views/includes/admin-settings-tab-general.php'); ?>

            </div>

            <div id="tabs-2">

                <?php include(BAP_DIR_PATH . 'views/includes/admin-settings-tab-email-templates.php'); ?>

            </div>

        </div>

    </div>

<?php if (isset($_POST['current-tab']) && !empty($_POST['current-tab'])) : ?>

    <script type="text/javascript">
        jQuery(function () {
            jQuery("#settings-tabs").tabs("option", "active", <?php echo (int)($_POST['current-tab']-1); ?>);
        });
    </script>

<?php endif; ?>