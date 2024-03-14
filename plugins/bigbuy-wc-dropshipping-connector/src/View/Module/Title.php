<?php

namespace WcMipConnector\View\Module;

defined('ABSPATH') || exit;

class Title
{
    public function getTitle(): void
    {
        ?>
            <h1 class="title-mip-woocommerce"><?php esc_html_e('BigBuy Dropshipping Connector for WooCommerce', 'WC-Mipconnector');?></h1>
        <?php
    }
}