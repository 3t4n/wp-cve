<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="wrap wpsd-wrap wpsd">
    <h1 class="wp-heading-inline"><?php echo __('Classified Ads Plugin', 'classified-ads'); ?></h1>
    <br /><br />
    <div class="wpsd-body">
        <div class="postbox" style="display: block;">
            <div class="postbox-header">
                <h3><?php echo __('How plugin works?', 'classified-ads'); ?></h3>
            </div>
            <div class="inside">
                <div class="wpsd_multilingual-about">
                    <div class="wpsd_multilingual-about-info">
                        <div class="top-content">
                            <p class="plugin-description">
                                <?php echo __('This is Addon plugin, require plugin WP Directory Kit to work properly', 'classified-ads'); ?>
                            </p>
                            <p class="plugin-description">
                                <?php echo __('Plugin will help you to configure WP Directory Kit to use for Classified Ads and import demo content', 'classified-ads'); ?>
                            </p>
                            <p class="plugin-description">
                                <?php echo __('Fields, Categories, Locations and demo listings will be imported into WP Directory Kit', 'classified-ads'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="postbox" style="display: block;">
            <div class="postbox-header">
                <h3><?php echo __('How to use it?', 'classified-ads'); ?></h3>
            </div>
            <div class="inside">
                <div class="wpsd_multilingual-about">
                    <div class="wpsd_multilingual-about-info">
                        <div class="top-content">
                            <p class="plugin-description">
                                <?php echo __('Click on "Import Classified Ads Configuration" button', 'classified-ads'); ?>
                            </p>
                            <p class="plugin-description wpsd-alert">
                                <h3><?php echo __('Required: ', 'classified-ads'); ?></h3>
                                <p>
                                    - <strong class="red">
                                    <a target="_blank" href="https://wordpress.org/plugins/wpdirectorykit/"><?php echo __('WP Directory Kit Plugin', 'classified-ads');?></a></strong>
                                    <?php if(in_array( 'wpdirectorykit/wpdirectorykit.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )):?>
                                        <span class="label label-success"><?php echo __('activated', 'classified-ads'); ?></span>
                                    <?php else :?>
                                        <span class="label label-danger"><?php echo __('not activated', 'classified-ads'); ?></span>
                                    <?php endif;?>
                                </p>
                                <p>
                                    - <strong class="red"><a target="_blank" href="https://wordpress.org/plugins/elementor/"><?php echo __('Elementor Plugin', 'classified-ads');?></a></strong>
                                    <?php if(in_array( 'elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )):?>
                                        <span class="label label-success"><?php echo __('activated', 'classified-ads'); ?></span>
                                    <?php else :?>
                                        <span class="label label-danger"><?php echo __('not activated', 'classified-ads'); ?></span>
                                    <?php endif;?>
                                </p>
                                <p>
                                    - <strong class="red"><a target="_blank" href="https://wordpress.org/plugins/elementinvader-addons-for-elementor/"><?php echo __('ElementInvader Addons for Elementor Plugin', 'classified-ads');?></a></strong>
                                    <?php if(in_array( 'elementinvader-addons-for-elementor/elementinvader-addons-for-elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )):?>
                                        <span class="label label-success"><?php echo __('activated', 'classified-ads'); ?></span>
                                    <?php else :?>
                                        <span class="label label-danger"><?php echo __('not activated', 'classified-ads'); ?></span>
                                    <?php endif;?>
                                </p>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="wpsd_colors-container">
            <ul class="hidden">
                <li id="max_time"></li>
                <li id="max_db"></li>
                <li id="max_mem"></li>
            </ul>

            <div class="run_button">
                <?php if(
                    in_array( 'wpdirectorykit/wpdirectorykit.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
                    && in_array( 'elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
                    && in_array( 'elementinvader-addons-for-elementor/elementinvader-addons-for-elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
                ):?>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=wdk_settings&function=import_demo&multipurpose=classified.xml'));?>" class="button button-primary"><?php echo __('Import Classified Ads Configuration', 'classified-ads'); ?></a>
                <?php else :?>
                    <p class="text-center"><?php echo __('Some required plugins missing', 'classified-ads'); ?>,<b><a href="<?php echo esc_url(classified_ads_get_tgmpa_link());?>" style="margin-left: 7px"><?php echo __('Begin installing plugins', 'classified-ads'); ?></a></b> <?php echo __('and after installation return here to configure Classified Ads', 'classified-ads'); ?></p>
                    
                    <a href="#" disabled class="disabled button button-primary"><?php echo __('Import Classified Ads Configuration', 'classified-ads'); ?></a>
                <?php endif;?>
            </div>

            <div class="quick_test_report_wrap">
            <div id="quick_test_report">
            </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">

jQuery(document).ready(function($)
{
    var wpsd_allow_run = true;

    $('#run_quick_tests').on( "click", function() 
    {
        if(wpsd_allow_run == false)return false;

        var run_button_element = $(this);

        run_button_element.hide();

        $('.lds-ellipsis').removeClass('hidden');

        $('.wpsd table span.result').html('');
        $('#quick_test_report').html('');
        $('#quick_test_report').css('display', 'none');

        wpsd_allow_run = false;
        var elements_all_plugins = $("table.wp-list-table a.wpsd_run_test");
        var current_element = -1;

        var myInterval = setInterval(function() 
        {
            if(current_element == elements_all_plugins.length-1)
            {
                //console.log('run_quick_tests END');
                $('.lds-ellipsis').addClass('hidden');
                wpsd_allow_run = true;
                run_button_element.show();
                generate_report();
                clearInterval(myInterval);
                return false;
            }
            current_element++;
            run_test_specific(elements_all_plugins.eq(current_element));
        }, 1500);

        return false;
    });

    $('a.wpsd_run_test').on( "click", function() 
    {
        if(wpsd_allow_run == false)return false;

        wpsd_allow_run = false;

        console.log($(this));
        plugin_element = $(this);

        plugin_element.parent().parent().find('span.result').first().html('');
        $('.lds-ellipsis').removeClass('hidden');

        setTimeout(function() {
            run_test_specific(plugin_element);
            $('.lds-ellipsis').addClass('hidden');
            wpsd_allow_run = true;
        }, 100);

        return false;
    });

    function run_test_specific(plugin_element)
    {
        wpsd_allow_run = false;

        var plugin_file = plugin_element.parent().parent().find('span.plugin_name').text();
        var resultSpan = plugin_element.parent().parent().find('span.result').first();
        var resultColor = plugin_element.parent().parent().find('span.color').first();
        var queries_number = '';

        // 1. Disable only selected plugin
        jQuery.ajax({
            url: '<?php echo home_url();?>?wpsd=1&plugin='+plugin_file+'&time=<?php echo time(); ?>', // time at end is added because of possible caching
            type: "GET",
            success: function (result) {
            },
            async: false
        }).done(function () {
        });

        // 2. Run test

        var ajaxTime= new Date().getTime();

        jQuery.ajax({
            url: '<?php echo home_url();?>?wpsd=3&time=<?php echo time(); ?>', // time at end is added because of possible caching
            type: "GET",
            success: function (result) {
                var findStringQuery = "[QUERIES_NUMBER]";
                var startStringQuery = result.indexOf("[QUERIES_NUMBER]")+findStringQuery.length;
                var endStringQuery   = result.indexOf("[/QUERIES_NUMBER]");
                var queriesNumber = 0;

                if (startStringQuery >= 0)
                {
                    queriesNumber = result.substr(startStringQuery, endStringQuery-startStringQuery);
                    queries_number+= ', DB queries: ' + queriesNumber;
                }

                var findStringPMemory = "[PEAK_MEMORY_USAGE]";
                var startStringPMemory = result.indexOf("[PEAK_MEMORY_USAGE]")+findStringPMemory.length;
                var endStringPMemory   = result.indexOf("[/PEAK_MEMORY_USAGE]");
                var pMemory = 0;

                if (startStringPMemory >= 0)
                {
                    pMemory = result.substr(startStringPMemory, endStringPMemory-startStringPMemory);
                    queries_number+= ', Peak Memory: ' + pMemory+' MB';
                }

                if(plugin_file == 'ALL')
                {
                    $('#max_db').html(queriesNumber);
                    $('#max_mem').html(pMemory);
                }
            },
            async: false
        }).done(function () {
            var totalTime = new Date().getTime()-ajaxTime;

            if(plugin_file == 'ALL')$('#max_time').html(totalTime);

            var max_time = $('#max_time').html();

            resultSpan.parent().find('span.timing').html(totalTime);
            resultSpan.html('Request time: '+totalTime+'ms'+queries_number);

            if(plugin_file == 'ALL' || plugin_file == 'NONE')
            {
                resultSpan.removeClass('orange');
                resultSpan.removeClass('red');
                resultSpan.removeClass('green');

                if(totalTime < 1000){resultSpan.addClass('green');resultColor.html('green')}
                else if(totalTime < 2000){resultSpan.addClass('orange');resultColor.html('orange')}
                else {resultSpan.addClass('red');resultColor.html('red')}
            }
            else
            {
                resultSpan.removeClass('orange');
                resultSpan.removeClass('red');
                resultSpan.removeClass('green');

                if(totalTime > max_time*0.9){resultSpan.addClass('green');resultColor.html('green')}
                else if(totalTime > max_time*0.8){resultSpan.addClass('orange');resultColor.html('orange')}
                else {resultSpan.addClass('red');resultColor.html('red')}
            }
        });

        // 3. re-enable all backed-up plugins list

        jQuery.ajax({
            url: '<?php echo home_url();?>?wpsd=2&plugin='+plugin_file+'&time=<?php echo time(); ?>', // time at end is added because of possible caching
            type: "GET",
            success: function (result) {
            },
            async: false
        }).done(function () {
        });
    }

    function generate_report()
    {
        var reportText = '';

        reportText+= '<h3>Report from testing website <?php echo home_url(); ?></h3>';
        reportText+= 'You will get best results by disabling plugins, ordered from top:<br /><br />';

        var results = [];

        $("table.wp-list-table a.wpsd_run_test").each(function(){

            plugin_element = $(this);

            var niceName = plugin_element.parent().parent().find('span.nice_name').text();
            var resultSpan = plugin_element.parent().parent().find('span.result').first().html();
            var resultTiming = plugin_element.parent().parent().find('span.timing').first().html();
            var resultColor = plugin_element.parent().parent().find('span.color').first().html();

            results.push({timing: resultTiming, name: niceName, results: resultSpan, color: resultColor});
        });


        results.sort(function (x, y) {
            return x.timing - y.timing;
        });

        $('#quick_test_report').html(reportText+makeTableHTML(results));
        $('#quick_test_report').css('display', 'inline-block');
    }

    function makeTableHTML(myArray) {
        var result = "<table border=1>";

        jQuery.each( myArray, function(){
            result += "<tr>";

            myObject = $(this)[0];

            for (var p in myObject) {
                if(p != 'color')
                result += "<td class='"+(myObject['color']=='green'?'':myObject['color'])+"'>"+myObject[p]+"</td>";
            }

            result += "</tr>";
        });
        
        result += "</table>";

        return result;
    }

});



</script>