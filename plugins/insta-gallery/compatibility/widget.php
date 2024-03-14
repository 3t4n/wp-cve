<?php
/**
 * Compatibility with the old version
 */
use QuadLayers\IGG\Models\Feed as Models_Feed;
use QuadLayers\IGG\Frontend\Load as Frontend;

class QLIGG_Widget extends WP_Widget {

	public function __construct() {
	parent::__construct(
		'QLIGG_Widget',
		QLIGG_PLUGIN_NAME,
		array(
			'classname'   => 'instagal-widget',
			'description' => esc_html__( 'Displays your Instagram gallery', 'insta-gallery' ),
		)
	);
	}

	public function widget( $args, $instance ) {
		$title   = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
		$feed_id = 0;

		if ( isset( $instance['feed_id'] ) && is_numeric( $instance['feed_id'] ) ) {
			$feed_id = $instance['feed_id'];
		} elseif ( isset( $instance['instagal_id'] ) && is_numeric( $instance['instagal_id'] ) ) {
			$feed_id = $instance['instagal_id'];
		}

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . wp_kses_post( $title ) . $args['after_title'];
		}

		$models_feed = new Models_Feed();

		$feed = $models_feed->get_by_id( $feed_id );

		echo Frontend::instance()->create_shortcode( $feed, $feed_id );

		echo $args['after_widget'];
	}

	public function form( $instance ) {

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'   => '',
				'feed_id' => 0,
			)
		);

		$title   = $instance['title'];
		$feed_id = $instance['feed_id'];

		$feed_model = new Models_Feed();

		$feeds = $feed_model->get();
		?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'insta-gallery' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label>
	</p>
	<?php if ( ! empty( $feeds ) && is_array( $feeds ) ) : ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'feed_id' ) ); ?>"><?php esc_html_e( 'Select your Instagram gallery', 'insta-gallery' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'feed_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'feed_id' ) ); ?>" class="widefat">
				<?php
				foreach ( $feeds as $id => $feed ) {
					?>
					<option value="<?php echo esc_html( $feed['id'] ); ?>" <?php selected( $id, $feed_id ); ?>>
						Feed <?php echo esc_html( $feed['id'] ); ?>
					</option>
				<?php } ?>
			</select>
		</p>
		<?php else : ?>
			<p style="color: #e23565;">
				<?php esc_html_e( 'Please add new gallery in plugin admin panel, then come back and select your gallery to here.', 'insta-gallery' ); ?>
			</p>
		<?php endif; ?>
			<p style="text-align: center;" >
				<a target="_blank" href="<?php echo admin_url( 'admin.php?page=qligg_feeds' ); ?>"><?php esc_html_e( 'Add New Gallery', 'insta-gallery' ); ?></a>
			</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance            = $old_instance;
		$instance['title']   = strip_tags( $new_instance['title'] );
		$instance['feed_id'] = trim( strip_tags( $new_instance['feed_id'] ) );
		return $instance;
	}

}
