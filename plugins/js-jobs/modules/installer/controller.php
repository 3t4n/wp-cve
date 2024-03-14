<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSControllerinstallerController {

    function __construct() {
        
    }

    function installation() {
        JRequest :: setVar('jsjobslt', 'installer');
        JRequest :: setVar('view', 'installer');
        $this->display();
    }

    function makeDir($path) {
        if (!file_exists($path)) {
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
            fclose($ourFileHandle);
        }
    }

    function recursiveremove($dir) {
        $structure = glob(jsjobslib::jsjobs_rtrim($dir, "/") . '/*');
        if (is_array($structure)) {
            foreach ($structure as $file) {
                if (is_dir($file))
                    $this->recursiveremove($file);
                elseif (is_file($file))
                    unlink($file);
            }
        }
        rmdir($dir);
    }

    function showresult() {
        $this->setRedirect('index.php?option=com_jsjobs&view=installer&jsjobslt=finalstep');
    }

    function completeinstallation() {
        $data = JSJOBSrequest::getVar('post');
        $this->installSampleData($data['install_sample_data'], $data['create_menu_link']);
        $this->updateconfiguration($data);
        $this->setRedirect('index.php?option=com_jsjobs');
    }

    function updateConfiguration($data) {
        //DB class limitations
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_config` SET configvalue = '" . $data['showemployerlink'] . "' WHERE configname = 'showemployerlink'";
        jsjobsdb::query($query);
        
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_config` SET configvalue = '" . $data['newlisting_requiredpackage'] . "' WHERE configname = 'newlisting_requiredpackage'";
        jsjobsdb::query($query);
        
        
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_config` SET configvalue = '" . $data['visitor_can_post_job'] . "' WHERE configname = 'visitor_can_post_job'";
        jsjobsdb::query($query);
        
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_config` SET configvalue = '" . $data['js_newlisting_requiredpackage'] . "' WHERE configname = 'js_newlisting_requiredpackage'";
        jsjobsdb::query($query);
        
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_config` SET configvalue = '" . $data['visitor_can_apply_to_job'] . "' WHERE configname = 'visitor_can_apply_to_job'";
        jsjobsdb::query($query);
        
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_config` SET configvalue = '" . $data['offline'] . "' WHERE configname = 'offline'";
        jsjobsdb::query($query);
    }

    function createMenuLink($menu) {


        $query = "SELECT max(lft) AS max_lft,max(rgt) AS max_rgt FROM `" . jsjobs::$_db->prefix . "menu` WHERE menutype=" . $db->quote('mainmenu');

        $result = jsjobsdb::get_row($query);
        if (empty($result)) {
            $app = JFactory::getApplication();
            $menusite = $app->getMenu('site');
            $lang = JFactory::getLanguage();
            $menutype = $menusite->getDefault($lang->getTag())->menutype;
            $query = "SELECT max(lft) AS max_lft,max(rgt) AS max_rgt FROM `" . jsjobs::$_db->prefix . "menu` WHERE menutype=" . $db->quote($menutype);

            $result = jsjobsdb::get_row($query);
        }
        if (!empty($result)) {
            $query = "SELECT id, lft, rgt, level FROM `" . jsjobs::$_db->prefix . "menu` WHERE lft =" . $db->quote($result->max_lft) . " AND rgt=" . $db->quote($result->max_rgt);

            $result = jsjobsdb::get_row($query);

            $query = "UPDATE `" . jsjobs::$_db->prefix . "menu` SET lft = lft+2 where lft > " . $result->lft;

            jsjobsdb::query($query);

            $query = "UPDATE `" . jsjobs::$_db->prefix . "menu` SET rgt = rgt+2 where rgt > " . $result->rgt;

            jsjobsdb::query($query);

            $query = "SELECT extension_id FROM `" . jsjobs::$_db->prefix . "extensions` WHERE type=" . $db->quote('component') . " AND element=" . $db->quote('com_jsjobs');

            $extension = jsjobsdb::get_row($query);

            $query = "SELECT MAX(id+1) FROM `" . jsjobs::$_db->prefix . "menu`";

            $id_for_menu = jsjobsdb::get_var($query);
            $link_left = $result->lft + 2;
            $link_right = $result->rgt + 2;

            $menu_link = "INSERT INTO `" . jsjobs::$_db->prefix . "menu` (`id`,`menutype`, `title`, `alias`, `note`, `path`, `link`, `type`, `published`, `parent_id`, `level`, `component_id`, `checked_out`, `checked_out_time`, `browserNav`, `access`, `img`, `template_style_id`, `params`, `lft`, `rgt`, `home`, `language`, `client_id`) 
			VALUES(" . $id_for_menu . ",'mainmenu','" . $menu['title'] . "','" . $menu['alias'] . "','','" . $menu['path'] . "','" . $menu['link'] . "','component',1,1,1," . $extension->extension_id . ",0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}'," . $link_left . "," . $link_right . ",0,'*',0);";
            $db->setQuery($menu_link);
            $db->query();
            return true;
        }
    }

    

    function insertJobCities($jobid, $cityid) {

        $insert_jobcity = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobcities` (`jobid`, `cityid`) 
        VALUES( " . $jobid . ", " . $cityid . ");";
        $db->setQuery($insert_jobcity);
        $db->query();
        return true;
    }

    function display($cachable = false, $urlparams = false) {
        $document = JFactory :: getDocument();
        $viewName = JSJOBSrequest::getVar('view', 'installer');
        $layoutName = JSJOBSrequest::getVar('jsjobslt', 'installer');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('installer', 'JSAutozModel');
        if (!JError::isError($model)) {
            $view->setModel($model, true);
        }
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
