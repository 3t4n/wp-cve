<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('majesticsupport-jquery-ui-css', MJTC_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');
wp_enqueue_style('majesticsupport-status-graph', MJTC_PLUGIN_URL . 'includes/css/status_graph.css');
wp_enqueue_script('majesticsupport-google-charts', MJTC_PLUGIN_URL . 'includes/js/google-charts.js');
wp_register_script( 'majesticsupport-google-charts-handle', '' );
wp_enqueue_script( 'majesticsupport-google-charts-handle' );
MJTC_message::MJTC_getMessage();
$formdata = MJTC_formfield::MJTC_getFormData();
$mjtc_scriptdateformat = MJTC_includer::MJTC_getModel('majesticsupport')->MJTC_getDateFormat();
$majesticsupport_js ="
    function updateuserlist(pagenum){
        jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'agent', task: 'getusersearchstaffreportajax',userlimit:pagenum, '_wpnonce':'". esc_attr(wp_create_nonce("get-usersearch-staffreport-ajax"))."'}, function (data) {
            if(data){
                jQuery('div#records').html('');
                jQuery('div#records').html(data);
                setUserLink();
            }
        });
    }
    function setUserLink() {
        jQuery('a.mjtc-userpopup-link').each(function () {
            var anchor = jQuery(this);
            jQuery(anchor).click(function (e) {
                var id = jQuery(this).attr('data-id');
                var name = jQuery(this).html();
                jQuery('input#username-text').val(name);
                jQuery('input#uid').val(id);
                jQuery('div#userpopup').slideUp('slow', function () {
                    jQuery('div#userpopupblack').hide();
                });
            });
        });
    }
    setUserLink();
    jQuery(document).ready(function ($) {
    	jQuery('.custom_date').datepicker({
            dateFormat: '". esc_html($mjtc_scriptdateformat)."'
        });
        jQuery('a#userpopup').click(function (e) {
            e.preventDefault();
            jQuery('div#userpopupblack').show();
            jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'agent', task: 'getusersearchstaffreportajax', '_wpnonce':'". esc_attr(wp_create_nonce("get-usersearch-staffreport-ajax"))."'}, function (data) {
                if(data){
                    jQuery('div#userpopup-records').html('');
                    jQuery('div#userpopup-records').html(data);
                    setUserLink();
                }
            });
            jQuery('div#userpopup').slideDown('slow');
        });
        jQuery('form#userpopupsearch').submit(function (e) {
            e.preventDefault();
            var name = jQuery('input#name').val();
            var emailaddress = jQuery('input#emailaddress').val();
            jQuery.post(ajaxurl, {action: 'mjsupport_ajax', name: name, emailaddress: emailaddress, mjsmod: 'agent', task: 'getusersearchstaffreportajax', '_wpnonce':'". esc_attr(wp_create_nonce("get-usersearch-staffreport-ajax"))."'}, function (data) {
                if (data) {
                    jQuery('div#userpopup-records').html(data);
                    setUserLink();
                }
            });//jquery closed
        });
        jQuery('.userpopup-close, div#userpopupblack').click(function (e) {
            jQuery('div#userpopup').slideUp('slow', function () {
                jQuery('div#userpopupblack').hide();
            });

        });
	});

	function resetFrom(){
		document.getElementById('date_start').value = '';
		document.getElementById('date_end').value = '';
		document.getElementById('uid').value = '';
		document.getElementById('username-text').value = '';
		document.getElementById('majesticsupportform').submit();
	}
