<?php

namespace src\fortnox;
if ( !defined('ABSPATH') ) die();

?>
<div class="fortnox_sync_products_shade"></div>
<div class="fortnox_sync_products_modal">
    <div class="modal_content">
        <section class="wc-backbone-modal-main" role="main">
            <header class="wc-backbone-modal-header">
                <h1><?php echo __("Product sync", WF_Plugin::TEXTDOMAIN ); ?></h1>
                <button class="fortnox_modal_close modal-close modal-close-link dashicons dashicons-no-alt">
                    <span class="screen-reader-text">Close modal panel</span>
                </button>
            </header>
            <article >
                <div class="fortnox-product-sync-progress">
                    <h4><?php echo __("Progress", WF_Plugin::TEXTDOMAIN ); ?></h4>
                    <div class="fortnox_sync_products_progress_bar">
                        <div class="outline">
                            <div class="fill"></div>
                        </div>
                        <div class="text">0%</div>
                    </div>
                </div>
                <p id="fortnox_product_sync_progress_description" class="description"><?php echo __("Click start to begin process.", WF_Plugin::TEXTDOMAIN ); ?></p>
            </article>
            <footer>
                <div class="inner">
                    <div>
                        <input class="button button-primary button-large" aria-label="<?php echo __("Start syncing", WF_Plugin::TEXTDOMAIN ); ?>"
                               id="fortnox_sync_products_btn" name="fortnox_sync_products_btn" type="button" value="<?php echo __("Start", WF_Plugin::TEXTDOMAIN ); ?>">
                    </div>
                </div>
            </footer>
        </section>
    </div>
</div>
