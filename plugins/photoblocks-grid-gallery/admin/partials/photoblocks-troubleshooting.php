<style>
/*
Name: Familiar
Description: Looks like SyntaxHighlighter.
Version: 1.0
Author: Aram Kocharyan
URL: http://aramk.com/
*/
.crayon-theme-familiar {
	border-width: 1px !important;
	text-shadow: none !important;
}
.crayon-theme-familiar-inline {
	border-width: 1px !important;
	border-color: #ddd !important;
	border-style: solid !important;
	background: #fafafa !important;
}
.crayon-theme-familiar .crayon-table .crayon-nums {
	border-right-width: 3px !important;
	border-right-style: solid !important;
	border-right-color: #6ce26c !important;
	padding-right: 5px !important;
	color: #afafaf !important;
}
.crayon-theme-familiar .crayon-striped-line {
	background: #f9f9f9 !important;
}
.crayon-theme-familiar .crayon-striped-num {
	color: #afafaf !important;
}
.crayon-theme-familiar .crayon-line {
	padding-left: 10px !important;
}
.crayon-theme-familiar .crayon-marked-line {
	background: #fffee2 !important;
	border-width: 1px !important;
	border-color: #e9e579 !important;
}
.crayon-theme-familiar .crayon-marked-num {
	border-width: 1px !important;
	color: #333333 !important;
}
.crayon-theme-familiar .crayon-marked-line.crayon-striped-line {
	background: #faf8d1 !important;
}
.crayon-theme-familiar .crayon-marked-num.crayon-striped-num {
	color: #333333 !important;
}
.crayon-theme-familiar .crayon-marked-line.crayon-top {
}
.crayon-theme-familiar .crayon-marked-num.crayon-top {
}
.crayon-theme-familiar .crayon-marked-line.crayon-bottom {
}
.crayon-theme-familiar .crayon-marked-num.crayon-bottom {
}
.crayon-theme-familiar .crayon-info {
	background: #faf9d7 !important;
	border-bottom-width: 1px !important;
	border-bottom-color: #b1af5e !important;
	border-bottom-style: solid !important;
	color: #7e7d34 !important;
}
.crayon-theme-familiar .crayon-toolbar {
	background: #eee !important;
	border-bottom-width: 1px !important;
	border-bottom-color: #dedede !important;
	border-bottom-style: solid !important;
}
.crayon-theme-familiar .crayon-toolbar > div {
	float: left !important;
}
.crayon-theme-familiar .crayon-toolbar .crayon-tools {
	float: right !important;
}
.crayon-theme-familiar .crayon-title {
	color: #333 !important;
}
.crayon-theme-familiar .crayon-language {
	color: #999 !important;
}
.crayon-theme-familiar .crayon-button {
	background-color: transparent !important;
}
.crayon-theme-familiar .crayon-button:hover {
	background-color: #EEE !important;
	color: #666;
}
.crayon-theme-familiar .crayon-button.crayon-pressed:hover {
	background-color: #EEE !important;
	color: #666;
}
.crayon-theme-familiar .crayon-button.crayon-pressed {
	background-color: #BCBCBC !important;
	color: #FFF;
}
.crayon-theme-familiar .crayon-button.crayon-pressed:active {
	background-color: #BCBCBC !important;
	color: #FFF;
}
.crayon-theme-familiar .crayon-button:active {
	background-color: #BCBCBC !important;
	color: #FFF;
}
.crayon-theme-familiar .crayon-pre .crayon-c {
	color: #ff8000 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-s {
	color: #008000 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-p {
	color: #b85c00 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-ta {
	color: #FF0000 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-k {
	color: #800080 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-st {
	color: #800080 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-r {
	color: #800080 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-t {
	color: #800080 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-m {
	color: #800080 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-i {
	color: #000 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-e {
	color: #004ed0 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-v {
	color: #002D7A !important;
}
.crayon-theme-familiar .crayon-pre .crayon-cn {
	color: #ce0000 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-o {
	color: #006fe0 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-sy {
	color: #333 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-n {
	color: #666 !important;
	font-style: italic !important;
}
.crayon-theme-familiar .crayon-pre .crayon-f {
	color: #999 !important;
}
.crayon-theme-familiar .crayon-pre .crayon-h {
	color: #006fe0 !important;
}
.crayon-font-consolas * {
	font-family: 'Courier New', monospace !important;
}
</style>
<style>
	h5 {
		font-size: 20px;
	}
	li {
		list-style-type: disc;
	}
	.panel {
		background: #fff;
		padding: 20px;
	}
	.panel-body ul {
		margin-left: 2em;
	}
</style>

