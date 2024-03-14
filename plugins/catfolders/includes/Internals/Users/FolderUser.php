<?php
namespace CatFolders\Internals\Users;

use CatFolders\Models\OptionModel;

defined( 'ABSPATH' ) || exit;

class FolderUser {
	public function __construct() {
		add_filter( 'catf_folder_created_by', array( $this, 'get_folder_created_by' ) );
	}

	public function get_folder_created_by() {
		$userRestriction = OptionModel::get_option( 'userrestriction' );

		if ( '1' === $userRestriction ) {
			return get_current_user_id();
		}

		return 0;
	}
}
