<?php


namespace BDroppy\Pages\EndPoints;

use BDroppy\Init\Core;

class CategoryMappingEndPoints
{
    protected $core;
    protected $config;
    protected $remote;
    protected $system;
    protected $wc;

    public function __construct(Core $core)
    {
        $this->core = $core;
        $this->remote = $core->getRemote();
        $this->config = $core->getConfig();
        $this->system = $core->getSystem();
        $this->wc = $core->getWc();
        $this->core->getLoader()->addAction( 'rest_api_init', $this, 'registerRoutes' );

    }


    public function registerRoutes()
    {
        $version = '1';
        $namespace = 'bdroppy' . '/v' . $version;

        $d = register_rest_route( $namespace, '/category-mapping', [
            [
                'methods'               => \WP_REST_Server::CREATABLE,
                'callback'              => [$this, 'ajaxGetCategoryMapping'],
                'permission_callback'   => [$this, 'permissionsCheck'],
            ]
        ] );
    }

    public function ajaxGetCategoryMapping(\WP_REST_Request $request)
    {
        $data = $request->get_param('data');
        switch ($request->get_param('type'))
        {
            case 'getBdSubCategory' :
                $subcategories = $this->remote->main->subcategories($data['category'],$this->system->language->getActives(0));
                return new \WP_REST_Response($subcategories, 200 );

            case 'getSubCategory' :
                $subcategories = get_terms( 'product_cat', ['hide_empty' => false,'parent'     => $data['category']]);
                return new \WP_REST_Response($subcategories, 200 );

            case 'addCategory' :
                $key = str_replace(' ','_',implode('-',$data['bdroppyIds']));
                $categoriesMapping = get_option('bdroppy-category-mapping');
                $categoriesMapping[$key] = $data;
                update_option('bdroppy-category-mapping',$categoriesMapping);
                return new \WP_REST_Response(1, 200 );
            case 'getCategoryList' :
                $categories = get_option('bdroppy-category-mapping');
                return new \WP_REST_Response($categories, 200 );
            case 'deleteItemByKey' :
                $categoriesMapping = get_option('bdroppy-category-mapping');
                unset($categoriesMapping[$data['key']]) ;
                update_option('bdroppy-category-mapping',$categoriesMapping);
                return new \WP_REST_Response($categoriesMapping, 200 );
        }
        exit();
    }


    public function permissionsCheck( $request ) {
        return current_user_can( 'manage_options' );
    }


}