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
 


trait Switcher {

	public static $args;


	public function switch_field( $args ) {

    $default = [

      'title'     => esc_html__( 'Switch Field', 'darklup-lite' ),
      'sub_title' => esc_html__( 'This is switch Field', 'darklup-lite' ),
      'name'      => '',
      'class'     => '',
      'class'     => '',
      'wrapper_class' => '',
	  'condition'   => '',
      'is_pro' 	  => 'no'

    ];

    $args = wp_parse_args( $args, $default );

    self::$args = $args;

    self::switcher_markup();

	}

	public static function switcher_markup() {

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
<div class="darkluplite-row <?php echo esc_html( $args['wrapper_class'].' '.$args['class'] ); ?> darkluplite-switcher--field"
    data-condition="<?php echo esc_html($conditionData); ?>">
    <div class="darkluplite-col-lg-12 darkluplite-col-md-12">
        <div class="darkluplite-single-settings-inner">
            <?php 
					if( $args['is_pro'] == 'yes' ) {
						echo '<div class="darklup-pro-ribbon">'.esc_html__( 'Pro', 'darklup-lite' ).'</div>';
					}
					?>
            <div class="darkluplite-switcher-inner-content">
                <div class="details">
                    <h5><?php echo esc_html( $args['title'] ); ?></h5>
                    <?php
						if( !empty( $args['sub_title'] ) ) {
							echo '<p>'.esc_html( $args['sub_title'] ).'</p>';
						}
			            ?>
                </div>
                <div class="switcher-colon">:</div>
                <div class="on-off-toggle button-switch">
                    <input class="on-off-toggle__input <?php echo esc_attr($fieldName); ?>"
                        name="<?php echo esc_attr( $optionName ).'['.$fieldName.']'; ?>" value="yes" type="checkbox"
                        <?php checked( $value, 'yes' ); ?> id="darkluplite_<?php echo esc_attr( $fieldName ); ?>" />
                    <label for="darkluplite_<?php echo esc_attr( $fieldName ); ?>"
                        class="on-off-toggle__slider"></label>
                </div>
            </div>

        </div>
    </div>
</div>
<?php
	}

}  