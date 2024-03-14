<?php
if(!defined('ABSPATH'))
    die('Forbidden');

global $wpdb;
$count=$wpdb->get_var('select count(*) count from '.RednaoWooCommercePDFInvoice::$CUSTOM_FIELDS_TABLE);






if(isset($_GET['action']))
{
    switch ($_GET['action'])
    {
        case 'add':

            if($count>3&&!RednaoWooCommercePDFInvoice::IsPR())
            {
                echo '<script type="application/javascript">alert("Sorry, you can have only three custom fields in the free version")</script>';

            }else
            {
                require_once RednaoWooCommercePDFInvoice::$DIR . 'pr/pages/custom_field_builder.php';
                return;
            }
            break;
        case 'delete':
            if(!isset($_GET['id'])){
                return;
            }
            $id=$_GET['id'];
            $wpdb->query($wpdb->prepare('delete from '.RednaoWooCommercePDFInvoice::$CUSTOM_FIELDS_TABLE.' where custom_field_id=%d',$id));
            $count-=1;
            break;
        case 'edit':
            require_once RednaoWooCommercePDFInvoice::$DIR . 'pr/pages/custom_field_builder.php';
            return;

    }
}
if(!class_exists('WP_LIST_TABLE'))
{
    require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');
}


wp_enqueue_script('jquery');
wp_enqueue_script('wrcrbc-bootstrap-list',RednaoWooCommercePDFInvoice::$URL.'js/screens/InvoiceList.js');
wp_localize_script('wrcrbc-bootstrap-list','rednaoPDFInvoiceParamsList',array(
    'AddNewURL'=>sprintf('?page=%s&action=%s',$_REQUEST['page'],'add'),
    'TemplateCount'=>$count,
    'IsPR'=>RednaoWooCommercePDFInvoice::IsPR()

));

wp_enqueue_style('wcrbc-bootstrap',RednaoWooCommercePDFInvoice::$URL.'css/bootstrap/css/bootstrap.min.css');
wp_enqueue_style('wcrbc-bootstrap-theme',RednaoWooCommercePDFInvoice::$URL.'css/bootstrap/css/bootstrap-theme.min.css');




?>
    <div class="bootstrap-wrapper">
        <button class="btn btn-success createInvoice" href="#" style="margin-top: 10px;" ><span class="glyphicon glyphicon-plus" style="padding-right:10px;"></span>Create New Custom Field</button>
    </div>


<?php
class InvoiceList extends WP_List_Table
{
    function get_columns()
    {
        return array(
            'custom_field_name'=>__('Field Name')
        );
    }

    function prepare_items()
    {
        $this->_column_headers=array($this->get_columns(),array(),$this->get_sortable_columns());
        global $wpdb;
        $query="SELECT custom_field_id,custom_field_name from ".RednaoWooCommercePDFInvoice::$CUSTOM_FIELDS_TABLE;
        if(isset($_GET['s']))
        {
            $query=$query." where custom_field_name like '%".esc_sql($wpdb->esc_like(sanitize_text_field($_GET['s'])))."%'";
        }

        $fields=$wpdb->get_results($query.' order by '.sanitize_sql_orderby($this->GetOrderByName().' '.$this->GetOrderByDirection()));
        foreach($fields as $field)
        {
            $field->custom_field_name=esc_html($field->custom_field_name);
        }
        $this->items=$fields;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'custom_field_name'     => array('custom_field_name',!isset($_GET['order'])?true:$this->GetOrderByDirection())
        );
        return $sortable_columns;
    }

    function GetOrderByName(){
        return 'custom_field_name';
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

    function column_custom_field_name($item) {
        $actions = array(
            __('edit')      => sprintf('<a href="?page=%s&id=%s&action=%s">Edit</a>',$_REQUEST['page'],$item->custom_field_id,'edit'),
            __('delete')    => sprintf('<a href="javascript:(function(event){confirm(\'Are you sure you want to delete the field?\')?(window.location=\'?page=%s&id=%s&action=%s\'):\'\';event.returnValue=false; return false;})()">Delete</a>',$_REQUEST['page'],$item->custom_field_id,'delete')
        );

        return sprintf('%1$s %2$s', $item->custom_field_name, $this->row_actions($actions) );
    }
}

echo '<form method="get">';
echo "<input type='hidden' name='page' value='invoice-builder-custom-fields'/>";
$invoiceList=new InvoiceList();
$invoiceList->prepare_items();
$invoiceList->search_box('Search Custom Field','custom_field_name');
$invoiceList->display();
echo '</form>';
?>