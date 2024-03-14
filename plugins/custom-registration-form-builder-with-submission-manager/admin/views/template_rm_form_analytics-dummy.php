<?php wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' ); ?>



<div class="rm-center-stats-box">
    <div class="rm-box-title">Submissions over time</div>
    <div class="rm-timerange-toggle">
    Show data for    <select id="rm_stat_timerange" onchange="rm_refresh_stats()">
        <option value="7">Last 7 days</option><option value="30" selected="">Last 30 days</option><option value="60">Last 60 days</option><option value="90">Last 90 days</option>        
    </select>
    </div>
    <div class="rm-box-graph" id="rm_subs_over_time_chart_div"><div style="position: relative;"><div dir="ltr" style="position: relative; width: 1098px; height: 500px;"><div style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;" aria-label="A chart."><svg width="1098" height="500" aria-label="A chart." style="overflow: hidden;"><defs id="_ABSTRACT_RENDERER_ID_4"><clipPath id="_ABSTRACT_RENDERER_ID_5"><rect x="55" y="96" width="988" height="309"></rect></clipPath><filter id="_ABSTRACT_RENDERER_ID_6"><feGaussianBlur in="SourceAlpha" stdDeviation="2"></feGaussianBlur><feOffset dx="1" dy="1"></feOffset><feComponentTransfer><feFuncA type="linear" slope="0.1"></feFuncA></feComponentTransfer><feMerge><feMergeNode></feMergeNode><feMergeNode in="SourceGraphic"></feMergeNode></feMerge></filter></defs><rect x="0" y="0" width="1098" height="500" stroke="none" stroke-width="0" fill="#ffffff"></rect><g><rect x="55" y="57" width="211" height="15" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><rect x="55" y="57" width="71" height="15" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="91" y="69.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">Visits</text></g><path d="M55,64.5L85,64.5" stroke="#485566" stroke-width="2" fill-opacity="1" fill="none"></path></g><g><rect x="150" y="57" width="116" height="15" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="186" y="69.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">Submissions</text></g><path d="M150,64.5L180,64.5" stroke="#00a9de" stroke-width="2" fill-opacity="1" fill="none"></path></g></g><g><rect x="55" y="96" width="988" height="309" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g clip-path="url(https://theeventprime.com/wp-admin/admin.php?page=rm_analytics_show_form&amp;rm_form_id=6&amp;rm_tr=30#_ABSTRACT_RENDERER_ID_5)"><g><rect x="55" y="404" width="988" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="55" y="360" width="988" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="55" y="316" width="988" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="55" y="272" width="988" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="55" y="228" width="988" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="55" y="184" width="988" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="55" y="140" width="988" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="55" y="96" width="988" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect></g><g><rect x="55" y="404" width="988" height="1" stroke="none" stroke-width="0" fill="#333333"></rect></g><g><path d="M71.95,228.5L104.85,334.1L137.75,158.09999999999997L170.64999999999998,404.5L203.54999999999998,369.3L236.45,351.7L269.35,334.1L302.25,281.29999999999995L335.15,210.89999999999998L368.05,246.1L400.95,263.7L433.84999999999997,351.7L466.75,351.7L499.65,334.1L532.55,193.29999999999998L565.45,298.9L598.35,351.7L631.25,316.5L664.15,210.89999999999998L697.05,246.1L729.9499999999999,210.89999999999998L762.85,369.3L795.75,175.7L828.65,351.7L861.55,298.9L894.4499999999999,386.9L927.3499999999999,140.5L960.25,298.9L993.15,228.5L1026.05,404.5" stroke="#485566" stroke-width="2" fill-opacity="1" fill="none"></path><path d="M71.95,281.29999999999995L104.85,386.9L137.75,334.1L170.64999999999998,404.5L203.54999999999998,404.5L236.45,369.3L269.35,404.5L302.25,351.7L335.15,316.5L368.05,386.9L400.95,316.5L433.84999999999997,404.5L466.75,386.9L499.65,369.3L532.55,351.7L565.45,369.3L598.35,404.5L631.25,386.9L664.15,369.3L697.05,334.1L729.9499999999999,334.1L762.85,386.9L795.75,298.9L828.65,404.5L861.55,404.5L894.4499999999999,404.5L927.3499999999999,351.7L960.25,386.9L993.15,369.3L1026.05,404.5" stroke="#00a9de" stroke-width="2" fill-opacity="1" fill="none"></path></g></g><g></g><g><g><text text-anchor="middle" x="71.95" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">21 Jun</text></g><g><text text-anchor="middle" x="137.75" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">23 Jun</text></g><g><text text-anchor="middle" x="203.54999999999998" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">25 Jun</text></g><g><text text-anchor="middle" x="269.35" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">27 Jun</text></g><g><text text-anchor="middle" x="335.15" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">29 Jun</text></g><g><text text-anchor="middle" x="400.95" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">01 Jul</text></g><g><text text-anchor="middle" x="466.75" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">03 Jul</text></g><g><text text-anchor="middle" x="532.55" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">05 Jul</text></g><g><text text-anchor="middle" x="598.35" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">07 Jul</text></g><g><text text-anchor="middle" x="664.15" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">09 Jul</text></g><g><text text-anchor="middle" x="729.9499999999999" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">11 Jul</text></g><g><text text-anchor="middle" x="795.75" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">13 Jul</text></g><g><text text-anchor="middle" x="861.55" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">15 Jul</text></g><g><text text-anchor="middle" x="927.3499999999999" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">17 Jul</text></g><g><text text-anchor="middle" x="993.15" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">19 Jul</text></g><g><text text-anchor="end" x="41.5" y="409.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">0.0</text></g><g><text text-anchor="end" x="41.5" y="365.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">2.5</text></g><g><text text-anchor="end" x="41.5" y="321.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">5.0</text></g><g><text text-anchor="end" x="41.5" y="277.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">7.5</text></g><g><text text-anchor="end" x="41.5" y="233.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">10.0</text></g><g><text text-anchor="end" x="41.5" y="189.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">12.5</text></g><g><text text-anchor="end" x="41.5" y="145.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">15.0</text></g><g><text text-anchor="end" x="41.5" y="101.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">17.5</text></g></g></g><g></g></svg><div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;"><table><thead><tr><th>Date</th><th>Visits</th><th>Submissions</th></tr></thead><tbody><tr><td>21 Jun</td><td>10</td><td>7</td></tr><tr><td>22 Jun</td><td>4</td><td>1</td></tr><tr><td>23 Jun</td><td>14</td><td>4</td></tr><tr><td>24 Jun</td><td>0</td><td>0</td></tr><tr><td>25 Jun</td><td>2</td><td>0</td></tr><tr><td>26 Jun</td><td>3</td><td>2</td></tr><tr><td>27 Jun</td><td>4</td><td>0</td></tr><tr><td>28 Jun</td><td>7</td><td>3</td></tr><tr><td>29 Jun</td><td>11</td><td>5</td></tr><tr><td>30 Jun</td><td>9</td><td>1</td></tr><tr><td>01 Jul</td><td>8</td><td>5</td></tr><tr><td>02 Jul</td><td>3</td><td>0</td></tr><tr><td>03 Jul</td><td>3</td><td>1</td></tr><tr><td>04 Jul</td><td>4</td><td>2</td></tr><tr><td>05 Jul</td><td>12</td><td>3</td></tr><tr><td>06 Jul</td><td>6</td><td>2</td></tr><tr><td>07 Jul</td><td>3</td><td>0</td></tr><tr><td>08 Jul</td><td>5</td><td>1</td></tr><tr><td>09 Jul</td><td>11</td><td>2</td></tr><tr><td>10 Jul</td><td>9</td><td>4</td></tr><tr><td>11 Jul</td><td>11</td><td>4</td></tr><tr><td>12 Jul</td><td>2</td><td>1</td></tr><tr><td>13 Jul</td><td>13</td><td>6</td></tr><tr><td>14 Jul</td><td>3</td><td>0</td></tr><tr><td>15 Jul</td><td>6</td><td>0</td></tr><tr><td>16 Jul</td><td>1</td><td>0</td></tr><tr><td>17 Jul</td><td>15</td><td>3</td></tr><tr><td>18 Jul</td><td>6</td><td>1</td></tr><tr><td>19 Jul</td><td>10</td><td>2</td></tr><tr><td>20 Jul</td><td>0</td><td>0</td></tr></tbody></table></div></div></div><div aria-hidden="true" style="display: none; position: absolute; top: 510px; left: 1108px; white-space: nowrap; font-family: &quot;Titillium Web&quot;; font-size: 15px; font-weight: bold;">8</div><div></div></div></div>

    <div class="rm-demo-data-notice-wrap">                            
        <p class="rm-demo-data-notice">
            <span class="material-icons"> info </span> Displaying demo data since there are no submissions yet.                                    </p>
    </div>
    
