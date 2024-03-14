<?php
/**
 * The statistic tab page
 *
 * @package YITH/Search/Utils
 * @version 2.1.0
 *
 * @var string $from
 * @var string $to
 * @var int    $counter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$from_time = $from ?? '';
$to_time   = $to ?? '';

$from    = isset( $from ) ? substr( $from, 0, 10 ) : '';
$to      = isset( $to ) ? substr( $to, 0, 10 ) : '';
$counter = $counter ?? 7;

$url_params = array(
	'page' => 'yith_wcas_panel',
	'tab'  => 'statistic',
	'from' => $from,
	'to'   => $to,
);


?>
<div class="ywcas-statistic">
	<?php require_once YITH_WCAS_INC . 'admin/views/panel/statistic-filter.php'; ?>
    <div class="ywcas-statistic-wrapper">
        <div class="ywcas-statistic-grid">
            <div class="ywcas-statistic-block top-searched">
                <h2 class="ywcas-statistic-block--title"><?php esc_html_e( 'Top Searches', 'yith-woocommerce-ajax-search' ); ?></h2>
				<?php
				$top_searches      = YITH_WCAS_Data_Search_Query_Log::get_top_searches( $from_time, $to_time, $counter, 0 );
				$best_tot_searches = count( $top_searches ) > 0 ? $top_searches[0]['searches'] : 0;
				if ( $best_tot_searches ) :
					$admin_url = add_query_arg( array_merge( $url_params, array( 'view_all' => 'searched' ) ), admin_url( 'admin.php' ) );
					foreach ( $top_searches as $query ) :
						$percentage = round( $query['searches'] / $best_tot_searches * 100 );
						?>
                        <div class="ywcas-statistic-block--term-wrapper">
                            <div class="ywcas-statistic-block--term-info"><span class="ywcas-statistic-block--term-info__term"><?php echo esc_html( $query['query'] ); ?></span><span class="ywcas-statistic-block--term-info__counter"><?php echo esc_html( $query['searches'] ); ?></span></div>
                            <div class="ywcas-statistic-block--term-progress">
                                <div class="ywcas-statistic-block--term-progress-bar" style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
                            </div>
                        </div>
					<?php endforeach; ?>
                    <div class="ywcas-statistic-block-view-all"><a href="<?php echo esc_url( $admin_url ); ?>"><?php esc_html_e( 'View all >', 'yith-woocommerce-ajax-search' ); ?></a></div>
				<?php else : ?>
                    <div class="no-result-stats">
						<?php
						echo wp_kses_post(
							sprintf( /* translators: %s is a html tag*/
								_x(
									'No statistics%sare reported for this period',
									'placeholder is an html tag',
									'yith-woocommerce-ajax-search'
								),
								'<br>'
							)
						);
						?>
                    </div>
				<?php endif; ?>

            </div>
            <div class="ywcas-statistic-block top-clicked">
                <h2 class="ywcas-statistic-block--title"><?php esc_html_e( 'Top Clicked Products', 'yith-woocommerce-ajax-search' ); ?></h2>
				<?php
				$top_clicked     = YITH_WCAS_Data_Search_Query_Log::get_top_clicked_products( $from_time, $to_time, $counter, 0 );
				$best_tot_clicks = count( $top_clicked ) > 0 ? $top_clicked[0]['clicks'] : 0;
				if ( $best_tot_clicks ) :
					$admin_url = add_query_arg( array_merge( $url_params, array( 'view_all' => 'clicked' ) ), admin_url( 'admin.php' ) );
					foreach ( $top_clicked as $product_clicked ) :
						$product = wc_get_product( $product_clicked['product_id'] );
						if ( ! $product instanceof WC_Product ) {
							continue;
						}
						$percentage = round( $product_clicked['clicks'] / $best_tot_clicks * 100 );
						?>
                        <div class="ywcas-statistic-block--term-wrapper">
                            <div class="ywcas-statistic-block--term-info"><span class="ywcas-statistic-block--term-info__term"><?php echo esc_html( $product->get_name() ); ?></span><span class="ywcas-statistic-block--term-info__counter"><?php echo esc_html( $product_clicked['clicks'] ); ?></span></div>
                            <div class="ywcas-statistic-block--term-progress">
                                <div class="ywcas-statistic-block--term-progress-bar" style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
                            </div>
                        </div>
					<?php endforeach; ?>
                    <div class="ywcas-statistic-block-view-all"><a href="<?php echo esc_url( $admin_url ); ?>"><?php esc_html_e( 'View all >', 'yith-woocommerce-ajax-search' ); ?></a></div>
				<?php else : ?>
                    <div class="no-result-stats">
						<?php
						echo wp_kses_post(
							sprintf(/* translators: %s is a html tag */
								_x(
									'No statistics%sare reported for this period',
									'placeholder is an html tag',
									'yith-woocommerce-ajax-search'
								),
								'<br>'
							)
						);
						?>
                    </div>
				<?php endif; ?>
            </div>
            <div class="ywcas-statistic-block top-no-results">
                <h2 class="ywcas-statistic-block--title"><?php esc_html_e( 'Searches with "No Results"', 'yith-woocommerce-ajax-search' ); ?></h2>
				<?php
				$top_no_results      = YITH_WCAS_Data_Search_Query_Log::get_top_no_results( $from_time, $to_time, $counter, 0 );
				$best_tot_no_results = count( $top_no_results ) > 0 ? $top_no_results[0]['no_results'] : 0;
				if ( $best_tot_no_results ) :
					$admin_url = add_query_arg( array_merge( $url_params, array( 'view_all' => 'no_results' ) ), admin_url( 'admin.php' ) );
					foreach ( $top_no_results as $query ) :
						$percentage = round( $query['no_results'] / $best_tot_no_results * 100 );
						?>
                        <div class="ywcas-statistic-block--term-wrapper">
                            <div class="ywcas-statistic-block--term-info"><span class="ywcas-statistic-block--term-info__term"><?php echo esc_html( $query['query'] ); ?></span><span class="ywcas-statistic-block--term-info__counter"><?php echo esc_html( $query['no_results'] ); ?></span></div>
                            <div class="ywcas-statistic-block--term-progress">
                                <div class="ywcas-statistic-block--term-progress-bar" style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
                            </div>
                        </div>
					<?php endforeach; ?>
                    <div class="ywcas-statistic-block-view-all"><a href="<?php echo esc_url( $admin_url ); ?>"><?php esc_html_e( 'View all >', 'yith-woocommerce-ajax-search' ); ?></a></div>
				<?php else : ?>
                    <div class="no-result-stats">
						<?php
						echo wp_kses_post(
							sprintf( /* translators: %s is a html tag */
								_x(
									'No statistics%sare reported for this period',
									'placeholder is an html tag',
									'yith-woocommerce-ajax-search'
								),
								'<br>'
							)
						);
						?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>
