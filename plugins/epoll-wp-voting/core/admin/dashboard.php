<?php
$tab = 'overview';
if(isset($_GET['tab'])){
    $tab = $_GET['tab'];
}
?>
<div class="wrap">
    <h1><?php esc_attr_e('Dashboard','it_epoll');?></h1>

    <div class="wp-filter">
        <ul class="filter-links">
            <li class="epoll_templates-overview">
                <a href="?page=epoll_dashboard&tab=overview"<?php if($tab == 'overview') echo esc_attr(' class=current','it_epoll');?>>
                    <?php esc_attr_e('Overview','it_epoll');?>    
                </a>
            </li>
            <li class="epoll_templates-reports">
                <a href="?page=epoll_dashboard&tab=reports"<?php if($tab == 'reports' || $tab == 'view_report') echo esc_attr(' class=current','it_epoll');?>>
                    <?php esc_attr_e('Reports','it_epoll');?>
                </a>
            </li>
            <li class="epoll_templates-reports">
                <a href="<?php echo esc_url('https://infotheme.net/item/epoll-pro/','it_epoll');?>" target="_blank">
                    <?php esc_attr_e('Upgrade to Pro','it_epoll');?>
                </a>
            </li>
            <li class="epoll_templates-reports">
            <a href="<?php echo esc_url('https://infotheme.net/documentation/epoll-3-1-pro/getting-started/changelog/','it_epoll');?>" target="_blank">
                    <?php esc_attr_e('What\'s New','it_epoll');?>
                </a>
            </li>
            <li class="epoll_templates-reports">
            <a href="<?php echo esc_url('https://tickets.infotheme.net/','it_epoll');?>" target="_blank">
                    <?php esc_attr_e('Create Ticket','it_epoll');?>
                </a>
            </li>
            <li class="epoll_templates-reports">
            <a href="<?php echo esc_url('https://forum.infotheme.net/','it_epoll');?>" target="_blank">
                    <?php esc_attr_e('Ask A Question','it_epoll');?>
                </a>
            </li>
        </ul>
        <a target="_blank" href="<?php echo esc_url('https://infotheme.net/item/epoll-pro/','it_epoll');?>" class="button-primary button-small right" style="margin-top:10px;" role="button"><span class="upload"><?php esc_attr_e('Buy ePoll PRO','it_epoll');?></span></a>
  
    </div>
    <div class="it_epoll_admin_extensions">

        <?php if($tab == 'overview'){
                include_once('dashboard/overview.php');
            }else if($tab == 'reports'){
                include_once('dashboard/reports.php');
            }else if($tab == 'view_report'){
                do_action('it_epoll_results_view_detailed_reports');
            }?> 
    </div>
</div>