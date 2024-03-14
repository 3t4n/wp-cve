<?php class EModal_View_Admin_Help extends EModal_View {
	public function output()
	{
		extract($this->values);?>
		<div class="wrap">
			<h2><?php esc_html_e(__($title, 'easy-modal' ) );?></h2>
			<h2 id="emodal-tabs" class="nav-tab-wrapper">
			<?php foreach($tabs as $tab){ ?>
				<a href="#<?php echo $tab['id']?>" id="<?php echo $tab['id']?>-tab" class="nav-tab emodal-tab"><?php echo $tab['label'];?></a>
			<?php } ?>
			</h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="tabwrapper">
						<?php foreach($tabs as $tab){ ?>
							<div id="<?php echo $tab['id']?>" class="emodal-tab-content">
								<?php do_action('emodal_admin_help_tab_'.$tab['id'])?>
							</div>
						<?php } ?>
						</div>
					</div>
					<div id="postbox-container-1" class="postbox-container">
						<div class="meta-box-sortables ui-sortable" id="side-sortables">
							<?php do_action('emodal_admin_sidebar');?>
						</div>
					</div>
				</div>
				<br class="clear"/>
			</div>
		</div><?php
	}
}