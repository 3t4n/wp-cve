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
 * Options abstract class.
 *
 * @package Easy_Related_Posts_Options
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
abstract class erpOptions {

    protected $options = NULL;
    protected $defaults;

    protected $optionsArrayName = NULL;

    public function __construct() {
        $this->defaults = erpDefaults::$comOpts;
        $this->options = $this->defaults;
    }

    public function getOptionsArrayName() {
        return $this->optionsArrayName;
    }

    /**
     * Get option array
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     * @return array Options array
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * Sets options in instance options array.
     * !IMPORTAND! Won't update options in DB, just sets them in instance options array.
     *
     * @param array $options
     *        	New options as associative array
     * @return erpOptions
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function setOptions($options) {
        $this->options = array_merge(isset($this->options) ? (array) $this->options : array(), $options);
        return $this;
    }

    public function getSortRelatedBy($unserializeIt = false) {
        $i = isset($this->options ['sortRelatedBy']) ? erpDefaults::$sortKeys [$this->options ['sortRelatedBy']] : 0;

        return (bool) $unserializeIt ? erpDefaults::$sortRelatedByOption[$i] : erpDefaults::$sortRelatedByOptionSerialized[$i];
    }

    /**
     * Get the value of given option.
     *
     * @param string $optionName
     * @return null string NULL if option not found, option value otherwise (uses default value if avail)
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getValue($optionName) {
        if ($this->isOptionSet($optionName)) {
            return $this->options[$optionName];
        } elseif (isset($this->defaults[$optionName])) {
            return $this->defaults[$optionName];
        } else {
            return null;
        }
    }

    /**
     * Just unsets option value if present in objects instance options array
     *
     * @return boolean Deleted option value if exists, NULL otherwise
     * @since 2.0.0
     */
    public function deleteOption($optionName) {
        if (array_key_exists($optionName, $this->options)) {
            $this->options [$optionName] = $value;
            unset($this->options [$optionName]);
            return $value;
        }
        return NULL;
    }

    /**
     * Checks if specified option exists
     *
     * @param string $optName
     * @return boolean True if option is set, false otherwise
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function isOptionSet($optName) {
        return isset($this->options [$optName]);
    }

    /*     * ******************************************************************
     * Option validation
     * ********************************************************************** */

    protected function validInt($value) {
        return (int) $value;
    }

