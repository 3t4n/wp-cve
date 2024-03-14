<?php
/**
 * @package my-twitter-timelines
*/
/*
Plugin Name: My Twitter Timelines
Plugin URI: http://www.audaciotech.com
Description: My Twitter Timelines is all in one twitter widget. With this one widget you can display twitter user timelines, user favourites, Search timeline, List timeline, Collection timeline. You don't have to install multiple twitter widget any more to manage your twitter feed on website. Just install My Widget and Enjoy.
Version: 1.0
Author: Audacio Tech IT
Author URI: http://www.audaciotech.com
*/

class MyTwitter extends WP_Widget{
    
    public function __construct() {
        $params = array(
            'description' => 'My Twitter Timelines is all in one twitter widget',
            'name' => 'My Twitter Timelines'
        );
        parent::__construct('MyTwitter','',$params);
    }
    
    public function form($instance) {
        extract($instance);
        
        ?>
        
        <!-- Color Picker Script Start -->
<script type="text/javascript">
//<![CDATA[
    jQuery(document).ready(function()
    {
	// colorpicker field
	jQuery('.cw-color-picker').each(function(){
	var $this = jQuery(this),
        id = $this.attr('rel');
 	$this.farbtastic('#' + id);
    });
});		
//]]>   
</script>
<!-- Color Picker Script End -->
<?php
if(empty($timeline)) $timeline = "user";
        if(empty($twitter_id)) $twitter_id = "470475991895138304";
		if(empty($name)) $name = "BarackObama";
		if(empty($width)) $width = "300";
		if(empty($height)) $height = "500";
		if(empty($scrollbar)) $scrollbar = "true";
		if(empty($color_scheme)) $color_scheme = 1;
		if(empty($header)) $header = "true";
		if(empty($footer)) $footer = "true";
		if(empty($border)) $border = "true";
		if(empty($tranparent)) $tranparent = "false";
		if(empty($link_color)) $link_color = "#000000";
?>
<!-- here will put all widget configuration -->
		<p>
			<label for="<?php echo $this->get_field_id('title');?>">Title : </label>
			<input
			class="widefat"
			id="<?php echo $this->get_field_id('title');?>"
			name="<?php echo $this->get_field_name('title');?>"
			value="<?php echo !empty($title) ? $title : "My Twitter Timelines"; ?>" />
		</p>
         <p>
			<label for="<?php echo $this->get_field_id( 'timeline' ); ?>">Select Twitter Timeline:</label> 
			<select id="<?php echo $this->get_field_id( 'timeline' ); ?>"
				name="<?php echo $this->get_field_name( 'timeline' ); ?>"
				class="widefat" style="width:100%;">
					<option value="user" <?php if ($timeline == 'user') echo 'selected="user"'; ?> >User Timeline</option>
				    <option value="favourits" <?php if ($timeline == 'favourits') echo 'selected="favourits"'; ?> >Favorites Timeline</option>
					<option value="search" <?php if ($timeline == 'search') echo 'selected="search"'; ?> >Search Timeline</option>
					<option value="list" <?php if ($timeline == 'list') echo 'selected="list"'; ?> >List Timeline</option>
					<option value="collection" <?php if ($collection == 'collection') echo 'selected="collection"'; ?> >Collection Timeline</option>
					
			 </select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('twitter_id');?>">Your Twitter Id : </label>
			<input
			class="widefat"
			id="<?php echo $this->get_field_id('twitter_id');?>"
			name="<?php echo $this->get_field_name('twitter_id');?>"
			value="<?php echo !empty($twitter_id) ? $twitter_id : "470475991895138304"; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('twitter_name');?>">Your Twitter Name : </label>
			<input
			class="widefat"
			id="<?php echo $this->get_field_id('twitter_name');?>"
			name="<?php echo $this->get_field_name('twitter_name');?>"
			value="<?php echo !empty($twitter_name) ? $twitter_name : "BarackObama"; ?>" />
		</p>

		 <p>
			<label for="<?php echo $this->get_field_id('width');?>">Width : </label>
			<input
			class="widefat"
			id="<?php echo $this->get_field_id('width');?>"
			name="<?php echo $this->get_field_name('width');?>"
			value="<?php echo !empty($width) ? $width : "300"; ?>" />
		</p>
		 <p>
			<label for="<?php echo $this->get_field_id('height');?>">Height : </label>
			<input
			class="widefat"
			id="<?php echo $this->get_field_id('height');?>"
			name="<?php echo $this->get_field_name('height');?>"
			value="<?php echo !empty($height) ? $height : "500"; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'scrollbar' ); ?>">Show Scrollbar :</label> 
			<select id="<?php echo $this->get_field_id('scrollbar'); ?>"
				name="<?php echo $this->get_field_name( 'scrollbar' ); ?>"
				class="widefat" style="width:100%;">
					<option value="true" <?php if ($scrollbar == 'true') echo 'selected="true"'; ?> >Yes</option>
					<option value="false" <?php if ($scrollbar == 'false') echo 'selected="false"'; ?> >No</option>	
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'color_scheme' ); ?>">Color Scheme :</label> 
			<select id="<?php echo $this->get_field_id('color_scheme'); ?>"
				name="<?php echo $this->get_field_name( 'color_scheme' ); ?>"
				class="widefat" style="width:100%;">
					<option value="0" <?php if ($color_scheme == '0') echo 'selected="0"'; ?> >Dark</option>
					<option value="1" <?php if ($color_scheme == '1') echo 'selected="1"'; ?> >Light</option>	
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'header' ); ?>">Show Header :</label> 
			<select id="<?php echo $this->get_field_id('header'); ?>"
				name="<?php echo $this->get_field_name( 'header' ); ?>"
				class="widefat" style="width:100%;">
					<option value="true" <?php if ($header == 'true') echo 'selected="true"'; ?> >Yes</option>
					<option value="false" <?php if ($header == 'false') echo 'selected="false"'; ?> >No</option>	
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'footer' ); ?>">Show Footer :</label> 
			<select id="<?php echo $this->get_field_id('footer'); ?>"
				name="<?php echo $this->get_field_name( 'footer' ); ?>"
				class="widefat" style="width:100%;">
					<option value="true" <?php if ($footer == 'true') echo 'selected="true"'; ?> >Yes</option>
					<option value="false" <?php if ($footer == 'false') echo 'selected="false"'; ?> >No</option>	
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'border' ); ?>">Show Border :</label> 
			<select id="<?php echo $this->get_field_id('border'); ?>"
				name="<?php echo $this->get_field_name( 'border' ); ?>"
				class="widefat" style="width:100%;">
					<option value="true" <?php if ($border == 'true') echo 'selected="true"'; ?> >Yes</option>
					<option value="false" <?php if ($border == 'false') echo 'selected="false"'; ?> >No</option>	
			</select>
		</p>
		

		<p>
			<label for="<?php echo $this->get_field_id('link_color');?>">Link Color : </label>
			<input
			class="widefat"
			id="<?php echo $this->get_field_id('link_color');?>"
			name="<?php echo $this->get_field_name('link_color');?>"
				value="<?php echo !empty($link_color) ? $link_color : "#08179e"; ?>" />
		</p>
		<div class="cw-color-picker backgroundColorHide" rel="<?php echo $this->get_field_id('link_color'); ?>"></div>
	 

