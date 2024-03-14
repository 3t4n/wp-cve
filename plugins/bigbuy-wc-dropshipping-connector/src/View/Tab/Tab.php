<?php

namespace WcMipConnector\View\Tab;

defined('ABSPATH') || exit;

class Tab
{
    public const CONFIGURATION_PAGE_VIEW = 'configuration';
    public const PUBLICATION_PAGE_VIEW = 'publication';
    public const SHIPPING_SERVICE_PAGE_VIEW = 'shipping-service';

    /**
     * @param string $pageTab
     */
    public function showTabs(string $pageTab): void
    {
        ?>
            <nav class="nav-tab-wrapper">
                <a href="?page=bigbuy-wc-dropshipping-connector" class="nav-tab <?php echo (!$pageTab ? esc_html('nav-tab-active') : esc_html('')) ?>"><?php esc_html_e('Minimum requirements', 'WC-Mipconnector');?></a>
                <a href="?page=bigbuy-wc-dropshipping-connector&tab=<?php echo esc_html(self::PUBLICATION_PAGE_VIEW)?>" class="nav-tab <?php echo ($pageTab === self::PUBLICATION_PAGE_VIEW ? esc_html('nav-tab-active') : esc_html('')) ?>"><?php esc_html_e('Publication', 'WC-Mipconnector');?></a>
                <a href="?page=bigbuy-wc-dropshipping-connector&tab=<?php echo esc_html(self::CONFIGURATION_PAGE_VIEW)?>" class="nav-tab <?php echo ($pageTab === self::CONFIGURATION_PAGE_VIEW ? esc_html('nav-tab-active') : esc_html('')) ?>"><?php esc_html_e('Configuration', 'WC-Mipconnector');?></a>
                <a href="?page=bigbuy-wc-dropshipping-connector&tab=<?php echo esc_html(self::SHIPPING_SERVICE_PAGE_VIEW)?>" class="nav-tab <?php echo ($pageTab === self::SHIPPING_SERVICE_PAGE_VIEW ? esc_html('nav-tab-active') : esc_html('')) ?>"><?php esc_html_e('Shipping Services', 'WC-Mipconnector');?></a>
            </nav>
        <?php
    }
}
