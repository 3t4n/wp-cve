<?php

defined( 'ABSPATH' ) || exit;

$args = array();

ob_start();
require_once CPT_PATH . '/includes/templates/page-tools.php';
$template = ob_get_clean();

$args[] = array(
	'id'         => 'tools',
	'parent'     => 'edit.php?post_type=' . CPT_UI_PREFIX,
	'order'      => null,
	'menu_icon'  => null,
	'title'      => __( 'Tools & Infos', 'custom-post-types' ),
	'content'    => $template,
	'admin_only' => true,
);

return $args;
