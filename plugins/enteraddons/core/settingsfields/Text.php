<?php 
namespace Enteraddons\Core;
/**
 * Enteraddons Post Type Meta class
 *
 * @package     EnterAddons Pro
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Text {

	protected static $args;

	protected static $type = '';

	public function text_meta( $args ) {
		self::$type = 'meta';
		$this->text( $args );
	}

	public function text( $args ) {

		$default = [
			'title' => '',
			'name'	=> '',
			'description'	=> '',
			'placeholder'	=> '',
			'wrapperclass'	=> '',
			'class'			=> '',
			'condition'		=> ''
		];

		self::$args = wp_parse_args( $args, $default );
		self::text_markup();
		
	}

	protected static function text_markup() {

		$args = self::$args;
	    $uniqName  = $args['name'];
	    
	    if( self::$type != 'meta' ) {
	    	$wrapTypeClass = '';
	    	$optionName = self::$optionName;
	    	$getData = self::$getOptionData;
	    	$fieldName = esc_attr( $optionName ).'['.$uniqName.']';
	    	$value = !empty( $getData[$uniqName] ) ? $getData[$uniqName] : '';
	    	
	    } else {
	    	$wrapTypeClass = 'ea-meta-field ';
	    	$fieldName = $uniqName;
	    	$value = get_post_meta( get_the_ID(), $uniqName, true );
	    }

	    $conditionData = '';
	    if( !empty( $args['condition'] ) ) {
	      $conditionData = json_encode( $args['condition'] );
	    }
		?>
		<div class="eap-admin-field <?php echo esc_attr( $args['wrapperclass'] ); ?>" data-condition="<?php echo esc_html($conditionData); ?>">
			<h4><?php echo esc_html( $args['title'] ); ?></h4>
			<div class="fb-field-group">
			<input type="text" class="<?php echo esc_attr( $args['class'] ); ?>" name="<?php echo $fieldName; ?>" placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
			<?php
			if( !empty( $args['description'] ) ) {
				echo '<p>'. $args['description'] .'</p>';
			}
			?>
			</div>
		</div>
		<?php
	}
}
