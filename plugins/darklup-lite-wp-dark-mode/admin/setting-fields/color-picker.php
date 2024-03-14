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
 


trait Color_Picker {

    public static $args;

	public function color_field($args) {

    $default = [
      'title'     => esc_html__( 'Color Picker', 'darklup-lite' ),
      'sub_title' => esc_html__( 'Set the color', 'darklup-lite' ),
      'name'      => '',
      'class'     => '',
      'wrapper_class'     => '',
      'condition' => '',
      'is_pro' 	  => 'no'
    ];

    $args = wp_parse_args( $args, $default );

    self::$args = $args;

    self::color_markup();
		
	}

    public static function color_markup() {


    $optionName = self::$optionName;
    $args = self::$args;
    $getData = self::$getOptionData;
    $fieldName  = $args['name'];
    $value = !empty( $getData[$fieldName] ) ? $getData[$fieldName] : '';

    $conditionData = '';
    if( !empty( $args['condition'] ) ) {
      $conditionData = json_encode( $args['condition'] );
    }

    ?>
    <div class="darkluplite-row darkluplite-color--picker <?php echo esc_attr( $args['wrapper_class'] ); ?>"
        data-condition="<?php echo esc_html($conditionData); ?>">
        <div class="darkluplite-col-lg-12 darkluplite-col-md-12 wpc_wrap_<?php echo esc_attr( $fieldName ); ?>">
            <div class="input-area">
                <div class="darkluplite-single-input-inner darkluplite-color-picker">

                    <?php 
                  if( $args['is_pro'] == 'yes' ) {
                    echo '<div class="darklup-pro-ribbon">'.esc_html__( 'Pro', 'darklup-lite' ).'</div>';
                  }
                  ?>

                    <div class="wpc-color-picker--content">
                        <div class="wpc-color-picker--titles">
                            <label
                                for="darkluplite_<?php echo esc_attr( $fieldName ); ?>"><?php echo esc_html( $args['title'] ); ?></label>
                            <?php 
                  if( !empty( $args['sub_title'] ) ) {
                    echo '<p>'.esc_html( $args['sub_title'] ).'</p>';
                  }
                  ?>
                        </div>
                        <div class="wpc-color-picker--input">
                            <input type="text" id="darkluplite_<?php echo esc_attr( $fieldName ); ?>" class="color-picker"
                                value="<?php echo esc_html( $value ); ?>"
                                name="<?php echo esc_attr( $optionName ).'['.$fieldName.']'; ?>" />
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <?php
  }



}  