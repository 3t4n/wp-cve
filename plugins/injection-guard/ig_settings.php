<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap ig_settings">

<div class="icon32" id="icon-options-general"><br></div><h2><span class="icon-large icon-settings"></span>&nbsp;Injection Guard - Settings</h2>
<hr />
<div class="list_head">
<a class="ig_how_link">How it works?</a>
<p class="hide welcome-panel"><strong>How it works?</strong><br />It simply log all the unique query strings which are trying to penetrate your website through URLs, either good or bad. By default, neither it blocks any query nor allows. Once you observe the activity on your website and mark parameters as good or bad so it simply denies to blocked parameters. It's not the ultimate solution that you blocked some query parameter, but at least it can alarm you about malicious activity in process so you can take some security measures. In addition, you can <a href="plugin-install.php?tab=search&s=wp+mechanic" target="_blank" title="Click here to install WordPress Mechanic plugin and get $60 worth help for free">install</a> WordPress Mechanic plugin and can ask for a free diagnosis.<a class="ig_dismiss_link welcome-panel-close">Dismiss</a>


</p>


</div>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

<ul class="nav nav-tabs">
	<li class="nav-item mb-0" role="presentation">
		<button class="nav-link active" data-bs-toggle="tab" data-bs-target="#ig_dashboard" type="button" role="tab" aria-controls="ig_dashboard" aria-selected="true"><?php _e('Dashboard', 'injection-guard'); ?></button>
	</li>
	<li class="nav-item mb-0" role="presentation">
		<button class="nav-link" data-bs-toggle="tab" data-bs-target="#ig_logs" type="button" role="tab" aria-controls="ig_logs" aria-selected="false"><?php _e('Log', 'injection-guard'); ?></button>
	</li>

</ul>

<div class="tab-content">

  <div id="ig_dashboard" class="tab-pane fade show active">

    
    <?php @include_once('templates/dashboard.php'); ?>
    
    
  </div>
  
  <div id="ig_logs" class="tab-pane fade">

	<div class="row mt-3">
		<div class="col-md-12">
			<div class="ms-3 h4"><span class="icon-list-alt"></span><?php _e('Logged Requests'); ?>:</div>
		</div>
	</div>
    
	<div class="row mt-3">
		<div class="col-md-12">

			<ul class="ig_bulk_action">			
				<li>																	
					<ul class="mb-3">
						<li>
							<div class="ig_params">
								<label for="">
									<input type="checkbox" name="" id=""> 
									<?php _e('Select All', 'injection-guard'); ?>
								</label>
							</div>
							
							<div class="ig_actions_selected">										
								<a title="<?php _e('Click to whitelist all selected', 'injection-guard'); ?>" data-type="whitelist"><i class="fa fa-thumbs-up text-primary"></i></a>
								
								<a title="<?php _e('Click to blacklist all selected', 'injection-guard'); ?>" data-type="blacklist"><i class="fa fa-thumbs-down text-danger"></i></a>
							</div>

							<hr class="w-50">

						</li>								
					</ul>								
										
				</li>
			</ul>

		

			<ul class="ig_log_list">
		
				<?php if(!empty($ig_logs)): ?>

				<?php 

					$today_date = date('y_m_d', time());
					foreach($ig_logs as $log_head => $params):						
						asort($params);
						
						?>
						<?php $count_blacklisted = isset($ig_blacklisted[$log_head])?count($ig_blacklisted[$log_head]):0; ?>
							<li>
								<i class="fa fa-flag"></i>&nbsp;
						
								<?php echo $log_head.' ('.$count_blacklisted.'/'.count($params).')'; ?>
								<?php if(!empty($params)): ?>
								
									<ul class="mt-2">
										<?php 

											$today_hr = false;
											$counter = 0;
											foreach($params as $param_key => $param):	
												$counter++;

												$current_compare_date = date('y_m_d', $param);
												
												if($counter == 1 && $today_date == $current_compare_date){
													$today_string = __('Today', '');
													echo "<li><strong class='mt-1'>$today_string:</strong></li>";
												}
												if($today_date != $current_compare_date && !$today_hr){
													$today_hr = true;
													echo "<li><hr class='w-50'><li>";
												}
												?>
												<li>
													<div class="ig_params">
													<input type="checkbox" data-uri="<?php echo $log_head; ?>" value="<?php echo $param_key; ?>">
													<i class="fa fa-question-circle"></i> <?php echo $param_key; ?> | <?php echo date(get_option( 'date_format' , "F j, Y"), $param); ?>
													</div>
													
													<div class="ig_actions" data-uri="<?php echo $log_head; ?>" data-val="<?php echo $param_key; ?>">
													
													<?php 
													$blacklisted = (isset($ig_blacklisted[$log_head]) && in_array($param_key, $ig_blacklisted[$log_head]));
													
													?>
													<a title="Click to whitelist" data-type="whitelist" class="<?php echo $blacklisted?'':'hide'; ?>"><i class="fa fa-thumbs-up"></i></a>
													
													<a title="Click to blacklist" data-type="blacklist" class="<?php echo $blacklisted?'hide':''; ?>"><i class="fa fa-thumbs-down text-danger"></i></a>
													</div>
												</li>
										<?php 

											endforeach; ?>        
									</ul>

								
								<?php endif; ?>         
							</li>
					<?php endforeach; ?>  
				<?php else: ?>
				<li>There are no logged requests to show at the moment.</li>      
				<?php endif; ?>    
			</ul>

		</div>
	</div>


