<!--====== OFFCANVAS MENU PART START ======-->
<?php if($settings['offcanvas_enable'] == 'yes'): ?>
    <div class="off_canvars_overlay">
    </div>
    <div class="offcanvas_menu">
        <div class="quomodo-container-fluid">
            <div class="quomodo-row">
                <div class="quomodo-col-12">
                    <div class="offcanvas_menu_wrapper">
                        <div class="canvas_close">
                            <a href="javascript:void(0)"><i class="fa fa-times"></i></a>  
                        </div>
                        <div class="offcanva-element-ready-ele-content">
                            <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['offcanvas_template_id'] ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<!--====== OFFCANVAS MENU PART ENDS ======-->
<!--====== HEADER PART START ======-->
<div class="element-ready-header-nav main-section style8">
    <div class="quomodo-container">
        <div class="quomodo-row">
            <div class="quomodo-col-lg-12">
                <div class="navigation">
                    <nav class="navbar <?php echo esc_attr($settings['mobile_menu_breakpoint']); ?> navbar-light ">
                        <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                            <?php    
                                wp_nav_menu($args);
                            ?> 
                        </div> <!-- navbar collapse -->
                                <?php if($settings['header_logo_enable'] == 'yes'): ?>
                                    <div class="navbar-brand logo p-0">
                                            <?php
                                                $this->add_render_attribute(
                                                    'header_logo_warapper',
                                                    [
                                                    
                                                        'href' =>  esc_url($settings['header_website_link']['url']), 
                                                        'target' => esc_attr($settings['header_website_link']['is_external'] == 'on'?'_blank':'self'),
                                                        'rel' => esc_attr($settings['header_website_link']['nofollow'] == 'on'?'nofollow':'')
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
                                            <?php echo wp_kses_post( $settings['header_logo_type'] == 'text'?'</h1>':'' ); ?> 
                                    </div> <!-- logo -->
                            <?php endif; ?>
                        <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent-2">
                            <?php   
                                wp_nav_menu($args_2);
                            ?> 
                        </div> <!-- navbar collapse -->
                        <?php if($settings['offcanvas_enable'] == 'yes'): ?>
                            <div class="navbar-btn d-flex d-lg-none align-items-center">
                                <div class="canvas-bar">
                                    <?php
                                        $offcanvas_menu_icon = $settings['offcanvas_menu_icon'];
                                        $canvas_url = ELEMENT_READY_ROOT_IMG.'/hamburger.svg';
                                        if( isset($offcanvas_menu_icon['library']) && $offcanvas_menu_icon['library'] ==''){
                                            ?>
                                              <img class="canvas_open" src="<?php echo esc_url($canvas_url); ?>" alt=" <?php echo esc_attr__('offcanvas icon'); ?>">
                                            <?php
                                        }else{
                                            \Elementor\Icons_Manager::render_icon( $settings['offcanvas_menu_icon'], [ 'aria-hidden' => 'true','class' => 'canvas_open' ] );
                                        }
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </nav>
                </div> <!-- navigation -->
            </div>
        </div> <!-- row -->
    </div>
</div>
               