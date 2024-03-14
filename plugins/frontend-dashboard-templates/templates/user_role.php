<?php

if ( isset( $_REQUEST['fed_user_profile'] ) ) {
	fedt_show_user_by_role( $fed_user_attr, $_REQUEST['fed_user_profile'] );
} else {
	fedt_show_users_by_role( $fed_user_attr );
}
