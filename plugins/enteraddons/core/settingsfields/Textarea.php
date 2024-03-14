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

trait Textarea {

	protected static $args;

	protected static $type = '';

	public function textarea_meta( $args ) {
		self::$type = 'meta';
		$this->textarea( $args );
	}
	
	public function textarea( $args ) {

		$default = [
			'title' => '',
			'name'	=> '',
			'description'	=> '',
			'class'			=> '',
			'condition'		=> ''
		];
		
		self::$args = wp_parse_args( $args, $default );
		self::textarea_markup();
	}

	protected static function textarea_markup() {

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
		<div class="eap-admin-field" data-condition="<?php echo esc_html($conditionData); ?>">
			<h4><?php echo esc_html( $args['title'] ); ?></h4>
			<div class="fb-field-group">
			<textarea name="<?php echo $fieldName; ?>" ><?php echo esc_attr( $value ); ?></textarea>
			<?php
			if( !empty( $args['description'] ) ) {
				echo '<p>'.esc_html( $args['description'] ).'</p>';
			}
			?>
			</div>
		</div>
		<?php
	}
}
