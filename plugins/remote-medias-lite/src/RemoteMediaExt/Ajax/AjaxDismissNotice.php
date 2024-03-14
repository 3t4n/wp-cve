<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Ajax;

use WPRemoteMediaExt\WPCore\WPajaxCall;
use WPRemoteMediaExt\WPCore\WPoption;
use WPRemoteMediaExt\WPCore\WPuser;

class AjaxDismissNotice extends WPajaxCall
{
    protected $dismissfield = 'dismissed_rml_notices';

    public function __construct()
    {
        parent::__construct('ocs-rml-dismiss-notice', 'ocsrml-adminmanager', true, true);
        $this->jsvar = 'rmlAdminManagerParams';
    }

    public function callback($data)
    {
        if (!current_user_can('manage_options') ||
            empty($_POST['notice'])
        ) {
            wp_send_json_error();
        }
        unset($data);

        $user = WPuser::getCurrent();
        if (is_null($user)) {
            wp_send_json_error();
        }
        $noticeId = sanitize_text_field($_POST['notice']);

        $dismissedNotices = $user->get($this->dismissfield, '');
        if (empty($dismissedNotices)) {
            $user->set($this->dismissfield, $noticeId);
        } elseif (strpos($dismissedNotices, $noticeId) === false) {
            $dismissedNotices = explode(',', $dismissedNotices);
            $dismissedNotices[] = $noticeId;
            $dismissedNotices = implode(',', $dismissedNotices);
            $user->set($this->dismissfield, $dismissedNotices);
        }
        
        $success = $user->save();
        if ($success == true) {
            wp_send_json_success();
        }
        wp_send_json_error();
    }
}
