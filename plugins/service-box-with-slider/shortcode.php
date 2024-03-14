<?php
wp_enqueue_script('jquery');
global $wpdb;
sbs_6310_external();
$style_table = $wpdb->prefix . 'sbs_6310_style';
$item_table = $wpdb->prefix . 'sbs_6310_item';
$styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $ids), ARRAY_A);
if(!$styledata || $styledata == '') return;
$styleTemplate = (int) substr($styledata['style_name'], -2);
$css = explode("!!##!!", $styledata['css']);
$key = explode(",", $css[0]);
$value = explode("||##||", $css[1]);
$filterKey = [];
$filterValue = [];
for($i = 0; $i < count($key); $i++) {
   $filterKey[] = esc_attr($key[$i]);
}
for($i = 0; $i < count($value); $i++) {
   $filterValue[] = esc_attr($value[$i]);
}
$cssData = array_combine($filterKey, $filterValue);
$styleId = substr($styledata['style_name'], -2);

$item_order = $styledata['itemids'];

$results = [];
if($styledata['itemids']){
   $itemList = explode('||##||', $styledata['itemids']);
   if($itemList[0]){
      if(!isset($itemList[1])) {
         $itemList = explode('##||##', $styledata['itemids']);
      } 
      $idExist = explode(',', $itemList[0]);
      if($idExist){
         $tempId = '';
         foreach ($idExist as $ie) {
            if (trim($ie) != '') {
               if ($tempId != '') {
                  $tempId .= ',';
               }
               $tempId .= $ie;
            }
         }
         if ($tempId == '') {
            return;
         }
         $results = $wpdb->get_results("SELECT * FROM $item_table WHERE id in ({$tempId}) ORDER BY title asc", OBJECT);
      }
      else{
         return;
      }
   }
   else{
      return;
   }
}
else{
   return;
}

if(!$results) return;
$desktop_row = esc_attr($cssData['desktop_item_per_row']);
$tablet_row = esc_attr($cssData['tablet_item_per_row']);
$mobile_row = esc_attr($cssData['mobile_item_per_row']);
$bgType = esc_attr($cssData['background_type']);

if (file_exists(sbs_6310_plugin_url . "output/{$styledata['style_name']}.php")) {
   $fonts = '';
   $google_font = get_option( 'sbs_6310_google_font_status');
   if ($google_font != 1) {
      $fonts = str_replace("+", " ", esc_attr($cssData['sbs_6310_title_font_family']));
      $fonts .= '|' . str_replace("+", " ", esc_attr($cssData['sbs_6310_details_font_family']));
      $fonts .= '|' . str_replace("+", " ", esc_attr($cssData['sbs_6310_read_more_font_family']));
      wp_enqueue_style("sbs-6310-google-font-".esc_attr($ids)."", "https://fonts.googleapis.com/css?family={$fonts}");
   }
   wp_enqueue_style('sbs-6310-font-awesome-5-0-13', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css');
   wp_enqueue_style('sbs-6310-font-awesome-4-07', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
   if (isset($cssData['sbs_6310_fun_template_slider']) && $cssData['sbs_6310_fun_template_slider'] == 1) {
      wp_enqueue_style('sbs-6310-owl-carousel', plugins_url('assets/css/owl.carousel.min.css', __FILE__));
      wp_enqueue_script('sbs-6310-owl-carousel-js', plugins_url('assets/js/owl.carousel.min.js', __FILE__), array('jquery'), TRUE);
   }
   ?>
    <div class="sbs-6310-service-box"
         sbs-6310-style-id='<?php echo esc_attr($ids) ?>'
         sbs-6310-style-desktop='<?php echo esc_attr($cssData['desktop_item_per_row']) ?>'
         sbs-6310-style-tablet='<?php echo esc_attr($cssData['tablet_item_per_row']) ?>'
         sbs-6310-style-mobile='<?php echo esc_attr($cssData['mobile_item_per_row']) ?>'
         sbs-6310-carousel-active="<?php echo isset($cssData['sbs_6310_fun_template_slider']) ? 1 : 0 ?>"
         sbs-6310-carousel-margin='<?php echo esc_attr($cssData['item_margin']) ?>'
         sbs-6310-carousel-duration='<?php echo esc_attr($cssData['effect_duration']) ?>'
         sbs-6310-carousel-nav='<?php echo isset($cssData['prev_next_active']) ? 1 : 0 ?>'
         sbs-6310-carousel-dot='<?php echo isset($cssData['indicator_activation']) ? 1 : 0 ?>'
         sbs-6310-carousel-navText='<?php echo esc_attr($cssData['slider_icon_style']) ?>'
      >
      <?php include sbs_6310_plugin_url . "output/".esc_attr($styledata['style_name']).".php"; ?>
   </div>
<?php
   include sbs_6310_plugin_url . "output/css/_common-css.php";
   include sbs_6310_plugin_url . "output/css/_css-".esc_attr($styleId).".php";

   wp_enqueue_script('sbs-6310-common-output-js', plugins_url('assets/js/sbs-6310-common-output.js', __FILE__), array('jquery'), TRUE);
} else {
   echo "<p>This template is available on pro only.</p>";
}
