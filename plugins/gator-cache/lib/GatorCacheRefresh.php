<?php
class GatorCacheRefresh
{
    protected $options;
    protected $configPath;
    protected $post;
    protected $refresh = false;
    protected $preTerms = array();
    protected $permalink = array();

    public function __construct($options, $configPath)
    {
        $this->options = $options;
        $this->configPath = $configPath;
    }

/**
 * getPreUpdateData
 * 
 * gets the terms before update so the caches can be adjusted if post removed from terms
 * 
 * @note hooked on pre_post_update
 */
    public function getPreUpdateData($postId)
    {
        if (false === ($postType = get_post_type($postId))) {
            return;
        }
        // get permalink here in pre-update in case the post is transitioned to draft or otherwise non-published
        $this->permalinks[$postId] = get_permalink($postId);
        $post = array('ID' => $postId, 'post_type' => $postType); 
        if (false === ($taxonomies = $this->getArchiveTerms((object) $post))) {
            return;
        }
        $this->preTerms[$postId] = $taxonomies; //array_map('GatorCacheRefresh::mapTerms', $taxonomies);
    }

/**
 * savePost
 *
 * Will invalidate the cache when post is updated
 * @note hooked on transition_post_status, which always fires on add or update
 */
    public function refresh($new_status, $old_status, $post)
    {
        if (((defined('DOING_AJAX') && DOING_AJAX) && (empty($_POST['action']) || 'inline-save' !== $_POST['action'])) // allow quick edit from ajax
          || '' === $post->post_name
          || (($newPost = 'publish' !== $old_status) && 'publish' !== $new_status)
          || '' === get_option('permalink_structure')) {
            return;
        }

        $postTypes = array('post' => 0, 'page' => 0) + array_flip($this->options['post_types']);
        if ((isset($postTypes['bbpress']) || isset($this->options['refresh_paths']['bbpress'])) && isset($this->options['app_support']['bbpress'])) {
            //bbpress supported - perform ops on child types
            $postTypes = $this->options['app_support']['bbpress'] + $postTypes;
        }
        if (!$this->options['enabled'] || WpGatorCache::VERSION !== $this->options['version'] || !isset($postTypes[$post->post_type])) {
            return;
        }
        $cache = GatorCache::getCache($opts = GatorCache::getConfig($this->configPath)->toArray());
        //remove post from all cache groups
        $groups = WpGatorCache::getCacheGroups($opts);

        if (!$cache->hasCacheGroups($groups)) {
            //the cache appears to be empty Jim
            return;
        }
        //rss feed, custom post type feeds are not cached since they contain a query string
        if ('post' === $post->post_type && false !== ($path = parse_url(get_feed_link(get_default_feed()), PHP_URL_PATH))) {
            $cache->removeGroups($path, $groups);//purge archive
        }
        //return the same refresh checks for new and updated posts
        if ($this->options['refresh']['all'] && WpGatorCache::hasRecentWidgets()) {
            //purge cache so sidebar widgets refresh @note could refine by post type 'post' === $post->post_type &&
            $cache->purgeGroups($groups);
            return $this->refresh = true;
        }
        //refresh parent posts and the current post
        foreach (($posts = $this->getRefreshPosts($post, $newPost)) as $postId) {
            $permalink = $post->ID == $postId && isset($this->permalinks[$postId]) ? $this->permalinks[$postId] : get_permalink($postId);
            // var_dump($permalink);exit;
            if (false !== ($path = parse_url($permalink, PHP_URL_PATH))) {
                $cache->removeGroups($path, $groups, true);
            }
        }
        //refresh home page
        if ($this->options['refresh']['home']) {
            //refresh the home page
            $this->options['refresh_paths']['all'][] = DIRECTORY_SEPARATOR;
        }
        //refresh custom paths
        if (!empty($this->options['refresh_paths'])) {
            if (!empty($this->options['refresh_paths']['all'])) {
                foreach ($this->options['refresh_paths']['all'] as $refreshPath) {
                    $cache->removeGroups($refreshPath, $groups);
                }
            }
            if (isset($this->options['app_support']['bbpress']) && !empty($this->options['refresh_paths']['bbpress'])
              && isset($this->options['app_support']['bbpress'][$post->post_type])) {
                foreach ($this->options['refresh_paths']['bbpress'] as $refreshPath) {
                    $cache->removeGroups($refreshPath, $groups);
                }
            }
            if (!empty($this->options['refresh_paths'][$post->post_type])) {
                foreach ($this->options['refresh_paths'][$post->post_type] as $refreshPath) {
                    $cache->removeGroups($refreshPath, $groups);
                }
            }
        }
        //refresh archive pages for this post or the last parent
        if (!$this->options['refresh']['archive']) {
            return $this->refresh = true;
        }
        if (isset($this->post)) {
            //bbpress
            if (false !== ($link = get_post_type_archive_link($this->post->post_type))
              && false !== ($path = parse_url($link, PHP_URL_PATH))) {
                $cache->removeGroups($path, $groups);
            }
            return $this->refresh = true;
        }
        //taxonomy archive
        $termIds = array();
        if (false !== ($terms = $this->getArchiveTerms($post))) {
            foreach ($terms as $term) {
                $termIds[] = $term->term_id;
                if (is_wp_error($termLink = get_term_link($term, $term->taxonomy))) {
                    continue;
                }
                if (false !== ($path = parse_url($termLink, PHP_URL_PATH))) {
                    $cache->removeGroups($path, $groups);//purge archive
                }
                //this is not necessary since the category feed is under the category directory which has already been removed
                /*if(false !== ($path = parse_url(get_term_feed_link($term->term_id, $term->taxonomy, get_default_feed()), PHP_URL_PATH))){
                    $cache->remove($path, $opts['group']);//purge archive feed, this will purge all feed types since the default is the top level
                }*/
            }
        }
        // check for removed terms
        if (!empty($this->preTerms[$post->ID])) {
            foreach ($this->preTerms[$post->ID] as $term) {
                if (in_array($term->term_id, $termIds) || is_wp_error($termLink = get_term_link($term, $term->taxonomy))) {
                    continue;
                }
                // the term has been removed from the object, refresh the term
                if (false !== ($path = parse_url($termLink, PHP_URL_PATH))) {
                    $cache->removeGroups($path, $groups);//purge archive
                }
            }
        }
        //woocommerce shop
        if ('product' === $post->post_type && false !== ($link = get_permalink(woocommerce_get_page_id('shop')))
          && false !== ($path = parse_url($link, PHP_URL_PATH))) {
            $cache->removeGroups($path, $groups);
        }
        $this->refresh = true;
    }

