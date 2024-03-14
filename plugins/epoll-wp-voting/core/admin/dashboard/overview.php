<table class="wp-table widefat dahsboard_report_table">
<tbody>
    <tr class="table_head">
        <td class="flex_1"><?php esc_attr_e('Contest / Poll Name','it_epoll');?></td>
        <td class="min_width_flex"><?php esc_attr_e('Status','it_epoll');?></td>
        <td class="min_width_flex"><?php esc_attr_e('Total Votes','it_epoll');?></td>
        <td class="min_width_flex"><?php esc_attr_e('Total Candidates','it_epoll');?></td>
        <td><?php esc_attr_e('Result','it_epoll');?></td>
    </tr>
    <?php
            // WP_Query arguments
            $itepollBackednqueryargs = array(
                'post_type'              => array( 'it_epoll_poll','it_epoll_opinion' ),
                'post_status'            => array( 'publish' ),
                'nopaging'               => false,
                'paged'                  => '0',
                'posts_per_page'         => '20',
                'order'                  => 'DESC',
            );

            // The Query
            $itepollBackednquery = new WP_Query( $itepollBackednqueryargs );

            // The Loop
            $i=1;
            if ( $itepollBackednquery->have_posts() ) {
                while ( $itepollBackednquery->have_posts() ) {
                    $itepollBackednquery->the_post();?>
                    
                        <tr>
                        
                        <td class="flex_1 dahsboard_report_h4_col">
                            <h4 class="dahsboard_report_h4">
                           
                                <a href="<?php echo esc_url(get_edit_post_link(get_the_id(),'it_epoll'));?>" target="_blank">
                                    <?php the_title();?>
                                </a>
                                <?php 
                            if(get_post_type(get_the_id()) == 'it_epoll_poll'){?>
                                <span class="it_epolladmin_pro_badge it_epolladmin_pro_badge_blue"><?php esc_attr_e('Voting Contest','it_epoll');?></span>
                           <?php }else{?>
                            <span class="it_epolladmin_pro_badge it_epolladmin_pro_badge_blue"><?php esc_attr_e('Poll','it_epoll');?></span>
                           <?php }?>
                            </h4>
                        </td>
                        <td class="min_width_flex">
                            <?php $poll_status = get_post_meta(get_the_id(),'it_epoll_poll_status',true);
                            if($poll_status == 'live'){?>   
                            <span class="it_epolladmin_pro_badge"><?php echo esc_attr($poll_status,'it_epoll');?></span>                         
                            <?php }else{?>
                                <span class="it_epolladmin_pro_badge it_epolladmin_pro_badge_blue_only"><?php echo esc_attr($poll_status,'it_epoll');?></span>
                            <?php }?>
                            
                        </td>
                        <td class="min_width_flex">
                            <?php 
                            if(get_post_meta(get_the_id(),'it_epoll_vote_total_count',true))  echo esc_attr(get_post_meta(get_the_id(),'it_epoll_vote_total_count',true).' Votes','it_epoll'); else esc_attr_e('0 Votes','it_epoll');;?>
                        </td>
                        <td class="min_width_flex">
                            <?php 
                                if(get_post_meta(get_the_id(),'it_epoll_poll_option',true)){

                                        echo esc_attr(sizeof(get_post_meta(get_the_id(),'it_epoll_poll_option',true)),'it_epoll');	
                                    }else{
                                        echo 0;
                                    }
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=epoll_dashboard&tab=reports&id='.get_the_id()),'it_epoll');?>"><span class="dashicons dashicons-external"></span></a>
                        </td>
                    </tr>
            <?php $i++;	}
            } else {?>
                <tr>
                    <td colspan="6" style="text-align: center;">
                        <h2><?php esc_attr_e('OOPS! You have no poll created yet!','it_epoll');?></h2>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align: center;">
                        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=it_epoll_poll'),'it_epoll');?>" class="button button-secondary"><i class="dashicons dashicons-chart-pie"></i> <?php esc_attr_e('Create New Poll','it_epoll');?></a>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align: center;">
                        
                    </td>
                </tr>
            <?php }

            // Restore original Post Data
            wp_reset_postdata();
            ?>
</tbody>
</table>