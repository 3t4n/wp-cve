<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb, $wppmfunction;
?>
<div class="wppm_bootstrap">
    <div class="row">
        <div class="col-sm-12">
            <h3>
                <?php _e('Addons','taskbuilder');?>
                <a href="https://taskbuilder.net/support/" class="btn btn-info wppm-help-site-link" style="float:right;margin-right:1% !important;margin-top:-9px !important;"><?php _e('Need Help? Click Here!','taskbuilder');?></a>
            </h3>
            <div class="wppm_padding_space"></div>
            <div class="row">
            <?php echo esc_html_e('Addons are available as shown below:','taskbuilder');?>
            </div>
            <div class="wppm_padding_space"></div>
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12 pricing-widget">
                    <div class="row">
                        <div class="pheader">
                            <h3 class="title"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/calendar.svg'); ?>"><?php echo esc_html_e('Calendar','taskbuilder');?></h3>
                            <h4 class="subtitle">$19.99</h4>
                            <div style="text-align:center">
                                <small class="payment_freq"><?php echo esc_html_e('Per year','taskbuilder');?></small>
                                <small class="payment_freq"><?php echo esc_html_e('(Add-ons subject to yearly license for support and updates.)','taskbuilder');?></small>
                            </div>
                        </div>
                        <div class="pbody">
                            <div class="addon-container">
                                <i class="fas fa-arrow-right"></i>
                                <div class="addon-details">
                                    <?php 
                                    echo esc_html_e('Calendar can help to schedule tasks to gain control over your work.
                                    The use of task calendar  is the main reason that keeps your day organized and supercharge your level of productivity.','taskbuilder');?>
                                </div>
                            </div>
                        </div>
                        <div class="pfooter">
                            <div class="purchase_addon">
                                <a href="https://taskbuilder.net/add-ons/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('Purchase','taskbuilder');?></a>
                            </div>
                            <div>
                                <a href="https://taskbuilder.net/task-calendar/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('View Details','taskbuilder');?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 pricing-widget">
                    <div class="row">
                        <div class="pheader">
                            <h3 class="title"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/cf.svg'); ?>"><?php echo esc_html_e('Custom Fields','taskbuilder');?></h3>
                            <h4 class="subtitle">$19.99</h4>
                            <div style="text-align:center">
                                <small class="payment_freq"><?php echo esc_html_e('Per year','taskbuilder');?></small>
                                <small class="payment_freq"><?php echo esc_html_e('(Add-ons subject to yearly license for support and updates.)','taskbuilder');?></small>
                            </div>
                        </div>
                        <div class="pbody">
                            <div class="addon-container">
                                <i class="fas fa-arrow-right"></i>
                                <div class="addon-details">
                                    <?php echo esc_html_e('Custom Fields extension allows you to collect extra information for your tasks. The Custom Fields extension is a very important tool for gathering accurate details on all your tasks.','taskbuilder');?>
                                </div>
                            </div>
                        </div>
                        <div class="pfooter">
                            <div class="purchase_addon">
                                <a href="https://taskbuilder.net/add-ons/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('Purchase','taskbuilder');?></a>
                            </div>
                            <div>
                                <a href="https://taskbuilder.net/custom-fields/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('View Details','taskbuilder');?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 pricing-widget">
                    <div class="row">
                        <div class="pheader">
                            <h3 class="title"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/time-tracker.svg'); ?>"> <?php echo esc_html_e('Time Tracker','taskbuilder');?></h3>
                            <h4 class="subtitle">$19.99</h4>
                            <div style="text-align:center">
                                <small class="payment_freq"><?php echo esc_html_e('Per year','taskbuilder');?></small>
                                <small class="payment_freq"><?php echo esc_html_e('(Add-ons subject to yearly license for support and updates.)','taskbuilder');?></small>
                            </div>
                        </div>
                        <div class="pbody">
                            <div class="addon-container">
                                <i class="fas fa-arrow-right"></i>
                                <div class="addon-details">
                                    <?php echo esc_html_e('Time Tracker add-on will help you to record and track the time spent on each individual task. We have designed this like a stop-watch for a task so that you can calculate the time spent on perticular task.','taskbuilder');?><
                                    </div>
                                </div>
                        </div>
                        <div class="pfooter">
                            <div class="purchase_addon">
                                <a href="https://taskbuilder.net/add-ons/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('Purchase','taskbuilder');?></a>
                            </div>
                            <div>
                                <a href="https://taskbuilder.net/time-tracker/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('View Details','taskbuilder');?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 pricing-widget">
                    <div class="row">
                        <div class="pheader">
                            <h3 class="title"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/gantt_chart.svg'); ?>"><?php echo esc_html_e('Gantt Chart','taskbuilder');?> </h3>
                            <h4 class="subtitle">$19.99</h4>
                            <div style="text-align:center">
                                <small class="payment_freq"><?php echo esc_html_e('Per year','taskbuilder');?></small>
                                <small class="payment_freq"><?php echo esc_html_e('(Add-ons subject to yearly license for support and updates.)','taskbuilder');?></small>
                            </div>
                        </div>
                        <div class="pbody">
                            <div class="addon-container">
                                <i class="fas fa-arrow-right"></i>
                                <div class="addon-details">
                                    <?php echo esc_html_e('Gantt Chart add-on effectively time manage your tasks and visualize upcoming deadlines for any project. Projects planners also use Gantt charts to maintain a bird&rsquo;s eye view of projects.','taskbuilder');?>
                                </div>
                            </div>
                        </div>
                        <div class="pfooter">
                            <div class="purchase_addon">
                                <a href="https://taskbuilder.net/add-ons/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('Purchase','taskbuilder');?></a>
                            </div>
                            <div>
                                <a href="https://taskbuilder.net/gantt-chart/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('View Details','taskbuilder');?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 pricing-widget">
                    <div class="row">
                        <div class="pheader">
                            <h3 class="title"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/clone_project.svg'); ?>"> <?php echo esc_html_e('Duplicate Project','taskbuilder');?></h3>
                            <h4 class="subtitle">$19.99</h4>
                            <div style="text-align:center">
                                <small class="payment_freq"><?php echo esc_html_e('Per year','taskbuilder');?></small>
                                <small class="payment_freq"><?php echo esc_html_e('(Add-ons subject to yearly license for support and updates.)','taskbuilder');?></small>
                            </div>
                        </div>
                        <div class="pbody">
                            <div class="addon-container">
                                <i class="fas fa-arrow-right"></i>
                                <div class="addon-details">
                                <?php echo esc_html_e('Duplicate Project extention allows you to clone existing project and you will get same project with(tasks+checklists).','taskbuilder');?></div>
                            </div>
                        </div>
                        <div class="pfooter">
                            <div class="purchase_addon">
                                <a href="https://taskbuilder.net/add-ons/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('Purchase','taskbuilder');?></a>
                            </div>
                            <div>
                                <a href="https://taskbuilder.net/duplicate-project/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('View Details','taskbuilder');?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 pricing-widget">
                    <div class="row">
                        <div class="pheader">
                            <h3 class="title"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/reports-icon.svg'); ?>"> <?php echo esc_html_e('Reports','taskbuilder');?></h3>
                            <h4 class="subtitle">$29.99</h4>
                            <div style="text-align:center">
                                <small class="payment_freq"><?php echo esc_html_e('Per year','taskbuilder');?></small>
                                <small class="payment_freq"><?php echo esc_html_e('(Add-ons subject to yearly license for support and updates.)','taskbuilder');?></small>
                            </div>
                        </div>
                        <div class="pbody">
                            <div class="addon-container">
                                <i class="fas fa-arrow-right"></i>
                                <div class="addon-details">
                                <?php echo esc_html_e('Report extention allows you to create detailed project progress reports to view, print or download as a PDF or in CSV. Also, you can download report in csv format.','taskbuilder');?></div>
                            </div>
                        </div>
                        <div class="pfooter">
                            <div class="purchase_addon">
                                <a href="https://taskbuilder.net/add-ons/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('Purchase','taskbuilder');?></a>
                            </div>
                            <div>
                                <a href="https://taskbuilder.net/report/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('View Details','taskbuilder');?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 pricing-widget">
                    <div class="row">
                        <div class="pheader">
                            <h3 class="title"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/user_group.svg'); ?>"> <?php echo esc_html_e('Buddypress Integration','taskbuilder');?></h3>
                            <h4 class="subtitle">$19.99</h4>
                            <div style="text-align:center">
                                <small class="payment_freq"><?php echo esc_html_e('Per year','taskbuilder');?></small>
                                <small class="payment_freq"><?php echo esc_html_e('(Add-ons subject to yearly license for support and updates.)','taskbuilder');?></small>
                            </div>
                        </div>
                        <div class="pbody">
                            <div class="addon-container">
                                <i class="fas fa-arrow-right"></i>
                                <div class="addon-details">
                                <?php echo esc_html_e('Buddypress extention allows you to create and manage projects in departments. This gives the group access to manage the project,tasks from frontend.','taskbuilder');?></div>
                            </div>
                        </div>
                        <div class="pfooter">
                            <div class="purchase_addon">
                                <a href="https://taskbuilder.net/add-ons/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('Purchase','taskbuilder');?></a>
                            </div>
                            <div>
                                <a href="https://taskbuilder.net/buddypress/" target="__blank" type="button" class="btn btn-success"><?php echo esc_html_e('View Details','taskbuilder');?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>