<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('RTCORE_Base')):

/**
 * RTCORE Base Class.
 *
 * @class RTCORE_Base
 * @version	1.0.0
 */
class RTCORE_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
	}

    public static function get_admin_url($page, $params, $path = 'admin.php')
    {
        $url = admin_url($path).'?page='.$page;
        foreach($params as $key=>$value) $url .= '&'.$key.'='.$value;

        return $url;
    }

    public function get_rtcore_path()
    {
        return RTCORE_ABSPATH;
    }

    public function rtcore_url()
    {
        return plugins_url().'/'.RTCORE_DIRNAME;
    }

    public function rtcore_asset_url($asset)
    {
        return $this->rtcore_url().'/assets/'.trim($asset, '/ ');
    }

    public function rtcore_asset_path($asset)
    {
        return $this->get_rtcore_path().'/assets/'.trim($asset, '/ ');
    }

    public function rtcore_tmp_path()
    {
        return $this->get_rtcore_path().'/assets/tmp';
    }

    public function response(Array $response)
    {
        echo json_encode($response);
        exit;
	}
}

endif;