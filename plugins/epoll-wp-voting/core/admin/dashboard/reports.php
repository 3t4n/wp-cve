<?php
	if(!isset($_REQUEST['id'])){
        $latest_cpt = get_posts(
            array('post_type'=>array('it_epoll_poll','it_epoll_opinion'),'numberposts'=>1));
        $pid = $latest_cpt[0]->ID;
    }else{
        $pid = sanitize_text_field($_REQUEST['id']);
    }
?>

<table class="wp-list-table widefat wp-filter wp-filter_reports_epoll_dash">
	<thead>
		<tr>
			<th>
            <form name="it_epoll_form_select_poll" action="<?php echo esc_url(admin_url('admin.php?page=epoll_dashboard&tab=reports'),'it_epoll');?>" method="post">
                <select name="id" onChange="this.form.submit()">
                    <option><?php esc_attr_e('Choose A Poll / Contest','it_epoll');?></option>
                    <optgroup label="<?php esc_attr_e('Voting Contest','it_epoll');?>">
                    <?php
            // WP_Query arguments
            $itepollBackednqueryargs = array(
                'post_type'              => array( 'it_epoll_poll' ),
                'post_status'            => array( 'publish' ),
                'nopaging'               => false,
                'paged'                  => '0',
                'posts_per_page'         => '10',
                'order'                  => 'DESC',
            );

            // The Query
            $itepollBackednquery = new WP_Query( $itepollBackednqueryargs );

            // The Loop
            $i=1;
            if ( $itepollBackednquery->have_posts() ) {
                while ( $itepollBackednquery->have_posts() ) {
                    $itepollBackednquery->the_post();?>
                    <option value="<?php the_id();?>"<?php if(get_the_id() == $pid) echo esc_attr(' selected','it_epoll');?>><?php the_title();?></option>
                    <?php }
            }
            
            // Restore original Post Data
            wp_reset_postdata();
            ?>
            </optgroup>
            <optgroup label="<?php esc_attr_e('Poll','it_epoll');?>">
                    <?php
            // WP_Query arguments
            $itepollBackednqueryargs = array(
                'post_type'              => array( 'it_epoll_opinion' ),
                'post_status'            => array( 'publish' ),
                'nopaging'               => false,
                'paged'                  => '0',
                'posts_per_page'         => '10',
                'order'                  => 'DESC',
            );

            // The Query
            $itepollBackednquery = new WP_Query( $itepollBackednqueryargs );

            // The Loop
            $i=1;
            if ( $itepollBackednquery->have_posts() ) {
                while ( $itepollBackednquery->have_posts() ) {
                    $itepollBackednquery->the_post();?>
                    <option value="<?php the_id();?>"<?php if(get_the_id() == $pid) echo esc_attr(' selected','it_epoll');?>><?php the_title();?></option>
                    <?php }
            }
            
            // Restore original Post Data
            wp_reset_postdata();
            ?>
            </optgroup>
                </select>
        </form>
            </th>
			<th>
				<form class="dash-date-filter dash-date-filter_min-width" action="<?php echo esc_url(admin_url('admin.php?page=epoll_dashboard&tab=reports'),'it_epoll');?>" method="post">
                        <input type="hidden" name="id" value="<?php echo esc_attr($pid,'it_epoll');?>" required/>
						<span><?php esc_attr_e('Export Result As','it_epoll');?> </span>
                       <div class="dash-date_btn_group">
                        <button type="submit" name="html_export" class="button button-primary">HTML </button>
                        <a href="<?php echo esc_url('https://infotheme.net/product/epoll-pro/','it_epoll');?>" name="csv_export" class="button button-secondary">Excel <span class="it_epolladmin_pro_badge"> Pro </span></a>
						<a href="<?php echo esc_url('https://infotheme.net/product/epoll-pro/','it_epoll');?>"  name="pdf_export" class="button button-secondary">PDF <span class="it_epolladmin_pro_badge"> Pro </span></a>
						<a href="<?php echo esc_url('https://infotheme.net/product/epoll-pro/','it_epoll');?>" name="json_export" class="button button-secondary">JSON <span class="it_epolladmin_pro_badge"> Pro </span></a>
                    
                    </div>
                </form>
			</th>
		</tr>
	</thead>
