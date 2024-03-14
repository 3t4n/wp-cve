<?php
/**
 * Widget TOC
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_toc_widget extends WP_Widget {

	
	function __construct() {
		parent::__construct(
			'ya_toc_widget', // Base ID
			esc_html__( '[YAHMAN Add-ons] TOC', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Support table of contents for Widget', 'yahman-add-ons' ), ) // Args
		);
	}

	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'title'    => '',
			'sticky'    => false,
		);

		return $defaults;
	}

	public function widget( $args, $instance ) {
		$toc_html = '';
		$toc_html = get_query_var('yahman_addons_toc_html');

		if ($toc_html === '') return;

		$settings = wp_parse_args( $instance, $this->default_settings() );

		if( $settings['sticky'] ){
			echo str_replace( 'class="' , 'class="toc_sticky ' , $args['before_widget'] );
		}else{
			echo $args['before_widget'];
		}

		if ( $settings['title'] ) {
			echo $args['before_title'] . esc_html($settings['title']) . $args['after_title'];
		}

		echo apply_filters( 'widget_text',$toc_html );

		echo $args['after_widget'];

	}
	public function form( $instance ) {

		$settings = wp_parse_args( $instance, $this->default_settings() );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>">
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'sticky' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'sticky' ) ); ?>" type="checkbox"<?php checked( $settings['sticky'] ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'sticky' ) ); ?>">
				<?php esc_html_e( 'Sticky', 'yahman-add-ons' ); ?>
			</label>
		</p>
		<?php

	}

	public function update( $new_instance, $old_instance ) {

		$instance = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['sticky'] = ! empty( $new_instance['sticky'] ) ? (bool) $new_instance['sticky']  : false;
		return $instance;

	}

} // class yahman_addons_toc_widget
