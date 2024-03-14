<?php

class Owm_Backup_Action_Remove extends Owm_Migration_Action {
	public static $action_key = 'backup_remove';

	public function do_action() {
		$info = new Owm_Backup_Info();
		if ( ! $info->is_info_exists() ) {
			return array(
				'status' => OWM_MIGRATION_STATUS_NO_DATA
			);
		}
		$backup_db_file_name = $info->get_backup_db_file_name();
		$backup_dir_path = $info->get_backup_dir_path();

		$mysql_backup = new Owm_Mysql_Query_Backup(
			$backup_dir_path,
			$backup_db_file_name,
			$info
		);
		$mysql_backup->file_delete();

		// delete hidden file
		$compressed_dir = new Owm_Archived_Dir( $info->get_backup_dir_path() );
		$compressed_dir->delete();

		// delete current dir
		if ( ! is_null( $backup_dir_path ) ) {
			$remove_status = Owm_File_Utils::delete_dir( $backup_dir_path );
			if ( ! $remove_status ) {
				$info->set_force_stop( true );
				$info->update();
			}
		}
		// delete previous dir
		$upload_dir = wp_upload_dir();
		foreach ( Owm_File_Utils::list_dir( $upload_dir[ 'basedir' ] . 'bk_*' ) as $previous_dir ) {
			Owm_File_Utils::delete_dir( $previous_dir );
		}

		$info->delete();

		return array(
			'status' => OWM_MIGRATION_STATUS_NO_DATA
		);
	}
}
