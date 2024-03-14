<?php

	if(!isset($_REQUEST['id']) && !isset($_REQUEST['option'])){
        $latest_cpt = get_posts(
            array('post_type'=>array('it_epoll_poll','it_epoll_opinion'),'numberposts'=>1));
        $pid = $latest_cpt[0]->ID;
		
		$it_epoll_poll_option_id = array();
		$it_epoll_poll_option_id = get_post_meta( $pid, 'it_epoll_poll_option_id', true );
		if($it_epoll_poll_option_id){
			$option = $it_epoll_poll_option_id[0];
		}else{
			$option = 0;
		}
		
		$it_epoll_option_names = array();
		$it_epoll_option_names = get_post_meta( $pid, 'it_epoll_poll_option', true );
    }else{
        $pid = sanitize_text_field($_REQUEST['id']);
        $option = sanitize_text_field($_REQUEST['option']);

		$it_epoll_poll_option_id = array();
		$it_epoll_poll_option_id = get_post_meta( $pid, 'it_epoll_poll_option_id', true );

		$it_epoll_option_names = array();
		$it_epoll_option_names = get_post_meta( $pid, 'it_epoll_poll_option', true );
    }
?>

<table class="wp-list-table widefat wp-filter wp-filter_reports_epoll_dash">
	<thead>
		<tr>
			<th>
			<a href="<?php echo esc_url(admin_url('admin.php?page=epoll_dashboard&tab=reports&id='.$pid),'it_epoll');?>"><i class="dashicons dashicons-arrow-left-alt"></i> Go Back</a>
			</th>
			<th>
            <form name="it_epoll_form_select_poll" action="<?php echo esc_url(admin_url('admin.php?page=epoll_dashboard&tab=reports'),'it_epoll');?>" method="post">
                <select name="id" onChange="this.form.submit()">
                    <option><?php esc_attr_e('Choose A Candidate / Option','it_epoll');?></option>
					<?php if($it_epoll_poll_option_id){
						$i = 0;
						foreach($it_epoll_poll_option_id as $option_id){?>
							<option value="<?php echo esc_attr($option_id,'it_epoll');?>" <?php if($option == $option_id) echo esc_attr(' selected','it_epoll');?>><?php echo esc_attr($it_epoll_option_names[$i],'it_epoll');?></option>
						<?php
							$i++;
						}
					}?>
                </select>
        </form>
            </th>
			<th>
				<form class="dash-date-filter" action="<?php echo esc_url(admin_url('admin.php?page=epoll_dashboard&tab=reports'),'it_epoll');?>" method="post">
                        <span><?php esc_attr_e('From','it_epoll');?></span>
                        <input type="date" class="widefat" name="from" value="" placeholder="<?php esc_attr_e('Choose A Date','it_epoll');?>"/>
                        <span><?php esc_attr_e('To','it_epoll');?></span>
                        <input type="hidden" name="id" value="<?php echo esc_attr($pid,'it_epoll');?>" required/>
                        <input type="date" class="widefat" name="to"  value="" placeholder="<?php esc_attr_e('Choose A Date','it_epoll');?>"/>
                       <div class="dash-date_btn_group">
                        <button type="submit" name="clear" class="button button-secondary"><?php esc_attr_e('Clear','it_epoll');?></button>
                        <button type="submit" name="submit" class="button button-primary"><?php esc_attr_e('Apply','it_epoll');?></button>
                    </div>
                </form>
			</th>
		</tr>
	</thead>
</table>

<div class="it_epoll_system_upgrade_pro">
	<div class="it_epoll_system_upgrade_pro_dotted_line"></div>
	<div class="dashicons dashicons-unlock it_epoll_system_upgrade_pro_icon"></div>
	<a href="<?php echo esc_url('https://infotheme.net/product/epoll-pro/','it_epoll');?>" class="it_edb_submit it_epoll_system_upgrade_pro_btn"><?php esc_attr_e('Upgrade to Pro for all Features','it_epoll');?></a>
