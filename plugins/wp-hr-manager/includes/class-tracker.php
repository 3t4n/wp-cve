<?php
namespace WPHR\HR_MANAGER;

/**
 * Tracker class
 */
class Tracker extends \wphr_Insights {

    public function __construct() {

        $notice = __( 'Want to help make <strong>WPHR Manager</strong> even more awesome? Allow WPHR to collect non-sensitive diagnostic data and usage information.', 'wphr' );

        parent::__construct( 'wphr', 'WPHR Manager', WPHR_FILE, $notice );
    }

    /**
     * Get the extra data
     *
     * @return array
     */
    protected function get_extra_data() {
        $data = array(
            'active_modules' => get_option( 'wphr_modules', [] ),
            'contacts'       => $this->get_people_count( 'contact' ),
            'customer'       => $this->get_people_count( 'customer' ),
            'vendor'         => $this->get_people_count( 'vendor' ),
            'sales'          => $this->transaction_type_count( 'sales' ),
            'expense'        => $this->transaction_type_count( 'expense' ),
        );

        return $data;
    }

    /**
     * Get people type count
     *
     * @param  string  $type
     *
     * @return integer
     */
    private function get_people_count( $type ) {
        return \WPHR\HR_MANAGER\Framework\Models\People::type( $type )->count();
    }

    private function transaction_type_count( $type ) {
		if ( file_exists(WPHR_MODULES . '/accounting/includes/models/transaction.php'))
		{
			if ( ! class_exists( '\WPHR\HR_MANAGER\Accounting\Model\Transaction' ) ) {
				require_once WPHR_MODULES . '/accounting/includes/models/transaction.php';
			}

			return \WPHR\HR_MANAGER\Accounting\Model\Transaction::type( $type )->count();
		}
    }
}
