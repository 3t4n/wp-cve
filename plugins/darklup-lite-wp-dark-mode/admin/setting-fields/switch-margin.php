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



trait Switch_Margin {

    public static $args;


    public function margin_field( $args ) {

        $default = [
            'title'     => esc_html__( 'Margin Field', 'darklup-lite' ),
            'sub_title' => esc_html__( 'This is number Field for Switch Margin', 'darklup-lite' ),
            'placeholder' => array(),
            'name'      => array(),
            'step'      => '1',
            'min'       => '1',
            'max'       => '10',
            'condition'   => '',
            'wrapper_class' => '',
            'is_pro' 	  => 'no'
      
        ];

        $args = wp_parse_args( $args, $default );

        self::$args = $args;

        self::margin_markup();

    }

    public static function margin_markup() {

        $optionName = self::$optionName;
        $args = self::$args;
        $getData = self::$getOptionData;
        $fieldName  = $args['name'];
        $value = array();
        foreach ($fieldName as $field){
            $value[] = !empty( $getData[$field] ) ? $getData[$field] : '';
        }


        $conditionData = '';
        if( !empty( $args['condition'] ) ) {
            $conditionData = json_encode( $args['condition'] );
        }

        ?>

<div class="darkluplite-row darkluplite-margin--field <?php echo esc_html( $args['wrapper_class'] ); ?>"
    data-condition="<?php echo esc_html($conditionData); ?>">
    <div class="darkluplite-col-lg-12 darkluplite-col-md-12">
        <div class="input-area">
            <div class="darkluplite-multi-input-inner style-two">

                <?php 
					if( $args['is_pro'] == 'yes' ) {
						echo '<div class="darklup-pro-ribbon">'.esc_html__( 'Pro', 'darklup-lite' ).'</div>';
					}
					?>

                <label><?php echo esc_html( $args['title'] ); ?></label>
                <?php
                        if( !empty( $args['sub_title'] ) ) {
                            echo '<p>'.esc_html( $args['sub_title'] ).'</p>';
                        }
                        ?>
                <div class="darkluplite-row">
                    <?php
                            foreach ($fieldName as $key=>$field){
                                ?>
                    <div class="darkluplite-col-lg-3 darkluplite-col-md-12 darkluplite-multi-field-container">
                        <input id="darkluplite_<?php echo esc_attr( $field ); ?>"
                            step="<?php echo esc_attr( $args['step'] ); ?>"
                            max="<?php echo esc_attr( $args['max'] ); ?>" type="number"
                            name="<?php echo esc_attr( $optionName ).'['.$field.']'; ?>"
                            placeholder="<?php echo esc_attr( $args['placeholder'][$key] ); ?>"
                            value="<?php echo esc_html( $value[$key] ); ?>">
                    </div>
                    <?php
                            }
                            ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    }

}