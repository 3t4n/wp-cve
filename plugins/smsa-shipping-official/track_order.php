<?php

if(!isset($_GET['awb_no']))
{
    ?>
<center><h3><?php echo esc_html('Visit to order page to track the order');?></h3></center>;

<?php 
}
else
{


    $sett = get_option('woocommerce_smsa-express-integration_settings');
$body = array(
    'accountNumber' => $sett['smsa_account_no'],
    'username' => $sett['smsa_username'],
    'password' => $sett['smsa_password'],
);

$args = array(
    'body' => json_encode($body) ,
    'timeout' => '5',
    'redirection' => '5',
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(
        'Content-Type' => 'application/json; charset=utf-8'
    ) ,
    'cookies' => array() ,
);
$re = wp_remote_post('https://smsaopenapis.azurewebsites.net/api/Token', $args);

$resp = json_decode($re['body']);

if (isset($resp->token))
{
    $url = 'https://smsaopenapis.azurewebsites.net/api/Shipment/Track?AWB=' .sanitize_text_field($_GET['awb_no']). '&Language=EN';
    $args = array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $resp->token
        )
    );
    $response = wp_remote_get($url, $args);
    $arr = json_decode($response['body']);
    
    if (isset($arr->trackingDetailsList))
    {
?> 
                    <div class="table_con">
                     <h2><?php echo esc_html('Tracking Information for WayBill Number '.sanitize_text_field($_GET['awb_no'])); ?></h2> 
                    <table class="ord_table1">
                    <tr>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Activity</th>
                    </tr>

               
                <?php
        foreach ($arr->trackingDetailsList as $roww)
        {
            echo '<tr>';
            echo '<td>' . $roww->office . ',' . $roww->countryCode . '</td>';
            echo '<td>' . $roww->eventTime . '</td>';
            echo '<td>' . $roww->eventDesc . '</td>';
            echo '</tr>';
        }
        echo '</table></div>';
    }

    else
    {
        ?>
       <h3><center><?php echo esc_html('Still Order Not Picked-Up by SMSA.');?></center></h3>
      
       <?php exit;
    }

}
else
{
    ?>
        <h3><?php echo esc_html('Please check your SMSA account credentials.');?></h3>";
    <?php
    exit;

}


}
?>
