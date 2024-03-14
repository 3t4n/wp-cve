<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Library;

use WPRemoteMediaExt\WPCore\View;
use WPRemoteMediaExt\WPCore\WPaction;
use WPRemoteMediaExt\WPCore\WPoption;
use WPRemoteMediaExt\WPCore\WPuser;

class MediaBannerDismissable extends WPaction
{
    protected $view;
    protected $dismissfield = 'dismissed_rml_notices';
    protected $dismissslug;

    public function __construct(View $view, $dismissslug = '')
    {
        parent::__construct('all_admin_notices');
        $this->view = $view;
        $this->dismissslug = $dismissslug;
    }

    public function action()
    {
        $user = WPuser::getCurrent();
        if (is_null($user)) {
            return;
        }

        $dismissed = $user->get($this->dismissfield);

        if (strpos($dismissed, $this->dismissslug) !== false) {
            return;
        }

        $this->view->appendData(
            array('noticeSlug' => $this->dismissslug)
        );
        $this->view->show();
    }
}
