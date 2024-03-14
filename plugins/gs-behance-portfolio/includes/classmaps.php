<?php

namespace GSBEH;

// if direct access than exit the file.
defined('ABSPATH') || exit;

return array(
    
    'Helpers'                        => 'includes/helpers.php',
    'Database'                       => 'includes/database.php',
    'Ajax'                           => 'includes/ajax.php',
    'Scrapper'                       => 'includes/scrapper.php',
    'Shortcode'                      => 'includes/shortcode.php',
    'DataLayer'                      => 'includes/datalayer.php',
    'Scripts'                        => 'includes/scripts.php',
    'TemplateLoader'                 => 'includes/template-loader.php',
    'Builder'                        => 'includes/shortcode-builder/builder.php',
    'Integrations'                   => 'includes/integrations/integrations.php',
);
