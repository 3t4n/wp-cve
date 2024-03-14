<?php
class Admin_NavControllerWtbp extends ControllerWtbp {
	public function getPermissions() {
		return array(
			WTBP_USERLEVELS => array(
				WTBP_ADMIN => array()
			),
		);
	}
}
