<?php
/**
 * Displays a range setting.
 *
 * See the class `Nelio_Content_Range_Setting`.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings/partials
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this partial:
 *
 * @var string  $id             The identifier of this field.
 * @var string  $name           The name of this field.
 * @var int     $min            The minimum value accepted by this range.
 * @var int     $max            The maximum value accepted by this range.
 * @var int     $step           The step this range uses.
 * @var int     $value          The concrete value of this field (or an empty string).
 * @var string  $verbose_value  Optional. A string to print the value of the range (replacing the `{value}` placeholder).
 * @var string  $desc           Optional. The description of this field.
 * @var string  $more           Optional. A link with more information about this field.
 */

?>

<input
	type="range"
	id="<?php echo esc_attr( $id ); ?>"
	name="<?php echo esc_attr( $name ); ?>"
	min="<?php echo esc_attr( $min ); ?>"
	max="<?php echo esc_attr( $max ); ?>"
	step="<?php echo esc_attr( $step ); ?>"
	value="<?php echo esc_attr( $value ); ?>"
	/>
<?php if ( ! empty( $verbose_value ) ) { ?>
		<p id="label-<?php echo esc_attr( $id ); ?>"><span class="description"></span></p>
		<script type="text/javascript">
		(function() {
			var elem = jQuery( '#<?php echo esc_attr( $id ); ?>' );
			function setLabel( value ) {
				var label = <?php echo wp_json_encode( $verbose_value ); ?>;
				label = label.replace( '{value}', value );
				jQuery( '#label-<?php echo esc_attr( $id ); ?> .description' ).html( label );
			}
			setLabel( elem.attr( 'value' ) );
			elem.on( 'input change', function() {
				setLabel( elem.attr( 'value' ) );
			});
		})();
		</script>
	<?php
}//end if
?>

<?php if ( ! empty( $desc ) ) { ?>
	<div class="setting-help" style="display:none;">
		<p><span class="description">
			<?php echo $desc; // phpcs:ignore ?>
			<?php if ( ! empty( $more ) ) { ?>
			<a href="<?php echo esc_url( $more ); ?>"><?php echo esc_html_x( 'Read more&hellip;', 'user', 'nelio-content' ); ?></a>
				<?php
			}//end if
			?>
		</span></p>
	</div>
<?php }//end if
?>
