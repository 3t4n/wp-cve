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

trait Selectbox {

	protected static $args;

	protected static $type = '';

	public function selectbox_meta( $args ) {
		self::$type = 'meta';
		$this->selectbox( $args );

	}
	
	public function selectbox( $args ) {

		$default = [
			'title' => '',
			'name'	=> '',
			'description'	=> '',
			'class'				=> '',
			'condition'		=> '',
			'options'		  => []
		];

		self::$args = wp_parse_args( $args, $default );
		self::selectbox_markup();

	}

	protected static function selectbox_markup() {

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
			<select name="<?php echo $fieldName; ?>">
				<?php 
          foreach( $args['options'] as  $key => $option ) {
            echo '<option value="'.esc_attr( $key ).'" '.selected( $value, $key, false ).'>'.esc_html( $option ).'</option>';
          }
        ?>
			</select>
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
