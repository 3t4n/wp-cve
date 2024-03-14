<?php
if(!isset($cssData['sbs_6310_fun_template_slider']) || $cssData['sbs_6310_fun_template_slider'] != 1){
   echo "<div class='sbs-6310-noslider sbs-6310-noslider-".esc_attr($ids)."'>";
   if ($results) { 
   echo "<div class='sbs-6310-row'>";
   echo "<div class='sbs-6310-template-".esc_attr($ids)."-main-wrapper'>";
   echo "<div class='sbs-6310-template-".esc_attr($ids)."-parallax'>";
   echo "<div class='sbs-6310-template-".esc_attr($ids)."-common-overlay'>";
   foreach ($results as $value) {
      ?>
      <div class="sbs-6310-col-list sbs-6310-col-<?php echo esc_attr($cssData['desktop_item_per_row']) ?>">
         <div class="sbs-6310-template-<?php echo esc_attr($ids) ?>">
            <div class="sbs-6310-template-<?php echo esc_attr($ids) ?>-icon-<?php echo esc_attr($ids) ?>">    
               <?php sbs_6310_display_icon($ids, esc_attr($value->icontype), esc_attr($value->icons), esc_attr($value->hovericons), esc_attr($value->image), esc_attr($value->hoverimage), 1) ?>
            </div>
            <div class="sbs-6310-template-<?php echo esc_attr($ids) ?>-title">
              <?php echo sbs_6310_replace(esc_attr($value->title)) ?>
            </div>            
               <?php sbs_6310_description($ids, sbs_6310_replace(esc_attr($value->description)), isset($cssData['template_details_show_hide']) ? 1 : 0); ?>
         </div>
      </div>
   <?php 
   }
   echo "</div>";
   echo "</div>";
   echo "</div>";
   echo "</div>";
} 
  echo "</div>";
} else{ 
   echo "<p>Slider is available on pro only.</p>";
}
?>




