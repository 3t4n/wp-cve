<?php
/**
 * Add metabox options for portfolio
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://averta.net/phlox/
 * @copyright  (c) 2010-2023 averta
*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;


/*======================================================================*/

function auxin_push_metabox_models_portfolio( $models ){

    // Load general metabox models
    locate_template( AUXIN_CON . 'admin/metaboxes/metabox-fields-general-slider-setting.php' , true, true );
    locate_template( AUXIN_CON . 'admin/metaboxes/metabox-fields-general-bg-setting.php'     , true, true );
    locate_template( AUXIN_CON . 'admin/metaboxes/metabox-fields-general-title-setting.php'  , true, true );
    locate_template( AUXIN_CON . 'admin/metaboxes/metabox-fields-general-advanced.php'       , true, true );
    locate_template( AUXIN_CON . 'admin/metaboxes/metabox-fields-general-layout.php'         , true, true );
    include_once( AUXELS_DIR . '/admin/includes/metaboxes/metabox-fields-general-header-template.php' );
    include_once( AUXELS_DIR . '/admin/includes/metaboxes/metabox-fields-general-header-template-settings.php' );
    include_once( AUXELS_DIR . '/admin/includes/metaboxes/metabox-fields-general-footer-template.php' );
    include_once( AUXELS_DIR . '/admin/includes/metaboxes/metabox-fields-general-footer-template-settings.php' );

    include_once( 'metabox-fields-portfolio-metadata.php'     );
    include_once( 'metabox-fields-portfolio-related.php'      );

    // Attach general common metabox models to hub
    $models[] = array(
        'model'     => auxin_metabox_fields_header_templates(),
        'priority'  => 10
    );

    $models[] = array(
        'model'     => auxin_metabox_fields_header_templates_settings(),
        'priority'  => 10
    );

    $models[] = array(
        'model'     => auxin_metabox_fields_footer_templates(),
        'priority'  => 10
    );

    $models[] = array(
        'model'     => auxin_metabox_fields_footer_templates_settings(),
        'priority'  => 10
    );

    $models[] = array(
        'model'     => auxin_metabox_fields_general_layout(),
        'priority'  => 10
    );
    $models[] = array(
        'model'     => auxin_metabox_fields_general_title() ,
        'priority'  => 10
    );
    $models[] = array(
        'model'     => auxin_metabox_fields_general_background(),
        'priority'  => 10
    );
    $models[] = array(
        'model'     => auxin_metabox_fields_general_slider(),
        'priority'  => 10
    );
    $models[] = array(
        'model'     => auxin_metabox_fields_general_advanced(),
        'priority'  => 10
    );
    $models[] = array(
        'model'     => auxpfo_metabox_fields_portfolio_metadata(),
        'priority'  => 8
    );
     $models[] = array(
        'model'     => auxpfo_metabox_fields_portfolio_related_metadata(),
        'priority'  => 9
    );

    return $models;
}

add_filter( 'auxin_admin_metabox_models_portfolio', 'auxin_push_metabox_models_portfolio' );


/**
 * Removes sidebar layout options from portfolio metabox fields
 *
 * @param  array  $models The list of metabox models
 *
 * @return array  The list of filtered metabox models
 */
function auxin_remove_sidebar_metabox_models( $models ){
    return auxin_remove_from_metabox_hub( $models, array( "field_ids" => array( "page_layout", "page_sidebar_style" ) ) );
}
add_filter( 'auxin_admin_metabox_models_portfolio', 'auxin_remove_sidebar_metabox_models', 27, 1 );