<div class="wrap" id="photoblocks-dashboard">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Troubleshooting', 'photoblocks' ); ?></h1>

	<hr class="wp-header-end">
	<br>
	<br>
	<br>
	<div class="panel">
	<div class="panel-body toggle-content">
   <div class="fusion-separator fusion-full-width-sep sep-none" style="border-color:#e0dede;margin-left: auto;margin-right: auto;margin-top:3px;margin-bottom:3px;"></div>
   <div class="fusion-title title sep-underline fusion-title-size-five">
	  <h5 class="title-heading-left" data-fontsize="17" data-lineheight="22">Server setup</h5>
	  <div class="title-sep-container">
		 <div class="title-sep sep-underline"></div>
	  </div>
   </div>
   <ul>
	  <li>Are you using <strong>Hostgator</strong>? Simply contact their support via phone or live chat to report <strong>mod security blockage</strong>. Send them the faulty link to a thumbnail, that you opened on a new tab in step 5.&nbsp;</li>
	  <li>
		 If you are not on Hostgator but getting 403 errors then try adding these lines to your <span class="fusion-highlight dark hiliDefault highlight1" style="background-color:#fafafa;">.htaccess</span> file in the WordPress installation folder. Revert the modification if it has no effect and <strong>contact the hosting's support</strong>.<br>
		 <div id="crayon-5ab173a4b5351560286152" class="crayon-syntax crayon-theme-familiar crayon-font-consolas crayon-os-pc print-yes notranslate" data-settings=" minimize scroll-mouseover disable-anim" style="margin-bottom: 12px; font-size: 14px !important; line-height: 17px !important; height: auto;">
			<br>
			<br>
			<div class="crayon-main" style="position: relative; z-index: 1; overflow: hidden;">
			   <table class="crayon-table" style="">
				  <tbody>
					 <tr class="crayon-row">
						<td class="crayon-nums " data-settings="show">
						   <div class="crayon-nums-content" style="font-size: 14px !important; line-height: 17px !important;">
							  <div class="crayon-num" data-line="crayon-5ab173a4b5351560286152-1">1</div>
							  <div class="crayon-num crayon-striped-num" data-line="crayon-5ab173a4b5351560286152-2">2</div>
							  <div class="crayon-num" data-line="crayon-5ab173a4b5351560286152-3">3</div>
							  <div class="crayon-num crayon-striped-num" data-line="crayon-5ab173a4b5351560286152-4">4</div>
						   </div>
						</td>
						<td class="crayon-code">
						   <div class="crayon-pre" style="font-size: 14px !important; line-height: 17px !important; -moz-tab-size:4; -o-tab-size:4; -webkit-tab-size:4; tab-size:4;">
							  <div class="crayon-line" id="crayon-5ab173a4b5351560286152-1"><span class="crayon-r ">&lt;</span><span class="crayon-r ">IfModule</span><span class="crayon-h"> </span><span class="crayon-e ">mod_security.c</span><span class="crayon-r ">&gt;</span></div>
							  <div class="crayon-line crayon-striped-line" id="crayon-5ab173a4b5351560286152-2"><span class="crayon-r">&nbsp;&nbsp;SecFilterEngine</span><span class="crayon-h"> </span><span class="crayon-i ">Off</span></div>
							  <div class="crayon-line" id="crayon-5ab173a4b5351560286152-3"><span class="crayon-r">&nbsp;&nbsp;SecFilterScanPOST</span><span class="crayon-h"> </span><span class="crayon-i ">Off</span></div>
							  <div class="crayon-line crayon-striped-line" id="crayon-5ab173a4b5351560286152-4"><span class="crayon-r ">&lt;</span><span class="crayon-r ">/IfModule</span><span class="crayon-r ">&gt;</span></div>
						   </div>
						</td>
					 </tr>
				  </tbody>
			   </table>
			</div>
		 </div>
	  </li>
	  <li>Are you on <strong>Bluehost</strong>'s Optimized Hosting for WordPress? Make sure you turn off <strong>Varnish caching</strong>. It empties query strings.</li>
	  <li>
		 Open your <span class="fusion-highlight dark hiliDefault highlight1" style="background-color:#fafafa;">.htaccess</span> file and ensure you <strong>do not have this line</strong> in it:<br>
		  
	  </li>
	  <li>
		 Or something like this:<br>         
		 <div id="crayon-5ab173a4b536d610667025" class="crayon-syntax crayon-theme-familiar crayon-font-consolas crayon-os-pc print-yes notranslate" data-settings=" minimize scroll-mouseover disable-anim" style=" margin-bottom: 12px; font-size: 14px !important; line-height: 17px !important; height: auto;">
			<div class="crayon-main" style="position: relative; z-index: 1; overflow: hidden;">
			   <table class="crayon-table" style="margin-left: -1px;">
				  <tbody>
					 <tr class="crayon-row">
						<td class="crayon-nums " data-settings="hide">
						   <div class="crayon-nums-content" style="font-size: 14px !important; line-height: 17px !important;">
							  <div class="crayon-num" data-line="crayon-5ab173a4b536d610667025-1">1</div>
						   </div>
						</td>
						<td class="crayon-code">
						   <div class="crayon-pre" style="font-size: 14px !important; line-height: 17px !important; -moz-tab-size:4; -o-tab-size:4; -webkit-tab-size:4; tab-size:4;">
							  <div class="crayon-line" id="crayon-5ab173a4b536d610667025-1"><span class="crayon-r">RewriteRule</span><span class="crayon-h"> </span><span class="crayon-i ">wp-content</span><span class="crayon-o">/</span><span class="crayon-i ">plugins</span><span class="crayon-o">/</span><span class="crayon-sy">(</span><span class="crayon-sy">.</span><span class="crayon-o">*</span><span class="crayon-sy">\</span><span class="crayon-sy">.</span><span class="crayon-i ">php</span><span class="crayon-sy">)</span><span class="crayon-sy">$</span><span class="crayon-h"> </span><span class="crayon-i ">-</span><span class="crayon-h"> </span><span class="crayon-cn ">[R=404,L]</span></div>
						   </div>
						</td>
					 </tr>
				  </tbody>
			   </table>
			</div>
		 </div>
	  </li>
	  <li>Make sure you have <strong>GD library</strong>. A possible error is "GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library".</li>
   </ul>
   <div class="fusion-sep-clear"></div>
   <div class="fusion-separator fusion-full-width-sep sep-none" style="border-color:#e0dede;margin-left: auto;margin-right: auto;margin-top:3px;margin-bottom:3px;"></div>
   <div class="fusion-title title sep-underline fusion-title-size-five">
	  <h5 class="title-heading-left" data-fontsize="17" data-lineheight="22">Security plugins</h5>
	  <div class="title-sep-container">
		 <div class="title-sep sep-underline"></div>
	  </div>
   </div>
   <ul>
	  <li><strong>iThemes Security (formerly Better WP Security)</strong> settings &gt; System Tweaks &gt; Configure Settings &gt; <span class="fusion-highlight dark hiliDefault highlight1" style="background-color:#fafafa;">Disable PHP in Plugins</span> – Off. Also, System Tweaks &gt; <span class="fusion-highlight dark hiliDefault highlight1" style="background-color:#fafafa;">Filter Suspicious Query Strings</span> – Disable.</li>
	  <li><strong>Sucuri Security</strong> plugin &gt; <span class="fusion-highlight dark hiliDefault highlight1" style="background-color:#fafafa;">1-Click Hardening</span> – Do not use it.</li>
	  <li><strong>WP All in ONE Security</strong>:&nbsp; WP Security &gt; Firewall &gt; Additional Firewall Rules (tabs at top of page) &gt; <span class="fusion-highlight dark hiliDefault highlight1" style="background-color:#fafafa;">Bad Query Strings</span> – Untick.<br> <strong> AND/OR</strong> WP Security &gt; Firewall &gt; 6G Blacklist Firewall Rules (tabs at top of page) &gt; <span class="fusion-highlight dark hiliDefault highlight1" style="background-color:#fafafa;">Enable 6G Firewall Protection</span> – Untick.</li>
   </ul>
   <div class="fusion-sep-clear"></div>
   <div class="fusion-separator fusion-full-width-sep sep-none" style="border-color:#e0dede;margin-left: auto;margin-right: auto;margin-top:3px;margin-bottom:3px;"></div>
   <div class="fusion-title title sep-underline fusion-title-size-five">
	  <h5 class="title-heading-left" data-fontsize="17" data-lineheight="22">Malformed CDN URL or misconfigured CDN</h5>
	  <div class="title-sep-container">
		 <div class="title-sep sep-underline"></div>
	  </div>
   </div>
   <ul>
	  <li>Make sure you use the <span class="fusion-highlight dark hiliDefault highlight1" style="background-color:#fafafa;">PhotoBlocks settings &gt; General > Host name &gt;&nbsp;Replace site's hostname with</span> setting. It must not start with http, contain any slashes or spaces.</li>      
	  <li>If you feel that your CDN is not able to provide thumbnails, contact the support of either the CDN or PhotoBlocks for more information. Usually all it takes is to change a couple of settings on the CDN.</li>
	  <li>If you use a WordPress plugin to connect with your CDN, let that be <a target="_blank" href="https://wordpress.org/plugins/w3-total-cache/" rel="noopener">W3 Total Cache</a>.</li>
   </ul>
   <div class="fusion-sep-clear"></div>
   <div class="fusion-separator fusion-full-width-sep sep-none" style="border-color:#e0dede;margin-left: auto;margin-right: auto;margin-top:3px;margin-bottom:3px;"></div>
   <div class="fusion-title title sep-underline fusion-title-size-five">
	  <h5 class="title-heading-left" data-fontsize="17" data-lineheight="22">Plan B, Jetpack Photon</h5>
	  <div class="title-sep-container">
		 <div class="title-sep sep-underline"></div>
	  </div>
   </div>
   <p>If all options above are failing, just install the <a href="https://wordpress.org/plugins/jetpack/">Jetpack</a> plugin and enable the Photon module. It's a <strong>thumbnail creation alternative</strong> and will make your images load faster as it's like a free CDN.</p>
   <ul class="fusion-checklist fusion-checklist-1">
	  <li class="fusion-li-item">
		 <span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-external-link" style="color:#333;"></i></span>
		 <div class="fusion-li-item-content"><a target="_blank" href="http://jetpack.me/" rel="noopener">Read more at jetpack.me</a></div>
	  </li>
   </ul>
</div>
	</div>
</div>
