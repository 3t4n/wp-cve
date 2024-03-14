<?php


namespace rednaoformpdfbuilder\Integration\Adapters\WPForm\Loader;
use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\FormProcessor\WPFormFormProcessor;
use rednaoformpdfbuilder\pr\PRLoader;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\Retriever\WPFormEntryRetriever;

class WPFormSubLoader extends Loader
{

    public function __construct($rootFilePath,$config)
    {
        $this->ItemId=12;
        $prefix='rednaopdfwpform';
        $formProcessorLoader=new WPFormProcessorLoader($this);
        $formProcessorLoader->Initialize();
        parent::__construct($prefix,$formProcessorLoader,$rootFilePath,$config);
        $this->AddMenu('WPForm PDF Builder',$prefix.'_pdf_builder','pdfbuilder_manage_templates','','Pages/BuilderList.php');
        $this->AddMenu('Our WPForms Plugins',$prefix.'_additional_plugins','administrator','','Pages/AdditionalPlugins.php');
        \add_filter('wpforms_frontend_confirmation_message',array($this,'AddPDFLink'),10,2);
        if($this->IsPR())
        {
            $this->PRLoader=new PRLoader($this);
        }else{
            $this->AddMenu('Entries',$prefix.'_pdf_builder_entries','manage_options','','Pages/EntriesFree.php');
        }
    }

    public function GetForm($formId){
        global $wpdb;
        $results=$wpdb->get_results($wpdb->prepare("select id ID, post_title,post_content from ".$wpdb->posts." where ID=%d",$formId),'ARRAY_A');
        if(count($results)==0)
            return null;


        /** @var WPFormFormProcessor $formProcessor */
        $formProcessor=$this->ProcessorLoader->FormProcessor;
        return $formProcessor->SerializeForm($results[0]);

    }

    public function GetRootURL()
    {
        return 'https://formwiz.rednao.com/';
    }

    public function GetEntry($entryId)
    {
        if(isset(wpforms()->entry))
            $entry= wpforms()->entry->get( $entryId);
        else{
            $entry=null;
        }
        if($entry==null)
            return null;

        $entry->fields=\json_decode($entry->fields,true);
        $entry->date_created=$entry->date;
        return $entry;
    }


    public function AddPDFLink($message,$formData)
    {
        global $RNWPCreatedEntry;
        if(!isset($RNWPCreatedEntry['CreatedDocuments']))
            return $message;

        if(\strpos($message,'[wpformpdflink]')===false)
            return $message;

        $links=array();
        foreach($RNWPCreatedEntry['CreatedDocuments'] as $createdDocument)
        {
            $data=array(
              'entryid'=>$RNWPCreatedEntry['EntryId'],
              'templateid'=>$createdDocument['TemplateId'],
              'nonce'=>\wp_create_nonce($this->Prefix.'_'.$RNWPCreatedEntry['EntryId'].'_'.$createdDocument['TemplateId'])
            );
            $url=admin_url('admin-ajax.php').'?data='.\json_encode($data).'&action='.$this->Prefix.'_view_pdf';
            $links[]='<a target="_blank" href="'.esc_attr($url).'">'.\esc_html($createdDocument['Name']).'.pdf</a>';
        }

        $message=\str_replace('[wpformpdflink]',\implode($links),$message);

        return $message;


    }

    /**
     * @return WPFormEntryRetriever
     */
    public function CreateEntryRetriever()
    {
        return new WPFormEntryRetriever($this);
    }



    public function AddAdvertisementParams($params)
    {
        if(\get_option($this->Prefix.'never_show_add',false)==true)
        {
            $params['Text']='';

        }else
        {
            $params['Text'] = 'Already have a pdf and just want to fill it with the form information?';
            $params['LinkText'] = 'Try PDF Importer for WPForms';
            $params['LinkURL'] = 'https://wordpress.org/plugins/pdf-importer-for-wpform/';
            $params['Icon'] = $this->URL . 'images/adIcons/wpform.png';
            $params['PageBuilderIcon'] = $this->URL . 'images/adIcons/pagebuilder.png';
            $params['PageBuilder']=true;
        }
        return $params;
    }

    public function AddBuilderScripts()
    {
        $this->AddScript('wpformbuilder','js/dist/WPFormBuilder_bundle.js',array('jquery', 'wp-element','@builder'));
    }

    public function GetPurchaseURL()
    {
        return 'https://formwiz.rednao.com/pdf-builder/';
    }
}


