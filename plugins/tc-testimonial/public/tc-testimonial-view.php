<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function tct_get_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }

    return $default;
}

function tcodes_testimonial_trigger(){
?>
<style media="screen">

/* Navigation */
.tc-testimonial-wrap  .owl-theme .owl-nav [class*='owl-'] {
 background-color: <?php echo tct_get_option('navigation-color', 'tct_styles', '#ECF0F1' ); ?>;
}
 .tc-testimonial-wrap  .owl-theme .owl-nav [class*='owl-']:hover {
  background-color: <?php echo tct_get_option('navigation-hover-color', 'tct_styles', '#EB6361' ); ?>;
 }
/* Dots */
.tc-testimonial-wrap .owl-theme .owl-dots .owl-dot span {
 background-color:<?php echo tct_get_option('dots-color', 'tct_styles', '#EB6361' ); ?>;
}
.tc-testimonial-wrap  .owl-theme .owl-dots .owl-dot.active span, .owl-theme .owl-dots .owl-dot:hover span {
 background-color:<?php echo tct_get_option('dots-hover-color', 'tct_styles', '#343434' ); ?>;
}

<?php
 $hide_midborder=tct_get_option('hide-midborder','tct_basics', 'no' );

if($hide_midborder=='no'){ ?>
.tc-client {
  border-bottom: 3px solid <?php echo tct_get_option('tcpt-mborder', 'tct_styles', '#343434' ); ?>;
}

<?php } ?>
.tc-content p:before,.tc-content p:after {
  color:<?php echo tct_get_option('tcpt-quote', 'tct_styles', '#343434' ); ?>;
}
.tc-content p{
  color:<?php echo tct_get_option('tcpt-text', 'tct_styles', '#fff' ); ?>;
}
.tc-testimonial-single {
    background-color:<?php echo tct_get_option('tcpt-box-bg', 'tct_styles', '#71BA51' ); ?>;
    border: 1px solid <?php echo tct_get_option('tcpt-box-bg', 'tct_styles', '#71BA51' ); ?>;
    margin: 0 10px;
    padding: 20px 20px;
    -moz-box-shadow: 0 0 5px <?php echo tct_get_option('tcpt-box-bg', 'tct_styles', '#71BA51' ); ?>;
   -webkit-box-shadow: 0 0 5px <?php echo tct_get_option('tcpt-box-bg', 'tct_styles', '#71BA51' ); ?>;
    box-shadow: 0 0 5px <?php echo tct_get_option('tcpt-box-bg', 'tct_styles', '#71BA51' ); ?>;
}

.tc-client-thumb img{
  border-radius: 50%;
  border: 2px solid <?php echo tct_get_option('author-image-border', 'tct_styles', '#fff' ); ?>;
}
.tc-author-details{
  color:<?php echo tct_get_option('author-name', 'tct_styles', '#fff' ); ?>
}
</style>

<script type="text/javascript">

jQuery(document).ready(function(){
    jQuery(".owl-carousel").owlCarousel({
      // control
          autoplay:<?php echo tct_get_option('auto-play','tct_basics', 'true' ); ?>,
          autoplayHoverPause:<?php  echo tct_get_option('stop-onhover','tct_basics', 'true' ); ?>,
          autoplayTimeout:<?php echo tct_get_option('auto_play_timeout','tct_basics', 4000 ); ?>,
          loop:<?php echo tct_get_option('loop','tct_basics', 'true' ); ?>,
          // Advances
          margin:<?php echo tct_get_option('margin-val','tct_advanced',5); ?>,
          nav:<?php echo tct_get_option('nav-val','tct_advanced', 'true' ); ?>,
          navText:["&lt;","&gt;"],
          autoHeight:<?php echo tct_get_option('autoheight','tct_advanced', 'false' ); ?>,
          autoWidth:<?php echo tct_get_option('autoheight','tct_advanced', 'false' ); ?>,
          stagePadding:<?php echo tct_get_option('stage-padding','tct_advanced', 'false' ); ?>,
          rtl:<?php echo tct_get_option('rtl-val','tct_advanced', 'false' ); ?>,
          dots:<?php echo tct_get_option('dots-val','tct_advanced', 'true' ); ?>,
          responsiveClass:true,
          responsive:{
              0:{
                  items:1,
              },
              600:{
                  items:<?php echo tct_get_option('items-tablet-val','tct_basics', '1' ); ?>,

              },
              1000:{
                  items:<?php  echo tct_get_option('medium-desktops','tct_basics', '1' ); ?>,

              }

          }

  });

});

</script>

<?php
}
add_action('wp_footer','tcodes_testimonial_trigger');

