
<script type="text/javascript">

    var tinyMCEPreInit={
        baseURL: '<?php use rnwcinv\compatibility\DocumentOptionsCompatibility;use rnwcinv\htmlgenerator\DocumentOptionsDTO;use rnwcinv\pr\utilities\FontManager;echo RednaoWooCommercePDFInvoice::$URL?>'+'js/lib/mce',
        suffix: ".min"
    };
</script>

<style type="text/css">

    .conditionfield select{
        padding:0;
        padding-right: 20px;
    }


    .media-modal{
        z-index: 10000000000000;
    }

    .BorderContainer div[data-property-name="border-bottom-color"]{
        display:inline-block;
        vertical-align:top;

    }

    .BorderContainer div[data-property-name="border-bottom-width"]{
        display:inline-block;
        vertical-align:top;
        margin-left:10px;

    }

    .BorderContainer div[data-property-name="border-bottom-style"]{
        display:inline-block;
        vertical-align:top;
        margin-left:10px;

    }

    td.subTotalValue p{
        text-align:right;
    }

    .BorderContainer div[data-property-name="border-bottom-width"] input
    {
        width:90px
    }



    .BorderContainer div[data-property-name="border-bottom-style"] select
    {
        width:90px !important;
    }


    .pdfPage{border:1px solid #afafaf;box-shadow:1px 1px 1px #888}.pdfPage,.pdfPage p{line-height:1em!important}.pdfPage p{margin:0!important;padding:0!important}.toolbarContainer{background-color:#fafafa;height:101px;border-bottom:1px solid #afafaf}.toolbarItem{display:inline-block;cursor:pointer;vertical-align:top;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.toolbarItem:hover{background-color:#f5f5f5}.toolbarItemContainer{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center;height:100px;padding:10px;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column}.widgetList{display:inline}.toolbarItemContainer .imageContainer{display:inline-block;padding:5px;background-color:#fff;border-radius:5px;border:1px solid #bfbfbf;transition:border-color .1s ease-in}.toolbarItemContainer:hover .imageContainer{border-color:#505050}
    .pdfHeader{border-bottom:1px solid #dfdfdf;background-image:url(data:image/png;base64,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)}
    .pdfRepeatableHeader{border-bottom:1px solid #dfdfdf;background-image:url(data:image/png;base64,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)}
    .pdfRepeatableFooter{border-top:1px solid #dfdfdf;background-image:url(data:image/png;base64,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)}
    .invoiceDetailRow td{text-align: left}
    .pdfContent,.pdfHeader,.pdfRepeatableHeader,.pdfRepeatableFooter{background-repeat:no-repeat;background-position:50%}
    .pdfContent{background-image:url(data:image/png;base64,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)}
    .PDFElement p{text-align: left}
    .pdfFooter{border-top:1px solid #dfdfdf;background-image:url(data:image/png;base64,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);background-repeat:no-repeat;background-position:50%}
    .PDFElement{margin:-1px; border: 1px solid transparent;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;cursor:pointer}.PDFElement.ElementSelected{border:1px dashed #000}#wpbody-content{width:auto!important;min-width:100%}.pdfPage hr{padding:0!important;margin:0!important}#propertiesTitleBar{background-color:#3899ec;color:#fff;border-top-left-radius:10px;border-top-right-radius:10px;height:50px;display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-line-pack:center;align-content:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center;padding:10px;cursor:all-scroll}#propertiesPanel{width:500px;box-shadow:0 0 18px 0 rgba(22,45,61,.27);border-radius:10px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}#propertiesContent,#stylesContent{width:100%;position:absolute;top:0;left:0}#propertiesContent,#stylesContent,#tabsContainer{height:500px;background-color:#fdfdfd;padding:10px}.propertiesCloseButton{margin-left:auto;font-size:20px;opacity:.7;cursor:pointer;transition:opacity .1s ease-in}.propertiesCloseButton:hover{opacity:1;cursor:pointer}.mce-floatpanel{z-index:10000000!important}.PDFElement p{font-size:inherit}.itemImage{user-drag:none;user-select:none;-moz-user-select:none;-webkit-user-drag:none;-webkit-user-select:none;-ms-user-select:none;border:1px dashed #000}.imageProperty{max-width:200px;max-height:200px;cursor:pointer}.fieldProperty{margin-bottom:10px}.columnsTable td,.columnsTable th{padding:0 5px 10px 0}.columnsTable tr:last-child td{padding-bottom:0}.columnRow .delete{opacity:0;cursor:pointer;transition:opacity .2s ease-in,color .2s ease-in}.columnRow .delete:hover{color:red}.columnRow:hover .delete{opacity:1}.modal-header{background-color:#39f;color:#fff;min-height:16.42857143px;padding:15px;border-bottom:1px solid #e5e5e5;border-top-right-radius:6px;border-top-left-radius:6px}.modal-body{position:relative;padding:15px}.modal-footer{padding:15px;text-align:right;border-top:1px solid #e5e5e5;border-bottom-right-radius:6px;border-bottom-left-radius:6px}.modal-content{background-color:transparent!important}button.close{transition:opacity 50ms ease-in}.modal-body,.modal-footer{background-color:#fff!important}.close:focus,.close:hover{color:#fff!important;opacity:1!important}.modal-dialog{margin-top:50px!important}#propertiesPanel input[type=checkbox]{outline:none!important}#propertiesContent hr{margin:0 0 2px 0;border-color:#ddd}.contexticon{margin-right:5px}.contexticon.deleteItem{color:red}.basicContextContainer{z-index:100000!important}.sfTemplateContainer{position:absolute;top:0;width:100%;height:100%;background-color:#fbfbfb;z-index:100000;border-color:#dedede;border-style:solid;border-width:1px}.sfTemplateContainer.rednao_popup{position:static;border:none;background:transparent}.sfTemplateContainer.rednao_popup .formList{display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center}.sfTemplateContainer.rednao_popup .sfTemplateItem{margin:10px}.sfTemplateItem{width:200px;text-align:center;cursor:pointer;border-radius:25px;padding-bottom:5px;transition:box-shadow .3s ease-in-out;border-color:#dfdfdf;border-width:1px;border-style:solid;display:inline-block;margin-right:10px;position:relative;overflow:hidden}.sfTemplateItem h2{font-size:16px;font-weight:700}.sfTemplateItem .sfImage{height:250px;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center}.sfTemplateItem:hover{box-shadow:10px 10px 5px #888}.sfTemplateItem h2{margin:0}.previewSettings{transition:color .2s ease-out}.previewSettings:hover{color:#ffd0a9}.sfPreviewButton{position:absolute;top:0;width:100%;background-color:#82b541;color:#fff;-webkit-border-top-left-radius:25px;-webkit-border-top-right-radius:25px;-moz-border-radius-topleft:25px;-moz-border-radius-topright:25px;border-top-left-radius:25px;border-top-right-radius:25px;font-size:15px;height:20px;top:-20px;transition:top .2s ease-out;cursor:zoom-in}.pdfToolBar{z-index:999}.sfTemplateItem:hover .sfPreviewButton{top:0}.fontItem{width:100px;text-align:center}.fontImage{font-size:40px}.fontItem:hover{cursor:pointer;color:red}.select2-dropdown{z-index:100000!important}#propertiesPanel .propertiesTab{cursor:pointer;opacity:.5;transition:opacity .2s ease-out}#propertiesPanel .propertiesTab.active{opacity:1}.sfStyleTitle{background-color:#f7f7f9;padding:3px;border-style:solid;border-width:1px;border-color:#e5e5e5}.sfStyleTitle a{padding-left:5px;outline:0!important;box-shadow:none;text-decoration:none}.sfStyleTitle a.collapsed .sfAccordionIcon{-webkit-transform:rotate(0deg);transform:rotate(0deg)}.sfStyleTitle a .sfAccordionIcon{-webkit-transform:rotate(90deg);transform:rotate(90deg)}.sfStyleContainer{padding:5px;border-color:#eee;border-style:solid;border-width:1px}.figureItem{text-align:center;height:170px;cursor:pointer}.figureItem:hover{color:red}.figureItem:hover .figureImage{border-color:red}.figureImage{width:120px;height:120px;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center;border:1px solid #dfdfdf}.figureImage img{max-width:110px;max-height:110px}.multiplePropertyColumn{background-color:#fafafa;border:1px solid #efefef}.inlineButtonGroup .propertyContent,.inlineButtonGroup label{display:inline-block!important}.rnSettings .tab-content{border-bottom:1px solid #ddd;border-left:1px solid #ddd;border-right:1px solid #ddd;padding:10px}.rnSettings .nav-tabs a{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center}#rnConditions td{padding:2px!important}#rnConditions th{text-align:center}.pdfPageContainer a,.pdfPageContainer img{-webkit-user-drag:none;-khtml-user-drag:none;-moz-user-drag:none;-o-user-drag:none;user-drag:none}.pdfTagList li{padding:5px;text-decoration:underline;cursor:pointer;font-size:19px}.pdfTagList li:hover{background-color:#a5f3ff}.toolbarItemContainer .imageContainer{position:relative}
    .fieldLabel {
        text-align: left;
    }
    @font-face {
        font-family: DejaVu Sans;
        src:url('<?php echo RednaoWooCommercePDFInvoice::$URL?>fonts/DejaVuSans.ttf');

    }

    .select2-selection.select2-selection--multiple{
        border-color:#ddd !important;
    }

    .fieldValueContainer p{
        text-align: left;
    }
</style>

<?php
if(!defined('ABSPATH'))
    die('Forbidden');
wp_enqueue_media();
$dependencies=array('wcrbc-pdfbuilder-shared');
wp_enqueue_script('jquery');
wp_enqueue_script('wcrbc-pdfbuilder-shared',RednaoWooCommercePDFInvoice::$URL.'js/dist/pageBuilderShared_bundle.js','jquery');

$dependencies=apply_filters('wcrbc-pdfbuilder-pdfbuilder-starting',$dependencies);

do_action('rndevtools_initializing_rendering','WooInvoiceBuilder','Builder');

wp_enqueue_media();
wp_enqueue_script('wcrbc-pdfbuilder-react',RednaoWooCommercePDFInvoice::$URL.'js/dist/pageBuilderReact_bundle.js',$dependencies);
wp_enqueue_script('wcrbc-pdfbuilder-velocity',RednaoWooCommercePDFInvoice::$URL.'js/lib/velocity/velocity.min.js',array('jquery'));
wp_enqueue_script('wcrbc-pdfbuilder-select2',RednaoWooCommercePDFInvoice::$URL.'js/lib/select2/select2.full.js',array('jquery'));
wp_enqueue_script('wcrbc-pdfbuilder-velocity-async',RednaoWooCommercePDFInvoice::$URL.'js/lib/velocityAsync/velocityAsync.js',array('wcrbc-pdfbuilder-velocity'));
//wp_enqueue_script('wcrbc-pdfbuilder-bundle-inline',RednaoWooCommercePDFInvoice::$URL.'js/pdfbuilder/release/inline.bundle.js',array('jquery'),RednaoWooCommercePDFInvoice::$FILE_VERSION,true);
//wp_enqueue_script('wcrbc-pdfbuilder-bundle-polyfills',RednaoWooCommercePDFInvoice::$URL.'js/pdfbuilder/release/polyfills.bundle.js',array('wcrbc-pdfbuilder-bundle-inline'),RednaoWooCommercePDFInvoice::$FILE_VERSION,true);
//wp_enqueue_script('wcrbc-pdfbuilder-bundle-vendor',RednaoWooCommercePDFInvoice::$URL.'js/pdfbuilder/release/vendor.bundle.js',RednaoWooCommercePDFInvoice::$FILE_VERSION,true);
//wp_enqueue_script('wcrbc-pdfbuilder-bundle-main',RednaoWooCommercePDFInvoice::$URL.'js/pdfbuilder/release/main.bundle.js',array('wcrbc-pdfbuilder-bundle-vendor'),RednaoWooCommercePDFInvoice::$FILE_VERSION,true);
wp_enqueue_script('wcrbc-bootstrap',RednaoWooCommercePDFInvoice::$URL.'css/bootstrap/js/bootstrap.min.js',array('jquery'),RednaoWooCommercePDFInvoice::$FILE_VERSION,true);
//wp_enqueue_script('wcrbc-pdfbuilder-bundle-style-2',RednaoWooCommercePDFInvoice::$URL.'js/pdfbuilder/release/styles.bundle.js');
wp_enqueue_script('wcrbc-pdfbuilder-spectrum',RednaoWooCommercePDFInvoice::$URL.'js/lib/spectrum/spectrum.js',array('jquery'));
wp_enqueue_script('wcrbc-pdfbuilder-bootstrapSlider',RednaoWooCommercePDFInvoice::$URL.'js/lib/bootstrapSlider/bootstrap-slider.js',array('jquery'));


wp_enqueue_style('wcrbc-pdfbuilder-react-style',RednaoWooCommercePDFInvoice::$URL.'css/PageBuilderStyle.css');
//wp_enqueue_style('wcrbc-pdfbuilder-bundle-style',RednaoWooCommercePDFInvoice::$URL.'js/pdfbuilder/release/styles.bundle.css');
wp_enqueue_style('wcrbc-bootstrap',RednaoWooCommercePDFInvoice::$URL.'css/bootstrap/css/bootstrap.min.css');
wp_enqueue_style('wcrbc-bootstrap-theme',RednaoWooCommercePDFInvoice::$URL.'css/bootstrap/css/bootstrap-theme.min.css');
wp_enqueue_style('wcrbc-bootstrap-slider',RednaoWooCommercePDFInvoice::$URL.'css/bootstrap-slider/bootstrap-slider.min.css');
wp_enqueue_style('wcrbc-spectrum-color',RednaoWooCommercePDFInvoice::$URL.'css/spectrum/spectrum.css');
wp_enqueue_style('wcrbc-select2',RednaoWooCommercePDFInvoice::$URL.'css/select2/select2.css');
wp_enqueue_style('wcrbc-select2-bootstrap',RednaoWooCommercePDFInvoice::$URL.'css/select2/select2-bootstrap.min.css');
wp_enqueue_style('wcrbc-context',RednaoWooCommercePDFInvoice::$URL.'css/basiccontext/basicContext.min.css');
wp_enqueue_style('wcrbc-context-default',RednaoWooCommercePDFInvoice::$URL.'css/basiccontext/default.min.css');
wp_enqueue_style('wcrbc-context-default-animation',RednaoWooCommercePDFInvoice::$URL.'css/basiccontext/animation.css');


wp_enqueue_style('wcrbc-ladda',RednaoWooCommercePDFInvoice::$URL.'css/ladda/ladda.min.css');
wp_enqueue_style('wcrbc-font-awesome',RednaoWooCommercePDFInvoice::$URL.'css/fontAwesome/css/font-awesome.css');
wp_enqueue_style('wcrbc-ladda',RednaoWooCommercePDFInvoice::$URL.'css/ladda/ladda-themeless.min.css');
wp_enqueue_style('wcrbc-toastr',RednaoWooCommercePDFInvoice::$URL.'css/toastr/toastr.css');


/*


wp_enqueue_style('wcrbc-bootstrap',RednaoWooCommercePDFInvoice::$URL.'css/bootstrap/css/bootstrap.min.css');
wp_enqueue_style('wcrbc-bootstrap-theme',RednaoWooCommercePDFInvoice::$URL.'css/bootstrap/css/bootstrap-theme.min.css');*/

$newsletter=get_option('wopdfinv_stage',1);

if($newsletter!=2)
{
?>

<?php
}

if(!is_writable(RednaoWooCommercePDFInvoice::GetSubFolderPath('attachments')))
{
    echo '<div class="alert alert-danger" role="alert"><strong><span class="glyphicon glyphicon-warning-sign"></span> Warning!</strong> The plugin can\'t create pdfs in the folder '.esc_html(RednaoWooCommercePDFInvoice::GetSubFolderPath('attachments')).' please fix the permissions of that folder or we won\'t be able to email invoices</div>';
}

global $wpdb;
if(isset($_GET['id']))
{
    $invoiceData = $wpdb->get_results($wpdb->prepare("SELECT invoice_id,attach_to,name,options,type,html,extensions,conditions,pages,my_account_download,create_when,order_actions FROM ".RednaoWooCommercePDFInvoice::$INVOICE_TABLE." WHERE invoice_id=%s",$_GET['id']));
}else{
    $invoiceData=array();
}

$codes=$wpdb->get_results("SELECT custom_field_id,custom_field_name,custom_field_type FROM ".RednaoWooCommercePDFInvoice::$CUSTOM_FIELDS_TABLE);


$pageOptions=false;
if(count($invoiceData)>0)
{
    $data=$invoiceData[0];
    $extensions=json_decode($data->extensions);
    if($extensions==false)
    {
        $extensions = array();
    }
    if(!is_array($extensions))
    {
        $extensions=array();
    }
    $extensions=apply_filters('rnpdf_invoice_load_extension_options',$data->invoice_id,$extensions);

    $orderActions=null;
    if($data->order_actions!=null&&$data->order_actions!='')
        $orderActions=json_decode($data->order_actions);

    /** @var DocumentOptionsDTO $pageOptions */
    $pageOptions=new stdClass();
    $pageOptions->pages=$data->pages;
    $pageOptions->pageId=$data->invoice_id;
    $pageOptions->myAccountDownload=$data->my_account_download=="1";
    $pageOptions->attachTo=json_decode($data->attach_to);
    $pageOptions->name=$data->name;
    $pageOptions->pageType=$data->type;
    $pageOptions->containerOptions=json_decode($data->options);
    $pageOptions->conditions=json_decode($data->conditions);
    $pageOptions->createWhen=$data->create_when;
    $pageOptions->orderActions=$orderActions;
    $pageOptions->extensions=$extensions;
    if(isset($data->pages))
        $pageOptions->pages=json_decode($data->pages);

    $pageOptions=DocumentOptionsCompatibility::execute($pageOptions);
}

$statuses=wc_get_order_statuses();
$statusList=array();

foreach ($statuses as $id=>$label)
{
    $statusList[]=array('Label'=>$label,'Id'=>str_replace('wc-','',$id));
}


$fontURL='';
$availableFonts=array();
if(RednaoWooCommercePDFInvoice::IsPR())
{
    $fontManager=new FontManager();
    $fontURL=$fontManager->GetFontURL();
    $availableFonts=$fontManager->GetAvailableFonts(true);

    echo "<style type=\"text/css\">";
    foreach($availableFonts as $font)
    {
        echo $fontManager->GetFontFace($font,false);

    }
    echo "</style>";
}

$id=0;
if(isset($_GET['id']))
    $id=$_GET['id'];
$extraConditions=array();
$emails=wc()->mailer()->get_emails();
$emailList=array();

foreach($emails as $currentEmail)
{
    if($currentEmail->is_enabled()||$currentEmail->is_manual())
    {
        $emailList[]=array(
            "Id"=>$currentEmail->id,
            "Label"=>$currentEmail->title

        );
    }
}




$orderby = 'name';
$order = 'asc';
$hide_empty = false ;
$cat_args = array(
    'orderby'    => $orderby,
    'order'      => $order,
    'hide_empty' => $hide_empty,
);

$product_categories = get_terms( 'product_cat', $cat_args );
$categories=array();
foreach($product_categories as $currentCategory)
{
    $categories[]=array(
            'Id'=>$currentCategory->slug,
            'Name'=>$currentCategory->name

    );
}

global $wp_roles;
$roles=array();

foreach($wp_roles->roles as $key=>$role)
{
    $roles[]=["Id"=>$key,"Label"=>$role['name']];
}

$taxes=[];
$tax_classes = WC_Tax::get_tax_classes();
$tax_classes[]='standard';
foreach($tax_classes as $tax_class)
{
    $classTax = WC_Tax::get_rates_for_tax_class( $tax_class );
    foreach($classTax as $tax_class)
        $taxes[]=$tax_class->tax_rate_name;

}

wp_localize_script('wcrbc-pdfbuilder-react','rednaoPDFInvoiceParams',array(
    'URL'=>RednaoWooCommercePDFInvoice::$URL,
    'Roles'=>$roles,
    'SITE_URL'=>site_url(),
    'PAGE_OPTIONS'=>$pageOptions,
    'ALREADY_EDITED'=>get_option('REDNAO_PDF_INVOICE_EDITED',false),
    'IsSuperAdmin'=>is_super_admin(),
    'IsPr'=>RednaoWooCommercePDFInvoice::IsPR(),
    'codes'=>json_encode($codes),
    'Statuses'=>$statusList,
    'Emails'=>apply_filters('wcpdfi_get_email_list',$emailList),
    'LatestNumber'=>apply_filters('wcpdfi_get_latest_invoice_number',get_option($id. '_rednao_pdf_invoice_number',0),$id),
    'AdminUrl'=>admin_url().'?page=wc_invoice_menu',
    'ExtraConditions'=>apply_filters('rnwcinv_pdf_extra_conditions',$extraConditions),
    'FontURL'=>$fontURL,
    'Newsletter'=>$newsletter,
    'AvailableFonts'=>$availableFonts,
    'FontNonce'=>\wp_create_nonce('rnwcinv_fontnonce'),
    'SaveNonce'=>\wp_create_nonce('rnwcinv_savenonce'),
    'Categories'=>$categories,
    'Taxes'=>$taxes,
    'TaxType'=>get_option('woocommerce_tax_total_display','')

));
if(!RednaoWooCommercePDFInvoice::IsPR())
{
    ?>


    <div class="bootstrap-wrapper">
        <div class="alert alert-info"><span style="margin-right: 10px;" class="fa fa-diamond"></span><strong>Custom fields, integration with other woocommerce plugins, bulk actions, printer support and much more. <a target="_blank"
                                                                                             href="http://wooinvoice.rednao.com/getit/">Get
                    the full version now</a></strong></div>
    </div>
    <?php
}
?>



<div style="position: fixed;top:0;left:0;width: 100%;height: 100%;z-index: 99999;background-color: white;">

    <div id="AppRot" style="width:100%;height: 100%;">
        <div style="width:100%;height:100%;display: flex;text-align: center;justify-content: center;align-items: center;flex-direction: column;">
            <span style="font-size: 50px" class="fa fa-spinner fa-spin"></span>
            <h1>Loading important stuffs, please wait =)</h1>
        </div>
    </div>

</div>



