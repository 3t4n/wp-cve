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
 


trait Media_Upload {

  public static $args;

	public function media_upload_field( $args ) {

    $default = [

      'title'     => esc_html__( 'Text Area Field', 'darklup-lite' ),
      'sub_title' => esc_html__( 'This is Text Area Field', 'darklup-lite' ),
      'placeholder' => '',
      'name'      => '',
      'class'     => '',
      'is_pro'    => 'no',
      'wrapper_class'     => ''

    ];

    $args = wp_parse_args( $args, $default );

    self::$args = $args;

    self::media_upload_markup();
		
	}

  public static function media_upload_markup() {

    $optionName = self::$optionName;
    $args       = self::$args;
    $getData    = self::$getOptionData;
    $fieldName  = $args['name'];
    $value = !empty( $getData[$fieldName] ) ? $getData[$fieldName] : '';

    ?>
<div class="darkluplite-row darkluplite--media--field <?php echo esc_attr( $args['wrapper_class'] ); ?>">
    <div class="darkluplite-col-lg-12 darkluplite-col-md-12">
        <div class="input-area">
            <?php 
          if( $args['is_pro'] == 'yes' ) {
            echo '<div class="darklup-pro-ribbon">'.esc_html__( 'Pro', 'darklup-lite' ).'</div>';
          }
          ?>
            <div class="darkluplite-single-input-inner style-two">
                <label
                    for="darkluplite_<?php echo esc_attr( $args['name'] ); ?>"><?php echo esc_html( $args['title'] ); ?></label>
                <?php 
              if( !empty( $args['sub_title'] ) ) {
                echo '<p>'.esc_html( $args['sub_title'] ).'</p>';
              }
              ?>
                <div class="darkluplite-media--inputs">
                    <input class="darkluplite_image_uploader" type="text"
                        name="<?php echo esc_attr( $optionName ).'['.$fieldName.']'; ?>"
                        value="<?php echo esc_html( $value ); ?>" />

                    <input type="button" class="darkluplite_image_upload_btn"
                        value="<?php esc_html_e( 'Upload', 'darklup-lite' ) ?>" />

                </div>


            </div>
        </div>
    </div>
</div>
<?php
  }



}  