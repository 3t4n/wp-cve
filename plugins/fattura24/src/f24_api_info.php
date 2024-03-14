<?php
/**
* Questo file è parte del plugin WooCommerce v3.x di Fattura24
* Autore: Fattura24.com <info@fattura24.com> 
*
* Descrizione: gestisce la tab "API calls" della schermata di impostazioni
* 
*/
namespace fattura24;

if (!defined('ABSPATH'))
    exit;

function fatt_24_show_api_info() {
    $apiKey = get_option(FATT_24_OPT_API_KEY);
    $command = 'GetCallLog';
    $result = fatt_24_api_call($command, array('apiKey' => $apiKey, 'page'=> 1, 'pageSize'=> 50), FATT_24_API_SOURCE);
    $customPagHTML = '';
    $totalPerPage = 31;
    $page = 1;
    $row = 1;
    $response = is_array($result)? json_encode($result) : simplexml_load_string($result);
    $totalRecord = 0;
    if (is_object($response)) {
        $totalRecord = $response->totalRecord;
    }

    
    $over500CallsMsg = __('You overcomed API calls daily limit!', 'fattura24'); // avertenza di limite oltrepassato
    $totalCallsMsg = __('Last 24 hours total calls : ', 'fattura24');
    $textColor = $totalRecord >= 500 ? 'red' : ($totalRecord > 450  ? 'orange' : 'green');
    $totalRecordColor = '<span style="color:' .$textColor . '">'.  $totalRecord . '</span>';
    $totalPage = ceil($totalRecord / $totalPerPage);
    
    ?>
 

    <div class='wrap'>
    <h2></h2>
    <?php fatt_24_get_link_and_logo(__('', 'fatt-24-api-info')); 
        echo fatt_24_build_nav_bar();
    ?>
        <div>
            <table>
                <tr>
                    <td style="vertical-align: top;">
                        <div style="padding:10px; font-size:150%;"><strong><?php _e('F24 API call logs stats', 'fattura24') ?></strong></div>
                        <div style="padding:10px; font-size:120%;"><?php _e($totalCallsMsg); echo ' '; _e($totalRecordColor);  
                                if ($totalRecord >= 500) {
                                    echo '<span style="color:red;"> ' .  $over500CallsMsg . '</span>';
                                }
                        ?></div>
                            <div style="padding:10px; font-size:120%;"><?php _e('Here below you find fattura24 API call logs for your key. <br />
                            In "Command" column you find the command you sent, in "Source" you find the starting point of the call. <br /> 
                            For instance: F24-Woo 4.8.5 means that you used fattura24 WooCommerce plugin version 4.8.5 .', 'fattura24');
                            ?></div><br />

                                <table class='wp-list-table widefat fixed striped pages' width="60">
                                <thead>
                                        <tr>
                                            <th class="manage-column ss-list-width" width="20%"><?php _e('Date', 'fattura24'); ?></th>
                                            <th class="manage-column ss-list-width" width="20%"><?php echo __('Command', 'fattura24'); ?></th>
                                            <th class="manage-column ss-list-width" width="20%"><?php _e('Source', 'fattura24'); ?></th>
                                            <th style="text-align:center;" class="manage-column ss-list-width" width="20%"><?php _e('Response time (ms)', 'fattura24'); ?></th>
                                            <th style="clear:both;" class="manage-column wp-list-table widefat" width="20%">&nbsp;</th>
                                        
                                        </tr>
                                    </thead>
       

     
<?php
  
    if(is_object($response)) {
        foreach ($response->log as $log) {
            echo '<tr>';
            echo '<td>' . $log->date . '</td>';
            echo '<td>' . $log->serviceName . '</td>';
            echo '<td>'. $log->source . '</td>';
            echo '<td style="text-align:center;">' . $log->deliveryTime . '</td>';
            echo '<td>&nbsp;</td>';
            echo '</tr>';
            $row++;
        }   
        //echo $totalPage;
        /*if ($totalPage > 1) {
            $customPagHTML     =  '</tbody></table><div><span>Page '.$page.' of '.$totalPage.'</span> '.paginate_links(array(
               'base' => add_query_arg($page, '%#%'),
               'format' => '',
               'prev_text' => __('&laquo;'),
               'next_text' => __('&raquo;'),
               'total' => $totalPage,
               'current' => $page
               )).'</div>';
               if ($row == $totalPerPage) {
                    $page++;
                    //echo '</tbody>';
                    //echo '</table>';
                    echo $customPagHTML;
                }
               }*/
            //}
        echo '</tbody>';
        echo '</table>';
        echo "<td style='width:250px; vertical-align: top;'>" . fatt_24_infobox() . "</td>";
        echo '</tr>';
        echo '</table>';
        echo '</div>';
    } else {
        // messaggio di errore se la connessione al server non funziona
        if(empty($result)) {
            $message = __('WARNING: reading total API calls was not possible', 'fattura24');
        } else {
            $message = __('WARNING: connection to Fattura24 API failed, please contact our technical service', 'fattura24');
        }
        echo '<tr>';
        echo '<td style="text-align:center;" colspan="5">' . $message . '</td>';
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
        echo "<td style='vertical-align: top;'>" . fatt_24_infobox() . "</td>";
        echo '</tr>';
        echo '</table>';
        echo '</div>';
    }
}