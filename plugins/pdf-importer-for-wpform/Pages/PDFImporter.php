<?php


namespace rnpdfimporter\Pages;


use rnpdfimporter\core\PageBase;
use rnpdfimporter\core\db\TemplateRepository;
use rnpdfimporter\core\Integration\IntegrationURL;
use rnpdfimporter\pr\Managers\FontManager;

class PDFImporter extends PageBase
{

    public function Render()
    {
        $this->Loader->AddScript('shared','js/dist/SharedCore_bundle.js',array('wp-element'));
        $dependencies=['@shared'];
        if($this->Loader->IsPR())
        {
            $this->Loader->AddScript('FormulaParser', 'js/dist/FormulaParserPro_bundle.js', array('@shared'));
            $this->Loader->AddScript('PDFImporterPro', 'js/dist/PDFImporterPRO_bundle.js', array('@importer'));

            $dependencies[]='@FormulaParser';
        }

        wp_enqueue_media();
        $this->Loader->AddScript('importer','js/dist/PDFImporter_bundle.js',$dependencies);
        $this->Loader->AddStyle('shared','js/dist/SharedCore_bundle.css');

        $this->Loader->AddStyle('importer','js/dist/PDFImporter_bundle.css');
        $this->Loader->ProcessorLoader->FormProcessor->SyncCurrentForms();

        $templateData=null;
        if(isset($_GET['templateId']))
        {
            $templateId=\intval($_GET['templateId']);
            if($templateId>0)
            {
                $repository=new TemplateRepository($this->Loader);
                $templateData=$repository->GetTemplateById($templateId);
            }
        }

        $fonts=[];

        if($this->Loader->IsPR())
        {
            $fontManager = new FontManager($this->Loader);
            foreach ($fontManager->GetAvailableFonts(false) as $currentFont)
            {
                $fonts[] = $currentFont->Name;
            }
        }

        $this->Loader->LocalizeScript('rnImporterVar','importer','importer',array(
            'FormList'=>$this->Loader->ProcessorLoader->FormProcessor->GetFormList(),
            'RootURL'=>$this->Loader->URL,
            'ajaxurl'=>IntegrationURL::AjaxURL(),
            'base'=>$this->Loader->BasePrefix,
            'DesignerURL'=>IntegrationURL::PageURL($this->Loader->Prefix),
            'TemplateData'=>$templateData,
            "IsPR"=>$this->Loader->IsPR(),
            'PurchaseURL'=>$this->Loader->GetPurchaseURL(),
            'Fonts'=>$fonts
        ));

        echo '<div id="app" style=" position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99999;
    background-color: white;">
        <style>                                  
            .lds-hourglass {
              display: inline-block;
              position: relative;
              width: 80px;
              height: 80px;
            }
            .lds-hourglass:after {
              content: " ";
              display: block;
              border-radius: 50%;
              width: 0;
              height: 0;
              margin: 8px;
              box-sizing: border-box;
              border: 32px solid black;
              border-color: black transparent black transparent;
              animation: lds-hourglass 1.2s infinite;
            }
            @keyframes lds-hourglass {
              0% {
                transform: rotate(0);
                animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
              }
              50% {
                transform: rotate(900deg);
                animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
              }
              100% {
                transform: rotate(1800deg);
              }
            }
 
        </style>
        <div style="width:100%;height:100%;justify-content: center;align-items: center;font-size: 20px;font-weight: bold;display: flex;flex-direction: column;">
            <div class="lds-hourglass"></div>
            <div style="font-size: 30px;margin-top: 10px;">'.__("Loading designer...").'</div>
        </div>';
    }
}