</div>


<div class="rm-left-stats-box">
    <div class="rm-box-title">Conversion % (Submissions/Total Visits)</div>
    <div class="rm-box-graph" id="rm_conversion_chart_div"><div style="position: relative;"><div dir="ltr" style="position: relative; width: 526px; height: 300px;"><div style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;" aria-label="A chart."><svg width="526" height="300" aria-label="A chart." style="overflow: hidden;"><defs id="_ABSTRACT_RENDERER_ID_0"><filter id="_ABSTRACT_RENDERER_ID_8"><feGaussianBlur in="SourceAlpha" stdDeviation="2"></feGaussianBlur><feOffset dx="1" dy="1"></feOffset><feComponentTransfer><feFuncA type="linear" slope="0.1"></feFuncA></feComponentTransfer><feMerge><feMergeNode></feMergeNode><feMergeNode in="SourceGraphic"></feMergeNode></feMerge></filter></defs><rect x="0" y="0" width="526" height="300" stroke="none" stroke-width="0" fill="#ffffff"></rect><g><text text-anchor="start" x="96" y="36.3" font-family="Titillium Web" font-size="18" stroke="none" stroke-width="0" fill="#87c2db">TOTAL VISITS 385</text><rect x="96" y="21" width="335" height="18" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect></g><g><rect x="168" y="266" width="190" height="12" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g column-id="Not submitted"><rect x="168" y="266" width="90" height="12" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="185" y="276.2" font-family="Titillium Web" font-size="12" stroke="none" stroke-width="0" fill="#222222">Not submitted</text></g><circle cx="174" cy="272" r="6" stroke="none" stroke-width="0" fill="#8facbf"></circle></g><g column-id="Submissions"><rect x="277" y="266" width="81" height="12" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="294" y="276.2" font-family="Titillium Web" font-size="12" stroke="none" stroke-width="0" fill="#222222">Submissions</text></g><circle cx="283" cy="272" r="6" stroke="none" stroke-width="0" fill="#00a9de"></circle></g></g><g><path d="M264,151L177.22490088810866,181.56275795999827A92,92,0,0,1,264,59L264,151A0,0,0,0,0,264,151" stroke="#ffffff" stroke-width="1" fill="#00a9de"></path><text text-anchor="start" x="196.75791233953544" y="117.85831513355305" font-family="Titillium Web" font-size="12" stroke="none" stroke-width="0" fill="#ffffff">30.4%</text></g><g><path d="M264,151L264,59A92,92,0,1,1,177.22490088810866,181.56275795999827L264,151A0,0,0,1,0,264,151" stroke="#ffffff" stroke-width="1" fill="#8facbf"></path><text text-anchor="start" x="302.24208766046456" y="192.54168486644696" font-family="Titillium Web" font-size="12" stroke="none" stroke-width="0" fill="#ffffff">69.6%</text></g><g></g></svg><div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;"><table><thead><tr><th>Submissions</th><th>Not submitted</th></tr></thead><tbody><tr><td>Not submitted</td><td>268</td></tr><tr><td>Banned</td><td>0</td></tr><tr><td>Submissions</td><td>117</td></tr></tbody></table></div></div></div><div aria-hidden="true" style="display: none; position: absolute; top: 310px; left: 536px; white-space: nowrap; font-family: &quot;Titillium Web&quot;; font-size: 12px; font-weight: bold;">117 (30.4%)</div><div></div></div></div>

    <div class="rm-demo-data-notice-wrap">                            
        <p class="rm-demo-data-notice">
            <span class="material-icons"> info </span> Displaying demo data since there are no submissions yet.                                    </p>
    </div>