</div>
<table class="wp-table widefat fixed striped posts it_epoll_sys_show_voter_table">
	<thead>
		<tr>
			<th>
				<?php esc_attr_e('Voter Name','it_epoll');?>
			</th>
			<th>
				<?php esc_attr_e('Contact Details','it_epoll');?>
			</th>
			<th>
				<?php esc_attr_e('Status','it_epoll');?>			
			</th>
			<th>
				<?php esc_attr_e('Action','it_epoll');?>	
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				John test
			</td>
			<td>
				<table class="wp-table it_epoll_sys_show_voter">
					<tr>
						<th>Email :</th>
						<th>test@test.com</th>
					</tr>
					<tr style="display: none;">
						<th>Phone</th>
						<th>0123456789</th>
					</tr>
					<tr style="display: none;">
						<th>Address</th>
						<th>Test House, Road, City, USA.</th>
					</tr>
					<tr style="display: none;">
						<th>Gender</th>
						<th>Male</th>
					</tr>
					<tr style="display: none;">
						<th>Date Of Birth</th>
						<th>04/04/1995</th>
					</tr>
				</table>
			</td>
			<th>
				Verified Voter
			</td>
			<td>
				<button type="button" class="button button-secondary it_epoll_sys_show_voter_btn">Delete</button>
			</td>
		</tr>
		<tr class="it_epoll_system_upgrade_pro_blur">
			<td>
				Ziyan test
			</td>
			<td>
				<table class="wp-table it_epoll_sys_show_voter">
					<tr>
						<th>Email :</th>
						<th>testziyan@test.com</th>
					</tr>
					<tr style="display: none;">
						<th>Phone</th>
						<th>0123456789</th>
					</tr>
					<tr style="display: none;">
						<th>Address</th>
						<th>Test House, Road, City, USA.</th>
					</tr>
					<tr style="display: none;">
						<th>Gender</th>
						<th>Male</th>
					</tr>
					<tr style="display: none;">
						<th>Date Of Birth</th>
						<th>04/04/1995</th>
					</tr>
				</table>
			</td>
			<th>
				Unverified Voter
			</td>
			<td>
				<button type="button" class="button button-secondary it_epoll_sys_show_voter_btn">Delete</button>
			</td>
		</tr>
		<tr class="it_epoll_system_upgrade_pro_blur">
			<td>
				Ziyan test
			</td>
			<td>
				<table class="wp-table it_epoll_sys_show_voter">
					<tr>
						<th>Email :</th>
						<th>testziyan@test.com</th>
					</tr>
					<tr style="display: none;">
						<th>Phone</th>
						<th>0123456789</th>
					</tr>
					<tr style="display: none;">
						<th>Address</th>
						<th>Test House, Road, City, USA.</th>
					</tr>
					<tr style="display: none;">
						<th>Gender</th>
						<th>Male</th>
					</tr>
					<tr style="display: none;">
						<th>Date Of Birth</th>
						<th>04/04/1995</th>
					</tr>
				</table>
			</td>
			<th>
				Unverified Voter
			</td>
			<td>
				<button type="button" class="button button-secondary it_epoll_sys_show_voter_btn">Delete</button>
			</td>
		</tr>
		<tr class="it_epoll_system_upgrade_pro_blur">
			<td>
				Ziyan test
			</td>
			<td>
				<table class="wp-table it_epoll_sys_show_voter">
					<tr>
						<th>Email :</th>
						<th>testziyan@test.com</th>
					</tr>
					<tr style="display: none;">
						<th>Phone</th>
						<th>0123456789</th>
					</tr>
					<tr style="display: none;">
						<th>Address</th>
						<th>Test House, Road, City, USA.</th>
					</tr>
					<tr style="display: none;">
						<th>Gender</th>
						<th>Male</th>
					</tr>
					<tr style="display: none;">
						<th>Date Of Birth</th>
						<th>04/04/1995</th>
					</tr>
				</table>
			</td>
			<th>
				Unverified Voter
			</td>
			<td>
				<button type="button" class="button button-secondary it_epoll_sys_show_voter_btn">Delete</button>
			</td>
		</tr>
		<tr class="it_epoll_system_upgrade_pro_blur">
			<td>
				Ziyan test
			</td>
			<td>
				<table class="wp-table it_epoll_sys_show_voter">
					<tr>
						<th>Email :</th>
						<th>testziyan@test.com</th>
					</tr>
					<tr style="display: none;">
						<th>Phone</th>
						<th>0123456789</th>
					</tr>
					<tr style="display: none;">
						<th>Address</th>
						<th>Test House, Road, City, USA.</th>
					</tr>
					<tr style="display: none;">
						<th>Gender</th>
						<th>Male</th>
					</tr>
					<tr style="display: none;">
						<th>Date Of Birth</th>
						<th>04/04/1995</th>
					</tr>
				</table>
			</td>
			<th>
				Unverified Voter
			</td>
			<td>
				<button type="button" class="button button-secondary it_epoll_sys_show_voter_btn">Delete</button>
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4">
				<form class="dash-date-filter" action="<?php echo esc_url(admin_url('admin.php?page=epoll_dashboard&tab=reports'),'it_epoll');?>" method="post">
					<input type="hidden" name="id" value="<?php echo esc_attr($pid,'it_epoll');?>" required/>
					<span><?php esc_attr_e('Export Result As','it_epoll');?></span>
				<div class="dash-date_btn_group">
						<a href="<?php echo esc_url('https://infotheme.net/product/epoll-pro/','it_epoll');?>" name="html_export" class="button button-secondary"><?php esc_attr_e('HTML','it_epoll');?> <span class="it_epolladmin_pro_badge"> <?php esc_attr_e('Pro','it_epoll');?> </span></a>
						<a href="<?php echo esc_url('https://infotheme.net/product/epoll-pro/','it_epoll');?>" name="csv_export" class="button button-secondary"><?php esc_attr_e('Excel','it_epoll');?> <span class="it_epolladmin_pro_badge"> <?php esc_attr_e('Pro','it_epoll');?> </span></a>
						<a href="<?php echo esc_url('https://infotheme.net/product/epoll-pro/','it_epoll');?>"  name="pdf_export" class="button button-secondary"><?php esc_attr_e('PDF','it_epoll');?> <span class="it_epolladmin_pro_badge"> <?php esc_attr_e('Pro','it_epoll');?> </span></a>
						<a href="<?php echo esc_url('https://infotheme.net/product/epoll-pro/','it_epoll');?>" name="json_export" class="button button-secondary"><?php esc_attr_e('JSON','it_epoll');?> <span class="it_epolladmin_pro_badge"> <?php esc_attr_e('Pro','it_epoll');?> </span></a>
					</div>
				</form>
			</td>
		</tr>
	</tfoot>
</table>