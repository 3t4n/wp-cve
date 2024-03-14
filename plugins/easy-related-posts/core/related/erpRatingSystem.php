<?php

/**
 * Easy related posts .
 *
 * @package   Easy_Related_Posts_Related
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */

/**
 * Ratings class.
 *
 * @package Easy_Related_Posts_Related
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class erpRatingSystem {

    /**
     * Categories weight
     *
     * @since 2.0.0
     * @var float
     */
    private $catWeight;

    /**
     * Tags weight
     *
     * @since 2.0.0
     * @var float
     */
    private $tagWeight;

    /**
     * Clicks per displayed weight
     *
     * @since 2.0.0
     * @var float
     */
    private $clickWeight;

    /**
     * Ratings array ()
     *
     * @since 2.0.0
     * @var array
     */
    private $ratingsArray;

    /**
     * Flatened ratings array (pid=>rating)
     *
     * @since 2.0.0
     * @var array
     */
    private $ratingsArrayFlat;

    /**
     * Rel data obj
     *
     * @since 2.0.0
     * @var erpRelData
     */
    private $relData;

    /**
     * Post dates
     *
     * @since 2.0.0
     * @var array
     */
    private $post_date;

    /**
     * Instance of this class.
     *
     * @since 2.0.0
     * @var erpRatingSystem
     */
    protected static $instance = null;

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since 2.0.0
     */
    protected function __construct(&$relData) {
        $this->relData = $relData;
    }

    /**
     * Return an instance of this class.
     *
     * @since 2.0.0
     * @return erpRatingSystem
     */
    public static function get_instance(erpRelData $relData) {
        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self($relData);
        }

        if (!(isset(self::$instance->relData->relTable) && isset($relData->relTable) && self::$instance->relData->relTable == $relData->relTable)) {
            self::$instance->relData = $relData;
        }
        return self::$instance;
    }

    public static function getNewInstance(erpRelData $relData) {
        return new self($relData);
    }

    public function setWeights($weights) {
        $this->catWeight = isset($weights['categories']) ? $weights['categories'] : 0;
        $this->tagWeight = isset($weights['tags']) ? $weights['tags'] : 0;
        $this->clickWeight = isset($weights['clicks']) ? $weights['clicks'] : 0;
    }

    public function calcTotalRating($pid) {
        $index = $this->relData->getIndexFromRelTable($pid);
        if (is_int($index)) {
            $row = $this->relData->getRowFromRelTable($index);
            return $this->calcCatRating($row) + $this->calcTagRating($row) + $this->calcClickRate($row);
        }
        return false;
    }

    /**
     * Rates pid2 based on common categories with pid1
     *
     * @param int $pid1 Host post id
     * @param int $pid2 Guest post id
     * @return number
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function rateBasedOnCats($pid1, $pid2) {
        // get posts categories
        $hostCats = get_the_category($pid1);
        $guestCats = get_the_category($pid2);

        if (empty($hostCats) || empty($guestCats)) {
            return 0.0;
        }

        $commonCatsCount = 0;
        $temp = array();
        foreach ($guestCats as $k => $v) {
            $temp [$k] = $v->term_id;
        }
        $guestCats = $temp;
        foreach ($hostCats as $k => $v) {
            if (in_array($v->term_id, $guestCats)) {
                $commonCatsCount++;
            }
        }
        return (float) ( $commonCatsCount / count($hostCats) );
    }

    /**
     * Rates pid2 based on common tags with pid1
     *
     * @param int $pid1 Host post id
     * @param int $pid2 Guest post id
     * @return number
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function rateBasedOnTags($pid1, $pid2) {
        $hostTags = get_the_tags($pid1);
        $guestTags = get_the_tags($pid2);

        if (empty($hostTags) || empty($guestTags)) {
            return 0.0;
        }
        $comTagsCount = 0;
        $temp = array();
        foreach ($guestTags as $k => $v) {
            $temp [$k] = $v->term_id;
        }
        $guestTags = $temp;
        foreach ($hostTags as $k => $v) {
            if (in_array($v->term_id, $guestTags)) {
                $comTagsCount++;
            }
        }
        return (float) ( $comTagsCount / count($hostTags) );
    }

    public function calcCatRating($row) {
        if ($row['pid1'] == $this->relData->pid) {
            return $this->catWeight * $row['score2_cats'];
        } else {
            $clicRate = $row ['displayed1'] == 0 ? 0 : (float) $row ['clicks1'] / $row ['displayed1'];
            return $this->catWeight * $row['score1_cats'];
        }
    }

    public function calcTagRating($row) {
        if ($row['pid1'] == $this->relData->pid) {
            return $this->tagWeight * $row['score2_tags'];
        } else {
            return $this->tagWeight * $row['score1_tags'];
        }
    }

    public function calcClickRate($row) {
        if (!isset($row['displayed2']) || !isset($row['clicks2']) || !isset($row['clicks1']) || !isset($row['displayed1'])) {
            return 0.0;
        }
        if ($row['pid1'] == $this->relData->pid) {
            $clicRate = $row ['displayed2'] == 0 ? 0 : (float) $row ['clicks2'] / $row ['displayed2'];
            return $this->clickWeight * $clicRate;
        } else {
            $clicRate = $row ['displayed1'] == 0 ? 0 : (float) $row ['clicks1'] / $row ['displayed1'];
            return $this->clickWeight * $clicRate;
        }
    }

    /**
     * Forms ratingsArrayFlat field based on relatedTable field and weights
     *
     * @return erpRatingSystem
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function formRatingsArrays() {
        if (!empty($this->relData->relTable)) {
            $this->ratingsArray = array();
            $this->ratingsArrayFlat = array();
            $this->post_date = array();
            foreach ($this->relData->relTable as $key => $value) {
                $rating = $this->calcCatRating($value) + $this->calcTagRating($value) + $this->calcClickRate($value);
                if ($rating == 0) {
                    continue;
                }
                if ($value['pid1'] == $this->relData->pid) {
                    $postDate = strtotime($value['post_date2']);
                    $this->ratingsArrayFlat[$value['pid2']] = $rating;
                    $this->ratingsArray[$value['pid2']]['ID'] = $value['pid2'];
                    $this->ratingsArray[$value['pid2']]['rating'] = $rating;
                    $this->ratingsArray[$value['pid2']]['post_date'] = $postDate;
                    $this->post_date[$value['pid2']] = $postDate;
                } else {
                    $postDate = strtotime($value['post_date1']);
                    $this->ratingsArrayFlat[$value['pid1']] = $rating;
                    $this->ratingsArray[$value['pid1']]['ID'] = $value['pid1'];
                    $this->ratingsArray[$value['pid1']]['rating'] = $rating;
                    $this->ratingsArray[$value['pid1']]['post_date'] = $postDate;
                    $this->post_date[$value['pid1']] = $postDate;
                }
            }
        }
        return $this;
    }

    /**
     * Sorts ratingsArrayFlat
     *
     * @param array $order
     *        	Possible values (date and/or rating => (order => desc or asc, rank => 1 or 2))
     *        Default (ratings => (order => desc, rank => 1), date => (order => desc, rank => 2))
     * @return boolean True on success, false otherwise
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     *
     */
    public function sortRatingsArrays($order = array()) {
        if (empty($this->ratingsArray) || empty($this->ratingsArrayFlat)) {
            return FALSE;
        }
        $ratingsFlat = $this->ratingsArrayFlat;
        $post_date = $this->post_date;
        extract($order);
        if (isset($rating) && isset($date)) {
            if ($rating['rank'] == 1) {
                $ratingFlag = $rating['order'] == 'desc' ? SORT_DESC : SORT_ASC;
                $dateFlag = $date['order'] == 'desc' ? SORT_DESC : SORT_ASC;
                array_multisort($ratingsFlat, $ratingFlag, $post_date, $dateFlag, $this->ratingsArray);
            } else {
                $ratingFlag = $rating['order'] == 'desc' ? SORT_DESC : SORT_ASC;
                $dateFlag = $date['order'] == 'desc' ? SORT_DESC : SORT_ASC;
                array_multisort($post_date, $dateFlag, $ratingsFlat, $ratingFlag, $this->ratingsArray);
            }
        } elseif (isset($rating)) {
            $ratingFlag = $rating['order'] == 'desc' ? SORT_DESC : SORT_ASC;
            array_multisort($ratingsFlat, $ratingFlag, $this->ratingsArray);
        } elseif (isset($date)) {
            $dateFlag = $date['order'] == 'desc' ? SORT_DESC : SORT_ASC;
            array_multisort($post_date, $dateFlag, $this->ratingsArray);
        } else {
            array_multisort($ratingsFlat, SORT_DESC, $post_date, SORT_DESC, $this->ratingsArray);
        }
        $this->ratingsArrayFlat = array();
        foreach ($this->ratingsArray as $k => $v) {
            $this->ratingsArrayFlat[$v['ID']] = $v['rating'];
        }
        return TRUE;
    }

    /**
     * Slices and return ratings array.
     * @param int $offset
     * @param int $length
     * @param array $postsToExlude
     * @return array Sliced array
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getSlicedRatingsArrayFlat($offset, $length, Array $postsToExlude = array()) {
        $ratings = $this->ratingsArrayFlat;

        foreach ($postsToExlude as $k => $v) {
            unset($ratings[$v]);
        }

        return empty($ratings) ? $ratings : array_slice($ratings, $offset, $length, true);
    }

    public function getSlicedRatingsArrayFlatLoose($minElements) {
        $included = $this->getSlicedRatingsArrayFlat(0, $minElements);
        $excluded = array_diff_assoc($this->ratingsArrayFlat, $included);

        $min = end($included);
        foreach ((array) $excluded as $key => $value) {
            if ($min > 0 && $value >= $min) {
                $included[$key] = $value;
            }
        }
        return $included;
    }

}
