<?php if($settings['offcanvas_enable'] == 'yes'): ?>
    <div class="off_canvars_overlay element-ready-offcanvas-overlay">   
    </div>
    <div class="offcanvas_menu">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="offcanvas_menu_wrapper <?php echo esc_attr($settings['offcanvas_container_direction'] =='yes'?'element-ready-offcanvus-leftbar':''); ?>">
                        <div class="canvas_close">
                            <a href="javascript:void(0)">
                                <i class="fa fa-times"></i>
                            </a>  
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
<div class="element-ready-header-nav main-section style5 style6 element-ready-style-7">
    <?php if($settings['main_section_container_disable'] =='yes'): ?>
    <div class="<?php echo esc_attr($settings['main_container_fluid_enable'] == 'yes'?'container-fluid':'container') ?>">
    <?php endif; ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="navigation">
                    <nav class="navbar <?php echo esc_attr($settings['mobile_menu_breakpoint']); ?> navbar-light ">
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
                        <?php if($settings['header_logo_enable'] == 'yes'): ?>
                                <div class="navbar-brand logo">
                                        <?php
                                            $this->add_render_attribute(
                                                'header_logo_warapper',
                                                [
                                                    'href' =>  esc_url($settings['header_website_link']['url']), 
                                                    'target' => esc_attr( $settings['header_website_link']['is_external'] == 'on'?'_blank':'self' ),
                                                    'rel' => esc_attr( $settings['header_website_link']['nofollow'] == 'on'?'nofollow':'' )
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
                        <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                            <?php                   
                                wp_nav_menu($args);
                            ?> 
                        </div> <!-- navbar collapse -->
                        <div class="navbar-btn d-flex align-items-center element-ready-canvas-container">
                            <?php if($settings['header_search_enable'] == 'yes'): ?>                                   
                                <div class="search-bar element-ready-search-open ">
                                    <a href="#"><?php \Elementor\Icons_Manager::render_icon( $settings['header_search_icon'], [ 'aria-hidden' => 'true' ] ); ?> 
                                        <span><?php echo esc_html($settings['header_search_text']); ?> </span>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if($settings['offcanvas_enable'] == 'yes'): ?>
                                    <div class="canvas-bar element-ready-canvas-bar">
                                        <?php
                                            $offcanvas_menu_icon = $settings['offcanvas_menu_icon'];
                                            $canvas_url = ELEMENT_READY_ROOT_IMG.'/post-bars.png'; 
                                            if( isset($offcanvas_menu_icon['library']) && $offcanvas_menu_icon['library'] ==''){
                                                ?>
                                                    <img class="canvas_open" src="<?php echo esc_url($canvas_url); ?>" alt="<?php echo esc_attr__('offcanvas icon'); ?>">
                                                <?php
                                            }else{
                                                ?>
                                                <span class="canvas_open">
                                                  <?php \Elementor\Icons_Manager::render_icon( $settings['offcanvas_menu_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                                </span>
                                                <?php
                                            }
                                        ?>
                                   </div>
                            <?php endif; ?>
                        </div>
                    </nav>
                </div> <!-- navigation -->
            </div>
        </div> <!-- row -->
        <?php if($settings['main_section_container_disable'] =='yes'): ?>
        </div>
    <?php endif; ?>
    </div>
<?php if($settings['header_search_enable'] == 'yes'): ?>    
    <?php include('search/content.php'); ?>   
<?php endif; ?>
