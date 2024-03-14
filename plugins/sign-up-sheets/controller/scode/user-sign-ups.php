<?php
/**
 * [user_sign_ups] Shortcode Controller
 *
 * @since 2.2.11
 */

namespace FDSUS\controller\scode;

use FDSUS\Controller\Base;
use FDSUS\Controller\Pro\Scode\TaskModel;
use FDSUS\Id;
use FDSUS\Lib\Dls\Notice;
use FDSUS\Model\Settings;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\SignupCollection;

class UserSignUps extends Base
{
    public function __construct()
    {
        parent::__construct();
        add_shortcode('user_sign_ups', array(&$this, 'shortcode'));
    }

    /**
     * Enqueue plugin css and js files
     */
    function addCssAndJsToSignUp()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_style(Id::PREFIX . '-style');
        wp_enqueue_script('dlssus-js');
    }

    /**
     * Main shortcode
     *
     * @param array $atts attributes from shortcode call
     *
     * @return string shortcode output
     */
    public function shortcode($atts)
    {
        /**
         * Filter shortcode attributes
         *
         * @param array $atts
         *
         * @return array
         * @since 2.2.11
         */
        $atts = apply_filters('fdsus_scode_user_sign_ups_attributes', $atts);

        /**
         * @var string $status
         */
        extract(
            shortcode_atts(
                array(
                    'status' => 'active' // 'active', 'expired', or 'all'
                ), $atts
            )
        );

        $this->addCssAndJsToSignUp();

        $user = wp_get_current_user();
        if (!$user->exists()) {
            Notice::add('info', esc_html__('You must be logged in to view your sign-ups.', 'fdsus'), true);
            return apply_filters(Id::PREFIX . '_notices', null);
        }

        /** @var SheetModel[]|false $signups */
        $signups = false;

        /**
         * Filter for signup collection
         *
         * @param SignupModel $signups
         * @param array       $atts shortcode attributes
         *
         * @return SignupModel[]
         * @since 2.2.11
         */
        $signups = apply_filters('fdsus_scode_user_sign_ups_collection', $signups, $atts);

        // Display all active if not already set
        if ($signups === false) {
            $signupCollection = new SignupCollection();
            $signups = $signupCollection->getByUser($user->ID);
        }

        $rows = array();
        foreach ($signups as $signup) {
            $task = $signup->getTask();
            if (!$task->isValid() || $task->post_status !== 'publish'
                || ($status === 'active' && $task->isExpired())
            ) {
                continue;
            }

            $sheet = $task->getSheet();
            if (!$sheet->isValid() || $sheet->post_status !== 'publish'
                || ($status === 'active' && $sheet->isExpired())
            ) {
                continue;
            }

            if ($status === 'expired' && !$task->isExpired() && !$sheet->isExpired()) {
                continue;
            }

            $row = array(
                'date_added'        => $signup->post_date,
                'task_date'         => $task->getDate(),
                'firstname'         => $signup->dlssus_firstname,
                'lastname'          => $signup->dlssus_lastname,
                'sheet'             => $sheet,
                'task'              => $task,
                'signup'            => $signup,
                'task_additional'   => array(),
                'signup_additional' => array(),
            );

            if ($sheet->showEmail()) {
                $row['signup_additional']['email'] = array(
                    'label' => __('Email', 'fdsus'),
                    'value' => $signup->dlssus_email,
                );
            }

            if ($sheet->showPhone()) {
                $row['signup_additional']['phone'] = array(
                    'label' => __('Phone', 'fdsus'),
                    'value' => $signup->dlssus_phone,
                );
            }

            if ($sheet->showAddress()) {
                $row['signup_additional']['phone'] = array(
                    'label' => __('Address', 'fdsus'),
                    'value' => $signup->dlssus_address . ', ' . $signup->dlssus_city . ', ' . $signup->dlssus_state
                        . ' ' . $signup->dlssus_zip,
                );
            }

            /**
             * Filter [user_sign_ups] row
             *
             * @param array       $row
             * @param SheetModel  $sheet
             * @param TaskModel   $task
             * @param SignupModel $signup
             *
             * @return array
             * @since 2.2.11
             */
            $row = apply_filters('fdsus_scode_user_sign_ups_row', $row, $sheet, $task, $signup);

            $rows[] = $row;
        }

        ob_start();

        $args = array(
            'user_signup_rows'  => $rows,
            'task_title_label'  => Settings::$text['task_title_label']['value'],
        );
        $this->locateTemplate('fdsus/user_sign_ups.php', true, false, $args);

        return ob_get_clean();
    }
}
