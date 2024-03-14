<div id='alpha_app'>
    <div class="fframe_app">
        <div class="fframe_main-menu-items">

            <div class="menu_logo_holder">
                <a href="<?php echo esc_url($base_url); ?>">
                    <img src="<?php echo esc_url($logo); ?>" />
                </a>
            </div>
            <div class="fframe_handheld"><span class="dashicons dashicons-menu-alt3"></span></div>

            <ul class="fframe_menu">
                <?php foreach ($menuItems as $item): ?>
                    <?php $hasSubMenu = !empty($item['sub_items']); ?>
                    <li data-key="<?php echo esc_attr($item['key']); ?>" class="fframe_menu_item <?php echo ($hasSubMenu) ? 'fframe_has_sub_items' : ''; ?> fframe_item_<?php echo esc_attr($item['key']); ?>">
                        <a class="fframe_menu_primary" href="<?php echo esc_url($item['permalink']); ?>">
                            <?php echo esc_attr($item['label']); ?>
                            <?php if($hasSubMenu){ ?>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            <?php } ?></a>
                        <?php if($hasSubMenu): ?>
                            <div class="fframe_submenu_items">
                                <?php foreach ($item['sub_items'] as $sub_item): ?>
                                    <a href="<?php echo esc_url($sub_item['permalink']); ?>"><?php echo esc_attr($sub_item['label']); ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php if($secondaryItems): ?>
            <ul class="frame_secondary_menu fframe_menu">
                <?php foreach ($secondaryItems as $item): ?>
                    <?php $hasSubMenu = !empty($item['sub_items']); ?>
                    <li data-key="<?php echo esc_attr($item['key']); ?>" class="fframe_menu_item <?php echo ($hasSubMenu) ? 'fframe_has_sub_items' : ''; ?> fframe_item_<?php echo esc_attr($item['key']); ?>">
                        <a class="fframe_menu_primary" href="<?php echo esc_url($item['permalink']); ?>">
                            <?php echo esc_attr($item['label']); ?>
                            <?php if($hasSubMenu){ ?>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            <?php } ?></a>
                        <?php if($hasSubMenu): ?>
                            <div class="fframe_submenu_items">
                                <?php foreach ($item['sub_items'] as $sub_item): ?>
                                    <a href="<?php echo esc_url($sub_item['permalink']); ?>"><?php echo esc_attr($sub_item['label']); ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
        <div class="fframe_body">
            <router-view></router-view>
        </div>
    </div>
</div>
