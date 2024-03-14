<?php
/**
 * Displays an input setting.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings/partials
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this partial:
 *
 * @var string  $type        The concrete type of this input.
 * @var string  $id          The identifier of this field.
 * @var string  $name        The name of this field.
 * @var boolean $value       The concrete value of this field (or an empty string).
 * @var string  $placeholder Optional. A default placeholder.
 * @var string  $desc        Optional. The description of this field.
 * @var string  $more        Optional. A link with more information about this field.
 */

?>

<p><input
	type="<?php echo esc_attr( $type ); ?>"
	id="<?php echo esc_attr( $id ); ?>"
	placeholder="<?php echo esc_attr( $placeholder ); ?>"
	name="<?php echo esc_attr( $name ); ?>"
	<?php if ( 'password' === $type ) { ?>
		onchange="
			document.getElementById('<?php echo esc_attr( $id ); ?>-check').pattern = this.value;
			if ( this.value != '' ) {
				document.getElementById('<?php echo esc_attr( $id ); ?>-check').required = 'required';
			} else {
				document.getElementById('<?php echo esc_attr( $id ); ?>-check').required = undefined;
			}
		"
	<?php } else { ?>
		value="<?php echo esc_attr( $value ); ?>"
		<?php
	}//end if
	?>
	/></p>
<?php if ( 'password' === $type ) { ?>
<p><input
	type="<?php echo esc_attr( $type ); ?>"
	id="<?php echo esc_attr( $id ); ?>-check"
	placeholder="<?php echo esc_attr_x( 'Confirm Password&hellip;', 'user', 'nelio-content' ); ?>"
	name="<?php echo esc_attr( $name ); ?>" /></p>
	<?php
}//end if
if ( ! empty( $desc ) ) {
	?>
	<div class="setting-help" style="display:none;">
		<p><span class="description">
			<?php
			echo $desc; // phpcs:ignore
			if ( ! empty( $more ) ) {
				?>
			<a href="<?php echo esc_url( $more ); ?>"><?php echo esc_html_x( 'Read more&hellip;', 'user', 'nelio-content' ); ?></a>
				<?php
			}//end if
			?>
		</span></p>
	</div>
<?php }//end if
?>
