<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper">
    <div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
            <span class="js-admin-title">
                <span class="heading">  
                    <a href="<?php echo esc_url(admin_url("admin.php?page=jsjobs"));?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
                    <span class="text-heading"><?php echo __('Pro Features', 'wp-jobs'); ?></span>    
                </span>
                <?php JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
            </span>
            <div id="js_profeature_main_wrapper">
        		<div id="proheading" class="proheading">
        			<span class="headtext"><?php echo __('JS JOBS PRO FEATURES','wp-jobs');?></span>
        			<a class="buynow" target="_blank" href="http://www.joomsky.com/products/js-jobs-pro-wp.html"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/buy-now.png"> <?php echo __('BUY NOW','wp-jobs');?></a>
        		</div>
            	<div class="topimage bgwhite"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/image-1.png"></div>
            	<div class="pro_wrapper">
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/add-job.png"></div>
            				<div class="data">
            					<div class="heading">Visitor Can Add/Edit Jobs</div>
            					<div class="detail">JS Jobs comes with unique feature, visitor can add jobs and also he can edit job.<br>
        JS Jobs send him an edit job link in his email.</div>
            				</div>
            			</div>
            		</div>
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/gold-feature.png"></div>
            				<div class="data">
            					<div class="heading">Featured Jobs</div>
            					<div class="detail">JS Jobs give you the jobs listing along with the special Featured jobs listing.<br>
                                Featured jobs are not just listing in different layout it can also be listed in Newest Jobs controllable by admin.</div>
            				</div>
            			</div>
            		</div>
            	</div>
            	<div class="pro_wrapper">
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/tell-friend.png"></div>
            				<div class="data">
            					<div class="heading">Tell A Friend</div>
            					<div class="detail">Tell a firend is a feature which enables users to share any job with thier friends by sending them a emails through our system.<br>Employers and job seeker both can use this feature</div>
            				</div>
            			</div>
            		</div>
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/message.png"></div>
            				<div class="data">
            					<div class="heading">Message System</div>
            					<div class="detail">JS Jobs have message system feature. Employer can send message to job seeker and job
        On each message JS Jobs send email notification.</div>
            				</div>
            			</div>
            		</div>
            	</div>
        		<div class="full_box">
        			<div class="box bgwhite">
        				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/themes.png"></div>
        				<div class="data">
        					<div class="heading">Multi Themes</div>
        					<div class="detail">You can customize the color layout for the JS Jobs.
        You can either select colors from a color pallet table or select the predefined set.</div>
        				</div>
        			</div>
        		</div>
            	<div class="pro_wrapper">
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/credits-log.png"></div>
            				<div class="data">
            					<div class="heading">Credits Log</div>
            					<div class="detail">Credits log is a record of every action for which admin has defined credits.<br>
        whenever any user or admin performs any action the Credits log is updated with the user name, cost and basic descrption of that action.</div>
            				</div>
            			</div>
            		</div>
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/multi-credits.png"></div>
            				<div class="data">
            					<div class="heading">Multiple Credits System</div>
            					<div class="detail">Admin can define multiple costs for same action with diffrent expires.<br>
        Whenever any user perform any of the those actions, he/she is charged for that specific action.</div>
            				</div>
            			</div>
            		</div>
            	</div>
            	<div class="pro_wrapper">
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/view-resume-detials.png"></div>
            				<div class="data">
            					<div class="heading">Pay For View Details</div>
            					<div class="detail">Admin can define cost for viewing contact detail of resume or company.<br> Whenever any user wants to view contact detail of company or resume they will have to pay for it.</div>
            				</div>
            			</div>
            		</div>
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/add-resume.png"></div>
            				<div class="data">
            					<div class="heading">Visitor Can Add Resume</div>
            					<div class="detail">If user hesitates to register, don't worry about it. JS Jobs offers, Vistor can add resume with full details.</div>
            				</div>
            			</div>
            		</div>
            	</div>
                <div class="full_box">
                    <div class="box bgwhite">
                        <div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/credits.png"></div>
                        <div class="data">
                            <div class="heading">Credits System</div>
                            <div class="detail">Admin create package for employer and job seeker with cost and credits.<br>
        Users buy credit packs defined by admin using paypal or woocommrece.</div>
                        </div>
                    </div>
                </div>

            	<div class="pro_wrapper">
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/suggets-jobs.png"></div>
            				<div class="data">
            					<div class="heading">Suggested Jobs</div>
            					<div class="detail">Very usefull feature for job seeker. System suggested jobs available in his control panel.</div>
            				</div>
            			</div>
            		</div>
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/apply-social.png"></div>
            				<div class="data">
            					<div class="heading">Apply With Social Media</div>
            					<div class="detail">JS Jobs offer a very useful feature for job seeker. Job seeker can apply any job using<br>
                                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/facebook.png">  Facebook<br>
                                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/in.png">  Linkedin<br>
                                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/xing.png">  Xing</div>
            				</div>
            			</div>
            		</div>
            	</div>
            	<div class="pro_wrapper">
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/login-social.png"></div>
            				<div class="data">
            					<div class="heading">Login With Social Media</div>
            					<div class="detail">If user hesitate to register, don’t worry about it, JS Jobs offer social login to these user.
                                JS Jobs also offer social login of these popular social sites.<br>
                                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/facebook.png">  Facebook<br>
                                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/in.png">  Linkedin<br>
                                    <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/xing.png">  Xing
                                </div>
        				    </div>
            			</div>
            		</div>
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/tags.png"></div>
            				<div class="data">
            					<div class="heading">Tags</div>
            					<div class="detail">Tags provide a useful way to group related jobs/resume 
                                together and quickly access related jobs/resume.</div>
            				</div>
            			</div>
            		</div>
            	</div>
                <div class="full_box">
                    <div class="box bgwhite">
                        <div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/applied-jobs.png"></div>
                        <div class="data">
                            <div class="heading">Applied Resume</div>
                            <div class="detail">JS Jobs give more power to employer on applied resume.
        Nowadays employer receive lot of resume for his jobs. Some of them relevant and some are not. JS Jobs handle this problem with filter and give useful options to employer.
        Employer can add filter on his job on base of category, education, gender & location
        Admin move resume to any of these tabs just by click<br>
        – Inbox<br>
        – Shortlisted<br>
        – Spam<br>
        – Hired<br>
        – Rejected<br>
        It help employer to find best candidate for his job.</div>
                        </div>
                    </div>
                </div>
                <div class="pro_wrapper">
                    <div class="small_box">
                        <div class="box bgwhite">
                            <div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/job-alert.png"></div>
                            <div class="data">
                                <div class="heading">Job Alert</div>
                                <div class="detail">Job seeker can get his desire job is his email account. Just subscribe for job alert and add his preferences and alert frequency<br>(daily/weekly/monthly).</div>
                            </div>
                        </div>
                    </div>
                    <div class="small_box">
                        <div class="box bgwhite">
                            <div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/custom-fields.png"></div>
                            <div class="data">
                                <div class="heading">User Fields</div>
                                <div class="detail">Custom fields are now more efficient and reliable.<br>
        Admin can make custom fields visible on search forms, refine search popup and on main listings.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pro_wrapper">
                    <div class="small_box">
                        <div class="box bgwhite">
                            <div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/career-bulider.png"></div>
                            <div class="data">
                                <div class="heading">Carrer Builder Jobs</div>
                                <div class="detail">JS Jobs has integrated career builder a famous job portal. Now Admin can inculde career builder jobs into his components jobs listings.admin has full control over career builder jobs.</div>
                            </div>
                        </div>
                    </div>
                    <div class="small_box">
                        <div class="box bgwhite">
                            <div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/indeed.png"></div>
                            <div class="data">
                                <div class="heading">Indeed Jobs</div>
                                <div class="detail">JS Jobs has integrated indeed a famous job portal. Now Admin can inculde indeed jobs into his components jobs listings.admin has full control over indeed jobs.</div>
                            </div>
                        </div>
                    </div>
                </div>
        		<div class="full_box">
        			<div class="box bgwhite">
        				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/notifications.png"></div>
        				<div class="data">
        					<div class="heading">Notifications</div>
        					<div class="detail">Do not miss any important action. JS Jobs have notifications in control panel page.<br>
        Every user will be notified for actions like company/job/resume  (featured) status change,
        job apply ,job applied resume status change or when someone sends a message or replies to a message.</div>
        				</div>
        			</div>
        		</div>
            	<div class="pro_wrapper">
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/paypal.png"></div>
            				<div class="data">
            					<div class="heading">Paypal</div>
            					<div class="detail">JS Jobs support PayPal express checkout.</div>
            				</div>
            			</div>
            		</div>
            		<div class="small_box">
            			<div class="box bgwhite">
            				<div class="img"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/pro/woo.png"></div>
            				<div class="data">
            					<div class="heading">Woo Commerce</div>
            					<div class="detail">JS Jobs also integration WP most popular shopping plugin, woo commerce.</div>
            				</div>
            			</div>
            		</div>
            	</div>
            </div>
        </div>
    </div>
</div>
