<?php
/**
 * Mail Customization Controller
 */

namespace FDSUS\Controller;

use FDSUS\Id as Id;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\Task as TaskModel;

class MailCustomization extends Base
{
    public function __construct()
    {
        parent::__construct();

        add_filter('fdsus_mail_subject', array(&$this, 'modifyMailSubject'), 10, 5);
    }

    /**
     * Filter for mail subject
     *
     * @param string      $subject
     * @param SheetModel  $sheet
     * @param TaskModel   $task
     * @param SignupModel $signup
     * @param string      $type
     *
     * @return string
     */
    public function modifyMailSubject($subject, $sheet, $task, $signup, $type)
    {
        if ($type === 'signup') {
            $subject = esc_html(get_option('dls_sus_email_subject'));
        }

        return $subject;
    }
}