<div class="better-img-box-slider style-1">
    <div class="prod-slick">
        <?php foreach ($settings['imgbox_list'] as $index => $item) : ?>
            <div class="item">
                <div class="img better-bg-img" data-background="<?php echo esc_url($item['image']['url']); ?>"></div>
                <div class="cont">
                    <h6><?php echo esc_html($item['title']); ?></h6>
                    <div class="tags">
                        <?php if (!empty($item['tag_text_1'])) : ?>
                            <a href="<?php echo esc_url($item['tag_link_1']['url']); ?>"><?php echo esc_html($item['tag_text_1']); ?></a>
                        <?php endif; ?>
                        <?php if (!empty($item['tag_text_2'])) : ?>
                            <a href="<?php echo esc_url($item['tag_link_2']['url']); ?>"><?php echo esc_html($item['tag_text_2']); ?></a>
                        <?php endif; ?>
                        <?php if (!empty($item['tag_text_3'])) : ?>
                            <a href="<?php echo esc_url($item['tag_link_3']['url']); ?>"><?php echo esc_html($item['tag_text_3']); ?></a>
                        <?php endif; ?>
                    </div>
                    <h5 class="price">$ <?php echo esc_html($item['price']); ?></h5>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($settings['show_arrows'] === 'true') : ?>
        <div class="control">
            <span class="prev"><i class="pe-7s-angle-left"></i></span>
            <span class="next"><i class="pe-7s-angle-right"></i></span>
        </div>
    <?php endif; ?>
</div>
