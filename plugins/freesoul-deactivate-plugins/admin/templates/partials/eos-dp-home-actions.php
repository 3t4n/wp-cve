<?php
/**
 * Template Home Actions.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$page_url = isset( $_GET['page'] ) && 'eos_dp_by_archive' === $_GET['page'] ? $archive_url : get_permalink( $post->ID );
$ttfb_url = add_query_arg(
	array(
		'resource'      => urlencode( add_query_arg( $args, $page_url ) ),
		'display_usage' => 'false',
	),
	'https://www.bytecheck.com/results'
);
?>
<a data-page_speed_insights="true" data-encode_url="true" title="<?php esc_html__( 'Check the TTFB loading plugins andd the theme according the settings you see now on this row (beta)', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-preview eos-dp-ttfb-preview" oncontextmenu="return false;" href="<?php echo esc_url( $ttfb_url ); ?>" target="_blank" rel="noopener">
	<span class="dashicons dashicons-search">
		<span class="eos-dp-ttfb-icon">TTFB</span>
	</span>
</a>
<?php
$url = add_query_arg(
	array(
		'url'           => add_query_arg( $args, $page_url ),
		'display_usage' => 'false',
	),
	'https://search.google.com/test/mobile-friendly'
);
?>
<a data-page_speed_insights="true" data-encode_url="true" title="<?php esc_html__( 'Check mobile usability loading plugins and the theme according the settings you see now on this row', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-preview eos-dp-ttfb-preview" oncontextmenu="return false;" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
	<span class="dashicons dashicons-search">
		<span class="dashicons dashicons-smartphone"></span>
	</span>
</a>
