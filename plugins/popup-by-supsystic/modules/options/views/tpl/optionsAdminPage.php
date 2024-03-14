<div class="wrap">
    <div class="supsystic-plugin">
        <?php /*?><header class="supsystic-plugin">
            <h1><?php echo PPS_WP_PLUGIN_NAME?></h1>
        </header><?php */?>
		<?php echo viewPps::ksesString($this->breadcrumbs);?>
        <section class="supsystic-content">
            <nav class="supsystic-navigation supsystic-sticky <?php dispatcherPps::doAction('adminMainNavClassAdd')?>">
                <ul>
					<?php foreach($this->tabs as $tabKey => $tab) { ?>
						<?php if(isset($tab['hidden']) && $tab['hidden']) continue;?>
						<li class="supsystic-tab-<?php echo esc_html($tabKey);?> <?php echo (($this->activeTab == $tabKey || in_array($tabKey, $this->activeParentTabs)) ? 'active' : '')?>">
							<a href="<?php echo esc_html($tab['url'])?>" title="<?php echo esc_html($tab['label'])?>">
								<?php if(isset($tab['fa_icon'])) { ?>
									<i class="fa <?php echo esc_html($tab['fa_icon'])?>"></i>
								<?php } elseif(isset($tab['wp_icon'])) { ?>
									<i class="dashicons-before <?php echo esc_html($tab['wp_icon'])?>"></i>
								<?php } elseif(isset($tab['icon'])) { ?>
									<i class="<?php echo esc_html($tab['icon'])?>"></i>
								<?php }?>
								<span class="sup-tab-label"><?php echo viewPps::ksesString($tab['label'])?></span>
							</a>
						</li>
					<?php }?>
                </ul>
            </nav>
            <div class="supsystic-container supsystic-<?php echo esc_html($this->activeTab)?>">
				<?php //dispatcherPps::doAction('discountMsg');?>
				<?php echo viewPps::ksesString($this->content)?>
                <div class="clear"></div>
            </div>
            <div class="supsystic-footer-wrapper">
            	<div class="supsystic-footer-add-review">Add your <a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/popup-by-supsystic?filter=5#postform">★★★★★</a> on wordpress.org</div>
            	<a href="https://supsystic.com/" target="_blank"><img src="<?php echo PPS_PLUGINS_URL .'/'. PPS_PLUG_NAME;?>/modules/supsystic_promo/img/supsystic-logo-small.png"></a>
            	<div class="supsystic-footer-plugin-version">PopUp by Supsystic Version: <?php echo PPS_VERSION?></div>
            </div>
        </section>
    </div>
</div>
<!--Option available in PRO version Wnd-->
<div id="ppsOptInProWnd" style="display: none;" title="<?php _e('Improve Free version', PPS_LANG_CODE)?>">
	<p id="ppsOptWndTemplateTxt" style="display: none;">
		<?php printf(__('Please be advised that this template with all other options and PRO templates is available only in <a target="_blank" href="%s">PRO version</a>. You can <a target="_blank" href="%s" class="button">Get PRO</a> today and get this and other PRO templates and features for your PopUps!', PPS_LANG_CODE), $this->mainLink, $this->mainLink)?>
	</p>
	<p id="ppsOptWndOptionTxt">
		<?php printf(__('Please be advised that this option is available only in <a target="_blank" href="%s">PRO version</a>. You can <a target="_blank" href="%s" class="button">Get PRO</a> today and get this and other PRO option for your PopUps!', PPS_LANG_CODE), $this->mainLink, $this->mainLink)?>
	</p>
</div>
