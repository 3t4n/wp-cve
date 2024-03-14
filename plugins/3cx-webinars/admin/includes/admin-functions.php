<?php

function wp3cxw_current_action() {
	if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] ) {
		return sanitize_key($_REQUEST['action']);
	}

	if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] ) {
		return sanitize_key($_REQUEST['action2']);
	}

	return false;
}

function wp3cxw_admin_has_edit_cap() {
	return current_user_can( 'wp3cxw_edit_webinar_forms' );
}
