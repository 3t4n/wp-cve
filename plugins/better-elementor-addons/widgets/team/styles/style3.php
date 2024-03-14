<div class="better-team style-3">
    <div class="item better-bg-img better-bg-repeat" data-background="<?php echo esc_url(plugins_url('/assets/img/dotz.png', dirname(__FILE__, 3))); ?>" style="background-repeat: repeat; background-size: auto;">
        <div class="img">
            <img src="<?php echo esc_url($settings['better_team_image']['url']); ?>" alt="">
        </div>
        <div class="info better-valign">
            <div class="better-full-width">
                <h6 class="scfont"><?php echo esc_html($settings['better_team_desg']); ?></h6>
                <h5><?php echo esc_html($settings['better_team_title']); ?></h5>
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
    </div>
</div>
