<?php
/**
 * Plugin Name: WooCommerce PDF Invoice Builder
 * Plugin URI: https://wooinvoice.rednao.com/getit
 * Description: Attach a PDF Invoice to your woocommerce...
 * Author: RedNao
 * Author URI: http://rednao.com
 * Version: 1.2.120
 * Text Domain: pdf_invoice_builder
 * Domain Path: /languages/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 * Slug: pdf-invoice-builder
 */

use rnwcinv\bulk_actions\RNBulkActionManager;
use rnwcinv\compatibility\RemoveGlobalInvoiceNumbers;
use rnwcinv\htmlgenerator\OrderValueRetriever;
use rnwcinv\pr\Translation\PDFTranslationBase;
use rnwcinv\pr\Translation\PDFTranslatorFactory;
use rnwcinv\utilities\RNIoC;

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


final class RednaoWooCommercePDFInvoice{
    public static $NAME;
    public static $DIR;
    public static $URL;
    public static $DBVERSION=60;
    public static $HOOK_PREFIX;
    public static $FILE_VERSION=34;
    public static $BASE_NAME;
    public static $VERSION='1.2.4';
    public $RootPath;
    public $Config;
    public static $Instance;
    public static $INVOICE_TABLE;
    public static $INVOICES_CREATED_TABLE;
    public static $CUSTOM_FIELDS_TABLE;

    public $bulkActionManager;
    /** @var RNIoC */
    public static $IoC;

    public function __construct()
    {

        load_plugin_textdomain( 'wooinvoicebuilder', false,  plugin_basename(dirname(__FILE__)) . '/languages/' );


        global $RednaoWooCommercePDFInvoiceInstance;
        $RednaoWooCommercePDFInvoiceInstance=$this;
        $this->RootPath=__FILE__;
        $this->Config=array(
            'ItemId'=>233,
            'Author'=>'Edgar Rojas',
            'UpdateURL'=>'https://wooinvoice.rednao.com/',
            'FileGroup'=>'woo-pdf-invoice-pro'
        );
        global $wpdb;
        RednaoWooCommercePDFInvoice::$INVOICE_TABLE=$wpdb->prefix."rednao_wc_invoice";
        RednaoWooCommercePDFInvoice::$INVOICES_CREATED_TABLE=$wpdb->prefix."rednao_wc_invoices_created";
        RednaoWooCommercePDFInvoice::$CUSTOM_FIELDS_TABLE=$wpdb->prefix."rednao_wc_invoices_custom_field";
        RednaoWooCommercePDFInvoice::$BASE_NAME=plugin_basename(__FILE__);
        RednaoWooCommercePDFInvoice::$NAME=dirname(plugin_basename(__FILE__));
        RednaoWooCommercePDFInvoice::$DIR=__DIR__.'/';
        RednaoWooCommercePDFInvoice::$URL=plugin_dir_url(__FILE__);


        RednaoWooCommercePDFInvoice::$IoC=new RNIoC();
        if(RednaoWooCommercePDFInvoice::IsPR())
        {
            require_once(RednaoWooCommercePDFInvoice::$DIR.'pr/woocommerce-pdf-pr.php');
        }

        require_once RednaoWooCommercePDFInvoice::$DIR.'woocommerce-pdf-invoice-ajax.php';
        $this->DefineHooks();

        //$this->CreateTemplateRedirect();


    }


    public function GetConfig($configName,$defaultValue='')
    {
        if(!isset($this->Config[$configName]))
            return $defaultValue;

        return $this->Config[$configName];
    }

    public static function  IsPR(){
        return file_exists(RednaoWooCommercePDFInvoice::$DIR.'pr/woocommerce-pdf-pr.php');
    }

