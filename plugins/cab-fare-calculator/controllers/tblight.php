<?php

require_once TBLIGHT_PLUGIN_PATH . 'controllers/onepage.php';

if ( isset( $_GET['action'] ) && $_GET['action'] == 'getPrice' ) {
	$controller = new TblightControllerOnepage();
	$result     = $controller->getPrice();
	echo json_encode( $result );
	exit();
} elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'getVehicles' ) {
	$controller = new TblightControllerOnepage();
	$result     = $controller->getVehicles();
	echo json_encode( $result );
	exit();
} elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'bookNow' ) {
	$controller = new TblightControllerOnepage();
	$result     = $controller->bookNow();
	echo json_encode( $result );
	exit();
} elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'calculateTotal' ) {

	$controller = new TblightControllerOnepage();
	$result     = $controller->calculateTotal();
	echo json_encode( $result );
	exit();
} elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'submitOrder' ) {
	$controller = new TblightControllerOnepage();
	$result     = $controller->submitOrder();
	echo json_encode( $result );
	exit();
} elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'getAvailableCarsAjax' ) {
	$controller = new TblightControllerOnepage();
	$result     = $controller->getAvailableCarsAjax();
	echo json_encode( $result );
	exit();
} elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'changeStatusAjax' ) {
	$controller = new TblightControllerOnepage();
	$result     = $controller->changeStatusAjax();
	echo json_encode( $result );
	exit();
} elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'resetBookingFormAjax' ) {
	$controller = new TblightControllerOnepage();
	$result     = $controller->resetBookingForm();
	echo json_encode( $result );
	exit();
}
