<?php

/**
Template Name: PayGreen Payment Gateway Frontoffice
Template Post Type: page
 */

$message_id = $_GET['message_id'];

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <h2><?php echo __('An error occurred while processing your order.', 'paygreen-payment-gateway') ?></h2>
            <p><?php echo __('Please contact the merchant to have more information.', 'paygreen-payment-gateway') ?></p>
            <p><?php echo __('Details : ', 'paygreen-payment-gateway') . __($message_id, 'paygreen-payment-gateway'); ?></p>
        </main>
    </div>

<?php
get_footer();