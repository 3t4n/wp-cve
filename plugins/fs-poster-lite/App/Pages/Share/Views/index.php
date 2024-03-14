<?php

namespace FSPoster\App\Pages\Share\Views;

use FSPoster\App\Providers\Pages;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-row">
	<div class="fsp-col-12 fsp-title">
		<div class="fsp-title-text">
			<?php echo esc_html__( 'Direct Share', 'fs-poster' ); ?>
		</div>
		<div class="fsp-title-button"></div>
	</div>
	<div class="fsp-col-12 fsp-col-lg-6 fsp-share-leftcol">
		<div class="fsp-card">
			<div class="fsp-card-body">
				<div class="fsp-form-group">
					<div id="wpMediaBtn" class="fsp-form-image <?php echo esc_html( $fsp_params[ 'imageId' ] ) > 0 ? 'fsp-hide' : ''; ?>">
						<i class="fas fa-camera"></i>
					</div>
					<div id="imageShow" class="fsp-form-image-preview <?php echo esc_html( $fsp_params[ 'imageId' ] ) > 0 ? '' : 'fsp-hide'; ?>">
						<img src="<?php echo esc_html( $fsp_params[ 'imageURL' ] ); ?>">
						<i class="fas fa-times" id="closeImg"></i>
					</div>
				</div>
				<div id="fspShareURL" class="fsp-form-group <?php echo esc_html( $fsp_params[ 'imageId' ] ) > 0 ? 'fsp-hide' : ''; ?>">
					<label><?php echo esc_html__( 'Link', 'fs-poster' ); ?></label>
					<input autocomplete="off" type="text" class="fsp-form-input link_url" placeholder="<?php echo esc_html__( 'Example: https://example.com', 'fs-poster' ); ?> " value="<?php echo esc_html( $fsp_params[ 'link' ] ); ?>">
				</div>
				<div class="fsp-form-group">
					<label class="fsp-is-jb">
						<?php echo esc_html__( 'Custom post message', 'fs-poster' ); ?>
						<span><?php echo esc_html__( 'Characters count:', 'fs-poster' ); ?> <span id="fspShareCharCount">0</span></span>
					</label>
					<textarea class="fsp-form-input message_box" placeholder="<?php echo esc_html__( 'Enter the custom post message', 'fs-poster' ); ?>" maxlength="2000"></textarea>
				</div>
			</div>
			<div class="fsp-card-footer">
				<button type="button" class="fsp-button shareNowBtn"><?php echo esc_html__( 'SHARE NOW', 'fs-poster' ); ?></button>
				<button type="button" class="fsp-button fsp-is-info schedule_button"><?php echo esc_html__( 'SCHEDULE', 'fs-poster' ); ?></button>
				<button type="button" class="fsp-button fsp-is-gray saveBtn"><?php echo esc_html__( 'SAVE THE POST', 'fs-poster' ); ?></button>
			</div>
		</div>
	</div>
	<div class="fsp-col-12 fsp-col-lg-6 fsp-share-rightcol">
		<?php Pages::controller( 'Base', 'MetaBox', 'post_meta_box', [
			'post_id' => 0
		] ); ?>
	</div>
	<div class="fsp-col-12 fsp-title">
		<div class="fsp-title-text">
			<?php echo esc_html__( 'Saved posts', 'fs-poster' ); ?>
			<span class="fsp-title-count"></span>
		</div>
		<div class="fsp-title-button">
			<button id="fspClearSavedPosts" class="fsp-button">
				<i class="far fa-trash-alt"></i> <span><?php echo esc_html__( 'CLEAR ALL', 'fs-poster' ); ?></span>
			</button>
		</div>
	</div>
	<div class="fsp-col-12">
	</div>
</div>
<script>
	FSPObject.saveID = <?php echo (int) $fsp_params[ 'post_id' ]; ?>;
</script>
