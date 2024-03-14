<?php
    /** @var $ex Exception */
?>
<form class="wpfs-form wpfs-w-60">
    <div class="wpfs-form-message wpfs-form-message--incorrect">
        <div class="wpfs-form-message-title">WP Full Pay shortcode error</div>
        An error occurred: <?php echo $ex->getMessage(); ?>.
    </div>
</form>
