<?php 
$argsmain = array(
	'menu'              => $nav,
    'menu_class'	=> "menu ".$ul_class,
    'container'      => false,
    'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    'walker'         => '',
    'add_li_class'  => '',
);
if($toggle=='yes'){
	$argsmain ['add_li_class'] = 'active';
}
wp_nav_menu($argsmain);