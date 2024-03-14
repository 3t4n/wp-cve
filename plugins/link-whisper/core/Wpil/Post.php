<?php

/**
 * Work with post
 */
class Wpil_Post
{
    public static $advanced_custom_fields_list = null;
    public static $post_types_without_editors = array(
        'web-story'
    );
    public static $post_url_cache = array();

    /**
     * Register services
     */
    public function register()
    {
        add_action('draft_to_published', [$this, 'updateStatMark'], 99999);
        add_action('save_post', [$this, 'updateStatMark'], 99999);
        add_action('before_delete_post', [$this, 'deleteReferences']);
        add_filter('wp_link_query_args', array(__CLASS__, 'filter_custom_link_post_types'), 10, 1);
        add_filter('wp_link_query', array(__CLASS__, 'custom_link_category_search'), 10, 2);
    }

    /**
     * Filters the post types that the custom link search box will look for so the user is only shown selected post types
     **/
    public static function filter_custom_link_post_types($query_args){
        if(!empty($_POST) && isset($_POST['wpil_custom_link_search'])){
            $selected_post_types = Wpil_Settings::getPostTypes();
            if(!empty($selected_post_types)){
                $query_args['post_type'] = $selected_post_types;
            }
        }
        return $query_args;
    }

    /**
     * Queries for terms when the user does a custom link search for outbound suggestions.
     * The existing search only does posts, so we have to do the terms separately
     **/
    public static function custom_link_category_search($queried_items = array()){
        if(!empty($_POST) && isset($_POST['wpil_custom_link_search'])){

            $selected_terms = get_option('wpil_2_term_types', array());

            if(empty($selected_terms)){
                return $queried_items;
            }

            $args = array('taxonomy' => $selected_terms, 'search' => $_POST['search'], 'number' => 20);

            $term_query = new WP_Term_Query($args);
            $terms = $term_query->get_terms();

            if(empty($terms)){
                return $queried_items;
            }

            foreach($terms as $term){
                $queried_items[] = array(
                    'ID' => $term->term_id,
                    'title' => $term->name,
                    'permalink' => get_term_link($term->term_id),
                    'info' => ucfirst($term->taxonomy),
                );

            }
        }

        return $queried_items;
    }

