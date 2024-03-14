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

trait Heading {

	protected static $args;

	protected static $type = '';

	public function heading_meta( $args ) {
		self::$type = 'meta';
		$this->heading( $args );
	}

	public function heading( $args ) {

		$default = [
			'title' => '',
			'background_color' => '',
			'color'	=> '',
			'description'	=> '',
			'wrapperclass'	=> '',
			'class'			=> '',
			'condition'		=> ''
		];

		self::$args = wp_parse_args( $args, $default );
		self::heading_markup();
	}

	protected static function heading_markup() {

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
		<div class="eap-admin-field <?php echo esc_attr( $wrapTypeClass.$args['wrapperclass'] ); ?>" data-condition="<?php echo esc_html($conditionData); ?>">
			<h4 style="padding: 7px; border-radius: 3px; background: <?php echo esc_attr( $args['background_color'] ); ?>; color: <?php echo esc_attr( $args['color'] ); ?>;"><?php echo esc_html( $args['title'] ); ?></h4>
		</div>
		<?php
	}
}
