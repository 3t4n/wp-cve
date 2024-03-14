<?php
if ( ! function_exists( 'spiceb_cloudpress_team' ) ) :
    function spiceb_cloudpress_team() {
        $team_section_enable = get_theme_mod('team_section_enable','on');
        $team_options = get_theme_mod('cloudpress_team_content');
        $team_animation_speed = get_theme_mod('team_animation_speed', 3000);
        $team_smooth_speed = get_theme_mod('team_smooth_speed', 1000);
        $team_nav_style = get_theme_mod('team_nav_style', 'bullets');
        $teamsettings = array(
          'team_animation_speed'  => $team_animation_speed,
          'team_smooth_speed'     => $team_smooth_speed,
          'team_nav_style'        => $team_nav_style
        );
        wp_register_script('cloudpress-team', SPICEB_PLUGIN_URL.'inc/js/cloudpress/team.js',array('jquery'));
        wp_localize_script('cloudpress-team','team_settings',$teamsettings);
        wp_enqueue_script('cloudpress-team');
        if($team_section_enable !='off')
    		{ ?>
        <section class="section-module team-members" id="team">
            <div class="container-fluid fullwidth">
            <?php
                $home_team_section_title = get_theme_mod('home_team_section_title',__('Cras ullamcorper turpis','spicebox'));
                $home_team_section_discription = get_theme_mod('home_team_section_discription',__('Cras blandit fringilla suscipit','spicebox'));	?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-header">
                            <?php
                            if(!empty($home_team_section_title)):?>
                                <h5 class="section-subtitle"><?php echo esc_html($home_team_section_title);?></h5>
                            <?php endif; if(!empty($home_team_section_discription)): ?>
                                <h2 class="section-title"><?php echo esc_html($home_team_section_discription);?></h2>
                            <?php endif;?>
                        </div>
                    </div>
                </div>

                <div class="row">
                  <div id="team-carousel" class="owl-carousel owl-theme col-md-12">
                      <?php $team_options = json_decode($team_options);
                      if( $team_options!='' )
                      {
                        foreach($team_options as $team_item) {
                          $image    = ! empty( $team_item->image_url ) ? apply_filters( 'cloudpress_translate_single_string', $team_item->image_url, 'Team section' ) : '';
                          $title    = ! empty( $team_item->title ) ? apply_filters( 'cloudpress_translate_single_string', $team_item->title, 'Team section' ) : '';
                          $subtitle = ! empty( $team_item->subtitle ) ? apply_filters( 'cloudpress_translate_single_string', $team_item->subtitle, 'Team section' ) : '';
                          $link     = ! empty( $team_item->link ) ? apply_filters( 'cloudpress_translate_single_string', $team_item->link, 'Team section' ) : '#';
                          $open_new_tab = $team_item->open_new_tab; ?>
                          <div class="item">
                              <div class="team-grid">
                                   <div class="img-holder">
                                     <img src="<?php echo esc_url($image);?>" >
                                   </div>
                                   <div class="details">
                                     <a href="<?php echo $link;?>" <?php if($open_new_tab=='yes'):?> target="_blank" <?php endif;?>><h6 class="name"><?php echo esc_html( $title ); ?></h6></a>
                                     <span class="position"><?php echo esc_html( $subtitle ); ?></span>
                                     <?php
                                     $icons         = html_entity_decode( $team_item->social_repeater );
                                     $icons_decoded = json_decode( $icons, true );
                                     if ( ! empty( $icons_decoded ) ) :?>
                                         <ul class="social-links">
                                            <?php
                                            foreach( $icons_decoded as $value )
                                            {
                                                $social_icon = ! empty( $value['icon'] ) ? apply_filters( 'cloudpress_translate_single_string', $value['icon'], 'Team section' ) : '';
                                                $social_link = ! empty( $value['link'] ) ? apply_filters( 'cloudpress_translate_single_string', $value['link'], 'Team section' ) : '';
                                                if ( ! empty( $social_icon ) )
                                                {
                                                ?>
                                                    <li><a <?php if($open_new_tab == 'yes'){ echo 'target="_blank"';}?> href="<?php echo esc_url( $social_link ); ?>" ><i class="fa <?php echo esc_attr( $social_icon ); ?> "></i></a></li>
                                                <?php
                                                }
                                            }
                                            ?>
                                         </ul>
                                     <?php endif;?>
                                   </div>
                               </div>
                            </div>
                      <?php }
                      }
                      else {
                      ?>
                      <div class="item">
                          <div class="team-grid">
                              <div class="img-holder">
                              <img src="<?php echo SPICEB_PLUGIN_URL ?>inc/cloudpress/images/team/team01.jpg" alt="<?php esc_attr_e('Curabitur maximus','spicebox'); ?>">
                              </div>
                              <div class="details">
                                  <h5 class="name"><?php esc_html_e('Curabitur maximus','spicebox'); ?></h5>
                                  <span class="position"><?php esc_html_e('Duis lobortis','spicebox'); ?></span>
                                  <ul class="social-links">
                                      <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                      <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                      <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                      <li><a href="#"><i class="fa fa-behance"></i></a></li>
                                  </ul>
                              </div>
                          </div>
                        </div>

                        <div class="item">
                            <div class="team-grid">
                                <div class="img-holder">
                                <img src="<?php echo SPICEB_PLUGIN_URL ?>inc/cloudpress/images/team/team02.jpg" alt="<?php esc_attr_e('Nulla sit amet','spicebox'); ?>">
                                </div>
                                <div class="details">
                                    <h5 class="name"><?php esc_html_e('Nulla sit amet','spicebox'); ?></h5>
                                    <span class="position"><?php esc_html_e('Quisque suscipit','spicebox'); ?></span>
                                    <ul class="social-links">
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                        <li><a href="#"><i class="fa fa-behance"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                         </div>

                          <div class="item">
                              <div class="team-grid">
                                  <div class="img-holder">
                                   <img src="<?php echo SPICEB_PLUGIN_URL ?>inc/cloudpress/images/team/team03.jpg" alt="<?php esc_attr_e('Nam maximus','spicebox'); ?>">
                                  </div>
                                  <div class="details">
                                      <h5 class="name"><?php esc_html_e('Nam maximus','spicebox'); ?></h5>
                                      <span class="position"><?php esc_html_e('Mauris convallis','spicebox'); ?></span>
                                      <ul class="social-links">
                                          <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                          <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                          <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                          <li><a href="#"><i class="fa fa-behance"></i></a></li>
                                      </ul>
                                  </div>
                               </div>
                          </div>

                          <div class="item">
                              <div class="team-grid">
                                  <div class="img-holder">
                                   <img src="<?php echo SPICEB_PLUGIN_URL ?>inc/cloudpress/images/team/team04.jpg" alt="<?php esc_attr_e('Aliquam maximus','spicebox'); ?>">
                                  </div>
                                  <div class="details">
                                      <h5 class="name"><?php esc_html_e('Aliquam maximus','spicebox'); ?></h5>
                                      <span class="position"><?php esc_html_e('Mauris feugiat','spicebox'); ?></span>
                                      <ul class="social-links">
                                          <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                          <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                          <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                          <li><a href="#"><i class="fa fa-behance"></i></a></li>
                                      </ul>
                                  </div>
                               </div>
                           </div>

                          <div class="item">
                              <div class="team-grid">
                                  <div class="img-holder">
                                      <img src="<?php echo SPICEB_PLUGIN_URL ?>inc/cloudpress/images/team/team05.jpg" alt="<?php esc_attr_e('Aenean sit amet','spicebox'); ?>">
                                  </div>
                                  <div class="details">
                                      <h5 class="name"><?php esc_html_e('Aenean sit amet','spicebox'); ?></h5>
                                      <span class="position"><?php esc_html_e('Morbi sollicitudin','spicebox'); ?></span>
                                      <ul class="social-links">
                                          <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                          <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                          <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                          <li><a href="#"><i class="fa fa-behance"></i></a></li>
                                      </ul>
                                  </div>
                               </div>
                          </div>
                      <?php
                      }
                      ?>
                    </div>
                </div>
            </div>
        </section>
<?php } }
endif;

