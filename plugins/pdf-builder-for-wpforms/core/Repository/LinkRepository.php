<?php


namespace rednaoformpdfbuilder\core\Repository;


use rednaoformpdfbuilder\core\Loader;

class LinkRepository
{
    /** @var Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function GetOrCreateDownloadURL($templateId, $entryId)
    {
        global $wpdb;
        $token=$wpdb->get_var($wpdb->prepare('select token from '.$this->Loader->LINKS_TABLE.' where entry_id=%s and template_id=%s',$entryId,$templateId));
        if($token==null)
        {
            $token=uniqid($templateId.'_'.$entryId);
            $attemps=0;
            while($wpdb->get_var($wpdb->prepare('select token from ' . $this->Loader->LINKS_TABLE . ' where token=%s', $token))!=null&&$attemps<20){
                $token=uniqid($templateId.'_'.$entryId);
                $attemps++;
            }

            if($attemps==20)
                return null;


            $wpdb->insert($this->Loader->LINKS_TABLE,array(
               'token'=>$token,
               'entry_id'=>$entryId,
               'template_id'=>$templateId,
               'expiration_date'=>date('c', strtotime('+1 year'))
            ));
        }

        return admin_url('admin-ajax.php').'?action='.$this->Loader->Prefix.'_open_pdf_link&token='.$token;



    }

    public function GetDataByToken(string $token)
    {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare('select entry_id,template_id from '.$this->Loader->LINKS_TABLE.' where token=%s',$token));
    }
}