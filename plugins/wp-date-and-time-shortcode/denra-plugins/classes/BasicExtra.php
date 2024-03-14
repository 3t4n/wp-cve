<?php

/**
 * Basic Extra
 *
 * Added extra properties and methods to the Basic related to file, dir, url.
 *
 * @author     Denra.com aka SoftShop Ltd <support@denra.com>
 * @copyright  2019-2020 Denra.com aka SoftShop Ltd
 * @license    GPLv2 or later
 * @version    1.1.1
 * @link       https://www.denra.com/
 */

namespace Denra\Plugins;

/**
 * Description of BasicWithFile
 *
 * @author Ivaylo Tinchev
 */
class BasicExtra extends Basic {
    
    public $file; // file
    public $dir; // dir to file
    public $dir_classes; // dir to classes
    public $url; // url to file without the file name
    public $plugin_basename; // the plugin basename
    
    public $text_domain = '';
    
    public function __construct($id, $data = []) {
        
        (isset($data['dir']) && $data['dir']) || die('<p>Plugin dir info needed for '.get_class($this).'.</p>');
        (isset($data['url']) && $data['url']) || die('<p>Plugin url info needed for '.get_class($this).'.</p>');
        
        parent::__construct($id, $data);
        
        // Set more important data
        $this->file = $data['file'];
        $this->dir = $data['dir'];
        $this->dir_classes = $data['dir'] . 'classes/';
        $this->url = $data['url'];
        $this->plugin_basename = \plugin_basename($this->file);
        
        // Load Text Domain
        if ($this->text_domain) {
            $mofile = $this->dir . 'i18n/' .  $this->text_domain . '-' . \get_locale() . '.mo';
            if (file_exists($mofile)) {
                \load_textdomain($this->text_domain, $mofile);
            }
        }
        
        //add_action('plugins_loaded', [&$this, 'loadTextDomain']);
        
        // Enqueue CSS and JS
        if (\current_user_can('manage_options')) {
            \add_action('admin_enqueue_scripts', [&$this, 'enqueueAdminCSSandJS'], PHP_INT_MAX);
        }
        \add_action('wp_enqueue_scripts', [&$this, 'enqueueUserCSSandJS'], PHP_INT_MAX);
        
    }
    
    public function enqueueAdminCSSandJS() {
        
        $this->enqueueCSS($this->id . '-admin', 'static/css/admin.css', 'all');
        $this->enqueueJS($this->id . '-admin', 'static/js/admin.js', FALSE);
        
    }
    
    public function enqueueUserCSSandJS() {
        
        $this->enqueueCSS($this->id . '-user', 'static/css/user.css', 'all');
        $this->enqueueJS($this->id . '-user', 'static/js/user.js', FALSE);
        
    }
    
    public function enqueueCSS($id, $file_css, $media = 'all') {
        
        $dir_file_css = $this->dir . $file_css;
        $url_file_css = $this->url . $file_css;
        if (file_exists($dir_file_css)) {
            \wp_enqueue_style($id, $url_file_css, [], filemtime($dir_file_css), $media);
        }
        
    }
    
    public function enqueueJS($id, $file_js, $in_footer = FALSE) {
        
        $dir_file_js = $this->dir . $file_js;
        $url_file_js = $this->url . $file_js;
        if (file_exists($dir_file_js)) {
            \wp_enqueue_script($id, $url_file_js, [], filemtime($dir_file_js),  $in_footer);
        }
        
    }
    
}
