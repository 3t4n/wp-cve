<!-- Header Start -->
<header class="header-01 main-section">
    <?php if($settings['main_section_container_disable'] =='yes'): ?>
    <div class="<?php echo esc_attr($settings['main_container_fluid_enable'] == 'yes'?'container-fluid':'container') ?> ">
        <div class="row">
            <div class="col-lg-12">
            <?php endif; ?>
                <nav class="navbar <?php echo esc_attr( $settings['mobile_menu_breakpoint'] ); ?> element-ready-nav-container">
                    <!-- logo Start-->
                    <?php if($settings['header_logo_enable'] == 'yes'): ?>
                        <?php
                            $this->add_render_attribute(
                                'header_logo_warapper',
                                [
                                    'class'  => 'navbar-brand',
                                    'href'   => esc_url($settings['header_website_link']['url']),
                                    'target' => esc_attr( $settings['header_website_link']['is_external'] == 'on'?'_blank':'self' ),
                                    'rel'    => esc_attr( $settings['header_website_link']['nofollow'] == 'on'?'nofollow':'' )
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
                    <!-- logo End-->
                    <?php endif; ?>     
                    <?php if($settings['enable_menu_expend'] == 'yes'): ?>
                        <!-- Menu Btn Start -->
                        <a class="menu-btn" href="#"><i class="fas fa-bars"></i></a>
                        <!-- Menu Btn Start -->
                    <?php endif; ?>
                    <?php if($settings['enable_mobile_menu'] =='yes'): ?>
                    <!-- Moblie Btn Start -->
                    <button class="navbar-toggler element-ready-hamburger" type="button">
                        <?php if( $settings['mobile_menu_icon']['library'] !='' ): ?>
                            <?php \Elementor\Icons_Manager::render_icon( $settings['mobile_menu_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        <?php else: ?>
                            <i aria-hidden="true" class="fal fa-bars"></i>
                        <?php endif; ?>
                    </button>
                    <!-- Moblie Btn End -->
                    <?php endif; ?>
                    <!-- Nav Menu Start -->
                    <div class="collapse navbar-collapse element-ready-navbar-collapse">
                        <?php    
                            wp_nav_menu($args);
                        ?>
                    </div>
                    <!-- Nav Menu End -->
                    <?php if( $settings['header_button_enable'] =='yes' ): ?> 
                                <?php
                                    $this->add_render_attribute(
                                        'header_button_warapper',
                                        [
                                            'class' => 'signup-btn',
                                            'href' =>  esc_url($settings['header_button_link']['url']), 
                                            'target' => $settings['header_button_link']['is_external'] == 'on'?'_blank':'self',
                                            'rel' => $settings['header_button_link']['nofollow'] == 'on'?'nofollow':''
                                        ]
                                    );
                                ?>
                    <!-- Sign In Btn -->
                    <a <?php echo $this->get_render_attribute_string( 'header_button_warapper' ); ?> >
                            <?php \Elementor\Icons_Manager::render_icon( $settings['header_button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        <span>
                        <?php echo esc_html($settings['header_button_text']); ?> 
                        </span>
                    </a>
                    <!-- Contact Btn End -->
                    <?php endif; ?> 
                </nav>
                <?php if($settings['main_section_container_disable'] =='yes'): ?>
            </div>
        </div>
        <?php endif; ?> 
        <?php if($settings['header_search'] == 'yes'): ?>
            <div class="row">
                <?php if( $settings['header_search_heading'] !='' ): ?>
                    <div class="col-lg-3 col-md-4">
                        <div class="all-categories-title"><?php echo esc_html($settings['header_search_heading']); ?> </div>
                    </div>
                <?php endif; ?>
                <?php if($settings['product_menu_search_form_enable'] == 'yes'): ?>
                    <div class="col-lg-7 col-md-7">
                        <!-- Search Category Start -->
                        <div class="search-product">
                            <form class="d-flex" method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>">
                                <div class="search-category">
                                    <?php if($settings['product_menu_selected'] !=''): ?>
                                        <?php    
                                            wp_nav_menu($cat_args);
                                        ?>
                                    <?php endif; ?>
                                </div>
                                <input type="search" value="<?php get_search_query(); ?>"  name="s" placeholder=" <?php echo esc_attr($settings['header_search_placeholder']); ?> ">
                                <?php if($settings['product_search_enable']): ?>
                                    <input type="hidden" name="post_type" value="product" />
                                <?php endif; ?>
                                <?php if($settings['mega_menu_section_search_btn_enable'] == 'yes'): ?>
                                    <button type="submit">
                                        <?php \Elementor\Icons_Manager::render_icon( $settings['header_search_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                        <?php echo wp_kses_post($settings['header_search_text']);  ?> 
                                    </button>
                                <?php endif; ?>
                            </form>
                        </div>
                        <!-- Search Category End -->
                    </div>
                <?php endif; ?>
                <?php  if( class_exists( 'woocommerce' ) ): ?>
                <div class="col-lg-2 col-md-1">
                <!-- Cart Start -->
                    <div class="cart-area element-ready-cart-content">
                        <?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {  ?>
                            <a class="cart-btn" href="<?php echo wc_get_cart_url(); ?>"><i class="fa fa-shopping-basket"></i><span>0</span></a>
                        <?php }else{ ?> 
                            <a class="cart-btn" href="<?php echo wc_get_cart_url(); ?>"><i class="fa fa-shopping-basket"></i><span><?php echo sprintf (_n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?></span></a>
                        <?php } ?> 
                        <div class="product-price clearfix">
                            <span class="price">
                                <span>
                                    <span><?php echo get_woocommerce_currency_symbol(); ?></span>
                                    <?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {  ?>
                                            <span>0</span>
                                    <?php }else{ ?> 
                                        <?php echo WC()->cart->total; ?>
                                    <?php } ?> 
                                </span>
                            </span>
                        </div>
                    </div>
                    <!-- Cart End -->
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if($settings['main_section_container_disable'] =='yes'): ?>
    </div>
    <?php endif; ?>
</header>
<!-- Header End -->