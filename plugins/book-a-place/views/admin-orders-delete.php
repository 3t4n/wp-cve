<?php

$message = '';
$error = '';

if (isset($_GET['order']) && !empty($_GET['order'])) {

    if ($this->delete_order($_GET['order'])) {
        $message = __('Order has been successfully deleted.', $this->plugin_slug);
        $this->redirect_to_orders_list();
    }

} else {
    $error = __('Order id is not specified.', $this->plugin_slug);
}

?>


<!--    Delete Scheme -->


<?php if ($message || $error) : ?>
    <div class="<?php echo ($error) ? 'error' : 'updated' ?>" id="message"><p><?php echo $error . $message; ?></p>
    </div>
<?php endif; ?>

<!--    End Delete Scheme -->