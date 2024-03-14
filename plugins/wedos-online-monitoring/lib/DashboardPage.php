<?php
namespace WEDOS\Mon\WP;

use PHPF\WP\Stats\GoogleCharts;

/**
 * Base class for pages
 *
 * @author    Petr Stastny <petr@stastny.eu>
 * @copyright WEDOS Internet, a.s.
 * @license   GPLv3
 */
class DashboardPage extends \PHPF\WP\Page\Page
{
    const PAGE_SLUG = 'won-dashboard';


    /**
     * Register admin page
     *
     * @return void
     */
    protected static function registerAdmin()
    {
        wp_enqueue_style('won.css', plugin_dir_url( __DIR__ ).'css/won.css');

        GoogleCharts::init();

        add_menu_page(
            /* translators: dashboard page title */
            __('WEDOS OnLine monitoring', 'wedos-online-monitoring'),
            /* translators: dashboard menu caption */
            __('WEDOS OnLine', 'wedos-online-monitoring'),
            'manage_options',
            self::PAGE_SLUG,
            function () { static::renderStatic(); },
            'dashicons-chart-area'
        );
    }


    /**
     * Register submit actions
     *
     * @return void
     */
    protected static function registerActions()
    {
        self::registerPostAction('wonPair');
    }


    /**
     * Render page content
     *
     * @return void
     */
    protected function render()
    {
        $paired = false;

        echo '<div class="wrap">';
        echo '<h1>'.esc_html(get_admin_page_title()).'</h1>';
        echo '</div>';

        $checkId = get_option('won_pair_checkId');

        if ($this->readPairToken()) {
            $this->renderPair();

        } elseif (!$checkId) {
            echo '<div class="notice notice-warning"><p>'.__('Your WordPress site is not linked to WEDOS OnLine monitoring.', 'wedos-online-monitoring').'</p></div>';

            echo '<p>'.__('This plugin allows you to link your <strong><a href="https://www.wedos.online/" target="_blank">WEDOS OnLine monitoring</a></strong> account to your WordPress.', 'wedos-online-monitoring').'</p>';
            echo '<p>'.__('This makes it easy to verify the ownership of the domain on which you run this WordPress, without which monitoring cannot be activated.', 'wedos-online-monitoring').'</p>';
            echo '<p>'.__('In the future, you will be able to use this plugin to track your site monitoring statistics directly in WordPress.', 'wedos-online-monitoring').'</p>';
            echo '<p><b>'.__('How to begin?', 'wedos-online-monitoring').'</b> '.__('Sign up for or sign in to your <a href="https://cp.wedos.online/" target="_blank">WEDOS OnLine account</a>, create a new HTTP check to start a watchdog for your WordPress site. You will then be able to link the check to your WordPress in the check detail.', 'wedos-online-monitoring').'</p>';

            echo '<p>';
            /* translators: button on dashboard */
            echo '<a href="https://cp.wedos.online/register" target="_blank" class="button">'.__('Register to WEDOS OnLine', 'wedos-online-monitoring').'</a>';
            /* translators: button on dashboard */
            echo ' <a href="https://cp.wedos.online/login" target="_blank" class="button">'.__('Log in to WEDOS OnLine', 'wedos-online-monitoring').'</a>';
            echo '</p>';

        } else {

            $connectOk = Pair\Pair::connectionCheck();

            if ($connectOk) {
                $paired = true;

                echo '<div class="notice notice-info"><p>'.__('Your WordPress site is linked to WEDOS OnLine monitoring.', 'wedos-online-monitoring').'</p></div>';
                echo '<p>'.sprintf(__('This plugin currently only displays basic statistics. More statistics and features are available in <a href="%s" target="_blank">WEDOS OnLine control panel</a>.', 'wedos-online-monitoring'), 'https://cp.wedos.online/check?id='.$checkId.'&amp;t=http').'</p>';

            } else {
                echo '<div class="notice notice-warning"><p>';
                echo '<b>'.__('Your WordPress site is not linked to WEDOS OnLine monitoring anymore.', 'wedos-online-monitoring').'</b>';
                echo '<br><br>';
                echo __('It seems that the link between your check and your WordPress site is no more valid (API response: authentication failed).', 'wedos-online-monitoring');
                echo '<br><br>';
                echo __('Try to link it again - use appropriate button in check detail in WEDOS OnLine <b><a href="https://cp.wedos.online/" target="_blank">control panel</a></b>.', 'wedos-online-monitoring');
                echo '</p></div>';
            }

            /* translators: button on dashboard */
            echo '<p><a href="https://cp.wedos.online/check?id='.$checkId.'&amp;t=http" target="_blank" class="button">'.__('Go to WEDOS OnLine', 'wedos-online-monitoring').'</a></p>';
        }

        if ($paired) {
            $this->renderCharts();
        }
    }