";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
$majesticsupport_js ="
	jQuery(document).ready(function ($) {
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
	function resizeCharts () {
	    // redraw charts, dashboards, etc here
	    chart.draw(data, options);
	}
	jQuery(window).resize(resizeCharts);
";
wp_add_inline_script('majesticsupport-google-charts-handle',$majesticsupport_js);
?>	
<div id="userpopupblack" style="display:none;"></div>
<div id="userpopup" style="display:none;">
	<div class="userpopup-top">
	    <div class="userpopup-heading">
	    	<?php echo esc_html(__('Select user','majestic-support')); ?>
	    </div>
	    <img alt="<?php echo esc_html(__('Close','majestic-support')); ?>" class="userpopup-close" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/close-icon-white.png" />
    </div>
    <div class="userpopup-search">
        <form id="userpopupsearch">
            <div class="userpopup-fields-wrp">
                <div class="userpopup-fields">
                    <input type="text" name="username" id="username" placeholder="<?php echo esc_html(__('Username','majestic-support')); ?>" />
                </div>
                <div class="userpopup-fields">
                    <input type="text" name="name" id="name" placeholder="<?php echo esc_html(__('Name','majestic-support')); ?>" />
                </div>
                <div class="userpopup-fields">
                    <input type="text" name="emailaddress" id="emailaddress" placeholder="<?php echo esc_html(__('Email Address','majestic-support')); ?>"/>
                </div>
                <div class="userpopup-btn-wrp">
                    <input class="userpopup-search-btn" type="submit" value="<?php echo esc_html(__('Search','majestic-support')); ?>" />
                    <input class="userpopup-reset-btn" type="submit" onclick="document.getElementById('name').value = '';document.getElementById('username').value = ''; document.getElementById('emailaddress').value = '';" value="<?php echo esc_html(__('Reset','majestic-support')); ?>" />
                </div>
            </div>
        </form>
    </div>
    <div id="userpopup-records-wrp">
	    <div id="userpopup-records">
            <div class="userpopup-records-desc">
                <?php echo esc_html(__('Use search feature to select the user','majestic-support')); ?>
            </div>
	    </div>
    </div>
</div>
<?php MJTC_message::MJTC_getMessage(); ?>

<?php
$t_name = 'getstaffmemberexport';
$link_export = admin_url('admin.php?page=majesticsupport_export&task='.esc_attr($t_name).'&action=mstask&uid='.esc_attr(majesticsupport::$_data['filter']['uid']).'&date_start='.esc_attr(majesticsupport::$_data['filter']['date_start']).'&date_end='.esc_attr(majesticsupport::$_data['filter']['date_end']));
?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
    	<?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('agent_reports'); ?>
        <div id="msadmin-data-wrp">
        	<div class="mjtc-admin-staff-boxes">
				<?php
					$open_percentage = 0;
					$close_percentage = 0;
					$overdue_percentage = 0;
					$answered_percentage = 0;
					$pending_percentage = 0;
					if(isset(majesticsupport::$_data['ticket_total']) && isset(majesticsupport::$_data['ticket_total']['allticket']) && majesticsupport::$_data['ticket_total']['allticket'] != 0){
					    $open_percentage = round((majesticsupport::$_data['ticket_total']['openticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
					    $close_percentage = round((majesticsupport::$_data['ticket_total']['closeticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
					    $overdue_percentage = round((majesticsupport::$_data['ticket_total']['overdueticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
					    $answered_percentage = round((majesticsupport::$_data['ticket_total']['answeredticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
					    $pending_percentage = round((majesticsupport::$_data['ticket_total']['pendingticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
					}
					if(isset(majesticsupport::$_data['ticket_total']) && isset(majesticsupport::$_data['ticket_total']['allticket']) && majesticsupport::$_data['ticket_total']['allticket'] != 0){
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
				                    $data = esc_html(__('Open', 'majestic-support')).' ( '.esc_html(majesticsupport::$_data['ticket_total']['openticket']).' )';
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
				                    $data = esc_html(__('Answered', 'majestic-support')).' ( '. esc_html(majesticsupport::$_data['ticket_total']['answeredticket']).' )';
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
	                                $data = esc_html(__('Pending', 'majestic-support')).' ( '. esc_html(majesticsupport::$_data['ticket_total']['pendingticket']).' )';
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
					                    $data = esc_html(__('Overdue', 'majestic-support')).' ( '. esc_html(majesticsupport::$_data['ticket_total']['overdueticket']).' )';
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
				                    $data = esc_html(__('Closed', 'majestic-support')).' ( '. esc_html(majesticsupport::$_data['ticket_total']['closeticket']).' )';
				                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
				                ?>
				            </div>
				        </a>
				    </div>
				</div>
			</div>
	    	<form class="mjtc-filter-form mjtc-report-form" name="majesticsupportform" id="majesticsupportform" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_reports&mjslay=staffreport"),"reports")); ?>">
			    <?php
			        $curdate = date_i18n('Y-m-d');
			        $enddate = date_i18n('Y-m-d', MJTC_majesticsupportphplib::MJTC_strtotime("now -1 month"));
			        $date_start = !empty(majesticsupport::$_data['filter']['date_start']) ? majesticsupport::$_data['filter']['date_start'] : $curdate;
			        $date_end = !empty(majesticsupport::$_data['filter']['date_end']) ? majesticsupport::$_data['filter']['date_end'] : $enddate;
			        $uid = !empty(majesticsupport::$_data['filter']['uid']) ? majesticsupport::$_data['filter']['uid'] : '';
			    	echo wp_kses(MJTC_formfield::MJTC_text('date_start', date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($date_start)), array('class' => 'custom_date mjtc-form-date-field','placeholder' => esc_html(__('Start Date','majestic-support')))), MJTC_ALLOWED_TAGS);
			    	echo wp_kses(MJTC_formfield::MJTC_text('date_end', date_i18n(majesticsupport::$_config['date_format'], MJTC_majesticsupportphplib::MJTC_strtotime($date_end)), array('class' => 'custom_date mjtc-form-date-field','placeholder' => esc_html(__('End Date','majestic-support')))), MJTC_ALLOWED_TAGS);
			    	echo wp_kses(MJTC_formfield::MJTC_hidden('uid', $uid), MJTC_ALLOWED_TAGS);
			    	echo wp_kses(MJTC_formfield::MJTC_hidden('MS_form_search', 'MS_SEARCH'), MJTC_ALLOWED_TAGS);
				?>
			    <?php if (!empty(majesticsupport::$_data['filter']['staffname'])) { ?>
			        <div id="username-div"><input class="mjtc-form-input-field" type="text" value="<?php echo esc_attr(majesticsupport::$_data['filter']['staffname']); ?>" id="username-text" readonly="readonly" data-validation="required"/></div><a href="#" id="userpopup" class="button mjtc-form-reset" title="<?php echo esc_attr(__('Select User', 'majestic-support')); ?>"><?php echo esc_html(__('Select User', 'majestic-support')); ?></a>
			    <?php } else { ?>
			        <div id="username-div"></div><input class="mjtc-form-input-field" type="text" value="" id="username-text" readonly="readonly" data-validation="required"/><a href="#" id="userpopup" class="button mjtc-form-reset" title="<?php echo esc_attr(__('Select User', 'majestic-support')); ?>"><?php echo esc_html(__('Select User', 'majestic-support')); ?></a>
			    <?php } ?>
			    <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('go', esc_html(__('Search', 'majestic-support')), array('class' => 'button mjtc-form-search')), MJTC_ALLOWED_TAGS); ?>
				<?php echo wp_kses(MJTC_formfield::MJTC_button('reset', esc_html(__('Reset', 'majestic-support')), array('class' => 'button mjtc-form-reset', 'onclick' => 'resetFrom();')), MJTC_ALLOWED_TAGS); ?>
			</form>
			<div class="mjtc-admin-report">
				<div class="mjtc-admin-subtitle"><?php echo esc_html(__('Overall Report','majestic-support')); ?></div>
				<div class="mjtc-admin-rep-graph" id="curve_chart" style="height:400px;width:98%; "></div>
			</div>
			<div class="mjtc-admin-report">
				<div class="mjtc-admin-subtitle"><?php echo esc_html(__('Agents','majestic-support')); ?></div>
				<div class="mjtc-admin-staff-list">
				<?php
				if(!empty(majesticsupport::$_data['staffs_report'])){
					foreach(majesticsupport::$_data['staffs_report'] AS $agent){ ?>
						<div class="mjtc-admin-staff-wrapper">
							<a href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport_reports&mjslay=staffdetailreport&id='.esc_attr($agent->id).'&date_start='.esc_attr(majesticsupport::$_data['filter']['date_start']).'&date_end='.esc_attr(majesticsupport::$_data['filter']['date_end']))); ?>" class="mjtc-admin-staff-anchor-wrapper" title="<?php echo esc_attr(__('Staff', 'majestic-support')); ?>">
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
										<img alt="<?php echo esc_html(__('staff image', 'majestic-support')); ?>" class="mjtc-report-staff-pic" src="<?php echo esc_url($imageurl); ?>" />
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
								<div class="mjtc-admin-staff-boxes">
									<?php
										$open_percentage = 0;
										$close_percentage = 0;
										$overdue_percentage = 0;
										$answered_percentage = 0;
										$pending_percentage = 0;
										if(isset(majesticsupport::$_data['ticket_total']) && isset(majesticsupport::$_data['ticket_total']['allticket']) && majesticsupport::$_data['ticket_total']['allticket'] != 0){
										    $open_percentage = round((majesticsupport::$_data['ticket_total']['openticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
										    $close_percentage = round((majesticsupport::$_data['ticket_total']['closeticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
										    $overdue_percentage = round((majesticsupport::$_data['ticket_total']['overdueticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
										    $answered_percentage = round((majesticsupport::$_data['ticket_total']['answeredticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
										    $pending_percentage = round((majesticsupport::$_data['ticket_total']['pendingticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
										}
										if(isset(majesticsupport::$_data['ticket_total']) && isset(majesticsupport::$_data['ticket_total']['allticket']) && majesticsupport::$_data['ticket_total']['allticket'] != 0){
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
									                    $data = esc_html(__('Open', 'majestic-support')).' ( '.esc_html(majesticsupport::$_data['ticket_total']['openticket']).' )';
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
									                    $data = esc_html(__('Answered', 'majestic-support')).' ( '. esc_html(majesticsupport::$_data['ticket_total']['answeredticket']).' )';
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
						                                $data = esc_html(__('Pending', 'majestic-support')).' ( '. esc_html(majesticsupport::$_data['ticket_total']['pendingticket']).' )';
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
										                    $data = esc_html(__('Overdue', 'majestic-support')).' ( '. esc_html(majesticsupport::$_data['ticket_total']['overdueticket']).' )';
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
									                    $data = esc_html(__('Closed', 'majestic-support')).' ( '. esc_html(majesticsupport::$_data['ticket_total']['closeticket']).' )';
									                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
									                ?>
									            </div>
									        </a>
									    </div>
										<?php if(in_array('feedback', majesticsupport::$_active_addons)){ ?>
											<div class="mjtc-support-link <?php echo esc_attr($rating_class)?>">
												<a href="#" class="mjtc-support-link mjtc-support-mariner" title="<?php echo esc_attr(__('Rating', 'majestic-support')); ?>">
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
							</a>
						</div>
					<?php
					}
				    if (majesticsupport::$_data[1]) {
				        $data = '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(majesticsupport::$_data[1]) . '</div></div>';
				        echo wp_kses($data, MJTC_ALLOWED_TAGS);
				    }
				} else {
					MJTC_layout::MJTC_getNoRecordFound();
				}
				?>
				</div>
			</div>
		</div>
	</div>
</div>
