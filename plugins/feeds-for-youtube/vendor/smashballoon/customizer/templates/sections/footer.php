<?php
	include_once CUSTOMIZER_ABSPATH . 'templates/sections/popup/license-learn-more.php';
	include_once CUSTOMIZER_ABSPATH . 'templates/sections/popup/why-renew-license-popup.php';
	$footer_template = sby_is_pro() ?  CUSTOMIZER_ABSPATH . 'templates/sections/footer-banner/footer-pro.php' :  CUSTOMIZER_ABSPATH . 'templates/sections/footer-banner/footer-free.php';
	include_once $footer_template;
	include_once CUSTOMIZER_ABSPATH . 'templates/sections/popup/feedtypes-popup.php';
	include_once CUSTOMIZER_ABSPATH . 'templates/sections/popup/feedtemplates-popup.php';
	include_once CUSTOMIZER_ABSPATH . 'templates/sections/popup/accountapi-popup.php';
	include_once CUSTOMIZER_ABSPATH . 'templates/sections/popup/confirm-dialog-popup.php';
	include_once CUSTOMIZER_ABSPATH . 'templates/sections/popup/embed-popup.php';
	include_once CUSTOMIZER_ABSPATH . 'templates/sections/popup/extensions-popup.php';
    include_once CUSTOMIZER_ABSPATH . 'templates/sections/popup/onboarding-customizer-popup.php';
?>
<div class="sb-notification-ctn" :data-active="notificationElement.shown" :data-type="notificationElement.type">
	<div class="sb-notification-icon" v-html="svgIcons[notificationElement.type+'Notification']"></div>
	<span class="sb-notification-text" v-html="notificationElement.text"></span>
</div>

<div class="sb-full-screen-loader" :data-show="fullScreenLoader ? 'shown' :  'hidden'">
	<div class="sb-full-screen-loader-logo">
		<div class="sb-full-screen-loader-spinner"></div>
		<div class="sb-full-screen-loader-img" v-html="svgIcons['smash']"></div>
	</div>
	<div class="sb-full-screen-loader-txt">
		Loading...
	</div>
</div>

<sb-confirm-dialog-component
:dialog-box.sync="dialogBox"
:source-to-delete="sourceToDelete"
:svg-icons="svgIcons"
:parent-type="'builder'"
:generic-text="genericText"
></sb-confirm-dialog-component>