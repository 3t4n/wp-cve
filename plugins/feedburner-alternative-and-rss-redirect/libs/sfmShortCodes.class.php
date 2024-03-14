<?php
/* widget shortcode class */

class sfmShortCodes {
  
  function __construct()
  {
   /* create short code */
   
   add_shortcode('sfm_newsletter', array(&$this,'sfm_shortcode'));
  }
  
  /* make short code from current widget */
  public function sfm_shortcode($atts)
  {
   
    extract(shortcode_atts(array(
        'id' => FALSE
    ), $atts));
    /* check if widget id is blank */
    if(empty($id)):
    return "<strong>wrong widget short code. please use a valid shortcode.</strong>";exit;
    endif;
     /* get widget options from database */
    $widget_data=get_option('widget_sfm-widget');
    $instance=$widget_data[$id];
    if(empty($instance)) :
     return "<strong>Widget not found.Please add a widget to any sidebar.</strong>";exit;
    endif;
    ob_start();
 ?>
    <div class="sfm_widget_sec" style="background-color: <?php echo $instance['sfm_back_color']; ?> ;  <?php echo $border; ?>;">   
                    <?php /* Display the widget title */
		if ( $instance['title'] ) echo  "<span class='sfmTitle' style='margin-bottom:10px;font-family:". $instance['sfm_font'].";font-size: ".$instance['sfm_font_size'].";color: ".$instance['sfm_font_color']." ;'>".$instance['title']."</span>" ;
		/* Link the main icons function */
                $SFMWid=new sfmWidget();
                echo $SFMWid->sfm_newsLetterForm('sfm-widget-front'.$id);
               ?>
   </div>
    <?php            
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
   
  }
  
}/* end of class */