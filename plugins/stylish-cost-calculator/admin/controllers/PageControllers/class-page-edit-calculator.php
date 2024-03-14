<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Stripe\Terminal\Location;

require_once dirname( __FILE__ ) . '/class-pages-breadcrumbs.php';
/**
 * *This loads one calculator, settings, translation and preview
 * todo: must inherit PagesBreadcrumbs class
 * @param id_form
 */
class PageEditTabs extends PagesBreadcrumbs {

	public function __construct() {
		require dirname( __DIR__, 1 ) . '/formController.php';
		$formC = new formController();

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		wp_register_script( 'scc-sortable', SCC_URL . 'lib/sortable/sortable.min.js', array( 'jquery' ), STYLISH_COST_CALCULATOR_VERSION, true );
		wp_enqueue_script( 'scc-sortable' );
		wp_enqueue_script( 'jquery-effects-core' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );

		wp_enqueue_script( 'jquery-ui-tooltip' );

		wp_enqueue_style( 'scc-admin-style' );

		//added to load shortcode
		wp_enqueue_style( 'scc-checkbox1' );
		wp_enqueue_script( 'scc-tom-select' );
		wp_enqueue_style( 'scc-bootstrapslider-css' );
		wp_enqueue_script( 'scc-bootstrapslider-js' );
		wp_enqueue_script( 'scc-nouislider' );
		wp_enqueue_script( 'scc-frontend' );
		wp_enqueue_script( 'wp-util' );
		wp_enqueue_script( 'scc-translate-js' );

		$currencies_array = 'window["scc_currencies"] = ' . json_encode(
			require_once( SCC_DIR . '/lib/currency_data.php' )
		);
		wp_add_inline_script( 'scc-frontend', $currencies_array );

		add_thickbox();
		// global $scc_googlefonts_var;

		$f1          = $formC->readWithRelations( $_GET['id_form'] );
		$isActivated = get_option( 'df_scc_licensed', 0 ) ? true : false;

		parent::__construct();
		wp_enqueue_media();

		if ( ! $f1 ) {
			return $this->show_eror();
		}

		$this->updateCalculatorUsageStat();

		require dirname( __DIR__, 2 ) . '/views/adminHeader.php';
		require dirname( __DIR__, 2 ) . '/views/searchAndTabs.php';
		require dirname( __DIR__, 2 ) . '/models/editElementModel.php';
		require dirname( __DIR__, 2 ) . '/views/editCalcualtor.php';
		require dirname( __DIR__, 2 ) . '/views/extraSettings.php';
		require dirname( __DIR__, 2 ) . '/views/adminFooter.php';
	}

	function show_eror() {
		?>
		<script>
			jQuery(document).ready(function() {
				Swal.fire({
					icon: 'error',
					allowOutsideClick: false,
					title: 'Oops...',
					confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
					text: "Calculator with ID <?php echo intval( $_GET['id_form'] ); ?> doesn't exist, or has been deleted. Please recheck your shortcode ",
				}).then((result) => {
					if (result.isConfirmed) {
						location.href = '<?php echo esc_url( admin_url( 'admin.php?page=scc_edit_items' ) ); ?>'
					}
				})
			})
		</script>
		<?php
	}
	function activation_prompt() {
		?>
		<script>
			jQuery(document).ready(function() {
				Swal.fire({
					icon: 'warning',
					allowOutsideClick: false,
					title: 'Pending License Activation',
					confirmButtonText: 'Activate Now',
					text: "Please activate Stylish Cost Calculator with a license key",
				}).then((result) => {
					if (result.isConfirmed) {
						location.href = '<?php echo esc_url( admin_url( 'admin.php?page=stylish_cost_calculator_premium_settings' ) ); ?>'
					}
				})
			})
		</script>
		<?php
	}

	private function updateCalculatorUsageStat() {
		if ( get_option( 'df-scc-save-count', null ) !== null ) {
			$save_count = get_option( 'df-scc-save-count' );
			update_option( 'df-scc-save-count', ( $save_count + 1 ) );
		} else {
			update_option( 'df-scc-save-count', 0 );
		}
	}
}
new PageEditTabs();
