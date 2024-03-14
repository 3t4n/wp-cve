<div class="better-price style-1">
    <div class="price-title">
        <h4><?php echo esc_html($better_price_box_title); ?></h4>
    </div>
    <div class="price-tag">
        <h2><?php echo esc_html($better_price_box_amount); ?> <span><?php echo esc_html($better_price_box_plan); ?></span></h2>
    </div>
    <div class="price-item">
        <ul>
            <?php foreach ($settings['better_price_box_features_list'] as $item) { 
                $better_price_box_features = $item['better_price_box_features'];
            ?>
                <li class="price-area__item"><?php echo esc_html($better_price_box_features); ?></li>
            <?php } ?>
        </ul>
    </div>
    <a href="<?php echo esc_url($better_price_box_button_link); ?>" class="box-btn"><?php echo esc_html($better_price_box_button_text); ?></a>
</div>
