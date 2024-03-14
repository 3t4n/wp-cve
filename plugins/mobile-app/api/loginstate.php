<?php

/*
* canvas-api/loginstate endpoint
* include ml_username and its value
*/
require_once CANVAS_DIR . 'core/canvas_theme.class.php';

$username = CanvasTheme::get_username();
$result   = array( 'ml_username' => $username );

echo json_encode( $result );
