<?php
/**
 * Widget Recommended Posts with thumbnail
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_recommend_posts_widget extends WP_Widget {

	
	function __construct() {
		parent::__construct(
			'ya_recommend_posts', // Base ID
			esc_html__( '[YAHMAN Add-ons] Recommended Posts with thumbnail', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Support Recommended Posts with thumbnail for Widget', 'yahman-add-ons' ), ) // Args
		);
	}

  /**
   * Set default settings of the widget
   */
  private function default_settings() {

    $defaults = array(
      'title'    => esc_html__('Recommended Posts', 'yahman-add-ons'),
      'post_in'   => '',
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

    $post_in = explode(',', $settings['post_in']);

    $popular_args = array(
      'offset' => 0,
      'order' => 'DESC',
      'orderby' => 'none',
      'post__in' => $post_in,
      'ignore_sticky_posts' => true,
      'post_type' => array( 'post', 'page' ),
    );

    $posts = new WP_Query( $popular_args );
//var_dump($posts);
    if ( $posts->have_posts() ) {

      echo $args['before_widget'];
      if ( $settings['title'] ) {
        echo $args['before_title'] . esc_html($settings['title']) . $args['after_title'];
      }




        // The loop
      require_once YAHMAN_ADDONS_DIR . 'inc/classes/post_list.php';
      $back_data = YAHMAN_ADDONS_POST_LIST::yahman_addons_post_list_output($posts,$settings);

      echo $back_data[0];
      //get_template_part( 'template-parts/widget-post_list','select');
        // Reset post data

      echo $args['after_widget']."\n";
    }

  }
  public function form( $instance ) {

        // Get Widget Settings.
    $settings = wp_parse_args( $instance, $this->default_settings() );

    ?>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'yahman-add-ons' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'post_in' ) ); ?>"><?php esc_html_e( 'Enter the post ID to use this function', 'yahman-add-ons' ); ?><br /><?php echo sprintf( esc_html__('Separate multiple %s with ,(comma).', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_in' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_in' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['post_in'] ); ?>">
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

    <?php
  }
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
    $instance['display_style'] = ! empty( $new_instance['display_style'] ) ? absint( $new_instance['display_style'] ) : '3';
    $instance['post_in'] = ! empty( $new_instance['post_in'] ) ? sanitize_text_field( $new_instance['post_in'] ) : '';

    return $instance;
  }

} // class yahman_addons_recommend_posts_widget
