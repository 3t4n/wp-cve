<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class dealer {

	public function __construct() {
		if ( isset( $_GET['page'] ) ) {
			$this->get( $_GET['page'] );
		}
	}
	protected function get( $page ) {
		// include notifications handler on all pages
		require dirname( __FILE__ ) . '/notificationsController.php';
		/**
		 * * This renders the pages acording to the menu options
		 * todo: needs formController to load data of forms
		 * @param page
		 */
		switch ( $page ) {
			case 'add_new_form2':
				require dirname( __FILE__ ) . '/PageControllers/class-page-new-calcualtor.php';
				break;
			case 'scc_edit_items' && isset( $_GET['id_form'] ):
				require dirname( __FILE__ ) . '/PageControllers/class-page-edit-calculator.php';
				break;
			case 'scc_edit_items':
				require dirname( __FILE__ ) . '/PageControllers/class-page-all-forms.php';
				break;
			case 'scc-diagnostics':
				require dirname( __FILE__ ) . '/PageControllers/class-page-diagnostic.php';
				break;
			case 'scc-help':
				require dirname( __FILE__ ) . '/PageControllers/class-page-help.php';
				break;
			case 'scc-license-help':
				require dirname( __FILE__ ) . '/PageControllers/class-page-members.php';
				break;
			case 'scc-global-settings':
				require dirname( __FILE__ ) . '/PageControllers/class-page-settings.php';
				break;
			case 'licence':
				require dirname( __FILE__ ) . '/PageControllers/class-page-licence.php';
				break;
			case 'scc-quote-management-screen':
				require dirname( __FILE__ ) . '/PageControllers/class-page-quote.php';
				break;
			case 'scc-coupons-management':
				require dirname( __FILE__ ) . '/PageControllers/class-page-coupons.php';
				break;
			case 'Stylish_Cost_Calculator_Migration':
				require dirname( __FILE__ ) . '/PageControllers/class-page-migration.php';
				break;
			default:
				require dirname( __FILE__ ) . '/PageControllers/class-page-new-calcualtor.php';
		}
	}
}
new dealer();
