<?php
/* ---------------------------------------------------------------------------------------------
   SVG ICONS CLASS
   Retrieve the SVG code for the specified icon. Based on a solution in TwentyNineteen.
   --------------------------------------------------------------------------------------------- */
if (!class_exists('Demo_Import_Kit_SVG_Icons')) :
    class Demo_Import_Kit_SVG_Icons
    {
        /* --------------------------------------------------------------------
           GET SVG CODE
           Get the SVG code for the specified icon
           -------------------------------------------------------------------- */
        public static function get_svg($icon)
        {
            $arr = apply_filters('demo_import_kit_svg_icons', self::$icons);
            if (array_key_exists($icon, $arr)) {
                $repl = '<svg class="svg-icon" aria-hidden="true" role="img" focusable="false" ';
                $svg = preg_replace('/^<svg /', $repl, trim($arr[$icon])); // Add extra attributes to SVG code.
                $svg = str_replace('#', '%23', $svg); // Urlencode hashes.
                $svg = preg_replace("/([\n\t]+)/", ' ', $svg); // Remove newlines & tabs.
                $svg = preg_replace('/>\s*</', '><', $svg); // Remove white space between SVG tags.
                return $svg;
            }
            return null;
        }

        static function get_theme_svg_name($url)
        {


            static $regex_map; // Only compute regex map once, for performance.
            if (!isset($regex_map)) {
                $regex_map = array();
                $map = Demo_Import_Kit_SVG_Icons::$social_icons_map; // Use reference instead of copy, to save memory.
                foreach (array_keys(Demo_Import_Kit_SVG_Icons::$icons) as $icon) {

                    $domains = array_key_exists($icon, $map) ? $map[$icon] : array(sprintf('%s.com', $icon));

                    $domains = array_map('trim', $domains); // Remove leading/trailing spaces, to prevent regex from failing to match.
                    $domains = array_map('preg_quote', $domains);
                    $regex_map[$icon] = sprintf('/(%s)/i', implode('|', $domains));

                }
            }
            foreach ($regex_map as $icon => $regex) {
                if (preg_match($regex, $url)) {
                    return demo_import_kit_get_svg($icon);
                }
            }

            return demo_import_kit_get_svg('chain');

        }

        static $social_icons_map = array(
            'amazon' => array(
                'amazon.com',
                'amazon.cn',
                'amazon.in',
                'amazon.fr',
                'amazon.de',
                'amazon.it',
                'amazon.nl',
                'amazon.es',
                'amazon.co',
                'amazon.ca',
            ),
            'apple' => array(
                'apple.com',
                'itunes.com',
            ),
            'behance' => array(
                'behance.net',
            ),
            'codepen' => array(
                'codepen.io',
            ),
            'facebook' => array(
                'facebook.com',
                'fb.me',
            ),
            'feed' => array(
                'feed',
            ),
            'lastfm' => array(
                'last.fm',
            ),
            'mail' => array(
                'mailto:',
            ),
            'slideshare' => array(
                'slideshare.net',
            ),
            'pocket' => array(
                'getpocket.com',
            ),
            'twitch' => array(
                'twitch.tv',
            ),
            'wp' => array(
                'wordpress.com',
                'wordpress.org',
            ),
        );

        /* --------------------------------------------------------------------
           ICON STORAGE
           Store the code for all SVGs in an array
           -------------------------------------------------------------------- */
        static $icons = array(
            'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="currentColor" d="M17.7043 16.2848L14.3054 12.8958C15.402 11.4988 15.9971 9.77351 15.9948 7.99743C15.9948 6.41569 15.5258 4.86947 14.647 3.5543C13.7683 2.23913 12.5192 1.21408 11.0579 0.608771C9.59657 0.00346513 7.98855 -0.15491 6.43721 0.153672C4.88586 0.462254 3.46085 1.22393 2.34239 2.34239C1.22393 3.46085 0.462254 4.88586 0.153672 6.43721C-0.15491 7.98855 0.00346513 9.59657 0.608771 11.0579C1.21408 12.5192 2.23913 13.7683 3.5543 14.647C4.86947 15.5258 6.41569 15.9948 7.99743 15.9948C9.77351 15.9971 11.4988 15.402 12.8958 14.3054L16.2848 17.7043C16.3777 17.798 16.4883 17.8724 16.6101 17.9231C16.7319 17.9739 16.8626 18 16.9945 18C17.1265 18 17.2572 17.9739 17.379 17.9231C17.5008 17.8724 17.6114 17.798 17.7043 17.7043C17.798 17.6114 17.8724 17.5008 17.9231 17.379C17.9739 17.2572 18 17.1265 18 16.9945C18 16.8626 17.9739 16.7319 17.9231 16.6101C17.8724 16.4883 17.798 16.3777 17.7043 16.2848ZM1.99936 7.99743C1.99936 6.81112 2.35114 5.65146 3.01022 4.66508C3.66929 3.6787 4.60606 2.90991 5.70207 2.45593C6.79807 2.00196 8.00408 1.88317 9.16759 2.11461C10.3311 2.34605 11.3999 2.91731 12.2387 3.75615C13.0775 4.595 13.6488 5.66375 13.8802 6.82726C14.1117 7.99077 13.9929 9.19678 13.5389 10.2928C13.0849 11.3888 12.3162 12.3256 11.3298 12.9846C10.3434 13.6437 9.18373 13.9955 7.99743 13.9955C6.40664 13.9955 4.88101 13.3636 3.75615 12.2387C2.6313 11.1138 1.99936 9.58821 1.99936 7.99743Z"></path></svg>',
            'cross' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="currentColor" d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z"></path></svg>',
            'exit' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M15.8333 15.8333H4.16667V4.16667H10V2.5H4.16667C3.24167 2.5 2.5 3.25 2.5 4.16667V15.8333C2.5 16.75 3.24167 17.5 4.16667 17.5H15.8333C16.75 17.5 17.5 16.75 17.5 15.8333V10H15.8333V15.8333ZM11.6667 2.5V4.16667H14.6583L6.46667 12.3583L7.64167 13.5333L15.8333 5.34167V8.33333H17.5V2.5H11.6667Z"></path></svg>',
            'arrow-left' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M18.271,9.212H3.615l4.184-4.184c0.306-0.306,0.306-0.801,0-1.107c-0.306-0.306-0.801-0.306-1.107,0 L1.21,9.403C1.194,9.417,1.174,9.421,1.158,9.437c-0.181,0.181-0.242,0.425-0.209,0.66c0.005,0.038,0.012,0.071,0.022,0.109 c0.028,0.098,0.075,0.188,0.142,0.271c0.021,0.026,0.021,0.061,0.045,0.085c0.015,0.016,0.034,0.02,0.05,0.033l5.484,5.483 c0.306,0.307,0.801,0.307,1.107,0c0.306-0.305,0.306-0.801,0-1.105l-4.184-4.185h14.656c0.436,0,0.788-0.353,0.788-0.788 S18.707,9.212,18.271,9.212z"></path></svg>',
            'arrow-right' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M1.729,9.212h14.656l-4.184-4.184c-0.307-0.306-0.307-0.801,0-1.107c0.305-0.306,0.801-0.306,1.106,0 l5.481,5.482c0.018,0.014,0.037,0.019,0.053,0.034c0.181,0.181,0.242,0.425,0.209,0.66c-0.004,0.038-0.012,0.071-0.021,0.109 c-0.028,0.098-0.075,0.188-0.143,0.271c-0.021,0.026-0.021,0.061-0.045,0.085c-0.015,0.016-0.034,0.02-0.051,0.033l-5.483,5.483 c-0.306,0.307-0.802,0.307-1.106,0c-0.307-0.305-0.307-0.801,0-1.105l4.184-4.185H1.729c-0.436,0-0.788-0.353-0.788-0.788 S1.293,9.212,1.729,9.212z"></path></svg>',
            'chevron-down' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M11.611,10.049l-4.76-4.873c-0.303-0.31-0.297-0.804,0.012-1.105c0.309-0.304,0.803-0.293,1.105,0.012l5.306,5.433c0.304,0.31,0.296,0.805-0.012,1.105L7.83,15.928c-0.152,0.148-0.35,0.223-0.547,0.223c-0.203,0-0.406-0.08-0.559-0.236c-0.303-0.309-0.295-0.803,0.012-1.104L11.611,10.049z"></path></svg>',
        );
    }
