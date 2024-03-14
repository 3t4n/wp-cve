<?php

class DMCA_Badge_Widget extends WP_Widget {
    
    function __construct() {
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
            
            
            parent::__construct( 
                'dmca_widget_badge', 
                __('DMCA Website Protection Badge', 'dmca-badge'), 
                array( 
                    'description' => __( 'Display your chosen DMCA Website Protection Badge in any widget area of your site.', 'dmca-badge' ), 
                ) 
            );
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }
        
	}

	function widget( $args, $instance ){
        $error_path = plugin_dir_url(__FILE__) ;
	    try {
            
            echo $args['before_widget'];
            echo DMCA_Badge_Plugin::this()->get_badge_html();
            echo $args['after_widget'];
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }
	}
}