if ( function_exists( 'spiceb_cloudpress_team' ) ) {
    $section_priority = apply_filters( 'cloudpress_section_priority', 6, 'spiceb_cloudpress_team' );
    add_action( 'spiceb_cloudpress_sections', 'spiceb_cloudpress_team', absint( $section_priority ) );
}


// cloudpress Team content data
if ( ! function_exists( 'spiceb_cloudpress_team_default_customize_register' ) ) :
add_action( 'customize_register', 'spiceb_cloudpress_team_default_customize_register' );
function spiceb_cloudpress_team_default_customize_register( $wp_customize ){
				//cloudpress default team data.
				$cloudpress_team_content_control = $wp_customize->get_setting( 'cloudpress_team_content' );
				if ( ! empty( $cloudpress_team_content_control ) )
				{
            $cloudpress_team_content_control->default = json_encode( array(
      					array(
          					'image_url'  => SPICEB_PLUGIN_URL .'/inc/cloudpress/images/team/team01.jpg',
          					'title'           => esc_html__( 'Curabitur maximus','spicebox' ),
          					'subtitle'        => esc_html__( 'Duis lobortis', 'spicebox' ),
          					'open_new_tab' => 'no',
          					'id'              => 'customizer_repeater_56d7ea7f40c56',
          					'social_repeater' => json_encode(
        						array(
          							array(
          								'id'   => 'customizer-repeater-social-repeater-57fb908674e06',
          								'link' => 'facebook.com',
          								'icon' => 'fa-facebook',
          							),
          							array(
          								'id'   => 'customizer-repeater-social-repeater-57fb9148530fc',
          								'link' => 'twitter.com',
          								'icon' => 'fa-twitter',
          							),
          							array(
          								'id'   => 'customizer-repeater-social-repeater-57fb9150e1e89',
          								'link' => 'linkedin.com',
          								'icon' => 'fa-linkedin',
          							),
          							array(
          								'id'   => 'customizer-repeater-social-repeater-57fb9150e1e256',
          								'link' => 'behance.com',
          								'icon' => 'fa-behance',
          							),
        						)),
				    ),
            array(
                'image_url'  => SPICEB_PLUGIN_URL .'/inc/cloudpress/images/team/team02.jpg',
                'title'           => esc_html__( 'Nulla sit amet', 'spicebox' ),
                'subtitle'        => esc_html__( 'Quisque suscipit', 'spicebox' ),
                'open_new_tab' => 'no',
                'id'              => 'customizer_repeater_56d7ea7f40c56',
                'social_repeater' => json_encode(
                array(
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb908674e06',
                      'link' => 'facebook.com',
                      'icon' => 'fa-facebook',
                    ),
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb9148530fc',
                      'link' => 'twitter.com',
                      'icon' => 'fa-twitter',
                    ),
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb9150e1e89',
                      'link' => 'linkedin.com',
                      'icon' => 'fa-linkedin',
                    ),
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb9150e1e256',
                      'link' => 'behance.com',
                      'icon' => 'fa-behance',
                    ),
                ) ),
            ),
            array(
                'image_url'  => SPICEB_PLUGIN_URL .'/inc/cloudpress/images/team/team03.jpg',
                'title'           => esc_html__( 'Nam maximus', 'spicebox' ),
                'subtitle'        => esc_html__( 'Mauris convallis', 'spicebox' ),
                'open_new_tab' => 'no',
                'id'              => 'customizer_repeater_56d7ea7f40c56',
                'social_repeater' => json_encode(
                array(
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb908674e06',
                      'link' => 'facebook.com',
                      'icon' => 'fa-facebook',
                    ),
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb9148530fc',
                      'link' => 'twitter.com',
                      'icon' => 'fa-twitter',
                    ),
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb9150e1e89',
                      'link' => 'linkedin.com',
                      'icon' => 'fa-linkedin',
                    ),
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb9150e1e256',
                      'link' => 'behance.com',
                      'icon' => 'fa-behance',
                    ),
                ) ),
            ),
            array(
                'image_url'  => SPICEB_PLUGIN_URL .'/inc/cloudpress/images/team/team04.jpg',
                'title'           => esc_html__( 'Aliquam maximus', 'spicebox' ),
                'subtitle'        => esc_html__( 'Mauris feugiat', 'spicebox' ),
                'open_new_tab' => 'no',
                'id'              => 'customizer_repeater_56d7ea7f40c56',
                'social_repeater' => json_encode(
                array(
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb908674e06',
                      'link' => 'facebook.com',
                      'icon' => 'fa-facebook',
                    ),
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb9148530fc',
                      'link' => 'twitter.com',
                      'icon' => 'fa-twitter',
                    ),
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb9150e1e89',
                      'link' => 'linkedin.com',
                      'icon' => 'fa-linkedin',
                    ),
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb9150e1e256',
                      'link' => 'behance.com',
                      'icon' => 'fa-behance',
                    ),
                ) ),
            ),
            array(
                'image_url'  => SPICEB_PLUGIN_URL .'/inc/cloudpress/images/team/team05.jpg',
                'title'           => esc_html__( 'Aenean sit amet','spicebox' ),
                'subtitle'        => esc_html__( 'Morbi sollicitudin', 'spicebox' ),
                'open_new_tab' => 'no',
                'id'              => 'customizer_repeater_56d7ea7f40c56',
                'social_repeater' => json_encode(
                array(
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb908674e06',
                      'link' => 'facebook.com',
                      'icon' => 'fa-facebook',
                    ),
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb9148530fc',
                      'link' => 'twitter.com',
                      'icon' => 'fa-twitter',
                    ),
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb9150e1e89',
                      'link' => 'linkedin.com',
                      'icon' => 'fa-linkedin',
                    ),
                    array(
                      'id'   => 'customizer-repeater-social-repeater-57fb9150e1e256',
                      'link' => 'behance.com',
                      'icon' => 'fa-behance',
                    ),
                ) ),
            ),
	      ) );
		}
}
endif;
