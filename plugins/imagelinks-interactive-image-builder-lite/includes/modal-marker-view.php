<?php
defined('ABSPATH') || exit;
?>
<div id="imagelinks-modal-{{ modalData.id }}" class="imagelinks-modal" tabindex="-1">
	<div class="imagelinks-modal-dialog">
		<div class="imagelinks-modal-header">
			<div class="imagelinks-modal-close" al-on.click="modalData.deferred.resolve('close');">&times;</div>
			<div class="imagelinks-modal-title"><?php esc_html_e('Edit the marker view', 'imagelinks'); ?></div>
		</div>
		<div class="imagelinks-modal-data">
			<div class="imagelinks-control">
				<div class="imagelinks-marker-canvas-wrap">
					<div class="imagelinks-marker-canvas">
						<div class="imagelinks-marker-wrap">
							<div class="imagelinks-marker-pulse" al-attr.class.imagelinks-active="modalData.marker.view.pulse.active">
							</div>
							<div class="imagelinks-marker"
								 al-style.width="modalData.appData.fn.getMarkerStyle(modalData.appData, modalData.marker, 'width')"
								 al-style.height="modalData.appData.fn.getMarkerStyle(modalData.appData, modalData.marker, 'height')"
								 al-init="modalData.fn.initMarker(modalData, $element)"
							>
								<div class="imagelinks-marker-icon-wrap"
									 al-style.color="modalData.appData.fn.getIconStyle(modalData.appData, modalData.marker.view.icon, 'color')"
									 al-style.font-size="modalData.appData.fn.getIconStyle(modalData.appData, modalData.marker.view.icon, 'font-size')"
								>
									<div class="imagelinks-marker-icon" al-if="modalData.marker.view.icon.name"><i class="fa {{modalData.marker.view.icon.name}}"></i></div>
									<div class="imagelinks-marker-icon-label" al-if="modalData.marker.view.icon.label">{{modalData.marker.view.icon.label}}</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-helper" title="<?php esc_html_e('Sets a marker title', 'imagelinks'); ?>"></div>
				<div class="imagelinks-label"><?php esc_html_e('Title', 'imagelinks'); ?></div>
				<input class="imagelinks-text imagelinks-long" type="text" al-text="modalData.marker.title">
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap" al-attr.class.imagelinks-nogap="modalData.marker.autoWidth">
						<div class="imagelinks-helper" title="<?php esc_html_e('Enable/disable auto marker width', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Auto width', 'imagelinks'); ?></div>
						<div al-toggle="modalData.marker.autoWidth"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap" al-if="!modalData.marker.autoWidth">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a marker width in px', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Width [px]', 'imagelinks'); ?></div>
						<input class="imagelinks-number imagelinks-long" al-integer="modalData.marker.width">
					</div>
				</div>
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap" al-attr.class.imagelinks-nogap="modalData.marker.autoHeight">
						<div class="imagelinks-helper" title="<?php esc_html_e('Enable/disable auto marker heihgt', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Auto height', 'imagelinks'); ?></div>
						<div al-toggle="modalData.marker.autoHeight"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap" al-if="!modalData.marker.autoHeight">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a marker height in px', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Height [px]', 'imagelinks'); ?></div>
						<input class="imagelinks-number imagelinks-long" al-integer="modalData.marker.height">
					</div>
				</div>
			</div>
			
			<!-- responsive & noevents -->
			<div class="imagelinks-control">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('The marker size depends on the image size', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Responsive', 'imagelinks'); ?></div>
						<div al-toggle="modalData.marker.responsive"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('The marker is never the target of mouse events', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('No events', 'imagelinks'); ?></div>
						<div al-toggle="modalData.marker.noevents"></div>
					</div>
				</div>
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a marker icon', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Icon name', 'imagelinks'); ?></div>
						<div class="imagelinks-input-group imagelinks-long">
							<div class="imagelinks-input-group-cell">
								<input class="imagelinks-text imagelinks-long" type="text" al-text="modalData.marker.view.icon.name" placeholder="<?php esc_html_e('Select an icon', 'imagelinks'); ?>">
							</div>
							<div class="imagelinks-input-group-cell imagelinks-pinch">
								<div class="imagelinks-btn imagelinks-default imagelinks-no-bl" al-on.click="modalData.appData.fn.selectIcon(modalData.appData, modalData.rootScope, modalData.marker.view.icon)"><span><i class="imagelinks-icon imagelinks-icon-select"></i></span></div>
							</div>
						</div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets an icon label', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Icon label', 'imagelinks'); ?></div>
						<input class="imagelinks-text imagelinks-long" type="text" al-text="modalData.marker.view.icon.label">
					</div>
				</div>
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets an icon color', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Icon color', 'imagelinks'); ?></div>
						<div class="imagelinks-color imagelinks-long" al-color="modalData.marker.view.icon.color"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets an icon size', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Icon size', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.icon.size"></div>
					</div>
				</div>
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-helper" title="<?php esc_html_e('Sets an icon margin', 'imagelinks'); ?>"></div>
				<div class="imagelinks-label"><?php esc_html_e('Icon margin', 'imagelinks'); ?></div>
				<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.icon.margin.all"></div>
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a top icon margin', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('top', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.icon.margin.top"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a right icon margin', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('right', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.icon.margin.right"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a bottom icon margin', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('bottom', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.icon.margin.bottom"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a left icon margin', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('left', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.icon.margin.left"></div>
					</div>
				</div>
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-helper" title="<?php esc_html_e('Sets a background image (jpeg or png format)', 'imagelinks'); ?>"></div>
				<div class="imagelinks-label"><?php esc_html_e('Background image', 'imagelinks'); ?></div>
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell">
						<input class="imagelinks-text imagelinks-long" type="text" al-text="modalData.marker.view.background.image.url" placeholder="<?php esc_html_e('Select an image', 'imagelinks'); ?>">
					</div>
					<div class="imagelinks-input-group-cell imagelinks-pinch">
						<div class="imagelinks-btn imagelinks-default imagelinks-no-bl" al-on.click="modalData.appData.fn.selectImage(modalData.appData, modalData.rootScope, modalData.marker.view.background.image)"><span><i class="imagelinks-icon imagelinks-icon-select"></i></span></div>
					</div>
				</div>
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-pinch">
						<div al-checkbox="modalData.marker.view.background.image.relative"></div>
					</div>
					<div class="imagelinks-input-group-cell">
						<?php esc_html_e('Use relative path', 'imagelinks'); ?>
					</div>
				</div>
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a background color', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Background color', 'imagelinks'); ?></div>
						<div class="imagelinks-color imagelinks-long" al-color="modalData.marker.view.background.color"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('How a background image will be repeated', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Background repeat', 'imagelinks'); ?></div>
						<div class="imagelinks-select imagelinks-long" al-backgroundrepeat="modalData.marker.view.background.repeat"></div>
					</div>
				</div>
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Specifies a size of the background image', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Background size', 'imagelinks'); ?></div>
						<div class="imagelinks-select imagelinks-long" al-backgroundsize="modalData.marker.view.background.size"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a starting position of the background image', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Background position', 'imagelinks'); ?></div>
						<input class="imagelinks-text imagelinks-long" type="text" al-text="modalData.marker.view.background.position" placeholder="<?php esc_html_e('Example: 50% 50%', 'imagelinks'); ?>">
					</div>
				</div>
			</div>
			
			<!-- border begin -->
			<div class="imagelinks-control">
				<div class="imagelinks-border-tabs">
					<div class="imagelinks-tab-all" al-attr.class.imagelinks-active="modalData.appData.ui.borderTabs.all" al-on.click="modalData.appData.fn.onBorderTab(modalData.appData,'all')" al-attr.class.imagelinks-enable="modalData.marker.view.border.all.active"><?php esc_html_e('All', 'imagelinks'); ?></div>
					<div class="imagelinks-tab-top" al-attr.class.imagelinks-active="modalData.appData.ui.borderTabs.top" al-on.click="modalData.appData.fn.onBorderTab(modalData.appData,'top')" al-attr.class.imagelinks-enable="modalData.marker.view.border.top.active"><?php esc_html_e('Top', 'imagelinks'); ?></div>
					<div class="imagelinks-tab-right" al-attr.class.imagelinks-active="modalData.appData.ui.borderTabs.right" al-on.click="modalData.appData.fn.onBorderTab(modalData.appData,'right')" al-attr.class.imagelinks-enable="modalData.marker.view.border.right.active"><?php esc_html_e('Right', 'imagelinks'); ?></div>
					<div class="imagelinks-tab-bottom" al-attr.class.imagelinks-active="modalData.appData.ui.borderTabs.bottom" al-on.click="modalData.appData.fn.onBorderTab(modalData.appData,'bottom')" al-attr.class.imagelinks-enable="modalData.marker.view.border.bottom.active"><?php esc_html_e('Bottom', 'imagelinks'); ?></div>
					<div class="imagelinks-tab-left" al-attr.class.imagelinks-active="modalData.appData.ui.borderTabs.left" al-on.click="modalData.appData.fn.onBorderTab(modalData.appData,'left')" al-attr.class.imagelinks-enable="modalData.marker.view.border.left.active"><?php esc_html_e('Left', 'imagelinks'); ?></div>
				</div>
			</div>
			
			<!-- border all -->
			<div class="imagelinks-control" al-if="modalData.appData.ui.borderTabs.all">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-pinch">
						<div al-checkbox="modalData.marker.view.border.all.active"></div>
					</div>
					<div class="imagelinks-input-group-cell">
						<?php esc_html_e('Enable border', 'imagelinks'); ?>
					</div>
				</div>
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border color', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border color', 'imagelinks'); ?></div>
						<div class="imagelinks-color imagelinks-long" al-color="modalData.marker.view.border.all.color"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border style', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border style', 'imagelinks'); ?></div>
						<div class="imagelinks-select imagelinks-long" al-borderstyle="modalData.marker.view.border.all.style"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border width', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border width', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.border.all.width"></div>
					</div>
				</div>
			</div>
			
			<!-- border top -->
			<div class="imagelinks-control" al-if="modalData.appData.ui.borderTabs.top">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-pinch">
						<div al-checkbox="modalData.marker.view.border.top.active"></div>
					</div>
					<div class="imagelinks-input-group-cell">
						<?php esc_html_e('Enable top border', 'imagelinks'); ?>
					</div>
				</div>
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border color', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border color', 'imagelinks'); ?></div>
						<div class="imagelinks-color imagelinks-long" al-color="modalData.marker.view.border.top.color"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border style', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border style', 'imagelinks'); ?></div>
						<div class="imagelinks-select imagelinks-long" al-borderstyle="modalData.marker.view.border.top.style"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border width', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border width', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.border.top.width"></div>
					</div>
				</div>
			</div>
			
			<!-- border right -->
			<div class="imagelinks-control" al-if="modalData.appData.ui.borderTabs.right">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-pinch">
						<div al-checkbox="modalData.marker.view.border.right.active"></div>
					</div>
					<div class="imagelinks-input-group-cell">
						<?php esc_html_e('Enable right border', 'imagelinks'); ?>
					</div>
				</div>
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border color', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border color', 'imagelinks'); ?></div>
						<div class="imagelinks-color imagelinks-long" al-color="modalData.marker.view.border.right.color"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border style', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border style', 'imagelinks'); ?></div>
						<div class="imagelinks-select imagelinks-long" al-borderstyle="modalData.marker.view.border.right.style"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border width', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border width', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.border.right.width"></div>
					</div>
				</div>
			</div>
			
			<!-- border bottom -->
			<div class="imagelinks-control" al-if="modalData.appData.ui.borderTabs.bottom">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-pinch">
						<div al-checkbox="modalData.marker.view.border.bottom.active"></div>
					</div>
					<div class="imagelinks-input-group-cell">
						<?php esc_html_e('Enable bottom border', 'imagelinks'); ?>
					</div>
				</div>
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border color', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border color', 'imagelinks'); ?></div>
						<div class="imagelinks-color imagelinks-long" al-color="modalData.marker.view.border.bottom.color"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border style', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border style', 'imagelinks'); ?></div>
						<div class="imagelinks-select imagelinks-long" al-borderstyle="modalData.marker.view.border.bottom.style"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border width', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border width', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.border.bottom.width"></div>
					</div>
				</div>
			</div>
			
			<!-- border left -->
			<div class="imagelinks-control" al-if="modalData.appData.ui.borderTabs.left">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-pinch">
						<div al-checkbox="modalData.marker.view.border.left.active"></div>
					</div>
					<div class="imagelinks-input-group-cell">
						<?php esc_html_e('Enable left border', 'imagelinks'); ?>
					</div>
				</div>
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border color', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border color', 'imagelinks'); ?></div>
						<div class="imagelinks-color imagelinks-long" al-color="modalData.marker.view.border.left.color"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border style', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border style', 'imagelinks'); ?></div>
						<div class="imagelinks-select imagelinks-long" al-borderstyle="modalData.marker.view.border.left.style"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border width', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Border width', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.border.left.width"></div>
					</div>
				</div>
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border radius', 'imagelinks'); ?>"></div>
				<div class="imagelinks-label"><?php esc_html_e('Border radius', 'imagelinks'); ?></div>
				<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.border.radius.all"></div>
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border top-left radius', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('top-left', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.border.radius.topLeft"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border top-right radius', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('top-right', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.border.radius.topRight"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border bottom-right radius', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('bottom-right', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.border.radius.bottomRight"></div>
						
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a border bottom-left radius', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('bottom-left', 'imagelinks'); ?></div>
						<div class="imagelinks-unit imagelinks-long" al-unit="modalData.marker.view.border.radius.bottomLeft"></div>
					</div>
				</div>
			</div>
			<!-- border end -->
			
			<div class="imagelinks-control">
				<div class="imagelinks-helper" title="<?php esc_html_e('Sets additional css classes to the marker', 'imagelinks'); ?>"></div>
				<div class="imagelinks-label"><?php esc_html_e('Additional CSS class', 'imagelinks'); ?></div>
				<input class="imagelinks-number imagelinks-long" type="text" al-text="modalData.marker.className">
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-helper" title="<?php esc_html_e('Enable/disable pulse animation', 'imagelinks'); ?>"></div>
				<div class="imagelinks-label"><?php esc_html_e('Enable pulse', 'imagelinks'); ?></div>
				<div al-toggle="modalData.marker.view.pulse.active"></div>
			</div>
			
			<div class="imagelinks-control">
				<div class="imagelinks-input-group imagelinks-long">
					<div class="imagelinks-input-group-cell imagelinks-rgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a pulse animation color', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Pulse color', 'imagelinks'); ?></div>
						<div class="imagelinks-color imagelinks-long" al-color="modalData.marker.view.pulse.color"></div>
					</div>
					<div class="imagelinks-input-group-cell imagelinks-lgap">
						<div class="imagelinks-helper" title="<?php esc_html_e('Sets a pulse animation duration', 'imagelinks'); ?>"></div>
						<div class="imagelinks-label"><?php esc_html_e('Pulse duration [ms]', 'imagelinks'); ?></div>
						<input class="imagelinks-number imagelinks-long" al-integer="modalData.marker.view.pulse.duration">
					</div>
				</div>
			</div>
		</div>
		<div class="imagelinks-modal-footer">
			<div class="imagelinks-modal-btn imagelinks-modal-btn-close" al-on.click="modalData.deferred.resolve('close');"><?php esc_html_e('Close', 'imagelinks'); ?></div>
			<div class="imagelinks-modal-btn imagelinks-modal-btn-create" al-on.click="modalData.deferred.resolve(true);"><?php esc_html_e('Save', 'imagelinks'); ?></div>
		</div>
	</div>
</div>