    private function readPairToken()
    {
       if (!empty($_REQUEST['pair']) && is_string($_REQUEST['pair']) && strlen($_REQUEST['pair']) == 30) {
           return $_REQUEST['pair'];
       }

       return false;
    }


    private function renderPair()
    {
        echo '<form method="post" action="'.esc_html(admin_url('admin-post.php')).'">';
        echo '<input type="hidden" name="pair" value="'.esc_html($_GET['pair']).'">';
        echo '<input type="hidden" name="action" value="wonPair">';

        echo '<div id="universal-message-container">';
        /* translators: page section caption */
        echo '<h2>'.__('Link WordPress with monitoring', 'wedos-online-monitoring').'</h2>';

        echo '<p>'.__('Do you want to pair your WordPress with your WEDOS OnLine monitoring account?', 'wedos-online-monitoring').'</p>';

        echo '</div>';

        wp_nonce_field('wonPair');
        /* translators: submit button */
        submit_button(__('Confirm link', 'wedos-online-monitoring'));

        echo '</form>';
    }


    public function execWonPair()
    {
        $pairToken = $this->readPairToken();
        if (!$pairToken) {
            return;
        }

        Pair\Pair::performPair($pairToken);
    }


    private function renderCharts()
    {
        $chartsData = $this->getChartsData();
        if (!$chartsData) {
            return;
        }

        echo '<table>';
        echo '<tr>';

        echo '<td style="vertical-align: top; width: 400px;">';
        echo '<h3>'.__('Average response time (ms) - last 36 hours', 'wedos-online-monitoring').'</h3>';

        if (isset($chartsData->avgTime->data) && is_array($chartsData->avgTime->data)) {
            echo '<div id="avgTimeChart" style="height: 200px; padding: 10px; background-color: white;"></div>';

            $options = [
                'legend' => ['position' => 'none'],
                'chartArea' => ['left' => 40, 'top' => 10, 'width' => '100%', 'height' => '100%'],
            ];

            GoogleCharts::addChart('LineChart', $chartsData->avgTime->data, $options, 'avgTimeChart');

        } else {
            echo '<p>'.__('No data available', 'wedos-online-monitoring').'</p>';
        }

        echo '</td>';

        echo '<td style="vertical-align: top; width: 400px;">';
        echo '<h3>'.__('Uptime - last 36 hours', 'wedos-online-monitoring').'</h3>';

        if (isset($chartsData->uptime->data) && is_array($chartsData->uptime->data)) {
            echo '<div id="uptimeChart" style="height: 200px; padding: 10px; background-color: white;"></div>';

            $options = [
                'isStacked' => 'percent',
                'colors' => ['green', 'orange', 'red', 'grey'],
                'hAxis' => ['textPosition' => 'none'],
                'bar' => ['groupWidth' => '100%'],
                'legend' => ['position' => 'none'],
                'chartArea' => ['left' => 0, 'top' => 0, 'width' => '100%', 'height' => '100%'],
            ];

            GoogleCharts::addChart('ColumnChart', $chartsData->uptime->data, $options, 'uptimeChart');

        } else {
            echo '<p>'.__('No data available', 'wedos-online-monitoring').'</p>';
        }

        echo '</td>';

        echo '</tr>';
        echo '</table>';

        if (isset($chartsData->overviewTable) && $chartsData->overviewTable) {
            echo '<h3>'.__('Overview statistics', 'wedos-online-monitoring').'</h3>';
            echo '<div class="won-table" style="background-color: white; padding: 10px; max-width: 700px;">';
            echo $chartsData->overviewTable;
            echo '</div>';

        } else {
            echo '<p>'.__('No data available', 'wedos-online-monitoring').'</p>';
        }

        GoogleCharts::drawCharts();
    }


    private function getChartsData()
    {
        $checkId = get_option('won_pair_checkId');
        $cacheKey = 'basicChartsData:'.$checkId;

        $cachedData = wp_cache_get($cacheKey, 'won');
        if ($cachedData) {
            return $cachedData;
        }

        $inputData = new \stdClass();
        $inputData->locale = get_locale();

        $apiClient = new \WEDOS\Mon\WP\ApiClient\MonApiClient('basicCharts');
        $result = $apiClient->send($inputData);
        $outputData = $apiClient->getResponseData();

        //var_dump($result);
        //var_dump($outputData);
        //exit;

        if ($apiClient->getHttpCode() != 200) {
            echo '<div class="notice notice-error"><p>';
            echo '<b>'.__('Failed to read statistics from WEDOS OnLine. Please try again later.', 'wedos-online-monitoring').'</b>';

            if (is_object($outputData)) {
                if (!empty($outputData->error->code)) {
                    echo '<br><br>';
                    echo __('Server response:', 'wedos-online-monitoring').' ';
                    echo '<span style="color: red;">'.$outputData->error->error.' ('.$outputData->error->code.')</span>';
                }
            }
            echo '</p></div>';

            return false;
        }

        wp_cache_set($cacheKey, $outputData, 'won', 300);

        return $outputData;
    }
}
