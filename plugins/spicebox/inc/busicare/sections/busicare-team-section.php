<?php
add_action('spiceb_busicare_team_action','spiceb_busicare_team_section');

function spiceb_busicare_team_section(){   
$team_options = get_theme_mod('busicare_team_content');
$team_nav_style = get_theme_mod('team_nav_style', 'bullets');
$isRTL = (is_rtl()) ? (bool) true : (bool) false;
$teamsettings = array('teamcarouselid' => '#team-carousel','team_nav_style' => $team_nav_style, 'rtl' => $isRTL);
wp_register_script('busicare-team', SPICEB_PLUGIN_URL . 'inc/busicare/js/front-page/team.js', array('jquery'));
wp_localize_script('busicare-team', 'team_settings', $teamsettings);
wp_enqueue_script('busicare-team');

if (empty($team_options)) {
    $team_options = json_encode(array(
                array(
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/team/team1.jpg',
                    'membername' => 'Danial Wilson',
                    'designation' => esc_html__('Senior Manager', 'busicare-plus'),
                    'link' => '#',
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d7ea7f40c56',
                    'social_repeater' => json_encode(
                            array(
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb908674e06',
                                    'link' => 'facebook.com',
                                    'icon' => 'fa-facebook',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9148530fc',
                                    'link' => 'twitter.com',
                                    'icon' => 'fa-twitter',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9150e1e89',
                                    'link' => 'linkedin.com',
                                    'icon' => 'fa-linkedin',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9150e1e256',
                                    'link' => 'behance.com',
                                    'icon' => 'fa-behance',
                                ),
                            )
                    ),
                ),
                array(
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/team/team2.jpg',
                    'membername' => 'Amanda Smith',
                    'designation' => esc_html__('Founder & CEO', 'busicare-plus'),
                    'link' => '#',
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d7ea7f40c66',
                    'social_repeater' => json_encode(
                            array(
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9155a1072',
                                    'link' => 'facebook.com',
                                    'icon' => 'fa-facebook',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9160ab683',
                                    'link' => 'twitter.com',
                                    'icon' => 'fa-twitter',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb916ddffc9',
                                    'link' => 'linkedin.com',
                                    'icon' => 'fa-linkedin',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb916ddffc784',
                                    'link' => 'behance.com',
                                    'icon' => 'fa-behance',
                                ),
                            )
                    ),
                ),
                array(
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/team/team3.jpg',
                    'membername' => 'Victoria Wills',
                    'designation' => esc_html__('Web Master', 'busicare-plus'),
                    'link' => '#',
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d7ea7f40c76',
                    'social_repeater' => json_encode(
                            array(
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb917e4c69e',
                                    'link' => 'facebook.com',
                                    'icon' => 'fa-facebook',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb91830825c',
                                    'link' => 'twitter.com',
                                    'icon' => 'fa-twitter',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb918d65f2e',
                                    'link' => 'linkedin.com',
                                    'icon' => 'fa-linkedin',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb918d65f2e8',
                                    'link' => 'behance.com',
                                    'icon' => 'fa-behance',
                                ),
                            )
                    ),
                ),
                array(
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/team/team4.jpg',
                    'membername' => 'Travis Marcus',
                    'designation' => esc_html__('UI Developer', 'busicare-plus'),
                    'link' => '#',
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d7ea7f40c86',
                    'social_repeater' => json_encode(
                            array(
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb925cedcb2',
                                    'link' => 'facebook.com',
                                    'icon' => 'fa-facebook',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb92615f030',
                                    'link' => 'twitter.com',
                                    'icon' => 'fa-twitter',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9266c223a',
                                    'link' => 'linkedin.com',
                                    'icon' => 'fa-linkedin',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9266c223a',
                                    'link' => 'behance.com',
                                    'icon' => 'fa-behance',
                                ),
                            )
                    ),
                ),
                    ));
}
$team_section_enable = get_theme_mod('team_section_enable', true);
if ($team_section_enable != false) {?>
<section class="section-space team-group">
    <div class="busicare-team-container container">
        <?php
        $home_team_section_title = get_theme_mod('home_team_section_title', __('Lorem ipsum', 'spicebox'));
        $home_team_section_discription = get_theme_mod('home_team_section_discription', __('Lorem ipsum dolor sit ame', 'spicebox'));
        if (($home_team_section_title) || ($home_team_section_discription) != '') {?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="section-header">
                        <?php if (!empty($home_team_section_title)): ?>
                            <h2 class="section-title"><?php echo esc_html($home_team_section_title); ?></h2>
                            <div class="title_seprater"></div>
                            <?php
                        endif;

                        if (!empty($home_team_section_discription)):
                            ?>
                            <h5 class="section-subtitle"><?php echo esc_html($home_team_section_discription); ?></h5>
                        <?php endif; ?>
                    </div>
                </div>                      
            </div>
        <?php } ?>
        <div class="row">
            <div id="team-carousel" class="owl-carousel owl-theme col-lg-12">
                <?php
                $team_options = json_decode($team_options);
                
                if (!empty($team_options)) {
                    foreach ($team_options as $team_item) {
                        $image = !empty($team_item->image_url) ? apply_filters('busicare_translate_single_string', $team_item->image_url, 'Team section') : '';
                        $title = !empty($team_item->membername) ? apply_filters('busicare_translate_single_string', $team_item->membername, 'Team section') : '';
                        $subtitle = !empty($team_item->designation) ? apply_filters('busicare_translate_single_string', $team_item->designation, 'Team section') : '';
                        $link = !empty($team_item->link) ? apply_filters('busicare_translate_single_string', $team_item->link, 'Team section') : '';
                        $open_new_tab = $team_item->open_new_tab;
                        ?>
                       <div class="item">
                            <div class="team-grid text-center">
                                <div class="img-holder">
                                    <?php if (!empty($image)) : ?>
                                        <?php
                                        if ($open_new_tab == 'yes') {$tm_trgt="_blank";}
                                        else{$tm_trgt="_self";}
                                        if (!empty($link)) :
                                            $link_html = '<a target="'.esc_attr($tm_trgt).'" href="' . esc_url($link) . '"';
                                            if (function_exists('busicare_is_external_url')) {
                                                $link_html .= busicare_is_external_url($link);
                                            }
                                            $link_html .= '>';
                                            echo wp_kses_post($link_html);
                                        endif;
                                        echo '<img class="img-fluid" src="' . esc_url($image) . '"';
                                        if (!empty($title)) {
                                            echo 'alt="' . esc_attr($title) . '" title="' . esc_attr($title) . '"';
                                        }
                                        echo '/>';
                                        if (!empty($link)) {
                                            echo '</a>';
                                        }
                                        ?>
                                    <?php endif; ?>

                                    <?php
                                    $icons = html_entity_decode($team_item->social_repeater);
                                    $icons_decoded = json_decode($icons, true);
                                    $socails_counts = $icons_decoded;
                                    if (!empty($socails_counts)) :
                                        ?> <div class="social-group"> 
                                            <?php if (!empty($icons_decoded)) : ?>
                                                <ul class="custom-social-icons">
                                                    <?php
                                                    foreach ($icons_decoded as $value) {
                                                        $social_icon = !empty($value['icon']) ? apply_filters('busicare_translate_single_string', $value['icon'], 'Team section') : '';
                                                        $social_link = !empty($value['link']) ? apply_filters('busicare_translate_single_string', $value['link'], 'Team section') : '';
                                                        if (!empty($social_icon)) {
                                                            ?>                          
                                                            <li>
                                                                <a <?php if ($open_new_tab == 'yes') {?>target="_blank"<?php }?> href="<?php echo esc_url($social_link);?>" class="btn btn-just-icon btn-simple">
                                                                    <i class="fa <?php echo esc_attr($social_icon); ?> ">
                                                                    </i>
                                                                </a>
                                                            </li>
                                                            <?php
                                                        }
                                                    }?>
                                                </ul>
                                            <?php endif;?>
                                            </div>
                                    <?php endif;?>
                                </div>
                                <?php if ($title != '' || $subtitle != ''): ?>
                                    <figcaption class="details">
                                        <?php if (!empty($title)) : ?>
                                            <?php if (!empty($link)) : ?>
                                                <a href="<?php echo esc_url($link);?>" <?php
                                                if ($open_new_tab == 'yes') {
                                                    echo 'target="_blank"';
                                                }
                                                ?>>
                                                   <?php endif; ?>
                                                <h4 class="name"><?php echo esc_html($title); ?></h4>
                                                <?php if (!empty($link)) : ?>   
                                                </a>
                                            <?php endif; ?> 
                                        <?php endif; ?>
                                        <?php if (!empty($subtitle)) : ?>
                                            <span class="position"><?php echo esc_html($subtitle); ?></span>
                                        <?php endif; ?>
                                    </figcaption>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    }
                } ?>
           </div>
        </div>
    </div>  
</section>  
<?php
}
}