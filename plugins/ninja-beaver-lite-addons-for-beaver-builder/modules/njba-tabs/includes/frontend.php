<?php
$activeTabIndex = '';
$activeTabIndex = $activeTabIndex > count( $settings->items ) ? 0 : $activeTabIndex;
$activeTabIndex = $activeTabIndex < 1 ? 0 : $activeTabIndex - 1;
$css_id         = '';
$layout         = $settings->tab_style_layout;
$i              = '';
?>
<div class="njba-tabs njba-tabs-<?php echo $settings->tab_style_layout; ?> njba-tab-section-main ">
	<?php if ( $layout == 'style-1' ) { ?>
        <div class="njba-tabs-labels njba-tab-menu-main tab-align-<?php echo $settings->tab_alignment; ?>">
            <div id="" class="njba-tabs-nav njba-tabs-nav<?php echo $id; ?> nav" role="tablist" data-index="<?php echo $i; ?>">

				<?php $module->njbaGetTabTitle(); ?>
            </div>
        </div>
        <div class="njba-tabs-panels njba-clearfix">
			<?php $module->njbaTabContent(); ?>
        </div>
	<?php } ?>
	<?php if ( $layout == 'style-2' ) { ?>
        <div class="njba-col-xs-12 njba-col-sm-4">
            <div class="njba-tabs-labels njba-tab-menu-main tab-align-<?php echo $settings->tab_alignment; ?>">
                <div id="" class="njba-tabs-nav njba-tabs-nav<?php echo $id; ?> nav" role="tablist" data-index="<?php echo $i; ?>">
					<?php $module->njbaGetTabTitle(); ?>
                </div>
            </div>
        </div>
        <div class="njba-col-xs-12 njba-col-sm-8">
            <div class="njba-tabs-panels njba-clearfix">
				<?php $module->njbaTabContent(); ?>
            </div>
        </div>

	<?php } ?>
	<?php if ( $layout == 'style-3' ) { ?>
        <div class="njba-tabs-labels njba-tab-menu-main tab-align-<?php echo $settings->tab_alignment; ?>">
            <div id="" class="njba-tabs-nav njba-tabs-nav<?php echo $id; ?> nav" role="tablist" data-index="<?php echo $i; ?>">
				<?php $module->njbaGetTabTitle(); ?>
            </div>
        </div>
        <div class="njba-tabs-panels njba-clearfix">
			<?php $module->njbaTabContent(); ?>
        </div>

	<?php } ?>

	<?php if ( $layout == 'style-4' ) { ?>
        <div class="njba-col-xs-12 njba-col-sm-4 njba-pull-right">
            <div class="njba-tabs-labels njba-tab-menu-main tab-align-<?php echo $settings->tab_alignment; ?>">
                <div id="" class="njba-tabs-nav njba-tabs-nav<?php echo $id; ?> nav" role="tablist" data-index="<?php echo $i; ?>">
					<?php $module->njbaGetTabTitle(); ?>

                </div>
            </div>
        </div>
        <div class="njba-col-xs-12 njba-col-sm-8">
            <div class="njba-tabs-panels njba-clearfix">
				<?php $module->njbaTabContent(); ?>
            </div>
        </div>

	<?php } ?>
	<?php if ( $layout == 'style-5' ) { ?>
        <div class="njba-tabs-labels njba-tab-menu-main tab-align-<?php echo $settings->tab_alignment; ?>">
            <div id="" class="njba-tabs-nav njba-tabs-nav<?php echo $id; ?> nav" role="tablist" data-index="<?php echo $i; ?>">

				<?php $module->njbaGetTabTitle(); ?>
            </div>
        </div>
        <div class="njba-tabs-panels njba-clearfix">
			<?php $module->njbaTabContent(); ?>
        </div>

	<?php } ?>
	<?php if ( $layout == 'style-6' ) { ?>
        <div class="njba-tabs-labels njba-tab-menu-main tab-align-<?php echo $settings->tab_alignment; ?>">
            <div id="" class="njba-tabs-nav njba-tabs-nav<?php echo $id; ?> nav" role="tablist" data-index="<?php echo $i; ?>">
				<?php $module->njbaGetTabTitle(); ?>
            </div>
        </div>
        <div class="njba-tabs-panels njba-clearfix">
			<?php $module->njbaTabContent(); ?>
        </div>

	<?php } ?>
	<?php if ( $layout == 'style-7' ) { ?>
		<?php
		$i = '';
		?>
        <div class="njba-tabs-labels njba-tab-menu-main tab-align-<?php echo $settings->tab_alignment; ?>">
            <div id="" class="njba-tabs-nav njba-tabs-nav<?php echo $id; ?> nav" role="tablist" data-index="<?php echo $i; ?>">

				<?php $module->njbaGetTabTitle(); ?>
            </div>
        </div>
        <div class="njba-tabs-panels njba-clearfix">
			<?php $module->njbaTabContent(); ?>
        </div>

	<?php } ?>
	<?php if ( $layout == 'style-8' ) { ?>
        <div class="njba-tabs-labels njba-tab-menu-main tab-align-<?php echo $settings->tab_alignment; ?>">
            <div id="" class="njba-tabs-nav njba-tabs-nav<?php echo $id; ?> nav" role="tablist" data-index="<?php echo $i; ?>">

				<?php $module->njbaGetTabTitle(); ?>
            </div>
        </div>
        <div class="njba-tabs-panels njba-clearfix">
			<?php $module->njbaTabContent(); ?>
        </div>

	<?php } ?>
</div>

<div class="accordion">
    <div class="accordion-section">
		<?php for ( $i = 0, $iMax = count( $settings->items ); $i < $iMax; $i ++ ) { ?>
            <a href="#<?php echo $i; ?>" class="accordion-section-title">
				<?php $class = ( $settings->show_icon === 'yes' ) ? '<span class="njba-accordion-icon"><i class= " ' . $settings->items[ $i ]->tab_font_icon . '"></i></span>' : '';
				$hover       = 'hover_title_class';
				$hover_icon  = 'hover_icon_class'; ?>
                <div class="njba-tab-label-inner icon-align-<?php echo $settings->tab_icon_position; ?>">
					<?php if ( $settings->tab_icon_position === 'left' || $settings->tab_icon_position === 'top' ) { ?>
						<?php echo $class; ?>
                        <span class="njba-accordion-label"> <?php echo $settings->items[ $i ]->label; ?></span>
					<?php } ?>
					<?php if ( $settings->tab_icon_position === 'right' || $settings->tab_icon_position === 'bottom' ) { ?>
                        <span class="njba-accordion-label"> <?php echo $settings->items[ $i ]->label; ?></span>
						<?php echo $class; ?>
					<?php } ?>
                </div>
            </a>
            <div id="<?php echo $i; ?>" class="accordion-section-content">
                <p><?php echo $settings->items[ $i ]->content; ?></p>
            </div>
		<?php } ?>
    </div>
</div>