    private function DefineHooks()
    {
       add_action('admin_menu',array($this,'HookCreateMenu'));
       add_action('woocommerce_thankyou',array($this,'WooCommerceThankYou'));
        add_action( 'add_meta_boxes', array( $this, 'AddOrderMetaBox' ));
        add_action( 'admin_enqueue_scripts', array( $this, 'AdminStyles' ) );
        add_action( 'woocommerce_admin_order_actions_end', array( $this, 'ViewPDFAction' ) );
        add_action('admin_enqueue_scripts',array($this,'RemoveOtherScripts'),999);
        add_action('admin_footer',array($this,'AddDeactivationDialog'));
        add_action('woocommerce_order_status_changed',array($this,'OrderStatusChanged'),10,4);
        add_filter( 'woocommerce_email_attachments', array( $this, 'AttachPDFEmail' ), 99, 3 );
        add_action( 'admin_notices', array($this,'ReviewNotice') );
        add_action('plugins_loaded',array($this,'UpdateDBIfNeeded'));
        add_action( 'woocommerce_order_after_calculate_totals',  array($this,'AfterCalculatingTotals'),10,2 );
        add_action( 'woocommerce_checkout_order_processed',array($this,'CheckoutOrderProcessed'),10,3 );
        register_activation_hook(__FILE__,array($this,'UpdateDBIfNeeded'));
        add_action( 'admin_enqueue_scripts', array($this,'LoadMetaScriptWhenNeeded'));

        $this->bulkActionManager=new RNBulkActionManager();
        $this->bulkActionManager->InitializeHooks();
    }

    public function WooCommerceThankYou($orderId){
        if(RednaoWooCommercePDFInvoice::IsPR())
        {
            $translator=PDFTranslatorFactory::GetTranslator(0,$orderId);
            if($translator!=null)
                $translator->GetOrderLanguage();//called to set the order language if it was not set automatically
        }
    }

    public function CheckoutOrderProcessed($orderId,$postData,$order){
        $this->AfterCalculatingTotals(null,$order);
    }

    /**
     * @param $taxes
     * @param $order WC_Order
     */
    public function AfterCalculatingTotals($taxes,$order){
        foreach($order->get_taxes() as $tax)
        {
            $tax->update_meta_data('rn_tax_percentage',WC_Tax::get_rate_percent($tax->get_rate_id()));
            $tax->save_meta_data();
        }
    }

