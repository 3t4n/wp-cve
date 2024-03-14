<div class="better-team style-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 better-valign">
                <div class="better-full-width">
                    <div class="mb-0">
                        <h6><?php echo esc_html($settings['better_team5_sub_title']);?></h6>
                        <h3><?php echo esc_html($settings['better_team5_title']);?></h3>
                    </div>
                    <div class="navs mt-30 wow fadeInUp" data-wow-delay=".3s">
                        <span class="prev cursor-pointer">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <span class="next cursor-pointer">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="team-container">
                    <?php foreach ($settings['better_member_list'] as $item): ?>
                        <div class="item wow fadeInUp" data-wow-delay=".3s">
                            <div class="img wow imago">
                                <img src="<?php echo esc_url($item['better_team2_image']['url']); ?>" alt="">
                            </div>
                            <div class="info">
                                <h5><?php echo esc_html($item['better_team2_title']);?></h5>
                                <span><?php echo esc_html($item['better_team2_desg']);?></span>
                                <div class="social">
                                    <?php for ($i = 1; $i <= 4; $i++):
                                        if (!empty($item["better_team2_social_link_$i"]['url'])): ?>
                                            <a href="<?php echo esc_url($item["better_team2_social_link_$i"]['url']); ?>">
                                                <i class="<?php echo esc_attr($item["better_team2_social_icon_$i"]['value']); ?>"></i>
                                            </a>
                                        <?php endif;
                                    endfor; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
