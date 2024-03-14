<div class="ycf-bootstrap-wrapper yrm-support-wrapper">
	<div class="row">
		<div class="col-lg-8">
			<div class="panel panel-default">
				<div class="panel-heading"><?php _e('Support', YRM_LANG)?></div>
				<div class="panel-body">
					<form id="yrm-form">
					<div class="row form-group">
						<div class="col-md-3">
							<label style="margin-top: 8px;"> <?php _e('Choose Support Type', YRM_LANG)?> </label>
						</div>
						<div class="col-md-3">
							<span>
								<input type="radio" checked="" name="report_type" value="Technical Support" id="ycd_tab_pr"><label class="yrm-inline-label-radio radio" for="ycd_tab_pr"><?php _e('Technical Support', YRM_LANG)?></label>
							</span>
						</div>
						<div class="col-md-3">
							<span>
								<input type="radio" name="report_type" value="Suggestion" id="ycd_tab_sug"> <label class="yrm-inline-label-radio radio" for="ycd_tab_sug"><?php _e('Suggestion', YRM_LANG)?></label>
							</span>
						</div>
						<div class="col-md-3">
							<span>
								<input type="radio" name="report_type" value="Feature Request" id="ycd_tab_q"> <label class="yrm-inline-label-radio radio" for="ycd_tab_q"><?php _e('Feature Request', YRM_LANG)?></label>
							</span>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-3">
							<label for="yrm-name"><?php _e('Name', YRM_LANG)?>*</label>
						</div>
						<div class="col-md-5">
							<input type="text" id="yrm-name" class="form-control input-sm yrm-required-fields" data-error="yrm-error-name" name="name" value="">
						</div>
					</div>
                    <div class="row form-group yrm-hide yrm-error-name">
                        <div class="col-md-12">
                            <label class="yrm-error"><?= __('This filed is required', YRM_LANG)?></label>
                        </div>
                    </div>
					<div class="row form-group">
						<div class="col-md-3">
							<label for="yrm-email"><?php _e('Email Address', YRM_LANG)?>*</label>
						</div>
						<div class="col-md-5">
							<input type="text" id="yrm-email" class="form-control input-sm yrm-required-fields" data-error="yrm-error-email" name="email" value="<?= get_option('admin_email'); ?>">
						</div>
					</div>
                    <div class="row form-group yrm-hide yrm-error-email">
                        <div class="col-md-12">
                            <label class="yrm-error"><?php echo  __('This filed is required', YRM_LANG)?></label>
                        </div>
                    </div>
                    <div class="row form-group yrm-hide yrm-validate-email-error">
                        <div class="col-md-12">
                            <label class="yrm-error"><?php echo  __('Please enter a valid email address', YRM_LANG)?></label>
                        </div>
                    </div>
					<div class="row form-group">
						<div class="col-md-3">
							<label for="yrm-website"><?php _e('Website', YRM_LANG)?>*</label>
						</div>
						<div class="col-md-5">
							<input type="text" id="yrm-website" class="form-control input-sm yrm-required-fields" data-error="yrm-error-website" name="website" value="<?= get_option('siteurl'); ?>">
						</div>
					</div>
					<div class="row form-group yrm-hide yrm-error-website">
                        <div class="col-md-12">
                            <label class="yrm-error"><?php echo  __('This filed is required', YRM_LANG)?></label>
                        </div>
					</div>
					<div class="row form-group">
						<div class="col-md-3">
							<label for="yrm-message"><?php _e('Message', YRM_LANG)?></label>
						</div>
						<div class="col-md-5">
							<textarea name="yrm-message" for="yrm-message" class="form-control">

							</textarea>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input class="button-primary gfbutton" type="submit" id="yrm-support-request-button" name="" value="<?php _e('Request Support', YRM_LANG)?>">
                            <img src="<?php echo YRM_IMG_URL.'ajax.gif'; ?>" alt="gif" class="yrm-support-spinner js-yrm-spinner yrm-hide" width="20px">
						</div>
					</div>
					</form>
                    <div class="row yrm-support-success yrm-hide">
                        <div class="col-md-12">
	                        <?php _e('Thank you for contacting us!', YRM_LANG)?>
                        </div>
                    </div>
                    <style>
						.ycf-bootstrap-wrapper .row {
							margin-left: 0 !important;
							margin-right: 0 !important;
						}
						.yrm-support-wrapper {
							margin-top: 10px;
						}						
					</style>
				</div>
			</div>
		</div>
		<div class="col-lg-6"></div>
	</div>
</div>