<?php
$isStatic = isset($this->currentMap['params']['is_static']) ? (int) $this->currentMap['params']['is_static'] : false;
//$popup = $this->currentMap['params']['map_display_mode'] == 'popup' ? true : false;

$viewId = esc_attr($this->currentMap['view_id']);
$mapHtmlId = esc_attr($this->currentMap['view_html_id']);
$mapPreviewClassname = @esc_attr($this->currentMap['html_options']['classname']);
//$mapOptsClassname = $popup ? 'display_as_popup' : '';
$markersDisplayType = $this->markersDisplayType === 'slider_checkbox_table';
$mapsWrapperStart = $markersDisplayType ? "<div class='gmpLeft'>" : '';
$mapsWrapperEnd = $markersDisplayType ? "</div>" : '';
$filtersWrapperStart = $markersDisplayType ? "<div class='filterRight'>" : '';
$filtersWrapperEnd = $markersDisplayType ? "</div>" : '';?>
<?php if($isStatic) { ?>
	<?php $canDrawStaticMap = (bool)(frameGmp::_()->getModule('supsystic_promo')->isPro()
			&& frameGmp::_()->getModule('add_map_options')
			&& method_exists(frameGmp::_()->getModule('add_map_options'), 'connectStaticMapCore'));
		/*	? frameGmp::_()->getModule('add_map_options')->generateStaticImgUrl($this->currentMap)
			: false;*/
		$title = esc_attr($this->currentMap['title']);
		$error = '';
		if(!$canDrawStaticMap) {
			// Detailed error message
			if(!frameGmp::_()->getModule('supsystic_promo')->isPro()) {
				$error = __('This feature available in PRO version. You can get it <a href="" target="_blank">here</a>.', GMP_LANG_CODE);
			} else {
				// PRO version exists - but there are no such functionality there - need to update
				$error = __('You need to upgrade PRO plugin to latest version to use this feature', GMP_LANG_CODE);
			}
			$title = $error;
		}
	?>
	<?php if(is_user_logged_in() && !empty($error)) { ?>
		<b><?php echo viewGmp::ksesString($error);?></b>
	<?php }?>
	<img id="<?php echo esc_attr($mapHtmlId); ?>" class="gmpMapImg gmpMapImg_<?php echo esc_attr($viewId); ?>"
		src="<?php echo GMP_IMG_PATH . 'gmap_preview.png'; ?>"
		data-id="<?php echo esc_attr($this->currentMap['id']); ?>" data-view-id="<?php echo esc_attr($viewId); ?>"
		title="<?php echo esc_attr($title); ?>" alt="<?php echo esc_attr($title); ?>"
	/>
<?php } else  { ?>
	<div class="gmp_map_opts" id="mapConElem_<?php echo esc_attr($viewId);?>"
		data-id="<?php echo esc_attr($this->currentMap['id']); ?>" data-view-id="<?php echo esc_attr($viewId);?>"
		<?php if(!empty($this->mbsIntegrating)) {
			echo 'data-mbs-gme-map="' . esc_attr($this->currentMap['id']) . '" style="display:none;"';
		} else if(!empty($this->mbsMapId) && !empty($this->mbsMapInfo)) {
			echo "data-mbs-gme-map-id='" . esc_attr($this->mbsMapId) . "' data-mbs-gme-map-info='" . esc_attr($this->mbsMapInfo) . "'";
		}
		?>
	>
		<?php echo htmlGmp::wpKsesHtml($mapsWrapperStart); ?>
		<div class="gmpMapDetailsContainer" id="gmpMapDetailsContainer_<?php echo esc_attr($viewId) ;?>">
			<i class="gmpKMLLayersPreloader fa fa-spinner fa-spin" aria-hidden="true" style="display: none;"></i>
			<div class="gmp_MapPreview <?php echo esc_attr($mapPreviewClassname);?>" id="<?php echo esc_attr($mapHtmlId) ;?>"></div>
			<?php dispatcherGmp::doAction('addMapCustomInfoWindow', $this->currentMap); ?>
		</div>
		<?php echo htmlGmp::wpKsesHtml($mapsWrapperEnd); ?>

		<?php echo htmlGmp::wpKsesHtml($filtersWrapperStart); ?>
		<div class="gmpMapMarkerFilters" id="gmpMapMarkerFilters_<?php echo esc_attr($viewId);?>">
			<?php dispatcherGmp::doAction('addMapFilters', $this->currentMap); ?>
		</div>
		<?php echo htmlGmp::wpKsesHtml($filtersWrapperEnd); ?>

		<div class="gmpMapProControlsCon" id="gmpMapProControlsCon_<?php echo esc_attr($viewId);?>">
			<?php dispatcherGmp::doAction('addMapBottomControls', $this->currentMap); ?>
		</div>

		<div class="gmpMapProDirectionsCon" id="gmpMapProDirectionsCon_<?php echo esc_attr($viewId);?>" >
			<?php dispatcherGmp::doAction('addMapDirectionsData', $this->currentMap); ?>
		</div>
		<div class="gmpMapProKmlFilterCon" id="gmpMapProKmlFilterCon_<?php echo esc_attr($viewId);?>" >
			<?php dispatcherGmp::doAction('addMapKmlFilterData', $this->currentMap); ?>
		</div>
		<div class="gmpSocialSharingShell gmpSocialSharingShell_<?php echo esc_attr($viewId);?>">
			<?php echo htmlGmp::wpKsesHtml($this->currentMap['params']['ss_html']);?>
		</div>

		<?php if (!empty($this->routeData['params'])) {?>
			<div class="gmpRouterData">
				<input type="hidden" id="route_opts_start_point_address" value="<?php echo esc_attr($this->routeData['params']['start_point_address']);?>">
				<input type="hidden" id="route_opts_end_point_address" value="<?php echo esc_attr($this->routeData['params']['end_point_address']);?>">
				<input type="hidden" id="route_opts_start_point_desc" value="<?php echo esc_attr($this->routeData['params']['start_point_desc']);?>">
				<input type="hidden" id="route_opts_end_point_desc" value="<?php echo esc_attr($this->routeData['params']['end_point_desc']);?>">
				<?php if (!empty($this->routeData['params']['router_points_address'])) {?>
					<?php foreach ($this->routeData['params']['router_points_address'] as $key => $waypoint) {?>
						<div>
						<input type="hidden" class="router_points_title" value="<?php echo esc_attr($this->routeData['params']['router_points_title'][$key]);?>">
						<input type="hidden" class="router_points_address" value="<?php echo esc_attr($waypoint);?>">
						</div>
					<?php }?>
				<?php }?>
			</div>
		<?php }?>

		<div style="clear: both;"></div>
	</div>
<?php } ?>
