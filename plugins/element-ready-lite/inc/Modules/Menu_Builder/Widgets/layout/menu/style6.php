<div class="element-ready-header-nav main-section style5 style6">
    <?php if($settings['main_section_container_disable'] =='yes'): ?>
        <div class="<?php echo esc_attr($settings['main_container_fluid_enable'] == 'yes'?'container-fluid':'container') ?>">
        <?php endif; ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="navigation">
                        <nav class="navbar <?php echo esc_attr($settings['mobile_menu_breakpoint']); ?> navbar-light ">
                            <?php if($settings['header_logo_enable'] == 'yes'): ?>
                                    <div class="navbar-brand logo">
                                        <?php
                                            $this->add_render_attribute(
                                                'header_logo_warapper',
                                                [
                                                    'href'   => esc_url($settings[ 'header_website_link' ][ 'url' ]),
                                                    'target' => esc_attr($settings[ 'header_website_link' ][ 'is_external' ] == 'on' ? '_blank' : 'self' ),
                                                    'rel'    => esc_attr( $settings[ 'header_website_link' ][ 'nofollow' ] == 'on' ? 'nofollow' : '' )
                                                ]
                                            );
                                        ?>
                                        <?php echo wp_kses_post($settings['header_logo_type'] == 'text'?'<h1 class="logo-title">':''); ?> 
                                            <a <?php echo $this->get_render_attribute_string( 'header_logo_warapper' ); ?>>
                                                <?php if( $settings['header_logo_type'] == 'logo' ): ?>
                                                    <?php if( $settings['header_logo']['url'] !='' ): ?>
                                                        <img src="<?php echo esc_url( $settings[ 'header_logo' ][ 'url' ] ); ?>" alt="<?php echo esc_attr__('logo','element-ready-lite'); ?>"/>
                                                    <?php endif; ?>
                                                <?php elseif( $settings['header_logo_type'] == 'svg' ): ?>
                                                    <?php \Elementor\Icons_Manager::render_icon( $settings['header_svg_logo'], [ 'aria-hidden' => 'true' ] ); ?>
                                                <?php elseif( $settings['header_logo_type'] == 'text' ): ?>
                                                    <?php echo esc_html($settings['header_text_logo']); ?>
                                                <?php endif; ?>
                                            </a>
                                        <?php echo wp_kses_post( $settings['header_logo_type'] == 'text'?'</h1>':'' ); ?> 
                                    </div> <!-- logo -->
                            <?php endif; ?>
                            <?php if($settings['enable_mobile_menu'] =='yes'): ?>
                                <button class="navbar-toggler element-ready-hamburger" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <?php if( $settings['mobile_menu_icon']['library'] !='' ): ?>
                                    <?php \Elementor\Icons_Manager::render_icon( $settings['mobile_menu_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    <?php else: ?>   
                                        <span class="toggler-icon"></span>
                                        <span class="toggler-icon"></span>
                                        <span class="toggler-icon"></span>
                                    <?php endif; ?>
                                </button> <!-- navbar toggler -->
                            <?php endif; ?>
                            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                <?php                   
                                    wp_nav_menu($args);
                                ?> 
                            </div> <!-- navbar collapse -->
                        </nav>
                    </div> <!-- navigation -->
                </div>
            </div> <!-- row -->
            <?php if($settings['main_section_container_disable'] =='yes'): ?>
             </div>
        <?php endif; ?>
    </div>
