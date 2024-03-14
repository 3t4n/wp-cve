<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intl
 * @subpackage Intl/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Intl
 * @subpackage Intl/includes
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
class IntelSubmission extends IntelEntity {

  public static function build_content(&$entity, $view_mode = 'full', $langcode = NULL) {
    if (!isset($langcode)) {
      //$langcode = $GLOBALS['language_content']->language;
    }

    // Remove previously built content, if exists.
    $entity->content = array();

    self::build_profile_content_elements($entity);

    // Allow modules to change the view mode.
    $context = array(
      'entity_type' => 'intel_visitor',
      'entity' => $entity,
      'langcode' => $langcode,
    );
    // TODO implement hooks
    //drupal_alter('entity_view_mode', $view_mode, $context);

    // Build fields content.
    //field_attach_prepare_view('intel_visitor', array($entity->vid => $entity), $view_mode, $langcode);
    //entity_prepare_view('intel_visitor', array($entity->vid => $entity), $langcode);
    //$entity->content['fields'] = field_attach_view('intel_visitor', $entity, $view_mode, $langcode);


    // Populate $entity->content with a render() array.
    //module_invoke_all('intel_visitor_view', $entity, $view_mode, $langcode);
    //module_invoke_all('entity_view', $entity, 'intel_visitor', $view_mode, $langcode);

    // Make sure the current view mode is stored if no module has already
    // populated the related key.
    $entity->content += array('#view_mode' => $view_mode);
  }

  public static function build_profile_content_elements($entity) {
    $weight = 0;
    $entity->content['location'] = array(
      '#markup' => Intel_Df::theme('intel_visitor_location', array('entity' => $entity)),
      '#region' => 'sidebar',
      '#weight' => $weight++,
    );
    $entity->content['browser_environment'] = array(
      '#markup' => Intel_Df::theme('intel_visitor_browser_environment', array('entity' => $entity)),
      '#region' => 'sidebar',
      '#weight' => $weight++,
    );
  }
}
