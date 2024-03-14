<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class RKMW_Controllers_Ranking extends RKMW_Classes_FrontController {

    public $info;
    public $ranks;
    public $serps;
    public $suggested;

    /** @var object Checkin process with Cloud */
    public $checkin;

    function init() {
        //Get the current page and tab and check if the option is active
        $page = str_replace(strtolower(RKMW_NAMESPACE) . '_', '', RKMW_Classes_Helpers_Tools::getValue('page'));
        $tab = RKMW_Classes_Helpers_Tools::getValue('tab', 'rankings');
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

        if (method_exists($this, $tab)) {
            call_user_func(array($this, $tab));
        }

        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap-reboot');
        if (is_rtl()) {
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('popper');
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap.rtl');
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('rtl');
        } else {
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap');
        }
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('switchery');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('fontawesome');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('datatables');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('global');

        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('assistant');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('navbar');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('rankings');

        echo $this->getView(ucfirst($page) . '/' . ucfirst($tab));

    }

    /**
     * Call the rankings
     */
    public function rankings() {
        add_action('rkmw_form_notices', array($this, 'getNotificationBar'));

        $days_back = (int)RKMW_Classes_Helpers_Tools::getValue('days_back', 30);

        $args = array();
        $args['days_back'] = $days_back;
        $args['keyword'] = (string)RKMW_Classes_Helpers_Tools::getValue('skeyword', '');
        $args['has_change'] = (string)RKMW_Classes_Helpers_Tools::getValue('schanges', '');
        $args['has_ranks'] = (string)RKMW_Classes_Helpers_Tools::getValue('ranked', '');


        if ($this->info = RKMW_Classes_RemoteController::getRanksStats($args)) {
            if (is_wp_error($this->info)) {
                $this->info = array();
            }
        }

        if ($this->ranks = RKMW_Classes_RemoteController::getRanks($args)) {
            if (is_wp_error($this->ranks)) {
                RKMW_Classes_Error::setError(esc_html__("Could not load the Rankings.", RKMW_PLUGIN_NAME));
                $this->ranks = array();
            }
        }
    }

    public function gscsync() {
        if (isset($this->checkin->connection_gsc) && $this->checkin->connection_gsc) {
            $args = array();
            $args['max_results'] = '100';
            $args['max_position'] = '30';

            if ($this->suggested = RKMW_Classes_RemoteController::syncGSC($args)) {
                if (is_wp_error($this->suggested)) {
                    RKMW_Classes_Error::setError(esc_html__("Could not load data.", RKMW_PLUGIN_NAME));
                    $this->suggested = array();
                }
            }
        }

        //Get the briefcase keywords
        if ($briefcase = RKMW_Classes_RemoteController::getBriefcase()) {
            if (!is_wp_error($briefcase)) {
                if (isset($briefcase->keywords)) {
                    $this->keywords = $briefcase->keywords;
                }
            }
        }
    }

    /**
     * Called when action is triggered
     *
     * @return void
     */
    public function action() {
        parent::action();

        if (!current_user_can('rkmw_manage_rankings')) {
            return;
        }

        switch (RKMW_Classes_Helpers_Tools::getValue('action')) {

            case 'rkmw_ranking_settings':
                //Save the settings
                if (!empty($_POST)) {
                    RKMW_Classes_ObjController::getClass('RKMW_Models_Settings')->saveValues($_POST);
                }

                //Save the settings on API too
                $args = array();
                $args['google_country'] = RKMW_Classes_Helpers_Tools::getValue('google_country','com');
                $args['google_language'] = 'en';
                RKMW_Classes_RemoteController::saveOptions($args);
                ///////////////////////////////

                //show the saved message
                RKMW_Classes_Error::setMessage(esc_html__("Saved", RKMW_PLUGIN_NAME));

                break;
            case 'rkmw_settings_gsc_revoke':
                //remove connection with Google Search Console
                $response = RKMW_Classes_RemoteController::revokeGscConnection();
                if (!is_wp_error($response)) {
                    RKMW_Classes_Error::setError(esc_html__("Google Search Console account is disconnected.", RKMW_PLUGIN_NAME) . " <br /> ", 'success');
                } else {
                    RKMW_Classes_Error::setError(esc_html__("Error! Could not disconnect the account.", RKMW_PLUGIN_NAME) . " <br /> ");
                }
                break;
            case 'rkmw_serp_refresh_post':
                $keyword = RKMW_Classes_Helpers_Tools::getValue('keyword', false);
                if ($keyword) {
                    $args = array();
                    $args['keyword'] = $keyword;
                    if (RKMW_Classes_RemoteController::checkPostRank($args) === false) {
                        RKMW_Classes_Error::setError(sprintf(esc_html__("Could not refresh the rank. Please check your SERP credits %shere%s", RKMW_PLUGIN_NAME), '<a href="' . RKMW_Classes_RemoteController::getCloudLink('account') . '">', '</a>'));
                    } else {
                        RKMW_Classes_Error::setMessage(sprintf(esc_html__("%s is queued and the rank will be checked soon.", RKMW_PLUGIN_NAME), '<strong>' . $keyword . '</strong>'));
                    }
                }

                break;
            case 'rkmw_serp_delete_keyword':
                $keyword = RKMW_Classes_Helpers_Tools::getValue('keyword', false);

                if ($keyword) {
                    $response = RKMW_Classes_RemoteController::deleteSerpKeyword(array('keyword' => $keyword));
                    if (!is_wp_error($response)) {
                        RKMW_Classes_Error::setError(esc_html__("The keyword is deleted.", RKMW_PLUGIN_NAME) . " <br /> ", 'success');
                    } else {
                        RKMW_Classes_Error::setError(esc_html__("Could not delete the keyword!", RKMW_PLUGIN_NAME) . " <br /> ");
                    }
                } else {
                    RKMW_Classes_Error::setError(esc_html__("Invalid params!", RKMW_PLUGIN_NAME) . " <br /> ");
                }
                break;
            case 'rkmw_ajax_rank_bulk_delete':
                RKMW_Classes_Helpers_Tools::setHeader('json');
                $inputs = RKMW_Classes_Helpers_Tools::getValue('inputs', array());

                if (!empty($inputs)) {
                    foreach ($inputs as $keyword) {
                        if ($keyword <> '') {
                            $args = array();
                            $args['keyword'] = $keyword;
                            RKMW_Classes_RemoteController::deleteSerpKeyword($args);
                        }
                    }

                    echo wp_json_encode(array('message' => esc_html__("Deleted!", RKMW_PLUGIN_NAME)));
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid params!", RKMW_PLUGIN_NAME)));
                }
                exit();
            case 'rkmw_ajax_rank_bulk_refresh':
                RKMW_Classes_Helpers_Tools::setHeader('json');
                $inputs = RKMW_Classes_Helpers_Tools::getValue('inputs', array());

                if (!empty($inputs)) {
                    foreach ($inputs as $keyword) {
                        if ($keyword <> '') {
                            $args = array();
                            $args['keyword'] = $keyword;
                            RKMW_Classes_RemoteController::checkPostRank($args);
                        }

                        echo wp_json_encode(array('message' => esc_html__("Sent!", RKMW_PLUGIN_NAME)));
                    }
                } else {
                    echo wp_json_encode(array('error' => esc_html__("Invalid params!", RKMW_PLUGIN_NAME)));
                }
                exit();

        }
    }

    /**
     * Show the graphs in Ranking
     */
    public function getScripts() {
        return '<script type="text/javascript">
               function drawChart(id, values, reverse) {
                    var data = google.visualization.arrayToDataTable(values);

                    var options = {

                        curveType: "function",
                        title: "",
                        chartArea:{width:"100%",height:"100%"},
                        enableInteractivity: "true",
                        tooltip: {trigger: "auto"},
                        pointSize: "2",
                        colors: ["#55b2ca"],
                        hAxis: {
                          baselineColor: "transparent",
                           gridlineColor: "transparent",
                           textPosition: "none"
                        } ,
                        vAxis:{
                          direction: ((reverse) ? -1 : 1),
                          baselineColor: "transparent",
                          gridlineColor: "transparent",
                          textPosition: "none"
                        }
                    };

                    var chart = new google.visualization.LineChart(document.getElementById(id));
                    chart.draw(data, options);
                    return chart;
                }
          </script>';
    }

}
