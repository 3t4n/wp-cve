<div class="better-team style-4">
    <div class="item <?php echo esc_attr($settings['better_team_hover_style']); ?>">
        <div class="img">
            <img src="<?php echo esc_url($settings['better_team_image']['url']); ?>" alt="">
        </div>
        <div class="info better-valign">
            <div class="better-full-width">
                <h6><?php echo esc_html($settings['better_team_title']); ?></h6>
                <p><?php echo esc_html($settings['better_team_desg']); ?></p>
                <div class="social">
                    <?php foreach ($settings['better_social_list'] as $index => $item): 
                        $link_key = 'link_' . $index;
                        $this->add_render_attribute($link_key, [
                            'href' => esc_url($item['better_social_link']['url']),
                            'target' => $item['better_social_link']['is_external'] ? '_blank' : '',
                            'rel' => $item['better_social_link']['nofollow'] ? 'nofollow' : '',
                        ]); ?>
                        <a <?php echo $this->get_render_attribute_string($link_key); ?>>
                            <i class="<?php echo esc_attr($item['better_social_icon']['value']); ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="shape"></div>
    </div>
</div>
