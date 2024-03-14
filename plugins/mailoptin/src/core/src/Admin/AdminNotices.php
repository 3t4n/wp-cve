<?php

namespace MailOptin\Core\Admin;

use MailOptin\Core\Repositories\EmailCampaignRepository;
use PAnD as PAnD;

class AdminNotices
{
    public function __construct()
    {
        add_action('admin_init', function () {
            if (\MailOptin\Core\is_mailoptin_admin_page()) {
                remove_all_actions('admin_notices');
            }

            do_action('mailoptin_admin_notices');

            add_action('admin_notices', array($this, 'optin_campaigns_cache_cleared'));
            add_action('admin_notices', array($this, 'template_class_not_found'));
            add_action('admin_notices', array($this, 'optin_class_not_found'));
            add_action('admin_notices', array($this, 'failed_campaign_retried'));
            add_action('admin_notices', array($this, 'email_campaign_count_limit_exceeded'));
            add_action('admin_notices', array($this, 'optin_branding_added_by_default'));
            add_action('admin_notices', array($this, 'review_plugin_notice'));
            add_action('admin_notices', array($this, 'show_woocommerce_features'));
            add_action('admin_notices', array($this, 'show_edd_features'));
            add_action('admin_notices', array($this, 'show_learndash_features'));
            add_action('admin_notices', array($this, 'show_tutorlms_features'));
            add_action('admin_notices', array($this, 'show_givewp_features'));
            add_action('admin_notices', array($this, 'show_lifterlms_features'));
            add_action('admin_notices', array($this, 'show_memberpress_features'));
            add_action('admin_notices', array($this, 'show_woocommerce_memberships_features'));
            add_action('admin_notices', array($this, 'show_restrict_content_pro_features'));
            add_action('admin_notices', array($this, 'show_pmpro_features'));
            add_action('admin_notices', array($this, 'show_wpforms_features'));
            add_action('admin_notices', array($this, 'show_cf7_features'));
            add_action('admin_notices', array($this, 'show_forminator_features'));
            add_action('admin_notices', array($this, 'show_ninja_forms_features'));
            add_action('admin_notices', array($this, 'show_gravity_forms_features'));

            add_filter('removable_query_args', array($this, 'removable_query_args'));
        });

        add_action('admin_init', array('PAnD', 'init'));
        add_action('admin_init', array($this, 'dismiss_leave_review_notice_forever'));
    }

    public function is_admin_notice_show()
    {
        return apply_filters('mo_ads_admin_notices_display', true);
    }

    public function removable_query_args($args)
    {
        $args[] = 'email-campaign-error';
        $args[] = 'optin-cache';
        $args[] = 'settings-updated';
        $args[] = 'license-settings-updated';
        $args[] = 'failed-campaign';
        $args[] = 'fbca';

        return $args;
    }

    /**
     * Notice shown when optin campaign caches has been successfully cleared.
     */
    public function optin_campaigns_cache_cleared()
    {
        if ( ! is_super_admin(get_current_user_id())) return;

        if (isset($_GET['optin-cache']) && $_GET['optin-cache'] == 'cleared') : ?>
            <div id="message" class="updated notice is-dismissible">
                <p>
                    <?php _e('Optin campaigns cache successfully cleared.', 'mailoptin'); ?>
                </p>
            </div>
        <?php endif;
    }

    /**
     * Template class not found - admin notice.
     */
    public function template_class_not_found()
    {
        if ( ! is_super_admin(get_current_user_id()))
            return;

        if (isset($_GET['email-campaign-error']) && $_GET['email-campaign-error'] == 'class-not-found') : ?>
            <div id="message" class="notice notice-error is-dismissible">
                <p>
                    <?php
                    _e('There was an error fetching email campaign template dependency.', 'mailoptin');
                    ?>
                </p>
            </div>
        <?php endif;
    }

    /**
     * Optin template class not found - admin notice.
     */
    public function optin_class_not_found()
    {
        if ( ! is_super_admin(get_current_user_id()))
            return;

        if (isset($_GET['optin-error']) && $_GET['optin-error'] == 'class-not-found') : ?>
            <div id="message" class="notice notice-error is-dismissible">
                <p>
                    <?php _e('There was an error fetching optin dependency. Try again or select another template.', 'mailoptin'); ?>
                </p>
            </div>
        <?php endif;
    }

    /**
     * Template class not found - admin notice.
     */
    public function failed_campaign_retried()
    {
        if ( ! is_super_admin(get_current_user_id())) return;

        if (isset($_GET['failed-campaign']) && $_GET['failed-campaign'] == 'retried') : ?>
            <div id="message" class="updated notice is-dismissible">
                <p>
                    <?php _e('Email campaigned resent.', 'mailoptin'); ?>
                </p>
            </div>
        <?php endif;
    }

