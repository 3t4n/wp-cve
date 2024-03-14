<?php
/**
 * Displays an text area setting.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings/partials
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this partial:
 *
 * @var string  $id          The identifier of this field.
 * @var string  $name        The name of this field.
 * @var string  $value       The concrete value of this field (or an empty string).
 * @var string  $placeholder Optional. A default placeholder.
 * @var string  $desc        Optional. The description of this field.
 * @var string  $more        Optional. A link with more information about this field.
 */

?>

<textarea
	id="<?php echo esc_attr( $id ); ?>"
	cols="40" rows="4"
	placeholder="<?php echo esc_attr( $placeholder ); ?>"
	name="<?php echo esc_attr( $name ); ?>" />
	<?php echo $value; // phpcs:ignore ?>
</textarea>
<?php if ( ! empty( $desc ) ) { ?>
	<div class="setting-help" style="display:none;">
		<p><span class="description"><?php // phpcs:ignore
		echo $desc; // phpcs:ignore
		if ( ! empty( $more ) ) {
			?>
			<a href="<?php echo esc_url( $more ); ?>"><?php echo esc_html_x( 'Read more&hellip;', 'user', 'nelio-content' ); ?></a>
			<?php
		}//end if
		?>
		</span></p>
	</div>
	<?php
}//end if
?>
