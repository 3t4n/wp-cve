<?php 

    /*############################### Like Box WIDGET CODE ###############################################*/

class like_box_facbook extends WP_Widget {
	private static $id_of_like_box=0;
	// Constructor //	
	function __construct() {		
		parent::__construct(
			'like_box_facbook', // Base ID
			'Like box Facebook', // Name
			array( 'description' => 'Like box Facebook ', ) 
		);	

	}

	/*############################### Function for the widget part ########################################*/
	
	function widget($args, $instance) {
		self::$id_of_like_box++;
		extract( $args );
		$title = $instance['title'];    
		$params_of_widget=array(
			'profile_id' 	=>  	$instance['profile_id'],
			'width' 		=>  	(int)$instance['width'], // Type here default Maximum Width
			'height' 		=>  	(int)$instance['height'],// Type here default Height
			'show_border' 	=>  	'show',
			'border_color' 	=>  	'#FFF',
			'header' 		=>  	$instance['header'], // Like Box Header type
			'show_cover_photo'=> 	$instance['cover_photo'],  //Like Box Header cover photo
			'connections' 	=> 		$instance['connections'],// Show Users Faces
			'stream' 		=> 	$instance['stream'],			
			'animation_efect'=>		'none',			
			'locale'		=>   	$instance['locale'], // Language	
		
		);
		// Part before the Widget //
		echo $before_widget;
		
		// Title of the widget //
		if ( $title ) { echo $before_title . $title . $after_title; }
		// Part of the Widget output//
		echo like_box_setting::generete_iframe_by_array($params_of_widget); 
		// Part after the Widget //
		
		echo $after_widget;
	}

    /*############################### Function for updating the settings ###############################################*/
	
		function update($new_instance, $old_instance) {	
		$instance['title'] = strip_tags($new_instance['title']);    
		$instance['profile_id'] = $new_instance['profile_id'];		
		$instance['connections'] = $new_instance['connections'];
		$instance['width'] = $new_instance['width'];
		$instance['height'] = $new_instance['height'];
		$instance['header'] = $new_instance['header'];
		$instance['cover_photo'] = $new_instance['cover_photo'];
		$instance['locale'] = $new_instance['locale'];
    $instance['stream'] = $new_instance['stream'];
		return $instance;  /// Return parameters new values
		
	}

	/*############ Function for the Admin page options ##################*/
	
