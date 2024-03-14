<?php
/*
Plugin Name: Asteroids Widget by Electric Tree House
Plugin URI: http://electrictreehouse.com/asteroids-widget-plugin/
Description: Turn your site into the game of Asteroids. Click to start and you can destroy the contents of your webpage by flying around and shooting them. Check out <a href="http://electrictreehouse.com/">Electric Tree House</a> (bottom of the right sidebar) to try it out yourself. See Plugin website for more details and customization options. 
Version: 3.0.0
Author: Eric Burger
Author URI: http://electrictreehouse.com/
*/          


class Asteroids_Widget extends WP_Widget {

	function Asteroids_Widget() {
		$widget_ops = array('classname' => 'widget_asteroids', 'description' => __('Play Asteroids and Blow Stuff Up'));
		$control_ops = array('width' => 500, 'height' => 350);
		$this->WP_Widget('asteroids', __('Asteroids Widget'), $widget_ops, $control_ops);
	}

	function widget( $args, $options ) {
		extract($args);
		$asteroids_title = apply_filters( 'widget_title', empty($options['title']) ? '' : $options['title'], $options );
		$asteroids_text = apply_filters( 'widget_asteroids', $options['text'], $options );
    $asteroids_link = '<p style="font-size: 70%;" align="right">By <a href="http://electrictreehouse.com">Eric</a>  and <a href="http://erkie.github.com/">Erik</a></p>';
		
		/* Set Image Locations */
    $plugin_dir = basename(dirname(__FILE__));	
		$bloginfo = home_url();
    $asteroids_gears = ''. $bloginfo . '/wp-content/plugins/' . $plugin_dir . '/gears';
    
    $asteroids_bk    = ''. $asteroids_gears .'/asteroids-bk.jpg';
		$asteroids_mainimage =    ''. $asteroids_gears .'/asteroids-image.jpg';
		$asteroids_rocketimage =  ''. $asteroids_gears .'/asteroids-rocket.png';
		$asteroids_nohoverimage = ''. $asteroids_gears .'/asteroids.jpg';
		$asteroids_hoverimage =   ''. $asteroids_gears .'/asteroids-hover.jpg';
		$asteroids_arcadered =    ''. $asteroids_gears .'/arcade-red.png';
		$asteroids_arcadeyellow = ''. $asteroids_gears .'/arcade-yellow.png';
		$asteroids_arcadeblack =  ''. $asteroids_gears .'/arcade-black.gif';
    
    /* Change bullet color to yellow */
    if(isset($options['bullet-color']) && $options['bullet-color'] != "")	{
      $address = ''.WP_PLUGIN_URL .'/'. basename(dirname(__FILE__)) . '/gears/play-asteroids-yellow.min.js';
      $asteroids_start = "startAsteroids('yellow','$address');";
    }else{
      $address = ''.WP_PLUGIN_URL .'/'. basename(dirname(__FILE__)) . '/gears/play-asteroids.min.js';
      $asteroids_start = "startAsteroids('','$address');";
    }
		
		$asteroids_show = $options['show'];
		$asteroids_slug = $options['slug'];
		$asteroids_buttonopt = $options['button-opt'];
		$asteroids_imageopt = $options['image-opt'];
    
    if($options['background']){
      $before_asteroids = '<div style="background-image: url('.$asteroids_bk.');padding:20px 10px 20px 10px;">';
      $before_text = '<span style="text-align:center;color:#fff;">';
      $after_text = '</span>';
      $after_asteroids = '</div>';
    }
    else{
      $before_asteroids = '<div>';
      $before_text = '';
      $after_text = '';
      $after_asteroids = '</div>';
    }
		
		switch ($asteroids_show) {
			case "all": 
        // Start
				echo $before_widget;
        // Add title
				if ( !empty( $asteroids_title ) ) { echo $before_title . $asteroids_title . $after_title; } 
								
        echo $before_asteroids;
        
				include( 'gears/run-asteroids.php');
				
        // Clean and foramt text 
        // ob_ and eval used to handle html and php
				ob_start();
				eval('?>'.$asteroids_text);
				$asteroids_text = ob_get_contents();
				ob_end_clean();       
        $asteroids_text = '<div class="asteroidswidget">'.($options['filter'] ? wpautop($asteroids_text) : $asteroids_text).'</div>';
        
        echo $before_text;
        echo $asteroids_text; 
        echo $after_text;
        
        echo $after_asteroids;
        // Add link to front page only
        if ( is_front_page() ) {echo $asteroids_link;}
        // Done
				echo $after_widget;
			break;
			
			case "front":
				if (is_front_page()) {
          // Start
          echo $before_widget;
          // Add title
          if ( !empty( $asteroids_title ) ) { echo $before_title . $asteroids_title . $after_title; } 
                  
          echo $before_asteroids;
          
          include( 'gears/run-asteroids.php');
          
          // Clean and foramt text 
          // ob_ and eval used to handle html and php
          ob_start();
          eval('?>'.$asteroids_text);
          $asteroids_text = ob_get_contents();
          ob_end_clean();       
          $asteroids_text = '<div class="asteroidswidget">'.($options['filter'] ? wpautop($asteroids_text) : $asteroids_text).'</div>';
          
          echo $before_text;
          echo $asteroids_text; 
          echo $after_text;
          
          echo $after_asteroids;
          // Add link to front page only
          echo $asteroids_link;
          // Done
          echo $after_widget;	
        }
					
			break;

			
			case "post":
        if (is_single($slug)) {
          // Start
          echo $before_widget;
          // Add title
          if ( !empty( $asteroids_title ) ) { echo $before_title . $asteroids_title . $after_title; } 
                  
          echo $before_asteroids;
          
          include( 'gears/run-asteroids.php');
          
          // Clean and foramt text 
          // ob_ and eval used to handle html and php
          ob_start();
          eval('?>'.$asteroids_text);
          $asteroids_text = ob_get_contents();
          ob_end_clean();       
          $asteroids_text = '<div class="asteroidswidget">'.($options['filter'] ? wpautop($asteroids_text) : $asteroids_text).'</div>';
          
          echo $before_text;
          echo $asteroids_text; 
          echo $after_text;
          
          echo $after_asteroids;

          // Add link to front page only
          echo $asteroids_link;
          // Done
          echo $after_widget;	
        }
			break;
			
			
			case "category":
        if (is_category($slug)) {
          // Start
          echo $before_widget;
          // Add title
          if ( !empty( $asteroids_title ) ) { echo $before_title . $asteroids_title . $after_title; } 
                  
          echo $before_asteroids;
          
          include( 'gears/run-asteroids.php');
          
          // Clean and foramt text 
          // ob_ and eval used to handle html and php
          ob_start();
          eval('?>'.$asteroids_text);
          $asteroids_text = ob_get_contents();
          ob_end_clean();       
          $asteroids_text = '<div class="asteroidswidget">'.($options['filter'] ? wpautop($asteroids_text) : $asteroids_text).'</div>';
          
          echo $before_text;
          echo $asteroids_text; 
          echo $after_text;
          
          echo $after_asteroids;

          // Add link to front page only
          echo $asteroids_link;
          // Done
          echo $after_widget;	
        }
			break;
			
						
			case "page":
        if (is_page($slug)) {
          // Start
          echo $before_widget;
          // Add title
          if ( !empty( $asteroids_title ) ) { echo $before_title . $asteroids_title . $after_title; } 
                  
          echo $before_asteroids;
          
          include( 'gears/run-asteroids.php');
          
          // Clean and foramt text 
          // ob_ and eval used to handle html and php
          ob_start();
          eval('?>'.$asteroids_text);
          $asteroids_text = ob_get_contents();
          ob_end_clean();       
          $asteroids_text = '<div class="asteroidswidget">'.($options['filter'] ? wpautop($asteroids_text) : $asteroids_text).'</div>';
          
          echo $before_text;
          echo $asteroids_text; 
          echo $after_text;
          
          echo $after_asteroids;

          // Add link to front page only
          echo $asteroids_link;
          // Done
          echo $after_widget;	
        }
			break;
		}

	}





