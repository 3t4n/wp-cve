<div class="supsystic-overview">
			<div class="full-page">
				<div class="plugin-title"><img src="<?php echo PPS_PLUGINS_URL .'/'. PPS_PLUG_NAME;?>/modules/supsystic_promo/img/plugin-icon.png">PopUp By Supsystic</div>
				<div class="plugin-description">This WordPress Popup plugin by Supsystic lets you easily create elegant popups with Subscription Forms, Facebook Likes, Option and Social locks. Plus you get Contact Forms, Slider and more. It’s the go-to tool for bloggers and websites using a lead magnet technique to get more Email Subscribers, Social Followers and Customers!</div>
			</div>
			<div class="supsystic-overview-flex">
			<div class="half-page half-page-left">
					<div class="border-wrapper">
						<ul>
							<li class="overview-section-btn" data-section="faq"><i class="fa fa-info-circle"></i> FAQ and Documentation</li>
							<li class="overview-section-btn" data-section="video"><i class="fa fa-play"></i> Video tutorial</li>
							<li class="overview-section-btn" data-section="settings"><i class="fa fa-cog"></i> Server Settings</li>
							<li class="overview-section-btn" data-section="support"><i class="fa fa-life-ring"></i> Support</li>
							<li class="overview-section-btn" data-section="promo_video"><i class="fa fa-star"></i> Our promo video</li>
							<li class="overview-section-btn"><a target="_blank" title="Go to supsystic.com" href="https://supsystic.com/plugins/popup-plugin/?utm_source=plugin&utm_campaign=popup"> Plugin page on supsystic.com <sup><i class="fa fa-external-link"></i></sup></a></li>
							<li class="overview-section-btn"><a target="_blank" title="Go to supsystic.com" href="https://supsystic.com/plugins/popup-plugin/?utm_source=plugin&utm_campaign=popup"> Compare FREE and PRO features <sup><i class="fa fa-external-link"></i></sup></a></li>
							<li class="overview-section-btn"><a target="_blank" title="Go to supsystic.com" href="https://supsystic.com/wordpress-plugins/?utm_source=plugin&utm_campaign=popup"> Check other supsystic FREE plugins <sup><i class="fa fa-external-link"></i></sup></a></li>
						</ul>
					</div>
					<div class="border-wrapper">
					<div class="overview-contact-form overview-section" data-section="support">
							<h3><i class="fa fa-life-ring"></i> Support</h3>
							<div class="contact-info-section">
								<p><i class="fa fa-clock-o" aria-hidden="true"></i> Our official support hours are 09:00 - 18:00 GMT+02:00, Monday to Friday – excluding bank holidays and other official holidays.</p>
								<p>The timescales listed below refer to these working hours.</p><br>
								<p><em>Support requests are prioritized based on the type of license:</em></p>
								<ul>
									<li><p><em>Pro Support</em> is reserved for customers with an active Pro license. We respond to new priority support requests within 12 hours.</p></li>
									<li><p><em>Standard Support</em> is provided to customers with an active Free license. We respond to standard support requests within 24h-48h.</p></li>
								</ul><br>
								<p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> While we don’t guarantee that we will resolve the request in this time period, we will acknowledge it and communicate with the customer as appropriate to help resolve the issue.</p>
							</div>

							<form id="form-settings">
								<table class="contact-form-table">
									<?php foreach($this->contactFields as $fName => $fData) { ?>
										<?php
											$htmlType = $fData['html'];
											$id = 'contact_form_'. $fName;
											$htmlParams = array('attrs' => 'id="'. $id. '"');
											if(isset($fData['placeholder']))
												$htmlParams['placeholder'] = $fData['placeholder'];
											if(isset($fData['options']))
												$htmlParams['options'] = $fData['options'];
											if(isset($fData['def']))
												$htmlParams['value'] = $fData['def'];
											if(isset($fData['valid']) && in_array('notEmpty', $fData['valid']))
												$htmlParams['required'] = true;
										?>
									<tr>
										<th scope="row">
											<label for="<?php echo $id?>"><?php echo $fData['label']?></label>
										</th>
										<td>
											<?php echo htmlPps::$htmlType($fName, $htmlParams)?>
										</td>
									</tr>
									<?php }?>
									<tr>
										<th scope="row" colspan="2">
											<?php echo htmlPps::hidden('mod', array('value' => 'supsystic_promo'))?>
											<?php echo viewPps::ksesString(htmlPps::defaultNonceForAdminPanel())?>
											<?php echo htmlPps::hidden('action', array('value' => 'sendContact'))?>
											<button class="button button-primary button-hero" style="float: right;">
												<i class="fa fa-upload"></i>
												<?php _e('Send email', PPS_LANG_CODE)?>
											</button>
											<div style="clear: both;"></div>
										</th>
									</tr>
								</table>
							</form>
							<div class="clear"></div>
					</div>

					<div id="contact-form-dialog" hidden>
							<div class="on-error" style="display:none">
									<p>Some errors occurred while sending mail please send your message trough this contact form:</p>
									<p><a href="https://supsystic.com/plugins/#contact" target="_blank">https://supsystic.com/plugins/#contact</a></p>
							</div>
							<div class="message"></div>
					</div>
					<div data-section="faq" class="faq-list overview-section">
							<h3><?php _e('FAQ and Documentation', PPS_LANG_CODE)?></h3>
							<?php foreach($this->faqList as $title => $desc) { ?>
								<div class="faq-title">
									<i class="fa fa-info-circle"></i>
									<?php echo $title;?>
									<div class="description" style="display: none;"><?php echo $desc;?></div>
								</div>
							<?php }?>
							<div style="clear: both;"></div>
							<a target="_blank" href="https://supsystic.com/docs/popup-documentation/?utm_source=plugin&utm_medium=faq&utm_campaign=popup" class="button button-primary button-hero">
									<i class="fa fa-info-circle"></i>
									Check all FAQs
							</a>
							<div class="clear"></div>
					</div>
					<div data-section="video" class="video overview-section">
							<h3><i class="fa fa-play"></i> Video tutorial</h3>
							<iframe type="text/html"
											width="100%"
											height="350px"
											src="https://www.youtube.com/embed/v8h2k3vvpdM"
											frameborder="0">
							</iframe>
							<div class="clear"></div>
					</div>
					<div data-section="promo_video" class="video overview-section">
							<h3><i class="fa fa-star"></i> Our promo video</h3>
							<iframe type="text/html"
											width="100%"
											height="350px"
											src="http://www.youtube.com/embed/dKd_9g6JzfU"
											frameborder="0">
							</iframe>
							<div class="clear"></div>
					</div>
					<div data-section="settings" class="server-settings overview-section">
							<h3><i class="fa fa-cog"></i> Server settings</h3>
							<ul class="settings-list">
									<?php foreach($this->serverSettings as $title => $element) {?>
										<li class="settings-line">
											<div class="settings-title"><?php echo $title?>:</div>
											<span><?php echo $element['value']?></span>
										</li>
									<?php }?>
							</ul>
							<div class="clear"></div>
					</div>
					</div>
			</div>
			<div class="half-page half-page-right">
					<a href="https://supsystic.com/pricing/?utm_source=plugin&utm_campaign=popup" target="_blank"><img class="overview-supsystic-img" src="<?php echo PPS_PLUGINS_URL .'/'. PPS_PLUG_NAME;?>/modules/supsystic_promo/img/overview-01.png"></a>
					<a href="https://supsystic.com/plugins/plugins-bundle/?utm_source=plugin&utm_campaign=popup" target="_blank"><img class="overview-supsystic-img" src="<?php echo PPS_PLUGINS_URL .'/'. PPS_PLUG_NAME;?>/modules/supsystic_promo/img/overview-02.png"></a>
					<a href="https://supsystic.com/wordpress-plugins/?utm_source=plugin&utm_campaign=popup" target="_blank"><img style="margin-top:20px;"  class="overview-supsystic-img" src="<?php echo PPS_PLUGINS_URL .'/'. PPS_PLUG_NAME;?>/modules/supsystic_promo/img/overview-03.png"></a>
					<div class="clear"></div>
			</div>
			</div>
</div>
