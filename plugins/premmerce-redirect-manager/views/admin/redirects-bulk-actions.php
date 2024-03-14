<?php
if(!defined('ABSPATH')){
	exit;
}
?>
<div class="tablenav <?= esc_attr($which); ?>">
    <div class="alignleft actions bulkactions">
        <?php $table->bulk_actions($which); ?>
    </div>

    <?php

    $table->extra_tablenav($which);
    $table->pagination($which);

    ?>

    <br class="clear" />
</div>