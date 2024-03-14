<?php

namespace Pagup\BetterRobots\Traits;

use  Pagup\BetterRobots\Core\Request ;
trait SettingHelper
{
    /**
     * Sanitizes option values based on predefined rules and a list of safe values.
     *
     * @param array $options Array of options to be sanitized.
     * @param array $safe Array of values considered safe for certain options.
     * @return array Sanitized array of options.
     */
    public function sanitize_options( array $options, array $safe ) : array
    {
        foreach ( $options as $key => $value ) {
            // Sanitize text areas 'personalize', 'user_agents' and 'rt_multisite'
            
            if ( $key === 'personalize' || $key === 'user_agents' || $key === 'rt_multisite' ) {
                $options[$key] = sanitize_textarea_field( $value );
            } elseif ( $key === 'crawl_delay' ) {
                
                if ( is_numeric( $value ) ) {
                    $options[$key] = sanitize_text_field( $value );
                } else {
                    $options[$key] = "";
                }
            
            } elseif ( $key === 'sitemap_file' ) {
                $options[$key] = sanitize_text_field( $value );
            } elseif ( $key === 'backlinks_bots' ) {
                $options[$key] = maybe_serialize( Request::array( $value ) );
            } else {
                
                if ( in_array( $value, $safe ) ) {
                    $options[$key] = sanitize_text_field( $value );
                } else {
                    $options[$key] = "";
                }
            
            }
        
        }
        return $options;
    }
    
    /**
     * Constructs the full URL to the robots.txt file for the WordPress site.
     *
     * @return string The full URL to the robots.txt file, including the protocol and any non-standard port if applicable.
     */
    public function robotsTxtURL()
    {
        // Parse the URL to get components
        $parsed_url = parse_url( get_site_url() );
        // Reconstruct the root URL with protocol
        $root_url_with_protocol = $parsed_url['scheme'] . '://' . $parsed_url['host'];
        // Check if a port is included, append it if present
        if ( !empty($parsed_url['port']) ) {
            $root_url_with_protocol .= ':' . $parsed_url['port'];
        }
        // Append /robots.txt to the root URL
        $robots_txt_url = $root_url_with_protocol . '/robots.txt';
        // Now $robots_txt_url contains the full URL to the robots.txt file
        return $robots_txt_url;
    }
    
    /**
     * Generates a URL for installing a specific WordPress plugin.
     *
     * @param string $plugin_slug The slug of the plugin to install.
     * @return string The URL for installing the specified WordPress plugin.
     */
    public function plugin_install_url( $plugin_slug )
    {
        // Generate a nonce specifically for this plugin installation
        $nonce = wp_create_nonce( 'install-plugin_' . $plugin_slug );
        // Create the URL for installing the plugin
        $url = admin_url( "update.php?action=install-plugin&plugin=" . $plugin_slug . "&_wpnonce=" . $nonce );
        return $url;
    }
    
