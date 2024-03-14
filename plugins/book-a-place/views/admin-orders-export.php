<?php

$message = '';
$error = '';


$orders = $this->get_orders();

$this->orders_to_csv($orders);

//$this->redirect_to_orders_list();

?>


<!--    Delete Scheme -->


<?php if ($message || $error) : ?>
    <div class="<?php echo ($error) ? 'error' : 'updated' ?>" id="message"><p><?php echo $error . $message; ?></p>
    </div>
<?php endif; ?>

<!--    End Delete Scheme -->