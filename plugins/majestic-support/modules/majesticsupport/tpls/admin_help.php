<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('admin_help'); ?>
    	<div id="msadmin-data-wrp">
    		<!-- help page -->
    		<div class="msupportadmin-help-top">
    			<div class="msupportadmin-help-top-left">
    				<div class="msupportadmin-help-top-left-cnt-img">
    					<img alt="<?php echo esc_html(__('Help icon','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/support-icon.jpg" />
    				</div>
    				<div class="msupportadmin-help-top-left-cnt-info">
    					<h2><?php echo esc_html(__('Videos Guidance','majestic-support')); ?></h2>
    					<p><?php echo esc_html(__('A reputable support system that offers step-by-step YouTube video guides to help you understand.','majestic-support')); ?></p>
    					<a href="https://www.youtube.com/@Majestic-Support/videos" target="_blank" class="msupportadmin-help-top-middle-action" title="<?php echo esc_attr(__('View all videos','majestic-support')); ?>"><img alt="<?php echo esc_html(__('Video icon','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/play-icon.jpg" /><?php echo esc_html(__('View All Videos','majestic-support')); ?></a>
    				</div>
    			</div>
    			<div class="msupportadmin-help-top-right">
    				<div class="msupportadmin-help-top-right-cnt-img">
    					<img alt="<?php echo esc_html(__('Majestic Support icon','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/support.png" />
    				</div>
    				<div class="msupportadmin-help-top-right-cnt-info">
    					<h2><?php echo esc_html(__('Contact Us For Support','majestic-support')); ?></h2>
    					<p><?php echo esc_html(__("At Majestic Support, we are committed to offering prompt and helpful customer care to help you at every step.",'majestic-support')); ?></p>
    					<a target="_blank" href="https://majesticsupport.com/support/" class="msupportadmin-help-top-middle-action second" title="<?php echo esc_attr(__('Submit ticket','majestic-support')); ?>"><img alt="<?php echo esc_html(__('Video icon','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/ticket.png" /><?php echo esc_html(__('Submit Ticket','majestic-support')); ?></a>
    				</div>
    			</div>
    		</div>
    		<div class="msupportadmin-help-btm">
    			<!-- tickets -->
    			<div class="msupportadmin-help-btm-wrp">
    				<h2 class="msupportadmin-help-btm-title"><?php echo esc_html(__('Tickets','majestic-support')); ?></h2>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=dYniAnKyv-Q" class="msupportadmin-help-btm-link"  target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('Ticket Creation','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('Ticket Creation','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=9NvBOu_ojMo" class="msupportadmin-help-btm-link"  target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('Visitor ticket creation','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('Visitor ticket creation','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=2iA8SuNLmMI" class="msupportadmin-help-btm-link"  target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to set ticket auto close','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to set ticket auto close','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link"  target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to reopen closed ticket','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to reopen closed ticket','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('Configuration','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to lock a ticket','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=WHvXkgYLtv8" class="msupportadmin-help-btm-link"  target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to add private note','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to add private note','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link"  target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('View ticket history','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('View ticket history','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=8dIMdKuTLx4&ab_channel=MajesticSupport" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to setup custom fields','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to setup custom fields','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link"  target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('Set ticket auto overdue','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('Set ticket auto overdue','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link"  target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('Manually set ticket overdue','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('Manually set ticket overdue','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=p1JJTwsfWG8" class="msupportadmin-help-btm-link"  target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to merge tickets','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to merge tickets','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link"  target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to export tickets','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to export tickets','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=8mS5EWOUl7c" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How use help topic','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How use help topic','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to change department','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to change department','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=Z5-dKDt8DJ8" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to use multi-forms','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to use multi-forms','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to paid support','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to paid support','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to use premade response','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to use premade response','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=qlDlPlS2QWY" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to add private credentials','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to add private credentials','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to ban/unban user','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to ban/unban user','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>	
    			</div>
                <!-- agents -->
                <div class="msupportadmin-help-btm-wrp">
                    <h2 class="msupportadmin-help-btm-title"><?php echo esc_html(__('Agents','majestic-support')); ?></h2>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=gA7XIvLX1Ko" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('Agent system','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('Agent system','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=_Dl-7qci9GE" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('Agent Auto Assign','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('Agent Auto Assign','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link"  target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('Manually assign ticket to agen','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('Manually assign ticket to agent','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                            <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                                <div class="msupportadmin-help-btm-cnt-img">
                                    <img alt="<?php echo esc_html(__('How to edit time','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                                </div>
                                <div class="msupportadmin-help-btm-cnt-title">
                                    <span><?php echo esc_html(__('How to edit time','majestic-support')); ?></span>
                                </div>
                            </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=mEBkMs59rJY" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to use time tracking','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to use time tracking','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- smart reply -->
                <div class="msupportadmin-help-btm-wrp">
                    <h2 class="msupportadmin-help-btm-title"><?php echo esc_html(__('Smart Replies','majestic-support')); ?></h2>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=YDYnagRWyEU" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to use smart replies','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to use smart replies','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                </div>
    			<!-- configurations -->
    			<div class="msupportadmin-help-btm-wrp">
    				<h2 class="msupportadmin-help-btm-title"><?php echo esc_html(__('Configurations','majestic-support')); ?></h2>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to set max open ticket','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to set max open ticket','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
    				<div class="msupportadmin-help-btm-cnt">
    					<a href="https://www.youtube.com/watch?v=gCB-wGVZph8&ab_channel=MajesticSupport" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to show counts','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to show counts','majestic-support')); ?></span>
                            </div>
    					</a>
    				</div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to set Captcha','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to set Captcha','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
    				<div class="msupportadmin-help-btm-cnt">
    					<a href="https://www.youtube.com/watch?v=6AE4ZHB9bJk&ab_channel=MajesticSupport" class="msupportadmin-help-btm-link" target="_blank">
                             <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('User options','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('User options','majestic-support')); ?></span>
                            </div>
    					</a>
    				</div>
    				<div class="msupportadmin-help-btm-cnt">
    					<a href="https://www.youtube.com/watch?v=bzK2IxQ0QaU" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to set login redirect','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to set login redirect','majestic-support')); ?></span>
                            </div>
    					</a>
    				</div>
    				<div class="msupportadmin-help-btm-cnt">
    					<a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to set fields ordering','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to set fields ordering','majestic-support')); ?></span>
                            </div>
    					</a>
    				</div>
    				<div class="msupportadmin-help-btm-cnt">
    					<a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to enable social login','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to enable social login','majestic-support')); ?></span>
                            </div>
    					</a>
    				</div>
    			</div>
    			<!-- setup -->
    			<div class="msupportadmin-help-btm-wrp">
    				<h2 class="msupportadmin-help-btm-title"><?php echo esc_html(__('Setup','majestic-support')); ?></h2>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to setup','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to setup','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=ySZXLllQWRY" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to enable email piping','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to enable email piping','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to set SMTP','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to set SMTP','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                             <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to solve email notification problem','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to solve email notification problem','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to translate','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to translate','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
    				<div class="msupportadmin-help-btm-cnt">
    					<a href="https://www.youtube.com/watch?v=OZTabfsnVIQ" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to set colors','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to set colors','majestic-support')); ?></span>
                            </div>
    					</a>
    				</div>
    				<div class="msupportadmin-help-btm-cnt">
    					<a href="https://www.youtube.com/watch?v=PV-shw5Nr8Q&ab_channel=MajesticSupport" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to add Shortcode','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to add Shortcode','majestic-support')); ?></span>
                            </div>
    					</a>
    				</div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=6xrHvIgRpZc" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to install addons','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to install addons','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to Desktop Notifications','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to Desktop Notifications','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
    				<div class="msupportadmin-help-btm-cnt">
    					<a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to set fields ordering','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to set fields ordering','majestic-support')); ?></span>
                            </div>
    					</a>
    				</div>
    			</div>    
                <!-- knowledge-base,downloads,announcements,FAQ -->
                <div class="msupportadmin-help-btm-wrp msupportadmin-help-sub-category">
                    <h2 class="msupportadmin-help-btm-title"><?php echo esc_html(__('Knowledgebase','majestic-support')).', '.esc_html(__('Downloads','majestic-support')).', '.esc_html(__('Announcements','majestic-support')).', '.esc_html(__('and','majestic-support')).' '.esc_html(__('FAQs','majestic-support')); ?></h2>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=g6l5M8hR1hE" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to use knowledge base','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to use knowledge base','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to use downloads','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to use downloads','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="https://www.youtube.com/watch?v=UJv3-FdD0Fs" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to add announcement','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to add announcement','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to create FAQ','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to create FAQ','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- misc -->
                <div class="msupportadmin-help-btm-wrp">
                    <h2 class="msupportadmin-help-btm-title"><?php echo esc_html(__('Misc','majestic-support')); ?></h2>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to use email cc','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to use email cc','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to use internal mail','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to use internal mail','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('Use front-end widgets','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('Use front-end widgets','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="msupportadmin-help-btm-cnt">
                        <a href="#" class="msupportadmin-help-btm-link" target="_blank">
                            <div class="msupportadmin-help-btm-cnt-img">
                                <img alt="<?php echo esc_html(__('How to enable admin widgets','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/help-page/video-icon.png" />
                            </div>
                            <div class="msupportadmin-help-btm-cnt-title">
                                <span><?php echo esc_html(__('How to enable admin widgets','majestic-support')); ?></span>
                            </div>
                        </a>
                    </div>
                </div>
    		</div>
		</div>
	</div>
</div>
