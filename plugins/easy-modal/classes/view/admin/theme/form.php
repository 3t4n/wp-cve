<?php class EModal_View_Admin_Theme_Form extends EModal_View {
	public function output()
	{
		extract($this->values)
		?><div class="wrap">
			<h2><?php 
				esc_html_e(__($title, 'easy-modal' ) );
				if(!empty($theme_new_url))
					echo ' <a href="' . esc_url( admin_url( $theme_new_url ) ) . '" class="add-new-h2">' . __('Add New', 'easy-modal' ) . '</a>';
			?></h2>
			<h2 id="emodal-tabs" class="nav-tab-wrapper">
			<?php foreach($tabs as $tab){ ?>
				<a href="#<?php echo $tab['id']?>" id="<?php echo $tab['id']?>-tab" class="nav-tab emodal-tab"><?php echo $tab['label'];?></a>
			<?php } ?>
			</h2>
			<form id="emodal-theme-editor" method="post" action="">
				<?php do_action('emodal_form_nonce');?>
				<?php wp_nonce_field(EMCORE_NONCE, EMCORE_NONCE);?>
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<div id="post-body-content">
							<div class="empreview">
								<div id="EModal-Preview">
									<div class="example-modal-overlay"></div>
									<h2>
										<?php _e('Theme Preview', 'easy-modal' )?>
									</h2>
									<div class="example-modal">
										<div class="title"><?php _e('Title Text', 'easy-modal' );?></div>
										<div class="content"><?php do_action('emodal_example_modal_content');?></div>
										<a class="close-modal"><?php _e('&#215;', 'easy-modal' );?></a>
									</div>
								</div>
							</div>
							<div class="tabwrapper">
							<?php foreach($tabs as $tab){ ?>
								<div id="<?php echo $tab['id']?>" class="emodal-tab-content">
									<?php do_action('emodal_admin_theme_form_tab_'.$tab['id'])?>
								</div>
							<?php } ?>
							</div>
						</div>
						<div id="postbox-container-1" class="postbox-container">
							<div class="meta-box-sortables ui-sortable" id="side-sortables">
								<div class="postbox " id="submitdiv">
									<div title="Click to toggle" class="handlediv"><br></div>
									<h3 class="hndle"><span><?php _e('Publish', 'easy-modal' );?></span></h3>
									<div class="inside">
										<div id="submitpost" class="submitbox">
											<div id="minor-publishing">
												<div id="minor-publishing-actions">
													<?php do_action('emodal_theme_form_minor_actions');?>
													<!--
													<div id="preview-action">
														<a id="post-preview" href="#" class="preview button"><?php _e('Preview', 'easy-modal' );?></a>
														<input type="hidden" value="" id="wp-preview" name="wp-preview">
													</div>
													-->
													<div class="clear"></div>
												</div><!-- #minor-publishing-actions -->
											</div>
											<br/>
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
	public function output2()
	{
		extract($this->values);
		$output = '<div class="wrap">'
			.'<h2>'. esc_html( __($title, 'easy-modal' ) ) .'</h2>'
			.'<h2 id="emodal-tabs" class="nav-tab-wrapper">';
			foreach($tabs as $tab)
			{
				$output .= '<a href="#'. $tab['id'] .'" id="'. $tab['id'] .'-tab" class="nav-tab emodal-tab">'. $tab['label'] .'</a>';
			}
			$output .= '</h2>'
			.'<form id="emodal-theme-editor" method="post" action="">'
				.wp_nonce_field(EMCORE_NONCE, EMCORE_NONCE, true, false)
				.'<div id="poststuff">'
					.'<div id="post-body" class="metabox-holder columns-2">'
						.'<div id="post-body-content">'
							.'<div class="empreview">'
								.'<div id="EModal-Preview">'
									.'<div class="example-modal-overlay"></div>'
									.'<h2>'
										.__('Theme Preview', 'easy-modal' )
									.'</h2>'
									.'<div class="example-modal">'
										.'<div class="title">'. __('Title Text', 'easy-modal' ) .'</div>'
										.'<div class="content">'. apply_filters('emodal_example_modal_content', '') .'</div>'
										.'<a class="close-modal">'. __('&#215;', 'easy-modal' ) .'</a>'
									.'</div>'
								.'</div>'
							.'</div>'
							.'<div class="tabwrapper">';
							foreach($tabs as $tab)
							{
								$output .= '<div id="'. $tab['id'] .'" class="emodal-tab-content">'
									.apply_filters('emodal_admin_theme_form_tab_'.$tab['id'], '')
								.'</div>';
							}
							$output .= '</div>'
						.'</div>'
						.'<div id="postbox-container-1" class="postbox-container">'
							.'<div class="meta-box-sortables ui-sortable" id="side-sortables">'
								.'<div class="postbox " id="submitdiv">'
									.'<div title="Click to toggle" class="handlediv"><br></div>'
									.'<h3 class="hndle"><span>'. __('Publish', 'easy-modal' ). '</span></h3>'
									.'<div class="inside">'
										.'<div id="submitpost" class="submitbox">'
											.'<div id="minor-publishing">'
												.'<div id="minor-publishing-actions">'
													.'<div id="preview-action">'
														.'<a id="post-preview" href="#" class="preview button">'. __('Preview', 'easy-modal' ) .'</a>'
														.'<input type="hidden" value="" id="wp-preview" name="wp-preview">'
													.'</div>'
													.'<div class="clear"></div>'
												.'</div><!-- #minor-publishing-actions -->'
											.'</div>'
											.'<br/>'
											.'<div id="major-publishing-actions" class="submitbox">'
												.'<div id="publishing-action">'
													.'<span class="spinner"></span>'
													.'<input type="submit" accesskey="p" value="'. __('Save', 'easy-modal' ) .'" class="button button-primary button-large" id="publish" name="publish">'
												.'</div>'
												.'<div class="clear"></div>'
											.'</div>'
										.'</div>'
										.'<div class="clear"></div>'
									.'</div>'
								.'</div>'
								. apply_filters('emodal_admin_sidebar', '')
							.'</div>'
						.'</div>'
					.'</div>'
					.'<br class="clear"/>'
				.'</div>'
			.'</form>'
		.'</div>';
		return $output;
	}
}