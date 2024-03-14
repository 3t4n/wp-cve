<h3><?php echo (! empty($this->method_title)) ? $this->method_title : __('Settings', 'woocommerce') ; ?></h3>

<?php echo (! empty($this->method_description)) ? wpautop($this->method_description) : ''; ?>

<table class="form-table">
    <h3><?= __('Panel Administracyjny', 'cashbill-payment-method'); ?></h3>
    <?php $this->generate_settings_html(); ?>
</table>