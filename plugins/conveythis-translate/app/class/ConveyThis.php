<?php

require_once 'Variables.php';

class ConveyThis
{
    static $instance;

    private $variables;
    private $ConveyThisCache;
    private $nodePathList = [];

    function __construct()
    {

        $this->variables = new Variables();
        $this->ConveyThisCache = new ConveyThisCache();



        uasort($this->variables->languages, function($a, $b){
            if(strcmp($a['title_en'], $b['title_en']) > 0)
                return 1;
            else if(strcmp($a['title_en'], $b['title_en']) < 0)
                return -1;
            else
                return 0;
        });

        uasort($this->variables->flags, function($a, $b){
            if(strcmp($a['title'], $b['title']) > 0)
                return 1;
            else if(strcmp($a['title'], $b['title']) < 0)
                return -1;
            else
                return 0;
        });

        $this->variables->blockpages_items = [];

        foreach( $this->variables->blockpages as $blockpage )
        {
            if( !empty( $blockpage ) )
            {
                $page_url = $this->getPageUrl( $blockpage );
                $this->variables->blockpages_items[] = $page_url;
            }
        }


        add_filter( 'plugin_row_meta', array( $this, '_row_meta' ), 10, 2 );
        add_filter( 'wp_nav_menu', array( $this, '_menu_shortcode' ), 20, 2 );

        add_action( 'init', array( $this, '_init' ) );

        add_action( 'update_option', array($this, 'plugin_update_option'), 10, 3 );

        add_action( 'admin_menu', array( $this, '_admin_menu' ) );
        add_action( 'admin_init', array( $this, '_admin_init' ) );
        add_action( 'admin_notices', array( $this, '_admin_notices' ), 10 ) ;

        add_action( 'admin_head-nav-menus.php', array( $this, 'add_nav_menu_meta_boxes' ) );
        add_filter( 'nav_menu_link_attributes', array( $this, 'magellanlinkfilter' ), 10, 3 );

        add_action( 'widgets_init', 'wp_register_widget' );
        add_shortcode('conveythis_switcher', array($this, 'get_conveythis_shortcode'));

        //RankMath
        //sitemap
        add_action( 'parse_query', array( $this, 'rank_math_sitemap_init' ), 0 );
        add_action( 'rank_math/sitemap/url', array( $this, 'sitemap_add_translated_urls' ), 10, 2 );
        //OpenGraph
        add_action( 'rank_math/opengraph/url', array( $this, 'rank_math_opengraph_url' ), 10, 2 );

        //Yoast sitemap
        add_action( 'pre_get_posts', array( $this, 'wpseo_init_sitemap' ), 1 );
        add_action( 'wpseo_sitemap_url', array( $this, 'sitemap_add_translated_urls' ), 10, 2 );
        //OpenGraph
        add_action( 'wpseo_opengraph_url', array( $this, 'rank_math_opengraph_url' ), 10, 2 );

        //SeoPress sitemap
        add_action( 'seopress_sitemaps_urlset', array( $this, 'sitemap_add_xhtml_to_urlset' ), 10, 1 );
        add_action( 'seopress_sitemaps_url', array( $this, 'sitemap_add_translated_urls' ), 10, 2 );
        //OpenGraph
        add_action( 'seopress_social_og_url', array( $this, 'seopress_opengraph_url' ), 10, 2 );

        add_action('wp_ajax_conveythis_clear_all_cache', array('ConveyThisCache', 'clearAllCache'));
        add_action('wp_ajax_conveythis_dismiss_all_cache', array('ConveyThisCache', 'dismissAllCacheMessages'));

        add_action('pre_post_update', array($this, 'clear_post'), 10, 2);

        if (strpos($_SERVER['REQUEST_URI'], '/wp-admin/') !== false) {

            if (isset($_POST['exclusions'])) {
                $this->updateRules($_POST['exclusions'], 'exclusion');
            }
            if (isset($_POST['glossary'])) {
                $this->updateRules($_POST['glossary'], 'glossary');
            }
            if (isset($_POST['exclusion_blocks'])) {
                $this->updateRules($_POST['exclusion_blocks'], 'exclusion_blocks');
            }

            if (isset($_POST['clear_translate_cache']) && $_POST['clear_translate_cache']) {
                $result = $this->ConveyThisCache->clear_cached_translations(true);
                header( 'Content-Type: application/json', 1 );
                echo json_encode(['clear_cache_translate' => $result]);
                exit();
            }

        }

        $flag_replaces = ['NV2' => 'af', '5iM' => 'al', '5W5' => 'dz', '0Iu' => 'ad', 'R3d' => 'ao', '16M' => 'ag', 'V1f' => 'ar', 'q9U' => 'am', '2Os' => 'au', '8Dv' => 'at', 'Wg1' => 'az', '' => 'xk', '0qL' => 'bs', 'D9A' => 'bh', '63A' => 'bd', 'u7L' => 'bb', 'O8S' => 'by', '0AT' => 'be', 'lH4' => 'bz', 'I2x' => 'bj', 'D9z' => 'bt', '8Vs' => 'bo', 'Z1t' => 'ba', 'Vf3' => 'bw', '1oU' => 'br', '3rE' => 'bn', 'x8P' => 'bf', '5qZ' => 'bi', 'o8B' => 'kh', '3cO' => 'cm', 'P4g' => 'ca', 'R5O' => 'cv', 'kN9' => 'cf', 'V5u' => 'td', 'wY3' => 'cl', 'Z1v' => 'cn', 'a4S' => 'co', 'N6k' => 'km', 'WK0' => 'cg', 'PP7' => 'cr', '6PX' => 'ci', '7KQ' => 'hr', 'vU2' => 'cu', '1ZY' => 'cz', 'Kv5' => 'cd', 'Ro2' => 'dk', 'MS7' => 'dj', 'E7U' => 'dm', 'Eu2' => 'do', 'D90' => 'ec', '7LL' => 'eg', '0zL' => 'sv', 'b8T' => 'gq', '8Gl' => 'er', 'VJ8' => 'ee', 'ZH1' => 'et', 'E1f' => 'fj', 'nM4' => 'fi', 'E77' => 'fr', 'R1u' => 'ga', 'TZ6' => 'gm', '8Ou' => 'ge', '6Mr' => 'gh', 'kY8' => 'gr', 'yG1' => 'gd', 'aE8' => 'gt', '6Lm' => 'gn', 'I39' => 'gw', 'Mh5' => 'gy', 'Qx7' => 'ht', 'm5Q' => 'hn', 'OU2' => 'hu', 'Ho8' => 'is', 'My6' => 'in', 'G0m' => 'id', 'Vo7' => 'ir', 'z7I' => 'iq', '5Tr' => 'ie', '5KS' => 'il', 'BW7' => 'it', 'u6W' => 'jm', '4YX' => 'jp', 's2B' => 'jo', 'QA5' => 'kz', 'X3y' => 'ke', 'l2H' => 'ki', 'P5F' => 'kw', 'uP6' => 'kg', 'Qy5' => 'la', 'j1D' => 'lv', 'Rl2' => 'lb', 'lB1' => 'ls', '9Qw' => 'lr', 'v6I' => 'ly', '2GH' => 'li', 'uI6' => 'lt', 'EV8' => 'lu', '6GV' => 'mk', '4tE' => 'mg', 'O9C' => 'mw', 'C9k' => 'my', '1Q3' => 'mv', 'Yi5' => 'ml', 'N11' => 'mt', 'Z3x' => 'mh', 'F18' => 'mr', 'mH4' => 'mu', '8Qb' => 'mx', 'H6t' => 'fm', 'FD8' => 'md', 't0X' => 'mc', 'X8h' => 'mn', '61A' => 'me', 'M2e' => 'ma', 'J7N' => 'mz', 'YB9' => 'mm', 'r0H' => 'na', 'M09' => 'nr', 'E0c' => 'np', '8jV' => 'nl', '0Mi' => 'nz', '5dN' => 'ni', 'Rj0' => 'ne', '8oM' => 'ng', '3Yz' => 'kp', '4KE' => 'no', '8NL' => 'om', 'n4T' => 'pk', '8G2' => 'pw', '93O' => 'pa', 'FD4' => 'pg', 'y5O' => 'py', '4MJ' => 'pe', '2qL' => 'ph', 'j0R' => 'pl', '0Rq' => 'pt', 'a8S' => 'qa', 'nC7' => 'ro', 'D1H' => 'ru', '8UD' => 'rw', 'X2d' => 'kn', 'I5e' => 'lc', '3Kf' => 'vc', '54E' => 'ws', 'K4F' => 'sm', 'cZ9' => 'st', 'J06' => 'sa', 'x2O' => 'sn', 'GC6' => 'rs', 'JE6' => 'sc', 'mS4' => 'sl', 'O6e' => 'sg', 'Y2i' => 'sk', 'ZR1' => 'si', '0U1' => 'sb', '3fH' => 'so', '7xS' => 'za', '0W3' => 'kr', 'H4u' => 'ss', 'A5d' => 'es', '9JL' => 'lk', 'Wh1' => 'sd', '7Rb' => 'sr', 'f6L' => 'sz', 'oZ3' => 'se', '8aW' => 'ch', 'UZ9' => 'sy', '00T' => 'tw', '7Qa' => 'tj', 'VU7' => 'tz', 'V6r' => 'th', '52C' => 'tl', 'HH3' => 'tg', '8Ox' => 'to', 'oZ8' => 'tt', 'pD6' => 'tn', 'YZ9' => 'tr', 'Tm5' => 'tm', 'u0Y' => 'tv', 'eJ2' => 'ug', '2Mg' => 'ua', 'DT3' => 'ae', 'Dw0' => 'gb', 'R04' => 'us', 'aL9' => 'uy', 'zJ3' => 'uz', 'D0Y' => 'vu', 'FG2' => 'va', 'Eg6' => 've', 'l2A' => 'vn', 'YZ0' => 'ye', '9Be' => 'zm', '80Y' => 'zw', '00H' => 'hk', '00P' => 'ha'];

        if ( $this->variables->style_change_flag && count( $this->variables->style_change_flag ) ) {
            $update_flag = false;
            foreach ( $this->variables->style_change_flag as $key => $flag ) {
                if ( isset( $flag_replaces[$flag] ) ) {
                    $this->variables->style_change_flag[$key] = $flag_replaces[$flag];
                    $update_flag = true;
                }
            }

            if ( $update_flag ) {
                update_option('style_change_flag', $this->variables->style_change_flag );
            }
        }

    }

    public function clear_post($post_id, $post_data)
    {
        $postLink = get_permalink($post_id);
        foreach ($this->variables->target_languages as $targetLanguage) {
            $clearUrl = $this->getTranslateSiteUrl($postLink, $targetLanguage);
            ConveyThisCache::clearPageCache($clearUrl, null);
        }
    }

    public function rank_math_sitemap_init(){
        global $wp_query;
        if( !empty($wp_query) ){
            $type = get_query_var( 'sitemap', '' );
            add_filter( "rank_math/sitemap/{$type}_urlset",  array( $this, 'sitemap_add_xhtml_to_urlset' ) );
        }
    }

    public function rank_math_opengraph_url($url){
        if(!empty( $this->variables->language_code )){
            $urlParts = parse_url($url);
            if (isset($urlParts['host'])) {
                $url = $urlParts['scheme'] . '://' . $urlParts['host'] . '/' . $this->variables->language_code . $urlParts['path'];
            }
        }
        return $url;
    }

    public function seopress_opengraph_url($html_url){

        if(!empty( $this->variables->language_code )){
            preg_match('/content="([^"]+)"/', $html_url, $matches);
            if (isset($matches[1])) {
                $url = $matches[1];

                $urlParts = parse_url($url);
                if (isset($urlParts['host'])) {
                    $url = $urlParts['scheme'] . '://' . $urlParts['host'] . '/' . $this->variables->language_code . $urlParts['path'];

                    $pattern = '/(content=")([^"]+)(")/';
                    $replacement = '${1}' . $url . '${3}';
                    $html_url = preg_replace($pattern, $replacement, $html_url);

                }
            }
        }

        return $html_url;
    }

    public function wpseo_init_sitemap() {
        global $wp_query;
        if ( !empty( $wp_query ) ) {
            $type = get_query_var( 'sitemap', '' );
            add_filter( "wpseo_sitemap_{$type}_urlset",  array( $this, 'sitemap_add_xhtml_to_urlset' ) );
        }
    }

    public function sitemap_add_xhtml_to_urlset( $urlset ){
        $urlset = str_replace(  '<urlset', '<urlset xmlns:xhtml="http://www.w3.org/1999/xhtml" ', $urlset);
        return $urlset;
    }

    public function sitemap_add_translated_urls( $output, $url ){

        if(in_array($url['loc'], $this->variables->blockpages)) // no need to add translated url for blocked pages
            return $output;

        $alternate = "";
        $translatedOutputUrls = array();

        foreach($this->variables->target_languages as $language){

            $site_url = home_url();

            $site_url = str_replace("https://","",$site_url);
            $site_url = str_replace("http://","",$site_url);

            if(!empty($this->variables->url_structure) && $this->variables->url_structure == "subdomain")
                $translatedUrl = str_replace($site_url, $language.".".$site_url, $url['loc']);
            else
                $translatedUrl = str_replace($site_url, $site_url."/".$language, $url['loc']);


            $loc = "\t\t<loc>".$translatedUrl."</loc>\n";
            $lasmod = !empty($url['mod']) ? "\t\t<lastmod>".date('c', strtotime($url['mod']))."</lastmod>\n" : "";
            $images = "";
            if( isset($url['images']) && is_array($url['images']) ){
                foreach ($url['images'] as $image) {
                    $images .= "\t\t<image:image><image:loc>".$image['src']."</image:loc></image:image>\n";
                }
            }

            $translatedOutputUrls[] = "\t<url>\n".$loc.$lasmod.$images."\t</url>\n";
            $alternate .= "\t<xhtml:link rel='alternate' hreflang='".$language."' href='".$translatedUrl."' />\n\t";
        }

        // add source language to alternate
        $alternate .= "\t<xhtml:link rel='alternate' hreflang='".$this->variables->source_language."' href='".$url['loc']."' />\n\t";

        //add alternate to translated url
        foreach ( $translatedOutputUrls as &$value){
            $value = str_replace("</url>", $alternate."</url>", $value);
        }

        //add alternate to source url
        $newOutput = str_replace("</url>", $alternate."</url>", $output);

        $translatedOutput = implode("",$translatedOutputUrls);

        return $newOutput.$translatedOutput;
    }

    public function magellanlinkfilter( $attr, $post, $menu )
    {
        preg_match( '/\[ConveyThis_(.*)\]/', $post->title, $matches );

        if( !empty( $matches ) )
        {
            $language = $this->searchLanguage( $matches[1] );

            if( !empty( $language ) )
            {
                if( !empty( $this->variables->language_code ) )
                {
                    if( $language['code2'] === $this->variables->source_language )
                    {
                        $language = $this->searchLanguage( $this->variables->language_code );
                    }

                    else if( $language['code2'] === $this->variables->language_code )
                    {
                        $language = $this->searchLanguage( $this->variables->source_language );
                    }
                }

                $site_url = $this->variables->site_url;
                $prefix = $this->getPageUrl( $site_url );

                if(!empty($this->variables->url_structure) && $this->variables->url_structure == "subdomain")
                    $location = $this->getSubDomainLocation( $language['code2'] );
                else
                    $location = $this->getLocation( $prefix, $language['code2'] );

                $icon = $this->genIcon( $language['language_id'], $language['flag'] );
                $attr['translate'] = 'no';
                $attr['href'] = $location;
                $attr['class'] = "conveythis-no-translate notranslate";

                if( $this->variables->style_text === 'full-text' )
                {
                    $post->title = $icon . $language['title'];
                }
                if( $this->variables->style_text === 'short-text' )
                {
                    $post->title = $icon . strtoupper( $language['code3'] );
                }
                if( $this->variables->style_text === 'without-text' )
                {
                    $post->title = $icon;
                }

            }
        }

        return $attr;
    }

