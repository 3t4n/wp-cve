<?php
/**
 * Widget Recent Posts with thumbnail
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_recent_posts_with_thumbnail_widget extends WP_Widget {

	
	function __construct() {
		parent::__construct(
			'ya_recent_posts', // Base ID
			esc_html__( '[YAHMAN Add-ons] Recent Posts with thumbnail', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Support Recent Posts with thumbnail for Widget', 'yahman-add-ons' ), ) // Args
		);
	}

  /**
   * Set default settings of the widget
   */
  private function default_settings() {

    $defaults = array(
      'title'    => esc_html__('Recent Posts', 'yahman-add-ons'),
      'post_not_in' => '',
      'category_not_in' => '',
      'include_page'   => false,
      'number_post'   => 5,
      'display_style' => '3',
      'ranking' => false,
      'update' => false,
      'pv' => false,
      'ul_class'   => '',
      'li_class'   => '',
    );

    return $defaults;
  }



  public function widget( $args, $instance ) {

    // Get Widget Settings.
    $settings = wp_parse_args( $instance, $this->default_settings() );

    $include_page  = $settings['include_page'] == '1' ? array( 'post', 'page') : 'post';
    $post_not_in = explode(',', $settings['post_not_in']);
    $category_not_in = explode(',', $settings['category_not_in']);

    $popular_args = array(
      'offset' => 0,
      'order' => 'DESC',
      'orderby' => 'date',
      'post_type' => $include_page,
      'post__not_in' => $post_not_in,
      'category__not_in' => $category_not_in,
      'posts_per_page' => $settings['number_post'],
      'ignore_sticky_posts' => true,
    );

    $posts = new WP_Query( $popular_args );
//var_dump($posts);
    if ( $posts->have_posts() ) {

      $ranking_data =  $args['before_widget'];
      if ( $settings['title'] ) {
        $ranking_data .=  $args['before_title'] . esc_html($settings['title']) . $args['after_title'];
      }



      require_once YAHMAN_ADDONS_DIR . 'inc/classes/post_list.php';
      $back_data = YAHMAN_ADDONS_POST_LIST::yahman_addons_post_list_output($posts,$settings);

      echo $ranking_data . $back_data[0] .$args['after_widget']."\n";

    }

  }
  public function form( $instance ) {

    // Get Widget Settings.
    $settings = wp_parse_args( $instance, $this->default_settings() );

    $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
    $post_not_in = ! empty( $instance['post_not_in'] ) ? $instance['post_not_in'] : '';
    $category_not_in = !empty( $instance['category_not_in'] ) ? $instance['category_not_in'] : '';
    $number_post   = ! empty( $instance['number_post'] ) ? $instance['number_post'] : 5 ;
    $display_style  = ! empty( $instance['display_style'] ) ? $instance['display_style'] : '3';
    $include_page  = ! empty( $instance['include_page'] ) ? $instance['include_page'] : '';

    ?>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'yahman-add-ons' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'number_post' ) ); ?>"><?php esc_html_e( 'Number of shown post', 'yahman-add-ons' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_post' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_post' ) ); ?>" type="number" step="1" min="1" max="20" value="<?php echo esc_attr( $settings['number_post'] ); ?>" />
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'display_style' ) ); ?>">
        <?php esc_html_e( 'Display style', 'yahman-add-ons' ); ?>
      </label><br />
      <label for="<?php echo esc_attr( $this->get_field_id( 'display_style' ) ); ?>_1">
        <input id="<?php echo esc_attr( $this->get_field_id( 'display_style' ) ); ?>_1" name="<?php echo esc_attr( $this->get_field_name( 'display_style' ) ); ?>" type="radio" value="1" <?php checked( '1', $settings['display_style'] ); ?>/>
        <?php esc_html_e( 'List', 'yahman-add-ons' ); ?>
      </label>

      <label for="<?php echo esc_attr( $this->get_field_id( 'display_style' ) ); ?>_2">
        <input id="<?php echo esc_attr( $this->get_field_id( 'display_style' ) ); ?>_2" name="<?php echo esc_attr( $this->get_field_name( 'display_style' ) ); ?>" type="radio" value="2" <?php checked( '2', $settings['display_style'] ); ?>/>
        <?php esc_html_e( 'List with thumbnail', 'yahman-add-ons' ); ?>
      </label>

      <label for="<?php echo esc_attr( $this->get_field_id( 'display_style' ) ); ?>_3">
        <input id="<?php echo esc_attr( $this->get_field_id( 'display_style' ) ); ?>_3" name="<?php echo esc_attr( $this->get_field_name( 'display_style' ) ); ?>" type="radio" value="3" <?php checked( '3', $settings['display_style'] ); ?>/>
        <?php esc_html_e( 'Title over a thumbnail', 'yahman-add-ons' ); ?>
      </label>

      <label for="<?php echo esc_attr( $this->get_field_id( 'display_style' ) ); ?>_4">
        <input id="<?php echo esc_attr( $this->get_field_id( 'display_style' ) ); ?>_4" name="<?php echo esc_attr( $this->get_field_name( 'display_style' ) ); ?>" type="radio" value="4" <?php checked( '4', $settings['display_style'] ); ?>/>
        <?php esc_html_e( 'Title under a thumbnail', 'yahman-add-ons' ); ?>
      </label>
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'category_not_in' ) ); ?>">
        <?php esc_html_e( 'Disappear when you type category id.', 'yahman-add-ons' ); ?><br /><?php echo sprintf( esc_html__('Multiple %s must be separated by a comma.', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
      </label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category_not_in' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category_not_in' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['category_not_in'] ); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'post_not_in' ) ); ?>">
        <?php esc_html_e( 'Disappear when you type post id.', 'yahman-add-ons' ); ?><br /><?php echo sprintf( esc_html__('Multiple %s must be separated by a comma.', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
      </label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_not_in' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_not_in' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['post_not_in'] ); ?>">
    </p>
    <p>
      <input id="<?php echo esc_attr( $this->get_field_id( 'include_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'include_page' ) ); ?>" type="checkbox"<?php checked( $settings['include_page'] ); ?> />
      <label for="<?php echo esc_attr( $this->get_field_id( 'include_page' ) ); ?>">
        <?php esc_html_e( 'include page', 'yahman-add-ons' ); ?>
      </label>
    </p>
    <?php
  }

  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
    $instance['include_page'] = ! empty( $new_instance['include_page'] ) ? absint( $new_instance['include_page'] ) : '';
    $instance['display_style'] = ! empty( $new_instance['display_style'] ) ? absint( $new_instance['display_style'] ) : '3';
    $instance['number_post'] = ! empty( $new_instance['number_post'] ) ? absint( $new_instance['number_post'] ) : 5 ;
    $instance['post_not_in'] = ! empty( $new_instance['post_not_in'] ) ? sanitize_text_field( $new_instance['post_not_in'] ) : '';
    $instance['category_not_in'] = ! empty( $new_instance['category_not_in'] ) ? sanitize_text_field( $new_instance['category_not_in'] ) : '';
    return $instance;
  }

} // class yahman_addons_recent_posts_with_thumbnail_widget
