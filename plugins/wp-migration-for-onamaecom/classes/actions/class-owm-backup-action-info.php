<?php

class Owm_Backup_Action_Info extends Owm_Migration_Action {
	public static $action_key = 'backup_info';

	public function do_action() {
		$info = new Owm_Backup_Info();

		return array(
			'status' => $info->get_status(),
			'backup_key' => $info->get_backup_key(),
			'site_url' => $info->get_site_url(),
			'backup_url' => $info->get_backup_url(),
			'start_datetime' => $info->get_start_datetime(),
			'finish_datetime' => $info->get_finish_datetime()
		);
	}

}