	function update( $newoptions, $oldoptions ) {
		$options = $oldoptions;
		$options['title'] = strip_tags($newoptions['title']);
		if ( current_user_can('unfiltered_html') )
			$options['text'] =  $newoptions['text'];
		else
		$options['text'] = stripslashes( wp_filter_post_kses( $newoptions['text'] ) );
		$options['filter'] = isset($newoptions['filter']);
		$options['bullet-color'] = isset($newoptions['bullet-color']);
    $options['background'] = isset($newoptions['background']);
		$options['button-opt'] = $newoptions['button-opt'];
		$options['image-opt'] = $newoptions['image-opt'];
		$options['show'] = $newoptions['show'];
		$options['slug'] = strip_tags(stripslashes($newoptions['slug']));
		return $options;
			
	}

	function form( $options ) {
		$options = wp_parse_args( (array) $options, array( 'title' => '', 'text' => '' , 'button-opt' => 'push-1' ) );
		$title = strip_tags($options['title']);
		$text = format_to_edit($options['text']);

		$allSelected = $frontSelected = $postSelected = $pageSelected = $categorySelected = false;
		switch ($options['show']) {
			case "all":
			$allSelected = true;
			break;
			case "":
			$allSelected = true;
			break;
			case "front":
			$frontSelected = true;
			break;
			case "post":
			$postSelected = true;
			break;
			case "page":
			$pageSelected = true;
			break;
			case "category":
			$categorySelected = true;
			break;
		}
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" style="width: 300px" value="<?php echo esc_attr($title); ?>" /></p>
    
    <p>Add a description so people know what they are clicking on. Add directions like: fly with the arrow keys and shoot with spacebar. Or add html/php code to include a picture. See Read Me for more editing options or visit <a href="http://electrictreehouse.com/asteroids-widget-plugin/" target="_blank">Electric Tree House</a>.</p>
    <?php /* All PHP code must be enclosed in the standard < ?php and ?> tags for it to be recognized. */ ?>
    
		<textarea class="widefat" rows="4" cols="22" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

		<p><label for="<?php echo $this->get_field_id('filter'); ?>">
    <input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($options['filter']) ? $options['filter'] : 0); ?> /><?php _e(' Auto-Format Text &nbsp;'); ?></label>
    
