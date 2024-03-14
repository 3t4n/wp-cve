<div class="better-portfolio style-6">
    <div class="container">
        <div class="row">
        
            <?php if(  $settings['portfolio6_categories_show'] == 'yes' ) : ?>
                <div class="filtering text-right col-12">
                    <div class="filter">
                        <span data-filter='*' class="active">All</span>
                        <?php foreach ( $settings['portfolio_categories'] as $index => $item ) : ?>
                            <span data-filter='.<?php echo esc_attr($item['item_category_slug']); ?>'><?php echo esc_html($item['item_category_title']); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="gallery better-full-width">

                <?php foreach ( $settings['portfolio6_items'] as $index => $item ) : 
                    if($settings['portfolio6_info_style'] == '1') : ?>
                    <div class="col-md-<?php echo intval($settings['better_portfolio4_columns']) ?> items <?php echo esc_attr($item['portfolio6_item_category_slug_call']); ?>">
                        <div class="item-img wow fadeInUp" data-wow-delay=".4s">
                            <a href="<?php echo esc_url($item['portfolio6_item_link']['url']); ?>">
                                <img src="<?php echo esc_url($item['portfolio6_item_image']['url']); ?>" alt="<?php echo esc_attr($item['portfolio6_item_title']); ?>">
                            </a>
                        </div>
                        <div class="cont">
                            <h6><?php echo esc_html($item['portfolio6_item_title']); ?></h6>
                            <span>
                                <a href="<?php echo esc_url($item['portfolio6_item_cat_link']['url']); ?>"><?php echo esc_html($item['portfolio6_item_cat']); ?></a>, 
                                <a href="<?php echo esc_url($item['portfolio6_item_cat_link2']['url']); ?>"><?php echo esc_html($item['portfolio6_item_cat2']); ?></a>
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($settings['portfolio6_info_style'] == '2') : ?>
                    <div class="col-md-<?php echo intval($settings['better_portfolio4_columns']) ?> items <?php echo esc_attr($item['portfolio6_item_category_slug_call']); ?>">
                        <div class="item-img wow fadeInUp" data-wow-delay=".4s">
                            <a href="<?php echo esc_url($item['portfolio6_item_link']['url']); ?>">
                                <img src="<?php echo esc_url($item['portfolio6_item_image']['url']); ?>" alt="<?php echo esc_attr($item['portfolio6_item_title']); ?>">
                            </a>
                            <div class="cont">
                                <h6><?php echo esc_html($item['portfolio6_item_title']); ?></h6>
                                <span>
                                    <a href="<?php echo esc_url($item['portfolio6_item_cat_link']['url']); ?>"><?php echo esc_html($item['portfolio6_item_cat']); ?></a>, 
                                    <a href="<?php echo esc_url($item['portfolio6_item_cat_link2']['url']); ?>"><?php echo esc_html($item['portfolio6_item_cat2']); ?></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>

            </div>

        </div>
    </div>
</div>
