<style>
    .tooltip {
  position: relative;
  display: inline-block;
  /*border-bottom: 1px dotted black;*/
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 400px;
  height: 400px;
  background-color: white;
  color: #fff;
  text-align: center;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
  margin-top: 20px;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}
</style>
<?php
/*
Plugin Name: SERPed.net
Plugin URI: http://serpedagency.com/serped-net.zip
Description: The SERPed.net plugin provides powerful SEO features to help you manage your sites, pages and internal links, set up analytics code, embed optin forms, display page metrics and more.
Version: 1.0
Author: Colin klinkert
License: GPLv2 or later
*/
include 'input.php';
if (isset($_GET['debug'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
}
if (isset($_POST['icp_tracking_code'])) {
    if(!wp_verify_nonce( $_POST['_wpnonce'], 'icp-tracking-852' )){
        echo '<script type="text/javascript"> window.location = \'' . admin_url('admin.php?page=icp_link_projects') . '\'; </script>';
        die();
    }  
    $res = srpd_update_tracking_code();
    if ($res == '1')
        $msg = '<div id="icp_closeNotification" class="icp_successMsg">Your changes have been successfully saved.</div>';
    else
        $msg = '<div id="icp_closeNotification" class="icp_errorMsg">Your changes have not been successfully saved.</div>';
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
    <tr>
        <td width="30"><img src="<?= srpd_root_path() ?>img/trackingcode.png" width="30" height="30" /></td>
        <td><h1>Web Analytics</h1></td>
    </tr>
</table>
<?php
function srpd_unixToMinute($sec) {
    $minuteP = $sec / 60;
    $minute = floor($minuteP);
    $minute = empty($minute) ? "N/A" : $minute;
    $sec = $sec - ($minute * 60);
    $sec = empty($sec) ? 'N/A' : number_format($sec,2);
    return $minute . " min " . $sec . "s";
}
if (isset($msg)) {
    echo $msg;
    ?>
    <script type="text/javascript">
        var $ = jQuery.noConflict();
        $(document).ready(function () {
            setTimeout(function () {
                $('#icp_closeNotification').fadeOut();
            }, 5000);
        });       
    </script>
<?php } ?>
<div id="icp_content">
    <h1>Tracking Code</h1>
    <p>Here is where you can paste your <a href="<?= urldecode($user['main_url']) ?>/login.php?return=analytics.php" target="_blank"> Web Analytics Site ID </a><span class="tooltip">(<strong>?</strong>)<span class="tooltiptext"><img width="400px" height="400px" src="<?= srpd_root_path() ?>img/site-id-tooltip.png"></span></span>. The plugin will automatically add it to all your posts and pages and start tracking traffic. Don't forget to click the 'Save Changes' button before exiting.</p>
    <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr>
            <td width="40%" valign="top">
                <form id="form1" name="form1" method="post" action="">
                    <input type="hidden" name="_wpnonce" value="<?=wp_create_nonce('icp-tracking-852')?>">
                    <p>
                            <?php 
                            $siteID = srpd_get_tracking_code();
                            
                            if (strlen($siteID) > 0 && (strpos($siteID, 'innercircleassets.com') > -1 || strpos($siteID, 'serped.net') > -1 || strpos($siteID, 'serpd.co') > -1 || strpos($siteID, 'serpd.org') > -1 || strpos($siteID, 'serpd.ws') > -1 || strpos($siteID, 'serped.co') > -1 || strpos($siteID, 'clickster.info') > -1 || strpos($siteID, 'trackingz.com') > -1 || strpos($siteID, 'tracktrack.co') > -1)) {
                                preg_match('/[\'"]setSiteId[\'"]\, (\d*)/', $siteID, $matches);
                                if (is_numeric($matches[1])) {
                                    $siteID = $matches[1];
                                }
                            }
                            ?>
                            <input type="number" name="icp_tracking_code" value="<?= $siteID ?>" id="icp_tracking_code" style="width:100%; min-width:500px; font-size:16px;padding: 20px;" placeholder="Paste your site ID here and click the 'Save Changes' button."> 
                    </p>
                    <p><input type="submit" name="button" id="button" value="Save Changes" class="button" /></p>
                </form>
            </td>
            <td valign="top">
                <?php
                    if (is_numeric($siteID)) {
                        ?>
                <canvas id="chart-stats" height="100"></canvas>
                       <?php
                                $dateImage = date('Y-m-d', strtotime('-30days')).','.date('Y-m-d');
                                $result = wp_remote_get('https://innercircleassets.com/analytics/index.php?module=API&format=JSON&method=VisitsSummary.get&language=en&idSite='.$siteID.'&period=day&date=' .$dateImage. '&token_auth=39af951ce498acfd1789a84594f51aa7');
                                $result = wp_remote_retrieve_body($result);
                                $result = json_decode($result,true);
                                
                                $series = array();
                                $nb_visits = $nb_uniq_visitors = $nb_users = $nb_actions = $nb_visits_converted = $bounce_count = array();
                                $sum_visit_length = $max_actions = $bounce_rate = $nb_actions_per_visit = $savg_time_on_site = array();
                                if(!empty($result) && is_array($result)){
                                       foreach($result as $key=>$val){
                                            if(!empty($val)){
                                                $series[0][] = (array('x'=> $key, 'y'=> (int) $val['nb_visits']));
                                                $series[1][] = (array('x'=> $key, 'y'=> (int) $val['nb_uniq_visitors']));
                                                $series[2][] = (array("x"=> $key, "y"=> (int) $val['nb_users']));
                                                $series[3][] = (array("x"=> $key, "y"=> (int) $val['bounce_count']));
                                                $series[4][] = (array("x"=> $key, "y"=> (int) $val['max_actions']));
                                                $series[5][] = (array("x"=> $key, "y"=> (int) $val['bounce_rate']));
                                                $series[6][] = (array("x"=> $key, "y"=> (int) $val['nb_actions_per_visit']));
                                                $series[7][] = (array("x"=> $key, "y"=> (int) $val['avg_time_on_site']));
                                            }
                                        }
                                }
                             
                                $report = wp_remote_get('https://innercircleassets.com/analytics/index.php?module=API&format=JSON&method=API.get&language=en&idSite='.$siteID.'&period=range&date=' .$dateImage. '&token_auth=39af951ce498acfd1789a84594f51aa7');
                                $report = wp_remote_retrieve_body($report);
                                $report = json_decode($report,true);
                                if(!empty($report) && is_array($report)){
                                    $token = '39af951ce498acfd1789a84594f51aa7';

                                    $api_url = 'https://members.serped.net/plugin/plugin.serped.analytics.php';
                                    $dataArray = array(
                                        'method' => 'POST',
                                        'body' => array(
                                            'sid' => $siteID,
                                            'date' => $dateImage,
                                            'plugin_key' => srpd_get_plugin_key(),
                                        )
                                    );
                            
                                    $result = wp_remote_post($api_url, $dataArray);
                                    $data = wp_remote_retrieve_body($result);
                                    $data = json_decode($data, true);
                                    $get_imgs = $data['serp_imgs'];

                                    $html = '<div>'
                                            . '<ul>';
                                    $html .= '<li class="padding-top-10" style="padding-bottom:15px"><img src="data:image/png;base64,'.$get_imgs['nb_visits_and_nb_uniq_visitors'].'" style="vertical-align: middle;" />  <span style="margin-left: 10px;"> <br><b>' . $report['nb_visits'] . '</b> Visits, <b>' . $report['nb_uniq_visitors'] . '</b> Unique Visitors</span></li>';
                                    $html .= '<li class="padding-top-10" style="padding-bottom:15px"><img src="data:image/png;base64,'.$get_imgs['avg_time_on_site'].'" style="vertical-align: middle;" /> <span style="margin-left: 10px;"><br><b>' . srpd_unixToMinute($report['avg_time_on_site']) . '</b> Average Visit Duration</span></li>'
                                            . '<li class="padding-top-10" style="padding-bottom:15px"><img src="data:image/png;base64,'.$get_imgs['bounce_rate'].'" style="vertical-align: middle;" /> <span style="margin-left: 10px;"><br><b>' . $report['bounce_rate'] . '</b> Visits have bounced (left the website after one page)</span></li>'
                                            . '<li class="padding-top-10" style="padding-bottom:15px"><img src="data:image/png;base64,'.$get_imgs['nb_actions_per_visit'].'" style="vertical-align: middle;" /> <span style="margin-left: 10px;"><br><b>' . $report['nb_actions_per_visit'] . '</b> Actions Per Visit </span></li>'
                                            . '<li class="padding-top-10" style="padding-bottom:15px"><img src="data:image/png;base64,'.$get_imgs['avg_time_on_site'].'" style="vertical-align: middle;" /> <span style="margin-left: 10px;"><br><b>' . (number_format($report['avg_time_generation'],2) + 0) . 's</b> average generation time</span></li>'
                                            . '<li class="padding-top-10" style="padding-bottom:15px"><img src="data:image/png;base64,'.$get_imgs['nb_pageviews_and_nb_uniq_pageviews'].'" style="vertical-align: middle;" /> <span style="margin-left: 10px;"><br><b>' . $report['nb_pageviews'] . '</b> pageviews, <b>' . $report['nb_uniq_pageviews'] . '</b> unique pageviews</span></li>'
                                            . '<li class="padding-top-10" style="padding-bottom:15px"><img src="data:image/png;base64,'.$get_imgs['nb_searches_and_nb_keywords'].'" style="vertical-align: middle;" /> <span style="margin-left: 10px;"><br><b>' . $report['nb_searches'] . '</b> total searches on your website, <b>' . $report['nb_keywords'] . '</b> unique keywords</span></li>'
                                            . '<li class="padding-top-10" style="padding-bottom:15px"><img src="data:image/png;base64,'.$get_imgs['nb_downloads_and_nb_uniq_downloads'].'" style="vertical-align: middle;" /> <span style="margin-left: 10px;"><br><b>' . $report['nb_downloads'] . '</b> Downloads, <b>' . $report['nb_uniq_downloads'] . '</b> unique downloads</span></li>'
                                            . '<li class="padding-top-10" style="padding-bottom:15px"><img src="data:image/png;base64,'.$get_imgs['nb_outlinks_and_nb_uniq_outlinks'].'" style="vertical-align: middle;" /> <span style="margin-left: 10px;"><br><b>' . $report['nb_outlinks'] . '</b> Outlinks, <b>' . $report['nb_uniq_outlinks'] . '</b> unique outlinks</span></li>'
                                            . '<li class="padding-top-10" style="padding-bottom:15px"><img src="data:image/png;base64,'.$get_imgs['max_actions'].'" style="vertical-align: middle;" /> <span style="margin-left: 10px;"><br><b>' . $report['max_actions'] . '</b> Max actions in one visit</span></li>'
                                            . '</ul>'
                                            . '</div>';
                                    $html .= '<div class="clearfix"></div>';
                                    echo $html;
                                    
                                }
                                
                            }
                        ?>
            </td>
        </tr>
    </table>
</div>
<?php 
wp_enqueue_script( 'my-custom-script', srpd_root_path(). 'js/Moment.min.js', array( 'jquery' ) );
wp_enqueue_script( 'my-custom-script1', srpd_root_path(). 'js/Chart.min.js', array( 'jquery' ) );   
?>
<script>
var $ = jQuery.noConflict();
    $(document).ready(function(){
        var data = <?= json_encode($series) ?>;
          var ctx = document.getElementById('chart-stats').getContext('2d');
          var chart = new Chart(ctx, {
            type: 'line',
            data: { datasets:  [
                    {
                        label: 'Visitors',
                        fill: false,
                        data: data[0],
                        borderColor: '#D4291F',
                        backgroundColor:'#D4291F'
                    },{
                        label: 'Uniq visitors',
                        fill: false,
                        data: data[1],
                        borderColor: '#ffc000',
                        backgroundColor:'#ffc000',
                        hidden:true
                    },{
                        label: 'Users',
                        fill: false,
                        data: data[2],
                        borderColor: '#26a62c',
                        backgroundColor:'#26a62c',
                        hidden:true
                    },{
                        label: 'Bounce Count',
                        fill: false,
                        data: data[3],
                        borderColor: '#3c3c45',
                        backgroundColor:'#3c3c45',
                        hidden:true
                    },{
                        label: 'Max Actions In One Visit',
                        fill: false,
                        data: data[4],
                        borderColor: '#ff1e6d',
                        backgroundColor:'#ff1e6d',
                        hidden:true
                    },{
                        label: 'Bounce Rate',
                        fill: false,
                        data: data[5],
                        borderColor: '#ff7302',
                        backgroundColor:'#ff7302',
                        hidden:true
                    },{
                        label: 'Actions Per Visit',
                        fill: false,
                        data: data[6],
                        borderColor: '#cd29f6',
                        backgroundColor:'#cd29f6',
                        hidden:true
                    },{
                        label: 'Avg. Visit Duration (in sec)',
                        fill: false,
                        data: data[7],
                        borderColor: '#846bb9',
                        backgroundColor:'#846bb9',
                        hidden:true
                    }
                ]},
            options: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 20,
                        padding:10
                    }
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            displayFormats: {
                                month: 'MMM YYYY'
                            }
                        }
                    }]
                }
            }
          });
      });
</script>
   
