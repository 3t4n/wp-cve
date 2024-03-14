<div class="element-ready-header-nav main-section element-ready-style-3">
    <div class="navigation">
        <nav class="navbar <?php echo esc_attr($settings['mobile_menu_breakpoint']); ?> navbar-light ">
            <?php if($settings['header_logo_enable'] == 'yes'): ?>
                <div class="navbar-brand logo">
                    <?php
                        $this->add_render_attribute(
                            'header_logo_warapper',
                            [
                                'class'  => 'link',
                                'href'   => esc_url($settings['header_website_link']['url']),
                                'target' => esc_attr($settings['header_website_link']['is_external'] == 'on' ? '_blank':'self'),
                                'rel'    => esc_attr($settings['header_website_link']['nofollow'] == 'on' ? 'nofollow':'')
                            ]
                        );
                    ?>
                    <?php echo wp_kses_post( $settings['header_logo_type'] == 'text'?'<h1 class="logo-title">':'' ); ?> 
                            <a <?php echo $this->get_render_attribute_string( 'header_logo_warapper' ); ?>>
                                <?php if( $settings['header_logo_type'] == 'logo' ): ?>
                                        <?php if( $settings['header_logo']['url'] !='' ): ?>
                                            <img src="<?php echo esc_url($settings['header_logo']['url']); ?>" alt="<?php echo esc_attr__('logo','element-ready-lite'); ?>"/>
                                        <?php endif; ?>
                                    <?php elseif( $settings['header_logo_type'] == 'svg' ): ?>
                                        <?php \Elementor\Icons_Manager::render_icon( $settings['header_svg_logo'], [ 'aria-hidden' => 'true' ] ); ?>
                                    <?php elseif( $settings['header_logo_type'] == 'text' ): ?>
                                        <?php echo esc_html($settings['header_text_logo']); ?>
                                    <?php endif; ?>
                            </a>
                    <?php echo wp_kses_post( $settings['header_logo_type'] == 'text'?'</h1>':''); ?> 
                </div> <!-- logo -->
            <?php endif; ?> 
            <div class="collapse navbar-collapse sub-menu-bar">
                <?php                   
                    wp_nav_menu($args);
                ?> 
            </div> <!-- navbar collapse -->
            <?php if( $settings['header_button_enable'] =='yes' ): ?> 
                <?php
                    $this->add_render_attribute(
                        'header_button_warapper',
                        [
                            'class'  => 'main-btn',
                            'href'   => esc_url($settings[ 'header_button_link' ][ 'url' ]),
                            'target' => esc_attr( $settings[ 'header_button_link' ][ 'is_external' ] == 'on' ? '_blank' : 'self' ),
                            'rel'    => esc_attr( $settings[ 'header_button_link' ][ 'nofollow' ] == 'on' ? 'nofollow' : '' )
                        ]
                    );
                ?>
                <div class="navbar-btn d-none d-sm-block">
                    <a <?php echo $this->get_render_attribute_string( 'header_button_warapper' ); ?>> 
                        <?php echo wp_kses_post($settings['header_button_text']); ?> 
                    </a>
                </div>
            <?php endif; ?> 
        </nav>
    </div> <!-- navigation -->
</div>
    

