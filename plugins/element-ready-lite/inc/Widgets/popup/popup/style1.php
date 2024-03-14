        <!--====== USER LOGIN SIGNUP START ======-->
        <div class="block-account element-ready-block-header element-ready-dropdown">
            <a class="element-ready-user-interface er-ready-cart-popup" href="javascript:void(0);">
                <?php \Elementor\Icons_Manager::render_icon( $settings['interface_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                <?php if($settings['interface_text'] !=''): ?>
                    <?php echo esc_html($settings['interface_text']); ?> 
                <?php endif; ?>
            </a>
            <div class="header-account element-ready-submenu">
                <?php if( $settings['modal_template_id'] > 0 && $settings['modal_template_id'] !='' ): ?>
                    <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['modal_template_id'] ); ?>
                <?php endif; ?>
                <div class="er-ready-count-close-btn">
                    <?php echo wp_kses_post( element_ready_render_icons( $settings['close_icon'] , 'er-icons' ) ); ?>
                </div>
            </div>
        </div>
        <!--====== USER LOGIN SIGNUP END ======-->