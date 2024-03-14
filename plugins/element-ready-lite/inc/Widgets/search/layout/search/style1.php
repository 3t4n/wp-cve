   <!--====== SEARCH PART START ======-->
   <div class="search-bar element-ready-search-open element-ready-search-icon-area d-block <?php echo esc_attr($settings['search_display']); ?>">
        <a href="#"><?php \Elementor\Icons_Manager::render_icon( $settings['header_search_icon'], [ 'aria-hidden' => 'true' ] ); ?> 
            <span><?php echo esc_html($settings['header_search_text']); ?></span>
        </a>
    </div>
   <div class="element-ready-search-box">
        <div class="element-ready-search-header">
            <h5 class="search-title">
                <?php if($settings['header_search_logo']['url'] !=''): ?>
                    <img src="<?php echo esc_url($settings['header_search_logo']['url']); ?>" alt="<?php echo esc_attr__('logo','element-ready-lite'); ?>">
                <?php endif; ?>
            </h5> <!-- search title -->
            <div class="element-ready-search-close text-right">
                <button class="element-ready-search-close-btn">
                <?php if($settings['header_search_close_text'] !=''): ?>
                        <?php echo esc_html($settings['header_search_close_text']); ?> 
                <?php endif; ?>
                 <?php \Elementor\Icons_Manager::render_icon( $settings['header_close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </button>
            </div> <!-- search close -->
        </div> <!-- search header -->
        <div class="element-ready-search-body">
            <?php if($settings['custom_search_templte'] !='yes'): ?>
                <div class="element-ready-search-form">
                    <form method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>">
                        <input type="search" value="<?php get_search_query(); ?>" name="s" placeholder="<?php echo esc_attr__('search here','element-ready-lite'); ?>">
                        <button>
                        <?php \Elementor\Icons_Manager::render_icon( $settings['header_search_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['popup_search_template_id'] ); ?>
            <?php endif; ?>
        </div> <!-- search body -->
    </div>

    <!--====== SEARCH PART ENDS ======-->