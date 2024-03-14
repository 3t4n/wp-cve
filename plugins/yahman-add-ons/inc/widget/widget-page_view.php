<?php
/**
 * Widget Pageview
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_page_view_widget extends WP_Widget {

	
	function __construct() {

    add_action('admin_enqueue_scripts', array($this, 'scripts'));

    parent::__construct(
      'ya_pv',// Base ID
			esc_html__( '[YAHMAN Add-ons] Pageview', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Show PV counter.', 'yahman-add-ons' ), ) // Args
		);
  }


  /**
   * Set default settings of the widget
   */
  private function default_settings() {

    $defaults = array(
      'page_view_title'    => esc_html__( 'Pageview', 'yahman-add-ons' ),
      'counter_image' => false,
    );

    $counter = 0;

    while($counter < 10){
      $defaults['counter_image_'.$counter] = '';
      $defaults['counter_image_id_'.$counter] = '';
      ++$counter;
    }

    $i = 1;
    while($i < 6){
      $defaults['counter_heading_'.$i] = '';
      $defaults['counter_unit_'.$i] = 'pv';
      ++$i;
    }

    return $defaults;
  }


  public function widget( $args, $instance ) {


    // Get Widget Settings.
    $settings = wp_parse_args( $instance, $this->default_settings() );

    $yahman_addons_count = get_option('yahman_addons_count') ;

    $option = get_option('yahman_addons') ;


    echo $args['before_widget'];


    echo $args['before_title'] . esc_html($settings['page_view_title']) . $args['after_title'];
    $i = 1;
    while($i < 6){
      $settings['coverage_period_'.$i] = ( ! empty( $instance['coverage_period_'.$i] ) ) ? sanitize_text_field( $instance['coverage_period_'.$i] ) : 'none';


      if($settings['coverage_period_'.$i] != 'none'){
        echo '<ul class="pv_'.$settings['coverage_period_'.$i].'" style="list-style:none;">';


        $counter_num = $yahman_addons_count['pv'][$settings['coverage_period_'.$i]];

        if($settings['counter_image']){

          $counter_dom = '';

          for ($j = 0; $j < strlen($counter_num); $j++) {

            $img_num = substr($counter_num, $j, 1);

            if( !isset($settings['counter_image_id_'.$img_num]) ) continue;

            $counter_img = wp_get_attachment_image_src( $settings['counter_image_id_'.$img_num] , 'full' );

            if( !$counter_img ) continue;

            $counter_dom .= '<img decoding="async" src="'.$counter_img[0].'"  width="'.$counter_img[1].'" height="'.$counter_img[2].'" />';

          }
          echo '<li>';
          echo '<div class="pv_heading">'.$settings['counter_heading_'.$i].'</div>';
          echo '<div class="pv_image f_box jc_fe ai_c">'.$counter_dom.'</div>';
          echo '</li>';

        }else{
          echo '<li>';
          echo '<div class="pv_heading">'.$settings['counter_heading_'.$i].'</div>';
          echo '<div class="pv_counter" style="text-align:right;">';
          echo '<span class="pv_num">'.$counter_num.'</span>';
          echo '<span class="pv_unit">'.$settings['counter_unit_'.$i].'</span>';
          echo '</div>';
          echo '</li>';
        }





        echo '</ul>';
      }



      ++$i;
    }

    echo $args['after_widget'];


  }
  public function form( $instance ) {

    // Get Widget Settings.
    $settings = wp_parse_args( $instance, $this->default_settings() );

    $period_value = array(
      'none' => esc_html( '-' ),
      'all' => esc_html_x( 'All', 'PV' ,'yahman-add-ons' ),
      'yearly' => esc_html__( 'Yearly', 'yahman-add-ons' ),
      'monthly' => esc_html__( 'Monthly', 'yahman-add-ons' ),
      'weekly' => esc_html__( 'Weekly', 'yahman-add-ons' ),
      'daily' => esc_html__( 'Daily', 'yahman-add-ons' ),
    );

    ?>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'yahman-add-ons' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'page_view_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'page_view_title' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['page_view_title'] ); ?>">
    </p>
    <?php
    $i = 1;
    while($i < 6){
      $settings['coverage_period_'.$i] = ( ! empty( $instance['coverage_period_'.$i] ) ) ? sanitize_text_field( $instance['coverage_period_'.$i] ) : 'none';
      ?>
      <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'coverage_period_'.$i ) ); ?>">
          <?php esc_html_e( 'Coverage period', 'yahman-add-ons' ); ?> #<?php echo esc_attr($i); ?></label><br />
          <select id="<?php echo esc_attr( $this->get_field_id( 'coverage_period_'.$i )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'coverage_period_'.$i )); ?>">
            <?php
            foreach ($period_value as $key => $value) {
              echo '<option ';
              selected( $settings['coverage_period_'.$i], $key );
              echo ' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
            }
            ?>
          </select>
        </p>

        <p>
          <label for="<?php echo esc_attr( $this->get_field_id( 'counter_heading_'.$i ) ); ?>"><?php esc_html_e( 'Heading', 'yahman-add-ons' ); ?></label>
          <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'counter_heading_'.$i ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'counter_heading_'.$i ) ); ?>" type="text" value="<?php echo esc_attr( $settings['counter_heading_'.$i] ); ?>">
        </p>
        <p>
          <label for="<?php echo esc_attr( $this->get_field_id( 'counter_unit_'.$i ) ); ?>"><?php esc_html_e( 'Unit', 'yahman-add-ons' ); ?></label>
          <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'counter_unit_'.$i ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'counter_unit_'.$i ) ); ?>" type="text" value="<?php echo esc_attr( $settings['counter_unit_'.$i] ); ?>">
        </p>
        <?php
        ++$i;
      }
      ?>
      <p>
        <label><?php esc_html_e( 'Counter image', 'yahman-add-ons' ); ?></label>
      </p>
      <p>
        <input id="<?php echo esc_attr( $this->get_field_id( 'counter_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'counter_image' ) ); ?>" type="checkbox"<?php checked( $settings['counter_image'] ); ?> />
        <label for="<?php echo esc_attr( $this->get_field_id( 'counter_image' ) ); ?>"><?php esc_html_e( 'Enable', 'yahman-add-ons' ); ?></label>
      </p>
      <?php
      $counter = 0;

      while($counter < 10){
        ?>

        <p>
          <label for="<?php echo esc_attr($this->get_field_id( 'counter_image_'.$counter ) ); ?>"><?php echo $counter; ?></label>
          <div class="profile_img" style="width: 100%; height:auto;">
            <div class="<?php echo esc_attr($this->get_field_id( 'counter_image_id_'.$counter ) ); ?>_placeholder" style="width: 100%; position: relative; text-align: center; cursor: default;border: 1px dashed #b4b9be;box-sizing: border-box;padding: 9px 0;line-height: 20px; margin: 10px 0;<?php if( !empty( $settings['counter_image_'.$counter] ) ){echo 'display:none;';} ?>"><?php esc_html_e( 'No image selected', 'yahman-add-ons' ); ?></div>
            <img class="<?php echo esc_attr($this->get_field_id( 'counter_image_id_'.$counter )); ?>_media_image custom_media_image" src="<?php if( !empty( $settings['counter_image_'.$counter] ) ){echo esc_url($settings['counter_image_'.$counter]);} ?>" style="max-width: 100%; max-width: 120px; height:auto; margin-bottom: 10px;" decoding="async" />

          </div>
          <input type="hidden" type="text" class="<?php echo esc_attr($this->get_field_id( 'counter_image_id_'.$counter )); ?>_media_id custom_media_id" name="<?php echo esc_attr($this->get_field_name( 'counter_image_id_'.$counter )); ?>" id="<?php echo esc_attr($this->get_field_id( 'counter_image_id_'.$counter )); ?>" value="<?php echo esc_attr($settings['counter_image_id_'.$counter]); ?>" />
          <input type="hidden" type="text" class="<?php echo esc_attr($this->get_field_id( 'counter_image_id_'.$counter )); ?>_media_url custom_media_url" name="<?php echo esc_attr($this->get_field_name( 'counter_image_'.$counter )); ?>" id="<?php echo esc_attr($this->get_field_id( 'counter_image_'.$counter )); ?>" value="<?php echo esc_attr($settings['counter_image_'.$counter]); ?>" >
          <input type="button" value="<?php esc_html_e( 'Clear Image', 'yahman-add-ons' ); ?>" class="button <?php echo esc_attr($this->get_field_id( 'counter_image_id_'.$counter )); ?>_remove-button custom_media_clear" id="<?php echo esc_attr($this->get_field_id( 'counter_image_id_'.$counter )); ?>" style="<?php if( !empty( $settings['counter_image_'.$counter] ) ){echo 'display:inline-block;';}else{echo 'display:none;';} ?>" />
          <input type="button" value="<?php esc_html_e( 'Select Image', 'yahman-add-ons' ); ?>" class="button upload-button custom_media_upload" id="<?php echo esc_attr($this->get_field_id( 'counter_image_id_'.$counter )); ?>"/>
        </p>

        <?php
        ++$counter;
      }


    }


    public function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['page_view_title'] = ( ! empty( $new_instance['page_view_title'] ) ) ? sanitize_text_field( $new_instance['page_view_title'] ) : '';
      $i = 1;
      while($i < 6){
        $instance['coverage_period_'.$i] = ( ! empty( $new_instance['coverage_period_'.$i] ) ) ? sanitize_text_field( $new_instance['coverage_period_'.$i] ) : 'none';
        $instance['counter_heading_'.$i] = ( ! empty( $new_instance['counter_heading_'.$i] ) ) ? sanitize_text_field( $new_instance['counter_heading_'.$i] ) : '';
        $instance['counter_unit_'.$i] = ( ! empty( $new_instance['counter_unit_'.$i] ) ) ? sanitize_text_field( $new_instance['counter_unit_'.$i] ) : '';
        ++$i;
      }

      $instance['counter_image'] = ( ! empty( $new_instance['counter_image'] ) ) ? (bool) $new_instance['counter_image']  : false;

      $counter = 0;
      while($counter < 10){
        $instance['counter_image_'.$counter] = ( ! empty( $new_instance['counter_image_'.$counter] ) ) ? esc_url( $new_instance['counter_image_'.$counter] ) : '';
        $instance['counter_image_id_'.$counter] = ( ! empty( $new_instance['counter_image_id_'.$counter] ) ) ? sanitize_text_field( $new_instance['counter_image_id_'.$counter] ) : '';
        ++$counter;
      }
      return $instance;
    }

    public function scripts($hook){


      if ($hook == 'widgets.php' || $hook == 'customize.php') {

        wp_enqueue_script( 'media-upload' );
        wp_enqueue_media();
        wp_enqueue_script('yahman_addons_media_uploader', YAHMAN_ADDONS_URI . 'assets/js/customizer/media-uploader.min.js', array('media-upload'));

      }

    }

} // class yahman_addons_popular_post_widget
