<?php
/**
 * Define default value for ARFormslite cornerstone control
 *
 * @package ARFormslite
 */

global $arflitemainhelper, $arfliteformhelper, $arfliteversion, $wpdb, $arfliteform;
$default_options = array();

$forms = $arfliteform->arflitegetAll( "is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name' );


$default_options['arf_forms']              = '';
$default_options['arf_forms_include_type'] = 'internal';

$default_options['class']   = '';
$default_options['style']   = '';
$default_options['heading'] = '';
return $default_options;