    /**
     * Insert links into sentence
     *
     * @param $sentence
     * @param $anchor
     * @param $url
     * @param $to_post_id
     * @return string
     */
    public static function getSentenceWithAnchor($link) {
        if (!empty($link['custom_sentence'])) {
            $link['custom_sentence'] = mb_ereg_replace(preg_quote(',</a>'), '</a>,', $link['custom_sentence']);
            return $link['custom_sentence'];
        }

        //get URL
        preg_match('/<a href="([^\"]+)"[^>]*>(.*)<\/a>/i', wp_unslash($link['sentence_with_anchor']), $matches);
        if (empty($matches[1])) {
            return $link['sentence'];
        }

        // update the sentence's tags
        $link['sentence'] = self::update_sentence_tags($link['sentence'], $link['sentence_with_anchor']);

        $url = $matches[1];

        //get anchor from source sentence
        $words = [];
        $word_start = false;
        $word_end = 0;
        preg_match_all('/<span[^>]+>([^<]+)<\/span>/i', $matches[2], $matches);
        if (count($matches[1])) {
            // go over the matches
            foreach ($matches[1] as $word) {
                // and try to determine where the anchor text begins and ends
                // if the start hasn't been found
                if ($word_start === false) {
                    // see if the first word in the sentence is a free-standing word
                    $word_start = stripos($link['sentence'], $word . ' ');
                    // if it is not...
                    if(false === $word_start){
                        // look for the word more loosly and set the start for that
                        $word_start = stripos($link['sentence'], $word);
                    }
                    // and provisionally set the text ending for the end of the first word
                    $word_end = $word_start + strlen($word);
                } else {
                    // in all other cases, just keep moving the anchor text ending down the sentence until we're done.
                    $word_end = stripos($link['sentence'], $word, $word_end) + strlen($word);
                }

                // also add the word to a list of words
                $words[] = $word;
            }
        }

        $word_search = array();
        $string_length = mb_strlen($link['sentence']);
        foreach($words as $word){
            $offset = 0;
            for($i = 0; $i < mb_substr_count($link['sentence'], $word); $i++){
                if($offset > $string_length){
                    break; // make sure we don't commit the capital offense of measuring a string that's shorter than the offset
                }

                $position = Wpil_Word::mb_strpos($link['sentence'], $word, $offset);
                $word_search[] = array(
                    'pos' => $position,
                    'word' => $word
                );
                $offset = $position + 1;
            }
        }

        if(!empty($word_search)){
            usort($word_search, function($a, $b){
                if ($a['pos'] == $b['pos']) {
                    return 0;
                }
    
                return ($a['pos'] > $b['pos']) ? 1 : -1;
            });
        }

        // now search the found words for the ones that match the selected sentence
        $start_pos = null;
        $end_pos = 0;
        $sentence_pos = 0; // sentence word counter so we can keep track of what sequencial sentence word we're looking at
        foreach($word_search as $search){
            if(
                isset($words[$sentence_pos]) && 
                $search['word'] === $words[$sentence_pos]){
                if($start_pos === null){
                    $start_pos = $search['pos'];
                }
                $end_pos = ($search['pos'] + mb_strlen($search['word']));

                $sentence_pos++;
            }elseif($sentence_pos >= count($words)){
                break;
            }else{
                // if the next word in the sentence isn't the next one in the search
                // check if it's actually the next starting word
                if(isset($words[0]) && $search['word'] === $words[0]){
                    // if it is, start the sentence here
                    $start_pos = $search['pos'];
                    $end_pos = ($search['pos'] + mb_strlen($search['word']));
                    $sentence_pos = 1;
                }else{
                    // otherwise, reset the search variables
                    $start_pos = null;
                    $end_pos = 0;
                    $sentence_pos = 0;
                }
            }
        }

        $anchor = false;
        // if we have positions to work with
        if($end_pos > $start_pos){
            // get the anchor
            $anchor = mb_substr($link['sentence'], (int)$start_pos, ($end_pos - (int)$start_pos));
            // check if there's a styling tag inside it
            $tags = array('<b>' => '</b>', '<i>' => '</i>', '<u>' => '</u>', '<strong>' => '</strong>', '<em>' => '</em>', '<code>' => '</code>');
            foreach($tags as $opening => $closing){
                // if we have an opening one inside the anchor, but no closing one
                if(false !== strpos($anchor, $opening) && false === strpos($anchor, $closing)){
                    // check if the closer is just after the last word
                    if(false !== Wpil_Word::mb_strpos($link['sentence'], ($anchor . $closing))){
                        // if it is, add the closing tag to the anchor
                        $anchor = ($anchor . $closing);
                    }
                }
            }
        }

        // if that didn't work, use the old method of getting the anchor text
        if(empty($anchor)){
            //get start position by nearest whitespace
            $start = 0;
            $i = 0;
            while(strpos($link['sentence'], ' ', $start+1) < $word_start && $i < 100) {
                $start = strpos($link['sentence'], ' ', $start+1);
                $next_whitespace = strpos($link['sentence'], ' ', $start+1);
                $tag = strpos($link['sentence'], '>', $start +1);
                if ($tag && $tag < $next_whitespace) {
                    $start = $tag;
                }
                $tag = strpos($link['sentence'], '(', $start +1);
                if ($tag && $tag < $next_whitespace) {
                    $start = $tag;
                }
                $i++;

                // exit the loop if there's no further whitespace
                if(empty($next_whitespace)){
                    break;
                }
            }
            if ($start) {
                $start++;
            }

            $nbsp = urldecode('%C2%A0');

            //get end position by nearest whitespace
            $end = 0;
            $prev_end = 0;
            while($end < $word_end && $end !== false) {
                $prev_end = $end;
                $end = strpos($link['sentence'], ' ', $end + 1);
                $tag = strpos($link['sentence'], ')', $prev_end +1);

                if($end > $word_end){
                    $maybe_end = strpos($link['sentence'], $nbsp, $prev_end + 1);
                    if(!empty($maybe_end) && $maybe_end < $word_end){
                        $end = $maybe_end;
                    }
                }

                if ($tag && $tag < $end) {
                    $end = $tag;
                }
            }

            if (substr($link['sentence'], $end-1, 1) == ',') {
                $end -= 1;
            }

            if ($end === false) {
                $end = strlen($link['sentence']);
            }

            $anchor = substr($link['sentence'], $start, $end - $start);
        }

        $external = !Wpil_Link::isInternal($url);
        $open_new_tab = (int)get_option('wpil_2_links_open_new_tab', 0);
        $open_external_new_tab = false;
        if($external){
            $open_external_new_tab = get_option('wpil_external_links_open_new_tab', null);
        }

        //add target blank if needed
        $blank = '';
        $rel = '';
        if (($open_new_tab == 1 && empty($external)) || 
            ($external && $open_external_new_tab) ||
            ($open_new_tab == 1 && $open_external_new_tab === null)
        ) {
            $noreferrer = !empty(get_option('wpil_add_noreferrer', false)) ? ' noreferrer': '';
            $blank = 'target="_blank"';
            $rel = 'rel="noopener' . $noreferrer;
        }

        // if the user has set external links to be nofollow, this is an external link, and this isn't an interlinked site
        if(
            !empty(get_option('wpil_add_nofollow', false)) && 
            $external && 
            !empty(wp_parse_url($url, PHP_URL_HOST)) &&
            !in_array(wp_parse_url($url, PHP_URL_HOST), Wpil_SiteConnector::get_linked_site_domains(), true))
        {
            if(empty($rel)){
                $rel = 'rel="nofollow';
            }else{
                $rel .= ' nofollow';
            }
        }

        if(!empty($rel)){
            $rel .= '"';
        }

        //add slashes to the anchor if it doesn't found in the sentence
        if (stripos(addslashes($link['sentence']), $anchor) === false) {
//            $anchor = addslashes($anchor);
        }

        $anchor2 = str_replace('$', '\\$', $anchor);

        /**
         * allow the users to add classes to the link
         * @param string The class list
         * @param bool $external Is the link going to an external site?
         * @param string The location of the filter
         **/
        $classes = apply_filters('wpil_link_classes', '', $external, 'suggestions');

        // if the user returned an array, stringify it
        if(is_array($classes)){
            $classes = implode(' ', $classes);
        }

        $classes = (!empty($classes)) ? 'class="' . sanitize_text_field($classes) . '"': '';

        $title = '';

        // todo build into a separate attr function with the other checks
        $attrs = '';
        if(!empty($title)){
            $attrs .= ' ' . $title;
        }
        if(!empty($blank)){
            $attrs .= ' ' . $blank;
        }
        if(!empty($rel)){
            $attrs .= ' ' . $rel;
        }
        if(!empty($classes)){
            $attrs .= ' ' . $classes;
        }

        //add link to sentence
        $sentence = preg_replace('/'.preg_quote($anchor, '/').'/i', '<a href="'.$url.'"' . $attrs . '>'.$anchor2.'</a>', $link['sentence'], 1);

        $sentence = str_replace('$', '\\$', $sentence);

        // format the tags inside the sentence to make sure there's no half-in half-out tags
        $sentence = self::format_sentence_tags($sentence);

        return $sentence;
    }

    /**
     * Updates the html style tags in the sentence with the results from sentence with anchor.
     **/
    public static function update_sentence_tags($sentence, $sentence_with_anchor){

        // find all the encoded style tags
        preg_match_all('/<span[^><]*?class=["\'][^"\']*?wpil_suggestion_tag[^"\']*?["\'][^>]*?>([^<]*?)<\/span>/', $sentence_with_anchor, $matches);

        if(empty($matches)){
            return $sentence;
        }

        foreach($matches[0] as $key => $match){
            $decoded = base64_decode($matches[1][$key]);
            if(preg_match('/' . preg_quote($match, '/') . '\s*/', $sentence_with_anchor)){
                $sentence_with_anchor = preg_replace('/' . preg_quote($match, '/') . '\s*/', $decoded, $sentence_with_anchor);
            }else{
                $sentence_with_anchor = str_replace($match, $decoded, $sentence_with_anchor);
            }
        }

        // find all the non word tags
        preg_match_all('/<span[^><]*?class=["\'][^"\']*?wpil-non-word[^"\']*?["\'][^>]*?>([^<]*?)<\/span>/', $sentence_with_anchor, $matches);

        // if there are non word tags, remove them so they don't throw off the formatting
        if(!empty($matches)){
            foreach($matches[0] as $key => $match){
                $sentence_with_anchor = str_replace($match, $matches[1][$key], $sentence_with_anchor);
            }
        }

        $new_sentence = strip_tags($sentence_with_anchor, '<b><i><u><strong><em><code>');

        // remove any tags that are opening and closing without content
        $new_sentence = str_replace(array('<b></b>', '<i></i>', '<u></u>', '<strong></strong>', '<em></em>', '<code></code>'), '', $new_sentence);
        $new_sentence = str_replace(array('<b> </b>', '<i> </i>', '<u> </u>', '<strong> </strong>', '<em> </em>', '<code> </code>'), '', $new_sentence);

        // if the sentences are the same after removing all tags
        if(trim(strip_tags($sentence)) === trim(strip_tags($sentence_with_anchor)) || trim(strip_tags($sentence)) === str_replace('  ', ' ', trim(strip_tags($sentence_with_anchor))) ){
            // update the sentence with the new tagged version
            $sentence = trim($new_sentence);
        }

        return $sentence;
    }

