<?php
/**
 * Handle frontend and backend notices
 *
 * Example:
 * Notice::instance(); // instantiate
 * Notice::add('error', 'My error message'); // add a notice
 *
 * == Changelog ==
 * = 1.0.6 =
 * * Fixed issue preventing excerpts from displaying
 *
 * = 1.0.5 =
 * * Added fix for preventing output while getting the excerpt
 *
 * = 1.0.4 =
 * * Added fix for block themes like Twenty Twenty-Two which output the content before wp_head.  Instead of only setting enableOutput=true at the end of the head, it now disables at the beginning of wp_head and enables at the end to prevent plugins like Yoast (which output content in th head) from displaying the notice.
 *
 * = 1.0.3 =
 * * Added fix for Yoast the_content in the head issue and Gravity Forms admin page error
 * * Added inline SVG icons
 *
 * @version 1.0.6
 */

namespace FDSUS\Lib\Dls;

use WP_Post;

class Notice
{

    /**
     * Amount of times to be able to call Notice::get()
     * Default is 1 (recommended due to the_content() being called more than once with Yoast, etc)
     *
     * @var int
     * @since 1.0.2
     */
    private static $outputLimit = 1;

    /**
     * Enable/Disable output
     * Created to prevent output until we are ready in case plugins like Yoast process the_content early
     *
     * @var bool
     * @since 1.0.2
     */
    private static $enableOutput = true;

    /**
     * Enable/Disable output
     *
     * @var bool
     * @since 1.0.5
     */
    private static $outputWasEnabledAtExcerptStart = false;

    private static $prefix = 'dlsntc';
    private static $instance = null;
    private static $displayCount = 0;
    private static $hideContent = false;
    private static $frontendFilter = 'the_content';
    private static $errorIcon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><title>Error</title><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm2.71 11.29a1 1 0 0 1 0 1.42a1 1 0 0 1-1.42 0L12 13.41l-1.29 1.3a1 1 0 0 1-1.42 0a1 1 0 0 1 0-1.42l1.3-1.29l-1.3-1.29a1 1 0 0 1 1.42-1.42l1.29 1.3l1.29-1.3a1 1 0 0 1 1.42 1.42L13.41 12z"/></svg>';
    private static $warnIcon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><title>Warning</title><path d="M22.56 16.3L14.89 3.58a3.43 3.43 0 0 0-5.78 0L1.44 16.3a3 3 0 0 0-.05 3A3.37 3.37 0 0 0 4.33 21h15.34a3.37 3.37 0 0 0 2.94-1.66a3 3 0 0 0-.05-3.04zM12 17a1 1 0 1 1 1-1a1 1 0 0 1-1 1zm1-4a1 1 0 0 1-2 0V9a1 1 0 0 1 2 0z"/></svg>';
    private static $infoIcon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><title>Info</title><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 14a1 1 0 0 1-2 0v-5a1 1 0 0 1 2 0zm-1-7a1 1 0 1 1 1-1a1 1 0 0 1-1 1z"/></svg>';
    private static $successIcon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><title>Success</title><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm4.3 7.61l-4.57 6a1 1 0 0 1-.79.39a1 1 0 0 1-.79-.38l-2.44-3.11a1 1 0 0 1 1.58-1.23l1.63 2.08l3.78-5a1 1 0 1 1 1.6 1.22z"/></svg>';
    private static $notices = array(
        'error' => array(),
        'warn' => array(), // not available by default for admin notices
        'info' => array(),
        'success' => array(),
    );

    private function __construct()
    {
        add_filter(self::$frontendFilter, array(__CLASS__, 'display'), 99999); // display after all notices have been added
        add_action('admin_notices', array(__CLASS__, 'display'), 99999);  // display after all notices have been added

        // Prevent output in the head (plugins like Yoast may output content in the head)
        add_action('wp_head', array(__CLASS__, 'disableOutput'), 0, 0);
        add_action('wp_head', array(__CLASS__, 'enableOutput'), 99999, 0);

        // Prevent output when getting the excerpt
        add_action('get_the_excerpt', array(__CLASS__, 'maybeDisableOutputInExcerpt'), 0, 2);
        add_action('get_the_excerpt', array(__CLASS__, 'maybeEnableOutputInExcerpt'), 99999, 2);

        if (is_admin()) {
            self::$enableOutput = true;
        }
    }

