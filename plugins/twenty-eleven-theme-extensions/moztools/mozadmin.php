<?php
/*  Copyright 2011  Michael J. Walker (email: mike@moztools.com)

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

include_once 'mozbase.php';

/**
 * Base class for all admin plugin classes.
 *
 * @author Michael J Walker
 */
class MozAdminBaseClass_MozTheme2011 extends MozBaseClass_MozTheme2011 {

    protected $adminMenu = 'unknown';
    protected $adminTitle = 'unknown';
    protected $adminMenuTitle = 'unknown';
    protected $adminMenuSlug = 'unknown';
    protected $adminCapability = 8;
    
    private $adminPageLink;
    private $formData;
    private $helpText;
    
    private static $functionMap = array('dashboard' => 'dashboard', 'posts' => 'posts',
                              'media' => 'media', 'links' => 'links',
                              'pages' => 'pages', 'comments' => 'comments',
                              'appearance' => 'theme', 'plugins' => 'plugins',
                              'users' => 'users', 'tools' => 'management',  
                              'settings' => 'options');  
     
    private static $urlMap = array('dashboard' => 'index.php', 'posts' => 'edit.php',
                          'media' => 'upload.php', 'links' => 'link-manager.php',
                          'pages' => 'edit.php?post_type=page', 'comments' => 'edit-comments.php',
                          'appearance' => 'themes.php', 'plugins' => 'plugins.php',
                          'users' => 'users/profile.php', 'tools' => 'tools.php',  
                          'settings' => 'options-general.php');  

    /**
     * Base class constructor.
     */
    protected function __construct() {
        parent::__construct();
        if ($this->ajaxRequest) {
            $this->initAjax();
        } else {
            register_activation_hook(__FILE__, $this->fn('pluginActivation'));
            register_deactivation_hook(__FILE__, $this->fn('pluginDeactivation'));
            $this->init();
        }
    }
    
    protected function init() {
        if (did_action('admin_menu') == 0) {
            // If we're too early, then call handler via later hook.
            $this->registerAction('admin_menu', 'handleAdmin');
        } else {
            $this->handleAdmin();
        }
    }
        
    public function handleAdmin() {
        $this->addAdminPage();
        $this->addAdminPageLink('Settings');
        if ($this->isAdminPage() && $this->hasAction()) {
            $action = $this->getAction();
            $this->removeAction();
            $this->processAdminRequest($this->getAction(), $_POST);
        }
    }

    /**
     * Add the manage embeds admin page to the admin menu, and if there
     * was a problem installing this version of Embedder, then hook into
     * the admin_notices action to display the error message.
     */
    protected function addAdminPage() {
        call_user_func('add_'.(self::$functionMap[$this->adminMenu]).'_page', 
                       $this->adminTitle, $this->adminMenuTitle,
                       $this->adminCapability, $this->adminMenuSlug,
                       $this->fn('displayAdminPage'));
    }

    /**
     * Placeholder for displaying the admin page of the plugin. 
     */
    public function displayAdminPage() {
        echo 'Insert Admin Page text here';
    }
    
    /**
     * Placeholder for processing an admin request for the plugin.
     */
    protected function processAdminRequest($action, $params) {        
    }

    /**
     * Add an admin page link to the Plugin page.
     * 
     * @param string Name of the link (typically 'Settings')
     */
    function addAdminPageLink($linkName) {
        add_filter('plugin_action_links_'.$this->pluginFile, $this->fn('pluginLinksCallback'), 10, 4);
        $this->adminPageLink = $linkName;
    }

    /**
     * Add help text (or HTML) to the context help panel pulldown at the top of
     * the admin page. Delay the addition of the text if the method was called
     * too early in the request processing.
     * @param string $text help text to be added
     */
    protected function addHelp($text) {
        $this->helpText = $text;
        if (did_action('admin_menu') > 0) {
            $this->addHelpText();
        } else {
            $this->registerAction('admin_menu', 'addHelpText');
        }
    }
    
    /**
     * Add help text (or HTML) to the context help panel pulldown at the top of
     * the admin page.
     */
    public function addHelpText() {
        add_contextual_help($this->adminMenu.'_page_'.$this->adminMenuSlug, $this->helpText);
    }
    
    /**
     * Callback for setting links in the plugin page.
     * 
     * @param array $meta metadata for the links
     * @param string $file name of the plugin file
     * @param array $data list of plugin info from main plugin file
     * @param string $context the context of the callback
     */
    function pluginLinksCallback($meta, $file, $data, $context) {
        $link = '<a href="'.$this->getAdminUrl().'">'.$this->adminPageLink.'</a>';
        $meta[] = $link;
        return $meta;
    }
    