</div>

<div class="rm-right-stats-box">
    <div class="rm-box-title">Browsers Used</div>
    <div class="rm-box-graph" id="rm_browser_usage_chart_div"><div style="position: relative;"><div dir="ltr" style="position: relative; width: 526px; height: 300px;"><div style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;" aria-label="A chart."><svg width="526" height="300" aria-label="A chart." style="overflow: hidden;"><defs id="_ABSTRACT_RENDERER_ID_1"><filter id="_ABSTRACT_RENDERER_ID_7"><feGaussianBlur in="SourceAlpha" stdDeviation="2"></feGaussianBlur><feOffset dx="1" dy="1"></feOffset><feComponentTransfer><feFuncA type="linear" slope="0.1"></feFuncA></feComponentTransfer><feMerge><feMergeNode></feMergeNode><feMergeNode in="SourceGraphic"></feMergeNode></feMerge></filter></defs><rect x="0" y="0" width="526" height="300" stroke="none" stroke-width="0" fill="#ffffff"></rect><g><rect x="322" y="58" width="109" height="88" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g column-id="Chrome"><rect x="322" y="58" width="109" height="12" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="339" y="68.2" font-family="Titillium Web" font-size="12" stroke="none" stroke-width="0" fill="#222222">Chrome</text></g><circle cx="328" cy="64" r="6" stroke="none" stroke-width="0" fill="#00a9de"></circle></g><g column-id="Safari"><rect x="322" y="77" width="109" height="12" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="339" y="87.2" font-family="Titillium Web" font-size="12" stroke="none" stroke-width="0" fill="#222222">Safari</text></g><circle cx="328" cy="83" r="6" stroke="none" stroke-width="0" fill="#8facbf"></circle></g><g column-id="Opera"><rect x="322" y="96" width="109" height="12" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="339" y="106.2" font-family="Titillium Web" font-size="12" stroke="none" stroke-width="0" fill="#222222">Opera</text></g><circle cx="328" cy="102" r="6" stroke="none" stroke-width="0" fill="#004f84"></circle></g><g column-id="iPhone"><rect x="322" y="115" width="109" height="12" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="339" y="125.2" font-family="Titillium Web" font-size="12" stroke="none" stroke-width="0" fill="#222222">iPhone</text></g><circle cx="328" cy="121" r="6" stroke="none" stroke-width="0" fill="#d2dce4"></circle></g><g column-id="Firefox"><rect x="322" y="134" width="109" height="12" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="339" y="144.2" font-family="Titillium Web" font-size="12" stroke="none" stroke-width="0" fill="#222222">Firefox</text></g><circle cx="328" cy="140" r="6" stroke="none" stroke-width="0" fill="#647a88"></circle></g></g><g><path d="M200,151L144.11810957204725,77.91638814208564A92,92,0,0,1,200,59L200,151A0,0,0,0,0,200,151" stroke="#ffffff" stroke-width="1" fill="#647a88"></path><text text-anchor="start" x="163.4432477479683" y="90.0409958980343" font-family="Titillium Web" font-size="12" stroke="none" stroke-width="0" fill="#ffffff">10.4%</text></g><g><path d="M200,151L140.60833668502633,80.73884196313861A92,92,0,0,1,144.11810957204725,77.91638814208564L200,151A0,0,0,0,0,200,151" stroke="#ffffff" stroke-width="1" fill="#d2dce4"></path></g><g><path d="M200,151L138.34705911094449,82.7144606836072A92,92,0,0,1,140.60833668502633,80.73884196313861L200,151A0,0,0,0,0,200,151" stroke="#ffffff" stroke-width="1" fill="#004f84"></path></g><g><path d="M200,151L108.7348868602421,162.60513350148076A92,92,0,0,1,138.34705911094454,82.71446068360713L200,151A0,0,0,0,0,200,151" stroke="#ffffff" stroke-width="1" fill="#8facbf"></path><text text-anchor="start" x="125.19298621181119" y="132.8466811802616" font-family="Titillium Web" font-size="12" stroke="none" stroke-width="0" fill="#ffffff">15.3%</text></g><g><path d="M200,151L200,59A92,92,0,1,1,108.7348868602421,162.60513350148076L200,151A0,0,0,1,0,200,151" stroke="#ffffff" stroke-width="1" fill="#00a9de"></path><text text-anchor="start" x="241.402876293574" y="200.4804717497416" font-family="Titillium Web" font-size="12" stroke="none" stroke-width="0" fill="#ffffff">73%</text></g><g></g></svg><div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;"><table><thead><tr><th>Browser</th><th>Hits</th></tr></thead><tbody><tr><td>Chrome</td><td>281</td></tr><tr><td>Edge</td><td>0</td></tr><tr><td>Safari</td><td>59</td></tr><tr><td>Internet Explorer</td><td>0</td></tr><tr><td>Opera</td><td>2</td></tr><tr><td>Android</td><td>0</td></tr><tr><td>iPhone</td><td>3</td></tr><tr><td>Firefox</td><td>40</td></tr><tr><td>BlackBerry</td><td>0</td></tr><tr><td>Other</td><td>0</td></tr></tbody></table></div></div></div><div aria-hidden="true" style="display: none; position: absolute; top: 310px; left: 536px; white-space: nowrap; font-family: &quot;Titillium Web&quot;; font-size: 12px; font-weight: bold;">59 (15.3%)</div><div></div></div></div>

    <div class="rm-demo-data-notice-wrap">                            
        <p class="rm-demo-data-notice">
            <span class="material-icons"> info </span> Displaying demo data since there are no submissions yet.                                    </p>
    </div>
