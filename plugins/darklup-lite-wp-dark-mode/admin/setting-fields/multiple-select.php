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
 


trait Multiple_Selectbox {

  public static $args;

  public function Multiple_select_box( $args ) {

    $default = [
      'title'     => esc_html__( 'Multiple Select Field', 'darklup-lite' ),
      'sub_title' => esc_html__( 'This is Multiple Select Field', 'darklup-lite' ),
      'name'        => '',
      'class'       => '',
      'wrapper_class' => '',
      'is_pro'    => 'no',
      'options'     => []
    ];

    $args = wp_parse_args( $args, $default );

    self::$args = $args;

    self::multiple_selectbox_markup();

    $conditionData = '';
    if( !empty( $args['condition'] ) ) {
      $conditionData = json_encode( $args['condition'] );
    }
  }


	public static function multiple_selectbox_markup() {
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
<div class="darkluplite-row <?php echo esc_attr( $args['wrapper_class'].' '.$args['class'] ); ?>"
    data-condition="<?php echo esc_html($conditionData); ?>">
    <div class="darkluplite-col-lg-12 darkluplite-col-md-12">
        <div class="darkluplite-single-settings-inner">
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
            <div class="button-switch" style="margin-left: 10px;">
                <div class="single-select-inner">

                    <select class="darkluplite-select2"
                        name="<?php echo esc_attr( $optionName ).'['.$fieldName.'][]'; ?>" multiple="multiple">
                        <?php 
                    foreach( $args['options'] as  $key => $option ) {

                      $v = '';

                      if( is_array( $value ) && in_array( $key , $value ) ) {

                        $v = $key;

                      }

                      echo '<option value="'.esc_attr( $key ).'" '.selected( $key, $v, false ).'>'.esc_html( $option ).'</option>';
                    }
                    ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
	}

}  