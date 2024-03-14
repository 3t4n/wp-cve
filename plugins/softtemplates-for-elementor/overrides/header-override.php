<?php
/**
 * Header file in case of the elementor way
 *
 * @package header-footer-elementor
 * @since 1.2.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php do_action( 'wp_body_open' ); ?>
    <div id="soft-template-page" class="softtemplates site">
    <?php
        $header_class = array();
        $header_attribute = array();

        $header_class[] = 'stfe-header';

        // Template Meta Options
        $structure = soft_template_core()->locations->get_structure_for_location('header');
        $template_id = soft_template_core()->conditions->find_matched_conditions( $structure->get_id() );
        $template_meta = get_post_meta( $template_id, '_elementor_page_settings', true );

        // Template Settings
        $stfe_fixed_header = isset($template_meta["fixed_header"]) ? $template_meta["fixed_header"] : '';
        $stfe_fixed_tablet_header = isset($template_meta["tablet_fixed_header"]) ? $template_meta["tablet_fixed_header"] : '';
        $stfe_fixed_mobile_header = isset($template_meta["mobile_fixed_header"]) ? $template_meta["mobile_fixed_header"] : '';
        $stfe_sticky_direction = isset($template_meta["sticky_trigger"]) ? $template_meta["sticky_trigger"] : '';
        $stfe_positions = isset($template_meta["top_positions"]) ? $template_meta["top_positions"] : '';
        $transparent_header = isset($template_meta["transparent_header"]) ? $template_meta["transparent_header"] : '';

        // sticky conditions
        if( $stfe_fixed_header == 'yes' ) {
            $header_class[] = 'stfe-sticky-header';
        }   
        
        if( $stfe_fixed_tablet_header == 'yes' ) {
            $header_class[] = 'hide-tablet';
        }   
        
        if( $stfe_fixed_mobile_header == 'yes' ) {
            $header_class[] = 'hide-mobile';
        }   
        
        // Sticky direction
        if( $stfe_sticky_direction == 'up' ) {
            $header_attribute[] = 'data-direction="up"';
        }   
        
        if( $stfe_sticky_direction == 'down' ) {
            $header_attribute[] = 'data-direction="down"';
        }   
        
        if( $stfe_sticky_direction == 'both' ) {
            $header_attribute[] = 'data-direction="both"';
        }

        if( !empty( $stfe_positions  ) ) {
            $header_attribute[] = 'data-top='.$stfe_positions['size'].'';
        }

        if( $transparent_header == 'yes' ) {
            $header_class[] = 'sfte-fixed-header';
        }
    ?>
    <header class="<?php echo esc_attr( join(" ",$header_class) ); ?>" <?php echo join(" ",$header_attribute); ?>>
        <?php soft_template_core()->locations->do_location( 'header'); ?>
    </header>