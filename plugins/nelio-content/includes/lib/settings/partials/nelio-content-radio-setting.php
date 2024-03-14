<?php
/**
 * Displays a radio setting.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings/partials
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this partial:
 *
 * @var array   $options The list of options.
 *                       Each of them is an array with its label, description, and so on.
 * @var string  $name    The name of this field.
 * @var boolean $value   The concrete value of this field (or an empty string).
 * @var string  $desc    Optional. The description of this field.
 * @var string  $more    Optional. A link with more information about this field.
 */

?>

<?php foreach ( $options as $option ) { ?>
	<p><input type="radio"
		name="<?php echo esc_attr( $name ); ?>"
		value="<?php echo esc_attr( $option['value'] ); ?>"
		<?php checked( $option['value'] === $value ); ?> />
		<?php echo $option['label']; // phpcs:ignore ?></p>
	<?php
}//end foreach
?>

<?php
$described_options = array();
foreach ( $options as $option ) {
	if ( isset( $option['desc'] ) ) {
		array_push( $described_options, $option );
	}//end if
}//end foreach

if ( ! empty( $desc ) ) {
	?>
	<div class="setting-help" style="display:none;">
		<p><span class="description">
		<?php echo $desc; // phpcs:ignore
		if ( ! empty( $more ) ) {
			?>
			<a href="<?php echo esc_url( $more ); ?>"><?php echo esc_html_x( 'Read more&hellip;', 'user', 'nelio-content' ); ?></a>
			<?php
		}//end if
		?>
		</span></p>

		<?php if ( count( $described_options ) > 0 ) { ?>
			<ul style="list-style-type:disc;margin-left:3em;">
				<?php foreach ( $described_options as $option ) { ?>
					<li><span class="description"><strong>
						<?php echo $option['label']; // phpcs:ignore ?>.</strong>
						<?php echo $option['desc']; // phpcs:ignore ?></span></li>
					<?php
				}//end foreach
				?>
			</ul>
			<?php
		}//end if
		?>

	</div>
	<?php
}//end if
?>