    public function genIcon( $language_id, $flag )
    {
        $i = 0;

        while( $i < 5 )
        {
            if( !empty( $this->variables->style_change_language[$i] ) && $this->variables->style_change_language[$i] == $language_id )
            {
                $flag = $this->variables->style_change_flag[$i];
            }
            $i++;
        }

        //

        $icon = '';

        if( $this->variables->style_flag === 'rect' )
        {
            $icon = '<span style="height: 20px; width: 30px; background-image: url(\'//cdn.conveythis.com/images/flags/svg/' . $flag . '.png\'); display: inline-block; background-size: contain; background-position: 50% 50%; background-repeat: no-repeat; background-color: transparent; margin-right: 10px; vertical-align: middle;"></span>'; // v3/rectangular
        }

        if( $this->variables->style_flag === 'sqr' )
        {
            $icon = '<span style="height: 24px; width: 24px; background-image: url(\'//cdn.conveythis.com/images/flags/svg/' . $flag . '.png\'); display: inline-block; background-size: contain; background-position: 50% 50%; background-repeat: no-repeat; background-color: transparent; margin-right: 10px; vertical-align: middle;"></span>'; // v3/square
        }

        if( $this->variables->style_flag === 'cir' )
        {
            $icon = '<span style="height: 24px; width: 24px; background-image: url(\'//cdn.conveythis.com/images/flags/svg/' . $flag . '.png\'); display: inline-block; background-size: contain; background-position: 50% 50%; background-repeat: no-repeat; background-color: transparent; margin-right: 10px; vertical-align: middle;"></span>'; // v3/round
        }

        if( $this->variables->style_flag === 'without-flag' )
        {
            $icon = '';
        }

        return $icon;
    }

    public function _menu_shortcode( $menu, $args )
    {
        return do_shortcode( $menu );
    }

    public function add_nav_menu_meta_boxes()
    {
        add_meta_box( 'conveythis_nav_link', __( 'ConveyThis', 'conveythis-translate' ), array( $this, 'nav_menu_links' ), 'nav-menus', 'side', 'low' );
    }

    public function nav_menu_links()
    {
        $languages = array();

        if( !empty( $this->variables->language_code ) )
        {
            $current_language_code = $this->variables->language_code;
        }
        else
        {
            $current_language_code = $this->variables->source_language;
        }


        $language = $this->searchLanguage( $current_language_code );

        if( !empty( $language ) )
        {
            $languages[] = array(
                'id' => $language['language_id'],
                'title' => $language['title'],
                'title_en' => $language['title_en'],
            );
        }

        if( !empty( $this->variables->language_code ) )
        {
            $language = $this->searchLanguage( $this->variables->source_language );

            if( !empty( $language ) )
            {
                $languages[] = array(
                    'id' => $language['language_id'],
                    'title' => $language['title'],
                    'title_en' => $language['title_en'],
                );
            }
        }

        foreach( $this->variables->target_languages as $language_code )
        {
            $language = $this->searchLanguage( $language_code );

            if( !empty( $language ) )
            {
                if( $current_language_code != $language['code2'] )
                {
                    $languages[] = array(
                        'id' => $language['language_id'],
                        'title' => $language['title'],
                        'title_en' => $language['title_en'],
                    );
                }
            }
        }

        require_once CONVEY_PLUGIN_ROOT_PATH . 'app/templates/posttype-conveythis-languages.php';

    }

    function _row_meta( $links, $file )
    {
        $plugin = plugin_basename( __FILE__ );

        if( $plugin == $file )
        {
            $links[] = '<a href="https://www.conveythis.com/help-center/support-and-resources/?utm_source=widget&utm_medium=wordpress" target="_blank">' . __( 'FAQ', 'conveythis-translate' ) . '</a>';
            $links[] = '<a href="https://wordpress.org/support/plugin/conveythis-translate" target="_blank">' . __( 'Support', 'conveythis-translate' ) . '</a>';
            $links[] = '<a href="https://wordpress.org/plugins/conveythis-translate/#reviews" target="_blank">' . __( 'Rate this plugin', 'conveythis-translate' ) . '</a>';

        }
        return $links;
    }

    public static function _settings_link( $links )
    {

        array_push( $links, '<a href="options-general.php?page=convey_this">' . __( 'Settings', 'conveythis-translate' ) . '</a>' );

        return $links;
    }

