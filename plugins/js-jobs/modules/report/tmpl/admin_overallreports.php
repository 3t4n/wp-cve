<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php 
    $msgkey = JSJOBSincluder::getJSModel('report')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Overall Reports', 'js-jobs'); ?>
    </span>
    <?php
        do_action('admin_enqueue_scripts');
    ?>
    <div id="charts">
        <div id="curve_chart"></div>		
        <div class="boxeswrapper">
            <div class="box">
                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/report/job.png" />
                <span class="number"><?php echo esc_html(jsjobs::$_data['totaljobs']); ?></span>
                <span class="desc"><?php echo __('Total jobs', 'js-jobs'); ?></span>
            </div>
            <div class="box">
                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/report/resume.png" />
                <span class="number"><?php echo esc_html(jsjobs::$_data['totalresume']); ?></span>
                <span class="desc"><?php echo __('Total resume', 'js-jobs'); ?></span>				
            </div>
            <div class="box">
                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/report/company.png" />
                <span class="number"><?php echo esc_html(jsjobs::$_data['totalcompany']); ?></span>
                <span class="desc"><?php echo __('Total companies', 'js-jobs'); ?></span>				
            </div>
            <div class="box">
                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/report/appliedresume.png" />
                <span class="number"><?php echo esc_html(jsjobs::$_data['totalappliedresume']); ?></span>
                <span class="desc"><?php echo __('Total applied resume', 'js-jobs'); ?></span>
            </div>
        </div>
        <div class="categorycharts">
            <span class="title one">
                <?php echo __('By Categories', 'js-jobs'); ?>
            </span>
            <div class="chartwrap">
                <span class="title"><?php echo __('Jobs', 'js-jobs'); ?></span>
                <div id="catbar1"></div>
            </div>
            <div class="chartwrap">
                <span class="title"><?php echo __('Resume', 'js-jobs'); ?></span>
                <div id="catbar2"></div>
            </div>
            <div class="chartwrap">
                <span class="title"><?php echo __('Companies', 'js-jobs'); ?></span>
                <div id="catpie"></div>
            </div>
        </div>
        <div class="categorycharts">
            <span class="title two">
                <?php echo __('By Cities', 'js-jobs'); ?>
            </span>
            <div class="chartwrap">
                <span class="title"><?php echo __('Jobs', 'js-jobs'); ?></span>
                <div id="citybar1"></div>
            </div>
            <div class="chartwrap">
                <span class="title"><?php echo __('Companies', 'js-jobs'); ?></span>
                <div id="citypie"></div>
            </div>
            <div class="chartwrap">
                <span class="title"><?php echo __('Resume', 'js-jobs'); ?></span>
                <div id="citybar2"></div>
            </div>
        </div>
        <div class="categorycharts">
            <span class="title three">
                <?php echo __('By Types', 'js-jobs'); ?>
            </span>
            <div class="chartwrap type">
                <span class="title"><?php echo __('Jobs', 'js-jobs'); ?></span>
                <div id="jobtypebar1"></div>
            </div>
            <div class="chartwrap type">
                <span class="title"><?php echo __('Resume', 'js-jobs'); ?></span>
                <div id="jobtypebar2"></div>
            </div>
        </div>
    </div>
