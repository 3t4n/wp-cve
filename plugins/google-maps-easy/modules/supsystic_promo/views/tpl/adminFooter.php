<div class="supsysticOverviewACFormOverlay">
		<form method="post" id="overview-ac-form" class="supsysticOverviewACForm">
			<div class="supsysticOverviewACTitle">
				<div class="supsysticOverviewACClose"><i class="fa fa-times" aria-hidden="true"></i></div>
				<a href="https://supsystic.com/" target="_blank"><img src="<?php echo GMP_PLUGINS_URL .'/'. GMP_PLUG_NAME;?>/modules/supsystic_promo/img/supsystic-logo-small.png"></a><br>
				<b>PRO plugins</b> and <b>amazing gifts</b>!
			</div>
			<?php
			global $current_user;
			$userName = $current_user->user_firstname.' '.$current_user->user_lastname;
			$userEmail = $current_user->user_email;
			?>
			<label>Name *</label>
			<input type="text" name="username" value="<?php echo $userName;?>">
			<label>Email *</label>
			<input type="text" name="email" value="<?php echo $userEmail;?>">
			<div class="supsysticOverviewACFormNotification"><input required style="width:10px; margin-top:2px;" type="checkbox" id="supsysticOverviewACTermsCheckbox" name="supsysticOverviewACTermsCheckbox" value=""><label for="supsysticOverviewACTermsCheckbox">I Accept the <a href="#terms" class="supsysticOverviewACTerms">Terms and Conditions</a> *</label></div>
			<button id="subscribe-btn" type="submit" class="button button-primary button-hero">
					<i class="fa fa-check-square" aria-hidden="true"></i>
					Subscribe
			</button>
			<div class="button button-primary button-hero supsysticOverviewACBtn supsysticOverviewACBtnRemind"><i class="fa fa-hourglass-half" aria-hidden="true"></i> Remind me tomorrow</div>
			<div class="button button-primary button-hero supsysticOverviewACBtn supsysticOverviewACBtnDisable"><i class="fa fa-times" aria-hidden="true"></i> Do not disturb me again</div>
			<div class="supsysticOverviewACFormNotification" style="color: red; float: left;" hidden>Fields with * are required to fill</div>
		</form>
		<div class="clear"></div>
		<div class="supsysticOverviewACFormOverlayTerms">
											<div class="supsysticOverviewACFormOverlayTermsClose"><i class="fa fa-times" aria-hidden="true"></i> Close</div>
											<p><span >This Contact Form License Agreement (&quot;Agreement&quot;) is entered into by and between Supsystic Pty LTD, a company registered under the laws of Australia, hereinafter referred to as &quot;Licensor,&quot; and the user of the contact form, hereinafter referred to as &quot;Licensee.&quot;</span></p>
											<p><span ><strong >1. Grant of License</strong></span></p>
											<p><span >Subject to the terms and conditions of this Agreement, Licensor hereby grants Licensee a non-exclusive, non-transferable, and revocable license to use the contact form provided by Licensor for collecting the following data: email, name, site domain and plugin name fields data (&quot;Data&quot;).</span></p>
											<p><span ><strong >2. Restrictions</strong></span></p>
											<p><span >Licensee agrees to use the contact form for lawful and legitimate purposes only and shall not:</span></p>
											<p><span >a. Use the contact form for any illegal or unauthorized purpose. b. Distribute or share the collected data without the consent of the data subjects. c. Modify, adapt, or reverse engineer the contact form or any of its components. d. Sell, sublicense, or distribute the contact form to third parties.</span></p>
											<p><span ><strong >3. Data Privacy</strong></span></p>
											<p><span >Licensor shall take reasonable measures to protect the collected data and shall not share, sell, or disclose the data to third parties without the explicit consent of Licensee.</span></p>
											<p><span ><strong >4. Support and Updates</strong></span></p>
											<p><span >Licensor may, at its discretion, provide support and updates for the contact form. Licensee is not entitled to automatic updates or support unless specified otherwise.</span></p>
											<p><span ><strong >5. Termination</strong></span></p>
											<p><span >This Agreement may be terminated by either party for any reason with written notice to the other party. Upon termination, Licensee shall cease using the contact form and remove it from their website.</span></p>
											<p><span ><strong >6. Intellectual Property</strong></span></p>
											<p><span >Licensor retains all rights, title, and interest in and to the contact form, including all related intellectual property rights.</span></p>
											<p><span ><strong >7. Disclaimer of Warranty</strong></span></p>
											<p><span >The contact form is provided &quot;as is&quot; without any warranty of any kind. Licensor disclaims all warranties, either express or implied, including, but not limited to, the implied warranties of merchantability and fitness for a particular purpose.</span></p>
											<p><span ><strong >8. Limitation of Liability</strong></span></p>
											<p><span >Licensor shall not be liable for any indirect, incidental, special, or consequential damages arising out of the use or inability to use the contact form.</span></p>
											<p><span ><strong >9. Entire Agreement</strong></span></p>
											<p><span >This Agreement constitutes the entire agreement between the parties concerning the subject matter hereof and supersedes all prior or contemporaneous agreements, understandings, and discussions.</span></p>
											<p><span ><strong >10. Contact Information</strong></span></p>
											<p><span >If you have any questions or concerns about this Agreement, please contact Supsystic Pty LTD at support@supsystic.com.</span></p>
											<p><span ><strong >11. Acceptance</strong></span></p>
											<p><span >By using the contact form, Licensee agrees to be bound by the terms and conditions of this Agreement.</span></p>
		</div>
</div>
<div id="supsysticOverviewACFormDialog" hidden>
			<div class="on-error" style="display:none">
					<p>Some errors occurred while sending mail please send your message trough this contact form:</p>
					<p><a href="https://supsystic.com/plugins/#contact" target="_blank">https://supsystic.com/plugins/#contact</a></p>
			</div>
			<div class="message"></div>
</div>