	function form($instance) {
		
		$defaults = array( 'title' => '','profile_id' => '','stream' => 'true', 'connections' => 'show','width' => '300','height' => '550','header' => 'small','cover_photo' => 'show','locale' => 'en_US');
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
        

        <p class="flb_field">
          <label for="title">Title</label>
          <br>
          <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" class="widefat">
        </p>
        <p class="flb_field">
          <label for="<?php echo $this->get_field_id('profile_id'); ?>">Page ID</label>
          <br>
          <input id="<?php echo $this->get_field_id('profile_id'); ?>" name="<?php echo $this->get_field_name('profile_id'); ?>" type="text" value="<?php echo $instance['profile_id']; ?>" class="widefat">
        </p>
        
      <p class="flb_field">
          <label for="<?php echo $this->get_field_id('width'); ?>">Like box width</label>
          <br>
          <input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $instance['width']; ?>" class="" size="3">
          <small>(px)</small>
        </p>
        
        <p class="flb_field">
          <label for="<?php echo $this->get_field_id('height'); ?>">Like box height</label>
          <br>
          <input id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $instance['height']; ?>" class="" size="3">
          <small>(px)</small>
        </p>
        
        <label for="this_is_a_bad">Like box Animation <span style="color:#7052fb;font-weight:bold;">Pro feature!</span></label>
        <br>
       
        <?php  like_box_setting::generete_animation_select('this_is_a_bad','none') ?>
        <br>
        <br>
          <label for="<?php echo $this->get_field_id('show_border'); ?>">Like box border <span style="color:#7052fb;font-weight:bold;">Pro feature!</span></label>
        <br>
       <select id="show_like_box_border" name="show_like_box_border_name" onMouseDown="alert('If you want use this feature upgrade to Like box Pro')">
            <option selected="selected" value="show">Show</option>
            <option  value="hide">Hide</option>
        </select>
        <br>
        <br>
        <label for="border_color">Like box Border Color <span style="color:#7052fb;font-weight:bold;">Pro feature!</span></label>
        <br>
            <div class="disabled_for_pro" onclick="alert('If you want use this feature upgrade to Like box Pro')">
				<div class="wp-picker-container disabled_picker">
					<button type="button" class="button wp-color-result" aria-expanded="false" style="background-color: rgb(255, 255, 255);"><span class="wp-color-result-text">Select Color</span></button>
				</div> 
            </div>        
        <p class="flb_field">
          <label for="<?php echo $this->get_field_id('stream'); ?>">Facebook latest posts</label>
          <br>
          <select id="<?php echo $this->get_field_id('stream'); ?>" name="<?php echo $this->get_field_name('stream'); ?>">
            <option <?php selected($instance['stream'],'show') ?>  value="show">Show</option>
            <option <?php selected($instance['stream'],'hide') ?>  value="hide">Hide</option>
        </select>
        </p>
        
        <p class="flb_field">
          <label for="<?php echo $this->get_field_id('connections'); ?>">Show Users Faces</label>
          <br>
          <select id="<?php echo $this->get_field_id('connections'); ?>" name="<?php echo $this->get_field_name('connections'); ?>">
            <option <?php selected($instance['connections'],'show') ?> value="show">Show</option>
            <option <?php selected($instance['connections'],'hide') ?> value="hide">Hide</option>
          </select>
        </p>
          
        
        <p class="flb_field">
          <label for="<?php echo $this->get_field_id('header'); ?>">Like box Header</label>
         <br>
           <select id="<?php echo $this->get_field_id('header'); ?>" name="<?php echo $this->get_field_name('header'); ?>">
            <option <?php selected($instance['header'],'small') ?> value="small">Small</option>
            <option <?php selected($instance['header'],'big') ?> value="big">Big</option>
          </select>
        </p>
         <p class="flb_field">
          <label for="<?php echo $this->get_field_id('header'); ?>">Like box cover photo</label>
          <br>
           <select id="<?php echo $this->get_field_id('cover_photo'); ?>" name="<?php echo $this->get_field_name('cover_photo'); ?>">
            <option <?php selected($instance['cover_photo'],'show') ?> value="show">Show</option>
            <option <?php selected($instance['cover_photo'],'hide') ?> value="hide">Hide</option>
          </select>
        </p>
        
        <p class="flb_field">
          <label for="<?php echo $this->get_field_id('locale'); ?>">Language</label>
          <br>
          <input id="<?php echo $this->get_field_id('locale'); ?>" name="<?php echo $this->get_field_name('locale'); ?>" type="text" value="<?php echo $instance['locale']; ?>" class="" size="4">
          <small>(en_US, de_DE, it_IT...)</small>
        </p>
        <a href="https://wpdevart.com/wordpress-facebook-like-box-plugin/" target="_blank" style="color: #7052fb; font-weight: bold; font-size: 18px; text-decoration: none;">Upgrade to Pro Version</a><br>
        <br>
        <br>
        <input type="hidden" id="flb-submit" name="flb-submit" value="1">
        <script>
			var pro_text='If you want to use this feature upgrade to Like box Pro';
            jQuery(".color_my_likbox").ready(function(e) {
				
				jQuery(".color_my_likbox").each(function(index, element) {
                    if(!jQuery(this).hasClass('wp-color-picker') && jQuery(this).attr('name').indexOf('__i__')==-1){ jQuery(this).wpColorPicker()};
                });
               
            });
        </script> 
		<?php 
	}
}
add_action('widgets_init', function(){ return register_widget("like_box_facbook"); } );