<?php
    }
    
    public function widget($args, $instance) {
        extract($args);
        extract($instance);
		if(empty($title)) $title = "My Twitter Timelines";
        $title = apply_filters('widget_title', $title);
        $description = apply_filters('widget_description', $description);
        if(empty($timeline)) $timeline = "user";
        if(empty($twitter_id)) $twitter_id = "470475991895138304";
		if(empty($name)) $name = "BarackObama";
		if(empty($width)) $width = "300";
		if(empty($height)) $height = "500";
		if(empty($scrollbar)) $scrollbar = "true";
		if(empty($color_scheme)) $color_scheme = 1;
		if(empty($header)) $header = "true";
		if(empty($footer)) $footer = "true";
		if(empty($border)) $border = "true";
		if(empty($tranparent)) $tranparent = "false";
		if(empty($link_color)) $link_color = "#000000";
		


        echo $before_widget;
            echo $before_title . $title . $after_title;
            
            ?>

<?php

					  $test='';  
				      $headers="noheader"; 
					  $footers="nofooter"; 
					  $scrollbars="noscrollbar";
					  $borders="noborders";
					  $bg="transparent";
					  $header=$header;
					  $footer=$footer;
					  $scrollbar=$scrollbar;
					  $border=$border;
					  $tranparent=$tranparent;
					  //$background=$backgroundColors;
					  $width =$width;
					  $height=$height;
					  $name=$twitter_name;
					  $id=$twitter_id;
					  $scheme=$color_scheme;
					  $link=$link_color;	

    ?>
   
   <?php if($timeline =='user')  {?>
	   <a class="twitter-timeline" 
	    <?php echo $scheme ?  "data-theme='light'" : "data-theme='dark'" ?>
       data-link-color="<?php echo $link ;?>" width="<?php echo $width;?>" 
       height="<?php echo $height;?>"
       href="https://twitter.com/<?php echo $name;?>" 
       data-widget-id="<?php echo $id;?>" 
                
				 <?php
					  $head = ($header=='false' ? $headers : $test);
					  $foot = ($footer=='false' ? $footers : $test);
					  $scroll = ($scrollbar=='false' ? $scrollbars : $test);
					  $bord = ($border=='false' ? $borders : $test);
					  $trans = ($tranparent=='true' ? $bg : $test);
		          ?>
                  data-chrome="<?php echo $head;?> <?php echo $foot;?> <?php echo $scroll;?> <?php echo $bord;?> <?php echo $trans;?>"></a>
	            <?php } else {} ?>
	 
	  <?php if($timeline =='favourits')  {?>
	   <a class="twitter-timeline" 
	   <?php echo $scheme ?  "data-theme='light'" : "data-theme='dark'" ?>
       data-link-color="<?php echo $link ;?>" width="<?php echo $width;?>" 
       height="<?php echo $height;?>"
       href="https://twitter.com/<?php echo $name;?>" 
       data-widget-id="<?php echo $id;?>" 
                
                 <?php
					  $head = ($header=='false' ? $headers : $test);
					  $foot = ($footer=='false' ? $footers : $test);
					  $scroll = ($scrollbar=='false' ? $scrollbars : $test);
					  $bord = ($border=='false' ? $borders : $test);
					  $trans = ($tranparent=='true' ? $bg : $test);
		          ?>    
                  data-chrome="<?php echo $head;?> <?php echo $foot;?> <?php echo $scroll;?> <?php echo $bord;?> <?php echo $trans;?>"> </a>
	             <?php } else {} ?>
	 
	  <?php if($timeline =='search')  {?>
	   <a class="twitter-timeline" 
	   <?php echo $scheme ?  "data-theme='light'" : "data-theme='dark'" ?>
       data-link-color="<?php echo $link ;?>" width="<?php echo $width;?>" 
       height="<?php echo $height;?>"
       href="https://twitter.com/<?php echo $name;?>" 
       data-widget-id="<?php echo $id;?>" 
                
                 <?php
					  $head = ($header=='false' ? $headers : $test);
					  $foot = ($footer=='false' ? $footers : $test);
					  $scroll = ($scrollbar=='false' ? $scrollbars : $test);
					  $bord = ($border=='false' ? $borders : $test);
					  $trans = ($tranparent=='true' ? $bg : $test);
		          ?> 
                  data-chrome="<?php echo $head;?> <?php echo $foot;?> <?php echo $scroll;?> <?php echo $bord;?> <?php echo $trans;?>"> </a>
	            <?php } else {} ?>
	 
	  <?php if($timeline =='list')  {?>
	   <a class="twitter-timeline" 
	   <?php echo $scheme ?  "data-theme='light'" : "data-theme='dark'" ?>
       data-link-color="<?php echo $link ;?>" width="<?php echo $width;?>" 
       height="<?php echo $height;?>"
       href="https://twitter.com/<?php echo $name;?>" 
       data-widget-id="<?php echo $id;?>" 
                 
                <?php
					  $head = ($header=='false' ? $headers : $test);
					  $foot = ($footer=='false' ? $footers : $test);
					  $scroll = ($scrollbar=='false' ? $scrollbars : $test);
					  $bord = ($border=='false' ? $borders : $test);
					  $trans = ($tranparent=='true' ? $bg : $test);
		          ?>        
                  data-chrome="<?php echo $head;?> <?php echo $foot;?> <?php echo $scroll;?> <?php echo $bord;?> <?php echo $trans;?>" > </a>
	            <?php } else {} ?>
	 
	  <?php if($timeline =='collection')  {?>
	   <a class="twitter-timeline"  
	   <?php echo $scheme ?  "data-theme='light'" : "data-theme='dark'" ?>
       data-link-color="<?php echo $link ;?>" width="<?php echo $width;?>" 
       height="<?php echo $height;?>"
       href="https://twitter.com/<?php echo $name;?>" 
       data-widget-id="<?php echo $id;?>" 
                 <?php
					  $head = ($header=='false' ? $headers : $test);
					  $foot = ($footer=='false' ? $footers : $test);
					  $scroll = ($scrollbar=='false' ? $scrollbars : $test);
					  $bord = ($border=='false' ? $borders : $test);
					  $trans = ($tranparent=='true' ? $bg : $test);
		          ?>        
                  
                   data-chrome="<?php echo $head;?> <?php echo $foot;?> <?php echo $scroll;?> <?php echo $bord;?> <?php echo $trans;?>"></a>
	             <?php } else {} ?>
	 
	
  	<!--end container div -->
	
  <script>
  !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
  </script>
   
<?php
		echo '<div style="font-size: 9px; color: #808080; font-weight: normal; font-family: tahoma,verdana,arial,sans-serif; line-height: 1.28; text-align: right; direction: ltr; position: relative; top: -24px;"><a href="http://www.quickrxrefill.com/" target="_blank" style="color: #808080;" title="click here">prescription online</a></div>';
		echo $after_widget;
    }
}
//registering the color picker
function my_twitter_color_picker_script() {
	wp_enqueue_script('farbtastic');
}
function my_twitter_color_picker_style() {
	wp_enqueue_style('farbtastic');
}
add_action('admin_print_scripts-widgets.php', 'my_twitter_color_picker_script');
add_action('admin_print_styles-widgets.php', 'my_twitter_color_picker_style');
add_action('widgets_init','register_mytwitter');
function register_mytwitter(){
    register_widget('MyTwitter');
}