    public function isRefreshed()
    {
        return $this->refresh;
    }

    protected function getRefreshPosts($post, $isNew)
    {
        $ids = array();
        if (!$isNew) {
            $ids[] = $post->ID;
        }
        if (isset($this->options['app_support']['bbpress'])
          && isset($this->options['app_support']['bbpress'][$post->post_type])
          && in_array('bbpress', $this->options['post_types'])) {
            //get bbpress parent posts
            $this->post = $post;//seeder
            for ($xx=0;$xx<25;$xx++) {
                if (false === ($id = $this->getParentPost($this->post))) {
                    break;
                }
                $ids[] = $id;
            }
        }
        return $ids;
    }

    protected function getParentPost($post)
    {
        if (0 === $post->post_parent) {
            return false;
        }
        if (null !== ($parent = get_post($post->post_parent))) {
            $this->post = $parent;
            return $this->post->ID;
        }
        return false;
    }

    public function getArchiveTerms($post)
    {
        $taxonomies = array_map('GatorCacheRefresh::mapTaxonomies',
            get_object_taxonomies($post->post_type, 'objects')
            //array_filter(get_object_taxonomies($post, 'objects'), 'GatorCacheRefresh::filterTaxonomies')
        );
        if (empty($taxonomies)) {
            return false;
        }
        $terms = wp_get_object_terms(array($post->ID), $taxonomies);//array_values($taxonomies)
        if (empty($terms)) {
            return false;
        }
        return $terms;
    }

    public static function mapTaxonomies($taxonomy)
    {
        return $taxonomy->name;
    }

    public static function mapTerms($term)
    {
        return $term->term_id;
    }

    public static function filterTaxonomies($taxonomy)
    {
        return $taxonomy->hierarchical;
    }
}
