<?php

namespace FSPoster\App\Pages\Settings\Views;

use FSPoster\App\Providers\Pages;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-row">
	<div class="fsp-col-12 fsp-title">
		<div class="fsp-title-text">
			<?php echo esc_html__( 'Settings', 'fs-poster' ); ?>
		</div>
		<div class="fsp-title-button">
			<button id="fspSaveSettings" class="fsp-button">
				<i class="fas fa-check"></i> <span><?php echo esc_html__( 'SAVE CHANGES', 'fs-poster' ); ?></span>
			</button>
		</div>
	</div>
	<div class="fsp-col-12 fsp-row">
		<div class="fsp-layout-left fsp-col-12 fsp-col-md-4 fsp-col-lg-3">
			<div class="fsp-card">
				<a href="?page=fs-poster-settings&setting=general" class="fsp-tab fsp-is-active">
					<div class="fsp-tab-title">
						<i class="fas fa-sliders-h fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text"><?php echo esc_html__( 'General settings', 'fs-poster' ); ?></span>
					</div>
					<div class="fsp-tab-badges"></div>
				</a>
                <a href="#" class="fsp-tab fsp-require-premium">
                    <div class="fsp-tab-title">
                        <i class="fas fa-file-export fsp-tab-title-icon"></i>
                        <span class="fsp-tab-title-text fsp-tab-title-crowned"><?php echo esc_html__( 'Premium settings', 'fs-poster' ); ?></span>
                    </div>
                    <div class="fsp-tab-badges"></div>
                </a>
			</div>
		</div>
		<div id="fspComponent" class="fsp-layout-right fsp-col-12 fsp-col-md-8 fsp-col-lg-9">
			<form id="fspSettingsForm" class="fsp-card fsp-settings">
				<?php Pages::controller( 'Settings', 'Main', 'component_' . $fsp_params[ 'active_tab' ] ); ?>
			</form>
		</div>
	</div>
</div>