    /**
     * Checks if a plugin with a given slug is installed.
     *
     * @param string $plugin_slug The slug of the plugin to check.
     * @return bool True if the plugin is installed, false otherwise.
     */
    public function is_plugin_installed( $plugin_slug )
    {
        // Include the plugin.php file if it's not already included
        if ( !function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        // Get all installed plugins
        $installed_plugins = get_plugins();
        // Loop through the installed plugins
        foreach ( $installed_plugins as $path => $details ) {
            // The plugin slug is typically the first segment of the path
            if ( strpos( $path, $plugin_slug ) === 0 ) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * List of installable plugins
     *
     * @return array Array of objects with url string & installed boolean value for each plugin
     */
    public function installable_plugins()
    {
        return [
            'bialty'    => [
            'url'       => $this->plugin_install_url( 'bulk-image-alt-text-with-yoast' ),
            'installed' => $this->is_plugin_installed( 'bulk-image-alt-text-with-yoast' ),
        ],
            'bigta'     => [
            'url'       => $this->plugin_install_url( 'bulk-image-title-attribute' ),
            'installed' => $this->is_plugin_installed( 'bulk-image-title-attribute' ),
        ],
            'autofkw'   => [
            'url'       => $this->plugin_install_url( 'auto-focus-keyword-for-seo' ),
            'installed' => $this->is_plugin_installed( 'auto-focus-keyword-for-seo' ),
        ],
            'autoLinks' => [
            'url'       => $this->plugin_install_url( 'automatic-internal-links-for-seo' ),
            'installed' => $this->is_plugin_installed( 'automatic-internal-links-for-seo' ),
        ],
            'massPing'  => [
            'url'       => $this->plugin_install_url( 'mass-ping-tool-for-seo' ),
            'installed' => $this->is_plugin_installed( 'mass-ping-tool-for-seo' ),
        ],
            'metaTags'  => [
            'url'       => $this->plugin_install_url( 'meta-tags-for-seo' ),
            'installed' => $this->is_plugin_installed( 'meta-tags-for-seo' ),
        ],
            'appAds'    => [
            'url'       => $this->plugin_install_url( 'app-ads-txt' ),
            'installed' => $this->is_plugin_installed( 'app-ads-txt' ),
        ],
        ];
    }
    
    /**
     * List of recommendations
     *
     * @return array Array of objects with details for each recommendation
     */
    public function recommendations_list()
    {
        // Base URL for the plugin directory
        $base_url = plugin_dir_url( __FILE__ );
        // Define free plugins array
        $free_plugins = [
            [
            "name" => __( "Schema App Structured Data by Hunch Manifest", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Get Schema.org structured data for all pages, posts, categories and profile pages on activation.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/schema-app-structured-data-for-schemaorg/",
            "img"  => "../assets/imgs/1.jpg",
        ],
            [
            "name" => __( "Yasr – Yet Another Stars Rating by Dario Curvino", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Boost the way people interact with your website, e-commerce or blog with an easy and intuitive WordPress rating system!", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/yet-another-stars-rating/",
            "img"  => "../assets/imgs/2.jpg",
        ],
            [
            "name" => __( "Better Robots.txt optimization – Website indexing, traffic, ranking & SEO Booster + Woocommerce", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Better Robots.txt is an all in one SEO robots.txt plugin, it creates a virtual robots.txt including your XML sitemaps (Yoast or else) to boost your website ranking on search engines.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/better-robots-txt/",
            "img"  => "../assets/imgs/3.png",
        ],
            [
            "name" => __( "Smush Image Compression and Optimization By WPMU DEV", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Compress and optimize (or optimise) image files, improve performance and boost your SEO rank using Smush WordPress image compression and optimization.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/wp-smushit/",
            "img"  => "../assets/imgs/4.jpg",
        ],
            [
            "name" => __( "404 to 301 By Joel James", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Automatically redirect, log and notify all 404 page errors to any page using 301 redirection...", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/404-to-301/",
            "img"  => "../assets/imgs/5.png",
        ],
            [
            "name" => __( "Yoast SEO By Team Yoast", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Improve your WordPress SEO: Write better content and have a fully optimized WordPress site using the Yoast SEO plugin.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/wordpress-seo/",
            "img"  => "../assets/imgs/6.png",
        ]
        ];
        // Define pro plugins array
        $pro_plugins = [
            [
            "name" => __( "WP-Optimize by David Anderson, Ruhani Rabin, Team Updraft", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "WP-Optimize is WordPress's most-installed optimization plugin. With it, you can clean up your database easily and safely, without manual queries.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/wp-optimize/",
            "img"  => "../assets/imgs/p01.png",
        ],
            [
            "name" => __( "WordPress Share Buttons Plugin – AddThis By The AddThis Team", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Share buttons from AddThis help you get more traffic from sharing through social networks.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/addthis/",
            "img"  => "../assets/imgs/p02.png",
        ],
            [
            "name" => __( "WP-SpamShield WordPress Anti-Spam Plugin by Red Sand Media Group", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "WP-SpamShield is a leading WordPress anti-spam plugin that stops spam instantly and improves your site's security.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://codecanyon.net/item/wpspamshield/21067720",
            "img"  => "../assets/imgs/p03.jpg",
        ],
            [
            "name" => __( "OneSignal – Free Web Push Notifications By OneSignal", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Increase engagement and drive more repeat traffic to your WordPress site with desktop push notifications. Now supporting Chrome, Firefox, and Safari.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/onesignal-free-web-push-notifications/",
            "img"  => "../assets/imgs/p04.png",
        ],
            [
            "name" => __( "WPfomify - Social Proof & FOMO Marketing Plugin", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "WPfomify increases conversion rates on your websites by displaying recent interaction, sales and sign-ups.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wpfomify.com/ref/65/",
            "img"  => "../assets/imgs/p05.jpg",
        ],
            [
            "name" => __( "Recart - Abandoned Cart Toolbox", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Recart Messenger Marketing and Abandoned Cart Toolbox is a All in one cart recovery marketing tools for ecommerce solution.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://recart.com/",
            "img"  => "../assets/imgs/p06.jpg",
        ],
            [
            "name" => __( "Wp-Roket - Probably the best caching Plugin for WordPress", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Speed up your WordPress website, more traffic, conversions and money with WP Rocket caching plugin.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://shareasale.com/r.cfm?b=1075949&u=1849247&m=74778&urllink=&afftrack",
            "img"  => "../assets/imgs/p07.png",
        ],
            [
            "name" => __( "Nofollow for external link By CyberNetikz", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Automatically insert rel=nofollow and target=_blank to all the external links into your website posts, pages or menus. Support exclude domain.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/nofollow-for-external-link/",
            "img"  => "../assets/imgs/p08.jpg",
        ],
            [
            "name" => __( "Google Reviews Widget By RichPlugins", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Google Reviews Widget show Google Places Reviews on your WordPress website to increase user confidence and SEO.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/widget-google-reviews/",
            "img"  => "../assets/imgs/p09.png",
        ],
            [
            "name" => __( "WP Chatbot for facebook Messenger customer chat By HoliThemes", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "Speed up your WordPress website, more traffic, conversions and money with WP Rocket caching plugin.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/wp-chatbot/",
            "img"  => "../assets/imgs/p10.png",
        ],
            [
            "name" => __( "WP Google My Business Auto Publish By Martin Gibson", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "WP Google My Business Auto Publish lets you publish posts, custom posts and pages automatically from WordPress to your Google My Business page.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://wordpress.org/plugins/wp-google-my-business-auto-publish/",
            "img"  => "../assets/imgs/p11.png",
        ],
            [
            "name" => __( "WP Search Console", "bulk-image-alt-text-with-yoast" ),
            "desc" => __( "A new way to boost your marketing content. Writing Web Content that is ranking like those of SEO Experts.", "bulk-image-alt-text-with-yoast" ),
            "link" => "https://www.wpsearchconsole.com/",
            "img"  => "../assets/imgs/p12.png",
        ]
        ];
        // Adding base URL to the img path of each plugin
        foreach ( $free_plugins as $key => $value ) {
            $free_plugins[$key]['img'] = $base_url . $value['img'];
        }
        foreach ( $pro_plugins as $key => $value ) {
            $pro_plugins[$key]['img'] = $base_url . $value['img'];
        }
        // If not, return only the free plugins
        return [
            'plugins' => $free_plugins,
        ];
    }
    
    /**
     * Plugin is in development mode notification
     *
     * @return string HTML string to display the notification
     */
    public function devNotification()
    {
        return '<div class="ep-alert ep-alert--error is-light" role="alert" style="width: 99%; margin-top: 1rem; font-weight: 700"><i class="ep-icon ep-alert__icon"><svg style="height: 1em; width: 1em;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024"><path fill="currentColor" d="M512 64a448 448 0 1 1 0 896 448 448 0 0 1 0-896m0 192a58.432 58.432 0 0 0-58.24 63.744l23.36 256.384a35.072 35.072 0 0 0 69.76 0l23.296-256.384A58.432 58.432 0 0 0 512 256m0 512a51.2 51.2 0 1 0 0-102.4 51.2 51.2 0 0 0 0 102.4"></path></svg></i><div class="ep-alert__content"><span class="ep-alert__title">PLUGIN IS RUNNING IN DEVELOPMENT MODE</span></div></div>';
    }

}