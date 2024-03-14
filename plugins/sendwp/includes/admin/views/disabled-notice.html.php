<div class="notice notice-error sendwp-notice">
    <p>
        <span style="font-weight:bold;"><?php _e('Attention:', 'sendwp'); ?></span>&nbsp;
        <?php echo sprintf(__('The SendWP plugin is installed, but the service is currently disabled or disconnected on this site. %sConnect or register.%s'), '<br /><a href="' . admin_url('admin.php?page=sendwp') . '">', '</a>'); ?>
    </p>
</div>