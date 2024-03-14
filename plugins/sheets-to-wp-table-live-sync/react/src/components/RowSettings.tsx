import React, { useState, useEffect } from 'react';
import { hintIcon, Cross, } from '../icons';
import { isProActive, getStrings } from '../Helpers';
import Tooltip from './Tooltip';

const RowSettings = ({ tableSettings, setTableSettings, setPreviewClasses, hidingContext, setHidingContext, }) => {

	const [thirdActiveTab, setThirdActiveTab] = useState<string>(
		localStorage.getItem('third_active_tab') || 'columns'
	);

	const handleThirdSetActiveTab = (key: React.SetStateAction<string>) => {
		setThirdActiveTab(key);
		setHidingContext(key);
		localStorage.setItem('third_active_tab', key);
	};

	useEffect(() => {
		switch (hidingContext) {
			case 'columns':
				setPreviewClasses('mode-hide-columns');
				break;
			case 'rows':
				setPreviewClasses('mode-hide-rows');
				break;
			case 'cells':
				setPreviewClasses('mode-hide-cells');
				break;
		}
	}, [hidingContext]);

	useEffect(() => {
		const handleClick = () => {
			WPPOOL.Popup('sheets_to_wp_table_live_sync').show();
		};

		const proSettings = document.querySelectorAll('.swptls-pro-settings');
		proSettings.forEach(item => {
			item.addEventListener('click', handleClick);
		});

		return () => {
			proSettings.forEach(item => {
				item.removeEventListener('click', handleClick);
			});
		};
	}, [thirdActiveTab]);

	const handleRemoveColumn = (value) => {
		const newColumns = tableSettings?.table_settings?.hide_column.filter((item) => item !== value);
		const table = window?.swptlsDataTable?.table().node();


		const cells = table.querySelectorAll(
			`td:nth-child(${value + 1})`
		);

		cells.forEach((cell) => {
			cell.classList.remove(
				'hidden-column'
			);
		});

		setTableSettings({
			...tableSettings,
			table_settings: {
				...tableSettings.table_settings,
				hide_column: newColumns
			}
		});
	}

	const handleRemoveRow = (value) => {
		const newRows = tableSettings?.table_settings?.hide_rows.filter((item) => item !== value);

		const currentRow = document.querySelector(`.row_${value}`);
		currentRow?.classList.remove('hidden-row');

		setTableSettings({
			...tableSettings,
			table_settings: {
				...tableSettings.table_settings,
				hide_rows: newRows
			}
		});
	}

	const handleCloseCells = (value) => {
		const newRows = tableSettings?.table_settings?.hide_cell.filter((item) => item !== value);

		const indices = JSON.parse(value);
		const selector = `.cell_index_${indices[0]}-${indices[1]}`;
		const cell = document.querySelector(selector);
		cell?.classList.remove('hidden-cell');

		setTableSettings({
			...tableSettings,
			table_settings: {
				...tableSettings.table_settings,
				hide_cell: newRows
			}
		});
	}

	return (
		<div>
			<div className="edit-row-settings-wrap">
				<div className="edit-form-group">
					<div className="hide-table-elements-tab-wrap">
						<div className="hide-table-elements-tab-nav">
							<button
								className={`${thirdActiveTab === 'columns' ? 'active' : ''
									} `}
								onClick={() =>
									handleThirdSetActiveTab('columns')
								}
							>
								{getStrings('hide-column')}
							</button>
							<button
								className={`${thirdActiveTab === 'rows' ? 'active' : ''
									} `}
								onClick={() =>
									handleThirdSetActiveTab('rows')
								}
							>
								{getStrings('hide-row')}
							</button>
							<button
								className={`${thirdActiveTab === 'cells' ? 'active' : ''
									} `}
								onClick={() =>
									handleThirdSetActiveTab('cells')
								}
							>
								{getStrings('hide-cell')}
							</button>
						</div>
						<div className="hide-table-elements-tab-content">
							{'columns' === thirdActiveTab && (
								<div className={`hide-columns-tab-content`}>
									<h4>
										<span>{hintIcon}</span> {getStrings('click-on-the-col')}
									</h4>

									<div className="hide-mobile-and-desktop-wrapper">
										<div className="hide-checkboxes hide-on-mobile">
											<label htmlFor="hide-on-mobile" className={`${!isProActive() ? ` swptls-pro-settings` : ``}`}>
												<input
													type="checkbox"
													name="hide_on_mobile"
													id="hide-on-mobile"
													checked={
														tableSettings.table_settings
															?.hide_on_mobile
													}
													onChange={(e) =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																hide_on_mobile:
																	e.target.checked,
															},
														})
													}
													disabled={!isProActive()}
												/>
												{getStrings('hide-mobile')}{' '}
											</label>
											<span className="tooltip-hide-on">
												<Tooltip content={getStrings('tooltip-13')} />
												{!isProActive() && (<button className='btn-pro'>PRO</button>)}
											</span>
										</div>
										<div className="hide-checkboxes hide-on-desktop">
											<label htmlFor="hide-on-desktop" className={`${!isProActive() ? ` swptls-pro-settings` : ``}`}>
												<input
													type="checkbox"
													name="hide_on_desktop"
													id="hide-on-desktop"
													defaultChecked={true} // aded by me forcefully it checked
													checked={
														tableSettings.table_settings
															?.hide_on_desktop
													}
													onChange={(e) =>
														setTableSettings({
															...tableSettings,
															table_settings: {
																...tableSettings.table_settings,
																hide_on_desktop:
																	e.target.checked,
															},
														})
													}
													disabled={!isProActive()}
												/>
												{getStrings('hide-desktop')}{' '}
											</label>
											<span className="tooltip-hide-on">
												<Tooltip content={getStrings('tooltip-14')} />
												{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
											</span>
										</div>
									</div>
									<div className="hidden-columns">
										<label htmlFor="hidden-columns" className={`hidden-columns-label`}>
											<span className={`${!isProActive() ? ` swptls-pro-settings` : ``}`}>{getStrings('hidden-column')}{' '}</span>

											<span>
												<Tooltip content={getStrings('tooltip-15')} />
												{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
											</span>
										</label>

										<ul className={`hidden-columns-list${!isProActive() ? ` swptls-pro-settings` : ``}`}>
											{tableSettings?.table_settings
												?.hide_column &&
												tableSettings?.table_settings?.hide_column.map(
													(value: string | number, index: any) => (
														<li key={index}> {`Col #${value}`} <span className="cross_sign" onClick={() => handleRemoveColumn(value)}>{Cross}</span></li>
													)
												)}
										</ul>
									</div>
								</div>
							)}
							{'rows' === thirdActiveTab && (
								<div className={`hide-rows-tab-content`}>
									<h4>
										<span>{hintIcon}</span> {getStrings('click-on-the-rows')}
									</h4>
									<div className="hidden-rows">
										<label htmlFor="hidden-rows">
											<span className={`${!isProActive() ? ` swptls-pro-settings` : ``}`}>{getStrings('hidden-row')}{' '}</span>
											<span>
												<Tooltip content={getStrings('tooltip-16')} />
												{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
											</span>
										</label>
										<ul className={`hidden-columns-list ${!isProActive() ? ` swptls-pro-settings` : ``}`}>
											{tableSettings?.table_settings
												?.hide_rows &&
												tableSettings?.table_settings?.hide_rows.map(
													(value: string | number, index: any) => (
														<li key={index}> {`Row #${value}`} <span className="cross_sign" onClick={() => handleRemoveRow(value)}>{Cross}</span></li>
													)
												)}
										</ul>
									</div>
								</div>
							)}
							{'cells' === thirdActiveTab && (
								<div className={`hide-cells-tab-content`}>
									<h4>
										<span>{hintIcon}</span> {getStrings('click-on-the-cells')}
									</h4>
									<div className="hidden-cells">
										<label htmlFor="hidden-cells">
											<span className={`${!isProActive() ? ` swptls-pro-settings` : ``}`}>
												{getStrings('hidden-cell')}{' '}
											</span>
											<span>
												<Tooltip content={getStrings('tooltip-17')} />
												{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
											</span>
										</label>
										<ul className={`hidden-columns-list ${!isProActive() ? ` swptls-pro-settings` : ``}`}>
											{tableSettings?.table_settings
												?.hide_cell &&
												tableSettings?.table_settings?.hide_cell.map(
													(value: any, index: any) => (
														<li key={index}> {value.slice(1, -1).split(',').map((celValue: any, key: number) => (
															`${key == 0 ? `Col #${celValue}, ` : `Row #${celValue}`}`
														))} <span className="cross_sign" onClick={() => handleCloseCells(value)}>{Cross}</span></li>
													)
												)}
										</ul>
									</div>
								</div>
							)}
						</div>
					</div>
				</div>
			</div>
		</div>
	);
};

export default RowSettings;
