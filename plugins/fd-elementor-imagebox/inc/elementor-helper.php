<?php
namespace Elementor;   

// Create Widgets category into elementor.
  
function fd_imagebox_widgets_init(){
    Plugin::instance()->elements_manager->add_category(
        'fd-imagebox',
        [
            'title'  => 'Elementor Imgebox',
            'icon' => 'font'
        ],
        1
    );
}
add_action('elementor/init','Elementor\fd_imagebox_widgets_init');
?>