    <label for="<?php echo $this->get_field_id('bullet-color'); ?>">
    <input id="<?php echo $this->get_field_id('bullet-color'); ?>" name="<?php echo $this->get_field_name('bullet-color'); ?>" type="checkbox" <?php checked(isset($options['bullet-color']) ? $options['bullet-color'] : 0); ?> /><?php _e(' Change Bullet Color to Yellow &nbsp;'); ?></label>
    
    <label for="<?php echo $this->get_field_id('background'); ?>">
    <input id="<?php echo $this->get_field_id('background'); ?>" name="<?php echo $this->get_field_name('background'); ?>" type="checkbox" <?php checked(isset($options['background']) ? $options['background'] : 0); ?> /><?php _e(' Add Asteroids Background'); ?></label>
    </p>
    
    <p><label for="<?php echo $this->get_field_id('image-opt'); ?>"  title="" style="line-height:5px;"><?php _e('Show Image Option: '); ?>
    <select name="<?php echo $this->get_field_name('image-opt'); ?>" id="<?php echo $this->get_field_name('image-opt'); ?>" style="width:130px;padding-left:10px;">
      <option label="None" value="none" <?php if($options['image-opt']=="none"){echo "selected";} ?>>None</option>
      <option label="Asteroids" value="image-1" <?php if($options['image-opt']=="image-1"){echo "selected";} ?>>Asteroids</option>
      <option label="Hover" value="image-2" <?php if($options['image-opt']=="image-2"){echo "selected";} ?>>Hover</option>
      <option label="Rocket" value="image-3" <?php if($options['image-opt']=="image-3"){echo "selected";} ?>>Rocket</option>
      <option label="Red Arcade" value="image-4" <?php if($options['image-opt']=="image-4"){echo "selected";} ?>>Red Arcade</option>
      <option label="Yellow Arcade" value="image-5" <?php if($options['image-opt']=="image-5"){echo "selected";} ?>>Yellow Arcade</option>
      <?php /* <option label="Black Arcade" value="image-6" <?php if($options['image-opt']=="image-6"){echo "selected";} ?>>Black Arcade</option>*/?>
    </select></label></p>
                    
