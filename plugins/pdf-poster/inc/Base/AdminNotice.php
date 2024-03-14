<?php
namespace PDFPro\Base;

use PDFPro\Helper\Plugin;
use PDFPro\Helper\Pipe;

global $pdfp_bs;

class AdminNotice{
    protected static $closed_ver = null;
    protected $post_type = 'pdfposter';

    public function register(){
        if(\is_admin()){
            self::$closed_ver = get_option('pdfp-notice-update', Plugin::version());

            add_action('admin_notices', [$this, 'updateNotice']);
            add_action('init', [$this, 'init']);

            add_action('admin_init', [$this, 'checkPipe']);
        }
    }
 
    public function updateNotice(){
        $screen = get_current_screen();
        if($screen->post_type === 'pdfposter' && self::$closed_ver < Plugin::getLatestVersion() && Plugin::getLatestVersion() > Plugin::version() && !isset($_GET['pdfp-notice-update'])){
            echo "<div class='notice notice-warning pdfp-notice pdfp-notice-update'><p>PDF Poster Pro new version ".Plugin::getLatestVersion()." available. <a href='https://pdfposter.com/downloads/' target='_blank'>Click Here</a> to get latest version.</p><p><a href='".site_url("wp-admin/$screen->parent_file&pdfp-notice-update=true")."'>Close</a></p></div>";
        }
    }
 

    public function init(){
        if(isset($_GET['pdfp-notice-import']) && $_GET['pdfp-notice-import'] == 'true'){
            update_option('pdfp-notice-import', true);
        }

        if(isset($_GET['pdfp-notice-update']) && $_GET['pdfp-notice-update'] == 'true'){
            update_option('pdfp-notice-update', Plugin::getLatestVersion());
        }
    }

    public function checkPipe(){
        global $pagenow;
        if('edit.php' === $pagenow && isset($_GET['post_type']) && $_GET['post_type'] === 'pdfposter' && !Pipe::checkPipe()){
            update_option('flcbplsc', array(
                'key' => '',
                'active' => false
            ));
        }
    }
}
