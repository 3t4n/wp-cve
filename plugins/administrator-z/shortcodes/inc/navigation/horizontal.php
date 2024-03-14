<?php
$argsmain = array(
        'menu'              => $nav,
        'menu_class'    => "header-nav header-nav-main nav ".$ul_class,
        'container'      => false,
        'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'walker'         => new $walker(),
        'add_li_class'  => '',
    );
    wp_nav_menu($argsmain);