    /**
     * Display notice that branding is now included by default in mailoptin optin forms
     */
    public function optin_branding_added_by_default()
    {
        if ( ! PAnD::is_admin_notice_active('optin-branding-added-by-default-forever')) {
            return;
        }

        if (MAILOPTIN_VERSION_NUMBER > '1.1.2.0') return;

        $learn_more = 'https://mailoptin.io/article/make-money-mailoptin-branding/?ref=wp_dashboard';

        $notice = sprintf(
            __('MailOptin branding is now included on all optin forms unless you explicitly disabled it at "Configuration" panel in form builder. %sLearn more%s', 'mailoptin'),
            '<a href="' . $learn_more . '" target="_blank">',
            '</a>'
        );

        echo '<div data-dismissible="optin-branding-added-by-default-forever" class="update-nag notice notice-warning is-dismissible">';
        echo "<p><strong>$notice</strong></p>";
        echo '</div>';
    }

    public function dismiss_leave_review_notice_forever()
    {
        if ( ! empty($_GET['mo_admin_action']) && $_GET['mo_admin_action'] == 'dismiss_leave_review_forever') {
            update_option('mo_dismiss_leave_review_forever', true);

            wp_safe_redirect(esc_url_raw(remove_query_arg('mo_admin_action')));
            exit;
        }
    }

    /**
     * Display one-time admin notice to review plugin at least 7 days after installation
     */
    public function review_plugin_notice()
    {
        if ( ! current_user_can('manage_options')) return;

        if ( ! PAnD::is_admin_notice_active('review-plugin-notice-forever')) return;

        if (get_option('mo_dismiss_leave_review_forever', false)) return;

        $install_date = get_option('mo_install_date', '');

        if (empty($install_date)) return;

        $diff = round((time() - strtotime($install_date)) / 24 / 60 / 60);

        if ($diff < 7) return;

        $review_url = 'https://wordpress.org/support/plugin/mailoptin/reviews/?filter=5#new-post';

        $dismiss_url = esc_url_raw(add_query_arg('mo_admin_action', 'dismiss_leave_review_forever'));

        $notice = sprintf(
            __('Hey, I noticed you have been using MailOptin for at least 7 days now - that\'s awesome! Could you please do me a BIG favor and give it a %1$s5-star rating on WordPress?%2$s This will help us spread the word and boost our motivation - thanks!', 'mailoptin'),
            '<a href="' . $review_url . '" target="_blank">',
            '</a>'
        );
        $label  = __('Sure! I\'d love to give a review', 'mailoptin');

        $dismiss_label = __('Dismiss Forever', 'mailoptin');

        $notice .= "<div style=\"margin:10px 0 0;\"><a href=\"$review_url\" target='_blank' class=\"button-primary\">$label</a></div>";
        $notice .= "<div style=\"margin:10px 0 0;\"><a href=\"$dismiss_url\">$dismiss_label</a></div>";

        echo '<div data-dismissible="review-plugin-notice-forever" class="update-nag notice notice-warning is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    /**
     * Display notice when limit of created email campaign is exceeded
     */
    public function email_campaign_count_limit_exceeded()
    {
        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

        if ( ! PAnD::is_admin_notice_active('email-campaign-count-limit-exceeded-3')) {
            return;
        }

        if (EmailCampaignRepository::campaign_count() < 1) return;

        if (strpos(\MailOptin\Core\current_url_with_query_string(), MAILOPTIN_EMAIL_CAMPAIGNS_SETTINGS_PAGE) === false) return;

        $upgrade_url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=email_campaign_limit';
        $notice      = sprintf(__('Upgrade to %s now to create multiple email automation with advance targeting and option to send directly to your email list subscribers.', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">' . __('MailOptin premium', 'mailoptin') . '</a>'
        );
        echo '<div data-dismissible="email-campaign-count-limit-exceeded-3" class="updated notice notice-success is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_woocommerce_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_woocommerce_features-forever')) {
            return;
        }

        if ( ! class_exists('WooCommerce')) return;

        $upgrade_url = 'https://mailoptin.io/integrations/woocommerce/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=woo_admin_notice';
        $notice      = sprintf(__('Did you know you can display targeted message & optin forms across your WooCommerce store, add customers to your email list after their purchase, automatically send email of new products and send newsletters to active subscribers and members in WooCommerce Memberships and Subscriptions plugins? %sLearn more%s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>'
        );
        echo '<div data-dismissible="show_woocommerce_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_edd_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_edd_features-forever')) {
            return;
        }

