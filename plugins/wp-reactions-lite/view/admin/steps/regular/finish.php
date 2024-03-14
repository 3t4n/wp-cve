<?php
use WP_Reactions\Lite\Helper;
global $wpra_lite;
?>
<div class="row">
    <div class="col-md-12 text-center wpra-step-finish">
        <div class="option-wrap">
            <?php Helper::tooltip('step-finish'); ?>
            <h1><?php _e( 'Your Reactions are Ready!', 'wpreactions' ); ?></h1>
            <div id="preview" class="mb-3"></div>
        </div>
    </div>
</div>