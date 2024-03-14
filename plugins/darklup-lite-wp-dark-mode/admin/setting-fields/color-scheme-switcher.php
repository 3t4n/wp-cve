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



trait Color_Scheme_Button {

    public static $args;


    public function color_scheme_radio_field( $args ) {

        $default = [

            'title'     => esc_html__( 'Image Radio Field', 'darklup-lite' ),
            'sub_title' => esc_html__( 'This is image radio Field', 'darklup-lite' ),
            'name'      => '',
            'condition' => '',
            'class'     => '',
            'wrapper_class'  => '',
            'is_pro'    => 'no',
            'options_title'=> [],
            'options' => [],
            'extra_cond' => [],

        ];

        $args = wp_parse_args( $args, $default );

        self::$args = $args;

        self::color_scheme_radio_markup();

    }

    public static function color_scheme_radio_markup() {

        $optionName = self::$optionName;
        $args = self::$args;
        $getData = self::$getOptionData;
        $fieldName  = $args['name'];
        $optionTitle  = $args['options_title'];
        $value = !empty( $getData[$fieldName] ) ? $getData[$fieldName] : '';

        $conditionData = '';
        if( !empty( $args['condition'] ) ) {
            $conditionData = json_encode( $args['condition'] );
        }
        
        $extraCondition = '';
        if (!empty($args['extra_cond'])) {
            $extraCondition = json_encode($args['extra_cond']);
        }
        ?>
<div class="darkluplite-row <?php echo esc_html( $args['wrapper_class'].' '.$args['class'] ); ?>"
    data-condition="<?php echo esc_html($conditionData); ?>"  data-extra_condition="<?php echo esc_html($extraCondition); ?>">
    <div class="darkluplite-col-lg-12 darkluplite-col-md-12">
        <div class="darkluplite-single-settings-inner color_scheme_wrapper">
            <?php
                    if( $args['is_pro'] == 'yes' ) {
                        echo '<div class="darklup-pro-ribbon">'.esc_html__( 'Pro', 'darklup-lite' ).'</div>';
                    }
                    ?>
            <div class="details">
                <h5><?php echo esc_html( $args['title'] ); ?></h5>
                <?php
                        if( !empty( $args['sub_title'] ) ) {
                            echo '<p>'.esc_html( $args['sub_title'] ).'</p>';
                        }
                        ?>
            </div>
            <div class="rect-design">
                <?php
                        foreach( $args['options'] as $key => $option ) {

                            /*if( $key > 2 ) {
                                echo '<label class="radio-img darkluplite-pro-item pro-feature"><img src="'.esc_url( $option ).'"></label>';
                            } else {
                                echo '<label class="radio-img"><input type="radio" name="'.esc_attr( $optionName ).'['.$fieldName.']" '.checked(  $value,$key,false ).' value="'.esc_attr( $key ).'" /><img src="'.esc_url( $option ).'"></label>';
                            }*/
                            if (!in_array($key, array("1", "2", "3"))) {
                                echo '<label class="rect-design-single-pro pro-feature"><img src="' . esc_url($option) . '"><div class="after"></div> </label>';
                            } else {
                                echo '<label class="rect-design-single"><input class="' . esc_attr($optionTitle[$key]) .'" type="radio" name="' . esc_attr($optionName) . '[' . $fieldName . ']" ' . checked($value, $key, false) . ' value="' . esc_attr($key) . '" /><img src="' . esc_url($option) . '"><span class="label-text">'.esc_attr($optionTitle[$key]).'</span></label>';
                            }

                        }
                        ?>
            </div>
        </div>
    </div>
</div>
<?php
    }

}