<div class="wrap">
    <div class="wpfs-page wpfs-page-payment-forms">
        <?php include('partials/wpfs-header.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <div class="wpfs-empty-state">
            <div class="wpfs-empty-state__icon">
                <span class="wpfs-icon-form"></span>
            </div>
            <div class="wpfs-empty-state__title"><?php _e( 'No payment forms yet.', 'wp-full-stripe-admin' ); ?></div>
            <div class="wpfs-empty-state__message"><?php _e( 'Create your first form, and start accepting payments.', 'wp-full-stripe-admin'); ?></div>
            <a class="wpfs-btn wpfs-btn-primary" href="<?php echo $createButtonUrl; ?>"><?php echo $createButtonLabel; ?></a>
        </div>
    </div>
	<?php include( 'partials/wpfs-demo-mode.php' ); ?>
</div>
