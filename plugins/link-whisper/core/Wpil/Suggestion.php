<?php

/**
 * Work with suggestions
 */
class Wpil_Suggestion
{
    public static $undeletable = false;
    public static $max_anchor_length = 0;
    public static $min_anchor_length = 0;
    public static $keyword_dummy_index = 0;

    /**
     * Gets the suggestions for the current post/cat on ajax call.
     * Processes the suggested posts in batches to avoid timeouts on large sites.
     **/
    public static function ajax_get_post_suggestions(){

        $post_id = intval($_POST['post_id']);
        $term_id = intval($_POST['term_id']);
        $key = intval($_POST['key']);
        $user = wp_get_current_user();

        if((empty($post_id) && empty($term_id)) || empty($key) || 999999 > $key || empty($user->ID)){
            wp_send_json(array(
                'error' => array(
                    'title' => __('Data Error', 'wpil'),
                    'text'  => __('There was some data missing while processing the site content, please refresh the page and try again.', 'wpil'),
                )
            ));
        }

        // if the nonce doesn't check out, exit
        Wpil_Base::verify_nonce('wpil_suggestion_nonce');

        // be sure to ignore any external object caches
        Wpil_Base::ignore_external_object_cache();

        // Remove any hooks that may interfere with AJAX requests
        Wpil_Base::remove_problem_hooks();

        if(!empty($term_id)){
            $post = new Wpil_Model_Post($term_id, 'term');
        }else{
            $post = new Wpil_Model_Post($post_id);
        }

        $count = null;
        if(isset($_POST['count'])){
            $count = intval($_POST['count']);
        }

        $batch_size = Wpil_Settings::getProcessingBatchSize();

        if(empty($count)){
            Wpil_Settings::update_suggestion_filters();
        }

        if(isset($_POST['type']) && 'outbound_suggestions' === $_POST['type']){
            // get the total number of posts that we'll be going through
            if(!isset($_POST['post_count']) || empty($_POST['post_count'])){
                $post_count = self::getPostProcessCount($post);
            }else{
                $post_count = intval($_POST['post_count']);
            }

            $phrase_array = array();
            while(!Wpil_Base::overTimeLimit(15, 45) && (($count - 1) * $batch_size) < $post_count){

                // get the phrases for this batch of posts
                $phrases = self::getPostSuggestions($post, null, false, null, $count, $key);

                if(!empty($phrases)){
                    $phrase_array[] = $phrases;
                }

                $count++;
            }

            $status = 'no_suggestions';
            if(!empty($phrase_array)){
                $stored_phrases = get_transient('wpil_post_suggestions_' . $key);
                if(empty($stored_phrases)){
                    $stored_phrases = $phrase_array;
                }else{
                    // decompress the suggestions so we can add more to the list
                    $stored_phrases = Wpil_Toolbox::decompress($stored_phrases);

                    foreach($phrase_array as $phrases){
                        // add the suggestions
                        $stored_phrases[] = $phrases;
                    }
                }

                // compress the suggestions to save space
                $stored_phrases = Wpil_Toolbox::compress($stored_phrases);

                // store the current suggestions in a transient
                set_transient('wpil_post_suggestions_' . $key, $stored_phrases, MINUTE_IN_SECONDS * 15);
                // send back our status
                $status = 'has_suggestions';
            }

            $num = ($batch_size * $count < $post_count) ? $batch_size * $count : $post_count;
            $message = sprintf(__('Processing Link Suggestions: %d of %d processed', 'wpil'), $num, $post_count);

            wp_send_json(array('status' => $status, 'post_count' => $post_count, 'batch_size' => $batch_size, 'count' => $count, 'message' => $message));

        }else{
            wp_send_json(array(
                'error' => array(
                    'title' => __('Unknown Error', 'wpil'),
                    'text'  => __('The data is incomplete for processing the request, please reload the page and try again.', 'wpil'),
                )
            ));
        }
    }

    /**
     * Updates the link report displays with the suggestion results from ajax_get_post_suggestions.
     **/
    public static function ajax_update_suggestion_display(){
        $post_id = intval($_POST['post_id']);
        $term_id = intval($_POST['term_id']);
        $process_key = intval($_POST['key']);
        $user = wp_get_current_user();

        // if the processing specifics are missing, exit
        if((empty($post_id) && empty($term_id)) || empty($process_key) || 999999 > $process_key || empty($user->ID)){
            wp_send_json(array(
                'error' => array(
                    'title' => __('Data Error', 'wpil'),
                    'text'  => __('There was some data missing while processing the site content, please refresh the page and try again.', 'wpil'),
                )
            ));
        }

        // if the nonce doesn't check out, exit
        Wpil_Base::verify_nonce('wpil_suggestion_nonce');

        // be sure to ignore any external object caches
        Wpil_Base::ignore_external_object_cache();

        // Remove any hooks that may interfere with AJAX requests
        Wpil_Base::remove_problem_hooks();

        if(!empty($term_id)){
            $post = new Wpil_Model_Post($term_id, 'term');
        }else{
            $post = new Wpil_Model_Post($post_id);
        }

        $same_category = Wpil_Settings::get_suggestion_filter('same_category');
        $max_suggestions_displayed = Wpil_Settings::get_max_suggestion_count();

        if('outbound_suggestions' === $_POST['type']){
            // get the suggestions from the database
            $phrases = get_transient('wpil_post_suggestions_' . $process_key);

            // if there are suggestions
            if(!empty($phrases)){
                // decompress the suggestions
                $phrases = Wpil_Toolbox::decompress($phrases);
            }

            // merge them all into a suitable array
            $phrase_groups = self::merge_phrase_suggestion_arrays($phrases);

            foreach($phrase_groups as $phrases){
                foreach($phrases as $phrase){
                    usort($phrase->suggestions, function ($a, $b) {
                        $a_pillar = false;
                        $b_pillar = false;
                        if(class_exists('WPSEO_Meta') && method_exists('WPSEO_Meta', 'get_value')){
                            $a_pillar = ($a->post->type === 'post' && WPSEO_Meta::get_value('is_cornerstone', $a->post->id) === '1');
                            $b_pillar = ($b->post->type === 'post' && WPSEO_Meta::get_value('is_cornerstone', $b->post->id) === '1');
                        }elseif(defined('RANK_MATH_VERSION')){
                            $a_pillar = ($a->post->type === 'post' && get_post_meta($a->post->id, 'rank_math_pillar_content', true) === 'on');
                            $b_pillar = ($b->post->type === 'post' && get_post_meta($b->post->id, 'rank_math_pillar_content', true) === 'on');
                        }

                        // if one of these is pillar and the other isnt
                        if($a_pillar !== $b_pillar){
                            // prioritize the pillar content
                            return ($a_pillar > $b_pillar) ? -1 : 1;
                        }

                        // if they both have the same pillar status, sort by score
                        if ($a->total_score == $b->total_score) {
                            return 0;
                        }
                        return ($a->total_score > $b->total_score) ? -1 : 1;
                    });
                }
            }

            $used_posts = array($post_id . ($post->type == 'term' ? 'cat' : ''));

            foreach($phrase_groups as $type => $phrases){
                if (!empty($phrase_groups[$type])) {
                    $phrase_groups[$type] = self::deleteWeakPhrases(array_filter($phrase_groups[$type]));
                    $phrase_groups[$type] = self::addAnchors($phrase_groups[$type], true);

                    // if the user is limiting the number of suggestions to display
                    if(!empty($max_suggestions_displayed) && !empty($phrase_groups[$type])){
                        // trim back the number of suggestions to fit
                        $phrase_groups[$type] = array_slice($phrase_groups[$type], 0, $max_suggestions_displayed);
                    }
                }
            }

            //remove same suggestions on top level
            foreach($phrase_groups as $phrases){
                foreach ($phrases as $key => $phrase) {
                    if(empty($phrase->suggestions) || !isset($phrase->suggestions[0])){
                        unset($phrases[$key]);
                        continue;
                    }

                    if(is_a($phrase->suggestions[0]->post, 'Wpil_Model_ExternalPost')){
                        $post_key = ($phrase->suggestions[0]->post->type=='term'?'ext_cat':'ext_post') . $phrase->suggestions[0]->post->id;
                    }else{
                        $post_key = ($phrase->suggestions[0]->post->type=='term'?'cat':'') . $phrase->suggestions[0]->post->id;
                    }

                    if (!empty($target) || !in_array($post_key, $used_posts)) {
                        $used_posts[] = $post_key;
                    } else {
                        if (!empty(self::$undeletable)) {
                            $phrase->suggestions[0]->opacity = .5;
                        } else {
                            unset($phrase->suggestions[0]);
                        }

                    }

                    if (!count($phrase->suggestions)) {
                        unset($phrases[$key]);
                    } else {
                        if (!empty(self::$undeletable)) {
                            $i = 1;
                            foreach ($phrase->suggestions as $suggestion) {
                                $i++;
                                if ($i > 10) {
                                    $suggestion->opacity = .5;
                                }
                            }
                        } else {
                            $phrase->suggestions = array_slice($phrase->suggestions, 0, 1);
                        }
                    }
                }
            }

            $selected_categories = self::get_selected_categories();
            $taxes = get_object_taxonomies(get_post($post_id));
            $query_taxes = array();
            foreach($taxes as $tax){
                if(get_taxonomy($tax)->hierarchical){
                    $query_taxes[] = $tax;
                }
            }
            $categories = wp_get_object_terms($post_id, $query_taxes, ['fields' => 'all_with_object_id']);
            if (empty($categories) || is_a($categories, 'WP_Error')) {
                $categories = [];
            }

            if(empty($selected_categories) && !empty($categories)){
                $selected_categories = array_map(function($cat){ return $cat->term_taxonomy_id; }, $categories);
            }

            $same_tag = !empty(Wpil_Settings::get_suggestion_filter('same_tag'));
            $selected_tags = self::get_selected_tags();
            $taxes = get_object_taxonomies(get_post($post_id));
            $query_taxes = array();
            foreach($taxes as $tax){
                if(empty(get_taxonomy($tax)->hierarchical)){
                    $query_taxes[] = $tax;
                }
            }
            $tags = wp_get_object_terms($post_id, $query_taxes, ['fields' => 'all_with_object_id']);
            if (empty($tags) || is_a($tags, 'WP_Error')) {
                $tags = [];
            }

            if(empty($selected_tags) && !empty($tags)){
                $selected_tags = array_map(function($tag){ return $tag->term_taxonomy_id; }, $tags);
            }

            $select_post_types = Wpil_Settings::get_suggestion_filter('select_post_types') ? 1 : 0;
            $selected_post_types = self::getSuggestionPostTypes();
            $post_types = Wpil_Settings::getPostTypeLabels(Wpil_Settings::getPostTypes());
            $filter_time_format = Wpil_Toolbox::convert_date_format_from_js();
            include WP_INTERNAL_LINKING_PLUGIN_DIR . '/templates/linking_data_list_v2.php';
            // clear the suggestion cache now that we're done with it
            self::clearSuggestionProcessingCache($process_key, $post->id);
        }

        exit;
    }

