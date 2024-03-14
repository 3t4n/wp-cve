<div class="better-team style-2 row">
    <div class="col-lg-12">
        <div class="team-container">
            <?php foreach ($settings['better_member_list'] as $item): ?>
                <div class="item wow fadeInUp" data-wow-delay=".3s">
                    <div class="img wow imago">
                        <img src="<?php echo esc_url($item['better_team2_image']['url']); ?>" alt="">
                        <div class="social better-valign">
                            <div class="better-full-width">
                                <?php for ($i = 1; $i <= 4; $i++): ?>
                                    <?php if (!empty($item['better_team2_social_link_' . $i]['url'])): ?>
                                        <a href="<?php echo esc_url($item['better_team2_social_link_' . $i]['url']); ?>">
                                            <i class="<?php echo esc_attr($item['better_team2_social_icon_' . $i]['value']); ?>"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    <div class="info">
                        <h5 class="custom-font"><?php echo esc_html($item['better_team2_title']); ?></h5>
                        <span><?php echo esc_html($item['better_team2_desg']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
