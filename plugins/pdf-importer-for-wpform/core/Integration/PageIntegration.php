<?php


namespace rnpdfimporter\core\Integration;


use Exception;
use rnpdfimporter\core\Loader;
use WP_Post;

class PageIntegration
{
    /** @var Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }


    public function GetPageList(){
        $list=get_pages();

        $listToReturn=array();
        /** @var WP_Post $page */
        foreach($list as $page)
        {
            if($page->post_status!='publish'||$page->post_title=='')
                continue;

            $listToReturn[]=array(
              'Id'=>$page->ID,
              'Label'=>$page->post_title
            );

        }
        \usort($listToReturn,function ($item1,$item2){
            return strcasecmp($item1['Label'],$item2['Label']);
        });

        return $listToReturn;
    }

    public function GetPageURLById($PageId)
    {
        $url=\get_permalink($PageId);
        if($url==false)
            throw new Exception('Page not found');

        return $url;
    }

}