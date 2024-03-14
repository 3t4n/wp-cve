<?php


/**
 * Searches the supplied string for any of the translate words and replaces them.
 *
 * @param string $text The string to replace the text in
 *
 * @return string The translated / adjusted text
 */
function autoship_search_for_translate_text( $text ){

  $search_terms = $replace_terms = array();

  $customizations = autoship_get_settings_fields ( array(
    'autoship_and_save_translation',
    'autoship_translation',
    'autoship_scheduled_order_translation'
   ));

  if ( !empty( $customizations['autoship_and_save_translation'] ) ){
    $search_terms[] = 'Autoship and Save';
    $search_terms[] = 'autoship and save';
    $replace_terms[] = $customizations['autoship_and_save_translation'];
    $replace_terms[] = strtolower( $customizations['autoship_and_save_translation'] );
  }

  if ( !empty( $customizations['autoship_translation'] ) ){
    $search_terms[] = 'Autoship';
    $search_terms[] = 'autoship';
    $replace_terms[] = $customizations['autoship_translation'];
    $replace_terms[] = strtolower( $customizations['autoship_translation'] );
  }

  if ( !empty( $customizations['autoship_scheduled_order_translation'] ) ){
    $search_terms[] = 'Scheduled Order';
    $search_terms[] = 'scheduled order';
    $replace_terms[] = $customizations['autoship_scheduled_order_translation'];
    $replace_terms[] = strtolower( $customizations['autoship_scheduled_order_translation'] );
  }

  if ( !empty( $customizations['autoship_scheduled_orders_translation'] ) ){
    $search_terms[] = 'Scheduled Orders';
    $search_terms[] = 'scheduled orders';
    $replace_terms[] = $customizations['autoship_scheduled_orders_translation'];
    $replace_terms[] = strtolower( $customizations['autoship_scheduled_orders_translation'] );
  }

  return empty( $search_terms ) ? $text : str_replace( $search_terms, $replace_terms, $text );

}

/**
 * Filters the Autoship Cloud and Scheduled Order text.
 *
 * @param string $text        The text to translate.
 * @param bool $translate     If the final text should be run through translator
 * @return string The translated / adjusted text
 */
function autoship_translate_text( $text, $translate = false ){

  $standardized = strtolower( $text );
  $new_text = $text;

  if ( 'autoship' == $standardized ){

    $customization = autoship_get_settings_fields ( 'autoship_translation', true );

    if ( ctype_lower( $text ) )
    $customization = strtolower( $customization );

    $new_text = !empty( $customization ) ? $customization : $text;

  }

  if ( 'autoship and save' == $standardized ){

    $customization = autoship_get_settings_fields ( 'autoship_and_save_translation', true );

    if ( ctype_lower( $text ) )
    $customization = strtolower( $customization );

    $new_text = !empty( $customization ) ? $customization : $text;

  }

  if ( 'scheduled order' == $standardized ){

    $customization = autoship_get_settings_fields ( 'autoship_scheduled_order_translation', true );

    if ( ctype_lower( $text ) )
    $customization = strtolower( $customization );

    $new_text = !empty( $customization ) ? $customization : $text;

  }

  if ( 'scheduled orders' == $standardized ){

    $customization = autoship_get_settings_fields ( 'autoship_scheduled_orders_translation', true );

    if ( ctype_lower( $text ) )
    $customization = strtolower( $customization );

    $new_text = !empty( $customization ) ? $customization : $text;

  }

  return $translate ? __( $new_text, 'autoship' ) : $new_text;

}
