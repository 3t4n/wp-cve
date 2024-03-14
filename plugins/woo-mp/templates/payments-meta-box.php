<?php defined( 'ABSPATH' ) || die; ?>

<div id="woo-mp-main">

    <div class="global-notice" hidden></div>

    <div data-panel="main" <?= $this->charges ? 'data-current-panel' : '' ?>>

        <?php $this->template( 'payments-table' ); ?>

        <div class="action-button-bar">
            <div class="action-buttons">
                <button type="button" class="button button-primary right" data-open-panel="charge">Add Payment</button>
            </div>
        </div>
    </div>

    <div id="charge" data-panel="charge" <?= $this->charges ? '' : 'data-current-panel' ?>>
        <div class="panel-notice" hidden></div>

        <div class="transaction-form">

            <?php $this->template( 'charge-form' ); ?>

        </div>

        <div class="action-button-bar">
            <div class="action-buttons">
                <button type="button" id="charge-btn" class="button button-primary">Charge</button>
                <button type="button" class="button right" data-close-panel>Close</button>
            </div>
        </div>
    </div>

    <div id="refund" data-panel="refund">

        <?php

        if ( Woo_MP\Woo_MP::is_pro() ) {
            $this->template( 'refund-panel' );
        } else {
            $this->template( 'upgrade-panel' );
        }

        ?>

    </div>

    <?php $this->template( 'notice-template' ); ?>

    <?php $this->template( 'notice-sections-template' ); ?>

</div>
