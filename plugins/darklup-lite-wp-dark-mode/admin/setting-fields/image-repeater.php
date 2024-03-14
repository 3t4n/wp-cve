<?php
namespace DarklupLite\Admin\Field;
 /**
  * 
  * @package    DarklupLite - WP Dark Mode
  * @version    1.0.0
  * @author     
  * @Websites: 
  *
  */
 


trait Image_Repeater {

  public static $args;


	public function image_repeater_field( $args ) {

    $default = [
      'title'     => esc_html__( 'Image Repeater Field', 'darklup-lite' ),
      'sub_title' => esc_html__( 'This is image repeater Field', 'darklup-lite' ),
      'placeholder' => '',
      'name'      => '',
      'class'     => '',
      'is_pro'    => 'no',
      'wrapper_class'     => ''
    ];

    $args = wp_parse_args( $args, $default );

    self::$args = $args;

    self::image_repeater_markup();

	}

  public static function image_repeater_markup() {

    $optionName = self::$optionName;
    $args = self::$args;
    $getData = self::$getOptionData;
    $fieldName  = $args['name'];
    $lightValue = !empty( $getData['light_img'] ) ? $getData['light_img'] : '';
    $darkValue = !empty( $getData['dark_img'] ) ? $getData['dark_img'] : '';
    $images = '';
    // array check
    if( is_array( $lightValue ) && is_array( $darkValue ) ) {
      $images = array_combine( $lightValue, $darkValue);
    }
    
    ?>
<div class="darkluplite-row <?php echo esc_attr( $args['wrapper_class'] ); ?>">
    <div class="darkluplite-col-lg-12 darkluplite-col-md-12">
        <div class="input-area">
            <div class="darkluplite-single-input-inner style-two">
                <?php 
          if( $args['is_pro'] == 'yes' ) {
            echo '<div class="darklup-pro-ribbon">'.esc_html__( 'Pro', 'darklup-lite' ).'</div>';
          }
          ?>
                <label
                    for="darkluplite_<?php echo esc_attr( $fieldName ); ?>"><?php echo esc_html( $args['title'] ); ?></label>
                <div class="img-url-repeater">
                    <div class="field-wrapper">
                        <?php
                  if( !empty( $images ) ):
                    foreach ( $images as $key => $value ) :
                  ?>
                        <div class="single-field">
                            <input type="text" name="darkluplite_settings[light_img][]"
                                placeholder="<?php esc_html_e( 'Light Image Url', 'darklup-lite' ); ?>"
                                value="<?php echo esc_url( $key ); ?>" />
                            <input type="text" name="darkluplite_settings[dark_img][]"
                                placeholder="<?php esc_html_e( 'Dark Image Url', 'darklup-lite' ); ?>"
                                value="<?php echo esc_url( $value ); ?>" />
                            <span
                                class="removetime fb-admin-btn"><?php esc_html_e( 'Remove', 'foodbook-lite' ); ?></span>
                        </div>
                        <?php 
                  endforeach;
                  endif
                  ?>
                    </div>
                    <span class="addtime fb-admin-btn"><?php esc_html_e( 'Add', 'foodbook-lite' ); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
  }


}  