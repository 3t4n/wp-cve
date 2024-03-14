<div class="af2_action_buttons_wrapper">
    <?php if(isset($menu_action_button_add_post)) { ?>
        <a class="af2_btn_link" href="<?php  _e(wp_kses_post($menu_action_button_add_post)); ?>">
            <div class="af2_action_button add_post"><i class="fas fa-plus"></i></div>
        </a>
    <?php }; ?>
    <?php if(isset($menu_action_button_copy_posts)) { ?>
        <div id="af2_copy_posts" class="af2_action_button af2_hide"><i class="fas fa-copy"></i></div>
    <?php }; ?>
    <?php if(isset($menu_action_button_delete_posts)) { ?>
        <div id="af2_delete_posts" class="af2_action_button af2_hide"><i class="fas fa-trash-alt"></i></div>
    <?php }; ?>
</div>