<?php

/**
 * Integration Import.
 * 
 * The functionality of this file is to import the integration options from the lite version.
 * Importing the integration options will allow the user to use the same integration options from the lite version.
 * Importing is done by saving the integration options to the database.
 * 
 * @package date-time-picker-field
 * @author InputWP <support@inputwp.com>
 * @link https://www.inputwp.com InputWP
 * @license https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0+
 * 
 */

namespace CMoreira\Plugins\DateTimePicker\Integration;


class IntegrationImport {


	/**
	 * Add basic actions for menu and settings
	 *
	 * @return Void
	 */
	public function __construct() {

    $imported = $this->check_already_imported();
    if (false == $imported) {

			$data = $this->get_data();
      $this->set_data( $data );
    }
	}


  protected function get_data() {

		$helper = new IntegrationHelper();
		$pickers = $helper->get_pickers_n_selectors();

		$manual = $this->get_manual_data($pickers);

    $opts = array_values($manual);

    return $opts;
  }


  protected function set_data( $data ) {

		$store = maybe_serialize($data);

    update_option('_dtpicker_new_integration', $store);
    update_option('_dtpicker_lite_imported_integration', true);
  }

  /**
   * Check for previous imports
   *
   * @return Void
   */
  protected function check_already_imported() {

		$pro = get_option('_dtpicker_pro_imported_integration');
    $post_id = get_option('_dtpicker_lite_imported_integration');

		if (false != $pro) {
			return true;
		}

    if (0 != intval($post_id)) {
      return true;
    } else {
      return false;
    }
  }


	protected function get_manual_data($pickers) {

		$output = array();
		foreach ($pickers as $picker_id => $class) {
			$output[] = array(
					'label'   => __('Manual', 'date-time-picker-field') . ' Lite',
					'plugin'  => 'manual',
					'picker'  => $picker_id,
					'selector'=> $class['selector'],
					'class'   => ''
				);
		}

		return $output;
	}
}
