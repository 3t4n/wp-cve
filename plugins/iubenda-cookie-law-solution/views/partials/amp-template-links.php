<?php
/**
 * AMP template link section - partial page.
 *
 * @package  Iubenda
 */

$amp_template_done        = (array) iubenda()->options['cs']['amp_template_done'];
$amp_template_done_status = array_filter( $amp_template_done );
if ( empty( $amp_template_done_status ) ) : ?>
	<p class="description">
		<?php esc_html_e( 'No file available. Save changes to generate iubenda AMP configuration file.', 'iubenda' ); ?>
	</p>
<?php else : ?>
<table class="table">
	<tbody>
	<?php
	// multi-language support.
	if ( iubenda()->multilang && ! empty( iubenda()->languages ) ) {
		foreach ( iubenda()->languages as $lang_id => $lang_name ) {
			$is_amp_template_done = (bool) ! iub_array_get( iubenda()->options['cs']['amp_template_done'], $lang_id, false );
			if ( $is_amp_template_done ) {
				continue;
			}
			?>
			<tr>
				<td><p class="text-bold"><?php echo esc_html( $lang_name ); ?></p></td>
				<td>
					<a href="<?php echo esc_url( iubenda()->amp->get_amp_template_url( $lang_id ) ); ?>" target="_blank"><?php echo esc_url( iubenda()->amp->get_amp_template_url( $lang_id ) ); ?></a>
				</td>
			</tr>
			<?php
		}
	} else {
		?>
		<tr>
			<td><p class="text-bold"><?php esc_html_e( 'Default language', 'iubenda' ); ?></p></td>
			<td>
				<a href="<?php echo esc_url( iubenda()->amp->get_amp_template_url() ); ?>" target="_blank"><?php echo esc_url( iubenda()->amp->get_amp_template_url() ); ?></a>
			</td>
		</tr>
		<?php
	}
	endif;
?>
	</tbody>
</table>
