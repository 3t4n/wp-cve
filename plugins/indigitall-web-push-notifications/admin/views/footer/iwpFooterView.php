<?php
	// Definición de constantes
	$footerTitle = __('We solve your doubts!', 'iwp-text-domain');
	$footerText1 = __('If you need additional information, you can consult our guide', 'iwp-text-domain');
	$guideLink = isset($dynamicDocumentationLink) ? $dynamicDocumentationLink : '';
	$guideText = __('WordPress Guide', 'iwp-text-domain');

	$footerText3 = __('If you are a developer, you can consult this guide', 'iwp-text-domain');
	$developerGuideLink = isset($developerDocumentationLink) ? $developerDocumentationLink : '';
	$developerGuideText = __('Developer guide', 'iwp-text-domain');

	$footerText2 = __('If you have more questions, do not hesitate to contact us at', 'iwp-text-domain');
	$emailLink = 'support@iurny.com';

	$developerMode = (isset($developerMode) && $developerMode) ? '1' : '0';
	$developerModeClass = ($developerMode === '0') ? '' : ' active ';
	$pluginVersion = IWP_PLUGIN_VERSION;
?>

<div class="iwp-admin-footer-container">
	<div class="iwp-admin-footer-title"><?php echo($footerTitle); ?></div>
	<div class="iwp-admin-footer-text">
		<span><?php echo($footerText1); ?>&nbsp;</span>
		<a class="iwp-admin-footer-link" href="<?php echo($guideLink); ?>" target="_blank"><?php echo($guideText); ?></a>
	</div>
	<div class="iwp-admin-footer-text">
		<span><?php echo($footerText3); ?>&nbsp;</span>
		<a class="iwp-admin-footer-link" href="<?php echo($developerGuideLink); ?>" target="_blank"><?php echo($developerGuideText); ?></a>
	</div>
	<div class="iwp-admin-footer-text">
		<span><?php echo($footerText2); ?>&nbsp;</span>
		<a class="iwp-admin-footer-link" href="mailto:<?php echo($emailLink); ?>" target="_blank"><?php echo($emailLink); ?></a>
	</div>
	<div id="iwpDeveloperMode" class="iwp-admin-footer-plugin-version <?php echo($developerModeClass); ?>" data-mode="<?php echo($developerMode); ?>">
		<?php echo($pluginVersion); ?>
	</div>
</div>
