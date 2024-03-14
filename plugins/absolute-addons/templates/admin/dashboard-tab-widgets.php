<?php
/**
 * Dashboard Main Layout
 *
 * @package ABSP
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
?>
	<div class="absp-settings-panel widgets">
		<div class="absp-settings-panel__header">
			<div class="widget-left">
				<h3><?php esc_html_e('Absolute Widgets', 'absolute-addons'); ?></h3>
			</div>
			<div class="widget-right">
				<span><?php esc_html_e('Global Control:', 'absolute-addons'); ?></span>
				<div class="btn-group">
					<button type="button" class="btn btn-green toggle-widget all-on"><?php esc_html_e('Enable All', 'absolute-addons'); ?></button>
					<button type="button" class="btn btn-red toggle-widget all-off"><?php esc_html_e('Disable All', 'absolute-addons'); ?></button>
				</div>
				<span class="info-text"><?php esc_html_e('Active or deactivate all widgets at once.', 'absolute-addons'); ?></span>
			</div>
		</div>
		<div class="absp-settings-panel__body">
			<ul class="widget-filter">
				<li><a href="#" class="active" data-filter="all"><?php esc_html_e('All', 'absolute-addons'); ?></a></li>
				<li><a href="#" data-filter="free"><?php esc_html_e('Free', 'absolute-addons'); ?></a></li>
				<li><a href="#" data-filter="pro"><?php esc_html_e('Pro', 'absolute-addons'); ?></a></li>
				<li><a href="#" data-filter="upcoming"><?php esc_html_e('Upcoming', 'absolute-addons'); ?></a></li>
			</ul>
			<form action="#" class="absp-widgets-form" id="absp-widgets-settings" method="post">
				<div class="widget-container">
					<h4><?php esc_html_e('Content Widgets', 'absolute-addons'); ?></h4>
					<div class="row">
						<?php

						$widgets_option = \AbsoluteAddons\Plugin::instance()->get_widgets_settings();

						foreach ( \AbsoluteAddons\Plugin::get_widgets() as $key => $val ) {

							// Default config.
							$is_active = $val['is_active'];
							$disabled  = false;

							// Set is active from settings if available.
							if ( isset( $widgets_option[ $key ] ) ) {
								$is_active = 'on' === $widgets_option[ $key ];
							}

							// If pro is not active, then disable the pro widgets.
							if ( $val['is_pro'] && ! absp_has_pro() ) {
								$is_active = false;
								$disabled  = true;
							}

							// Disable widgets if it's upcoming.
							if ( $val['is_upcoming'] ) {
								$is_active = false;
								$disabled  = true;
							}
						?>
							<div class="widget-item <?php echo $val['is_pro'] ? 'is-pro' : 'is-free'; ?> <?php echo $val['is_upcoming'] ? 'is-upcoming' : ''; ?>">
								<div class="widget-content">
									<div class="type-tag">
										<?php if ( $val['is_pro'] ) { ?>
											<span class="absp-pro"><?php esc_html_e( 'Pro', 'absolute-addons' ); ?></span>
										<?php }?>
									</div>
									<div class="widget-item-title">
										<label for="widget-<?php echo esc_attr( $key ); ?>">
											<h4><?php absp_render_title( $val['label'] ); ?></h4>
										</label>
									</div>
									<div class="widget-switch-wrap">
										<div class="widget-switch">
											<?php if ( ! $val['is_upcoming'] ) { ?>
											<input type="hidden" name="widgets[<?php echo esc_attr( $key ); ?>]" value="off">
											<?php } ?>
											<input type="checkbox" name="widgets[<?php echo esc_attr( $key ); ?>]" class="widget-switch-control" id="widget-<?php echo esc_attr( $key ); ?>" tabindex="0" <?php checked( $is_active ); disabled( $disabled ); ?>>
											<label class="widget-switch-label" for="widget-<?php echo esc_attr( $key ); ?>"></label>
										</div>
									</div>
								</div>
								<div class="widget-footer">
									<?php if ( ! empty($val['demo_url'] || $val['youtube_url'] || $val['doc_url'] ) ) { ?>
										<div class="widget-action-buttons">
											<?php if ( $val['demo_url'] ) { ?>
												<a href="<?php echo esc_url($val['demo_url']) ?>" class="button-go-see demo" target="_blank">
													<svg width="20" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="desktop" class="svg-inline--fa fa-desktop fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M528 0H48C21.5 0 0 21.5 0 48v320c0 26.5 21.5 48 48 48h192l-16 48h-72c-13.3 0-24 10.7-24 24s10.7 24 24 24h272c13.3 0 24-10.7 24-24s-10.7-24-24-24h-72l-16-48h192c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zm-16 352H64V64h448v288z"></path></svg>
												</a>
											<?php }?>
											<?php if ( $val['youtube_url'] ) { ?>
												<a href="<?php echo esc_url($val['youtube_url']) ?>" class="button-go-see video" target="_blank">
													<svg width="20" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="video" class="svg-inline--fa fa-video fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M336.2 64H47.8C21.4 64 0 85.4 0 111.8v288.4C0 426.6 21.4 448 47.8 448h288.4c26.4 0 47.8-21.4 47.8-47.8V111.8c0-26.4-21.4-47.8-47.8-47.8zm189.4 37.7L416 177.3v157.4l109.6 75.5c21.2 14.6 50.4-.3 50.4-25.8V127.5c0-25.4-29.1-40.4-50.4-25.8z"></path></svg>
												</a>
											<?php }?>
											<?php if ( $val['doc_url'] ) { ?>
												<a href="<?php echo esc_url($val['doc_url']) ?>" class="button-go-see doc" target="_blank">
													<svg width="20" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="question-circle" class="svg-inline--fa fa-question-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zM262.655 90c-54.497 0-89.255 22.957-116.549 63.758-3.536 5.286-2.353 12.415 2.715 16.258l34.699 26.31c5.205 3.947 12.621 3.008 16.665-2.122 17.864-22.658 30.113-35.797 57.303-35.797 20.429 0 45.698 13.148 45.698 32.958 0 14.976-12.363 22.667-32.534 33.976C247.128 238.528 216 254.941 216 296v4c0 6.627 5.373 12 12 12h56c6.627 0 12-5.373 12-12v-1.333c0-28.462 83.186-29.647 83.186-106.667 0-58.002-60.165-102-116.531-102zM256 338c-25.365 0-46 20.635-46 46 0 25.364 20.635 46 46 46s46-20.636 46-46c0-25.365-20.635-46-46-46z"></path></svg>
												</a>
											<?php }?>
										</div>
									<?php } ?>
									<div class="type-badge">
										<?php if ( $val['is_new'] ) { ?>
											<span class="absp-new"><?php esc_html_e( 'New', 'absolute-addons' ); ?></span>
										<?php }?>
										<?php if ( $val['is_upcoming'] ) { ?>
											<span class="absp-upcoming"><?php esc_html_e( 'Upcoming', 'absolute-addons' ); ?></span>
										<?php	}?>
									</div>
								</div>
							</div>
					<?php
						}
					?>
					</div>
				</div>
				<div class="widget-button">
					<button type="submit" class="btn-gr absp-admin--save"><?php esc_html_e('SAVE SETTINGS', 'absolute-addons'); ?></button>
				</div>
			</form>
		</div>
		<div class="absp-settings-panel__footer"></div>
	</div>
<?php
// End of file dashboard-tab-widgets.php.
