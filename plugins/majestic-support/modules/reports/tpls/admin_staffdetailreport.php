<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('majesticsupport-jquery-ui-css', MJTC_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');
wp_enqueue_style('majesticsupport-status-graph', MJTC_PLUGIN_URL . 'includes/css/status_graph.css');
wp_enqueue_script('majesticsupport-google-charts', MJTC_PLUGIN_URL . 'includes/js/google-charts.js');
wp_register_script( 'majesticsupport-google-charts-handle', '' );
wp_enqueue_script( 'majesticsupport-google-charts-handle' );
$mjtc_scriptdateformat = MJTC_includer::MJTC_getModel('majesticsupport')->MJTC_getDateFormat();
$majesticsupport_js ="
    jQuery(document).ready(function ($) {
        $('.custom_date').datepicker({
            dateFormat: '". esc_html($mjtc_scriptdateformat)."'
        });
        google.load('visualization', '1', {packages:['corechart']});
		google.setOnLoadCallback(drawChart);
	});

	function drawChart() {
      	var data = new google.visualization.DataTable();
		data.addColumn('date', '". esc_html(__('Dates','majestic-support'))."');
        data.addColumn('number', '". esc_html(__('New','majestic-support'))."');
        data.addColumn('number', '". esc_html(__('Answered','majestic-support'))."');
        data.addColumn('number', '". esc_html(__('Pending','majestic-support'))."');
        data.addColumn('number', '". esc_html(__('Overdue','majestic-support'))."');
        data.addColumn('number', '". esc_html(__('Closed','majestic-support'))."');
		data.addRows([
			". majesticsupport::$_data['line_chart_json_array']."
        ]);

        var options = {
          colors:['#159667','#2168A2','#f39f10','#B82B2B','#3D355A'],
          curveType: 'function',
          legend: { position: 'bottom' },
          pointSize: 6,
		  // This line will make you select an entire row of data at a time
		  focusTarget: 'category',
		  chartArea: {width:'90%',top:50}
		};

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
    }
";
wp_add_inline_script('majesticsupport-google-charts-handle',$majesticsupport_js);
$majesticsupport_js ="
	function resetFrom(){
		document.getElementById('date_start').value = '';
		document.getElementById('date_end').value = '';
		document.getElementById('majesticsupportform').submit();
	}

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
MJTC_message::MJTC_getMessage();
$t_name = 'getstaffmemberexportbystaffid';
$link_export = admin_url('admin.php?page=majesticsupport_export&task='.esc_attr($t_name).'&action=mstask&uid='.esc_attr(majesticsupport::$_data['filter']['uid']).'&date_start='.esc_attr(majesticsupport::$_data['filter']['date_start']).'&date_end='.esc_attr(majesticsupport::$_data['filter']['date_end']));
$show_flag = 0;
?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
    	<?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('agentdetail_reports'); ?>
        <div id="msadmin-data-wrp">
		    <?php
			$agent = majesticsupport::$_data['staff_report'];
			?>
			<a href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport_reports&mjslay=staffdetailreport&id='.esc_attr($agent->id).'&date_start='.esc_attr(majesticsupport::$_data['filter']['date_start']).'&date_end='.esc_attr(majesticsupport::$_data['filter']['date_end']))); ?>"></a>
			<form class="mjtc-filter-form mjtc-report-form" name="majesticsupportform" id="majesticsupportform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_reports&mjslay=staffdetailreport&id=".esc_attr(majesticsupport::$_data['staff_report']->id)),"staff-detail-report")); ?>">
			    <?php
			        $curdate = date_i18n('Y-m-d');
			        $enddate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
			        $date_start = !empty(majesticsupport::$_data['filter']['date_start']) ? majesticsupport::$_data['filter']['date_start'] : $curdate;
			        $date_end = !empty(majesticsupport::$_data['filter']['date_end']) ? majesticsupport::$_data['filter']['date_end'] : $enddate;
			    	echo wp_kses(MJTC_formfield::MJTC_text('date_start', date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($date_start)), array('class' => 'custom_date mjtc-form-date-field','placeholder' => esc_html(__('Start Date','majestic-support')))), MJTC_ALLOWED_TAGS);
			    	echo wp_kses(MJTC_formfield::MJTC_text('date_end', date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($date_end)), array('class' => 'custom_date mjtc-form-date-field','placeholder' => esc_html(__('End Date','majestic-support')))), MJTC_ALLOWED_TAGS);
			    	echo wp_kses(MJTC_formfield::MJTC_hidden('MS_form_search', 'MS_SEARCH'), MJTC_ALLOWED_TAGS);
				?>
			    <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('go', esc_html(__('Search', 'majestic-support')), array('class' => 'button mjtc-form-search')), MJTC_ALLOWED_TAGS); ?>
				<?php echo wp_kses(MJTC_formfield::MJTC_button('reset', esc_html(__('Reset', 'majestic-support')), array('class' => 'button mjtc-form-reset', 'onclick' => 'resetFrom();')), MJTC_ALLOWED_TAGS); ?>
			</form>
			<div class="mjtc-admin-report">
				<div class="mjtc-admin-subtitle"><?php echo esc_html(__('Agent Statistics','majestic-support')); ?></div>
				<div class="mjtc-admin-rep-graph" id="curve_chart" style="height:400px;width:98%; "></div>
			</div>
			<div class="mjtc-admin-report">
				<div class="mjtc-admin-staff-list p0">
					<?php
						if(!empty($agent)){ ?>
						<div class="mjtc-admin-staff-wrapper">
							<div class="mjtc-admin-staff-cnt">
								<div class="mjtc-report-staff-image">
									<?php
										if($agent->photo){
					                        $maindir = wp_upload_dir();
					                        $path = $maindir['baseurl'];
											$imageurl = $path."/".majesticsupport::$_config['data_directory']."/staffdata/staff_".$agent->id."/".$agent->photo;
										}else{
											$imageurl = MJTC_PLUGIN_URL."includes/images/user.png";
										}
									?>
									<img alt="<?php echo esc_html(__('staff image','majestic-support')); ?>" class="mjtc-report-staff-pic" src="<?php echo esc_url($imageurl); ?>" />
								</div>
								<div class="mjtc-report-staff-cnt">
									<div class="mjtc-report-staff-info mjtc-report-staff-name">
										<?php
											if($agent->firstname && $agent->lastname){
												$agentname = $agent->firstname . ' ' . $agent->lastname;
											}else{
												$agentname = $agent->display_name;
											}
											echo esc_html($agentname);
										?>
									</div>
									<div class="mjtc-report-staff-info mjtc-report-staff-email">
										<?php
											if($agent->display_name){
												$username = $agent->display_name;
											}else{
												$username = $agent->user_nicename;
											}
											echo esc_html($username);
										?>
									</div>
									<div class="mjtc-report-staff-info mjtc-report-staff-email">
										<?php
											if($agent->email){
												$email = $agent->email;
											}else{
												$email = $agent->user_email;
											}
											echo esc_html($email);
										?>
									</div>
								</div>
							</div>
							<div class="mjtc-admin-staff-boxes">
								<?php
								$rating_class = 'box6';
									if(in_array('feedback', majesticsupport::$_active_addons)){
										if($agent->avragerating > 4){
											$rating_class = 'box65';
										}elseif($agent->avragerating > 3){
											$rating_class = 'box64';
										}elseif($agent->avragerating > 2){
											$rating_class = 'box63';
										}elseif($agent->avragerating > 1){
											$rating_class = 'box62';
										}elseif($agent->avragerating > 0){
											$rating_class = 'box61';
										}
									}
									if(in_array('timetracking', majesticsupport::$_active_addons)){
										$hours = floor($agent->time[0] / 3600);
							            $mins = floor($agent->time[0] / 60);
							            $mins = floor($mins % 60);
							            $secs = floor($agent->time[0] % 60);
							            $avgtime = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
							        }
						        ?>
								<?php
									$open_percentage = 0;
									$close_percentage = 0;
									$overdue_percentage = 0;
									$answered_percentage = 0;
									$pending_percentage = 0;
									if(isset($agent) && isset($agent->allticket) && $agent->allticket != 0){
									    $open_percentage = round(($agent->openticket / $agent->allticket) * 100);
									    $close_percentage = round(($agent->closeticket / $agent->allticket) * 100);
									    $overdue_percentage = round(($agent->overdueticket / $agent->allticket) * 100);
									    $answered_percentage = round(($agent->answeredticket / $agent->allticket) * 100);
									    $pending_percentage = round(($agent->pendingticket / $agent->allticket) * 100);
									}
									if(isset($agent) && isset($agent->allticket) && $agent->allticket != 0){
									    $allticket_percentage = 100;
									}
								?>
								<div class="mjtc-support-count">
								    <div class="mjtc-support-link">
								        <a class="mjtc-support-link mjtc-support-green" href="#" data-tab-number="1" title="<?php echo esc_attr(__('Open Ticket', 'majestic-support')); ?>">
								            <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($open_percentage); ?>" data-tab-number="1">
								                <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($open_percentage); ?>">
								                    <div class="circle">
								                        <div class="mask full">
								                             <div class="fill mjtc-support-open"></div>
								                        </div>
								                        <div class="mask half">
								                            <div class="fill mjtc-support-open"></div>
								                            <div class="fill fix"></div>
								                        </div>
								                        <div class="shadow"></div>
								                    </div>
								                    <div class="inset">
								                    </div>
								                </div>
								            </div>
								            <div class="mjtc-support-link-text mjtc-support-green">
								                <?php
								                    $data = esc_html(__('Open', 'majestic-support')).' ( '.esc_html($agent->openticket).' )';
								                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
								                ?>
								            </div>
								        </a>
								    </div>
								    <div class="mjtc-support-link">
								        <a class="mjtc-support-link mjtc-support-brown" href="#" data-tab-number="2" title="<?php echo esc_attr(__('answered ticket', 'majestic-support')); ?>">
								            <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($answered_percentage); ?>" >
								                <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($answered_percentage); ?>">
								                    <div class="circle">
								                        <div class="mask full">
								                             <div class="fill mjtc-support-answer"></div>
								                        </div>
								                        <div class="mask half">
								                            <div class="fill mjtc-support-answer"></div>
								                            <div class="fill fix"></div>
								                        </div>
								                        <div class="shadow"></div>
								                    </div>
								                    <div class="inset">
								                    </div>
								                </div>
								            </div>
								            <div class="mjtc-support-link-text mjtc-support-brown">
								                <?php
								                    $data = esc_html(__('Answered', 'majestic-support')).' ( '. esc_html($agent->answeredticket).' )';
								                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
								                ?>
								            </div>
								        </a>
								    </div>
								    <div class="mjtc-support-link">
					                    <a class="mjtc-support-link mjtc-support-yellow" href="#" data-tab-number="3" title="<?php echo esc_attr(__('pending ticket', 'majestic-support')); ?>">
					                        <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($pending_percentage); ?>">
					                            <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($pending_percentage); ?>">
					                                <div class="circle">
					                                    <div class="mask full">
					                                         <div class="fill mjtc-support-pending"></div>
					                                    </div>
					                                    <div class="mask half">
					                                        <div class="fill mjtc-support-pending"></div>
					                                        <div class="fill fix"></div>
					                                    </div>
					                                    <div class="shadow"></div>
					                                </div>
					                                <div class="inset">
					                                </div>
					                            </div>
					                        </div>
					                        <div class="mjtc-support-link-text mjtc-support-yellow">
					                            <?php
					                                $data = esc_html(__('Pending', 'majestic-support')).' ( '. esc_html($agent->pendingticket).' )';
					                                echo wp_kses($data, MJTC_ALLOWED_TAGS);
					                            ?>
					                        </div>
					                    </a>
					                </div>
					                <?php if(in_array('overdue', majesticsupport::$_active_addons)){ ?>
									    <div class="mjtc-support-link">
									        <a class="mjtc-support-link mjtc-support-orange" href="#" data-tab-number="4" title="<?php echo esc_attr(__('overdue ticket', 'majestic-support')); ?>">
									            <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($overdue_percentage); ?>" >
									                <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($overdue_percentage); ?>">
									                    <div class="circle">
									                        <div class="mask full">
									                             <div class="fill mjtc-support-overdue"></div>
									                        </div>
									                        <div class="mask half">
									                            <div class="fill mjtc-support-overdue"></div>
									                            <div class="fill fix"></div>
									                        </div>
									                        <div class="shadow"></div>
									                    </div>
									                    <div class="inset">
									                    </div>
									                </div>
									            </div>
									            <div class="mjtc-support-link-text mjtc-support-orange">
									                <?php
									                    $data = esc_html(__('Overdue', 'majestic-support')).' ( '. esc_html($agent->overdueticket).' )';
									                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
									                ?>
									            </div>
									        </a>
									    </div>
									<?php } ?>
								    <div class="mjtc-support-link">
								        <a class="mjtc-support-link mjtc-support-red" href="#" data-tab-number="5" title="<?php echo esc_attr(__('Close Ticket', 'majestic-support')); ?>">
								            <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($close_percentage); ?>" >
								                <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($close_percentage); ?>">
								                    <div class="circle">
								                        <div class="mask full">
								                             <div class="fill mjtc-support-close"></div>
								                        </div>
								                        <div class="mask half">
								                            <div class="fill mjtc-support-close"></div>
								                            <div class="fill fix"></div>
								                        </div>
								                        <div class="shadow"></div>
								                    </div>
								                    <div class="inset">
								                    </div>
								                </div>
								            </div>
								            <div class="mjtc-support-link-text mjtc-support-red">
								                <?php
								                    $data = esc_html(__('Closed', 'majestic-support')).' ( '. esc_html($agent->closeticket).' )';
								                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
								                ?>
								            </div>
								        </a>
								    </div>
									<?php if(in_array('feedback', majesticsupport::$_active_addons)){ ?>
										<div class="mjtc-support-link <?php echo esc_attr($rating_class)?>">
											<a href="#" class="mjtc-support-link mjtc-support-mariner" title="<?php echo esc_attr(__('Average rating', 'majestic-support')); ?>">
												<span class="mjtc-report-box-number">
													<?php if($agent->avragerating > 0){ ?>
														<span class="rating" ><?php echo esc_html(round($agent->avragerating,1)); ?></span>/5
													<?php }else{ ?>
														NA
													<?php } ?>
												</span>
												<span class="mjtc-report-box-title"><?php echo esc_html(__('Average rating','majestic-support')); ?></span>
												<div class="mjtc-report-box-color"></div>
											</a>
										</div>
									<?php } ?>
									<?php if(in_array('timetracking', majesticsupport::$_active_addons)){ ?>
										<div class="mjtc-support-link">
											<a href="#" class="mjtc-support-link mjtc-support-purple" title="<?php echo esc_attr(__('Average time', 'majestic-support')); ?>">
												<span class="mjtc-report-box-number">
													<span class="time" >
														<?php echo esc_html($avgtime); ?>
													</span>
													<span class="exclamation" >
														<?php
														if($agent->time[1] != 0){
											            	echo esc_html('!');
											            }
														?>
													</span>
												</span>
												<span class="mjtc-report-box-title"><?php echo esc_html(__('Average time','majestic-support')); ?></span>
												<div class="mjtc-report-box-color"></div>
											</a>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php
					} ?>
				</div>
			</div>
			<div class="mjtc-admin-report">
				<div class="mjtc-admin-subtitle">
					<?php echo esc_html(__('Tickets','majestic-support')); ?>
				</div>
				<?php
				if(!empty(majesticsupport::$_data['staff_tickets'])){ ?>
					<table id="majestic-support-table" class="mjtc-admin-report-tickets">
						<tr class="majestic-support-table-heading">
							<th class="left"><?php echo esc_html(__('Subject','majestic-support')); ?></th>
							<th><?php echo esc_html(__('Status','majestic-support')); ?></th>
							<th><?php echo esc_html(__('Priority','majestic-support')); ?></th>
							<th><?php echo esc_html(__('Created','majestic-support')); ?></th>
							<?php if(in_array('feedback', majesticsupport::$_active_addons)){ ?>
								<th><?php echo esc_html(__('Rating','majestic-support')); ?></th>
							<?php }?>
							<?php if(in_array('timetracking', majesticsupport::$_active_addons)){ ?>
								<th><?php echo esc_html(__('Time Taken','majestic-support')); ?></th>
							<?php }?>
						</tr>
						<?php
						foreach(majesticsupport::$_data['staff_tickets'] AS $ticket) {
							if(in_array('timetracking', majesticsupport::$_active_addons)){
								$hours = floor($ticket->time / 3600);
					            $mins = floor($ticket->time / 60);
					            $mins = floor($mins % 60);
					            $secs = floor($ticket->time % 60);
					            $avgtime = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
					        }
							if(in_array('feedback', majesticsupport::$_active_addons)){
					            $rating_color = 0;
					            if($ticket->rating > 4){
					            	$rating_color = '#ea1d22';
					            }elseif($ticket->rating > 3){
					            	$rating_color = '#f58634';
					            }elseif($ticket->rating > 2){
					            	$rating_color = '#a8518a';
					            }elseif($ticket->rating > 1){
					            	$rating_color = '#0098da';
					            }elseif($ticket->rating > 0){
					            	$rating_color = '#069a2e';
					            }
					        } ?>
							<tr>
								<td class="overflow left">
									<span class="majestic-support-table-responsive-heading">
										<?php echo esc_html(__('Subject','majestic-support')); ?> :
									</span>
									<a title="<?php echo esc_attr(__('Ticket', 'majestic-support')); ?>" target="_blank" href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid='.esc_attr($ticket->id))); ?>"><?php echo esc_html($ticket->subject); ?></a>
								<?php
								if($agent->id != $ticket->staffid){
									$show_flag = 1;
									?>
									<font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font>
								<?php } ?>
								</td>
								<td >
									<span class="majestic-support-table-responsive-heading">
										<?php echo esc_html(__('Status','majestic-support')); ?> :
									</span>
									<?php
							            // 0 -> New Ticket
							            // 1 -> Waiting admin/staff reply
							            // 2 -> in progress
							            // 3 -> waiting for customer reply
							            // 4 -> close ticket
										$status = '';
										switch($ticket->status){
											case 0:
												$status = '<font color="#159667">'.esc_html(__('New','majestic-support')).'</font>';
												if($ticket->isoverdue == 1)
													$status = '<font color="#B82B2B">'.esc_html(__('Overdue','majestic-support')).'</font>';
											break;
											case 1:
												$status = '<font color="#f39f10">'.esc_html(__('Pending','majestic-support')).'</font>';
												if($ticket->isoverdue == 1)
													$status = '<font color="#B82B2B">'.esc_html(__('Overdue','majestic-support')).'</font>';
											break;
											case 2:
												$status = '<font color="#f39f10">'.esc_html(__('In Progress','majestic-support')).'</font>';
												if($ticket->isoverdue == 1)
													$status = '<font color="#B82B2B">'.esc_html(__('Overdue','majestic-support')).'</font>';
											break;
											case 3:
												$status = '<font color="#2168A2">'.esc_html(__('Answered','majestic-support')).'</font>';
												if($ticket->isoverdue == 1)
													$status = '<font color="#B82B2B">'.esc_html(__('Overdue','majestic-support')).'</font>';
											break;
											case 4:
												$status = '<font color="#3D355A">'.esc_html(__('Closed','majestic-support')).'</font>';
											break;
											case 5:
												$status = '<font color="#3D355A">'.esc_html(__('Merged and closed','majestic-support')).'</font>';
											break;
										}
										echo wp_kses($status, MJTC_ALLOWED_TAGS);
									?>
								</td>
								<td>
									<span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Priority','majestic-support')); ?> :</span>
									<span style="background-color:<?php echo esc_attr($ticket->prioritycolour); ?>;" class="mjtc-sprt-rep-prty"><?php echo esc_html(majesticsupport::MJTC_getVarValue($ticket->priority)); ?></span>
								</td>
								<td >
									<span class="majestic-support-table-responsive-heading"><?php echo esc_html(__("Created",'majestic-support'));?>: </span>
									<?php echo esc_html(date_i18n(majesticsupport::$_config['date_format'],MJTC_majesticsupportphplib::MJTC_strtotime($ticket->created))); ?>
								</td>
								<?php if(in_array('feedback', majesticsupport::$_active_addons)){ ?>
									<td >
										<span class="majestic-support-table-responsive-heading"> <?php echo esc_html(__('Rating','majestic-support')); ?> : </span>
										<?php if($ticket->rating > 0){ ?>
											<span style="color:<?php echo esc_attr($rating_color); ?>;font-weight:bold;font-size:16px;" > <?php echo esc_html($ticket->rating);?></span>
											<?php echo esc_html(__('Out of','majestic-support')).'<span style="font-weight:bold;font-size:15px;" >&nbsp;5</span>';
										}else{
											echo esc_html('NA');
										} ?>
									</td>
								<?php } ?>
								<?php if(in_array('timetracking', majesticsupport::$_active_addons)){ ?>
									<td >
										<span class="majestic-support-table-responsive-heading"><?php echo esc_html(__('Time Taken','majestic-support')); ?> : </span>
										<?php echo esc_html($avgtime); ?>
									</td>
								<?php } ?>
							</tr>
						<?php
						} ?>
					</table>
					<?php
				} else {
					MJTC_layout::MJTC_getNoRecordFound();
				} ?>
			</div>
			<?php
			if($show_flag == 1){ ?>
				<div class="mjtc-form-button">
		        <?php echo wp_kses('<font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font>'.esc_html(__('Tickets not assigned to the agent','majestic-support')), MJTC_ALLOWED_TAGS); ?>
		        </div>
	        	<?php
        	}
	        if(!empty(majesticsupport::$_data['staff_tickets'])){
			    if (majesticsupport::$_data[1]) {
			        $data = '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(majesticsupport::$_data[1]) . '</div></div>';
			        echo wp_kses($data, MJTC_ALLOWED_TAGS);
			    }
			}
			?>
			</div>
		</div>
	</div>
