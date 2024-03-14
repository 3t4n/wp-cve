<?php
if (!defined('ABSPATH'))
    exit;

$siteurl = site_url();
$siteurl = esc_url($siteurl);
?>


<table  width='100%' cellpadding='10' style='margin-top:20px' class='wp-list-table widefat  striped '>
    <thead>
        <th width='50'>S.No</th>
        <th width='250px'>Status</th>
        <th>Logs</th>

    </thead>
    <tbody id='the-list'>
    <?php
            global $wpdb;
            $get_shortcode_id = $wpdb->get_results("select * from zcf_submitlogs ORDER BY id DESC;");
            $i=1;
            foreach ($get_shortcode_id as $value) {

        ?>
        <tr>
        <td><?php echo esc_html($i);?></td>
        <td><?php  echo esc_html($value->crmsubmitlogStatus); ?></td>
         <td><?php  echo esc_html($value->crmsubmitlogDescribtion); ?></td>
     </tr>
    <?php $i++;?>
<?php } ?>
    </tbody>
</table>
