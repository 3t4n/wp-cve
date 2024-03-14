<?php

/**
 * Easy related posts .
 *
 * @package   Easy_Related_Posts_Options
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
erpPaths::requireOnce(erpPaths::$erpOptions);

/**
 * Widget options class.
 *
 * @package Easy_Related_Posts_Options
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class erpWidOpts extends erpOptions {

    public function __construct(Array $instance = NULL) {
        parent::__construct();
        $this->optionsArrayName = 'widget_' . erpDefaults::erpWidgetOptionsArrayName;

        $this->defaults = erpDefaults::$widOpts + erpDefaults::$comOpts;
        
        if ($instance !== NULL && !empty($instance)) {
            $this->options = $instance;
        } else {
            $this->options = $this->defaults;
        }
    }

    /**
     * Validates widget options
     *
     * @param array $options New options
     * @return array Assoc array containg only the validated options
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function validateWidgetOptions(Array $options) {
        return $this->switchValidationTypes($options, erpDefaults::$widOptsValidations);
    }

    public function saveOptions($new_instance, $old_instance) {
        return $this->validateCommonOptions($new_instance) + $this->validateWidgetOptions($new_instance);
    }

    /*     * **********************************************************************
     * Geters for options
     * ********************************************************************** */

    public function getPostTitleColor() {
        return $this->getValue('postTitleColor');
    }

    public function getExcColor() {
        return $this->getValue('excColor');
    }

    public function getHideIfNoPosts() {
        return $this->getValue('hideIfNoPosts');
    }

}
