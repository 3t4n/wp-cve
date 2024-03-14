<?php

/**
 * Easy related posts .
 *
 * @package   Easy_Related_Posts_Options
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */

/**
 * Default options class.
 *
 * @package Easy_Related_Posts_Options
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class erpDefaults {

    const intFormal = 'int';
    const floatFormal = 'float';
    const stringFormal = 'string';
    const arrayFormal = 'array';
    const boolFormal = 'bool';

    /**
     * Plugin version.
     *
     * @since 2.0.0
     * @var int
     */
    const erpVersion = 2;

    /**
     * Plugin release.
     *
     * @since 2.0.0
     * @var int
     */
    const erpRelease = 0;

    /**
     * Plugin subrelease.
     *
     * @since 2.0.0
     * @var int
     */
    const erpSubRelease = 2;

    /**
     * This should be upddated if widget class name change
     *
     * @since 2.0.0
     * @var string
     */
    const erpWidgetOptionsArrayName = 'erp_widget';

    /**
     * Version number is stored in DB as a WP option. 
     * This is the name of the option
     *
     * @since 2.0.0
     * @var string
     */
    const versionNumOptName = 'erpVersionNumbers';

    /**
     * Sorting options
     *
     * @since 2.0.0
     * @var array
     */
    public static $sortRelatedByOptionSerialized = array(
        "a:1:{s:4:'date';a:2:{s:5:'order';s:4:'desc';s:4:'rank';i:1;}}", // date desc
    ); 

    /**
     * Options available for related storting
     *
     * @since 2.0.0
     * @var array
     */
    public static $sortRelatedByOption = array(
        array(
            'date' => array(
                'order' => 'desc',
                'rank' => 1
            )
        )
    );

    /**
     * Sortkeys (sort_key_string => $indexInSortOptionsArrays)
     *
     * @since 2.0.0
     * @var array
     */
    public static $sortKeys = array(
        'date_descending' => 0,
    );

    /**
     * Options for related fetching options
     *
     * @since 2.0.0
     * @var array
     */
    public static $fetchByOptions = array(
        'Categories',
        'Tags'
    );

    /**
     * Fetch by weights
     * @var type array
     */
    public static $fetchByOptionsWeights = array(
        'categories' => array('clicks' => 0.15, 'categories' => 0.85, 'tags' => 0.0),
        'tags' => array('clicks' => 0.15, 'categories' => 0.0, 'tags' => 0.85),
    );

    /**
     * Content positioning options
     * @var array  
     */
    public static $contentPositioningOptions = array(
        'Title',
        'Title, excerpt',
        'Title, thumbnail',
        'Thumbnail, title',
        'Thumbnail, title, excerpt',
        'Title, thumbnail, excerpt',
        'Title, excerpt, thumbnail'
    );

    /**
     * Common options used from all components.
     *
     * @since 2.0.0
     * @var array
     */
    public static $comOpts = array(
        'title' => 'Easy Related Posts',
        'numberOfPostsToDisplay' => 6,
        'fetchBy' => 'categories',
        'offset' => 0,
        'content' => array(
            'thumbnail',
            'title',
            'excerpt'
        ),
        'sortRelatedBy' => 'date_descending',
        'defaultThumbnail' => EPR_DEFAULT_THUMBNAIL,
        'postTitleFontSize' => 0,
        'excFontSize' => 0,
        'excLength' => 15,
        'moreTxt' => ' ...read more',
        'thumbnailHeight' => 150,
        'thumbnailWidth' => 300,
        'cropThumbnail' => true,
        'postTitleColor' => '#ffffff',
        'excColor' => '#ffffff'
    );

    /**
     * Used for options validation. Does not contain values for options but types
     *
     * @since 2.0.0
     * @var array
     */
    public static $comOptsValidations = array(
        'title' => array('type' => self::stringFormal),
        'numberOfPostsToDisplay' => array('type' => self::intFormal, 'min' => 1),
        'fetchBy' => array('type' => self::stringFormal),
        'offset' => array('type' => self::intFormal, 'min' => 0),
        'content' => array('type' => self::arrayFormal),
        'sortRelatedBy' => array('type' => self::stringFormal),
        'defaultThumbnail' => array('type' => self::stringFormal),
        'postTitleFontSize' => array('type' => self::intFormal, 'min' => 0),
        'excFontSize' => array('type' => self::intFormal, 'min' => 0),
        'excLength' => array('type' => self::intFormal, 'min' => 1),
        'moreTxt' => array('type' => self::stringFormal),
        'thumbnailHeight' => array('type' => self::intFormal, 'min' => 0),
        'thumbnailWidth' => array('type' => self::intFormal, 'min' => 0),
        'cropThumbnail' => array('type' => self::boolFormal),
        'postTitleColor' => array('type' => self::stringFormal),
        'excColor' => array('type' => self::stringFormal)
    );

    /**
     * Main plugin options
     *
     * @since 2.0.0
     * @var array
     */
    public static $mainOpts = array(
        'activate' => true,
        'dsplLayout' => 'Grid',
        'categories' => array(),
        'tags' => array(),
        'postTypes' => array(
            'page',
            'attachment',
            'nav_menu_item',
            'revision'
        )
    );

    /**
     * Used for options validation. Does not contain values for options but types
     *
     * @since 2.0.0
     * @var array
     */
    public static $mainOptsValidations = array(
        'activate' => array('type' => self::boolFormal),
        'dsplLayout' => array('type' => self::stringFormal),
        'categories' => array('type' => self::arrayFormal),
        'tags' => array('type' => self::arrayFormal),
        'postTypes' => array('type' => self::arrayFormal),
    );

    /**
     * Widget options
     *
     * @since 2.0.0
     * @var array
     */
    public static $widOpts = array(
        'dsplLayout' => 'Basic',
        'hideIfNoPosts' => false,
    );

    /**
     * Used for options validation. Does not contain values for options but types
     *
     * @since 2.0.0
     * @var array
     */
    public static $widOptsValidations = array(
        'dsplLayout' => array('type' => self::stringFormal),
        'hideIfNoPosts' => array('type' => self::boolFormal),
    );

    /**
     * Critical options, related result depends on them
     *
     * @since 2.0.0
     * @var array
     */
    public static $criticalOpts = array(
        'fetchBy',
        'numberOfPostsToDisplay',
        'offset'
            // 'sortRelatedBy'
    );

    /**
     * Compares the input string if matches plugin version
     *
     * @param string $version
     * @return number -1 if input isn't string, else 0 if version differs, else 1 if release differs, else 2 if subrelease differs, else 3
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public static function compareVersion($version) {
        if (!is_string($version)) {
            return -1;
        }
        $vrs = explode('.', $version, 3);
        if (count($vrs) < 3) {
            return -1;
        }
        if ($vrs [0] != self::erpVersion) {
            return 0;
        } elseif ($vrs [1] != self::erpRelease) {
            return 1;
        } elseif ($vrs [2] != self::erpSubRelease) {
            return 2;
        }
        return 3;
    }

    /**
     * Updates version numbers in DB.
     * If not present adds them.
     *
     * @return boolean
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public static function updateVersionNumbers() {
        return update_option(self::versionNumOptName, self::erpVersion . '.' . self::erpRelease . '.' . self::erpSubRelease);
    }

}
