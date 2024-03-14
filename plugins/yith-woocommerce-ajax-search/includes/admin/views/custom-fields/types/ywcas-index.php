<?php
/**
 * Template file to set Index field
 *
 * @package YITH\Search\Views
 *
 * @var array $field The field.
 */

defined( 'ABSPATH' ) || exit;
list ( $field_id, $class, $name, $custom_attributes ) = yith_plugin_fw_extract( $field, 'id', 'class', 'name', 'custom_attributes', );
$last_process = get_option( 'ywcas_last_index_process' );
$process      = array();
if ( $last_process ) {
	$process_transient_name = ywcas()->indexer->get_process_transient_name( $last_process );
	$process                = get_transient( $process_transient_name );
}
$progress = $process['progress'] ?? 0;
?>

<div class="yith-plugin-fw-slider-container <?php echo empty( $class ) ? esc_attr( $class ) : ''; ?> ywcas-index">
	<div class="yith-wcas-success-message hide"><i class="yith-icon yith-icon-check-alt"></i> <?php esc_html_e( 'The search index has been built successfully!', 'yith-woocommerce-ajax-search' ); ?></div>
	<div class="yith-wcas-waiting-message <?php esc_attr_e( ( $progress > 0 && $progress < 100 ) ? '' : 'hide' ); ?>">
		<span>
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block; shape-rendering: auto;" width="25px" height="25px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
			<g>
				<path d="M50 15A35 35 0 1 0 74.74873734152916 25.251262658470843" fill="none" stroke="#afafaf" stroke-width="7"></path>
				<path d="M49 3L49 27L61 15L49 3" fill="#afafaf"></path>
				<animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="2s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
			</g>
		</svg>
		</span>
		<?php esc_html_e( 'Search indexing has started! You can leave this page if you want to, the indexing process will continue in the background.', 'yith-woocommerce-ajax-search' ); ?></div>
	<?php
	if ( $last_process ) :
		if ( ! empty( $process ) ) :
			?>
			<p><strong><?php echo esc_html_x( 'Indexed:', 'label to show the number of element indexed', 'yith-woocommerce-ajax-search' ); ?></strong> 
								  <?php
									printf(
										esc_html__( '%1$d / %2$d items - %3$d%%', 'yith-woocommerce-ajax-search' ),
										$process['processed_items'],
										$process['total_items'],
										intval( $process['progress'] )
									);
									?>
					</p>
			<p><strong><?php echo esc_html_x( 'On date:', 'label to show the number of element indexed', 'yith-woocommerce-ajax-search' ); ?></strong> <?php echo date_i18n( wc_date_format(), $process['start_date'] ); ?></p>
		<?php endif; ?>
	<?php else : ?>
		<p><?php echo esc_html_x( 'The shop data are not indexed. Click "Rebuild index" to start the process', 'label of a button', 'yith-woocommerce-ajax-search' ); ?></p>
	<?php endif; ?>
	<input type="hidden" id="process_percentage" value="<?php esc_attr_e( $progress ); ?>">
	<button class="yith-plugin-fw__button yith-plugin-fw__button--primary ywcas-rebuild-index"><?php echo esc_html_x( 'Rebuild index', 'label of a button', 'yith-woocommerce-ajax-search' ); ?></button>
</div>