endif;


if (!function_exists('demo_import_kit_get_svg')):

    /**
     * Get information about the SVG icon.
     *
     * @param string $svg_name The name of the icon.
     * @param string $group The group the icon belongs to.
     * @param string $color Color code.
     */
    function demo_import_kit_get_svg($svg_name)
    {

        // Make sure that only our allowed tags and attributes are included.
        $svg = wp_kses(
            Demo_Import_Kit_SVG_Icons::get_svg($svg_name),
            array(
                'svg' => array(
                    'class' => true,
                    'xmlns' => true,
                    'width' => true,
                    'height' => true,
                    'viewbox' => true,
                    'aria-hidden' => true,
                    'role' => true,
                    'focusable' => true,
                ),
                'path' => array(
                    'fill' => true,
                    'fill-rule' => true,
                    'd' => true,
                    'transform' => true,
                ),
                'polygon' => array(
                    'fill' => true,
                    'fill-rule' => true,
                    'points' => true,
                    'transform' => true,
                    'focusable' => true,
                ),
            )
        );
        if (!$svg) {
            return false;
        }
        return $svg;

    }

endif;

if (!function_exists('demo_import_kit_svg')):
    /**
     * Output and Get Theme SVG.
     * Output and get the SVG markup for an icon in the JoltNews_SVG_Icons class.
     *
     * @param string $svg_name The name of the icon.
     * @param string $group The group the icon belongs to.
     * @param string $color Color code.
     */
    function demo_import_kit_svg($svg_name, $return = false)
    {

        if ($return) {

            return demo_import_kit_get_svg($svg_name); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in demo_import_kit_get_svg();.

        } else {

            echo demo_import_kit_get_svg($svg_name); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in demo_import_kit_get_svg();.

        }
    }

endif;