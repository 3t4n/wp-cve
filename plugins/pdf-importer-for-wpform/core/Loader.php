<?php

namespace rnpdfimporter\core;
use rnpdfimporter\ajax\ImporterManager;
use rnpdfimporter\ajax\Settings;
use rnpdfimporter\api\PDFImporterApi;
use rnpdfimporter\core\db\core\DBManager;
use rnpdfimporter\core\Integration\Processors\Loader\ProcessorLoaderBase;
use rnpdfimporter\core\PluginBase;
use rnpdfimporter\pr\core\PRLoader;

abstract class Loader extends PluginBase
{
    public $PDFImporterTable;
    /** @var ProcessorLoaderBase */
    public $ProcessorLoader;
    public $FormConfigTable;
    public $RECORDS_TABLE;
    /** @var PRLoader */
    public $PRLoader;
    public $BasePrefix;

    public static $Loader;
    public function __construct($prefix,$basePrefix, $dbVersion, $fileVersion,$mainFile,$config=null)
    {
        global $wpdb;
        $this->BasePrefix=$basePrefix;
        $this->PDFImporterTable= $wpdb->prefix.$prefix.'_pdf_import';
        $dbManager=new DBManager();
        $this->FormConfigTable= $wpdb->prefix.$prefix.'_form_config';
        $this->RECORDS_TABLE=$wpdb->prefix.$prefix.'_'.'records';
        parent::__construct($prefix, $dbVersion, $fileVersion,$mainFile,$config);
        self::$Loader=$this;
        require_once $this->DIR.'vendor/autoload.php';

        new ImporterManager($this);
        new Settings($this);
    }

    public function  IsPR(){
        return file_exists($this->DIR.'pr');
    }

    public abstract function CreateEntryRetriever();
    public abstract function GetPurchaseURL();
    public function CreateHooks()
    {
        add_filter('rnpdfimporter_get_loader',function(){return $this;});
    }

    public function GetRootURL()
    {
        return 'https://formwiz.rednao.com/';
    }

    public abstract function AddAdvertisementParams($params);

    public function OnCreateTable()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $sql = "CREATE TABLE " . $this->PDFImporterTable . " (
                id INT AUTO_INCREMENT,
                field_settings MEDIUMTEXT,
                date_format VARCHAR(100),
                name VARCHAR(200) NOT NULL,
                form_used INT,
                pdf_file_path VARCHAR(3000),
                pdf_name VARCHAR(3000),
                pdf_file_name MEDIUMTEXT,
                attach_to_email MEDIUMTEXT,
                skip_condition MEDIUMTEXT,
                additional_settings MEDIUMTEXT,
                PRIMARY KEY  (id)
                ) COLLATE utf8_general_ci;";
        \dbDelta($sql);




        $sql = "CREATE TABLE " . $this->FormConfigTable . " (
                id INT AUTO_INCREMENT,
                original_id BIGINT,
                name VARCHAR(200) NOT NULL,
                fields MEDIUMTEXT,
                notifications MEDIUMTEXT,
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


    }

    public abstract function GetProductItemId();


}
