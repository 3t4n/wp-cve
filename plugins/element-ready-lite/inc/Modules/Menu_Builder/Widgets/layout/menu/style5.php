<div class="element-ready-header-nav element-ready-style-5 main-section">
<?php if($settings['main_section_container_disable'] =='yes'): ?>
        <div class="<?php echo esc_attr($settings['main_container_fluid_enable'] == 'yes'?'quomodo-container-fluid':'quomodo-container') ?>">
        <?php endif; ?>
                <div class="quomodo-row">
                    <div class="quomodo-col-lg-12">
                        <div class="navigation">
                            <nav class="navbar <?php echo esc_attr($settings['mobile_menu_breakpoint']); ?> navbar-light ">
                                <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                    <?php                   
                                        wp_nav_menu($args);
                                    ?> 
                                </div><!-- navbar collapse -->
                                <?php if( $settings['header_button_enable'] =='yes' ): ?> 
                                        <?php
                                            $this->add_render_attribute(
                                                'header_button_warapper',
                                                [
                                                    'class'  => 'main-btn',
                                                    'href'   => esc_url( $settings[ 'header_button_link' ][ 'url' ] ),
                                                    'target' => esc_attr( $settings[ 'header_button_link' ][ 'is_external' ] == 'on' ? '_blank' : 'self' ),
                                                    'rel'    => esc_attr( $settings[ 'header_button_link' ][ 'nofollow' ] == 'on' ? 'nofollow' : '' )
                                                ]
                                            );
                                        ?>
                                        <div class="navbar-btn">
                                            <a <?php echo $this->get_render_attribute_string( 'header_button_warapper' ); ?>> 
                                                <?php echo esc_html($settings['header_button_text']); ?> 
                                            </a>
                                        </div>
                                    <?php endif; ?> 
                                </nav>
                            </div> <!-- navigation -->
                        </div>
                    </div> <!-- row -->
                <?php if($settings['main_section_container_disable'] =='yes'): ?>
             </div>
            <?php endif; ?>
        </div>