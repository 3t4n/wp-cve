<?php

namespace Wdr\App\Controllers\Admin;

use Wdr\App\Controllers\ManageDiscount;
use Wdr\App\Models\DBTable;

class Messages extends ManageDiscount
{

    /**
     * Admin review conditions
     * @return void
     */
    function checkAdminReviewConditions() {
        $review_data = get_option('_awdr_review');
        $time_difference_for_review = $this->timeDifferenceForReview($review_data);
        $review_status = isset($review_data['status']) ? $review_data['status'] : '' ;
        $order_count = DBTable::getOrderCount();

        if(empty($review_data) && $order_count > 100) {
            add_action('admin_notices', array($this, 'showAdminReviewNotification'));
            $this->setReviewData($time_difference_for_review,$review_status);
        } elseif (!empty($review_data['time']) && !empty($review_status) && ($review_status == "add" || $review_status == "later")) {
            if ($order_count > 100) {
                if ($review_status == "add" && !empty($time_difference_for_review) && $time_difference_for_review > 24 * 60 * 60) {
                    add_action('admin_notices', array($this, 'showAdminReviewNotification'));
                    $this->setReviewData($time_difference_for_review,$review_status);
                } elseif ($review_status == "later" && !empty($time_difference_for_review) && $time_difference_for_review > 3 * 24 * 60 * 60) {
                    add_action('admin_notices', array($this, 'showAdminReviewNotification'));
                    $this->setReviewData($time_difference_for_review,$review_status);
                }
            }
        }
    }

    /**
     * Select admin review status
     * @param $time_difference_for_review
     * @param $review_status
     * @return void
     */
    function setReviewData($time_difference_for_review,$review_status) {

        if (isset($_GET['awdr_review'])) {
            $review_action = $_GET['awdr_review'];

            switch ($review_action) {
                case $review_action == "add":
                    if ($review_status != $review_action || $time_difference_for_review > 24 * 60 * 60) {
                        $this->saveReviewData("add");
                    }
                    wp_redirect("https://wordpress.org/support/plugin/woo-discount-rules/reviews/?filter=5");
                    exit();

                case $review_action == "later":
                    if($review_status != $review_action || $time_difference_for_review > 3 * 24 * 60 * 60) {
                        $this->saveReviewData("later");
                    }
                    wp_redirect(remove_query_arg('awdr_review'));
                    exit();

                case $review_action == "done":
                    $this->saveReviewData("done");
                    wp_redirect(remove_query_arg('awdr_review'));
                    exit();
            }
        }
    }

    /**
     * Save admin notice data to database
     * @param $status
     * @return void
     */
    function saveReviewData($status) {
        $data = [
            'status' => $status,
            'time' => current_time('timestamp')
        ];
        update_option("_awdr_review", $data);
    }

    /**
     * Calculate time difference for admin review
     * @return int|mixed|string|void
     */
    function timeDifferenceForReview($review_data) {
        if(!empty($review_data['time'])) {
            return current_time('timestamp') - $review_data['time'];
        } else {
            return 0;
        }
    }

    /**
     * Set admin review notification path
     * @return void
     */
    function showAdminReviewNotification() {
        $review_path = WDR_PLUGIN_PATH . 'App/Views/Admin/review-notice.php';
        self::$template_helper->setPath($review_path)->display();
    }

    /**
     * Display major release message
     * @param $plugin_data
     * @param $response
     * @return void
     */
    function majorReleaseMessage($plugin_data, $response) {
        if(isset($plugin_data) && is_array($plugin_data) && version_compare(substr($plugin_data['Version'], 0, 1),substr($plugin_data['new_version'], 0, 1), '<' )) {
            $message_path = WDR_PLUGIN_PATH . 'App/Views/Admin/release-message.php';
            self::$template_helper->setPath($message_path)->display();
        }
    }
}