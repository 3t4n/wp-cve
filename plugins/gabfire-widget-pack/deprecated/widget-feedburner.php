<?php
if ( !defined('ABSPATH')) exit;
  
class gabfire_feedburner extends WP_Widget {

	function gabfire_feedburner() {
		$widget_ops = array( 'classname' => 'gabfire_feedburner_widget', 'description' => 'Feedburner Email Subscribe');
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'gabfire_feedburner_widget' );
		parent::__construct( 'gabfire_feedburner_widget', 'Gabfire: Feedburner Email Subscribe', $widget_ops, $control_ops);
	}	
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$user	= $instance['user'];
		$text	= $instance['text'];
		$bgcol	= $instance['bgcol'];
		$bordercol	= $instance['bordercol'];
		$textcol	= $instance['textcol'];
		$wstyle	= $instance['wstyle'];
		$a_text	= $instance['a_text'];
		$bgurl	= $instance['bgurl'];

			echo $before_widget;

				if ($wstyle == "small") {
					if ( $title ) {
						echo $before_title . $title . $after_title;
					}
					?>
					
					<form class="gabfire_f_widget" action="//feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('//feedburner.google.com/fb/a/mailverify?uri=<?php echo esc_attr( $user ); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
						<fieldset <?php if ( $bgcol ) { echo 'style="background:' . esc_attr( $bgcol ) .';border:1px solid ' . esc_attr( $bordercol ) .';"'; } ?>>
							<input type="email" style="width:80%;color:<?php echo esc_attr( $textcol ); ?>;<?php if ( $bgcol ) { echo 'background:' . esc_attr( $bgcol ); } ?>" class="text" name="email" value="<?php echo esc_attr( $text ); ?>" onfocus="if (this.value == '<?php echo esc_attr( $text ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo esc_attr( $text ); ?>';}" />
							<input type="hidden" value="<?php echo esc_attr( $user ); ?>" name="uri" />
							<input type="hidden" name="loc" value="<?php bloginfo('language'); ?>"/>
							<input type="image" class="feedburner_submit" src="<?php echo get_template_directory_uri(); ?>/framework/images/add.png" alt="<?php _e('Subscribe', 'gabfire-widget-pack'); ?>" />
						</fieldset>
					</form>
				<?php } else { ?>
					
					<div class="news-signup" <?php if ( $bgurl ) { echo 'style="background:url(' . esc_attr( $bgurl ) .') repeat top center"'; } ?>>	
						<h3 class="widgettitle"><?php echo esc_attr( $title ); ?></h3>
						<form class="sidebar_feedwidget" action="//feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('//feedburner.google.com/fb/a/mailverify?uri=<?php echo esc_attr( $user ); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
							<fieldset>
								<input type="email" class="sidebar_mailinput" name="email" value="<?php echo esc_attr( $text ); ?>" onfocus="if (this.value == '<?php echo esc_attr( $text ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo esc_attr( $text ); ?>';}" />
							<input type="hidden" value="<?php echo esc_attr( $user ); ?>" name="uri" />
							<input type="hidden" name="loc" value="<?php bloginfo('language'); ?>"/>
								<input type="image" class="sidebar_mailinput_submit" src="<?php echo plugins_url(); ?>/gabfire-widget-pack/images/submit-newsletter-add.png" alt="<?php _e('Subscribe by mail','gabfire'); ?>" />
							</fieldset>
						</form>
						<?php echo wpautop($a_text); ?>
					</div>
				<?php } ?>			
			
			<?php 
		echo "<div class='clear'></div>$after_widget"; 
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['user'] = ( ! empty( $new_instance['user'] ) ) ? sanitize_text_field( $new_instance['user'] ) : '';
		$instance['text'] = ( ! empty( $new_instance['text'] ) ) ? sanitize_text_field( $new_instance['text'] ) : '';
		$instance['bgcol'] = ( ! empty( $new_instance['bgcol'] ) ) ? sanitize_text_field( $new_instance['bgcol'] ) : '';
		$instance['bordercol'] = ( ! empty( $new_instance['bordercol'] ) ) ? sanitize_text_field( $new_instance['bordercol'] ) : '';
		$instance['textcol'] = ( ! empty( $new_instance['textcol'] ) ) ? sanitize_text_field( $new_instance['textcol'] ) : '';
		$instance['a_text'] = ( ! empty( $new_instance['a_text'] ) ) ? sanitize_text_field( $new_instance['a_text'] ) : '';
		$instance['wstyle'] = ( ! empty( $new_instance['wstyle'] ) ) ? sanitize_text_field( $new_instance['wstyle'] ) : '';
		$instance['bgurl'] 	= ( ! empty( $new_instance['bgurl'] ) ) ? sanitize_text_field( $new_instance['bgurl'] ) : '';
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' => 'Subscribe by Email',
			'user' => '',
			'text' => 'Enter your email',
			'bordercol' => '#cccccc',
			'bgcol' => '#efefef',
			'textcol' => '#555555',
			'wstyle' => 'small',
			'bgurl' => '',
			'a_text' => 'You are registering to get updates and emails about our articles! We will definitely make it worth your time'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>
		
		<p>
			<label for="<?php echo $this->get_field_name( 'wstyle' ); ?>"><?php _e('Select subscribe form size','gabfire-widget-pack'); ?></label>
			<select id="<?php echo $this->get_field_id( 'wstyle' ); ?>" name="<?php echo $this->get_field_name( 'wstyle' ); ?>">
				<option value="small" <?php if ( 'small' == $instance['wstyle'] ) echo 'selected="selected"'; ?>><?php _e('Small Form','gabfire-widget-pack'); ?></option>
				<option value="big" <?php if ( 'big' == $instance['wstyle'] ) echo 'selected="selected"'; ?>><?php _e('Big Form','gabfire-widget-pack'); ?></option>    
			</select>
		</p>		
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','gabfire-widget-pack'); ?></label>
			<input class="widefat"  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('Feedburner ID','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo esc_attr($instance['user']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo esc_attr($instance['text']); ?>" />
		</p>		
		
		
		<p style="background-color: #efefef;border:1px solid #ddd;padding:10px;"><?php _e('Adjust style of input field for small subscribe form.','gabfire-widget-pack'); ?></p>
		
		<p>
			<label for="<?php echo $this->get_field_id('bgcol'); ?>"><?php _e('Input field background color','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('bgcol'); ?>" name="<?php echo $this->get_field_name('bgcol'); ?>" type="text" value="<?php echo esc_attr($instance['bgcol']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('bordercol'); ?>"><?php _e('Input field border color','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('bordercol'); ?>" name="<?php echo $this->get_field_name('bordercol'); ?>" type="text" value="<?php echo esc_attr($instance['bordercol']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('textcol'); ?>"><?php _e('Input field text color','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('textcol'); ?>" name="<?php echo $this->get_field_name('textcol'); ?>" type="text" value="<?php echo esc_attr($instance['textcol']); ?>" />
		</p>
		
		<p style="background-color: #efefef;border:1px solid #ddd;padding:10px;"><?php _e('If big form is selected, display a custom text below input field'); ?></p>
		
		<p>
			<label for="<?php echo $this->get_field_id('a_text'); ?>"><?php _e('Text','gabfire-widget-pack'); ?></label>
			<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('a_text'); ?>" name="<?php echo $this->get_field_name('a_text'); ?>"><?php echo esc_attr($instance['a_text']); ?></textarea>
		</p>	

		<p style="background-color: #efefef;border:1px solid #ddd;padding:10px;"><?php _e('URL of image to replace background of big form with'); ?></p>
		
		<p>
			<label for="<?php echo $this->get_field_id('bgurl'); ?>"><?php _e('URL for custom background','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('bgurl'); ?>" name="<?php echo $this->get_field_name('bgurl'); ?>" type="text" value="<?php echo esc_attr($instance['bgurl']); ?>" />
		</p>		
			
	<?php
	}
}

function register_gabfire_feedburner() {
	register_widget('gabfire_feedburner');
}

add_action('widgets_init', 'register_gabfire_feedburner');