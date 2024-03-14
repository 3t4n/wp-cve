<?php if(!defined('ABSPATH'))  die('You are not allowed to call this page directly.'); ?>

<div class="wrap" id="resads-admanagement">
    <h2><?php _e('AdManagement', RESADS_ADMIN_TEXTDOMAIN); ?> <a href="<?php printf('?page=%s&action=%s', 'resads-admanagement', 'new') ?>" class="add-new-h2"><?php _e('Add New Ad', RESADS_ADMIN_TEXTDOMAIN); ?></a></h2>
    <form id="resads-admanagement-form" action="" method="get">
    <?php
    $List_Table = new ResAds_AdManagement_List_Table();
    $List_Table->prepare_items();
    $List_Table->search_box(__('Search', RESADS_ADMIN_TEXTDOMAIN), 'resads-admanagement-search');
    foreach ($_GET as $key => $value) 
    {
        if('s' !== $key && !is_array($value))
            print "<input type='hidden' name='$key' value='$value' />";
    }
    $List_Table->display();
    ?>
    </form>
    <p><?php _e('Here you found all created ads. You can edit or delete them.', RESADS_ADMIN_TEXTDOMAIN); ?></p>
</div>