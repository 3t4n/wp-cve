<?php
/*
   Plugin Name: Twenty Eleven Theme Extensions
   Plugin URI: http://moztools.com/wordpress/theme2011-plugin
   Description: Easy-to-use customizations for the default Twenty Eleven WordPress theme.
   Author: Mike Walker, MozTools
   Version: 1.2
   Author URI: http://moztools.com/

   Copyright 2011  Michael J. Walker (email: mike@moztools.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once 'moztools/mozbase.php';

define('MOZTHEME2011_OPTIONS', 'moztheme2011');
define('MOZTHEME2011_NAME', 'Twenty Eleven');
define('MOZTHEME2011_TEMPLATE', 'twentyeleven');

if (is_admin()) {
    include_once 'moztheme2011admin.php';
    MozBaseClass_MozTheme2011::registerPluginAdmin('MozTheme2011Admin', 'setup_theme');
} else {
    MozBaseClass_MozTheme2011::registerPlugin('MozTheme2011', 'setup_theme');
}

class MozTheme2011 extends MozBaseClass_MozTheme2011 {

    protected $pluginOptions = MOZTHEME2011_OPTIONS;
    private $themeExtensions = 'themes.php?page=moztheme2011';
    private $themeOptions = 'themes.php?page=theme_options';
    
    private $inSidebar = false;  // True when processing sidebar template
    private $idHeadline = false; // Set to id of first stick post when encountered
    
    public static function create() {
        if (get_template() == MOZTHEME2011_TEMPLATE || strpos(get_current_theme(), MOZTHEME2011_NAME) !== false) {
            new MozTheme2011();
        }
    }

    function __construct() {
        parent::__construct();
        $this->registerStyles('moztheme2011');
        $this->registerFilter('body_class', 'filterSingular', 100);
        
        if ($this->isOptionSet('enable-custom-colors') || $this->isOptionSet('embed-custom-css')) { 
            $this->registerAction('wp_head', 'embedStyles');
        }
                
        if ($this->isOptionSet('sidebar-post') || $this->isOptionSet('sidebar-page')) {
            $this->registerAction('get_footer', 'addSidebar');
            $this->registerAction('get_sidebar', 'inSidebar');
        }
        
        if ($this->isOptionSet('headline')) {
            $this->registerFilter('the_title', 'addHeadline', 10, 2);
            $this->registerFilter('post_class', 'addHeadlineClass');
        }
        
        if ($this->isOptionSet('image-size')) {
            $this->registerFilter('twentyeleven_header_image_height', 'getHeaderImageHeight');
            if ($this->isOptionSet('force-image-size')) { 
                wp_enqueue_script("jquery");
                $this->registerAction('wp_head', 'embedScript');
            }
        }
        if (is_user_logged_in()) {
            $this->registerAction('wp_before_admin_bar_render', 'addThemeMenuItems');
        }
    }
    
    public function getHeaderImageHeight($height) {
        return $this->isOptionSet('image-size') ? $this->getOption('image-height') : $height;
    }
    
    private function hasSidebar() {
        return $this->isOptionSet('sidebar-post') && is_single() || $this->isOptionSet('sidebar-page') && is_page();
    }
    
    private function hasHeadline($id) {
        return $this->isOptionSet('headline') && is_home() && ($this->idHeadline === false || $this->idHeadline == $id);
    }
    
    public function addSidebar() {
        if ($this->hasSidebar()) {
            get_sidebar();
        }
    }
    
    public function filterSingular($classes) {
        if ($this->hasSidebar()) {
            $key = array_search('singular', $classes);
            if ($key !== false) {
                unset($classes[$key]);
            }
            $classes[] = 'moz-sidebar-adjust';
            if ($this->isOptionSet('adjust-nav')) {
                $classes[] = 'moz-nav-adjust';
            }
        }
        if ($this->isOptionSet('align-widget-titles')) {
            $classes[] = 'moz-widget-list-adjust';
        }
        return $classes;
    }
    
    public function addHeadline($title, $id) {
        if (is_home() && !$this->inSidebar && is_sticky($id) 
                      && ($this->idHeadline === false || $this->idHeadline == $id)) {
            $text = $this->getOption('headline-text');
            $pos = strpos($text, '%title%');
            if ($pos !== false) {
                $text = explode('%title%', $text);
                foreach($text as $i => $str) {
                    if (!empty($str)) {
                        $text[$i] = '<span class="headline-text">'.$str.'</span>';
                    }
                }
                $title = $text[0].$title.$text[1];
            } else {
                $title = '<span class="headline-text">'.$text.'</span>'.$title;
            }
            $this->idHeadline = $id;
        }
        return $title;
    }
    
    public function addHeadlineClass($classes) {
        global $post;
        if ($this->hasHeadline($post->ID)) {
            $classes[] = 'moz-headline';
        }
        return $classes;
    }
    
    public function inSidebar() {
        $this->inSidebar = true;
    }
    
    /**
     * Embed CSS styles from the Custom Colors and Custom CSS
     * theme extensions options.
     */
    public function embedStyles() { 
	    echo '<style type="text/css">';
	    if ($this->isOptionSet('enable-custom-colors')) {
            echo $this->getOption('custom-colors-css');
	    }
        if ($this->isOptionSet('embed-custom-css')) { 
            echo $this->getOption('custom-css');
        }
        echo '</style>';
    }

    /**
     * Embed a snippet of javascript for when we're resizing the header image.
     */
    public function embedScript() { 
        echo '<script type="text/javascript">'.chr(13).'//<![CDATA['.chr(13);
        echo 'jQuery(document).ready(function($) {';
        echo '   $("#branding img").css("height", $("#branding img").attr("height"));'; 
        echo '});';
        echo chr(13).'//]]>'.chr(13).'</script>';
    }
    
    /**
     * Add Theme Options and Theme Extensions to the admin menu
     * bar you see on your site when you are logged in.
     */
    function addThemeMenuItems() {
        global $wp_admin_bar;
        $wp_admin_bar->add_menu(array('parent' => 'appearance', 'id' => 'theme_options',
                                      'title' => __('Theme Options'),'href' => get_admin_url().$this->themeOptions));
        $wp_admin_bar->add_menu(array('parent' => 'appearance', 'id' => 'theme_extensions',
                                      'title' => __('Theme Extensions'),'href' => get_admin_url().$this->themeExtensions));
    }
}