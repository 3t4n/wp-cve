<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Library;

use WPRemoteMediaExt\WPCore\View;
use WPRemoteMediaExt\WPCore\WPaction;

class MediaBanner extends MediaBannerDismissable
{
    protected $view;
    protected $dismissoption = '';

    public function action()
    {
        global $pagenow;

        if (isset($pagenow) &&
            $pagenow == 'edit.php' &&
            !empty($_GET['post_type']) &&
            $_GET['post_type'] == 'rmlaccounts'
        ) {
            $this->view->show();
        }
    }
}
