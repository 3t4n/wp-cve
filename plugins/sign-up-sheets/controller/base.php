<?php
/**
 * Base class for all controllers
 */

namespace FDSUS\Controller;

use FDSUS\Id;

class Base
{
    /** @var string  */
    protected $themeFilesDir = 'theme-files'; // Sub directory in plugin to put all your template files

    /**
     * PostTypeBase constructor
     */
    public function __construct()
    {
    }

    /**
     * Retrieve the name of the highest priority template file that exists.
     *
     * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
     * inherit from a parent theme can just overload one file.
     *
     * Modified from core WP code to check plugin directory last
     *
     * @param string|array $templateNames Template file(s) to search for, in order.
     * @param bool         $load          If true the template file will be loaded if it is found.
     * @param bool         $requireOnce   Whether to require_once or require. Default true. Has no effect if $load is false.
     * @param array        $args          Arguments to pass to the template (added in WP 5.5.0)
     *
     * @return string The template filename if one is located.
     */
    public function locateTemplate($templateNames, $load = false, $requireOnce = true, $args = array())
    {
        $located = false;

        foreach ((array)$templateNames as $templateName) {
            if (empty($templateName)) {
                continue;
            }

            // Replace `/` with `DIRECTORY_SEPARATOR` for better server support
            $templateName = str_replace('/', DIRECTORY_SEPARATOR, $templateName);

            $stylesheetPathFile = STYLESHEETPATH . DIRECTORY_SEPARATOR . $templateName; // child
            $templatePathFile = TEMPLATEPATH . DIRECTORY_SEPARATOR . $templateName; // parent
            $pluginFile = Id::getPluginPath() . $this->themeFilesDir . DIRECTORY_SEPARATOR . $templateName; // plugin

            if (file_exists($stylesheetPathFile)) {
                $located = $stylesheetPathFile;
                break;
            } elseif (file_exists($templatePathFile)) {
                $located = $templatePathFile;
                break;
            } elseif (file_exists($pluginFile)) {
                $located = $pluginFile;
                break;
            }
        }

        if ((true == $load) && !empty($located)) {
            load_template($located, $requireOnce, $args);
        }

        return $located;
    }

    /**
     * Send a JSON response back to an Ajax request.
     * (variation from WP core - added to support earlier version)
     *
     * @param mixed $response Variable (usually an array or object) to encode as JSON, then print and die.
     */
    protected function wpSendJson($response)
    {
        @header('Content-Type: application/json;');
        echo json_encode($response);
        die;
    }

    /**
     * Is this an internal redirect (no domain supplied)
     *
     * @param string $redirect
     *
     * @return bool
     */
    protected function isInternalRedirect($redirect)
    {
        if (empty($redirect)) {
            return false;
        }

        $parsedRedirect = parse_url($redirect);

        if (empty($parsedRedirect)
            || !is_array($parsedRedirect)
            || isset($parsedRedirect['scheme'])
            || isset($parsedRedirect['host'])
        ) {
            return false;
        }

        return true;
    }
}
