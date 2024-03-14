<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 
$currentStats = json_decode(get_option('wpa_stats'), true);  
?>
<br/>
<table class="wp-list-table widefat fixed bookmarks">
    <?php
    /*
    <thead>
    <tr>
        <th colspan="5"><strong>Quick Stats</strong></th>
    </tr>
    </thead>
    */ ?>
    <thead>
        <tr>
            <th><strong>Source</strong></th>
            <th><strong>Today</strong></th>
            <th><strong>This Week</strong></th>
            <th><strong>This Month</strong></th>
            <th><strong>All Time</strong></th>        
        </tr>
    <thead>
     <tbody>
        <?php         
        if (!empty($currentStats)){
            foreach ($currentStats as $source=>$statData): ?>
                <tr>
                    <td><strong><?php echo ucfirst($source); ?></strong></td>
                    <td><?php echo @wpa_check_date($statData['today']['date'],'today')?$statData['today']['count']:'0'; ?></td>
                    <td><?php echo @wpa_check_date($statData['week']['date'],'week')?$statData['week']['count']:'0'; ?></td>
                    <td><?php echo @wpa_check_date($statData['month']['date'],'month')?$statData['month']['count']:'0'; ?></td>
                    <td><?php echo $statData['all_time']; ?></td>        
                </tr>
            <?php endforeach;
        } else { ?>
            <tr><td colspan="5">No Record Found</td></tr>
        <?php } ?>

    </tbody>
</table><br/>
<br/>
<?php /*
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
    <tr>
    	<th colspan="2"><strong>Visualization </strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
    	<td colspan="2">
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                  google.charts.load('current', {'packages':['bar']});
                  google.charts.setOnLoadCallback(drawChart);

                  function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                      ['Source', 'Today', 'This Week','This Month', 'All Time'],
                      <?php foreach ($currentStats as $source=>$statData): ?>
                        [
                            '<?php echo ucfirst($source); ?>',
                            <?php echo wpa_check_date($todayDate,'today')?$statData['today']['count']:'0'; ?>,
                            <?php echo wpa_check_date($weekDate,'week')?$statData['week']['count']:'0'; ?>,
                            <?php echo wpa_check_date($monthDate,'month')?$statData['month']['count']:'0'; ?>,
                            <?php echo $statData['all_time']; ?>
                        ],
                      <?php endforeach;?>
                    ]);

                    var options = {
                      chart: {
                        title: 'Spam blocked by WP Armour',
                        hAxis: {title: 'Source',  titleTextStyle: {color: 'red'}},

                      },
                      colors:['#bedcf5','#75b5e9','#2c8ddd','#19609b']
                    };

                    var chart = new google.charts.Bar(document.getElementById('wpae_chart_div'));

                    chart.draw(data, google.charts.Bar.convertOptions(options));
                  }
                </script>

                <div id="wpae_chart_div" style="width:100%; height:400px; margin-top:15px; margin-bottom:10px;">
                </div>

        </td>
    </tr>
    </tbody>
</table><br/>*/ ?>