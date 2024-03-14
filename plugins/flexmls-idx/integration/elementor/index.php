<?php

if (!defined('ABSPATH')) exit; 

$module_files = glob( __DIR__ . '/modules/*/*.php' );

require_once 'FlexMLSwidget.php';
require_once 'modules/fmcListingDetails.php';
require_once 'modules/fmcSearchResults.php';
require_once 'modules/fmcLocationLinks.php';
require_once 'modules/fmcMarketStats.php';
require_once 'modules/fmcSearch.php';
require_once 'modules/fmcPhotos.php';

require_once 'controls/location-control.php';
require_once 'controls/sortable-list-control.php';
require_once 'controls/checkboxes-control.php';

add_action( 'elementor/editor/before_enqueue_styles', function() {
   wp_enqueue_style( 'style-elementor-flexmls', plugins_url('flexmls-elementor.css', __FILE__));
} );

add_action('elementor/widgets/register', function($widgets_manager){  

  $version = ( defined( 'FMC_DEV' ) && FMC_DEV ) ? false : FMC_PLUGIN_VERSION;
			
  if(is_admin()){
    wp_enqueue_script( 'flexmls_admin_script_', plugins_url( '../assets/js/admin.js', dirname( __FILE__ ) ), 
			array( 'jquery', 'wp-color-picker' ), $version );

		$color_picker_strings = array(
			'clear'            => __( 'Clear', 'fmcdomain' ),
			'clearAriaLabel'   => __( 'Clear color', 'fmcdomain' ),
			'defaultString'    => __( 'Default', 'fmcdomain' ),
			'defaultAriaLabel' => __( 'Select default color', 'fmcdomain' ),
			'pick'             => __( 'Select Color', 'fmcdomain' ),
			'defaultLabel'     => __( 'Color value', 'fmcdomain' ),
		);
		wp_localize_script( 'flexmls_admin_script_', 'wpColorPickerL10n', $color_picker_strings );	

    wp_enqueue_script('flexmls_admin_script_');
    
    wp_enqueue_script( 'fmcElementor', plugins_url( 'scripts/elementor-init.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), $version );
    wp_enqueue_style( 'fmc_connect1', plugins_url( '../assets/css/style_admin.css', dirname( __FILE__ ) ), array(), $version );
  }
  
  global $fmc_widgets_integration;
  global $wp_widget_factory;  

  $fms_widgets_base = [];
  $fms_allowed_widgets = [
    '\FlexMLS\Widgets\LeadGeneration' => 'fmcleadgen',
  ];
  
  $fms_add_widgets = [
    'fmcListingDetails' => 'fmcListingDetails',
    'fmcSearchResults' => 'fmcSearchResults',
    'fmcLocationLinks' => 'fmcLocationLinks',
    'fmcMarketStats' => 'fmcMarketStats',
    'fmcSearch' => 'fmcSearch',
    'fmcPhotos' => 'fmcPhotos'
  ];

  foreach ($fmc_widgets_integration as $name => $settings) {
    if($name != 'fmcNeighborhoods'){
      $fms_widgets_base[] = $name;
    }
  }

  $widget_types = $widgets_manager->get_widget_types();

  foreach ( $wp_widget_factory->widgets as $widget_class => $widget_obj ) {
    if ( in_array( $widget_class, $fms_widgets_base ) ) {
      $base = $widget_obj->id_base;
      $fms_allowed_widgets[$widget_class] = $base;        
    }
  }

  foreach ($fms_allowed_widgets as $widget_class => $id_base) {    
    //$widgets_manager->unregister_widget_type("wp-widget-{$id_base}");  
    fmc_widget_rename($id_base, $widget_types);
    
    if(!in_array( $widget_class, $fms_add_widgets )){
      $widgets_manager->register(
        new EL_FMC_widget( [], [
          'widget_name' => $widget_class,
          'widget_title' => $id_base == 'fmcleadgen' ? $fmc_widgets_integration['fmcLeadGen']['title'] : $fmc_widgets_integration[$widget_class]['title']
        ], ['flexmls'])
      );
    } else {
      
    }
  }

  foreach ($fms_add_widgets as $id_base) {
    $className = 'EL_'.$id_base;
    $widgets_manager->register(
      new $className( [], null, ['flexmls'])
    );
  }

});

function fmc_widget_rename($id_base, $widget_types){
    $w = $widget_types["wp-widget-{$id_base}"];
    $w_name = $w->get_widget_instance()->name;
    $w->get_widget_instance()->name = $w_name.' <br><span style="color:red">deprecated</span>'; 
}

add_action('elementor/controls/controls_registered', function($controls_manager){  
  $controls_manager->register_control( 'location_control', new Location_Control() );
  $controls_manager->register_control( 'sortable_list_control', new Sortable_List() );
  $controls_manager->register_control( 'checkboxes_control', new Checkboxes() );
});