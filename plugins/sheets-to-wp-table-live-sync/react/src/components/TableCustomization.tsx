import React, { useState, useEffect } from 'react';

import { lockWhite, hintIcon } from '../icons';

import { isProActive, getStrings } from './../Helpers';

import theme_one_default_style from '../images//theme-one-default-style.png';
import theme_two_stripped_table from '../images/theme-two-stripped-table.png';
import theme_three_dark_table from '../images/theme-three-dark-table.png';
import theme_four_tailwind_style from '../images/theme-four-tailwind-style.png';
import theme_five_colored_column from '../images/theme-five-colored-column.png';
import theme_six_hovered_style from '../images/theme-six-hovered-style.png';

//styles
import '../styles/_tableCustomization.scss';
import Tooltip from './Tooltip';

const TableCustomization = ({ tableSettings, setTableSettings }) => {

	const [secondActiveTab, setSecondActiveTab] = useState<string>(
		localStorage.getItem('second_active_tab') || 'utility'
	);

	const handleSecondSetActiveTab = (key) => {
		setSecondActiveTab(key);
		localStorage.setItem('second_active_tab', key);
	};

	const isOnExportOptionsList = (key) => {
		return tableSettings.table_settings.table_export.includes(key);
	};

	const handleExportOptions = (e) => {
		const exportOption = e.target.dataset.item;
		const currentExports =
			tableSettings?.table_settings?.table_export || [];
		const newExports = currentExports.includes(exportOption)
			? currentExports.filter((item) => item !== exportOption)
			: [...currentExports, exportOption];

		setTableSettings({
			...tableSettings,
			table_settings: {
				...tableSettings.table_settings,
				table_export: [...newExports],
			},
		});
	};

	useEffect(() => {
		const handleClick = () => {
			WPPOOL.Popup('sheets_to_wp_table_live_sync').show();
		};

		const proSettings = document.querySelectorAll('.swptls-pro-settings, .btn-pro-lock');
		proSettings.forEach(item => {
			item.addEventListener('click', handleClick);
		});

		return () => {
			proSettings.forEach(item => {
				item.removeEventListener('click', handleClick);
			});
		};
	}, [secondActiveTab]);



	//This used when we change the tab alert gone but we need to show this always
	useEffect(() => {
		var tableRows = document.querySelectorAll('.gswpts_rows');
		var mergeTipsElement = document.getElementById('merge-hints');

		var verticalMergeFound = false;

		tableRows.forEach(function (row) {
			var cells = row.querySelectorAll('td');

			cells.forEach(function (cell) {
				var rowspan = cell.getAttribute('rowspan');

				if (rowspan && parseInt(rowspan) > 1) {
					// Vertical merge found
					verticalMergeFound = true;
				}
			});
		});

		if (mergeTipsElement) {
			mergeTipsElement.style.display = verticalMergeFound ? 'block' : 'none';
		}

	}, [tableSettings]);



	return (
		<div>
			<div className="edit-table-customization-wrap">
				<div className="edit-form-group">
					<div className="table-customization-tab-wrap">
						<div className="table-customization-tab-nav">
							<button
								className={`${secondActiveTab === 'utility'
									? 'active'
									: ''
									}`}
								onClick={() =>
									handleSecondSetActiveTab('utility')
								}
							>
								{getStrings('Utility')}
							</button>
							<button
								className={`${secondActiveTab === 'style' ? 'active' : ''
									}`}
								onClick={() =>
									handleSecondSetActiveTab('style')
								}
							>
								{getStrings('Style')}
							</button>
							<button
								className={`${secondActiveTab === 'layout' ? 'active' : ''
									}`}
								onClick={() =>
									handleSecondSetActiveTab('layout')
								}
							>
								{getStrings('Layout')}
							</button>
							<button
								className={`${secondActiveTab === 'theme' ? 'active' : ''
									}`}
								onClick={() =>
									handleSecondSetActiveTab('theme')
								}
							>
								{getStrings('Theme')}
							</button>
						</div>
						<div className="table-customization-tab-content">
							{'utility' === secondActiveTab && (
								<div className="table-customization-utility">
									<div className={`edit-form-group`}>
										<h4>
											{getStrings('let-export')}{' '}
											<Tooltip content={getStrings('tooltip-22')} />{' '}
											{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
										</h4>

										<div className={`exports-btns${!isProActive() ? ` swptls-pro-settings` : ``}`}>
											<button
												className={
													tableSettings &&
														tableSettings?.table_settings?.table_export?.includes(
															'excel'
														)
														? 'active'
														: ''
												}
												data-item="excel"
												onClick={(e) =>
													handleExportOptions(e)
												}
											>
												{getStrings('Excel')}
											</button>
											<button
												className={
													tableSettings?.table_settings?.table_export?.includes(
														'json'
													)
														? 'active'
														: ''
												}
												data-item="json"
												onClick={(e) =>
													handleExportOptions(e)
												}
											>
												{getStrings('JSON')}
											</button>
											<button
												className={
													tableSettings?.table_settings?.table_export?.includes(
														'pdf'
													)
														? 'active'
														: ''
												}
												data-item="pdf"
												onClick={(e) =>
													handleExportOptions(e)
												}
											>
												{getStrings('PDF')}
											</button>
											<button
												className={
													tableSettings?.table_settings?.table_export?.includes(
														'csv'
													)
														? 'active'
														: ''
												}
												data-item="csv"
												onClick={(e) =>
													handleExportOptions(e)
												}
											>
												{getStrings('CSV')}
											</button>
											<button
												className={
													tableSettings?.table_settings?.table_export?.includes(
														'print'
													)
														? 'active'
														: ''
												}
												data-item="print"
												onClick={(e) =>
													handleExportOptions(e)
												}
											>
												{getStrings('Print')}
											</button>
											<button
												className={
													tableSettings?.table_settings?.table_export?.includes(
														'copy'
													)
														? 'active'
														: ''
												}
												data-item="copy"
												onClick={(e) =>
													handleExportOptions(e)
												}
											>
												{getStrings('Copy')}
											</button>
										</div>
									</div>

									<div className={`edit-form-group`}>
										<h4>
											{getStrings('link-behave')}{' '}
											<Tooltip content={getStrings('tooltip-23')} />{' '}
											{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
										</h4>

										<div className={`utility-checkbox-wrapper${!isProActive() ? ` swptls-pro-settings` : ``}`}>
											<label
												className={`utility-checkboxees${tableSettings
													?.table_settings
													?.redirection_type ===
													'_self'
													? ' active'
													: ''
													}`}
												htmlFor="current-window"
											>
												<input
													type="radio"
													name="redirection_type"
													id="current-window"
													onClick={() =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																redirection_type:
																	'_self',
															},
														})
													}
												/>
												<span>
													{getStrings('open-ct-window')}{' '}
													<Tooltip content={getStrings('tooltip-24')} />
												</span>
												<div
													className={`control__indicator${tableSettings
														?.table_settings
														?.redirection_type ===
														'_self'
														? ' active'
														: ''
														}`}
												></div>
											</label>
											<label
												className={`utility-checkboxees${tableSettings
													?.table_settings
													?.redirection_type ===
													'_blank'
													? ' active'
													: ''
													}`}
												htmlFor="new-window"
											>
												<input
													type="radio"
													name="redirection_type"
													id="new-window"
													onClick={() =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																redirection_type:
																	'_blank',
															},
														})
													}
												/>
												<span>
													{getStrings('open-new-window')}{' '}
													<Tooltip content={getStrings('tooltip-25')} />
												</span>
												<div
													className={`control__indicator${tableSettings
														?.table_settings
														?.redirection_type ===
														'_blank'
														? ' active'
														: ''
														}`}
												></div>
											</label>
										</div>
									</div>

									{/* Cursor behavior on the Table */}
									<div className={`edit-form-group`}>
										<h4>
											{getStrings('cursor-behavior')}{' '}
											<Tooltip content={getStrings('tooltip-45')} />{' '}
											{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
											{<button className='btn-pro btn-new cursor-behave'>{getStrings('new')}</button>}
										</h4>

										<div className={`utility-checkbox-wrapper${!isProActive() ? ` swptls-pro-settings` : ``}`}>
											<label
												className={`utility-checkboxees${tableSettings?.table_settings?.cursor_behavior === 'copy_paste' || !isProActive() ? ' active' : ''}`}
												htmlFor="copy-paste"
											>
												<input
													type="radio"
													name="cursor_behavior"
													id="copy-paste"
													onClick={() =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																cursor_behavior:
																	'copy_paste',
															},
														})
													}
												/>
												<span>
													{getStrings('highlight-and-copy')}{' '}
													<Tooltip content={getStrings('tooltip-46')} />
												</span>
												<div
													className={`control__indicator${tableSettings
														?.table_settings
														?.cursor_behavior ===
														'copy_paste'
														|| !isProActive() ? ' active' : ''
														}`}
												></div>
											</label>
											<label
												className={`utility-checkboxees${isProActive() && tableSettings?.table_settings?.cursor_behavior === 'left_right' ? ' active' : ''}`}
												htmlFor="left-right"
											>
												<input
													type="radio"
													name="cursor_behavior"
													id="left-right"
													onClick={() =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																cursor_behavior:
																	'left_right',
															},
														})
													}
												/>
												<span>
													{getStrings('left-to-right')}{' '}
													<Tooltip content={getStrings('tooltip-47')} />
												</span>
												<div
													className={`control__indicator${isProActive() && tableSettings?.table_settings?.cursor_behavior === 'left_right' ? ' active' : ''}`}
												></div>
											</label>
										</div>
									</div>

									{/* image and link support */}
									{/* {'style' === tableSettings?.table_settings?.table_link_support && ( */}
									<div className={`edit-form-group special-feature`}>
										<label
											className={`cache-table ${!isProActive() ? ` swptls-pro-settings` : ``}`}
											htmlFor="table_link_support"
										>
											<input
												type="checkbox"
												name="table_link_support"
												id="table_link_support"
												checked={
													tableSettings
														?.table_settings
														?.table_link_support
												}
												onClick={(e) =>
													setTableSettings({
														...tableSettings,
														table_settings: {
															...tableSettings.table_settings,
															table_link_support:
																e.target
																	.checked,
														},
													})
												}
												disabled={!isProActive()} // added to disable click if its not pro
											/>
											{getStrings('import-links')}{' '}
										</label>
										<span className='tooltip-cache'>
											<Tooltip content={getStrings('tooltip-26')} />{' '}
											{!isProActive() && (<button className='btn-pro cache-pro-tag'>{getStrings('pro')}</button>)}
											{/* {<button className='btn-pro btn-new'>{getStrings('new')}</button>} */}
										</span>
									</div>
									{/* )} */}


									{/* image and link support */}
									<div className={`edit-form-group special-feature`}>
										<label
											className={`cache-table ${!isProActive() ? ` swptls-pro-settings` : ``}`}
											htmlFor="table_img_support"
										>
											<input
												type="checkbox"
												name="table_img_support"
												id="table_img_support"
												checked={
													tableSettings
														?.table_settings
														?.table_img_support
												}
												onClick={(e) =>
													setTableSettings({
														...tableSettings,
														table_settings: {
															...tableSettings.table_settings,
															table_img_support:
																e.target
																	.checked,
														},
													})
												}
												disabled={!isProActive()} // added to disable click if its not pro
											/>
											{getStrings('import-image')}{' '}
										</label>
										<span className='tooltip-cache'>
											<Tooltip content={getStrings('tooltip-27')} />{' '}
											{!isProActive() && (<button className='btn-pro cache-pro-tag'>{getStrings('pro')}</button>)}
											{/* {<button className='btn-pro btn-new'>{getStrings('new')}</button>} */}
										</span>
									</div>


									{/* Cache feature  */}
									<div className={`edit-form-group cache-feature`}>
										<label
											className={`cache-table ${!isProActive() ? ` swptls-pro-settings` : ``}`}
											htmlFor="table-cache"
										>
											<input
												type="checkbox"
												name="table_cache"
												id="table-cache"
												checked={
													tableSettings
														?.table_settings
														?.table_cache
												}
												onClick={(e) =>
													setTableSettings({
														...tableSettings,
														table_settings: {
															...tableSettings.table_settings,
															table_cache:
																e.target
																	.checked,
														},
													})
												}
												disabled={!isProActive()} // added to disable click if its not pro
											/>
											{getStrings('cache-table')}{' '}
										</label>
										<span className='tooltip-cache'>
											<Tooltip content={getStrings('tooltip-28')} />{' '}
											{!isProActive() && (<button className='btn-pro cache-pro-tag'>{getStrings('pro')}</button>)}
										</span>
									</div>

								</div>
							)}

							{'style' === secondActiveTab && (
								<div className="table-customization-style">
									<div className={`edit-form-group`}>
										<h4>
											{getStrings('cell-formatting')}{' '}
											<Tooltip content={getStrings('tooltip-29')} />
											{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
										</h4>

										<div className={`style-checkbox-items${!isProActive() ? ` swptls-pro-settings` : ``}`}>
											<label
												className={`style-checkbox${tableSettings
													?.table_settings
													?.cell_format ===
													'expand'
													? ' active'
													: ''
													}`}
												htmlFor="expand"
											>
												<input
													type="radio"
													name="cell_format"
													id="expand"
													onClick={() =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																cell_format:
																	'expand',
															},
														})
													}
												/>
												<span>
													{getStrings('expanded')}{' '}
													<Tooltip content={getStrings('tooltip-30')} />
												</span>
												<div
													className={`control__indicator${tableSettings
														?.table_settings
														?.cell_format ===
														'expand'
														? ' active'
														: ''
														}`}
												></div>
											</label>
											<label
												className={`style-checkbox${tableSettings
													?.table_settings
													?.cell_format === 'wrap'
													? ' active'
													: ''
													}`}
												htmlFor="wrap"
											>
												<input
													type="radio"
													name="responsive_style"
													id="wrap"
													onClick={() =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																cell_format:
																	'wrap',
															},
														})
													}
												/>
												<span>
													{getStrings('wrapped')}{' '}
													<Tooltip content={getStrings('tooltip-31')} />
												</span>
												<div
													className={`control__indicator${tableSettings
														?.table_settings
														?.cell_format ===
														'wrap'
														? ' active'
														: ''
														}`}
												></div>
											</label>
										</div>
									</div>

									<div className={`edit-form-group`}>
										<h4>
											{getStrings('responsive-style')}{' '}
											<Tooltip content={getStrings('tooltip-32')} />
											{!isProActive() && (<button className='btn-pro'>{getStrings('')}{getStrings('pro')}</button>)}
										</h4>
										<div className={`style-checkbox-items${!isProActive() ? ` swptls-pro-settings` : ``}`}>
											<label
												className={`style-checkbox${tableSettings
													?.table_settings
													?.responsive_style ===
													'default_style'
													? ' active'
													: ''
													}`}
												htmlFor="default_style"
											>
												<input
													type="radio"
													name="responsive_style"
													id="default_style"
													onClick={() =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																responsive_style:
																	'default_style',
															},
														})
													}
												/>
												<span>
													{getStrings('default')}{' '}
													<Tooltip content={getStrings('tooltip-33')} />
												</span>
												<div
													className={`control__indicator${tableSettings
														?.table_settings
														?.responsive_style ===
														'default_style'
														? ' active'
														: ''
														}`}
												></div>
											</label>
											<label
												className={`style-checkbox${tableSettings
													?.table_settings
													?.responsive_style ===
													'collapse_style'
													? ' active'
													: ''
													}`}
												htmlFor="collapse_style"
											>
												<input
													type="checkbox"
													name="collapse_style"
													id="collapse_style"
													onClick={() =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																responsive_style:
																	'collapse_style',
															},
														})
													}
												/>
												<span>
													{getStrings('collapsible-style')}{' '}
													<Tooltip content={getStrings('tooltip-34')} />
												</span>
												<div
													className={`control__indicator${tableSettings
														?.table_settings
														?.responsive_style ===
														'collapse_style'
														? ' active'
														: ''
														}`}
												></div>
											</label>
											<label
												className={`style-checkbox${tableSettings
													?.table_settings
													?.responsive_style ===
													'scroll_style'
													? ' active'
													: ''
													}`}
												htmlFor="scroll_style"
											>
												<input
													type="checkbox"
													name="scroll_style"
													id="scroll_style"
													onClick={() =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																responsive_style:
																	'scroll_style',
															},
														})
													}
												/>
												<span>
													{getStrings('scrollable-style')}{' '}
													<Tooltip content={getStrings('tooltip-35')} />
												</span>
												<div
													className={`control__indicator${tableSettings
														?.table_settings
														?.responsive_style ===
														'scroll_style'
														? ' active'
														: ''
														}`}
												></div>
											</label>
										</div>
									</div>

									{/* Merge feature */}
									<div className={`edit-form-group table-style`} id='merged-activattion'>
										<label
											className={`${!isProActive() ? `swptls-pro-settings` : ``}`}
											htmlFor="table-merge-style"
										>
											<div className="toggle-switch">
												<input
													type="hidden"
													name="merged_support"
													value={tableSettings?.table_settings?.merged_support}
												/>
												<input
													type="checkbox"
													id="table-merge-style"
													checked={isProActive() && tableSettings?.table_settings?.merged_support}
													onChange={(e) =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																merged_support: e.target.checked,
															},
														})
													}

													disabled={!isProActive()}
												/>
												<div className="slider round"></div>
											</div>
											{getStrings('merge-cells')}{' '}
										</label>
										<span className='import-tooltip'>
											<Tooltip content={getStrings('tooltip-36')} />{' '}
											{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
											{<button className='btn-pro btn-beta merge-toggle'>{getStrings('beta')}</button>}
										</span>
									</div>

									{tableSettings?.table_settings?.merged_support && (
										<h4 className='merge-tips' id='merge-hints' style={{ display: 'none' }}>
											{getStrings('active-merge-condition-alert')}
										</h4>
									)}

								</div>
							)}

							{'layout' === secondActiveTab && (
								<div className="table-customization-layout">
									<div className="edit-form-group">
										<label htmlFor="rows-per-page">
											{getStrings('row-per-page')}{' '}
											<Tooltip content={getStrings('tooltip-37')} />
										</label>
										<select
											name="rows_per_page"
											id="rows-per-page"
											value={
												tableSettings?.table_settings
													?.default_rows_per_page
											}
											onChange={(e) =>
												setTableSettings({
													...tableSettings,
													table_settings: {
														...tableSettings.table_settings,
														default_rows_per_page:
															parseInt(
																e.target.value
															),
													},
												})
											}
										>
											<>
												{isProActive() ? (<>
													<option value="1">{getStrings('1')}</option>
													<option value="5">{getStrings('5')}</option>
													<option value="10">{getStrings('10')}</option>
													<option value="15">{getStrings('15')}</option>
													<option value="25">{getStrings('25')}</option>
													<option value="50">{getStrings('50')}</option>
													<option value="100">{getStrings('100')}</option>
													<option value="-1">{getStrings('All')}</option>
												</>) : (<>
													<option value="1">{getStrings('1')}</option>
													<option value="5">{getStrings('5')}</option>
													<option value="10">{getStrings('10')}</option>
													<option value="15">{getStrings('15')}</option>
												</>)}
											</>
										</select>
									</div>

									<div className={`edit-form-group`}>

										<label htmlFor="table_height">
											{getStrings('table-height')}{' '}
											<Tooltip content={getStrings('tooltip-38')} />
											{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
										</label>
										<div className={`edit-form-group swptls-select${!isProActive() ? ` swptls-pro-settings` : ``}`}>
											<select
												className={`${!isProActive() ? ` swptls-pro-settings` : ``}`}
												name="table_height"
												id="table_height"
												value={
													tableSettings?.table_settings
														?.vertical_scrolling
												}
												onChange={(e) =>
													setTableSettings({
														...tableSettings,
														table_settings: {
															...tableSettings.table_settings,
															vertical_scrolling:
																parseInt(
																	e.target.value
																),
														},
													})
												}
											>
												<option value="default_height">
													{getStrings('default-height')}
												</option>
												<option value="400">{getStrings('400px')}</option>
												<option value="500">{getStrings('500px')}</option>
												<option value="600">{getStrings('600px')}</option>
												<option value="700">{getStrings('700px')}</option>
												<option value="800">{getStrings('800px')}</option>
												<option value="900">{getStrings('900px')}</option>
												<option value="1000">{getStrings('1000px')}</option>
											</select>
										</div>

									</div>
								</div>
							)}

							{'theme' === secondActiveTab && (
								<div className="table-customization-theme-wrapper">
									<h4>
										{getStrings('select-theme')}{' '}
										<Tooltip content={getStrings('tooltip-39')} />
									</h4>
									<div className={`table-customization-theme-btns ${tableSettings
										?.table_settings
										?.import_styles ? 'active_sheetstyle' : 'disable_sheetstyle'
										}`}
									>
										<div className="item-wrapper">
											<button
												className={`single-theme-button${tableSettings
													?.table_settings
													?.table_style ===
													'default-style'
													? ' active'
													: ''
													}`}
												onClick={() =>
													setTableSettings({
														...tableSettings,
														table_settings: {
															...tableSettings.table_settings,
															table_style:
																'default-style',
														},
													})
												}
											>
												<img
													src={
														theme_one_default_style
													}
													alt="theme_one_default_style"
												/>
											</button>
											<span>{getStrings('Default-Style')}</span>
										</div>

										{/* PRO Theme  */}
										<div className={`item-wrapper${!isProActive() ? ` swptls-pro-theme` : ``}`}>
											<button
												className={`single-theme-button${tableSettings
													?.table_settings
													?.table_style ===
													'style-2'
													? ' active'
													: ''
													}`}
												onClick={() =>
													setTableSettings({
														...tableSettings,
														table_settings: {
															...tableSettings.table_settings,
															table_style:
																'style-2',
														},
													})
												}
												disabled={!isProActive()}
											>
												<img
													src={
														theme_two_stripped_table
													}
													alt="theme_two_stripped_table"
												/>
												{!isProActive() && (<div className='btn-pro-lock'>{lockWhite} <span className="pro-tag-unlock">{getStrings('unlock')}</span></div>)}
											</button>
											<span>{getStrings('Stripped-Table')}</span>
										</div>

										<div className={`item-wrapper${!isProActive() ? ` swptls-pro-theme` : ``}`}>
											<button
												className={`single-theme-button${tableSettings
													?.table_settings
													?.table_style ===
													'style-4'
													? ' active'
													: ''
													}`}
												onClick={() =>
													setTableSettings({
														...tableSettings,
														table_settings: {
															...tableSettings.table_settings,
															table_style:
																'style-4',
														},
													})
												}
											>
												<img
													src={
														theme_three_dark_table
													}
													alt="theme_three_dark_table"
												/>
												{!isProActive() && (<div className='btn-pro-lock'>{lockWhite} <span className="pro-tag-unlock">{getStrings('unlock')}</span></div>)}
											</button>
											<span>{getStrings('Dark-Table')}</span>
										</div>
										<div className={`item-wrapper${!isProActive() ? ` swptls-pro-theme` : ``}`}>
											<button
												className={`single-theme-button${tableSettings
													?.table_settings
													?.table_style ===
													'style-5'
													? ' active'
													: ''
													}`}
												onClick={() =>
													setTableSettings({
														...tableSettings,
														table_settings: {
															...tableSettings.table_settings,
															table_style:
																'style-5',
														},
													})
												}
											>
												<img
													src={
														theme_four_tailwind_style
													}
													alt="theme_four_tailwind_style"
												/>
												{!isProActive() && (<div className='btn-pro-lock'>{lockWhite} <span className="pro-tag-unlock">{getStrings('unlock')}</span></div>)}
											</button>
											<span>{getStrings('Taliwind-Style')}</span>
										</div>
										<div className={`item-wrapper${!isProActive() ? ` swptls-pro-theme` : ``}`}>
											<button
												className={`single-theme-button${tableSettings
													?.table_settings
													?.table_style ===
													'style-1'
													? ' active'
													: ''
													}`}
												onClick={() =>
													setTableSettings({
														...tableSettings,
														table_settings: {
															...tableSettings.table_settings,
															table_style:
																'style-1',
														},
													})
												}
											>
												<img
													src={
														theme_five_colored_column
													}
													alt="theme_five_colored_column"
												/>
												{!isProActive() && (<div className='btn-pro-lock'>{lockWhite} <span className="pro-tag-unlock">{getStrings('unlock')}</span></div>)}
											</button>
											<span>{getStrings('colored-column')}</span>
										</div>
										<div className={`item-wrapper${!isProActive() ? ` swptls-pro-theme` : ``}`}>
											<button
												className={`single-theme-button${tableSettings
													?.table_settings
													?.table_style ===
													'style-3'
													? ' active'
													: ''
													}`}
												onClick={() =>
													setTableSettings({
														...tableSettings,
														table_settings: {
															...tableSettings.table_settings,
															table_style:
																'style-3',
														},
													})
												}
											>
												<img
													src={
														theme_six_hovered_style
													}
													alt="theme_six_hovered_style"
												/>
												{!isProActive() && (<div className='btn-pro-lock'>{lockWhite} <span className="pro-tag-unlock">{getStrings('unlock')}</span></div>)}
											</button>
											<span>{getStrings('hover-style')}</span>
										</div>
									</div>

									{/* Toogle Sheet style */}
									{/* Start  */}
									<div className={`edit-form-group table-style`}>
										<label
											className={`${!isProActive() ? `swptls-pro-settings` : ``}`}
											htmlFor="table-style"
										>
											<div className="toggle-switch">
												<input
													type="hidden"
													name="import_styles"
													value={tableSettings?.table_settings?.import_styles}
												/>
												<input
													type="checkbox"
													id="table-style"
													checked={tableSettings?.table_settings?.import_styles}
													onChange={(e) =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																import_styles: e.target.checked,
															},
														})
													}
													disabled={!isProActive()}
												/>
												<div className="slider round"></div>
											</div>
											{getStrings('import-color-from-sheet')}{' '}
										</label>
										<span className='import-tooltip'>
											<Tooltip content={getStrings('tooltip-40')} />{' '}
											{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
											{/* {<button className='btn-pro btn-new'>New</button>} */}
										</span>
									</div>

									{/* END  */}

								</div>
							)}


						</div>
					</div>
				</div>
			</div>
		</div>
	);
};

export default TableCustomization;
