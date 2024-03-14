<?php

namespace WcMipConnector\View\Module;

defined('ABSPATH') || exit;

use WcMipConnector\View\Assets\Assets;

class PublicationContainer
{
    /** @var Assets  */
    protected $assets;

    public function __construct()
    {
        $this->assets = new Assets();
    }

    /**
     * @param string $isoCode
     */
    public function showPublicationView(string $isoCode): void
    {
        ?>
            <div id="publication-body">
                <h3><?php esc_html_e('Publication from the ', 'WC-Mipconnector');?><?php esc_html_e('Multi-Channel Integration Platform', 'WC-Mipconnector');?></h3>
                <section class="flex-col content-step2-section-mip">
                    <aside class="step-container" data-steps="1">
                        <figure class="step-item step-current">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 16 16">
                                <path d="M47.029 141.952L5 183.98V205h21.019l42.029-42.029v-1.914l-19.105-19.104h-1.914zm-22.925 55.405h-5.727v-5.734h-5.736v-5.729l5.736-5.734 11.462 11.463-5.735 5.734zm0-19.103l-3.821-3.82 26.746-26.746 3.821 3.821-26.746 26.745zm59.373-38.088l-13.643-13.643c-2.031-2.031-5.354-2.031-7.385 0l-2.946 2.945c-.021.021-.032.043-.052.063l-6.688 6.692 21.01 21.012 6.695-6.69c.02-.019.043-.03.061-.049l2.947-2.946c2.032-2.03 2.032-5.355.001-7.384zM2 11.501V14h2.5l7.373-7.373-2.499-2.499L2 11.501zm11.805-6.807c.26-.26.26-.68 0-.94l-1.56-1.559a.662.662 0 0 0-.94 0l-1.22 1.219 2.5 2.5 1.22-1.22z"/>
                            </svg>
                            <figcaption class="step-caption"><?php esc_html_e('Configuration', 'WC-Mipconnector');?></figcaption>
                        </figure>
                        <figure class="step-line"></figure>
                        <figure class="step-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                                <path d="M19.4 13c0-.3.1-.6.1-1s0-.7-.1-1l2.1-1.6c.2-.1.2-.4.1-.6l-2-3.5c-.1-.2-.4-.3-.6-.2l-2.5 1c-.5-.4-1.1-.7-1.7-1l-.4-2.7c.1-.2-.1-.4-.4-.4h-4c-.2 0-.5.2-.5.4l-.4 2.7c-.6.2-1.1.6-1.7 1l-2.4-1c-.3-.1-.5 0-.7.2l-2 3.5c-.1.2 0 .4.2.6l2.1 1.6c0 .3-.1.6-.1 1s0 .7.1 1l-2.1 1.6c-.2.1-.2.4-.1.6l2 3.5c.1.3.3.3.6.3l2.5-1c.5.4 1.1.7 1.7 1l.4 2.6c0 .2.2.4.5.4h4c.3 0 .5-.2.5-.4l.4-2.6c.6-.3 1.2-.6 1.7-1l2.5 1c.2.1.5 0 .6-.2l2-3.5c.1-.2.1-.5-.1-.6l-2.3-1.7zm-7.4 2.5c-1.9 0-3.5-1.6-3.5-3.5s1.6-3.5 3.5-3.5 3.5 1.6 3.5 3.5-1.6 3.5-3.5 3.5z"/>
                            </svg>
                            <figcaption class="step-caption"><?php esc_html_e('Languages and taxes', 'WC-Mipconnector');?></figcaption>
                        </figure>
                        <figure class="step-line"></figure>
                        <figure class="step-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 50 50">
                                <path d="M25 16.8l-1.4.2c-.1 0-.3.2-.3.3v2.3c0 .2.1.3.3.3l1.6.2c.2.4.4.7.6 1l-.6 1.4c-.1.1 0 .3.1.4l2.1 1.1c.1.1.3 0 .4-.1l.9-1.2c.4.1.7 0 1.1 0l.9 1.2c0 .2.2.2.3.1l2.1-1.1c.1-.1.2-.2.1-.4l-.6-1.4c.1-.2.2-.3.3-.5.1-.2.2-.4.3-.5l1.5-.2c.1 0 .3-.2.3-.3v-2.3c0-.2-.1-.3-.3-.3l-1.7-.1c-.2-.4-.4-.7-.6-1l.6-1.4c.1-.1 0-.3-.1-.4L30.8 13c-.1-.1-.3 0-.4.1l-.9 1.2c-.4-.1-.7 0-1.1 0l-.9-1.3c-.1-.1-.3-.2-.4-.1l-2 1c-.1.2-.1.2-.1.4l.6 1.4c-.1.2-.2.3-.3.5-.1.2-.2.4-.3.6zm2.7.8c.5-.7 1.4-1 2.2-.6.7.5 1 1.4.6 2.2-.5.7-1.4 1-2.2.6-.8-.4-1.1-1.4-.6-2.2zm-7 5.4l-.3 2c-.5.2-.8.5-1.2.7l-1.8-.7c-.2-.1-.4 0-.5.2l-1.5 2.5c-.1.2-.1.4.1.5l1.5 1.2c0 .2-.1.5-.1.7s0 .5.1.7L15.5 32c-.1.1-.2.3-.1.5l1.5 2.5c.1.2.3.2.5.2l1.8-.7c.4.3.7.6 1.2.7l.3 2c0 .2.2.3.4.3H24c.2 0 .4-.1.4-.3l.3-2c.5-.2.8-.5 1.2-.7l1.8.7c.2.1.4 0 .5-.2l1.5-2.5c.1-.2.1-.4-.1-.5l-1.5-1.2c0-.2.1-.5.1-.7s0-.5-.1-.7l1.5-1.2c.1-.1.2-.3.1-.5L28 25.2c-.1-.2-.2-.2-.4-.2l-1.8.7c-.4-.3-.7-.6-1.2-.7l-.3-2c0-.2-.2-.3-.4-.3H21c-.2.1-.3.1-.3.3zm1.7 5c1.1 0 2.1.9 2.1 2.1 0 1.1-.9 2.1-2.1 2.1-1.1 0-2.1-.9-2.1-2.1.2-1.3 1-2.1 2.1-2.1zM43 14.9C39.5 8.6 32.8 4.4 25.1 4.4 15 4.4 6.7 11.7 4.9 21.3h4.8c1.7-7 8-12.2 15.4-12.2 6.4 0 11.9 3.8 14.4 9.3l-2.9 2.9H46v-9.4s-3 3-3 3zm-17.9 26c-6.3 0-11.6-3.6-14.2-8.8l3.4-3.4H4V39l3.5-3.5c3.6 6 10.2 10 17.7 10 10.1 0 18.4-7.3 20.2-16.8h-4.8c-1.7 7.1-8 12.2-15.5 12.2z"/>
                            </svg>
                            <figcaption class="step-caption"><?php esc_html_e('Synchronisation', 'WC-Mipconnector');?></figcaption>
                        </figure>
                        <figure class="step-line"></figure>
                        <figure class="step-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 14 14">
                                <path d="M9.3 5.1h-.3v-1.2c-.1-1.6-1.9-2.8-4-2.8s-3.9 1.2-4 2.8v6.2c.1 1.6 1.8 2.9 4 2.9.9 0 1.6-.2 2.3-.5.6.3 1.3.5 2 .5 2.2 0 4-1.8 4-4s-1.8-3.9-4-3.9zm-3.9 5h-.4c-1.6 0-3-.9-3-2 .7.6 1.8 1 3 1h.3c0 .3 0 .6.1 1zm0-2h-.4c-1.6 0-3-.9-3-2 .7.6 1.8 1 3 1 .3 0 .6 0 .9-.1-.2.3-.4.7-.5 1.1zm-.4-6c1.6 0 3 .9 3 2s-1.4 2-3 2-3-.9-3-2 1.4-2 3-2zm-3 8c.7.6 1.8 1 3 1 .3 0 .5 0 .8-.1.2.3.4.6.6.8-.4.2-.9.3-1.4.3-1.6 0-3-.9-3-2zm7.3 2c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.4 3-3 3z"/>
                            </svg>
                            <figcaption class="step-caption"><?php esc_html_e('Price strategy', 'WC-Mipconnector');?></figcaption>
                        </figure>
                    </aside>
                    <div class="step2-container">
                        <p class="strongBB"><?php esc_html_e('Once the minimum requirements are met, you can now carry out the synchronisation of your store from the ', 'WC-Mipconnector');?>
                            <?php esc_html_e('Multi-Channel Integration Platform', 'WC-Mipconnector');?>.</p>
                        <p><strong><?php esc_html_e('What can be done with the BigBuy Dropshipping Connector plugin for WooCommerce?', 'WC-Mipconnector');?></strong></p>
                        <ul class="nomarginBB">
                            <li>-<?php esc_html_e('Catalogue synchronisation: images, texts, videos, categories, brands, tags, etc.', 'WC-Mipconnector');?></li>
                            <li>-<?php esc_html_e('Synchronisation of stock and prices in real time.', 'WC-Mipconnector');?></li>
                            <li>-<?php esc_html_e('Automated pricing rules by price ranges, categories and products.', 'WC-Mipconnector');?></li>
                            <li>-<?php esc_html_e('Synchronisation of orders: shipping prices, carriers and tracking.', 'WC-Mipconnector');?></li>
                        </ul>
                        <a class="u-pdt" href="<?php echo esc_attr('https://platform.bigbuy.eu/'.$isoCode.'/controlpanel/configuration/2');?>" target="_blank">
                            <button class="u-mgh button button-primary"><?php esc_html_e('Go to the', 'WC-Mipconnector');?>
                                <?php esc_html_e('Multi-Channel Integration Platform', 'WC-Mipconnector');?></button>
                        </a>
                        <a class="txt-decoration-none" href="<?php echo esc_attr('https://www.bigbuy.eu/academy/'.$isoCode.'/?utm_source=modulos&utm_medium=referral&utm_campaign=woocommerce');?>" target="_blank">
                            <div class="academy-button">
                                <img class="" width="25" height="25" src="<?php echo $this->assets->getImageAsset('academy.svg') ?>">
                                <span>
                                <strong><?php esc_html_e('Academy', 'WC-Mipconnector');?></strong> - <?php esc_html_e('How to synchronise WooCommerce via the ', 'WC-Mipconnector');?>
                                    <?php esc_html_e('Multi-Channel Integration Platform', 'WC-Mipconnector');?>
                            </span>
                            </div>
                        </a>
                    </div>
                </section>
            </div>
        <?php
    }
}