    protected function validString($value) {
        if (is_serialized($value)) {
            return $value;
        }
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $s = preg_replace('(https?://)', '', $value);
            $u = preg_replace('(https?://)', '', get_site_url());
            if (strpos($s, $u) === false) {
                return null;
            }
        }
        return wp_strip_all_tags((string) $value);
    }

    protected function validFloat($value) {
        return (float) $value;
    }

    protected function validArray($value) {
        if (is_string($value)) {
            if (is_serialized($value)) {
                $unser = unserialize($value);
                if(is_array($unser)){
                    return $unser;
                }
            }
            return explode('-', strip_tags($value));
        }
        return (array) $value;
    }

    protected function validBool($value) {
        return (bool) $value;
    }

    /**
     * Validates common options
     *
     * @param array $options New options
     * @return array Assoc array containg only the validated options
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function validateCommonOptions(Array $options) {
        return $this->switchValidationTypes($options, erpDefaults::$comOptsValidations);
    }

    /**
     * Validates options and adds missing ones
     *
     * @param array $options
     * @param array $types
     * @return array
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    protected function switchValidationTypes($options, $types) {
        $validated = array();
        // Validate options that are set
        foreach ($options as $key => $value) {
            if (isset($types [$key])) {
                switch ($types [$key]['type']) {
                    case erpDefaults::intFormal :
                        $val = $this->validInt($value);
                        if (isset($types[$key]['min']) && $val < $types[$key]['min']) {
                            break;
                        }
                        if (isset($types[$key]['max']) && $val > $types[$key]['max']) {
                            break;
                        }
                        $validated [$key] = $val;
                        break;
                    case erpDefaults::stringFormal :
                        $validated [$key] = $this->validString($value);
                        break;
                    case erpDefaults::floatFormal :
                        $validated [$key] = $this->validFloat($value);
                        break;
                    case erpDefaults::arrayFormal :
                        $validated [$key] = $this->validArray($value);
                        break;
                    case erpDefaults::boolFormal :
                        $validated [$key] = $this->validBool($value);
                        break;
                    default:
                        break;
                }
                unset($types[$key]);
            }
        }

        // Add options that are not set
        foreach ($types as $key => $value) {
            switch ($value['type']) {
                case erpDefaults::intFormal :
                    if (isset($this->defaults[$key])) {
                        $validated[$key] = $this->defaults[$key];
                    } elseif (isset($value['min'])) {
                        $validated[$key] = $value['min'];
                    }
                    break;
                case erpDefaults::stringFormal :
                    if (isset($this->defaults[$key])) {
                        $validated[$key] = $this->defaults[$key];
                    }
                    break;
                case erpDefaults::floatFormal :
                    if (isset($this->defaults[$key])) {
                        $validated[$key] = $this->defaults[$key];
                    } elseif (isset($value['min'])) {
                        $validated[$key] = $value['min'];
                    }
                    break;
                case erpDefaults::arrayFormal :
                    $validated[$key] = array();
                    break;
                case erpDefaults::boolFormal :
                    $validated [$key] = false;
                    break;
                default:
                    break;
            }
        }

        return $validated;
    }

    /*     * **********************************************************************
     * Geters for options
     * ********************************************************************** */

    /**
     * If we have to display the post thumb
     * @return boolean
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function haveToShowThumbnail() {
        return isset($this->options['content']) && in_array('thumbnail', $this->options['content']);
    }

    /**
     * If we have to display the post excerpt
     * @return boolean
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function haveToShowExcerpt() {
        return $this->isOptionSet('content') && in_array('excerpt', $this->options['content']);
    }

    /**
     * Get title form settings
     *
     * @return string|null
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getTitle() {
        return $this->getValue('title');
    }

    /**
     * Get number of posts to display
     *
     * @return int|null
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getNumberOfPostsToDiplay() {
        return $this->getValue('numberOfPostsToDisplay');
    }

    /**
     * Get fetchBy option
     *
     * @return string|NULL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getFetchBy() {
        return $this->getValue('fetchBy');
    }

    /**
     * Get offset value
     *
     * @return int|NULL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getOffset() {
        return $this->getValue('offset');
    }

    /**
     * Get content array
     *
     * @return array|NULL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getContentPositioning() {
        return $this->getValue('content');
    }

    /**
     * Get default thumb url
     * @return string|NULL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getDefaultThumbnail() {
        if ($this instanceof erpMainOpts) {
            $t = $this->getValue('defaultThumbnail');
        } else {
            erpPaths::requireOnce(erpPaths::$erpMainOpts);
            $mOpts = new erpMainOpts();
            $t = $mOpts->getDefaultThumbnail();
        }
        return !empty($t) ? $t : erpDefaults::$comOpts['defaultThumbnail'];
    }

    /**
     * Get rel post title font size
     * @return int|NULL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getPostTitleFontSize() {
        return $this->getValue('postTitleFontSize');
    }

    /**
     * Get rel post exc font size
     * @return int|NULL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getExcFontSize() {
        return $this->getValue('excFontSize');
    }

    /**
     * Get rel post exc length
     * @return int|NULL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getExcLength() {
        return $this->getValue('excLength');
    }

    /**
     * Get more text
     *
     * @return string|NULL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getMoreTxt() {
        return $this->getValue('moreTxt');
    }

    /**
     * Get thumbnail height
     *
     * @return int|NULL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getThumbnailHeight() {
        return $this->getValue('thumbnailHeight');
    }

    /**
     * Get thumbnail width
     *
     * @return int|NULL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getThumbnailWidth() {
        return $this->getValue('thumbnailWidth');
    }

    /**
     * Get crop thumb value
     * @return bool|NULL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getCropThumbnail() {
        return $this->getValue('cropThumbnail');
    }

    /**
     * Get display layout value
     * @return string|NULL
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function getDsplLayout() {
        return $this->getValue('dsplLayout');
    }

    public function getPostTitleColor() {
        return $this->getValue('postTitleColor');
    }

    public function getExcColor() {
        return $this->getValue('excColor');
    }

}
