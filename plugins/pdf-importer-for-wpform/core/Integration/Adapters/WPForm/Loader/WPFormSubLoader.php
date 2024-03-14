<?php


namespace rnpdfimporter\core\Integration\Adapters\WPForm\Loader;
use rnpdfimporter\core\Integration\Adapters\WPForm\Entry\Retriever\WPFormEntryRetriever;
use rnpdfimporter\core\Loader;
use rnpdfimporter\pr\core\PRLoader;

class WPFormSubLoader extends Loader
{

    public $ItemId;
    public function __construct($prefix,$basePrefix,$dbVersion,$fileVersion,$mainFile,$config=null)
    {
        $this->ItemId=12;
        $this->ProcessorLoader=new WPFormProcessorLoader($this);
        $this->ProcessorLoader->Initialize();
        parent::__construct($prefix,$basePrefix,$dbVersion,$fileVersion,$mainFile,$config);
        \add_filter('wpforms_frontend_confirmation_message',array($this,'AddPDFLink'),10,2);

        $this->AddMenu('PDF Importer for WPFORM',$this->Prefix,'administrator','','rnpdfimporter\Pages\PDFList');
        $this->AddMenu('Our WPForms Plugins',$prefix.'_additional_plugins','administrator','','rnpdfimporter\Pages\AdditionalPlugins');

        if($this->IsPR())
        {
            $this->PRLoader=new PRLoader($this);
        }
    }

    public function GetRootURL()
    {
        return 'https://formwiz.rednao.com/';
    }

    public function AddPDFLink($message,$formData)
    {
        global $RNWPImporterCreatedEntry;
        if(!isset($RNWPImporterCreatedEntry['CreatedDocuments']))
            return $message;

        if(\strpos($message,'[wpformpdflink]')===false)
            return $message;

        $links=array();
        $UsedTemplates=[];
        foreach($RNWPImporterCreatedEntry['CreatedDocuments'] as $createdDocument)
        {
            if(in_array($createdDocument['TemplateId'],$UsedTemplates))
                continue;
            $data=array(
              'entryid'=>$RNWPImporterCreatedEntry['EntryId'],
              'templateid'=>$createdDocument['TemplateId'],
              'nonce'=>\wp_create_nonce($this->Prefix.'_'.$RNWPImporterCreatedEntry['EntryId'].'_'.$createdDocument['TemplateId'])
            );
            $url=admin_url('admin-ajax.php').'?data='.\json_encode($data).'&action='.$this->Prefix.'_public_create_pdf';
            $links[]='<a target="_blank" href="'.esc_attr($url).'">'.\esc_html($createdDocument['Name']).'.pdf</a>';
            $UsedTemplates[]=$createdDocument['TemplateId'];
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


    public function AddBuilderScripts()
    {
        $this->AddScript('wpformbuilder','js/dist/WPFormBuilder_bundle.js',array('jquery', 'wp-element','@builder'));
    }

    public function GetPurchaseURL()
    {
        return 'https://formwiz.rednao.com/pdf-importer/';
    }


    public function AddAdvertisementParams($params)
    {
        if(\get_option($this->Prefix.'never_show_add',false)==true)
        {
            $params['Text']='';

        }else
        {
            $params['Text'] = 'Want to create a pdf instead of importing one?';
            $params['LinkText'] = 'Try PDF Builder for WPForms';
            $params['LinkURL'] = 'https://wordpress.org/plugins/pdf-builder-for-wpforms/';
            $params['Icon'] = $this->URL . 'images/adIcons/wpform.jpg';
        }
        return $params;
    }

    public function GetProductItemId()
    {
        return 16;
    }
}