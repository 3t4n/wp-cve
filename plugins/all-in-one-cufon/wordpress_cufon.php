<?php  /*
 Plugin Name: All-In-One Cuf&oacute;n
 Plugin URI: http://lizatom.com/wordpress-plugin/all-in-one-cufon/
 Description: All-In-One Cufon plugin allows you an easy replacement of standard fonts with beautiful catchy fonts. This plugin implements Simo Kinnunen's Cuf&oacute;n script, which aspires to become a worthy alternative to sIFR. All-In-One Cuf&oacute;n automatically detects uploaded fonts, offers coding examples and gives you the option to enable one or some of the fonts. Enjoy!
 Author: Lizatom.com
 Version: 1.3.0
 Author URI: http://lizatom.com
 */

/**
  * Here's the magic
  *
  * @since 1.0
  */
 function wpcuf_insert_code()
 {     
     $plugin = WP_PLUGIN_URL . '/' . plugin_basename(dirname(__file__));
     $cufon_font_location = WP_PLUGIN_URL . '/cufon-fonts';
     $wpcuf_code = get_option('wpcuf_code');

     /*echo "
		<script type='text/javascript' src='$plugin/js/cufon/cufon-yui.js'></script>          	
		";*/
     wp_enqueue_script('cufon-yui', $plugin . '/js/cufon/cufon-yui.js' );  
     wp_print_scripts('cufon-yui'); 
     $count = 0;
     foreach (glob(WP_PLUGIN_DIR . "/cufon-fonts/*") as $path_to_files)
     {
         $count++;
         $file = basename($path_to_files);
         
         if(is_admin()) { ?>
            
            <?php wp_enqueue_script('font-' . $file, WP_PLUGIN_URL . '/cufon-fonts/' . $file );
            wp_print_scripts('font-' . $file); ?> 
                       
            
<?php         } else if((get_option("enable_font-$count") == "1")) { ?>		
		<script src="<?php echo WP_PLUGIN_URL; ?>/cufon-fonts/<?php echo $file; ?>" type="text/javascript"></script>        
<?php 
         }
     }    

    if(is_admin()) { // WE ARE IN THE ADMIN PANEL ?>   
    
     <link rel='stylesheet' href='<?php echo $plugin; ?>/style/style.css' type='text/css' media='all' />
     
 <?php
     echo "<script type='text/javascript'>\n";
     $count = 0;
     foreach (glob(WP_PLUGIN_DIR . "/cufon-fonts/*") as $path_to_files)
     {
         $count++;
         $file_name = basename($path_to_files);
         $file_content = file_get_contents($path_to_files);
         $delimeterLeft = 'font-family":"';
         $delimeterRight = '"';
         $font_name = font_name($file_content, $delimeterLeft, $delimeterRight, $debug = false);
         echo stripslashes("Cufon('#font-$count', { fontFamily: '$font_name' });\n");
     }
     echo "</script>\n";
     ?>
     
     <?php wp_enqueue_script('Delicious', $plugin . '/font/Delicious_500.font.js' );
            wp_print_scripts('Delicious'); ?>
     <script type='text/javascript'>
             
             Cufon.replace('h2.codeTips', {	hover: true }); //hover rule should be always first!
     
             Cufon('h2.codeTips', { fontFamily: 'Delicious' });
             
             Cufon('h2.codeTips#tip4', { textShadow: '2px 2px red' });
             
             Cufon('h2.codeTips#tip2', { color: '-linear-gradient(red, blue)' });
             
             
              // no comas behind last option             
       
     </script>    
    
     <?php
    } else { // WE ARE ON THE SITE
         /*code from form*/
         echo "<script type='text/javascript'>";
         echo stripslashes(get_option("wpcuf_code"));
         echo "</script>";
   }
     
 }

 if (is_admin())
 {
     add_action('admin_head', 'wpcuf_insert_code');
 } else
 {
     add_action('wp_head', 'wpcuf_insert_code');
 }

 /**
  * extract name of the font
  *
  * @since 1.0
  */
 function font_name($inputStr, $delimeterLeft, $delimeterRight, $debug = false)
 {
     $posLeft = strpos($inputStr, $delimeterLeft);
     if ($posLeft === false)
     {
         if ($debug)
         {
             echo "Warning: left delimiter '{$delimeterLeft}' not found";
         }
         return false;
     }
     $posLeft += strlen($delimeterLeft);
     $posRight = strpos($inputStr, $delimeterRight, $posLeft);
     if ($posRight === false)
     {
         if ($debug)
         {
             echo "Warning: right delimiter '{$delimeterRight}' not found";
         }
         return false;
     }
     return substr($inputStr, $posLeft, $posRight - $posLeft);
 }

 //*************** Admin function ***************
 /**
  * Let's call admin panel
  *
  * @since 1.0
  */
 function wpcuf_admin()
 {
     include ('wordpress_cufon_adminpanel.php');
 }

/**
  * Name of the panel etc.
  *
  * @since 1.0
  */
 function wpcuf_admin_actions()
 {
     add_options_page("All-In-One Cufon", "All-In-One Cufon", 1, "All-In-OneCufon",
         "wpcuf_admin");
 }

 add_action('admin_menu', 'wpcuf_admin_actions');
?>