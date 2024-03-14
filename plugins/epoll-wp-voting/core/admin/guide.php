<?php
$tab = 'general';
if(isset($_GET['tab'])){
    $tab = $_GET['tab'];
}

?>
<div class="wrap">
    <h1><?php esc_attr_e('Tutorials','it_epoll');?></h1>
    <div class="it_epoll_admin_extensions">
        <div class="it_epoll_admin_box">
        <div class="wp-filter">
        <ul class="filter-links">
            <li class="epoll_templates-overview">
                <a href="?page=epoll_docs&tab=general"<?php if($tab=='general') echo esc_attr(' class=current','it_epoll');?>>
                    <?php esc_attr_e('General','it_epoll');?>    
                </a>
            </li>
            <li class="epoll_templates-overview">
                <a href="?page=epoll_docs&tab=voting"<?php if($tab=='voting') echo esc_attr(' class=current','it_epoll');?>>
                    <?php esc_attr_e('Voting','it_epoll');?>    
                </a>
            </li>
            <li class="epoll_templates-overview">
                <a href="?page=epoll_docs&tab=poll"<?php if($tab=='poll') echo esc_attr(' class=current','it_epoll');?>>
                    <?php esc_attr_e('Poll','it_epoll');?>    
                </a>
            </li>
            <li class="epoll_templates-overview">
                <a href="?page=epoll_docs&tab=troubleshooting"<?php if($tab=='troubleshooting') echo esc_attr(' class=current','it_epoll');?>>
                    <?php esc_attr_e('Troubleshooting','it_epoll');?>    
                </a>
            </li>
        </ul>
</div>
        <div class="it_epoll_admin_box_content">
            <div class="it_epoll_admin_box_item">
             <?php get_it_epoll_store_docs($tab);?>
              
            </div>
        </div>
        </div>
    </div>
</div>