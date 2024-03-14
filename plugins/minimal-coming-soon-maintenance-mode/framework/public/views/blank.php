<?php

/**
 * Renders the blank template for the plugin.
 *
 * @link       http://www.webfactoryltd.com
 * @since      1.0
 */

if (!defined('WPINC')) {
    die;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html($options['title']); ?></title>
    <?php if (!empty($options['favicon'])) : ?>
        <link rel="shortcut icon" href="<?php echo esc_url($options['favicon']); ?>" />
    <?php endif; ?>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
    <link rel="pingback" href="<?php esc_url(bloginfo('pingback_url')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo esc_url(CSMM_URL); ?>/framework/public/css/basic.css" />
    <?php
        if(!in_array($options["header_font"], array('Arial','Helvetica','Georgia','Times New Roman','Tahoma','Verdana','Geneva')) || !in_array($options["secondary_font"], array('Arial','Helvetica','Georgia','Times New Roman','Tahoma','Verdana','Geneva'))){
        ?>
        <script src='<?php echo esc_url(CSMM_URL) . '/framework/admin/js/webfont.js'; ?>'></script>
        <script>
            WebFont.load({
                bunny: {
                    families: ['<?php echo esc_attr($options["header_font"]); ?>', '<?php echo esc_attr($options["secondary_font"]); ?>']
                }
            });
        </script>
        <?php
        }
    ?>


    <?php
    // user defined css for the blank mode
    if (!empty($options['custom_css'])) {
        echo '<style>';
        CSMM::wp_kses_wf(stripslashes($options['custom_css']));
        echo '</style>';
    }
    ?>
</head>

<body>
    <?php

    // Custom html
    // Nothing else will be included here since we are serving a blank template
    $custom_html = stripslashes($options['custom_html']);

    // form
    if (!empty($custom_html) && false !== strpos($custom_html, '{{form}}')) {
        if (!empty($options['mailchimp_api']) && !empty($options['mailchimp_list'])) {
            // Checking if the form is submitted or not
            if (isset($_POST['signals_email'])) {
                // Processing begins
                $signals_email = strip_tags($_POST['signals_email']);

                if ('' === $signals_email) {
                    $code         = 'danger';
                    $response     = __('Please provide your email address.', 'minimal-coming-soon-maintenance-mode');
                } else {
                    $signals_email = filter_var(strtolower(trim($signals_email)), FILTER_SANITIZE_EMAIL);

                    if (strpos($signals_email, '@')) {
                        require_once CSMM_PATH . '/framework/admin/include/classes/class-mailchimp.php';

                        $MailChimp = new Signals_MailChimp($options['mailchimp_api']);
                        $api_url  = "/lists/" . $options['mailchimp_list'] . "/members";

                        $out_array =  array(
                            'email_address' => $signals_email,
                            'status' => 'pending'
                        );

                        $result = $MailChimp->post($api_url, $out_array);

                        if ($result['status'] == 400) {
                            $code         = 'danger';
                            if ($result['title'] == 'Member Exists') {
                                $response     = $options['message_subscribed'];
                            } else {
                                $response     = $result['detail'];
                            }
                        } elseif (isset($result['unique_email_id'])) {
                            $code         = 'success';
                            $response     = $options['message_done'];
                        }
                    } else {
                        $code             = 'danger';
                        $response         = $options['message_noemail'];
                    }
                }
            } // signals_email

            // Subscription form
            // Displaying errors as well if they are set
            $subscription_form = '<div class="subscription">';

            if (isset($code) && isset($response)) {
                $subscription_form .= '<div class="signals-alert signals-alert-' . $code . '">' . $response . '</div>';
            }

            $subscription_form .= '<form role="form" method="post">
					<input type="text" name="signals_email" autocomplete="email" placeholder="' . esc_attr($options['input_text']) . '">
					<input type="submit" name="submit" value="' . esc_attr($options['button_text']) . '">
				</form>';
            $subscription_form .= '</div>';

            // Replacing the form placeholder
            $custom_html = str_replace('{{form}}', $subscription_form, $custom_html);
        } // mailchimp_api && mailchimp_list
    } // custom_html

    // Output the user defined html
    CSMM::wp_kses_wf($custom_html);

    ?>
</body>

</html>