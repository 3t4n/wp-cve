import React, { useState, useEffect, useRef } from 'react';
import { useParams } from 'react-router-dom';

import Column from '../core/Column';
import Row from '../core/Row';
import Title from '../core/Title';
import DataSource from './DataSource';
import DisplaySettings from './DisplaySettings';
import TableCustomization from './TableCustomization';
import RowSettings from './RowSettings';
import DataTable from 'datatables.net-dt';
import { getNonce, getStrings, isProActive, setPdfUrl, handleTableAppearance } from '../Helpers';
import { OrangeCopyIcon, Cross, Cloud, Merge } from '../icons';
import Modal from './../core/Modal';

import './../../node_modules/datatables.net-dt/css/jquery.dataTables.min.css';
import '../styles/_editTable.scss';
import '../styles/_frontend.scss';
import '../styles/_create_table.scss';

import { toast } from 'react-toastify';


function EditTable() {
	const { id } = useParams();
	const sheetUrlRef = useRef();
	const verticalModelRef = useRef();
	const [loader, setLoader] = useState<boolean>(false);
	const [verticalmodal, setVerticalmodal] = useState<boolean>(false);
	const [previewLoader, setPreviewLoader] = useState<boolean>(false);

	const [privatesheetmessage, setPrivateSheetmessage] = useState(false);
	const [limitedtmessage, setLimitedmessage] = useState(false);

	const [hidingContext, setHidingContext] = useState(localStorage.getItem('third_active_tab') || 'columns');
	const [copySuccess, setCopySuccess] = useState(false);
	const [activeTab, setActiveTab] = useState(
		localStorage.getItem('active_tab') || 'data_source'
	);
	const [tableSettings, setTableSettings] = useState({});
	const [previewClasses, setPreviewClasses] =
		useState('mode-hide-columns');
	const [tablePreview, setTablePreview] = useState();
	const [paginated, setPaginated] = useState(false);

	const handleSetActiveTab = (key) => {
		setActiveTab(key);
		localStorage.setItem('active_tab', key);
	};

	const getTableData = () => {
		setLoader(true);

		wp.ajax.send('swptls_edit_table', {
			data: {
				nonce: getNonce(),
				id,
			},
			success(response) {
				setTableSettings({
					...response,
					id,
				});

				getTablePreview(response);
				setLoader(false);

			},
			error(error) {
				console.error(error);
			},
		});
	};

	const getTablePreview = (values) => {
		setPreviewLoader(true);
		wp.ajax.send('swptls_get_table_preview', {
			data: {
				nonce: getNonce(),
				...values,
				table_settings: JSON.stringify(values.table_settings),
				id,
			},
			success(response) {
				setTablePreview({
					...response
				});

				setPreviewLoader(false);
				setPrivateSheetmessage(response.is_private);

				// console.log(response.is_private)

				let dom = `<"#filtering_input"lf>rt<"#bottom_options"ip>`;

				if (isProActive()) {
					dom = `B<"#filtering_input"lf>rt<"#bottom_options"ip>`;
				}


				if (!previewLoader) {
					const tableOptions = {
						pageLength: parseInt(values.table_settings.default_rows_per_page),
						dom,
						// ordering: true,
						ordering: values.table_settings.allow_sorting,
						// order: values.table_settings.allow_sorting ? [[0, '']] : [],
						order: values.table_settings.allow_sorting ? [] : [],
						lengthMenu: [
							[1, 5, 10, 15],
							[getStrings('1'), getStrings('5'), getStrings('10'), getStrings('15')],
						],
						language: {
							search: getStrings('search'),
							// lengthMenu: getStrings('filtering'),
							// info: getStrings('dataTables_info'),
							lengthMenu: getStrings('filtering_show') + " _MENU_ " + getStrings('filtering_entries'),
							info: getStrings('dataTables_info_showing') + " _START_ " + getStrings('dataTables_info_to') + " _END_ " + getStrings('dataTables_info_of') + " _TOTAL_ " + getStrings('filtering_entries'),
							emptyTable: getStrings('data-empty-notice'),
							zeroRecords: getStrings('data-empty-notice'),
							paginate: {
								first: "first",
								previous: getStrings('previous'),
								next: getStrings('next'),
								last: getStrings('last')
							},
						},
						buttons: [
							{
								text: `<img src="${SWPTLS_APP.icons.curlyBrackets}" />`,
								className: "ui inverted button transition hidden json_btn",
								action: function (e, dt, button, config) {
									var data = dt.buttons.exportData();
									window.dataTable.fileSave(new Blob([JSON.stringify(data)]), `${values.table_name}.json`);
								},
								titleAttr: getStrings('export-json')
							},
							{
								text: `<img src="${SWPTLS_APP.icons.fileCSV}" />`,
								extend: "csv",
								className: "ui inverted button transition hidden csv_btn",
								title: `${values.table_name}`,
								titleAttr: getStrings('export-csv')
							},
							{
								text: `<img src="${SWPTLS_APP.icons.fileExcel}" />`,
								extend: "excel",
								className: "ui inverted button transition hidden excel_btn",
								title: `${values.table_name}`,
								titleAttr: getStrings('export-excel')
							},
							{
								text: `<img src="${SWPTLS_APP.icons.printIcon}" />`,
								extend: "print",
								className: "ui inverted button transition hidden print_btn",
								title: `${values.table_name}`,
								titleAttr: getStrings('print')
							},
							{
								text: `<img src="${SWPTLS_APP.icons.copySolid}" />`,
								extend: "copy",
								className: "ui inverted button transition hidden copy_btn",
								title: `${values.table_name}`,
								titleAttr: getStrings('copy')
							},
						]
					};

					// Making sorting on Clicking - correct ordering
					const container = document.querySelector('#create_tables');
					const numberOfColumns = container.querySelectorAll('thead th').length;

					// Track the sorting order for each column
					const sortingOrders = Array.from({ length: numberOfColumns }, () => 'asc');

					// Event listener for column sorting
					if (container) {
						container.addEventListener('click', function (event) {
							const target = event.target;

							// Check if the click is on a th element
							if (target.tagName === 'TH') {
								const columnIndex = target.cellIndex;

								// Toggle sorting order for the clicked column
								sortingOrders[columnIndex] =
									sortingOrders[columnIndex] === 'asc' ? 'desc' : 'asc';

								// Clear the existing sorting order
								window.swptlsDataTable.order([]);

								// Apply sorting to the clicked column with the updated order
								window.swptlsDataTable.order([columnIndex, sortingOrders[columnIndex]]).draw();
							}
						});
					}

					if (isProActive()) {
						tableOptions.lengthMenu = [
							[1, 5, 10, 15, 25, 50, 100, -1],
							[getStrings('1'), getStrings('5'), getStrings('10'), getStrings('15'), getStrings('25'), getStrings('50'), getStrings('100'), getStrings('All')]
						];
					}

					if (values.table_settings.vertical_scrolling) {
						tableOptions.scrollY = `${values.table_settings.vertical_scrolling}px`;
					}

					window.swptlsDataTable = new DataTable('#create_tables', tableOptions);

					if (isProActive()) {
						setPdfUrl(values?.source_url);
					}
				}
			},
			error(error) {
				toast.error(error.message);
				setTablePreview('');
				setPreviewLoader(false);
			},
		});
	};

	const handleClosePopup = () => {
		setVerticalmodal(false);
	};

	function handleCancelOutside(event: MouseEvent) {
		if (
			verticalModelRef.current &&
			!verticalModelRef.current.contains(event.target)
		) {
			handleClosePopup();
		}
	}
	useEffect(() => {
		document.addEventListener('mousedown', handleCancelOutside);
		return () => {
			document.removeEventListener('mousedown', handleCancelOutside);
		};
	}, [handleCancelOutside]);


	const handleTableSettingsSave = (e) => {
		e.preventDefault();

		delete tableSettings.output;
		delete tableSettings.table_data;
		delete tableSettings.message;
		delete tableSettings.table_columns;
		delete tableSettings.id;

		wp.ajax.send('swptls_save_table', {
			data: {
				nonce: getNonce(),
				id,
				settings: JSON.stringify(tableSettings),
			},
			success(response) {
				setTableSettings({
					...response,
				});

				if (!previewLoader) {
					getTablePreview(response);
				}

				toast.success("Settings saved successfully.");
			},
			error(error) { },
		});
	};

	useEffect(() => {
		getTableData();

		if (!isProActive()) {
			toast.warning(<>{getStrings('live-sync-is-limited')}<a target='blank' href='https://go.wppool.dev/DoC'>{getStrings('upgrade-pro')}</a> {getStrings('for-showing-full')}</>,
			)

			setLimitedmessage(true);
		}
	}, []);


	useEffect(() => {
		if (!previewLoader) {
			const table = window?.swptlsDataTable?.table().node();

			if (activeTab !== 'row_settings') {
				return;
			}

			if (table) {
				const handleClick = (event) => {
					const target = event.target;
					const currentNode = target.nodeName;
					if (!isProActive()) {
						return false;
					}

					switch (hidingContext) {
						case 'columns':
							// Check if the clicked element is a table cell
							if (target.nodeName === 'TD') {
								// Get the column index of the clicked cell
								const columnIndex = target.cellIndex;

								let hiddenColumns = [
									...tableSettings?.table_settings
										?.hide_column || [],
								];

								if (hiddenColumns.includes(columnIndex)) {
									hiddenColumns = hiddenColumns.filter(
										(item) => item !== columnIndex
									);
									target.classList.remove('hidden-column');
								} else {
									hiddenColumns.push(columnIndex);
									target.classList.add('hidden-column');
								}

								// Toggle 'hidden' class on every cell in the column
								const cells = table.querySelectorAll(
									`td:nth-child(${columnIndex + 1})`
								);
								cells.forEach((cell) => {
									cell.classList.toggle(
										'hidden-column',
										hiddenColumns.includes(columnIndex)
									);
								});

								setTableSettings({
									...tableSettings,
									table_settings: {
										...tableSettings?.table_settings,
										hide_column: hiddenColumns,
									},
								});
							}
							break;

						case 'rows':
							if (currentNode === 'TD') {
								// Get the parent row (tr element)
								const row = target.parentNode;
								const td = target;

								// You can also access the row index of the current row
								const rowIndex = td.dataset.row;

								let hiddenRows = [
									...tableSettings?.table_settings?.hide_rows || [],
								];

								if (hiddenRows.includes(rowIndex)) {
									hiddenRows = hiddenRows.filter(
										(item) => item !== rowIndex
									);
									row.classList.remove('hidden-row');
								} else {
									hiddenRows.push(rowIndex);
									row.classList.add('hidden-row');
								}

								setTableSettings({
									...tableSettings,
									table_settings: {
										...tableSettings.table_settings,
										hide_rows: hiddenRows,
									},
								});
							}
							break;

						case 'cells':
							if (target.nodeName === 'TD') {
								const cellIndex = target.dataset.index;

								let currentCells = [
									...tableSettings?.table_settings?.hide_cell || [],
								];

								if (currentCells.includes(cellIndex)) {
									currentCells = currentCells.filter(
										(item) => item !== cellIndex
									);
									target.classList.remove('hidden-cell');
								} else {
									currentCells.push(cellIndex);
									target.classList.add('hidden-cell');
								}

								setTableSettings({
									...tableSettings,
									table_settings: {
										...tableSettings?.table_settings,
										hide_cell: currentCells,
									},
								});
							}
							break;
					}
				};

				table.addEventListener('click', handleClick);

				return () => {
					table.removeEventListener('click', handleClick);
				};
			}
		}
	}, [previewLoader, tableSettings, hidingContext, activeTab]);


	useEffect(() => handleTableAppearance(tableSettings.table_settings), [tableSettings.table_settings, previewLoader]);


	// POPUP 
	useEffect(() => {
		const handleClick = () => {
			WPPOOL.Popup('sheets_to_wp_table_live_sync').show();
		};

		const proSettings = document.querySelectorAll('.swptls-pro-settings, .btn-pro-lock');

		console.log(proSettings)
		proSettings.forEach(item => {
			item.addEventListener('click', handleClick);
		});

		return () => {
			proSettings.forEach(item => {
				item.removeEventListener('click', handleClick);
			});
		};
	}, []);

	const handleClick = () => {
		WPPOOL.Popup('sheets_to_wp_table_live_sync').show();
	};


	const handleCopyShortcode = async (id) => {
		const shortcode = `[gswpts_table id="${id}"]`;

		try {
			await navigator.clipboard.writeText(shortcode);
			setCopySuccess(true);
			toast.success('Shortcode copied successfully.');
		} catch (err) {
			setCopySuccess(false);
			toast.success('Shortcode copy failed.');
		}
	};

	return (
		<div>
			{loader ? (
				<h2>{getStrings('loading')}</h2>
			) : (
				<>
					<div className="edit-header">
						<Row>
							<Column xs="12" sm="6">
								<Title tagName="h2">{getStrings('table-settings')}</Title>
							</Column>
							<Column xs="12" sm="6" textSm="right">
								<div className={`shortcode-copy-wrap ${!copySuccess ? '' : 'btn-success'}`}>
									<p onClick={() => handleCopyShortcode(id)}>
										{getStrings('shortcode')}: <span>{`[gswpts_table id="${id}"]`}</span>{' '}
										{OrangeCopyIcon}
									</p>
								</div>
							</Column>
						</Row>
					</div >

					<div className="edit-body">
						<div className="tab-card">
							<div className="tab-and-save-btns">
								<div className="edit-tabs">
									<button
										className={`edit-tab ${activeTab === 'data_source'
											? 'active'
											: ''
											}`}
										onClick={() =>
											handleSetActiveTab('data_source')
										}
									>
										{getStrings('data-source')}
									</button>
									<button
										className={`edit-tab ${activeTab === 'display_settings'
											? 'active'
											: ''
											}`}
										onClick={() =>
											handleSetActiveTab(
												'display_settings'
											)
										}
									>
										{getStrings('display-settings')}
									</button>
									<button
										className={`edit-tab ${activeTab === 'table_customization'
											? 'active'
											: ''
											}`}
										onClick={() =>
											handleSetActiveTab(
												'table_customization'
											)
										}
									>
										{getStrings('tc')}
									</button>
									<button
										className={`edit-tab ${activeTab === 'row_settings'
											? 'active'
											: ''
											}`}
										onClick={() =>
											handleSetActiveTab('row_settings')
										}

									>
										{getStrings('hide-row-col')}
									</button>
								</div>
								<button
									className="save-button"
									onClick={(e) => handleTableSettingsSave(e)
									}
								>
									{getStrings('save')}
								</button>
							</div>
							<div
								className={`edit-tab-content ${activeTab === 'table_customization'
									? 'table-customization'
									: activeTab === 'row_settings'
										? 'row_settings'
										: activeTab === 'display_settings'
											? 'display_settings'
											: ''
									}`}
							>
								{'data_source' === activeTab && (
									<DataSource
										tableSettings={tableSettings}
										setTableSettings={setTableSettings}
										sheetUrlRef={sheetUrlRef}
									/>
								)}

								{'display_settings' === activeTab && (
									<DisplaySettings
										tableSettings={tableSettings}
										setTableSettings={setTableSettings}
									/>
								)}

								{'table_customization' === activeTab && (
									<TableCustomization
										tableSettings={tableSettings}
										setTableSettings={setTableSettings}
									/>
								)}

								{'row_settings' === activeTab && (
									<RowSettings
										tableSettings={tableSettings}
										setTableSettings={setTableSettings}
										setPreviewClasses={setPreviewClasses}
										hidingContext={hidingContext}
										setHidingContext={setHidingContext}
									/>
								)}
								<br />

								{privatesheetmessage === true ? (
									<>
										<div className='private-sheet-notice-container invalid-download'>
											<div className="invalid-card">
												<label className="invalid-download-new">
													<span className="icon">
														<svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
															<path d="M1.67982 14.5H14.3202C15.6128 14.5 16.4185 13.1253 15.7722 12.0305L9.45205 1.32111C8.80576 0.226297 7.19424 0.226297 6.54795 1.32111L0.227771 12.0305C-0.418516 13.1253 0.387244 14.5 1.67982 14.5ZM8 8.73784C7.53837 8.73784 7.16067 8.36741 7.16067 7.91467V6.26834C7.16067 5.8156 7.53837 5.44517 8 5.44517C8.46163 5.44517 8.83933 5.8156 8.83933 6.26834V7.91467C8.83933 8.36741 8.46163 8.73784 8 8.73784ZM8.83933 12.0305H7.16067V10.3842H8.83933V12.0305Z" fill="#FF8023" />
														</svg>
													</span>
													<span>Unable to access the Sheet! Please follow the instructions below:</span>
												</label>

												<div className="text">
													<ol>
														<li>On your Google Sheet, click on the <button>Share</button>button located at the top-right corner. Then on the popup, choose the <span className='swptls-text-highlight'>“Anyone with the link”</span> option under General access
														</li>
														<li>Click on the
															<span className="icon settings-icon">
																<svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
																	<rect y="0.5" width="20" height="20" rx="5" fill="#727A80" />
																	<path fill-rule="evenodd" clip-rule="evenodd" d="M11.7321 3.52122C12.4285 3.69368 13.095 3.96996 13.7092 4.34078C13.8323 4.41508 13.9303 4.52461 13.9905 4.6552C14.0508 4.7858 14.0705 4.93146 14.0471 5.07335C13.9658 5.56869 14.0895 5.94755 14.3202 6.17904C14.5517 6.41053 14.9313 6.53347 15.4259 6.45223C15.5679 6.42866 15.7137 6.44827 15.8445 6.50851C15.9752 6.56875 16.0849 6.66686 16.1592 6.79012C16.53 7.40429 16.8063 8.07075 16.9788 8.76713C17.0135 8.90685 17.0055 9.05376 16.9557 9.18886C16.906 9.32396 16.8169 9.44101 16.6998 9.52487C16.2915 9.81747 16.1103 10.1719 16.1103 10.4997C16.1103 10.8275 16.2915 11.1827 16.6998 11.4753C16.8167 11.5591 16.9058 11.676 16.9555 11.811C17.0052 11.9459 17.0133 12.0927 16.9788 12.2323C16.8063 12.9287 16.53 13.5951 16.1592 14.2093C16.0849 14.3326 15.9752 14.4307 15.8445 14.4909C15.7137 14.5512 15.5679 14.5708 15.4259 14.5472C14.9306 14.466 14.5524 14.5896 14.321 14.8204C14.0895 15.0519 13.9658 15.4315 14.0478 15.9261C14.0713 16.0681 14.0517 16.2139 13.9915 16.3446C13.9312 16.4754 13.8331 16.585 13.7099 16.6594C13.0953 17.0303 12.4283 17.3066 11.7314 17.4789C11.5918 17.5135 11.4451 17.5054 11.3101 17.4557C11.1752 17.4059 11.0582 17.3169 10.9744 17.2C10.6825 16.7917 10.3274 16.6105 9.99957 16.6105C9.67246 16.6105 9.3166 16.7917 9.02472 17.2C8.94091 17.3169 8.82398 17.4059 8.68902 17.4557C8.55407 17.5054 8.40731 17.5135 8.2677 17.4789C7.57082 17.3066 6.90386 17.0303 6.28925 16.6594C6.16599 16.585 6.06788 16.4754 6.00764 16.3446C5.9474 16.2139 5.92779 16.0681 5.95136 15.9261C6.03332 15.4315 5.91038 15.0526 5.67817 14.8211C5.4474 14.5896 5.06853 14.466 4.5732 14.5479C4.43131 14.5713 4.28565 14.5517 4.15505 14.4914C4.02446 14.4312 3.91493 14.3332 3.84063 14.21C3.46968 13.5954 3.1934 12.9285 3.02107 12.2316C2.98651 12.092 2.99461 11.9452 3.04434 11.8103C3.09406 11.6753 3.18312 11.5584 3.30001 11.4746C3.70763 11.1827 3.88952 10.8275 3.88952 10.4997C3.88952 10.1726 3.70763 9.81675 3.30001 9.52487C3.18312 9.44106 3.09406 9.32413 3.04434 9.18918C2.99461 9.05422 2.98651 8.90746 3.02107 8.76785C3.19346 8.07122 3.46974 7.4045 3.84063 6.79012C3.91493 6.66699 4.02446 6.56897 4.15505 6.50873C4.28565 6.4485 4.43131 6.42882 4.5732 6.45223C5.06853 6.53347 5.4474 6.41053 5.67889 6.17904C5.91038 5.94755 6.03332 5.56797 5.95208 5.07335C5.92866 4.93146 5.94835 4.7858 6.00858 4.6552C6.06882 4.52461 6.16683 4.41508 6.28997 4.34078C6.90413 3.96997 7.5706 3.69369 8.26698 3.52122C8.40669 3.4865 8.55361 3.49453 8.68871 3.54426C8.82381 3.59399 8.94086 3.68314 9.02472 3.80016C9.31732 4.20778 9.67174 4.38967 9.99957 4.38967C10.3274 4.38967 10.6825 4.20778 10.9751 3.80016C11.0589 3.68327 11.1759 3.59421 11.3108 3.54449C11.4458 3.49476 11.5925 3.48666 11.7321 3.52122ZM11.8055 5.03813C11.3332 5.51045 10.7127 5.82678 9.99957 5.82678C9.2864 5.82678 8.66598 5.51117 8.19365 5.03741C7.92626 5.12588 7.66579 5.23401 7.41435 5.36092C7.41507 6.02951 7.19939 6.69091 6.69544 7.19559C6.19148 7.69955 5.52936 7.91522 4.86077 7.9145C4.73424 8.16468 4.6264 8.42493 4.53726 8.6938C5.01102 9.16613 5.32662 9.78655 5.32662 10.4997C5.32662 11.2129 5.01102 11.8333 4.53726 12.3056C4.6264 12.5745 4.73496 12.8355 4.86077 13.0856C5.52936 13.0842 6.19076 13.2999 6.69544 13.8046C7.19939 14.3078 7.41507 14.9699 7.41435 15.6385C7.66381 15.765 7.92478 15.8729 8.19365 15.962C8.66598 15.4883 9.2864 15.1727 9.99957 15.1727C10.7127 15.1727 11.3332 15.489 11.8055 15.962C12.0731 15.8736 12.3338 15.7655 12.5855 15.6385C12.5841 14.9699 12.7997 14.3085 13.3044 13.8038C13.8077 13.2999 14.4698 13.0849 15.1384 13.0849C15.2649 12.8355 15.3727 12.5745 15.4619 12.3056C14.9881 11.8333 14.6725 11.2129 14.6725 10.4997C14.6725 9.78655 14.9888 9.16613 15.4619 8.6938C15.3734 8.42641 15.2653 8.16593 15.1384 7.9145C14.4698 7.91522 13.8084 7.69955 13.3037 7.19559C12.7997 6.69163 12.5848 6.02951 12.5848 5.36092C12.3333 5.23402 12.0729 5.1266 11.8055 5.03813ZM9.99957 7.62406C10.7622 7.62406 11.4937 7.92703 12.033 8.46632C12.5723 9.00561 12.8752 9.73704 12.8752 10.4997C12.8752 11.2624 12.5723 11.9938 12.033 12.5331C11.4937 13.0724 10.7622 13.3754 9.99957 13.3754C9.23689 13.3754 8.50546 13.0724 7.96617 12.5331C7.42688 11.9938 7.12391 11.2624 7.12391 10.4997C7.12391 9.73704 7.42688 9.00561 7.96617 8.46632C8.50546 7.92703 9.23689 7.62406 9.99957 7.62406ZM9.99957 9.06189C9.61823 9.06189 9.25251 9.21337 8.98287 9.48302C8.71322 9.75266 8.56174 10.1184 8.56174 10.4997C8.56174 10.8811 8.71322 11.2468 8.98287 11.5164C9.25251 11.7861 9.61823 11.9375 9.99957 11.9375C10.3809 11.9375 10.7466 11.7861 11.0163 11.5164C11.2859 11.2468 11.4374 10.8811 11.4374 10.4997C11.4374 10.1184 11.2859 9.75266 11.0163 9.48302C10.7466 9.21337 10.3809 9.06189 9.99957 9.06189Z" fill="white" />
																</svg>
															</span>
															icon on the popup and ensure that the option <span className='swptls-text-highlight'>“Viewers and commenters can see the option to download, print, and copy”</span> is selected
														</li>
														<li>
															<span>Save the changes by clicking on the<button className='done-btn'>Done</button>button</span>
														</li>
													</ol>
												</div>
											</div>
											<div className='private-video-player'>
												<iframe className='private-player' width="360" height="215" src="https://www.youtube.com/embed/ZBYD3F7k0jg?si=ifNLQQkE8wcAfFxA" title="YouTube video player" frameborder="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
											</div>
										</div>
									</>
								) : (
									<></>
								)}
							</div>

						</div>



						<div className="table-preview wrapper">
							{privatesheetmessage === true && (
								<style>
									{`
									.table-preview::before {
									background-color: unset !important;
									}
								`}
								</style>
							)}

							{previewLoader ? (
								<h2>{getStrings('lp')}</h2>
							) : (
								<>
									{/* For limitation notice  */}

									{limitedtmessage === true ? (
										<>

											<div className="invalid-card has--limit-upgrade">
												<label className="invalid-upgrade" onClick={() => handleClick()}>
													<span className="icon">
														<svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
															<path d="M1.67982 14.5H14.3202C15.6128 14.5 16.4185 13.1253 15.7722 12.0305L9.45205 1.32111C8.80576 0.226297 7.19424 0.226297 6.54795 1.32111L0.227771 12.0305C-0.418516 13.1253 0.387244 14.5 1.67982 14.5ZM8 8.73784C7.53837 8.73784 7.16067 8.36741 7.16067 7.91467V6.26834C7.16067 5.8156 7.53837 5.44517 8 5.44517C8.46163 5.44517 8.83933 5.8156 8.83933 6.26834V7.91467C8.83933 8.36741 8.46163 8.73784 8 8.73784ZM8.83933 12.0305H7.16067V10.3842H8.83933V12.0305Z" fill="#FF8023" />
														</svg>
													</span>
													<span>The table is limited to 50 rows only. <a>Upgrade to Pro</a> to show the full Google Sheet with awesome customizations</span>
												</label>

												<button
													className="btn"
													onClick={() => handleClick()}
												>Upgrade Now →</button>
											</div>
										</>
									) : (
										<div></div>
									)}


									{/* If table is private after create  */}
									{privatesheetmessage === true ? (
										<>
											{/* Show nothing  */}
										</>
									) : (
										<div
											className={`gswpts_tables_container table-preview ${activeTab === 'row_settings'
												? previewClasses
												: ''
												} gswpts_${tableSettings?.table_settings?.table_style}${!isProActive() ? ` swptls-lite-table-preview` : ``}${tableSettings?.table_settings?.hide_on_desktop ? ` hide-column-on-desktop` : ``}${tableSettings?.table_settings?.hide_on_mobile ? ` hide-column-on-mobile` : ``}${tableSettings?.table_settings?.swap_filter_inputs ? ` swap-filter-inputs` : ``}${tableSettings?.table_settings?.swap_bottom_options ? ` swap-bottom-options` : ``}${tableSettings?.table_settings?.allow_sorting ? `` : ` sorting-off`}`}
											id="table-preview"
											dangerouslySetInnerHTML={{
												__html: tablePreview?.html,
											}}
										></div>
									)}
								</>
							)}
						</div>



					</div>
				</>
			)
			}

			{verticalmodal && (
				<Modal>
					<div
						className="delete-table-modal-wrap modal-content"
						ref={verticalModelRef}
					>
						<div
							className="cross_sign"
							onClick={() => handleClosePopup()}
						>
							{Cross}
						</div>
						<div className="delete-table-modal">
							<div className="modal-media">{Merge}</div>
							<h2>{getStrings('merge-alert')}</h2>
							<p>
								{getStrings('merge-info')}
							</p>
							<div className="action-buttons">
								<button
									className="swptls-button cancel-button"
									onClick={handleClosePopup}
								>
									{getStrings('merge-confirm')}
								</button>

							</div>
						</div>
					</div>
				</Modal>
			)}

		</div >
	);
}

export default EditTable;
