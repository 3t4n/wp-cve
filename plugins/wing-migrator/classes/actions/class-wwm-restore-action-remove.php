<?php

class Wwm_Restore_Action_Remove extends Wwm_Migration_Action {
	public static $action_key = 'restore_remove';

	public function do_action() {
		$info = new Wwm_Restore_Info();
		$restore_dir_path = $info->get_restore_dir_path();

		if ( ! is_null( $restore_dir_path ) ) {
			$remove_status = Wwm_File_Utils::delete_dir( $restore_dir_path );
			if ( ! $remove_status ) {
				$info->set_force_stop( true );
				$info->update();
			}
		}
		// delete previous dir
		$upload_dir = wp_upload_dir();
		foreach ( Wwm_File_Utils::list_dir( $upload_dir[ 'basedir' ] . 'rs_*' ) as $previous_dir ) {
			Wwm_File_Utils::delete_dir( $previous_dir );
		}

		foreach ( Wwm_File_Utils::list_dir( $upload_dir[ 'basedir' ] . 'bk_*' ) as $previous_dir ) {
			Wwm_File_Utils::delete_dir( $previous_dir );
		}

		$info->delete();

		return array(
			'status' => WWM_MIGRATION_STATUS_NO_DATA
		);
	}
}
