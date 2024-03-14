<div id="ppsPopupEditDesignTabs">
	<a href="<?php echo viewPps::ksesString($this->ppsAddNewUrl. '&change_for='. $this->popup['id'])?>" class="button button-primary ppsPopupSelectTpl">
		<?php _e('Change PopUp Template', PPS_LANG_CODE)?>
	</a>
	<h3 class="nav-tab-wrapper">
		<?php $i = 0;?>
		<?php foreach($this->designTabs as $tKey => $tData) { ?>
			<?php
				$iconClass = 'pps-edit-icon';
				if(isset($tData['avoid_hide_icon']) && $tData['avoid_hide_icon']) {
					$iconClass .= '-not-hide';	// We will just exclude it from selector to hide, jQuery.not() - make browser slow down in this case - so better don't use it
				}
			?>
			<a class="nav-tab <?php if($i == 0) { echo viewPps::ksesString('nav-tab-active'); }?>" href="#<?php echo viewPps::ksesString($tKey)?>">
				<?php if(isset($tData['fa_icon'])) { ?>
					<i class="<?php echo viewPps::ksesString($iconClass)?> fa <?php echo viewPps::ksesString($tData['fa_icon'])?>"></i>
				<?php } elseif(isset($tData['icon_content'])) { ?>
					<i class="<?php echo viewPps::ksesString($iconClass)?> fa"><?php echo viewPps::ksesString($tData['icon_content'])?></i>
				<?php }?>
				<span class="ppsPopupTabTitle"><?php echo viewPps::ksesString($tData['title'])?></span>
			</a>
		<?php $i++; }?>
	</h3>
	<div style="clear: both;"></div>
	<?php foreach($this->designTabs as $tKey => $tData) { ?>
		<div id="<?php echo viewPps::ksesString($tKey)?>" class="ppsTabContent">
			<?php echo viewPps::ksesString($tData['content'])?>
		</div>
	<?php }?>
</div>
