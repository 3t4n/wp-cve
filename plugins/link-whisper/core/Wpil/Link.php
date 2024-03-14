<?php

/**
 * Work with links
 */
class Wpil_Link
{
    static $url_redirect_cache = array();

    /**
     * Register services
     */
    public function register()
    {
        add_action('wp_ajax_wpil_get_link_title', ['Wpil_Link', 'getLinkTitle']);
    }

    /**
     * Check if link is internal
     *
     * @param $url
     * @return bool
     */
    public static function isInternal($url)
    {
        // it's internal if there are no protocol slashes or the first char is a single slash
        if (strpos($url, '//') === false || 0 === strpos($url, '/')) {
            return true;
        }

        if (self::markedAsExternal($url)) {
            return false;
        }

        if(self::isAffiliateLink($url)){
            return false;
        }

        $localhost = parse_url(get_home_url(), PHP_URL_HOST);
        $host = parse_url($url, PHP_URL_HOST);

        if (!empty($localhost) && !empty($host)) {
            $localhost = str_replace('www.', '', $localhost);
            $host = str_replace('www.', '', $host);
            if ($localhost == $host) {
                return true;
            }

            $internal_domains = Wpil_Settings::getInternalDomains();

            if(in_array($host, $internal_domains, true)){
                return true;
            }
        }

        return false;
    }

    /**
     * Checks to see if the url can be traced to a post.
     * The main idea is to cut down on the number of external links that are sent through the URL-to-post functionality,
     * so this doesn't have to be an exhaustive search with complete validation.
     * 
     * @param string $url The url to check
     * @return bool Is the url one that we think we can trace?
     **/
    public static function is_traceable($url = ''){
        if(empty($url)){
            return false;
        }

        // clean up the url a little bit for consistent searching
        $url = str_replace('www.', '', $url);

        $host = parse_url($url, PHP_URL_HOST);

        // if there's no host
        if(empty($host)){
            // most likely it's traceable because it _should_ be relative
            return true;
        }

        $localhost = parse_url(get_home_url(), PHP_URL_HOST);

        // if the host matches localhost
        if(empty($localhost) || $localhost === $host || $host === str_replace('www.', '', $localhost)){
            // it is tracable
            return true;
        }

        /* return false if:
            * there's is a host
            * it doesn't match the home site's
            * and trying to filter it doesn't work
        */
        return false;
    }

    /**
     * Checks if the url is a known cloaked affiliate link.
     * 
     * @param string $url The url to be checked
     * @return bool Whether or not the url is to a cloaked affiliate link. 
     **/
    public static function isAffiliateLink($url){
        // if ThirstyAffiliates is active
        if(class_exists('ThirstyAffiliates')){
            $links = self::getThirstyAffiliateLinks();

            if(isset($links[$url])){
                return true;
            }
        }

        return false;
    }

    /**
     * Get link title by URL
     */
    public static function getLinkTitle()
    {
        Wpil_Base::verify_nonce('wpil_suggestion_nonce');
        if(!is_admin()){
            die();
        }

        $link = !empty($_POST['link']) ? esc_url_raw(trim($_POST['link'])): '';
        $title = '';
        $id = '';
        $type = '';
        $date = __('Not Available', 'wpil');

        if ($link) {
            if (self::isInternal($link)) {
                $post = Wpil_post::getPostByLink($link);
                if(!empty($post) && isset($post->type)){
                    $title = $post->getTitle();
                    $link = $post->getSlug();
                    $id = $post->id;
                    $type = $post->type;

                    if($post->type === 'post'){
                        $date = get_the_date(get_option('date_format', 'F j, Y'), $post->id);
                    }
                }
            }else{
                $title = __('External Page URL', 'wpil');
            }

            wp_send_json([
                'title' => esc_html($title),
                'link' => esc_url_raw($link),
                'id' => $id,
                'type' => $type,
                'date' => $date
            ]);
        }

        die;
    }

    /**
     * Remove class "wpil_internal_link" from links
     */
    public static function removeLinkClass()
    {
        global $wpdb;

        $wpdb->get_results("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, 'wpil_internal_link', '') WHERE post_content LIKE '%wpil_internal_link%'");
    }

    /**
     * Clean link from trash symbols
     *
     * @param $link
     * @return string
     */
    public static function clean($link)
    {
        $link = str_replace(['http://', 'https://', '//www.'], '//', strtolower(trim($link)));
        if (substr($link, -1) == '/') {
            $link = substr($link, 0, -1);
        }

        return $link;
    }

