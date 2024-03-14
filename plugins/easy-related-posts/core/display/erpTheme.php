<?php

/*
 * Copyright (C) 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * VPluginTheme.php
 *
 * @package   Easy_Related_Posts_Core_display
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
if (!class_exists('VPluginTheme')) {

    /**
     * Description of VPluginTheme
     * 
     * @package Easy_Related_Posts_Core_display
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     */
    abstract class VPluginTheme {

        /**
         * The name of the theme
         * @var string 
         */
        protected $name;

        /**
         * A description for theme
         * @var string
         */
        protected $description;

        /**
         * An array name if you are going  to save options to DB
         * If no array name is defined then options wont get stored in DB. 
         * Instead they are validated and returned as an assoc array.
         * @var string Default is null 
         */
        protected $optionsArrayName = null;

        /**
         * An assoc array containing theme options if any
         * @var array
         */
        protected $options = array();

        /**
         * An assoc array containing default theme options if any
         * @var array
         */
        protected $defOptions = array();

        /**
         * Unique isntance id. It is generated automaticaly
         * @var string
         */
        protected $uniqueID;

        /**
         * If the theme is loaded
         * @var bool
         */
        protected $isLoaded;

        /**
         * Additional vars to be passed to view
         * @var array
         */
        protected $additionalViewData = array();

        /**
         * Type of theme eg main, widget etc
         * @var string
         */
        protected $type = 'general';

        /**
         * Always call the parent constructor at child classes
         */
        public function __construct() {
            $this->uniqueID = uniqid();
            if ($this->optionsArrayName) {
                $options = get_option($this->optionsArrayName);
                if ($options) {
                    $this->options = $options;
                } else {
                    $this->options = $this->defOptions;
                }
            } else {
                $this->options = $this->defOptions;
            }
        }

        abstract public function validateSettings($newSettings);

        /**
         * Stores theme settings in DB or returns validated options if 
         * optionsArrayName is not set
         * @param type $newSettings
         * @return bool|array If optionsArrayName is set returns bool the result of update option operations, else returns validated settings
         */
        public function saveSettings($newSettings) {
            $validated = $this->validateSettings($newSettings);
            if (is_array($validated) && is_string($this->optionsArrayName) && !empty($this->optionsArrayName)) {
                return update_option($this->optionsArrayName, $validated);
            }
            return $validated;
        }

        /**
         * Render the theme.
         * 
         * @param array $data Data to be passed to view
         * @param string $filePath Path to the file that contains the mark-up
         * @param bool $echo If we should echo the result or just return it
         * @return \WP_Error
         * @since 1.0.0
         */
        public function render($filePath, Array $data = array(), $echo = false) {
            if (!empty($this->additionalViewData)) {
                $data = array_merge($data, $this->additionalViewData);
            }
            if (file_exists($filePath)) {
                return self::view($filePath, $data, $echo);
            }
            return new WP_Error('error', 'File ' . $filePath . ' not found');
        }

        /**
         * Render theme settings
         * 
         * @param string $filePath Path to the markup file
         * @param bool $echo If we should echo the result or just return it
         * @return string
         */
        public function renderSettings($filePath, $echo = false) {
            return self::view($filePath, $this->options, $echo);
        }

        /**
         * Use this if you want to set additional vars to passed to view
         * when rendering themes public facing
         * @param array $data
         * @return \VPluginTheme
         */
        public function setAdditionalViewData(Array $data) {
            $this->additionalViewData = array_merge($this->additionalViewData, $data);
            return $this;
        }

        /**
         * Renders a template
         *
         * @param string $filePath
         *        	The path to markup file
         * @param string $viewData
         *        	Any data passed to markup file
         * @param bool $echo If set to true echoes the out. Default is to return it
         * @return string Rendered content
         * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
         * @since 1.0.0
         */
        public static function view($filePath, $viewData = null, $echo = FALSE) {
            ( $viewData ) ? extract($viewData) : null;

            ob_start();
            include ( $filePath );
            $template = ob_get_contents();
            ob_end_clean();
            if (!$echo) {
                return $template;
            }
            echo $template;
        }

        public function getName() {
            return $this->name;
        }

        public function getDescription() {
            return $this->description;
        }

        public function getOptionsArrayName() {
            return $this->optionsArrayName;
        }

        public function getOptions() {
            return $this->options;
        }

        public function getDefOptions() {
            return $this->defOptions;
        }

        public function getUniqueID() {
            return $this->uniqueID;
        }

        public function getisLoaded() {
            return $this->isLoaded;
        }

        public function getAdditionalViewData() {
            return $this->additionalViewData;
        }

        public function getType() {
            return $this->type;
        }

        public function loadOptions($options) {
            if (is_array($options)) {
                $this->options = array_merge($this->options, $options);
            } elseif (is_string($options)) {
                $fromDB = get_option($options);
                if (is_array($fromDB)) {
                    $this->options = array_merge($this->options, $fromDB);
                }
            }
        }

    }

}