    /**
     * Makes sure there aren't any tags that are half-in/half-out of the anchor tag.
     * Moves any offending tags along the same lines as the JS mover:
     ** If just the closing tag is inside the anchor, move it left until it's outside the anchor.
     ** If just the opening tag is inside the anchor, move it right until it's outside the anchor.
     ** If opening and closing tags are next to each other, remove them.
     **/
    public static function format_sentence_tags($sentence){

        // return the sentence if there's no tags inside the anchor
        if(empty(preg_match('/<a.*?>.*?(<[A-Za-z\/]*?>).*?<\/a>/', $sentence, $check)) || !isset($check[1]) || empty($check[1])){
            return $sentence;
        }

        // get the anchor tag and it's position data
        $link_start = Wpil_Word::mb_strpos($sentence, '<a href="');
        $link_end = Wpil_Word::mb_strpos($sentence, '</a>', $link_start);
        $link_length = ($link_end + 4 - $link_start);
        $link = mb_substr($sentence, $link_start, $link_length);
        $link_copy = $link;

        $tags_before_anchor = array();
        $tags_after_anchor = array();

        // check the anchor to see what tags it contains
        $tags_to_check = array('(<b>|<\/b>)', '(<i>|<\/i>)', '(<u>|<\/u>)', '(<strong>|<\/strong>)', '(<em>|<\/em>)', '(<code>|<\/code>)');
        foreach($tags_to_check as $tag){
            // if it only contains one tag
            if(preg_match_all('/' . $tag . '/', $link, $matches, PREG_OFFSET_CAPTURE) === 1){
                // extract the tag
                $pulled_tag = $matches[0][0][0];
                // get the tag's position
                $position = $matches[0][0][1];
                // replace the tag in the copied link
                $link_copy = mb_ereg_replace(preg_quote($pulled_tag), '', $link_copy);
                // find out if the tag is the first thing after the opening link tag // allowing for space
                $at_start = preg_match('/<a.*?>[ ]*(' . preg_quote($pulled_tag, '/') . ').*?<\/a>/', $sentence);

                // if the tag is a closing tag
                if(strpos($pulled_tag, '/')){
                    // put it on the list of tags that come before the anchor
                    $tags_before_anchor[$position] = $pulled_tag;
                }else{
                    // if it's an opening tag, check to see if it it's immediately after the link's opening tag
                    if($at_start){
                        // if it does, put it on the list that comes before the link tag
                        $tags_before_anchor[$position] = $pulled_tag;
                    }else{
                        // if it doesn't come right after the link, put it on the list of tags that come after the anchor
                        $tags_after_anchor[$position] = $pulled_tag;
                    }
                }
            }
        }

        // if there are tags that should be moved in front of the anchor
        if(!empty($tags_before_anchor)){
            // sort them to make sure we don't make a mess
            ksort($tags_before_anchor);
            // and insert them before the anchor
            $link_copy = implode('', $tags_before_anchor) . $link_copy;
        }

        // if there are tags that should be moved past the end of the anchor
        if(!empty($tags_after_anchor)){
            // sort them to make sure we don't make a mess
            ksort($tags_after_anchor);
            // and add them after the anchor
            $link_copy = $link_copy . implode('', $tags_after_anchor);
        }

        // replace the old link with the new link
        $sentence = mb_ereg_replace(preg_quote($link), $link_copy, $sentence);

        // remove any double tags // it is possible that a user will have something like <strong><em><u></u></em></strong> that should be removed, but we'll cross that bridge when we get there
        $sentence = str_replace(array('<b></b>', '<i></i>', '<u></u>', '<strong></strong>', '<em></em>', '<code></code>'), '', $sentence);
        $sentence = str_replace(array('<b> </b>', '<i> </i>', '<u> </u>', '<strong> </strong>', '<em> </em>', '<code> </code>'), ' ', $sentence);

        return $sentence;
    }

    /**
     * Set mark for post to update report
     *
     * @param $post_id
     */
    public static function updateStatMark($post_id, $direct_call = false)
    {
        // don't save links for revisions
        if(wp_is_post_revision($post_id)){
            return;
        }

        // make sure the post isn't an auto-draft
        $post = get_post($post_id);
        if(!empty($post) && 'auto-draft' === $post->post_status){
            return;
        }

        // make sure we're checking the link stats at the end of the processing or that it's been called directly
        if(99999 !== Wpil_Toolbox::get_current_action_priority() && !$direct_call){
            return;
        }

        // if this is a reusable block
        if($post->post_type === 'wp_block'){
            // process it's links to see if we need to update posts that it links to
            Wpil_Report::update_reusable_block_links($post); // reusable blocks update separately of the main post, so we're able to check at this point in the process!
        }

        // make sure this is for a post type that we track
        if(!in_array($post->post_type, Wpil_Settings::getPostTypes())){
            return;
        }

        // clear the meta flag
        update_post_meta($post_id, 'wpil_sync_report3', 0);

        if (get_option('wpil_option_update_reporting_data_on_save', false)) {
            Wpil_Report::fillMeta();
            if(WPIL_STATUS_LINK_TABLE_EXISTS){
                Wpil_Report::remove_post_from_link_table(new Wpil_Model_Post($post_id));
                Wpil_Report::fillWpilLinkTable();
            }
            Wpil_Report::refreshAllStat();
        }else{
            if(WPIL_STATUS_LINK_TABLE_EXISTS){
                $post = new Wpil_Model_Post($post_id);
                // if the current post has the Thrive builder active, load the Thrive content
                $thrive_active = get_post_meta($post->id, 'tcb_editor_enabled', true);
                if(!empty($thrive_active)){
                    $thrive_content = Wpil_Editor_Thrive::getThriveContent($post->id);
                    if($thrive_content){
                        $post->setContent($thrive_content);
                    }
                }
                if(Wpil_Report::stored_link_content_changed($post)){
                    // get the fresh post content for the benefit of the descendent methods
                    $post->getFreshContent();
                    // find any inbound internal link references that are no longer valid
                    $removed_links = Wpil_Report::find_removed_report_inbound_links($post);
                    // update the links stored in the link table
                    Wpil_Report::update_post_in_link_table($post);
                    // update the meta data for the post
                    Wpil_Report::statUpdate($post, true);
                    // and update the link counts for the posts that this one links to
                    Wpil_Report::updateReportInternallyLinkedPosts($post, $removed_links);
                }

                // if the links haven't changed, reset the processing flag
                update_post_meta($post_id, 'wpil_sync_report3', 1);
            }
        }
    }

