<div class="popupally-setting-div {{selected_item_opened}}" id="popupally-display-div-{{id}}">
	<div class="popupally-header popupally-header-icon" toggle-target="#display-toggle-{{id}}" id="popupally-display-header-{{id}}">
		<div class="view-toggle-block">
			<input name="[{{id}}][is-open]" {{selected_item_checked}} type="checkbox" value="true" toggle-group="display"
				   toggle-class="popupally-item-opened" toggle-element="#popupally-display-div-{{id}}" min-height="40" min-height-element="#popupally-display-header-{{id}}"
				   popupally-change-source="display-toggle-{{id}}" id="display-toggle-{{id}}" popup-id="{{id}}">
			<label hide-toggle="is-open" data-dependency="display-toggle-{{id}}" data-dependency-value="false">&#x25BC;</label>
			<label hide-toggle="is-open" data-dependency="display-toggle-{{id}}" data-dependency-value="true">&#x25B2;</label>
		</div>
		<div class="popupally-name-display-block">
			<div class="popupally-name-display" hide-toggle data-dependency="edit-name-display-{{id}}" data-dependency-value="display">
				<table class="popupally-header-table">
					<tbody>
						<tr>
							<td class="popupally-number-col">{{id}}. </td>
							<td class="popupally-name-label-col"><div class="popupally-name-label" name-sync-text="{{id}}">{{name}}</div></td>
							<td class="popupally-name-edit-col"><div class="pencil-icon" click-value="edit" click-target="#edit-name-display-{{id}}"></div></td>
						</tr>
					</tbody>
				</table>
			</div>
			<input type="hidden" id="edit-name-display-{{id}}" popupally-change-source="edit-name-display-{{id}}" value="display" />
			<input class="popupally-name-edit full-width" name-sync-val="{{id}}" style="display:none;"
				   hide-toggle data-dependency="edit-name-display-{{id}}" data-dependency-value="edit" value="{{name}}" />
		</div>
	</div>
	<div hide-toggle="is-open" data-dependency="display-toggle-{{id}}" data-dependency-value="true">
		<div class="popupally-setting-section">
			<input type="hidden" id="popupally-display-type-{{id}}" value="popup" />
			<table class="popupally-display-type-container">
				<tbody>
					<tr class="popupally-display-type-top-row">
						<td id="popupally-display-type-popup-{{id}}" class="popupally-display-type-tab-label-col popupally-display-type-tab-active"
							click-target="#popupally-display-type-{{id}}" click-value="popup" tab-group="popupally-display-type-{{id}}"
							target="popup" active-class="popupally-display-type-tab-active">
							Popup
						</td>
						<td id="popupally-display-type-popup-{{id}}" class="popupally-display-type-tab-label-col"
							click-target="#popupally-display-type-{{id}}" click-value="embed" tab-group="popupally-display-type-{{id}}"
							target="embed" active-class="popupally-display-type-tab-active">
							Embedded opt-in
						</td>
					</tr>
					<tr>
						<td colspan="2" class="popupally-display-type-content-cell">
							<div class="popupally-sub-setting-content-container" popupally-display-type-{{id}}="popup" style="display: block;">
								<div class="popupally-setting-section">
									<div class="popupally-setting-section-header">What kind of popup will this be?</div>
									<div class="popupally-setting-section-help-text">the first popup you assign to a page will take priority over future popups of the same trigger-type (but you can have multiple different types per page)</div>
									<div class="popupally-setting-configure-block">
										<table class="popupally-setting-configure-table">
											<tbody>
												<tr>
													<td class="popupally-setting-configure-table-left-col">
														<input class="popupally-setting-configure-checkbox" type="checkbox" input-all-false-check="popupally-conditional-display-{{id}}"
															   popupally-change-source="exit-intent-{{id}}" id="exit-intent-{{id}}" name="[{{id}}][enable-exit-intent-popup]" {{enable-exit-intent-popup}}
															   value="true"/>
													</td>
													<td class="popupally-setting-configure-table-right-col">
														<div>
															<label for="exit-intent-{{id}}" popup-id="{{id}}">Exit-intent popup</label>
														</div>
													</td>
												</tr>
												<tr hide-toggle="enable-exit-intent-popup" data-dependency="exit-intent-{{id}}" data-dependency-value="true">
													<td colspan="2">
														<div class="popupally-inline-help-text">An Exit-intent popup appears right before someone is about to leave. This is the best option to capture subscribers.</div>
													</td>
												</tr>
												<tr>
													<td class="popupally-setting-configure-table-left-col">
														<input class="popupally-setting-configure-checkbox" type="checkbox" input-all-false-check="popupally-conditional-display-{{id}}" popupally-change-source="timed-{{id}}" id="timed-{{id}}" name="[{{id}}][timed]" {{timed}} value="true"/>
													</td>
													<td>
														<div>
															<label for="timed-{{id}}" popup-id="{{id}}">Time-delayed popup</label>
														</div>
														<table hide-toggle="timed" data-dependency="timed-{{id}}" data-dependency-value="true">
															<tbody>
																<tr>
																	<td style="width:60%;">
																		<div>Show after <input type="text" size="4" name="[{{id}}][timed-popup-delay]" value="{{timed-popup-delay}}"/> seconds</div>
																	</td>
																	<td><div class="popupally-inline-help-text">-1 to disable; 0 to show immediately on load</div></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr hide-toggle="timed" data-dependency="timed-{{id}}" data-dependency-value="true">
													<td colspan="2">
														<div class="popupally-inline-help-text">A time-delay popup appears after a set delay. This is the most common popup, but also most annoying to visitors.</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<input type="hidden" name="[{{id}}][priority]" value="{{priority}}" />
								</div>
							</div>
							<div class="popupally-sub-setting-content-container"
								 popupally-display-type-{{id}}="embed" style="display: none;">
								<div class="popupally-setting-section">
									<div class="popupally-setting-section-header">Make this opt-in a part of the page?</div>
									<div class="popupally-setting-section-help-text">when embedded, the opt-in is displayed as part of the page layout instead of a popup.</div>
									<table class="popupally-setting-configure-table">
										<tbody>
											<tr>
												<td class="popupally-setting-configure-table-left-col">
													<input style="margin-right:10px" type="checkbox" input-all-false-check="popupally-conditional-display-{{id}}" popupally-change-source="embedded-{{id}}" id="embedded-{{id}}" name="[{{id}}][enable-embedded]" {{enable-embedded}} value="true"/>
												</td>
												<td>
													<div><label for="embedded-{{id}}" popup-id="{{id}}">Embedded sign up (directly display the opt-in on the page/post, NOT as a popup)</label></div>
													<div hide-toggle="enable-embedded" data-dependency="embedded-{{id}}" data-dependency-value="true">
														Show sign up box at
														<select popupally-change-source="embedded-location-{{id}}" name="[{{id}}][embedded-location]">
															<option s--embedded-location--none--d value="none">None</option>
															<option s--embedded-location--post-start--d value="post-start">start of post/page content</option>
															<option s--embedded-location--post-end--d value="post-end">end of post/page content</option>
															<option s--embedded-location--page-end--d value="page-end">bottom of the page (not-follow)</option>
														</select>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="popupally-setting-section popupally-conditional-display-{{id}}" {{display-page-selection}}>
			<div class="popupally-setting-section-header">Advanced options</div>
			<div class="popupally-setting-configure-block">
				Want even more customizations?
				<a class="popupally-trial-button" href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank"><span class="popupally-click-arrow"></span>Try PopupAlly Pro for $1!</a>
			</div>
		</div>

		<div class="popupally-setting-section popupally-conditional-display-{{id}}" {{display-page-selection}}>
			<div>
				<div class="popupally-setting-section-header">Show this popup on which posts/pages?</div>
				<div class="popupally-setting-section-help-text">select which posts or pages you'd like this popup to appear on</div>
				<div class="popupally-setting-configure-block">
					<table class="popupally-setting-configure-table">
						<tbody>
							<tr>
								<td class="popupally-setting-configure-table-left-col">
									<input type="checkbox" popupally-change-source="show-all-{{id}}" id="show-all-{{id}}" name="[{{id}}][show-all]" {{show-all}} value="true"/>
								</td>
								<td>
									<div>
										<label for="show-all-{{id}}">Show for all posts and pages sitewide?</label>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="popupally-setting-section-sub-header" hide-toggle="show-all" data-dependency="show-all-{{id}}" data-dependency-value="false">Use for only these posts/pages</div>
				<div class="popupally-setting-section-sub-header" hide-toggle="show-all" data-dependency="show-all-{{id}}" data-dependency-value="true">Do NOT use for these posts/pages</div>
				<div class="popupally-setting-section-help-text popupally-warning" hide-toggle="show-all" data-dependency="show-all-{{id}}" data-dependency-value="true">the popup will NOT be shown for the pages/posts selected, ie. if "All Pages" and "All Posts" are checked, this popup will appear for NONE of the pages/posts.</div>
				<table id="page-settings-{{id}}" class="popupally-setting-page-list-table">
					<tbody>
						<tr valign="top" hide-toggle="show-all" data-dependency="show-all-{{id}}" data-dependency-value="false">
							{{include-selection}}
						</tr>
						<tr valign="top" hide-toggle="show-all" data-dependency="show-all-{{id}}" data-dependency-value="true">
							{{exclude-selection}}
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="popupally-setting-section popupally-conditional-display-{{id}}" {{display-page-selection}}>
			<div class="popupally-setting-section-header">How to stop showing this popup</div>
			<div class="popupally-setting-section-help-text">so you don't annoy your site visitors, use these settings to set how often to show your popup</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Show popup every</div>
				<table class="popupally-setting-configure-table">
					<tbody>
						<tr>
							<td style="width:20%;">
								<div><input name="[{{id}}][cookie-duration]" type="text" size="4" value="{{cookie-duration}}"> days</div>
							</td>
							<td>
								<div class="popupally-inline-help-text">
									<ul>
										<li>-1: re-appear on every refresh/new page load. <strong>For testing ONLY!</strong></li>
										<li>0: re-appear after closing and re-opening the browser</li>
										<li>1+: re-appear after the defined number of days.</li>
									</ul>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Show Thank You Page Setup</div>
				<div class="popupally-setting-section-help-text">A Thank You Page can be used to <strong>permanently</strong> stop showing this popup for visitors who have already opted in</div>
				<table class="popupally-setting-configure-table">
					<tbody>
						<tr>
							<td class="popupally-setting-configure-table-left-col">
								<input {{show-thank-you}} type="checkbox" value="true" popupally-change-source="show-thank-you-setup-{{id}}" id="show-thank-you-setup-{{id}}" />
							</td>
							<td>
								<div>
									<label for="show-thank-you-setup-{{id}}">Advanced functionality. Make sure to follow the <a class="underline" target="_blank" href="https://kb.accessally.com/tutorials/thank-you-page-setup-guide-for-popupally/">Thank You Page Setup Tutorial</a> before enabling</label>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="popupally-setting-configure-block" valign="top" {{show-thank-you-hide}} hide-toggle data-dependency="show-thank-you-setup-{{id}}" data-dependency-value="true">
					<div class="popupally-setting-section-sub-header">Thank you page after signing-up</div>
					<div>
						<input readonly type="text" class="selected-num-status" update-num-trigger=".thank-you-page-{{id}}"><label> pages selected</label>
						<div class="include-selection page-selection-scroll">
							<ul>
								{{thank-you-page-selection}}
							</ul>
						</div>
					</div>
				</div>
				<div class="popupally-setting-configure-block" valign="top" {{show-thank-you-hide}} hide-toggle data-dependency="show-thank-you-setup-{{id}}" data-dependency-value="true">
					<div class="popupally-setting-section-sub-header">Or you can put the following script on the thank you page (need to be hosted on {{host-url}})</div>
					<div>
						<textarea class="full-width" rows="4" readonly>{{cookie-js}}</textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