    /**
     * Get singleton instance
     *
     * @param array $args
     * @return Notice|null
     */
    public static function instance($args = array())
    {
        /** @var string $frontendFilter */
        /** @var string $outputLimit */
        extract($args);

        if (isset($frontendFilter)) self::$frontendFilter = $frontendFilter;
        if (isset($outputLimit)) self::$outputLimit = (int)$outputLimit;

        if (self::$instance === null) {
            self::$instance = new Notice;
        }
        return self::$instance;
    }

    /**
     * Add a notice
     *
     * @param string $type
     * @param string $message
     * @param bool   $hideContent remove the content from displaying if this message displays (frontend only)
     * @param string $class       any class(es) to add to message P tag
     */
    public static function add($type, $message, $hideContent = false, $class = '')
    {
        self::$notices[$type][] = array('msg' => $message, 'class' => $class);
        if ($hideContent) self::$hideContent = true;
    }

    /**
     * Get notices
     *
     * @param int $displayCountIncrement Set to zero to just get notices without incrementing count
     *
     * @return array
     */
    public static function get($displayCountIncrement = 1)
    {
        if (!empty(self::$outputLimit)) {
            // Limit get count
            self::$displayCount += $displayCountIncrement;
            if (self::$displayCount > self::$outputLimit) return array();
        }

        return self::$notices;
    }

    /**
     * Display notices
     *
     * @param string $content
     *
     * @return string
     */
    public static function display($content = '')
    {
        if (!self::$enableOutput) {
            return '';
        }

        $output = null;
        $notices = self::get();

        foreach ($notices as $type => $messages) {
            if (empty($messages)) continue;

            switch ($type) {
                case 'error':
                    $icon = self::$errorIcon;
                    break;
                case 'warn':
                    $icon = self::$warnIcon;
                    break;
                case 'info':
                    $icon = self::$infoIcon;
                    break;
                case 'success':
                    $icon = self::$successIcon;
                    break;
                default:
                    $icon = '';
            }

            $msgOutput = null;
            foreach ($messages as $message) {

                $msgOutput .= sprintf('<%1$s class="%2$s-message %3$s">%4$s %5$s</%1$s>',
                    is_admin() ? 'p' : 'div', // TODO: update to allow overriding the DIV for P on frontend for better semantics - must work with confirmation with form which doesn't work with P
                    self::$prefix,
                    $message['class'],
                    is_admin() ? '' : $icon,
                    $message['msg']
                );
            }
            reset($messages);

            $output .= sprintf('<div class="%1$s-notice %1$s-%2$s %3$s">%4$s</div>',
                self::$prefix,
                $type,
                is_admin() ? 'notice notice-' . $type : null, // admin notice classes
                $msgOutput
            );
        }
        reset($notices);

        if (is_admin()) {
            echo $output;
        } else {
            if (self::$hideContent) $content = null; // Hide content if any messages requested it
            return $output . $content;
        }

        return '';
    }

    /**
     * Disable output
     *
     * @since 1.0.4
     */
    public static function disableOutput()
    {
        self::$enableOutput = false;
    }

    /**
     * Enable output
     *
     * @since 1.0.3
     */
    public static function enableOutput()
    {
        self::$enableOutput = true;
    }

    /**
     * Maybe disable output when getting the excerpt
     *
     * @param string  $postExcerpt The post excerpt.
     * @param WP_Post $post        Post object.
     *
     * @return string
     * @since 1.0.4
     */
    public static function maybeDisableOutputInExcerpt($postExcerpt, $post)
    {
        self::$outputWasEnabledAtExcerptStart = self::$enableOutput;
        self::$enableOutput = false;

        return $postExcerpt;
    }

    /**
     * Maybe enable output when getting the excerpt
     *
     * @param string  $postExcerpt The post excerpt.
     * @param WP_Post $post         Post object.
     *
     * @return string
     * @since 1.0.3
     */
    public static function maybeEnableOutputInExcerpt($postExcerpt, $post)
    {
        if (self::$outputWasEnabledAtExcerptStart) {
            self::$enableOutput = true;
            self::$outputWasEnabledAtExcerptStart = false;
        }

        return $postExcerpt;
    }

    /**
     * Is content hidden
     *
     * @return bool
     */
    public static function isContentHidden()
    {
        return self::$hideContent;
    }

    public static function resetDisplayCount()
    {
        self::$displayCount = 0;
    }
}