    function _admin_menu()
    {

        add_menu_page(
            'ConveyThis Settings',
            'ConveyThis',
            'manage_options',
            'convey_this',
            array( $this, 'pluginOptions' ),
            'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxnIGNsaXAtcGF0aD0idXJsKCNjbGlwMF8xMDFfODMpIj4KPG1hc2sgaWQ9Im1hc2swXzEwMV84MyIgc3R5bGU9Im1hc2stdHlwZTpsdW1pbmFuY2UiIG1hc2tVbml0cz0idXNlclNwYWNlT25Vc2UiIHg9IjAiIHk9IjEwIiB3aWR0aD0iOTIiIGhlaWdodD0iODAiPgo8cGF0aCBkPSJNMCAxMEg5MS4xNTc4VjkwSDBWMTBaIiBmaWxsPSJ3aGl0ZSIvPgo8L21hc2s+CjxnIG1hc2s9InVybCgjbWFzazBfMTAxXzgzKSI+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNNjAuMDY4OSA4OS45MDg5QzU2LjA5MzUgODkuOTA4OSA1Mi4yNjEyIDg5LjM3ODUgNDguNjY4NSA4OC4zMjA1QzQ4LjMzMjYgODguMjIzOCA0OC4xNDEzIDg3Ljk4MzQgNDguMDQ2MyA4Ny42OTQ3QzQ3Ljk5ODEgODcuMzU3NyA0OC4wOTMxIDg3LjA2OSA0OC4zMzI2IDg2LjgyODdDNDguNzE2NyA4Ni40OTE3IDQ4Ljk1NjIgODYuMDExIDQ4Ljk1NjIgODUuNDMzNkM0OC45NTYyIDg0LjQyNCA0OC4xNDEzIDgzLjU1NzkgNDcuMDg4MyA4My41NTc5SDI5Ljg5MTNDMjguODg1IDgzLjU1NzkgMjguMDIxOSA4Mi43NDAyIDI4LjAyMTkgODEuNjgwOEMyOC4wMjE5IDgwLjY3MTEgMjguODM2OSA3OS44MDUxIDI5Ljg5MTMgNzkuODA1MUgzNS4zNTJDMzYuMzU4MiA3OS44MDUxIDM3LjIxOTkgNzguOTg3NCAzNy4yMTk5IDc3LjkyOEMzNy4yMTk5IDc2LjkxODMgMzYuNDA1IDc2LjA1MjMgMzUuMzUyIDc2LjA1MjNIMzAuMDM0NEgyOS45ODYzSDIzLjYxNTdDMjIuNjA5NCA3Ni4wNTIzIDIxLjc0NzggNzUuMjM0NiAyMS43NDc4IDc0LjE3NTFDMjEuNzQ3OCA3My4xNjU0IDIyLjU2MTMgNzIuMjk5NCAyMy42MTU3IDcyLjI5OTRIMjguNjQ1NUMyOS42NTE4IDcyLjI5OTQgMzAuNTEzNSA3MS40ODE3IDMwLjUxMzUgNzAuNDIyM0MzMC41MTM1IDY5LjQxMjYgMjkuNjk4NiA2OC41NDY2IDI4LjY0NTUgNjguNTQ2NkgyMC44ODQ3QzE5Ljg3OTggNjguNTQ2NiAxOS4wMTY3IDY3LjcyODkgMTkuMDE2NyA2Ni42NzA5QzE5LjAxNjcgNjUuNjU5OCAxOS44MzE2IDY0Ljc5MzcgMjAuODg0NyA2NC43OTM3SDM1LjMwMzhDMzYuMzEgNjQuNzkzNyAzNy4xNzE3IDYzLjk3NiAzNy4xNzE3IDYyLjkxOEMzNy4xNzE3IDYxLjkwNjkgMzYuMzU4MiA2MS4wNDA5IDM1LjMwMzggNjEuMDQwOUgxNi42MjE2QzE1LjYxNTMgNjEuMDQwOSAxNC43NTM2IDYwLjIyMzIgMTQuNzUzNiA1OS4xNjUyQzE0Ljc1MzYgNTguMTU0MSAxNS41Njg1IDU3LjI4OCAxNi42MjE2IDU3LjI4OEgyMi45NDUzQzIzLjk1MTYgNTcuMjg4IDI0LjgxMzMgNTYuNDcwMyAyNC44MTMzIDU1LjQxMjNDMjQuODEzMyA1NC40MDEyIDIzLjk5ODQgNTMuNTM1MiAyMi45NDUzIDUzLjUzNTJIMTYuMjg3MUMxNS4yODA4IDUzLjUzNTIgMTQuNDE3NyA1Mi43MTc1IDE0LjQxNzcgNTEuNjU5NUMxNC40MTc3IDUwLjY0ODQgMTUuMjMyNiA0OS43ODIzIDE2LjI4NzEgNDkuNzgyM0gyOS4wMjgyQzMwLjAzNDQgNDkuNzgyMyAzMC44OTYxIDQ4Ljk2NDcgMzAuODk2MSA0Ny45MDY2QzMwLjg5NjEgNDYuODk1NSAzMC4wODI2IDQ2LjAyOTUgMjkuMDI4MiA0Ni4wMjk1SDE5Ljc4MzRDMTguNzc3MiA0Ni4wMjk1IDE3LjkxNTUgNDUuMjExOCAxNy45MTU1IDQ0LjE1MzhDMTcuOTE1NSA0My4xNDI3IDE4LjcyOSA0Mi4yNzgxIDE5Ljc4MzQgNDIuMjc4MUgyMy4zMjhDMjQuMzM0MiA0Mi4yNzgxIDI1LjE5NTkgNDEuNDU5IDI1LjE5NTkgNDAuNDAwOUMyNS4xOTU5IDM5LjM5MTMgMjQuMzgyNCAzOC41MjUyIDIzLjMyOCAzOC41MjUySDE2LjA0NzZDMTUuMDQxMyAzOC41MjUyIDE0LjE3ODIgMzcuNzA2MSAxNC4xNzgyIDM2LjY0ODFDMTQuMTc4MiAzNS42Mzg0IDE0Ljk5MzEgMzQuNzcyNCAxNi4wNDc2IDM0Ljc3MjRIMzYuMTE4N0MzNy4xMjM1IDM0Ljc3MjQgMzcuOTg2NyAzMy45NTMzIDM3Ljk4NjcgMzIuODk1M0MzNy45ODY3IDMxLjk4MDkgMzcuMzE0OSAzMS4yMTE1IDM2LjQ1MzIgMzEuMDY3OUgzMC43NTNDMzAuNjU2NiAzMS4wNjc5IDMwLjU2MTcgMzEuMDY3OSAzMC40MTcxIDMxLjAxOTZIMjkuMjE5NUMyOS4xMjQ2IDMxLjAxOTYgMjkuMDI4MiAzMS4wNjc5IDI4Ljg4NSAzMS4wNjc5SDEzLjUwNzhDMTIuNTAxNiAzMS4wNjc5IDExLjYzOTkgMzAuMjQ4OCAxMS42Mzk5IDI5LjE5MDhDMTEuNjM5OSAyOC4xODExIDEyLjQ1NDggMjcuMzE1MSAxMy41MDc4IDI3LjMxNTFIMjguODg1QzI5LjAyODIgMjcuMzE1MSAyOS4xNzI4IDI3LjMxNTEgMjkuMzE1OSAyNy4zNjJIMzAuMjc0QzMwLjQxNzEgMjcuMzE1MSAzMC41NjE3IDI3LjMxNTEgMzAuNzA0OCAyNy4zMTUxSDMxLjYxNDdDMzIuNDI5NiAyNy4xMjE3IDMzLjAwNSAyNi40MDA3IDMzLjAwNSAyNS40ODYzQzMzLjAwNSAyNC41NzE5IDMyLjMzMzIgMjMuODAyNSAzMS40NzE1IDIzLjY1NzVIMjkuMzY0MUMyOC4zNTc4IDIzLjY1NzUgMjcuNDk2MSAyMi44Mzk4IDI3LjQ5NjEgMjEuNzgxOEMyNy40OTYxIDIwLjc3MDcgMjguMzA5NiAxOS45MDQ2IDI5LjM2NDEgMTkuOTA0Nkg0My44NzgyQzQ0Ljg4NDQgMTkuOTA0NiA0NS43NDYxIDE5LjA4NyA0NS43NDYxIDE4LjAyODlDNDUuNzQ2MSAxNy4xMTQ1IDQ1LjA3NTggMTYuMzQ1MiA0NC4yMTQxIDE2LjIwMDJIMzQuOTIxMUMzMy45MTQ5IDE2LjIwMDIgMzMuMDUxOCAxNS4zODI1IDMzLjA1MTggMTQuMzI0NEMzMy4wNTE4IDEzLjMxMzQgMzMuODY2NyAxMi40NDczIDM0LjkyMTEgMTIuNDQ3M0g0Ni41MTI5QzUwLjg3MjMgMTAuODYwMyA1NS42MTQ1IDEwLjA5MDkgNjAuNzM5MiAxMC4wOTA5QzY0LjI4MzggMTAuMDkwOSA2Ny41NDIxIDEwLjM3OTYgNzAuNTExMiAxMC45NTdDNzMuNDMzNiAxMS41MzQzIDc2LjExNjQgMTIuMzUyIDc4LjUxMTYgMTMuMzYxN0M4MC45MDY4IDE0LjM3MjggODMuMTEwNiAxNS42MjI4IDg1LjE2OTkgMTcuMDY2MkM4Ni45ODk2IDE4LjM2NiA4OC42NjYyIDE5Ljc2MSA5MC4yNDc5IDIxLjMwMTFDOTAuNTgyNCAyMS42MzY3IDkwLjU4MjQgMjIuMTY3MiA5MC4yOTQ3IDIyLjUwMjhMODAuNzE1NCAzMy41NjkzQzgwLjU3MDkgMzMuNzYxMyA4MC4zNzk1IDMzLjg1OCA4MC4xNCAzMy44NThDNzkuOTAwNSAzMy44NTggNzkuNzA5MiAzMy44MDk3IDc5LjUxNzkgMzMuNjY2Qzc2LjczODYgMzEuMjU5OSA3My45MTI2IDI5LjMzNTggNzEuMDg2NiAyNy44OTI0QzY4LjAyMTEgMjYuMzUyMyA2NC41MjMzIDI1LjU4MyA2MC42OTI1IDI1LjU4M0M1Ny40ODI0IDI1LjU4MyA1NC41MTE4IDI2LjIwNzMgNTEuODMwNCAyNy40NTg3QzQ5LjA5OTMgMjguNzEwMSA0Ni43NTI0IDMwLjM5MzggNDQuNzg4MSAzMi42MDY2QzQyLjgyMzcgMzQuODE5MyA0MS4yOTE3IDM3LjMyMjIgNDAuMTg5MSA0MC4yMDlDMzkuMDg3OSA0My4wOTU4IDM4LjU2MDYgNDYuMTc0NiAzOC41NjA2IDQ5LjQ0NjdWNDkuNjg3MUMzOC41NjA2IDUyLjk1NzggMzkuMDg3OSA1Ni4wODUgNDAuMTg5MSA1OC45NzE4QzQxLjI5MTcgNjEuOTA2OSA0Mi43NzcgNjQuNDU2NyA0NC42OTMxIDY2LjYyMjVDNDYuNjA5MiA2OC44MzUyIDQ4Ljk1NjIgNzAuNTY3MyA1MS42ODU4IDcxLjgxODdDNTQuNDE2OSA3My4xMTcxIDU3LjM4NiA3My43NDI4IDYwLjY5MjUgNzMuNzQyOEM2NS4wNTA1IDczLjc0MjggNjguNzM5NiA3Mi45MjUxIDcxLjc1NyA3MS4zMzY3Qzc0LjU4MyA2OS44NDQ5IDc3LjM2MjIgNjcuODI1NiA4MC4xNCA2NS4zMjI3QzgwLjQ3NTkgNjQuOTg1NyA4MS4wMDE3IDY1LjAzNDEgODEuMzM3NiA2NS4zNzExTDkwLjgyMTkgNzQuOTk0MkM5MS4xNTc4IDc1LjMyOTggOTEuMTU3OCA3NS44NjAzIDkwLjgyMTkgNzYuMTk1OUM4OS4wNTAzIDc4LjA3MyA4Ny4xODI0IDc5Ljc1NjcgODUuMzEzIDgxLjI0ODVDODMuMjA1NiA4Mi45MzIyIDgwLjkwNjggODQuMzc1NiA3OC40MTUyIDg1LjU3ODdDNzUuOTI1MSA4Ni43ODA0IDczLjE0NTkgODcuNjk0NyA3MC4xNzY3IDg4LjMyMDVDNjcuMTExMiA4OS42MjAyIDYzLjc1OCA4OS45MDg5IDYwLjA2ODkgODkuOTA4OVpNMy4zNTMyMiA0Ni40MTQ5SDQuMDIzNThDNS4wMjk4MyA0Ni40MTQ5IDUuODkxNTIgNDcuMjMyNiA1Ljg5MTUyIDQ4LjI5MkM1Ljg5MTUyIDQ5LjMwMTcgNS4wNzgwMiA1MC4xNjc3IDQuMDIzNTggNTAuMTY3N0gzLjM1MzIyQzIuMzQ2OTcgNTAuMTY3NyAxLjQ4NTI4IDQ5LjM1IDEuNDg1MjggNDguMjkyQzEuNTMyMDUgNDcuMjMyNiAyLjM0Njk3IDQ2LjQxNDkgMy4zNTMyMiA0Ni40MTQ5Wk05LjI0NDc1IDQ2LjQxNDlIMTEuOTc1OEMxMi45ODA2IDQ2LjQxNDkgMTMuODQzNyA0Ny4yMzI2IDEzLjg0MzcgNDguMjkyQzEzLjg0MzcgNDkuMzAxNyAxMy4wMjg4IDUwLjE2NzcgMTEuOTc1OCA1MC4xNjc3SDkuMjQ0NzVDOC4yMzg1IDUwLjE2NzcgNy4zNzY4MSA0OS4zNSA3LjM3NjgxIDQ4LjI5MkM3LjM3NjgxIDQ3LjIzMjYgOC4xOTE3MyA0Ni40MTQ5IDkuMjQ0NzUgNDYuNDE0OVpNOS43NzE5NiAyMC40MzUxSDEyLjE2NzFDMTMuMTczNCAyMC40MzUxIDE0LjAzNTEgMjEuMjUyOCAxNC4wMzUxIDIyLjMxMDhDMTQuMDM1MSAyMy4zMjE5IDEzLjIyMDEgMjQuMTg3OSAxMi4xNjcxIDI0LjE4NzlIOS43NzE5NkM4Ljc2NTcxIDI0LjE4NzkgNy45MDQwMiAyMy4zNjg4IDcuOTA0MDIgMjIuMzEwOEM3LjkwNDAyIDIxLjMwMTEgOC43NjU3MSAyMC40MzUxIDkuNzcxOTYgMjAuNDM1MVpNMTcuMTQ4OCAyMC40MzUxSDI0LjE0MjlDMjUuMTQ3OCAyMC40MzUxIDI2LjAxMDkgMjEuMjUyOCAyNi4wMTA5IDIyLjMxMDhDMjYuMDEwOSAyMy4zMjE5IDI1LjE5NTkgMjQuMTg3OSAyNC4xNDI5IDI0LjE4NzlIMTcuMTQ4OEMxNi4xNDI1IDI0LjE4NzkgMTUuMjgwOCAyMy4zNjg4IDE1LjI4MDggMjIuMzEwOEMxNS4yODA4IDIxLjMwMTEgMTYuMDk0MyAyMC40MzUxIDE3LjE0ODggMjAuNDM1MVpNMjAuODM3OSA3OS45MDA0SDIyLjk0NTNDMjMuOTUxNiA3OS45MDA0IDI0LjgxMzMgODAuNzE5NSAyNC44MTMzIDgxLjc3NzVDMjQuODEzMyA4Mi43ODcyIDIzLjk5ODQgODMuNjUzMiAyMi45NDUzIDgzLjY1MzJIMjAuODM3OUMxOS44MzE2IDgzLjY1MzIgMTguOTY4NSA4Mi44MzU1IDE4Ljk2ODUgODEuNzc3NUMxOC45Njg1IDgwLjcxOTUgMTkuODMxNiA3OS45MDA0IDIwLjgzNzkgNzkuOTAwNFpNNi4xMzEwNCA1Ny41NzY3SDEwLjY4MThDMTEuNjg4MSA1Ny41NzY3IDEyLjU0OTggNTguMzk0NCAxMi41NDk4IDU5LjQ1MzhDMTIuNTQ5OCA2MC40NjM1IDExLjczNjMgNjEuMzI5NiAxMC42ODE4IDYxLjMyOTZINi4xMzEwNEM1LjEyNDc5IDYxLjMyOTYgNC4yNjMxIDYwLjUxMTkgNC4yNjMxIDU5LjQ1MzhDNC4yNjMxIDU4LjM5NDQgNS4xMjQ3OSA1Ny41NzY3IDYuMTMxMDQgNTcuNTc2N1pNMjcuNTQyOSAxMy4wMjQ3SDMwLjI3NEMzMS4yODAyIDEzLjAyNDcgMzIuMTQxOSAxMy44NDM4IDMyLjE0MTkgMTQuOTAxOEMzMi4xNDE5IDE1LjkxMTUgMzEuMzI4NCAxNi43Nzc1IDMwLjI3NCAxNi43Nzc1SDI3LjU0MjlDMjYuNTM4MSAxNi43Nzc1IDI1LjY3NSAxNS45NTk4IDI1LjY3NSAxNC45MDE4QzI1LjY3NSAxMy44OTA3IDI2LjUzODEgMTMuMDI0NyAyNy41NDI5IDEzLjAyNDdaTTEuODY3OTQgMzUuMjA0N0g5LjA1MzQyQzEwLjA1OTcgMzUuMjA0NyAxMC45MjE0IDM2LjAyMjQgMTAuOTIxNCAzNy4wODE4QzEwLjkyMTQgMzguMDkxNSAxMC4xMDc5IDM4Ljk1NzUgOS4wNTM0MiAzOC45NTc1SDEuODY3OTRDMC44MTM1MDQgMzguOTU3NSAwIDM4LjA5MTUgMCAzNy4wODE4QzAgMzYuMDcwNyAwLjgxMzUwNCAzNS4yMDQ3IDEuODY3OTQgMzUuMjA0N1pNNy40NzMxOCA2NC45ODU3SDkuODY4MzRDMTAuODczMiA2NC45ODU3IDExLjczNjMgNjUuODA0OCAxMS43MzYzIDY2Ljg2MjhDMTEuNzM2MyA2Ny44NzI1IDEwLjkyMTQgNjguNzM4NSA5Ljg2ODM0IDY4LjczODVINy40NzMxOEM2LjQ2NjkzIDY4LjczODUgNS42MDM4MiA2Ny45MjA5IDUuNjAzODIgNjYuODYyOEM1LjYwMzgyIDY1Ljg1MTcgNi40MTg3NCA2NC45ODU3IDcuNDczMTggNjQuOTg1N1oiIGZpbGw9IiNBQUFBQUEiLz4KPC9nPgo8bWFzayBpZD0ibWFzazFfMTAxXzgzIiBzdHlsZT0ibWFzay10eXBlOmx1bWluYW5jZSIgbWFza1VuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeD0iNTciIHk9IjM3IiB3aWR0aD0iNDQiIGhlaWdodD0iMjYiPgo8cGF0aCBkPSJNNTcuMTg3NyAzNy40NzAxSDEwMFY2Mi41ODgxSDU3LjE4NzdWMzcuNDcwMVoiIGZpbGw9IndoaXRlIi8+CjwvbWFzaz4KPGcgbWFzaz0idXJsKCNtYXNrMV8xMDFfODMpIj4KPHBhdGggZD0iTTk5LjYzNDQgNDguOTk4N0M5OS4yODI5IDQ4LjM0MTcgOTguNTc3MSA0OC4wNDAyIDk3Ljk3NDggNDcuNjc0N0M5MC43NTk2IDQzLjQxNDIgODIuNDU1OSA0MS4wNjc4IDc0LjA5ODMgNDAuOTI0MkM3Mi43MjA3IDM4Ljg1NjUgNzAuMzExNCAzNy41Mjk3IDY3LjgzODMgMzcuNTIyNkM2NC43MTMzIDM3LjUyMTEgNjEuNTg2OCAzNy40ODg0IDU4LjQ2MDMgMzcuNTA2OUM1Ny41MDIzIDM3LjUzOTYgNTcuMDQ0NSAzOC45Mjc2IDU3LjgwMTMgMzkuNTMwNUM1OS42NTY1IDQwLjk0MjYgNjEuNTU4NCA0Mi4yOTA4IDYzLjQyNSA0My42ODcyQzY0LjcwNDggNDQuNjg1NSA2NS40ODk5IDQ2LjI5NjcgNjUuNDg1NyA0Ny45Mjc5QzY1LjQ5NDIgNDkuMzU0MiA2NS40OTEzIDUwLjc3OTEgNjUuNDk3IDUyLjIwNTRDNjUuNDkxMyA1My44MDI0IDY0LjczNDUgNTUuMzgyNCA2My40NzYgNTYuMzU3OUM2MS42MDgxIDU3Ljc1NzIgNTkuNzAwNCA1OS4xMDI1IDU3Ljg0MSA2MC41MTE4QzU3LjA2NzIgNjEuMTQ3NCA1Ny42MDU3IDYyLjU3MjMgNTguNjAyMSA2Mi41Mjk3QzYxLjY4MDMgNjIuNTQ5NiA2NC43NiA2Mi41NDUzIDY3LjgzODMgNjIuNTU4MUM3MC4zMTE0IDYyLjU2OTUgNzIuNzM0OSA2MS4yNzQgNzQuMTI5NSA1OS4yMTM0QzgyLjgzOTkgNTkuMDYyNyA5MS41MjIgNTYuNjAxMSA5OC45NTcgNTEuOTk2NEM5OS45MjM1IDUxLjM4MDYgMTAwLjIyMyA0OS45NzcxIDk5LjYzNDQgNDguOTk4N1oiIGZpbGw9IiNBQUFBQUEiLz4KPC9nPgo8cGF0aCBkPSJNNjAuNTE4MyA1MS4yMTcyQzYxLjAzNDIgNTEuMjY0MiA2MS41NTQzIDUwLjk0NTYgNjEuNzI0NCA1MC40NDc5QzYxLjk1ODIgNDkuODUyIDYxLjYxMjQgNDkuMTE4MyA2MS4wMDU5IDQ4LjkyMzRDNTkuMTAyNSA0OC43ODQxIDU3LjE3NjQgNDguODg5MyA1NS4yNjMxIDQ4Ljg0ODFDNTQuNjA4NCA0OC44ODUgNTMuNzk5MSA0OC42NjMyIDUzLjMxNDQgNDkuMjUwNUM1Mi42NzUyIDQ5LjkxODkgNTMuMTg4MyA1MS4xODQ1IDU0LjExMDkgNTEuMTg4OEM1Ni4yNDY3IDUxLjIxNTggNTguMzgxMSA1MS4yMDMgNjAuNTE4MyA1MS4yMTcyWiIgZmlsbD0iI0FBQUFBQSIvPgo8cGF0aCBkPSJNNjAuOTE1IDQ1LjA2NjhDNTcuNDEwMiA0NC45ODQzIDUzLjg5OTYgNDUuMDQyNiA1MC4zOTE5IDQ1LjAwNzFDNDkuODY0NyA0NC45NTczIDQ5LjMwMDYgNDUuMjI2MSA0OS4xMTUgNDUuNzQ5NEM0OC43OTc1IDQ2LjQ1MTkgNDkuMzcyOSA0Ny4zNTIxIDUwLjEzODIgNDcuMzQ3OEM1My42MDA2IDQ3LjM3MiA1Ny4wNjI5IDQ3LjM2NzcgNjAuNTI2NyA0Ny4zODM0QzYxLjA0NCA0Ny40MjYgNjEuNTcxMiA0Ny4xMTMyIDYxLjc0MTMgNDYuNjA4M0M2MS45ODM2IDQ1Ljk4MTIgNjEuNTcyNiA0NS4yMDE5IDYwLjkxNSA0NS4wNjY4WiIgZmlsbD0iI0FBQUFBQSIvPgo8cGF0aCBkPSJNNjAuOTE1IDUyLjc4NDRDNTcuNDEwMiA1Mi43MDE5IDUzLjg5OTYgNTIuNzYwMyA1MC4zOTE5IDUyLjcyNDdDNDkuODY0NyA1Mi42NzQ5IDQ5LjMwMDYgNTIuOTQyMyA0OS4xMTUgNTMuNDY3QzQ4Ljc5NzUgNTQuMTY4MSA0OS4zNzI5IDU1LjA2ODMgNTAuMTM4MiA1NS4wNjU0QzUzLjYwMDYgNTUuMDg5NiA1Ny4wNjI5IDU1LjA4MzkgNjAuNTI2NyA1NS4xMDFDNjEuMDQ0IDU1LjE0MjIgNjEuNTcxMiA1NC44MzA4IDYxLjc0MTMgNTQuMzI0NUM2MS45ODM2IDUzLjY5NzQgNjEuNTcyNiA1Mi45MTk1IDYwLjkxNSA1Mi43ODQ0WiIgZmlsbD0iI0FBQUFBQSIvPgo8L2c+CjxkZWZzPgo8Y2xpcFBhdGggaWQ9ImNsaXAwXzEwMV84MyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSJ3aGl0ZSIvPgo8L2NsaXBQYXRoPgo8L2RlZnM+Cjwvc3ZnPgo='
        );

    }

