<div class="af2_menu_hooks_wrapper mb10">
    <?php if(isset($menu_hook_inline_checkbox)) {
            $menuHook = ''; if($menu_hook_inline_checkbox['active']){ $menuHook = 'checked'; }
    ?>
        <div class="af2_toggle_wrapper">
            <input type="checkbox" id="<?php _e($menu_hook_inline_checkbox['id']); ?>" class="af2_toggle" <?php _e($menuHook); ?>>
            <label for="<?php _e($menu_hook_inline_checkbox['id']); ?>" class="af2_toggle_btn"></label>
            <h4 class="af2_toggle_label ml5"><?php _e($menu_hook_inline_checkbox['label']); ?></h4>
        </div>
    <?php }; ?>
    <?php if(isset($menu_hook_extra_title)) { ?>
        <h4><?php _e($menu_hook_extra_title['label']); ?>: <?php _e($menu_hook_extra_title['value']); ?></h4>
    <?php }; ?>
    <?php if(isset($menu_hook_inline_search)) { ?>
        <div class="af2_menu_headline_search_component">
            <?php
            $filter_columns = null;
            if(is_array($menu_hook_inline_search)) {
                $filter_columns = '';
                for($i = 0; $i < sizeof($menu_hook_inline_search); $i++) {
                    if($i != 0) $filter_columns .= ';';
                    $filter_columns .= $menu_hook_inline_search[$i];
                }
            }
            else {
                $filter_columns = $menu_hook_inline_search;
            }
            ?>
            <input id="af2_search_filter" type="text" data-searchfiltercolumn="<?php echo esc_html($filter_columns); ?>" placeholder="<?php _e('Search...', 'funnelforms-free'); ?>">
            <div class="af2_menu_headline_search_component_icon"><i class="fas fa-search"></i></div>
        </div>
    <?php }; ?>
    <?php if(isset($menu_hook_inline_button_form)) { ?>
        <div id="<?php _e(esc_html($menu_hook_inline_button_form['id'])); ?>" class="af2_btn af2_btn_primary <?php _e(esc_html($menu_hook_inline_button_form['bonus_class'])); ?>"><div class="af2_pro_sign"><i class="fas fa-star"></i><?php _e('PRO', 'funnelforms-free'); ?></div><i class="<?php _e(esc_html($menu_hook_inline_button_form['icon'])); ?>"></i><?php _e($menu_hook_inline_button_form['label']); ?></div>
    <?php }; ?>
</div>