</table>
<table class="wp-list-table widefat it_epoll_dash_table dahsboard_report_table">
	<thead>
		
		<?php 
	
		$it_epoll_poll_vote_total_count = (int)get_post_meta($pid, 'it_epoll_vote_total_count',true);
		$it_epoll_option_names = array();
		$it_epoll_option_names = get_post_meta( $pid, 'it_epoll_poll_option', true );
		$it_epoll_poll_option_id = array();
		$it_epoll_poll_option_id = get_post_meta( $pid, 'it_epoll_poll_option_id', true );


		$i=0;
		$count_array = array();
		$winnerAr=array();
		if($it_epoll_option_names){?>
		
            </thead>
            <tbody class="it_epoll_dash_row">
            <tr class="table_head" id="table_head">
				<td class="flex_1">
					<?php esc_attr_e('Canidate / Option Name','it_epoll');?>
				</td>
				<td class="min_width_flex">
                    <?php esc_attr_e('Total Votes','it_epoll');?>
				</td>
				<td class="min_width_flex">
                    <?php esc_attr_e('Votes in (x/x)','it_epoll');?>
				</td>
				<td class="min_width_flex">
                    <?php esc_attr_e('Live Result','it_epoll');?>
				</td>
				<td>
					<?php esc_attr_e('Entries','it_epoll');?>
				</td>
			</tr>
			<?php
			foreach($it_epoll_option_names as $it_epoll_option_name):
			$it_epoll_poll_vote_count = (int)get_post_meta($pid, 'it_epoll_vote_count_'.(float)$it_epoll_poll_option_id[$i],true);
					
				 array_push($winnerAr,$it_epoll_poll_vote_count);
				 $i++; endforeach;
				 if (count(array_keys($winnerAr, max($winnerAr))) > 1){
					$winner = sizeof($winnerAr)+1;
				 }else{
					$winner = array_keys($winnerAr, max($winnerAr));
					$winner = $winner[0];
				 }

                 
				$j = 0;
		foreach($it_epoll_option_names as $it_epoll_option_name):
			$it_epoll_option_id = $it_epoll_poll_option_id[$j];
			$it_epoll_poll_vote_count = (int)get_post_meta($pid, 'it_epoll_vote_count_'.(float)$it_epoll_option_id,true);
			$it_epoll_poll_vote_percentage = "$it_epoll_poll_vote_count/$it_epoll_poll_vote_total_count";		
			?>
                    <tr id="dv_<?php echo esc_attr($it_epoll_poll_vote_count,'it_epoll');?>">
                        <td class="flex_1">
                            <?php echo esc_attr($it_epoll_option_names[$j],'it_epoll');?>
                        </td>
                        <td class="it_epoll_dash_row_count min_width_flex">
                            <?php echo esc_attr($it_epoll_poll_vote_count,'it_epoll');?>
                        </td>
                        <td class="min_width_flex">
                            <?php echo esc_attr($it_epoll_poll_vote_percentage,'it_epoll');?>
                        </td>
                        <td class="min_width_flex">
                            <?php if($j == $winner){?>
                                <?php if(get_post_meta($pid,'it_epoll_poll_status',true) != 'live'){?>
                                    <span class="it_epoll_winner_result_badge"><?php esc_attr_e('Winner','it_epoll');?></span>
                                <?php }else{?>	
                                <span class="it_epoll_winner_result_badge"><?php esc_attr_e('Winning','it_epoll');?></span> <sup class="it_epoll_winner_result_badge_text"><?php esc_attr_e('Now','it_epoll');?></sup>
                                <?php }}elseif(get_post_meta($pid,'it_epoll_poll_status',true) != 'live'){?>
                                    <span class="it_epoll_leading_result_badge"><?php esc_attr_e('Participated','it_epoll');?></span>
                                <?php }else{?>
                                <span class="it_epoll_leading_result_badge"><?php esc_attr_e('Leading','it_epoll');?></span> <sup class="it_epoll_winner_result_badge_text"><?php esc_attr_e('Now','it_epoll');?></sup>
                            <?php  } ?>
                                
                        </td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=epoll_dashboard&tab=view_report&id='.$pid.'&option='.$it_epoll_option_id),'it_epoll');?>"><span class="dashicons dashicons-external"></span></a>		
                        </td>
                    </tr>
		<?php $j++; endforeach;?>
                    </tbody>
        <script type="text/javascript">
                var main = document.querySelector('.it_epoll_dash_table .it_epoll_dash_row');

                [].map.call( main.children, Object ).sort( function ( a, b ) {
                    if(b.id != 'table_head'){
                        return +b.id.match( /\d+/ ) - +a.id.match( /\d+/ );
                    }
                    
                }).forEach( function ( elem,index ) {
                    main.appendChild( elem );
                   
                });
            </script>
        <?php
			}else{?>
				<tr>
						<td style="flex:1">
							<h2><?php esc_attr_e('OOPS! it seems you didn\'t created any options for this poll!','it_epoll');?></h2>
						</td>
					</tr>
					<tr>
						<td style="flex:1">
							<a href="<?php echo esc_url(admin_url('post.php?post='.$_REQUEST["id"].'&action=edit'),'it_epoll');?>" class="button button-secondary"><i class="dashicons dashicons-chart-pie"></i> <?php esc_attr_e('Edit This Poll','it_epoll');?></a>
						</td>
					</tr>
					<tr>
						<td style="flex:1">
							
						</td>
					</tr>
			<?php }
		?>
</table>