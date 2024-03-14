<?php


namespace rnpdfimporter\Pages;


use rnpdfimporter\core\PageBase;
use rnpdfimporter\core\db\TemplateRepository;
use rnpdfimporter\core\Integration\IntegrationURL;

class PDFList extends PageBase
{

    public function Render()
    {
        if(isset($_GET['templateId']))
        {
            $importer=new PDFImporter($this->Loader);
            $importer->Render();
            return;
        }
        $this->Loader->AddScript('shared','js/dist/SharedCore_bundle.js',array('wp-element'));
        $this->Loader->AddScript('pdflist','js/dist/PDFList_bundle.js',array('@shared'));


        $this->Loader->AddStyle('importer','js/dist/PDFList_bundle.css');
        $this->Loader->AddStyle('shared','js/dist/SharedCore_bundle.css');

        $templateRepository=new TemplateRepository($this->Loader);

        $this->Loader->LocalizeScript('rnListVar','pdflist','importer',$this->Loader->AddAdvertisementParams(array(
            'TemplateList'=>$templateRepository->GetTemplateList(),
            'ajaxurl'=>IntegrationURL::AjaxURL(),
            'TemplateURL'=>IntegrationURL::PageURL($this->Loader->Prefix),
            "IsPR"=>$this->Loader->IsPR(),
            'PurchaseURL'=>$this->Loader->GetPurchaseURL()
        )));

        echo '<div id="app"></div>';
    }
}