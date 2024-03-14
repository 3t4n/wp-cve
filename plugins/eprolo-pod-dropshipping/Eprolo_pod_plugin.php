<?php

require_once 'Eprolo_pod_life_cycle.php';

class Eprolo_Pod_Plugin extends Eprolo_Pod_Life_Cycle {


	//Setting menu name
	public function getPluginDisplayName() {
		return ' Inkedjoy POD';
	}

	protected function getMainPluginFileName() {
		return 'eprolo_pod.php';
	}

	public function upgrade() {
	}


	//Add menu
	public function addActionsAndFilters() {
		add_action( 'admin_menu', array( &$this, 'addSettingsSubMenuPage' ) );
	}

	//Get stored data
	protected function initOptions() {
				$options = $this->getOptionMetaData();
		if ( ! empty( $options ) ) {
			foreach ( $options as $key => $arr ) {
				if ( is_array( $arr ) && count( $arr > 1 ) ) {
					 $this->addOption( $key, $arr[1] );
				}
			}
		}
	}

}