</div>

<div class="rm-left-stats-box">
    <div class="rm-analytics-stat-counter">
        <div class="rm-analytics-stat-counter-value">20.61<span class="rm-counter-value-dark">%</span></div>
        <div class="rm-analytics-stat-counter-text">Failure Rate</div>
    </div>

    <div class="rm-demo-data-notice-wrap">                            
        <p class="rm-demo-data-notice">
            <span class="material-icons"> info </span> Displaying demo data since there are no submissions yet.                                    </p>
    </div>
</div>



<div class="rm-right-stats-box">
    <div class="rm-analytics-stat-counter">
        <div class="rm-analytics-stat-counter-value">134.09<span class="rm-counter-value-dark">s</span></div>
        <div class="rm-analytics-stat-counter-text">Average Filling Time</div>
    </div>

    <div class="rm-demo-data-notice-wrap">                            
        <p class="rm-demo-data-notice">
            <span class="material-icons"> info </span> Displaying demo data since there are no submissions yet.                                    </p>
    </div>
</div>

<div class="rm-center-stats-box">
    <div class="rm-box-title">Browser wise Conversion</div>
    <div class="rm-box-graph" id="rm_conversion_by_browser_chart_div"><div style="position: relative;"><div dir="ltr" style="position: relative; width: 1098px; height: 500px;"><div style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;" aria-label="A chart."><svg width="1098" height="500" aria-label="A chart." style="overflow: hidden;"><defs id="_ABSTRACT_RENDERER_ID_2"><clipPath id="_ABSTRACT_RENDERER_ID_3"><rect x="275" y="96" width="549" height="309"></rect></clipPath></defs><rect x="0" y="0" width="1098" height="500" stroke="none" stroke-width="0" fill="#ffffff"></rect><g><rect x="275" y="57" width="244" height="15" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><rect x="275" y="57" width="104" height="15" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="311" y="69.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">Total Visits</text></g><rect x="275" y="57" width="30" height="15" stroke="none" stroke-width="0" fill="#485566"></rect></g><g><rect x="403" y="57" width="116" height="15" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g><text text-anchor="start" x="439" y="69.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">Submissions</text></g><rect x="403" y="57" width="30" height="15" stroke="none" stroke-width="0" fill="#00a9de"></rect></g></g><g><rect x="275" y="96" width="549" height="309" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect><g clip-path="url(https://theeventprime.com/wp-admin/admin.php?page=rm_analytics_show_form&amp;rm_form_id=6&amp;rm_tr=30#_ABSTRACT_RENDERER_ID_3)"><g><rect x="275" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="366" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="458" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="549" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="640" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="732" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="823" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#cccccc"></rect><rect x="321" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="412" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="503" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="595" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="686" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#ebebeb"></rect><rect x="777" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#ebebeb"></rect></g><g><rect x="276" y="117" width="512" height="10" stroke="none" stroke-width="0" fill="#485566"></rect><rect x="276" y="179" width="107" height="9" stroke="none" stroke-width="0" fill="#485566"></rect><rect x="276" y="241" width="3" height="9" stroke="none" stroke-width="0" fill="#485566"></rect><rect x="276" y="302" width="4" height="10" stroke="none" stroke-width="0" fill="#485566"></rect><rect x="276" y="364" width="72" height="9" stroke="none" stroke-width="0" fill="#485566"></rect><rect x="276" y="128" width="158" height="9" stroke="none" stroke-width="0" fill="#00a9de"></rect><rect x="276" y="189" width="30" height="10" stroke="none" stroke-width="0" fill="#00a9de"></rect><rect x="275" y="251" width="0.5" height="10" stroke="none" stroke-width="0" fill="#00a9de"></rect><rect x="275" y="313" width="0.5" height="9" stroke="none" stroke-width="0" fill="#00a9de"></rect><rect x="276" y="374" width="23" height="10" stroke="none" stroke-width="0" fill="#00a9de"></rect></g><g><rect x="275" y="96" width="1" height="309" stroke="none" stroke-width="0" fill="#333333"></rect></g></g><g></g><g><g><text text-anchor="middle" x="275.5" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">0</text></g><g><text text-anchor="middle" x="366.8333" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">50</text></g><g><text text-anchor="middle" x="458.1667" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">100</text></g><g><text text-anchor="middle" x="549.5" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">150</text></g><g><text text-anchor="middle" x="640.8333" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">200</text></g><g><text text-anchor="middle" x="732.1667" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">250</text></g><g><text text-anchor="middle" x="823.5" y="426.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#444444">300</text></g><g><text text-anchor="end" x="260" y="132.55" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">Chrome</text></g><g><text text-anchor="end" x="260" y="194.15" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">Safari</text></g><g><text text-anchor="end" x="260" y="255.75" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">Opera</text></g><g><text text-anchor="end" x="260" y="317.35" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">iPhone</text></g><g><text text-anchor="end" x="260" y="378.95" font-family="Titillium Web" font-size="15" stroke="none" stroke-width="0" fill="#222222">Firefox</text></g></g></g><g><g><text text-anchor="middle" x="549.5" y="469.75" font-family="Titillium Web" font-size="15" font-style="italic" stroke="none" stroke-width="0" fill="#222222">Hits</text><rect x="275" y="457" width="549" height="15" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect></g><g><text text-anchor="middle" x="110.75" y="250.5" font-family="Titillium Web" font-size="15" font-style="italic" transform="rotate(-90 110.75 250.5)" stroke="none" stroke-width="0" fill="#222222">Browser</text><path d="M97.99999999999999,405L98.00000000000001,96L113.00000000000001,96L112.99999999999999,405Z" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></path></g></g><g></g></svg><div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;"><table><thead><tr><th>Browser</th><th>Total Visits</th><th>Submissions</th></tr></thead><tbody><tr><td>Chrome</td><td>281</td><td>87</td></tr><tr><td>Safari</td><td>59</td><td>17</td></tr><tr><td>Opera</td><td>2</td><td>0</td></tr><tr><td>iPhone</td><td>3</td><td>0</td></tr><tr><td>Firefox</td><td>40</td><td>13</td></tr></tbody></table></div></div></div><div aria-hidden="true" style="display: none; position: absolute; top: 510px; left: 1108px; white-space: nowrap; font-family: &quot;Titillium Web&quot;; font-size: 15px;">Submissions</div><div></div></div></div>

    <div class="rm-demo-data-notice-wrap">                            
        <p class="rm-demo-data-notice">
            <span class="material-icons"> info </span> Displaying demo data since there are no submissions yet.                                    </p>
    </div>
