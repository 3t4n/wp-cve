<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_COUPON_BOX_Admin_Export_Email {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'menu_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );
		add_action( 'admin_init', array( $this, 'generate_csv' ) );
	}

	/**
	 * Converting data to CSV
	 */
	public function generate_csv() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['wcb_export_email'] ) ) {
			return;
		}
		if ( isset( $_POST['_woocouponboxexpemail_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_woocouponboxexpemail_nonce'] ) ), 'woocouponboxexpemail_action_nonce' ) ) {
			echo 'a1';
			$p_type        = 'wcb';
			$select_export = isset( $_POST['wcb_select_export'] ) ? sanitize_text_field( wp_unslash( $_POST['wcb_select_export'] ) ) : 1;
			$filename      = "woo_coupon_box_";
			if ( $select_export == '1' ) {
				$start = isset( $_POST['wcb_exp_start_date'] ) ? sanitize_text_field( wp_unslash( $_POST['wcb_exp_start_date'] ) ) : '';
				$end   = isset( $_POST['wcb_exp_end_date'] ) ? sanitize_text_field( wp_unslash( $_POST['wcb_exp_end_date'] ) ) : '';

				if ( ! $start && ! $end ) {
					$args     = array(
						'post_type'      => $p_type,
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
					);
					$filename .= date_i18n( 'Y-m-d_h-i-s', time() ) . ".csv";
				} elseif ( ! $start ) {
					$args     = array(
						'post_type'      => $p_type,
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
						'date_query'     => array(
							array(
								'before'    => $end,
								'inclusive' => true

							)
						),
					);
					$filename .= 'before_' . $end . ".csv";
				} elseif ( ! $end ) {
					$args     = array(
						'post_type'      => $p_type,
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
						'date_query'     => array(
							array(
								'after'     => $start,
								'inclusive' => true
							)
						),

					);
					$filename .= 'from' . $start . 'to' . date_i18n( 'Y-m-d' ) . ".csv";
				} else {
					if ( strtotime( $start ) > strtotime( $end ) ) {
						wp_die( 'Incorrect input date' );
					}
					$args     = array(
						'post_type'      => $p_type,
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
						'date_query'     => array(
							array(
								'before'    => $end,
								'after'     => $start,
								'inclusive' => true

							)
						),
					);
					$filename .= 'from' . $start . 'to' . $end . ".csv";
				}
			} else {
				$wcb_post_campaign = isset( $_POST['wcb_post_campaign'] ) ? sanitize_text_field( wp_unslash( $_POST['wcb_post_campaign'] ) ) : '';
				if ( $wcb_post_campaign ) {
					$args     = array(
						'post_type'      => 'wcb',
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'wcb_email_campaign',
								'field'    => 'term_id',
								'terms'    => $wcb_post_campaign

							)
						),
					);
					$filename .= ( get_term_by( 'id', $wcb_post_campaign, 'wcb_email_campaign' )->name ) . '_' . date_i18n( 'Y-m-d_h-i-s', time() ) . '.csv';
				}
			}

			if ( isset( $args ) ) {
				$the_query        = new WP_Query( $args );
				$csv_source_array = array();
				$csv_meta_data    = array();
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$id                 = get_the_ID();
						$meta_data          = get_post_meta( $id, 'woo_coupon_box_meta', true );
						$csv_meta_data[]    = $meta_data;
						$csv_source_array[] = get_the_title();
					}
					wp_reset_postdata();
					$data_rows  = array();
					$header_row = array(
						'order',
						'email',
						'name',
						'mobile',
						'birthday',
						'gender',
					);
					foreach ( $csv_source_array as $key => $result ) {
						$row         = array();
						$row[0]      = ( $key + 1 );
						$row[1]      = $result;
						$row[2]      = isset( $csv_meta_data[ $key ]['name'] ) ? $csv_meta_data[ $key ]['name'] : '';
						$row[3]      = isset( $csv_meta_data[ $key ]['mobile'] ) ? $csv_meta_data[ $key ]['mobile'] : '';
						$row[4]      = isset( $csv_meta_data[ $key ]['birthday'] ) ? $csv_meta_data[ $key ]['birthday'] : '';
						$row[5]      = isset( $csv_meta_data[ $key ]['gender'] ) ? $csv_meta_data[ $key ]['gender'] : '';
						$data_rows[] = $row;
					}

					$fh = @fopen( 'php://output', 'w' );

					fprintf( $fh, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );
					header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
					header( 'Content-Description: File Transfer' );
					header( 'Content-type: text/csv' );
					header( 'Content-Disposition: attachment; filename=' . $filename );
					header( 'Expires: 0' );
					header( 'Pragma: public' );
					fputcsv( $fh, $header_row );

					foreach ( $data_rows as $data_row ) {
						fputcsv( $fh, $data_row );
					}

					fclose( $fh );
					die();
				}
			}
		}
	}

	public function menu_options() {
		add_submenu_page(
			'edit.php?post_type=wcb',
			esc_html__( 'Export Email', 'woo-coupon-box' ),
			esc_html__( 'Export Email', 'woo-coupon-box' ),
			'manage_options',
			'woo_coupon_box_export_email',
			array( $this, 'setting_page_export' )
		);
	}

	public function setting_page_export() {
		global $wp_version;
		?>
        <div class="wrap">
            <h2><?php echo esc_html__( 'Export email subscribe', 'woo-coupon-box' ); ?></h2>

            <div class="vi-ui raised">
                <form class="vi-ui form" method="post" action="">
					<?php
					wp_nonce_field( 'woocouponboxexpemail_action_nonce', '_woocouponboxexpemail_nonce' );
					settings_fields( 'woo-coupon-box' );
					do_settings_sections( 'woo-coupon-box' );
					?>
                    <div class="two fields">
                        <div class="field">
                            <label for=""><?php esc_html_e( 'Choose type', 'woo-coupon-box' ); ?></label>
                            <select class="vi-ui dropdown wcb_select_export" name="wcb_select_export">
                                <option value="1"><?php esc_html_e( 'Date', 'woo-coupon-box' ); ?></option>
                                <option value="2"><?php esc_html_e( 'Campaign', 'woo-coupon-box' ); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="wcb_post_date">
                        <div class="two fields">
                            <div class="field">
                                <label for="wcb_exp_start_date"><?php esc_html_e( 'Start Date', 'woo-coupon-box' ) ?></label>
                                <input type="date" class="vi-ui" value="" name="wcb_exp_start_date"
                                       id="wcb_exp_start_date"/>
                            </div>
                        </div>
                        <div class="two fields">
                            <div class="field">
                                <label for="wcb_exp_end_date"><?php esc_html_e( 'End Date', 'woo-coupon-box' ) ?></label>
                                <input type="date" class="vi-ui" value="" name="wcb_exp_end_date"
                                       id="wcb_exp_end_date"/>
                            </div>
                        </div>
                    </div>
                    <div class="wcb_post_campaign">
                        <label for=""><?php esc_html_e( 'Choose campaign', 'woo-coupon-box' ); ?></label>
                        <select class="vi-ui dropdown wcb_select_export" name="wcb_post_campaign">
							<?php
							if ( $wp_version < '4.5.0' ) {
								$terms_cp = get_terms( 'wcb_email_campaign', array(
									'hide_empty' => false,
								) );
							} else {
								$terms_cp = get_terms( array(
									'taxonomy'   => 'wcb_email_campaign',
									'hide_empty' => false,
								) );
							}
							if ( count( $terms_cp ) ) {
								foreach ( $terms_cp as $term_cp ) {
									echo '<option value="' . esc_attr( $term_cp->term_id ) . '">' . esc_html( $term_cp->name ) . '</option>';
								}
							}
							?>

                        </select>
                    </div>
					<?php submit_button( 'Export', 'primary', 'wcb_export_email' ); ?>
                </form>
            </div>
        </div>
		<?php
	}

	public function admin_enqueue_script() {
		$screen = get_current_screen()->id;
		if ( $screen !== 'wcb_page_woo_coupon_box_export_email' ) {
			return;
		}

		// style
		wp_enqueue_style( 'woo-coupon-box-export-form', VI_WOO_COUPON_BOX_CSS . 'form.min.css', '', VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-export-transition', VI_WOO_COUPON_BOX_CSS . 'transition.min.css', '', VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-export-dropdown', VI_WOO_COUPON_BOX_CSS . 'dropdown.min.css', '', VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-export-segment', VI_WOO_COUPON_BOX_CSS . 'segment.min.css', '', VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-export-datepicker', VI_WOO_COUPON_BOX_CSS . 'wcb_datepicker.css', '', VI_WOO_COUPON_BOX_VERSION );

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'woo-coupon-box-export-form', VI_WOO_COUPON_BOX_JS . 'form.min.js', array( 'jquery' ), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_script( 'woo-coupon-box-export-transition', VI_WOO_COUPON_BOX_JS . 'transition.min.js', array( 'jquery' ), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_script( 'woo-coupon-box-export-dropdown', VI_WOO_COUPON_BOX_JS . 'dropdown.min.js', array( 'jquery' ), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_script( 'woo-coupon-box-export-dependOn', VI_WOO_COUPON_BOX_JS . 'dependsOn-1.0.2.min.js', array( 'jquery' ), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_script( 'woo-coupon-box-export-admin', VI_WOO_COUPON_BOX_JS . 'wcb_export_date.js', array( 'jquery' ), VI_WOO_COUPON_BOX_VERSION );
	}

}