<?php
namespace WPRemoteMediaExt\RemoteMediaExt;

use WPRemoteMediaExt\WPCore\WPposttype;
use WPRemoteMediaExt\WPCore\admin\WPadminNotice;

class AccountPostType extends WPposttype
{
    const POSTTYPE = 'rmlaccounts';

    public function __construct()
    {
        $args = array(
            'labels' => array(
                'name' => __('Remote Libraries', 'remote-medias-lite'),
                'singular_label' => __('Remote Library', 'remote-medias-lite'),
                'add_new' => _x('Add New', 'Remote Library', 'remote-medias-lite'),
                'add_new_item' => _x('Add New Remote Library', 'Remote Library', 'remote-medias-lite'),
                'edit_item' => _x('Edit Remote Library', 'Remote Library', 'remote-medias-lite'),
                'new_item' => _x('New Remote Library', 'Remote Library', 'remote-medias-lite'),
                'view_item' => _x('View Remote Library', 'Remote Library', 'remote-medias-lite'),
                'search_items' => _x('Search Remote Libraries', 'Remote Library', 'remote-medias-lite'),
                'not_found' => _x('No Remote Library found', 'Remote Library', 'remote-medias-lite'),
                'not_found_in_trash' => _x('No Remote Library found in Trash', 'Remote Library', 'remote-medias-lite'),
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'upload.php',
            'show_in_admin_bar' => false,
            'capability_type' => 'page',
            'hierarchical' => true,
            'supports' => array('title')
        );

        parent::__construct(self::POSTTYPE, $args);

        if (is_admin()) {
            $this->initAdmin();
        }
    }

    public function initAdmin()
    {
        add_filter('post_updated_messages', array($this, 'initPostUpdateMsg'));
        add_action('current_screen', array($this, 'initPostTypeScreen'), 0);
    }

    public function initAdminNotices()
    {
        global $post;

        if (empty($post) || empty($post->ID)) {
            return;
        }

        $account = Accounts\RemoteAccountFactory::create($post->ID);
        if (is_null($account)) {
            return;
        }
        if ($account->isValid()) {
            return;
        }

        $status = $account->getStatus();
        switch ($status) {
            case Accounts\AbstractRemoteAccount::STATUS_AUTHNEEDED:
                $notice = new WPadminNotice(sprintf(__("Remote library authentication needed. %sAuthenticate now%", 'remote-medias-lite'), '<a class="action_query_test" href="#">', '</a>'), "message error rmlnotice");
                $notice->register();
                break;
            case Accounts\AbstractRemoteAccount::STATUS_INVALID:
                $notice = new WPadminNotice(__("Could not connect to remote Library. Please verify your remote library settings. Only valid libraries are added to the media manager.", 'remote-medias-lite'), "message error");
                $notice->register();
                break;
        }
    }

    public function initPostTypeScreen()
    {
        $screen = get_current_screen();
        if (empty($screen) || $screen->id != self::POSTTYPE) {
            return;
        }
        add_action('admin_notices', array($this, 'initAdminNotices'), 0);
    }

    public function initPostUpdateMsg($messages)
    {
        $post = get_post();

        $messages[$this->getSlug()] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => __('Remote library settings updated.', 'remote-medias-lite'),
            4  => __('Remote library settings updated.', 'remote-medias-lite'),
            /* translators: %s: date and time of the revision */
            5  => isset($_GET['revision']) ? sprintf(__('Remote library settings restored to revision from %s', 'remote-medias-lite'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
            6  => __('Remote library settings published.', 'remote-medias-lite'),
            7  => __('Remote library settings saved.', 'remote-medias-lite'),
            8  => __('Remote library settings submitted.', 'remote-medias-lite'),
            9  => sprintf(
                __('Remote library settings scheduled for: <strong>%1$s</strong>.', 'remote-medias-lite'),
                // translators: Publish box date format, see http://php.net/date
                date_i18n(__('M j, Y @ G:i', 'remote-medias-lite'), strtotime($post->post_date))
            ),
            10 => __('Remote library settings draft updated.', 'remote-medias-lite')
        );

        return $messages;
    }
}
