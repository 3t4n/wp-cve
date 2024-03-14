<?php
///////////////////////////////////////// Creating the widget
class tgchannel_widget extends WP_Widget
	{
			function __construct()
				{
						parent::__construct(
						'tgchannel_widget',
						__('TGchannel Widget', 'tgchannel_widget_domain') ,
						array(
									'description' => __('show your channel as a widget', 'tgchannel_widget_domain') ,
						));
				}

			// Creating widget front-end

			public function widget($args, $instance)
				{
					include('css/style.css.php');	
						$title = apply_filters('widget_title', $instance['title']);
						// before and after widget arguments are defined by themes
						echo $args['before_widget'];
						if (!empty($title)) echo $args['before_title'] . $title . $args['after_title'];
						// widget body
?>	


		     <div class="tgchannelwdgbody">	
		<div id = "tgwidget" class="tghead">
	    <h3>@<?php echo esc_attr(get_option('TG_Channel_name')); ?></h3>
	    </div>	
		
	    <div id="tgwidgetbox" class="tgwidgetboxed">
		
		<div id="insidertg">

	    <?php
	    $sendResponseUrl = "https://www.ttmgabot.com/TGHook/mypostid.php?".get_option('TG_Channel_name')."&".get_option('siteurl');
        $mypostid =  file_get_contents($sendResponseUrl);
	
	    if ($mypostid == ''){
		$mypostid = get_option('TG_Channel_postid');
	    }
						$tgpostid = $mypostid;					
						$fivepost = $tgpostid - 5;
        ?>
	    <p id="channel" style="display:none;"><?php
						echo get_option('TG_Channel_name'); ?>/</p>
	    <p id="postnum" style="display:none;"><?php
						echo $fivepost; ?></p>
	    <?php
						$i = $tgpostid;
						while ($i >= $fivepost)
							{
									echo '<script async src="https://telegram.org/js/telegram-widget.js" data-telegram-post="' . get_option('TG_Channel_name') . '/' . $fivepost . '" data-width="100%" ></script>';
									$fivepost = $fivepost + 1;
							}

        ?>
		</div>
	    </div>
	
		
	    <div id = "joinchannel" class="tgfooter">
	    <a  target="_blank" href="https://t.me/<?php
						echo get_option('TG_Channel_name'); ?>"><?php
						echo __('Join Channel', 'TGchannel'); ?></a>
	    </div>
		</div>
	
	
	
	<?php	
						echo $args['after_widget'];
				}

			// Widget Backend

			public

			function form($instance)
				{
						if (isset($instance['title']))
							{
									$title = $instance['title'];
							}
						else
							{
									$title = __('New title', 'tgchannel');
							}

						// Widget admin form

    ?>
        <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
        <input class="widefat" id="<?php
						echo $this->get_field_id('title'); ?>" name="<?php
						echo $this->get_field_name('title'); ?>" type="text" value="<?php
						echo esc_attr($title); ?>" />
        </p>
    <?php
				}

			// Updating widget replacing old instances with new

			public

			function update($new_instance, $old_instance)
				{
						$instance = array();
						$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
						return $instance;
				}
	} // Class tgchannel_widget ends here

	
	
	
	
	
	
	
	
	
	
	
// shortcode

function TGchannel_shortcode()
	{
			// shortcode body
include('css/style.css.php');			
    ?>	
    <div class="tgchannelwdgbody">	

		<div id = "tg_short_head" class="tghead">
	<h3><?php
			echo esc_attr(get_option('TG_Channel_name')); ?></h3>
	</div>	
		
	<div id="tgbox" class="tgboxed">
    <div id="insidetgbox">
	<?php
	$sendResponseUrl = "https://www.ttmgabot.com/TGHook/mypostid.php?".get_option('TG_Channel_name')."&".get_option('siteurl');
    $mypostid =  file_get_contents($sendResponseUrl);
	
		if ($mypostid == ''){
		$mypostid = get_option('TG_Channel_postid');
	}
	
						$tgpostid = $mypostid;
			$fivepost = $tgpostid - 5;
     ?>
	 
	<p id="channel" style="display:none;"><?php
			echo get_option('TG_Channel_name'); ?>/</p>
	<p id="postnum" style="display:none;"><?php
			echo $fivepost; ?></p>
	<?php
			$i = $tgpostid;
			while ($i >= $fivepost)
				{
						echo '<script async src="https://telegram.org/js/telegram-widget.js" data-telegram-post="' . get_option('TG_Channel_name') . '/' . $fivepost . '" data-width="100%" ></script>';
						$fivepost = $fivepost + 1;
				}

    ?>
</div>
	</div>
	
		
	<div id = "tg_short_joinchannel" class="tgfooter">
	  <a target="_blank" href="https://t.me/<?php
			echo get_option('TG_Channel_name'); ?>"><?php
			echo __('Join Channel', 'TGchannel'); ?></a>
	</div>
    </div>

	<?php
	}	