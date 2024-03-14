<?php
function srizon_mortgage_permission_admin() {
	return current_user_can( apply_filters( 'srizon_mortgage_admin_access', 'edit_posts' ) );
}

include_once 'admin/settings.php';
include_once 'admin/instances.php';

