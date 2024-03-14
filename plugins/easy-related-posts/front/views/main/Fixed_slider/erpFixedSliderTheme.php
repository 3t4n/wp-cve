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
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
erpPaths::requireOnce(erpPaths::$erpTheme);

/**
 * Description of erpTheme
 * 
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class erpFixedSliderTheme extends erpTheme{

    /**
     * The name of the theme
     * @var string 
     */
    protected $name = 'Fixed slider';

    /**
     * A description for theme
     * @var string
     */
    protected $description = 'Display related as a fixed slider';

    /**
     * An array name if you are going  to save options to DB
     * If no array name is defined then options wont get stored in DB. 
     * Instead they are validated and returned as an assoc array.
     * @var string Default is null 
     */
    protected $optionsArrayName = 'erpFixedSliderOptions';

    /**
     * An assoc array containing default theme options if any
     * @var array
     */
    protected $defOptions = array(
        'position' => 'bottom',
        'numOfPostsPerRow' => 3,
        'backgroundColor' => '#ffffff',
        'backgroundTransparency' => 0.8,
        'triggerAfter' => 0.8,
        'thumbCaption' => false
    );
    
    protected $css = array(
        'sliderCSS' => 'assets/css/slider.css'
    );
    protected $js = array(
        'sliderJS' => array(
            'path' => 'assets/js/slider.js',
            'deps' => array('jquery')
        )
    );
    protected $preregScripts = array(
        'css' => array('erp-bootstrap', 'erp-bootstrap-text', 'erp-erpCaptionCSS'),
        'js' => array('erp-erpCaptionJS')
    );
    
    /**
     * Type of theme eg main, widget etc
     * @var string
     */
    protected $type = 'main';

    /**
     * Always call the parent constructor at child classes
     */
    public function __construct() {
        $this->basePath = plugin_dir_path(__FILE__);
        parent::__construct();
    }

    public function validateSettings($options){
        $newOptions = array ();
	if (isset($options [ 'position' ])) {
		$newOptions['position'] = strip_tags($options [ 'position' ]);
	}
	if (isset($options [ 'backgroundColor' ])) {
		$newOptions['backgroundColor'] = strip_tags($options [ 'backgroundColor' ]);
	}
	if (isset($options [ 'backgroundTransparency' ]) && $options [ 'backgroundTransparency' ] > 0 &&  $options [ 'backgroundTransparency' ] <= 1) {
		$newOptions['backgroundTransparency'] = (float)$options [ 'backgroundTransparency' ];
	}
	if (isset( $options [ 'triggerAfter' ] ) && $options [ 'triggerAfter' ] > 0 &&  $options [ 'triggerAfter' ] <= 1) {
		$newOptions['triggerAfter'] = (float)$options['triggerAfter'];
	}
	if (isset( $options [ 'numOfPostsPerRow' ] ) && $options [ 'numOfPostsPerRow' ] > 0) {
		$newOptions['numOfPostsPerRow'] = (int)$options['numOfPostsPerRow'];
	}
	$newOptions['thumbCaption'] = isset( $options [ 'thumbCaption' ] );
	return array_merge($this->defOptions, $newOptions);
    }

    public function render($path = '', Array $data = array(), $echo = false) {
        return parent::render(plugin_dir_path(__FILE__).'slider.php', $data, $echo);
    }
    
    public function renderSettings($filePath = '', $echo = false) {
        return parent::renderSettings(plugin_dir_path(__FILE__).'settings.php', $echo);
    }
}