    /**
     * Delete all post meta on post delete
     *
     * @param $post_id
     */
    public static function deleteReferences($post_id)
    {
        // if this is a post revision
        if(wp_is_post_revision($post_id)){
            // don't delete the references since that will pull the data for the parent post!
            return;
        }

        foreach (array_merge(Wpil_Report::$meta_keys, ['wpil_sync_report3', 'wpil_sync_report2_time']) as $key) {
            delete_post_meta($post_id, $key);
        }
        if(WPIL_STATUS_LINK_TABLE_EXISTS){
            // remove the current post from the links table and the links that point to it
            Wpil_Report::remove_post_from_link_table(new Wpil_Model_Post($post_id), true);
        }
    }

    /**
     * Get linked post Ids for current post
     *
     * @param $post
     * @param bool $return_ids Do we jsut return the linked post ids or the whole link object
     * @return array
     */
    public static function getLinkedPostIDs($post, $return_ids = true)
    {
        $linked_post_ids = array();

        // get the inbound post links
        if(WPIL_STATUS_LINK_TABLE_EXISTS){
            $links = Wpil_Report::getCachedReportInternalInboundLinks($post);
        }else{
            $links = Wpil_Report::getInternalInboundLinks($post);
        }

        // if we're supposed to return just the ids
        if($return_ids){
            // process out the ids
            $linked_post_ids[] = $post->id;

            foreach ($links as $link) {
                if (!empty($link->post->id)) {
                    $linked_post_ids[] = $link->post->id;
                }
            }
        }else{
            $url = $post->getLinks()->view;
            $host = parse_url($url, PHP_URL_HOST);


            $linked_post_ids[] = new Wpil_Model_Link([
                'url' => $url,
                'host' => str_replace('www.', '', $host),
                'internal' => Wpil_Link::isInternal($url),
                'post' => $post,
                'anchor' => '',
            ]);

            $linked_post_ids = array_merge($linked_post_ids, $links);
        }

        return $linked_post_ids;
    }

