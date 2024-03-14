<?php

$message = '';
$error = '';

if (isset($_GET['scheme']) && !empty($_GET['scheme'])) {

    if ($this->delete_scheme($_GET['scheme'])) {
        $message = __('Scheme has been successfully deleted.', $this->plugin_slug);
    }

} else {
    $error = __('Scheme id is not specified.', $this->plugin_slug);
}

?>


<!--    Delete Scheme -->


<?php if ($message || $error) : ?>
    <div class="<?php echo ($error) ? 'error' : 'updated' ?>" id="message"><p><?php echo $error . $message; ?></p>
    </div>
<?php endif; ?>

<!--    End Delete Scheme -->