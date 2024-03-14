<?php
defined('ABSPATH') || exit;

/* @var $this NewsletterPopupMaker */

@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();

if (!$controls->is_action()) {
    $controls->data = $this->options;
} else {
    if ($controls->is_action('save')) {
        $this->save_options($controls->data);
        $controls->add_message_saved();
    }
}
?>
<div class="wrap" id="tnp-wrap">

    <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

    <div id="tnp-heading">

        <h2>Popup Maker Integration</h2>

        <p>
        </p>

    </div>

    <div id="tnp-body">
        <p>
            This integration adds the Newsletter form support to Popup Maker. When you create a popup you can add
            as conversion event the submission of a subscription so the popup won't show again.
        </p>
        <p>
            There are no options to be set here.
        </p>
    </div>

    <?php include NEWSLETTER_DIR . '/tnp-footer.php'; ?>

</div>