    protected function getAdminUrl($page = '') {
        return self::$urlMap[$this->adminMenu].'?page='.($page == '' ? $this->adminMenuSlug : $page);
    }
    
    protected function isAdminPage() {
        return $_REQUEST['page'] == $this->adminMenuSlug;
    }
    
    protected function registerAjax($fnames) {
        if (!is_array($fnames)) {
            $fnames = array($fnames);
        }
        foreach ($fnames as $name) {
            add_action('wp_ajax_'.$name, $this->fn($name));
        }
    }
    
    protected function hasAction() {
        return !empty($_POST) && !empty($_POST['action']);
    }
    
    protected function getAction() {
        return $this->hasAction() ? $_POST['action'] : false;
    }
    
    protected function removeAction() {
        unset($_POST['action']);
    }
    
    protected function setFormData($data) {
        $this->formData = $data;
    }
    
    protected function getValue($id, $default) {
        return isset($this->formData) && isset($this->formData[$id]) ? $this->formData[$id] : $default;
    }
    
    /**
     * Note, if the state is not set, then we must assume that it's false (i.e. unset)
     */
    protected function getState($id) {
        return isset($this->formData) && isset($this->formData[$id]) ? $this->formData[$id] : false;
    }
    
    protected function embedAjax() { ?>
        <div id="ajax-fields" style="display:none">
            <input type="hidden" id="ajax-url" value="<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php" /> 
            <img id="ajax-spinner" src="<?php echo get_option('siteurl'); ?>/wp-admin/images/wpspin_light.gif" class="ajax-spinner"/>
        </div>    
        <?php 
    }

    protected function pluginActivation() {
    }

    protected function pluginDeactivation() {
    }
    
    protected function title($icon, $title) {
        return '<div id="'.$icon.'" class="icon32"><br /></div><h2>'.$title.'</h2>';
    }
    protected function wrapClass($content, $class) {
        return '<div class="'.$class.'">'.$content.'</div>';
    }
    protected function wrapForm($content, $action, $id = '') {
        return '<form id="'.(!empty($id) ? $id : 'mz-admin-form').'" action="'.$action.'" method="post">'.$content.'</form>';
    }
    protected function wrapTable($content) {
        return '<table class="form-table"><tbody>'.$content.'</tbody></table>';
    }
    protected function wrapRow($content, $rowTitle, $idHelp = false) {
        return '<tr valign="top"><th scope="row">'.$rowTitle.($help !== false ? '<img id="'.$idHelp.'" src="'.$this->pluginUrl.'/images/help_16.png" title="Click here for help">' : '').'</th><td>'.$content.'</td></tr>';
    }
    protected function wrapIndent($content) {
        return '<div class="moz-indent">'.$content.'</div>';
    }
    protected function hiddenInput($id, $value = '', $classes = '') {
        return '<input id="'.$id.'" type="hidden" value="'.$this->getValue($id, $value).'" name="'.$id.'" class="'.$classes.'"/>';
    }
    protected function textInput($id, $label = '', $value = '', $description = '') {
        return '<span id="'.$id.'-field"><input id="'.$id.'" class="regular-text code" type="text" value="'.$this->getValue($id, $value).'" name="'.$id.'" />'
        .' '.$label.'</label>'.(!empty($description) ? '<div /><span class="description">'.$description.'</span>' : '').'</span>';
    }
    protected function textArea($id, $rows = 10, $label = '', $value = '') {
        return '<div id="'.$id.'-field">'.(!empty($label) ? $label.$this->crlf() : '')
              .'<textarea id="'.$id.'" rows="'.$rows.'" class="large-text code" name="'.$id.'">'.$this->getValue($id, $value).'</textarea></div>';
    }
    protected function checkBox($id, $label = '') {
        return '<label id="'.$id.'-field" for="'.$id.'"><input id="'.$id.'" type="checkbox" '.($this->getState($id) ? 'checked="checked"' : '').' name="'.$id.'">'
        .' '.$label.'</label>';
    }
    protected function radioButton($id, $label = '') {
        return '<label id="'.$id.'-field" for="'.$id.'"><input id="'.$id.'" type="radio" '.($this->getState($id) ? 'checked="checked"' : '').' name="'.$id.'">'
        .' '.$label.'</label>';
    }
    protected function crlf($n = 2) {
        return '<div style="padding-top:'.$n.'px"></div>';
    }
    protected function submitButton($id, $label) {
        return '<p class="submit"><input id="'.$id.'" class="button-primary" type="submit" value="'.$label.'" name="'.$id.'"></p>';
    }
    protected function helpText($file) {
        return '<div class="help-contents" style="display:none">'.file_get_contents($this->pluginDir.'/'.$file.'.html').'</div>';
    }
}
