<div class="panel panel-default">
	<div class="panel-heading">
		<?php _e('Info', YRM_LANG); ?>
		<span class="yrm-tab-triangle glyphicon glyphicon-triangle-top"></span>
	</div>
	<div class="panel-body">
		<p>
			<?php _e('How to use Read more? See the video example');?>
			<a class="yrm-admin-link" href="<?php echo YRM_READ_MORE_VIDEO; ?>" target="_blank"><span class="yrm-play-promotion-video" data-href="<?php echo YRM_READ_MORE_VIDEO; ?>"></span></a></p>

		<div class="yrm-shortcode-content-wrapper">
			<?php
			if(ReadMore::RemoveOption('less-button-title')) {
				$shortCode = '[expander_maker id="'.esc_attr($id).'" more="Read more"][/expander_maker]';
			}
			else {
				$shortCode = '[expander_maker id="'.esc_attr($id).'" more="Read more" less="Read less"]Read more hidden text[/expander_maker]';
			}
			
			?>
			<?php if($id != 0): ?>
				<div class="row form-group">
					<div class="col-md-12">
						<label><?php _e('Shortcode')?></label>
					</div>
					<div class="col-md-12">
						<div class="yrm-tooltip" style="display: block !important;">
							<span class="yrm-tooltiptext" id="yrm-tooltip"><?php _e('Copy to clipboard', YRM_LANG)?></span>
							<input type="text" id="expm-shortcode-info-div" class="widefat" readonly="readonly" value='<?php echo esc_attr($shortCode); ?>'>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?php if($id == 0): ?>
				<div class="row form-group">
					<div class="col-md-12">
						<div class="no-shortcode">
							<span><?php _e('Please do save read more for getting shortcode.', YRM_LANG); ?></span>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label><?php _e('More Button CSS Class Name')?></label>
			</div>
			<div class="col-md-6">
				yrm-more-button-wrapper
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label><?php _e('Less Button CSS Class Name')?></label>
			</div>
			<div class="col-md-6">
				yrm-less-button-wrapper
			</div>
		</div>
        <div class="row form-group">
			<div class="col-md-6">
				<label><?php _e('Open JS Trigger Event')?></label>
			</div>
			<div class="col-md-6">
                YrmOpen
			</div>
		</div>
        <div class="row form-group">
			<div class="col-md-6">
				<label><?php _e('Close JS Trigger Event')?></label>
			</div>
			<div class="col-md-6">
                YrmClose
			</div>
		</div>
        <?php if(YRM_PKG == YRM_FREE_PKG): ?>
        <div class="row form-group">
			<div class="col-md-6">
				<label><?php _e('DEMO')?></label>
			</div>
			<div class="col-md-6">

			</div>
		</div>
        <div class="row form-group">
			<div class="col-md-4">
                <span><?php _e('Website')?></span><br><br>
                <label><a href="<?php echo YRM_DEMO_URL; ?>" target="_blank">Visit</a></label>
			</div>
            <div class="col-md-4">
                <span><?php _e('Login')?></span><br><br>
				<label><span>demo</span></label>
			</div>
            <div class="col-md-4">
                <span><?php _e('Password')?></span><br><br>
                <label><span>demo</span></label>
			</div>
		</div>
        <?php endif; ?>
	</div>
</div>