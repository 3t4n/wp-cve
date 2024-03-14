<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php
$current_post_id = get_the_ID();
if (get_post_status( $current_post_id ) == 'publish' ):
    ?>
    <style>
        #analytics-container{
            right: 11.6px;
            position: relative;
            margin: -6px 0 12px 0;
            width: 109%;
            background: #fff;
        }

        #analytics-container .row{
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin: 0 auto;
            /*margin-bottom:0.7vh;*/
            position:relative;
            line-height:2;
        }

        #analytics-container .row.odd{
            background-color:#f9f9f9;
        }

        #analytics-container .row:hover{
            background-color:red;
            cursor:pointer;
        }

        #analytics-container .row .reset-notice{
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            text-align: center;
            background-color:#fff !important;
            color:#a00;
            display:none;

        }

        #analytics-container .row:hover .reset-notice{
            display:block;
        }

        #analytics-container .row:first-of-type{
            font-weight: bold;
            background-color:transparent;
            cursor:auto;
            letter-spacing: -0.5px;
        }

        #analytics-container .row:first-of-type:hover .reset-notice{
            display:none;
        }

        #analytics-container .row>*{
            display: inline-block;
            min-width: 76px;
            text-align: center;
            font-size:12.3px;
        }

        #analytics-container .row *:last-of-type{
            min-width: 57px;
        }

        #analytics-container .row>*:first-child{
            min-width: 43px;
        }

        .analytics-controls{
            position: relative;
            right: -60%;
        }

        .analytics-controls a:focus{
            box-shadow:none;
        }

        .php-analytics-toggle-wrap{
            display:none;
        }

        .php-analytics-toggle-link{
            color: #00a0d2;
            cursor:pointer;
            margin-bottom:0;
            font-size:12.3px;
        }

        .nodisplay{
            display:none;
        }

        .resetAllCounters{
            color:#a00!important;
            font-size:12.3px;
            display: block;
            margin-top: 8px;
        }

        .resetAllCounters:hover{
            cursor:pointer;
            text-decoration:underline;
            box-shadow:none;
        }
    </style>

<?php if(!isset($_COOKIE['ifso_hide_analytics_notice'])): ?>
    <div class="analytics-noticebox whileLoading purple-noticebox">
        <span class="closeX" style="border-color:#c0bc25;">X</span>
        <p>If-So does not count admin visits. Browse incognito to make tests.</p>
    </div>
<?php endif; ?>
    <h4 id="analytics-loading-notice" style="margin:20px 0 28px 0;font-weight:normal;text-align:center;"><?php _e('Loading stats...', 'if-so'); ?> </h4>
    <div id="analytics-container" pid="<?php echo $current_post_id ?>"></div>
    <p class="php-analytics-toggle-link"><span class="ifso-turnme"><i class="fa fa-angle-down" aria-hidden="true"></i></span><?php _e('Set up a conversion', 'if-so'); ?></p>
    <div class="php-analytics-toggle-wrap">
        <p style="font-size:12.3px;line-height:1.3;margin-top:2px;">
            <?php _e('Paste the shortcode into your conversion page. Conversions will be assigned to the last version a visitor has seen.', 'if-so');?> <a target="_blank" href="https://www.if-so.com/help/documentation/analytics/?utm_source=Plugin&utm_medium=settings&utm_campaign=analyticsConversionOptionsLarnMore#anc_conversion-options"><?php _e('Advanced Options', 'if-so'); ?></a>
        </p>

        <span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value='[ifso_conversion <?php echo "triggers=\"{$current_post_id}\""; ?>]' class="large-text code"></span>
        <a href="javascript:resetAllFields();" class="resetAllCounters">Reset trigger stats</a>
    </div>

    <script>
        var pid;

        function constructAnalyticsUi(){
            pid = jQuery('#analytics-container').attr('pid');
            refreshAnalyticsDisplay();
        }

        function getAnalyticsData(postid){
            ajaxPost({action:'ifso_analytics_req',an_action:'getFields',postid:postid}, buildAnalyticsDisplay)
        }

        function buildAnalyticsDisplay(res){
            document.querySelector('#analytics-loading-notice').className = '';
            container = document.querySelector('#analytics-container');
            container.innerHTML = '';
            data = JSON.parse(res);
            container.appendChild(createRow(['<?php _e('Version', 'if-so'); ?>','<?php _e('Views', 'if-so'); ?>','<?php _e('Conversions', 'if-so'); ?>','<?php _e('Conv. rate', 'if-so'); ?>']));
            for(var x = 0;x<=data.length-1;x++){
                var convRate = ( Number(data[x]['views'])!=0) ? (Number(data[x]['conversion'])*100/Number(data[x]['views']) ).toFixed(2) + '%' : '0.00%';
                //var convRate = ( Number(data[x]['views'])!=0) ? Math.round((Number(data[x]['conversion'])*100/Number(data[x]['views']) )) + '%' : '0%';
                var newrow = createRow([data[x]['version_name'],data[x]['views'],data[x]['conversion'],convRate]);
                newrow.setAttribute('myversion',x);
                if(data[x]['version_name']=='Default') newrow.setAttribute('myversion','default');
                if(x%2==0) newrow.className += ' odd';
                newrow.addEventListener('click',function(e){
                    var version =e.currentTarget.getAttribute('myversion');
                    resetVersionFields(version);
                });
                container.appendChild(newrow);
            }
            document.querySelector('#analytics-loading-notice').className = 'nodisplay';
            if (document.querySelector('.analytics-noticebox')!= null && typeof(document.querySelector('.analytics-noticebox'))!== 'undefined') document.querySelector('.analytics-noticebox').classList.remove('whileLoading');
            document.querySelectorAll('#refreshTriggerAnalytics>i').forEach(function(e){e.classList.remove('spin');});
        }

        function resetAllFields(){
            if(confirm('<?php _e("Are you sure you want to reset this trigger stats?", 'if-so'); ?>')){
                ajaxPost({action:'ifso_analytics_req',an_action:'resetFields',postid:pid}, function(){
                    refreshAnalyticsDisplay(pid);
                })
            }
        }

        function resetVersionFields(version){
            if(confirm('<?php _e("Are you sure you want to reset this version stats?", 'if-so'); ?>')){
                ajaxPost({action:'ifso_analytics_req',an_action:'resetFields',postid:pid,versionid:version}, function(){
                    refreshAnalyticsDisplay(pid);
                })
            }
        }

        function refreshAnalyticsDisplay(){
            document.querySelectorAll('#refreshTriggerAnalytics>i').forEach(function(e){e.classList.add('spin');});
            getAnalyticsData(pid);
        }

        function createRow(children){
            var row = document.createElement('div');
            row.className = 'row';
            for(var i=0;i<=children.length-1;i++){
                var el = document.createElement('span');
                el.innerHTML = (children[i] && children[i]!='false') ?  children[i] : 0;
                row.appendChild(el);
            }
            var reset_notice = document.createElement('div');
            reset_notice.className = 'reset-notice';
            reset_notice.innerHTML = 'Reset version stats';
            row.appendChild(reset_notice);
            return row;
        }


    </script>

<?php else: ?>
    <h4 style="margin-bottom:8px;font-weight:normal;"><?php _e('Statistics will be available after you publish', 'if-so'); ?></h4>
<?php endif; ?>