        if ( ! class_exists('\Easy_Digital_Downloads')) return;

        $upgrade_url = 'https://mailoptin.io/integrations/easy-digital-downloads/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=edd_admin_notice';
        $notice      = sprintf(__('Did you know you can add Easy Digital Downloads customers to your email list after they purchase any product or based on their purchased product, send newsletters and automated email of new downloads? %sLearn more%s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>'
        );
        echo '<div data-dismissible="show_edd_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_learndash_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_learndash_features-forever')) {
            return;
        }

        if ( ! class_exists('\SFWD_LMS')) return;

        $upgrade_url = 'https://mailoptin.io/article/learndash-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=learndash_admin_notice';
        $notice      = sprintf(__('Did you know you can add LearnDash students to your email list after they enroll to any course or based on the group or course they are enrolled in? %sLearn more%s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>'
        );
        echo '<div data-dismissible="show_learndash_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_tutorlms_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_tutorlms_features-forever')) {
            return;
        }

        if ( ! class_exists('\TUTOR\Tutor')) return;

        $upgrade_url = 'https://mailoptin.io/integrations/tutor-lms/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=tutorlms_admin_notice';
        $notice      = sprintf(__('Did you know you can add Tutor LMS students to your email list after enrollment to any course and send automated emails and broadcasts to enrolled students? %sLearn more%s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>'
        );
        echo '<div data-dismissible="show_tutorlms_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_givewp_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_givewp_features-forever')) {
            return;
        }

        if ( ! class_exists('\Give')) return;

        $notice = sprintf(__('Did you know you can %1$sadd GiveWP donors to your email list%2$s after they have donated or based on the donation form they donated through %3$sand send emails to donors%2$s at anytime? %4$sLearn more%2$s', 'mailoptin'),
            '<a href="https://mailoptin.io/article/givewp-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=givewp_admin_notice" target="_blank">',
            '</a>',
            '<a href="https://mailoptin.io/article/send-emails-givewp-donors-wordpress/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=givewp_admin_notice" target="_blank">',
            '<a href="https://mailoptin.io/integrations/givewp/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=givewp_admin_notice" target="_blank">'
        );
        echo '<div data-dismissible="show_givewp_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_lifterlms_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_lifterlms_features-forever')) {
            return;
        }

        if ( ! function_exists('llms')) return;

        $upgrade_url = 'https://mailoptin.io/article/lifterlms-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=lifterlms_admin_notice';
        $notice      = sprintf(__('Did you know you can %1$sadd LifterLMS students to your email list%2$s after enrollment or based on the membership/course they are enrolled %3$sand send emails to students%2$s at anytime? %4$sLearn more%2$s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">',
            '</a>',
            '<a href="https://mailoptin.io/article/send-wordpress-emails-lifterlms-students/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=lifterlms_admin_notice" target="_blank">',
            '<a href="https://mailoptin.io/integrations/lifterlms/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=lifterlms_admin_notice" target="_blank">'
        );
        echo '<div data-dismissible="show_lifterlms_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_memberpress_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_memberpress_features-forever')) {
            return;
        }

        if ( ! class_exists('\MeprAppCtrl')) return;

        $upgrade_url = 'https://mailoptin.io/article/memberpress-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=memberpress_admin_notice';
        $notice      = sprintf(__('Did you know you can %1$sadd MemberPress members to your email list%2$s after membership subscription %3$sand send emails to members%2$s at anytime? %4$sLearn more%2$s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>',
            '<a href="https://mailoptin.io/article/send-wordpress-emails-memberpress-members/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=memberpress_admin_notice" target="_blank">',
            '<a href="https://mailoptin.io/integrations/memberpress/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=memberpress_admin_notice" target="_blank">'
        );
        echo '<div data-dismissible="show_memberpress_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_woocommerce_memberships_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_woocommerce_memberships_features-forever')) {
            return;
        }

        if ( ! class_exists('\WC_Memberships_Loader')) return;

        $upgrade_url = 'https://mailoptin.io/article/woocommerce-memberships-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=woocommerce_memberships_admin_notice';
        $notice      = sprintf(__('Did you know you can %1$sadd members in WooCommerce memberships to your email list%2$s after membership subscription %3$sand send emails to members%2$s at anytime?', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>',
            '<a href="https://mailoptin.io/article/send-emails-woocommerce-memberships/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=woocommerce_memberships_admin_notice" target="_blank">',
        );
        echo '<div data-dismissible="show_woocommerce_memberships_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_restrict_content_pro_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_restrict_content_pro_features-forever')) {
            return;
        }

        if ( ! class_exists('\Restrict_Content_Pro')) return;

        $upgrade_url = 'https://mailoptin.io/article/restrict-content-pro-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=restrict_content_pro_admin_notice';
        $notice      = sprintf(__('Did you know you can %1$sadd Restrict Content Pro members to your email list%2$s after membership subscription %3$sand send emails to members%2$s at anytime? %4$sLearn more%2$s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>',
            '<a href="https://mailoptin.io/article/send-wordpress-emails-restrict-content-pro-members/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=restrict_content_pro_admin_notice" target="_blank">',
            '<a href="https://mailoptin.io/integrations/restrict-content-pro/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=restrict_content_pro_admin_notice" target="_blank">'
        );
        echo '<div data-dismissible="show_restrict_content_pro_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_pmpro_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_pmpro_features-forever')) return;

        if ( ! defined('PMPRO_VERSION')) return;

        $upgrade_url = 'https://mailoptin.io/article/paid-memberships-pro-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=pmpro_admin_notice';
        $notice      = sprintf(__('Did you know you can %1$sadd Paid Memberships Pro members to your email list%2$s after membership subscription %3$sand send emails to members%2$s at anytime? %4$sLearn more%2$s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>',
            '<a href="https://mailoptin.io/article/send-wordpress-emails-paid-memberships-pro-members/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=pmpro_admin_notice" target="_blank">',
            '<a href="https://mailoptin.io/integrations/paid-memberships-pro/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=pmpro_admin_notice" target="_blank">'
        );
        echo '<div data-dismissible="show_pmpro_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_cf7_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_cf7_features-forever')) {
            return;
        }

        if ( ! class_exists('WPCF7')) return;

        $upgrade_url = 'https://mailoptin.io/article/contact-form-7-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=cf7_admin_notice';
        $notice      = sprintf(__('Did you know with MailOptin, you can connect Contact Form 7 to major email marketing software such as Mailchimp, AWeber, Campaign Monitor, MailerLite, ActiveCampaign? %sLearn more%s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>'
        );
        echo '<div data-dismissible="show_cf7_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_forminator_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_forminator_features-forever')) {
            return;
        }

        if ( ! class_exists('Forminator')) return;

        $upgrade_url = 'https://mailoptin.io/article/forminator-email-marketing-crm/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=forminator_admin_notice';
        $notice      = sprintf(__('Did you know with MailOptin, you can connect Forminator to major email marketing software such as Mailchimp, Constant Contact, MailerLite, ActiveCampaign, ConvertKit? %sLearn more%s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>'
        );
        echo '<div data-dismissible="show_forminator_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_ninja_forms_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_ninja_forms_features-forever')) {
            return;
        }

        if ( ! class_exists('Ninja_Forms')) return;

        $upgrade_url = 'https://mailoptin.io/article/ninja-forms-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=ninja_forms_admin_notice';
        $notice      = sprintf(__('Did you know with MailOptin, you can connect Ninja Forms to major email marketing software such as Mailchimp, AWeber, Campaign Monitor, MailerLite, ActiveCampaign? %sLearn more%s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>'
        );
        echo '<div data-dismissible="show_ninja_forms_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_gravity_forms_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_gravity_forms_features-forever')) {
            return;
        }

        if ( ! class_exists('GFForms')) return;

        $upgrade_url = 'https://mailoptin.io/article/gravity-forms-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=gravity_forms_admin_notice';
        $notice      = sprintf(__('Did you know with MailOptin, you can connect Gravity Forms to your email marketing software and CRM including Mailchimp, Brevo (Sendinblue), MailerLite, Ontraport, GetResponse? %sLearn more%s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>'
        );
        echo '<div data-dismissible="show_gravity_forms_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    public function show_wpforms_features()
    {
        if ( ! $this->is_admin_notice_show()) return;

        if ( ! PAnD::is_admin_notice_active('show_wpforms_features-forever')) {
            return;
        }

        if ( ! class_exists('WPForms\WPForms')) return;

        $upgrade_url = 'https://mailoptin.io/article/wpforms-email-marketing-crm/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=wpforms_admin_notice';
        $notice      = sprintf(__('Did you know with MailOptin, you can connect WPForms to major email marketing software such as Mailchimp, ConvertKit, MailerLite, HubSpot, Brevo (Sendinblue)? %sLearn more%s', 'mailoptin'),
            '<a href="' . $upgrade_url . '" target="_blank">', '</a>'
        );
        echo '<div data-dismissible="show_wpforms_features-forever" class="notice notice-info is-dismissible">';
        echo "<p>$notice</p>";
        echo '</div>';
    }

    /**
     * @return AdminNotices
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}