    function _admin_notices()
    {

        if( !extension_loaded('xml') )
        {
            ?>
            <div class="error settings-error notice is-dismissible">
                <p>
                    <?php echo  __( 'Plugin requires installing php-xml extension.', 'conveythis-translate' ); ?>
                </p>
            </div>
            <?php
        }
    }

    function _admin_init()
    {

        register_setting( 'my-plugin-settings', 'api_key'  );

        register_setting( 'my-plugin-settings', 'source_language' );
        register_setting( 'my-plugin-settings', 'target_languages', array( $this, '_check_target_languages' ) );

        register_setting( 'my-plugin-settings-group', 'api_key' );
        register_setting( 'my-plugin-settings-group', 'source_language' );
        register_setting( 'my-plugin-settings-group', 'target_languages', array( $this, '_check_target_languages' ) );
        register_setting( 'my-plugin-settings-group', 'target_languages_translations' );
        register_setting( 'my-plugin-settings-group', 'default_language' );

        register_setting( 'my-plugin-settings-group', 'style_change_language', array( $this, '_check_style_change_language' ) );
        register_setting( 'my-plugin-settings-group', 'style_change_flag', array( $this, '_check_style_change_flag' ) );
        register_setting( 'my-plugin-settings-group', 'style_flag' );
        register_setting( 'my-plugin-settings-group', 'style_text' );
        register_setting( 'my-plugin-settings-group', 'style_position_vertical' );
        register_setting( 'my-plugin-settings-group', 'style_position_horizontal' );
        register_setting( 'my-plugin-settings-group', 'style_indenting_vertical' );
        register_setting( 'my-plugin-settings-group', 'style_indenting_horizontal' );
        register_setting( 'my-plugin-settings-group', 'auto_translate' );
        register_setting( 'my-plugin-settings-group', 'hide_conveythis_logo' );
        register_setting( 'my-plugin-settings-group', 'translate_media' );
        register_setting( 'my-plugin-settings-group', 'translate_document' );
        register_setting( 'my-plugin-settings-group', 'translate_links' );
        register_setting( 'my-plugin-settings-group', 'change_direction' );
        register_setting( 'my-plugin-settings-group', 'conveythis_lang_code_url' );
        register_setting( 'my-plugin-settings-group', 'conveythis_clear_cache' );
        register_setting( 'my-plugin-settings-group', 'conveythis_select_region' );


        register_setting( 'my-plugin-settings-group', 'alternate' );
        register_setting( 'my-plugin-settings-group', 'accept_language' );
        register_setting( 'my-plugin-settings-group', 'blockpages', array( $this, '_check_blockpages' ) );
        register_setting( 'my-plugin-settings-group', 'show_javascript' );

        register_setting( 'my-plugin-settings-group', 'style_position_type' );
        register_setting( 'my-plugin-settings-group', 'style_position_vertical_custom' );
        register_setting( 'my-plugin-settings-group', 'style_selector_id' );

        register_setting( 'my-plugin-settings-group', 'url_structure' );

        register_setting( 'my-plugin-settings-group', 'style_background_color' );
        register_setting( 'my-plugin-settings-group', 'style_hover_color' );
        register_setting( 'my-plugin-settings-group', 'style_border_color' );
        register_setting( 'my-plugin-settings-group', 'style_text_color' );
        register_setting( 'my-plugin-settings-group', 'style_corner_type' );
        register_setting( 'my-plugin-settings-group', 'style_widget' );

        register_setting( 'my-plugin-settings-group', 'conveythis_system_links');

        if( !empty( $_REQUEST['page'] ) && $_REQUEST['page'] == 'convey_this' )
        {

            if( !empty( $this->variables->api_key ) )
            {

                if (($key = array_search($this->variables->source_language, $this->variables->target_languages)) !== false) { //remove source_language from target_languages
                    unset($this->variables->target_languages[$key]);
                }

                $this->send( 'PUT', '/website/update/', array(
                        'referrer' => '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                        'source_language' => $this->variables->source_language ?: 'en',
                        'target_languages' => $this->variables->target_languages ?: ['en'],
                        'accept_language' => $this->variables->accept_language,
                        'blockpages' => $this->variables->blockpages_items,
                        'technology' => 'wordpress')
                );

                $this->variables->exclusions = $this->send(  'GET', '/admin/account/domain/pages/excluded/?referrer='. urlencode($_SERVER['HTTP_HOST']) );
                $this->variables->glossary = $this->send(  'GET', '/admin/account/domain/pages/glossary/?referrer='. urlencode($_SERVER['HTTP_HOST']) );
                $this->variables->exclusion_blocks = $this->send(  'GET', '/admin/account/domain/excluded/blocks/?referrer='. urlencode($_SERVER['HTTP_HOST']) );

                if(isset($_GET["settings-updated"]))
                {
                    $this->updateDataPlugin();
                }

                $this->ConveyThisCache->clear_cached_translations(true);

            }

        }
    }

    function updateDataPlugin() {

        if (($key = array_search($this->variables->source_language, $this->variables->target_languages)) !== false) { //remove source_language from target_languages
            unset($this->variables->target_languages[$key]);
        }

        $this->send( 'PUT', '/plugin/settings/', array(
            'referrer' => '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'accept_language' => $this->variables->accept_language,
            'blockpages' => $this->variables->blockpages_items,
            'technology' => 'wordpress',

            'settings' => array(
                'source_language' => $this->variables->source_language ?: 'en',
                'target_languages' => $this->variables->target_languages ?: ['en'],
                'style_change_language' => $this->variables->style_change_language,
                'style_change_flag' => $this->variables->style_change_flag,
                'default_language' => $this->variables->default_language,
                'style_flag' => $this->variables->style_flag,
                'style_text' => $this->variables->style_text,
                'style_position_vertical' => $this->variables->style_position_vertical,
                'style_position_horizontal' => $this->variables->style_position_horizontal,
                'style_indenting_vertical' => $this->variables->style_indenting_vertical,
                'style_indenting_horizontal' => $this->variables->style_indenting_horizontal,
                'auto_translate' => $this->variables->auto_translate,
                'hide_conveythis_logo' => $this->variables->hide_conveythis_logo,
                'translate_media' => $this->variables->translate_media,
                'translate_document' => $this->variables->translate_document,
                'translate_links' => $this->variables->translate_links,
                'change_direction' => $this->variables->change_direction,
                'style_position_type' => $this->variables->style_position_type,
                'style_position_vertical_custom' => $this->variables->style_position_vertical_custom,
                'style_selector_id' => $this->variables->style_selector_id,
                'url_structure' => $this->variables->url_structure,
                'style_background_color' => $this->variables->style_background_color,
                'style_hover_color' => $this->variables->style_hover_color,
                'style_border_color' => $this->variables->style_border_color,
                'style_text_color' => $this->variables->style_text_color,
                'style_corner_type' => $this->variables->style_corner_type,
                'style_widget' => $this->variables->style_widget,
                'select_region' => $this->variables->select_region
            )

        ));



    }

    function reqOnGetSettingsUser() {
        $api_key = $this->variables->api_key;
        $domain_name = $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : '';

        if (!$api_key) return Array();

        $req_method = "GET";
        $request_uri = '/plugin/settings/'. $api_key .'/'. $domain_name .'/';
        $headers = [
            'X-Api-Key' => $this->variables->api_key
        ];

        if (strpos($request_uri, '/admin/') === 0) {
            $headers['X-Auth-Token'] = API_AUTH_TOKEN;
        }

        $response = $this->httpRequest( $request_uri, [
            'headers' => $headers,
            'body' => null,
            'method' => $req_method,
            'redirection' => '10',
            'httpversion' => '1.1',
            'blocking' => true,
            'cookies' => []
        ], false);

        $body = $response['body'];



        $data = json_decode( $body, true );

        return (!empty($data['data']) ? $data['data'] : Array());

    }

    function writeDataInBD() {

        $data = $this->reqOnGetSettingsUser();

        foreach ($data as $option_name => $new_value) {
            $current_value = get_option($option_name, 'option_does_not_exist');

            if ($current_value === 'option_does_not_exist') {
                continue;
            }

            if ($current_value !== $new_value) {
                update_option($option_name, $new_value);
            }
        }

    }

    function getSettingsOnStart($api_key)
    {
        $this->variables->api_key = $api_key;

        $this->writeDataInBD();
    }

    function dataCheckAPI() {

        $this->writeDataInBD();

    }

    function _check_style_change_language( $value )
    {
        if( !is_array( $value ) )
        {
            return array();
        }

        return $value;
    }

    function _check_style_change_flag( $value )
    {
        if( !is_array( $value ) )
        {
            return array();
        }

        return $value;
    }

    function _check_blockpages( $value )
    {
        if( !is_array( $value ) )
        {
            return array();
        }

        return $value;
    }

    function _check_target_languages( $value )
    {

        if( !empty( $value ) )
        {
            $target_languages = array();

            if (is_string($value)) {
                $language_codes = explode(',', $value);
            } elseif (is_array($value)) {
                $language_codes = $value;
            }

            foreach( $language_codes as $language_code )
            {
                $language = $this->searchLanguage( $language_code );

                if( !empty( $language ) )
                {
                    $target_languages[] = $language['code2'];
                }
            }
            return $target_languages;
        }

        else
        {
            return array();
        }
    }

    public function searchLanguage( $value )
    {
        foreach( $this->variables->languages as $language )
        {
            if( $value === $language['code2'] || $value === $language['title_en'] )
            {
                return $language;
            }
        }
    }

    function getPageUrl( $str )
    {
        $n = 0;
        $length = strlen( $str );
        $buffer = '';
        $step = 0;

        while( $n < $length )
        {
            if( $str[$n] === '/' )
            {
                if( $step === 1 ) $step = 2;

                if( $step === 0 )
                {
                    $buffer = '/';
                    $step = 1;
                }
            }

            else
            {
                if( $step === 2 )
                {
                    $buffer = '';
                    $step = 0;
                }

                if( $step === 1 ) $step = 3;
            }

            if( $str[$n] === '?' || $str[$n] === '#' ) break;
            if( $step === 3 ) $buffer .= $str[$n];

            $n++;
        }

        $buffer = trim( $buffer );
        $buffer = rtrim( $buffer, '/' );

        if( empty( $buffer ) )
        {
            $buffer = '/';
        }
        return rtrim( $buffer, '/' ) . '/';
    }

    function getPageHost($url)
    {
        $urlData = parse_url( $url );
        $host = isset( $urlData['host'] )?trim( preg_replace('/^www\./', '', $urlData['host'] )) : null;
        return $host;
    }

    function _init()
    {
        if (strpos($_SERVER["REQUEST_URI"], '/wp-json/') !== false) {
            return;
        }

        if (strpos($_SERVER["REQUEST_URI"], '/conveythis-404/') !== false) {
            get_template_part( 404 );
            return;
        }

        //$_SERVER["REQUEST_URI"] = preg_replace('/[^a-zA-Z0-9\-_\/.%:&=?#а-яА-ЯёЁ]/u', '', $_SERVER["REQUEST_URI"]);

        $this->variables->site_url = home_url();
        $this->variables->site_host = $this->getPageHost( $this->variables->site_url );
        $this->variables->site_prefix = $this->getPageUrl( $this->variables->site_url );
        $this->variables->referrer = '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        if( !is_admin() )
        {
            if(empty($this->variables->url_structure) || $this->variables->url_structure != "subdomain"){ // no need to do anything with subdomains

                if( $this->variables->auto_translate && isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ){

                    if( class_exists('Locale') ){
                        $browserLanguage = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
                        $browserLanguage = substr($browserLanguage, 0, 2);
                    }else{
                        $browserLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
                    }

                    if (in_array($browserLanguage, $this->variables->target_languages)) {

                        session_start();
                        if (empty($_SESSION['conveythis-autoredirected'])){

                            $_SESSION['conveythis-autoredirected'] = true;


                            $preventAutoRedirect = false;
                            foreach ($this->variables->target_languages as $key => $language) {	//check if already contains translate language prefix

                                if(strpos($_SERVER["REQUEST_URI"], '/'.$language.'/') !== false
                                    && strpos($_SERVER["REQUEST_URI"], '/'.$language.'/') === 0){

                                    $preventAutoRedirect = true;
                                }
                            }

                            if(!$preventAutoRedirect){
                                $location = $this->getLocation($this->variables->site_prefix, $browserLanguage);
                                header("Location: ".$location);
                                die();
                            }
                        }
                    }

                }

                if (!empty($this->variables->target_languages)) {

                    $tempRequestUri = $_SERVER["REQUEST_URI"];
                    if (substr($tempRequestUri, -1) != "/")
                        $tempRequestUri .= "/";

                    //$this->target_languages_translations

                    preg_match('/^(' . str_replace('/', '\/', $this->variables->site_prefix) . '([^\/]+)\/)(.*)/', $tempRequestUri, $matches);

                    if (!empty($matches)) {
                        $this->variables->language_code = array_search(urldecode(trim($matches[2])), $this->variables->target_languages_translations);
                    }

                    if (!$this->variables->language_code) {

                        preg_match('/^(' . str_replace('/', '\/', $this->variables->site_prefix) . '(' . implode('|', $this->variables->target_languages) . ')\/)(.*)/', $tempRequestUri, $matches);

                        if (!empty($matches)) {
                            $this->variables->language_code = esc_attr($matches[2]);
                        }
                    }
                    if (!in_array($this->variables->default_language, $this->variables->target_languages)) {
                        $this->variables->default_language = '';
                    }
                    if (!$this->variables->language_code && strpos($_SERVER['REQUEST_URI'], 'wp-login') === false && strpos($_SERVER['REQUEST_URI'], 'wp-admin') === false) {
                        if (!isset($_SERVER['HTTP_REFERER']) || !$_SERVER['HTTP_REFERER'] || $this->variables->site_host != $this->getPageHost($_SERVER['HTTP_REFERER'])) {
                            $this->variables->language_code = isset($this->variables->target_languages_translations[$this->variables->default_language]) ? $this->variables->target_languages_translations[$this->variables->default_language] : $this->variables->default_language;
                        }
                        if ($this->variables->language_code) {
                            $translated_slug = $this->find_translation($_SERVER['REQUEST_URI'], $this->variables->source_language, $this->variables->default_language, '//' . $_SERVER['HTTP_HOST']);

                            if ($translated_slug) {
                                $_SERVER['REQUEST_URI'] = $translated_slug;
                            }
                            header('Location: /' . $this->variables->language_code . $_SERVER["REQUEST_URI"], true, 302);
                            exit();
                        }
                    }

                    if ($this->variables->language_code) {

                        $tmp = esc_attr($matches[1]);
                        $origin = $_SERVER["REQUEST_URI"];
                        $_SERVER["REQUEST_URI"] = esc_url(substr_replace($_SERVER["REQUEST_URI"], $this->variables->site_prefix, 0, strlen($tmp)));
                        $this->variables->referrer = '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                        if (trim($matches[3]) && $this->variables->translate_links) {
                            $slug = '/' . urldecode(trim($matches[3]));
                            $original_slug = $this->find_original_slug($slug, $this->variables->source_language, $this->variables->language_code, $this->variables->referrer);
                            if ($original_slug) {
                                $_SERVER["REQUEST_URI"] = preg_replace('/\/' . preg_quote($matches[3], '/') . '$/', $original_slug, $_SERVER["REQUEST_URI"]);
                                $this->variables->referrer = '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                            }
                        }

                        $page_url = $this->getPageUrl($this->variables->referrer);

                        if (in_array($page_url, $this->variables->blockpages_items)) {
                            $_SERVER["REQUEST_URI"] = $origin;
                            $this->variables->language_code = null;
                        }

                        if (preg_match("/\/(feed|wp-json)\//", $page_url)) {    //prevent translation of RSS and wp-json
                            $this->variables->language_code = null;
                        }
                    }
                }


                if( !empty( $this->variables->source_language ) && !empty( $this->variables->target_languages ) )
                {
                    $page_url = $this->getPageUrl( $this->variables->referrer );

                    if( !in_array( $page_url, $this->variables->blockpages_items ) )
                    {
                        // if( !empty( $this->variables->show_javascript ) )
                        // {
                        $this->getCurrentPlan();
                        add_action( 'wp_footer', array( $this, '_inline_script' ) );
                        // }
                    }
                }

                if( !empty( $this->variables->alternate ) )
                {
                    add_action( 'wp_head', array( $this, '_alternate' ), 0 );

                    if( !empty( $this->variables->language_code ) )
                    {
                        add_filter( 'locale', function( $value ) {
                            return $this->variables->language_code;
                        });
                    }

                    else
                    {
                        add_filter( 'locale', function( $value ) {
                            $langs = explode( '_', $value );
                            return $langs[0];
                        });
                    }
                }

                ob_start( array( $this, '_translatePage' ) );

            }else{
                if( !empty( $this->variables->source_language ) && !empty( $this->variables->target_languages ) ){
                    $this->getCurrentPlan();
                    if( !empty( $this->variables->alternate ) )
                        add_action( 'wp_head', array( $this, '_alternate' ), 0 );
                    add_action( 'wp_footer', array( $this, '_inline_script' ) );
                }
            }
        } else {
            new ConveyThisAdminNotices();
        }

    }