// Add Shortcode

function tcodes_testimonial_shortcode( $atts ) {

  // Attributes
 extract( shortcode_atts(
 	array(
 		'posts_num' => "-1",
 		'order' => 'DESC',
 		 'category'=>'',

 	), $atts )
 );
    $args = array(
        'orderby' => 'date',
         'order' => $order,
          'tctestimonial_category' =>$category,
           'showposts' => $posts_num,
          'post_type' => 'tctestimonial'
    );

      $tcodes_loop = new WP_Query($args);

      $output = '<div class="tc-testimonial-wrap">';
      $output .= '<div class="tc-testimonial tcnav-vmid owl-carousel">';
          if($tcodes_loop->have_posts()){
              while($tcodes_loop->have_posts()) {
                  $tcodes_loop->the_post();
                  // get terms
                    $tct_company_name=testimonial_author_s_info_get_meta( 'testimonial_author_s_info_company_name' );
                    $tct_designation=testimonial_author_s_info_get_meta( 'testimonial_author_s_info_designation' );
                    $tct_location=testimonial_author_s_info_get_meta( 'testimonial_author_s_info_location' );
                  // End terms
                  $tcodes_content = wpautop(get_the_content());
                  $tcodes_title = get_the_title();
                  $tcodes_thumbnail = get_the_post_thumbnail(get_the_ID(), array( 100, 100));
                  $tccompany_url=get_post_meta( get_the_ID(), 'tcodes_author_company', true );
                  $output .= '<div class="tc-testimonial-single">';
                      $output .= '<div class="tc-client">';
                              if(!empty($tcodes_thumbnail)) {
                                  $output .= '<div class="tc-client-thumb">';
                                      $output .='<a href="'.$tccompany_url.'">'. $tcodes_thumbnail.'</a>';
                                  $output .= '</div>';
                              }
                                  $output .= '<div class="tc-author-details">';
                                          $output .= '<div class="author-infobox">';
                                            $output .= '<p class="tc-author-name">'.$tcodes_title.'<span class="role">'.$tct_designation.'<span></p>';

                                          $output .= '</div>';
                                          $output .= '<div class="author-infobox">';
                                          $output .= '<p class="tc-author-name">'. $tct_company_name.'<span class="role">'.$tct_location.'<span></p>';
                                            //$output .= '<h3 class="tc-author-meta">'. $tct_company_name.'</h3>';
                                            //$output .= '<h3 class="tc-author-meta">'.$tct_location.'</h3>';
                                          $output .= '</div>';
                                    //$output .= '<h3 class="tc-author-company">'.get_post_meta( get_the_ID(), 'tcodes_author_company', true ).'</h3>';
                                  $output .= '</div>';
                      $output .= '</div>'; // tc-client

                      $output .= '<div class="tc-content">'.$tcodes_content.'</div>';
                  $output .= '</div>'; //tc-testimonial
              } //end while tcodes_loop
          } else{
              echo 'No Testimonial was  Found.';
          }
          wp_reset_postdata();
          wp_reset_query();
      $output .= '</div>';
      $output .= '</div>'; // wrap

      return $output;
}
add_shortcode('tc-testimonial', 'tcodes_testimonial_shortcode' );

 ?>
