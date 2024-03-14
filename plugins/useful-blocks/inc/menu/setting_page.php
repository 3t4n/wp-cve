<?php
if ( ! defined( 'ABSPATH' ) ) exit;

//メッセージ用
$green_message = '';
if( isset( $_REQUEST['settings-updated'] ) && $_REQUEST['settings-updated'] ) {
	$green_message = __('Your settings have been saved.', 'useful-blocks' );
}

$menu_tabs = \Ponhiro_Blocks::$menu_tabs;

$plan = USFL_BLKS_IS_PRO ? 'pro' : 'free';
?>
<div class="usfl_blks_page -is-<?=$plan?>" data-lang=<?=get_locale()?>>
	<div class="usfl_blks_page__head">
		<div class="usfl_blks_page__inner">
			<h1 class="usfl_blks_page__title">
				<a href="https://ponhiro.com/useful-blocks/" target="_blank">
					<img src="<?=USFL_BLKS_URL . 'assets/img/ub_logo.png'?>" alt="Useful Blocks">
				</a>
				<?php if ( ! USFL_BLKS_IS_PRO ) : ?>
					<a href="https://ponhiro.com/useful-blocks/" target="_blank" class="usfl_blks_page__gopro">
						<i class="pb-icon-chevron-circle-right"></i><span>Go Pro</span>
					</a>
				<?php endif; ?>
			</h1>
			<div class="usfl_blks_page__tabs">
				<div class="nav-tab-wrapper">
					<?php 
						foreach ( $menu_tabs as $key => $val ) :
							$nav_class = ( $val === reset( $menu_tabs ) ) ? 'nav-tab act_' : 'nav-tab';
							echo '<a href="#' . $key . '" class="' . $nav_class . '">',
								USFL_BLKS_IS_PRO ? $val : $val . ' <span>(DEMO)</span>',
							'</a>';
						endforeach;
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="usfl_blks_page__body">
		<div class="usfl_blks_page__inner">
			<?php
				if ( $green_message ) :
					echo '<div class="notice updated is-dismissible"><p>'. $green_message .'</p></div>';
				endif;
			?>
			<?php if ( ! USFL_BLKS_IS_PRO ) : ?>
				<div class="usfl_blks_page__free_message">
					<i class="pb-icon-lightbulb"></i>
					<?php 
						echo sprintf(
							__( 'Only the %s can actually save the settings.', 'useful-blocks' ), 
							'<a href="https://ponhiro.com/useful-blocks/" target="_blank">'. __( 'Pro version', 'useful-blocks' ).'</a>'
						);
						echo '<br>';
						echo __( 'In the free version, you can check the usability of the setting page.', 'useful-blocks' );
					?>
				</div>
			<?php endif; ?>
			<?php
				ob_start();
				foreach ( $menu_tabs as $key => $val ) :

					$tab_class = ( $val === reset( $menu_tabs ) ) ? "tab-contents act_" : "tab-contents";
					echo '<div id="' . $key . '" class="' . $tab_class . '">';

						//タブコンテンツの読み込み（専用のファイルが有れば優先）
						$file = __DIR__ . '/tab/'. $key . '.php';
						$file = apply_filters( 'usfl_blks_setting_content_path', $file, $key );
						if ( file_exists( $file ) ) {
							include_once $file;
						} else {
							// ファイルなければ単純に do_settings_sections
							do_settings_sections( \Ponhiro_Blocks::PAGE_NAMES[$key] );
							do_action( 'usfl_blks_after_settings_sections', $key );
						}

					echo '</div>';
				endforeach;
				settings_fields( 'usfl_blks_setting_group' ); //settings_fields: nonce や referer などを出力するだけ

				$setting_contents = ob_get_clean();
				echo apply_filters( 'usfl_blks_setting_contents', $setting_contents );

			?>
		</div>
	</div>
	<?php if ( ! USFL_BLKS_IS_PRO ) : ?>
		<div class="usfl_blks_page__adarea">
			<!-- <div class="__ad_item -ponhiro">
				<a href="###">
					<img src="<?=USFL_BLKS_URL?>assets/img/ponhiro_blog_banner.jpg" alt="SWELL">
				</a>
				<span>ぽんひろ.com</span>
			</div> -->
			<div class="__ad_item -swell">
				<a href="https://swell-theme.com/" target="_blank">
					<img src="<?=USFL_BLKS_URL?>assets/img/swell2_pr_banner.jpg" alt="SWELL">
				</a>
				<span>WordPressテーマ SWELL</span>
			</div>
		</div>
	<?php endif; ?>
</div>


