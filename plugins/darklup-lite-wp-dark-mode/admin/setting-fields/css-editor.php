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
 


trait CSS_Editor {
  

  public static $args;


  public function css_editor_field( $args ) {

    $default = [

      'title'     => esc_html__( 'CSS Editor Field', 'darklup-lite' ),
      'sub_title' => esc_html__( 'This is CSS editor Field', 'darklup-lite' ),
      'placeholder' => '',
      'name'      => '',
      'class'     => '',
      'is_pro'    => 'no',
      'wrapper_class'     => '',

    ];

    $args = wp_parse_args( $args, $default );

    self::$args = $args;

    self::editor_markup();

  }


	public static function editor_markup() {

    $optionName = self::$optionName;
    $args = self::$args;
    $getData = self::$getOptionData;
    $fieldName  = $args['name'];
    $value = !empty( $getData[$fieldName] ) ? $getData[$fieldName] : '';

		?>
<div class="darkluplite-row <?php echo esc_attr( $args['wrapper_class'] ); ?>">
    <div class="darkluplite-col-lg-12 darkluplite-col-md-12">
        <div class="input-area">
            <?php 
          if( $args['is_pro'] == 'yes' ) {
            echo '<div class="darklup-pro-ribbon">'.esc_html__( 'Pro', 'darklup-lite' ).'</div>';
          }
          ?>
            <div class="darkluplite-single-input-inner style-two">
                <label
                    for="darkluplite_<?php echo esc_attr( $fieldName ); ?>"><?php echo esc_html( $args['title'] ); ?></label>
                <input type="text" hidden id="editortext" name="custoncss">
                <div id="darklupEditor" class="custom-editor"></div>
            </div>
        </div>
    </div>
</div>
<?php
	}

}  