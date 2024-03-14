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


trait Checkbox {

	protected static $args;

	protected static $type = '';

	public function checkbox_meta( $args ) {
		self::$type = 'meta';
		$this->checkbox( $args );
	}

	public function checkbox( $args ) {

		$default = [
			'title' => '',
			'name'	=> '',
			'description'	=> '',
			'class'			=> '',
			'condition'		=> ''
		];
		self::$args = wp_parse_args( $args, $default );
		$this->checkbox_markup();
	}

	protected function checkbox_markup() {

	    $args = self::$args;
	    $uniqName  = $args['name'];
	    
	    if( self::$type != 'meta' ) {
	    	$wrapTypeClass = '';
	    	$optionName = self::$optionName;
	    	$getData = self::$getOptionData;
	    	$fieldName = esc_attr( $optionName ).'['.$uniqName.']';
	    	$value = !empty( $getData[$uniqName] ) ? $getData[$uniqName] : '';
	    	
	    } else {
	    	$wrapTypeClass = 'ea-meta-field';
	    	$fieldName = $uniqName;
	    	$value = get_post_meta( get_the_ID(), $uniqName, true );
	    }
	    
	    $conditionData = '';
	    if( !empty( $args['condition'] ) ) {
	      $conditionData = json_encode( $args['condition'] );
	    }
		?>
		<div class="eap-admin-field <?php echo esc_attr( $wrapTypeClass ); ?>" data-condition="<?php echo esc_html($conditionData); ?>">
			<h4><?php echo esc_html( $args['title'] ); ?></h4>
			<div class="fb-field-group">
			<input type="checkbox" value="yes" name="<?php echo $fieldName; ?>" <?php checked( esc_html( $value ), 'yes'  ); ?>  />
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
