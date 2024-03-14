<?php

/**
 * Easy related posts .
 *
 * @package Easy_Related_Posts_Related
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link http://example.com
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */

/**
 * Query formater class.
 *
 * @package Easy_Related_Posts_Related
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class erpQueryFormater {

    /**
     * Post id
     *
     * @since 2.0.0
     * @var int
     */
    private $pid;

    /**
     * argument array
     *
     * @since 2.0.0
     * @var array;
     */
    private $argsArray = array();

    /**
     * Tags array
     *
     * @since 2.0.0
     * @var array
     */
    private $tags = array();

    /**
     * Cats array
     *
     * @since 2.0.0
     * @var array
     */
    private $categories = array();

    /**
     * post types array
     *
     * @since 2.0.0
     * @var array
     */
    private $postTypes = array();

    /**
     * vissited posts
     *
     * @since 2.0.0
     * @var array
     */
    private $visitedPosts = array();

    /**
     * query limit
     *
     * @since 2.0.0
     * @var int
     */
    private $queryLimit = 10;

    /**
     * Query offset
     *
     * @since 2.0.0
     * @var int
     */
    private $queryOffset = 0;

    /**
     * Post in array
     *
     * @since 2.0.0
     * @var array
     */
    private $postIn = array();

    /**
     * Return arguments array
     *
     * @return array;
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getArgsArray() {
        return $this->argsArray;
    }

    /**
     * Sets main query arguments.
     * These are: post_status, perm, post_visibility,
     * ignore_sticky_posts, post__not_in, orderby, order.
     * Also limits the query based in $this->queryLimit value
     *
     * @param int $pid
     * @param string $orderBy
     * @param string $order
     * @param int $limit
     *        	limits the fetched post default 10
     * @param int $offset
     *        	Default 0
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since
     *
     */
    public function setMainArgs($pid, $orderBy = 'date', $order = 'DESC') {
        if ($this->pid != $pid) {
            $this->clearQueryArgs();
        }

        $this->argsArray ['post_status'] = 'publish';
        $this->argsArray ['perm'] = 'readable';
        $this->argsArray ['post_visibility'] = 'public';
        $this->argsArray ['ignore_sticky_posts'] = 1;
        $this->argsArray ['post__not_in'] = (array) $pid;
        $this->argsArray ['orderby'] = $orderBy;
        $this->argsArray ['order'] = $order;

        return $this;
    }
    
    public function getPostsAfterDate($year, $month, $day) {
        $this->argsArray ['date_query'] = array(
            'after' => array(
                'year' => $year,
                'month' => $month,
                'day' => $day
            )
        );
    }

    /**
     * Resets class arguments to default values
     *
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function clearQueryArgs() {
        $this->argsArray = array();
        $this->categories = array();
        $this->postIn = array();
        $this->postTypes = array();
        $this->queryLimit = 10;
        $this->queryOffset = 0;
        $this->tags = array();
        $this->visitedPosts = array();
        return $this;
    }

    /**
     * Sets tags in query args array
     *
     * @param array $tags
     *        	Array of tags object
     * @param string $operator
     *        	WP_Query tags operator
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function setTags($tags, $operator = 'in') {
        if (!empty($tags)) {
            if (gettype(end($tags)) == 'object') {
                $temp = array();
                foreach ($tags as $k => $v) {
                    $temp [$k] = $v->term_id;
                }
                $tags = $temp;
            }
            $this->argsArray ['tag__' . $operator] = $tags;
            $this->tags [$operator] = $tags;
        }
        return $this;
    }

    /**
     * Excludes tags that are setted in plugin options
     *
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function exTags($tags) {
        if (!empty($tags) && $tags) {
            $this->setTags($tags, 'not_in');
        }
        return $this;
    }

    /**
     * Clears any tag filters in args array
     *
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function clearTags() {
        $filters = array(
            'tag',
            'tag_id',
            'tag__and',
            'tag__in',
            'tag__not_in',
            'tag_slug__and',
            'tag_slug__in'
        );
        foreach ($filters as $k => $v) {
            unset($this->argsArray [$v]);
        }
        $this->tags = array();
        return $this;
    }

    /**
     * Sets categories in query args array
     *
     * @param array $categories
     *        	Array of categories object
     * @param string $operator
     *        	WP_Query categories operator
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function setCategories($categories, $operator = 'in') {
        if (!empty($categories)) {
            if (gettype(end($categories)) == 'object') {
                $temp = array();
                foreach ($categories as $k => $v) {
                    $temp [$k] = $v->term_id;
                }
                $categories = $temp;
            }
            $this->argsArray ['category__' . $operator] = $categories;
            $this->categories [$operator] = $categories;
        }
        return $this;
    }

    /**
     * Excludes categories
     *
     * @param array $categories
     *        	Array of categories obj
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function exCategories($categories) {
        if (!empty($categories) && $categories) {
            $this->setCategories($categories, 'not_in');
        }
        return $this;
    }

    /**
     * Clears any categories filters in args array
     *
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function clearCategories() {
        $filters = array(
            'cat',
            'category_name',
            'category__and',
            'category__in',
            'category__not_in'
        );
        foreach ($filters as $k => $v) {
            unset($this->argsArray [$v]);
        }
        $this->categories = array();
        return $this;
    }

    /**
     * Clears post_type argument in args array
     *
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function clearPostTypes() {
        unset($this->argsArray ['post_type']);
        $this->postTypes = array();
        return $this;
    }

    /**
     * Excludes post types
     *
     * @param array $post_types
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function exPostTypes($post_types) {
        $post_typ = get_post_types();
        if (isset($post_types) && !empty($post_types)) {
            foreach ((array) $post_types as $key => $value) {
                unset($post_typ [$value]);
            }
            $this->argsArray ['post_type'] = $post_typ;
            $this->postTypes = $post_typ;
        }
        return $this;
    }

    /**
     * Excludes the visited posts by setting post__not_in argument in WP_Query
     *
     * @param
     *        	<array, string> $visited If is a string tries to unserializeit
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function exVisitedPosts($visited) {
        if (is_string($visited)) {
            $visited = unserialize($visited);
        }
        if (isset($visited)) {
            if (isset($this->argsArray ['post__not_in'])) {
                $this->argsArray ['post__not_in'] = array_merge($this->argsArray ['post__not_in'], $visited);
            } else {
                $this->argsArray ['post__not_in'] = $visited;
            }
        }
        return $this;
    }

    /**
     * Sets post__in param in args array
     *
     * @param array $postsIds
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function setPostInArg(Array $postsIds) {
        $this->argsArray ['post__in'] = empty($postsIds) ? array(0) : $postsIds;
        $this->postIn = $postsIds;
        return $this;
    }

    /**
     * Unsets post__in param
     *
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function clearPostInParam() {
        $this->postIn = array();
        unset($this->argsArray ['post__in']);
        return $this;
    }

    /**
     * Sets $this->queryLimit and $this->queryOffset
     *
     * @param int $limit
     * @param int $offset
     * @return erpQueryFormater
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function setQueryLimit($limit, $offset) {
        $this->queryLimit = $limit;
        $this->queryOffset = $offset;
        return $this;
    }

}
