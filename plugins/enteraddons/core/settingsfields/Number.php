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

trait Number {

	protected static $args;

	public function number( $args ) {

		$default = [
			'title' => '',
			'name'	=> '',
			'description'	=> '',
			'class'			=> '',
			'default'		=> '',
			'max'			=> '',
			'min'			=> '',
			'placeholder'	=> '',
			'condition'		=> ''
		];

		self::$args = wp_parse_args( $args, $default );

		self::number_markup();
		
	}

	protected static function number_markup() {

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
		<div class="eap-admin-field <?php echo esc_attr( $wrapTypeClass ); ?>" data-condition="<?php echo esc_html( $conditionData ); ?>">
			<h4><?php echo esc_html( $args['title'] ); ?></h4>
			<div class="fb-field-group">
			<input type="number" min="<?php echo esc_attr( $args['min'] );  ?>" max="<?php echo esc_attr( $args['max'] );  ?>" name="<?php echo esc_attr( $fieldName ); ?>" placeholder="<?php echo esc_attr( $args['placeholder'] );  ?>" value="<?php echo esc_attr( $value ); ?>" />
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