    /** 
     * Saves the user's "Load without animation" setting so it's persistent between loads
     **/
    public static function ajax_save_animation_load_status(){
        if(isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'wpil-load-with-animation-nonce') && (isset($_POST['status']) || array_key_exists('status', $_POST))){
            update_user_meta(get_current_user_id(), 'wpil_disable_load_with_animation', (int)$_POST['status']);
        }
    }

    /**
     * Merges multiple arrays of phrase data into a single array suitable for displaying.
     **/
    public static function merge_phrase_suggestion_arrays($phrase_array = array(), $inbound_suggestions = false){

        if(empty($phrase_array)){
            return array();
        }

        $merged_phrases = array('internal_site' => array(), 'external_site' => array());
        if(true === $inbound_suggestions){ // a simpler process is used for the inbound suggestions // Note: not currently used but might be used for inbound external matches
            foreach($phrase_array as $phrase_batch){
                $unserialized_batch = maybe_unserialize($phrase_batch);
                if(!empty($unserialized_batch)){
                    $merged_phrases = array_merge($merged_phrases, $unserialized_batch);
                }
            }
        }else{
            foreach($phrase_array as $phrase_batch){
                $unserialized_batch = maybe_unserialize($phrase_batch);
                if(is_array($unserialized_batch) && !empty($unserialized_batch)){
                    foreach($unserialized_batch as $phrase_key => $phrase_obj){
                        // go over each suggestion in the phrase obj
                        foreach($phrase_obj->suggestions as $post_id => $suggestion){
                            if(is_a($suggestion->post, 'Wpil_Model_ExternalPost')){
                                if(!isset($merged_phrases['external_site'][$phrase_key])){
                                    $base_phrase = $phrase_obj;
                                    unset($base_phrase->suggestions);
                                    $merged_phrases['external_site'][$phrase_key] = $base_phrase;
                                }
                                $merged_phrases['external_site'][$phrase_key]->suggestions[] = $suggestion;
                            }else{
                                if(!isset($merged_phrases['internal_site'][$phrase_key])){
                                    $base_phrase = $phrase_obj;
                                    unset($base_phrase->suggestions);
                                    $merged_phrases['internal_site'][$phrase_key] = $base_phrase;
                                }
                                $merged_phrases['internal_site'][$phrase_key]->suggestions[] = $suggestion;
                            }
                        }
                    }
                }
            }
        }

        return $merged_phrases;
    }

    public static function getPostProcessCount($post){
        global $wpdb;
        //add all posts to array
        $post_count = 0;
        $exclude = self::getTitleQueryExclude($post);
        $post_types = implode("','", Wpil_Settings::getPostTypes());
        $exclude_categories = Wpil_Settings::getIgnoreCategoriesPosts();
        if (!empty($exclude_categories)) {
            $exclude_categories = " AND ID NOT IN (" . implode(',', $exclude_categories) . ") ";
        } else {
            $exclude_categories = '';
        }

        $results = $wpdb->get_results("SELECT COUNT('ID') AS `COUNT` FROM {$wpdb->prefix}posts WHERE `post_status` = 'publish' $exclude $exclude_categories AND post_type IN ('{$post_types}')");
        $post_count = $results[0]->COUNT;

        $taxonomies = Wpil_Settings::getTermTypes();
        if (!empty($taxonomies) && empty(self::get_selected_categories()) && empty(self::get_selected_tags())) {
            //add all categories to array
            $exclude = "";
            if ($post->type == 'term') {
                $exclude = " AND t.term_id != {$post->id} ";
            }

            $results = $wpdb->get_results("SELECT COUNT(t.term_id)  AS `COUNT` FROM {$wpdb->prefix}term_taxonomy tt LEFT JOIN {$wpdb->prefix}terms t ON tt.term_id = t.term_id WHERE tt.taxonomy IN ('" . implode("', '", $taxonomies) . "') $exclude");
            $post_count += $results[0]->COUNT;
        }

        return $post_count;
    }

    /**
     * Get link suggestions for the post
     *
     * @param $post_id
     * @param $ui
     * @param null $target_post_id
     * @return array|mixed
     */
    public static function getPostSuggestions($post, $target = null, $all = false, $keyword = null, $count = null, $process_key = 0)
    {
        $stemmed_ignore_words = Wpil_Settings::getStemmedIgnoreWords();
        $is_outbound = (empty($target)) ? true: false;

        if ($target) {
            $internal_links = Wpil_Post::getLinkedPostIDs($target, false);
        }else{

            $internal_links = get_transient('wpil_outbound_post_links' . $process_key);
            if(empty($internal_links)){
                $internal_links = Wpil_Report::getOutboundLinks($post);
                $internal_links = $internal_links['internal'];
                set_transient('wpil_outbound_post_links' . $process_key, Wpil_Toolbox::compress($internal_links), MINUTE_IN_SECONDS * 15);

            }else{
                $internal_links = Wpil_Toolbox::decompress($internal_links);
            }

        }

        $used_posts = [];
        foreach ($internal_links as $link) {
            if (!empty($link->post)) {
                $used_posts[] = ($link->post->type == 'term' ? 'cat' : '') . $link->post->id;
            }
        }

        //get all possible words from post titles
        $words_to_posts = self::getTitleWords($post, $target, $keyword, $count, $process_key);

        //get all posts with same category
        $result = self::getSameCategories($post, $process_key, $is_outbound);
        $category_posts = [];
        foreach ($result as $cat) {
            $category_posts[] = $cat->object_id;
        }

        $phrases = self::getOutboundPhrases($post, $process_key);

        //divide text to phrases
        foreach ($phrases as $key_phrase => $phrase) {
            //get array of unique sentence words cleared from ignore phrases
            if (!empty($_REQUEST['keywords'])) {
                $sentence = trim(preg_replace('/\s+/', ' ', $phrase->text));
                $words_uniq = array_map(function($word){ return Wpil_Stemmer::Stem($word); }, array_unique(Wpil_Word::getWords($sentence)));
            } else {
                // if this is an inbound scan
                if(!empty($target)){
                    $text = Wpil_Word::strtolower(Wpil_Word::removeEndings($phrase->text, ['.','!','?','\'',':','"']));
                    $words_uniq = array_unique(Wpil_Word::cleanFromIgnorePhrases($text));
                }else{
                    // if this is an outbound scan
                    $words_uniq = $phrase->words_uniq;
                }
            }

            $suggestions = [];
            foreach ($words_uniq as $word) {

                // copy the current state of the word and restemm it without accents to catch accent trouble
                $orig = $word;
                $restemm = Wpil_Stemmer::Stem(Wpil_Word::remove_accents($word), true);

                if (empty($_REQUEST['keywords']) && (in_array($word, $stemmed_ignore_words) || in_array($restemm, $stemmed_ignore_words))) {
                    continue;
                }

                //skip word if no one post title has this word
                if (empty($words_to_posts[$word])) {
                    // if the de-accented & restemmed version of the word is present
                    if(isset($words_to_posts[$restemm])){
                        // set the word to it
                        $word = $restemm;
                    }else{
                        continue;
                    }
                }

                //create array with all possible posts for current word
                foreach ($words_to_posts[$word] as $p) {
                    if (is_null($target)) {
                        $key = $p->type == 'term' ? 'cat' . $p->id : $p->id;
                    } else {
                        $key = $post->type == 'term' ? 'cat' . $post->id : $post->id;
                    }

                    if (in_array($key, $used_posts) || (isset($suggestions[$key]) && isset($suggestions[$key]['words']) && in_array($orig, $suggestions[$key]['words']))) {
                        continue;
                    }

                    //create new suggestion
                    if (empty($suggestions[$key])) {
                        //check if post have same category with main post
                        $same_category = false;
                        if ($p->type == 'post' && in_array($p->id, $category_posts)) {
                            $same_category = true;
                        }

                        if (!is_null($target)) {
                            $suggestion_post = $post;
                        } else {
                            $suggestion_post = $p;
                        }

                        // unset the suggestions post content if it's set
                        if(isset($suggestion_post->content)){
                            $suggestion_post->content = null;
                        }

                        $suggestions[$key] = [
                            'post' => $suggestion_post,
                            'post_score' => $same_category ? .5 : 0,
                            'words' => []
                        ];
                    }

                    //add new word to suggestion
                    if (!in_array($orig, $suggestions[$key]['words'])) {
                        $suggestions[$key]['words'][] = $orig;
                        $suggestions[$key]['post_score'] += 1;
                    }
                }
            }

            //check if suggestion has at least 2 words & is less than 10 words long, and then calculate count of close words
            foreach ($suggestions as $key => $suggestion) {
                if ((!empty($_REQUEST['keywords']) && count($suggestion['words']) != count(array_unique(explode(' ', $keyword))))
                    || (empty($_REQUEST['keywords']) && count($suggestion['words']) < 2)
                ) {
                    unset ($suggestions[$key]);
                    continue;
                }

                // get the suggestion's current length
                $suggestion['length'] = self::getSuggestionAnchorLength($phrase, $suggestion['words']);

                // if the suggested anchor is longer than 10 words
                if(self::get_max_anchor_length() < $suggestion['length']){
                    // see if we can trim up the suggestion to get under the limit
                    $trimmed_suggestion = self::adjustTooLongSuggestion($phrase, $suggestion);
                    // if we can
                    if( self::get_max_anchor_length() >= $trimmed_suggestion['length'] && 
                        count($suggestion['words']) >= 2)
                    {
                        // update the suggestion
                        $suggestion = $trimmed_suggestion;
                    }else{
                        // if we can't, remove the suggestion
                        unset($suggestions[$key]);
                        continue;
                    }
                }

                sort($suggestion['words']);

                $close_words = self::getMaxCloseWords($suggestion['words'], $suggestion['post']->getTitle());

                if ($close_words > 1) {
                    $suggestion['post_score'] += $close_words;
                }

                //calculate anchor score
                $close_words = self::getMaxCloseWords($suggestion['words'], $phrase->text);
                $suggestion['anchor_score'] = count($suggestion['words']);
                if ($close_words > 1) {
                    $suggestion['anchor_score'] += $close_words * 2;
                }
                $suggestion['total_score'] = $suggestion['anchor_score'] + $suggestion['post_score'];

                $phrase->suggestions[$key] = new Wpil_Model_Suggestion($suggestion);
            }

            if (!count($phrase->suggestions)) {
                unset($phrases[$key_phrase]);
                continue;
            }

            usort($phrase->suggestions, function ($a, $b) {
                if ($a->total_score == $b->total_score) {
                    return 0;
                }
                return ($a->total_score > $b->total_score) ? -1 : 1;
            });
        }

        // if we're processing outbound suggestions
        if(empty($target)){
            // remove all top-level suggestion post duplicates and leave the best suggestion as top
            $phrases = self::remove_top_level_suggested_post_repeats($phrases);
        }

        //remove same suggestions on top level
        foreach ($phrases as $key => $phrase) {
            if(empty($phrase->suggestions)){
                unset($phrases[$key]);
                continue;
            }
            $post_key = ($phrase->suggestions[0]->post->type=='term'?'cat':'') . $phrase->suggestions[0]->post->id;
            if (!empty($target) || !in_array($post_key, $used_posts)) {
                $used_posts[] = $post_key;
            } else {
                if (!empty(self::$undeletable)) {
                    $phrase->suggestions[0]->opacity = .5;
                } else {
                    unset($phrase->suggestions[0]);
                }

            }

            if (!count($phrase->suggestions)) {
                unset($phrases[$key]);
            } else {
                if (!empty(self::$undeletable)) {
                    $i = 1;
                    foreach ($phrase->suggestions as $suggestion) {
                        $i++;
                        if ($i > 10) {
                            $suggestion->opacity = .5;
                        }
                    }
                } else {
                    if (!$all) {
                        $phrase->suggestions = array_slice($phrase->suggestions, 0, 1);
                    }else{
                        $phrase->suggestions = array_values($phrase->suggestions);
                    }
                }
            }
        }

        $phrases = self::deleteWeakPhrases($phrases);

        return $phrases;
    }

    /**
     * Divide text to sentences
     *
     * @param $content
     * @return array
     */
    public static function getPhrases($content, $with_links = false, $word_segments = array(), $single_words = false, $ignore_text = array())
    {
        // get the section skip type and counts
        $section_skip_type = Wpil_Settings::getSkipSectionType();

        // replace unicode chars with their decoded forms
        $replace_unicode = array('\u003c', '\u003', '\u0022');
        $replacements = array('<', '>', '"');

        $content = str_ireplace($replace_unicode, $replacements, $content);

        // replace any base64ed image urls
        $content = preg_replace('`src="data:image\/(?:png|jpeg);base64,[\s]??[a-zA-Z0-9\/+=]+?"`', '', $content);
        $content = preg_replace('`alt="Source: data:image\/(?:png|jpeg);base64,[\s]??[a-zA-Z0-9\/+=]+?"`', '', $content);

        // decode page builder encoded sections
        $content = self::decode_page_builder_content($content);

        // remove the heading tags from the text
        $content = mb_ereg_replace('<h1(?:[^>]*)>(.*?)<\/h1>|<h2(?:[^>]*)>(.*?)<\/h2>|<h3(?:[^>]*)>(.*?)<\/h3>|<h4(?:[^>]*)>(.*?)<\/h4>|<h5(?:[^>]*)>(.*?)<\/h5>|<h6(?:[^>]*)>(.*?)<\/h6>', '', $content);

        // remove the head tag if it's present. It should only be present if processing a full page stored in the content
        if(false !== strpos($content, '<head')){
            $content = mb_ereg_replace('<head(?:[^>]*)>(.*?)<\/head>', '', $content);
        }

        // remove any title tags that might be present. These should only be present if processing a full page stored in the content
        if(false !== strpos($content, '<title')){
            $content = mb_ereg_replace('<title(?:[^>]*)>(.*?)<\/title>', '', $content);
        }

        // remove any meta tags that might be present. These should only be present if processing a full page stored in the content
        if(false !== strpos($content, '<meta')){
            $content = mb_ereg_replace('<meta(?:[^>]*)>(.*?)<\/meta>', '', $content);
        }

        // remove any link tags that might be present. These should only be present if processing a full page stored in the content
        if(false !== strpos($content, '<link')){
            $content = mb_ereg_replace('<link(?:[^>]*)>(.*?)<\/link>', '', $content);
        }

        // remove any script tags that might be present. We really don't want to suggest links for schema sections
        if(false !== strpos($content, '<script')){
            $content = mb_ereg_replace('<script(?:[^>]*)>(.*?)<\/script>', '', $content);
        }

        // if there happen to be any css tags, remove them too
        if(false !== strpos($content, '<style')){
            $content = mb_ereg_replace('<style(?:[^>]*)>(.*?)<\/style>', '', $content);
        }

        // if there are any 'pre' tags, remove them from the content
        if(false !== strpos($content, '<pre')){
            $content = mb_ereg_replace('<pre(?:[^>]*)>(.*?)<\/pre>', "\n", $content);
        }

        // remove page builder modules that will be turned into things like headings, buttons, and links
        $content = self::removePageBuilderModules($content);

        // remove elements that have certain classes
        $content = self::removeClassedElements($content);

        // encode the links in the content to avoid breaking them. (And to avoid cases where there's a line break char in the anchor, and when the text is split, we get half a linmk in two sentences... which are then discarded for safety!)
        $content = preg_replace_callback('|<a\s[^><]*?href=[\'\"][^><\'\"]*?[\'\"][^><]*?>[\s\S]*?<\/a>|i', function($i){ return str_replace($i[0], 'wpil-link-replace_' . base64_encode($i[0]), $i[0]); }, $content);

        // encode the contents of attributes so we don't have mistakes when breaking the content into sentences
        $content = preg_replace_callback('|(?:[a-zA-Z-]*?=["]([^"]*?)["])[^<>]*?|i', function($i){ return str_replace($i[1], 'wpil-attr-replace_' . base64_encode($i[1]), $i[0]); }, $content);
        $content = preg_replace_callback('/(?:[a-zA-Z-_0-9]*?=[\']((?:[\\]+?[\']|[^\'])*?)[\'])[^<>]*?/i', function($i){ return str_replace($i[1], 'wpil-attr-replace_' . base64_encode($i[1]), $i[0]); }, $content);

        // encode any supplied ignore text so we don't split sentences that are supposed to contain punctuation. (EX: Autolink keywords or sentences that contain Dr. or Mr.)
        $ignore_text = (is_string($ignore_text)) ? array($ignore_text): $ignore_text;
        $ignore_text = array_merge($ignore_text, self::getIgnoreTextDefaults());
        foreach($ignore_text as $text){
            $i_text = "(?<![[:alpha:]<>-_1-9])" . preg_quote($text, '/') . "(?![[:alpha:]<>-_1-9])";
            $content = preg_replace_callback('/' . $i_text . '/i' , function($i){ return str_replace($i[0], 'wpil-ignore-replace_' . base64_encode($i[0]), $i[0]); }, $content);
        }

        $i_text = "(?<![[:alpha:]<>-_1-9])(?:[A-Za-z]\.){2,20}(?![[:alpha:]<>-_1-9])"; // also run a regex for searching common abbreviations
        $content = preg_replace_callback('/' . $i_text . '/i' , function($i){ return str_replace($i[0], 'wpil-ignore-replace_' . base64_encode($i[0]), $i[0]); }, $content);

        // if the user want's to skip paragraphs
        if('paragraphs' === $section_skip_type){
            // remove the number he's selected
            $content = self::removeParagraphs($content);
        }

        //divide text to sentences
        $replace = [
            ['.<', '. ', '. ', '.&nbsp;', '.\\', '!<', '! ', '! ', '!\\', '?<', '? ', '? ', '?\\', '<div', '<br', '<li', '<p', '<h1', '<h2', '<h3', '<h4', '<h5', '<h6', '。', '<td', '</td>', '<ul', '</ul>', '<ol', '</ol>'],
            [".\n<", ". \n", ".\n", ".\n&nbsp;", ".\n\\", "!\n<", "! \n", "!\n", "!\n\\", "?\n<", "? \n", "?\n", "?\n\\", "\n<div", "\n<br", "\n<li", "\n<p", "\n<h1", "\n<h2", "\n<h3", "\n<h4", "\n<h5", "\n<h6", "\n。", "\n<td", "</td>\n", "\n<ul", "</ul>\n", "\n<ol", "</ol>\n"]
        ];
        $content = str_ireplace($replace[0], $replace[1], $content);
        $content = preg_replace('|\.([A-Z]{1})|', ".\n$1", $content);
        $content = preg_replace('|\[[^\]]+\]|i', "\n", $content);

        $list = explode("\n", $content);


        foreach($list as $key => $item){
            // decode all the attributes now that the content has been broken into sentences
            if(false !== strpos($item, 'wpil-attr-replace_')){
                $list[$key] = preg_replace_callback('|(?:[a-zA-Z-]*=["\'](wpil-attr-replace_([^"\']*?))["\'])[^<>]*?|i', function($i){
                    return str_replace($i[1], base64_decode($i[2]), $i[0]);
                }, $item);
            }

            // also decode any links
            if(false !== strpos($item, 'wpil-link-replace_')){
                $list[$key] = preg_replace_callback('/(?:wpil-link-replace_([A-z0-9=\/+]*))/', function($i){
                    return str_replace($i[0], base64_decode($i[1]), $i[0]);
                }, $item);
            }
        }

        $list = self::mergeSplitSentenceTags($list);
        self::removeEmptySentences($list, $with_links);
        self::trimTags($list, $with_links);

        // if the user want's to skip sentences
        if('sentences' === $section_skip_type){
            // remove the number he's selected
            $list = array_slice($list, Wpil_Settings::getSkipSentences());
        }

        $phrases = [];
        foreach ($list as $item) {
            $item = trim($item);

            if(!empty($word_segments)){
                // check if the phrase contains 2 title words
                $wcount = 0;
                foreach($word_segments as $seg){
                    if(false !== stripos($item, $seg)){
                        $wcount++;
                        if($wcount > 1){
                            break;
                        }
                    }
                }
                if($wcount < 2){
                    continue;
                }
            }

            if (in_array(substr($item, -1), ['.', ',', '!', '?', '。'])) {
                $item = substr($item, 0, -1);
            }

            // save the src before we decode the ignored txt
            $src_raw = $item;
            // decode the ignored txt
            $item = self::decodeIgnoredText($item);

            $sentence = [
                'src_raw' => $src_raw,
                'src' => $item,
                'text' => strip_tags(htmlspecialchars_decode($item))
            ];

            $sentence['text'] = trim($sentence['text']);

            //add sentence to array if it has at least 2 words
            if (!empty($sentence['text']) && ($single_words || count(explode(' ', $sentence['text'])) > 1)) {
                $phrases = array_merge($phrases, self::getPhrasesFromSentence($sentence, true));
            }
        }


        return $phrases;
    }

    /**
     * Decodes sections in the content that were created by page builders so we can accurately split the content up into phrases.
     * As always, this content is not meant to be saved, so it's alright if we change the formatting so we can process it.
     * We won't be decoding shortcodes because it's not possible to save links to shortcodes.
     * @param string $content The content to maybe decode content sections from
     * @return string $content The content that may have had it's content decoded
     **/
    public static function decode_page_builder_content($content = ''){
        // if the content contains a Thrive raw content shortcode, decode the shortcode content.
        if(false !== strpos($content, '___TVE_SHORTCODE_RAW__')){
            list( $start, $end ) = array(
                '___TVE_SHORTCODE_RAW__',
                '__TVE_SHORTCODE_RAW___',
            );
            if ( ! preg_match_all( "/{$start}((<p>)?(.+?)(<\/p>)?){$end}/s", $content, $matches, PREG_OFFSET_CAPTURE ) ) {
                return $content;
            }
        
            $position_delta = 0;
            foreach ( $matches[1] as $i => $data ) {
                $raw_shortcode = $data[0]; // the actual matched regexp group
                $position      = $matches[0][ $i ][1] + $position_delta; //the index at which the whole group starts in the string, at the current match
                $whole_group   = $matches[0][ $i ][0];
        
                $raw_shortcode = html_entity_decode( $raw_shortcode );//we keep the code encoded and now we need to decode
        
                $replacement = '</div><div class="tve_shortcode_rendered">' . $raw_shortcode;
        
                $content     = substr_replace( $content, $replacement, $position, strlen( $whole_group ) );
                /* increment the positioning offsets for the string with the difference between replacement and original string length */
                $position_delta += strlen( $replacement ) - strlen( $whole_group );
            }
        }

        return $content;
    }

    /**
     * Removes page builder created modules from the text content.
     * Since these heading elements are rendered by the builder, the normal HTML heading/link remover doesn't catch these.
     * Checks the text for the presence of the modules so we're not regexing the text unnecessarily
     * 
     * @param string $content The post content.
     * @return string $content The processed post content
     **/
    public static function removePageBuilderModules($content = ''){


        $fusion_regex = '';
        // remove fusion builder (Avada) titles if present
        if(false !== strpos($content, 'fusion_title')){
            $fusion_regex .= '\[fusion_title(?:[^\]]*)\](.*?)\[\/fusion_title\]';
        }
        if(false !== strpos($content, 'fusion_imageframe')){
            $fusion_regex .= '|\[fusion_imageframe(?:[^\]]*)\](.*?)\[\/fusion_imageframe\]';
        }
        if(false !== strpos($content, 'fusion_button')){
            $fusion_regex .= '|\[fusion_button(?:[^\]]*)\](.*?)\[\/fusion_button\]';
        }
        if(false !== strpos($content, 'fusion_gallery')){
            $fusion_regex .= '|\[fusion_gallery(?:[^\]]*)\](.*?)\[\/fusion_gallery\]';
        }
        if(false !== strpos($content, 'fusion_code')){
            $fusion_regex .= '|\[fusion_code(?:[^\]]*)\](.*?)\[\/fusion_code\]';
        }
        if(false !== strpos($content, 'fusion_modal')){
            $fusion_regex .= '|\[fusion_modal(?:[^\]]*)\](.*?)\[\/fusion_modal\]';
        }
        if(false !== strpos($content, 'fusion_menu')){
            $fusion_regex .= '|\[fusion_menu(?:[^\]]*)\](.*?)\[\/fusion_menu\]';
        }
        if(false !== strpos($content, 'fusion_modal_text_link')){
            $fusion_regex .= '|\[fusion_modal_text_link(?:[^\]]*)\](.*?)\[\/fusion_modal_text_link\]';
        }
        if(false !== strpos($content, 'fusion_vimeo')){
            $fusion_regex .= '|\[fusion_vimeo(?:[^\]]*)\](.*?)\[\/fusion_vimeo\]';
        }
        // if there is Fusion (Avada) content
        if(!empty($fusion_regex)){
            // remove any leading "|" since it would be bad to have it...
            $fusion_regex = ltrim($fusion_regex, '|');
            // remove the items we don't want to add links to
            $content = mb_ereg_replace($fusion_regex, "\n", $content);
        }

        $cornerstone_regex = '';
        // if a Cornerstone heading is present in the text
        if(false !== strpos($content, 'cs_element_headline')){
            $cornerstone_regex .= '\[cs_element_headline(?:[^\]]*)\]\[cs_content_seo\](.*?)\[\/cs_content_seo\]';
        }
        if(false !== strpos($content, 'x_custom_headline')){
            $cornerstone_regex .= '|\[x_custom_headline(?:[^\]]*)\](.*?)\[\/x_custom_headline\]';
        }
        if(false !== strpos($content, 'x_image')){
            $cornerstone_regex .= '|\[x_image(?:[^\]]*)\](.*?)\[\/x_image\]';
        }
        if(false !== strpos($content, 'x_button')){
            $cornerstone_regex .= '|\[x_button(?:[^\]]*)\](.*?)\[\/x_button\]';
        }
        if(false !== strpos($content, 'cs_element_card')){
            $cornerstone_regex .= '|\[cs_element_card(?:[^\]]*)\]\[cs_content_seo\](.*?)\[\/cs_content_seo\]';
        }

        // if there is Cornerstone content
        if(!empty($cornerstone_regex)){
            // remove any leading "|" since it would be bad to have it...
            $cornerstone_regex = ltrim($cornerstone_regex, '|');
            // remove the items we don't want to add links to
            $content = mb_ereg_replace($cornerstone_regex, "\n", $content); // Remove Cornerstone/X|Pro theme headings and items with links
        }

        return $content;
    }

    /**
     * Removes elements from the content that have certain classes.
     * Currently just removes twitter embedded tweets
     **/
    public static function removeClassedElements($content = ''){
        if(empty($content)){
            return $content;
        }

        // remove the twitter-tweet element as a standard remove if it's in the content
        if(false !== strpos($content, 'blockquote') && false !== strpos($content, 'twitter-tweet')){
            $content = mb_ereg_replace('<blockquote[\s][^>]*?(class=["\'][^"\']*?(twitter-tweet)[^"\']*?["\'])[^>]*?>.*?<(\/blockquote|\\\/blockquote)', '', $content);
        }

        return $content;
    }

    /**
     * Returns a filterable list of abbreviations that the sentence splitter should ignore.
     * Contains abbreviations that don't follow the (Letter + Period * (Repeat?)) pattern used for common abbrs. like "U.S.A."
     * There's a regex up in the sentence splitter that handles that.
     * This is for abbrs. that come in a specific format like "Prof." or "Ave."
     * @return array
     **/
    public static function getIgnoreTextDefaults(){
        return apply_filters('wpil_phrase_abbreviation_list', 
            array(  'Mr.', 'Mrs.', 'Ms.', 'Mssr.', 'Dr.', 'Prof.', 'Rev.', 'St.',
                    'Gen.',  'Col.', 'Maj.', 'Capt.', 'Lt.', 'Sgt.', 'Sr.', 'Jr.',
                    'Ave.', 'Blvd.', 'Co.', 'Inc.', 'Ltd.', 'Esq.', 'etc.', 'EX:',
                    'vs.', 'Dept.', 'Sec.', 'Treas.', 'Vol.', 'Ed.',
            )
        );
    }

    /**
     * Removes the user's selected number of paragraphs from the start of the post content
     **/
    public static function removeParagraphs($content){
        $skip_count = Wpil_Settings::getSkipSentences();

        if(empty($skip_count)){
            return $content;
        }

        // create an offset index for the tags we're searching for
        $char_count = array(
            'p' => 4,
            'div' => 6,
            'newline' => 2,
            'blockquote' => 12
        );

        $i = 0;
        $pos = 0;
        while($i < $skip_count && $pos < mb_strlen($content)){
            // search for the possible paragraph endings
            $pos_search = array(
                'p' => Wpil_Word::mb_strpos($content, '</p>', $pos),
                'div' => Wpil_Word::mb_strpos($content, '</div>', $pos),
                'newline' => Wpil_Word::mb_strpos($content, '\n', $pos), // newlines mainly apply to module-based builder content since we separate the modules with "\n"
                'blockquote' => Wpil_Word::mb_strpos($content, '</blockquote>', $pos)
            );

            // sort the results and remove the empties
            asort($pos_search);
            $pos_search = array_filter($pos_search);

            // exit if nothing is found
            if(empty($pos_search)){
                break;
            }

            // get the closest paragraph ending and it's type
            $temp_pos = reset($pos_search);
            $temp_ind = key($pos_search);

            // if the ending was a div
            if($temp_ind === 'div'){
                // see if there's an opening tag before the last pos
                $div_pos = Wpil_Word::mb_strpos($content, '<div', $pos);

                // if there is
                if(false !== $div_pos){
                    // check if there's any text between the tags
                    $div_content = mb_substr($content, $div_pos, ($temp_pos - $div_pos)); // full-length string ending - div start == div_content. If we don't remove the div start the string is too long
                    $div_content = trim(strip_tags(mb_ereg_replace('<a[^>]*>.*?</a>|<h[1-6][^>]*>.*?</h[1-6]>', '', $div_content))); // remove links, headings, strip tags that might have text attrs, and trim

                    // if there isn't any content, but there is a runner-up tag
                    if(empty($div_content) && count($pos_search) > 1){
                        // go with it's position since the div wasn't actually a paragraph
                        $slice = array_slice($pos_search, 1, 1);
                        $temp_pos = reset($slice);
                        $temp_ind = key($slice);
                    }
                }else{
                    // if there isn't an opening div tag, we're holding the tail of a container.
                    // So go with the runner-up tag
                    if(count($pos_search) > 1){
                        $slice = array_slice($pos_search, 1, 1);
                        $temp_pos = reset($slice);
                        $temp_ind = key($slice);
                    }
                }
            }

            $pos = ($temp_pos + $char_count[$temp_ind]);

            $i++;
        }

        // make sure that content is longer than the skip pos
        if(mb_strlen($content) > $pos){
            $content = mb_substr($content, $pos);
        }

        return $content;
    }

    /**
     * Get phrases from sentence
     */
    public static function getPhrasesFromSentence($sentence, $one_word = false)
    {
        $phrases = [];
        $replace = [', ', ': ', '; ', ' – ', ' (', ') ', ' {', '} '];
        $exceptions = ['&amp;' => '%%wpil-amp%%'];
        $src = $sentence['src_raw'];

        //change divided symbols inside tags to special codes
        preg_match_all('|<[^>]+>|', $src, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $tag) {
                $tag_replaced = $tag;
                foreach ($replace as $key => $value) {
                    if (strpos($tag, $value) !== false) {
                        $tag_replaced = str_replace($value, "[rp$key]", $tag_replaced);
                    }
                }

                if ($tag_replaced != $tag) {
                    $src = str_replace($tag, $tag_replaced, $src);
                }
            }
        }

        // except the exceptables
        foreach($exceptions as $key => $value){
            $src = str_replace($key, $value, $src);
        }

        //divide sentence to phrases
        $src = str_ireplace($replace, "\n", $src);

        // de-except the exceptables
        foreach(array_flip($exceptions) as $key => $value){
            $src = str_replace($key, $value, $src);
        }

        //change special codes to divided symbols inside tags
        foreach ($replace as $key => $value) {
            $src = str_replace("[rp$key]", $value, $src);
        }

        $list = explode("\n", $src);

        foreach ($list as $item) {
            $item = self::decodeIgnoredText($item);
            $phrase = new Wpil_Model_Phrase([
                'text' => trim(strip_tags(htmlspecialchars_decode($item))),
                'src' => $item,
                'sentence_text' => $sentence['text'],
                'sentence_src' => $sentence['src'],
            ]);

            if (!empty($phrase->text) && ($one_word || count(explode(' ', $phrase->text)) > 1)) {
                $phrases[] = $phrase;
            }
        }

        return $phrases;
    }

    /**
     * Decodes any ignored text.
     * @param string The text that is to be decoded
     **/
    public static function decodeIgnoredText($text = ''){
        if(false !== strpos($text, 'wpil-ignore-replace_')){
            $text = preg_replace_callback('/(?:wpil-ignore-replace_([A-z0-9=\/+]*))/', function($i){
                return str_replace($i[0], base64_decode($i[1]), $i[0]);
            }, $text);
        }

        return $text;
    }

    /**
     * Collect uniques words from all post titles
     *
     * @param $post_id
     * @param null $target
     * @return array
     */
    public static function getTitleWords($post, $target = null, $keyword = null, $count = null, $process_key = 0)
    {
        global $wpdb;
        $start = microtime(true);

        $ignore_words = Wpil_Settings::getIgnoreWords();
        $ignore_posts = Wpil_Settings::getIgnorePosts();
        $ignore_categories_posts = Wpil_Settings::getIgnoreCategoriesPosts();
        $ignore_numbers = get_option(WPIL_OPTION_IGNORE_NUMBERS, 1);
        $outbound_selected_posts = Wpil_Settings::getOutboundSuggestionPostIds();

        $posts = [];
        if (!is_null($target)) {
            $posts[] = $target;
        } else {
            $limit  = Wpil_Settings::getProcessingBatchSize();
            $post_ids = get_transient('wpil_title_word_ids_' . $process_key);
            if(empty($post_ids) && !is_array($post_ids)){
                //add all posts to array
                $exclude = self::getTitleQueryExclude($post);
                $post_types = implode("','", self::getSuggestionPostTypes());

                // get all posts in the same language if translation active
                $include = "";
                $check_language_ids = false;
                $ids = array();
                if (Wpil_Settings::translation_enabled()) {
                    $ids = Wpil_Post::getSameLanguagePosts($post->id);

                    if(!empty($ids) && count($ids) < $limit){
                        $include = " AND ID IN (" . implode(', ', $ids) . ") ";
                    }elseif(!empty($ids) && count($ids) > $limit){
                        $check_language_ids = true;
                    }else{
                        $include = " AND ID IS NULL ";
                    }
                }

                $statuses_query = Wpil_Query::postStatuses();
                $post_ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE 1=1 $exclude AND post_type IN ('{$post_types}') $statuses_query " . $include);

                if(empty($post_ids)){
                    $post_ids = array();
                }

                // if we have a lot of language ids to check
                if(!empty($post_ids) && $check_language_ids){
                    // find all the ids that are in the same language
                    $post_ids = array_intersect($post_ids, $ids);
                }

                set_transient('wpil_title_word_ids_' . $process_key, $post_ids, MINUTE_IN_SECONDS * 15);
            }

            // if we're limiting outbound suggestions to specfic posts
            if(empty($target) && !empty($outbound_selected_posts) && !empty($result)){
                // get all of the ids that the user wants to make suggestions to
                $ids = array();
                foreach($outbound_selected_posts as $selected_post){
                    if(false !== strpos($selected_post, 'post_')){
                        $ids[substr($selected_post, 5)] = true;
                    }
                }

                // filter out all the items that aren't in the outbound suggestion limits
                $result_items = array();
                foreach($result as $item){
                    if(isset($ids[$item->ID])){
                        $result_items[] = $item;
                    }
                }

                // update the results with the filtered ids
                $result = $result_items;
            }


            $posts = [];
            $process_ids = array_slice($post_ids, 0, $limit);

            if(!empty($process_ids)){
                $process_ids = implode("', '", $process_ids);
                $result = $wpdb->get_results("SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE ID IN ('{$process_ids}')");

                foreach ($result as $item) {
                    if (!in_array('post_' . $item->ID, $ignore_posts) && !in_array($item->ID, $ignore_categories_posts)) {
                        $post_obj = new Wpil_Model_Post($item->ID);
                        $post_obj->title = $item->post_title;
                        $posts[] = $post_obj;
                    }
                }

                // remove this batch of post ids from the list and save the list
                $save_ids = array_slice($post_ids, $limit);
                set_transient('wpil_title_word_ids_' . $process_key, $save_ids, MINUTE_IN_SECONDS * 15);
            }

            // if terms are to be scanned, but the user is restricting suggestions by term, don't search for terms to link to. Only search for terms if:
            if (    !empty(Wpil_Settings::getTermTypes()) && // terms have been selected
                    empty(Wpil_Settings::get_suggestion_filter('same_category')) && // we're not restricting by category
                    empty(Wpil_Settings::get_suggestion_filter('same_tag'))) // and we're not restricting by tag
            {
                if (is_null($count) || $count == 0) {
                    //add all categories to array
                    $exclude = "";
                    if ($post->type == 'term') {
                        $exclude = " AND t.term_id != {$post->id} ";
                    }

                    $taxonomies = Wpil_Settings::getTermTypes();
                    $result = $wpdb->get_results("SELECT t.term_id FROM {$wpdb->prefix}term_taxonomy tt LEFT JOIN {$wpdb->prefix}terms t ON tt.term_id = t.term_id WHERE tt.taxonomy IN ('" . implode("', '", $taxonomies) . "') $exclude");

                    // if the user only wants to make outbound suggestions to specific categories
                    if(empty($target) && !empty($outbound_selected_posts) && !empty($result)){
                        // get all of the ids that the user wants to make suggestions to
                        $ids = array();
                        foreach($outbound_selected_posts as $selected_term){
                            if(false !== strpos($selected_term, 'term_')){
                                $ids[substr($selected_term, 5)] = true;
                            }
                        }

                        foreach($result as $key => $item){
                            if(!isset($ids[$item->term_id])){
                                unset($result[$key]);
                            }
                        }
                    }

                    foreach ($result as $term) {
                        if (!in_array('term_' . $term->term_id, $ignore_posts)) {
                            $posts[] = new Wpil_Model_Post($term->term_id, 'term');
                        }
                    }
                }
            }
        }

        $words = [];
        foreach ($posts as $key => $p) {
            //get unique words from post title
            if (!empty($keyword)) { 
                $title_words = array_unique(Wpil_Word::getWords($keyword));
            } else {
                $title = $p->getTitle();
                $title_words = array_map(function($w){ return trim(trim($w, '[]{}\'"()$&|'));}, array_unique(Wpil_Word::getWords($title)));
            }

            foreach ($title_words as $word) {
                $word = Wpil_Stemmer::Stem(Wpil_Word::strtolower($word));
                //check if word is not a number and is not in the ignore words list
                if (!empty($_REQUEST['keywords']) ||
                    (strlen($word) > 2 && !in_array($word, $ignore_words) && (!$ignore_numbers || !is_numeric(str_replace(['.', ',', '$'], '', $word))))
                ) {
                    $words[$word][] = $p;
                }
            }

            if ($key % 100 == 0 && microtime(true) - $start > 10) {
                break;
            }
        }

        return $words;
    }

    /**
     * Get max amount of words in group between sentence
     *
     * @param $words
     * @param $title
     * @return int
     */
    public static function getMaxCloseWords($words_used_in_suggestion, $phrase_text)
    {
        // get the individual words in the source phrase, cleaned of puncuation and spaces
        $phrase_text = Wpil_Word::getWords($phrase_text);

        // stem each word in the phrase text
        foreach ($phrase_text as $key => $value) {
            $phrase_text[$key] = Wpil_Stemmer::Stem(Wpil_Word::strtolower($value));
        }

        // loop over the phrase words, and find the largest grouping of the suggestion's words that occur in sequence in the phrase
        $max = 0;
        $temp_max = 0;
        foreach($phrase_text as $key => $phrase_word){
            if(in_array($phrase_word, $words_used_in_suggestion)){
                $temp_max++;
                if($temp_max > $max){
                    $max = $temp_max;
                }
            }else{
                if($temp_max > $max){
                    $max = $temp_max;
                }
                $temp_max = 0;
            }
        }

        return $max;
    }

    /**
     * Measures how long an anchor text suggestion would be based on the words from the match
     **/
    public static function getSuggestionAnchorLength($phrase = '', $words = array()){
        // get the anchor word indexes
        $anchor_words = self::getSuggestionAnchorWords($phrase->text, $words, true);

        // return the lenght of the anchor if we have the indexes, and zero if we don't
        return !empty($anchor_words) ? $anchor_words['max'] - $anchor_words['min'] + 1: 0;
    }

    /**
     * Adjusts long suggestions so they're shorter by removing words that aren't required for making suggestions.
     * Since LW uses ALL possible common words in making suggestions, it's possible that the matches will contain so many words that it trips the max anchor length check.
     * So an extremly valid suggestion will be removed because it's over qualified.
     * This function's job is to remove extra words that are less important to the overall suggestion to hopefully get under the limit.
     * 
     * @param object $phrase
     * @param array $suggestion The suggestion data that we're going to adjust. This is before the data is put into a suggestion object, so we're going to be dealing with an array
     **/
    public static function adjustTooLongSuggestion($phrase = array(), $suggestion = array()){
        if(empty($suggestion)){
            return $suggestion;
        }

        // get the suggested anchor words
        $anchor_words = self::getSuggestionAnchorWords($phrase->text, $suggestion['words']);

        if(empty($anchor_words)){
            return $suggestion;
        }

        // create a list of the matching words
        $temp_sentence = implode(' ', $anchor_words);
        // and create the inital map of the words
        $word_positions = array_map(function($word){ 
            return array(
                'word' => $word,            // the stemmed anchor word
                'value' => 0,               // the words matching value
                'significance' => array(),  // what gives the word meaning. (keyword|title match)
                'keyword_class' => array());// tag so we can tell if words are part of a keyword
            }, $anchor_words);

        // first, find any target keyword matches
        if(!empty($suggestion['matched_target_keywords'])){
            foreach($suggestion['matched_target_keywords'] as $kword_ind => $keyword){
                $pos = mb_strpos($temp_sentence, $keyword->stemmed);
                if(false !== $pos){
                    // get the string before and after the keyword
                    $before = mb_substr($temp_sentence, 0, $pos);
                    $after  = mb_substr($temp_sentence, ($pos + mb_strlen($keyword->stemmed)));

                    // now explode all of the strings to get the positions
                    $before = explode(' ', $before);
                    $keyword_bits = explode(' ', $keyword->stemmed);
                    $after  = explode(' ', $after);

                    //
                    $offset = (count($before) > 0) ? count($before) - 1: 0;

                    // and map the pieces so we can tell where the keyword is
                    foreach($keyword_bits as $ind => $bit){
                        $ind2 = ($ind + $offset);
                        $word_positions[$ind2]['value'] += 20;
                        $word_positions[$ind2]['significance'][] = 'keyword';
                        $word_positions[$ind2]['keyword_class'][] = 'keyword-' . $kword_ind;
                    }
                }
            }
        }

        // next get the matched title words
        $stemmed_title_words = Wpil_Word::getStemmedWords(Wpil_Word::strtolower($suggestion['post']->getTitle()));
        $title_count = count($stemmed_title_words);
        $position_count = count($word_positions);

        foreach($stemmed_title_words as $title_ind => $title_word){
            foreach($word_positions as $ind => $dat){
                if($dat['word'] === $title_word){
                    $word_positions[$ind]['value'] += 1;
                    $word_positions[$ind]['significance'][] = 'title-word';

                    // if this is the first word in the anchor & the first word in the post title
                    if($title_ind === 0 && $ind === 0){
                        // note it since it's more likely to be important
                        $word_positions[$ind]['significance'][] = 'first-title-word';
                        $word_positions[$ind]['significance'][] = 'title-position-match';
                        $word_positions[$ind]['value'] += 1;
                    }elseif($title_ind === ($title_count - 1) && $ind === ($position_count - 1)){
                        // if this is the last word anchor & the last word in the post title
                        // note it since it's more likely to be important
                        $word_positions[$ind]['significance'][] = 'last-title-word';
                        $word_positions[$ind]['significance'][] = 'title-position-match';
                        $word_positions[$ind]['value'] += 1;
                    }
                }
            }
        }

        // now that we've mapped the word positions, it's time to begin working on what words to remove.
        // first check the easy ones, are there any words on the edges of the sentence that aren't keywords
        $end = end($word_positions);
        $start = reset($word_positions);

        // check the end first
        if( in_array('title-word', $end['significance'], true) &&           // if it's a title word
            !in_array('last-title-word', $end['significance'], true) &&     // it's not the word in the post title
            !in_array('keyword', $end['significance'], true)                // and it's not a keyword
        ){
            // remove the last word from the possible anchor
            $word_positions = array_slice($word_positions, 0, count($word_positions) - 1);

            // remove any insignificant words that result
            $end = end($word_positions);
            if(empty($end['significance'])){
                $word_ind = (count($word_positions) - 1);
                while(empty($word_positions[$word_ind]) && $word_ind > 0){
                    $word_positions = array_slice($word_positions, 0, $word_ind);
                    $word_ind--;
                }
            }

            // if the proposed anchor is shorter than the limit
            if(count($word_positions) <= self::get_max_anchor_length()){
                // update the suggestion words
                $new_words = array_filter(array_map(function($data){ return (!empty($data['significance'])) ? $data['word']: false; }, $word_positions));
                $suggestion['words'] = $new_words;
                // say how long the suggestion is
                $suggestion['length'] = count($word_positions);
                // and return the suggestion
                return $suggestion;
            }
        }

        if( in_array('title-word', $start['significance'], true) && 
            !in_array('first-title-word', $start['significance'], true) && 
            !in_array('keyword', $start['significance'], true)
        ){
            // remove the first word from the possible anchor
            $word_positions = array_slice($word_positions, 1);

            // remove any insignificant words that result
            $start = reset($word_positions);
            if(empty($start['significance'])){
                $word_count = count($word_positions);
                $loop = 0;
                while(empty($word_positions[0]) && $loop < $word_count){
                    $word_positions = array_slice($word_positions, 1);
                    $loop++;
                }
            }

            // if the proposed anchor is shorter than the limit
            if(count($word_positions) <= self::get_max_anchor_length()){
                // update the suggestion words
                $new_words = array_filter(array_map(function($data){ return (!empty($data['significance'])) ? $data['word']: false; }, $word_positions));
                $suggestion['words'] = $new_words;
                // say how long the suggestion is
                $suggestion['length'] = count($word_positions);
                // and return the suggestion
                return $suggestion;
            }
        }

        // if we've made it this far, we weren't able to remove enough words to fit within the limit
        // Now we have to try and judge which words are most likely to not be missed

        // we'll go around 5 times at the most
        for($run = 0; $run < 5; $run++){
            // get the first and last words in the suggested anchor
            $first = $word_positions[0];
            $last = end($word_positions);

            // check if they're both keyword-words
            if( in_array('keyword', $first['significance'], true) &&
                in_array('keyword', $last['significance'], true))
            {
                // if they are, figure out which is the less valuable keyword and remove it
                $first_score = 0;
                $last_score = 0;

                foreach($word_positions as $dat){
                    $first_class = array_intersect($dat['keyword_class'], $first['keyword_class']);
                    $last_class = array_intersect($dat['keyword_class'], $last['keyword_class']);

                    if(!empty($first_class)){
                        $first_score += $dat['value'];
                    }

                    if(!empty($last_class)){
                        $last_score += $dat['value'];
                    }
                }

                // if the first word's score is lower than the last
                if($first_score < $last_score){
                    // remove words from the start
                    $temp = self::removeStartingWords($first, $word_positions);
                }else{
                    // if the last score is smaller or the same as the first, remove the words from the end
                    $temp = self::removeEndingWords($last, $word_positions);
                }
            }elseif($last['value'] < $first['value']){
                $temp = self::removeEndingWords($last, $word_positions);
            }elseif($last['value'] > $first['value']){
                $temp = self::removeStartingWords($first, $word_positions);
            }else{
                // if both words have the same value, remove the word(s) from the end of the anchor
                $temp = self::removeEndingWords($last, $word_positions);
            }

            // exit the loop if all we've managed to do is delete the text
            if(empty($temp)){
                break;
            }

            // update the word positions with the temp data
            $word_positions = $temp;

            // if the proposed anchor is shorter than the limit
            if(count($word_positions) <= self::get_max_anchor_length()){
                // update the suggestion words
                $new_words = array_filter(array_map(function($data){ return (!empty($data['significance'])) ? $data['word']: false; }, $word_positions));
                $suggestion['words'] = $new_words;
                // say how long the suggestion is
                $suggestion['length'] = count($word_positions);
                // and break out of the loop
                break;
            }
        }

        // now that we're done chewing the anchor text, return the suggestion
        return $suggestion;
    }

    /**
     * Removes words from the start of the suggested anchor
     **/
    private static function removeStartingWords($first, $word_positions){
        $anchr_len = count($word_positions);

        // if the first word is part of a keyword
        if( in_array('keyword', $first['significance'], true) && 
            isset($word_positions[1]) && 
            in_array('keyword', $word_positions[1]['significance'], true)
        ){
            // remove all of the words in this keyword and any insignificant words following it
            $ind = 0;
            $kword_class = $word_positions[$ind]['keyword_class'];
            while($ind < $anchr_len && (in_array('keyword', $word_positions[0]['significance'], true) || empty($word_positions[0]['significance']))){
                $same_class = array_intersect($word_positions[0]['keyword_class'], $kword_class);

                // if the word is part of the keyword(s) we started with or is just a filler word
                if(!empty($same_class) || empty($word_positions[0]['significance'])){
                    // remove it
                    $word_positions = array_slice($word_positions, 1);
                    // and increment the index for another pass
                    $ind++;
                }else{
                    // if we can't remove any more words, exit
                    break;
                }
            }
        }else{
            // if the word isn't part of a keyword, remove it and any insignificant words following it
            $ind = 0;
            $word_positions = array_slice($word_positions, 1);
            while($ind < ($anchr_len - 1) && empty($word_positions[0]['significance'])){
                // if the word is just a filler word
                if(empty($word_positions[0]['significance'])){
                    // remove it
                    $word_positions = array_slice($word_positions, 1);
                    // and increment the index for another pass
                    $ind++;
                }else{
                    break;
                }
            }
        }

        return $word_positions;
    }

    /**
     * Removes words from the end of the suggested anchor
     **/
    private static function removeEndingWords($last, $word_positions){
        $ind = count($word_positions) - 1;

        // if the last word is part of a keyword
        if( in_array('keyword', $last['significance'], true) && 
            isset($word_positions[$ind]) && 
            in_array('keyword', $word_positions[$ind]['significance'], true)
        ){
            // remove all of the words in this keyword and any insignificant words preceeding it
            $kword_class = $word_positions[$ind]['keyword_class'];
            while($ind >= 0 && (in_array('keyword', $word_positions[$ind]['significance'], true) || empty($word_positions[$ind]['significance']))){
                $same_class = array_intersect($word_positions[$ind]['keyword_class'], $kword_class);

                // if the word is part of the keyword(s) we started with or is just a filler word
                if(!empty($same_class) || (isset($word_positions[$ind]) && empty($word_positions[$ind]['significance']))){
                    // remove it
                    $word_positions = array_slice($word_positions, 0, $ind);
                    // and decrement the index for another pass
                    $ind--;
                }else{
                    // if we can't remove any more words, exit
                    break;
                }
            }
        }else{
            // if the word isn't part of a keyword, remove it and any insignificant words preceeding it
            $word_positions = array_slice($word_positions, 0, $ind);
            $ind--;
            while($ind >= 0 && empty($word_positions[$ind]['significance'])){
                // if the word is just a filler word
                if(isset($word_positions[$ind]) && empty($word_positions[$ind]['significance'])){
                    // remove it
                    $word_positions = array_slice($word_positions, 0, $ind);
                    // and decrement the index for another pass
                    $ind--;
                }else{
                    break;
                }
            }
        }

        return $word_positions;
    }

    /**
     * Add anchors to sentences
     *
     * @param $sentences
     * @return mixed
     */
    public static function addAnchors($phrases, $outbound = false)
    {
        if(empty($phrases)){
            return array();
        }

        $post = Wpil_Base::getPost();
        $used_anchors = ($_POST['type'] === 'outbound_suggestions') ? Wpil_Post::getAnchors($post) : array();
        $nbsp = urldecode('%C2%A0');

        $ignored_words = Wpil_Settings::getIgnoreWords();
        foreach ($phrases as $key_phrase => $phrase) {
            //prepare rooted words array from phrase
            $words = trim(preg_replace('/\s+|'.$nbsp.'/', ' ', $phrase->text));
            $words = $words_real = (!self::isAsianText()) ? array_map(function($a){ return trim($a, '():\''); /* remove the letters that going to be marked as "non-word chars" later */}, explode(' ', $words)) : mb_str_split(trim($words));
            foreach ($words as $key => $value) {
                $value = Wpil_Word::removeEndings($value, ['[', ']', '(', ')', '{', '}', '.', ',', '!', '?', '\'', ':', '"']);
                if (!empty($_REQUEST['keywords']) || !in_array($value, $ignored_words)) {
                    $words[$key] = Wpil_Stemmer::Stem(Wpil_Word::strtolower(strip_tags($value)));
                } else {
                    unset($words[$key]);
                }
            }

            foreach ($phrase->suggestions as $suggestion_key => $suggestion) {
                //get min and max words position in the phrase
                $anchor_indexes = self::getSuggestionAnchorWords($words, $suggestion->words, true);

                if(empty($anchor_indexes) || (empty($anchor_indexes['min']) && empty($anchor_indexes['max']))){
                    // if it can't, remove it from the list
                    unset($phrase->suggestions[$suggestion_key]);
                    // and proceed
                    continue;
                }

                $min = $anchor_indexes['min'];
                $max = $anchor_indexes['max'];

                // check to see if we can get a link in this suggestion
                $has_words = array_slice($words_real, $min, $max - $min + 1);
                if(empty($has_words) || ($max - $min) > Wpil_Settings::getSuggestionMaxAnchorSize()){
                    // if it can't, remove it from the list
                    unset($phrase->suggestions[$suggestion_key]);
                    // and proceed
                    continue;
                }

                //get anchors and sentence with anchor
                $anchor = ''; // TODO: Maybe rework this so that we first insert the link in the ->src, and then insert that in the ->sentence_src. Curently, it's possible to have sentences that contain 2 of the same keyword, but split on styling tags to show up at once. To fix this now, I'll filter out the duplicates, but since the secondary sentence was found, it is something to think aobut. Also I want to remember this just in case I have to go chasing duplicate suggestions again!
                $sentence_with_anchor = '<span class="wpil_word">' . implode('</span> <span class="wpil_word">', explode(' ', str_replace($nbsp, ' ', strip_tags($phrase->sentence_src, '<b><i><u><strong><em><code>')))) . '</span>';
                $sentence_with_anchor = str_replace(
                    [   ',</span>', 
                        '<span class="wpil_word">(', 
                        ')</span>', 
                        ':</span>', 
                        '<span class="wpil_word">\'', 
                        '\'</span>'
                    ], 
                    [   '</span><span class="wpil_word no-space-left wpil-non-word">,</span>', 
                        '<span class="wpil_word no-space-right wpil-non-word">(</span><span class="wpil_word">', 
                        '</span><span class="wpil_word no-space-left wpil-non-word">)</span>', 
                        '</span><span class="wpil_word no-space-left wpil-non-word">:</span>', 
                        '<span class="wpil_word no-space-right wpil-non-word">\'</span><span class="wpil_word">', 
                        '</span><span class="wpil_word no-space-left wpil-non-word">\'</span>'
                    ], $sentence_with_anchor);
                $sentence_with_anchor = self::formatTags($sentence_with_anchor);
                if ($max >= $min) {
                    if ($max == $min) {
                        $anchor = '<span class="wpil_word">' . $words_real[$min] . '</span>';
                        $to = '<a href="%view_link%">' . $anchor . '</a>';
                        $sentence_with_anchor = preg_replace('/'.preg_quote($anchor, '/').'/', $to, $sentence_with_anchor, 1);
                    } else {
                        $anchor = '<span class="wpil_word">' . implode('</span> <span class="wpil_word">', array_slice($words_real, $min, $max - $min + 1)) . '</span>';
                        $from = [
                            '<span class="wpil_word">' . $words_real[$min] . '</span>',
                            '<span class="wpil_word">' . $words_real[$max] . '</span>'
                        ];
                        $to = [
                            '<a href="%view_link%"><span class="wpil_word">' . $words_real[$min] . '</span>',
                            '<span class="wpil_word">' . $words_real[$max] . '</span></a>'
                        ];

                        $start_counts = substr_count($sentence_with_anchor, $from[0]);
                        if($start_counts > 1){
                            preg_match_all('/'.preg_quote($from[0], '/').'/', $sentence_with_anchor, $starts, PREG_OFFSET_CAPTURE);
                            preg_match('/'.preg_quote($from[1], '/').'/', $sentence_with_anchor, $end, PREG_OFFSET_CAPTURE);

                            if(!empty($starts) && !empty($end)){
                                $closest = 0;

                                foreach($starts[0] as $start){
                                    if($start[1] > $closest && $start[1] < $end[0][1]){
                                        $closest = $start[1];
                                    }
                                }

                                $maybe_start = substr($sentence_with_anchor, 0, $closest);

                                if(!empty($maybe_start) && !empty(preg_match('/'.preg_quote($maybe_start . $from[0], '/').'/', $sentence_with_anchor))){
                                    $from[0] = ($maybe_start . $from[0]);
                                    $to[0] = ($maybe_start . $to[0]);
                                }
                            }
                        }

                        $sentence_with_anchor = preg_replace('/'.preg_quote($from[0], '/').'/', $to[0], $sentence_with_anchor, 1);
                        $begin = strpos($sentence_with_anchor, '<a ');
                        if ($begin !== false) {
                            $first = substr($sentence_with_anchor, 0, $begin);
                            $second = substr($sentence_with_anchor, $begin);
                            $second = preg_replace('/'.preg_quote($from[1], '/').'/', $to[1], $second, 1);
                            $sentence_with_anchor = $first . $second;
                        }
                    }
                }

                // if a link couldn't be added to the sentence
                if(false === strpos($sentence_with_anchor, '<a ') || false === strpos($sentence_with_anchor, '</a>')){
                    // remove it from the list
                    unset($phrase->suggestions[$suggestion_key]);
                    // and proceed
                    continue;
                }

                self::setSentenceSrcWithAnchor($suggestion, $phrase->sentence_src, $words_real[$min], $words_real[$max]);

                //add results to suggestion
                $suggestion->anchor = $anchor;

                if (in_array(strip_tags($anchor), $used_anchors)) {
                    unset($phrases[$key_phrase]);
                }

                $suggestion->sentence_with_anchor = self::setSuggestionTags($sentence_with_anchor);
                $suggestion->original_sentence_with_anchor = $suggestion->sentence_with_anchor;
            }

            // if there are no suggestions, remove the phrase
            if(empty($phrase->suggestions)){
                unset($phrases[$key_phrase]);
            }
        }

        // if these are outbound suggestions
        if($outbound){
            // merge different suggestions from the same source sentence into the same sentence
            $phrases = self::mergeSourceTextPhrases($phrases);
        }else{
            // remove any suggestions that have the exact same suggestion text and anchor
            $known_suggestions = array();
            foreach($phrases as $key_phrase => $phrase){
                foreach($phrase->suggestions as $ind => $suggestion){
                    $key = md5($suggestion->original_sentence_with_anchor . $suggestion->post->id);
                    if(!isset($known_suggestions[$key])){
                        $known_suggestions[$key] = true;
                    }else{
                        unset($phrase->suggestions[$ind]);
                    }
                }

                // if there are no suggestions, remove the phrase
                if(empty($phrase->suggestions)){
                    unset($phrases[$key_phrase]);
                }
            }
        }

        return $phrases;
    }

    public static function formatTags($sentence_with_anchor){
        $tags = array(
            '<span class="wpil_word"><b>',
            '<span class="wpil_word"><i>',
            '<span class="wpil_word"><u>',
            '<span class="wpil_word"><strong>',
            '<span class="wpil_word"><em>',
            '<span class="wpil_word"><code>',
            '<span class="wpil_word"></b>',
            '<span class="wpil_word"></i>',
            '<span class="wpil_word"></u>',
            '<span class="wpil_word"></strong>',
            '<span class="wpil_word"></em>',
            '<span class="wpil_word"></code>',
            '<b></span>',
            '<i></span>',
            '<u></span>',
            '<strong></span>',
            '<em></span>',
            '<code></span>',
            '</b></span>',
            '</i></span>',
            '</u></span>',
            '</strong></span>',
            '</em></span>',
            '</code></span>'
        );

        // the replace tokens of the tags
        $replace_tags = array(
            '<span class="wpil_word wpil_suggestion_tag open-tag wpil-bold-open wpil-bold">PGI+</span><span class="wpil_word">', // these are the base64ed versions of the tags so we can process them later
            '<span class="wpil_word wpil_suggestion_tag open-tag wpil-ital-open wpil-ital">PGk+</span><span class="wpil_word">',
            '<span class="wpil_word wpil_suggestion_tag open-tag wpil-under-open wpil-under">PHU+</span><span class="wpil_word">',
            '<span class="wpil_word wpil_suggestion_tag open-tag wpil-strong-open wpil-strong">PHN0cm9uZz4=</span><span class="wpil_word">',
            '<span class="wpil_word wpil_suggestion_tag open-tag wpil-em-open wpil-em">PGVtPg==</span><span class="wpil_word">',
            '<span class="wpil_word wpil_suggestion_tag open-tag wpil-code-open wpil-code">PGNvZGU+</span><span class="wpil_word">',
            '<span class="wpil_word wpil_suggestion_tag close-tag wpil-bold-close wpil-bold">PC9iPg==</span><span class="wpil_word">',
            '<span class="wpil_word wpil_suggestion_tag close-tag wpil-ital-close wpil-ital">PC9pPg==</span><span class="wpil_word">',
            '<span class="wpil_word wpil_suggestion_tag close-tag wpil-under-close wpil-under">PC91Pg==</span><span class="wpil_word">',
            '<span class="wpil_word wpil_suggestion_tag close-tag wpil-strong-close wpil-strong">PC9zdHJvbmc+</span><span class="wpil_word">',
            '<span class="wpil_word wpil_suggestion_tag close-tag wpil-em-close wpil-em">PC9lbT4=</span><span class="wpil_word">',
            '<span class="wpil_word wpil_suggestion_tag close-tag wpil-code-close wpil-code">PC9jb2RlPg==</span><span class="wpil_word">',
            '</span><span class="wpil_word wpil_suggestion_tag open-tag wpil-bold-open wpil-bold">PGI+</span>', 
            '</span><span class="wpil_word wpil_suggestion_tag open-tag wpil-ital-open wpil-ital">PGk+</span>',
            '</span><span class="wpil_word wpil_suggestion_tag open-tag wpil-under-open wpil-under">PHU+</span>',
            '</span><span class="wpil_word wpil_suggestion_tag open-tag wpil-strong-open wpil-strong">PHN0cm9uZz4=</span>',
            '</span><span class="wpil_word wpil_suggestion_tag open-tag wpil-em-open wpil-em">PGVtPg==</span>',
            '</span><span class="wpil_word wpil_suggestion_tag open-tag wpil-code-open wpil-code">PGNvZGU+</span>',
            '</span><span class="wpil_word wpil_suggestion_tag close-tag wpil-bold-close wpil-bold">PC9iPg==</span>',
            '</span><span class="wpil_word wpil_suggestion_tag close-tag wpil-ital-close wpil-ital">PC9pPg==</span>',
            '</span><span class="wpil_word wpil_suggestion_tag close-tag wpil-under-close wpil-under">PC91Pg==</span>',
            '</span><span class="wpil_word wpil_suggestion_tag close-tag wpil-strong-close wpil-strong">PC9zdHJvbmc+</span>',
            '</span><span class="wpil_word wpil_suggestion_tag close-tag wpil-em-close wpil-em">PC9lbT4=</span>',
            '</span><span class="wpil_word wpil_suggestion_tag close-tag wpil-code-close wpil-code">PC9jb2RlPg==</span>',
        );

        return str_replace($tags, $replace_tags, $sentence_with_anchor);
    }

    /**
     * Add anchor to the sentence source
     *
     * @param $suggestion
     * @param $sentence
     * @param $word_start
     * @param $word_end
     */
    public static function setSentenceSrcWithAnchor(&$suggestion, $sentence, $word_start, $word_end)
    {
        $sentence .= ' ';
        $begin = strpos($sentence, $word_start . ' ');
        if($begin === false){
            $begin = strpos($sentence, $word_start);
        }
        while($begin && substr($sentence, $begin - 1, 1) !== ' ') {
            $begin--;
        }

        $end = strpos($sentence, $word_end . ' ', $begin);
        if(false === $end){
            $end = strpos($sentence, $word_end, $begin);
        }
        $end += strlen($word_end);
        while($end < strlen($sentence) && substr($sentence, $end, 1) !== ' ') {
            $end++;
        }

        $anchor = substr($sentence, $begin, $end - $begin);
        $replace = '<a href="%view_link%">' . $anchor . '</a>';
        $suggestion->sentence_src_with_anchor = preg_replace('/'.preg_quote($anchor, '/').'/', $replace, trim($sentence), 1);

    }

    public static function setSuggestionTags($sentence_with_anchor){
        // if there isn't a tag inside the suggested text, return it
        if(false === strpos($sentence_with_anchor, 'wpil_suggestion_tag')){
            return $sentence_with_anchor;
        }

        // see if the tag is inside the link
        $link_start = Wpil_Word::mb_strpos($sentence_with_anchor, '<a href="%view_link%"');
        $link_end = Wpil_Word::mb_strpos($sentence_with_anchor, '</a>', $link_start);
        $link_length = ($link_end + 4 - $link_start);
        $link = mb_substr($sentence_with_anchor, $link_start, $link_length);

        // if it's not or the open and close tags are in the link, return the link
        if(false === strpos($link, 'wpil_suggestion_tag') || (false !== strpos($link, 'open-tag') && false !== strpos($link, 'close-tag'))){ // todo make this tag specific. As it is now, we _could_ get the opening of one tag and the closing tag of another one since we're only looking for open and close tags. But considering that we've not had much trouble at all from the prior system, this isn't a priority.
            return $sentence_with_anchor;
        }

        // if we have the opening tag inside the link, move it right until it's outside the link
        if(false !== strpos($link, 'open-tag')){
            // get the tag start
            $open_tag = Wpil_Word::mb_strpos($sentence_with_anchor, '<span class="wpil_word wpil_suggestion_tag open-tag', $link_start);
            // extract the tag
            $tag = mb_substr($sentence_with_anchor, $open_tag, (Wpil_Word::mb_strpos($sentence_with_anchor, '</span>', $open_tag) + 7) - $open_tag);

            // get the text before the link
            $before = mb_substr($sentence_with_anchor, 0, $link_start);
            // get the text after the link
            $after = mb_substr($sentence_with_anchor, ($link_end + 4));
            // replace the tag in the link
            $link = mb_ereg_replace(preg_quote($tag), '', $link);
            // rebuild the sentence with the tag out side the link
            $sentence_with_anchor = ($before . $link . $tag . $after);

            // and re calibrate the search variables in case there's more that needs to be done
            $link_start = Wpil_Word::mb_strpos($sentence_with_anchor, '<a href="%view_link%"');
            $link_end = Wpil_Word::mb_strpos($sentence_with_anchor, '</a>', $link_start);
            $link_length = ($link_end + 4 - $link_start);
            $link = mb_substr($sentence_with_anchor, $link_start, $link_length);
        }

        // if we have the closing tag inside the link, move it left until it's outside the link
        if(false !== strpos($link, 'close-tag')){
            // get the tag start
            $close_tag = Wpil_Word::mb_strpos($sentence_with_anchor, '<span class="wpil_word wpil_suggestion_tag close-tag', $link_start);
            // extract the tag
            $tag = mb_substr($sentence_with_anchor, $close_tag, (Wpil_Word::mb_strpos($sentence_with_anchor, '</span>', $close_tag) + 7) - $close_tag);
            // replace the tag
            $sentence_with_anchor = mb_ereg_replace(preg_quote($tag), '', $sentence_with_anchor);
            // get the points before and after the link opening tag
            $before = mb_substr($sentence_with_anchor, 0, $link_start);
            $after = mb_substr($sentence_with_anchor, $link_start);
            // and insert the cloasing tag just before the link
            $sentence_with_anchor = ($before . $tag . $after);
        }

        return $sentence_with_anchor;
    }

    /**
     * Calculates the words used for anchor texts
     **/
    public static function getSuggestionAnchorWords($text, $words = array(), $return_indexes = false){
        if(is_string($text) && !empty($text)){
            // stem the sentence words
            $stemmed_phrase_words = array_map(array('Wpil_Stemmer', 'Stem'), Wpil_Word::getWords($text));
        }elseif(is_array($text) && !empty($text)){
            // if it's not a string, it was cleaned up somewhere else
            $stemmed_phrase_words = $text;
        }else{
            return false;
        }

        $min = count($stemmed_phrase_words);
        $max = 0;
        foreach ($words as $word) {
            if (in_array($word, $stemmed_phrase_words)) {
                $pos = array_search($word, $stemmed_phrase_words);
                $min = $pos < $min ? $pos : $min;
                $max = $pos > $max ? $pos : $max;
            }
        }

        $i = 0;
        while($i < 5 && $word_occurances = array_count_values(array_slice(array_filter($stemmed_phrase_words), $min, $max - $min + 1))){ // make sure that we're checking the current sentence for duplicate words
            if( array_key_exists($min, $stemmed_phrase_words) && 
                isset($word_occurances[$stemmed_phrase_words[$min]]) &&
                $word_occurances[$stemmed_phrase_words[$min]] > 1
            ){
                // get a list of the words past the current min
                $word_map = array_slice($stemmed_phrase_words, $min + 1, null, true);
                // go over each word
                foreach($word_map as $key => $stemmed_word){
                    // when we've found the next word in the sentence that is also one of the suggestion words
                    if (in_array($stemmed_word, $words)) {
                        // set the min for that word
                        $min = $key;
                        // and break the loop so we can check the next word
                        break;
                    }
                }
            }elseif( array_key_exists($max, $stemmed_phrase_words) && 
                isset($word_occurances[$stemmed_phrase_words[$max]]) &&
                $word_occurances[$stemmed_phrase_words[$max]] > 1
            ){
                $old_max = $max;
                $max = 0;
                foreach ($words as $word) {
                    if (in_array($word, $stemmed_phrase_words)) {
                        $pos = array_search($word, $stemmed_phrase_words);
                        $max = $pos > $max && $pos < $old_max ? $pos : $max;
                    }
                }
            }else{
                break;
            }

            $i++;
        }

        // if min is larger than max
        if($min > $max){
            // reverse them
            $old_max = $max;
            $max = $min;
            $min = $old_max;
        }

        // try obtaining the anchor text
        $anchor_words = array_slice($stemmed_phrase_words, $min, $max - $min + 1);

        // if we aren't able to
        if(empty($anchor_words)){
            // get the suggestion text start and end points
            $word_occurances = array_count_values(array_filter($stemmed_phrase_words));

            // try the old method of getting anchor text
            $intersect = array_keys(array_intersect($stemmed_phrase_words, $words));
            $min = current($intersect);
            $max = end($intersect);

            // check to make sure that if there's duplicates of the words
            // we use the anchor text with the smallest possible length
            $j = 0;
            while($j < 5){
                if( $min !== false &&
                    array_key_exists($min, $stemmed_phrase_words) && 
                    isset($word_occurances[$stemmed_phrase_words[$min]]) &&
                    $word_occurances[$stemmed_phrase_words[$min]] > 1
                ){
                    reset($intersect);
                    $min = next($intersect);
                }elseif( 
                    $max !== false && 
                    array_key_exists($max, $stemmed_phrase_words) && 
                    isset($word_occurances[$stemmed_phrase_words[$max]]) &&
                    $word_occurances[$stemmed_phrase_words[$max]] > 1
                ){
                    reset($intersect);
                    end($intersect);
                    $max = prev($intersect);
                }else{
                    break;
                }

                $j++;
            }

            $anchor_words = array_slice($stemmed_phrase_words, $min, $max - $min + 1);
        }

        if($return_indexes){
            return array('min' => $min, 'max' => $max);
        }else{
            return $anchor_words;
        }
    }

    /**
     * Merges phrases with the same source text into the same suggestion pool.
     * That way, users get a dropdown of different suggestions instead of a number of loose suggestions.
     * 
     * @param array $phrases 
     * @return array $merged_phrases
     **/
    public static function mergeSourceTextPhrases($phrases){
        $merged_phrases = array();
        $phrase_key_index = array();
        foreach($phrases as $phrase_key => $data){
            $phrase_key_index[$data->sentence_text] = $phrase_key;
        }

        foreach($phrases as $phrase_key => $data){
            $key = $phrase_key_index[$data->sentence_text];
            if(isset($merged_phrases[$key])){
                $merged_phrases[$key]->suggestions = array_merge($merged_phrases[$key]->suggestions, $data->suggestions);
            }else{
                $merged_phrases[$key] = $data;
            }
        }

        return $merged_phrases;
    }

    /**
     * Removes all repeat toplevel suggestions leaving only the best one to be presented to the user.
     * Loops around multiple times to make sure that we're only showing the top one per post on the top level.
     **/
    public static function remove_top_level_suggested_post_repeats($phrases){
        $count = 0;
        do{
            // sort the top-level suggestions so we can tell which suggestion has the highest score between the different phrases so we can remove the lower ranking ones
            $top_level_posts = array();
            foreach($phrases as $key => $phrase){
                if(empty($phrase->suggestions)){
                    unset($phrases[$key]);
                    continue;
                }

                $post_key = ($phrase->suggestions[0]->post->type=='term'?'cat':'') . $phrase->suggestions[0]->post->id;
                $tk_match = (isset($phrase->suggestions[0]->passed_target_keywords) && !empty($phrase->suggestions[0]->passed_target_keywords)) ? true: false;
                $top_level_posts[$post_key][] = (object)array('key' => $key, 'total_score' => $phrase->suggestions[0]->total_score, 'target_match' => $tk_match);
            }

            // sort the top-level posts so we can find the best suggestion for each phrase and build the list of suggestions to remove
            $remove_suggestions = array();
            foreach($top_level_posts as $post_key => $dat){
                // skip suggestions that are the only ones for their posts
                if(count($dat) < 2){
                    continue;
                }

                usort($top_level_posts[$post_key], function ($a, $b) {
                    if ($a->total_score == $b->total_score) {
                        return 0;
                    }
                    return ($a->total_score > $b->total_score) ? -1 : 1;
                });

                // remove the top suggestion from the list of suggestions to remove and make a list of the phrase keys
                $remove_suggestions[$post_key] = array_map(function($var){ return $var->key; }, array_slice($top_level_posts[$post_key], 1));
            }

            // go over the phrases and remove any suggested links that are not the top-level ones
            foreach($phrases as $key => $phrase){
                $post_key = ($phrase->suggestions[0]->post->type=='term'?'cat':'') . $phrase->suggestions[0]->post->id;

                // skip any that aren't on the list
                if(!isset($remove_suggestions[$post_key])){
                    continue;
                }

                // if the phrase is listed in the remove list
                if(in_array($key, $remove_suggestions[$post_key], true)){

                    // remove the suggestion
                    $suggestions = array_slice($phrase->suggestions, 1);
                    // if this is the only suggestion
                    if(empty($suggestions)){
                        // remove the phrase from the list to consider
                        unset($phrases[$key]);
                    }else{
                        // if it wasn't the only suggestion for the phrase, update the list of suggestions
                        $phrases[$key]->suggestions = $suggestions;
                    }
                }
            }

            // exit if we've gotten into endless looping
            if($count > 100){ // todo: create some kind of "Link Whisper System Instability Report" that will tell users if the plugin is getting wasty or caught in loops or anything like that.
                break;
            }
            $count++;
        }while(!empty($remove_suggestions));

        return $phrases;
    }

    /**
     * Delete phrases with sugeestion point < 3
     *
     * @param $phrases
     * @return array
     */
    public static function deleteWeakPhrases($phrases)
    {
        // return the phrases without trimming if there's less than 10 of them
        if (count($phrases) <= 10) {
            return $phrases;
        }

        // go over the phrases and count how many have > 3 points in post score
        $three_and_more = 0;
        foreach ($phrases as $key => $phrase) {
            if(!isset($phrase->suggestions[0])){
                unset($phrases[$key]);
                continue;
            }
            if ($phrase->suggestions[0]->post_score >=3) {
                $three_and_more++;
            }
        }

        // if we have less than 10 posts scoring 3 or higher
        if ($three_and_more < 10) {
            foreach ($phrases as $key => $phrase) {
                // find the suggestions that score less than 3 points
                if ($phrase->suggestions[0]->post_score < 3) {
                    // if we still don't have 10 suggestions
                    if ($three_and_more < 10) {
                        // increment the 3 and more counter so that this suggestion is allowed
                        $three_and_more++;
                    } else {
                        // when we've hit the limit, trim off all the other suggestions
                        unset($phrases[$key]);
                    }
                }
            }
        } else {
            // if we have 10 posts that score 3 or more
            foreach ($phrases as $key => $phrase) {
                // trimm off all the suggestions that score less than 3 points
                if ($phrase->suggestions[0]->post_score < 3) {
                    unset($phrases[$key]);
                }
            }
        }

        return $phrases;
    }

    /**
     * Merges sentences that have been split on styling tags back into a single sentence
     **/
    public static function mergeSplitSentenceTags($list = array()){
        if(empty($list)){
            return $list;
        }

        $tags = array(
            'b' => array('opening' => array('<b>'), 'closing' => array('</b>', '<\/b>')),
            'strong' => array('opening' => array('<strong>'), 'closing' => array('</strong>', '<\/strong>')),
            'code' => array('opening' => array('<code>'), 'closing' => array('</code>', '<\/code>'))
        );

        $updated_list = array();
        $skip_until = false;
        foreach($list as $key => $item){
            if($skip_until !== false && $skip_until >= $key){
                continue;
            }

            $sentence = '';
            // look over the tags
            foreach($tags as $tag => $dat){
                // if the string has an opening tab, but not a closing one
                if(self::hasStringFromArray($item, $dat['opening']) && !self::hasStringFromArray($item, $dat['closing'])){
                    // find a string that contains the closing tag
                    foreach(array_slice($list, $key, null, true) as $sub_key => $sub_item){
                        $sentence .= $sub_item;
                        $skip_until = $sub_key;
                        if(self::hasStringFromArray($sub_item, $dat['closing'])){
                            break 2;
                        }
                    }
                }
            }

            // add the sentence to the list
            $updated_list[$key] = !empty($sentence) ? $sentence: $item;
        }

        return $updated_list;
    }

    /**
     * Searches a string for the presence of any strings from an array.
     * 
     * @return bool Returns true if the string contains a string from the search array. Returns false if the string does not contain a search string or is empty.
     **/
    public static function hasStringFromArray($string = '', $search = array()){
        if(empty($string) || empty($search)){
            return false;
        }

        foreach($search as $s){
            // if the string contains the searched string, return true
            if(false !== strpos($string, $s)){
                return true;
            }
        }

        return false;
    }

    /**
     * Remove empty sentences from the list
     *
     * @param $sentences
     */
    public static function removeEmptySentences(&$sentences, $with_links = false)
    {
        $prev_key = null;
        foreach ($sentences as $key => $sentence)
        {
            //Remove text from alt and title attributes
            $pos = 0;
            // if the previous sentence has an alt or title attr
            if ($prev_key && ($pos = strpos($prev_sentence, 'alt="') !== false || $pos = strpos($prev_sentence, 'title="') !== false)) {
                // if there's a closing quote after the 
                if (isset($sentences[$prev_key]) && strpos($sentences[$prev_key], '"', $pos) == false) {
                    $pos = strpos($sentence, '"');
                    if ($pos !== false) {
                        $sentences[$key] = substr($sentence, $pos + 1);
                    } else {
                        unset ($sentences[$key]);
                    }
                }
            }
            $prev_sentence = $sentence;

            $endings = ['</h1>', '</h2>', '</h3>'];

            if (!$with_links) {
                $endings[] = '</a>';
            }

            if (in_array(trim($sentence), $endings) && $prev_key) {
                unset($sentences[$prev_key]);
            }
            if (empty(trim(strip_tags($sentence)))) {
                unset($sentences[$key]);
            }

            if (substr($sentence, 0, 5) == '<!-- ' && substr($sentence, 0, -4) == ' -->') {
                unset($sentences[$key]);
            }

            $cleaned = trim(Wpil_Word::clearFromUnicode($sentence));
            if('&nbsp;' === $cleaned || empty($cleaned)){
                unset($sentences[$key]);
            }

            $prev_key = $key;
        }
    }

    /**
     * Remove tags from the beginning and the ending of the sentence
     *
     * @param $sentences
     */
    public static function trimTags(&$sentences, $with_links = false)
    {
        foreach ($sentences as $key => $sentence)
        {
            if (strpos($sentence, '<h') !== false || strpos($sentence, '</h') !== false) {
                unset($sentences[$key]);
                continue;
            }

            if (!$with_links && (
                strpos($sentence, '<a ') !== false || strpos($sentence, '</a>') !== false ||
                strpos($sentence, '<ta ') !== false || strpos($sentence, '</ta>') !== false ||
                strpos($sentence, '<img ') !== false || strpos($sentence, 'wp:image') !== false)
            ){
                unset($sentences[$key]);
                continue;
            }

            if (substr_count($sentence, '<a ') >  substr_count($sentence, '</a>')
            ) {
                // check and see if we've split the anchor by mistake
                if( isset($sentences[$key + 1]) && // if there's a sentence after this one
                    substr_count($sentence . $sentences[$key + 1], '<a ') === substr_count($sentence . $sentences[$key + 1], '</a>') // and adding that sentence to this one equalizes the tag count
                ){
                    // update the next sentence with this full one so we can process it in it's entirety
                    $sentences[$key + 1] = $sentence . $sentences[$key + 1];
                }

                // then unset the current sentence and skip to the next one
                unset($sentences[$key]);
                continue;
            }

            if (substr_count($sentence, '<ta ') >  substr_count($sentence, '</ta>')
            ) {
                // check and see if we've split the anchor by mistake
                if( isset($sentences[$key + 1]) && // if there's a sentence after this one
                    substr_count($sentence . $sentences[$key + 1], '<ta ') === substr_count($sentence . $sentences[$key + 1], '</ta>') // and adding that sentence to this one equalizes the tag count
                ){
                    // update the next sentence with this full one so we can process it in it's entirety
                    $sentences[$key + 1] = $sentence . $sentences[$key + 1];
                }

                // then unset the current sentence and skip to the next one
                unset($sentences[$key]);
                continue;
            }

            $sentence = trim($sentences[$key]);
            while (substr($sentence, 0, 1) == '<' || substr($sentence, 0, 1) == '[') {
                $end_char = substr($sentence, 0, 1) == '<' ? '>' : ']';
                $end = strpos($sentence, $end_char);
                $tag = substr($sentence, 0, $end + 1);
                if (in_array($tag, ['<b>', '<i>', '<u>', '<strong>', '<em>', '<code>'])) {
                    break;
                }
                if (substr($tag, 0, 3) == '<a ' || substr($tag, 0, 4) == '<ta ') {
                    break;
                }
                $sentence = trim(trim(trim(substr($sentence, $end + 1)), ','));
            }

            while (substr($sentence, -1) == '>' || substr($sentence, -1) == ']') {
                $start_char = substr($sentence, -1) == '>' ? '<' : '[';
                $start = strrpos($sentence, $start_char);
                $tag = substr($sentence, $start);
                if (in_array($tag, ['</b>', '</i>', '</u>', '</strong>', '<em>', '<code>', '</a>', '</ta>'])) {
                    break;
                }
                $sentence = trim(trim(trim(substr($sentence, 0, $start)), ','));
            }

            $sentences[$key] = $sentence;
        }
    }

    /**
     * Generate subquery to search posts or products only with same categories
     *
     * @param $post
     * @return string
     */
    public static function getTitleQueryExclude($post)
    {
        global $wpdb;

        $exclude = "";
        $linked_posts = array();

        if ($post->type == 'post') {
            $redirected = Wpil_Settings::getRedirectedPosts();  // ignore any posts that are hidden by redirects
            $redirected[] = $post->id;                          // ignore the current post
            foreach($linked_posts as $link){
                if(!empty($link->post) && $link->post->type === 'post'){
                    $redirected[] = $link->post->id;
                }
            }
            $redirected = implode(', ', $redirected);
            $exclude .= " AND ID NOT IN ({$redirected}) ";
        }

        if (!empty(Wpil_Settings::get_suggestion_filter('same_category'))) {
            if ($post->type === 'post') {
                if (!empty(Wpil_Settings::get_suggestion_filter('selected_category'))) {
                    $categories = self::get_selected_categories();
                } else {
                    $taxes = get_object_taxonomies(get_post($post->id));
                    $query_taxes = array();
                    foreach($taxes as $tax){
                        if(get_taxonomy($tax)->hierarchical){
                            $query_taxes[] = $tax;
                        }
                    }
                    $categories = wp_get_object_terms($post->id, $query_taxes, ['fields' => 'tt_ids']);
                }
                foreach($linked_posts as $link){
                    if(!empty($link->post) && $link->post->type === 'term'){
                        $categories[] = $link->post->id;
                    }
                }
                $categories = count($categories) ? implode(',', $categories) : "''";
                $exclude .= " AND ID in (select object_id from {$wpdb->prefix}term_relationships where term_taxonomy_id in ($categories))";
            }
        }

        if (!empty(Wpil_Settings::get_suggestion_filter('same_tag'))) {
            if ($post->type === 'post') {
                if (!empty(Wpil_Settings::get_suggestion_filter('selected_tag'))) {
                    $tags = self::get_selected_tags();
                } else {
                    $taxes = get_object_taxonomies(get_post($post->id));
                    $query_taxes = array();
                    foreach($taxes as $tax){
                        if(empty(get_taxonomy($tax)->hierarchical)){
                            $query_taxes[] = $tax;
                        }
                    }
                    $tags = wp_get_object_terms($post->id, $query_taxes, ['fields' => 'tt_ids']);
                }
                foreach($linked_posts as $link){
                    if(!empty($link->post) && $link->post->type === 'term'){
                        $tags[] = $link->post->id;
                    }
                }
                $tags = count($tags) ? implode(',', $tags) : "''";
                $exclude .= " AND ID in (select object_id from {$wpdb->prefix}term_relationships where term_taxonomy_id in ($tags))";
            }
        }

        return $exclude;
    }

    /**
     * Gets the post types for use in making suggestions.
     * Looks to see if the user has selected any post types from the suggestion panel.
     * If he has, then it returns the post types the user has selected. Otherwise, it returns the post types from the LW Settings
     **/
    public static function getSuggestionPostTypes(){

        // get the post types from the settings
        $post_types = Wpil_Settings::getPostTypes();

        // if the user has selected post types from the suggestion panel
        if( Wpil_Settings::get_suggestion_filter('select_post_types'))
        {
            // obtain the selected post types
            $user_selected = Wpil_Settings::get_suggestion_filter('selected_post_types');
            if(!empty($user_selected)){
                // check to make sure the supplied post types are ones that are in the settings
                $potential_types = array_intersect($post_types, $user_selected);

                // if there are post types, set the current post types for the selected ones
                if(!empty($potential_types)){
                    $post_types = $potential_types;
                }
            }
        }

        return $post_types;
    }

    /**
     * Gets the phrases from the current post for use in outbound linking suggestions.
     * Caches the phrase data so subsequent requests are faster
     * 
     * @param $post The post object we're getting the phrases from.
     * @param int $process_key The ajax processing key for the current process.
     * @return array $phrases The phrases from the given post
     **/
    public static function getOutboundPhrases($post, $process_key){
        // try getting cached phrase data
        $phrases = get_transient('wpil_processed_phrases_' . $process_key);

        // if there aren't any phrases, process them now
        if(empty($phrases)){
            $phrases = self::getPhrases($post->getContent());

            //divide text to phrases
            foreach ($phrases as $key_phrase => &$phrase) {
                // replace any punctuation in the text and lower the string
                $text = Wpil_Word::strtolower(Wpil_Word::removeEndings($phrase->text, ['.','!','?','\'',':','"']));

                //get array of unique sentence words cleared from ignore phrases
                if (!empty($_REQUEST['keywords'])) {
                    $sentence = trim(preg_replace('/\s+/', ' ', $text));
                    $words_uniq = Wpil_Word::getWords($sentence);
                } else {
                    $words_uniq = Wpil_Word::cleanIgnoreWords(Wpil_Word::cleanFromIgnorePhrases($text)); // NOTE: If we get a lot of customers asking where their suggestions went in ver 2.3.5, "CleaningTheIgnoreWords" is the most likely cause.
                }

                // remove words less than 3 letters long and stem the words
                foreach($words_uniq as $key => $word){
                    if(strlen($word) < 3){
                        unset($words_uniq[$key]);
                        continue;
                    }

                    $words_uniq[$key] = Wpil_Stemmer::Stem($word);
                }

                $phrase->words_uniq = $words_uniq;
            }

            $save_phrases = Wpil_Toolbox::compress($phrases);
            set_transient('wpil_processed_phrases_' . $process_key, $save_phrases, MINUTE_IN_SECONDS * 15);
            reset($phrases);
            unset($save_phrases);
        }else{
            $phrases = Wpil_Toolbox::decompress($phrases);
        }

        return $phrases;
    }

    /**
     * Gets the categories that are assigned to the current post.
     * If we're doing an outbound scan, it caches the cat ids so they can be pulled up without a query
     **/
    public static function getSameCategories($post, $process_key = 0, $is_outbound = false){
        global $wpdb;

        if($is_outbound){
            $cats = get_transient('wpil_post_same_categories_' . $process_key);
            if(empty($cats) && !is_array($cats)){
                $cats = $wpdb->get_results("SELECT object_id FROM {$wpdb->prefix}term_relationships where term_taxonomy_id in (SELECT r.term_taxonomy_id FROM {$wpdb->prefix}term_relationships r inner join {$wpdb->prefix}term_taxonomy t on t.term_taxonomy_id = r.term_taxonomy_id where r.object_id = {$post->id} and t.taxonomy = 'category')");
            
                if(empty($cats)){
                    $cats = array();
                }

                set_transient('wpil_post_same_categories_' . $process_key, $cats, MINUTE_IN_SECONDS * 15);
            }
            
        }else{
            $cats = $wpdb->get_results("SELECT object_id FROM {$wpdb->prefix}term_relationships where term_taxonomy_id in (SELECT r.term_taxonomy_id FROM {$wpdb->prefix}term_relationships r inner join {$wpdb->prefix}term_taxonomy t on t.term_taxonomy_id = r.term_taxonomy_id where r.object_id = {$post->id} and t.taxonomy = 'category')");
        }

        return $cats;
    }

    /**
     * Clears the cached data when the suggestion processing is complete
     * 
     * @param int $processing_key The id of the suggestion processing run.
     **/
    public static function clearSuggestionProcessingCache($processing_key = 0, $post_id = 0){
        // clear the suggestions
        delete_transient('wpil_post_suggestions_' . $processing_key);
        // clear any cached inbound links cache
        delete_transient('wpil_stored_post_internal_inbound_links_' . $post_id);
        // clear the processed phrase cache
        delete_transient('wpil_processed_phrases_' . $processing_key);
        // clear the outbound post link cache
        delete_transient('wpil_outbound_post_links' . $processing_key);
        // clear the post category cache
        delete_transient('wpil_post_same_categories_' . $processing_key);
    }

    /**
     * Checks to see if we're dealing with Asian caligraphics
     * Todo: Not currently active
     **/
    public static function isAsianText(){
        return false;
    }

    /**
     * Gets the currently selected categories for suggestion matching.
     * Pulls data from the $_POST variable or the stored filtering settings
     * @return array
     **/
    public static function get_selected_categories(){
        return Wpil_Settings::get_suggestion_filter('selected_category');
    }

    /**
     * Gets the currently selected tags for suggestion matching.
     * Pulls data from the $_POST variable or the stored filtering settings
     * @return array
     **/
    public static function get_selected_tags(){
        return Wpil_Settings::get_suggestion_filter('selected_tag');
    }

    /**
     * Gets the max length for a suggested anchor
     **/
    public static function get_max_anchor_length(){
        if(empty(self::$max_anchor_length)){
            self::$max_anchor_length = Wpil_Settings::getSuggestionMaxAnchorSize();
        }

        return self::$max_anchor_length;
    }

    /**
     * Gets the min length for a suggested anchor
     **/
    public static function get_min_anchor_length(){
        if(empty(self::$min_anchor_length)){
            self::$min_anchor_length = Wpil_Settings::getSuggestionMinAnchorSize();
        }

        return self::$min_anchor_length;
    }
}
