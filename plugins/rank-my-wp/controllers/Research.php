<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class RKMW_Controllers_Research extends RKMW_Classes_FrontController {

    public $blogs;
    public $kr;
    //--
    public $keywords = array();
    public $suggested = array();
    public $rankkeywords = array();
    public $labels = array();
    public $countries = array();
    public $post_id = false;
    //--
    public $index;
    public $error;
    public $user;

    /** @var object Checkin process with Cloud */
    public $checkin;

    function init() {

        $page = str_replace(strtolower(RKMW_NAMESPACE) . '_', '', RKMW_Classes_Helpers_Tools::getValue('page'));
        $tab = RKMW_Classes_Helpers_Tools::getValue('tab', 'research');
        if (strpos($page, '/') !== false) {
            $tab = substr($page, strpos($page, '/') + 1);
            $page = substr($page, 0, strpos($page, '/'));
        }

        //Check if the menu is called with slash tab
        $menu = $page . '/' . $tab;
        if (RKMW_Classes_Helpers_Tools::menuOptionExists($menu) && !RKMW_Classes_Helpers_Tools::getMenuVisible($menu)) {
            echo $this->getView('Errors/Inactive');
            return;
        }

        //Checkin to API
        $this->checkin = RKMW_Classes_RemoteController::checkin();

        if (is_wp_error($this->checkin)) {
            if ($this->checkin->get_error_message() == 'no_data') {
                echo $this->getView('Errors/Error');
                return;
            } elseif ($this->checkin->get_error_message() == 'maintenance') {
                echo $this->getView('Errors/Maintenance');
                return;
            }
        }

        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap-reboot');
        if (is_rtl()) {
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('popper');
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap.rtl');
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('rtl');
        } else {
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('popper');
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap');
        }
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('switchery');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('datatables');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('fontawesome');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('global');

        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('navbar');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('research');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia($tab);
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('chart');

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_print_styles();
        wp_print_scripts();

        if (method_exists($this, $tab)) {
            call_user_func(array($this, $tab));
        }

        echo $this->getView(ucfirst($page) . '/' . ucfirst($tab));
    }

    public function research() {
        add_action('rkmw_form_notices', array($this, 'getNotificationBar'));

        $countries = RKMW_Classes_RemoteController::getKrCountries();

        if (!is_wp_error($countries)) {
            $this->countries = $countries;
        } else {
            $this->error = $countries->get_error_message();
        }
    }

    public function briefcase() {
        add_action('rkmw_form_notices', array($this, 'getNotificationBar'));

        $search = (string)RKMW_Classes_Helpers_Tools::getValue('skeyword', '');
        $labels = RKMW_Classes_Helpers_Tools::getValue('slabel', false);

        $args = array();
        $args['search'] = $search;
        if ($labels && !empty($labels)) {
            $args['label'] = join(',', $labels);
        }

        $briefcase = RKMW_Classes_RemoteController::getBriefcase($args);
        $this->rankkeywords = RKMW_Classes_RemoteController::getRanks();

        if (!is_wp_error($briefcase)) {
            if (isset($briefcase->keywords) && !empty($briefcase->keywords)) {
                $this->keywords = $briefcase->keywords;
            } else {
                $this->error = esc_html__("No keyword found.", RKMW_PLUGIN_NAME);
            }

            if (isset($briefcase->labels)) {
                $this->labels = $briefcase->labels;
            }

        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('briefcase');

    }

    public function labels() {
        add_action('rkmw_form_notices', array($this, 'getNotificationBar'));

        $args = array();
        if (!empty($labels)) {
            $args['label'] = join(',', $labels);
        }

        $briefcase = RKMW_Classes_RemoteController::getBriefcase($args);

        if (!is_wp_error($briefcase)) {
            if (isset($briefcase->labels)) {
                $this->labels = $briefcase->labels;
            }
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('briefcase');

    }

    public function suggested() {
        add_action('rkmw_form_notices', array($this, 'getNotificationBar'));

        //Get the briefcase keywords
        if ($briefcase = RKMW_Classes_RemoteController::getBriefcase()) {
            if (!is_wp_error($briefcase)) {
                if (isset($briefcase->keywords)) {
                    $this->keywords = $briefcase->keywords;
                }
            }
        }

        $this->suggested = RKMW_Classes_RemoteController::getKrFound();

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('briefcase');

    }

    function history() {

        $args = array();
        $args['limit'] = 100;
        $this->kr = RKMW_Classes_RemoteController::getKRHistory($args);

    }

    /**
     * Called when action is triggered
     *
     * @return void
     */
    public function action() {
        parent::action();

        switch (RKMW_Classes_Helpers_Tools::getValue('action')) {

            case 'rkmw_briefcase_addkeyword':
                if (!current_user_can('rkmw_manage_research')) {
                    $response['error'] = RKMW_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    RKMW_Classes_Helpers_Tools::setHeader('json');

                    if (RKMW_Classes_Helpers_Tools::isAjax()) {
                        echo wp_json_encode($response);
                        exit();
                    } else {
                        RKMW_Classes_Error::setError(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME));
                    }
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');
                $keyword = (string)RKMW_Classes_Helpers_Tools::getValue('keyword', '');
                $do_serp = (int)RKMW_Classes_Helpers_Tools::getValue('doserp', 0);
                $is_hidden = (int)RKMW_Classes_Helpers_Tools::getValue('hidden', 0);

                if ($keyword <> '') {
                    //set ignore on API
                    $args = array();
                    $args['keyword'] = $keyword;
                    $args['do_serp'] = $do_serp;
                    $args['is_hidden'] = $is_hidden;
                    RKMW_Classes_RemoteController::addBriefcaseKeyword($args);

                    if (RKMW_Classes_Helpers_Tools::isAjax()) {
                        if ($do_serp) {
                            echo wp_json_encode(array('message' => esc_html__("Keyword Saved. The rank check will be ready in a minute.", RKMW_PLUGIN_NAME)));
                        } else {
                            echo wp_json_encode(array('message' => esc_html__("Keyword Saved!", RKMW_PLUGIN_NAME)));
                        }
                        exit();
                    } else {
                        RKMW_Classes_Error::setMessage(esc_html__("Keyword Saved!", RKMW_PLUGIN_NAME));
                    }
                } else {
                    if (RKMW_Classes_Helpers_Tools::isAjax()) {
                        echo wp_json_encode(array('error' => esc_html__("Invalid params!", RKMW_PLUGIN_NAME)));
                        exit();
                    } else {
                        RKMW_Classes_Error::setError(esc_html__("Invalid params!", RKMW_PLUGIN_NAME));
                    }
                }
                break;
            case 'rkmw_briefcase_deletekeyword':
                if (!current_user_can('rkmw_manage_settings')) {
                    $response['error'] = RKMW_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    RKMW_Classes_Helpers_Tools::setHeader('json');
                    echo wp_json_encode($response);
                    exit();
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');
                $keyword = (string)RKMW_Classes_Helpers_Tools::getValue('keyword', '');

                if ($keyword <> '') {
                    //set ignore on API
                    $args = array();
                    $args['keyword'] = stripslashes($keyword);
                    RKMW_Classes_RemoteController::removeBriefcaseKeyword($args);

                    echo wp_json_encode(array('message' => esc_html__("Deleted!", RKMW_PLUGIN_NAME)));
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid params!", RKMW_PLUGIN_NAME)));
                }
                exit();
            case 'rkmw_briefcase_deletefound':
                if (!current_user_can('rkmw_manage_settings')) {
                    $response['error'] = RKMW_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    RKMW_Classes_Helpers_Tools::setHeader('json');
                    echo wp_json_encode($response);
                    exit();
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');
                $keyword = (string)RKMW_Classes_Helpers_Tools::getValue('keyword', '');

                if ($keyword <> '') {
                    //set ignore on API
                    $args = array();
                    $args['keyword'] = stripslashes($keyword);
                    RKMW_Classes_RemoteController::removeKrFound($args);

                    echo wp_json_encode(array('message' => esc_html__("Deleted!", RKMW_PLUGIN_NAME)));
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid params!", RKMW_PLUGIN_NAME)));
                }
                exit();
            /**********************************/
            case 'rkmw_briefcase_addlabel':
                if (!current_user_can('rkmw_manage_research')) {
                    $response['error'] = RKMW_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    RKMW_Classes_Helpers_Tools::setHeader('json');
                    echo wp_json_encode($response);
                    exit();
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');

                $name = (string)RKMW_Classes_Helpers_Tools::getValue('name', '');
                $color = (string)RKMW_Classes_Helpers_Tools::getValue('color', '#ffffff');

                if ($name <> '' && $color <> '') {
                    $args = array();

                    $args['name'] = $name;
                    $args['color'] = $color;
                    $json = RKMW_Classes_RemoteController::addBriefcaseLabel($args);

                    if (!is_wp_error($json)) {
                        echo wp_json_encode(array('saved' => esc_html__("Saved!", RKMW_PLUGIN_NAME)));
                    } else {
                        echo wp_json_encode(array('error' => $json->get_error_message()));
                    }

                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid Label or Color!", RKMW_PLUGIN_NAME)));
                }
                exit();
            case 'rkmw_briefcase_editlabel':
                if (!current_user_can('rkmw_manage_research')) {
                    $response['error'] = RKMW_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    RKMW_Classes_Helpers_Tools::setHeader('json');
                    echo wp_json_encode($response);
                    exit();
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');

                $id = (string)RKMW_Classes_Helpers_Tools::getValue('id', 0);
                $name = (string)RKMW_Classes_Helpers_Tools::getValue('name', 0);
                $color = (string)RKMW_Classes_Helpers_Tools::getValue('color', '#ffffff');

                if ((int)$id > 0 && $name <> '' && $color <> '') {
                    $args = array();

                    $args['id'] = $id;
                    $args['name'] = $name;
                    $args['color'] = $color;
                    RKMW_Classes_RemoteController::saveBriefcaseLabel($args);

                    echo wp_json_encode(array('saved' => esc_html__("Saved!", RKMW_PLUGIN_NAME)));
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid params!", RKMW_PLUGIN_NAME)));
                }
                exit();
            case 'rkmw_briefcase_deletelabel':
                if (!current_user_can('rkmw_manage_research')) {
                    $response['error'] = RKMW_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    RKMW_Classes_Helpers_Tools::setHeader('json');
                    echo wp_json_encode($response);
                    exit();
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');

                $id = (int)RKMW_Classes_Helpers_Tools::getValue('id', 0);

                if ($id > 0) {
                    //set ignore on API
                    $args = array();

                    $args['id'] = $id;
                    RKMW_Classes_RemoteController::removeBriefcaseLabel($args);

                    echo wp_json_encode(array('deleted' => esc_html__("Deleted!", RKMW_PLUGIN_NAME)));
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid params!", RKMW_PLUGIN_NAME)));
                }
                exit();
            case 'rkmw_briefcase_keywordlabel':
                if (!current_user_can('rkmw_manage_research')) {
                    $response['error'] = RKMW_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    RKMW_Classes_Helpers_Tools::setHeader('json');
                    echo wp_json_encode($response);
                    exit();
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');

                $keyword = (string)RKMW_Classes_Helpers_Tools::getValue('keyword', '');
                $labels = RKMW_Classes_Helpers_Tools::getValue('labels', array());

                if ($keyword <> '') {
                    $args = array();

                    $args['keyword'] = $keyword;
                    $args['labels'] = '';
                    if (is_array($labels) && !empty($labels)) {
                        $args['labels'] = join(',', $labels);
                        RKMW_Classes_RemoteController::saveBriefcaseKeywordLabel($args);
                    } else {
                        RKMW_Classes_RemoteController::saveBriefcaseKeywordLabel($args);

                    }
                    echo wp_json_encode(array('saved' => esc_html__("Saved!", RKMW_PLUGIN_NAME)));
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid Keyword!", RKMW_PLUGIN_NAME)));
                }
                exit();
            case 'rkmw_briefcase_backup':
                if (!current_user_can('rkmw_manage_settings')) {
                    RKMW_Classes_Error::setError(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    return;
                }

                $briefcase = RKMW_Classes_RemoteController::getBriefcase();

                $fp = fopen(RKMW_CACHE_DIR . 'file.csv', 'w');
                foreach ($briefcase->keywords as $row) {
                    fputcsv($fp, array($row->keyword), ',', '"');
                }
                fclose($fp);

                header('Content-type: text/csv');
                header("Content-Disposition: attachment; filename=rank-my-wp-briefcase-" . gmdate('Y-m-d') . ".csv");
                header("Pragma: no-cache");
                header("Expires: 0");
                readfile(RKMW_CACHE_DIR . 'file.csv');

                exit();
            case 'rkmw_briefcase_restore':
                if (!current_user_can('rkmw_manage_settings')) {
                    RKMW_Classes_Error::setError(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    return;
                }

                if (!empty($_FILES['rkmw_upload_file']) && $_FILES['rkmw_upload_file']['tmp_name'] <> '') {
                    $fp = fopen($_FILES['rkmw_upload_file']['tmp_name'], 'rb');

                    try {
                        $data = '';
                        $keywords = array();


                        while (($line = fgets($fp)) !== false) {
                            $data .= $line;
                        }
                        if (function_exists('base64_encode') && base64_decode($data) <> '') {
                            $data = @base64_decode($data);
                        }

                        if ($data = json_decode($data)) {
                            if (is_array($data) and !empty($data)) {
                                foreach ($data as $row) {
                                    if (isset($row->keyword)) {
                                        $keywords[] = $row->keyword;
                                    }
                                }
                            }
                        } else {
                            //Get the data from CSV
                            $fp = fopen($_FILES['rkmw_upload_file']['tmp_name'], 'rb');

                            while (($data = fgetcsv($fp, 1000, ";")) !== FALSE) {
                                if (!isset($data[0]) || $data[0] == '' || strlen($data[0]) > 255 || is_numeric($data[0])) {
                                    RKMW_Classes_Error::setError(esc_html__("Error! The backup is not valid.", RKMW_PLUGIN_NAME) . " <br /> ");
                                    break;
                                }

                                if (is_string($data[0]) && $data[0] <> '') {
                                    $keywords[] = strip_tags($data[0]);
                                }
                            }

                            if (empty($keywords)) {
                                $fp = fopen($_FILES['rkmw_upload_file']['tmp_name'], 'rb');

                                while (($data = fgetcsv($fp, 1000, ",")) !== FALSE) {
                                    if (!isset($data[0]) || $data[0] == '' || strlen($data[0]) > 255 || is_numeric($data[0])) {
                                        RKMW_Classes_Error::setError(esc_html__("Error! The backup is not valid.", RKMW_PLUGIN_NAME) . " <br /> ");
                                        break;
                                    }

                                    if (is_string($data[0]) && $data[0] <> '') {
                                        $keywords[] = strip_tags($data[0]);
                                    }
                                }
                            }


                        }

                        if (!empty($keywords)) {
                            foreach ($keywords as $keyword) {
                                if ($keyword <> '') {
                                    RKMW_Classes_RemoteController::addBriefcaseKeyword(array('keyword' => $keyword));
                                }
                            }

                            RKMW_Classes_Error::setError(esc_html__("Great! The backup is restored.", RKMW_PLUGIN_NAME) . " <br /> ", 'success');
                        } else {
                            RKMW_Classes_Error::setError(esc_html__("Error! The backup is not valid.", RKMW_PLUGIN_NAME) . " <br /> ");
                        }
                    } catch (Exception $e) {
                        RKMW_Classes_Error::setError(esc_html__("Error! The backup is not valid.", RKMW_PLUGIN_NAME) . " <br /> ");
                    }
                } else {
                    RKMW_Classes_Error::setError(esc_html__("Error! You have to enter a previously saved backup file.", RKMW_PLUGIN_NAME) . " <br /> ");
                }
                break;
            /************************************************* AJAX */
            case 'rkmw_ajax_briefcase_doserp':
                if (!current_user_can('rkmw_manage_settings')) {
                    $response['error'] = RKMW_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    RKMW_Classes_Helpers_Tools::setHeader('json');
                    echo wp_json_encode($response);
                    exit();
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');

                $json = array();
                $keyword = (string)RKMW_Classes_Helpers_Tools::getValue('keyword', '');

                if ($keyword <> '') {
                    $args = array();
                    $args['keyword'] = $keyword;
                    if (RKMW_Classes_RemoteController::addSerpKeyword($args) === false) {
                        $json['error'] = RKMW_Classes_Error::showNotices(esc_html__("Could not add the keyword to SERP Check. Please try again.", RKMW_PLUGIN_NAME), 'rkmw_error');
                    } else {
                        $json['message'] = RKMW_Classes_Error::showNotices(esc_html__("The keyword is added to SERP Check.", RKMW_PLUGIN_NAME), 'rkmw_success');
                    }
                } else {
                    $json['error'] = RKMW_Classes_Error::showNotices(esc_html__("Invalid parameters.", RKMW_PLUGIN_NAME), 'rkmw_error');
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');
                echo wp_json_encode($json);
                exit();
            case 'rkmw_ajax_research_others':
                RKMW_Classes_Helpers_Tools::setHeader('json');
                $keyword = RKMW_Classes_Helpers_Tools::getValue('keyword', false);
                $country = RKMW_Classes_Helpers_Tools::getValue('country', 'com');
                $lang = RKMW_Classes_Helpers_Tools::getValue('lang', 'en');

                if ($keyword) {
                    $args = array();
                    $args['keyword'] = $keyword;
                    $args['country'] = $country;
                    $args['lang'] = $lang;
                    $json = RKMW_Classes_RemoteController::getKROthers($args);

                    if (!is_wp_error($json)) {
                        if (isset($json->keywords)) {
                            echo wp_json_encode(array('keywords' => $json->keywords));
                        }
                    } else {
                        echo wp_json_encode(array('error' => $json->get_error_message()));
                    }
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid params!", RKMW_PLUGIN_NAME)));
                }

                exit();
            case 'rkmw_ajax_research_process':
                RKMW_Classes_Helpers_Tools::setHeader('json');
                $keywords = RKMW_Classes_Helpers_Tools::getValue('keywords', false);
                $lang = RKMW_Classes_Helpers_Tools::getValue('lang', 'en');
                $country = RKMW_Classes_Helpers_Tools::getValue('country', 'com');

                $count = (int)RKMW_Classes_Helpers_Tools::getValue('count', 10);
                $id = (int)RKMW_Classes_Helpers_Tools::getValue('id', 0);
                $this->post_id = RKMW_Classes_Helpers_Tools::getValue('post_id', false);

                if ($id > 0) {
                    $args = array();
                    $args['id'] = $id;
                    $this->kr = RKMW_Classes_RemoteController::getKRSuggestion($args);

                    if (!is_wp_error($this->kr)) {
                        if (!empty($this->kr)) {
                            //Get the briefcase keywords
                            if ($briefcase = RKMW_Classes_RemoteController::getBriefcase()) {
                                if (!is_wp_error($briefcase)) {
                                    if (isset($briefcase->keywords)) {
                                        $this->keywords = $briefcase->keywords;
                                    }
                                }
                            }

                            //research ready, return the results
                            echo wp_json_encode(array('done' => true, 'html' => $this->getView('Research/ResearchDetails')));
                        } else {
                            //still loading
                            echo wp_json_encode(array('done' => false));
                        }
                    } else {
                        //show the keywords in results to be able to add them to brifcase
                        $keywords = explode(',', $keywords);
                        if (!empty($keywords)) {
                            foreach ($keywords as $keyword) {
                                $this->kr[] = json_decode(wp_json_encode(array(
                                    'keyword' => $keyword,
                                )));
                            }
                        }
                        echo wp_json_encode(array('done' => true, 'html' => $this->getView('Research/ResearchDetails')));

                    }
                } elseif ($keywords) {
                    $args = array();
                    $args['q'] = $keywords;
                    $args['country'] = $country;
                    $args['lang'] = $lang;
                    $args['count'] = $count;
                    $process = RKMW_Classes_RemoteController::setKRSuggestion($args);

                    if (!is_wp_error($process)) {
                        if (isset($process->id)) {
                            //Get the briefcase keywords
                            echo wp_json_encode(array('done' => false, 'id' => $process->id));

                        }
                    } else {
                        if ($process->get_error_code() == 'limit_exceeded') {
                            echo wp_json_encode(array('done' => true, 'error' => esc_html__("Keyword Research limit exceeded", RKMW_PLUGIN_NAME)));
                        } else {
                            echo wp_json_encode(array('done' => true, 'error' => $process->get_error_message()));
                        }
                    }
                } else {
                    echo wp_json_encode(array('done' => true, 'error' => esc_html__("Invalid params!", RKMW_PLUGIN_NAME)));
                }
                exit();
            case 'rkmw_ajax_research_history':
                RKMW_Classes_Helpers_Tools::setHeader('json');
                $id = (int)RKMW_Classes_Helpers_Tools::getValue('id', 0);

                if ($id > 0) {
                    $args = $this->kr = array();
                    $args['id'] = $id;
                    $krHistory = RKMW_Classes_RemoteController::getKRHistory($args);

                    if (!empty($krHistory)) { //get only the first report
                        $this->kr = current($krHistory);
                    }

                    //Get the briefcase keywords
                    if ($briefcase = RKMW_Classes_RemoteController::getBriefcase()) {
                        if (!is_wp_error($briefcase)) {
                            if (isset($briefcase->keywords)) {
                                $this->keywords = $briefcase->keywords;
                            }
                        }
                    }

                    echo wp_json_encode(array('html' => $this->getView('Research/HistoryDetails')));
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid params!", RKMW_PLUGIN_NAME)));
                }
                exit();

            case 'rkmw_ajax_briefcase_bulk_delete':
                if (!current_user_can('rkmw_manage_settings')) {
                    $response['error'] = RKMW_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    RKMW_Classes_Helpers_Tools::setHeader('json');
                    echo wp_json_encode($response);
                    exit();
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');
                $keywords = RKMW_Classes_Helpers_Tools::getValue('inputs', array());

                if (!empty($keywords)) {
                    foreach ($keywords as $keyword) {
                        //set ignore on API
                        $args = array();
                        $args['keyword'] = stripslashes($keyword);
                        RKMW_Classes_RemoteController::removeBriefcaseKeyword($args);
                    }

                    echo wp_json_encode(array('message' => esc_html__("Deleted!", RKMW_PLUGIN_NAME)));
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid params!", RKMW_PLUGIN_NAME)));
                }
                exit();
            case 'rkmw_ajax_briefcase_bulk_label':
                if (!current_user_can('rkmw_manage_settings')) {
                    $response['error'] = RKMW_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    RKMW_Classes_Helpers_Tools::setHeader('json');
                    echo wp_json_encode($response);
                    exit();
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');

                $keywords = RKMW_Classes_Helpers_Tools::getValue('inputs', array());
                $labels = RKMW_Classes_Helpers_Tools::getValue('labels', array());

                if (!empty($keywords)) {
                    foreach ($keywords as $keyword) {
                        $args = array();

                        $args['keyword'] = $keyword;
                        $args['labels'] = '';
                        if (is_array($labels) && !empty($labels)) {
                            $args['labels'] = join(',', $labels);
                            RKMW_Classes_RemoteController::saveBriefcaseKeywordLabel($args);
                        } else {
                            RKMW_Classes_RemoteController::saveBriefcaseKeywordLabel($args);

                        }
                    }

                    echo wp_json_encode(array('message' => esc_html__("Saved!", RKMW_PLUGIN_NAME)));
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid Keyword!", RKMW_PLUGIN_NAME)));
                }

                exit();
            case 'rkmw_ajax_briefcase_bulk_doserp':
                if (!current_user_can('rkmw_manage_settings')) {
                    $response['error'] = RKMW_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    RKMW_Classes_Helpers_Tools::setHeader('json');
                    echo wp_json_encode($response);
                    exit();
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');

                $keywords = RKMW_Classes_Helpers_Tools::getValue('inputs', array());

                if (!empty($keywords)) {
                    foreach ($keywords as $keyword) {
                        $args = array();
                        $args['keyword'] = $keyword;
                        RKMW_Classes_RemoteController::addSerpKeyword($args);

                    }

                    echo wp_json_encode(array('message' => esc_html__("The keywords are added to SERP Check!", RKMW_PLUGIN_NAME)));
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid Keyword!", RKMW_PLUGIN_NAME)));
                }
                exit();

            case 'rkmw_ajax_labels_bulk_delete':
                if (!current_user_can('rkmw_manage_settings')) {
                    $response['error'] = RKMW_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", RKMW_PLUGIN_NAME), 'rkmw_error');
                    RKMW_Classes_Helpers_Tools::setHeader('json');
                    echo wp_json_encode($response);
                    exit();
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');
                $inputs = RKMW_Classes_Helpers_Tools::getValue('inputs', array());

                if (!empty($inputs)) {
                    foreach ($inputs as $id) {
                        if ($id > 0) {
                            $args = array();
                            $args['id'] = $id;
                            RKMW_Classes_RemoteController::removeBriefcaseLabel($args);
                        }
                    }

                    echo wp_json_encode(array('message' => esc_html__("Deleted!", RKMW_PLUGIN_NAME)));
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid params!", RKMW_PLUGIN_NAME)));
                }
                exit();
        }


    }
}
