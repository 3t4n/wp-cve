<?php

defined( 'ABSPATH' ) || exit;

$args   = array();
$args[] = array(
	'id'         => CPT_UI_PREFIX . '_template',
	'parent'     => 'edit.php?post_type=' . CPT_UI_PREFIX,
	'order'      => 3,
	'menu_icon'  => null,
	'title'      => __( 'Templates', 'custom-post-types' ),
	'content'    => cpt_utils()->get_pro_banner(),
	'admin_only' => true,
);
$args[] = array(
	'id'         => CPT_UI_PREFIX . '_admin_pages',
	'parent'     => 'edit.php?post_type=' . CPT_UI_PREFIX,
	'order'      => 4,
	'menu_icon'  => null,
	'title'      => __( 'Admin pages', 'custom-post-types' ),
	'content'    => cpt_utils()->get_pro_banner(),
	'admin_only' => true,
);
$args[] = array(
	'id'         => CPT_UI_PREFIX . '_admin_notices',
	'parent'     => 'edit.php?post_type=' . CPT_UI_PREFIX,
	'order'      => 5,
	'menu_icon'  => null,
	'title'      => __( 'Admin notices', 'custom-post-types' ),
	'content'    => cpt_utils()->get_pro_banner(),
	'admin_only' => true,
);
return $args;
