<?php

$message = '';
$error = '';


if ($this->clear_orders()) {
    $message = __('Orders have been successfully deleted.', $this->plugin_slug);
}

$this->redirect_to_orders_list();

?>


<!--    Delete Scheme -->


<?php if ($message || $error) : ?>
    <div class="<?php echo ($error) ? 'error' : 'updated' ?>" id="message"><p><?php echo $error . $message; ?></p>
    </div>
<?php endif; ?>

<!--    End Delete Scheme -->