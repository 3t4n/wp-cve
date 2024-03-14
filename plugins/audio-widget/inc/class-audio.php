<?php 
/*!
 * Audio Widget Class 0.1
 * http://sinayazdi.com/audio-widget
 * Licensed under the GPL2
 */

/**
 * Adds SMY_Audio_Widget widget.
 */
class SMY_Audio_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'smy_audio_widget', // Base ID
			__( 'Audio Widget', 'audio_widget' ), // Name
			array( 'description' => __( 'Add a Audio with Poster to your widget', 'audio_widget' ), ) // Args
			);
	}



	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		$title    = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$desc     = ! empty( $instance['desc'] ) ? $instance['desc'] : '';
		$link     = ! empty( $instance['link'] ) ? $instance['link'] : '';
		$blank    = ! empty( $instance['blank'] ) ? 'target="_blank"' : '';

		$src      = ! empty( $instance['src'] ) ? $instance['src'] : '';
		$poster   = ! empty( $instance['poster'] ) ? $instance['poster'] : '';
		$controls = ! empty( $instance['controls'] ) ? ' controls' : '';
		$loop     = ! empty( $instance['loop'] ) ? ' loop' : '';
		$autoplay = ! empty( $instance['autoplay'] ) ? ' autoplay' : '';
		$preload  = ! empty( $instance['preload'] ) ? ' preload' : '';


		if ($title && filter_var($link, FILTER_VALIDATE_URL)) {
			echo '<a href="'.$link.'" class="smy_audio_link" '.$blank.'>'.
				 '<h3>'.$title.'</h3>'.
				 '</a>';
		} elseif ($title) {
			echo '<h3 class="smy_audio_title">'.$title.'</h3>';
		}


		if ($poster && filter_var($link, FILTER_VALIDATE_URL)) {
			echo '<a href="'.$link.'" class="smy_audio_link" '.$blank.'>'.
				 '<img class="smy_audio_image" src="'.$poster.'"  id="'.$this->get_field_id('poster').'_image" >'.
				 '</a>';
		} elseif ($poster) {
			echo '<img class="smy_audio_image" src="'.$poster.'"  id="'.$this->get_field_id('poster').'_image" >';
		}

		echo '<audio src="'.$src.'" class="widefat"'.$controls.$loop.$autoplay.$preload.'></audio>';


		if ($desc) {
			echo '<p class="smy_audio_desc">'.$desc.'</p>';
		}

		echo $args['after_widget'];
	}




	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title    = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$desc     = ! empty( $instance['desc'] ) ? $instance['desc'] : '';
		$link     = ! empty( $instance['link'] ) ? $instance['link'] : 'http://';
		$blank    = ! empty( $instance['blank'] ) ? 'checked=checked' : '';

		$src      = ! empty( $instance['src'] ) ? $instance['src'] : '';
		$poster   = ! empty( $instance['poster'] ) ? $instance['poster'] : '';
		$controls = ! empty( $instance['controls'] ) ? 'checked=checked' : '';
		$loop     = ! empty( $instance['loop'] ) ? 'checked=checked' : '';
		$autoplay = ! empty( $instance['autoplay'] ) ? 'checked=checked' : '';
		$preload  = ! empty( $instance['preload'] ) ? 'checked=checked' : '';

		?>

		<style>
			.smy_audio_widget_image {
				max-width: 100%;
				height: auto;
				width: auto;
				vertical-align: top;
				background-image: -webkit-linear-gradient(45deg,#c4c4c4 25%,transparent 25%,transparent 75%,#c4c4c4 75%,#c4c4c4),-webkit-linear-gradient(45deg,#c4c4c4 25%,transparent 25%,transparent 75%,#c4c4c4 75%,#c4c4c4);
				background-image: linear-gradient(45deg,#c4c4c4 25%,transparent 25%,transparent 75%,#c4c4c4 75%,#c4c4c4),linear-gradient(45deg,#c4c4c4 25%,transparent 25%,transparent 75%,#c4c4c4 75%,#c4c4c4);
				background-position: 0 0,10px 10px;
				-webkit-background-size: 20px 20px;
				background-size: 20px 20px;
			}
			.smy_audio_widget_add_audio,
			.smy_audio_widget_add_poster {
				padding: 0 6px 1px 5px !important;
			}
			.smy_audio_widget_add_audio span.smy_audio_buttons_icon:before,
			.smy_audio_widget_add_poster span.smy_audio_buttons_icon:before {
				font: 400 18px/1 dashicons;
				speak: none;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
				content: "\f521";
				vertical-align: text-top;
			}
			.smy_audio_widget_add_audio span.smy_poster_buttons_icon:before,
			.smy_audio_widget_add_poster span.smy_poster_buttons_icon:before {
				font: 400 18px/1 dashicons;
				speak: none;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
				content: "\f128";
				vertical-align: text-top;
			}
		</style>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:', 'audio_widget'); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>


		<div id="<?php echo $this->get_field_id('src').'_type'; ?>">

			<?php
			echo '<img class="smy_audio_widget_image" src="'.$poster.'"  id="'.$this->get_field_id('poster').'_image'.'">';
			echo '<audio src="'.$src.'" class="widefat" controls></audio>';
			?>

		</div>

		<p>
			<button class="smy_audio_widget_add_audio button" id="<?php echo $this->get_field_id('src').'_add_button'; ?>">
				<span class="smy_audio_buttons_icon"></span>
				<?php _e('Add Audio', 'audio_widget') ?>
			</button>

			<button class="smy_audio_widget_add_poster button" id="<?php echo $this->get_field_id('poster').'_add_button'; ?>">
				<span class="smy_poster_buttons_icon"></span>
				<?php _e('Add Poster', 'audio_widget') ?>
			</button>

			<input type="hidden" class="smy_audio_widget_url" name="<?php echo $this->get_field_name('src'); ?>" id="<?php echo $this->get_field_id('src').'_url'; ?>" value="<?php echo esc_attr( $src ); ?>">
			<input type="hidden" class="smy_audio_widget_poster" name="<?php echo $this->get_field_name('poster'); ?>" id="<?php echo $this->get_field_id('poster').'_poster'; ?>" value="<?php echo esc_attr( $poster ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>"><?php esc_html_e('Description:', 'audio_widget'); ?></label> 
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'desc' ) ); ?>" rows="6" cols="20"><?php echo esc_textarea( $desc ); ?></textarea>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e('Link:', 'audio_widget'); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" type="text" value="<?php echo esc_url( $link ); ?>">
		</p>

		<p>
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'blank' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'blank' ) ); ?>" type="checkbox" <?php echo esc_attr( $blank ); ?> >
			<label for="<?php echo esc_attr( $this->get_field_id( 'blank' ) ); ?>"><?php esc_html_e('Open link in new page?', 'audio_widget'); ?></label>
		</p>

		<!-- Extra Audio & Video Options -->

		<p>
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'controls' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'controls' ) ); ?>" type="checkbox" <?php echo esc_attr( $controls ); ?> >
			<label for="<?php echo esc_attr( $this->get_field_id( 'controls' ) ); ?>"><?php esc_html_e('Add Controls to audio?', 'audio_widget'); ?></label>
		</p>

		<p>
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'loop' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'loop' ) ); ?>" type="checkbox" <?php echo esc_attr( $loop ); ?> >
			<label for="<?php echo esc_attr( $this->get_field_id( 'loop' ) ); ?>"><?php esc_html_e('Loop audio?', 'audio_widget'); ?></label>
		</p>

		<p>
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'autoplay' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'autoplay' ) ); ?>" type="checkbox" <?php echo esc_attr( $autoplay ); ?> >
			<label for="<?php echo esc_attr( $this->get_field_id( 'autoplay' ) ); ?>"><?php esc_html_e('Autoplay audio?', 'audio_widget'); ?></label>
		</p>

		<p>
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'preload' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'preload' ) ); ?>" type="checkbox" <?php echo esc_attr( $preload ); ?> >
			<label for="<?php echo esc_attr( $this->get_field_id( 'preload' ) ); ?>"><?php esc_html_e('Preload audio?', 'audio_widget'); ?></label>
		</p>

		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['desc']     = ( ! empty( $new_instance['desc'] ) ) ? strip_tags( $new_instance['desc'] ) : '';
		$instance['link']     = ( ! empty( $new_instance['link'] ) ) ? strip_tags( $new_instance['link'] ) : '';
		$instance['blank']    = ( ! empty( $new_instance['blank'] ) ) ? strip_tags( $new_instance['blank'] ) : '';
		$instance['src']      = ( ! empty( $new_instance['src'] ) ) ? strip_tags( $new_instance['src'] ) : '';
		$instance['poster']   = ( ! empty( $new_instance['poster'] ) ) ? strip_tags( $new_instance['poster'] ) : '';
		$instance['controls'] = ( ! empty( $new_instance['controls'] ) ) ? strip_tags( $new_instance['controls'] ) : '';
		$instance['loop']     = ( ! empty( $new_instance['loop'] ) ) ? strip_tags( $new_instance['loop'] ) : '';
		$instance['autoplay'] = ( ! empty( $new_instance['autoplay'] ) ) ? strip_tags( $new_instance['autoplay'] ) : '';
		$instance['preload']  = ( ! empty( $new_instance['preload'] ) ) ? strip_tags( $new_instance['preload'] ) : '';

		return $instance;
	}

} // class SMY_Audio_Widget



// register SMY_Audio_Widget widget
function register_smy_audio_widget () {
	register_widget( 'SMY_Audio_Widget' );
}
add_action( 'widgets_init', 'register_smy_audio_widget' );

?>