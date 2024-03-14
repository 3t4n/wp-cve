<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlpe\App\Helpers;
defined('ABSPATH') or die;

class Point
{
    function getAppsPageUrl()
    {
        return admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG))) . '#/apps';
    }
}