abstract class erpTheme extends VPluginTheme {

    protected $css;
    protected $basePath;
    protected $js;
    protected $preregScripts;

    function __construct() {
        parent::__construct();
    }
    
    public function getNewInstance() {
        return new $this;
    }

    protected function enqPreregScripts() {
        if (is_array($this->preregScripts) && !empty($this->preregScripts)) {
            if (isset($this->preregScripts['css']) && is_array($this->preregScripts['css'])) {
                foreach ($this->preregScripts['css'] as $key => $value) {
                    wp_enqueue_style($value);
                }
            }
            if (isset($this->preregScripts['js']) && is_array($this->preregScripts['js'])) {
                foreach ($this->preregScripts['js'] as $key => $value) {
                    wp_enqueue_script($value);
                }
            }
        }
    }
    
    public function getBasePath() {
        if (!$this->basePath) {
            $reflector = new ReflectionClass(get_class($this));
            $this->basePath = dirname($reflector->getFileName()).DIRECTORY_SEPARATOR;
        }
        return $this->basePath;
    }

    /**
     * Enques all css files as specified in xml file
     * @return \display\erpTemplates
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    protected function enqueCSS() {
        if (isset($this->css) && is_array($this->css) && is_admin_bar_showing() && !is_admin() || !is_admin()) {
            foreach ($this->css as $key => $value) {
                if (is_array($value)) {
                    wp_enqueue_style($key.'-'.$this->uniqueID, $this->getUrl($value['path']), $value['deps'], easyRelatedPosts::VERSION);
                } else {
                    wp_enqueue_style($key.'-'.$this->uniqueID, $this->getUrl($value), array(), easyRelatedPosts::VERSION);
                }
            }
        }
        return $this;
    }

    /**
     * Enques all js files as specified in xml file
     * @return \display\erpTemplates
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    protected function enqueJS() {
        if (isset($this->js) && is_array($this->js) && is_admin_bar_showing() && !is_admin() || !is_admin()) {
            foreach ($this->js as $key => $value) {
                if (is_array($value)) {
                    wp_enqueue_script($key.'-'.$this->uniqueID, $this->getUrl($value['path']), $value['deps'], easyRelatedPosts::VERSION, false);
                } else {
                    wp_enqueue_script($key.'-'.$this->uniqueID, $this->getUrl($value), array(), easyRelatedPosts::VERSION, false);
                }
            }
        }
        return $this;
    }

    
    public function render($filePath, Array $data = array(), $echo = false) {
        $this->enqPreregScripts();
        $this->enqueCSS();
        $this->enqueJS();
        return parent::render($filePath, $data, $echo);
    }

    /**
     * Get the url for a given file
     * @param string $templateFileRelativePath Relative path from the template base path
     * @return string Url
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    protected function getUrl($templateFileRelativePath) {
        return plugins_url($templateFileRelativePath, $this->basePath.'*.php');
    }

    public function formPostData(WP_Query $wpq, erpOptions $optionsObj, $ratings = array()) {
        erpPaths::requireOnce(erpPaths::$erpPostData);

        $data = array(
            'title' => $optionsObj->getValue('title'),
            'options' => $this->options,
            'uniqueID' => $this->uniqueID,
            'optionsObj' => $optionsObj,
            'posts' => array()
        );
        
        while ($wpq->have_posts()) {
            $wpq->the_post();

            $rating = isset($ratings[get_the_ID()]) ? $ratings[get_the_ID()] : null;

            $postData = new erpPostData($wpq->post, $optionsObj, $rating);

            if ($optionsObj->haveToShowExcerpt()) {
                $postData->setExcerpt($optionsObj->getValue('excLength'), $optionsObj->getValue('moreTxt'));
            }

            if ($optionsObj->haveToShowThumbnail()) {
                $postData->setThumbnail($optionsObj->getDefaultThumbnail());
            }

            array_push($data['posts'], $postData);
        }

        wp_reset_postdata();

        $this->setAdditionalViewData(array_merge($data, $this->options));
        return $this;
    }

    public function setOptions($options) {
        $this->options = array_merge($this->options, $options);
    }
}
