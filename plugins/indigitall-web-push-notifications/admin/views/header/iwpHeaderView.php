<?php
	$logoSrc = isset($logoSrc) ? "<img class='iwp-admin-header-logo' src='$logoSrc' alt='iurny Logo'>" : '';
	$consoleSso = isset($consoleSso) ? $consoleSso : '#'; // Better safe than sorry

	// Definición de constantes
	$guideLink = isset($dynamicDocumentationLink) ? $dynamicDocumentationLink : '';
	$guideText = __('Help guide', 'iwp-text-domain');
	$consoleText = strtoupper(__('Go to console','iwp-text-domain'));

	$logoutModal = isset($logoutModal) ? $logoutModal : '';
	$subHeaderHtml = isset($subHeaderHtml) ? $subHeaderHtml : '';
	$mainMenuHtml = isset($mainMenuHtml) ? $mainMenuHtml : '';

	$registered = (isset($registered) && $registered);
	$consoleEvent = iwpCustomEvents::MICRO_IR_CONSOLA;
?>

<div class="iwp-admin-header-container">
	<div class="iwp-admin-header-logo-container"><?php echo($logoSrc); ?></div>
	<?php if ($registered) { ?>
			<div class="iwp-admin-header-info">
				<div class="iwp-admin-header-info-guide">
					<a class="iwp-admin-header-info-guide-link" href="<?php echo($guideLink); ?>" target="_blank">
						<?php echo($guideText); ?>
					</a>
				</div>
				<div class="iwp-admin-header-info-console">
					<a class="iwp-admin-header-info-console-link" href="<?php echo($consoleSso); ?>"
					   data-event="<?php echo($consoleEvent); ?>">
						<?php echo($consoleText); ?>
					</a>
				</div>
			</div>
	<?php }	?>
</div>

<?php
	if ($registered) {
		echo($subHeaderHtml);
	}
	echo($mainMenuHtml);
	echo($logoutModal);
?>
