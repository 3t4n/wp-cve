<?php
/**
 * Widget Popular Posts
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_popular_post_widget extends WP_Widget {

	
	function __construct() {
		parent::__construct(
      'ya_pl_pp',// Base ID
			esc_html__( '[YAHMAN Add-ons] Popular Posts', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Show popular posts.', 'yahman-add-ons' ), ) // Args
		);
	}

  /**
   * Set default settings of the widget
   */
  private function default_settings() {

    $defaults = array(
      'popular_post_title' => '',
      'post_not_in' => '',
      'time_period'   => 'all',
      'category_not_in'   => '',
      'number_post'   => 5,
      'archive_rank'   => '',
      'display_style' => '3',
      'pv' => false,
      'include_page' => false,
      'cache' => false,
      'ranking' => true,
      'update' => false,
      'ul_class'   => '',
      'li_class'   => '',
    );

    return $defaults;
  }


  public function widget( $args, $instance ) {

    // Get Widget Settings.
    $settings = wp_parse_args( $instance, $this->default_settings() );

    $pv_key = '_yahman_addons_pv_';
    $period_key = '_yahman_addons_coverage_period_';

    switch ($settings['time_period']){
      case 'all':
      $rank_name = esc_html__('Popular Posts', 'yahman-add-ons');
      $period_value = 1;
      $settings['transient_time'] = 24 * HOUR_IN_SECONDS;
      break;
      case 'yearly':
      $rank_name = esc_html__('Yearly Popular Posts', 'yahman-add-ons');
      $period_value = date('Y');
      $settings['transient_time'] = 24 * HOUR_IN_SECONDS;
      break;
      case 'monthly':
      $rank_name = esc_html__('Monthly Popular Posts', 'yahman-add-ons');
      $period_value = date('Y').date('n');
      $settings['transient_time'] = 24 * HOUR_IN_SECONDS;
      break;
      case 'weekly':
      $rank_name = esc_html__('Weekly Popular Posts', 'yahman-add-ons');
      $period_value = date('Y').date('W');
      $settings['transient_time'] = 12 * HOUR_IN_SECONDS;
      break;
      case 'daily':
      $rank_name = esc_html__('Daily Popular Posts', 'yahman-add-ons');
      $period_value = date('Y').date('n').date('j');
      $settings['transient_time'] = 1 * HOUR_IN_SECONDS;
      break;
      default:
      return;
    }
    $rank_name = $settings['popular_post_title'] != '' ? $settings['popular_post_title'] : $rank_name;
    $include_page  = $settings['include_page'] == '1' ? array( 'post', 'page') : 'post';
    $post_not_in = explode(',', $settings['post_not_in']);
    $category_not_in = explode(',', $settings['category_not_in']);
    $archive_pala = array();
    $archive_name = '';
    if($settings['archive_rank'] == '1'){
      if(is_single()){
        $category = get_the_category();
        $archive_pala = array('category__in' => $category[0]->term_id);
        $archive_name = $category[0]->cat_name.' ';
      }
      if(is_category()){
        $archive_pala = array('category__in' => get_query_var('cat'));
        $archive_name = single_cat_title('', false).' ';
      }
      if(is_tag()){
        $archive_pala = array('tag__in' => get_query_var('tag_id'));
        $archive_name = single_tag_title('', false).' ';
      }
      if(is_author()){
        $archive_pala = array('author__in' => get_query_var('author'));
        global $wp_query;
        $curauth = $wp_query->get_queried_object();
        $archive_name = $curauth->display_name.' ';
      }
      /*if(is_year()){
        $archive_pala = array('date_query' => array(array('year'=> get_query_var('year')),);
      }
      if(is_month());
      if(is_day());*/
    }


    $posts = $ranking_data = null;

    if($settings['cache']){
      $transient_cate = '';

      
      if(!empty($archive_pala)){

        foreach ($archive_pala as $key => $value) {
          $transient_cate = '_'.$value;
        }

      }

      $settings['transient_name'] = $args['widget_id'].$transient_cate;

      $ranking_data = get_transient( $settings['transient_name'] );

    }

    if(empty($ranking_data)){
      $popular_args = array(
        'post_type' => $include_page,
        'meta_key' => $pv_key.$settings['time_period'],
        'post__not_in' => $post_not_in,
        'category__not_in' => $category_not_in,
        'posts_per_page' => $settings['number_post'],
        'ignore_sticky_posts' => true,
        'orderby' => 'meta_value_num',
        'meta_query' => array(
          array(
           'key' => $period_key.$settings['time_period'],
           'value' => $period_value,
           'compare' => '=')
        )
      );
      $popular_args = array_merge($popular_args,$archive_pala);
      $posts = new WP_Query( $popular_args );

      if ( $posts->have_posts() ) {


        $ranking_data = $args['before_widget'];
        $ranking_data .= $args['before_title'] . esc_html($archive_name).esc_html($rank_name) . $args['after_title'];

        require_once YAHMAN_ADDONS_DIR . 'inc/classes/post_list.php';
        $back_data = YAHMAN_ADDONS_POST_LIST::yahman_addons_post_list_output($posts,$settings);

        $ranking_data .= $back_data[0] .$args['after_widget']."\n";
      }


      if($settings['cache'] && $back_data[1] === $settings['number_post']){
        set_transient( $settings['transient_name'], $ranking_data, $settings['transient_time'] );
      }

    }


    echo $ranking_data;

  }
  public function form( $instance ) {

    // Get Widget Settings.
    $settings = wp_parse_args( $instance, $this->default_settings() );

    ?>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'yahman-add-ons' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'popular_post_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_post_title' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['popular_post_title'] ); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'time_period' ) ); ?>">
        <?php esc_html_e( 'the time period', 'yahman-add-ons' ); ?>
      </label>
      <br />
      <label for="<?php echo esc_attr( $this->get_field_id( 'time_period' ) ); ?>_all">
        <input id="<?php echo esc_attr( $this->get_field_id( 'time_period' ) ); ?>_all" name="<?php echo esc_attr( $this->get_field_name( 'time_period' ) ); ?>" type="radio" value="all" <?php checked( 'all', $settings['time_period'] ); ?>/>
        <?php esc_html_e( 'All', 'yahman-add-ons' ); ?>
      </label>
      <label for="<?php echo esc_attr( $this->get_field_id( 'time_period' ) ); ?>_yearly">
        <input id="<?php echo esc_attr( $this->get_field_id( 'time_period' ) ); ?>_yearly" name="<?php echo esc_attr( $this->get_field_name( 'time_period' ) ); ?>" type="radio" value="yearly" <?php checked( 'yearly', $settings['time_period'] ); ?>/>
        <?php esc_html_e( 'Yearly', 'yahman-add-ons' ); ?>
      </label>
      <label for="<?php echo esc_attr( $this->get_field_id( 'time_period' ) ); ?>_monthly">
        <input id="<?php echo esc_attr( $this->get_field_id( 'time_period' ) ); ?>_monthly" name="<?php echo esc_attr( $this->get_field_name( 'time_period' ) ); ?>" type="radio" value="monthly" <?php checked( 'monthly', $settings['time_period'] ); ?>/>
        <?php esc_html_e( 'Monthly', 'yahman-add-ons' ); ?>
      </label>
      <label for="<?php echo esc_attr( $this->get_field_id( 'time_period' ) ); ?>_weekly">
        <input id="<?php echo esc_attr( $this->get_field_id( 'time_period' ) ); ?>_weekly" name="<?php echo esc_attr( $this->get_field_name( 'time_period' ) ); ?>" type="radio" value="weekly" <?php checked( 'weekly', $settings['time_period'] ); ?>/>
        <?php esc_html_e( 'Weekly', 'yahman-add-ons' ); ?>
      </label>
      <label for="<?php echo esc_attr( $this->get_field_id( 'time_period' ) ); ?>_daily">
        <input id="<?php echo esc_attr( $this->get_field_id( 'time_period' ) ); ?>_daily" name="<?php echo esc_attr( $this->get_field_name( 'time_period' ) ); ?>" type="radio" value="daily" <?php checked( 'daily', $settings['time_period'] ); ?>/>
        <?php esc_html_e( 'Daily', 'yahman-add-ons' ); ?>
      </label>
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'number_post' ) ); ?>"><?php esc_html_e( 'Display priorities', 'yahman-add-ons' ); ?></label>
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
      <label for="<?php echo esc_attr( $this->get_field_id( 'category_not_in' ) ); ?>"><?php esc_html_e( 'Disappear when you type category id.', 'yahman-add-ons' ); ?><br />
        <?php echo sprintf( esc_html__('Multiple %s must be separated by a comma.', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
      </label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category_not_in' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category_not_in' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['category_not_in'] ); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'post_not_in' ) ); ?>"><?php esc_html_e( 'Disappear when you type post id.', 'yahman-add-ons' ); ?><br />
        <?php echo sprintf( esc_html__('Multiple %s must be separated by a comma.', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
      </label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_not_in' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_not_in' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['post_not_in'] ); ?>">
    </p>
    <p>
      <input id="<?php echo esc_attr( $this->get_field_id( 'pv' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pv' ) ); ?>" type="checkbox"<?php checked( $settings['pv']); ?> />
      <label for="<?php echo esc_attr( $this->get_field_id( 'pv' ) ); ?>">
        <?php esc_html_e( 'display pv', 'yahman-add-ons' ); ?>
      </label>
    </p>
    <p>
      <input id="<?php echo esc_attr( $this->get_field_id( 'include_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'include_page' ) ); ?>" type="checkbox"<?php checked( $settings['include_page']); ?> />
      <label for="<?php echo esc_attr( $this->get_field_id( 'include_page' ) ); ?>">
        <?php esc_html_e( 'include page', 'yahman-add-ons' ); ?>
      </label>
    </p>
    <p>
      <input id="<?php echo esc_attr( $this->get_field_id( 'archive_rank' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'archive_rank' ) ); ?>" type="checkbox"<?php checked( $settings['archive_rank'] ); ?> />
      <label for="<?php echo esc_attr( $this->get_field_id( 'archive_rank' ) ); ?>">
        <?php esc_html_e( 'Enable archive ranking in archive', 'yahman-add-ons' ); ?>
      </label>
    </p>
    <p>
      <input id="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cache' ) ); ?>" type="checkbox"<?php checked( $settings['cache'] ); ?> />
      <label for="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>">
        <?php esc_html_e( 'Enable cache', 'yahman-add-ons' ); ?>
      </label>
    </p>
    <?php
  }


  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['popular_post_title'] = ( ! empty( $new_instance['popular_post_title'] ) ) ? sanitize_text_field( $new_instance['popular_post_title'] ) : '';


    $instance['display_style'] = ! empty( $new_instance['display_style'] ) ? absint( $new_instance['display_style'] ) : '3';
    $instance['number_post'] = ! empty( $new_instance['number_post'] ) ? absint( $new_instance['number_post'] ) : 5 ;
    $instance['time_period'] = ! empty( $new_instance['time_period'] ) ? sanitize_text_field( $new_instance['time_period'] ) : 'all' ;
    $instance['post_not_in'] = ! empty( $new_instance['post_not_in'] ) ? sanitize_text_field( $new_instance['post_not_in'] ) : '';
    $instance['category_not_in'] = ! empty( $new_instance['category_not_in'] ) ? sanitize_text_field( $new_instance['category_not_in'] ) : '';

    $instance['include_page'] = (bool)$new_instance[ 'include_page' ];
    $instance[ 'archive_rank' ] = (bool)$new_instance[ 'archive_rank' ];
    $instance[ 'cache' ] = (bool)$new_instance[ 'cache' ];
    $instance[ 'pv' ] = (bool)$new_instance[ 'pv' ];
    return $instance;
  }

} // class yahman_addons_popular_post_widget
