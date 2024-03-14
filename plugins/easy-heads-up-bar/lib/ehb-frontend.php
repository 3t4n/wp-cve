<?php 
/**
* The admin class
*/
class ehbFrontend
{
  function __construct()
  {
    add_action('wp_loaded',array(&$this, 'ehu_run_show_bar'));
    add_action( 'wp_enqueue_scripts', array(&$this, 'ehu_load_scripts') );
  }
  function ehu_run_show_bar(){
     add_filter( 'wp_footer', array(&$this, 'ehu_show_bar') );
  }
  /**
  * Displays the bar on the front end
  **/
  function ehu_show_bar()
  {
    // Set up the vars
    global $ehb_meta_prefix;
    $prefix     = $ehb_meta_prefix;
    $bar_html   = "";
    $bar_array  = "";
    $today      = current_time('m/d/Y');
    $yesterday  = date('m/d/Y', strtotime($today) - 86400);
    $tomorrow   = date('m/d/Y', strtotime($today) + 86400);
    $frontpage  = is_front_page();
    $i = 0;

    // WP_Query arguments
    $args = array(
      'post_type'   => 'heads_up_bar',
      'post_status' => 'publish',
    );

    // The Query
    $query = new WP_Query( $args );
    // The Loop
    if ( $query->have_posts() ) {
      while ( $query->have_posts() ) {
        $query->the_post();
        $bar_ID = $query->post->ID;

        $show = true;
        $show_where = get_post_meta( $bar_ID,"{$prefix}show_where", true );
        if( ($show_where == 'home' ) && (!$frontpage) ) $show=false;
        if( ($show_where == 'interior' ) && ($frontpage) ) $show=false;        
        if( $show )
        {
          $start_date = get_post_meta( $bar_ID,"{$prefix}start_date", true );
          $start_date = ($start_date=="") ? $yesterday : $start_date ;
          $start_date = ($start_date==$today) ? $yesterday : $start_date ;
          $check_start_date = ehu_check_date($start_date,  $today);
          if(false !== $check_start_date)
          {
            $end_date       = get_post_meta( $bar_ID,"{$prefix}end_date", true );
            $end_date       = ($end_date=="") ? $tomorrow : $end_date ;
            $check_end_date = ehu_check_date( $today, $end_date);
            if(false !== $check_end_date)
            {
              $bar_content      = get_post_meta( $bar_ID,"{$prefix}bar_content",       true );
              if ($bar_content=="") {
                $bar_content = get_the_title( $bar_ID );
                if ($bar_content=="") $bar_content = get_bloginfo( 'name' );
              }
              $bar_location     = get_post_meta( $bar_ID,"{$prefix}bar_location",      true );
              if($bar_location=='') $bar_location='top';
                if($bar_location=='top')
                {
                  $bar_border_locatoin = "bottom";
                }else{
                  $bar_border_locatoin = "top";
                }
              $bar_bg_color       = get_post_meta( $bar_ID,"{$prefix}bar_bg_color",      true );
              $bar_border_color   = get_post_meta( $bar_ID,"{$prefix}bar_border_color",  true );
              $bar_text_color     = get_post_meta( $bar_ID,"{$prefix}text_color",        true );
              $bar_link_color     = get_post_meta( $bar_ID,"{$prefix}link_color",        true );
              $bar_hide           = get_post_meta( $bar_ID,"{$prefix}hide_bar",          true );
              $bar_content_width  = get_post_meta( $bar_ID,"{$prefix}bar_content_width", true );
              // Set a default width to 100%
              $bar_content_width  = ( $bar_content_width == "" )  ? "80" : $bar_content_width;
              // get bar position
              $bp                 = get_post_meta( $bar_ID,"{$prefix}bar_position", true );
              $bar_position       = (!empty( $bp ) ) ? $bp : 'relative' ;
              
              // lets build a bar workshop ;)
              $bar_html .= "<div id='ehu-bar'";
              $bar_html .= " data-bar-text-color='{$bar_text_color}'";
              $bar_html .= " data-bar-link-color='{$bar_link_color}'";
              $bar_html .= " data-bar-location='{$bar_location}'";
              $bar_html .= " data-hide-bar='{$bar_hide}'";
              $bar_html .= " class='ehu-{$bar_location}-{$bar_position}'";
              $bar_html .= " style='background-color:{$bar_bg_color};border-{$bar_border_locatoin}: 4px solid {$bar_border_color};padding: 6px;'>";
              
              if( $bar_hide == "yes" )
              {
                $bar_html .= "  <div id='ehu-close-button' ";
                $bar_html .=      "title='".__('Close Bar','ehb_lang')."'>";
                $bar_html .=      "X</div>";
              }
              
              $bar_html .= "  <div id='ehu-bar-content' style='display:block;color:{$bar_text_color};padding:2px;margin:0 auto;width:{$bar_content_width}%;'>";
              $bar_html .= do_shortcode($bar_content); 
              $bar_html .= "  </div>";
              $bar_html .= "</div>";
              $bar_html .= "  <div id='ehu-open-button' ";
              $bar_html .=      "title='".__('Open Bar','ehb_lang')."'>";
              $bar_html .=      "&curren;";
              $bar_html .= "  </div>";
              $bar_array[$i] = $bar_html; $i++;
              //reset the $bar_html
              $bar_html = "";
            }
          } // end if(false !== $check_start_date)
        } // end if($how)
      }// end while loop 
    } else {
      // no posts found
    }
    if (is_array($bar_array) && !empty($bar_array)) 
    {
      $random_bar = array_random($bar_array);
      echo $random_bar;      
    }
    // Restore original Post Data
    wp_reset_postdata();
  } // End fun ehu_show_bar()


  /**
   * Load the scripts
   */
  function ehu_load_scripts() 
  {
    $ehb_js_url     = EHB_URL. 'js/ehu.js';
    $ehb_css_url    = EHB_URL. 'css/ehu.css';
    wp_enqueue_script('ehb_js_url',$ehb_js_url,array('jquery'), EHB_VERSION,true );
    wp_register_style('ehb_stylesheet', $ehb_css_url,false, EHB_VERSION,'all');
    wp_enqueue_style( 'ehb_stylesheet');
  }

}
$ehbFrontend = new ehbFrontend();