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
 * Relative data class.
 *
 * @package Easy_Related_Posts_Related
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class erpRelData {

    /**
     * Post id that concerns this object
     *
     * @since 2.0.0
     * @var int
     */
    public $pid;

    /**
     * Relative table
     *
     * @since 2.0.0
     * @var array
     */
    public $relTable = array();

    /**
     * WP_Query result
     *
     * @since 2.0.0
     * @var WP_Query
     */
    private $wpQuery;

    /**
     * Count of fould posts
     *
     * @since 2.0.0
     * @var int
     */
    private $postCount;

    /**
     * Offset of query result
     *
     * @since 2.0.0
     * @var int
     */
// 	private $offset = 0;
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
     * Array containig query arguments
     *
     * @since 2.0.0
     * @var array
     */
    private $qArgs = array();
    private $criticalOptions;
    private $ratings = array();

    public function __construct($pid, $criticalOptions, $relTable = null) {
        $this->pid = $pid;
        $this->relTable = $relTable;
        $this->criticalOptions = $criticalOptions;
    }

    /**
     * Sets wp_query
     * @param WP_Query $q
     * @param array $arguments
     * @param int $offset
     * @return erpRelData
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function setWP_Query($arguments, $limit, $offset, $q = null) {
        $this->wpQuery = $q;
        $this->postCount = isset($q->post_count) ? $q->post_count : null;
// 		$this->offset = $offset;
        $this->queryLimit = $limit;
        $this->qArgs = $arguments;
        return $this;
    }

    /**
     * Return result
     * @return WP_Query
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getResult() {
        if (!has_filter('post_limits', array($this, 'limitPosts'))) {
            add_filter('post_limits', array($this, 'limitPosts'));
        }
        if ($this->wpQuery == null) {
            $this->wpQuery = new WP_Query($this->qArgs);
        }

        remove_filter('post_limits', array($this, 'limitPosts'));
        wp_reset_postdata();

        return $this->wpQuery;
    }

    /**
     * Limits posts in WP query.
     * !IMPORTANT! This is an action hook, not to be called directly.
     * $this->queryLimit must be set in order to actually work
     *
     * @param int $limit
     * @return string
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function limitPosts($limit) {
        if ($this->queryLimit > 0) {
            if ($this->queryOffset > 0) {
                $offset = $this->queryOffset;
            } else {
                $offset = 0;
            }
            return 'LIMIT ' . $offset . ', ' . $this->queryLimit;
        }
        return $limit;
    }

    /**
     * Sets $this->queryLimit and $this->queryOffset
     * @param int $limit
     * @param int $offset
     * @return erpRelData
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function setQueryLimit($limit, $offset) {
        $this->queryLimit = $limit;
        $this->queryOffset = $offset;
        return $this;
    }

    /**
     * Search if given post id is present in rel table and returns index
     * @param int $pid
     * @return <boolean : int> False if post not found, index int otherwise
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getIndexFromRelTable($pid) {
        foreach ($this->relTable as $key => $value) {
            if ($pid == $value['pid1'] || $pid == $value['pid2']) {
                return $key;
            }
        }
        return false;
    }

    /**
     * Return a single row from rel table
     * @param int $i Index
     * @return <boolean, array> False if index is't set, row otherwise
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getRowFromRelTable($i) {
        return isset($this->relTable[$i]) ? $this->relTable[$i] : false;
    }

    /**
     * Sorts WP_Query object based on given array of post ids
     * @param array $postIDs
     * @return WP_Query
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function sortWPQuery(Array $postIDs) {
        if (empty($this->wpQuery->posts) || empty($postIDs)) {
            return FALSE;
        }

        usort($this->wpQuery->posts, function ( $a, $b ) use($postIDs ) {
            $apos = array_search($a->ID, $postIDs);
            $bpos = array_search($b->ID, $postIDs);

            return ( $apos < $bpos ) ? -1 : 1;
        });
        return $this->wpQuery;
    }

    /**
     * Checks if critical options from parameter match those from instance
     *
     * @param array $options
     *        	Options array, all critical options must be defined
     * @return array An array containing the options that don't match, or empty array if all match
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function criticalOptionsMismatch(Array $options) {
        $critical = erpDefaults::$criticalOpts;
        $misMatch = array();
        foreach ($critical as $k => $v) {
            if (!isset($options[$v]) || !isset($this->criticalOptions[$v]) || $options[$v] != $this->criticalOptions[$v]) {
                array_push($misMatch, $v);
            }
        }
        return $misMatch;
    }

    /**
     * @param array $relTable
     */
    public function setRelTable($relTable) {
        $this->relTable = $relTable;
        return $this;
    }

    public function getRatings() {
        return $this->ratings;
    }

    public function setRatings($ratings) {
        $this->ratings = $ratings;
        return $this;
    }

}
