<?php
/**
 * Register and load the widget
 */
function wps_load_widget() {
	register_widget('WPS_Widgets');
}
add_action('widgets_init', 'wps_load_widget');

/**
 * Creating the widget 
 */
class WPS_Widgets extends WP_Widget {
	function __construct() {
		parent::__construct(
			'wc_widget_stores',
			__('WC Pickup Store', 'wc-pickup-store'),
			array(
				'description' => __('Shows store details in a widget section', 'wc-pickup-store')
			)
		);
	}

	private function wps_widget_fields($exclude = null) {
		$fields = array('title', 'per_page', 'post_ids', 'store_image', 'store_image_size', 'store_columns', 'show_name', 'show_city', 'show_direction', 'show_phone', 'show_description', 'show_waze');

		if(!is_null($exclude)) {
			$fields = array_diff($fields, $exclude);
		}

		return $fields;
	}

	/**
	 * Creating widget front-end
	 * 
	 * @version 1.8.6
	 * @since 1.x
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$per_page = !empty( $instance['per_page'] ) ? $instance['per_page'] : 1;
		$image_size = explode( 'x', strtolower( $instance['store_image_size'] ) );
		$attr = array();
		$bootstrap_version = WPS()->bootstrap_version;

		foreach ( $this->wps_widget_fields( array( 'title', 'per_page', 'image_size' ) ) as $key => $field ) {
			$attr[$field] = !empty($instance[$field] ) ? $instance[$field] : '';
		}

		if ( !empty( $image_size[1] ) ) {
			$store_image_size = $image_size;
		} else {
			$store_image_size = $image_size[0];
		}

		$classes = array(
			'1' => ( $bootstrap_version == 'version_3' ) ? 'col-xs-12' : 'col-12',
			'2' => ( $bootstrap_version == 'version_3' ) ? 'col-xs-6' : 'col-6',
			'3' => ( $bootstrap_version == 'version_3' ) ? 'col-sm-4 col-xs-6' : 'col-sm-4 col-6',
			'4' => ( $bootstrap_version == 'version_3' ) ? 'col-sm-3 col-xs-6' : 'col-sm-3 col-6'
		);

		$query_args = array(
			'post_type' => 'store',
			'posts_per_page' => $per_page,
		);

		if ( !empty( $attr['post_ids'] ) ) {
			$query_args['orderby'] = 'post__in';
			$query_args['post__in'] = explode( ',', $attr['post_ids'] );
		}

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$template_file = wps_locate_template( 'wrapper-store.php', false );

		if ( $template_file ) {
			echo '<div class="row">';
				$query = new WP_Query( apply_filters( 'wps_store_widget_query_args', $query_args ) );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) : $query->the_post();
						global $post;
						$store_id = $post->ID;
						$store_image = (!empty($attr['store_image'])) ? esc_attr($attr['store_image']) : '';
						$class = (!empty($attr['store_columns'])) ? esc_attr($classes[$attr['store_columns']]) : '';
						$city = (!empty($attr['show_city'])) ? sanitize_text_field(wps_get_post_meta($store_id, 'city')) : '';
						$phone = (!empty($attr['show_phone'])) ? sanitize_text_field(wps_get_post_meta($store_id, 'phone')) : '';
						$address = (!empty($attr['show_direction'])) ? wp_kses_post(wps_get_post_meta($store_id, 'address')) : '';
						$description = (!empty($attr['show_description'])) ? wp_kses_post(wps_get_post_meta($store_id, 'description')) : '';
						$waze = (!empty($attr['show_waze'])) ? esc_url(wps_get_post_meta($store_id, 'waze')) : '';
						
						include $template_file;
						
					endwhile;
					wp_reset_postdata();
				}
			echo '</div>';
		}

		echo $args['after_widget'];
	}

	// Widget Backend 
	public function form( $instance ) {
		$attr = array();
		foreach ($this->wps_widget_fields() as $key => $field) {
			$attr[$field] = (!empty($instance[$field])) ? $instance[$field] : '';
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?= __('Title', 'wc-pickup-store'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $attr['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'per_page' ); ?>"><?= __('Stores to show', 'wc-pickup-store'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'per_page' ); ?>" name="<?php echo $this->get_field_name( 'per_page' ); ?>" type="number" min="-	1" value="<?php echo (!empty($attr['per_page'])) ? esc_attr( $attr['per_page'] ) : 1; ?>" />
			<span class="description"><?= __('Set -1 to show all stores.', 'wc-pickup-store') ?></span>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_ids' ); ?>"><?= __('Set posts IDs', 'wc-pickup-store'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'post_ids' ); ?>" name="<?php echo $this->get_field_name( 'post_ids' ); ?>" type="text" value="<?php echo esc_attr( $attr['post_ids'] ); ?>" />
			<span class="description"><?= __('Add post IDs to show followed by a comma. Ex. 01,05.', 'wc-pickup-store') ?></span>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'store_image' ); ?>"><?= __('Show image?', 'wc-pickup-store'); ?>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'store_image' ); ?>" name="<?php echo $this->get_field_name( 'store_image' ); ?>" value="1" <?php checked($attr['store_image'], '1'); ?>">
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'store_image_size' ); ?>"><?= __('Image size', 'wc-pickup-store'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'store_image_size' ); ?>" name="<?php echo $this->get_field_name( 'store_image_size' ); ?>" type="text" value="<?php echo (!empty($attr['store_image_size'])) ? esc_attr( $attr['store_image_size'] ) : 'thumbnail'; ?>" />
			<span class="description">thumbnail, medium, full, 100x100</span>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'store_columns' ); ?>"><?= __('Show in columns?', 'wc-pickup-store'); ?></label>
			<select name="<?php echo $this->get_field_name( 'store_columns' ); ?>" id="<?php echo $this->get_field_id( 'store_columns' ); ?>">
				<option value="1" <?php selected($attr['store_columns'], '1') ?>>1</option>
				<option value="2" <?php selected($attr['store_columns'], '2') ?>>2</option>
				<option value="3" <?php selected($attr['store_columns'], '3') ?>>3</option>
				<option value="4" <?php selected($attr['store_columns'], '4') ?>>4</option>
			</select>
		</p>
		<fieldset>
			<legend><?= __('Fields', 'wc-pickup-store') ?></legend>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_name' ); ?>"><?= __('Show store name?', 'wc-pickup-store'); ?>
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_name' ); ?>" name="<?php echo $this->get_field_name( 'show_name' ); ?>" value="1" <?php checked($attr['show_name'], '1'); ?>">
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_city' ); ?>"><?= __('Show city?', 'wc-pickup-store'); ?>
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_city' ); ?>" name="<?php echo $this->get_field_name( 'show_city' ); ?>" value="1" <?php checked($attr['show_city'], '1'); ?>">
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_direction' ); ?>"><?= __('Show direction?', 'wc-pickup-store'); ?>
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_direction' ); ?>" name="<?php echo $this->get_field_name( 'show_direction' ); ?>" value="1" <?php checked($attr['show_direction'], '1'); ?>">
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_phone' ); ?>"><?= __('Show phone?', 'wc-pickup-store'); ?>
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_phone' ); ?>" name="<?php echo $this->get_field_name( 'show_phone' ); ?>" value="1" <?php checked($attr['show_phone'], '1'); ?>">
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_description' ); ?>"><?= __('Show description?', 'wc-pickup-store'); ?>
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_description' ); ?>" name="<?php echo $this->get_field_name( 'show_description' ); ?>" value="1" <?php checked($attr['show_description'], '1'); ?>">
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_waze' ); ?>"><?= __('Show waze?', 'wc-pickup-store'); ?>
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_waze' ); ?>" name="<?php echo $this->get_field_name( 'show_waze' ); ?>" value="1" <?php checked($attr['show_waze'], '1'); ?>">
				</label>
			</p>
		</fieldset>
		<?php 
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		foreach ($this->wps_widget_fields() as $key => $field) {
			$instance[$field] = ( ! empty( $new_instance[$field] ) ) ? strip_tags( $new_instance[$field] ) : '';
		}

		return $instance;
	}
}