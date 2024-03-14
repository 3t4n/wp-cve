<?php
/**
 * AJAX Class
 */

namespace FDSUS\Controller;

use FDSUS\Id;
use FDSUS\Model\Data;
use FDSUS\Controller\Migrate as Migrate;

class Ajax
{

    public $data;

    public function __construct()
    {
        $this->data = new Data();

        if (!FDSUS_DISABLE_MIGRATE_2_0_to_2_1) {
            add_action('wp_ajax_' . Id::PREFIX . '_migrate_status', array(&$this, 'getMigrateStatus'));
        }
    }

    /**
     * Get migrate status
     */
    public function getMigrateStatus()
    {
        $out = array(
            'output' => null,
            'status' => null,
            'percent_complete' => 0
        );

        // Set progress bar
        global $_wp_admin_css_colors;
        $admin_color = get_user_option('admin_color');
        $colors = $_wp_admin_css_colors[$admin_color]->colors;
        $progressBar = '
            <div style="border: 1px solid ' . $colors[3] . '; margin-top: .6em;">
                <div style="background: ' . $colors[3] . '; width: %1$s; text-align: center;">
                    <span style="line-height: 2em;">%1$s</span>
                </div>
            </div>
        ';

        $migrate = new Migrate();
        $status = $migrate->getStatus();
        $out['status'] = $status['state'];
        $restartButton = sprintf(' <a href="%s" class="button">%s</a>',
            add_query_arg('migrate', 'rerun-2.1', $this->data->getSettingsUrl()),
            esc_html__('Restart Upgrade', 'fdsus')
        );
        if ($status['state'] == 'complete') {
            // Complete
            $out['output'] = 'Sign-up sheets database upgrade is complete. &#x2713;' . sprintf($progressBar, '100%');
        } elseif (in_array($status['state'], array('running', 'rerun'))) {
            // Set percent
            $completed = $status['sheets_completed'] + $status['tasks_completed'] + $status['signups_completed'];
            $count = $this->data->getV20ItemCount();
            $total = $count->sheets + $count->tasks + $count->signups + (isset($count->categories) ? $count->categories : 0);
            $out['percent_complete'] = (int) ($completed / $total * 100);

            // Check last updated
            $now = current_time('timestamp', 1);
            $last = $status['last_updated'];
            // If changed within the last 2 minutes
            if (empty($last) || $now - $last < 60 * 2) {
                // Running
                $out['output'] = 'Sign-up sheets database upgrade is processing.' . sprintf($progressBar, $out['percent_complete'] . '%');
            } else {
                // Frozen
                $out['output'] = 'Sign-up sheets database upgrade may have stalled. (' . $out['percent_complete'] . '% complete) ' . $restartButton;
            }
        } else {
            // Weird status
            $out['output'] = 'Sign-up sheets database upgrade may not have completed properly. ' . $restartButton;
        }

        // WP 3.5+
        if (function_exists('wp_send_json')) wp_send_json($out);

        // WP < 3.5
        $this->_wp_send_json($out);
    }

    /**
     * Send a JSON response back to an Ajax request.
     * (variation from WP core - added to support earlier version)
     *
     * @param mixed $response Variable (usually an array or object) to encode as JSON, then print and die.
     */
    private function _wp_send_json($response)
    {
        @header('Content-Type: application/json;');
        echo json_encode($response);
        die;
    }

}

if (isset($_GET['manual']) && $_GET['manual'] == 'true') {
    $fdsus_ajax = new Ajax();
}
