<div id="ppsPopupEditTabs">
	<section class="supsystic-bar supsystic-sticky sticky-padd-next sticky-save-width sticky-base-width-auto" data-prev-height="#supsystic-breadcrumbs" data-next-padding-add="15">
		<h3 class="nav-tab-wrapper ppsMainTabsNav" style="margin-bottom: 0px; margin-top: 12px;">
			<?php $i = 0;?>
			<?php foreach($this->tabs as $tKey => $tData) { ?>
				<?php
					$iconClass = 'pps-edit-icon';
					if(isset($tData['avoid_hide_icon']) && $tData['avoid_hide_icon']) {
						$iconClass .= '-not-hide';	// We will just exclude it from selector to hide, jQuery.not() - make browser slow down in this case - so better don't use it
					}
				?>
				<a class="nav-tab <?php if($i == 0) { echo esc_html('nav-tab-active'); }?>" href="#<?php echo esc_html($tKey)?>">
					<?php if(isset($tData['fa_icon'])) { ?>
						<i class="<?php echo esc_html($iconClass)?> fa <?php echo viewPps::ksesString($tData['fa_icon'])?>"></i>
					<?php } elseif(isset($tData['icon_content'])) { ?>
						<i class="<?php echo esc_html($iconClass)?> fa"><?php echo viewPps::ksesString($tData['icon_content'])?></i>
					<?php }?>
					<span class="ppsPopupTabTitle"><?php echo viewPps::ksesString($tData['title'])?></span>
				</a>
			<?php $i++; }?>
		</h3>
	</section>
	<section>
		<div class="supsystic-item supsystic-panel" style="padding-left: 10px;">
			<div id="containerWrapper">
				<form id="ppsPopupEditForm">
					<?php foreach($this->tabs as $tKey => $tData) { ?>
						<div id="<?php echo esc_html($tKey)?>" class="ppsTabContent">
							<?php echo viewPps::ksesString($tData['content'])?>
						</div>
					<?php }?>
					<?php if(isset($this->popup['params']['opts_attrs'])) {?>
						<?php foreach($this->popup['params']['opts_attrs'] as $optKey => $attr) {
							echo viewPps::ksesString(htmlPps::hidden('params[opts_attrs]['. $optKey. ']', array('value' => $attr)));
						}?>
					<?php }?>
					<?php echo viewPps::ksesString(htmlPps::hidden('mod', array('value' => 'popup')))?>
					<?php echo viewPps::ksesString(htmlPps::hidden('action', array('value' => 'save')))?>
					<?php echo viewPps::ksesString(htmlPps::hidden('id', array('value' => $this->popup['id'])))?>
					<?php echo viewPps::ksesString(htmlPps::defaultNonceForAdminPanel())?>
				</form>
				<div style="clear: both;"></div>
				<div id="ppsPopupPreview" style="">
					<iframe id="ppsPopupPreviewFrame" width="" height="" frameborder="0" src="" style=""></iframe>
					<?php
					$popupEditAdminData = array(
						'previewUrl' => $this->previewUrl,
					);
						$popupEditAdminData = dispatcherPps::applyFilters('jsInitVariables', $popupEditAdminData);
						framePps::_()->addScript('popupEditAdmin', PPS_JS_PATH. 'popupEditAdmin.js');
						framePps::_()->addJSVar('popupEditAdmin', 'PPS_PEA_DATA', $popupEditAdminData);
					?>
				</div>
			</div>
		</div>
	</section>
</div>
<div id="ppsPopupPreviewUpdatingMsg">
	<?php _e('Loading preview...', PPS_LANG_CODE)?>
</div>
<div id="ppsPopupGoToTop">
	<a id="ppsPopupGoToTopBtn" href="#">
		<img src="<?php echo esc_html(uriPps::_(PPS_IMG_PATH))?>pointer-up.png" /><br />
		<?php _e('Back to top', PPS_LANG_CODE)?>
	</a>
</div>
<?php dispatcherPps::doAction('afterPopupEdit', $this->popup);?>