</div>


<div class="rm-analytics-table-wrapper">
<table class="rm-form-analytics">
    <thead>
        <tr>
            <th>#</th>
            <th><?php echo esc_html(RM_UI_Strings::get('LABEL_IP')); ?></th>
            <th><?php echo esc_html(RM_UI_Strings::get('LABEL_SUBMISSION_STATE')); ?></th>
            <th><?php echo esc_html(RM_UI_Strings::get('LABEL_VISITED_ON')); ?></th>
            <th><?php echo esc_html(RM_UI_Strings::get('LABEL_SUBMITTED_ON')); ?></th>
            <th><?php echo esc_html(RM_UI_Strings::get('LABEL_TIME_TAKEN')); ?></th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>1</td>
            <td><a href="https://geoiptool.com/?ip=94.100.79.26">94.100.79.26</a></td>
            <td>&nbsp;
                <img class='rmsubmitted_icon' src='<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . "images/right.png"; ?>'>
            </td>
            <td>17 May 2023, 10:09am</td>
            <td>17 May 2023, 10:20am</td>
            <td>2s</td>
        </tr>
        
        <tr>
            <td>2</td>
            <td><a href="https://geoiptool.com/?ip=194.36.195.89">194.36.195.89</a></td>
            <td>&nbsp;
                <img class='rmsubmitted_icon' src='<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . "images/right.png"; ?>'>
            </td>
            <td>19 Jul 2023, 02:21pm</td>
            <td>19 Jul 2023, 02:27pm</td>
            <td>386s</td>
        </tr>
        
        <tr>
            <td>3</td>
                        <td><a href="https://geoiptool.com/?ip=170.253.33.116">170.253.33.116</a></td>
                        <td>&nbsp;
                            </td>
            <td>19 Jul 2023, 11:19am</td>
            <td></td>
            <td></td>
        </tr>
        
        <tr>
            <td>4</td>
            <td><a href="https://geoiptool.com/?ip=107.130.247.189">107.130.247.189</a></td>
            <td>&nbsp;
                <img class='rmsubmitted_icon' src='<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . "images/right.png"; ?>'>
            </td>
            <td>17 Jul 2023, 08:38pm</td>
            <td>17 Jul 2023, 08:40pm</td>
            <td>120s</td>
        </tr>
        
        <tr>
            <td>5</td>
                        <td><a href="https://geoiptool.com/?ip=84.106.222.114">84.106.222.114</a></td>
                        <td>&nbsp;
                            </td>
            <td>17 Jul 2023, 03:30pm</td>
            <td></td>
            <td></td>
        </tr>
        
        <tr>
            <td>6</td>
            <td><a href="https://geoiptool.com/?ip=174.192.8.95">174.192.8.95</a></td>
            <td>&nbsp;
            </td>
            <td>17 Jul 2023, 12:33pm</td>
            <td></td>
            <td></td>
        </tr>
        
        <tr>
            <td>7</td>
            <td><a href="https://geoiptool.com/?ip=203.219.186.30">203.219.186.30</a></td>
            <td>&nbsp;
                <img class='rmsubmitted_icon' src='<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . "images/right.png"; ?>'>
            </td>
            <td>13 Jul 2023, 06:37am</td>
            <td>13 Jul 2023, 06:47am</td>
            <td>584s</td>
        </tr>
        
        <tr>
            <td>8</td>
            <td><a href="https://geoiptool.com/?ip=2.101.177.106">2.101.177.106</a></td>
            <td>&nbsp;
                <img class='rmsubmitted_icon' src='<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . "images/right.png"; ?>'>
            </td>
            <td>12 Jul 2023, 05:27pm</td>
            <td>12 Jul 2023, 05:34pm</td>
            <td>413s</td>
        </tr>
        
        <tr>
            <td>9</td>
            <td><a href="https://geoiptool.com/?ip=170.253.33.116">170.253.33.116</a></td>
            <td>&nbsp;
                <img class='rmsubmitted_icon' src='<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . "images/right.png"; ?>'>
            </td>
            <td>11 Jul 2023, 06:19pm</td>
            <td>13 Jul 2023, 05:49pm</td>
            <td>125s</td>
        </tr>
        
        <tr>
            <td>10</td>
            <td><a href="https://geoiptool.com/?ip=170.253.33.116">170.253.33.116</a></td>
            <td>&nbsp;
                <img class='rmsubmitted_icon' src='<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . "images/right.png"; ?>'>
            </td>
            <td>11 Jul 2023, 06:19pm</td>
            <td>11 Jul 2023, 06:35pm</td>
            <td>170s</td>
        </tr>
        

        
    </tbody>


