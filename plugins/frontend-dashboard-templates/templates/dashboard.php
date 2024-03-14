<?php
/**
 * Dashboard Page
 *
 * @package frontend-dashboard
 */
$template_type = get_option( 'fed_admin_settings_upl', 'default' );

$template = isset( $template_type['settings']['fed_upl_template_model'] ) ? $template_type['settings']['fed_upl_template_model'] : 'default';

$templates = new FED_Template_Loader( FED_TEMPLATES_PLUGIN );

$templates->get_template_part( 'fedt_' . $template );

