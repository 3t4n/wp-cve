<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pektsekye_ProductCategoryDropdowns_Widget_Selector extends WP_Widget {


	function __construct() {

		$widgetOptions = array(
			'description' => __( 'Product categories as dependent drop-down selects.', 'product-category-dropdowns')
		);

		parent::__construct(false, _x('Category Dropdowns', 'Wigdet title in admin panel', 'product-category-dropdowns'), $widgetOptions);
	}


	function form($instance) {	

    if ($instance && !empty($instance['title'])) {
         $title = esc_attr($instance['title']);
    } else {
         $title = __('Select category', 'product-category-dropdowns');
    }
             
    ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'product-category-dropdowns'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
      </p>                    
    <?php
	}


	function update($new_instance, $instance) {
    $instance['title'] = strip_tags($new_instance['title']);               
    return $instance;
	}


	function widget($args, $instance) {
    extract($args);

    $title = isset($instance['title']) ? $instance['title'] : '';

    echo $before_widget;

    echo '<div class="widget-text wp_widget_plugin_box">';

    if ($title) {
      echo $before_title . $title . $after_title;
    }

    include_once(Pektsekye_PCD()->getPluginPath() . 'Block/Selector.php'); 
         
    $block = new Pektsekye_ProductCategoryDropdowns_Block_Selector();
    if (isset($args['widget_id'])){
      $block->setWidgetId($args['widget_id']);
    }     
        
    $block->toHtml();

    echo '</div>';
    echo $after_widget;
	}
}