<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Widget Page
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_widget($option,$option_key,$option_checkbox){

  $widget_list = array(

    esc_html__('Pageview','yahman-add-ons') => array(
      'pv' => array(
        'title' => esc_html__('Pageview widget','yahman-add-ons'),
        'hint' => '<label class="ya_link_color" for="ya_pv" onclick="to_top();">'
        .esc_html__('Need count of pageview.','yahman-add-ons').
        '</label>',
      ),
      'pp' => array(
        'title' => esc_html__('Popular Post widget','yahman-add-ons'),
        'hint' => '<label class="ya_link_color" for="ya_pv" onclick="to_top();">'
        .esc_html__('Need count of pageview.','yahman-add-ons').
        '</label>',
      ),
    ),

    esc_html__('Google AdSense','yahman-add-ons') => array(
      'google_ad_responsive' => array(
        'title' => sprintf(esc_html__('Google AdSense %s widget', 'yahman-add-ons'),esc_html_x('Display', 'google_ad', 'yahman-add-ons')),
        'hint' => '',
      ),
      'google_ad_infeed' => array(
        'title' => sprintf(esc_html__('Google AdSense %s widget', 'yahman-add-ons'),esc_html_x('In-feed', 'google_ad', 'yahman-add-ons')),
        'hint' => '',
      ),
      'google_ad_inarticle' => array(
        'title' => sprintf(esc_html__('Google AdSense %s widget', 'yahman-add-ons'),esc_html_x('In-article', 'google_ad', 'yahman-add-ons')),
        'hint' => '',
      ),
      'google_ad_autorelaxed' => array(
        'title' => sprintf(esc_html__('Google AdSense %s widget', 'yahman-add-ons'),esc_html_x('Matched content', 'google_ad', 'yahman-add-ons')),
        'hint' => '',
      ),
      'google_ad_link' => array(
        'title' => sprintf(esc_html__('Google AdSense %s widget', 'yahman-add-ons'),esc_html_x('Link', 'google_ad', 'yahman-add-ons')),
        'hint' => '',
      ),
    ),

    esc_html__('Social','yahman-add-ons') => array(
      'sns_link' => array(
        'title' => esc_html__('Social Links widget','yahman-add-ons'),
        'hint' => '<label class="ya_link_color" for="ya_sns" onclick="to_top();">'
        .esc_html__('Based on social account settings.','yahman-add-ons').
        '</label>',
      ),
      'twitter' => array(
        'title' => esc_html__('Twitter timeline widget','yahman-add-ons'),
        'hint' => '',
      ),
      'facebook' => array(
        'title' => esc_html__('Facebook timeline widget','yahman-add-ons'),
        'hint' => '',
      ),
    ),

    esc_html__('Profile','yahman-add-ons') => array(
      'profile' => array(
        'title' => esc_html__('Profile widget','yahman-add-ons'),
        'hint' => '',
      ),
      'another' => array(
        'title' => esc_html__('Another Profile widget','yahman-add-ons'),
        'hint' => '',
      ),
    ),

    esc_html__('Table of contents','yahman-add-ons') => array(
      'toc' => array(
        'title' => esc_html__('Table of contents widget','yahman-add-ons'),
        'hint' => '<label class="ya_link_color" for="ya_toc" onclick="to_top();">'
        .esc_html__('Based on TOC settings.','yahman-add-ons').
        '</label>',
      ),
    ),

    esc_html__('Post list','yahman-add-ons') => array(
      'recent' => array(
        'title' => esc_html__('Recent Posts with thumbnail widget','yahman-add-ons'),
        'hint' => '',
      ),
      'update' => array(
        'title' => esc_html__('Update Posts with thumbnail widget','yahman-add-ons'),
        'hint' => '',
      ),
      'recommend' => array(
        'title' => esc_html__('Recommended Posts with thumbnail widget','yahman-add-ons'),
        'hint' => '',
      ),
      'art_2col' => array(
        'title' => esc_html__('Articles into two columns widget', 'yahman-add-ons' ),
        'hint' => '',
      ),
      '2lists_2col' => array(
        'title' => esc_html__('Two lists of articles widget', 'yahman-add-ons' ),
        'hint' => '',
      ),
      'alu' => array(
        'title' => esc_html__('Articles line up widget','yahman-add-ons'),
        'hint' => '',
      ),
      'carousel_slider' => array(
        'title' => esc_html__('Slider with Carousel Slider widget', 'yahman-add-ons' ),
        'hint' => '',
      ),
    ),

    esc_html__('Other','yahman-add-ons') => array(
      'cse' => array(
        'title' => esc_html__('Google Custom Search widget','yahman-add-ons'),
        'hint' => '',
      ),
      'dda' => array(
        'title' => esc_html__('Drop Down Archives widget without JavaScript','yahman-add-ons'),
        'hint' => '',
      ),
      'ddc' => array(
        'title' => esc_html__('Drop Down Categories widget without JavaScript','yahman-add-ons'),
        'hint' => '',
      ),
    ),






  );

$widget_area['post_type'] = array(
  'post' => esc_html_x('the post', 'widget' ,'yahman-add-ons' ),
  'page' => esc_html_x('the page', 'widget' ,'yahman-add-ons' ),
);
$widget_area['position_num'] = array(
  esc_html_x('the first', 'widget' ,'yahman-add-ons' ),
  esc_html_x('the second', 'widget' ,'yahman-add-ons' ),
  esc_html_x('the third', 'widget' ,'yahman-add-ons' ),
);

?>

<div id="ya_widget_content" class="tab_content ya_box_design">
  <h2><?php esc_html_e('YAHMAN Add-ons Widget','yahman-add-ons'); ?></h2>

  <?php
  foreach ($widget_list as $heading => $child) {
    echo '<h3>'.$heading.'</h3>';
    foreach ($child as $key => $value) {

      $widget[$key] = isset($option['widget'][$key]) ? true: false;
      ?>

      <div class="ya_setting_content">
        <div class="ya_tooltip_wrap">
          <label for="widget_<?php echo esc_attr($key); ?>">
            <?php echo esc_html($value['title']); ?>
          </label>
          <div class="ya_tooltip">
            <?php esc_html_e('Add widget.','yahman-add-ons'); ?>
            <?php if($value['hint'] !== '') echo '<br>'.$value['hint']; ?>
          </div>
        </div>
        <div class="ya_checkbox">
         <input name="yahman_addons[widget][<?php echo esc_attr($key); ?>]" type="checkbox" id="widget_<?php echo esc_attr($key); ?>"<?php checked(true, $widget[$key]); ?> class="ya_checkbox" />
         <label for="widget_<?php echo esc_attr($key); ?>"></label>
       </div>
     </div>


     <?php
   }
 }

 ?>


 <h3><?php esc_html_e('Widget area','yahman-add-ons'); ?></h3>
 <?php
 foreach ($widget_area['post_type'] as $post_type_key => $post_type_val) {
  $i = 1;
  foreach ($widget_area['position_num'] as $position_num_key => $position_num_val) {
    $widget_area['judge'] = isset($option['widget_area'][$post_type_key]['before_h2'][$i]) ? true: false;
    ?>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="widget_area_<?php echo esc_attr($post_type_key); ?>_before_h2_<?php echo esc_attr($i); ?>">
          <?php echo sprintf( esc_html__('Before %1$s H2 of %2$s', 'yahman-add-ons' ), $position_num_val, $post_type_val ); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Add widget area.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
       <input name="yahman_addons[widget_area][<?php echo esc_attr($post_type_key); ?>][before_h2][<?php echo esc_attr($i); ?>]" type="checkbox" id="widget_area_<?php echo esc_attr($post_type_key); ?>_before_h2_<?php echo esc_attr($i); ?>"<?php checked(true, $widget_area['judge'] ); ?> class="ya_checkbox" />
       <label for="widget_area_<?php echo esc_attr($post_type_key); ?>_before_h2_<?php echo esc_attr($i); ?>"></label>
     </div>
   </div>

   <?php
   ++$i;
 }
}


?>

</div>




<?php
}
