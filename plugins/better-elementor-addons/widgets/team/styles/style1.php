<div class="better-team style-1">
    <img src="<?php echo esc_url($better_team_image); ?>" alt="">
    <div class="team-hover">
        <div class="team-hover-table">
            <div class="team-hover-cell">
                <h4><?php echo esc_html($better_team_title); ?></h4>
                <p><?php echo esc_html($better_team_desg); ?></p>
                <div class="team-social">
                    <?php foreach ($settings['better_social_list'] as $item): 
                        $better_social_title = $item['better_social_title'];
                        $better_social_icon = $item['better_social_icon']['value'];
                        $better_social_link = $item['better_social_link']['url'];
                    ?>
                        <a href="<?php echo esc_url($better_social_link); ?>"><i class="<?php echo esc_attr($better_social_icon); ?>"></i></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
