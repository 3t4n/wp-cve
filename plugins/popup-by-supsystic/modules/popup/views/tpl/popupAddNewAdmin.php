<style type="text/css">
	.popup-list-item.sup-promo:after {
		background-image: url("<?php echo esc_html($this->getModule()->getAssetsUrl());?>img/assets/ribbon-2.png");
		background-repeat: no-repeat;
		background-position: 0;
		content: " ";
		position: absolute;
		display: block;

		top: 0;
		right: 0;
		width: 100px;
		height: 100px;
	}
</style>
<section>
	<div class="supsystic-item supsystic-panel">
		<h3 style="line-height: 30px;">
			<?php if($this->changeFor) {
				printf(__('Change Template to any other from the list below or <a class="button" href="%s">return to Pop-Up edit</a>', PPS_LANG_CODE), $this->editLink);
			} else {
				_e('Choose Pop-Up Template. You can change it later.', PPS_LANG_CODE);
			}?>
		</h3>
		<hr />
		<div id="containerWrapper" style="width: 95%; margin: 40px auto;">
			<?php if(!$this->changeFor) { ?>
				<div class="supsystic-bar supsystic-sticky sticky-padd-next sticky-save-width sticky-base-width-auto sticky-outer-height">
					<form id="ppsCreatePopupForm">
						<label>
							<h3 style="float: left; margin: 10px;"><?php _e('PopUp Name', PPS_LANG_CODE)?>:</h3>
							<?php echo viewPps::ksesString(htmlPps::text('label', array('attrs' => 'style="float: left; width: 60%;"', 'required' => true)))?>
						</label>
						<button class="button button-primary" style="margin-top: 1px;">
							<i class="fa fa-check"></i>
							<?php _e('Save', PPS_LANG_CODE)?>
						</button>
						<?php echo viewPps::ksesString(htmlPps::defaultNonceForAdminPanel())?>
						<?php echo viewPps::ksesString(htmlPps::hidden('original_id'))?>
						<?php echo viewPps::ksesString(htmlPps::hidden('mod', array('value' => 'popup')))?>
						<?php echo viewPps::ksesString(htmlPps::hidden('action', array('value' => 'createFromTpl')))?>
					</form>
					<div style="clear: both;"></div>
					<div style="padding-top: 10px;">
						<a href="#all" data-id="0" style="margin-bottom: 5px;" class="ppsTypeFilterBtn button active focus"><?php _e('All', PPS_LANG_CODE)?></a>
						<?php foreach($this->types as $tId => $t) { ?>
						<a href="#<?php echo esc_html($t['code']);?>" data-id="<?php echo esc_html($tId);?>" class="ppsTypeFilterBtn button"
						    <?php if(isset($t['fective'])) { ?>
								data-fective="<?php echo esc_html(implode(',', $t['fective']))?>" data-replace=""
							<?php }?>
						><?php echo viewPps::ksesString($t['label'])?></a>
						<?php }?>
					</div>
					<div style="clear: both;"></div>
					<div id="ppsCreatePopupMsg"></div>
				</div>
			<?php } else { ?>
				<div style="padding-top: 10px;">
					<a href="#all" data-id="0" style="margin-bottom: 5px;" class="ppsTypeFilterBtn button active focus"><?php _e('All', PPS_LANG_CODE)?></a>
					<?php foreach($this->types as $tId => $t) { ?>
					<a href="#<?php echo esc_html($t['code']);?>" data-id="<?php echo esc_html($tId);?>" class="ppsTypeFilterBtn button"
					   <?php if(isset($t['fective'])) { ?>
						   data-fective="<?php echo esc_html(implode(',', $t['fective']))?>" data-replace=""
					   <?php }?>
					><?php echo viewPps::ksesString($t['label'])?></a>
					<?php }?>
				</div>
			<?php }?>
			<div  class="popup-list">
				<?php foreach($this->list as $popup) { ?>
					<?php $isPromo = isset($popup['promo']) && !empty($popup['promo']);?>
					<?php $promoClass = $isPromo ? 'sup-promo' : '';?>
					<div class="popup-list-item preset <?php echo esc_html($promoClass);?>" data-id="<?php echo ($isPromo ? 0 : esc_html($popup['id']))?>" data-type-id="<?php echo esc_html($popup['type_id']);?>">
						<img src="<?php echo esc_html($popup['img_preview_url'])?>" class="ppsTplPrevImg" />
						<div class="preset-overlay">
							<h3>
								<span class="ppsTplLabel"><?php echo viewPps::ksesString($popup['label'])?></span><br />
								<?php echo viewPps::ksesString($this->types[ $popup['type_id'] ]['label'])?>&nbsp;<?php _e('type', PPS_LANG_CODE)?>
							</h3>
							<?php if($isPromo) { ?>
							<a href="<?php echo esc_html($popup['promo_link'])?>" target="_blank" class="button ppsPromoTplBtn"><?php _e('Get in PRO', PPS_LANG_CODE)?></a>
							<?php }?>
						</div>
					</div>
				<?php }?>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
</section>
<!--Change tpl wnd-->
<div id="ppsChangeTplWnd" title="<?php _e('Change Template', PPS_LANG_CODE)?>" style="display: none;">
	<form id="ppsChangeTplForm">
		<?php _e('Are you sure you want to change your current template - to ', PPS_LANG_CODE)?><span id="ppsChangeTplNewLabel"></span>?
		<?php echo viewPps::ksesString(htmlPps::hidden('id'))?>
		<?php echo viewPps::ksesString(htmlPps::hidden('new_tpl_id'))?>
		<?php echo viewPps::ksesString(htmlPps::hidden('mod', array('value' => 'popup')))?>
		<?php echo viewPps::ksesString(htmlPps::defaultNonceForAdminPanel())?>
		<?php echo viewPps::ksesString(htmlPps::hidden('action', array('value' => 'changeTpl')))?>
	</form>
	<div id="ppsChangeTplMsg"></div>
</div>
<!---->