    function haveOptionEndSlash(){
        $haveSlash = true;
        $permalinkStructure = get_transient('convey_permalink_structure');
        if ($permalinkStructure === false) {
            $permalinkStructure = get_option('permalink_structure');
            set_transient('convey_permalink_structure', $permalinkStructure, 12 * HOUR_IN_SECONDS);
        }

        if (substr($permalinkStructure, -1) !== '/') {
            $haveSlash = false;
        }
        return $haveSlash;
    }

    function getCurrentPlan(){

        $domain_name = $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : '';

        $response = $this->httpRequest("/website/code/get?api_key=".$this->variables->api_key."&domain_name=".$domain_name."&referer=". base64_encode($_SERVER["REQUEST_URI"]), array(
            'method' => "GET"
        ), true , $this->variables->select_region);
        $responseBody = wp_remote_retrieve_body( $response );

        if(!empty($responseBody)){

            $json = json_decode($responseBody);
            if(!empty($json->code)){
                if(strpos($json->code, "conveythis_trial_expired") !== false){
                    $this->variables->plan = 'trial-expired';
                }else{
                    $this->variables->plan = 'paid';
                }
                if (preg_match('/no_translate_element_ids:\s*(\[.+\])/U', $json->code, $matches))  {
                    $this->variables->exclusion_block_ids = json_decode($matches[1]);
                }
                if (preg_match('/is_exceeded:/U', $json->code, $matches))  {
                    $this->variables->exceeded = true;
                }
            }else{
                $this->variables->show_widget = false;
            }

        }

    }

    public function languageAccept( $data, $value )
    {
        $languages = explode( ',', $value );

        if( !empty( $languages ) )
        {
            foreach( $languages as $language )
            {
                $tmp = explode( ';', $language );
                $code = explode( '-', $tmp[0] );

                if( in_array( $code[0], $this->variables->target_languages ) )
                {
                    $location = $this->replaceLink( $data, $code[0] );

                    header('Location: ' . $location );
                    break;
                }
            }
        }
    }

    public function _alternate()
    {
        $site_url_parts = parse_url(home_url());
        $site_domain = $site_url_parts["scheme"]."://".$site_url_parts["host"];
        $site_url = home_url();

        $prefix = $this->getPageUrl( $site_url );

//        if(!empty($this->variables->url_structure) && $this->variables->url_structure == "subdomain"){
//            $location = $this->getSubDomainLocation( $this->variables->source_language );
//            echo '<link rel="alternate" href="' . esc_attr($location) .'" hreflang="x-default">';
//        }else{
//            $location = $this->getLocation( $prefix, $this->variables->source_language );
//            echo '<link rel="alternate" href="' . esc_attr($site_domain . $location) .'" hreflang="x-default">';
//        }
        echo "\n";

        $data = array_merge( $this->variables->target_languages, array( $this->variables->source_language ) );

        $_temp_blockpages = [];
        foreach ($this->variables->blockpages as $blockpages)
        {
            $_temp_blockpages[] = str_replace($site_domain, '', $blockpages);
        }

        foreach( $data as $value )
        {
            $language = $this->searchLanguage( $value );

            if( !empty( $language ) )
            {
                if(!empty($this->variables->url_structure) && $this->variables->url_structure == "subdomain")
                {
                    $location = $this->getSubDomainLocation( $language['code2'], true );
                    echo '<link rel="alternate" href="'. esc_attr($location) .'" hreflang="'. esc_attr( $language['code2'] ) .'">';
//                    header('Link:<'.esc_attr($location).'>; rel="alternate"; hreflang="'.esc_attr( $language['code2'] ).'"', false);
                }
                else
                {

//                    if( $this->variables->language_code === $language['code2'] ) //|| $language['code2'] === $this->variables->source_language
//                        continue;

//                    if( empty($this->variables->language_code) && $language['code2'] === $this->variables->source_language )
//                        continue;

                    $location = $this->getLocation( $prefix, $language['code2'], true );

                    if( $language['code2'] === $this->variables->source_language || $this->variables->lang_code_url )
                    {
                        echo '<link rel="alternate" href="' . esc_attr($site_domain . $location) .'" hreflang="'. esc_attr( $language['code2'] ) .'">';
//                        header('Link:<'.esc_attr($site_domain . $location).'>; rel="alternate"; hreflang="'.esc_attr( $language['code2'] ).'"', false);
                    }
                    else
                    {
                        $_short_url = str_replace($site_domain . '/' . $language['code2'], '', esc_attr($site_domain . $location));

                        if(!in_array($_short_url, $_temp_blockpages))
                        {
                            echo '<link rel="alternate" href="' . esc_attr($site_domain . $location) .'" hreflang="'. esc_attr( $language['code2'] ) .'">';
//                            header('Link:<'.esc_attr($site_domain . $location).'>; rel="alternate"; hreflang="'.esc_attr( $language['code2'] ).'"', false);
                        }
                        else
                            continue;
                    }

                }
            }
            echo "\n";

        }
    }

    private function deleteQueryParams($url, $alternate_link) {
        $parsedUrl = parse_url($url);

        if (isset($parsedUrl['query'])) {
            if ($alternate_link) $parsedUrl['query']='';

            parse_str($parsedUrl['query'], $queryParams);

            foreach ($this->variables->query_params_block as $param)
            {
                if(array_key_exists($param, $queryParams))
                {
                    unset($queryParams[$param]);
                }
            }

            $newUrl = $parsedUrl['path'];

            if (!empty($queryParams)) {
                $newUrl .= '?' . http_build_query($queryParams, '', '&');
            }

            return $newUrl;
        }

        return $url;
    }

    public function getLocation( $prefix, $language_code, $alternate_link=false )
    {
        $_url = $this->deleteQueryParams($_SERVER["REQUEST_URI"], $alternate_link);


        if( $this->variables->source_language == $language_code )
        {
            return $_url;
        }
        else
        {

            if ( isset( $this->variables->target_languages_translations[$language_code] ) ) {
                $language_code = $this->variables->target_languages_translations[$language_code];
            }

            if(
                strpos($_url, '/'.$language_code.'/') !== false &&
                strpos($_url, '/'.$language_code.'/') === 0
            )
            { //check if already contains language prefix
                return $_url;
            }
            else
            {
                return substr_replace( $_url, $prefix . '' . $language_code . '/', 0, strlen( $prefix ) );
            }

        }

    }

    public function getSubDomainLocation( $language_code, $alternative_link=false )
    {

        $_url = $this->deleteQueryParams($_SERVER["REQUEST_URI"], $alternative_link);

        if( $this->variables->source_language == $language_code )
        {
            return $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].$_url;
        }