    <p><label for="<?php echo $this->get_field_id('button-opt'); ?>"  title="" style="line-height:5px;"><?php _e('Use Button or Text Link: '); ?> 
    <select name="<?php echo $this->get_field_name('button-opt'); ?>" id="<?php echo $this->get_field_name('button-opt'); ?>" style="width:130px;padding-left:10px;">
      <option label="None" value="none" <?php if($options['button-opt']=="none"){echo "selected";} ?>>None</option>
      <option label="Push Button" value="push-1" <?php if($options['button-opt']=="push-1"){echo "selected";} ?>>Push Button 1</option>
      <option label="Text Link" value="text-1" <?php if($options['button-opt']=="text-1"){echo "selected";} ?>>Text Link 1</option>
    </select></label></p>
            
    <p><span>Choose the pages on which the Asteroids Widget should appear:</span><br \>
    <label for="<?php echo $this->get_field_id('show'); ?>"  title="Show only on specified page(s)/post(s)/category. Default is All" style="line-height:35px;">Display only on: 
      <select name="<?php echo $this->get_field_name('show'); ?>" id="<?php echo $this->get_field_id('show'); ?>" onchange="javascript: toggleAsteroidsSlugMenu('<?php echo $this->get_field_id( 'show' ); ?>');">
        <option label="All" value="all" <?php if ($allSelected){echo "selected";} ?>>All</option>
        <option label="Front Page" value="front" <?php if ($frontSelected){echo "selected";} ?>>Front Page</option>
        <option label="Post(s)" value="post" <?php if ($postSelected){echo "selected";} ?>>Post(s)</option>
        <option label="Category" value="category" <?php if ($categorySelected){echo "selected";} ?>>Category</option>
        <option label="Page(s)" value="page" <?php if ($pageSelected){echo "selected";} ?>>Page(s)</option>
      </select></label>
    
    <span id="<?php echo $this->get_field_id('show'); ?>_slug">
      <label for="<?php echo $this->get_field_name('slug'); ?>"  title="Optional limitation to specific page, post or category. Use ID, slug or title." style="line-height:35px;">
        <span>Slug, Title, or ID : </span>
        <input type="text" style="width: 150px;" id="<?php echo $this->get_field_name('slug'); ?>" name="<?php echo $this->get_field_name('slug'); ?>" value="<?php echo htmlspecialchars($options['slug']); ?>" />
        <span style="float:right;line-height:5px;padding-right:110px;"><em><small> Comma Separated </small></em></span>
        
      </label>
    </span>
    

    <p>Though Erik and I are upstanding young people, have put a lot of time and work into this widget, and would appreciate the link, you can use the "Page(s)" option to disable the link. </p>
    The Asteroids Button can be added to any post or page by simple writting [asteroids] within the text.
    
    <script type="text/javascript">
      jQuery(window).load(function() {
        toggleAsteroidsSlugMenu('<?php echo $this->get_field_id( 'show' ); ?>');
      });
      toggleAsteroidsSlugMenu('<?php echo $this->get_field_id( 'show' ); ?>');
    </script>
        
<?php
	}
}

  // Register
  add_action('widgets_init', create_function('', 'return register_widget("Asteroids_Widget");'));

  // Admin JS Functions
	function asteroids_admin_head(){ 
    wp_enqueue_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js' );
    wp_enqueue_script('asteroids_admin_menu',WP_PLUGIN_URL .'/'. basename(dirname(__FILE__)) . '/gears/asteroids-admin-menu.js');
	}
  add_action('admin_head', 'asteroids_admin_head');
  


  // Load Asteroids JS Functions
  function asteroids_Enqueue_Scripts() {
    wp_enqueue_script('asteroids_admin_menu',WP_PLUGIN_URL .'/'. basename(dirname(__FILE__)) . '/gears/start-asteroids-function.js');
  }
  add_action('wp_enqueue_scripts', 'asteroids_Enqueue_Scripts');
  
  
  
  // Asteroids Short Code
  // [asteroids foo="foo-value"]
  function asteroids_short_func( $atts ) {
    extract( shortcode_atts( array(
      'foo' => 'something',
      'bar' => 'something else',
    ), $atts ) );

    $address = ''.WP_PLUGIN_URL .'/'. basename(dirname(__FILE__)) . '/gears/play-asteroids.min.js';
    $asteroids_start = "startAsteroids('','$address');";
    $asteroids_buttonopt = "push-1";
    include( 'gears/run-asteroids.php');
    //return "foo = {$foo}";
  }
  add_shortcode( 'asteroids', 'asteroids_short_func' );
  
?>