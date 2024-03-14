<?php
/**
 * @package talentlms-wordpress
 */

namespace TalentlmsIntegration;

use TalentLMS_Siteinfo;
use TalentlmsIntegration\Services\PluginService;
use TalentlmsIntegration\Validations\TLMSPositiveInteger;

class Ajax implements PluginService
{

    public function register(): void
    {
        add_action(
            'wp_ajax_tlms_resynch',
            array($this, 'tlms_resyncCourse')
        );
    }

    public function tlms_resyncCourse(): void
    {
        global $wpdb;
        try {
            $courseId = (new TLMSPositiveInteger($_POST['course_id']))->getValue();
            $limit = TalentLMS_Siteinfo::getRateLimit();

            if (!empty($courseId) && (empty($limit['remaining']) || $limit['remaining'] > 4)) {
                // we are gonna make at least 3 api calls, so we have to be prepared.
                $product_ID = $wpdb->get_var("SELECT product_id FROM ".TLMS_PRODUCTS_TABLE." WHERE course_id = "
                                             .$courseId);

                if ($product_ID) {
                    $wpdb->query("DELETE FROM ".TLMS_PRODUCTS_TABLE." WHERE course_id = "
                                 .$courseId);
                    $wpdb->query("DELETE FROM ".TLMS_COURSES_TABLE." WHERE id = "
                                 .$courseId);
                    $wpdb->query("DELETE FROM ".WP_POSTS_TABLE." WHERE ID = "
                                 .(int)$product_ID);

                    Utils::tlms_getCourses(true);
                    Utils::tlms_getCategories(true);
                    Utils::tlms_addProduct($courseId, Utils::tlms_selectCourses());
                }
                echo json_encode(array('api_limitation' => 'none'), JSON_THROW_ON_ERROR);
            } else {
                echo json_encode(
                    array(
                        'api_limitation' => 'Api usage resets at '.$limit['formatted_reset']
                    ),
                    JSON_THROW_ON_ERROR
                );
            }
        } catch (\Exception $exception) {
            if ($exception instanceof \InvalidArgumentException) {
                echo json_encode(
                    array(
                        'error' => 'Invalid course id'
                    ),
                    JSON_THROW_ON_ERROR
                );
            }
        }

        wp_die();
    }
}
