<?php
    $proExtensionP = '<p class="yrm-ext-title yrm-ext-title-pro">'. __('PRO EXTENSION', YRM_LANG).'</p>';
    $comingSoonClass = '';
    if (!empty($extension['comingSoon'])) {
	    $proExtensionP = '<p class="yrm-ext-title yrm-ext-title-coming">'.__('Coming Soon', YRM_LANG).'</p>';
	    $comingSoonClass = 'yrm-coming-soon-wrapper';
    }
	$allowedTag = ReadMoreAdminHelper::getAllowedTags();
?>
<div class="product-banner" onclick="window.open('<?php echo YRM_PRO_URL.'#yrm-'.esc_attr($extension['shortKey']); ?>')">
	<div class="yrm-<?php echo esc_attr($extension['shortKey']);?> <?php echo esc_attr($comingSoonClass); ?>">
		<div class="yrm-types type-banner-pro">
			<?php echo wp_kses($proExtensionP, $allowedTag); ?>
		</div>
	</div>
	<div class="yrm-type-view-footer">
		<span class="yrm-promotion-title"><?php _e($extension['boxTitle'], YRM_LANG);?></span>
		<?php if(!empty($extension['videoURL'])): ?>
			<span class="yrm-play-promotion-video" data-href="<?php echo esc_attr($extension['videoURL']); ?>"></span>
		<?php endif; ?>
	</div>
</div>