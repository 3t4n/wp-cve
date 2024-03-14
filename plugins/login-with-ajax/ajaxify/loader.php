<?php
if( !empty(LoginWithAjax::$data['ajaxify']) ) {
	include('ajaxify.php');
}

if( is_admin() ) {
	include('ajaxify-admin.php');
}