<?php

class SiteOrigin_Masonry_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'siteorigin-masonry',
			__( 'Masonry Layout', 'so-masonry' ),
			array(
				'description' => __( 'A stunning, responsive masonry layout.', 'so-masonry' ),
			)
		);
	}

	/**
	 * Output the masonry HTML
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		// Enqueue all the front end scripts for the masonry
		SiteOrigin_Masonry::single()->enqueue();

		$instance = wp_parse_args($instance, array(
			'sticky' => false,
			'additional' => '',
			'responsive' => true,
		));

		$query_args = $instance;
		unset($query_args['additional']);
		unset($query_args['sticky']);

		$query_args = wp_parse_args($instance['additional'], $query_args);

		global $wp_query;
		$query_args['paged'] = $wp_query->get('paged');

		switch($instance['sticky']){
			case 'ignore' :
				$query_args['ignore_sticky_posts'] = 1;
				break;
			case 'only' :
				$query_args['post__in'] = get_option( 'sticky_posts' );
				break;
			case 'exclude' :
				$query_args['post__not_in'] = get_option( 'sticky_posts' );
				break;
		}

		if ( !empty( $instance['title'] ) ) {
			echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
		}

		$posts = new WP_Query($query_args);

		echo $args['before_widget'];
		include plugin_dir_path(__FILE__).'../tpl/masonry.php';
		echo $args['after_widget'];

	}

	function update($new, $old){
		$new['responsive'] = !empty($new['responsive']);
		return $new;
	}

	function form( $instance ) {
		$instance = wp_parse_args($instance, array(
			'title' => '',
			'responsive' => true,

			// Query args
			'post_type' => 'post',
			'posts_per_page' => '',

			'order' => 'DESC',
			'orderby' => 'date',

			'cat' => '',
			'sticky' => '',

			'additional' => '',
		));

		// Get all the loop template files
		$post_types = get_post_types(array('public' => true));
		$post_types = array_values($post_types);
		$post_types = array_diff($post_types, array('attachment', 'revision', 'nav_menu_item'));

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title', 'so-masonry' ) ?></label>
			<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'title' ) ?>" id="<?php echo $this->get_field_id( 'title' ) ?>" value="<?php echo esc_attr( $instance['title'] ) ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('post_type') ?>"><?php _e('Post Type', 'so-masonry') ?></label>
			<select id="<?php echo $this->get_field_id( 'post_type' ) ?>" name="<?php echo $this->get_field_name( 'post_type' ) ?>" value="<?php echo esc_attr($instance['post_type']) ?>">
				<?php foreach($post_types as $type) : ?>
					<option value="<?php echo esc_attr($type) ?>" <?php selected($instance['post_type'], $type) ?>><?php echo esc_html($type) ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('posts_per_page') ?>"><?php _e('Posts Per Page', 'so-masonry') ?></label>
			<input type="text" class="small-text" id="<?php echo $this->get_field_id( 'posts_per_page' ) ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ) ?>" value="<?php echo esc_attr($instance['posts_per_page']) ?>" />
		</p>

		<p>
			<label <?php echo $this->get_field_id('orderby') ?>><?php _e('Order By', 'so-masonry') ?></label>
			<select id="<?php echo $this->get_field_id( 'orderby' ) ?>" name="<?php echo $this->get_field_name( 'orderby' ) ?>" value="<?php echo esc_attr($instance['orderby']) ?>">
				<option value="none" <?php selected($instance['orderby'], 'none') ?>><?php esc_html_e('None', 'so-masonry') ?></option>
				<option value="ID" <?php selected($instance['orderby'], 'ID') ?>><?php esc_html_e('Post ID', 'so-masonry') ?></option>
				<option value="author" <?php selected($instance['orderby'], 'author') ?>><?php esc_html_e('Author', 'so-masonry') ?></option>
				<option value="name" <?php selected($instance['orderby'], 'name') ?>><?php esc_html_e('Name', 'so-masonry') ?></option>
				<option value="name" <?php selected($instance['orderby'], 'name') ?>><?php esc_html_e('Name', 'so-masonry') ?></option>
				<option value="date" <?php selected($instance['orderby'], 'date') ?>><?php esc_html_e('Date', 'so-masonry') ?></option>
				<option value="modified" <?php selected($instance['orderby'], 'modified') ?>><?php esc_html_e('Modified', 'so-masonry') ?></option>
				<option value="parent" <?php selected($instance['orderby'], 'parent') ?>><?php esc_html_e('Parent', 'so-masonry') ?></option>
				<option value="rand" <?php selected($instance['orderby'], 'rand') ?>><?php esc_html_e('Random', 'so-masonry') ?></option>
				<option value="comment_count" <?php selected($instance['orderby'], 'comment_count') ?>><?php esc_html_e('Comment Count', 'so-masonry') ?></option>
				<option value="menu_order" <?php selected($instance['orderby'], 'menu_order') ?>><?php esc_html_e('Menu Order', 'so-masonry') ?></option>
				<option value="menu_order" <?php selected($instance['orderby'], 'menu_order') ?>><?php esc_html_e('Menu Order', 'so-masonry') ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('order') ?>"><?php _e('Order', 'so-masonry') ?></label>
			<select id="<?php echo $this->get_field_id( 'order' ) ?>" name="<?php echo $this->get_field_name( 'order' ) ?>" value="<?php echo esc_attr($instance['order']) ?>">
				<option value="DESC" <?php selected($instance['order'], 'DESC') ?>><?php esc_html_e('Descending', 'so-masonry') ?></option>
				<option value="ASC" <?php selected($instance['order'], 'ASC') ?>><?php esc_html_e('Ascending', 'so-masonry') ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('sticky') ?>"><?php _e('Sticky Posts', 'so-masonry') ?></label>
			<select id="<?php echo $this->get_field_id( 'sticky' ) ?>" name="<?php echo $this->get_field_name( 'sticky' ) ?>" value="<?php echo esc_attr($instance['sticky']) ?>">
				<option value="" <?php selected($instance['sticky'], '') ?>><?php esc_html_e('Default', 'so-masonry') ?></option>
				<option value="ignore" <?php selected($instance['sticky'], 'ignore') ?>><?php esc_html_e('Ignore Sticky', 'so-masonry') ?></option>
				<option value="exclude" <?php selected($instance['sticky'], 'exclude') ?>><?php esc_html_e('Exclude Sticky', 'so-masonry') ?></option>
				<option value="only" <?php selected($instance['sticky'], 'only') ?>><?php esc_html_e('Only Sticky', 'so-masonry') ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('cat') ?>"><?php _e('Post Category', 'so-masonry') ?></label>
			<select id="<?php echo $this->get_field_id( 'cat' ) ?>" name="<?php echo $this->get_field_name( 'cat' ) ?>" value="<?php echo esc_attr($instance['cat']) ?>">
				<option value="" <?php selected($instance['cat'], '') ?>><?php esc_html_e('Default', 'so-masonry') ?></option>
				<?php
				$categories = get_categories();
				foreach($categories as $cat) {
					?><option value="<?php echo intval($cat->term_id) ?>" <?php selected($instance['cat'], $cat->term_id) ?>><?php echo esc_html($cat->name) ?></option><?php
				}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('additional') ?>"><?php _e('Additional ', 'so-masonry') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'additional' ) ?>" name="<?php echo $this->get_field_name( 'additional' ) ?>" value="<?php echo esc_attr($instance['additional']) ?>" />
			<small><?php printf(__('Additional query arguments. See <a href="%s" target="_blank">query_posts</a>.', 'so-masonry'), 'http://codex.wordpress.org/Function_Reference/query_posts') ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('responsive') ?>">
				<?php _e('Responsive Layout', 'so-masonry') ?>
			</label>
			<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'responsive' ) ?>" name="<?php echo $this->get_field_name( 'responsive' ) ?>" <?php checked($instance['responsive']) ?> />
		</p>
		<?php
	}
}