<?php
/**
 * This is remote method file.
 *
 * @package broken-link-finder/helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'MOBLC_Post' ) ) {
	/**
	 * This is remote method class.
	 */
	class MOBLC_Post {
		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'moblc_handle_post' ) );
		}
		/**
		 * Function for calling function to download report csv file.
		 *
		 * @return void
		 */
		public function moblc_handle_post() {
			if ( current_user_can( 'manage_options' ) && isset( $_POST['option'] ) && ! empty( $_POST['option'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce is being verified in further functions.
				$option = sanitize_text_field( wp_unslash( $_POST['option'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce is being verified in each functions seprately.
				switch ( $option ) {
					case 'moblc_download_report_csv':
						$this->moblc_download_report_csv();
						break;
				}
			}
		}
		/**
		 * Function to download csv report.
		 *
		 * @return void
		 */
		public function moblc_download_report_csv() {
			$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';
			if ( ! wp_verify_nonce( $nonce, 'DownloadReportNonce' ) && ! wp_verify_nonce( $nonce, 'DownloadRedirectionReportNonce' ) ) {
				exit;
			}
			$generated_date = gmdate( 'd-m-Y His' );
			global $moblc_db_queries;
			$csv_output = '';
			$table      = 'moblc_link_details_table';
			$separator  = ',';
			$result     = $moblc_db_queries->moblc_get_column_names( $table );
			if ( count( $result ) > 0 ) {
				$i = 0;
				foreach ( $result as $row ) {
					$csv_output = $csv_output . $row . $separator;
					$i ++;
				}
				$csv_output = substr( $csv_output, 0, - 1 );
			}
			$csv_output .= "\n";

			$values = $moblc_db_queries->moblc_get_table_data( $table );
			$sid    = 1;
			foreach ( $values as $rowr ) {
				$i = 0;
				foreach ( $rowr as $field ) {
					if ( strlen( $rowr->status_code ) === 3 ) {
						if ( 0 === $i ) {
							$csv_output = $csv_output . $sid . $separator;
						} else {
							$csv_output = $csv_output . trim( $field ) . $separator;
						}
					}
					$i ++;
				}
				$csv_output  = substr( $csv_output, 0, - 1 );
				$csv_output .= "\n";
				$sid ++;
			}
			header( 'Pragma: public' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Cache-Control: private', false );
			header( 'Content-Type: application/x-excel' );
			header(
				'Content-Disposition: attachment; filename="report' . $generated_date
				. '.csv";'
			);
			header( 'Content-Transfer-Encoding: binary' );
			echo esc_html( $csv_output );
			exit;
		}

	}
	new MOBLC_Post();
}