</table>
    
    <div class="rm-demo-data-notice-wrap">                            
        <p class="rm-demo-data-notice">
            <span class="material-icons"> info </span> Displaying demo data since there are no submissions yet.                                    </p>
    </div>   
</div>


<style>
    
   .rmagic .rmagic-analytics-wrap .rm-analytics-table-wrapper.rm-analytics-table-dummy {
       padding: 0px;
       background-color: transparent;
       border: 0px solid #ccd0d4;
       box-shadow: 0 0px 0px rgba(0,0,0,.04);
    }
    
    .rm-demo-data-notice-wrap{
        margin-top: 40px;
    }
    
    .rm-demo-data-notice-wrap .rm-demo-data-notice{
    display: flex;
    flex-direction: row;
    align-content: space-around;
    flex-wrap: nowrap;
    justify-content: flex-start;
    align-items: stretch;
    color: #D09756;
    background-color: #FFFAE2;
    border-radius: 0px 0px 5px 5px;
    border-top: 1px solid #FFDFBB;
    padding: 12px 0px 12px 24px;
    position: absolute;
    bottom: 0px;
    left:0px;
    width: 100%;
    margin: 0px;
    font-size: 11px;
   
    }
    
    .rm-demo-data-notice-wrap .rm-demo-data-notice span {
    font-size: 15px;
    margin-right: 6px;
    }
    
    .rmagic .rmagic-analytics-wrap .rm-analytics-table-wrapper.rm-analytics-table-dummy .rm-analytics-table-wrapper,
    .rmagic .rmagic-analytics .rm-center-stats-box{
        position: relative;
    }
    
    .rmagic .rmagic-analytics .rm-left-stats-box, 
    .rmagic .rmagic-analytics .rm-right-stats-box{
        position: relative;
    }
    
</style>

