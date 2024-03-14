<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
    $msgkey = JSJOBSincluder::getJSModel('employer')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);


JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
    ?>
    <div id="jsjobs-wrapper">
        <div class="page_heading"><?php echo __('Stats', 'js-jobs'); ?></div>
        <?php if(isset(jsjobs::$_data[0]) AND !empty(jsjobs::$_data[0])){ ?>
        <div class="jsjobs-bottom-wrapper">
            <div class="js-topstats">
                <div class="js-mainwrp js-col-xs-12 js-col-md-4">
                    <div class="company tprow">
                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/stats/total-compnies.png">
                        <div class="js-headtext"><?php echo __('Total','js-jobs') .'&nbsp'. __('Companies','js-jobs'); ?></div>
                        <div class="js-count">(<?php echo esc_html(jsjobs::$_data[0]['totalcompanies']); ?>)</div>
                    </div>
                </div>
                <div class="js-mainwrp js-col-xs-12 js-col-md-4">
                    <div class="totaljob tprow">
                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/stats/total-jobs.png">
                        <div class="js-headtext"><?php echo __('Total','js-jobs') .'&nbsp'. __('Jobs','js-jobs'); ?></div>
                        <div class="js-count">(<?php echo esc_html(jsjobs::$_data[0]['totaljobs']); ?>)</div>
                    </div>
                </div>
                <div class="js-mainwrp js-col-xs-12 js-col-md-4">
                    <div class="activejob tprow">
                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/stats/active-jobs.png">
                        <div class="js-headtext"><?php echo __('Active','js-jobs') .'&nbsp'. __('Jobs','js-jobs'); ?></div>
                        <div class="js-count">(<?php echo esc_html(jsjobs::$_data[0]['activejobs']); ?>)</div>
                    </div>
                </div>
                <div class="js-mainwrp js-col-xs-12 js-col-md-4">
                    <div class="appliedjobs tprow">
                        <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/stats/applied-jobs.png">
                        <div class="js-headtext"><?php echo __('Applied Resume','js-jobs'); ?></div>
                        <div class="js-count">(<?php echo esc_html(jsjobs::$_data[0]['totalapplied']); ?>)</div>
                    </div>
                </div>
            </div>
            <table id="js-table" class="jsjobs-first-table">
                <thead class="stats">
                    <tr>
                        <th class="title"><img class="table-image" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/stats/company-stats.png"><?php echo __('Companies','js-jobs');?></th>
                        <th class="publish center"> <?php echo __('Publish','js-jobs');?> </th>
                        <th class="expired center"> <?php echo __('Expired','js-jobs');?> </th>
                    </tr>
                </thead>
                <tbody class="stats">
                    <tr>
                        <td class="title feature"><?php echo __('Featured companies','js-jobs');?></td>
                        <td class="publish center responsive_feature"><?php echo esc_html(jsjobs::$_data[0]['featuredcompanypublish']); ?></td>
                        <td class="expired center responsive_feature"><?php echo esc_html(jsjobs::$_data[0]['featuredcompanyexpire']); ?></td>
                    </tr>
                </tbody>
            </table>
            <table id="js-table" class="jsjobs-second-table">
                <thead class="stats">
                    <tr>
                        <th class="title"><img class="table-image" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/stats/jobs-stats.png"><?php echo __('Jobs','js-jobs');?></th>
                        <th class="publish center"> <?php echo __('Publish','js-jobs');?> </th>
                        <th class="expired center"> <?php echo __('Expired','js-jobs');?> </th>
                    </tr>
                </thead>
                <tbody class="stats jkl">
                    <tr>
                        <td class="title feature"><?php echo __('Featured jobs','js-jobs');?></td>
                        <td class="publish center responsive_feature"><?php echo esc_html(jsjobs::$_data[0]['featuredjobpublish']); ?></td>
                        <td class="expired center responsive_feature"><?php echo esc_html(jsjobs::$_data[0]['featuredjobexpire']); ?></td>
                    </tr>
                    <tr>
                        <td class="title simplejob"><?php echo __('Jobs','js-jobs');?></td>
                        <td class="publish center responsive_simple"><?php echo esc_html(jsjobs::$_data[0]['jobspublish']); ?></td>
                        <td class="expired center responsive_simple"><?php echo esc_html(jsjobs::$_data[0]['jobsexpire']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php 
    } else{
        $msg = __('No record found','js-jobs');
        JSJOBSlayout::getNoRecordFound();
    }
        ?>
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>

<script >
    // responsive tables
    var headertext = [];
    headers = document.querySelectorAll(".jsjobs-first-table th");
    tablerows = document.querySelectorAll(".jsjobs-first-table th");
    tablebody = document.querySelector(".jsjobs-first-table tbody");

    for (var i = 0; i < headers.length; i++) {
        var current = headers[i];
        headertext.push(current.textContent.replace(/\r?\n|\r/, ""));
    }
    for (var i = 0; row = tablebody.rows[i]; i++) {
        for (var j = 0; col = row.cells[j]; j++) {
            col.setAttribute("data-th", headertext[j]);
        }
    }    

    // responsive tables
    var headertext = [];
    headers = document.querySelectorAll(".jsjobs-second-table th");
    tablerows = document.querySelectorAll(".jsjobs-second-table th");
    tablebody = document.querySelector(".jsjobs-second-table tbody");

    for (var i = 0; i < headers.length; i++) {
        var current = headers[i];
        headertext.push(current.textContent.replace(/\r?\n|\r/, ""));
    }
    for (var i = 0; row = tablebody.rows[i]; i++) {
        for (var j = 0; col = row.cells[j]; j++) {
            col.setAttribute("data-th", headertext[j]);
        }
    }    
</script>
</div>
