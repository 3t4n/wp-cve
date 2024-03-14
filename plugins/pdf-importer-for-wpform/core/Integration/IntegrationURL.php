<?php


namespace rnpdfimporter\core\Integration;


use rnpdfimporter\core\Loader;

class IntegrationURL
{
    public static function PageURL($page)
    {
        return \admin_url('admin.php').'?page='.$page;
    }

    public static function AjaxURL()
    {
        return admin_url( 'admin-ajax.php' );
    }

    /**
     * @param $loader Loader
     */
    public static function PublicEntryURL($loader,$entryId,$reference)
    {
        $option=new OptionsManager();
        $pageId=$option->GetOption('entry_detail','');
        $url='';
        if($pageId==''||!($url=\get_permalink($pageId)))
        {
            $post = array(
                'post_content'   => '[rnentry]',
                'post_name'      => __('Entry Details'),
                'post_title'     => __('Details'),
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'ping_status'    => 'closed',
                'comment_status' => 'closed'
            );
            $pageId = wp_insert_post( $post );
            $option->SaveOptions('entry_detail',$pageId);
            $url=\get_permalink($pageId);
        }

        if(\strpos($url,'?')===false)
            $url.='?';
        else
            $url.='&';

        return $url.'ref='.$entryId.'__'.$reference;

    }


    public static function PreviewURL(){
        $previewPage=get_page_by_title("Easy Calculation Forms Preview","OBJECT","rednao_forms_preview");
        $page_id=0;
        if($previewPage==null)
        {
            $post = array(
                'post_content'   => '[rnformpreview]	',
                'post_name'      => 'Easy Calculation Forms Preview',
                'post_title'     => 'Easy Calculation Forms Preview',
                'post_status'    => 'draft',
                'post_type'      => 'rednao_forms_preview',
                'ping_status'    => 'closed',
                'comment_status' => 'closed'
            );
            $page_id = wp_insert_post( $post );
        }else
            $page_id=$previewPage->ID;

        $url=get_permalink($page_id);
        return $url;
    }


}