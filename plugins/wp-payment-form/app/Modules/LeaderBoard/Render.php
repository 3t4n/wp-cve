<?php

namespace WPPayForm\App\Modules\LeaderBoard;

use WPPayForm\App\App;
use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Services\GeneralSettings;
use WPPayForm\App\Models\Submission;
use WPPayForm\App\Http\Controllers\FormController;
use WPPayForm\App\Models\DemoForms;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajax Handler Class
 * @since 1.0.0
 */
class Render
{
    public function render($template_id = '', $form_id = null, $per_page = 10, $show_total = true, $show_name = true, $show_avatar = true, $orderby = null)
    {

        $leaderboard_settings = get_option("wppayform_donation_leaderboard_settings", array(
            'enable_donation_for' => 'all',
            'template_id' => 3,
            'enable_donation_for_specific' => [],
            'orderby' => 'grand_total'
        ));
        if ($leaderboard_settings == false || $leaderboard_settings == null || Arr::get($leaderboard_settings, 'enable_donation_for') == 'disable') {
            return;
        }
        wp_enqueue_style('wppayform_leaderboard', WPPAYFORM_URL . 'assets/css/leaderboard.css', array(), WPPAYFORM_VERSION);
        wp_enqueue_script(
            'wppayform_leaderboard_js',
            WPPAYFORM_URL . 'assets/js/leaderboard.js',
            array('jquery'),
            WPPAYFORM_VERSION,
            true
        );
        
        wp_localize_script('wppayform_leaderboard_js', 'wp_payform_leader_board', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_payform_nonce'),
            'no_donor_image' => WPPAYFORM_URL . 'assets/images/empty-cart.svg',
        ));
        $template_src = 'leaderBoard.' . $template_id;

        $donationItems = $this->getDonarList($form_id, '', $orderby, 'true', $per_page);
  
        ob_start();
        App::make('view')->render($template_src, [
            'donars'            => $donationItems['donars'],
            'topThreeDonars'    => $donationItems['topThreeDonars'],
            'show_total'        => $show_total,
            'show_name'         => $show_name,
            'show_avatar'       => $show_avatar,
            'form_id'           => $form_id,
            'per_page'          => $per_page,
            'orderby'           => $orderby,
            'has_more_data'     => $donationItems['has_more_data'],
            'total'    => $donationItems['total'],
            'template_id' => $template_id
        ]);
        $view = ob_get_clean();
        return $view;
    }

    public function leaderBoardRender()
    {
        $form_id = $_POST['form_id'];
        $searchText = $_REQUEST['searchText'];
        $sortKey = $_REQUEST['sortKey'];
        $sortType = $_REQUEST['sortType'];
        $per_page = $_REQUEST['perPage'];


        $donationItems = $this->getDonarList($form_id, $searchText, $sortKey, $sortType, $per_page);

        wp_send_json_success($donationItems, 200);
    }

    private function getDonarList($form_id, $searchText = null, $sortKey = null, $sortType = '', $per_page = 20)
    {
        $donationItems = (new Submission())->getDonationItem($form_id, $searchText, $sortKey, $sortType, 0, $per_page);

        return $donationItems;
    }
}
