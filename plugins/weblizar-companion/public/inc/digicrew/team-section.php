<?php

defined('ABSPATH') or die();

class wl_companion_teams {

    public static function wl_companion_teams_html() {
?>
        <!--our-Team-->
        <section class="ws-section-spacing">
            <div class="container">
                <?php if (!empty(get_theme_mod('digicrew_team_title'))) { ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title-two ">
                                <h2><?php echo get_theme_mod('digicrew_team_title', 'Meet Our Team'); ?></h2>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!empty(get_theme_mod('digicrew_team_data'))) { ?>
                    <div class="row">
                        <?php
                        $name_arr = unserialize(get_theme_mod('digicrew_team_data'));
                        foreach ($name_arr as $key => $value) {
                        ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="team-box">
                                    <?php if (!empty($value['team_image'])) { ?>
                                        <div class="img-wapper">
                                            <img src="<?php echo esc_url($value['team_image']); ?>" alt="team-1">
                                        </div>
                                    <?php } ?>
                                    <div class="team-overlay">
                                        <div class="team-info">
                                            <h3><?php if (!empty($value['team_name'])) {
                                                    esc_html_e($value['team_name'], WL_COMPANION_DOMAIN);
                                                } ?></h3>
                                            <h4><?php if (!empty($value['team_designation'])) {
                                                    esc_html_e($value['team_designation'], WL_COMPANION_DOMAIN);
                                                } ?></h4>
                                            <ul class="social-icon">
                                                <?php if (!empty($value['fb_link'])) { ?>
                                                    <li><a href="<?php esc_html_e($value['fb_link'], WL_COMPANION_DOMAIN); ?>"><i class="fab fa-facebook-f"></i></a></li>
                                                <?php } ?>
                                                <?php if (!empty($value['twitter_link'])) { ?>
                                                    <li><a href="<?php esc_html_e($value['twitter_link'], WL_COMPANION_DOMAIN); ?>"><i class="fab fa-twitter"></i></a></li>
                                                <?php } ?>
                                                <?php if (!empty($value['insta_link'])) { ?>
                                                    <li><a href="<?php esc_html_e($value['insta_link'], WL_COMPANION_DOMAIN); ?>"><i class="fab fa-instagram"></i></a></li>
                                                <?php } ?>
                                                <?php if (!empty($value['google_plus_link'])) { ?>
                                                    <li><a href="<?php esc_html_e($value['google_plus_link'], WL_COMPANION_DOMAIN); ?>"><i class="fab fa-linkedin-in"></i></a></li>
                                                <?php } ?>
                                                <?php if (!empty($value['youtube_link'])) { ?>
                                                    <li><a href="<?php esc_html_e($value['youtube_link'], WL_COMPANION_DOMAIN); ?>"><i class="fab fa-youtube"></i></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>
        <!--//our-Team-->
<?php
    }
}
?>