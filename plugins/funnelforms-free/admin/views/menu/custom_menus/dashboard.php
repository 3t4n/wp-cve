<div class="af2_dashboard_page">
    <div class="af2_card_table">
        <div class="af2_card">
            <div class="af2_card_block colorOne kpis-impressions">
                <div class="af2_card_label">
                    <div class="af2_statistics_icon"><i class="fas fa-eye"></i></div>
                    <div class="af2_statistics_label"><h5><?php _e('Impressions', 'funnelforms-free'); ?></h5></div>
                </div>
                <div class="af2_statistics_value"><h3>-</h3></div>
            </div>
        </div>
        <div class="af2_card">
            <div class="af2_card_block colorTwo kpis-leads">
                <div class="af2_card_label af2_small">
                    <div class="af2_statistics_icon"><i class="fas fa-comment-dots"></i></div>
                    <div class="af2_statistics_label"><h5><?php _e('Leads', 'funnelforms-free'); ?></h5></div>
                </div>
                <div class="af2_statistics_value"><h3>-</h3></div>
            </div>
        </div>
        <div class="af2_card">
            <div class="af2_card_block colorThree kpis-conversionrate">
                <div class="af2_card_label">
                    <div class="af2_statistics_icon"><i class="fas fa-chart-bar"></i></div>
                    <div class="af2_statistics_label"><h5><?php _e('Conversion rate', 'funnelforms-free'); ?></h5></div>
                </div>
                <div class="af2_statistics_value"><h3>-</h3></div>
            </div>
        </div>
        <div class="af2_card">
            <div class="af2_card_block colorFour kpis-impressionfactor">
                <div class="af2_card_label">
                    <div class="af2_statistics_icon"><i class="fas fa-percentage"></i></div>
                    <div class="af2_statistics_label"><h5><?php _e('Impression / Conversion', 'funnelforms-free'); ?></h5></div>
                </div>
                <div class="af2_statistics_value"><h3>-</h3></div>
            </div>
        </div>
    </div>
</div>
<div class="af2_card" style="margin-top: 20px;">
<div id="revenue-chart" class="af2_card_block"  style="min-width: 100%;min-height: 400px;">
    <div class="af2_chart_header" style="display: flex;justify-content: space-between;">
        <div class="af2_chart_heading">
            <h4 class="header mt-0"><?php _e('Leads by days in', 'funnelforms-free'); ?> <span id="af2_date-text" class="">-</span></h4>
        </div>
        <div class="af2_chart_inputs">
            <?php _e('Period:', 'funnelforms-free'); ?>       
            <select id="monthSelect" name="month">
                <option value="" disabled><?php _e('Select month', 'funnelforms-free'); ?></option>
                
            </select>   
            <select id="yearSelect" name="year">
                <option value="" disabled><?php _e('Select year', 'funnelforms-free'); ?></option>
            </select>           
        </div>
    </div>
    <div class="af2_chart_main">
        <div class="af2_chart_chart_wrapper">
            <canvas id="leadsConversionGraph" class="chartjs-render-monitor"></canvas>
        </div>
    </div>
</div>
</div>