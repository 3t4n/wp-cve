<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Stylish_Cost_Calculator_Coupon {

	public static function init() {
	}
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 90 );

		wp_localize_script( 'scc-backend', 'pageCouponSettings', array( 'nonce' => wp_create_nonce( 'coupon-settings-page' ) ) );

		$this->coupon_page();
	}

	function coupon_page() {
		$i = new couponController();

		wp_enqueue_script( 'jquery-ui-datepicker' );
		$translation_array = 'var rt_vars = ' . json_encode(
			array(
				'rt_adminurl' => admin_url(),
				'rt_url'      => get_site_url(),
				'rt_urlajax'  => ( get_site_url() . '/wp-admin/admin-ajax.php' ),
				'a_value'     => '10',
			)
		);
		wp_add_inline_script( 'scc-js-js', $translation_array );
		?>
		
		<style>
			#border1 {
				border: 2px black;
				-moz-box-shadow: inset 1px 1px 10px #F0F0F0;
				border-radius: 10px;
				background-color: #f8f9ff;
				padding: 15px;
				margin-left: 25px;
				margin-bottom: 15px;
				box-shadow: 0px 0px;
				max-width: 300px;
			}

			.green_dot {
				height: 16px;
				width: 16px;
				background-color: green;
				border-radius: 50%;
				display: inline-block;
				margin-left: 10px;
				margin-right: 10px;
			}

			.red_dot {
				height: 16px;
				width: 16px;
				background-color: red;
				border-radius: 50%;
				display: inline-block;
				margin-left: 10px;
				margin-right: 10px;
			}

			.yellow_dot {
				height: 16px;
				width: 16px;
				background-color: orange;
				border-radius: 50%;
				display: inline-block;
				margin-left: 10px;
				margin-right: 10px;
			}
		</style>
		<?php
		global $wp_version;
		$test_char   = '' . DB_CHARSET;
		$php_version = phpversion();
		ob_start();
		phpinfo( INFO_MODULES );
		$contents              = ob_get_clean();
		$moduleAvailable       = strpos( $contents, 'mod_security' ) !== false;
		$plugin_conflicted     = 0;
		$theme_conflicted      = 0;
		$theme                 = wp_get_theme();
		$spl_license_return    = get_option( 'act_ser_conn_refused' );
		$license_key_activated = get_option( 'spl_license_return' );
		$site_url              = site_url();
		function url() {
			if ( isset( $_SERVER['HTTPS'] ) ) {
				$protocol = ( $_SERVER['HTTPS'] && $_SERVER['HTTPS'] != 'off' ) ? 'https' : 'http';
			} else {
				$protocol = 'http';
			}
			return $protocol . '://' . $_SERVER['HTTP_HOST'];
		}
		$coupons = $i->read();
		echo '<div class="row"><div class="scc_title_bar" >Coupon Generator & Editor (Premium Feature)</div></div>';
		if ( ! isset( $_GET['id'] ) ) {
			echo '<div class="row" style="max-width:500px;background-color:#fff;border-radius:10px;padding-left:25px;padding-bottom:25px;padding-top:25px;margin-left:20px;margin-bottom:30px;">';
			echo '<div style="font-size:20px;margin-bottom:10px;text-align:center;color:black;" class="sccsubtitle scc_email_quote_label">Add New Coupon</span></div>';
			echo '<div style="color:green; margin-left:50px;font-size:15px;">' . ( isset( $_GET['saved'] ) && $_GET['saved'] == true ? 'Coupon have been successfully saved' : '' ) . '</div>';
			echo '<div style="color:green; margin-left:50px;font-size:15px;">' . ( isset( $_GET['deleted'] ) && $_GET['deleted'] == true ? 'Coupon have been successfully deleted' : '' ) . '</div>';
		} else {
			$coupon_array = $i->read( $_GET['id'] );
			if ( is_array( $coupon_array ) ) {
				if ( sizeof( $coupon_array ) > 0 ) {
					$coupon = $coupon_array[0];
				}
			}
			echo '<div class="row" style="max-width:500px;background-color:#fff;border-radius:10px;padding-left:25px;padding-bottom:25px;padding-top:25px;margin-left:20px;margin-bottom:30px;">';
			echo '<div style="font-size:20px;margin-bottom:10px;text-align:center;color:black;" class="sccsubtitle scc_email_quote_label">Edit Existing Coupon</span></div>';
			echo '<div style="color:green; margin-left:50px">' . ( isset( $_GET['saved'] ) && $_GET['saved'] == true ? 'Coupon have been successfully saved' : '' ) . '</div>';
			echo '<div style="color:orange; margin-left:50px">' . ( isset( $_GET['deleted'] ) && $_GET['deleted'] == true ? 'Coupon have been successfully deleted' : '' ) . '</div>';
		}
		echo '<br><div class="row" style="margin-top: 5px">';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div style="font-size:15px;font-weight:bold">Coupon Name</div><small>Internal purposes only</small>';
		echo '</div>';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div class="form-group">';
		$coupon_name = isset( $coupon ) && isset( $coupon->name ) ? htmlspecialchars( sanitize_text_field( $coupon->name ) ) : '';
		?>
		<input type="text" class="form-control" style="height:40px;border:2px solid #e8e8e8" id="coupon_name" value="<?php echo esc_html( $coupon_name ); ?>">
		<?php
		echo '</div>';
		echo '<p id="coupon_name_error" style="color:red"></p>';
		echo '</div>';
		echo '</div>';
		echo '<div class="row" style="margin-top: 5px">';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div style="font-size:15px;font-weight:bold">Coupon Code</div>';
		echo '</div>';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div class="form-group">';
		$coupon_code = isset( $coupon ) && isset( $coupon->code ) ? htmlspecialchars( sanitize_text_field( $coupon->code ) ) : '';
		?>
		<input class="form-control" id="coupon_code" style="height:40px;border:2px solid #e8e8e8" value="<?php echo esc_html( $coupon_code ); ?>">
		<?php
		echo '</div>';
		echo '<p id="coupon_code_error" style="color:red"></p>';
		echo '</div>';
		echo '</div>';
		echo '<div class="row" style="margin-top: 5px">';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div style="font-size:15px;font-weight:bold">Start Date</div>';
		echo '</div>';
		echo '<div class="col-md-6 col-sm-6" data-provide="datepicker">';
		echo '<div class="input-group date">';
		$start_date = isset( $coupon ) && isset( $coupon->startdate ) ? date( 'm/d/Y', strtotime( $coupon->startdate ) ) : '';
		?>
		<input type="text" class="form-control" style="height:40px;border:2px solid #e8e8e8" id="coupon_start_date" value="<?php echo esc_html( $start_date ); ?>" date-format="mm/dd/yy">
		<?php
		echo '<div class="input-group-addon">';
		echo '<span class="fas fa-calendar-alt"></span>';
		echo '</div>';
		echo '</div>';
		echo '<p id="coupon_start_date_error" style="color:red"></p>';
		echo '</div>';
		echo '</div>';
		echo '<div class="row" style="margin-top: 5px">';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div style="font-size:15px;font-weight:bold">End Date</div>';
		echo '</div>';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div class="input-group">';
		$end_date = isset( $coupon ) && isset( $coupon->enddate ) ? date( 'm/d/Y', strtotime( $coupon->enddate ) ) : '';
		?>
		<input class="form-control" style="height:40px;border:2px solid #e8e8e8" id="coupon_end_date" value="<?php echo esc_html( $end_date ); ?>" date-format="mm/dd/yy">
		<?php
		echo '<div class="input-group-addon">';
		echo '<span class="fas fa-calendar-alt"></span>';
		echo '</div>';
		echo '</div>';
		echo '<p id="coupon_end_date_error" style="color:red"></p>';
		echo '</div>';
		echo '</div>';
		echo '<div class="row" style="margin-top: 5px">';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div style="font-size:15px;font-weight:bold">Discount %</div>';
		echo '</div>';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div class="form-group">';
		echo '<input min="0" max="100" style="height:40px;border:2px solid #e8e8e8" type="number" step="0.01" class="form-control" id="coupon_discountpercentage" value="' . ( isset( $coupon ) && isset( $coupon->discountpercentage ) && $coupon->discountpercentage != -1.0 ? floatval( $coupon->discountpercentage ) : '' ) . '">';
		echo '</div>';
		echo '<p id="coupon_discountpercentage_error" style="color:red"></p>';
		echo '</div>';
		echo '</div>';
		echo '<div class="row" style="margin-top: 5px; margin-bottom: 10px">';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div style="font-size:15px;font-weight:bold">Discount $</div>';
		echo '</div>';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div class="form-group">';
		echo '<input type="number" style="height:40px;border:2px solid #e8e8e8" min=0 class="form-control" id="coupon_discountvalue" value="' . ( isset( $coupon ) && isset( $coupon->discountvalue ) && $coupon->discountvalue != -1.0 ? floatval( $coupon->discountvalue ) : '' ) . '">';
		echo '</div>';
		echo '<p id="coupon_discountvalue_error" style="color:red"></p>';
		echo '</div>';
		echo '</div>';
		echo '<div style="padding:10px; cursor: pointer">';
		echo '<div style="font-size:15px;color:#314bf8; margin-bottom: 20px" data-toggle="collapse" data-target=".advanced_settings" data-bs-toggle="collapse" data-bs-target=".advanced_settings" aria-expanded="false" aria-controls=".advanced_settings"> <strong>Advanced Settings</strong></div>';
		echo '<div class="row advanced_settings collapse" style="margin-top: 10px" >';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div style="font-size:15px;font-weight:bold">Minimum Spend</div>';
		echo '</div>';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div class="form-group">';
		echo '<input type="number" style="height:40px;border:2px solid #e8e8e8" min=0 class="form-control" id="coupon_minspend" value="' . ( isset( $coupon ) && isset( $coupon->minspend ) && $coupon->minspend != -1.0 ? floatval( $coupon->minspend ) : '' ) . '">';
		echo '</div>';
		echo '<p id="coupon_minspend_error" style="color:red"></p>';
		echo '</div>';
		echo '</div>';
		echo '<div class="row advanced_settings collapse" style="margin-top: 5px">';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div style="font-size:15px;font-weight:bold">Maximum Spend</div>';
		echo '</div>';
		echo '<div class="col-md-6 col-sm-6">';
		echo '<div class="form-group">';
		echo '<input type="number" style="height:40px;border:2px solid #e8e8e8" min=0 class="form-control" id="coupon_maxspend" value="' . ( isset( $coupon ) && isset( $coupon->maxspend ) && $coupon->maxspend != -1.0 ? floatval( $coupon->maxspend ) : '' ) . '">';
		echo '</div>';
		echo '<p id="coupon_maxspend_error" style="color:red"></p>';
		echo '</div>';
		echo '</div>';
		echo '<input type="hidden" id="coupon_id" value="' . ( isset( $coupon ) && isset( $coupon->id ) ? $coupon->id : '0' ) . '">';
		echo '</div>';
		echo '<div class="row" style="margin-top: 5px; margin-bottom: 30px">';
		echo '<div>';
		echo '<button  onclick="saveCoupon()" id="coupon_saving_button" title="You need to purchase a premium license to use this feature." class="btn btn-lg btn-primary use-tooltip">Save</button>';
		echo '<div style="color:green">' . ( isset( $_GET['success'] ) && $_GET['success'] == true ? 'Coupon have been successfully saved' : '' ) . '</div>';
		echo '<div style="color:orange; margin-left:10px">' . ( isset( $_GET['deleted'] ) && $_GET['deleted'] == true ? 'Coupon have been successfully deleted' : '' ) . '</div>';
		echo '</div>';
		echo '</div></div>';
		echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">';
		echo '<div class="row" style="max-width:1000px;background-color:#f9f9fe;padding-left:25px;padding-bottom:25px;padding-top:25px;margin-left:20px;">';
		echo '<div style="margin-top:20px;font-size:25px;" class="sccsubtitle scc_email_quote_label"><span style="font-weight:800;color:#314bf8">EXISTING</span> COUPONS</div>';
		echo '<table class="table">';
		echo '<thead>';
		echo '<tr>';
		echo '<th style="max-width:10px;">Coupon Name</th>';
		echo '<th style="max-width:11px;">Coupon Code</th>';
		echo '<th style="max-width:11px;">Start Date</th>';
		echo '<th style="max-width:11px;">End Date</th>';
		echo '<th style="max-width:11px;">Disc %</th>';
		echo '<th style="max-width:11px;">Disc $</th>';
		echo '<th style="max-width:15px;">Actions</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach ( $coupons as $c ) {
			echo '<tr>';
			echo '<td>';
			echo $c->name;
			echo '</td>';
			echo '<td>';
			echo $c->code;
			echo '</td>';
			echo '<td>';
			echo date( 'm/d/Y', strtotime( $c->startdate ) );
			echo '</td>';
			echo '<td>';
			echo date( 'm/d/Y', strtotime( $c->enddate ) );
			echo '</td>';
			echo '<td>';
			echo $c->discountpercentage == -1.0 ? '' : $c->discountpercentage;
			echo '</td>';
			echo '<td>';
			echo $c->discountvalue == -1.0 ? '' : $c->discountvalue;
			echo '</td>';
			echo '<td>';
			echo '<button onclick="editCoupon(' . $c->id . ')" class="btn btn-xs btn-primary use-tooltip" title="You need to purchase a premium license to use this feature." style="margin-rigth:15px;">Edit</button>';
			echo '<button onclick="deleteCoupon(' . $c->id . ')" class="btn btn-xs btn-danger use-tooltip" title="You need to purchase a premium license to use this feature." style="margin-rigth:10px">Delete</button>';
			echo '</td>';
			echo '</tr>';
		}
		if ( sizeof( $coupons ) == 0 ) {
			echo '<tr><td colspan="7" style="text-align: center;">No existing Coupons</td></tr>';
		}
		echo '</tbody>';
		echo '</table></div>';
		echo '<div id="delete_coupon_modal_container" class="fade" role="dialog"></div>';
		echo '<script>function editCoupon(id){return;}</script>';
	}
}
$stylish_cost_calculator_Coupon = new Stylish_Cost_Calculator_Coupon();
?>
<script>
	window.addEventListener('DOMContentLoaded', (event) => {
		jQuery('#coupon_end_date').datepicker();
		jQuery('#coupon_start_date').datepicker();
		jQuery('.use-tooltip').each(function(index,node) {
			new bootstrap.Tooltip(node, {delay: { show: 600, hide: 300 }, trigger: 'hover focus', html: true})
		})
	})
	// COUPONS FUNCTIONS !!
	function saveCoupon() {
		return;
	}


	/**
	 * *Handles delete coupon
	 */
	function deleteCoupon(idCoupon){
		return;
	}

</script>
