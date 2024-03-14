<div class="widget-eli elementinvader-addons-for-elementor eli-menu elementor-clickable" id="eli_<?php echo esc_html($this->get_id_int()); ?>">
    <div class="eli-container">
        <div class="wl_nav_mask"></div>
        <?php
        if ('dropdown' !== $settings['layout']) :
            $this->add_render_attribute('main-menu', 'class', [
                'wl-nav-menu--main',
                'wl-nav-menu__container',
                'wl-nav-menu--layout-' . $settings['layout'],
            ]);
            ?>
            <nav <?php echo wp_kses_post($this->get_render_attribute_string('main-menu')); ?>><?php echo wp_kses_post($menu_html); ?></nav>
            <?php
        endif;
        ?>
        <div <?php echo wp_kses_post($this->get_render_attribute_string('menu-toggle')); ?>>
            <?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            <?php if (!empty($settings['menu_text'])): ?>
                <span class="wl-screen-only"><?php echo wp_kses_post($settings['menu_text']); ?></span>
            <?php endif; ?>
        </div>
        <nav class="wl-nav-menu--dropdown wl-nav-menu__container" role="navigation" aria-hidden="true">
            <a href="#" class="wl_close-menu">
                <span class="bar1"></span>
                <span class="bar3"></span>
            </a>
            <?php echo wp_kses_post($dropdown_menu_html); ?>
        </nav>
    </div>
    
</div>