<?php if(!defined('ABSPATH')) die('You are not allowed to call this page directly.'); ?>
<div class="wrap" id="resads-adspot">
    <h2><?php _e('AdSpot', RESADS_ADMIN_TEXTDOMAIN) ?> <a href="<?php printf('?page=%s&action=%s', 'resads-adspots', 'new') ?>" class="add-new-h2"><?php _e('Add New AdSpot', RESADS_ADMIN_TEXTDOMAIN); ?></a></h2>
    <form id="resads-adspot-form" action="" method="get">
    <?php
    $List_Table = new ResAds_AdSpot_List_Table();
    $List_Table->prepare_items();
    $List_Table->search_box(__('Search', RESADS_ADMIN_TEXTDOMAIN), 'resads-adspot-search');
    foreach ($_GET as $key => $value) 
    {
        if('s' !== $key && !is_array($value))
            print "<input type='hidden' name='$key' value='$value' />";
    }
    $List_Table->display();
    ?>
    </form>
</div>