</div>
</div>
<script >
            google.charts.load('current', {'packages':['corechart']});
            google.setOnLoadCallback(drawChartTop);
            function drawChartTop() {
            var data = new google.visualization.DataTable();
                    data.addColumn('date', '<?php echo __('Dates', 'js-jobs'); ?>');
                    data.addColumn('number', '<?php echo __('Jobs', 'js-jobs'); ?>');
                    data.addColumn('number', '<?php echo __('Resume', 'js-jobs'); ?>');
                    data.addColumn('number', '<?php echo __('Company', 'js-jobs'); ?>');
                    data.addColumn('number', '<?php echo __('Applied resume', 'js-jobs'); ?>');
                    data.addRows([
                    <?php echo wp_kses_post(jsjobs::$_data['line_chart_json_array']); ?>
                    ]);
                    var options = {
                    colors:['#1EADD8', '#179650', '#D98E11', '#5F3BBB', '#DB624C'],
                            curveType: 'function',
                            legend: { position: 'bottom' },
                            pointSize: 6,
                            height: 400,
                            width: '100%',
                            // This line will make you select an entire row of data at a time
                            focusTarget: 'category',
                            chartArea: {width:'90%', top:50}
                    };
                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                    chart.draw(data, options);
            }

    google.setOnLoadCallback(drawChartCatBar1);
            function drawChartCatBar1(){
            var data = google.visualization.arrayToDataTable([
                    ['<?php echo __('Categories', 'js-jobs'); ?>', '<?php echo __('Jobs', 'js-jobs'); ?>', { role: 'style' }, { role: 'annotation' } ],
                <?php echo wp_kses_post(jsjobs::$_data['catbar1']); ?>
            ]);
                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, 1,
                    { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                            2]);
                    var options = {
                    title: "",
                            width:'100%',
                            height: 300,
                            bar: {groupWidth: "80%"},
                            legend: { position: "none" },
                            chartArea: {width:'90%', top:50}
                    };
                    var chart = new google.visualization.BarChart(document.getElementById("catbar1"));
                    chart.draw(view, options);
            }

    google.setOnLoadCallback(drawChartCatBar2);
            function drawChartCatBar2(){
                var data = google.visualization.arrayToDataTable([
                    ['<?php echo __('Categories', 'js-jobs'); ?>', '<?php echo __('Resume', 'js-jobs'); ?>', { role: 'style' }, { role: 'annotation' } ],
                    <?php echo wp_kses_post(jsjobs::$_data['catbar2']); ?>
                ]);
                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, 1,
                    { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                            2]);
                    var options = {title: "", width:'100%', height: 300, bar: {groupWidth: "80%"}, legend: { position: "none" }};
                    var chart = new google.visualization.ColumnChart(document.getElementById("catbar2"));
                    chart.draw(view, options);
            }

    google.setOnLoadCallback(drawChart);
            function drawChart() {
                var piedata = google.visualization.arrayToDataTable([
                    ['<?php echo __('Categories', 'js-jobs'); ?>', '<?php echo __('Companies', 'js-jobs'); ?>'],
                    <?php echo wp_kses_post(jsjobs::$_data['catpie']); ?>
                ]);
                    var pieoptions = {title: '', width:'100%', height:300, legend: {position:"bottom"}, pieHole: 0.4, };
                    var piechart = new google.visualization.PieChart(document.getElementById('catpie'));
                    piechart.draw(piedata, pieoptions);
            }

    google.setOnLoadCallback(drawChartCityBar1);
            function drawChartCityBar1(){
            var data = google.visualization.arrayToDataTable([
                    ['<?php echo __('Cities', 'js-jobs'); ?>', '<?php echo __('Jobs', 'js-jobs'); ?>', { role: 'style' }, { role: 'annotation' } ],
                    <?php echo wp_kses_post(jsjobs::$_data['citybar1']); ?>
            ]);
                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, 1,
                    { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                            2]);
                    var options = {
                    title: "",
                            width:'100%',
                            height: 300,
                            bar: {groupWidth: "80%"},
                            legend: { position: "none" },
                            chartArea: {width:'90%', top:50}
                    };
                    var chart = new google.visualization.BarChart(document.getElementById("citybar1"));
                    chart.draw(view, options);
            }

    google.setOnLoadCallback(drawChartCityBar2);
            function drawChartCityBar2(){
            var data = google.visualization.arrayToDataTable([
                    ['<?php echo __('Cities', 'js-jobs'); ?>', '<?php echo __('Resume', 'js-jobs'); ?>', { role: 'style' }, { role: 'annotation' } ],
                <?php echo wp_kses_post(jsjobs::$_data['citybar2']); ?>
            ]);
                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, 1,
                    { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                            2]);
                    var options = {title: "", width:'100%', height: 300, bar: {groupWidth: "80%"}, legend: { position: "none" }};
                    var chart = new google.visualization.ColumnChart(document.getElementById("citybar2"));
                    chart.draw(view, options);
            }

    google.setOnLoadCallback(drawChartCity);
            function drawChartCity() {
            var piedata = google.visualization.arrayToDataTable([
                    ['<?php echo __('Cities', 'js-jobs'); ?>', '<?php echo __('Companies', 'js-jobs'); ?>'],
                <?php echo wp_kses_post(jsjobs::$_data['citypie']); ?>
            ]);
                    var pieoptions = {title: '', width:'100%', height:300, legend: {position:"bottom"}, pieHole: 0.4, };
                    var piechart = new google.visualization.PieChart(document.getElementById('citypie'));
                    piechart.draw(piedata, pieoptions);
            }

    google.setOnLoadCallback(drawChartJobtypeBar1);
            function drawChartJobtypeBar1(){
            var data = google.visualization.arrayToDataTable([
                    ['<?php echo __('Job type', 'js-jobs'); ?>', '<?php echo __('Jobs', 'js-jobs'); ?>', { role: 'style' }, { role: 'annotation' } ],
                <?php echo wp_kses_post(jsjobs::$_data['jobtypebar1']); ?>
            ]);
                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, 1,
                    { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                            2]);
                    var options = {
                    title: "",
                            width:'100%',
                            height: 300,
                            bar: {groupWidth: "80%"},
                            legend: { position: "none" },
                            chartArea: {width:'90%', top:50}
                    };
                    var chart = new google.visualization.BarChart(document.getElementById("jobtypebar1"));
                    chart.draw(view, options);
            }

            google.setOnLoadCallback(drawChartJobtypeBar2);
            function drawChartJobtypeBar2(){
                var data = google.visualization.arrayToDataTable([
                    ['<?php echo __('Job type', 'js-jobs'); ?>', '<?php echo __('Resume', 'js-jobs'); ?>', { role: 'style' }, { role: 'annotation' } ],
                    <?php echo wp_kses_post(jsjobs::$_data['jobtypebar2']); ?>
                ]);
                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, 1,
                    { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                            2]);
                    var options = {title: "", width:'100%', height: 300, bar: {groupWidth: "80%"}, legend: { position: "none" }};
                    var chart = new google.visualization.ColumnChart(document.getElementById("jobtypebar2"));
                    chart.draw(view, options);
            }

</script>
