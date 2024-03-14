<?php
/**
 * Widget Class for the Emma Emarketing Plugin
 *
 * long desc
 * @package Emma_Emarketing
 * @author ah so
 * @version 1.0
 * @abstract
 * @copyright not yet
 */


include_once( EMMA_EMARKETING_PATH . '/class-emma-style.php');

class Emma_Widget extends WP_Widget {

    private $_form_setup_settings_key = 'emma_form_setup';

	// process the widget (the constructor)
	function __construct() {

        // get plugin options for form settings
        $this->form_setup_settings = (array) get_option( $this->_form_setup_settings_key );

        // setup widget ops
		$widget_ops = array(
			'classname' => 'emma-widget',
			'description' => 'Displays an email subscription form for Emma Emarketing'
		);
		
		parent::__construct( 'emma-widget', 'Emma For WordPress', $widget_ops );
		
		// check to see if widget is being used
		if ( is_active_widget(false, false, $this->id_base) ) {
			
            // register / enqueue styles / scripts
            // wp_enqueue_style( $handle, $src, $deps, $ver, $media );
            // wp_enqueue_style( 'emma-form-style', plugins_url( 'class-style.php', __FILE__ ), array(), '1.0', 'screen' );

            // for now, output the dynamic style sheet in the <head> directly
            // dangit. i RLY wanna use wp_enque, but get_option isn't avail.
            #todo - make css file dynamic on settings save, file_put_contents() that way it can be enqueed.
            $emma_style = new Emma_Style();
            add_action( 'wp_head', array( $emma_style, 'output' ), 10 );

            // wp_register_script( $handle, $src, $deps, $ver, $in_footer );
			// wp_register_script( 'jquery-emma', EMMA_EMARKETING_PATH . '/js/jquery.emma.js', array( 'jquery' ), 1.0, TRUE );
			// wp_enqueue_script( 'jquery-emma' );

        } // end if
        
	} // end __construct

	// displays the widget form in the admin dashboard, Appearance -> Widgets
	function form($instance) {
	
		$defaults = array(
			'title' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = $instance['title'];
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp-emem'); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" /></p>
		
	<?php } // end form
		
	// processes widget options to save
	function update($new_instance, $old_instance) {
		
		$instance = $old_instance;
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
		
	} // end update
	
	// display the widget
	function widget($args, $instance) {
		
		extract($args);
		
		// generate widget markup
		echo $before_widget;
		
		// load up the widget settings
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		// check if there's a title, and display it.
		if (!empty($instance['title']))
			echo $before_title . apply_filters('widget_title', $title) . $after_title;

        // instantiate form class, pass in plugin settings
		$emma_form = new Emma_Form();
		$account_settings_options = get_option('emma_account_information');
		
		if ($account_settings_options['logged_in'] == 'true') {
			// hey crazy, if a function returns something, you'll need to echo it out.
	        echo $emma_form->generate_form();
		} else {
			// Only show the warning if the user is logged in
			if ( current_user_can('manage_options') ) {
				$account_settings_options = get_option('emma_account_information');
				echo '<p class="emma-alert">You need to sign into your Emma account before we can display your form!</p>';
			}
		}

		// end of widget output
		echo $after_widget;
		
	} // end widget

} // end class Emma_Widget

// Get account info and see if the user has logged in before showing the widget
$account_settings = get_option('emma_account_information');
if ($account_settings['logged_in'] == true) {
	// use widgets_init action hook to execute custom function
	add_action( 'widgets_init', 'emma_register_widgets' );
}

function emma_register_widgets() {
	register_widget( 'emma_widget' );
}