    /**
     * Get all Advanced Custom Fields names
     *
     * @return array
     */
    public static function getAdvancedCustomFieldsList($post_id)
    {
        global $wpdb;

        $fields = [];

        if(!class_exists('ACF') || get_option('wpil_disable_acf', false)){
            return $fields;
        }

        $fields_query = $wpdb->get_results("SELECT SUBSTR(meta_key, 2) as `name` FROM {$wpdb->postmeta} WHERE post_id = $post_id AND meta_value IN (SELECT DISTINCT post_name FROM {$wpdb->posts} WHERE post_name LIKE 'field_%') AND SUBSTR(meta_key, 2) != ''");
        foreach ($fields_query as $field) {
            $name = trim($field->name);

            if ($name) {
                $fields[] = $field->name;
            }
        }

        // if there are any fields created with PHP/JSON
        $local_field_groups = (function_exists('acf_get_local_store')) ? acf_get_local_store('groups') : false;
        if(!empty($local_field_groups) && isset($local_field_groups->data)){
            $search_fields = array();
            $secondary_lookup_fields = array();
            foreach($local_field_groups->data as $group){
                // go to some pains to ignore options pages
                if( isset($group['location']) &&
                    isset($group['location'][0]) &&
                    isset($group['location'][0][0]) &&
                    isset($group['location'][0][0]['param']) &&
                    $group['location'][0][0]['param'] == 'options_page' &&
                    $group['location'][0][0]['operator'] == '==')
                {
                    continue;
                }

                if(isset($group['name'])){
                    $search_fields[$group['name']] = true;
                }elseif(isset($group['key']) && function_exists('acf_get_fields')){
                    $secondary_fields = acf_get_fields($group['key']);
                    if(!empty($secondary_fields)){
                        foreach($secondary_fields as $field){
                            if( isset($field['type']) && 
                                ($field['type'] === 'textarea' || $field['type'] === 'wysiwyg') &&
                                isset($field['key'])
                            ){
                                $secondary_lookup_fields[$field['key']] = true;
                            }elseif(isset($field['type']) && $field['type'] === 'flexible_content' && isset($field['layouts']) && !empty($field['layouts'])){
                                foreach($field['layouts'] as $layout){
                                    if(isset($layout['sub_fields']) && !empty($layout['sub_fields'])){
                                        $secondary_lookup_fields = array_merge($secondary_lookup_fields, self::getRecursiveACFSubFields($layout));
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if(!empty($search_fields)){
                $search_fields = array_keys($search_fields);
                $search_fields = '`meta_key` LIKE \'' . implode('_%\' OR `meta_key` LIKE \'', $search_fields) . '_%\'';

                $fields_query = $wpdb->get_results("SELECT meta_key as 'name' FROM {$wpdb->postmeta} WHERE `post_id` = $post_id AND ({$search_fields})  AND `meta_value` != ''");

                if(!empty($fields_query)){
                    foreach ($fields_query as $field) {
                        $name = trim($field->name);
                        if(!empty($name)){
                            $fields[] = $name;
                        }
                    }
                }
            }

            if(!empty($secondary_lookup_fields)){
                $secondary_lookup_fields = array_keys($secondary_lookup_fields);
                $search_fields = " AND `meta_value` IN ('" . implode("', '", $secondary_lookup_fields) . "')";
                $fields_query = $wpdb->get_col("SELECT meta_key FROM {$wpdb->postmeta} WHERE `post_id` = $post_id {$search_fields}");

                if(!empty($fields_query)){
                    foreach($fields_query as $field){
                        if(0 === strpos($field, '_')){
                            $name = trim(substr($field, 1));
                            if(!empty($name)){
                                $fields[] = $name;
                            }
                        }
                    }
                }
            }

            // remove any duplicate fields
            $fields = array_flip(array_flip($fields));
        }

        return $fields;
    }

    /**
     * Recursively goes through the potential multitude of ACF subfields and pulls out all of the
     * textarea & WYSIWYG fields so we can search the database for them
     **/
    public static function getRecursiveACFSubFields($fields){
        $found_fields = array();
        if(isset($fields['sub_fields']) && !empty($fields['sub_fields'])){
            foreach($fields['sub_fields'] as $sub){
                // only get the fields that can reasonably be assumed to be linkable
                if( isset($sub['type']) &&
                    ($sub['type'] === 'textarea' || $sub['type'] === 'wysiwyg') &&
                    isset($sub['key'])
                ){
                    $found_fields[$sub['key']] = true;
                }elseif(isset($sub['sub_fields']) && !empty($sub['sub_fields'])){
                    $found_fields = array_merge($found_fields, self::getRecursiveACFSubFields($sub));
                }
            }
        }

        return $found_fields;
    }


    /**
     * Gets an array of all custom fields on the site.
     * @return array
     **/
    public static function getAllCustomFields()
    {
        global $wpdb;

        if(!class_exists('ACF') || get_option('wpil_disable_acf', false)){
            return array();
        }

        if (self::$advanced_custom_fields_list === null) {
            $fields = [];
            // try getting the main set of ACF fields
            //$post_names = $wpdb->get_col("SELECT DISTINCT pm.meta_key as `name` FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON pm.meta_value = p.post_name WHERE p.post_type = 'acf-field' AND p.post_name LIKE 'field_%'");
            $post_names = $wpdb->get_col("SELECT DISTINCT pm.meta_key as `name` FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON pm.meta_value = p.post_name WHERE p.post_type = 'acf-field'");

            // if we found some
            if (!empty($post_names)) {
                // clean up their names and add them to the field list
                foreach ($post_names as $name) {
                    $name = trim(substr($name, 1));
                    if (!empty($name)) {
                        $fields[] = $name;
                    }
                }
            }

            // if there are any fields created with PHP/JSON
            $local_field_groups = (function_exists('acf_get_local_store')) ? acf_get_local_store('groups') : false;
            if(!empty($local_field_groups) && isset($local_field_groups->data)){
                $search_fields = array();
                $secondary_lookup_fields = array();
                foreach($local_field_groups->data as $group){
                    // go to some pains to ignore options pages
                    if( isset($group['location']) &&
                        isset($group['location'][0]) &&
                        isset($group['location'][0][0]) &&
                        isset($group['location'][0][0]['param']) &&
                        $group['location'][0][0]['param'] == 'options_page' &&
                        $group['location'][0][0]['operator'] == '==')
                    {
                        continue;
                    }

                    if(isset($group['name'])){
                        $search_fields[] = $group['name'];
                    }elseif(isset($group['key']) && function_exists('acf_get_fields')){
                        $secondary_fields = acf_get_fields($group['key']);
                        if(!empty($secondary_fields)){
                            foreach($secondary_fields as $field){
                                if( isset($field['type']) && 
                                    ($field['type'] === 'textarea' || $field['type'] === 'wysiwyg') &&
                                    isset($field['key'])
                                ){
                                    $secondary_lookup_fields[$field['key']] = true;
                                }elseif(isset($field['type']) && $field['type'] === 'flexible_content' && isset($field['layouts']) && !empty($field['layouts'])){
                                    foreach($field['layouts'] as $layout){
                                        if(isset($layout['sub_fields']) && !empty($layout['sub_fields'])){
                                            $secondary_lookup_fields = array_merge($secondary_lookup_fields, self::getRecursiveACFSubFields($layout));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if(!empty($search_fields)){
                    $search_fields = '`meta_key` LIKE \'' . implode('_%\' OR `meta_key` LIKE \'', $search_fields) . '_%\'';

                    $fields_query = $wpdb->get_results("SELECT DISTINCT meta_key as `name` FROM {$wpdb->postmeta} WHERE ({$search_fields})");

                    if(!empty($fields_query)){
                        foreach ($fields_query as $field) {
                            $name = trim($field->name);
                            if ($name) {
                                $fields[] = $field->name;
                            }
                        }
                    }
                }

                if(!empty($secondary_lookup_fields)){
                    $secondary_lookup_fields = array_keys($secondary_lookup_fields);
                    $secondary_fields = "`meta_value` IN ('" . implode("', '", $secondary_lookup_fields) . "')";
                    $fields_query = $wpdb->get_col("SELECT DISTINCT meta_key FROM {$wpdb->postmeta} WHERE {$secondary_fields}");
                    if(!empty($fields_query)){

                        foreach($fields_query as $field){
                            if(0 === strpos($field, '_')){
                                $name = trim(substr($field, 1));
                                $fields[] = $name;
                            }
                        }
                    }
                }

                // if we've found some fields
                if(!empty($fields)){
                    // remove any duplicate fields
                    $fields = array_flip(array_flip($fields));

                    // re-key the array in case something sensitive is listening
                    $fields = array_values($fields);
                }

            self::$advanced_custom_fields_list = $fields;

            }
        }

        return self::$advanced_custom_fields_list;
    }

    /**
     * Gets a list of the possible meta content fields to add links to
     * @param string $type Is the content for a post or a term?
     * @return array $fields An array of the possible fields for the item
     **/
    public static function getMetaContentFieldList($type = 'post'){
        $fields = [];

        if(defined('RH_MAIN_THEME_VERSION') && $type === 'term'){
            $fields[] = 'brand_second_description';
        }

        return $fields;
    }

    /**
     * Get all posts with the same language
     *
     * @param $post_id
     * @return array
     */
    public static function getSameLanguagePosts($post_id)
    {
        global $wpdb;
        $ids = [];
        $posts = [];

        // if WPML is active and there's languages saved
        if(defined('WPML_PLUGIN_BASENAME')) {
            $table = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}icl_languages'");
            if($table == $wpdb->prefix . 'icl_languages'){
                $post_types = self::getSelectedLanguagePostTypes();
                $language = $wpdb->get_var("SELECT language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = $post_id AND `element_type` IN ({$post_types}) ");
                if (!empty($language)) {
                    $posts = $wpdb->get_results("SELECT element_id as id FROM {$wpdb->prefix}icl_translations WHERE element_id != $post_id AND language_code = '$language' AND `element_type` IN ({$post_types}) ");
                }
            }
        }

        // if Polylang is active
        if(defined('POLYLANG_VERSION')){
            $taxonomy_id = $wpdb->get_var("SELECT t.term_taxonomy_id FROM {$wpdb->term_taxonomy} t INNER JOIN {$wpdb->term_relationships} r ON t.term_taxonomy_id = r.term_taxonomy_id WHERE t.taxonomy = 'language' AND r.object_id = " . $post_id);
            if (!empty($taxonomy_id)) {
                $posts = $wpdb->get_results("SELECT object_id as id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = $taxonomy_id AND object_id != $post_id");
            }
        }

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $ids[] = $post->id;
            }
        }

        return $ids;
    }

    /**
     * Gets the selected post types formatted for WPML
     **/
    public static function getSelectedLanguagePostTypes(){
        $post_types = implode("', 'post_", Wpil_Suggestion::getSuggestionPostTypes());

        if(!empty($post_types)){
            $post_types = "'post_" . $post_types . "'";
        }

        return $post_types;
    }

    /**
     * Get all terms in the same language
     *
     * @param $term_id
     * @return array
     */
    public static function getSameLanguageTerms($term_id)
    {
        global $wpdb;
        $ids = [];

        // if WPML is active and there's languages saved
        if(defined('WPML_PLUGIN_BASENAME')) {
            $table = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}icl_languages'");
            if($table == $wpdb->prefix . 'icl_languages'){
                $term_types = self::getSelectedLanguageTermTypes();
                $language = $wpdb->get_var("SELECT language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = $term_id AND `element_type` IN ({$term_types}) ");
                if (!empty($language)) {
                    $ids = $wpdb->get_col("SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE element_id != $term_id AND language_code = '$language' AND `element_type` IN ({$term_types}) ");
                }
            }
        }

        // if Polylang is active
        if(defined('POLYLANG_VERSION')){
            // get the terms that have been translated... Eventually
            $taxonomy_description = $wpdb->get_var("SELECT `description` FROM {$wpdb->term_taxonomy} t INNER JOIN {$wpdb->term_relationships} r ON t.term_taxonomy_id = r.term_taxonomy_id WHERE t.taxonomy = 'term_translations' AND r.object_id = " . $term_id);
            if (!empty($taxonomy_description)) {
                $description_data = maybe_unserialize($taxonomy_description);
                $lang_code = array_search($term_id, $description_data);
                if(!empty($lang_code)){
                    $data = $wpdb->get_results("SELECT * FROM {$wpdb->term_taxonomy} WHERE `taxonomy` = 'term_translations' AND  `description` LIKE '%\"{$lang_code}\"%' AND term_id != $term_id");
                    if(!empty($data)){
                        foreach($data as $term){
                            $dat = maybe_unserialize($term->description);
                            if(!empty($dat) && isset($dat[$lang_code])){
                                $ids[] = $dat[$lang_code];
                            }
                        }
                    }
                }
            }
        }

        if (!empty($ids)) {
            $ids[] = array_flip(array_flip($ids));
        }

        return $ids;
    }

    /**
     * Gets the selected post types formatted for WPML
     **/
    public static function getSelectedLanguageTermTypes(){
        $term_types = implode("', 'tax_", Wpil_Settings::getTermTypes());

        if(!empty($term_types)){
            $term_types = "'tax_" . $term_types . "'";
        }

        return $term_types;
    }

    public static function getAnchors($post)
    {
        preg_match_all('|<a [^>]+>([^<]+)</a>|i', $post->getContent(), $matches);

        if (!empty($matches[1])) {
            return $matches[1];
        }

        return [];
    }

    /**
     * Get post model by view link
     * URLtoPost
     * IDFROMLINK
     * IDFROMURL
     * 
     * @param $link
     * @return Wpil_Model_Post|null
     */
    public static function getPostByLink($link)
    {
        global $wpdb;
        $post = null;
        $link = trim($link);
        $starting_link = $link;

        // check to see if we've already come across this link
        $cached = self::get_cached_url_post($link);
        // if we have
        if(!empty($cached)){
            //return the cached version
            return $cached;
        }

        // check to make sure that we are reasonably sure we can trace the link
        if(!Wpil_Link::is_traceable($link)){
            // if we're not, return null
            return $post;
        }

        // check to see if the link isn't a pretty link
        if(preg_match('#[?&](p|page_id|attachment_id)=(\d+)#', $link, $values)){
            // if it's not, get the id
            $id = absint($values[2]);
            // if there is an id
            if($id){
                // get the post so we can make sure it exists
                $wp_post = get_post($id);
                // if it does exist, set the id. Else, set it to null
                $post_id = (!empty($wp_post)) ? $wp_post->ID: null;
            }
        }else{
            // make sure the link isn't double slashed anywhere that it's not supposed to be
            if(!empty(preg_match('/(?<!http:|https:)\/\//', $link, $m)) || !empty($m)){
                $link = preg_replace('/(?<!http:|https:)(?:\/\/\/|\/\/)/', '/', $link);
                $link = preg_replace('/(?<!http:|https:)(?:\/\/\/|\/\/)/', '/', $link);
            }

            // clean up any translations if it's a relative link
            $link = Wpil_Link::clean_translated_relative_links($link);
            $post_id = url_to_postid($link);
        }

        if (!empty($post_id)) {
            $post = new Wpil_Model_Post($post_id);
        } else {
            $slug = array_filter(explode('/', $link));
            $term = Wpil_Term::getTermBySlug(end($slug), $link);
            if(!empty($term)){
                $post = new Wpil_Model_Post($term->term_id, 'term');
            }
        }

        // if we couldn't find the post and custom permalinks is active
        if(empty($post) && defined('CUSTOM_PERMALINKS_FILE')){
            // consult it's database listings to see if we can find the post the link belongs to
            $search_url = $link;

            // get the home url and clean it up
            $site_url = get_home_url();
            $site_url = preg_replace('/http:\/\/|https:\/\/|www\./', '', $site_url);
            // make sure the supplied link is similarly clean
            $search_url = preg_replace('/http:\/\/|https:\/\/|www\./', '', $search_url);

            // and replace the home portion of the link to make it relative
            $search_url = trim(str_replace($site_url, '', $search_url), '/'); // Don't add slashes around the url
            
            // get the stati and types to search
            $status = Wpil_Query::postStatuses('p');
            $type = Wpil_Query::postTypes('p');

            // now search the db
			$search = $wpdb->get_col(
				$wpdb->prepare(
					'SELECT p.ID ' .
					" FROM $wpdb->posts AS p INNER JOIN $wpdb->postmeta AS pm ON (pm.post_id = p.ID) " .
					" WHERE pm.meta_key = 'custom_permalink' " .
					' AND (pm.meta_value = %s OR pm.meta_value = %s) ' .
					" {$status} {$type} " .
					" LIMIT 1",
					$search_url,
					$search_url . '/'
				)
			);
            // if we found a post
            if(!empty($search)){
                // that is our new post object
                $post = new Wpil_Model_Post($search[0]);
            }
        }

        // if all that didn't work, the post might be draft or Polylang Pro might be active and we'll have to check for multiple posts with the same name
        // so we'll try pulling the post name from the URL and seeing if that will get us an id
        if((empty($post) || defined('POLYLANG_PRO')) && is_string($link) && !empty($link) && Wpil_Link::isInternal($link)){
            // get the permalink structure
            $link_structure = get_option('permalink_structure', '');
            if(!empty($link_structure)){
                // see if the post name is in it
                if(false !== strpos($link_structure, '%postname%')){
                    // if it is, blow up the link structure
                    $exploded_structure = explode('/', '/' . trim($link_structure, '/') . '/'); // frame the permalink with "/" so that we're consistently comparing it to the link
                    // make the supplied link relative, and blow it up too
                    if(!Wpil_Toolbox::isRelativeLink($link)){
                        // get the home url and clean it up
                        $site_url = get_home_url();
                        $site_url = preg_replace('/http:\/\/|https:\/\/|www\./', '', $site_url);
                        // make sure the supplied link is similarly clean
                        $link = preg_replace('/http:\/\/|https:\/\/|www\./', '', $link);

                        // and replace the home portion of the link to make it relative
                        $link = '/'. trim(str_replace($site_url, '', $link), '/') . '/'; // we're going to assume that the user isn't using a draft post as the home url... That would give us just "/" at this point, and "///" isn't a valid url
                    }

                    // if we couldn't get a 
                    if(Wpil_Settings::translation_enabled()){

                        // if polylang is active
                        if(defined('POLYLANG_VERSION')){
                            global $polylang;

                            if(!empty($polylang)){
                                // get the link's language
                                $lang = $polylang->links_model->get_language_from_url($link);

                                // if we got the language, try getting it's term
                                if(!empty($lang)){
                                    $language_term = get_term_by('slug', $lang, 'language');
                                }

                                // and remove any translation effect from the url
                                $link = $polylang->links_model->remove_language_from_link($link);
                            }
                        }
                    }

                    // now blow up the link
                    $exploded_link = explode('/', $link);

                    // and see if the link has a postname in the same position as the permalink structure
                    $name = '';
                    foreach($exploded_structure as $key => $piece){
                        if( $piece === '%postname%' &&          // if we're focussed on the postname
                            isset($exploded_link[$key]) &&      // and there's a corresponding piece in the link
                            !empty($exploded_link[$key]) &&     // and there's something in the corresponding piece
                            is_string($exploded_link[$key]) &&  // and the corresponding is a string
                            strlen($exploded_link[$key]) > 0)   // and it's at least 1 char long
                        {
                            // extract the piece as the post name and exit the loop
                            $name = $exploded_link[$key];
                            break;
                        }
                    }

                    // if we've found something
                    if(!empty($name)){
                        $post_types = Wpil_Query::postTypes();

                        if(Wpil_Settings::translation_enabled() && !empty($language_term)){
                            $query = $wpdb->prepare("SELECT a.ID FROM {$wpdb->posts} a LEFT JOIN {$wpdb->term_relationships} b ON a.ID = b.object_id WHERE a.post_name = %s && b.term_taxonomy_id = %d {$post_types} LIMIT 1", $name, $language_term->term_id);
                        }else{
                            $query = $wpdb->prepare("SELECT `ID` FROM {$wpdb->posts} WHERE `post_name` = %s {$post_types} LIMIT 1", $name);
                        }

                        // see if there's a post in the database with the same name from among the post types that the user has selected
                        $dat = $wpdb->get_col($query);

                        // if there isn't one, check across all the post types
                        if(empty($dat)){
                            $dat = $wpdb->get_col($wpdb->prepare("SELECT `ID` FROM {$wpdb->posts} WHERE `post_name` = %s AND `post_type` != 'revision' LIMIT 1", $name));
                        }

                        // if that didn't work either, try looking for the title
                        if(empty($dat)){ // TODO: set up some kind of a post title lookup table. The post_title column isn't indexed, and searching it for many results can take forever
                            // replace any hyphens with spaces
                            $name = str_replace('-', ' ', $name);
                            // and search through our post types
                            $dat = $wpdb->get_col($wpdb->prepare("SELECT `ID` FROM {$wpdb->posts} WHERE `post_title` = %s {$post_types} LIMIT 1", $name)); // for exceedingly long titles, I might consider re-adding the LIKE check. But we'll cross that bridge when we get there
                        
                            // if that still didn't work, check the title across all the post types
                            if(empty($dat)){
                                $dat = $wpdb->get_col($wpdb->prepare("SELECT `ID` FROM {$wpdb->posts} WHERE `post_title` = %s AND `post_type` != 'revision' LIMIT 1", $name));
                            }
                        }

                        // if we've found a post id
                        if(!empty($dat) && isset($dat[0]) && !empty($dat[0])){
                            // create the post object we've been striving for
                            $post = new Wpil_Model_Post($dat[0]);
                        }
                    }
                }
            }
        }

        // if we've gone this far and haven't 

        // cache the results of our efforts in case we come across this link again
        self::update_cached_url_post($starting_link, $post);

        return $post;
    }

    /**
     * Checks to see if the url was previously processed into a post object.
     * If it is in the cache, it returns the cached post so we don't have to run through the process again.
     * Returns false if the url hasn't been processed yet, or it doesn't go to a known post
     **/
    public static function get_cached_url_post($url = ''){
        if(empty($url) || !is_string($url)){
            return false;
        }

        // clean up the url a little so we have consistency between slightly different links
        // clean up any translations if it's a relative link
        $url = Wpil_Link::clean_translated_relative_links($url);
        // remove www & protocol bits
        $url = str_replace(['http', 'https'], '', str_replace('www.', '', $url));

        if(empty($url) || !isset(self::$post_url_cache[$url])){
            return false;
        }

        return self::$post_url_cache[$url];
    }

    /**
     * Updates the url cache when we come across a url + post that we haven't stored yet.
     * Also does some housekeeping to make sure the cache doesn't grow too big
     **/
    public static function update_cached_url_post($url, $post){
        if(empty($url) || empty($post) || isset(self::$post_url_cache[$url]) || !is_string($url)){
            return false;
        }

        // clean up the url a little so we have consistency between slightly different links
        // clean up any translations if it's a relative link
        $url = Wpil_Link::clean_translated_relative_links($url);
        // remove www & protocol bits
        $url = str_replace(['http', 'https'], '', str_replace('www.', '', $url));

        if(empty($url)){
            return false;
        }

        self::$post_url_cache[$url] = $post;

        if(count(self::$post_url_cache) > 5000){
            $ind = key(self::$post_url_cache);
            unset(self::$post_url_cache[$ind]);
        }
    }

    /**
     * Get post IDs from certain category
     *
     * @param $category_id
     * @return array
     */
    public static function getCategoryPosts($category_id)
    {
        global $wpdb;

        $posts = [];
        $categories = $wpdb->get_results("SELECT r.object_id as `id` FROM {$wpdb->term_relationships} r INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = r.term_taxonomy_id WHERE tt.term_id = " . $category_id);
        foreach ($categories as $post) {
            $posts[] = $post->id;
        }

        return $posts;
    }

    /**
     * Gets the meta keys for content areas created with page builders so we can search the database for content.
     * @return array
     **/
    public static function get_builder_meta_keys(){
        $builder_meta = array();
        // if Goodlayers is active
        if(defined('GDLR_CORE_LOCAL')){
            $builder_meta[] = 'gdlr-core-page-builder';
        }
        // if Themify builder is active
        if(class_exists('ThemifyBuilder_Data_Manager')){
            $builder_meta[] = '_themify_builder_settings_json';
        }
        // if Oxygen is active
        if(defined('CT_VERSION')){
            $builder_meta[] = 'ct_builder_shortcodes';
        }
        // if Muffin is active
        if(defined('MFN_THEME_VERSION')){
            $builder_meta[] = 'mfn-page-items-seo';
        }
        // if "Thrive" is active
        if(defined('TVE_PLUGIN_FILE') || defined('TVE_EDITOR_URL')){
            $builder_meta[] = 'tve_updated_post';
        }
        // if Elementor is active
        if(defined('ELEMENTOR_VERSION')){
            $builder_meta[] = '_elementor_data';
        }

        return $builder_meta;
    }

    /**
     * Makes sure all single and double qoutes are excaped once in the supplied text.
     * @param string $text The text that needs to have it's quotes escaped
     * @return string $text The updated text with the single and double qoutes escaped
     **/
    public static function normalize_slashes($text){
        $old_text = $text;
        // add slashes to the single qoutes
        $text = mb_eregi_replace("(?<!\\\\)'", "\'", $text);
        // add slashes to the double qoutes
        $text = mb_eregi_replace('(?<!\\\\)"', '\"', $text);
        // and return the text if it's not empty
        return !empty($text) ? $text: $old_text; // if it is empty, return the original text
    }

    /**
     * Makes sure that the supplied sentence uses the same slashing as the rest of the post content.
     * Does a check to see if the sentence to insert is custom sentence since we're not able to normalize there without guessing
     * @param string $text The text that needs to have it's quotes escaped
     * @param string $compare_sentence A string (sentence or changed_sentence) to compare with so we can tell if this is a custom sentence
     * @param int $start The beginning of the replace_text where the link is supposed to be inserted
     * @param int $end The end of the replace_text where the link is supposed to be inserted
     * @param string $content The content that we're looking to insert the link into
     * @return string $text The updated text with the single and double qoutes escaped
     **/
    public static function adjust_sentence_slashes($sentence = '', $compare_sentence = '', $start = 0, $end = 0, $content = ''){
        if(empty($sentence) || empty($compare_sentence) || empty($content)){
            return $sentence;
        }

        // get the slightly cleaned up versions of the sentence and changed sentence
        $cleaned_sentence = trim(strip_tags($sentence));
        $cleaned_compare_sentence = trim(strip_tags($compare_sentence));
        $insert_text = mb_substr($content, $start, ($end - $start));

        // first,  
        if( (self::normalize_slashes($cleaned_sentence) !== self::normalize_slashes($cleaned_compare_sentence)) || // or if this is a modified sentence
            empty($insert_text) || // or there is no insert text
            false === Wpil_Word::mb_strpos(self::normalize_slashes($insert_text), self::normalize_slashes($sentence))) // or if the sentence is not in the search range
        {
            // if any of these are true, return the search sentence now
            return $sentence;
        }

        // search the insert text for a basis to work with
        preg_match_all('/\\\*[\'"]/', $insert_text, $matches);

        $q1_count = false;
        $q2_count = false;
        if(!empty($matches)){
            foreach($matches[0] as $match){
                if($q1_count === false && false !== strpos($match, "'")){
                    $q1_count = substr_count($match, '\\');
                }elseif($q2_count === false && false !== strpos($match, '"')){
                    $q2_count = substr_count($match, '\\');
                }
            }
        }

        // search the content to see what the html tags are usually slashed as
        preg_match_all('/(?:(?:href|class)=([\\\]*["\'])([^"]*?)[\\\]*[\'"])[^<>]*?/', $content, $matches);
        $html_quote = false;
        $quote_avg = array();
        if(!empty($matches)){

            foreach($matches[1] as $match){
                $q_count = substr_count($match, '\\');
                if(!isset($quote_avg[$q_count])){
                    $quote_avg[$q_count] = 1;
                }else{
                    $quote_avg[$q_count]++;
                }
            }

            if(!empty($quote_avg)){
                arsort($quote_avg);
                $html_quote = key($quote_avg);
            }
        }

        // base64 encode any tags so we don't break them by adding slashes
        $sentence = preg_replace_callback('|(<([a-zA-Z]*).*?>)(.*?)(<\/\2>)|i', function($i){ 
            $i[0] = str_replace($i[1], 'wpil-html-replace_' . base64_encode($i[1]) . '_wpil-html-replace', $i[0]);
            $i[0] = str_replace($i[4], 'wpil-html-replace_' . base64_encode($i[4]) . '_wpil-html-replace', $i[0]);
            return $i[0]; 
        }, $sentence);

        // reslash the changed sentence using the slash data that we've pulled about the post
        if($q1_count !== false){
            $replace = "'";
            for($i = 0; $i < $q1_count; $i++){
                $replace = ('\\' . $replace);
            }
            $sentence = preg_replace("/\\\*'/", $replace, $sentence);
        }
        if($q2_count !== false){
            $replace = '"';
            for($i = 0; $i < $q2_count; $i++){
                $replace = ('\\' . $replace);
            }
            $sentence = preg_replace('/\\\*"/', $replace, $sentence);
        }

        // decode all the encoded elements and do any slashing that's required
        if(false !== strpos($sentence, 'wpil-html-replace_')){
            $sentence = preg_replace_callback('`wpil-html-replace_(([A-Za-z0-9+\/]{4})*([A-Za-z0-9+\/]{3}=|[A-Za-z0-9+\/]{2}==)?)_wpil-html-replace`i', function($i) use ($html_quote){
                $html_content = base64_decode($i[1]);

                if(false !== $html_quote){
                    $replace = '"';
                    for($j = 0; $j < $html_quote; $j++){
                        $replace = ('\\' . $replace);
                    }
                    $html_content = preg_replace('/\\\*"/', $replace, $html_content);
                }

                return str_replace($i[0], $html_content, $i[0]);
            }, $sentence);
        }

        return $sentence;
    }

    /**
     * Helper function for checking if the supplied string is json
     * @return bool
     **/
    public static function is_json($str) {
        if(empty($str) || !is_string($str)){
            return false;
        }
        $json = json_decode($str);
        return $json && $str != $json;
    }
}
