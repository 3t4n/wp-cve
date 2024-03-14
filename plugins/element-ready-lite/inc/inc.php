<?php

// Dashboard Page 
require_once(__DIR__ . '/dashboard/page.php');
require_once(__DIR__ . '/dashboard/notice.php');

// Elementor Section field Page 

// require_once(__DIR__ . '/section_control/Element_Ready_Sticky.php');
// require_once(__DIR__ . '/section_control/Element_Ready_Effects.php');
// require_once(__DIR__ . '/section_control/Element_Ready_Lite_Section_Layout.php');
// require_once(__DIR__ . '/section_control/Element_Ready_Section_Dismiss.php');
// require_once(__DIR__ . '/section_control/Element_Ready_Page_Cookie.php');
// require_once(__DIR__ . '/section_control/Element_Ready_Section_Tooltip.php');
// require_once(__DIR__ . '/section_control/Element_Ready_Section.php');


$allFiles = ['Element_Ready_Sticky', 'Element_Ready_Effects', 'Element_Ready_Lite_Section_Layout', 'Element_Ready_Section_Dismiss', 'Element_Ready_Page_Cookie', 'Element_Ready_Section_Tooltip', 'Element_Ready_Section'];

foreach ($allFiles as $getF) {
    if (file_exists(__DIR__ . "/section_control/{$getF}.php")) {
        require_once(__DIR__ . "/section_control/{$getF}.php");
    }
}
