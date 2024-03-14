<?php
$tab = 'general';
if(isset($_GET['tab'])){
    $tab = $_GET['tab'];
}
?>
<div class="wrap">
    <h1><?php esc_attr_e('Frequently Asked Questions','it_epoll');?></h1>
    <div class="it_epoll_admin_extensions">
        <div class="it_epoll_admin_box">
        <div class="wp-filter">
        <ul class="filter-links">
            <li class="epoll_templates-overview">
                <a href="?page=epoll_faq&tab=general"<?php if($tab == 'general') echo esc_attr(' class=current','it_epoll');?>>
                    <?php esc_attr_e('General','it_epoll');?>    
                </a>
            </li>
           
        </ul>
        <a target="_blank" href="<?php echo esc_url('https://wordpress.org/support/plugin/epoll-wp-voting/','it_epoll');?>" type="button" class="button-primary button-small button-orange right" style="margin-top:12px;" role="button"><span class="upload"><?php esc_attr_e('Create Support Ticket','it_epoll');?></span></a>
        <a target="_blank" href="<?php echo esc_url('https://forum.infotheme.net/','it_epoll');?>" type="button" class="button-primary button-small right" style="margin-top:12px;     margin-right: 12px;" role="button"><span class="upload"><?php esc_attr_e('Ask Another Question?','it_epoll');?></span></a>
</div>
        <div class="it_epoll_admin_box_content">
            <div class="it_epoll_admin_box_item">
                <?php get_it_epoll_store_docs('forum');?>
            </div>
        </div>
        </div>
    </div>
</div>