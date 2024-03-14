<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if (majesticsupport::$_config['offline'] == 2) {
    ?>
    <div class="ms-main-up-wrapper">
        <?php MJTC_message::MJTC_getMessage(); ?>
        <?php include_once(MJTC_PLUGIN_PATH . 'includes/header.php'); ?>
        <h1 class="ms-missing-addon-message" >
            <?php echo esc_attr(__('Page Not Found !!','majestic-support')); ?>
        </h1>
    <?php
} else {
    MJTC_layout::MJTC_getSystemOffline();
} ?>
