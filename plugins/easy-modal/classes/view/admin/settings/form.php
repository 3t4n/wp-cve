<?php class EModal_View_Admin_Settings_Form extends EModal_View {
	public function output()
	{
		extract($this->values)
		?><div class="wrap">
			<h2><?php esc_html_e($title );?></h2>
			<h2 id="emodal-tabs" class="nav-tab-wrapper">
			<?php foreach($tabs as $tab){ ?>
				<a href="#<?php echo $tab['id']?>" id="<?php echo $tab['id']?>-tab" class="nav-tab emodal-tab"><?php echo $tab['label'];?></a>
			<?php } ?>
			</h2>
			<form id="emodal-settings-editor" method="post" action="">
				<?php do_action('emodal_form_nonce');?>
				<?php wp_nonce_field( EMCORE_NONCE, EMCORE_NONCE);?>
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<div id="post-body-content">
							<div class="tabwrapper">
							<?php foreach($tabs as $tab){ ?>
								<div id="<?php echo $tab['id']?>" class="emodal-tab-content">
									<?php do_action('emodal_admin_settings_form_tab_'.$tab['id'])?>
								</div>
							<?php } ?>
							</div>
						</div>
						<div id="postbox-container-1" class="postbox-container">
							<div class="meta-box-sortables ui-sortable" id="side-sortables">
								<div class="postbox " id="submitdiv">
									<div title="Click to toggle" class="handlediv"><br></div>
									<h3 class="hndle"><span><?php _e('Save', 'easy-modal' );?></span></h3>
									<div class="inside">
										<div id="submitpost" class="submitbox">
											<div id="major-publishing-actions" class="submitbox">
												<div id="publishing-action">
													<span class="spinner"></span>
													<input type="submit" accesskey="p" value="<?php _e('Save', 'easy-modal' );?>" class="button button-primary button-large" id="publish" name="publish">
												</div>
												<div class="clear"></div>
											</div>
										</div>
										<div class="clear"></div>
									</div>
								</div>
								<?php do_action('emodal_admin_sidebar');?>
							</div>
						</div>
					</div>
					<br class="clear"/>
				</div>
			</form>
		</div><?php
	}
}