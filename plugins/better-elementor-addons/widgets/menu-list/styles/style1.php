<div class="better-menu-list style-1">
    <?php foreach ($settings['menu_menu_list_1'] as $index => $item) : ?>
        <div class="menu-block">
            <div class="item-thumb">
                <img src="<?php echo esc_url($item['image_1']['url']); ?>" alt="img">
            </div>
            <div class="item-inner">
                <div class="info clearfix">
                    <h3 class="list-title pull-left"><?php echo esc_html($item['title_1']); ?></h3>
                    <h3 class="list-price pull-right"><?php echo esc_html($item['price_1']); ?></h3>
                </div>
                <div class="list-desk">
                    <p><?php echo esc_html($item['description_1']); ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div><!--/.testimonial-->
