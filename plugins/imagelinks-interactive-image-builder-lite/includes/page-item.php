<?php
defined('ABSPATH') || exit;

$page = sanitize_key(filter_input( INPUT_GET, 'page', FILTER_DEFAULT ));
$author = get_the_author_meta('display_name', $item->author);
$modified = mysql2date(get_option('date_format'), $item->modified) . ' at ' . mysql2date(get_option('time_format'), $item->modified);
?>
<div class="wrap imagelinks">
	<?php require 'page-info.php'; ?>
	<div class="imagelinks-page-header">
		<div class="imagelinks-title"><?php esc_html_e('ImageLinks Item', 'imagelinks'); ?></div>
		<div class="imagelinks-actions">
			<a href="?page=imagelinks_item"><?php esc_html_e('Add Item', 'imagelinks'); ?></a>
		</div>
	</div>
	<div class="imagelinks-messages" id="imagelinks-messages">
	</div>
	<!-- imagelinks app -->
	<div id="imagelinks-app-item" class="imagelinks-app" style="display:none;">
		<input id="imagelinks-load-config-from-file" type="file" style="display:none;" />
		<div class="imagelinks-loader-wrap">
			<div class="imagelinks-loader">
				<div class="imagelinks-loader-bar"></div>
				<div class="imagelinks-loader-bar"></div>
				<div class="imagelinks-loader-bar"></div>
				<div class="imagelinks-loader-bar"></div>
			</div>
		</div>
		<div class="imagelinks-wrap">
			<div class="imagelinks-main-header">
				<input class="imagelinks-title" type="text" al-text="appData.config.title" placeholder="<?php esc_html_e('Title', 'imagelinks'); ?>">
			</div>
			<div class="imagelinks-workplace">
				<div class="imagelinks-markers-frame">
					<div class="imagelinks-markers-toolbar-creation">
						<i class="imagelinks-icon imagelinks-icon-plus" al-on.click="appData.fn.addMarker(appData)" title="<?php esc_html_e('Add marker', 'imagelinks'); ?>"></i>
					</div>
					<div class="imagelinks-markers-toolbar-navigation" al-if="appData.config.markers.length > 0">
						<i class="imagelinks-icon imagelinks-icon-prev" al-on.click="appData.fn.prevMarker(appData)" title="<?php esc_html_e('Prev marker', 'imagelinks'); ?>"></i>
						<i class="imagelinks-icon imagelinks-icon-next" al-on.click="appData.fn.nextMarker(appData)" title="<?php esc_html_e('Next marker', 'imagelinks'); ?>"></i>
					</div>
					<div class="imagelinks-markers-toolbar-operations" al-if="appData.ui.activeMarker != null">
						<i class="imagelinks-icon imagelinks-icon-copy" al-on.click="appData.fn.copyMarker(appData)" title="<?php esc_html_e('Copy marker', 'imagelinks'); ?>"></i>
						<i class="imagelinks-icon imagelinks-icon-style" al-on.click="appData.fn.editMarker(appData, appData.ui.activeMarker)" title="<?php esc_html_e('Edit marker', 'imagelinks'); ?>"></i>
						<i class="imagelinks-icon imagelinks-icon-tooltip" al-attr.class.imagelinks-inactive="!appData.ui.activeMarker.tooltip.active" al-on.click="appData.fn.toggleMarkerTooltip(appData, appData.ui.activeMarker)" title="<?php esc_html_e('Enable/disable tooltip', 'imagelinks'); ?>"></i>
						<i class="imagelinks-icon" al-attr.class.imagelinks-icon-eye="appData.ui.activeMarker.visible" al-attr.class.imagelinks-icon-eye-off="!appData.ui.activeMarker.visible" al-on.click="appData.fn.toggleMarkerVisible(appData, appData.ui.activeMarker)" title="<?php esc_html_e('Show/hide marker', 'imagelinks'); ?>"></i>
						<i class="imagelinks-icon" al-attr.class.imagelinks-icon-lock-open="!appData.ui.activeMarker.lock" al-attr.class.imagelinks-icon-lock="appData.ui.activeMarker.lock" al-on.click="appData.fn.toggleMarkerLock(appData, appData.ui.activeMarker)" title="<?php esc_html_e('Lock/unlock marker', 'imagelinks'); ?>"></i>
					</div>
					<div class="imagelinks-markers-toolbar-view">
						<i class="imagelinks-icon imagelinks-icon-zoom-in" al-on.click="appData.fn.canvasZoomIn(appData)" title="<?php esc_html_e('Zoom in', 'imagelinks'); ?>"></i>
						<span class="imagelinks-zoom-value">{{appData.fn.getCanvasZoom(appData)}}%</span>
						<i class="imagelinks-icon imagelinks-icon-zoom-out" al-on.click="appData.fn.canvasZoomOut(appData)" title="<?php esc_html_e('Zoom out', 'imagelinks'); ?>"></i>
						<i class="imagelinks-icon imagelinks-icon-zoom-default" al-on.click="appData.fn.canvasZoomDefault(appData)" title="<?php esc_html_e('Zoom default', 'imagelinks'); ?>"></i>
						<i class="imagelinks-icon imagelinks-icon-zoom-fit" al-on.click="appData.fn.canvasZoomFit(appData)" title="<?php esc_html_e('Zoom fit', 'imagelinks'); ?>"></i>
						<i class="imagelinks-icon imagelinks-icon-center" al-on.click="appData.fn.canvasMoveDefault(appData)" title="<?php esc_html_e('Move default', 'imagelinks'); ?>"></i>
					</div>
					<div id="imagelinks-markers-canvas-wrap" class="imagelinks-markers-canvas-wrap" al-on.mousedown="appData.fn.onMoveCanvasStart(appData, $event)">
						<div id="imagelinks-markers-canvas" class="imagelinks-markers-canvas">
							<div id="imagelinks-markers-image" class="imagelinks-markers-image"></div>
							<div class="imagelinks-markers-stage">
								<div class="imagelinks-marker-pos"
									 al-attr.class.imagelinks-active="appData.fn.isMarkerActive(appData, marker)"
									 al-attr.class.imagelinks-hidden="!marker.visible"
									 al-attr.class.imagelinks-lock="marker.lock"
									 al-on.click="appData.fn.onMarkerClick(appData, marker)"
									 al-on.mousedown="appData.fn.onEditMarkerStart(appData, marker, 'drag', $event)"
									 al-style.top="appData.fn.getMarkerStyle(appData, marker, 'y')"
									 al-style.left="appData.fn.getMarkerStyle(appData, marker, 'x')"
									 al-style.transform="appData.fn.getMarkerStyle(appData, marker, 'angle')"
									 al-repeat="marker in appData.config.markers"
								>
									<div class="imagelinks-marker-wrap">
										<div class="imagelinks-marker-pulse" al-attr.class.imagelinks-active="marker.view.pulse.active">
										</div>
										<div class="imagelinks-marker"
											 tabindex="1"
											 al-on.keydown="appData.fn.onEditMarkerKeyDown(appData, marker, $event)"
											 al-style.width="appData.fn.getMarkerStyle(appData, marker, 'width')"
											 al-style.height="appData.fn.getMarkerStyle(appData, marker, 'height')"
											 al-init="appData.fn.initMarker(appData, marker, $element)"
										>
											<div class="imagelinks-marker-icon-wrap"
												 al-style.color="appData.fn.getIconStyle(appData, marker.view.icon, 'color')"
												 al-style.font-size="appData.fn.getIconStyle(appData, marker.view.icon, 'font-size')"
											>
												<div class="imagelinks-marker-icon" al-if="marker.view.icon.name"><i class="fa {{marker.view.icon.name}}"></i></div>
												<div class="imagelinks-marker-icon-label" al-if="marker.view.icon.label">{{marker.view.icon.label}}</div>
											</div>
										</div>
										<div class="imagelinks-marker-outline">
										</div>
										<div class="imagelinks-marker-resizer">
											<div class="imagelinks-marker-coord">X: {{appData.fn.getMarkerCoord(appData, marker, 'x')}} <br>Y: {{appData.fn.getMarkerCoord(appData, marker, 'y')}} <br>L: {{appData.fn.getMarkerCoord(appData, marker, 'angle')}}°</div>
											<div class="imagelinks-marker-rotator" al-on.mousedown="appData.fn.onEditMarkerStart(appData, marker, 'rotate', $event)">
												<div class="imagelinks-marker-line"></div>
											</div>
											<div class="imagelinks-marker-dragger-tl" al-on.mousedown="appData.fn.onEditMarkerStart(appData, marker, 'tl', $event)" al-if="!marker.autoWidth && !marker.autoHeight"></div>
											<div class="imagelinks-marker-dragger-tm" al-on.mousedown="appData.fn.onEditMarkerStart(appData, marker, 'tm', $event)" al-if="!marker.autoHeight"></div>
											<div class="imagelinks-marker-dragger-tr" al-on.mousedown="appData.fn.onEditMarkerStart(appData, marker, 'tr', $event)" al-if="!marker.autoWidth && !marker.autoHeight"></div>
											<div class="imagelinks-marker-dragger-rm" al-on.mousedown="appData.fn.onEditMarkerStart(appData, marker, 'rm', $event)" al-if="!marker.autoWidth"></div>
											<div class="imagelinks-marker-dragger-br" al-on.mousedown="appData.fn.onEditMarkerStart(appData, marker, 'br', $event)" al-if="!marker.autoWidth && !marker.autoHeight"></div>
											<div class="imagelinks-marker-dragger-bm" al-on.mousedown="appData.fn.onEditMarkerStart(appData, marker, 'bm', $event)" al-if="!marker.autoHeight"></div>
											<div class="imagelinks-marker-dragger-bl" al-on.mousedown="appData.fn.onEditMarkerStart(appData, marker, 'bl', $event)" al-if="!marker.autoWidth && !marker.autoHeight"></div>
											<div class="imagelinks-marker-dragger-lm" al-on.mousedown="appData.fn.onEditMarkerStart(appData, marker, 'lm', $event)" al-if="!marker.autoWidth"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="imagelinks-main-menu">
					<div class="imagelinks-left-panel">
						<a class="imagelinks-version-lite" href="https://1.envato.market/MAWdq" al-if="appData.plan=='lite'"><?php esc_html_e('Buy Pro version', 'imagelinks'); ?></a>
						<a class="imagelinks-version-pro" href="#" al-if="appData.plan=='pro'"><?php esc_html_e('Pro Version', 'imagelinks'); ?></a>
					</div>
					<div class="imagelinks-right-panel">
						<div class="imagelinks-item">
							<i class="imagelinks-icon imagelinks-icon-menu"></i>
							<div class="imagelinks-menu-list">
								<a href="#" al-on.click="appData.fn.loadConfigFromFile(appData)"><i class="imagelinks-icon imagelinks-icon-from-file"></i><?php esc_html_e('Load Config From File', 'imagelinks'); ?></a>
								<a href="#" al-on.click="appData.fn.saveConfigToFile(appData)"><i class="imagelinks-icon imagelinks-icon-to-file"></i><?php esc_html_e('Save Config To File', 'imagelinks'); ?></a>
								<a href="#" al-on.click="appData.fn.selectImportItem(appData)" al-if="appData.import_items"><i class="imagelinks-icon imagelinks-icon-download"></i><?php esc_html_e('Import Config', 'imagelinks'); ?></a>
							</div>
						</div>
						<div class="imagelinks-item" al-on.click="appData.fn.toggleFullscreen(appData)">
							<i class="imagelinks-icon imagelinks-icon-size-fullscreen" al-if="!appData.ui.fullscreen"></i>
							<i class="imagelinks-icon imagelinks-icon-size-actual" al-if="appData.ui.fullscreen"></i>
						</div>
					</div>
				</div>
				<div class="imagelinks-main-tabs imagelinks-clear-fix">
					<div class="imagelinks-tab" al-attr.class.imagelinks-active="appData.ui.tabs.general" al-on.click="appData.fn.onTab(appData, 'general')"><?php esc_html_e('General', 'imagelinks'); ?><div class="imagelinks-status" al-if="appData.config.active"></div></div>
					<div class="imagelinks-tab" al-attr.class.imagelinks-active="appData.ui.tabs.markers" al-on.click="appData.fn.onTab(appData, 'markers')"><?php esc_html_e('Markers', 'imagelinks'); ?></div>
					<div class="imagelinks-tab" al-attr.class.imagelinks-active="appData.ui.tabs.customCSS" al-on.click="appData.fn.onTab(appData, 'customCSS')"><?php esc_html_e('Custom CSS', 'imagelinks'); ?><div class="imagelinks-status" al-if="appData.config.customCSS.active"></div></div>
					<div class="imagelinks-tab" al-attr.class.imagelinks-active="appData.ui.tabs.customJS" al-on.click="appData.fn.onTab(appData, 'customJS')"><?php esc_html_e('Custom JS', 'imagelinks'); ?><div class="imagelinks-status" al-if="appData.config.customJS.active"></div></div>
					<div class="imagelinks-tab" al-attr.class.imagelinks-active="appData.ui.tabs.shortcode" al-on.click="appData.fn.onTab(appData, 'shortcode')" al-if="appData.wp_item_id"><?php esc_html_e('Shortcode', 'imagelinks'); ?></div>
					<div class="imagelinks-tab">
						<div class="imagelinks-button imagelinks-green" al-on.click="appData.fn.preview(appData);" al-if="appData.wp_item_id" title="<?php esc_html_e('The item should be saved before preview', 'imagelinks'); ?>"><?php esc_html_e('Preview', 'imagelinks'); ?></div>
						<div class="imagelinks-button imagelinks-blue" al-on.click="appData.fn.saveConfig(appData);"><?php esc_html_e('Save', 'imagelinks'); ?></div>
					</div>
				</div>
				<div class="imagelinks-main-data">
					<div class="imagelinks-section" al-attr.class.imagelinks-active="appData.ui.tabs.general">
						<div class="imagelinks-stage">
							<div class="imagelinks-main-panel">
								<div class="imagelinks-data imagelinks-active">
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Enable/disable item', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Enable item', 'imagelinks'); ?></div>
										<div al-toggle="appData.config.active"></div>
									</div>
									
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Sets a main image (jpeg or png format)', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Main image', 'imagelinks'); ?></div>
										<div class="imagelinks-input-group">
											<div class="imagelinks-input-group-cell">
												<input class="imagelinks-text imagelinks-long" type="text" al-text="appData.config.image.url" placeholder="<?php esc_html_e('Select an image', 'imagelinks'); ?>">
											</div>
											<div class="imagelinks-input-group-cell imagelinks-pinch">
												<div class="imagelinks-btn imagelinks-default imagelinks-no-bl" al-on.click="appData.fn.selectImage(appData, appData.rootScope, appData.config.image)" title="<?php esc_html_e('Select an image', 'imagelinks'); ?>"><span><i class="imagelinks-icon imagelinks-icon-select"></i></span></div>
											</div>
										</div>
										<div class="imagelinks-input-group">
											<div class="imagelinks-input-group-cell imagelinks-pinch">
												<div al-checkbox="appData.config.image.relative"></div>
											</div>
											<div class="imagelinks-input-group-cell">
												<?php esc_html_e('Use relative path', 'imagelinks'); ?>
											</div>
										</div>
									</div>
									
									<div class="imagelinks-control">
										<div class="imagelinks-info"><?php esc_html_e('Container settings', 'imagelinks'); ?></div>
									</div>
									
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('The container width will be auto calculated', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Auto width', 'imagelinks'); ?></div>
										<div al-toggle="appData.config.autoWidth"></div>
									</div>
									
									<div class="imagelinks-control" al-if="!appData.config.autoWidth">
										<div class="imagelinks-helper" title="<?php esc_html_e('Sets the container width, can be any valid CSS units, not just pixels', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Custom width', 'imagelinks'); ?></div>
										<input class="imagelinks-text" type="text" al-text="appData.config.containerWidth" placeholder="<?php esc_html_e('Default: auto', 'imagelinks'); ?>">
									</div>
									
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('The container height will be auto calculated', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Auto height', 'imagelinks'); ?></div>
										<div al-toggle="appData.config.autoHeight"></div>
									</div>
									
									<div class="imagelinks-control" al-if="!appData.config.autoHeight">
										<div class="imagelinks-helper" title="<?php esc_html_e('Sets the container height, can be any valid CSS units, not just pixels', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Custom height', 'imagelinks'); ?></div>
										<input class="imagelinks-text" type="text" al-text="appData.config.containerHeight" placeholder="<?php esc_html_e('Default: auto', 'imagelinks'); ?>">
									</div>
									
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Specifies a theme of elements', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Main theme', 'imagelinks'); ?></div>
										<select class="imagelinks-select imagelinks-capitalize" al-select="appData.config.theme">
											<option al-option="null"><?php esc_html_e('none', 'imagelinks'); ?></option>
											<option al-repeat="theme in appData.themes" al-option="theme.id">{{theme.title}}</option>
										</select>
									</div>
									
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Background color in hexadecimal format (#fff or #555555)', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Background color', 'imagelinks'); ?></div>
										<div class="imagelinks-color" al-color="appData.config.background.color"></div>
									</div>
									
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Sets a background image (jpeg or png format)', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Background image', 'imagelinks'); ?></div>
										<div class="imagelinks-input-group">
											<div class="imagelinks-input-group-cell">
												<input class="imagelinks-text imagelinks-long" type="text" al-text="appData.config.background.image.url" placeholder="<?php esc_html_e('Select an image', 'imagelinks'); ?>">
											</div>
											<div class="imagelinks-input-group-cell imagelinks-pinch">
												<div class="imagelinks-btn imagelinks-default imagelinks-no-bl" al-on.click="appData.fn.selectImage(appData, appData.rootScope, appData.config.background.image)" title="<?php esc_html_e('Select a background image', 'imagelinks'); ?>"><span><i class="imagelinks-icon imagelinks-icon-select"></i></span></div>
											</div>
										</div>
										<div class="imagelinks-input-group">
											<div class="imagelinks-input-group-cell imagelinks-pinch">
												<div al-checkbox="appData.config.background.image.relative"></div>
											</div>
											<div class="imagelinks-input-group-cell">
												<?php esc_html_e('Use relative path', 'imagelinks'); ?>
											</div>
										</div>
									</div>
									
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Specifies a size of the background image', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Background size', 'imagelinks'); ?></div>
										<div class="imagelinks-select" al-backgroundsize="appData.config.background.size"></div>
									</div>
									
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('How the background image will be repeated', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Background repeat', 'imagelinks'); ?></div>
										<div class="imagelinks-select" al-backgroundrepeat="appData.config.background.repeat"></div>
									</div>
									
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Sets a starting position of the background image', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Background position', 'imagelinks'); ?></div>
										<input class="imagelinks-text" type="text" al-text="appData.config.background.position" placeholder="<?php esc_html_e('Example: 50% 50%', 'imagelinks'); ?>">
									</div>
									
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Sets additional css classes to the container', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('Additional CSS classes', 'imagelinks'); ?></div>
										<input class="imagelinks-text" type="text" al-text="appData.config.class">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="imagelinks-section" al-attr.class.imagelinks-active="appData.ui.tabs.markers">
						<div class="imagelinks-stage">
							<div class="imagelinks-sidebar-panel" al-attr.class.imagelinks-hidden="!appData.ui.sidebar" al-style.width="appData.ui.sidebarWidth">
								<div class="imagelinks-data">
									<div class="imagelinks-markers-wrap">
										<div class="imagelinks-markers-toolbar">
											<div class="imagelinks-left-panel">
												<i class="imagelinks-icon imagelinks-icon-plus" al-on.click="appData.fn.addMarker(appData)" title="<?php esc_html_e('add marker', 'imagelinks'); ?>"></i>
												<span al-if="appData.ui.activeMarker != null">
												<i class="imagelinks-separator"></i>
												<i class="imagelinks-icon imagelinks-icon-copy" al-on.click="appData.fn.copyMarker(appData)" title="<?php esc_html_e('copy', 'imagelinks'); ?>"></i>
												<i class="imagelinks-icon imagelinks-icon-arrow-up" al-on.click="appData.fn.updownMarker(appData, 'up')" title="<?php esc_html_e('move up', 'imagelinks'); ?>"></i>
												<i class="imagelinks-icon imagelinks-icon-arrow-down" al-on.click="appData.fn.updownMarker(appData, 'down')" title="<?php esc_html_e('move down', 'imagelinks'); ?>"></i>
												</span>
											</div>
											<div class="imagelinks-right-panel">
												<i class="imagelinks-icon imagelinks-icon-trash imagelinks-icon-red" al-if="appData.ui.activeMarker != null" al-on.click="appData.fn.deleteMarker(appData)" title="<?php esc_html_e('delete', 'imagelinks'); ?>"></i>
												<i class="imagelinks-icon imagelinks-icon-save" al-if="appData.ui.activeMarker != null" al-on.click="appData.fn.saveMarkerTemplate(appData, appData.ui.activeMarker)" title="<?php esc_html_e('save the marker as a template', 'imagelinks'); ?>"></i>
											</div>
										</div>
										<div class="imagelinks-marker"
										 al-attr.class.imagelinks-active="appData.fn.isMarkerActive(appData, marker)"
										 al-on.click="appData.fn.onMarkerItemClick(appData, marker)"
										 al-repeat="marker in appData.config.markers"
										 >
											<i class="imagelinks-icon imagelinks-icon-pin"></i>
											<div class="imagelinks-label">{{marker.title ? marker.title : '...'}}</div>
											<div class="imagelinks-actions">
												<i class="imagelinks-icon imagelinks-icon-style" al-on.click="appData.fn.editMarker(appData, marker)" title="<?php esc_html_e('edit', 'imagelinks'); ?>"></i>
												<i class="imagelinks-icon imagelinks-icon-tooltip" al-attr.class.imagelinks-inactive="!marker.tooltip.active" al-on.click="appData.fn.toggleMarkerTooltip(appData, marker)" title="<?php esc_html_e('enable/disable tooltip', 'imagelinks'); ?>"></i>
												<i class="imagelinks-icon" al-attr.class.imagelinks-icon-eye="marker.visible" al-attr.class.imagelinks-icon-eye-off="!marker.visible" al-on.click="appData.fn.toggleMarkerVisible(appData, marker)" title="<?php esc_html_e('show/hide', 'imagelinks'); ?>"></i>
												<i class="imagelinks-icon" al-attr.class.imagelinks-icon-lock-open="!marker.lock" al-attr.class.imagelinks-icon-lock="marker.lock" al-on.click="appData.fn.toggleMarkerLock(appData, marker)" title="<?php esc_html_e('lock/unlock', 'imagelinks'); ?>"></i>
											</div>
										</div>
									</div>
								</div>
								<div class="imagelinks-sidebar-resizer" al-on.mousedown="appData.fn.onSidebarResizeStart(appData, $event)">
									<div class="imagelinks-sidebar-hide" al-on.click="appData.fn.toggleSidebarPanel(appData)">
										<i class="imagelinks-icon imagelinks-icon-next" al-if="!appData.ui.sidebar"></i>
										<i class="imagelinks-icon imagelinks-icon-prev" al-if="appData.ui.sidebar"></i>
									</div>
								</div>
							</div>
							<div class="imagelinks-main-panel">
								<div class="imagelinks-tabs imagelinks-clear-fix">
									<div class="imagelinks-tab" al-attr.class.imagelinks-active="appData.ui.markerTabs.marker" al-on.click="appData.fn.onMarkerTab(appData, 'marker')"><?php esc_html_e('Marker', 'imagelinks'); ?></div>
									<div class="imagelinks-tab" al-attr.class.imagelinks-active="appData.ui.markerTabs.tooltip" al-on.click="appData.fn.onMarkerTab(appData, 'tooltip')"><?php esc_html_e('Tooltip', 'imagelinks'); ?><div class="imagelinks-status" al-if="appData.ui.activeMarker != null && appData.ui.activeMarker.tooltip.active"></div></div>
								</div>
								<div class="imagelinks-data" al-attr.class.imagelinks-active="appData.ui.markerTabs.marker">
									<div al-if="appData.ui.activeMarker == null">
										<div class="imagelinks-control">
											<div class="imagelinks-info"><?php esc_html_e('Please, select a marker to view settings', 'imagelinks'); ?></div>
										</div>
									</div>
									<div al-if="appData.ui.activeMarker != null">
										<div class="imagelinks-block imagelinks-block-flat" al-attr.class.imagelinks-block-folded="appData.ui.markerSections.general">
											<div class="imagelinks-block-header" al-on.click="appData.fn.onMarkerSection(appData,'general')">
												<div class="imagelinks-block-title"><?php esc_html_e('General', 'imagelinks'); ?></div>
												<div class="imagelinks-block-state"></div>
											</div>
											<div class="imagelinks-block-data">
												<div class="imagelinks-control">
													<div class="imagelinks-helper" title="<?php esc_html_e('Sets a marker title', 'imagelinks'); ?>"></div>
													<div class="imagelinks-label"><?php esc_html_e('Title', 'imagelinks'); ?></div>
													<input class="imagelinks-text imagelinks-long" type="text" al-text="appData.ui.activeMarker.title">
												</div>
												
												<div class="imagelinks-control">
													<div class="imagelinks-helper" title="<?php esc_html_e('Sets a marker position', 'imagelinks'); ?>"></div>
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-label"><?php esc_html_e('X [px]', 'imagelinks'); ?></div>
															<input class="imagelinks-number imagelinks-long" al-integer="appData.ui.activeMarker.x">
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-label"><?php esc_html_e('Y [px]', 'imagelinks'); ?></div>
															<input class="imagelinks-number imagelinks-long" al-integer="appData.ui.activeMarker.y">
														</div>
													</div>
												</div>
												
												<div class="imagelinks-control">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap" al-attr.class.imagelinks-nogap="appData.ui.activeMarker.autoWidth">
															<div class="imagelinks-helper" title="<?php esc_html_e('Enable/disable auto marker width', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Auto width', 'imagelinks'); ?></div>
															<div al-toggle="appData.ui.activeMarker.autoWidth"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap" al-if="!appData.ui.activeMarker.autoWidth">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a marker width in px', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Width [px]', 'imagelinks'); ?></div>
															<input class="imagelinks-number imagelinks-long" al-integer="appData.ui.activeMarker.width">
														</div>
													</div>
												</div>
												<div class="imagelinks-control">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap" al-attr.class.imagelinks-nogap="appData.ui.activeMarker.autoHeight">
															<div class="imagelinks-helper" title="<?php esc_html_e('Enable/disable auto marker heihgt', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Auto height', 'imagelinks'); ?></div>
															<div al-toggle="appData.ui.activeMarker.autoHeight"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap" al-if="!appData.ui.activeMarker.autoHeight">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a marker height in px', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Height [px]', 'imagelinks'); ?></div>
															<input class="imagelinks-number imagelinks-long" al-integer="appData.ui.activeMarker.height">
														</div>
													</div>
												</div>
												
												<div class="imagelinks-control">
													<div class="imagelinks-helper" title="<?php esc_html_e('Set a marker angle', 'imagelinks'); ?>"></div>
													<div class="imagelinks-label"><?php esc_html_e('Angle [deg]', 'imagelinks'); ?></div>
													<input class="imagelinks-number imagelinks-long" al-float="appData.ui.activeMarker.angle">
												</div>
											</div>
										</div>
										
										<div class="imagelinks-block imagelinks-block-flat" al-attr.class.imagelinks-block-folded="appData.ui.markerSections.data">
											<div class="imagelinks-block-header" al-on.click="appData.fn.onMarkerSection(appData,'data')">
												<div class="imagelinks-block-title"><?php esc_html_e('Data', 'imagelinks'); ?></div>
												<div class="imagelinks-block-state"></div>
											</div>
											<div class="imagelinks-block-data">
												<div class="imagelinks-control">
													<div class="imagelinks-helper" title="<?php esc_html_e('Adds a specific url to the marker', 'imagelinks'); ?>"></div>
													<div class="imagelinks-label"><?php esc_html_e('URL', 'imagelinks'); ?></div>
													<div class="imagelinks-input-group imagelinks-long">
														<input class="imagelinks-text imagelinks-long" type="text" al-text="appData.ui.activeMarker.link" placeholder="<?php esc_html_e('URL', 'imagelinks'); ?>">
													</div>
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-pinch">
															<div al-checkbox="appData.ui.activeMarker.linkNewWindow"></div>
														</div>
														<div class="imagelinks-input-group-cell">
															<?php esc_html_e('Open url in a new window', 'imagelinks'); ?>
														</div>
													</div>
												</div>
												<div class="imagelinks-control">
													<div class="imagelinks-helper" title="<?php esc_html_e('Adds a specific string data to the marker, if we want to use it in custom code later', 'imagelinks'); ?>"></div>
													<div class="imagelinks-label"><?php esc_html_e('User data', 'imagelinks'); ?></div>
													<textarea class="imagelinks-long" al-textarea="appData.ui.activeMarker.userData"></textarea>
												</div>
											</div>
										</div>
										
										<div class="imagelinks-block imagelinks-block-flat" al-attr.class.imagelinks-block-folded="appData.ui.markerSections.appearance">
											<div class="imagelinks-block-header" al-on.click="appData.fn.onMarkerSection(appData,'appearance')">
												<div class="imagelinks-block-title"><?php esc_html_e('Appearance', 'imagelinks'); ?></div>
												<div class="imagelinks-block-state"></div>
											</div>
											<div class="imagelinks-block-data">
												<!-- responsive & noevents -->
												<div class="imagelinks-control">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('The marker size depends on the image size', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Responsive', 'imagelinks'); ?></div>
															<div al-toggle="appData.ui.activeMarker.responsive"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('The marker is never the target of mouse events', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('No events', 'imagelinks'); ?></div>
															<div al-toggle="appData.ui.activeMarker.noevents"></div>
														</div>
													</div>
												</div>
												
												<!-- icon & label -->
												<div class="imagelinks-control">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a marker icon', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Icon name', 'imagelinks'); ?></div>
															<div class="imagelinks-input-group imagelinks-long">
																<div class="imagelinks-input-group-cell">
																	<input class="imagelinks-text imagelinks-long" type="text" al-text="appData.ui.activeMarker.view.icon.name" placeholder="<?php esc_html_e('Select an icon', 'imagelinks'); ?>">
																</div>
																<div class="imagelinks-input-group-cell imagelinks-pinch">
																	<div class="imagelinks-btn imagelinks-default imagelinks-no-bl" al-on.click="appData.fn.selectIcon(appData, appData.rootScope, appData.ui.activeMarker.view.icon)"><span><i class="imagelinks-icon imagelinks-icon-select"></i></span></div>
																</div>
															</div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets an icon label', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Icon label', 'imagelinks'); ?></div>
															<input class="imagelinks-text imagelinks-long" type="text" al-text="appData.ui.activeMarker.view.icon.label">
														</div>
													</div>
												</div>
												
												<!-- icon color & size -->
												<div class="imagelinks-control">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets an icon color', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Icon color', 'imagelinks'); ?></div>
															<div class="imagelinks-color imagelinks-long" al-color="appData.ui.activeMarker.view.icon.color"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets an icon size', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Icon size', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.icon.size"></div>
														</div>
													</div>
												</div>
												
												<!-- icon margin -->
												<div class="imagelinks-control">
													<div class="imagelinks-helper" title="<?php esc_html_e('Sets an icon margin', 'imagelinks'); ?>"></div>
													<div class="imagelinks-label"><?php esc_html_e('Icon margin', 'imagelinks'); ?></div>
													<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.icon.margin.all"></div>
												</div>
												
												<!-- icon margins -->
												<div class="imagelinks-control">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a top icon margin', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('top', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.icon.margin.top"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a right icon margin', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('right', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.icon.margin.right"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a bottom icon margin', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('bottom', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.icon.margin.bottom"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a left icon margin', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('left', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.icon.margin.left"></div>
														</div>
													</div>
												</div>
												
												<!-- marker background image -->
												<div class="imagelinks-control">
													<div class="imagelinks-helper" title="<?php esc_html_e('Sets a background image (jpeg or png format)', 'imagelinks'); ?>"></div>
													<div class="imagelinks-label"><?php esc_html_e('Background image', 'imagelinks'); ?></div>
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell">
															<input class="imagelinks-text imagelinks-long" type="text" al-text="appData.ui.activeMarker.view.background.image.url" placeholder="<?php esc_html_e('Select an image', 'imagelinks'); ?>">
														</div>
														<div class="imagelinks-input-group-cell imagelinks-pinch">
															<div class="imagelinks-btn imagelinks-default imagelinks-no-bl" al-on.click="appData.fn.selectImage(appData, appData.rootScope, appData.ui.activeMarker.view.background.image)"><span><i class="imagelinks-icon imagelinks-icon-select"></i></span></div>
														</div>
													</div>
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-pinch">
															<div al-checkbox="appData.ui.activeMarker.view.background.image.relative"></div>
														</div>
														<div class="imagelinks-input-group-cell">
															<?php esc_html_e('Use relative path', 'imagelinks'); ?>
														</div>
													</div>
												</div>
												
												<!-- marker background color & repeat -->
												<div class="imagelinks-control">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a background color', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Background color', 'imagelinks'); ?></div>
															<div class="imagelinks-color imagelinks-long" al-color="appData.ui.activeMarker.view.background.color"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('How the background image will be repeated', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Background repeat', 'imagelinks'); ?></div>
															<div class="imagelinks-select imagelinks-long" al-backgroundrepeat="appData.ui.activeMarker.view.background.repeat"></div>
														</div>
													</div>
												</div>
												
												<!-- marker background size & position -->
												<div class="imagelinks-control">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Specifies a size of the background image', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Background size', 'imagelinks'); ?></div>
															<div class="imagelinks-select imagelinks-long" al-backgroundsize="appData.ui.activeMarker.view.background.size"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a starting position of the background image', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Background position', 'imagelinks'); ?></div>
															<input class="imagelinks-text imagelinks-long" type="text" al-text="appData.ui.activeMarker.view.background.position" placeholder="<?php esc_html_e('Example: 50% 50%', 'imagelinks'); ?>">
														</div>
													</div>
												</div>
												
												<!-- border tabs -->
												<div class="imagelinks-control">
													<div class="imagelinks-border-tabs">
														<div class="imagelinks-tab-all" al-attr.class.imagelinks-active="appData.ui.borderTabs.all" al-on.click="appData.fn.onBorderTab(appData,'all')" al-attr.class.imagelinks-enable="appData.ui.activeMarker.view.border.all.active"><?php esc_html_e('All', 'imagelinks'); ?></div>
														<div class="imagelinks-tab-top" al-attr.class.imagelinks-active="appData.ui.borderTabs.top" al-on.click="appData.fn.onBorderTab(appData,'top')" al-attr.class.imagelinks-enable="appData.ui.activeMarker.view.border.top.active"><?php esc_html_e('Top', 'imagelinks'); ?></div>
														<div class="imagelinks-tab-right" al-attr.class.imagelinks-active="appData.ui.borderTabs.right" al-on.click="appData.fn.onBorderTab(appData,'right')" al-attr.class.imagelinks-enable="appData.ui.activeMarker.view.border.right.active"><?php esc_html_e('Right', 'imagelinks'); ?></div>
														<div class="imagelinks-tab-bottom" al-attr.class.imagelinks-active="appData.ui.borderTabs.bottom" al-on.click="appData.fn.onBorderTab(appData,'bottom')" al-attr.class.imagelinks-enable="appData.ui.activeMarker.view.border.bottom.active"><?php esc_html_e('Bottom', 'imagelinks'); ?></div>
														<div class="imagelinks-tab-left" al-attr.class.imagelinks-active="appData.ui.borderTabs.left" al-on.click="appData.fn.onBorderTab(appData,'left')" al-attr.class.imagelinks-enable="appData.ui.activeMarker.view.border.left.active"><?php esc_html_e('Left', 'imagelinks'); ?></div>
													</div>
												</div>
												
												<!-- border all -->
												<div class="imagelinks-control" al-if="appData.ui.borderTabs.all">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-pinch">
															<div al-checkbox="appData.ui.activeMarker.view.border.all.active"></div>
														</div>
														<div class="imagelinks-input-group-cell">
															<?php esc_html_e('Enable border', 'imagelinks'); ?>
														</div>
													</div>
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border color', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border color', 'imagelinks'); ?></div>
															<div class="imagelinks-color imagelinks-long" al-color="appData.ui.activeMarker.view.border.all.color"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border style', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border style', 'imagelinks'); ?></div>
															<div class="imagelinks-select imagelinks-long" al-borderstyle="appData.ui.activeMarker.view.border.all.style"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border width', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border width', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.border.all.width"></div>
														</div>
													</div>
												</div>
												
												<!-- border top -->
												<div class="imagelinks-control" al-if="appData.ui.borderTabs.top">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-pinch">
															<div al-checkbox="appData.ui.activeMarker.view.border.top.active"></div>
														</div>
														<div class="imagelinks-input-group-cell">
															<?php esc_html_e('Enable top border', 'imagelinks'); ?>
														</div>
													</div>
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border color', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border color', 'imagelinks'); ?></div>
															<div class="imagelinks-color imagelinks-long" al-color="appData.ui.activeMarker.view.border.top.color"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border style', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border style', 'imagelinks'); ?></div>
															<div class="imagelinks-select imagelinks-long" al-borderstyle="appData.ui.activeMarker.view.border.top.style"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border width', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border width', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.border.top.width"></div>
														</div>
													</div>
												</div>
												
												<!-- border right -->
												<div class="imagelinks-control" al-if="appData.ui.borderTabs.right">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-pinch">
															<div al-checkbox="appData.ui.activeMarker.view.border.right.active"></div>
														</div>
														<div class="imagelinks-input-group-cell">
															<?php esc_html_e('Enable right border', 'imagelinks'); ?>
														</div>
													</div>
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border color', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border color', 'imagelinks'); ?></div>
															<div class="imagelinks-color imagelinks-long" al-color="appData.ui.activeMarker.view.border.right.color"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border style', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border style', 'imagelinks'); ?></div>
															<div class="imagelinks-select imagelinks-long" al-borderstyle="appData.ui.activeMarker.view.border.right.style"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border width', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border width', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.border.right.width"></div>
														</div>
													</div>
												</div>
												
												<!-- border bottom -->
												<div class="imagelinks-control" al-if="appData.ui.borderTabs.bottom">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-pinch">
															<div al-checkbox="appData.ui.activeMarker.view.border.bottom.active"></div>
														</div>
														<div class="imagelinks-input-group-cell">
															<?php esc_html_e('Enable bottom border', 'imagelinks'); ?>
														</div>
													</div>
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border color', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border color', 'imagelinks'); ?></div>
															<div class="imagelinks-color imagelinks-long" al-color="appData.ui.activeMarker.view.border.bottom.color"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border style', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border style', 'imagelinks'); ?></div>
															<div class="imagelinks-select imagelinks-long" al-borderstyle="appData.ui.activeMarker.view.border.bottom.style"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border width', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border width', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.border.bottom.width"></div>
														</div>
													</div>
												</div>
												
												<!-- border left -->
												<div class="imagelinks-control" al-if="appData.ui.borderTabs.left">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-pinch">
															<div al-checkbox="appData.ui.activeMarker.view.border.left.active"></div>
														</div>
														<div class="imagelinks-input-group-cell">
															<?php esc_html_e('Enable left border', 'imagelinks'); ?>
														</div>
													</div>
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border color', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border color', 'imagelinks'); ?></div>
															<div class="imagelinks-color imagelinks-long" al-color="appData.ui.activeMarker.view.border.left.color"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border style', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border style', 'imagelinks'); ?></div>
															<div class="imagelinks-select imagelinks-long" al-borderstyle="appData.ui.activeMarker.view.border.left.style"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border width', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Border width', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.border.left.width"></div>
														</div>
													</div>
												</div>
												
												<!-- border radius -->
												<div class="imagelinks-control">
													<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border radius', 'imagelinks'); ?>"></div>
													<div class="imagelinks-label"><?php esc_html_e('Border radius', 'imagelinks'); ?></div>
													<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.border.radius.all"></div>
												</div>
												
												<!-- border radiuses -->
												<div class="imagelinks-control">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border top-left radius', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('top-left', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.border.radius.topLeft"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border top-right radius', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('top-right', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.border.radius.topRight"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border bottom-right radius', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('bottom-right', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.border.radius.bottomRight"></div>
															
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border bottom-left radius', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('bottom-left', 'imagelinks'); ?></div>
															<div class="imagelinks-unit imagelinks-long" al-unit="appData.ui.activeMarker.view.border.radius.bottomLeft"></div>
														</div>
													</div>
												</div>
												
												<!-- custom css class -->
												<div class="imagelinks-control">
													<div class="imagelinks-helper" title="<?php esc_html_e('Sets additional css classes to the marker', 'imagelinks'); ?>"></div>
													<div class="imagelinks-label"><?php esc_html_e('Additional CSS classes', 'imagelinks'); ?></div>
													<input class="imagelinks-number imagelinks-long" type="text" al-text="appData.ui.activeMarker.className">
												</div>
											</div>
										</div>
										
										<div class="imagelinks-block imagelinks-block-flat" al-attr.class.imagelinks-block-folded="appData.ui.markerSections.animation">
											<div class="imagelinks-block-header" al-on.click="appData.fn.onMarkerSection(appData,'animation')">
												<div class="imagelinks-block-title"><?php esc_html_e('Animation', 'imagelinks'); ?></div>
												<div class="imagelinks-block-state"></div>
											</div>
											<div class="imagelinks-block-data">
												<div class="imagelinks-control">
													<div class="imagelinks-helper" title="<?php esc_html_e('Enable/disable pulse animation', 'imagelinks'); ?>"></div>
													<div class="imagelinks-label"><?php esc_html_e('Enable pulse', 'imagelinks'); ?></div>
													<div al-toggle="appData.ui.activeMarker.view.pulse.active"></div>
												</div>
												
												<div class="imagelinks-control">
													<div class="imagelinks-input-group imagelinks-long">
														<div class="imagelinks-input-group-cell imagelinks-rgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a pulse animation color', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Pulse color', 'imagelinks'); ?></div>
															<div class="imagelinks-color imagelinks-long" al-color="appData.ui.activeMarker.view.pulse.color"></div>
														</div>
														<div class="imagelinks-input-group-cell imagelinks-lgap">
															<div class="imagelinks-helper" title="<?php esc_html_e('Sets a pulse animation duration', 'imagelinks'); ?>"></div>
															<div class="imagelinks-label"><?php esc_html_e('Pulse duration [ms]', 'imagelinks'); ?></div>
															<input class="imagelinks-number imagelinks-long" al-integer="appData.ui.activeMarker.view.pulse.duration">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="imagelinks-data" al-attr.class.imagelinks-active="appData.ui.markerTabs.tooltip">
									<div class="imagelinks-data-block" al-attr.class.imagelinks-active="appData.ui.activeMarker == null">
										<div class="imagelinks-control">
											<div class="imagelinks-info"><?php esc_html_e('Please, select a marker to view tooltip settings', 'imagelinks'); ?></div>
										</div>
									</div>
									<div class="imagelinks-data-block" al-attr.class.imagelinks-active="appData.ui.activeMarker != null">
										<div class="imagelinks-block imagelinks-block-flat" al-attr.class.imagelinks-block-folded="appData.ui.tooltipSections.data">
											<div class="imagelinks-block-header" al-on.click="appData.fn.onTooltipSection(appData,'data')">
												<div class="imagelinks-block-title"><?php esc_html_e('Data', 'imagelinks'); ?></div>
												<div class="imagelinks-block-state"></div>
											</div>
											<div class="imagelinks-block-data">
												<div al-if="appData.ui.activeMarker != null">
													<div class="imagelinks-control">
														<div class="imagelinks-helper" title="<?php esc_html_e('Enable/disable tooltip for the selected marker', 'imagelinks'); ?>"></div>
														<div class="imagelinks-label"><?php esc_html_e('Enable tooltip', 'imagelinks'); ?></div>
														<div al-toggle="appData.ui.activeMarker.tooltip.active"></div>
													</div>
												</div>
												
												<div class="imagelinks-control">
													<?php
														$settings = array(
															'tinymce' => true,
															'textarea_name' => 'imagelinks-tooltip-text',
															'wpautop' => false,
															'editor_height' => 200, // In pixels, takes precedence and has no default value
															'drag_drop_upload' => true,
															'media_buttons' => true,
															'teeny' => true,
															'quicktags' => true
														);
														wp_editor('','imagelinks-tooltip-editor', $settings);
													?>
												</div>
											</div>
										</div>
										
										<div class="imagelinks-block imagelinks-block-flat" al-attr.class.imagelinks-block-folded="appData.ui.tooltipSections.appearance">
											<div class="imagelinks-block-header" al-on.click="appData.fn.onTooltipSection(appData,'appearance')">
											
											<div class="imagelinks-block-title"><?php esc_html_e('Appearance', 'imagelinks'); ?></div>
												<div class="imagelinks-block-state"></div>
											</div>
											<div class="imagelinks-block-data">
												<div al-if="appData.ui.activeMarker != null">
													<div class="imagelinks-control">
														<div class="imagelinks-input-group imagelinks-long">
															<div class="imagelinks-input-group-cell imagelinks-rgap">
																<div class="imagelinks-helper" title="<?php esc_html_e('Specifies a tooltip event trigger', 'imagelinks'); ?>"></div>
																<div class="imagelinks-label"><?php esc_html_e('Trigger', 'imagelinks'); ?></div>
																<div class="imagelinks-select imagelinks-long" al-tooltiptrigger="appData.ui.activeMarker.tooltip.trigger"></div>
															</div>
															<div class="imagelinks-input-group-cell imagelinks-lgap">
																<div class="imagelinks-helper" title="<?php esc_html_e('Specifies a tooltip placement', 'imagelinks'); ?>"></div>
																<div class="imagelinks-label"><?php esc_html_e('Placement', 'imagelinks'); ?></div>
																<div class="imagelinks-select imagelinks-long" al-tooltipplacement="appData.ui.activeMarker.tooltip.placement"></div>
															</div>
														</div>
													</div>
													
													<div class="imagelinks-control">
														<div class="imagelinks-helper" title="<?php esc_html_e('Sets tooltip offset', 'imagelinks'); ?>"></div>
														<div class="imagelinks-input-group imagelinks-long">
															<div class="imagelinks-input-group-cell imagelinks-rgap">
																<div class="imagelinks-label"><?php esc_html_e('Offset X [px]', 'imagelinks'); ?></div>
																<input class="imagelinks-number imagelinks-long" al-integer="appData.ui.activeMarker.tooltip.offset.x">
															</div>
															<div class="imagelinks-input-group-cell imagelinks-lgap">
																<div class="imagelinks-label"><?php esc_html_e('Offset Y [px]', 'imagelinks'); ?></div>
																<input class="imagelinks-number imagelinks-long" al-integer="appData.ui.activeMarker.tooltip.offset.y">
															</div>
														</div>
													</div>
													
													<div class="imagelinks-control" al-if="appData.ui.activeMarker.tooltip.trigger == 'hover' || appData.ui.activeMarker.tooltip.trigger == 'clickbody'">
														<div class="imagelinks-input-group imagelinks-long">
															<div class="imagelinks-input-group-cell imagelinks-rgap" al-if="appData.ui.activeMarker.tooltip.trigger != 'clickbody'">
																<div class="imagelinks-helper" title="<?php esc_html_e('Enable/disable tooltip follow the cursor as you hover over the marker', 'imagelinks'); ?>"></div>
																<div class="imagelinks-label"><?php esc_html_e('Follow the cursor', 'imagelinks'); ?></div>
																<div al-toggle="appData.ui.activeMarker.tooltip.followCursor"></div>
															</div>
															<div class="imagelinks-input-group-cell">
																<div class="imagelinks-helper" title="<?php esc_html_e('The tooltip won\'t hide when you hover over or click on them', 'imagelinks'); ?>"></div>
																<div class="imagelinks-label"><?php esc_html_e('Interactive', 'imagelinks'); ?></div>
																<div al-toggle="appData.ui.activeMarker.tooltip.interactive"></div>
															</div>
														</div>
													</div>
													
													<div class="imagelinks-control">
														<div class="imagelinks-input-group imagelinks-long">
															<div class="imagelinks-input-group-cell imagelinks-rgap">
																<div class="imagelinks-helper" title="<?php esc_html_e('The tooltip size depends on the image size', 'imagelinks'); ?>"></div>
																<div class="imagelinks-label"><?php esc_html_e('Responsive', 'imagelinks'); ?></div>
																<div al-toggle="appData.ui.activeMarker.tooltip.responsive"></div>
															</div>
															<div class="imagelinks-input-group-cell imagelinks-lgap">
																<div class="imagelinks-helper" title="<?php esc_html_e('Determines if the tooltip is placed within the viewport as best it can be if there is not enough space', 'imagelinks'); ?>"></div>
																<div class="imagelinks-label"><?php esc_html_e('Smart', 'imagelinks'); ?></div>
																<div al-toggle="appData.ui.activeMarker.tooltip.smart"></div>
															</div>
														</div>
													</div>
													
													<div class="imagelinks-control">
														<div class="imagelinks-input-group imagelinks-long">
															<div class="imagelinks-input-group-cell imagelinks-rgap" al-attr.class.imagelinks-nogap="appData.ui.activeMarker.tooltip.widthFromCSS">
																<div class="imagelinks-helper" title="<?php esc_html_e('If true, the tooltip width will be taken from CSS rules, dont forget to define them', 'imagelinks'); ?>"></div>
																<div class="imagelinks-label"><?php esc_html_e('Width from CSS', 'imagelinks'); ?></div>
																<div al-toggle="appData.ui.activeMarker.tooltip.widthFromCSS"></div>
															</div>
															<div class="imagelinks-input-group-cell imagelinks-lgap" al-if="!appData.ui.activeMarker.tooltip.widthFromCSS">
																<div class="imagelinks-helper" title="<?php esc_html_e('Specifies a tooltip width', 'imagelinks'); ?>"></div>
																<div class="imagelinks-label"><?php esc_html_e('Width [px]', 'imagelinks'); ?></div>
																<input class="imagelinks-number imagelinks-long" al-integer="appData.ui.activeMarker.tooltip.width" placeholder="<?php esc_html_e('auto', 'imagelinks'); ?>">
															</div>
														</div>
													</div>
													
													<div class="imagelinks-control">
														<div class="imagelinks-helper" title="<?php esc_html_e('The tooltip will be shown immediately once the instance is created', 'imagelinks'); ?>"></div>
														<div class="imagelinks-label"><?php esc_html_e('Show on init', 'imagelinks'); ?></div>
														<div al-toggle="appData.ui.activeMarker.tooltip.showOnInit"></div>
													</div>
													
													<div class="imagelinks-control">
														<div class="imagelinks-helper" title="<?php esc_html_e('Sets additional css classes to the tooltip', 'imagelinks'); ?>"></div>
														<div class="imagelinks-label"><?php esc_html_e('Additional CSS classes', 'imagelinks'); ?></div>
														<input class="imagelinks-number imagelinks-long" type="text" al-text="appData.ui.activeMarker.tooltip.className">
													</div>
												</div>
											</div>
										</div>
										
										<div class="imagelinks-block imagelinks-block-flat" al-attr.class.imagelinks-block-folded="appData.ui.tooltipSections.animation">
											<div class="imagelinks-block-header" al-on.click="appData.fn.onTooltipSection(appData,'animation')">
												<div class="imagelinks-block-title"><?php esc_html_e('Animation', 'imagelinks'); ?></div>
												<div class="imagelinks-block-state"></div>
											</div>
											<div class="imagelinks-block-data">
												<div al-if="appData.ui.activeMarker != null">
													<div class="imagelinks-control">
														<div class="imagelinks-input-group imagelinks-long">
															<div class="imagelinks-input-group-cell imagelinks-rgap">
																<div class="imagelinks-helper" title="<?php esc_html_e('Select a show animation effect for the tooltip from the list or write your own', 'imagelinks'); ?>"></div>
																<div class="imagelinks-label"><?php esc_html_e('Show animation', 'imagelinks'); ?></div>
																<div class="imagelinks-input-group imagelinks-long">
																	<div class="imagelinks-input-group-cell">
																		<input class="imagelinks-text imagelinks-long" type="text" al-text="appData.ui.activeMarker.tooltip.showAnimation">
																	</div>
																	<div class="imagelinks-input-group-cell imagelinks-pinch">
																		<div class="imagelinks-btn imagelinks-default imagelinks-no-bl" al-on.click="appData.fn.selectShowAnimation(appData, appData.ui.activeMarker.tooltip)" title="<?php esc_html_e('Select an effect', 'imagelinks'); ?>"><span><i class="imagelinks-icon imagelinks-icon-select"></i></span></div>
																	</div>
																</div>
															</div>
															<div class="imagelinks-input-group-cell imagelinks-lgap">
																<div class="imagelinks-helper" title="<?php esc_html_e('Select a hide animation effect for the tooltip from the list or write your own', 'imagelinks'); ?>"></div>
																<div class="imagelinks-label"><?php esc_html_e('Hide animation', 'imagelinks'); ?></div>
																<div class="imagelinks-input-group imagelinks-long">
																	<div class="imagelinks-input-group-cell">
																		<input class="imagelinks-text imagelinks-long" type="text" al-text="appData.ui.activeMarker.tooltip.hideAnimation">
																	</div>
																	<div class="imagelinks-input-group-cell imagelinks-pinch">
																		<div class="imagelinks-btn imagelinks-default imagelinks-no-bl" al-on.click="appData.fn.selectHideAnimation(appData, appData.ui.activeMarker.tooltip)" title="<?php esc_html_e('Select an effect', 'imagelinks'); ?>"><span><i class="imagelinks-icon imagelinks-icon-select"></i></span></div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													
													<div class="imagelinks-control">
														<div class="imagelinks-helper" title="<?php esc_html_e('Sets animation duration for show and hide effects', 'imagelinks'); ?>"></div>
														<div class="imagelinks-label"><?php esc_html_e('Duration [ms]', 'imagelinks'); ?></div>
														<input class="imagelinks-number imagelinks-long" al-integer="appData.ui.activeMarker.tooltip.duration">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="imagelinks-section" al-attr.class.imagelinks-active="appData.ui.tabs.customCSS" al-if="appData.ui.tabs.customCSS">
						<div class="imagelinks-stage">
							<div class="imagelinks-main-panel">
								<div class="imagelinks-data imagelinks-active">
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Enable/disable custom styles', 'imagelinks'); ?>"></div>
										<div class="imagelinks-input-group">
											<div class="imagelinks-input-group-cell imagelinks-pinch">
												<div al-toggle="appData.config.customCSS.active"></div>
											</div>
											<div class="imagelinks-input-group-cell">
												<div class="imagelinks-label"><?php esc_html_e('Enable styles', 'imagelinks'); ?></div>
											</div>
										</div>
									</div>
									<div class="imagelinks-control">
										<pre id="imagelinks-notepad-css" class="imagelinks-notepad"></pre>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="imagelinks-section" al-attr.class.imagelinks-active="appData.ui.tabs.customJS" al-if="appData.ui.tabs.customJS">
						<div class="imagelinks-stage">
							<div class="imagelinks-main-panel">
								<div class="imagelinks-data imagelinks-active">
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Enable/disable custom javascript code', 'imagelinks'); ?>"></div>
										<div class="imagelinks-input-group">
											<div class="imagelinks-input-group-cell imagelinks-pinch">
												<div al-toggle="appData.config.customJS.active"></div>
											</div>
											<div class="imagelinks-input-group-cell">
												<div class="imagelinks-label"><?php esc_html_e('Enable javascript code', 'imagelinks'); ?></div>
											</div>
										</div>
									</div>
									<div class="imagelinks-control">
										<pre id="imagelinks-notepad-js" class="imagelinks-notepad"></pre>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="imagelinks-section" al-attr.class.imagelinks-active="appData.ui.tabs.shortcode" al-if="appData.wp_item_id">
						<div class="imagelinks-main-panel">
							<div class="imagelinks-data imagelinks-active">
								<h3><?php esc_html_e('Use a shortcode like the one below, simply copy and paste it into a post or page.', 'imagelinks'); ?></h3>
								
								<div class="imagelinks-control">
									<div class="imagelinks-label"><?php esc_html_e('Standard shortcode', 'imagelinks'); ?></div>
									<div class="imagelinks-input-group">
										<div class="imagelinks-input-group-cell">
											<input id="imagelinks-shortcode-1" class="imagelinks-text imagelinks-long" type="text" value='[imagelinks id="{{appData.wp_item_id}}"]' readonly="readonly">
										</div>
										<div class="imagelinks-input-group-cell imagelinks-pinch">
											<div class="imagelinks-btn imagelinks-default imagelinks-no-bl" al-on.click="appData.fn.copyToClipboard(appData, '#imagelinks-shortcode-1')" title="<?php esc_html_e('Copy to clipboard', 'imagelinks'); ?>"><span><i class="imagelinks-icon imagelinks-icon-copy"></i></span></div>
										</div>
									</div>
								</div>
								
								<div class="imagelinks-control ">
									<div class="imagelinks-label"><?php esc_html_e('Shortcode with custom CSS classes', 'imagelinks'); ?></div>
									<div class="imagelinks-input-group">
										<div class="imagelinks-input-group-cell">
											<input id="imagelinks-shortcode-2" class="imagelinks-text imagelinks-long" type="text" value='[imagelinks id="{{appData.wp_item_id}}" class="your-css-custom-class"]' readonly="readonly">
										</div>
										<div class="imagelinks-input-group-cell imagelinks-pinch">
											<div class="imagelinks-btn imagelinks-default imagelinks-no-bl" al-on.click="appData.fn.copyToClipboard(appData, '#imagelinks-shortcode-2')" title="<?php esc_html_e('Copy to clipboard', 'imagelinks'); ?>"><span><i class="imagelinks-icon imagelinks-icon-copy"></i></span></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="imagelinks-modals" class="imagelinks-modals">
		</div>
	</div>
	<!-- /end imagelinks app -->
</div>