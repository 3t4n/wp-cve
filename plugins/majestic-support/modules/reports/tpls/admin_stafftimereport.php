<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('majesticsupport-jquery-ui-css', MJTC_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');
$id = MJTC_request::MJTC_getVar('id');
wp_enqueue_script('majesticsupport-google-charts', MJTC_PLUGIN_URL . 'includes/js/google-charts.js');
wp_register_script( 'majesticsupport-google-charts-handle', '' );
wp_enqueue_script( 'majesticsupport-google-charts-handle' );
$majesticsupport_js ="
    jQuery(document).ready(function ($) {
        $('.custom_date').datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
    google.load('visualization', '1', {packages:['corechart']});
    google.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', '". esc_html(__('Dates','majestic-support'))."');
        data.addColumn('number', '". esc_html(__('Minutes','majestic-support'))."');
        data.addRows([
            ". majesticsupport::$_data['line_chart_json_array']."
        ]);

        var options = {
          colors:['#159667','#2168A2','#f39f10','#B82B2B','#3D355A'],
          curveType: 'function',
          legend: { position: 'bottom' },
          pointSize: 6,
          // This line will make you select an entire row of data at a time
          focusTarget: 'category',
          chartArea: {width:'90%',top:50}
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
    }
";
wp_add_inline_script('majesticsupport-google-charts-handle',$majesticsupport_js);
$majesticsupport_js ="
    function resetFrom(){
        document.getElementById('date_start').value = '';
        document.getElementById('date_end').value = '';
        document.getElementById('majesticsupportform').submit();
    }
";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
MJTC_message::MJTC_getMessage();
?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
    <span class="mjtc-adminhead-title"> <a class="jsanchor-backlink" href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport_reports&mjslay=staffdetailreport&id='.esc_attr($id)));?>"><img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/back-icon.png" /></a> <span class="jsheadtext"><?php echo esc_html(__("Report By Agent", 'majestic-support')) ?></span>
    </span>
    <a href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport_reports&mjslay=staffreport&date_start='.esc_attr(majesticsupport::$_data['filter']['date_start']).'&date_end='.esc_attr(majesticsupport::$_data['filter']['date_end']))); ?>"></a>
    <form class="mjtc-filter-form mjtc-report-form" name="majesticsupportform" id="majesticsupportform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_reports&mjslay=stafftimereport&id=".$id),"reports")); ?>">
        <?php
            $curdate = date_i18n('Y-m-d');
            $enddate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
            $date_start = !empty(majesticsupport::$_data['filter']['date_start']) ? majesticsupport::$_data['filter']['date_start'] : $curdate;
            $date_end = !empty(majesticsupport::$_data['filter']['date_end']) ? majesticsupport::$_data['filter']['date_end'] : $enddate;
        	echo wp_kses(MJTC_formfield::MJTC_text('date_start', $date_start, array('class' => 'custom_date','placeholder' => esc_html(__('Start Date','majestic-support')))), MJTC_ALLOWED_TAGS);
        	echo wp_kses(MJTC_formfield::MJTC_text('date_end', $date_end, array('class' => 'custom_date','placeholder' => esc_html(__('End Date','majestic-support')))), MJTC_ALLOWED_TAGS);
        	echo wp_kses(MJTC_formfield::MJTC_hidden('MS_form_search', 'MS_SEARCH'), MJTC_ALLOWED_TAGS);
    	?>
        <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('go', esc_html(__('Search', 'majestic-support')), array('class' => 'button')), MJTC_ALLOWED_TAGS); ?>
    	<?php echo wp_kses(MJTC_formfield::MJTC_button('reset', esc_html(__('Reset', 'majestic-support')), array('class' => 'button', 'onclick' => 'resetFrom();')), MJTC_ALLOWED_TAGS); ?>
    </form>
    <span class="mjtc-admin-subtitle"><?php echo esc_html(majesticsupport::$_data[0]['staffname']); ?></span>
    <div id="curve_chart" style="height:400px;width:98%; "></div>
  </div>
</div>
