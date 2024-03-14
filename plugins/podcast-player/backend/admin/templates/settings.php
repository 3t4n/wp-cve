<?php
/**
 * Podcast player settings page
 *
 * @package Podcast Player
 * @since 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<p class="pp-notes"><?php esc_html_e( 'Make sure to update your feed in "Toolkit" section after making changes here.', 'podcast-player' ); ?></p>
<?php
$title_cls   = 'pp-section-title';
$content_cls = 'pp-hide-section-content';
printf( '<form action="options.php" method="post">' );
settings_fields( 'pp_options_group' );
foreach ( $this->sections as $setkey => $setlabel ) {
	if ( 'advanced' === $setkey ) {
		$title_cls   .= ' pp-hidden-settings-title';
		$content_cls .= ' pp-hidden-settings';
	}
	printf( '<div class="pp-options-section-wrapper"><h3 class="%1$s">%2$s</h3><div class="%3$s">', esc_attr( $title_cls ), esc_html( $setlabel ), esc_attr( $content_cls ) );
	if ( 'advanced' === $setkey ) {
		?>
		<p class="pp-notes" style="margin-bottom: 0;"><?php esc_html_e( 'These options are only for specific cases. DO NOT enable if you are not sure.', 'podcast-player' ); ?></p>
		<?php
	}
	do_settings_sections( "pp_{$setkey}_settings" );
	submit_button( 'Save Options' );
	echo '</div></div>';
}
echo '</form>';