    /**
     * Check if link was marked as external
     *
     * @param $link
     * @return bool
     */
    public static function markedAsExternal($link)
    {
        $external_links = Wpil_Settings::getMarkedAsExternalLinks();

        if (in_array($link, $external_links)) {
            return true;
        }

        foreach ($external_links as $external_link) {
            if (substr($external_link, -1) == '*' && strpos($link, substr($external_link, 0, -1)) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks to see if the supplied text contains a link.
     * The check is pretty simple at this point, just seeing if the form of an opening tag or a closing tag is present in the text
     * 
     * @param string $text
     * @return bool
     **/
    public static function hasLink($text = '', $replace_text = ''){

        // if there's no link anywhere to be seen, return false
        if(empty(preg_match('/<a [^><]*?(href|src)[^><]*?>|<\/a>/i', $text))){
            return false;
        }

        // if there is a link in the replace text, return true
        if(preg_match('/<a [^><]*?(href|src)[^><]*?>|<\/a>/i', $replace_text)){
            return true;
        }

        // if there is a link, see if it ends before the replace text
        $replace_start = mb_strpos($text, $replace_text);
        if(preg_match('/<\/a>/i', mb_substr($text, 0, $replace_start)) ){
            // if it does, no worries!
            return false;
        }elseif(preg_match('/<a [^><]*?(href|src)[^><]*?>/i', mb_substr($text, 0, $replace_start)) || preg_match('/<\/a>/i', mb_substr($text, $replace_start)) ){
            // if there's an opening tag before the replace text or somewhere after the start, then presumably the replace text is in the middle of a link
            return true;
        }

        return false;
    }


    /**
     * Checks to see if the supplied text contains a heading tag.
     * The check is pretty simple at this point, just seeing if the form of an opening tag or a closing tag is present in the text
     * 
     * @param string $text
     * @return bool
     **/
    public static function hasHeading($text = '', $replace_text = '', $sentence = ''){
        // if there's no heading anywhere to be seen, return false
        if(empty(preg_match('/<h[1-6][^><]*?>|<\/h[1-6]>|&lt;h[1-6]( |&gt;)|&lt;\/h[1-6]&gt;/i', $text))){
            return false;
        }

        // if there is a heading, see if it ends before the replace text
        $replace_start = mb_strpos($text, $sentence);
        if(preg_match('/<\/h[1-6]>|&lt;\/h[1-6]&gt;/i', mb_substr($text, 0, $replace_start)) ){
            // if it does, no worries!
            return false;
        }elseif(preg_match('/<h[1-6][^><]*?>|&lt;h[1-6].*?&gt;/i', mb_substr($text, 0, $replace_start)) || (preg_match('/<\/h[1-6]>|&lt;\/h[1-6]&gt;/i', mb_substr($text, $replace_start)) && !preg_match('/<h[1-6][^><]*?>|&lt;h[1-6].*?&gt;/i', mb_substr($text, $replace_start)) ) ){
            // if there's an opening tag before the replace text or somewhere after the start, then presumably the replace text is in the middle of a heading
            return true;
        }

        // if there is a heading in the replace text, return true
        if(substr_count($replace_text, $sentence) > 1 && preg_match('/<h[1-6][^><]*?>|<\/h[1-6]>|&lt;h[1-6].*?&gt;|&lt;\/h[1-6]&gt;/i', $replace_text)){
            return true;
        }

        return false;
    }

    /**
     * Checks to see if the supplied text contains a script tag.
     * The check is pretty simple at this point, just seeing if the form of an opening tag or a closing tag is present in the text
     * 
     * @param string $text
     * @return bool
     **/
    public static function hasScript($text = '', $replace_text = '', $sentence = ''){
        // if there's no script tag anywhere to be seen, return false
        if(empty(preg_match('/<script[^><]*?>|<\/script>|&lt;script( |&gt;)|&lt;\/script&gt;/i', $text))){
            return false;
        }

        // if there is a script tag, see if it ends before the replace text
        $replace_start = mb_strpos($text, $sentence);
        if(preg_match('/<\/script>|&lt;\/script&gt;/i', mb_substr($text, 0, $replace_start)) ){
            // if it does, no worries!
            return false;
        }elseif(preg_match('/<script[^><]*?>|&lt;script.*?&gt;/i', mb_substr($text, 0, $replace_start)) || (preg_match('/<\/script>|&lt;\/script&gt;/i', mb_substr($text, $replace_start)) && !preg_match('/<script[^><]*?>|&lt;script.*?&gt;/i', mb_substr($text, $replace_start)) ) ){
            // if there's an opening tag before the replace text or somewhere after the start, then presumably the replace text is in the middle of a script section
            return true;
        }

        // if there is a script tag in the replace text, return true
        if(substr_count($replace_text, $sentence) > 1 && preg_match('/<script[^><]*?>|<\/script>|&lt;script.*?&gt;|&lt;\/script&gt;/i', $replace_text)){
            return true;
        }

        return false;
    }

    public static function remove_all_links_from_text($text = ''){
        if(empty($text)){
            return $text;
        }

        $text = preg_replace('/<a[^>]+>(.*?)<\/a>/', '$1', $text);

        return $text;
    }

    /**
     * Gets all ThirstyAffiliate links in an array keyed with the urls.
     * Caches the results to save processing time later
     **/
    public static function getThirstyAffiliateLinks(){
        global $wpdb;
        $links = get_transient('wpil_thirsty_affiliate_links');

        if(empty($links)){
            // query for the link posts
            $results = $wpdb->get_col("SELECT `ID` FROM {$wpdb->posts} WHERE `post_type` = 'thirstylink'");

            // store a flag if there are no link posts
            if(empty($results)){
                set_transient('wpil_thirsty_affiliate_links', 'no-links', 5 * MINUTE_IN_SECONDS);
                return array();
            }

            // get the urls to the link posts
            $links = array();
            foreach($results as $id){
                $links[] = get_permalink($id);
            }

            // flip the array for easy searching
            $links = array_flip($links);

            // store the results
            set_transient('wpil_thirsty_affiliate_links', $links, 5 * MINUTE_IN_SECONDS);

        }elseif($links === 'no-links'){
            return array();
        }

        return $links;
    }

    /**
     * Checks to see if the supplied text is base64ed.
     * @param string $text The text to check if base64 encoded.
     * @param bool $skip_decode Should we skip the decoding check? Compressed data fails the check, so we need to skip it if the data could be gz-compressed.
     * @return bool True if the text is base64 encoded, false if the string is empty or not encoded
     **/
    public static function checkIfBase64ed($text = '', $skip_decode = false){
        if(empty($text) || !is_string($text)){
            return false;
        }
        $possible = preg_match('`^([A-Za-z0-9+/]{4})*?([A-Za-z0-9+/]{3}=|[A-Za-z0-9+/]{2}==)??$`', $text);

        if($possible === 0){
            return false;
        }

        if($skip_decode || !empty(mb_detect_encoding(base64_decode($text)))){
            return true;
        }

        return false;
    }

    /**
     * Checks if the link is relative
     * 
     * @param string $link
     **/
    public static function isRelativeLink($link = ''){
        if(empty($link) || empty(trim($link))){
            return false;
        }

        if(strpos($link, 'http') === false && substr($link, 0, 1) === '/'){
            return true;
        }

        // parse the URL to see if it only contains a path
        $parsed = wp_parse_url($link);
        if( !isset($parsed['host']) && 
            !isset($parsed['scheme']) && 
            isset($parsed['path']) && !empty($parsed['path'])
        ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * De-translates relative links so we can track the urls to the post they're pointing to.
     * @param string $link The url that we're going to de-translate if possible
     * @return string Returns the de-translated link if we're successful and the original link if we're not
     **/
    public static function clean_translated_relative_links($link = ''){
        
        // if WPML is active and this is a relative permalink
        if(Wpil_Settings::wpml_enabled() && self::isRelativeLink($link)){
            // get the WPML main class
            global $sitepress;

            // if we can check the settings
            if(!empty($sitepress) && method_exists($sitepress, 'get_setting')){
                // find out how we're differnetiating the languages in URLs
                $set = $sitepress->get_setting('language_negotiation_type');
                // if we're doing it with directories ("/en/testing-post")
                if($set === '1'){
                    // grab the first directory since that should be the language directory
                    $bits = explode('/', trim($link, '/'));
                    $dir = isset($bits[0]) ? $bits[0]: false;

                    // if we've got a dir & its for a WPML locale
                    if(Wpil_Settings::is_supported_wpml_local($dir)){
                        // remove the dir from the link
                        $new_link = mb_substr(ltrim($link, '/'), mb_strlen($dir));
                        $link = (0 !== mb_strpos($link, '/')) ? ltrim($new_link, '/'): $new_link;
                    }
                }
            }
        }

        return $link;
    }

    /**
     * Gets the applied url redirect for the given URL.
     * Cleans up the link a little to remove query parameters.
     * @TODO: add regex url chasing support. Since this is checking redirect status of specific link, we could actually apply the rules here
     * @param string $url The url to check
     * @return string|bool Returns the redirected URL if a redirect is active, and FALSE if there's no redirect
     **/
    public static function get_url_redirection($url = ''){
        if(empty($url)){
            return false;
        }

        // return false if there are no redirects active
        if(null === self::$url_redirect_cache){
            return false;
        }elseif(empty(self::$url_redirect_cache)){
            self::$url_redirect_cache = Wpil_Settings::getRedirectionUrls();
        }

        // if the url is being redirected
        if(isset(self::$url_redirect_cache[$url])){
            // return the redirect location
            return self::$url_redirect_cache[$url];
        }

        // if that didn't work, try cleaning up the url a bit to see if that makes the difference
        $url = trailingslashit(strtok($url, '?#'));
        
        // if that works
        if(isset(self::$url_redirect_cache[$url])){
            // return the redirect location
            return self::$url_redirect_cache[$url];
        }

        // otherwise, the url is not being redirected as far as we can tell
        return false;
    }

    /**
     * Checks if the given URL points to the standard "home" locations
     **/
    public static function url_points_home($url = ''){
        if(empty($url)){
            return false;
        }

        // trim the url just in case there's a trailing whitespace or somthing
        $url = trim($url);

        // if the url is pointing to the site root
        if($url === '/'){
            return true;
        }

        // make sure the url is slashed for consistency
        $url = trailingslashit($url);

        // if the url is pointing to the home_url
        if(trailingslashit(get_home_url()) === $url){
            return true;
        }

        // if the url is pointing to the home_url
        if(trailingslashit(get_site_url()) === $url){
            return true;
        }

        // if we haven't caught it, the url probably isn't pointing to the site home url
        return false;
    }
}
