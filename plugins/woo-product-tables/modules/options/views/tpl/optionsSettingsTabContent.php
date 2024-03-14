<section class="woobewoo-bar">
	<ul class="woobewoo-bar-controls">
		<li title="<?php echo esc_attr(__('Save all options', 'woo-product-tables')); ?>">
			<button class="button button-primary" id="wtbpSettingsSaveBtn" data-toolbar-button>
				<i class="fa fa-fw fa-save"></i>
				<?php esc_html_e('Save', 'woo-product-tables'); ?>
			</button>
		</li>
	</ul>
	<div class="woobewoo-clear"></div>
	<hr />
</section>
<section>
	<form id="wtbpSettingsForm" class="wtbpInputsWithDescrForm">
		<div class="woobewoo-item woobewoo-panel">
			<div id="containerWrapper">
				<table class="form-table">
					<?php foreach ($this->options as $optCatKey => $optCatData) { ?>
						<?php if (isset($optCatData['opts']) && !empty($optCatData['opts'])) { ?>
							<?php foreach ($optCatData['opts'] as $optKey => $opt) { ?>
								<?php
								$htmlType = isset($opt['html']) ? $opt['html'] : false;
								if (empty($htmlType)) {
									continue;
								}
								$htmlOpts = array('value' => $opt['value'], 'attrs' => 'data-optkey="' . esc_attr($optKey) . '"');
								if (in_array($htmlType, array('selectbox', 'selectlist')) && isset($opt['options'])) {
									if (is_callable($opt['options'])) {
										$htmlOpts['options'] = call_user_func( $opt['options'] );
									} elseif (is_array($opt['options'])) {
										$htmlOpts['options'] = $opt['options'];
									}
								}
								if (isset($opt['pro']) && !empty($opt['pro'])) {
									$htmlOpts['attrs'] .= ' class="wtbpProOpt"';
								}
								?>
								<tr
									<?php if (isset($opt['connect']) && $opt['connect']) { ?>
										data-connect="<?php echo esc_attr($opt['connect']); ?>" class="woobewoo-hidden"
									<?php } ?>
								>
									<th scope="row" class="col-w-30perc">
										<?php echo esc_html($opt['label']); ?>
										<?php if (!empty($opt['changed_on'])) { ?>
											<br />
											<span class="description">
												<?php 
												if ($opt['value']) {
													/* translators: %s: label */
													echo esc_html(sprintf(__('Turned On %s', 'woo-product-tables'), DateWtbp::_($opt['changed_on'])));
												} else {
													/* translators: %s: label */
													echo esc_html(sprintf(__('Turned Off %s', 'woo-product-tables'), DateWtbp::_($opt['changed_on'])));
												}
												?>
											</span>
										<?php } ?>
										<?php if (isset($opt['pro']) && !empty($opt['pro'])) { ?>
											<span class="wtbpProOptMiniLabel">
												<a href="<?php echo esc_url($opt['pro']); ?>" target="_blank">
													<?php esc_html_e('PRO option', 'woo-product-tables'); ?>
												</a>
											</span>
										<?php } ?>
									</th>
									<td class="col-w-1perc">
										<i class="fa fa-question woobewoo-tooltip" title="<?php echo esc_attr($opt['desc']); ?>"></i>
									</td>
									<td class="col-w-1perc">
										<?php HtmlWtbp::$htmlType('opt_values[' . $optKey . ']', $htmlOpts); ?>
									</td>
									<td class="col-w-60perc">
										<div id="wtbpFormOptDetails_<?php echo esc_attr($optKey); ?>" class="wtbpOptDetailsShell">
										<?php
										if (isset($opt['add_sub_opts']) && !empty($opt['add_sub_opts'])) {
											if (is_string($opt['add_sub_opts'])) {
												HtmlWtbp::echoEscapedHtml($opt['add_sub_opts']);
											} elseif (is_callable($opt['add_sub_opts'])) {
												HtmlWtbp::echoEscapedHtml(call_user_func_array($opt['add_sub_opts'], array($this->options)));
											}
										}
										?>
										</div>
									</td>
								</tr>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</table>
				<div class="woobewoo-clear"></div>
			</div>
		</div>
		<?php HtmlWtbp::hidden('mod', array('value' => 'options')); ?>
		<?php HtmlWtbp::hidden('action', array('value' => 'saveGroup')); ?>
	</form>
</section>
