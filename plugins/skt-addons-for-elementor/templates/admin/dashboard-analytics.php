<?php

/**
 * Dashboard analytics tab template
 */

defined('ABSPATH') || die();

$widgets =  skt_addons_elementor_has_pro() ? self::get_widgets() : \Skt_Addons_Elementor\Elementor\Widgets_Manager::get_local_widgets_map();
$inactive_widgets = \Skt_Addons_Elementor\Elementor\Widgets_Manager::get_inactive_widgets();
$used_widget = self::get_raw_usage();
$unuse_widget = self::get_un_usage();

$total_widgets_count = count( $widgets );
$total_used_widget_count = count( $used_widget );
$total_unuse_widget_count = count( $unuse_widget );

$disable_btn = count( $unuse_widget ) == count( array_intersect( $unuse_widget, $inactive_widgets ) );
?>
<div class="skt-dashboard-panel skt-dashboard-panel-analytics">

	<?php if ( ! \Elementor\Tracker::is_allow_track() ) : ?>
		<div class="skt-dashboard-analytics-notice">
			<div class="skt-dashboard-panel__header flex-content used-widgets">
				<div class="skt-dashboard-panel__header-content">
					<h2><?php esc_html_e( "Analytics Data Not Available", "skt-addons-elementor" ); ?></h2>
					<p class="f16" style="margin: 0 0;"><?php printf( esc_html__( 'To see Analytics you need to follow these 2 steps:', 'skt-addons-elementor' ) ); ?></p>
				</div>
			</div>
			<p class="f16 step">
				<?php
					printf( '<strong>%s</strong> %s <a href="%s" target="_blank">%s</a> %s',
						__( 'Step - 1:', 'skt-addons-elementor' ),
						__( 'Go to dashboard>Elementor>Settings>Experiment tab and tick "Usage Data Sharing" (last option) then save the change.', 'skt-addons-elementor' ),
						admin_url( 'admin.php?page=elementor#tab-experiments' ),
						__( 'Click here', 'skt-addons-elementor' ),
						__( 'to go to the page.', 'skt-addons-elementor' )
					);
				?>
			</p>
			<p class="f16 step" style="margin: 0 0;">
				<?php
					printf( '<strong>%s</strong> %s <a href="%s" target="_blank">%s</a> %s',
						__( 'Step - 2:', 'skt-addons-elementor' ),
						__( 'Go to dashboard>Elementor>System Info>Elements Usage and press the "Recalculate" button.', 'skt-addons-elementor' ),
						admin_url( 'admin.php?page=elementor-system-info' ),
						__( 'Click here', 'skt-addons-elementor' ),
						__( 'to go to the page.', 'skt-addons-elementor' )
					);
				?>
			</p>
		</div>
	<?php else: ?>
		<!-- Used Widget Analytics -->
		<div class="skt-dashboard-panel__header flex-content used-widgets">
			<div class="skt-dashboard-panel__header-content">
				<h2><?php esc_html_e( 'Used Widgets', 'skt-addons-elementor' ); ?></h2>
				<?php if( $total_used_widget_count ): ?>
					<p class="f16" style="margin: 0 0;"><?php printf( esc_html__( 'You are using only %s %s widgets. %s', 'skt-addons-elementor' ), '<strong>', $total_used_widget_count,  '</strong>' ); ?></p>
				<?php else: ?>
					<p class="f16"><?php printf( esc_html__( 'No used widget found!', 'skt-addons-elementor' ) ); ?></p>
				<?php endif; ?>
			</div>

			<div class="skt-dashboard-panel__header-summary">
				<div class="data"><?php printf( esc_html__('Total Widget: %s', 'skt-addons-elementor' ), $total_widgets_count);?></div>
				<div class="data"><?php printf( esc_html__('Used: %s', 'skt-addons-elementor' ), $total_used_widget_count);?></div>
				<div class="data"><?php printf( esc_html__('Unused: %s', 'skt-addons-elementor' ), $total_unuse_widget_count);?></div>
			</div>
		</div>

		<div class="skt-dashboard-analytics" style="margin-bottom: 80px;">
			<?php
			foreach ($used_widget as $key => $data) :
				?>
				<div class="skt-dashboard-analytics__item">
					<fieldset>
						<div class="widget_inner">
							<div class="widget-title"><?php echo esc_html($widgets[$key]['title']); ?></div>
							<span class="skt-dashboard-analytics__item-total-count"><?php esc_html_e('total use: ', 'skt-addons-elementor'); ?><?php echo esc_html($data);?></span>
						</div>
					</fieldset>
				</div>
			<?php
			endforeach;
			?>
		</div>

		<!-- Unused Widget Analytics -->
		<div class="skt-dashboard-panel__header flex-content unused-widgets">
			<div class="skt-dashboard-panel__header-content">
				<h2><?php esc_html_e( 'Unused Widgets', 'skt-addons-elementor' ); ?></h2>
				<?php if( $total_unuse_widget_count ): ?>
					<p class="f16"><?php printf( esc_html__( '%s %s widgets %s are unused right now.', 'skt-addons-elementor' ), '<strong>', $total_unuse_widget_count,  '</strong>' ); ?></p>
				<?php else: ?>
					<p class="f16"><?php printf( esc_html__( 'No unused widget found.', 'skt-addons-elementor' ) ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<?php if( !empty($unuse_widget) ) :?>
		<div class="skt-dashboard-analytics">
			<?php
			foreach ($unuse_widget as $key => $data) :
				?>
				<div class="skt-dashboard-analytics__item">
					<fieldset>
						<div class="widget_inner">
							<div class="widget-title">
								<?php echo esc_html($widgets[$data]['title']); ?>
								<?php if( in_array( $data, $inactive_widgets ) ) : ?>
									<span class="disable" title="Disable"></span>
								<?php else:?>
									<span class="enabled" title="Enabled"></span>
								<?php endif;?>
							</div>
							<span class="skt-dashboard-analytics__item-total-count"><?php echo esc_html('total use: 0');?></span>
						</div>
					</fieldset>
				</div>
			<?php
			endforeach;
			?>
		</div>
		<?php endif;?>
	<?php endif;?>
</div>