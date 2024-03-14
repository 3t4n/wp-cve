<?php defined('ABSPATH') || exit;

class Nouvello_WeManage_Utm_CF7_Session
{

    const DEFAULT_SESSION = array(
        'is_form_enabled' => '',
        'form_id' => '',
        'entry_id' => '',
        'user_id' => '',
        'date_created_timestamp' => '',
        'user_synced_session' => ''
    );

    private static $instance;
    private $session = self::DEFAULT_SESSION;

    protected function __construct()
    {
    }

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setup($form, $entry)
    {

        $is_form_enabled = Nouvello_WeManage_Utm_CF7_Form::is_enabled($form);

        if ($is_form_enabled == false) :

            //exit
            $this->clear();
            $this->session['is_form_enabled'] = false;

            return false;

        endif;

        $form_id = $form->id();
        $user_id = $entry->get_meta('current_user_id');

        try {

            $date_created_timestamp = $entry->get_meta('timestamp');

            if (empty($date_created_timestamp) || $date_created_timestamp < 0) {
                $date_created_timestamp = time();
            }
        } catch (\Exception $e) {
            $date_created_timestamp = time();
        }

        //prepare session
        $instance_session = Nouvello_WeManage_Utm_Session::instance();
        $instance_session->setup($user_id);

        $user_synced_session = $instance_session->get('user_synced_session');
        $user_synced_session = Nouvello_WeManage_Utm_Service::prepare_conversion_lag($user_synced_session, $date_created_timestamp);
        $user_synced_session = Nouvello_WeManage_Utm_Service::prepare_conversion_type($user_synced_session, Nouvello_WeManage_Utm_CF7_Form::get_conversion_type($form));

        $this->session = Nouvello_WeManage_Utm_Functions::merge_default(array(
            'is_form_enabled' => $is_form_enabled,
            'form_id' => $form_id,
            'user_id' => $user_id,
            'date_created_timestamp' => $date_created_timestamp,
            'user_synced_session' => $user_synced_session
        ), self::DEFAULT_SESSION);

        return true;
    }

    public function get($key)
    {

        if (isset($this->session[$key])) :
            return $this->session[$key];
        else :
            return null;
        endif;
    }

    public function clear()
    {

        $this->session = self::DEFAULT_SESSION;
    }
}
