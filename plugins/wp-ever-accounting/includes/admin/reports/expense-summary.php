<?php
defined( 'ABSPATH' ) || exit();

/**
 * Expense Summary Report.
 */
function eaccounting_reports_expense_summary_tab() {
	$year        = filter_input( INPUT_GET, 'year', FILTER_SANITIZE_NUMBER_INT, array( 'options' => array( 'default' => wp_date( 'Y' ) ) ) );
	$category_id = filter_input( INPUT_GET, 'category_id', FILTER_SANITIZE_NUMBER_INT );
	$account_id  = filter_input( INPUT_GET, 'account_id', FILTER_SANITIZE_NUMBER_INT );
	$vendor_id   = filter_input( INPUT_GET, 'vendor_id', FILTER_SANITIZE_NUMBER_INT );

	?>
	<div class="ea-card is-compact">
		<form action="" class="ea-report-filter">
			<?php
			eaccounting_hidden_input(
				array(
					'name'  => 'page',
					'value' => 'ea-reports',
				)
			);
			eaccounting_hidden_input(
				array(
					'name'  => 'tab',
					'value' => 'expense_summary',
				)
			);

			$years = range( wp_date( 'Y' ), ( $year - 5 ), 1 );
			eaccounting_select2(
				array(
					'placeholder' => __( 'Year', 'wp-ever-accounting' ),
					'name'        => 'year',
					'options'     => array_combine( array_values( $years ), $years ),
					'value'       => $year,
				)
			);
			eaccounting_account_dropdown(
				array(
					'placeholder' => __( 'Account', 'wp-ever-accounting' ),
					'default'     => '',
					'name'        => 'account_id',
					'value'       => $account_id,
					'attr'        => array(
						'data-allow-clear' => true,
					),
				)
			);
			eaccounting_contact_dropdown(
				array(
					'placeholder' => __( 'Vendor', 'wp-ever-accounting' ),
					'name'        => 'vendor_id',
					'type'        => 'vendor',
					'value'       => $vendor_id,
					'attr'        => array(
						'data-allow-clear' => true,
					),
				)
			);
			eaccounting_category_dropdown(
				array(
					'placeholder' => __( 'Category', 'wp-ever-accounting' ),
					'name'        => 'category_id',
					'type'        => 'expense',
					'value'       => $category_id,
					'attr'        => array(
						'data-allow-clear' => true,
					),
				)
			);
			submit_button( __( 'Filter', 'wp-ever-accounting' ), 'action', false, false );
			?>
		</form>
	</div>
	<div class="ea-card">
		<?php
		global $wpdb;
		$dates      = array();
		$totals     = array();
		$expenses   = array();
		$graph      = array();
		$categories = array();
		$start      = eaccounting_get_financial_start( $year );
		$end        = eaccounting_get_financial_end( $year );

		$where  = "category_id NOT IN ( SELECT id from {$wpdb->prefix}ea_categories WHERE type='other')";
		$where .= $wpdb->prepare( ' AND (payment_date BETWEEN %s AND %s)', $start, $end );
		if ( ! empty( $account_id ) ) {
			$where .= $wpdb->prepare( ' AND account_id=%d', $account_id );
		}
		if ( ! empty( $vendor_id ) ) {
			$where .= $wpdb->prepare( ' AND contact_id=%d', $vendor_id );
		}
		if ( ! empty( $category_id ) ) {
			$where .= $wpdb->prepare( ' AND category_id=%d', $category_id );
		}

		$transactions = $wpdb->get_results(
			"
		SELECT name, payment_date, currency_code, currency_rate, amount, ea_categories.id category_id
		FROM {$wpdb->prefix}ea_transactions ea_transactions
		LEFT JOIN {$wpdb->prefix}ea_categories ea_categories ON ea_categories.id=ea_transactions.category_id
		WHERE $where AND ea_transactions.type = 'expense'
		"
		);

		foreach ( $transactions as $key => $transaction ) {
			$transaction->amount = eaccounting_price_to_default( $transaction->amount, $transaction->currency_code, $transaction->currency_rate );

			$transactions[ $key ] = $transaction;
		}

		$categories = wp_list_pluck( $transactions, 'name', 'category_id' );
		$date       = new \EverAccounting\DateTime( $start );
		// Dates.
		for ( $j = 1; $j <= 12; $j ++ ) {
			$dates[ $j ]                     = $date->format( 'F' );
			$graph[ $date->format( 'F-Y' ) ] = 0;
			// Totals.
			$totals[ $dates[ $j ] ] = array(
				'amount' => 0,
			);

			foreach ( $categories as $cat_id => $category_name ) {
				$expenses[ $cat_id ][ $dates[ $j ] ] = array(
					'category_id' => $cat_id,
					'name'        => $category_name,
					'amount'      => 0,
				);
			}
			$date->modify( '+1 month' )->format( 'Y-m' );
		}

		foreach ( $transactions as $transaction ) {
			if ( isset( $expenses[ $transaction->category_id ] ) ) {
				$month      = wp_date( 'F', strtotime( $transaction->payment_date ) );
				$month_year = wp_date( 'F-Y', strtotime( $transaction->payment_date ) );
				$expenses[ $transaction->category_id ][ $month ]['amount'] += $transaction->amount;
				$graph[ $month_year ]                                      += $transaction->amount;
				$totals[ $month ]['amount']                                += $transaction->amount;
			}
		}
		$chart = new \EverAccounting\Chart();
		$chart->type( 'line' )->width( 0 )->height( 300 )->set_line_options()->labels( array_values( $dates ) )
			->dataset(
				array(
					'label'           => __( 'Expense', 'wp-ever-accounting' ),
					'data'            => array_values( $graph ),
					'borderColor'     => '#f2385a',
					'backgroundColor' => '#f2385a',
					'borderWidth'     => 4,
					'pointStyle'      => 'line',
					'fill'            => false,
				)
			)
		?>
		<div class="ea-report-graph">
			<?php $chart->render(); ?>
		</div>
		<div class="ea-table-report">
			<table class="ea-table">
				<thead>
				<tr>
					<th><?php esc_html_e( 'Categories', 'wp-ever-accounting' ); ?></th>
					<?php foreach ( $dates as $date ) : ?>
						<th class="align-right"><?php echo esc_html( $date ); ?></th>
					<?php endforeach; ?>
				</tr>
				</thead>
				<tbody>

				<?php if ( ! empty( $expenses ) ) : ?>
					<?php foreach ( $expenses as $category_id => $category ) : ?>
						<tr>
							<td><?php echo esc_html( $categories[ $category_id ] ); ?></td>
							<?php foreach ( $category as $item ) : ?>
								<td class="align-right"><?php echo esc_html( eaccounting_format_price( $item['amount'] ) ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr class="no-results">
						<td colspan="13">
							<p><?php esc_html_e( 'No records found', 'wp-ever-accounting' ); ?></p>
						</td>
					</tr>
				<?php endif; ?>
				</tbody>
				<tfoot>
				<tr>
					<th><?php esc_html_e( 'Total', 'wp-ever-accounting' ); ?></th>
					<?php foreach ( $totals as $total ) : ?>
						<th class="align-right"><?php echo esc_html( eaccounting_format_price( $total['amount'] ) ); ?></th>
					<?php endforeach; ?>
				</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<?php
}

add_action( 'eaccounting_reports_tab_expense_summary', 'eaccounting_reports_expense_summary_tab' );
