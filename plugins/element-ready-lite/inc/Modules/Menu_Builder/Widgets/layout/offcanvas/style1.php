<!--====== SIDEBAR MENU PART START ======-->
    <div class="sidebar-btn d-flex align-items-center">
        <div class="bar d-block navigation-button element-ready-canvas-bar <?php echo esc_attr($settings['offcanvas_display']); ?>">
            <?php

                $offcanvas_menu_icon = $settings['offcanvas_menu_icon'];
                $canvas_url =  ELEMENT_READY_ROOT_IMG.'/hamburger.svg'; 
                if( isset($offcanvas_menu_icon['library']) && $offcanvas_menu_icon['library'] ==''){
                    ?>
                        <img src="<?php echo esc_url($canvas_url); ?>" alt="<?php echo esc_attr__('offcanvas icon','element-ready-lite'); ?>">
                    <?php
                }else{
                    \Elementor\Icons_Manager::render_icon( $settings['offcanvas_menu_icon'], [ 'aria-hidden' => 'true' ] );
                }
                
            ?>
        </div>
    </div>
    <div class="element-ready-body-overlay <?php echo esc_attr( $settings['offcanvas_container_direction']!='yes'?'element-ready-overlay-rightbar':'' ); ?>"></div>
    <div class="element-ready-sidebar-menu <?php echo esc_attr( $settings['offcanvas_container_direction']!='yes'?'element-ready-offcanvus-rightbar':'' ); ?>">
        <button class="sidebar-menu-close"><i class="fa fa-times"></i></button>
        <div class="sidebar-inner">
            <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['offcanvas_template_id'] ); ?>
        </div>
    </div>
    <!--====== SIDEBAR MENU PART ENDS ======-->
