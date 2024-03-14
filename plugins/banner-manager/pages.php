<?php

// db manager
require_once(BM_PLUGIN_DIR .'/data.php');

// templates
require_once(BM_PLUGIN_DIR .'/templates.php');

class pages
{
    /**
     * Init
     */
    public function __construct()
    {
        // Menua sortu
        add_action('admin_menu', array($this, 'set_menu'));
        add_action('admin_menu', array($this, 'set_submenu'));
    }

    /**
     * Menurako aukera gehitu
     */
    public function set_menu()
    {
        add_menu_page(__('Banner manager', 'banner-manager'),    __('Banners', 'banner-manager'), 'level_5', 'bm-index', array($this, 'page_load'));
    }

    /**
     * Menurako aukera gehitu
     */
    public function set_submenu()
    {
        add_submenu_page('bm-index', __('Categories', 'banner-manager'), __('Categories', 'banner-manager'), 'level_5', 'bm-categories', array($this, 'page_load'));
    }

    /**
     * Adminitratzaile guneko html orria
     */
    public function page_load()
    {
        // page configuration
        $page = isset($_REQUEST['page'])? $_REQUEST['page'] : null;
        $status = isset($_REQUEST['status'])? $_REQUEST['status'] : null;

        // get params
        $id = isset($_REQUEST['id'])? $_REQUEST['id'] : null;
        $category = isset($_REQUEST['category'])? $_REQUEST['category'] : null;
        $title = isset($_REQUEST['title'])? $_REQUEST['title'] : null;
        $src = null;
        $src_old = isset($_REQUEST['src_old'])? $_REQUEST['src_old'] : null;;

        $width = isset($_REQUEST['width'])? $_REQUEST['width'] : null;
        $height = isset($_REQUEST['height'])? $_REQUEST['height'] : null;

        $link = isset($_REQUEST['link'])? $_REQUEST['link'] : null;
        $blank = isset($_REQUEST['blank'])? 1 : 0;
        $active = isset($_REQUEST['active'])? 1 : 0;
        $groupkey = isset($_REQUEST['groupkey'])? $_REQUEST['groupkey'] : null;

        // filtroak
        $filter_category = isset($_REQUEST['filter_category'])? $_REQUEST['filter_category'] : null;
        $filter_active = isset($_REQUEST['filter_active'])? $_REQUEST['filter_active'] : 1;

        // return params
        $params = array();
        $params['filter_category'] =  $filter_category;
        $params['filter_active'] =  $filter_active;
        $params['url_query'] = "filter_category=$filter_category&amp;filter_active=$filter_active";

        switch($page)
        {
            case 'bm-index':
                switch($status)
                {
                    case 'new':

                        // upload banner
                        if(!empty($_FILES["src"]["name"]))
                        {

                            $upload = wp_upload_bits($_FILES["src"]["name"], null, file_get_contents($_FILES["src"]["tmp_name"]));

                            if(!$upload['error'])
                            {
                                $src = $upload['url'];
                            } else {
                                throw new Exception($upload['error']);
                            }
                        }
                        else
                        {
                            $src = $src_old;
                        }

                        if(empty($id) && !empty($src))
                        {
                            data::new_banner( $category, $title, $src, $link, $blank, $active, $groupkey );
                            $params['message'] =  __('Banner saved.', 'banner-manager');
                        }
                        else
                        {
                            data::update_banner( $id, $category, $title, $src, $link, $blank, $active, $groupkey );
                            $params['message'] =  __('Banner edited.', 'banner-manager');
                        }
                        break;
                    case 'delete':
                        data::del_banner($id);
                        $params['message'] =  __('Banner deleted.', 'banner-manager');
                        break;
                    case 'edit':
                        $params['banner'] = data::get_banner($id);
                        break;

                }

                $params['banners'] = data::get_banners( $filter_category, $filter_active );
                $params['categories'] = data::get_categories();

                $template = 'banners';

                break;

            case 'bm-categories':

                switch($status)
                {
                    case 'new':
                        if(!empty($category) && empty($id))
                        {
                            data::new_category($category, $width, $height);
                            $params['message'] =  __('Category saved.', 'banner-manager');
                        }
                        else
                        {
                            data::update_category($id, $category, $width, $height);
                            $params['message'] =  __('Category edited.', 'banner-manager');
                        }
                        break;
                    case 'delete':
                        data::del_category($id);
                        $params['message'] =  __('Banner deleted.', 'banner-manager');
                        break;
                    case 'edit':
                        $params['category'] = data::get_category($id);
                        break;
                }

                $params['categories'] = data::get_categories();
                $template = 'categories';
                break;
        }

        templates::load($template, $params);
    }
}

// execute class
new pages();
