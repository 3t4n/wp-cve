<?php
add_action('spiceb_wphester_team_action','spiceb_wphester_team_section');

function spiceb_wphester_team_section(){   
$team_options = get_theme_mod('wphester_team_content');

$team_animation_speed = get_theme_mod('team_animation_speed', 3000);
$team_smooth_speed = get_theme_mod('team_smooth_speed', 1000);
$team_nav_style = get_theme_mod('team_nav_style', 'bullets');
$isRTL = (is_rtl()) ? (bool) true : (bool) false;
$teamsettings = array('team_animation_speed' => $team_animation_speed, 'team_smooth_speed' => $team_smooth_speed, 'team_nav_style' => $team_nav_style, 'rtl' => $isRTL);
wp_register_script('wphester-team', SPICEB_PLUGIN_URL . 'inc/wphester/js/front-page/team.js', array('jquery'));
wp_localize_script('wphester-team', 'team_settings', $teamsettings);
wp_enqueue_script('wphester-team');

if (empty($team_options)) {
    $team_options = json_encode(array(
                array(
                    'image_url' => SPICEB_PLUGIN_URL . 'inc/busicare/images/team/team1.jpg',
                    'membername' => 'Danial Wilson',
                    'designation' => esc_html__('Senior Manager', 'spicebox'),
                    'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime quae, dolores dicta. Blanditiis rem amet repellat, dolores nihil quae in mollitia asperiores ut rerum repellendus, voluptatum eum, officia laudantium quaerat?',
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_26d7ea7f40c56',
                    'social_repeater' => json_encode(
                            array(
                                array(
                                    'id' => 'customizer-repeater-social-repeater-37fb908374e06',
                                    'link' => 'facebook.com',
                                    'icon' => 'fa-facebook',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-47fb9144530fc',
                                    'link' => 'twitter.com',
                                    'icon' => 'fa-twitter',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9750e1e09',
                                    'link' => 'linkedin.com',
                                    'icon' => 'fa-linkedin',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-67fb0150e1e256',
                                    'link' => 'behance.com',
                                    'icon' => 'fa-behance',
                                ),
                            )
                    ),
                ),
                array(
                    'image_url' => SPICEB_PLUGIN_URL . 'inc/busicare/images/team/team2.jpg',
                    'membername' => 'Amanda Smith',
                    'designation' => esc_html__('Founder & CEO', 'spicebox'),
                    'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime quae, dolores dicta. Blanditiis rem amet repellat, dolores nihil quae in mollitia asperiores ut rerum repellendus, voluptatum eum, officia laudantium quaerat?',
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d1ea2f40c66',
                    'social_repeater' => json_encode(
                            array(
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9133a7772',
                                    'link' => 'facebook.com',
                                    'icon' => 'fa-facebook',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9160rt683',
                                    'link' => 'twitter.com',
                                    'icon' => 'fa-twitter',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb916zzooc9',
                                    'link' => 'linkedin.com',
                                    'icon' => 'fa-linkedin',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb916qqwwc784',
                                    'link' => 'behance.com',
                                    'icon' => 'fa-behance',
                                ),
                            )
                    ),
                ),
                array(
                    'image_url' => SPICEB_PLUGIN_URL . 'inc/busicare/images/team/team3.jpg',
                    'membername' => 'Victoria Wills',
                    'designation' => esc_html__('Web Master', 'spicebox'),
                    'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime quae, dolores dicta. Blanditiis rem amet repellat, dolores nihil quae in mollitia asperiores ut rerum repellendus, voluptatum eum, officia laudantium quaerat?',
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
                    'image_url' => SPICEB_PLUGIN_URL . 'inc/busicare/images/team/team4.jpg',
                    'membername' => 'Travis Marcus',
                    'designation' => esc_html__('UI Developer', 'spicebox'),
                    'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime quae, dolores dicta. Blanditiis rem amet repellat, dolores nihil quae in mollitia asperiores ut rerum repellendus, voluptatum eum, officia laudantium quaerat?',
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
if ($team_section_enable != false) {
$team_section_class = 'team bg-default';
?>
<section class="section-space <?php echo esc_attr($team_section_class);?>">
    <div class="wphester-team-container container">
        <?php
        $home_team_section_title = get_theme_mod('home_team_section_title', __('Magna Aliqua', 'spicebox'));
        $home_team_section_discription = get_theme_mod('home_team_section_discription', __('Ullamco Laboris Nisi', 'spicebox'));
        if (($home_team_section_title) || ($home_team_section_discription) != '') {?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="section-header">
                <?php                
                if(!empty($home_team_section_title)):?><h2 class="section-title"><?php echo esc_html($home_team_section_title); ?></h2><?php endif;
                if(!empty($home_team_section_discription)):?><p class="section-subtitle"><?php echo esc_html($home_team_section_discription); ?></p>
                <?php endif;?>
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
                    $image = !empty($team_item->image_url) ? apply_filters('wphester_translate_single_string', $team_item->image_url, 'Team section') : '';
                   
                    $title = !empty($team_item->membername) ? apply_filters('wphester_translate_single_string', $team_item->membername, 'Team section') : '';
                    $subtitle = !empty($team_item->designation) ? apply_filters('wphester_translate_single_string', $team_item->designation, 'Team section') : '';
                    $aboutme = !empty($team_item->text) ? apply_filters('wphester_translate_single_string', $team_item->text, 'Team section') : '';?>                    
                    <div class="item">
                        <div class="team-grid text-center">
                        <?php if(!empty($image)){ ?>
                            <div class="img-holder"> 
                                <img src="<?php echo esc_url($image); ?>" class="img-fluid"> 
                            </div>   
                        <?php }                        
                        $icons = html_entity_decode($team_item->social_repeater);
                        $icons_decoded = json_decode($icons, true);
                        $socails_counts = $icons_decoded;
                        if (!empty($socails_counts)) :
                            if (!empty($icons_decoded)) : ?>
                                <div class="social-share">
                                    <div class="slideouticons">
                                        <label class="mainlabel"><i class="fa fa-share-alt"></i></label>
                                        <div class="icon-wrapper">
                                            <ul class="list-inline list-unstyled ml-0 mt-3 mb-1">
                                            <?php foreach ($icons_decoded as $value){
                                                 $social_icon = !empty($value['icon']) ? apply_filters('wphester_translate_single_string', $value['icon'], 'Team section') : '';
                                                 $social_link = !empty($value['link']) ? apply_filters('wphester_translate_single_string', $value['link'], 'Team section') : '';
                                                if (!empty($social_icon)){ ?>                          
                                                    <li class="list-inline-item"><a class="p-2 fa-lg fb-ic" <?php if ($open_new_tab == 'yes') { echo 'target="_blank"'; } ?> href="<?php echo esc_url($social_link); ?>" class="btn btn-just-icon btn-simple"><i class="fa <?php echo esc_attr($social_icon); ?> " aria-hidden="true"></i></a></li>
                                                <?php
                                                }
                                            }?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;
                        endif;?>
                        <!-- Content -->
                        <div class="card-body">
                            <h4 class="name mt-1 mb-2"><?php echo esc_html($title);?></h4>
                            <p class="mt-1 mb-2"><?php echo esc_html($subtitle);?></p>
                        </div>
                        </div>               
                    </div>
                    <?php
                }
            }?>
            </div>  
        </div>
    </div>  
</section>  
<?php
}
}