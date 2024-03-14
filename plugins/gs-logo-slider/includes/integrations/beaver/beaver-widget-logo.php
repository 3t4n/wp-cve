<?php 
namespace GSLOGO;
use FLBuilderModule;

class Beaver extends FLBuilderModule {

    public function __construct() {
        
        parent::__construct(array(
            'name'            => __( 'GS Logo Slider', 'gslogo' ),
            'description'     => __( 'Display brand and logos by GS Logo Slider', 'gslogo' ),
            'group'           => __( 'GS Plugins', 'gslogo' ),
            'category'        => __( 'Basic', 'gslogo' ),
            'dir'             => GSL_PLUGIN_DIR . 'includes/integrations/beaver/',
            'url'             => GSL_PLUGIN_URI . 'includes/integrations/beaver/',
            'icon'            => 'icon.svg',
            'editor_export'   => true, // Defaults to true and can be omitted.
            'enabled'         => true, // Defaults to true and can be omitted.
            'partial_refresh' => false, // Defaults to false and can be omitted.
        ));
        
    }

    public function get_icon( $icon = '' ) {

        $path = GSL_PLUGIN_DIR . 'assets/img/' . $icon;

        // check if $icon is referencing an included icon.
        if ( '' != $icon && file_exists( $path ) ) {
            $icon = file_get_contents( $path );
            $icon = gs_str_replace_first( 'width="50"', 'width="20"', $icon );
            return gs_str_replace_first( 'height="50"', 'height="20"', $icon );
        }

        return '';
    }

}