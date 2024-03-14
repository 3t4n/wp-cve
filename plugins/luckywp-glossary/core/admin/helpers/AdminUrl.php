<?php

namespace luckywp\glossary\core\admin\helpers;

use luckywp\glossary\core\Core;

class AdminUrl
{

    /**
     * @param string $pageId
     * @param string|null $action
     * @param array $params
     * @return string
     */
    public static function to($pageId, $action = null, $params = [])
    {
        $params['page'] = Core::$plugin->prefix . $pageId;
        if ($action !== null) {
            $params['action'] = $action;
        }
        return admin_url('admin.php?' . http_build_query($params));
    }

    /**
     * @param string $postType
     * @param string|null $pageId
     * @param string|null $action
     * @param array $params
     * @return string
     */
    public static function byPostTypeTo($postType, $pageId = null, $action = null, $params = [])
    {
        $params['post_type'] = $postType;
        if ($pageId !== null) {
            $params['page'] = Core::$plugin->prefix . $pageId;
        }
        if ($action !== null) {
            $params['action'] = $action;
        }
        return admin_url('edit.php?' . http_build_query($params));
    }

    /**
     * @param string $pageId
     * @param string $action
     * @return bool
     */
    public static function isPage($pageId, $action = '')
    {
        return Core::$plugin->request->get('page') == Core::$plugin->prefix . $pageId
            && (!$action || Core::$plugin->request->get('action') == $action);
    }
}
