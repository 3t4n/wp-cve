<div class="element-ready-style1">
    <?php if($settings['header_logo_enable'] == 'yes'): ?>
       <div class="navbar-header">
            <nav class="navbar navbar-expand-lg fixed-top main-section">
                <?php if($settings['main_section_container_disable'] =='yes'): ?>
                    <div class="<?php echo esc_attr($settings['main_container_fluid_enable'] == 'yes'?'container-fluid':'container') ?>">
                        <?php endif; ?>
                        <div class="row align-items-center">
                            <?php if($settings['header_logo_enable'] == 'yes'): ?>
                                <?php
                               
                                    $this->add_render_attribute(
                                        'header_logo_warapper',
                                        [
                                            'href' =>  esc_url($settings['header_website_link']['url']), 
                                            'target' => esc_attr( $settings['header_website_link']['is_external'] == 'on'?'_blank':'self'),
                                            'rel' => esc_attr($settings['header_website_link']['nofollow'] == 'on'?'nofollow':'')
                                        ]
                                    );
                                        
                                ?>
                                <div class="col-6">
                                    <div class="navbar-brand logo">
                                        <?php echo wp_kses_post( $settings['header_logo_type'] == 'text'?'<h1 class="logo-title">':''); ?> 
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
                                        <?php echo wp_kses_post($settings['header_logo_type'] == 'text'?'</h1>':''); ?> 
                                    </div>
                                </div>
                            <?php endif; ?> 
                            <div class="col-<?php echo esc_attr($settings['header_logo_enable'] == 'yes')?'6':'12' ?> ">
                                <div class="element-ready-fs-menu-wrapper position-relative">
                                    <div class="hamburger">
                                        <div class="hamburger--container element-ready-hamburger">
                                            <?php if( $settings['mobile_menu_icon']['library'] !='' ): ?>
                                            <?php \Elementor\Icons_Manager::render_icon( $settings['mobile_menu_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                            <?php else: ?>   
                                                <div class="hamburger--bars"></div> 
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fsmenu">
                            <div class="fsmenu--container">
                                <div class="fsmenu--text-block">
                                    <div class="fsmenu--text-container">
                                        <?php                   
                                            wp_nav_menu($args);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if($settings['main_section_container_disable'] =='yes'): ?>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    <?php else: ?>
        <div class="element-ready-fs-menu-wrapper position-relative">
            <div class="hamburger">
                <div class="hamburger--container element-ready-hamburger">
                    <?php if( $settings['mobile_menu_icon']['library'] !='' ): ?>
                        <?php \Elementor\Icons_Manager::render_icon( $settings['mobile_menu_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    <?php else: ?>   
                        <div class="hamburger--bars"></div> 
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="fsmenu main-section">
            <div class="fsmenu--container">
                <div class="fsmenu--text-block">
                    <div class="fsmenu--text-container">
                        <?php                   
                            wp_nav_menu($args);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?> 
</div>  