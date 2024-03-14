<?php

namespace WunderAuto\Types\Actions;

/**
 * Class WooEmailHelper
 */
class WooEmailHelper extends \WC_Email
{
    /**
     * Wrap the body in the WooCommerce header and footer
     * Apply styles via Emogrifier if possible.
     *
     * @param string $heading
     * @param string $body
     *
     * @return string|null
     */
    public function getHtml($heading, $body)
    {
        $body = $this->getHtmlBody($heading, $body);
        $body = $this->style_inline($body);

        return $body;
    }

    /**
     * Get the WooCommerce header and footer
     *
     * @param string $heading
     * @param string $message
     *
     * @return string
     */
    private function getHtmlBody($heading, $message)
    {
        // Buffer.
        ob_start();
        do_action('woocommerce_email_header', $heading, null);
        echo wpautop(wptexturize($message)); // WPCS: XSS ok.
        do_action('woocommerce_email_footer', null);

        // Get contents.
        $message = ob_get_clean();

        return $message === false ? '' : $message;
    }
}
