<?php

namespace WunderAuto\Types\Actions;

/**
 * Class WooCommerceEmail
 */
class WooCommerceEmail extends EmailBaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Send WooCommerce email', 'wunderauto');
        $this->description = __('Send html email using the WooCommerce template', 'wunderauto');
        $this->group       = 'Email';

        $this->docLink = "https://www.wundermatics.com/docs/sending-html-emails/#8-toc-title";
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        if (!defined('WC_ABSPATH')) {
            return false;
        }
        include_once WC_ABSPATH . 'includes/class-wc-emails.php';
        \WC_Emails::instance();

        $this->readConfig();

        if (!$this->to && !$this->bcc) {
            return false;
        }

        $customEmail = new WooEmailHelper();
        $content     = $customEmail->getHtml($this->heading, $this->body);

        $this->body = (string)$content;
        add_filter('wp_mail_content_type', [$this, 'mailContentType']);

        $this->sendEmail();

        return true;
    }
}
