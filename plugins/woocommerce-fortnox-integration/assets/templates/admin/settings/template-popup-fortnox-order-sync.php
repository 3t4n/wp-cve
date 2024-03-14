<?php

namespace src\fortnox;
if ( !defined('ABSPATH') ) die();

$order_status_select = array_map( function( $status, $title ){
    return [
        "value" => substr( $status, 3),
        "title" => $title
    ];
}, array_keys( wc_get_order_statuses() ), wc_get_order_statuses() );

?>
<div class="fortnox_sync_orders_date_range_shade"></div>
<div class="fortnox_sync_orders_date_range_modal">
    <div class="modal_content">
        <section class="wc-backbone-modal-main" role="main">
            <header class="wc-backbone-modal-header">
                <h1><?= __("Order sync", WF_Plugin::TEXTDOMAIN ) ?></h1>
                <button class="fortnox_modal_close modal-close modal-close-link dashicons dashicons-no-alt">
                    <span class="screen-reader-text">Close modal panel</span>
                </button>
            </header>
            <article>
                <div class="fortnox-sync-on-date-range-section">
                    <?= sprintf(__("Synchronize orders placed from<br>%s to %s", WF_Plugin::TEXTDOMAIN ),
                        '<input id="order_sync_date_from" name="order_sync_date_from" type="date" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])">',
                        '<input id="order_sync_date_to" name="order_sync_date_to" type="date" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])">') ?>
                </div>
                <div class="fortnox-sync-on-status-section">
                    <label for="fortnox_sync_on_status"><?= __( 'Order status', WF_Plugin::TEXTDOMAIN ) ?></label>
                    <select name="fortnox_sync_on_status" id="fortnox_sync_on_status" class="regular-text">
                        <?php foreach ( $order_status_select as $status ) : ?>
                        <option value="<?php echo $status['value']; ?>"><?php echo $status['title']; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="fortnox-order-sync-range-progress">
                    <h4><?= __( 'Progress', WF_Plugin::TEXTDOMAIN ) ?></h4>
                    <div class="fortnox_order_sync_range_progress_bar">
                        <div class="outline">
                            <div class="fill"></div>
                        </div>
                        <div class="text">0%</div>
                    </div>
                    <p id="fortnox_order_sync_range_progress_description" class="description"></p>
                </div>
            </article>
            <footer>
                <div class="inner">
                    <div>
                        <input class="button button-primary button-large" aria-label="<?= __("Start syncing", WF_Plugin::TEXTDOMAIN ) ?>"
                           id="fortnox_order_date_range_btn" name="fortnox_order_date_range_btn" type="button" value="<?= __("Start", WF_Plugin::TEXTDOMAIN ) ?>">
                    </div>
                </div>
            </footer>
        </section>
    </div>
</div>
