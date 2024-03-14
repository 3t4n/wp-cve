<?php
/**
 * Displays a checkbox setting.
 *
 * See the class `Nelio_Content_Checkbox_Setting`.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings/partials
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this partial:
 *
 * @var string  $id      The identifier of this field.
 * @var string  $name    The name of this field.
 * @var boolean $checked Whether this checkbox is selected or not.
 * @var string  $desc    Optional. The description of this field.
 * @var string  $more    Optional. A link with more information about this field.
 */

?>

<p>
	<input
		type="checkbox"
		id="<?php echo esc_attr( $id ); ?>"
		name="<?php echo esc_attr( $name ); ?>"
		<?php checked( $checked ); ?>
	/>
<?php
echo $desc; // phpcs:ignore
if ( ! empty( $more ) ) {
	?>
	<span class="description"><a href="<?php echo esc_url( $more ); ?>">
		<?php echo esc_html_x( 'Read more&hellip;', 'user', 'nelio-content' ); ?></a>
	</span>
	<?php
}//end if
?>
</p>
