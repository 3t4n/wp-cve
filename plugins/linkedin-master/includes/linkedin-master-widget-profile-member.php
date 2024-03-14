<?php
//Hook Widget
add_action( 'widgets_init', 'linkedin_master_widget_profile_member' );
//Register Widget
function linkedin_master_widget_profile_member() {
register_widget( 'linkedin_master_widget_profile_member' );
}

class linkedin_master_widget_profile_member extends WP_Widget {
	function __construct(){
	$widget_ops = array( 'classname' => 'LinkedIn Master Basic Member Profile', 'description' => __('LinkedIn Master Basic Member Profile, allows you to display your linkedin personal profile.', 'linkedin_master') );
	$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'linkedin_master_widget_profile_member' );
	parent::__construct( 'linkedin_master_widget_profile_member', __('LinkedIn Master Basic Member Profile', 'linkedin_master'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		//Our variables from the widget settings.
		$linkedin_title = isset( $instance['linkedin_title'] ) ? $instance['linkedin_title'] :false;
		$linkedin_title_new = isset( $instance['linkedin_title_new'] ) ? $instance['linkedin_title_new'] :false;
		$show_linkedinprofile = isset( $instance['show_linkedinprofile'] ) ? $instance['show_linkedinprofile'] :false;
		$publicurl = $instance['publicurl'];
		$vanatyname = $instance['vanatyname'];
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
	//Prepare lang from options
	if(is_multisite()){
		$linkedin_master_system_wide_language = get_blog_option($blog_id, 'linkedin_master_system_wide_language');
	}
	else{
		$linkedin_master_system_wide_language = get_option('linkedin_master_system_wide_language');
	}
	if(empty($linkedin_master_system_wide_language)){
		$linkedin_master_system_wide_language = 'en_US';
	}

	//Prepare Url
	if(empty($publicurl)){
		$publicurl = 'https://www.linkedin.com/in/pedro-alves-techgasp/';
	}
	//Prepare Vanati
	if(empty($vanatyname)){
		$vanatyname = 'pedro-alves-techgasp';
	}
	//RENDER WIDGET
	if ( $show_linkedinprofile ){
		echo '<div class="LI-profile-badge"  data-version="v1" data-size="medium" data-locale="'.$linkedin_master_system_wide_language.'" data-type="horizontal" data-theme="light" data-vanity="'.$vanatyname.'"><a class="LI-simple-link" href="'.$publicurl.'?trk=profile-badge"></a></div>';
			
	}
	else {
	}
	echo $after_widget;
	}
	//Update the widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		//Strip tags from title and name to remove HTML
		$instance['linkedin_title'] = strip_tags( $new_instance['linkedin_title'] );
		$instance['linkedin_title_new'] = $new_instance['linkedin_title_new'];
		$instance['show_linkedinprofile'] = $new_instance['show_linkedinprofile'];
		$instance['publicurl'] = $new_instance['publicurl'];
		$instance['vanatyname'] = $new_instance['vanatyname'];
		return $instance;
	}
	function form( $instance ) {
	$plugin_master_name = constant('LINKEDIN_MASTER_NAME');
	//Set up some default widget settings.
	$defaults = array( 'linkedin_title_new' => __('LinkedIn Master', 'linkedin_master'), 'linkedin_title' => true, 'linkedin_title_new' => false, 'show_linkedinprofile' => false, 'publicurl' => false, 'vanatyname' => false );
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
	<label for="<?php echo $this->get_field_id( 'linkedin_title_new' ); ?>"><?php _e('Change Title:', 'linkedin_master'); ?></label>
	<br>
	<input id="<?php echo $this->get_field_id( 'linkedin_title_new' ); ?>" name="<?php echo $this->get_field_name( 'linkedin_title_new' ); ?>" value="<?php echo $instance['linkedin_title_new']; ?>" style="width:auto;" />
	</p>
<div style="background: url(<?php echo plugins_url('images/techgasp-hr.png', dirname(__FILE__)); ?>) repeat-x; height: 10px"></div>
	<p>
	<img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" />
	&nbsp;
	<input type="checkbox" <?php checked( (bool) $instance['show_linkedinprofile'], true ); ?> id="<?php echo $this->get_field_id( 'show_linkedinprofile' ); ?>" name="<?php echo $this->get_field_name( 'show_linkedinprofile' ); ?>" />
	<label for="<?php echo $this->get_field_id( 'show_linkedinprofile' ); ?>"><b><?php _e('Show LinkedIn Profile', 'linkedin_master'); ?></b></label></br>
	</p>
	<label for="<?php echo $this->get_field_id( 'publicurl' ); ?>"><?php _e('insert Profile Url:', 'linkedin_master'); ?></label></br>
	<input id="<?php echo $this->get_field_id( 'publicurl' ); ?>" name="<?php echo $this->get_field_name( 'publicurl' ); ?>" value="<?php echo $instance['publicurl']; ?>" style="width:100%;" />
	<div class="description">Example: <strong>https://www.linkedin.com/in/my-linkedin-profile-url/</strong></div>
	</p>
	<p>
	<label for="<?php echo $this->get_field_id( 'vanatyname' ); ?>"><?php _e('insert Vanaty Name:', 'linkedin_master'); ?></label></br>
	<input id="<?php echo $this->get_field_id( 'vanatyname' ); ?>" name="<?php echo $this->get_field_name( 'vanatyname' ); ?>" value="<?php echo $instance['vanatyname']; ?>" style="width:100%;" />
	<div class="description">Last part of your Url, example: <strong>my-linkedin-profile-url</strong></div>
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
