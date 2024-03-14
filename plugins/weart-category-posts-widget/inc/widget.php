<?php
class weart_featured_widget extends WP_Widget {

  // CONSTRUCT
    function __construct() {
      parent::__construct(
        /* Base   ID   */ 'weart_featured_widget',
        /* Widget name */ esc_html__('Weart Category Posts', 'weart-category-posts-widget')
      );
		  add_action( 'wp_enqueue_scripts', array($this,'enqueue_scripts'));
    }
  // end

  // ENQUEUE STYLE
    function enqueue_scripts(){
      wp_register_style( 'weart-featured-widget-style', WEART_WIDGET_URL.'css/style.css', false, WEART_WIDGET_VER );
      wp_enqueue_style( 'weart-featured-widget-style' );
    }
  // end

  // DISPLAY
    public function widget( $args, $instance ) {

      // vars
        // global WP vars
        global $post;
        // title of the widget
        $title = apply_filters('widget_title', $instance['title'] );
        // number of posts
        $number = $instance['number'];
        // category of posts
        $category = $instance['category'];
        // date
        $date = $instance['date'];
        // position
        $position = $instance['position'];
        // excerpt
        $excerpt = $instance['excerpt'];
        // featured
        $featured = $instance['featured'];
      // end

      // widget header
        // themes widget settings
        echo wpautop( $args['before_widget'] );
        // if widget has a title
        if ( $title ) { echo wpautop( $args['before_title'] . $title . $args['after_title'] ); }
      // end

      // widget body
        ?><div class="wfpw">
          <?php $recent = new WP_Query(array(
            'posts_per_page' => $number,
            'cat'=>$category ));
          $wfpw_num = 1; while($recent->have_posts()) : $recent->the_post(); ?>
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
              <div class="wfpw-row">
                <?php if ( has_post_thumbnail() ) { ?>
                  <div class="wfpw-img <?php echo esc_attr($featured); ?>">
                    <?php if($position): ?><span class="wfpw-num"><?php echo esc_attr($wfpw_num) ?></span><?php endif; ?>
                    <div class="wfpw-img-file" style="background-image: url( <?php the_post_thumbnail_url(); ?> );"></div>
                  </div>
                <?php } ?>
                <div class="wfpw-text">
                  <h3 class="wfpw-title"><?php the_title(); ?></h3>
                  <?php if($excerpt): the_excerpt(); endif; ?>
                  <?php if($date): ?>
                    <time class="wfpw-date" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time(get_option('date_format')); ?></time>
                  <?php endif; ?>
                </div>
              </div>
            </a>
          <?php $wfpw_num++; endwhile; wp_reset_postdata(); ?>
        </div><?php
      // end

      // widget footer
        echo wpautop( $args['after_widget'] );
      // end
    }
  // end

  // FORM
    public function form( $instance ) {
      //defaults
        $defaults = array(
          'title' => esc_html__('Featured Posts','weart-category-posts-widget'),
          'number' => 5,
          'category' => '',
          'position' => true,
          'date' => true,
          'excerpt' => false,
          'featured' => 'small',
          'popular_days' => 30
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
      // end
      ?>

        <!-- title -->
          <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
              <?php esc_html_e('Title:','weart-category-posts-widget') ?>
            </label>
            <input
              id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
              name="<?php echo esc_attr( $this->get_field_name( 'title' )); ?>"
              value="<?php echo esc_attr( $instance['title'] ); ?>"
              type="text"
              class="widefat"
            />
          </p>
        <!-- end -->

        <!-- posts num -->
          <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>">
              <?php esc_html_e('Number of posts to display:','weart-category-posts-widget'); ?>
            </label>
            <input
              id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
              name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>"
              value="<?php echo esc_attr( $instance['number'] ); ?>"
              type="number"
              class="widefat"
            />
          </p>
        <!-- end -->

        <!-- category -->
          <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>">
              <?php esc_html_e('Category of posts','weart-category-posts-widget'); ?>
            </label>
            <select
              id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"
              name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>"
              class="widefat">

              <option><?php esc_html_e('No Category / Fresh posts', 'weart-category-posts-widget') ?></option>
              <?php // Get categories as array
              $categories = get_categories( $args ); foreach ( $categories as $category ) :
              // Check if current term ID is equal to term ID stored in database
              $selected = ( $instance['category'] ==  $category->term_id  ) ? 'selected' : ''; ?>
                <option value="<?php echo esc_attr($category->term_id); ?>"   <?php echo esc_attr($selected) ?>>
                  <?php echo esc_attr($category->name) ?>
                </option>
              <?php endforeach; ?>

            </select>
          </p>
        <!-- end -->

        <!-- featured-img -->
          <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>">
              <?php esc_html_e('Featured image options','weart-category-posts-widget'); ?>
            </label>
            <select
              id="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>"
              name="<?php echo esc_attr( $this->get_field_name( 'featured' ) ); ?>"
              class="widefat">
              <?php $fI = $instance['featured'];
              $s = 'selected'; ?>
              <option value="small" <?php if($fI == "small"){ echo $s; } ?>>
                <?php esc_html_e('Thumbnail size', 'weart-category-posts-widget') ?>
              </option>
              <option value="full" <?php if($fI == "full"){ echo $s; } ?>>
                <?php esc_html_e('Full-width', 'weart-category-posts-widget') ?>
              </option>
              <option value="no" <?php if($fI == "no"){ echo $s; } ?>>
                <?php esc_html_e('No image', 'weart-category-posts-widget') ?>
              </option>
            </select>
          </p>
        <!-- end -->

        <!-- position -->
          <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'position' ) ); ?>">
              <input
                id="<?php echo esc_attr( $this->get_field_id( 'position' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'position' ) ); ?>"
                value="1" <?php checked( $instance['position'], 1 ); ?>
                type="checkbox"
                class="checkbox"
              />
              <?php esc_html_e('Display the position of the post?','weart-category-posts-widget'); ?>
            </label>
          </p>
        <!-- end -->

        <!-- date -->
          <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>">
              <input
                id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>"
                value="1" <?php checked( $instance['date'], 1 ); ?>
                type="checkbox"
                class="checkbox"
              />
              <?php esc_html_e('Display the date above the title?','weart-category-posts-widget'); ?>
            </label>
          </p>
        <!-- end -->

        <!-- excerpt -->
          <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>">
              <input
                id="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'excerpt' ) ); ?>"
                value="1" <?php checked( $instance['excerpt'], 1 ); ?>
                type="checkbox"
                class="checkbox"
              />
              <?php esc_html_e('Display the excerpts?','weart-category-posts-widget'); ?>
            </label>
          </p>
        <!-- end -->

      <?php
    }
  // end

  // UPDATING DATAS
    public function update( $new_instance, $old_instance ) {
      $instance = array();

      // title
      $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
      // number of posts
      $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
      // category
      $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';
      // Position
      $instance['position'] = ( ! empty( $new_instance['position'] ) ) ? strip_tags( $new_instance['position'] ) : '';
      // Date
      $instance['date'] = ( ! empty( $new_instance['date'] ) ) ? strip_tags( $new_instance['date'] ) : '';
      // Excerpt
      $instance['excerpt'] = ( ! empty( $new_instance['excerpt'] ) ) ? strip_tags( $new_instance['excerpt'] ) : '';
      // featured
      $instance['featured'] = ( ! empty( $new_instance['featured'] ) ) ? strip_tags( $new_instance['featured'] ) : '';

      return $instance;
    }
  // end

}