<?php
/**
 * Handle  shortcode.
 *
 * @param  array $attr Array of shortcode attributes.
 * @return string $output HTML
 */
function tc_pricing_table_shortcode( $attr = array() ) {

  $tableid=$attr['tableid'];
  $post = get_post();

// Get plans to this page.
$plans = get_post_meta($tableid , '_tc_tablemeta', true );

$output ='<div class="tcpt-wrap">';

// Return empty string, if we don't have members.
if ( empty( $plans ) ) {
return $output;
}
foreach ( $plans as $plan ) {

  $output .= '<div style="color:' . $plan['plan_color'] . '; background-color:'. $plan['plan_bg_color'] . ';"  class="tcpt_single_column"> <ul class="tcpt-flist">';

  if(!empty($plan['plan_title'])){
    $output .= '<li style="color:' . $plan['plan_h_color'] . '; background-color:'. $plan['plan_hbg_color'] . ';" class="plan">'. esc_attr( $plan['plan_title'] ).'</li>';
      }
    if(!empty($plan['plan_currency'])){
      $output .= '<li class="price"> <span class="currency-icon">'. esc_attr( $plan['plan_currency']).'</span>';
    }
  if(!empty($plan['package_price'])){
  $output .= esc_attr( $plan['package_price']);
  }
    if(!empty($plan['package_price'])){
      $output .='<span class="month">/'.esc_attr( $plan['pricing_per'] ).'</span> </li>';
    }


    if(!empty($plan['tcpt_features'])){
            $features = explode(',',$plan['tcpt_features']);
            $features = array_map('trim', $features);

            foreach($features as $feature) {
              if(!empty($feature)){
               $output .='<li>'.$feature .'</li>';
              }

           }

    }

if(!empty($plan['action_link'])){
     $output .= '<li class="sign-up"> <a style="color:' . $plan['plan_button_color'] . '; background-color:'. $plan['plan_button_bg_color'] . ';" class="btn btn-action" href="'.esc_attr( $plan['action_link'] ).'">';
   }
if(!empty($plan['action_button'])){
   $output .= esc_attr( $plan['action_button'] ).'</a>';

 }
    $output .= '</ul>';
  $output .= '</div>';

} // end of the loop

$output .= '</div>'; //  // end of the wraper

$output .= '<div style="clear:both;"></div>';
return $output;
}
add_shortcode( 'tc-pricing-table', 'tc_pricing_table_shortcode' );
