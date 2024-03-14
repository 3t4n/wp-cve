<section>
	<div class="supsystic-item supsystic-panel">
		<div id="containerWrapper">
			<div id="gmpMgrTab" class="mgrTabContent">
				<a href="<?php echo esc_attr($this->_getPageLink('marker_groups_add_new'))?>" class="button button-table-action" id="addMarkerGroup" style="display: <?php echo $this->editMarkerGroup ? 'inline-block;' : 'none;'?>">
					<?php _e('Add Category', GMP_LANG_CODE)?>
				</a>
				<a href="<?php echo esc_attr($this->_getPageLink('marker_groups_options'))?>" class="button button-table-action" id="markerGroupOptions" style="display: <?php echo $this->editMarkerGroup ? 'inline-block;' : 'none;'?>">
					<?php _e('Options', GMP_LANG_CODE)?>
				</a>
				<button class="button" id="gmpMgrSaveBtn">
					<i class="fa fa-save"></i>
					<?php _e('Save', GMP_LANG_CODE)?>
				</button>
				<form id="gmpMgrForm">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="marker_group_title">
									<?php _e('Category Title', GMP_LANG_CODE)?>:
								</label>
							</th>
							<td>
								<?php echo htmlGmp::wpKsesHtml(htmlGmp::text('marker_group[title]', array(
									'value' => $this->editMarkerGroup ? esc_attr($this->marker_group['title']) : '',
									'attrs' => 'style="width: 50%;" id="marker_group_title"',
									'required' => true)))?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="marker_group_parent">
									<?php _e('Parent Category', GMP_LANG_CODE)?>:
								</label>
							</th>
							<td>
								<?php echo htmlGmp::wpKsesHtml(htmlGmp::selectbox('marker_group[parent]', array(
									'options' => $this->parentsList,
									'value' => $this->editMarkerGroup ? esc_attr($this->marker_group['parent']) : 0,
									'attrs' => 'style="width: 50%;" id="marker_group_parent"',
								)))?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="marker_group_bg_color">
									<?php _e('Background Color', GMP_LANG_CODE)?>:
								</label>
							</th>
							<td>
								<?php echo htmlGmp::wpKsesHtml(htmlGmp::colorpicker('marker_group[bg_color]', array(
									'value' => $this->editMarkerGroup && $this->marker_group['params']['bg_color'] ? esc_attr($this->marker_group['params']['bg_color']) : '#E4E4E4')))?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="marker_group_claster_icon">
									<?php _e('Cluster Icon', GMP_LANG_CODE)?>:
								</label>
							</th>
							<td>
								<?php
								$curMarkerGroupClusterIcon = uriGmp::_(
									$this->editMarkerGroup
									&& isset($this->marker_group['params']['claster_icon'])
									&& $this->marker_group['params']['claster_icon']
										? esc_attr($this->marker_group['params']['claster_icon'])
										: GMP_MODULES_PATH . 'gmap/img/m1.png');
								$curMarkerGroupClusterIconWidth =
									$this->editMarkerGroup
									&& isset($this->marker_group['params']['clasterer_icon_width'])
									&& $this->marker_group['params']['clasterer_icon_width']
										? esc_attr($this->marker_group['params']['clasterer_icon_width'])
										: 53;
								$curMarkerGroupClusterIconHeight =
									$this->editMarkerGroup
									&& isset($this->marker_group['params']['clasterer_icon_height'])
									&& $this->marker_group['params']['marker_clasterer_icon_height']
										? esc_attr($this->marker_group['params']['marker_clasterer_icon_height'])
										: 52;
								?>
								<img id="gmpMarkerGroupClastererIconPrevImg" class="gmpSubOpt" src="<?php echo esc_attr($curMarkerGroupClusterIcon) ?>" style="max-width: 53px; height: auto;" />
								<a id="gmpUploadMarkerGroupClastererIconBtn" class="button gmpSubOpt" href="#" ><?php _e('Upload Icon', GMP_LANG_CODE)?></a>
								<a id="gmpDefaultMarkerGroupClastererIconBtn" class="button gmpSubOpt" href="#" ><?php _e('Default Icon', GMP_LANG_CODE)?></a>
								<div class="gmpMarkerGroupClastererUplRes"></div>
								<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('marker_group[claster_icon]', array('value' => esc_attr($curMarkerGroupClusterIcon), )))?>
								<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('marker_group[claster_icon_width]', array('value' => esc_attr($curMarkerGroupClusterIconWidth), )))?>
								<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('marker_group[claster_icon_height]', array('value' => esc_attr($curMarkerGroupClusterIconHeight), )))?>
							</td>
						</tr>
					</table>
					<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('mod', array('value' => 'marker_groups')))?>
					<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('action', array('value' => 'save')))?>
					<?php echo htmlGmp::defaultNonceForAdminPanel(); ?>
					<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('marker_group[id]', array('value' => $this->editMarkerGroup ? esc_attr($this->marker_group['id']) : '')))?>
				</form>
			</div>
		</div>
	</div>
</section>
