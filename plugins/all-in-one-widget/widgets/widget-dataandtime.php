<?php
/**
 * Author Date and Time Class
 */
if ( !defined('ABSPATH')) exit;

class Themeidol_Date_and_Time extends WP_Widget {

  /*--------------------------------------------------*/
  /* Constructor
  /*--------------------------------------------------*/

  /**
   * Specifies the classname and description, instantiates the widget,
   */
  public function __construct() {


    parent::__construct(
      'themeidol-dataandtime',
      __( 'Themeidol-Date and Time', 'themeidol-all-widget' ),
      array(
        'classname'  => 'themeidol_widget_date_time',
        'description' => __( 'Show the local date and/or time.',
          'themeidol-all-widget')
      )
    );

    // Register admin styles and scripts
    add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

    // Register site styles and scripts
    add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );

    // Refreshing the widget's cached output with each new post
    add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
    add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
    add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

  } // end constructor



  /*--------------------------------------------------*/
  /* Widget API Functions
  /*--------------------------------------------------*/

  /**
   * Outputs the content of the widget.
   *
   * @param array args  The array of form elements
   * @param array instance The current instance of the widget
   */
  public function widget( $args, $instance ) {
    // Check if there is a cached output
    $cache = wp_cache_get( 'themeidol-dataandtime', 'widget' );

    if ( !is_array( $cache ) ) {
      $cache = array();
    }

    if ( !isset( $args['widget_id'] ) ) {
      $args['widget_id'] = $this->id;
    }

    if ( isset( $cache[ $args['widget_id'] ] ) ) {
      return print $cache[ $args['widget_id'] ];
    }

    //Widget settings
    $time_format = $instance['time_format'];
    $date_format = $instance['date_format'];
    $font_family = $instance['font_family'];
    $font_size = $instance['font_size'];
    $text_color = $instance ['text_color'];
    $background_color = $instance ['background_color'];

    extract( $args, EXTR_SKIP );
    $before_widget = str_replace('widget ', 'idol-widget ',  $before_widget);
    $widget_string = $before_widget;

    ob_start();
    ?>
    <!-- This file is used to markup the public-facing widget. -->
    <div id="date-time" class="date-time" style="color: <?php echo $text_color ?>;
      background-color: <?php echo $background_color ?>;
      font-family: <?php echo $font_family ?>;
      font-size: <?php echo $font_size ?>;">
      <div class="date"></div>
      <div class="time"></div>
    </div>
    <script type="text/javascript">
      update('<?php echo $args["widget_id"]; ?>',
        '<?php echo $time_format; ?>',
        '<?php echo $date_format; ?>');
    </script>
    <?php
   
    $widget_string .= ob_get_clean();
    $widget_string .= $after_widget;

    $cache[ $args['widget_id'] ] = $widget_string;

    wp_cache_set( 'themeidol-dataandtime', $cache, 'widget' );

    print $widget_string;

  } // end widget


  public function flush_widget_cache() {
    wp_cache_delete( 'themeidol-dataandtime', 'widget' );
  }
  /**
   * Processes the widget's options to be saved.
   *
   * @param   array   new_instance    The new instance of values to be
   *                                  generated via the update.
   * @param   array   old_instance    The previous instance of values before
   *                                  the update.
   */
  public function update( $new_instance, $old_instance ) {

    $instance = $old_instance;

    $instance['time_format'] = $new_instance['time_format'];
    $instance['date_format'] = $new_instance['date_format'];
    $instance['font_family'] = $new_instance['font_family'];
    $instance['font_size'] = $new_instance['font_size'];
    $instance['text_color'] = $new_instance['text_color'];
    $instance['background_color'] = $new_instance['background_color'];

    return $instance;

  } // end widget

  /**
   * Generates the administration form for the widget.
   *
   * @param   array   instance        The array of keys and values for the
   *                                  widget.
   */
  public function form( $instance ) {
    // Define default values for variables.
    $instance = wp_parse_args(
      (array) $instance,
      array(
        'time_format' => '12-hour-seconds',
        'date_format' => 'long',
        'font_family' => 'Arial, Arial, Helvetica, sans-serif',
        'font_size' => '20px',
        'text_color' => '#000',
        'background_color' => 'transparent'
      )
    );

    // Store the values of the widget in their own variables.
    $text_color = esc_attr( $instance['text_color'] );
    $background_color = esc_attr( $instance['background_color'] );

    // Display the admin form
    ?>
    <!-- This file is used to markup the administration form of the widget. -->
      <p>
        <label for="<?php echo $this->get_field_id( 'time_format' ); ?>">
          <?php _e( 'Time Format', 'themeidol-all-widget' ); ?>:
        </label>
        <select id="<?php echo $this->get_field_id( 'time_format' ); ?>"
          name="<?php echo $this->get_field_name( 'time_format' ); ?>"
          class="widefat">
          <?php $this->render_time_format( $instance ); ?>
        </select>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'date_format' ); ?>">
          <?php _e( 'Date Format', 'themeidol-all-widget' ); ?>:
        </label>
        <select id="<?php echo $this->get_field_id( 'date_format' ); ?>"
          name="<?php echo $this->get_field_name( 'date_format' ); ?>"
          class="widefat">
          <?php $this->render_date_format( $instance ); ?>
        </select>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'font_family' ); ?>">
          <?php _e( 'Font Family', 'themeidol-all-widget' ); ?>:
        </label>
        <select id="<?php echo $this->get_field_id( 'font_family' ); ?>"
          name="<?php echo $this->get_field_name( 'font_family' ); ?>"
          class="widefat">
          <?php $this->render_font_family( $instance ); ?>
        </select>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'font_size' ); ?>">
          <?php _e( 'Font Size', 'themeidol-all-widget' ); ?>:
        </label>
        <select id="<?php echo $this->get_field_id( 'font_size' ); ?>"
          name="<?php echo $this->get_field_name( 'font_size' ); ?>"
          class="widefat">
          <?php $this->render_font_size( $instance ); ?>
        </select>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'text_color' ); ?>">
          <?php _e( 'Text Color', 'themeidol-all-widget' ) ?>:
        </label>
        <input id="<?php echo $this->get_field_id( 'text_color' ); ?>"
          name="<?php echo $this->get_field_name( 'text_color' ); ?>"
          value="<?php echo $text_color; ?>"
          type="text" class="color-picker" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'background_color' ); ?>">
          <?php _e( 'Background Color', 'themeidol-all-widget' ) ?>:
        </label>
        <input id="<?php echo $this->get_field_id( 'background_color' ); ?>"
          name="<?php echo $this->get_field_name( 'background_color' ); ?>"
          value="<?php echo $background_color; ?>"
          type="text" class="color-picker" />
      </p>
    <?php
  } // end form


  /**
   * Registers and enqueues admin-specific styles.
   */
  public function register_admin_styles() {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'themeidol-dataandtime'.'-admin-styles', THEMEIDOL_WIDGET_CSS_URL.'datetime-admin.css' );
  } // end register_admin_styles

  /**
   * Registers and enqueues admin-specific JavaScript.
   */
  public function register_admin_scripts() {
    wp_enqueue_script( 'themeidol-dataandtime'.'-admin-script', THEMEIDOL_WIDGET_JS_URL.'datetime-admin.js', array('jquery', 'wp-color-picker') );
  } // end register_admin_scripts

  /**
   * Registers and enqueues widget-specific styles.
   */
  public function register_widget_styles() {
    wp_enqueue_style( 'themeidol-dataandtime'.'-widget-styles', THEMEIDOL_WIDGET_CSS_URL.'datetime-style.css');
  } // end register_widget_styles

  /**
   * Registers and enqueues widget-specific scripts.
   */
  public function register_widget_scripts() {
    wp_enqueue_script( 'themeidol-dataandtime'.'-script', THEMEIDOL_WIDGET_JS_URL.'datetime-widget.js', array('jquery') );
  } // end register_widget_scripts

  /**
   * Render options in the Time Format dropdown.
   *
   * @since     1.1.0
   */
  public function render_time_format( $instance ) {
    $formats = array(
      "none" => "None",
      "12-hour" => date("g:i A", current_time( 'timestamp', 0 ) ),
      "12-hour-seconds" => date("g:i:s A", current_time( 'timestamp', 0 ) ),
      "24-hour" => date("G:i", current_time( 'timestamp', 0 ) ),
      "24-hour-seconds" => date("G:i:s", current_time( 'timestamp', 0 ) ),
    );

    foreach( $formats as $key => $value ) {
      $selected = ( $instance['time_format'] == $key ) ?
        'selected="selected"' : '';
      echo '<option value="' . $key . '" ' . $selected . '>' . $value .
        '</option>';
    }
  }

  /**
   * Render options in the Date Format dropdown.
   *
   * @since     1.1.0
   */
  public function render_date_format( $instance ) {
    $formats = array(
      "none" => "None",
      "short" => date( "n/j/Y", current_time( 'timestamp', 0 ) ),
      "european" => date( "j/n/Y", current_time( 'timestamp', 0 ) ),
      "medium" => date( "M j Y", current_time( 'timestamp', 0 ) ),
      "long" => date( "F j, Y", current_time( 'timestamp', 0 ) ),
    );

    foreach( $formats as $key => $value ) {
      $selected = ( $instance['date_format'] == $key ) ?
        'selected="selected"' : '';
      echo '<option value="' . $key . '" ' . $selected . '>' . $value .
        '</option>';
    }
  }

  /**
   * Render options in the Font Family dropdown.
   *
   * @since     1.1.0
   */
  public function render_font_family( $instance ) {
    $font_families = array(
      "Arial, Arial, Helvetica, sans-serif" => "Arial",
      "Comic Sans MS, Comic Sans MS, cursive" => "Comic Sans MS",
      "Courier New, Courier New, Courier, monospace" => "Courier New",
      "Georgia, Georgia, serif" => "Georgia",
      "Lucida Sans Unicode, Lucida Grande, sans-serif" => "Lucida Sans Unicode",
      "Tahoma, Geneva, sans-serif" => "Tahoma",
      "Times New Roman, Times, serif" => "Times New Roman",
      "Trebuchet MS, Helvetica, sans-serif" => "Trebuchet MS",
      "Verdana, Verdana, Geneva, sans-serif" => "Verdana",
    );

    foreach( $font_families as $key => $value ) {
      $selected = ( $instance['font_family'] == $key ) ?
        'selected="selected"' : '';
      echo '<option value="' . $key . '" ' . $selected . '>' . $value .
        '</option>';
    }
  }

  /**
   * Render options in the Font Size dropdown.
   *
   * @since     1.1.0
   */
  public function render_font_size( $instance ) {
    $font_sizes = array(
      "8px" => "8",
      "9px" => "9",
      "10px" => "10",
      "11px" => "11",
      "12px" => "12",
      "14px" => "14",
      "16px" => "16",
      "18px" => "18",
      "20px" => "20",
      "22px" => "22",
      "24px" => "24",
      "26px" => "26",
      "28px" => "28",
      "36px" => "36",
      "48px" => "48",
      "72px" => "72",
    );

    foreach( $font_sizes as $key => $value ) {
      $selected = ( $instance['font_size'] == $key ) ?
        'selected="selected"' : '';
      echo '<option value="' . $key . '" ' . $selected . '>' . $value .
        '</option>';
    }
  }
} // end class

add_action( 'widgets_init', create_function( '',  'register_widget("Themeidol_Date_and_Time");' ) );