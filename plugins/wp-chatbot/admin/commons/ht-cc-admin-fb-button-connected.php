<?php

/**
 *  View of Facebook button area when connected.
 * 
 * @uses at class-htcc-admin.php 
 */

if (!defined('ABSPATH')) exit;
?>
<h2><?php settings_errors(); ?> </h2>

<div class="connected-page <?php _e($is_pro ? 'pro' : '') ?>">
    <div class="active-page-info">
        <div class="page_name__wrap">
            <p class="page_name"><?php echo $connected_page['name']; ?></p>
            <?php _e($is_pro ? '<span class="pro">PRO</span>' : '') ?>
        </div>
    <p class="connect_check"> <i class="fa fa-check"></i><?php _e('Connected')?></p>
    </div>
</div>



