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
 


trait Number {

  public static $args;


	public function number_field( $args ) {

    $default = [

      'title'     => esc_html__( 'Number Field', 'darklup-lite' ),
      'sub_title' => esc_html__( 'This is number Field', 'darklup-lite' ),
      'placeholder' => '',
      'name'      => '',
      'step'      => '1',
      'min'       => '1',
      'max'       => '10',
      'class'     => '',
      'is_pro'    => 'no',
      'condition'   => '',
      'wrapper_class'     => ''

    ];

    $args = wp_parse_args( $args, $default );

    self::$args = $args;

    self::number_markup();

	}

  public static function number_markup() {

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
<div class="darkluplite-row <?php echo esc_attr( $args['wrapper_class'] ); ?> darkluplite--number--field"
    data-condition="<?php echo esc_html($conditionData); ?>">
    <div class="darkluplite-col-lg-12 darkluplite-col-md-12">
        <div class="input-area">
            <?php 
          if( $args['is_pro'] == 'yes' ) {
            echo '<div class="darklup-pro-ribbon">'.esc_html__( 'Pro', 'darklup-lite' ).'</div>';
          }
          ?>
            <div class="darkluplite-single-input-inner style-two">
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
                    <div class="wpc-color-picker--inputs">
                        <input id="darkluplite_<?php echo esc_attr( $fieldName ); ?>"
                            step="<?php echo esc_attr( $args['step'] ); ?>"
                            max="<?php echo esc_attr( $args['max'] ); ?>"
                            class="<?php echo esc_attr( $args['class'] ); ?>" type="number"
                            name="<?php echo esc_attr( $optionName ).'['.$fieldName.']'; ?>"
                            placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
                            value="<?php echo esc_html( $value ); ?>">
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<?php
  }


}  