<?php

/*
 * Add New Scheme
 */

$message = '';
$error = '';

if (isset($_POST['submit-scheme'])) {
    if (isset($_POST['scheme-name']) && !empty($_POST['scheme-name']) && isset($_POST['scheme-width']) && !empty($_POST['scheme-width']) && isset($_POST['scheme-height']) && !empty($_POST['scheme-height'])) {
        if ($this->add_scheme($_POST)) {
            $message = __('Scheme has been successfully added.', $this->plugin_slug);
        } else {
            $error = __('Error.', $this->plugin_slug);
        }
    } else {
        $error = __('All fields are required.', $this->plugin_slug);
    }
}

$form_title = __('Add New Scheme', $this->plugin_slug);
$submit_button_name = __('Add', $this->plugin_slug);

?>




<?php if ($message || $error) : ?>
    <div class="<?php echo ($error) ? 'error' : 'updated' ?>" id="message">
        <p><?php echo $error . $message; ?></p>
    </div>
<?php endif; ?>


<?php require_once(BAP_DIR_PATH . 'views/includes/admin-schemes-form.php'); ?>

