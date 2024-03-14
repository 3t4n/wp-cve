<?php
if(!defined('ABSPATH'))
    die('Forbidden');

global $wpdb;
$count=$wpdb->get_var('select count(*) count from '.RednaoWooCommercePDFInvoice::$INVOICE_TABLE);




if(isset($_GET['action']))
{
    switch ($_GET['action'])
    {
        case 'add':

            if($count>0&&!RednaoWooCommercePDFInvoice::IsPR())
            {
                echo '<script type="application/javascript">alert("Sorry, you can have only one invoice template in the free version")</script>';

            }else
            {
                require_once RednaoWooCommercePDFInvoice::$DIR . 'pages/invoice_builder.php';
                return;
            }
            break;
        case 'delete':
            if(!isset($_GET['id'])){
                return;
            }


            $invoiceId=$_GET['id'];
            if(!wp_verify_nonce($_GET['nonce'],'delete_invoice_'.$_GET['id']))
            {
                echo '<script type="application/javascript">alert("Invalid nonce, please refresh your screen and try again")</script>';
            }else
            {

                $wpdb->query($wpdb->prepare('delete from ' . RednaoWooCommercePDFInvoice::$INVOICE_TABLE . ' where invoice_id=%d', $invoiceId));
                $count -= 1;
            }
            break;
        case 'edit':
            require_once RednaoWooCommercePDFInvoice::$DIR . 'pages/invoice_builder.php';
            return;
        case 'import':
            require_once RednaoWooCommercePDFInvoice::$DIR.'template-importer.php';
            break;

    }
}
if(!class_exists('WP_LIST_TABLE'))
{
    require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');
}


wp_enqueue_script('jquery');
wp_enqueue_script('wrcrbc-bootstrap-list',RednaoWooCommercePDFInvoice::$URL.'js/screens/InvoiceList.js');
wp_localize_script('wrcrbc-bootstrap-list','rednaoPDFInvoiceParamsList',array(
    'AddNewURL'=>sprintf('?page=%s&action=%s','wc_invoice_menu','add'),
    'TemplateCount'=>$count,
    'IsPR'=>RednaoWooCommercePDFInvoice::IsPR()

));

wp_enqueue_style('wcrbc-bootstrap',RednaoWooCommercePDFInvoice::$URL.'css/bootstrap/css/bootstrap.min.css');
wp_enqueue_style('wcrbc-bootstrap-theme',RednaoWooCommercePDFInvoice::$URL.'css/bootstrap/css/bootstrap-theme.min.css');




?>
    <div class="bootstrap-wrapper">
        <button class="btn btn-success createInvoice" href="#" style="margin-top: 10px;" ><span class="glyphicon glyphicon-plus" style="padding-right:10px;"></span>Create New Invoice</button>

        <button id="invoiceImport" class="btn btn-warning" href="#" style="margin-top: 10px;" ><span class="glyphicon glyphicon-import" style="padding-right:10px;"></span>Import</button>
            <form action="?page=wc_invoice_menu&action=import" method="post" enctype="multipart/form-data" id="formImporter" style="display: none;">
                <input name="files" accept=".zip" type="file" id="fileToImport"/>

            </form>


    </div>


<?php
class InvoiceList extends WP_List_Table
{
    function get_columns()
    {
        return array(
            'name'=>__('Template Name')
        );
    }

    function prepare_items()
    {
        $this->_column_headers=array($this->get_columns(),array(),$this->get_sortable_columns());
        global $wpdb;
        $query="SELECT invoice_id,name,attach_to from ".RednaoWooCommercePDFInvoice::$INVOICE_TABLE;

        if(isset($_GET['s']))
        {

            $query=$query." where name like '%".esc_sql($wpdb->esc_like(sanitize_text_field($_GET['s'])))."%'";

        }
        $invoices=$result=$wpdb->get_results($query.' order by '.sanitize_sql_orderby($this->GetOrderByName().' '.$this->GetOrderByDirection()));
        foreach($invoices as $invoice)
        {
            $invoice->name=esc_html($invoice->name);
            $attachTo=json_decode($invoice->attach_to);
            $attachToText='';
            if($attachTo!=null)
                foreach ($attachTo as $attachToItem)
                {
                    if($attachToText!='')
                        $attachToText.=',';
                    $attachToText.=esc_html($attachToItem);
                }
            $invoice->attach_to=$attachToText;
        }
        $this->items=$invoices;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'name'     => array('name',!isset($_GET['order'])?true:$this->GetOrderByDirection())
        );
        return $sortable_columns;
    }

    function GetOrderByName(){
        return 'name';
    }


    function GetOrderByDirection(){
        $orderBy='asc';
        if(isset($_GET['order']))
            $orderBy=strval($_GET['order']);
        if($orderBy!='desc'&&$orderBy!='asc')
            $orderBy='asc';

        return $orderBy;

    }
    function column_default($item, $column_name)
    {
        return $item->$column_name;
    }

    function column_name($item) {
        $actions = array(
            __('edit')      => sprintf('<a href="?page=%s&id=%s&action=%s">Edit</a>','wc_invoice_menu',$item->invoice_id,'edit'),
            __('delete')    => sprintf('<a href="javascript:(function(event){confirm(\'Are you sure you want to delete the form?\')?(window.location=\'?page=%s&id=%s&action=%s&nonce=%s\'):\'\'; return false;})()">Delete</a>','wc_invoice_menu',$item->invoice_id,'delete',wp_create_nonce('delete_invoice_'.$item->invoice_id))
        );

        return sprintf('%1$s %2$s', $item->name, $this->row_actions($actions) );
    }
}


echo '<form method="get">';
echo "<input type='hidden' name='page' value='wc_invoice_menu'/>";
$invoiceList=new InvoiceList();
$invoiceList->prepare_items();
$invoiceList->search_box('Search Template','Name');
$invoiceList->display();
echo '</form>';
?>