<div class="wm_help">
<div class="wp_sep"></div>
<strong>Need help?</strong><br />

<a href="https://wordpress.org/plugins/wp-mechanic/" target="_blank" title="WordPress Mechanic"><img src="https://plugins.svn.wordpress.org/wp-mechanic/assets/icon-128x128.png" /></a>
<a href="plugin-install.php?tab=search&s=wp+mechanic" target="_blank" style="border-left:4px solid #eee; margin-left:60px;" title="Click here to install WordPress Mechanic plugin and get $60 worth help for free"><img src="https://plugins.svn.wordpress.org/wp-mechanic/assets/banner-772x250.png" width="60%" /></a>
<div class="wp_sep last"></div>
</div>
   

	<input type="hidden" name="ig_key" value="">   
   <p class="submit"><?php if(!empty($ig_logs)): ?><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"><?php endif; ?></p>
    
  </div>
</div>







</form>



</div>


<script type="text/javascript" language="javascript">

jQuery(document).ready(function($) {

	jQuery('.dismiss_link').click(function(){
		jQuery(this).parent().hide();
		jQuery('.useful_link').fadeIn();
	});

	
	jQuery('.useful_link').click(function(){
		jQuery('.dismiss_link').parent().show();
		jQuery(this).fadeOut();
	});	
	
	jQuery('.ig_how_link').click(function(){
		jQuery('.ig_dismiss_link').parent().show();
		jQuery(this).fadeOut();
	});	
	
	jQuery('.ig_dismiss_link').click(function(){
		jQuery(this).parent().hide();
		jQuery('.ig_how_link').fadeIn();
	});	

	jQuery('body').off('click', '.ig_actions a');
	jQuery('body').on('click', '.ig_actions a', function(){
		if(!ig_obj.ig_super_admin){ alert(ig_obj.ig_super_admin_msg); return false; }
		var aClicked = jQuery(this);
		jQuery.blockUI({message:''});
		jQuery.post(ajaxurl, {action: 'ig_update','type':aClicked.attr('data-type'),'val':aClicked.parent().attr('data-val'), 'uri_index':aClicked.parent().attr('data-uri'), ig_nonce: ig_obj.ig_nonce}, function(response) {
			response = jQuery.parseJSON(response);
			
			if(response.status==true){
				
				aClicked.siblings().show();
				aClicked.hide();
			}
			setTimeout(function(){ jQuery.unblockUI(); }, 1000);
		});
	});

	//jQuery('.useful_link').click();


	// Find list items representing folders and
	// style them accordingly.  Also, turn them
	// into links that can expand/collapse the
	// tree leaf.
	$('.logs_area li > ul').each(function(i) {
		// Find this list's parent list item.
		var parent_li = $(this).parent('li');
	
		// Style the list item as folder.
		parent_li.addClass('folder');
	
		// Temporarily remove the list from the
		// parent list item, wrap the remaining
		// text in an anchor, then reattach it.
		var sub_ul = $(this).remove();
		parent_li.wrapInner('<a/>').find('a').click(function() {
			// Make the anchor toggle the leaf display.
	
		var options = {};
		sub_ul.toggle();// 'pulsate', options, 200 );
	
		});
		parent_li.append(sub_ul);
	});
	
	// Hide all lists except the outermost.
	$('.logs_area ul ul').hide();

});

</script>
<style type="text/css">
.update-nag,
#message {
    display: none;
}
[class^="icon-"], [class*=" icon-"] {
    margin-right: 6px;
}
#wpcontent, #wpfooter {
    background-color: #fff;
}
.welcome-panel{
	padding: 22px 10px 16px;
}
</style>