    public function LoadMetaScriptWhenNeeded(){
        $screen=get_current_screen();
        if($screen!=null&&$screen->post_type=='shop_order')
        {
            global $theorder;
            if($theorder!=null)
            {
                $orderid = $theorder->get_id();
            }
            else
                $orderid=get_the_ID();

            wp_enqueue_script('jquery');
            wp_enqueue_script('rnwpdfi-metabox',RednaoWooCommercePDFInvoice::$URL.'js/dist/metabox_bundle.js',array('jquery'),RednaoWooCommercePDFInvoice::$FILE_VERSION);
            wp_enqueue_style('rnwpdfi-metabox',RednaoWooCommercePDFInvoice::$URL.'js/dist/metabox_bundle.css');

            wp_enqueue_script('rnwpdfi_translator',RednaoWooCommercePDFInvoice::$URL.'js/lib/Translator/RNTranslator.js');

            $data=require(RednaoWooCommercePDFInvoice::$DIR.'jstranslations/metabox.php');
            wp_localize_script('rnwpdfi-metabox','RNTranslatorDictionary',apply_filters('rnwcinv_orders_panel_translations',$data));


            global $wpdb;
            $results=$wpdb->get_results($wpdb->prepare("select invoice.invoice_id InvoiceId,name Name,created.invoice_number InvoiceNumber,unix_timestamp(created.date) Date,formatted_invoice_number FormattedInvoiceNumber,email_config EmailConfig,conditions Conditions
                                    from ".RednaoWooCommercePDFInvoice::$INVOICE_TABLE." invoice
                                    left join ".RednaoWooCommercePDFInvoice::$INVOICES_CREATED_TABLE." created
                                    on invoice.invoice_id=created.invoice_id and order_id=%s order by invoice.Name",$orderid));


            echo $wpdb->last_error;
            $DefaultPrinterId='';
            $DefaultPrinterLabel='';

            if(RednaoWooCommercePDFInvoice::IsPR())
            {
                $DefaultPrinterLabel=\rnwcinv\pr\utilities\Printer\Printer::GetDefaultPrinterLabel();
                $DefaultPrinterId=\rnwcinv\pr\utilities\Printer\Printer::GetDefaultPrinter();
            }

            $to = '';
            $order = new WC_Order( $orderid );
            if($order!=false)
            {
                $to=$order->get_billing_email();

            }

            if(RednaoWooCommercePDFInvoice::IsPR())
            {
                require_once RednaoWooCommercePDFInvoice::$DIR.'PDFGenerator.php';
                require_once RednaoWooCommercePDFInvoice::$DIR.'pr/conditions/ConditionManager.php';

                for($i=0;$i<count($results);$i++)
                {
                    $currentResult=$results[$i];
                    if(!isset($currentResult->Conditions))
                        continue;

                    $conditions=json_decode($currentResult->Conditions);
                    if($conditions===false)
                        continue;

                    if(!isset($conditions->DontShowThisTemplate)||$conditions->DontShowThisTemplate==false)
                        continue;

                    $option=RednaoPDFGenerator::GetPageOptionsById($currentResult->InvoiceId);
                    $retriever=new OrderValueRetriever(null,$option,false,$order,null);
                    $manager=new ConditionManager($retriever);
                    if(!$manager->ShouldProcess(json_decode($option->conditions))||!apply_filters( 'rednao_wcpdfinvoice_should_process', true,$order->get_id(),$option ))
                    {
                        array_splice($results,$i,1);
                        $i--;
                    }

                }
            }




            wp_localize_script('rnwpdfi-metabox','rnpdfinvOrderParams',
                array(
                    'Invoices'=>$results,
                    'PDFBuilderURL'=>get_admin_url(null,'admin.php?page=wc_invoice_menu'),
                    'GenerationURL'=>wp_nonce_url( get_admin_url( null,"admin-ajax.php?action=rednao_wcpdfinv_generate_pdf&orderid=" . $orderid ), 'rednao_wcpdfinv_generate_pdf_'.$orderid ),
                    'OrderId'=>$orderid,
                    'CanViewOrderNonce'=>'can_view_order_'.$orderid,
                    'HasPrintNode'=>get_option('rniotg_printer_api_key','')!='',
                    'DefaultPrinterId'=>$DefaultPrinterId,
                    'DefaultPrinterLabel'=>$DefaultPrinterLabel,
                    'PrintNonce'=>wp_create_nonce('print_'.$orderid),
                    'EmailNonce'=>wp_create_nonce('pdfi_manage_nonce'),
                    'DeleteNonce'=>wp_create_nonce('delete_'.$orderid),
                    'EditInvoiceNumberNonce'=>wp_create_nonce('edit_number_'.$orderid),
                    'IsPr'=>self::IsPR(),
                    'Email'=>$to
                ));
        }
    }

    public function AddDeactivationDialog(){
        echo '<script type="text/javascript">var rednaoAdminEmail="'.get_option('admin_email').'"</script>';
        echo '<script type="text/javascript" src="'.RednaoWooCommercePDFInvoice::$URL.'js/dist/deactivation_bundle.js"></script>';
    }

    public function ReviewNotice(){
        require_once RednaoWooCommercePDFInvoice::$DIR.'ReviewHelper.php';
        $review=new ReviewHelper();
        $review->Start();

    }

    public function OrderStatusChanged($orderId,$fromStatus,$toStatus,$instance=null,$test=null){
        global $wpdb;

        $results=$wpdb->get_results($wpdb->prepare("select template.invoice_id from ".RednaoWooCommercePDFInvoice::$INVOICE_TABLE." template 
                                                                left join ".RednaoWooCommercePDFInvoice::$INVOICES_CREATED_TABLE." created
                                                                 on created.invoice_id=template.invoice_id and order_id=%s where create_when=%s and created.invoice_id is null",$orderId,$toStatus));

        if($instance==null)
        {
            $instance=wc_get_order($orderId);
        }

        if(count($results)>0)
        {
            foreach ($results as $result)
            {

                require_once 'PDFGenerator.php';
                $option=RednaoPDFGenerator::GetPageOptionsById($result->invoice_id);
                $retriever=new OrderValueRetriever(null,$option,false,$instance,null);

                if (RednaoWooCommercePDFInvoice::IsPR())
                {

                    require_once RednaoWooCommercePDFInvoice::$DIR.'pr/conditions/ConditionManager.php';
                    $manager=new ConditionManager($retriever);
                    if(!$manager->ShouldProcess(json_decode($option->conditions))||!apply_filters( 'rednao_wcpdfinvoice_should_process', true,$orderId,$option ))
                    {
                        continue;
                    }
                }

                $generator = self::$IoC->getPDFGenerator($option, false, $instance);
                $generator->Generate(true);

            }
        }
    }


    public function GetInvoiceListThatShouldBeAttached(){

    }

    /**
     * @param $attachments
     * @param $email_id
     * @param $order WC_Order
     * @return mixed
     * @throws Exception
     */
    public function AttachPDFEmail($attachments, $email_id, $order){
        if ( !is_object( $order ) || !isset( $email_id ) ) {
            return $attachments;
        }




        if (get_class($order) == 'WP_User')
        {
            return $attachments;
        }

        require_once 'smarty/wrappers/WrapperBase.php';


        if($order!=null&&!method_exists($order,'get_id'))
            return $attachments;
        $order_id=$order->get_id();
        if(get_class($order)!='rnadvanceemailingwc\pr\SplittedOrder')
        {
            $order = null;
            try
            {
                $order = new WC_Order($order_id);
                if ($order == null)
                    return $attachments;
            } catch (Exception $exception)
            {
                return $attachments;
            }
        }


        if (in_array($email_id, array('no_stock', 'low_stock', 'backorder', 'customer_new_account', 'customer_reset_password')) || (get_post_type($order_id) != 'shop_order'&&get_post_type($order_id) != 'shop_order_placehold'))
        {
            return $attachments;
        }


        require_once 'PDFGenerator.php';
        $optionList=RednaoPDFGenerator::GetPageOptionsByEmailId($email_id);
        $tmp_path = RednaoWooCommercePDFInvoice::GetSubFolderPath('attachments');
        $files=array_diff(scandir($tmp_path),array('.','..'));

        foreach($files as $currentFile)
        {
            $currentFile=$tmp_path.$currentFile;
            if(is_dir($currentFile)&&time()-filemtime($currentFile)>60)
            {
                self::recursiveRemove($currentFile);
            }
        }

        //array_map('unlink', ( glob( $tmp_path.'*.pdf' ) ? glob( $tmp_path.'*.pdf' ) : array() ) );

        $i=1;
        while(is_dir($tempFolderToReturn=$tmp_path.'temp'.$i.'/'))
        {
            $i++;
        }
        $tmp_path=$tempFolderToReturn;
        if(!\mkdir($tmp_path))
            throw new Exception('Could not create folder '.$tempFolderToReturn);

        if (get_post_type($order_id) == 'wc_booking' && isset($order->order))
        {
            $order = $order->order;
        }
        $index=0;

        foreach($optionList as $option)
        {
            $retriever=new OrderValueRetriever(null,$option,false,$order,null);
            if($option->conditions!=''&&RednaoWooCommercePDFInvoice::IsPR())
            {
                require_once RednaoWooCommercePDFInvoice::$DIR.'pr/conditions/ConditionManager.php';
                $manager=new ConditionManager( $retriever);
                if(!$manager->ShouldProcess(json_decode($option->conditions))||!apply_filters( 'rednao_wcpdfinvoice_should_process', true,$order_id,$option ))
                {
                    continue;
                }
            }

            $index++;
            $generator =\rnwcinv\GeneratorFactory::GetGenerator($option,$order);
            $secure=false;
            if(\rnwcinv\utilities\Sanitizer::GetValueFromPath($option,['containerOptions','PasswordProtect','enabled'],false)==true)
            {
                $encryptionSource=\rnwcinv\utilities\Sanitizer::GetValueFromPath($option,['containerOptions','PasswordProtect','encryptionSource'],false);
                if($encryptionSource=='client')
                {
                    $emails=WC()->mailer()->get_emails();
                    foreach($emails as $currentEmail)
                    {
                        if($currentEmail->id==$email_id&&$currentEmail->is_customer_email())
                        {
                            $secure=true;
                        }
                    }
                }
                else
                    $secure=true;
            }

            $generator->GenerateAttachment($tmp_path, $attachments,$index,$secure);
        }
        return $attachments;

    }


    public static function recursiveRemove($dir) {
        $structure = glob(rtrim($dir, "/").'/*');
        if (is_array($structure)) {
            foreach($structure as $file) {
                if (is_dir($file)) self::recursiveRemove($file);
                elseif (is_file($file)) unlink($file);
            }
        }
        rmdir($dir);
    }

    public static function GetSubFolderPath ($subfolder) {
        $upload_dir = wp_upload_dir();
        $upload_base = trailingslashit( $upload_dir['basedir'] );
        $subfolderbase = trailingslashit( apply_filters( 'rednao_wcpdfinvoice_temp_folder', $upload_base . 'rednao_wcpdfi/' ) );

        if ( !@is_dir( $subfolderbase ) ) {
            RednaoWooCommercePDFInvoice::CreateFolder($subfolderbase);
        }

        $fullPath=$subfolderbase.$subfolder.'/';

        if ( !@is_dir( $fullPath ) ) {
            RednaoWooCommercePDFInvoice::CreateFolder($fullPath);
        }

        return $fullPath;
    }



    public static function CreateFolder($folderPath)
    {
        @mkdir( $folderPath );
        @file_put_contents( $folderPath . '.htaccess', 'deny from all' );
        @touch( $folderPath . 'index.php' );
    }

    public function ViewPDFAction($order){
       if ( $order->get_status() == 'trash' ) {
            return;
        }

       $orderActions=\rnwcinv\Managers\OrderActionsManager::GetOrderActions();
       if(count($orderActions)==0)
       {
           $url = wp_nonce_url(admin_url("admin-ajax.php?action=rednao_wcpdfinv_generate_pdf&orderid=" . $order->get_id()), 'rednao_wcpdfinv_generate_pdf_'.$order->get_id());
           echo '<a style="display:inline-flex;align-items:center;justify-content:center"  href="' . $url . '" class="button tips wpo_wcpdf" target="_blank" alt="View PDF" data-tip="View PDF">' .
               '<img src="' . RednaoWooCommercePDFInvoice::$URL . 'images/invoice.png" width="16"></a>';
       }else{
            foreach ($orderActions as $currentAction)
            {
                $label='';
                switch ($currentAction->Id)
                {
                    case "Download":
                        $label=__('Download','wooinvoicebuilder');
                        break;
                    case 'View':
                        $label=__('View','wooinvoicebuilder');
                        break;

                }
                $url = wp_nonce_url(admin_url("admin-ajax.php?action=rednao_wcpdfinv_generate_pdf&orderid=" . $order->get_id().'&actionid='.$currentAction->Id.'&invoice_id='.$currentAction->InvoiceId), 'rednao_wcpdfinv_generate_pdf_'.$order->get_id());
                echo '<a style="display:inline-flex;align-items:center;justify-content:center"  href="' . $url . '" class="button tips wpo_wcpdf" target="_blank" alt="'.esc_attr($label.' '.$currentAction->Name).'" data-tip="'.esc_attr($label.' '.$currentAction->Name).'">' .
                    '<img src="' . $currentAction->Icon.'" style="max-width: 24px;max-height: 24px;width:auto;height: auto;"></a>';
            }
       }
    }

    public function AdminStyles(){
        wp_register_style( 'rednao_wc_pdf_invoice', RednaoWooCommercePDFInvoice::$URL . '/css/metaBoxStyle.css',array('jquery'),RednaoWooCommercePDFInvoice::$FILE_VERSION);
        wp_enqueue_script('jquery');
        wp_enqueue_script('rednao_wc_pdf_invoice_metabox', RednaoWooCommercePDFInvoice::$URL . '/js/screens/MetaBoxes.js',array('jquery'), RednaoWooCommercePDFInvoice::$FILE_VERSION);
    }

    public function UpdateDBIfNeeded(){
        $dbversion=get_option("WC_PDF_INVOICE_LATEST_DB_VERSION",0);
        if($dbversion<RednaoWooCommercePDFInvoice::$DBVERSION)
        {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            $sql = "CREATE TABLE " . RednaoWooCommercePDFInvoice::$INVOICE_TABLE . " (
                invoice_id INT AUTO_INCREMENT,
                name VARCHAR(200) NOT NULL,
                attach_to VARCHAR(1000),
                options MEDIUMTEXT NOT NULL,
                create_when VARCHAR(20),
                html MEDIUMTEXT NOT NULL,
                conditions MEDIUMTEXT,
                order_actions MEDIUMTEXT,
                type INT,
                my_account_download TINYINT,            
                extensions MEDIUMTEXT,    
                pages MEDIUMTEXT,
                email_config MEDIUMTEXT,
                PRIMARY KEY  (invoice_id)
                ) COLLATE utf8_general_ci;";
                    dbDelta($sql);

            $sql = "CREATE TABLE " . RednaoWooCommercePDFInvoice::$INVOICES_CREATED_TABLE . " (
                invoice_created_id INT AUTO_INCREMENT,
                invoice_id INT NOT NULL,
                invoice_number BIGINT,
                formatted_invoice_number VARCHAR(2000),
                order_id INT NOT NULL,
                date DATETIME,    
                html MEDIUMTEXT,
                fields_dictionary MEDIUMTEXT,
                PRIMARY KEY  (invoice_created_id)
                ) COLLATE utf8_general_ci;";
            dbDelta($sql);

            $sql = "CREATE TABLE " . RednaoWooCommercePDFInvoice::$CUSTOM_FIELDS_TABLE . " (
                custom_field_id INT AUTO_INCREMENT,
                custom_field_name varchar(300) NOT NULL,
                custom_field_text MEDIUMTEXT,
                custom_field_type varchar(10),
                PRIMARY KEY  (custom_field_id)
                ) COLLATE utf8_general_ci;";
            dbDelta($sql);


            $this->CreateDefaultInvoiceIfNeeded();

            update_option("WC_PDF_INVOICE_LATEST_DB_VERSION", RednaoWooCommercePDFInvoice::$DBVERSION);
        }

        if($dbversion>0&&$dbversion<40)
        {
            $invoiceNumbers=new RemoveGlobalInvoiceNumbers();
            $invoiceNumbers->Execute();
        }
    }

    public static function CheckIfPDFAdmin(){
        if(!current_user_can('manage_options'))
        {
            die('Forbidden');
        }
    }

    public function AddOrderMetaBox() {
        if ( ! function_exists( '\wc_get_page_screen_id' ) ) {
            return;
        }

        $wc_screen_id = \wc_get_page_screen_id( 'shop-order' );
        add_meta_box( 'rednao_order_invoice', __( 'PDF Invoice', 'wooinvoicebuilder' ), array(
            $this,
            'DisplayMetaBox',
        ), $wc_screen_id, 'side', 'high' );
    }

    public function DisplayMetaBox($post){
        require_once 'meta-box.php';
        new RedNaoWooCommerceMetaBox($post);

    }

    public function HookCreateMenu()
    {
        add_menu_page('WC Invoice','WC Invoice','manage_options',"wc_invoice_menu",array($this,'CreateMenu')/*,plugin_dir_url(__FILE__).'images/smartFormsIcon.png'*/);
        add_submenu_page("wc_invoice_menu",'Manage Invoices','Manage Invoices','manage_options',__FILE__.'manage_invoices', array($this,'ManageInvoices'));

        add_submenu_page("wc_invoice_menu",'Documentation/Support','Documentation/Help','manage_options',__FILE__.'wish_list', array($this,'SupportMenu'));
        add_submenu_page("wc_invoice_menu",'Blank page/500 error resolver','Blank page/500 error resolver','manage_options',__FILE__.'errorresolver', array($this,'ErrorResolver'));

        add_submenu_page("wc_invoice_menu",'Our WC Plugins','Our WC Plugins','manage_options',__FILE__.'our_plugins', array($this,'OurWCPlugins'));

        if(RednaoWooCommercePDFInvoice::IsPR())
        {
            add_submenu_page("wc_invoice_menu", 'Custom Fields', 'Custom Fields', 'manage_options', 'invoice-builder-custom-fields', array($this, 'CustomFields'));
            add_submenu_page("wc_invoice_menu",'Settings','Settings','manage_options',__FILE__.'settings', array($this,'Settings'));
            //  add_submenu_page("wc_invoice_menu", 'PDF Viewer', 'PDF Viewer', 'manage_options', __FILE__ . 'pdfViewer', array($this, 'PDFViewer'));
        }

    }

    public function OurWCPlugins(){
        global $rninstance;
        $rninstance=$this;
        require_once RednaoWooCommercePDFInvoice::$DIR.'pages/additional_plugins.php';
    }


    public function ManageInvoices(){
        require_once RednaoWooCommercePDFInvoice::$DIR.'pages/manage_invoices.php';
    }

    public function CreateMenu(){
        require_once RednaoWooCommercePDFInvoice::$DIR.'pages/invoice_list.php';
    }

    public function CustomFields(){
        require_once RednaoWooCommercePDFInvoice::$DIR.'pages/custom_field_list.php';
    }

    public function PDFViewer(){
        require_once RednaoWooCommercePDFInvoice::$DIR.'pr/pages/pdf_viewer.php';
    }

    public function SupportMenu(){
        require_once RednaoWooCommercePDFInvoice::$DIR.'pages/documentation.php';
    }

    public function ErrorResolver(){
        require_once RednaoWooCommercePDFInvoice::$DIR.'pages/errorresolver.php';
    }

    public function Settings(){
        require_once RednaoWooCommercePDFInvoice::$DIR.'pr/pages/settings.php';
    }

    private function CreateTemplateRedirect()
    {
        add_filter('query_vars',array($this,'SetQueryVars'));
        add_action('template_redirect', array($this,'TemplateRedirect'));
    }



    private function CreateDefaultInvoiceIfNeeded()
    {
        global $wpdb;
        $count=$wpdb->get_var('select count(*) from '.RednaoWooCommercePDFInvoice::$INVOICE_TABLE);
        if($count<=0)
        {
            $wpdb->insert(RednaoWooCommercePDFInvoice::$INVOICE_TABLE,array(
                'name'=>'Default Template',
                'type'=>1,
                'attach_to'=>'["customer_completed_order"]',
                 'create_when'=>'completed',
                 'options'=>'{"pageSize":{"type":"A4","width":794,"height":1123},"headerOptions":{"height":100,"width":794,"position":"documentHeader"},"contentOptions":{"height":923,"width":794,"position":"documentContent"},"footerOptions":{"height":100,"width":794,"position":"documentFooter"},"fieldOptions":[{"targetId":"header","styles":{"left":"504px","top":"18px","position":"absolute","font-size":"14px","width":"250px","height":"32px","overflow":"hidden"},"type":"field","fieldID":3,"fieldOptions":{"fieldType":"order_number","labelPosition":"left","label":"Order Number:","prefix":"","sufix":"","digits":5}},{"targetId":"header","styles":{"left":"25px","top":"34px","position":"absolute","font-size":"14px","width":"177px","height":21},"type":"text","Text":"<p><span style=\"font-size: 24pt;\">Invoice<\/span><\/p>","fieldID":5},{"targetId":"header","styles":{"left":"503px","top":"50px","position":"absolute","font-size":"14px","width":"250px","height":"50px","overflow":"hidden"},"type":"field","fieldID":4,"fieldOptions":{"fieldType":"inv_date","labelPosition":"left","format":"F j, Y","label":"Order Date:"}},{"targetId":"content","styles":{"left":"5%","top":"57px","position":"absolute","font-size":"14px","width":"90%"},"type":"table","GridStyle":"1","ColumnOptions":[{"header":"Product","type":"prod","width":"60%","additionalProperties":[]},{"header":"Qty","type":"qty","width":"20%","additionalProperties":[]},{"header":"Price","type":"price","width":"20%","additionalProperties":[]}],"ShowShipping":true,"ShowSubtotal":true,"ShowTotal":true,"ShowTaxes":true,"ShowDiscount":true,"ShowFees":true,"ShippingLabel":"Shipping","TotalLabel":"Total","SubTotalLabel":"Subtotal","TaxesLabel":"Tax","DiscountLabel":"Discount","SubTotalAdditionalProperties":{"includeTaxes":false,"includeDiscount":false},"fieldID":6,"DesignerHeight":286},{"targetId":"content","styles":{"left":"64px","top":"411px","position":"absolute","font-size":"14px","width":"656px","height":"217px","overflow":"hidden"},"type":"field","fieldID":7,"fieldOptions":{"fieldType":"customer_notes","labelPosition":"top","label":""}}],"styles":"#pdfField_5 p{padding:0 !important;}#pdfField_6 .pdfTable,#pdfField_6 {border-collapse:collapse !important;}#pdfField_6 .pdfTable tr{border-spacing:0 !important;}#pdfField_6 .pdfTable .price{text-align:right !important;}#pdfField_6 .subTotalValue{text-align:right !important;}#pdfField_6 .th_price{text-align:right !important;}#pdfField_6 .qty{text-align:center !important;}#pdfField_6 .th_qty{text-align:center !important;}#pdfField_6 .invoiceDetailRow td{padding:5px 0 5px 0 !important;}#pdfField_6 .footerTable{margin-top:10px !important;}#pdfField_6 .footerTable th,#pdfField_6 .footerTable td{padding:7px 0 7px 0 !important;}#pdfField_6 .pdfTable thead th{background-color:black !important;color:white !important;padding:5px 2px 5px 2px !important;}#pdfField_6 .pdfTable > tbody td{border-bottom:1px #dfdfdf solid !important;}#pdfField_6 .subTotalLabel{padding-right:50px !important;font-size:12px !important;line-height:12px !important;}#pdfField_6 .subTotalRow .subTotalLabel,#pdfField_6  .subTotalRow .subTotalValue{border-top:1px #dfdfdf solid !important;}#pdfField_6 .total.subTotalRow .subTotalLabel,#pdfField_6  .total.subTotalRow .subTotalValue{border-top:2px black solid !important;border-bottom:2px black solid !important;}"}'
            ));
        }
    }



    function RemoveOtherScripts($hook){
        if($hook=='toplevel_page_wc_invoice_menu')
        {

          //  add_action('admin_print_scripts' ,array($this,'RemoveScripts'));
          //  add_action('admin_print_styles',array($this,'RemoveStyles'));
        }
    }



    function RemoveStyles(){

        global $wp_styles;
        $styles=$wp_styles->queue;
        $queuedStyles=$wp_styles->queue;
        $allowedStyles=array('admin-bar','colors','ie','wp-auth-check');
        foreach($queuedStyles as $queue)
        {
            if(isset($wp_styles->registered[$queue]))
            {
                if($wp_styles->registered[$queue]->src)
                {
                    if(strpos($wp_styles->registered[$queue]->src,'wp-includes/')!==false||strpos($wp_styles->registered[$queue]->src,'wp-admin/')!==false||$wp_styles->registered[$queue]->src===true)
                        continue;
                    if(in_array($queue,$allowedStyles))
                        continue;

                    if(strpos($queue,'rednao_wc')!==false)
                        continue;

                    wp_dequeue_style($queue);
                    wp_deregister_style($queue);

                }
            }

        }

    }


    function RemoveScripts(){

        global $wp_scripts;
        $queuedScripts=$wp_scripts->queue;
        $allowedScripts=array('jquery','common','jquery-ui-core','admin-bar','utils','svg-painter','wp-auth-check');
        foreach($queuedScripts as $queue)
        {
            if(isset($wp_scripts->registered[$queue]))
            {
                if($wp_scripts->registered[$queue]->src)
                {
                    if(strpos($wp_scripts->registered[$queue]->src,'wp-includes/')!==false||strpos($wp_scripts->registered[$queue]->src,'wp-admin/')!==false||$wp_scripts->registered[$queue]->src===true)
                        continue;
                    if(in_array($queue,$allowedScripts))
                        continue;

                    if(strpos($queue,'rednao_wc')!==false)
                        continue;

                    if(strpos($queue,'_batch_handler')!==false)
                        continue;

                    wp_dequeue_script($queue);

                }
            }

        }

    }


}

if(function_exists('RedNaoWCInvLoader'))
    die('Looks like you already have a version of the plugin installed (perhaps the free version)? please deactivate/delete it before activating this version ');


require_once plugin_dir_path(__FILE__).'autoload.php';


RednaoWooCommercePDFInvoice::$Instance=new RednaoWooCommercePDFInvoice();

if(!function_exists('WPDFIB'))
{
    function WPDFIB()
    {
        return new \rnwcinv\api\PDFApi();
    }
}
