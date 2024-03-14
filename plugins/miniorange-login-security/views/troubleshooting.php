<?php
/**
 * Display Troubleshooting tab.
 *
 * @package miniorange-login-security/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
echo '<div class="momls_wpns_divided_layout">
        <div class="mo2f_table_layout">
	<table class="momls_wpns_help">
	<tbody><tr>
	<td class="momls_wpns_help_cell">
	<div id="momls_wpns_help_curl_title" class="momls_wpns_title_panel">
	<div class="momls_wpns_help_title">How to enable PHP cURL extension? (Pre-requisite)</div>
	</div>
	<div hidden="" id="momls_wpns_help_curl_desc" class="momls_wpns_help_desc" style="display: none;">
	<ul>
	    <li>Step 1:&nbsp;&nbsp;&nbsp;&nbsp;Open php.ini file located under php installation folder.</li>
	    <li>Step 2:&nbsp;&nbsp;&nbsp;&nbsp;Search for <b>extension=php_curl.dll</b>. </li>
	    <li>Step 3:&nbsp;&nbsp;&nbsp;&nbsp;Uncomment it by removing the semi-colon(<b>;</b>) in front of it.</li>
	    <li>Step 4:&nbsp;&nbsp;&nbsp;&nbsp;Restart the Apache Server.</li>
	</ul>
	     For any further queries, please contact us.</div>
	</td></tr><tr>
	<td class="momls_wpns_help_cell">
	<div id="momls_wpns_help_mobile_auth_title" class="momls_wpns_title_panel">
	<div class="momls_wpns_help_title">How to enable Mobile authentication ( 2 Factor ) ?</div>
	</div>
	<div hidden="" id="momls_wpns_help_mobile_auth_desc" class="momls_wpns_help_desc" style="display: none;">
	<ul>
	<li>Step 1:&nbsp;&nbsp;&nbsp;&nbsp;Go to <b>Login Security</b> Tab and go to <b>Mobile Authentication</b> section.</li>
	<li>Step 2:&nbsp;&nbsp;&nbsp;&nbsp;If you have not installed 2 factor plugin you wil see link <b>"Install 2 Factor Plugin"</b>. Click this link and activate miniOrange 2 factor plugin.</li>
	<li>Step 3:&nbsp;&nbsp;&nbsp;&nbsp;If you already have 2 factor plugin installed and its disable you wil see link <b>"Click here to activate 2 Factor Plugin"</b>. Click this link and activate miniOrange 2 factor plugin.</li>
    <li>Step 4:&nbsp;&nbsp;&nbsp;&nbsp;Go to <b>"miniOrange 2-Factor"</b> tab from WordPress sidebar</li>
	<li>Step 5:&nbsp;&nbsp;&nbsp;&nbsp;Click on <b>"Setup Two-Factor"</b> tab and configure your 2nd factor method which you want to use during login.</li>
	</ul>
	For any further queries, please contact us.
	</div></td>
	</tr><tr>
	<td class="momls_wpns_help_cell">
	<div id="momls_wpns_help_disposable_title" class="momls_wpns_title_panel">
	<div class="momls_wpns_help_title">What "Block Registerations from fake users" does ? (Premium Feature)</div>
	</div>
	<div hidden="" id="momls_wpns_help_disposable_desc" class="momls_wpns_help_desc" style="display: none;">
	There are many fake email provides which provides dispsable or temporary email address to users which expires in few minutes or few hours. You can block registrations from those email addresses.<br><br>For any further queries, please contact us.	</div>
	</td></tr>
	<tr>
	<td class="momls_wpns_help_cell">
	<div id="momls_wpns_help_strong_pass_title" class="momls_wpns_title_panel">
	<div class="momls_wpns_help_title">What "Enforce Strong Passwords" does ?</div>
	</div>
	<div hidden="" id="momls_wpns_help_strong_pass_desc" class="momls_wpns_help_desc" style="display: none;">
    This feature check if users are having strong passwords for their account. If No, we force users to change their passwords to strong passwords during their login to WordPress.<br><br>
	For any further queries, please contact us.</div>
	</td></tr><tr>
	<td class="momls_wpns_help_cell">
	<div id="momls_wpns_help_adv_user_ver_title" class="momls_wpns_title_panel">
	<div class="momls_wpns_help_title">What "Advanced User Verification" does ? (Premium Feature)</div>
	</div>
	<div hidden="" id="momls_wpns_help_adv_user_ver_desc" class="momls_wpns_help_desc" style="display: none;">
	This verifies users phone number or email address before registering users by sending One Time Password ( OTP ) on his phone number or email address. You can avoid fake registrations with it.<br><br>
		For any further queries, please contact us.</div></td></tr>
		<tr>
	<td class="momls_wpns_help_cell">
							<div id="momls_wpns_help_social_login_title" class="momls_wpns_title_panel">
								<div class="momls_wpns_help_title">What "Social Login Integration" does ? (Premium Feature)</div>
							</div>
							<div hidden="" id="momls_wpns_help_social_login_desc" class="momls_wpns_help_desc" style="display: none;">
								You can allow your users to login or register to your site with their existing account with supported social networks like Google, Twitter, Facebook, Vkontakte, LinkedIn, Instagram, Amazon, Salesforce, Windows Live. No need to remember multiple account credentials for users.<br><br>
								
								For any further queries, please contact us.								
							</div>
						</td>
					</tr><tr>
						<td class="momls_wpns_help_cell">
							<div id="momls_wpns_help_custom_template_title" class="momls_wpns_title_panel">
								<div class="momls_wpns_help_title">What "Customized Email Templates" does ? (Premium Feature)</div>
							</div>
							<div hidden="" id="momls_wpns_help_custom_template_desc" class="momls_wpns_help_desc" style="display: none;">
								You can customize email templates for emails that are sent to users for unusual activities and also Administrator for blocked IP\'s. You can add your own subject, from name and email content. Also we support HTML content for email body.<br><br>
								
								For any further queries, please contact us.								
							</div>
						</td>
					</tr>
					
				</tbody></table>
	    </div>
	</div>';