        else
        {
            return $_SERVER["REQUEST_SCHEME"]."://".$language_code.".".$_SERVER["HTTP_HOST"].$_url;
        }
    }

    function pluginOptions()
    {
        if( !current_user_can( 'manage_options' ) )
        {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        if(empty($_GET["settings-updated"]))
        {
            $this->dataCheckAPI();
        }

        require_once CONVEY_PLUGIN_ROOT_PATH . 'app/views/index.php';
    }

    public function _inline_script()
    {
        if (is_404()) {
            return;
        }
        if (!$this->variables->show_widget) {
            return;
        }
        $site_url = $this->variables->site_url;
        $prefix = $this->getPageUrl( $site_url );

        $languages = array();

        if( !empty( $this->variables->language_code ) )
        {
            $current_language_code = $this->variables->language_code;
        }

        else
        {
            $current_language_code = $this->variables->source_language;
        }

        $language = $this->searchLanguage( $current_language_code );

        if( !empty( $language ) )
        {
            if(!empty($this->variables->url_structure) && $this->variables->url_structure == "subdomain")
                $location = $this->getSubDomainLocation( $language['code2'] );
            else
                $location = $this->getLocation( $prefix, $language['code2'] );

            $languages[] = '{"id":"'. esc_attr( $language['language_id'] ) .'", "location":"'. esc_attr( $location ) .'", "active":true}';

        }

        if( !empty( $this->variables->language_code ) )
        {
            $language = $this->searchLanguage( $this->variables->source_language );

            if( !empty( $language ) )
            {
                if(!empty($this->variables->url_structure) && $this->variables->url_structure == "subdomain")
                    $location = $this->getSubDomainLocation( $language['code2'] );
                else
                    $location = $this->getLocation( $prefix, $language['code2'] );

                $languages[] = '{"id":"'. esc_attr( $language['language_id'] ) .'", "location":"'. esc_attr( $location ) .'", "active":false}';
            }
        }

        if (($key = array_search($this->variables->source_language, $this->variables->target_languages)) !== false) { //remove source_language from target_languages
            unset($this->variables->target_languages[$key]);
        }

        foreach( $this->variables->target_languages as $language_code )
        {
            $language = $this->searchLanguage( $language_code );

            if( !empty( $language ) )
            {
                if( $current_language_code != $language['code2'] )
                {
                    if(!empty($this->variables->url_structure) && $this->variables->url_structure == "subdomain")
                        $location = $this->getSubDomainLocation( $language['code2'] );
                    else
                        $location = $this->getLocation( $prefix, $language['code2'] );

                    $languages[] = '{"id":"'. esc_attr( $language['language_id'] ) .'", "location":"'. esc_attr( $location ) .'", "active":false}';
                }
            }
        }

        $source_language_id = 0;

        if( !empty( $this->variables->source_language ) )
        {
            $language = $this->searchLanguage( $this->variables->source_language );

            if( !empty( $language ) )
            {
                $source_language_id = $language['language_id'];
            }
        }

        $i = 0;

        $temp = array();

        while( $i < 5 )
        {
            if( !empty( $this->variables->style_change_language[$i] ) )
            {
                $temp[] = '"' . $this->variables->style_change_language[$i] . '":"' . $this->variables->style_change_flag[$i] . '"';
            }
            $i++;
        }

        $change = '{' . implode( ',', $temp ) .'}';

        $positionTop = 'null';
        $positionBottom = 'null';
        $positionLeft = 'null';
        $positionRight = 'null';

        if($this->variables->style_position_type == 'custom' && $this->variables->style_selector_id != '') {
            if ($this->variables->style_position_vertical_custom == 'top') {
                $positionTop = 50;
                $positionBottom = "null";
            } else {
                $positionTop = "null";
                $positionBottom = 0;
            }

            $positionLeft  = "null";
            $positionRight = 25;
            $styleSelectorId = $this->variables->style_selector_id ?: null;
        } else {
            if ($this->variables->style_position_vertical == 'top') {
                $positionTop = $this->variables->style_indenting_vertical ?: 0;
                $positionBottom = "null";
            } else {
                $positionTop = "null";
                $positionBottom = $this->variables->style_indenting_vertical ?: 0;
            }
            if ($this->variables->style_position_horizontal == 'right') {
                $positionRight = (!is_null($this->variables->style_indenting_horizontal) && !empty($this->variables->style_indenting_horizontal))  ? $this->variables->style_indenting_horizontal : 24;
                $positionLeft = "null";
            } else {
                $positionRight = "null";
                $positionLeft = (!is_null($this->variables->style_indenting_horizontal) && !empty($this->variables->style_indenting_horizontal))  ? $this->variables->style_indenting_horizontal : 24;
            }
            $styleSelectorId = null;
        }

        if ($this->variables->plan == 'trial-expired'){
            wp_enqueue_script('conveythis-trial-expired', plugins_url('../widget/js/trial-expired.js',__FILE__));
            return;
        }

        if (!empty($this->variables->api_key)) {

            wp_enqueue_script('conveythis-notranslate', plugin_dir_url(__DIR__).'widget/js/notranslate.js');
            wp_enqueue_script('conveythis-conveythis', CONVEYTHIS_JAVASCRIPT_PLUGIN_URL . "/conveythis.js", [], 155);
            wp_enqueue_script('conveythis-translate', CONVEYTHIS_JAVASCRIPT_PLUGIN_URL . "/translate.js");
            if (!is_admin() && !empty($this->variables->language_code)) {
                //wp_enqueue_script('conveythis-update-cache', plugins_url('../widget/js/update-cache.js', __FILE__));
            }

            $initScript = 'document.addEventListener("DOMContentLoaded", function(e) {';
            $initScript .= 'conveythis.init({';
            $initScript .= 'change:' . $change . ',';
            $initScript .= 'icon:"' . esc_attr($this->variables->style_flag) . '",';
            $initScript .= 'text:"' . esc_attr($this->variables->style_text) . '",';
            $initScript .= 'positionTop:' . esc_attr($positionTop) . ',';
            $initScript .= 'positionBottom:' . esc_attr($positionBottom) . ',';
            $initScript .= 'positionLeft:' . esc_attr($positionLeft) . ',';
            $initScript .= 'positionRight:' . esc_attr($positionRight) . ',';
            $initScript .= 'languages:[' . implode(', ', $languages) . '],';
            $initScript .= 'api_key:"' . esc_attr($this->variables->api_key) . '",';
            $initScript .= 'source_language_id:"' . esc_attr($source_language_id) . '",';
            $initScript .= 'auto_translate:' . esc_attr($this->variables->auto_translate ? $this->variables->auto_translate : '1') . ',';
            $initScript .= 'hide_conveythis_logo:' . esc_attr($this->variables->hide_conveythis_logo ? $this->variables->hide_conveythis_logo : '0') . ',';
            $initScript .= 'translate_media:' . esc_attr($this->variables->translate_media ? $this->variables->translate_media : '0') . ',';
            $initScript .= 'translate_document:' . esc_attr($this->variables->translate_document ? $this->variables->translate_document : '0') . ',';
            $initScript .= 'translate_links:' . esc_attr($this->variables->translate_links ? $this->variables->translate_document : '0') . ',';
            $initScript .= 'change_direction:' . esc_attr($this->variables->change_direction ? $this->variables->translate_document : '0') . ',';
            $initScript .= 'php_plugin_cur_lang:"' . $this->searchLanguage($current_language_code)['language_id'] . '",';
            $initScript .= 'background_color:"' . esc_attr($this->variables->style_background_color) . '",';
            $initScript .= 'hover_color:"' . esc_attr($this->variables->style_hover_color) . '",';
            $initScript .= 'border_color:"' . esc_attr($this->variables->style_border_color) . '",';
            $initScript .= 'text_color:"' . esc_attr($this->variables->style_text_color) . '",';
            $initScript .= 'corner_type:"' . esc_attr($this->variables->style_corner_type) . '",';
            $initScript .= 'style_widget:"' . esc_attr($this->variables->style_widget) . '",';

            if (isset($styleSelectorId)) {
                $initScript .= 'selector: "' . $styleSelectorId . '",';
            }
            if (empty($this->variables->show_javascript)) {
                $initScript .= 'hide_conveythis_button: 1,';
            }
            if (isset($this->variables->url_structure) && $this->variables->url_structure == "subdomain") {
                $initScript .= 'is_subdomain: 1,';
            }
            if ( $this->variables->exceeded ) {
                $initScript .= 'is_exceeded: 1,';
            }

            $initScript .= '});';
            $initScript .= '});';

            wp_add_inline_script('conveythis-translate', $initScript);
        }
    }

    function DOMinnerHTML(DOMNode $element)
    {
        $innerHTML = "";
        $children  = $element->childNodes;

        foreach ($children as $child)
        {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }

    function shouldTranslateWholeTag($element){
        for($i = 0; $i < count($element->childNodes); $i++){
            $child = $element->childNodes->item($i);

            if(in_array(strtoupper($child->nodeName), $this->variables->siblingsAvoidArray)){
                return false;
            }
        }
        return true;
    }

    function allowTranslateWholeTag($element){
        for($i = 0; $i < count($element->childNodes); $i++){
            $child = $element->childNodes->item($i);

            if(in_array(strtoupper($child->nodeName), $this->variables->siblingsAllowArray)){
                $outerHTML = $element->ownerDocument->saveHTML($child);

                if(preg_match("/>(\s*[^<>\s]+[\s\S]*?)</", $outerHTML)){
                    return true;
                }else if(strtoupper($child->nodeName) == "BR"){
                    $innerHTML = $this->DOMinnerHTML($element);

                    if(preg_match("/\s*[^<>\s]+\s*<br>\s*[^<>\s]+/i", $innerHTML)){
                        return true;
                    }
                }
            }
        }
        return false;
    }

    function isTextNodeExists($element){
        for($i = 0; $i < count($element->childNodes); $i++){
            $child = $element->childNodes->item($i);

            if($child->nodeName == "#text" && trim($child->textContent)){
                return true;
            }
        }
        return false;
    }

    // DOM

    function domRecursiveRead( $doc )
    {
        foreach( $doc->childNodes as $child )
        {
            if( $child->nodeType === 3 )
            {
                $value = trim( $child->textContent );
                // $value = htmlentities($child->textContent, null, 'utf-8');
                // $value = str_ireplace("&nbsp;", " ", $value);
                // $value = trim($value);

                if( !empty( $value ) )
                {
                    if	($child->nextSibling || $child->previousSibling) {

                        if($child->parentNode && $this->allowTranslateWholeTag($child->parentNode) && $this->shouldTranslateWholeTag($child->parentNode)){
                            $value = trim($this->DOMinnerHTML($child->parentNode));
                            $value = preg_replace("/\<!--(.*?)\-->/", "", $value);
                            $this->variables->segments[$value] = $value;
                            $this->collectNode( $child->parentNode, 'innerHTML', $value );
                        } else {
                            $this->variables->segments[$value] = $value;
                            $this->collectNode( $child, 'textContent', $value );
                        }
                    }
                    else {
                        $this->variables->segments[$value] = $value;
                        $this->collectNode( $child, 'textContent', $value );
                    }
                }

            }
            else
            {
                if( $child->nodeType === 1 )
                {
                    if( $child->hasAttribute('title') )
                    {
                        $attrValue = trim( $child->getAttribute('title') );
                        if( !empty( $attrValue ) ) {
                            $this->collectNode( $child, 'title', $attrValue );
                        }
                    }

                    if( $child->hasAttribute('alt') )
                    {
                        $attrValue = trim( $child->getAttribute('alt') );
                        if( !empty( $attrValue ) ) {
                            $this->collectNode( $child, 'alt', $attrValue );
                        }
                    }

                    if( $child->hasAttribute('placeholder') )
                    {
                        $attrValue = trim( $child->getAttribute('placeholder') );
                        if( !empty( $attrValue ) ) {
                            $this->collectNode( $child, 'placeholder', $attrValue );
                        }
                    }

                    if( $child->hasAttribute( 'type' ) )
                    {
                        $attrTypeValue = trim( $child->getAttribute( 'type' ) );

                        if( strcasecmp( $attrTypeValue, 'submit' ) === 0 || strcasecmp( $attrTypeValue, 'reset' ) === 0)
                        {
                            if( $child->hasAttribute( 'value' ) )
                            {
                                $attrValue = trim( $child->getAttribute( 'value' ) );
                                if( !empty( $attrValue ) ) {
                                    $this->collectNode( $child, 'value', $attrValue );
                                }
                            }
                        }
                    }

                    if( !empty( $attrValue ) )
                    {
                        $this->variables->segments[$attrValue] = $attrValue;
                    }

                    if( strcasecmp( $child->nodeName, 'meta' ) === 0 )
                    {
                        if( $child->hasAttribute('name') || $child->hasAttribute( 'property' ) )
                        {
                            if($child->hasAttribute('name'))
                                $metaAttributeName = trim( $child->getAttribute('name') );
                            else
                                $metaAttributeName = trim( $child->getAttribute('property') );

                            if(
                                (
                                    strcasecmp( $metaAttributeName, 'title' ) === 0 ||
                                    strcasecmp( $metaAttributeName, 'twitter:title' ) === 0 ||
                                    strcasecmp( $metaAttributeName, 'og:title' ) === 0
                                )
                                ||
                                (
                                    strcasecmp( $metaAttributeName, 'description' ) === 0 ||
                                    strcasecmp( $metaAttributeName, 'twitter:description' ) === 0 ||
                                    strcasecmp( $metaAttributeName, 'og:description' ) === 0
                                )
                                ||
                                strcasecmp( $metaAttributeName, 'keywords' ) === 0
                            )
                            {
                                if( $child->hasAttribute('content') )
                                {
                                    $metaAttrValue = trim( $child->getAttribute('content') );

                                    if( !empty( $metaAttrValue ) )
                                    {
                                        $this->variables->segments[$metaAttrValue] = $metaAttrValue;
                                        $this->collectNode( $child, 'content', $metaAttrValue );
                                    }
                                }
                            }
                        }
                    }

                    if($child->nodeName == 'img'){

                        if($this->variables->translate_media){
                            $src = $child->getAttribute("src");
                            $ext = strtolower(pathinfo($src, PATHINFO_EXTENSION));
                            if(strpos($ext,"?") !== false) $ext = substr($ext, 0, strpos($ext,"?"));

                            if(in_array($ext, $this->variables->imageExt)){
                                $this->variables->segments[$src] = $src;
                                $this->collectNode( $child, 'src', $src );
                            }
                        }

                    }


                    $shouldReadChild = true;

                    if($child->nodeName == 'a'){

                        if($this->variables->translate_document){

                            $href = $child->getAttribute("href");

                            $ext = strtolower(pathinfo($href, PATHINFO_EXTENSION));
                            if(strpos($ext,"?") !== false) $ext = substr($ext, 0, strpos($ext,"?"));

                            if(in_array($ext, $this->variables->documentExt)){
                                $this->variables->segments[$href] = $href;
                                $this->collectNode( $child, 'href', $href );
                            }
                        }

                        if($this->variables->translate_links) {
                            $href = $child->getAttribute("href");
                            $pageHost = $this->getPageHost($href);
                            $link = parse_url($href);

                            if ((!$pageHost || $pageHost == $this->variables->site_host) && $link['path'] && $link['path'] != '/') {
                                $this->variables->segments[$link['path']] = $link['path'];
                                $this->variables->links[$link['path']] = $link['path'];
                                $this->collectNode( $child, 'href', $link['path'] );
                            }
                        }

                        $translateAttr = $child->getAttribute("translate");
                        if($translateAttr && $translateAttr == "no"){
                            // no need to walk inside
                            $shouldReadChild = false;
                        }
                    }

                    if( in_array(strtoupper($child->nodeName), $this->variables->siblingsAllowArray) ){

                        if($child->parentNode){
                            if($this->isTextNodeExists($child->parentNode) && $this->allowTranslateWholeTag($child->parentNode) && $this->shouldTranslateWholeTag($child->parentNode)){
                                // no need to walk inside
                                $shouldReadChild = false;
                            }
                        }
                    }

                    if ($child->hasAttribute('class')) {
                        $class = $child->getAttribute("class");
                        if (strpos($class, 'conveythis-no-translate') !== false) {
                            // no need to walk inside
                            $shouldReadChild = false;
                        }
                    }

                    if ($child->hasAttribute('id')) {
                        $idAdminWP = $child->getAttribute("id");
                        if (strpos($idAdminWP, 'wpadminbar') !== false) {
                            // no need to walk inside
                            $shouldReadChild = false;
                        }
                    }

                    foreach ($this->variables->exclusion_block_ids as $exclusionBlockId) {
                        if ($child->hasAttribute('id') && $child->getAttribute("id") == $exclusionBlockId) {
                            // no need to walk inside
                            $shouldReadChild = false;
                            break;
                        }
                    }

                    if( strcasecmp( $child->nodeName, 'script' ) !== 0 && strcasecmp( $child->nodeName, 'style' ) !== 0 && $shouldReadChild == true )
                    {
                        $this->domRecursiveRead( $child );
                    }
                }
            }
        }
    }

    private function collectNode( $item, $attr, $value ) {
        // Add node original value and attribute in list so then we can find the element by its DOM path and replace original content for each element with translation
        $path = $item->getNodePath();
        if ( !isset( $this->nodePathList[$path] ) ) {
            $this->nodePathList[$path] = [];
        }

        $this->nodePathList[$path][$attr]  = $value;
    }

    function replaceSegments( $doc )
    {
        // Get all elements of document
        $xpath = new DOMXPath( $doc );
        $elements = $xpath->query('//text() | //*');

        foreach ( $elements as $el ) {
            // If translate is not allowed don't do anything
            if( $el->nodeType === 1 && $el->hasAttribute( 'translate' ) && trim( $el->getAttribute('translate') ) === 'no' ) {
                continue;
            }
            // Check if there is translation remained for each element
            $node_path = $el->getNodePath();
            if ( isset( $this->nodePathList[$node_path] ) ) {
                foreach ( $this->nodePathList[$node_path] as $attr => $value ) {
                    // If translation is found replace current text or attribute with translation
                    $segment = $this->searchSegment($value);
                    if ($segment) {
                        if ($attr == 'innerHTML') {
                            $el->innerHTML = $segment;
                        } elseif ($attr == 'textContent') {
                            if ($el->parentNode && $el->parentNode->childNodes->length == 1) {
                                $el->parentNode->innerHTML = $segment;
                            }else {
                                $el->textContent = $segment;
                            }
                        } else {
                            $el->setAttribute($attr, $segment);
                        }
                    }
                }
            }

            // Srcset attribute handler
            if ($el->nodeName == 'img' && $this->variables->translate_media) {
                if ($el->hasAttribute("srcset")) {
                    $src_value = parse_url(trim($el->getAttribute('src')));
                    $srcset_value = $el->getAttribute('srcset');
                    $urls = explode(',', $srcset_value);

                    foreach ($urls as &$url) {
                        $srcset_parts = parse_url(trim($url));
                        $width = explode(' ', trim($url))[1];

                        if (isset($srcset_parts['path'])) {
                            $url = str_replace($srcset_parts['path'], $src_value['path'], $url) . ' ' . $width;
                        }
                    }

                    $replaced_srcset = implode(', ', $urls);
                    $el->setAttribute('srcset', $replaced_srcset);
                }
            }

            if ( $el->nodeName == 'a' ) {
                // Replace link url with current language segment
                $href = $el->getAttribute( 'href' );
                if ( !preg_match( '/\/wp-content\//', $href ) ) {
                    $replaced_href = $this->replaceLink( $href, $this->variables->language_code );
                    if ( $replaced_href && $replaced_href !== $href ) {
                        $el->setAttribute( 'href', $replaced_href );
                    }
                }
            }elseif ( $el->nodeName == 'form' ) {
                $action = $el->getAttribute( 'action' );
                $replaced_action = $this->replaceLink( $action, $this->variables->language_code );
                if ( $replaced_action && $replaced_action !== $action ) {
                    $el->setAttribute('action', $replaced_action );
                }
            }elseif ( $el->nodeName == 'article' ) {
                if ( $el->hasAttribute( 'data-permalink' ) ) {
                    $replaced_link = $this->replaceLink( $el->getAttribute( 'data-permalink' ), $this->variables->language_code );
                    if ( $replaced_link ) {
                        $el->setAttribute('data-permalink', $replaced_link );
                    }
                }
            }
        }

        $canonical = $xpath->query("//link[@rel='canonical']");

        if ($canonical->length > 0) {

            $canonicalTag = $canonical->item(0);

            $currentHref = $canonicalTag->getAttribute('href');

            $urlComponents = parse_url($currentHref);

            if(isset($this->variables->language_code))
                $newHref = $urlComponents['scheme'] . '://' . $urlComponents['host'] . '/' . $this->variables->language_code;
            else
                $newHref = $urlComponents['scheme'] . '://' . $urlComponents['host'];


            if (!empty($urlComponents['path'])) {
                $newHref .= $urlComponents['path'];
            }
            if (!empty($urlComponents['query'])) {
                $newHref .= '?' . $urlComponents['query'];
            }

            $canonicalTag->setAttribute('href', $newHref);
        }
        else
        {
            $currentPageUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $urlComponents = parse_url($currentPageUrl);

            $path = isset($urlComponents['path']) ? ltrim($urlComponents['path'], '/') : '';

            if(isset($this->variables->language_code))
                $modifiedUrl = $urlComponents['scheme'] . '://' . $urlComponents['host'] . '/' . $this->variables->language_code . '/' . $path;
            else
                $modifiedUrl = $urlComponents['scheme'] . '://' . $urlComponents['host'] . '/' . $path;

            if (!empty($urlComponents['query'])) {
                $modifiedUrl .= '?' . $urlComponents['query'];
            }

            $head = $doc->getElementsByTagName('head')->item(0);
            $newCanonical = $doc->createElement('link');
            $newCanonical->setAttribute('rel', 'canonical');
            $newCanonical->setAttribute('href', $modifiedUrl);
            $head->appendChild($newCanonical);
        }

        return $doc->saveHTML();
    }

    function domRecursiveApply( $doc, $items )
    {
        foreach( $doc->childNodes as $child )
        {
            if( $child->nodeType === 3 )
            {
                $value = $child->textContent;
                $segment = $this->searchSegment( $value, $items );

                if( !empty( $segment ) )
                {
                    $child->textContent = $segment;
                }
            }
            else
            {
                if( $child->nodeType === 1 )
                {
                    if( $child->hasAttribute( 'title' )  )
                    {

                        $attrValue = $child->getAttribute( 'title' );
                        $segment = $this->searchSegment( $attrValue, $items );

                        if( !empty( $segment ) )
                        {
                            $child->setAttribute( 'title', $segment );
                        }
                    }

                    if( $child->hasAttribute( 'alt' ) )
                    {
                        $attrValue = $child->getAttribute( 'alt' );
                        $segment = $this->searchSegment( $attrValue, $items );

                        if( !empty( $segment ) )
                        {
                            $child->setAttribute( 'alt', $segment );
                        }
                    }

                    if( $child->hasAttribute( 'placeholder' ) )
                    {
                        $attrValue = $child->getAttribute( 'placeholder' );
                        $segment = $this->searchSegment( $attrValue, $items );

                        if( !empty( $segment ) )
                        {
                            $child->setAttribute( 'placeholder', $segment );
                        }
                    }

                    if( $child->hasAttribute( 'type' ) )
                    {
                        $attrValue = trim( $child->getAttribute( 'type' ) );

                        if( strcasecmp( $attrValue, 'submit' ) === 0 || strcasecmp( $attrValue, 'reset' ) === 0 )
                        {
                            if( $child->hasAttribute( 'value' ) )
                            {
                                $attrValue = $child->getAttribute( 'value' );
                                $segment = $this->searchSegment( $attrValue, $items );

                                if( !empty( $segment ) )
                                {
                                    $child->setAttribute( 'value', $segment );
                                }
                            }
                        }
                    }

                    if( strcasecmp( $child->nodeName, 'img' ) === 0 )
                    {
                        if( $child->hasAttribute( 'src' ) )
                        {
                            $metaAttrValue = trim( $child->getAttribute( 'src' ) );

                            if( !empty( $metaAttrValue ) )
                            {
                                if( strpos( $metaAttrValue, '//' ) === false )
                                {
                                    if( strncmp( $metaAttrValue, $this->variables->site_url, strlen( $this->variables->site_url ) ) !== 0 )
                                    {
                                        $newAttrValue = rtrim( $this->variables->site_url, '/' ) . '/' . ltrim( $metaAttrValue, '/' );

                                        $child->setAttribute( 'src', $newAttrValue );
                                    }
                                }
                            }
                        }
                    }

                    if( strcasecmp( $child->nodeName, 'a' ) === 0 )
                    {

                        if( $child->hasAttribute( 'href' ) )
                        {
                            $href = preg_replace('/[^a-zA-Z0-9\-_\/.%:&=?#а-яА-ЯёЁ]/u', '', $child->hasAttribute( 'href' ));

                            $metaAttrValue = trim( $href );

                            if( !empty( $metaAttrValue ) )
                            {
                                if( $metaAttrValue !== '#' )
                                {
                                    if( $child->hasAttribute( 'translate' ) )
                                    {
                                        $metaAttrValue = trim( $child->getAttribute( 'translate' ) );

                                        if( $metaAttrValue === 'no' )
                                        {

                                        }

                                        else
                                        {
                                            $temp = $this->replaceLink( $metaAttrValue, $this->variables->language_code );
                                            $child->setAttribute( 'href', $temp );
                                        }
                                    }

                                    else
                                    {
                                        $temp = $this->replaceLink( $metaAttrValue, $this->variables->language_code );
                                        $child->setAttribute( 'href', $temp );
                                    }
                                }
                            }
                        }
                    }

                    if( strcasecmp( $child->nodeName, 'meta' ) === 0 )
                    {
                        if( $child->hasAttribute( 'name' ) || $child->hasAttribute( 'property' ) )
                        {
                            if( $child->hasAttribute( 'name' ) )
                                $metaAttributeName = trim( $child->getAttribute( 'name' ) );
                            else
                                $metaAttributeName = trim( $child->getAttribute( 'property' ) );

                            if(
                                (
                                    strcasecmp( $metaAttributeName, 'title' ) === 0 ||
                                    strcasecmp( $metaAttributeName, 'twitter:title' ) === 0 ||
                                    strcasecmp( $metaAttributeName, 'og:title' ) === 0
                                )
                                ||
                                (
                                        strcasecmp( $metaAttributeName, 'description' ) === 0 ||
                                        strcasecmp( $metaAttributeName, 'twitter:description' ) === 0 ||
                                        strcasecmp( $metaAttributeName, 'og:description' ) === 0
                                )
                                ||
                                strcasecmp( $metaAttributeName, 'keywords' ) === 0
                            )
                            {
                                if( $child->hasAttribute( 'content' ) )
                                {
                                    $metaAttrValue = $child->getAttribute( 'content' );
                                    $segment = $this->searchSegment( $metaAttrValue, $items );

                                    if( !empty( $segment ) )
                                    {
                                        $child->setAttribute( 'content', $segment );
                                    }
                                }
                            }
                        }
                    }

                    if( strcasecmp( $child->nodeName, 'script' ) !== 0 && strcasecmp( $child->nodeName, 'style' ) !== 0 )
                    {
                        if( $child->hasAttribute( 'translate' ) )
                        {
                            $metaAttrValue = trim( $child->getAttribute( 'translate' ) );

                            if( $metaAttrValue === 'no' )
                            {

                            }

                            else
                            {
                                $this->domRecursiveApply( $child, $items );
                            }
                        }

                        else
                        {
                            $this->domRecursiveApply( $child, $items );
                        }
                    }
                }
            }
        }
    }

    function replaceLink( $value, $language_code )
    {
        $aPos = strpos( $value, '//' );

        if(in_array($value, $this->variables->blockpages)){
            return $value;
        }

        if( $aPos !== false )
        {
            $ePos = strpos( $this->variables->site_url, '//' );
            $aStr = substr( $value, $aPos );
            $eStr = substr( $this->variables->site_url, $ePos );
            $eLen = strlen( $eStr );

            if( strncmp( $aStr, $eStr, $eLen ) !== 0 )
            {
                return $value;
            }
        }

        if (strpos($value, '#') === 0
            || strpos($value, 'mailto:') === 0
            || strpos($value, 'tel:') === 0
            || strpos($value, 'javascript:') === 0) {
            return $value;
        }

        $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
        if(strpos($ext,"?") !== false) $ext = substr($ext, 0, strpos($ext,"?"));

        if(in_array($ext, $this->variables->avoidUrlExt)){
            return $value;
        }
        //

        if (isset($this->variables->target_languages_translations[$language_code])) {
            $language_code = $this->variables->target_languages_translations[$language_code];
        }
        $link = parse_url( $value );

        if (!isset($link['path'])) $link['path'] = '/';

        if( isset($link['path']) && stripos( $link['path'], 'wp-admin' ) === false )
        {
            if( $this->variables->translate_links ) {
                $pageHost = $this->getPageHost( $value );
                if ( ( !$pageHost || $pageHost == $this->variables->site_host ) && $link['path'] && $link['path'] != '/' ) {
                    $segment = $this->searchSegment( $link['path'] );
                    if ( $segment ) {
                        $link['path'] = $segment;
                    }
                }
            }

            $link['path'] = substr_replace( $link['path'], $this->variables->site_prefix . '' . $language_code . '/', 0, strlen( $this->variables->site_prefix ) );


            return $this->unparse_url( $link );
        }

        return $value;
    }

    function unparse_url( $parsed_url )
    {

        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

        $path = preg_replace('/[^a-zA-Z0-9\-_\/.%:&=?#а-яА-ЯёЁ]/u', '', $path);

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    function domLoad( $output )
    {
        $doc = new DOMDocument();

        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;

        libxml_use_internal_errors( true );

        if (extension_loaded('mbstring')) {
            $doc->loadHTML(mb_convert_encoding($output, 'HTML-ENTITIES', 'UTF-8'));
        }else{
            $doc->loadHTML( $output );
        }

        libxml_clear_errors();

        return $doc;
    }

    function searchSegment($value)
    {
        $source_text = html_entity_decode($value);

        $source_text = trim(preg_replace("/\<!--(.*?)\-->/", "", $source_text));

        if (count($this->variables->segments_hash) && !isset($this->variables->segments_hash[md5($source_text)])) {
            return false;
        }

        if (!empty($this->variables->items) && !empty($source_text)) {
            foreach ($this->variables->items as $item) {
                $source_text2 = isset($item['source_text']) ? html_entity_decode($item['source_text']) : '';
                if (strcmp($source_text, trim($source_text2)) === 0) {
                    return str_replace($source_text, $item['translate_text'], $source_text);
                }
            }

            if (!extension_loaded('mbstring')) {
                $sourceLower = iconv('UTF-8', 'utf-8//TRANSLIT//IGNORE', $source_text);
            } else {
                $sourceLower = mb_strtolower($source_text, 'UTF-8');
            }

            $source_text = trim($sourceLower);
            foreach ($this->variables->items as $item) {
                $source_text2 = isset($item['source_text']) ? html_entity_decode($item['source_text']) : '';

                if (!extension_loaded('mbstring')) {
                    $source2Lower = iconv('UTF-8', 'utf-8//TRANSLIT//IGNORE', $source_text2);
                } else {
                    $source2Lower = mb_strtolower($source_text2, 'UTF-8');
                }

                if (strcmp($source_text, trim($source2Lower)) === 0) {
                    return str_replace($source_text, $item['translate_text'], $source_text);
                }
            }

            foreach ($this->variables->items as $item) {
                $source_text2 = isset($item['source_text']) ? html_entity_decode($item['source_text']) : '';
                if (!extension_loaded('mbstring')) {
                    $source2Lower = iconv('UTF-8', 'utf-8//TRANSLIT//IGNORE', $source_text2);
                } else {
                    $source2Lower = mb_strtolower($source_text2, 'UTF-8');
                }


                if (strcmp($source_text, strip_tags($source2Lower)) === 0) {
                    return str_replace($source_text, $item['translate_text'], $source_text);
                }
            }
        }
    }

    function is_wordpress_url($url) {

        foreach ($this->variables->wp_patterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }

        return false;

    }

    private function checkRequestURI() {

        if (is_array($this->variables->system_links) && isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];

            foreach ($this->variables->system_links as $system_link) {
                if (isset($system_link['link']) && $system_link['link'] == $requestUri) {
                    return false;
                }
            }
        }

        return true;
    }

    function _translatePage($content){

        if(
            $this->checkRequestURI()
            &&
            (
                is_404() ||
                $this->is_wordpress_url($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])
            )
        )
        {
            return $content;
        }

        if( !is_admin() && !empty($this->variables->language_code) && !empty($content) )
        {
            if( extension_loaded('xml') )
            {
                $scriptContainer = [];
                /* strip all JS content */
                $content = preg_replace_callback ("#<script([^>]*)>(.*?)</script>#s", function ($matches) use (&$scriptContainer) {
                    $scriptContainer[md5($matches[2])] = $matches[2];
                    return "<script".$matches[1].">".md5($matches[2])."</script>";
                }, $content);
                /* ----- */

                require_once 'JSLikeHTMLElement.php';

                $doc = $this->domLoad( $content );

                $doc->registerNodeClass('DOMElement', 'JSLikeHTMLElement');

                $language = $this->searchLanguage( $this->variables->language_code );
                if ( isset( $language['rtl'] ) && $this->variables->change_direction && $doc->documentElement )
                {
                    $doc->documentElement->setAttribute('dir', 'rtl' );
                }

                $content = $doc->saveHTML();

                if($this->variables->plan != 'free'){

                    $this->domRecursiveRead( $doc );

                    sort( $this->variables->segments );

                    $update_cache =
                        isset( $_POST['action'] )
                        && $_POST['action'] == 'conveythis_update_cache' ? true : false;

                    $cacheKey = md5(serialize(array_merge($this->variables->segments,$this->variables->links,[$this->variables->referrer])));
                    $this->variables->items = $this->ConveyThisCache->get_cached_translations($this->variables->source_language, $this->variables->language_code, $this->variables->referrer, $cacheKey);

                    $this->variables->segments = $this->filterSegments($this->variables->segments);

                    if (!empty($this->variables->items) && !$this->allowCache($this->variables->items)) {
                        $this->ConveyThisCache->clear_cached_translations(false, $this->variables->referrer, $this->variables->source_language, $this->variables->language_code);
                    }

                    if (empty($this->variables->items)) {
                        for ($i = 1; $i <= 3; $i++) {

                            $response = $this->send('POST', '/website/translate/', [
                                'referrer' => $this->variables->referrer,
                                'source_language' => $this->variables->source_language,
                                'target_language' => $this->variables->language_code,
                                'segments' => $this->variables->segments,
                                'links' => $this->variables->links
                            ], true);

                            if (isset($response['error'])) {
                                if (!$update_cache) {
                                    header('Location: ' . $this->variables->referrer, true, 302);
                                    exit();
                                }
                                break;
                            }

                            if (!empty($response)) {

                                if (!empty($this->variables->segments)){

                                    $new_response = array();
                                    $this_segments = $this->variables->segments;

                                    foreach ($response as $response_val){
                                        foreach ($this_segments as $segments_val){
                                            if (!empty($response_val["source_text"]) and !empty($segments_val) and $this->comparisonSegments($response_val["source_text"], $segments_val))
                                                $new_response[] = $response_val;
                                        }
                                    }
                                }

                                if (!empty($new_response)) $response = $new_response;

                                $this->variables->items = $response;

                                break;
                            }
                        }

                        if ($this->allowCache($this->variables->items)) {
                            $this->ConveyThisCache->save_cached_translations(
                                $this->variables->source_language,
                                $this->variables->language_code,
                                $this->variables->referrer,
                                $this->variables->items,
                                $cacheKey
                            );
                        }

                        $clearUrl = $this->getTranslateSiteUrl($this->variables->referrer, $this->variables->language_code);
                        ConveyThisCache::clearPageCache($clearUrl, null);

                        if ( $update_cache ) {
                            return json_encode( array('success' => true ) );
                        }
                    }
                }

                foreach ($this->variables->segments as $segment) {
                    $source_text = trim(preg_replace("/\<!--(.*?)\-->/", "", html_entity_decode($segment)));
                    $this->variables->segments_hash[md5($source_text)] = 1;
                }

                $content = $this->replaceSegments( $doc );
                // return JS content
                $content = strtr($content, $scriptContainer);
                $content = html_entity_decode($content, ENT_HTML5, 'UTF-8');
            }
        }

        return  $content;
    }

    function filterSegments($segments)
    {
        $res = [];

        if ($segments && is_array($segments)) {
            foreach ($segments as $segment) {
                if (preg_match('/\p{L}/u', $segment)) {
                    $res[] = $segment;
                }
            }
        }
        return $res;
    }

    function allowCache($items)
    {
        return count($items) == count($this->variables->segments) ? true : false;
    }

    function comparisonSegments($response_value, $segments_value)
    {
        $source_text = html_entity_decode($segments_value);
        $source_text = trim(preg_replace("/\<!--(.*?)\-->/", "", $source_text));

        $source_text2 = html_entity_decode($response_value);

        if (strcmp($source_text, trim($source_text2)) === 0) {
            return true;
        }

        if (!extension_loaded('mbstring')) {
            $sourceLower = iconv('UTF-8', 'utf-8//TRANSLIT//IGNORE', $source_text);
        } else {
            $sourceLower = mb_strtolower($source_text, 'UTF-8');
        }

        $source_text = trim($sourceLower);

        $source_text2 = html_entity_decode($response_value);

        if (!extension_loaded('mbstring')) {
            $source2Lower = iconv('UTF-8', 'utf-8//TRANSLIT//IGNORE', $source_text2);
        } else {
            $source2Lower = mb_strtolower($source_text2, 'UTF-8');
        }

        if (strcmp($source_text, trim($source2Lower)) === 0) {
            return true;
        }

        $source_text2 = html_entity_decode($response_value);

        if (!extension_loaded('mbstring')) {
            $source2Lower = iconv('UTF-8', 'utf-8//TRANSLIT//IGNORE', $source_text2);
        } else {
            $source2Lower = mb_strtolower($source_text2, 'UTF-8');
        }

        if (strcmp($source_text, strip_tags($source2Lower)) === 0) {
            return true;
        }

        return false;
    }

    static function customLogs($message) {
        $uploads  = wp_upload_dir(null, false);
        $logs_dir = $uploads['basedir'] . '/conveythis';
        $log_file = $logs_dir . '/' . 'log.log';

        if (!is_dir($logs_dir)) {
            mkdir($logs_dir, 0755, true);
        }

        if (file_exists($log_file) && filesize($log_file) > 20 * 1024 * 1024) {
            unlink($log_file);
        }

        $file = fopen($log_file, 'a');
        fwrite($file, date('Y-m-d h:i:s') . " :: " . print_r($message, true) . PHP_EOL);
        fclose($file);
    }


    private function updateRules($rules, $type) {

        if (is_string($rules)) {
            $rules = json_decode($rules, true);
        }

        if ($type == 'exclusion')
        {
            $this->send('POST', '/admin/account/domain/pages/excluded/', array(
                'referrer' => '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                'rules' => $rules
            ));
        }
        elseif ($type == 'glossary')
        {
            $this->send('POST', '/admin/account/domain/pages/glossary/', array(
                'referrer' => '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                'rules' => $rules
            ));
        }
        elseif ($type == 'exclusion_blocks')
        {
            $this->send('POST', '/admin/account/domain/excluded/blocks/', array(
                'referrer' => '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                'blocks' => $rules
            ));
        }

    }

    private function send($request_method = 'GET', $request_uri = '', $query = [], $return_error = false)
    {
        $headers = [
            'X-Api-Key' => $this->variables->api_key
        ];
        if (count($query)) {
            $headers['Content-Type'] = 'application/json; charset=UTF-8';
        }
        if (strpos($request_uri, '/admin/') === 0) {
            $headers['X-Auth-Token'] = API_AUTH_TOKEN;
        }

        $args = [
            'headers' => $headers,
            'body' => count($query) ? json_encode($query) : null,
            'method' => $request_method,
            'redirection' => '10',
            'httpversion' => '1.1',
            'blocking' => true,
            'cookies' => []
        ];



        $response = $this->httpRequest( $request_uri, $args, true , $this->variables->select_region );

        if(!is_array($response)) {
            return [];
        }

        $body = $response['body'];
        $code = $response['response']['code'];

        if( !empty( $body ) )
        {
            $data = json_decode( $body, true );

            if( !empty( $data ) )
            {
                if( $data['status'] == 'success' )
                {
                    return $data['data'];
                }
                else if($data['status'] == 'error'){
                    if ( $return_error )
                    {
                        return ['error' => $data['message']];
                    }
                    return [];
                }
                else
                {
                    if( !empty( $data['message'] ) )
                    {

                        if( is_admin() )
                        {

                            if (!function_exists('add_settings_error')) {
                                include_once(ABSPATH . 'wp-admin/includes/template.php');
                            }

                            $message = esc_html__( $data['message'], 'conveythis-translate' );

                            if( strpos( $message, '#' ) )
                            {
                                $message = str_replace( '#', '<a target="_blank" href="https://www.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress">' . __( 'change plan', 'conveythis-translate' ) . '</a>', $message );
                            }

                            add_settings_error( 'conveythis-translate', '501', $message, 'error' );
                        }
                    }
                }
            }
        }
        return null;
    }

    private static function httpRequest( $url, $args = [], $proxy = true, $region = 'US' ) {

        $args['timeout'] = 1;
        $response = [];
        $proxyApiURL = ($region == 'EU' && !empty(CONVEYTHIS_API_PROXY_URL_FOR_EU)) ? CONVEYTHIS_API_PROXY_URL_FOR_EU : CONVEYTHIS_API_PROXY_URL;

        if( $proxy )
            $response = wp_remote_request( $proxyApiURL. $url, $args );

        if( is_wp_error( $response ) || empty($response) || empty( $response['body'] ))
        {
            $args['timeout'] = 30;
            $response = wp_remote_request( CONVEYTHIS_API_URL. $url, $args );
        }

        return $response;
    }

    private function find_translation($slug, $source_language, $target_language, $referer) {
        $response = $this->send('POST', '/website/find-translation/', array(
            'referrer' => $referer,
            'source_language' => $source_language,
            'target_language' => $target_language,
            'segments' => [$slug]
        ));
        if (count($response)) {
            return $response[0]['translate_text'];
        }
        return false;
    }

    private function find_original_slug($slug, $source_language, $target_language, $referer) {
        $original_slug = $this->ConveyThisCache->get_cached_slug($slug, $target_language, $source_language);

        if (!$original_slug) {
            $response = $this->send('POST', '/website/find-translation-source/', array(
                'referrer' => $referer,
                'source_language' => $source_language,
                'target_language' => $target_language,
                'segments' => [$slug]
            ));

            if (count($response)) {
                $original_slug = $response[0]['source_text'];
                if ( $original_slug ) {
                    $this->ConveyThisCache->save_cached_slug($slug, $target_language, $source_language, $original_slug);
                }
            }
        }
        return $original_slug;
    }

    private function getTranslateSiteUrl($path, $targetLanguage = '')
    {
        $translateUrl = '';
        if (strlen($path) > 0 && strlen($targetLanguage) > 0) {
            $pageUrl = trim($path);
            $pageUrl = str_replace($this->variables->site_url, '', $pageUrl);
            $pageUrl = str_replace($this->variables->site_prefix, '', $pageUrl);
            $pageUrl = str_replace($this->variables->site_host, '', $pageUrl);
            $pageUrl = str_replace('//', '/', $pageUrl);
            $translateUrl = $this->variables->site_url . '/' . $targetLanguage . $pageUrl;
        }
        return $translateUrl;
    }



    public function get_conveythis_shortcode(){

        $this->variables->shortcode_counter++;
        return '<div id="conveythis_widget_placeholder_'.$this->variables->shortcode_counter.'" class="conveythis_widget_placeholder"></div>';
    }

    public static function Instance()
    {
        if( self::$instance === null )
        {
            self::$instance = new ConveyThis();
        }
        return self::$instance;
    }

    public static function getCurrentDomain()
    {
        return str_ireplace('www.', '', parse_url(get_site_url(), PHP_URL_HOST));
    }

    public static function plugin_activate()
    {

        $defaultTargetLng = 'en';
        $lng = explode("_", (get_locale()));
        if(is_array($lng) && isset($lng[0]) && strlen($lng[0]) == 2){
            $defaultTargetLng = $lng[0];
        }

        add_option( 'api_key', '' );
        add_option( 'conveythis_new_user', '1' );
        add_option( 'source_language', $defaultTargetLng );
        add_option( 'target_languages', [] );
        add_option( 'target_languages_translations', [] );
        add_option( 'style_change_language', [] );
        add_option( 'style_change_flag', [] );
        add_option( 'style_flag', 'rect' );
        add_option( 'style_text', 'full-text' );
        add_option( 'style_position_vertical', 'bottom' );
        add_option( 'style_position_horizontal', 'left' );
        add_option( 'style_indenting_vertical', '12' );
        add_option( 'style_indenting_horizontal', '24' );
        add_option( 'auto_translate', '0' );
        add_option( 'hide_conveythis_logo', '0' );
        add_option( 'translate_media', '0' );
        add_option( 'translate_document', '0' );
        add_option( 'translate_links', '0' );
        add_option( 'no_translate_element_id', '' );
        add_option( 'change_direction', '0' );
        add_option( 'alternate', '1' );
        add_option( 'accept_language', '0' );
        add_option( 'blockpages', [] );
        add_option( 'show_javascript', '1' );
        add_option( 'mb_admin_notice', [] );
        add_option( 'style_position_type', 'fixed' );
        add_option( 'style_position_vertical_custom', 'bottom' );
        add_option( 'style_selector_id', '' );
        add_option( 'conveythis_lang_code_url', '1' );
        add_option( 'conveythis_clear_cache', '0' );
        add_option( 'conveythis_select_region', 'US' );

        add_option( 'url_structure', 'regular' );

        add_option( 'style_background_color', '#ffffff' );
        add_option( 'style_hover_color', '#f6f6f6' );
        add_option( 'style_border_color', '#e0e0e0' );
        add_option( 'style_text_color', '#000000' );
        add_option( 'style_corner_type', 'cir' );
        add_option( 'style_widget', 'dropdown' );
        add_option( 'conveythis_system_links', [] );

        self::sendEvent('activate');

    }

    public static function plugin_deactivate()
    {
        self::sendEvent('deactivate');
    }

    public static function plugin_uninstall()
    {
        delete_option( 'api_key' );
        delete_option( 'conveythis_new_user' );
        delete_option( 'source_language' );
        delete_option( 'target_languages' );
        delete_option( 'target_languages_translations' );
        delete_option( 'style_change_language' );
        delete_option( 'style_change_flag' );
        delete_option( 'style_flag' );
        delete_option( 'style_text' );
        delete_option( 'style_position_vertical' );
        delete_option( 'style_position_horizontal' );
        delete_option( 'style_indenting_vertical' );
        delete_option( 'style_indenting_horizontal' );
        delete_option( 'auto_translate' );
        delete_option( 'hide_conveythis_logo' );
        delete_option( 'translate_media' );
        delete_option( 'translate_document' );
        delete_option( 'translate_links' );
        delete_option( 'no_translate_element_id' );
        delete_option( 'change_direction' );
        delete_option( 'alternate' );
        delete_option( 'accept_language' );
        delete_option( 'blockpages' );
        delete_option( 'show_javascript' );
        delete_option( 'mb_admin_notice' );
        delete_option( 'style_position_type');
        delete_option( 'style_position_vertical_custom');
        delete_option( 'style_selector_id');
        delete_option( 'url_structure');
        delete_option( 'conveythis_lang_code_url' );
        delete_option( 'conveythis_clear_cache' );
        delete_option( 'conveythis_select_region' );

        delete_option( 'style_background_color');
        delete_option( 'style_hover_color');
        delete_option( 'style_border_color');
        delete_option( 'style_text_color');
        delete_option( 'style_corner_type');
        delete_option( 'style_widget');
        delete_option( 'conveythis_system_links');

        self::sendEvent('uninstall');
    }

    static function plugin_update_option($optionName, $oldValue, $newValue)
    {

        self::optionPermalinkChanged($optionName, $oldValue, $newValue);

        $pluginOption = false;
        $eventName = 'updOption';
        if(!empty($optionName)){
            if ($optionName == 'api_key') {
                $eventName .= self::getEventOptionName('ApiKey', $oldValue, $newValue);
                $pluginOption = true;
            }

            if ($optionName == 'source_language') {
                $eventName .= self::getEventOptionName('SourceLanguage', $oldValue, $newValue);
                $pluginOption = true;
            }

            if ($optionName == 'target_languages') {
                $eventName .= self::getEventOptionName('TargetLanguage', $oldValue, $newValue);
                $pluginOption = true;
            }
        }

        if($pluginOption){
            self::sendEvent($eventName);
        }

    }

    static function optionPermalinkChanged($option, $oldValue, $value)
    {
        if ($option === 'permalink_structure') {
            delete_transient('convey_permalink_structure');
        }
    }

    static function getEventOptionName($name = '', $oldValue = '', $newValue = '')
    {
        $eventName = '';
        if (empty($oldValue) && !empty($newValue)) {
            $eventName .= 'First';
        }
        if (!empty($oldValue) && !empty($newValue)) {
            $eventName .= 'Update';
        }
        $eventName .= $name;

        return $eventName;
    }

    public static function redirect_after_activate($plugin)
    {
        if( $plugin == "conveythis-translate/index.php" ) {
            // Don't forget to exit() because wp_redirect doesn't exit automatically
            exit(wp_redirect(admin_url('options-general.php?page=convey_this')));
        }
    }

    public static function sendEvent($event = 'default', $message = '')
    {
        $key = get_option('api_key') ? get_option('api_key') : 'no_key';
        $response = self::httpRequest('/25/background/event/' . $key . '/' . base64_encode(self::getCurrentDomain()) . '/' . $event . '/');
    }

    function dismissNotice($function)
    {
        $metaName = 'convey_meta';
        $userMeta = get_user_meta(get_current_user_id(), $metaName, true);
        $userMeta = array_unique(array_filter(array_merge((array)$userMeta, [$function])));
        update_user_meta(get_current_user_id(), $metaName, $userMeta);
        delete_transient($function);
    }

    public function isDismiss($function)
    {
        $isDismiss = false;
        if (!empty($function)) {
            $userMeta = get_user_meta(get_current_user_id(), 'convey_meta', true);
            if (in_array($function, (array)$userMeta, true)) {
                $isDismiss = true;
            }
        }
        return $isDismiss;
    }

    public function getWidgetStyles(){
        return $this->variables->widgetStyles;
    }

    public static function modify_admin_bar($wp_admin_bar)
    {

        if (!is_admin_bar_showing()) {
            return;
        }

        if (!($wp_admin_bar instanceof WP_Admin_Bar)) {
            return;
        }

        $class_to_add = 'conveythis-no-translate';

        $nodes = $wp_admin_bar->get_nodes();

        if (empty($nodes)) {
            return;
        }

        foreach ($nodes as $node) {

            if (!is_object($node)) {
                continue;
            }

            $args = $node;

            if (is_array($args->meta)) {
                $args->meta['class'] = empty($args->meta['class'])
                    ? $class_to_add
                    : (strpos($args->meta['class'], $class_to_add) === false
                        ? $args->meta['class'] . ' ' . $class_to_add
                        : $args->meta['class']);

                try
                {
                    $wp_admin_bar->add_node($args);
                }
                catch (Exception $e)
                {
                    ConveyThis::customLogs("Function modify_admin_bar:\n" . $e);
                }
            }

        }

    }

}