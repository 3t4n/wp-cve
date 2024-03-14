<?php

defined('ABSPATH') or die();

class wl_companion_teams {

    public static function wl_companion_teams_html() {
?>
        <div id="team"></div>
        <div class="enigma_team_section">
            <?php if (!empty(get_theme_mod('team_title'))) { ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="enigma_heading_title">
                                <h3><?php echo get_theme_mod('team_title', 'Our Team'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (!empty(get_theme_mod('enigma_team_data'))) { ?>
                <div class="container scrollimation scale-in">
                    <?php $name_arr = unserialize(get_theme_mod('enigma_team_data'));
                    foreach ($name_arr as $key => $value) {
                        if (!empty($value['team_image'])) {
                    ?>

                            <div class="col-md-3 service scrollimation scale-in d2 pull-left in mb-5">
                                <img class="img-circle rounded-circle img-responsive" src="<?php echo esc_url($value['team_image']); ?>" height="261px" width="276px">
                                <?php if (!empty($value['team_designation'])) { ?>
                                    <div class="pos"><?php esc_html_e($value['team_designation'], WL_COMPANION_DOMAIN);  ?></div>
                                <?php } ?>

                                <div class="caption">
                                    <div class="long">
                                        <h3 class="team_"><?php if (!empty($value['team_name'])) { esc_html_e($value['team_name'], WL_COMPANION_DOMAIN); } ?></h3>
                                    </div>
                                    <div class="team_social">
                                        <?php if (!empty($value['team_text'])) { ?>
                                            <a href="<?php echo esc_url($value['team_text']); ?>"><i class="fab fa-facebook-f"></i></a>
                                        <?php }
                                        if (!empty($value['team_link'])) { ?>
                                            <a href="<?php echo esc_url($value['team_link']); ?>"><i class="fab fa-twitter"></i></a>
                                        <?php }
                                        if (!empty($value['team_ldn_link'])) { ?>
                                            <a href="<?php echo esc_url($value['team_ldn_link']); ?>"><i class="fab fa-linkedin-in"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
            <?php } else { ?>

                <div class="container scrollimation scale-in">
                    <?php
                    for ($i = 1; $i <= 4; $i++) {
                    ?>
                        <div class="col-md-3 service scrollimation scale-in d2 pull-left in mb-5">
                            <?php if (get_theme_mod('team_' . $i . '_img')) { ?>
                                <img class="img-circle rounded-circle img-responsive" src="<?php echo esc_url(get_theme_mod('team_' . $i . '_img')); ?>" height="261px" width="276px">
                            <?php }
                            if (get_theme_mod('team_post_' . $i)) { ?>
                                <div class="pos"><?php esc_html_e(get_theme_mod('team_post_' . $i), WL_COMPANION_DOMAIN); ?></div>
                            <?php } ?>

                            <div class="caption">
                                <div class="long">
                                    <h3 class="team_<?php esc_attr_e($i, WL_COMPANION_DOMAIN) ?>"><?php esc_html_e(get_theme_mod('team_name_' . $i), WL_COMPANION_DOMAIN); ?></h3>
                                </div>
                                <div class="team_social">
                                    <?php if (!empty(get_theme_mod('team_fb_' . $i))) { ?>
                                        <a href="<?php echo esc_url(get_theme_mod('team_fb_' . $i)); ?>"><i class="fab fa-facebook-f"></i></a>
                                    <?php }
                                    if (!empty(get_theme_mod('team_twitter_' . $i))) { ?>
                                        <a href="<?php echo esc_url(get_theme_mod('team_twitter_' . $i)); ?>"><i class="fab fa-twitter"></i></a>
                                    <?php }
                                    if (!empty(get_theme_mod('team_linkedin_' . $i))) { ?>
                                        <a href="<?php echo esc_url(get_theme_mod('team_linkedin_' . $i)); ?>"><i class="fab fa-linkedin-in"></i></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php  } ?>
                </div><!-- row end--->
            <?php  } ?>
        </div> <!-- container div end here -->
<?php
    }
}
?>