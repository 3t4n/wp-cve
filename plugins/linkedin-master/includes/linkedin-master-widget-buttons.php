<?php
//Hook Widget
add_action( 'widgets_init', 'linkedin_master_widget_buttons' );
//Register Widget
function linkedin_master_widget_buttons() {
register_widget( 'linkedin_master_widget_buttons' );
}

class linkedin_master_widget_buttons extends WP_Widget {
	function __construct(){
	$widget_ops = array( 'classname' => 'LinkedIn Master Buttons', 'description' => __('LinkedIn Master Buttons allows you to show the Share Page on LinkedIn or Follow Company on LinkedIn.', 'linkedin_master') );
	$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'linkedin_master_widget_buttons' );
	parent::__construct( 'linkedin_master_widget_buttons', __('LinkedIn Master Buttons', 'linkedin_master'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		//Our variables from the widget settings.
		$linkedin_title = isset( $instance['linkedin_title'] ) ? $instance['linkedin_title'] :false;
		$linkedin_title_new = isset( $instance['linkedin_title_new'] ) ? $instance['linkedin_title_new'] :false;
		$show_linkedinbutton_share = isset( $instance['show_linkedinbutton_share'] ) ? $instance['show_linkedinbutton_share'] :false;
		$show_linkedinbutton_follow = isset( $instance['show_linkedinbutton_follow'] ) ? $instance['show_linkedinbutton_follow'] :false;
		$linkedinbutton_follow_count = isset( $instance['linkedinbutton_follow_count'] ) ? $instance['linkedinbutton_follow_count'] :false;
		$linkedinbutton_follow = $instance['linkedinbutton_follow'];
		echo $before_widget;
		
// Display the widget title
	if ( $linkedin_title ){
		if (empty ($linkedin_title_new)){
			$linkedin_title_new = constant('LINKEDIN_MASTER_NAME');
			echo $before_title . $linkedin_title_new . $after_title;
		}
		else{
			echo $before_title . $linkedin_title_new . $after_title;
		}
	}
	else{
	}
	//Prepare Share URL
	if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'){
		$linkedinurissl = 'https://';
	}
	else {
		$linkedinurissl = 'http://';
	}
	@$linkedinmaster_url = $linkedinurissl.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	//RENDER WIDGETS
	//Display LinkedIn Share Website Button
	if ( $show_linkedinbutton_share ){
		$show_linkedinbutton_share_create = '<div style="overflow:visible !important">' . 
											'<script type="IN/Share" data-url="'.$linkedinmaster_url.'"></script>' .
											'</div>';
	}
	else{
		$show_linkedinbutton_share_create = false;
	}
//Display LinkedIn Share Website Button
	if ( $show_linkedinbutton_follow ){
					if ( $linkedinbutton_follow_count ){
				$linkedinbutton_follow_count_create = 'data-counter="right"';
			}
			else{
				$linkedinbutton_follow_count_create = '';
			}
		$show_linkedinbutton_follow_create = '&nbsp;&nbsp;<script type="IN/FollowCompany" data-id="'.$linkedinbutton_follow.'" '.$linkedinbutton_follow_count_create.'"></script>';
	}
	else{
		$show_linkedinbutton_follow_create = false;
	}
	echo '<div style="display:flex;">' .
		$show_linkedinbutton_share_create . $show_linkedinbutton_follow_create .
		'</div>' .
	$after_widget;
	}
	//Update the widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		//Strip tags from title and name to remove HTML
		$instance['linkedin_title'] = strip_tags( $new_instance['linkedin_title'] );
		$instance['linkedin_title_new'] = $new_instance['linkedin_title_new'];
		$instance['show_linkedinbutton_share'] = $new_instance['show_linkedinbutton_share'];
		$instance['show_linkedinbutton_follow'] = $new_instance['show_linkedinbutton_follow'];
		$instance['linkedinbutton_follow_count'] = $new_instance['linkedinbutton_follow_count'];
		$instance['linkedinbutton_follow'] = $new_instance['linkedinbutton_follow'];
		return $instance;
	}
	function form( $instance ) {
	$plugin_master_name = constant('LINKEDIN_MASTER_NAME');
	//Set up some default widget settings.
	$defaults = array( 'linkedin_title_new' => __('LinkedIn Master', 'linkedin_master'), 'linkedin_title' => true, 'linkedin_title_new' => false, 'show_linkedinbutton_share' => false, 'show_linkedinbutton_follow' => false, 'linkedinbutton_follow_count' => false, 'linkedinbutton_follow' => false );
	$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<br>
		<b>Check the buttons to be displayed:</b>
	<p>
	<img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" />
	&nbsp;
	<input type="checkbox" <?php checked( (bool) $instance['linkedin_title'], true ); ?> id="<?php echo $this->get_field_id( 'linkedin_title' ); ?>" name="<?php echo $this->get_field_name( 'linkedin_title' ); ?>" />
	<label for="<?php echo $this->get_field_id( 'linkedin_title' ); ?>"><b><?php _e('Display Widget Title', 'linkedin_master'); ?></b></label></br>
	</p>
	<p>
	<label for="<?php echo $this->get_field_id( 'linkedin_title_new' ); ?>"><?php _e('Change Title:', 'linkedin_master'); ?></label><br>
	<input id="<?php echo $this->get_field_id( 'linkedin_title_new' ); ?>" name="<?php echo $this->get_field_name( 'linkedin_title_new' ); ?>" value="<?php echo $instance['linkedin_title_new']; ?>" style="width:100%;" />
	</p>
<div style="background: url(<?php echo plugins_url('images/techgasp-hr.png', dirname(__FILE__)); ?>) repeat-x; height: 10px"></div>
	<p>
	<img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" />
	&nbsp;
	<input type="checkbox" <?php checked( (bool) $instance['show_linkedinbutton_share'], true ); ?> id="<?php echo $this->get_field_id( 'show_linkedinbutton_share' ); ?>" name="<?php echo $this->get_field_name( 'show_linkedinbutton_share' ); ?>" />
	<label for="<?php echo $this->get_field_id( 'show_linkedinbutton_share' ); ?>"><b><?php _e('LinkedIn Share Website Button', 'linkedin_master'); ?></b></label></br>
	</p>
<div style="background: url(<?php echo plugins_url('images/techgasp-hr.png', dirname(__FILE__)); ?>) repeat-x; height: 10px"></div>
	<p>
	<img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" />
	&nbsp;
	<input type="checkbox" <?php checked( (bool) $instance['show_linkedinbutton_follow'], true ); ?> id="<?php echo $this->get_field_id( 'show_linkedinbutton_follow' ); ?>" name="<?php echo $this->get_field_name( 'show_linkedinbutton_follow' ); ?>" />
	<label for="<?php echo $this->get_field_id( 'show_linkedinbutton_follow' ); ?>"><b><?php _e('LinkedIn Follow Company', 'linkedin_master'); ?></b></label></br>
	</p>
	<p>
	<img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" />
	&nbsp;
	<input type="checkbox" <?php checked( (bool) $instance['linkedinbutton_follow_count'], true ); ?> id="<?php echo $this->get_field_id( 'linkedinbutton_follow_count' ); ?>" name="<?php echo $this->get_field_name( 'linkedinbutton_follow_count' ); ?>" />
	<label for="<?php echo $this->get_field_id( 'linkedinbutton_follow_count' ); ?>"><b><?php _e('Activate Button Bubble Count', 'linkedin_master'); ?></b></label></br>
	</p>
	<p>
	<label for="<?php echo $this->get_field_id( 'linkedinbutton_follow' ); ?>"><?php _e('LinkedIn Company Name:', 'linkedin_master'); ?></label></br>
	<input id="<?php echo $this->get_field_id( 'linkedinbutton_follow' ); ?>" name="<?php echo $this->get_field_name( 'linkedinbutton_follow' ); ?>" value="<?php echo $instance['linkedinbutton_follow']; ?>" style="width:100%;" />
	<div class="description">As a company page administrator, your Company ID can be retrieved by navigating to the admin section of your company page. For example, the LinkedIn Company Admin Page is https://www.linkedin.com/company/1337/admin/. We will use the Company ID 1337 in our example.</div>
	</p>
<div style="background: url(<?php echo plugins_url('images/techgasp-hr.png', dirname(__FILE__)); ?>) repeat-x; height: 10px"></div>
	<p>
	<div class="description">Remember to visit Linkedin Master Settings page to Activate Linkedin system wide script and to override your Linkedin Language.</div>
	</p>
<div style="background: url(<?php echo plugins_url('images/techgasp-hr.png', dirname(__FILE__)); ?>) repeat-x; height: 10px"></div>
	<p>
	<img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; width:18px; vertical-align:middle;" />
	&nbsp;
	<b><?php echo $plugin_master_name; ?> Website</b>
	</p>
	<p><a class="button-secondary" href="https://wordpress.techgasp.com/linkedin-master/" target="_blank" title="<?php echo $plugin_master_name; ?> Info Page">Info Page</a> <a class="button-secondary" href="https://wordpress.techgasp.com/linkedin-master-documentation/" target="_blank" title="<?php echo $plugin_master_name; ?> Documentation">Documentation</a> <a class="button-primary" href="https://wordpress.techgasp.com/linkedin-master/" target="_blank" title="Visit Website">Get Add-ons</a></p>
	<?php
	}
 }
?>
