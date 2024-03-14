<section class="better-portfolio style-4 better-section-padding gutter pt-0">
    <div class="container">
        <div class="row">
        
            <div class="filtering col-12">
                <div class="filter">
                    <span data-filter='*' class="active">All</span>
                    <?php foreach ($settings['portfolio_categories'] as $index => $item): ?>
                        <span data-filter='.<?php echo esc_attr($item['item_category_slug']); ?>'><?php echo esc_html($item['item_category_title']); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="gallery better-full-width">

                <?php foreach ($settings['portfolio4_items'] as $index => $item): ?>
                    <div class="col-md-<?php echo intval($settings['better_portfolio4_columns']) ?> items <?php echo esc_attr($item['portfolio4_item_category_slug_call']); ?>">
                        <div class="item-img wow fadeInUp" data-wow-delay=".4s">
                            <a href="<?php echo esc_url($item['portfolio4_item_link']['url']); ?>">
                                <img src="<?php echo esc_url($item['portfolio4_item_image']['url']); ?>" alt="<?php echo esc_attr($item['portfolio4_item_title']); ?>">
                                <div class="item-img-overlay better-valign">
                                    <div class="overlay-info better-full-width">
                                        <h5 data-splitting><?php echo esc_html($item['portfolio4_item_title']); ?></h5>
                                        <p class="wow txt" data-splitting><?php echo esc_html($item['portfolio4_item_cat']); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

        </div>
    </div>
</section>
