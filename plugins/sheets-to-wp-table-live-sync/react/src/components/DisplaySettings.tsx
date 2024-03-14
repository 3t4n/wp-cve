import React, { useState, useEffect, useRef } from 'react';
import { getStrings } from './../Helpers';
import { swapIcon } from '../icons';
import Tooltip from './Tooltip';

const DisplaySettings = ({ tableSettings, setTableSettings }) => {

	//Merge render conditionally 
	useEffect(() => {
		const mergedSupport = tableSettings?.table_settings?.merged_support || false;

		if (mergedSupport) {
			var tableRows = document.querySelectorAll('.gswpts_rows');

			tableRows.forEach(function (row) {
				var cells = row.querySelectorAll('td');

				cells.forEach(function (cell, index) {
					var rowspan = cell.getAttribute('rowspan');

					if (rowspan && parseInt(rowspan) > 1) {
						var dataIndex = cell.getAttribute('data-index');
						// console.log('Found rowspan:', dataIndex);

						// Update isvertical to true and exit the loop
						setTableSettings((prevSettings) => ({
							...prevSettings,
							table_settings: {
								...prevSettings.table_settings,
								isvertical: true,
							},
						}));
						return;
					}
				});
			});
		}
	}, [setTableSettings]);

	return (
		<div>
			<div className="edit-display-settings-wrap">
				<div className="edit-form-group">
					<div className="swptls-table-top-elements">
						<h4>{getStrings('table-top-elements')}</h4>

						<div className="display-settings-block-wrapper">
							<div className="top-elements-wrapper">
								<div className="edit-form-group">
									<input
										type="checkbox"
										name="hide_entries"
										id="hide-entries"
										checked={
											!tableSettings.table_settings
												?.show_x_entries
										}
										onChange={(e) =>
											setTableSettings({
												...tableSettings,
												table_settings: {
													...tableSettings.table_settings,
													show_x_entries:
														!tableSettings.table_settings
															?.show_x_entries
												},
											})
										}
									/>
									<label htmlFor="hide-entries">
										{getStrings('hide-ent')}{' '}
										<Tooltip content={getStrings('tooltip-3')} />
									</label>
								</div>
								<div className="edit-form-group">
									<input
										type="checkbox"
										name="hide_search_box"
										id="hide-search-box"
										checked={
											!tableSettings.table_settings
												?.search_bar
										}
										onChange={(e) =>
											setTableSettings({
												...tableSettings,
												table_settings: {
													...tableSettings.table_settings,
													search_bar: !tableSettings.table_settings.search_bar
												},
											})
										}
									/>
									<label htmlFor="hide-search-box">
										{getStrings('hide-search-box')}{' '}
										<Tooltip content={getStrings('tooltip-4')} />
									</label>
								</div>
							</div>
							<div className="swap-wrapper">
								<div className="edit-form-group">
									<label
										htmlFor="swap_table_top"
										className={
											tableSettings.table_settings
												?.swap_filter_inputs
												? 'active'
												: ''
										}
									>
										<input
											type="checkbox"
											name="swap_filter_inputs"
											id="swap_table_top"
											checked={
												!tableSettings.table_settings
													?.swap_filter_inputs
											}
											onChange={(e) =>
												setTableSettings({
													...tableSettings,
													table_settings: {
														...tableSettings.table_settings,
														swap_filter_inputs: !tableSettings.table_settings.swap_filter_inputs
													},
												})
											}
										/>
										<span>{swapIcon}</span> {getStrings('swap')}
									</label>
								</div>
							</div>
						</div>
					</div>
					<div className="swptls-table-basic-elements">
						<div className="edit-form-group">
							<input
								type="checkbox"
								name="hide_title"
								id="hide-title"
								checked={
									!tableSettings.table_settings?.show_title
								}
								onChange={(e) =>
									setTableSettings({
										...tableSettings,
										table_settings: {
											...tableSettings.table_settings,
											show_title: !tableSettings.table_settings.show_title
										},
									})
								}
							/>
							<label htmlFor="hide-title">
								{getStrings('hide-title')}
								<Tooltip content={getStrings('tooltip-5')} />
							</label>
						</div>

						{/* Disable active sorting  */}
						<div className="edit-form-group">
							<input
								type="checkbox"
								name="disable_sorting"
								id="disable-sorting"
								checked={
									!tableSettings.table_settings?.allow_sorting
								}

								onChange={(e) =>
									setTableSettings({
										...tableSettings,
										table_settings: {
											...tableSettings.table_settings,
											allow_sorting: !tableSettings.table_settings?.allow_sorting
										},
									})
								}
							/>
							<label htmlFor="disable-sorting">
								{getStrings('disable-sorting')}
								<Tooltip content={getStrings('tooltip-6')} />
							</label>
						</div>
					</div>

					<div className="swptls-table-bottom-elements">
						<h4>{getStrings('table-bottom-ele')}</h4>

						<div className="display-settings-block-wrapper">
							<div className="top-elements-wrapper">
								<div className="edit-form-group">
									<input
										type="checkbox"
										name="hide_entry_info"
										id="hide-entry-info"
										checked={
											!tableSettings.table_settings
												?.show_info_block
										}
										onChange={(e) =>
											setTableSettings({
												...tableSettings,
												table_settings: {
													...tableSettings.table_settings,
													show_info_block:
														!tableSettings.table_settings
															?.show_info_block,
												},
											})
										}
									/>
									<label htmlFor="hide-entry-info">
										{getStrings('hide-entry-info')}{' '}
										<Tooltip content={getStrings('tooltip-7')} />
									</label>
								</div>
								<div className="edit-form-group">
									<input
										type="checkbox"
										name="hide_pagination"
										id="hide-pagination"
										checked={
											!tableSettings.table_settings
												?.pagination
										}
										onChange={(e) =>
											setTableSettings({
												...tableSettings,
												table_settings: {
													...tableSettings.table_settings,
													pagination:
														!tableSettings.table_settings
															?.pagination
												},
											})
										}
									/>
									<label htmlFor="hide-pagination">
										{getStrings('hide-pagi')}{' '}
										<Tooltip content={getStrings('tooltip-9')} />
									</label>
								</div>
							</div>
							<div className="swap-wrapper">
								<div className="edit-form-group">
									<label
										htmlFor="swap-bottom-options"
										className={
											tableSettings.table_settings
												?.swap_bottom_options
												? 'active'
												: ''
										}
									>
										<input
											type="checkbox"
											name="swap_bottom_options"
											id="swap-bottom-options"
											checked={
												!tableSettings.table_settings
													?.swap_bottom_options
											}
											onChange={(e) =>
												setTableSettings({
													...tableSettings,
													table_settings: {
														...tableSettings.table_settings,
														swap_bottom_options:
															!tableSettings.table_settings
																?.swap_bottom_options
													},
												})
											}
										/>
										<span>{swapIcon}</span> {getStrings('swap')}
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
};

export default DisplaySettings;
