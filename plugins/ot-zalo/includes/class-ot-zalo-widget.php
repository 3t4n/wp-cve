<?php

class OT_Zalo_Widget extends WP_Widget {

	function __construct() {
		parent::__construct( 'ot_zalo_widget', __( 'OT Zalo Follow', 'ot-zalo' ), array( 'description' => __( 'Zalo Follow widget', 'ot-zalo' ), ) );
	}

	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', $instance[ 'title' ] );

		echo $args[ 'before_widget' ];
		if ( ! empty( $title ) ) {
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		}

		$oaid  = isset( $instance[ 'oaid' ] ) ? $instance[ 'oaid' ] : ot_zalo_get_option( 'zalo_oaid' );
		$cover = ( isset( $instance[ 'cover' ] ) && isset( $instance[ 'cover' ] ) == 'on' ) ? 'yes' : 'no';
		$article  = isset( $instance[ 'article' ] ) && !empty($instance[ 'article' ]) ? $instance[ 'article' ] : 3;
		$width  = isset( $instance[ 'width' ] ) ? $instance[ 'width' ] : '';
		$height  = isset( $instance[ 'height' ] ) ? $instance[ 'height' ] : '';

		?>

		<?php if ( empty( $oaid ) ): ?>
			<p><?php _e( 'Please enter you zalo official account ID', 'ot-zalo' ); ?></p>
		<?php else: ?>

			<div class="zalo-follow-button" data-oaid="<?php echo $oaid; ?>" data-cover="<?php echo $cover; ?>"
			     data-article="<?php echo $article; ?>"
			     data-width="<?php echo $width; ?>" data-height="<?php echo $height; ?>"></div>

		<?php endif; ?>
		<?php
		echo $args[ 'after_widget' ];
	}

	public function form( $instance ) {

		$title    = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
		$oaid    = isset( $instance[ 'oaid' ] ) ? $instance[ 'oaid' ] : ot_zalo_get_option( 'zalo_oaid' );
		$cover   = isset( $instance[ 'cover' ] ) ? $instance[ 'cover' ] : '';
		$width   = isset( $instance[ 'width' ] ) ? $instance[ 'width' ] : '';
		$height  = isset( $instance[ 'height' ] ) ? $instance[ 'height' ] : '';
		$article = isset( $instance[ 'article' ] ) ? $instance[ 'article' ] : '';

		?>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'ot-zalo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
			       value="<?php echo esc_attr( $title ); ?>"/>
		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'oaid' ); ?>"><?php _e( 'Zalo Official Account ID:', 'ot-zalo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'oaid' ); ?>"
			       name="<?php echo $this->get_field_name( 'oaid' ); ?>" type="text"
			       value="<?php echo esc_attr( $oaid ); ?>"/>
		</p>

		<p>
			<label
				for="<?php echo $this->get_field_id( 'cover' ); ?>"><?php _e( 'Show Cover Image:', 'ot-zalo' ); ?></label>
			<input <?php checked( esc_attr( $cover ), 'on', true ); ?> type="checkbox" class="checkbox"
			                                                           id="<?php echo $this->get_field_id( 'cover' ); ?>"
			                                                           name="<?php echo $this->get_field_name( 'cover' ); ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width:', 'ot-zalo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>"
			       name="<?php echo $this->get_field_name( 'width' ); ?>" type="text"
			       value="<?php echo esc_attr( $width ); ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height:', 'ot-zalo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>"
			       name="<?php echo $this->get_field_name( 'height' ); ?>" type="text"
			       value="<?php echo esc_attr( $height ); ?>"/>
		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'article' ); ?>"><?php _e( 'Number article:', 'ot-zalo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'article' ); ?>"
			       name="<?php echo $this->get_field_name( 'article' ); ?>" type="text"
			       value="<?php echo esc_attr( $article ); ?>"/>
			<p><?php _e('Number article to show. Min: 0, Max: 5', 'ot-zalo'); ?></p>
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance              = array();
		$instance[ 'title' ]    = ( ! empty( $new_instance[ 'title' ] ) ) ? strip_tags( $new_instance[ 'title' ] ) : '';
		$instance[ 'oaid' ]    = ( ! empty( $new_instance[ 'oaid' ] ) ) ? strip_tags( $new_instance[ 'oaid' ] ) : '';
		$instance[ 'cover' ]   = ( ! empty( $new_instance[ 'cover' ] ) ) ? strip_tags( $new_instance[ 'cover' ] ) : '';
		$instance[ 'width' ]   = ( ! empty( $new_instance[ 'width' ] ) ) ? strip_tags( $new_instance[ 'width' ] ) : '';
		$instance[ 'height' ]  = ( ! empty( $new_instance[ 'height' ] ) ) ? strip_tags( $new_instance[ 'height' ] ) : '';
		$instance[ 'article' ] = ( ! empty( $new_instance[ 'article' ] ) ) ? strip_tags( $new_instance[ 'article' ] ) : 0;

		return $instance;
	}
}