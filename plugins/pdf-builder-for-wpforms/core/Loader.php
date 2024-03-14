<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 2/25/2019
 * Time: 8:57 AM
 */

namespace rednaoformpdfbuilder\core;

use rednaoformpdfbuilder\ajax\DesignerAjax;
use rednaoformpdfbuilder\ajax\PDFBuilderUtils;
use rednaoformpdfbuilder\ajax\TemplateListAjax;
use rednaoformpdfbuilder\core\Managers\LogManager;
use rednaoformpdfbuilder\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rednaoformpdfbuilder\Integration\Processors\Loader\ProcessorLoaderBase;


abstract class Loader extends PluginBase
{
    /** @var PRLoader */
    public $PRLoader=null;
    public $RECORDS_TABLE;
    public $LINKS_TABLE;
    public $TEMPLATES_TABLE;
    public $CUSTOM_FIELD;
    public $ItemId;

    /** @var ProcessorLoaderBase */
    public $ProcessorLoader;

    public $FormConfigTable;

    public abstract function AddBuilderScripts();


    public function __construct($prefix,$processLoader,$rootFilePath,$config)
    {
        parent::__construct($prefix,31,38,$rootFilePath,$config);
        global $wpdb;
        LogManager::Initialize($this);
        $this->ProcessorLoader=$processLoader;
        $this->LINKS_TABLE=$wpdb->prefix.$prefix.'_links';
        $this->FormConfigTable= $wpdb->prefix.$prefix.'_form_config';
        $this->RECORDS_TABLE=$wpdb->prefix.$prefix.'_'.'records';
        $this->TEMPLATES_TABLE=$wpdb->prefix.$prefix.'_'.'templates';
        $this->CUSTOM_FIELD=$wpdb->prefix.$prefix.'_'.'custom_field';

        add_filter('upgrader_process_complete',array($this,'OnUpgrade'),10,2);

        add_filter('pdfbuilder_get_loader',function (){return $this;});
        new DesignerAjax($this,$prefix);
        new TemplateListAjax($this,$prefix);
        new PDFBuilderUtils($this,$prefix);
    }

     public function GetRootURL(){
        return 'https://pdfbuilder.rednao.com/';
     }

    public function GetForm($formId){
        return null;
    }

    public function GetEntry($entryId)
    {
        return [];
    }

    /**
     * @return EntryRetrieverBase
     */
    public abstract function CreateEntryRetriever();
    public abstract function GetPurchaseURL();

    public function OnUpgrade($object,$options){
        $this->OnPluginIsActivated();
    }

    public function OnPluginIsActivated()
    {
        $role=get_role('administrator');
        $role->add_cap('pdfbuilder_manage_templates');

    }


    public function CheckIfPDFAdmin(){
        if(!current_user_can('manage_options'))
        {
            die('Forbidden');
        }
    }

    public function  IsPR(){
        return file_exists($this->DIR.'pr');
    }

    public function OnCreateTable()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql = "CREATE TABLE " . $this->FormConfigTable . " (
                id INT AUTO_INCREMENT,
                original_id BIGINT,
                name VARCHAR(200) NOT NULL,
                fields MEDIUMTEXT,
                notifications MEDIUMTEXT,
                PRIMARY KEY  (id)
                ) COLLATE utf8_general_ci;";
        \dbDelta($sql);

        $sql = "CREATE TABLE " . $this->LINKS_TABLE . " (
                id INT AUTO_INCREMENT,
                token VARCHAR(25) NOT NULL,
                entry_id INT,
                template_id INT,
                expiration_date DATETIME,
                PRIMARY KEY  (id)
                ) COLLATE utf8_general_ci;";
        \dbDelta($sql);

        $sql = "CREATE TABLE " . $this->RECORDS_TABLE . " (
                id INT AUTO_INCREMENT,   
                original_id bigint,              
                form_id INT,                
                date DATETIME,
                user_id INT,
                entry MEDIUMTEXT, 
                seq_num VARCHAR(20),
                raw MEDIUMTEXT,       
                PRIMARY KEY  (id)
                ) COLLATE utf8_general_ci;";
        \dbDelta($sql);

        $sql = "CREATE TABLE " . $this->TEMPLATES_TABLE . " (
                id INT AUTO_INCREMENT,                 
                pages MEDIUMTEXT,                
                styles MEDIUMTEXT,
                document_settings MEDIUMTEXT,
                form_id INT,        
                name VARCHAR(200),
                PRIMARY KEY  (id)
                ) COLLATE utf8_general_ci;";
        \dbDelta($sql);

        $sql = "CREATE TABLE " . $this->CUSTOM_FIELD . " (
                id INT AUTO_INCREMENT,                 
                name VARCHAR(200),                
                code MEDIUMTEXT,
                form_id INT,
                PRIMARY KEY  (id)
                ) COLLATE utf8_general_ci;";
        \dbDelta($sql);
    }



    public function AddAdvertisementParams($params)
    {
        return $params;
    }
    public function CreateHooks()
    {
        add_action('admin_enqueue_scripts',array($this,'AddDeactivationDialog'));
    }

    public function AddDeactivationDialog(){
        global $pagenow;
        if($pagenow=='plugins.php')
        {
            \wp_enqueue_script($this->Prefix.'_deactivation_bundle',$this->URL . 'js/dist/DeactivationDialog_bundle.js',array('wp-element'));
        }
    }

}