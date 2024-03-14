<?php
/**
 * Expand Divi Recent Posts Widget
 * adds a recent posts with thubmnails widget
 *
 * @package  ExpandDivi/ExpandDiviRecentPostsWidget
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviRecentPostsWidget extends WP_Widget {

	/**
	 * Sets up the widgets name and description
	 */
	function __construct() {
		$args = array(
			'name' => esc_html__( 'Expand Divi Recent Posts', 'expand-divi' ),
			'description' => esc_html__( 'Display recent posts with featured images.', 'expand-divi' )
		);
		parent::__construct( 'expand_divi_recent_posts_widget', '', $args );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		$rounded = ! empty( $instance['rounded'] ) ? $instance['rounded'] : 'NO';
		$query = new WP_Query(
			$query_args = [
				'post_type'      => $instance['post_type'],
				'posts_per_page' => $instance['number'],
				'orderby'        => $instance['orderBy'],
				'order'          => $instance['order'],
				'post_status'    => 'publish'
			]
		);

		echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . $instance['title'] . $args['after_title'];
			}

			if ( $query->have_posts() ) :
				echo '<div class="expand_divi_recent_posts_wrap';
				if ( $rounded != 'NO' ) {
					echo ' ed_rounded';
				}
				echo '">';
				while ( $query->have_posts() ) : $query->the_post();
					echo '<div class="expand_divi_recent_post">';
						echo '<a href="' . get_the_permalink() . '">' . get_the_post_thumbnail() . '</a>';
						echo '<div class="expand_divi_recent_content"><h5><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h5></div>';
					echo '</div>';
				endwhile;
				echo '</div>';
				else:
				echo '<p class="expand_divi_no_recent_posts">';
				esc_html_e( 'No posts!', 'expand-divi' );
				echo '</p>';
			endif;
			wp_reset_postdata();
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$number = ! empty( $instance['number'] ) ? $instance['number'] : 5;
		$order = ! empty( $instance['order'] ) ? $instance['order'] : 'DESC';
		$orderBy = ! empty( $instance['orderBy'] ) ? $instance['orderBy'] : 'ID';
		$rounded = ! empty( $instance['rounded'] ) ? $instance['rounded'] : 'NO';
		$post_type = ! empty( $instance['post_type'] ) ? $instance['post_type'] : 'Post';
		$args = array(
		    'public'              => true,
		    'exclude_from_search' => false,
		    '_builtin'            => false
		);
		$post_types = get_post_types( $args );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'expand-divi' ); ?></label>
			<input  type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of Posts:', 'expand-divi' ); ?></label>
			<input  type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" value="<?php echo esc_attr( $number ); ?>" min="1">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>"><?php esc_attr_e( 'Post Type:', 'expand-divi' ); ?></label>
			<select  class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_type' ) ); ?>">
				<option>Post</option>
				<?php 
				foreach ( $post_types as $the_post_type ) {
					echo "<option value='" . $the_post_type ."' " . selected( $post_type, $the_post_type, false ) . ">";
					echo $the_post_type;
					echo "</option>";
				 } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_attr_e( 'Order:', 'expand-divi' ); ?></label>
			<select  class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
				<option <?php echo ( $order == 'DESC') ? 'selected' : ''; ?> value="DESC">DESC</option>

				<option <?php echo ( $order == 'ASC') ? 'selected' : ''; ?> value="ASC">ASC</option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderBy' ) ); ?>"><?php esc_attr_e( 'Order By:', 'expand-divi' ); ?></label>
			<select  class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderBy' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderBy' ) ); ?>">
				<option <?php echo ( $orderBy == 'ID') ? 'selected' : ''; ?> value="ID">Date</option>
				<option <?php echo ( $orderBy == 'rand') ? 'selected' : ''; ?> value="rand">Randomly</option>
				<option <?php echo ( $orderBy == 'title') ? 'selected' : ''; ?> value="title">Title</option>
				<option <?php echo ( $orderBy == 'comment_count') ? 'selected' : ''; ?> value="comment_count">Popularity</option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'rounded' ) ); ?>"><?php esc_attr_e( 'Rounded Images?', 'expand-divi' ); ?></label>
			<select  class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'rounded' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rounded' ) ); ?>">
				<option <?php echo ( $rounded == 'NO') ? 'selected' : ''; ?> value="NO">NO</option>

				<option <?php echo ( $rounded == 'YES') ? 'selected' : ''; ?> value="YES">YES</option>
			</select>
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

	    $instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
	    $instance['post_type'] = $new_instance['post_type'];
	    $instance['number'] = $new_instance['number'];
	    $instance['order'] = $new_instance['order'];
	    $instance['orderBy'] = $new_instance['orderBy'];
	    $instance['rounded'] = $new_instance['rounded'];

		return $instance;
	}

}

add_action( 'widgets_init', function(){
	register_widget( 'ExpandDiviRecentPostsWidget' );
});