<?php
/**
 * Widget Another Profile
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_another_profile_widget extends WP_Widget {

	
	function __construct() {

		add_action('admin_enqueue_scripts', array($this, 'scripts'));

		parent::__construct(
			'ya_another', // Base ID
			esc_html__( '[YAHMAN Add-ons] Another Profile', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Support another profile for Widget', 'yahman-add-ons' ), ) // Args
		);

	}

  /**
   * Set default settings of the widget
   */
  private function default_settings() {

  	$defaults = array(
  		'title'    => esc_html__( 'About me', 'yahman-add-ons'),
  		'name' => '',
  		'image' => '',
      'image_id' => '',
      'image_bg' => '',
      'image_bg_id' => '',
      'image_shape'   => 'circle',
      'text' => '',
      'read_more_url' => '',
      'read_more_text' => esc_html__( 'Read More', 'yahman-add-ons' ),
      'read_more_url' => '',
      'read_more_blank' => false,
      'icon_shape' => 'icon_square',
      'icon_size' => 'icon_medium',
      'icon_align'    => 'center',
      'icon_user_color' => '',
      'icon_user_hover_color' => '',
      'icon_tooltip' => false,
    );

  	return $defaults;
  }

  public function widget( $args, $instance ) {

  	$this->settings = wp_parse_args( $instance, $this->default_settings() );
  	$settings = $this->settings;

  	$settings['image_shape']  = $settings['image_shape'] === 'circle' ? ' br50' : '';

  	$settings['read_more_blank']  = $settings['read_more_blank'] === true ? ' target="_blank"' : '';


  	$sns_info['icon_shape']  = $settings['icon_shape'];
  	$sns_info['icon_size']  = $settings['icon_size'];
  	$sns_info['icon_user_color']  = $settings['icon_user_color'];
  	$sns_info['icon_user_hover_color']  = $settings['icon_user_hover_color'];
  	$sns_info['icon_tooltip']  = $settings['icon_tooltip'];
  	$sns_info['icon_tooltip']  = $sns_info['icon_tooltip'] === true ? ' sns_tooltip' : '';

    $sns_info['icon_align'] = ' jc_c';

    switch ($settings['icon_align']){
      case 'left':
      $sns_info['icon_align'] = 'jc_fs';
      break;

      case 'right':
      $sns_info['icon_align'] = ' jc_fe';
      break;

      case 'space_between':
      $sns_info['icon_align'] = ' jc_sb';
      break;

      case 'space_around':
      $sns_info['icon_align'] = ' jc_sa';
      break;

      default:
    }


    echo $args['before_widget'];
	  //echo esc_html(apply_filters( 'widget_title', $instance['title'] ));

    if ( $settings['title'] ) {
      echo $args['before_title'] . esc_html($settings['title']) . $args['after_title'];
    }
    require_once YAHMAN_ADDONS_DIR . 'inc/widget/profile_output.php';
    yahman_addons_profile_widget_output($settings);

    $i = 1;
    while($i <= 5){
      $sns_info['account'][$i] = $sns_info['share'][$i] = '';
      $sns_info['icon'][$i] = ! empty( $instance['sns_icon_'.$i] ) ? esc_attr( $instance['sns_icon_'.$i] ) : 'none';
      $sns_info['url'][$i] = ! empty( $instance['sns_url_'.$i] ) ? esc_attr( $instance['sns_url_'.$i] ) : '';
      if($sns_info['icon'][$i] != 'none')$sns_info['class'] = ' pf_sns_wrap';
      ++$i;
    }
    $sns_info['loop'] = 5;
    $sns_info['class'] = $sns_info['icon_align'];

    $sns_info['widget_id'] = $args['widget_id'];

    if($sns_info['class'] != ''){
      require_once YAHMAN_ADDONS_DIR . 'inc/widget/social-output.php';
      yahman_addons_social_output($sns_info);
    }

    echo '</div>';
    echo $args['after_widget'];
  }
  public function form( $instance ) {
    // Get Widget Settings.
  	$settings = wp_parse_args( $instance, $this->default_settings() );

  	require_once YAHMAN_ADDONS_DIR . 'inc/social-list.php';

  	?>
  	<p>
  		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'yahman-add-ons' ); ?></label>
  		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>">
  	</p>
  	<p>
  		<label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php esc_html_e( 'Name', 'yahman-add-ons' ); ?></label>
  		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['name'] ); ?>">
  	</p>



  	<p>
  		<label for="<?php echo esc_attr($this->get_field_id( 'image' ) ); ?>"><?php esc_html_e( 'Profile image', 'yahman-add-ons' ); ?></label>
  		<div class="profile_img" style="width: 100%; height:auto;">
  			<div class="<?php echo esc_attr($this->get_field_id( 'image_id' ) ); ?>_placeholder" style="width: 100%; position: relative; text-align: center; cursor: default;border: 1px dashed #b4b9be;box-sizing: border-box;padding: 9px 0;line-height: 20px; margin: 10px 0;<?php if( !empty( $settings['image'] ) ){echo 'display:none;';} ?>"><?php esc_html_e( 'No image selected', 'yahman-add-ons' ); ?></div>
  			<img class="<?php echo esc_attr($this->get_field_id( 'image_id' )); ?>_media_image custom_media_image" src="<?php if( !empty( $settings['image'] ) ){echo esc_url($settings['image']);} ?>" style="width: 100%; max-width: 120px; height:auto; margin-bottom: 10px;" decoding="async" />

  		</div>
  		<input type="hidden" type="text" class="<?php echo esc_attr($this->get_field_id( 'image_id' )); ?>_media_id custom_media_id" name="<?php echo esc_attr($this->get_field_name( 'image_id' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'image_id' )); ?>" value="<?php echo esc_attr($settings['image_id']); ?>" />
  		<input type="hidden" type="text" class="<?php echo esc_attr($this->get_field_id( 'image_id' )); ?>_media_url custom_media_url" name="<?php echo esc_attr($this->get_field_name( 'image' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'image' )); ?>" value="<?php echo esc_attr($settings['image']); ?>" >
  		<input type="button" value="<?php esc_html_e( 'Clear Image', 'yahman-add-ons' ); ?>" class="button <?php echo esc_attr($this->get_field_id( 'image_id' )); ?>_remove-button custom_media_clear" id="<?php echo esc_attr($this->get_field_id( 'image_id' )); ?>" style="<?php if( !empty( $settings['image'] ) ){echo 'display:inline-block;';}else{echo 'display:none;';} ?>" />
  		<input type="button" value="<?php esc_html_e( 'Select Image', 'yahman-add-ons' ); ?>" class="button upload-button custom_media_upload" id="<?php echo esc_attr($this->get_field_id( 'image_id' )); ?>"/>
  	</p>

  	<p>
  		<label for="<?php echo esc_attr( $this->get_field_id( 'image_shape' ) ); ?>">
  			<?php esc_html_e( 'Profile image display shape', 'yahman-add-ons' ); ?></label><br />
  			<select id="<?php echo esc_attr( $this->get_field_id( 'image_shape' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_shape' )); ?>">
  				<option <?php echo selected( $settings['image_shape'], 'circle' ); ?> value="circle" ><?php esc_html_e( 'Circle', 'yahman-add-ons' ); ?></option>
  				<option <?php echo selected( $settings['image_shape'], 'square' ); ?> value="square" ><?php esc_html_e( 'Square', 'yahman-add-ons' ); ?></option>
  			</select>
  		</p>

  		<p>
  			<label for="<?php echo esc_attr($this->get_field_id( 'image_bg' )); ?>"><?php esc_html_e( 'Background image', 'yahman-add-ons' ); ?></label>
  			<div class="profile_bg_img" style="width: 100%; height:auto;">
  				<div class="<?php echo esc_attr($this->get_field_id( 'image_bg_id' )); ?>_placeholder" style="width: 100%; position: relative; text-align: center; cursor: default;border: 1px dashed #b4b9be;box-sizing: border-box;padding: 9px 0;line-height: 20px; margin: 10px 0; <?php if( !empty( $settings['image_bg'] ) ){echo 'display:none;';} ?>"><?php esc_html_e( 'No image selected', 'yahman-add-ons' ); ?></div>
  				<img class="<?php echo esc_attr($this->get_field_id( 'image_bg_id' )); ?>_media_image custom_media_image" src="<?php if( !empty( $settings['image_bg'] ) ){echo esc_url($settings['image_bg']);} ?>" style="width: 100%; max-width: 316px; height:auto; margin-bottom: 10px;" decoding="async" />

  			</div>
  			<input type="hidden" type="text" class="<?php echo esc_attr($this->get_field_id( 'image_bg_id' )); ?>_media_id custom_media_id" name="<?php echo esc_attr($this->get_field_name( 'image_bg_id' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'image_bg_id' )); ?>" value="<?php echo esc_attr($settings['image_bg_id']); ?>" />
  			<input type="hidden" type="text" class="<?php echo esc_attr($this->get_field_id( 'image_bg_id' )); ?>_media_url custom_media_url" name="<?php echo esc_attr($this->get_field_name( 'image_bg' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'image_bg' )); ?>" value="<?php echo esc_url($settings['image_bg']); ?>" >
  			<input type="button" value="<?php esc_html_e( 'Clear Image', 'yahman-add-ons' ); ?>" class="button <?php echo esc_attr($this->get_field_id( 'image_bg_id' )); ?>_remove-button custom_media_clear" id="<?php echo esc_attr($this->get_field_id( 'image_bg_id' )); ?>" style="<?php if( !empty( $settings['image_bg'] ) ){echo 'display:inline-block;';}else{echo 'display:none;';} ?>" />
  			<input type="button" value="<?php esc_html_e( 'Select Image', 'yahman-add-ons' ); ?>" class="button upload-button custom_media_upload" id="<?php echo esc_attr($this->get_field_id( 'image_bg_id' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'image_bg_id' )); ?>" />
  		</p>

  		<p>
  			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>">
  				<?php esc_html_e( 'Profile text', 'yahman-add-ons' ); ?></label><br />
  				<textarea id="<?php echo esc_attr($this->get_field_id( 'text' )); ?>" rows="5" style="width:100%;" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo $settings['text']; ?></textarea>
  			</p>

  			<p>
  				<label for="<?php echo esc_attr( $this->get_field_id( 'read_more_url' ) ); ?>"><?php esc_html_e( 'Read more URL', 'yahman-add-ons' ); ?></label>
  				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'read_more_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'read_more_url' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['read_more_url'] ); ?>">
  			</p>

  			<p>
  				<label for="<?php echo esc_attr( $this->get_field_id( 'read_more_text' ) ); ?>"><?php esc_html_e( 'Read more text', 'yahman-add-ons' ); ?></label>
  				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'read_more_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'read_more_text' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['read_more_text'] ); ?>">
  			</p>
  			<p>
  				<input id="<?php echo esc_attr( $this->get_field_id( 'read_more_blank' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'read_more_blank' ) ); ?>" type="checkbox"<?php checked( $settings['read_more_blank'] ); ?> />
  				<label for="<?php echo esc_attr( $this->get_field_id( 'read_more_blank' ) ); ?>"><?php esc_html_e( 'Read more link open new window.', 'yahman-add-ons' ); ?></label>
  			</p>



  			<hr>

  			<p>
  				<label for="<?php echo esc_attr( $this->get_field_id( 'icon_shape' ) ); ?>">
  					<?php esc_html_e( 'Display style', 'yahman-add-ons' ); ?>
  				</label><br />
  				<select id="<?php echo esc_attr( $this->get_field_id( 'icon_shape' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_shape' )); ?>">
  					<?php
  					foreach (yahman_addons_social_shape_list() as $key => $value) {
  						echo '<option '. selected( $settings['icon_shape'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
  					}
  					?>
  				</select>
  			</p>

        <p>
          <label for="<?php echo esc_attr( $this->get_field_id( 'icon_align' ) ); ?>">
            <?php esc_html_e( 'Align', 'yahman-add-ons' ); ?>
          </label><br />
          <select id="<?php echo esc_attr( $this->get_field_id( 'icon_align' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_align' )); ?>">
            <?php
            foreach (yahman_addons_social_align_list() as $key => $value) {
              echo '<option '. selected( $settings['icon_align'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
            }
            ?>
          </select>
        </p>

        <p>
          <label for="<?php echo esc_attr( $this->get_field_id( 'icon_size' ) ); ?>">
           <?php esc_html_e( 'Icon Size', 'yahman-add-ons' ); ?>
         </label><br />
         <select id="<?php echo esc_attr( $this->get_field_id( 'icon_size' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_size' )); ?>">
           <?php
           foreach (yahman_addons_social_size_list() as $key => $value) {
            echo '<option '. selected( $settings['icon_size'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
          }
          ?>
        </select>
      </p>
      <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'icon_user_color' ) ); ?>" style="display:block;"><?php esc_html_e( 'Specifies the color of the icon.', 'yahman-add-ons'  ); ?></label>
        <input class="ya_color-picker" id="<?php echo esc_attr( $this->get_field_id( 'icon_user_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_user_color' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['icon_user_color'] ); ?>" data-alpha-enabled="true" data-alpha-color-type="hex" />
      </p>
      <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'icon_user_hover_color' ) ); ?>" style="display:block;"><?php esc_html_e( 'Specifies the color of hover.', 'yahman-add-ons'  ); ?></label>
        <input class="ya_color-picker" id="<?php echo esc_attr( $this->get_field_id( 'icon_user_hover_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_user_hover_color' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['icon_user_hover_color'] ); ?>" data-alpha-enabled="true" data-alpha-color-type="hex" />
      </p>

      <p>
        <input id="<?php echo esc_attr( $this->get_field_id( 'icon_tooltip' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_tooltip' ) ); ?>" type="checkbox"<?php checked( $settings['icon_tooltip'] ); ?> />
        <label for="<?php echo esc_attr( $this->get_field_id( 'icon_tooltip' ) ); ?>"><?php esc_html_e( 'Tool tip', 'yahman-add-ons' ); ?></label>
      </p>


      <?php
      $i = 1;

      $sns_name = yahman_addons_social_name_list();
      unset($sns_name['buffer']);
      unset($sns_name['digg']);
      unset($sns_name['evernote']);
      unset($sns_name['mail']);
      unset($sns_name['messenger']);
      unset($sns_name['pocket']);
      unset($sns_name['reddit']);
      unset($sns_name['whatsapp']);
      unset($sns_name['print']);

      while($i <= 5){
        $settings['sns_icon_'.$i] = ! empty( $instance['sns_icon_'.$i] ) ? esc_attr( $instance['sns_icon_'.$i] ) : 'none';
        $settings['sns_url_'.$i] = ! empty( $instance['sns_url_'.$i] ) ? esc_attr( $instance['sns_url_'.$i] ) : '';
        ?>
        <p>
         <label for="<?php echo esc_attr( $this->get_field_id( 'sns_icon_'.$i ) ); ?>">
          <?php  echo sprintf(esc_html__( 'Social Icon #%s', 'yahman-add-ons'),esc_html($i)); ?>
        </label><br />
        <select id="<?php echo esc_attr($this->get_field_id( 'sns_icon_'.$i )); ?>" name="<?php echo esc_attr($this->get_field_name( 'sns_icon_'.$i )); ?>">
         <?php
         foreach($sns_name as $account => $account_info){
           $selected = selected( $settings['sns_icon_'.$i], $account, false );
           echo '<option '.esc_attr($selected).' value="'.esc_attr($account).'" >'.esc_html($account_info['name']).'</option>';
         }
         ?>
       </select>
       <br />
       <label for="<?php echo esc_attr( $this->get_field_id( 'sns_url_'.$i ) ); ?>">
         <?php  echo sprintf(esc_html__( 'Social URL #%s', 'yahman-add-ons'),esc_html($i)); ?>
       </label><br />
       <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'sns_url_'.$i ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'sns_url_'.$i ) ); ?>" type="text" value="<?php echo esc_attr( $settings['sns_url_'.$i] ); ?>">
     </p>


     <?php
     $i++;
   }
 }


 public function update( $new_instance, $old_instance ) {
   $instance = array();
   $allowed_html = array(
     'a' => array(
       'href' => array (),
       'target' => array()
     ),
     'br' => array(),
     'strong' => array(),
     'b' => array(),
     'span' => array(),
   );


   $instance['title'] = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
   $instance['name'] = ( ! empty( $new_instance['name'] ) ) ? sanitize_text_field( $new_instance['name'] ) : '';
   $instance['image'] = ( ! empty( $new_instance['image'] ) ) ? esc_url( $new_instance['image'] ) : '';
   $instance['image_id'] = ( ! empty( $new_instance['image_id'] ) ) ? sanitize_text_field( $new_instance['image_id'] ) : '';
   $instance['image_shape'] = ( ! empty( $new_instance['image_shape'] ) ) ? sanitize_text_field( $new_instance['image_shape'] ) : 'circle';
   $instance['image_bg'] = ( ! empty( $new_instance['image_bg'] ) ) ? esc_url( $new_instance['image_bg'] ) : '';
   $instance['image_bg_id'] = ( ! empty( $new_instance['image_bg_id'] ) ) ? sanitize_text_field( $new_instance['image_bg_id'] ) : '';
   $instance['text'] = ( ! empty( $new_instance['text'] ) ) ? wp_kses($new_instance['text'], $allowed_html) : '';
   $instance['read_more_url'] = ( ! empty( $new_instance['read_more_url'] ) ) ? esc_url( $new_instance['read_more_url'] ) : '';
   $instance['read_more_text'] = ( ! empty( $new_instance['read_more_text'] ) ) ? sanitize_text_field( $new_instance['read_more_text'] ) : '';
   $instance['read_more_blank'] = ( ! empty( $new_instance['read_more_blank'] ) ) ? (bool) $new_instance['read_more_blank']  : false;

   $instance['icon_shape'] = ! empty( $new_instance['icon_shape'] ) ? esc_attr( $new_instance['icon_shape'] ) : 'icon_square';
   $instance['icon_size'] = ! empty( $new_instance['icon_size'] ) ? esc_attr( $new_instance['icon_size'] ) : '';
   $instance['icon_user_color'] = ! empty( $new_instance['icon_user_color'] ) ? esc_attr( $new_instance['icon_user_color'] ) : '';
   $instance['icon_user_hover_color'] = ! empty( $new_instance['icon_user_hover_color'] ) ? esc_attr( $new_instance['icon_user_hover_color'] ) : '';
   $instance['icon_tooltip'] = ! empty( $new_instance['icon_tooltip'] ) ? (bool) $new_instance['icon_tooltip']  : false;
   $i = 1;
   while($i <= 5){
     $instance['sns_icon_'.$i] = ! empty( $new_instance['sns_icon_'.$i] ) ? esc_attr( $new_instance['sns_icon_'.$i] ) : 'none';
     $instance['sns_url_'.$i] = ! empty( $new_instance['sns_url_'.$i] ) ? esc_attr( $new_instance['sns_url_'.$i] ) : '';
     $i++;
   }
   return $instance;

 }

 public function scripts($hook){


   if ($hook == 'widgets.php' || $hook == 'customize.php') {
     wp_enqueue_script( 'media-upload' );
     wp_enqueue_media();
     wp_enqueue_script('yahman_addons_media_uploader', YAHMAN_ADDONS_URI . 'assets/js/customizer/media-uploader.min.js', array('media-upload'));
     wp_enqueue_style( 'wp-color-picker');
     wp_enqueue_script( 'wp-color-picker');

     wp_enqueue_script('yahman_addons_widget-color-picker', YAHMAN_ADDONS_URI . 'assets/js/customizer/color-picker-widget.min.js', array('wp-color-picker'));

     
       wp_enqueue_script('wp-color-picker-alpha',YAHMAN_ADDONS_URI . 'assets/js/customizer/wp-color-picker-alpha.min.js', array('wp-color-picker'), null , true );
     
       
     

   }

 }



} // class yahman_addons_another_profile_widget
