<?php
defined('ABSPATH') || exit;

if (function_exists('wc_notice_count') && wc_notice_count() > 0) { ?>

    <div class="woo-ready-ele-notices">
        <?php wc_print_notices(); ?>
    </div>

<?php } ?>