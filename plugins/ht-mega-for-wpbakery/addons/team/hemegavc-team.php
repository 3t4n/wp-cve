<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Team{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_team', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }


    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'animate', HTMEGAVC_LIBS_URI. '/animate-css/animate.css' );
      wp_enqueue_style( 'animate' );

      wp_register_script( 'htmegavc-team', plugins_url('/js/htmegavc-team.js', __FILE__ ));
      wp_enqueue_script( 'htmegavc-team' );

      wp_register_style( 'htmegavc-team', plugins_url('/css/team.css', __FILE__) );
      wp_enqueue_style( 'htmegavc-team' );
    }
 

    
    /*
    Shortcode logic how it should be rendered
    */
    public function render_shortcode( $atts, $content = null ) {

    	extract(shortcode_atts(array(
            'htmega_team_style' => '1', 
            'htmega_team_image_hover_style' => 'none', 
            'htmega_member_image' => '', 
            'htmega_member_imagesize' => 'large', 
            'htmega_member_name' => 'Sams Roy', 
            'htmega_member_designation' => 'Managing director', 
            'htmega_member_bioinfo' => 'I am web developer.', 
            'htmega_team_member_social_link_list' => '', 
            'team_member_content_bg' => '', 
            'team_member_hover_content_bg' => '', 
            'wrapper_css' => '', 
    	),$atts));

      $wrapper_css = ' '. apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_team', $atts );

      $unique_class =  uniqid('htmegavc_team_');
      $htmega_member_name_style = '';
      if(isset($atts['htmega_member_name_options']) && $atts['htmega_member_name_options']){
        foreach(explode('|', $atts['htmega_member_name_options']) as $item){
          if($item == 'font_family:Use%20From%20Theme'){
            continue;
          }
          $htmega_member_name_style .= $item . ';';
        }
        $htmega_member_name_style = preg_replace(array('/_/', '/%23/', '/%20/'), array('-', '#', ' '), $htmega_member_name_style);
      }



      // designation style
      $htmega_member_designation_style = '';
      if(isset($atts['htmega_member_designation_options']) && $atts['htmega_member_designation_options']){
        foreach(explode('|', $atts['htmega_member_designation_options']) as $item){
          if($item == 'font_family:Use%20From%20Theme'){
            continue;
          }
          $htmega_member_designation_style .= $item . ';';
        }
        $htmega_member_designation_style = preg_replace(array('/_/', '/%23/', '/%20/'), array('-', '#', ' '), $htmega_member_designation_style);
      }

      $htmega_member_bioinfo_style = '';
      if(isset($atts['htmega_member_bioinfo_options']) && $atts['htmega_member_bioinfo_options']){
        foreach(explode('|', $atts['htmega_member_bioinfo_options']) as $item){
          if($item == 'font_family:Use%20From%20Theme'){
            continue;
          }
          $htmega_member_bioinfo_style .= $item . ';';
        }
        $htmega_member_bioinfo_style = preg_replace(array('/_/', '/%23/', '/%20/'), array('-', '#', ' '), $htmega_member_bioinfo_style);
      }


    	ob_start();
?>

<div class="htmegavc-team htmegavc-team-style-<?php echo esc_attr($htmega_team_style); ?> <?php echo esc_attr($unique_class.$wrapper_css) ?>" >

    <?php if( $htmega_team_style == 2 ):?>
        <div class="htmegavc-thumb">
          <?php 
            if($htmega_member_image){
              echo wp_get_attachment_image($htmega_member_image , $htmega_member_imagesize);
            } else {
              echo '<img src="'. plugins_url('/images/team-default.jpg', __FILE__) .'" />';
            }
          ?>
            <div class="htmegavc-team-hover-action">
                <div class="htmegavc-hover-action" style="background-color:<?php echo esc_attr($team_member_hover_content_bg); ?>">
                    <?php
                        if( !empty($htmega_member_name) ){
                            echo '<h4 class="htmegavc-team-name" style="'.$htmega_member_name_style.'">'.esc_html( $htmega_member_name ).'</h4>';
                        }
                    ?>
                    <ul class="htmegavc-social-network">
                        <?php foreach ( vc_param_group_parse_atts($atts['htmega_team_member_social_link_list'] ) as $socialprofile ) :
                          $color = isset($socialprofile['htmega_icon_color']) ? 'color:' .$socialprofile['htmega_icon_color'] .';' : '';
                          $font_size = isset($socialprofile['htmega_icon_font_size']) ? 'font-size:' .$socialprofile['htmega_icon_font_size']. ';' : '';
                        ?>
                            <li ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><i class="fa <?php echo esc_attr( $socialprofile['htmega_social_icon'] ); ?>" style="<?php echo esc_attr($color.$font_size) ?>"></i></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

    <?php elseif( $htmega_team_style == 3 ):?>
        <div class="htmegavc-thumb">
            <?php 
              if($htmega_member_image){
                echo wp_get_attachment_image($htmega_member_image , $htmega_member_imagesize);
              } else {
                echo '<img src="'. plugins_url('/images/team-default.jpg', __FILE__) .'" />';
              }
            ?>
            <div class="htmegavc-team-hover-action">

                <div class="htmegavc-team-click-action" style="background-color:<?php echo esc_attr($team_member_content_bg); ?>">
                    <div class="plus_click"></div>
                    <?php
                        if( !empty($htmega_member_name) ){
                            echo '<h4 class="htmegavc-team-name" style="'.$htmega_member_name_style.'">'.esc_attr__( $htmega_member_name,'htmegavc' ).'</h4>';
                        }
                        if( !empty($htmega_member_designation) ){
                            echo '<span class="htmegavc-team-designation" style="'. $htmega_member_designation_style .'">'.esc_attr__( $htmega_member_designation,'htmegavc' ).'</span>';
                        }
                    ?>
                    <ul class="htmegavc-social-network">
                        <?php
                        $social_style = '';
                        foreach ( vc_param_group_parse_atts($atts['htmega_team_member_social_link_list'] ) as $socialprofile ):
                          $color = isset($socialprofile['htmega_icon_color']) ? 'color:' .$socialprofile['htmega_icon_color'] .';' : '';
                          $font_size = isset($socialprofile['htmega_icon_font_size']) ? 'font-size:' .$socialprofile['htmega_icon_font_size']. ';' : '';
                        ?>
                            <li ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><i class="fa <?php echo esc_attr( $socialprofile['htmega_social_icon'] ); ?>" style="<?php echo esc_attr($color.$font_size) ?>"></i></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>
        </div>

    <?php 
        elseif( $htmega_team_style == 4 ):
    ?>
        <div class="htmegavc-thumb htmegavc-team-image-hover-<?php echo esc_attr($htmega_team_image_hover_style); ?>" data-hover-bg-color="<?php echo esc_attr($team_member_hover_content_bg); ?>">
            <?php 
              if($htmega_member_image){
                echo wp_get_attachment_image($htmega_member_image , $htmega_member_imagesize);
              } else {
                echo '<img src="'. plugins_url('/images/team-default.jpg', __FILE__) .'" />';
              }
            ?>
            <div class="htmegavc-team-hover-action">
                <div class="htmegavc-hover-action">
                    <?php
                        if( !empty($htmega_member_name) ){
                            echo '<h4 class="htmegavc-team-name" style="'.$htmega_member_name_style.'">'.esc_attr__( $htmega_member_name,'htmegavc' ).'</h4>';
                        } 
                        if( vc_param_group_parse_atts($atts['htmega_team_member_social_link_list'] ) ): 
                    ?>
                        <ul class="htmegavc-social-network">
                            <?php
                            $social_style = '';
                            foreach ( vc_param_group_parse_atts($atts['htmega_team_member_social_link_list'] ) as $socialprofile ):
                              $color = isset($socialprofile['htmega_icon_color']) ? 'color:' .$socialprofile['htmega_icon_color'] .';' : '';
                              $font_size = isset($socialprofile['htmega_icon_font_size']) ? 'font-size:' .$socialprofile['htmega_icon_font_size']. ';' : '';
                            ?>
                                <li ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><i class="fa <?php echo esc_attr( $socialprofile['htmega_social_icon'] ); ?>" style="<?php echo esc_attr($color.$font_size) ?>"></i></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <style type="text/css">
          .htmegavc-team-style-4.<?php echo esc_attr($unique_class) ?> .htmegavc-team-hover-action::before{
            background: <?php echo $team_member_hover_content_bg; ?>
          }
        </style>

    <?php elseif( $htmega_team_style == 5 ):?>
        <div class="htmegavc-thumb">
            <?php 
              if($htmega_member_image){
                echo wp_get_attachment_image($htmega_member_image , $htmega_member_imagesize);
              } else {
                echo '<img src="'. plugins_url('/images/team-default.jpg', __FILE__) .'" />';
              }
            ?>
            <div class="htmegavc-team-hover-action" style="background-color:<?php echo esc_attr($team_member_hover_content_bg); ?>">
                <div class="htmegavc-hover-action">
                    <?php
                        if( !empty($htmega_member_name) ){
                            echo '<h4 class="htmegavc-team-name" style="'.$htmega_member_name_style.'">'.esc_attr__( $htmega_member_name,'htmegavc' ).'</h4>';
                        }
                        if( !empty($htmega_member_designation) ){
                            echo '<p class="htmegavc-team-designation" style="'. $htmega_member_designation_style .'">'.esc_attr__( $htmega_member_designation,'htmegavc' ).'</p>';
                        }
                        if( !empty($htmega_member_bioinfo) ){ echo '<p class="htmegavc-team-bio-info" style="'. $htmega_member_bioinfo_style .'">'.esc_attr__( $htmega_member_bioinfo,'htmegavc' ).'</p>'; }
                    ?>
                    <?php if( vc_param_group_parse_atts($atts['htmega_team_member_social_link_list'] ) ): ?>
                        <ul class="htmegavc-social-network">
                            <?php
                            $social_style = '';
                            foreach ( vc_param_group_parse_atts($atts['htmega_team_member_social_link_list'] ) as $socialprofile ):
                              $color = isset($socialprofile['htmega_icon_color']) ? 'color:' .$socialprofile['htmega_icon_color'] .';' : '';
                              $font_size = isset($socialprofile['htmega_icon_font_size']) ? 'font-size:' .$socialprofile['htmega_icon_font_size']. ';' : '';
                            ?>
                                <li ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><i class="fa <?php echo esc_attr( $socialprofile['htmega_social_icon'] ); ?>" style="<?php echo esc_attr($color.$font_size) ?>"></i></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;?>
                </div>
            </div>
        </div>

    <?php elseif( $htmega_team_style == 6 ):?>
        <div class="htmegavc-thumb">
            <?php 
              if($htmega_member_image){
                echo wp_get_attachment_image($htmega_member_image , $htmega_member_imagesize);
              } else {
                echo '<img src="'. plugins_url('/images/team-default.jpg', __FILE__) .'" />';
              }
            ?>
        </div>
        <div class="htmegavc-team-info">
            <div class="htmegavc-team-content">
                <?php
                    if( !empty($htmega_member_name) ){
                        echo '<h4 class="htmegavc-team-name" style="'.$htmega_member_name_style.'">'.esc_attr__( $htmega_member_name,'htmegavc' ).'</h4>';
                    }
                    if( !empty($htmega_member_designation) ){
                        echo '<p class="htmegavc-team-designation" style="'. $htmega_member_designation_style .'">'.esc_attr__( $htmega_member_designation,'htmegavc' ).'</p>';
                    }
                    if( !empty($htmega_member_bioinfo) ){ echo '<p class="htmegavc-team-bio-info" style="'. $htmega_member_bioinfo_style .'">'.esc_attr__( $htmega_member_bioinfo,'htmegavc' ).'</p>'; }
                ?>
            </div>
            <?php if( vc_param_group_parse_atts($atts['htmega_team_member_social_link_list'] ) ): ?>
                <ul class="htmegavc-social-network">
                            <?php
                            $social_style = '';
                            foreach ( vc_param_group_parse_atts($atts['htmega_team_member_social_link_list'] ) as $socialprofile ):
                              $color = isset($socialprofile['htmega_icon_color']) ? 'color:' .$socialprofile['htmega_icon_color'] .';' : '';
                              $font_size = isset($socialprofile['htmega_icon_font_size']) ? 'font-size:' .$socialprofile['htmega_icon_font_size']. ';' : '';
                            ?>
                                <li ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><i class="fa <?php echo esc_attr( $socialprofile['htmega_social_icon'] ); ?>" style="<?php echo esc_attr($color.$font_size) ?>"></i></a></li>
                            <?php endforeach; ?>
                        </ul>
            <?php endif;?>
        </div>

    <?php elseif( $htmega_team_style == 7 ):?>

        <div class="htmegavc-thumb">
            <?php 
              if($htmega_member_image){
                echo wp_get_attachment_image($htmega_member_image , $htmega_member_imagesize);
              } else {
                echo '<img src="'. plugins_url('/images/team-default.jpg', __FILE__) .'" />';
              }
            ?>
            <div class="htmegavc-team-hover-action">
                <div class="htmegavc-hover-action">
                    <?php if( vc_param_group_parse_atts($atts['htmega_team_member_social_link_list'] ) ): ?>
                        <ul class="htmegavc-social-network">
                            <?php
                            $social_style = '';
                            foreach ( vc_param_group_parse_atts($atts['htmega_team_member_social_link_list'] ) as $socialprofile ):
                              $color = isset($socialprofile['htmega_icon_color']) ? 'color:' .$socialprofile['htmega_icon_color'] .';' : '';
                              $font_size = isset($socialprofile['htmega_icon_font_size']) ? 'font-size:' .$socialprofile['htmega_icon_font_size']. ';' : '';
                            ?>
                                <li ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><i class="fa <?php echo esc_attr( $socialprofile['htmega_social_icon'] ); ?>" style="<?php echo esc_attr($color.$font_size) ?>"></i></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <div class="htmegavc-team-content">
            <?php
                if( !empty($htmega_member_name) ){
                    echo '<h4 class="htmegavc-team-name" style="'.$htmega_member_name_style.'">'.esc_attr__( $htmega_member_name,'htmegavc' ).'</h4>';
                }
                if( !empty($htmega_member_designation) ){
                    echo '<p class="htmegavc-team-designation" style="'. $htmega_member_designation_style .'">'.esc_attr__( $htmega_member_designation,'htmegavc' ).'</p>';
                }
            ?>
        </div>

    <?php else:?>
        <div class="htmegavc-thumb">
            <?php 
              if($htmega_member_image){
                echo wp_get_attachment_image($htmega_member_image , $htmega_member_imagesize);
              } else {
                echo '<img src="'. plugins_url('/images/team-default.jpg', __FILE__) .'" />';
              }
            ?>
            <div class="htmegavc-team-hover-action" style="background-color:<?php echo esc_attr($team_member_hover_content_bg); ?>">
                <div class="htmegavc-team-hover">
                    <?php if( $htmega_team_member_social_link_list ): ?>
                        <ul class="htmegavc-social-network">
                            <?php
                            $social_style = '';
                            foreach ( vc_param_group_parse_atts($atts['htmega_team_member_social_link_list'] ) as $socialprofile ):
                              $color = isset($socialprofile['htmega_icon_color']) ? 'color:' .$socialprofile['htmega_icon_color'] .';' : '';
                              $font_size = isset($socialprofile['htmega_icon_font_size']) ? 'font-size:' .$socialprofile['htmega_icon_font_size']. ';' : '';
                            ?>
                                <li ><a href="<?php echo esc_url( $socialprofile['htmega_social_link'] ); ?>"><i class="fa <?php echo esc_attr( $socialprofile['htmega_social_icon'] ); ?>" style="<?php echo esc_attr($color.$font_size) ?>"></i></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;?>
                    <?php if( !empty($htmega_member_bioinfo) ){ echo '<p class="htmegavc-team-bio-info" style="'. $htmega_member_bioinfo_style .'">'.esc_attr__( $htmega_member_bioinfo,'htmegavc' ).'</p>'; }?>
                </div>
            </div>
        </div>
        <div class="htmegavc-team-content">
            <?php
                if( !empty($htmega_member_name) ){
                    echo '<h4 class="htmegavc-team-name" style="'.$htmega_member_name_style.'">'.esc_attr__( $htmega_member_name,'htmegavc' ).'</h4>';
                }
                if( !empty($htmega_member_designation) ){
                    echo '<p class="htmegavc-team-designation" style="'. $htmega_member_designation_style .'">'.esc_attr__( $htmega_member_designation,'htmegavc' ).'</p>';
                }
            ?>
        </div>
    <?php endif;?>

</div>

<?php
    	return ob_get_clean();
    }



    public function integrateWithVC() {
    
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("HT Team Member", 'htmegavc'),
            "description" => __("Add progress Bar to your page", 'htmegavc'),
            "base" => "htmegavc_team",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegvc_team_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(

                // tab 1 fields
                array(
                  "param_name" => "htmega_team_style",
                  "heading" => __("Style", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => '1',
                  'value' => [
                      __( 'Style One', 'htmegavc' )  =>  '1',
                      __( 'Style Two', 'htmegavc' )  =>  '2',
                      __( 'Style Three', 'htmegavc' )  =>  '3',
                      __( 'Style Four', 'htmegavc' )  =>  '4',
                      __( 'Style Five', 'htmegavc' )  =>  '5',
                      __( 'Style Six', 'htmegavc' )  =>  '6',
                      __( 'Style Seven', 'htmegavc' )  =>  '7',
                  ],
                ),
                array(
                  "param_name" => "htmega_team_image_hover_style",
                  'heading' => __( 'Image Hover Animate', 'htmegavc' ),
                  'type' => 'dropdown',
                  'default_set' => 'top',
                  'value' => [
                      __( 'None', 'htmegavc' )  => 'none',
                      __( 'Left', 'htmegavc' )  => 'left',
                      __( 'Right', 'htmegavc' )  => 'right',
                      __( 'Top', 'htmegavc' )  => 'top',
                      __( 'Bottom', 'htmegavc' )  => 'bottom',
                  ],
                  'dependency' =>[
                      'element' => 'htmega_team_style',
                      'value' => array( '4' ),
                  ],
                ),
                array(
                  "param_name" => "htmega_member_image",
                  'heading' => __( 'Member image', 'htmegavc' ),
                  'type' => 'attach_image',
                ),
                array(
                  "param_name" => "htmega_member_imagesize",
                  'heading' => __( 'Image Size', 'htmegavc' ),
                  'type' => 'textfield',
                  'default_set' => 'large',
                  'value' => 'large',
                  'description' => __('Put any image size here. Eg: full/large/medium/thumbnail etc', 'htmegavc')
                ),
                array(
                  "param_name" => "htmega_member_name",
                  'heading' => __( 'Name', 'htmegavc' ),
                  'type' => 'textfield',
                  'value' => 'Sams Roy',
                  'edit_field_class' => 'vc_col-sm-9',
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use custom Style?', 'htmegavc' ),
                  'param_name' => 'htmega_member_name_use_custom_style',
                  'description' => __( 'Enable custom font option.', 'htmegavc' ),
                  'edit_field_class' => 'vc_col-sm-3',
                ),
                array(
                  "param_name" => "htmega_member_designation",
                  'heading' => __( 'Designation', 'htmegavc' ),
                  'type' => 'textfield',
                  'value' =>  __( 'Managing director', 'htmegavc' ),
                  'edit_field_class' => 'vc_col-sm-9',
                  'dependency' =>[
                      'element' => 'htmega_team_style',
                      'value' => array( '1','3','5','6','7' ),
                  ],
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use custom Style?', 'htmegavc' ),
                  'param_name' => 'htmega_member_designation_use_custom_style',
                  'description' => __( 'Enable custom font option.', 'htmegavc' ),
                  'edit_field_class' => 'vc_col-sm-3',
                  'dependency' =>[
                      'element' => 'htmega_team_style',
                      'value' => array( '1','3','5','6','7' ),
                  ],
                ),
                array(
                  "param_name" => "htmega_member_bioinfo",
                  'heading' => __( 'Bio Info', 'htmegavc' ),
                  'type' => 'textarea',
                  'value' => __( 'I am web developer.', 'htmegavc' ),
                  'edit_field_class' => 'vc_col-sm-9',
                  'dependency' =>[
                      'element' => 'htmega_team_style',
                      'value' => array( '1', '5','6', ),
                  ],
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use custom Style?', 'htmegavc' ),
                  'param_name' => 'htmega_member_bioinfo_use_custom_style',
                  'description' => __( 'Enable custom font option.', 'htmegavc' ),
                  'edit_field_class' => 'vc_col-sm-3',
                  'dependency' =>[
                      'element' => 'htmega_team_style',
                      'value' => array( '1','5','6','7' ),
                  ],
                ),
                // end tab 1 fields

                // social media fields - tab 2
                array(
                  'type' => 'param_group',
                  'heading' => __( 'Add Social Links', 'htmegavc' ),
                  'param_name' => 'htmega_team_member_social_link_list',
                  'group'  => __( 'Social Links', 'htmegavc' ),
                  'value' => urlencode( json_encode (array(
                      array(
                          'htmega_social_title'         => __('Facebook','htmegavc'),
                          'htmega_social_icon'         => 'fa-facebook',
                          'htmega_social_link'         => 'https://www.facebook.com/hastech.company/',
                      ),
                      array(
                          'htmega_social_title'         => __('Twitter','htmegavc'),
                          'htmega_social_icon'         => 'fa-twitter',
                          'htmega_social_link'         => 'https://www.facebook.com/hastech.company/',
                      ),
                      array(
                          'htmega_social_title'         => __('Google Plus','htmegavc'),
                          'htmega_social_icon'         => 'fa-google-plus',
                          'htmega_social_link'         => 'https://www.facebook.com/hastech.company/',
                      ),
                   ))),
                  'params' => array(
	                    array(
	                      "param_name" => "htmega_social_title",
	                      "heading" => __( "Title", "htmegavc" ),
	                      "type" => "textfield",
	                      "value" => "Facebook",
	                    ),
	                    array(
	                      'param_name' => 'htmega_social_link',
	                      'heading' => __( 'Link', 'htmegavc' ),
	                      'type' => 'textfield',
	                      'value' => 'https://www.facebook.com/hastech.company/',
	                    ),
	                    array(
	                      'param_name' => 'htmega_social_icon',
	                      'type' => 'textfield',
	                      'heading' => __( 'Icon Class', 'htmegavc' ),
	                      'value' => 'fa-facebook',
	                      'description' => __( 'Write icon class here. Example: fa-facebook . You can you any icon name from <a href="https://fontawesome.com/v4.7.0/cheatsheet/" target="_blank"> here </a>', 'htmegavc' ),
	                    ),
	              	),

                ),
                 // Styling tab - tab 3
                array(
                  "param_name"  => "team_member_content_bg",
                  "heading"     => __( "Content background color", "htmegavc" ),
                  "type"        => "colorpicker",
                  'dependency' => [
                      'element' => 'htmega_team_style',
                      'value' => array( '3' ),
                  ],
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  "param_name"  => "team_member_hover_content_bg",
                  "heading"     => __( "Hover Content background color", "htmegavc" ),
                  "type"        => "colorpicker",
                  'dependency' => [
                      'element' => 'htmega_team_style',
                      'value' => array( '1','2','4', '5' ),
                  ],
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),

                // misc tab
                array(
                  'type' => 'font_container',
                  'param_name' => 'htmega_member_name_options',
                  'value' => 'tag:h2|text_align:left',
                  'dependency' => [
                      'element' => 'htmega_member_name_use_custom_style',
                      'value' => array( 'true' ),
                  ],
                  'group'  => __( 'Name Options', 'htmegavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family',
                      'font_size',
                      'line_height',
                      'color',
                      'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                      'color_description' => __( 'Select heading color.', 'htmegavc' ),
                    ),
                  ),
                ),
                array(
                  'type' => 'font_container',
                  'param_name' => 'htmega_member_designation_options',
                  'dependency' => [
                      'element' => 'htmega_member_designation_use_custom_style',
                      'value' => array( 'true' ),
                  ],
                  'group'  => __( 'Designation Options', 'htmegavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family',
                      'font_size',
                      'line_height',
                      'color',
                      'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                      'color_description' => __( 'Select heading color.', 'htmegavc' ),
                    ),
                  ),
                ),
                array(
                  'type' => 'font_container',
                  'param_name' => 'htmega_member_bioinfo_options',
                  'dependency' => [
                      'element' => 'htmega_member_bioinfo_use_custom_style',
                      'value' => array( 'true' ),
                  ],
                  'group'  => __( 'Bio Info Options', 'htmegavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family',
                      'font_size',
                      'line_height',
                      'color',
                      'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                      'color_description' => __( 'Select heading color.', 'htmegavc' ),
                    ),
                  ),
                ),
                array(
                  "type" => "css_editor",
                  "heading" => __( "Wrapper Styling", "htmegavc" ),
                  "param_name" => "wrapper_css",
                  "group" => __( "Styling", "htmegavc" ),
              ),
            )
        ) );
    }

}

// Finally initialize code
new Htmegavc_Team();