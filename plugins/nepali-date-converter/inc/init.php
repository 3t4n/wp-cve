<?php
/**
 * Load frameworks.
 */
require_once( NEPALI_DATE_CONVERTER_PATH . 'inc/frameworks/nepali-calendar/nepali_calendar.php' );

/**
 * Load functions.
 */
require_once( NEPALI_DATE_CONVERTER_PATH . 'inc/functions/functions.php' );

/**
 * Hooks Load.
 */
require_once( NEPALI_DATE_CONVERTER_PATH . 'inc/hooks/enqueue-scripts.php' );
require_once( NEPALI_DATE_CONVERTER_PATH . 'inc/hooks/wp-footer.php' );
require_once( NEPALI_DATE_CONVERTER_PATH . 'inc/hooks/wp-ajax.php' );

/**
 * Front end functions.
 */
require_once( NEPALI_DATE_CONVERTER_PATH . 'inc/class/ndc-frontend.php' );

/**
 * Load widgets.
 */
require_once( NEPALI_DATE_CONVERTER_PATH . 'inc/widgets/widget-nepali-date-converter.php' );
require_once( NEPALI_DATE_CONVERTER_PATH . 'inc/widgets/widget-today-date.php' );

/**
 * Load shortcodes
 */
require_once( NEPALI_DATE_CONVERTER_PATH . 'inc/shortcode/shortcode-nepali-date-converter.php' );
require_once( NEPALI_DATE_CONVERTER_PATH . 'inc/shortcode/shortcode-today-date.php' );

/**
 * Post Date
 */
require_once( NEPALI_DATE_CONVERTER_PATH . 'inc/class/ndc-post-date.php' );