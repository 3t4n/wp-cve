<?php
/**
 * easy-share-solution-widget
 *
 * @link              http://codecanyon.net/user/expert-wp
 * @since             1.0.0
 * @package           Easy share solution
 *
 */

/**
 * Core class used to implement a Easy share solution widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class born_share_widget extends WP_Widget {

	/**
	 * Sets up a Easy-share widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$share_widget = array('classname' => 'share_wid', 'description' => __( 'A list of social share buttons.','easy-share-solution') );
		parent::__construct('shares', __('Easy share solution','easy-share-solution'), $share_widget);
	}

	/**
	 * Outputs the content for the  easy-share-solution widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the  easy-share-solution widget instance.
	 */
	public function widget( $args, $instance ) {

		/**
		 * Filter the widget title.
		 *
		 * @since 2.6.0
		 *
		 * @param string $title    The widget title. Default 'Share buttons'.
		 * @param array  $instance An array of the widget's settings.
		 * @param mixed  $id_base  The widget ID.
		 */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Share buttons','easy-share-solution' ) : $instance['title'], $instance, $this->id_base );

		$sharebtn = empty( $instance['sharebtn'] ) ? 'share_one' : $instance['sharebtn'];
		$btnType = empty( $instance['btnType'] ) ? 'button_one' : $instance['btnType'];

		/**
		 * Filter the arguments for the  easy-share-solution widget.
		 *
		 * @since 2.8.0
	
		 */
	

	
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			
		?>
		
			 <div id="bshare_widget" class="shar-button-widget <?php if($btnType=='button_two'): ?>widget-round-btn<?php endif; ?>">
			<?php  if ( $sharebtn == 'share_one' ): ?>
				<button class="button-popup share bshare-Facebook s_Facebook"><i class="icon-Facebook"></i></button>
				<button class="button-popup share bshare-Twitter s_Twitter"><i class="icon-Twitter"></i></button>
				<button class="button-popup share bshare-Googleplus s_Googleplus"><i class="icon-Googleplus"></i></button>
				<button class="button-popup share bshare-Linkedin s_Linkedin"><i class="icon-Linkedin"></i></button>
				<button class="button-popup share bshare-Pinterest s_Pinterest"><i class="icon-Pinterest"></i></button>
				<?php endif; ?>
				<?php  if ( $sharebtn == 'share_two' ): ?>
				<button class="button-popup share bshare-Facebook s_Facebook"><i class="icon-Facebook"></i></button>
				<button class="button-popup share bshare-Twitter s_Twitter"><i class="icon-Twitter"></i></button>
				<button class="button-popup share bshare-Googleplus s_Googleplus"><i class="icon-Googleplus"></i></button>
				<button class="button-popup share bshare-Linkedin s_Linkedin"><i class="icon-Linkedin"></i></button>
				<button class="button-popup share bshare-Pinterest s_Pinterest"><i class="icon-Pinterest"></i></button>
				<button class="button-popup share bshare-Buffer s_Buffer"><i class="icon-Buffer"></i></button>
				<button class="button-popup share bshare-Digg s_Digg"><i class="icon-Digg"></i></button>
				<button class="button-popup share bshare-Pocket s_Pocket"><i class="icon-Pocket"></i></button>
				<button class="button-popup share bshare-Tumblr s_Tumblr"><i class="icon-Tumblr"></i></button>
				<button class="button-popup share bshare-Blogger s_Blogger"><i class="icon-Blogger"></i></button>
				<button class="button-popup share bshare-Myspace s_Myspace"><i class="icon-Myspace"></i></button>
				<button class="button-popup share bshare-Delicious s_Delicious"><i class="icon-Delicious"></i></button>
				<button class="button-popup share bshare-Ok s_Ok"><i class="icon-Ok"></i></button>
				<button class="button-popup share bshare-Reddit s_Reddit"><i class="icon-Reddit"></i></button>
				<button class="button-popup share bshare-Aim s_Aim"><i class="icon-Aim"></i></button>
				<?php endif; ?>
				<?php  if ( $sharebtn == 'share_three' ): ?>
				<button class="button-popup share bshare-Facebook s_Facebook"><i class="icon-Facebook"></i></button>
				<button class="button-popup share bshare-Twitter s_Twitter"><i class="icon-Twitter"></i></button>
				<button class="button-popup share bshare-Googleplus s_Googleplus"><i class="icon-Googleplus"></i></button>
				<button class="button-popup share bshare-Linkedin s_Linkedin"><i class="icon-Linkedin"></i></button>
				<button class="button-popup share bshare-Pinterest s_Pinterest"><i class="icon-Pinterest"></i></button>
				<button class="button-popup share bshare-Buffer s_Buffer"><i class="icon-Buffer"></i></button>
				<button class="button-popup share bshare-Digg s_Digg"><i class="icon-Digg"></i></button>
				<button class="button-popup share bshare-Pocket s_Pocket"><i class="icon-Pocket"></i></button>
				<button class="button-popup share bshare-Tumblr s_Tumblr"><i class="icon-Tumblr"></i></button>
				<button class="button-popup share bshare-Blogger s_Blogger"><i class="icon-Blogger"></i></button>
				<button class="button-popup share bshare-Myspace s_Myspace"><i class="icon-Myspace"></i></button>
				<button class="button-popup share bshare-Delicious s_Delicious"><i class="icon-Delicious"></i></button>
				<button class="button-popup share bshare-Ok s_Ok"><i class="icon-Ok"></i></button>
				<button class="button-popup share bshare-Reddit s_Reddit"><i class="icon-Reddit"></i></button>
				<button class="button-popup share bshare-Aim s_Aim"><i class="icon-Aim"></i></button>
				<button class="button-popup share bshare-Wordpress s_Wordpress"><i class="icon-Wordpress"></i></button>
				<button class="button-popup share bshare-Friendfeed s_Friendfeed"><i class="icon-Friendfeed"></i></button>
				<button class="button-popup share bshare-Hackernews s_Hackernews"><i class="icon-Hackernews"></i></button>
				<button class="button-popup share bshare-Plurk s_Plurk"><i class="icon-Plurk"></i></button>
				<button class="button-popup share bshare-Stumbleupon s_Stumbleupon"><i class="icon-Stumbleupon"></i></button>
				<button class="button-popup share bshare-Box s_Box"><i class="icon-Box"></i></button>
				<button class="button-popup share bshare-Gmail s_Gmail"><i class="icon-Gmail"></i></button>
				<button class="button-popup share bshare-Instapaper s_Instapaper"><i class="icon-Instapaper"></i></button>
				<button class="button-popup share bshare-Yahoo s_Yahoo"><i class="icon-Yahoo"></i></button>
				<button class="button-popup share bshare-Vk s_Vk"><i class="icon-Vk"></i></button>
				<button class="button-popup share bshare-Diigo s_Diigo"><i class="icon-Diigo"></i></button>
				<button class="button-popup share bshare-Google s_Google"><i class="icon-Google"></i></button>
				<button class="button-popup share bshare-Amazon s_Amazon"><i class="icon-Amazon"></i></button>
				<button class="button-popup share bshare-Evernote s_Evernote"><i class="icon-Evernote"></i></button>
				<button class="button-popup share bshare-Viadeo s_Viadeo"><i class="icon-Viadeo"></i></button>
				<button class="button-popup share bshare-Mixi s_Mixi"><i class="icon-Mixi"></i></button>
				<button class="button-popup share bshare-Myworld s_Myworld"><i class="icon-Myworld"></i></button>
				<?php endif; ?>
			</div>
			<?php
		
			echo $args['after_widget'];
		
	}

	/**
	 * Handles updating settings for the current  easy-share-solution widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		if ( in_array( $new_instance['sharebtn'], array( 'share_one', 'share_two', 'share_three' ) ) ) {
			$instance['sharebtn'] = $new_instance['sharebtn'];
		} else {
			$instance['sharebtn'] = 'share_one';
		}
		if ( in_array( $new_instance['btnType'], array( 'button_one', 'button_two') ) ) {
			$instance['btnType'] = $new_instance['btnType'];
		} else {
			$instance['btnType'] = 'button_one';
		}


		return $instance;
	}

	/**
	 * Outputs the settings form for the  easy-share-solution widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'sharebtn' => 'share_one','btnType' => 'button_one', 'title' => '') );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:','easy-share-solution' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'btnType' ) ); ?>"><?php _e( 'Select button style:','easy-share-solution' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'btnType' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'btnType' ) ); ?>" class="widefat">
				<option value="button_one"<?php selected( $instance['btnType'], 'button_one' ); ?>><?php _e('Square buttons','easy-share-solution'); ?></option>
				<option value="button_two"<?php selected( $instance['btnType'], 'button_two' ); ?>><?php _e('Round buttons','easy-share-solution'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'sharebtn' ) ); ?>"><?php _e( 'Select button number:','easy-share-solution' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'sharebtn' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'sharebtn' ) ); ?>" class="widefat">
				<option value="share_one"<?php selected( $instance['sharebtn'], 'share_one' ); ?>><?php _e('Five share buttons','easy-share-solution'); ?></option>
				<option value="share_two"<?php selected( $instance['sharebtn'], 'share_two' ); ?>><?php _e('Fifteen share buttons','easy-share-solution'); ?></option>
				<option value="share_three"<?php selected( $instance['sharebtn'], 'share_three' ); ?>><?php _e( 'All share buttons','easy-share-solution' ); ?></option>
			</select>
		</p>
	
		<?php
	}

}
	/**
	 * Register  easy-share-solution-widget .
	 */

function bornForShare_widget_reg(){
	register_widget('born_share_widget');
}
add_action('widgets_init','bornForShare_widget_reg');