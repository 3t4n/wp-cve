<?php
/* proto-widget.php */
/* Core function creates a widget based on options & filters. */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if ( !class_exists( 'proto_widget_01' ) ):
	class proto_widget_01 {
	public $class = NULL;

	function __construct( $class = null ) {
		$this->class  = $class;
	}
	public function proto_number( $description, $field, $value , $placeholder='') {

?>
         <div class="<?php echo $field; ?>-section" id="<?php echo $field; ?>-section">
          <label>
            <span class="proto-description" ><?php echo $description?></span> <br/>
            <input id="<?php echo $this->class->get_field_id( $field );?>" type="number" class="proto-number proto-field" min="0" title="<?php echo $placeholder?>" placeholder="<?php echo $placeholder?>" name="<?php echo $this->class->get_field_name( $field );?>" value="<?php echo $value; ?>" />
          </label>
          </div>
        <?php
	}
	public function proto_text( $description, $field, $value, $placeholder='' ) {

?>
         <div class="<?php echo $field; ?>-section" id="<?php echo $field; ?>-section">
          <label>

            <span class="proto-description" ><?php echo $description?></span> <br/>
            <input id="<?php echo $this->class->get_field_id( $field );?>" type="text" class="proto-text proto-field"  title="<?php echo $placeholder?>" placeholder="<?php echo $placeholder?>" name="<?php echo $this->class->get_field_name( $field );?>" value="<?php echo $value; ?>" />
          </label>
          </div>
        <?php
	}
	public function proto_checkbox( $description, $field, $value, $class = "" ) {
		$checked = ( $value == 'on' || $value == '1' || $value == $field ) ? 'on' : 'off';
?>
               <div class="<?php echo $field; ?>-section" id="<?php echo $field; ?>-section">
                <label  for="<?php echo $this->class->get_field_id( $field ); ?>" id="<?php echo $field; ?>-label">
		           <input class="checkbox proto-checkbox proto-field <?php echo $class;?>" type="checkbox" <?php checked( $checked, 'on' ); ?> id="<?php echo $this->class->get_field_id( $field ); ?>" name="<?php echo $this->class->get_field_name( $field ); ?>" />
                   <span class="proto-description"><?php echo $description; ?></span>
		        </label>
              </div>
            <?php
	}
	public function proto_select( $description, $field, $list, $value ) {

?>

            <div class="<?php echo $field; ?>-section" id="<?php echo $field; ?>-section">
            <span class="proto-description"><?php echo $description; ?></span><br/>
            <select id="<?php echo $this->class->get_field_id( $field ); ?>" class="proto-select proto-field" name="<?php echo $this->class->get_field_name( $field );?>">
                <?php
		foreach ( $list as $key=>$item ) {
		    //$key = strtolower($item);
		    ?>
                    <option value="<?php echo $key?>" <?php if ( strtolower( $value ) == strtolower( $key ) ) echo "selected"?>><?php echo $item; ?></option>
                    <?php
		} ?>
                </select>
            </div>
        <?php
	}
        public function proto_color( $description, $field, $value ) {

            $id = $this->class->get_field_id( $field );
            ?>
            <label><span class="proto-description" ><?php echo $description?></span></label>
            <div class="<?php echo $field; ?>-section" id="<?php echo $field; ?>-section">
                <label>

                    <input id="<?php echo $id; ?>" type="text" class="proto-color proto-field" name="<?php echo $this->class->get_field_name( $field );?>" value="<?php echo $value; ?>" data-default-color="" />
                </label>
            </div>

            <?php
        }
}
endif;