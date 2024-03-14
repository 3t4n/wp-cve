<div 
   <?php
      echo " class='sbs-6310-noslider sbs-6310-noslider-".esc_attr($templateId)."' ";
   ?>
>
   <?php 
   sbs_6310_search_template($templateId, $cssData); 
   if ($results) { 
   echo "<div class='sbs-6310-row'>";
   echo "<div class='sbs-6310-template-".esc_attr($templateId)."-parallax'>";
   echo "<div class='sbs-6310-template-".esc_attr($templateId)."-common-overlay'>";
   if($bgType == 4) {
      $youtube_video_url = $cssData['youtube_video_url'] ? explode('?v=', esc_attr( $cssData['youtube_video_url'])) : [];
      if(isset($youtube_video_url[1])) {
         echo "<iframe src='https://www.youtube.com/embed/".esc_attr($youtube_video_url[1])."?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=".esc_attr($youtube_video_url[1])."&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>"; 
      }  
   }
   foreach ($results as $value) {
      ?>
      <div class="sbs-6310-col-list sbs-6310-col-<?php echo esc_attr($cssData['desktop_item_per_row']) ?> ">
         <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-wrapper sbs-6310-template-<?php echo esc_attr($templateId) ?>-flip-right">
            <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>">
               <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-front">
               <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-icon">
                  <?php sbs_6310_display_icon($templateId, esc_attr($value->icontype), esc_attr($value->icons), esc_attr($value->hovericons), esc_attr($value->image), esc_attr($value->hoverimage)) ?>
               </div>
               <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-title">
                 <?php echo sbs_6310_replace(esc_attr($value->title)) ?>
               </div>
               </div>
               <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-back">
               <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-description"> 
                  <?php echo sbs_6310_replace(esc_attr($value->description)) ?>
                  <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-read-more">
                     <?php sbs_6310_display_read_more($templateId, esc_attr($value->targertype), sbs_6310_replace(esc_attr($value->detailstext)), esc_url($value->detailsurl) ) ?>
                  </div>
               </div>
               </div>
            </div>
         </div>
      </div>
      <?php 
   }
   echo "</div>";
   echo "</div>";
   echo "</div>";
}
?>
</div>

<?php
   echo "<div class='sbs-6310-slider sbs-6310-carousel-".esc_attr($templateId)."'>";
   echo "<div class='sbs-6310-template-".esc_attr($templateId)."-main-wrapper'>";
   echo "<div class='sbs-6310-template-".esc_attr($templateId)."-parallax'>";
   echo "<div class='sbs-6310-template-".esc_attr($templateId)."-common-overlay'>";
   if($bgType == 4) {
      $youtube_video_url = $cssData['youtube_video_url'] ? explode('?v=', esc_attr( $cssData['youtube_video_url'])) : [];
      if(isset($youtube_video_url[1])) {
         echo "<iframe src='https://www.youtube.com/embed/".esc_attr($youtube_video_url[1])."?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=".esc_attr($youtube_video_url[1])."&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>"; 
      }  
   }
   echo "<div class='sbs-6310-slider-".esc_attr($templateId)." sbs-6310-owl-carousel'>";    
      if ($results) {
         foreach ($results as $value) {
            ?>
            <div class="sbs-6310-item">
               <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-wrapper sbs-6310-template-<?php echo esc_attr($templateId) ?>-flip-right">
                  <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>">
                     <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-front">
                     <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-icon">
                        <?php sbs_6310_display_icon($templateId, esc_attr($value->icontype), esc_attr($value->icons), esc_attr($value->hovericons), esc_attr($value->image), esc_attr($value->hoverimage)) ?>
                     </div>
                     <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-title">
                       <?php echo sbs_6310_replace(esc_attr($value->title)) ?>
                     </div>
                     </div>
                     <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-back">
                     <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-description"> 
                        <?php echo sbs_6310_replace(esc_attr($value->description)) ?>
                        <div class="sbs-6310-template-<?php echo esc_attr($templateId) ?>-read-more">
                           <?php sbs_6310_display_read_more($templateId, esc_attr($value->targertype), sbs_6310_replace(esc_attr($value->detailstext)), esc_url($value->detailsurl) ) ?>
                        </div>
                     </div>
                     </div>
                  </div>
               </div>
            </div>
         <?php 
         }
      }
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
?>






