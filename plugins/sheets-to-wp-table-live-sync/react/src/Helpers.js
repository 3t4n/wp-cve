const config = Object.assign({}, window.SWPTLS_APP);

export function getNonce() {
	return config.nonce;
}

export function getTables() {
	return config.tables;
}

export function getStrings( key ) {
	return config.strings[key];
}

export function getTabs() {
	return config.tabs;
}

export function isValidGoogleSheetsUrl(url) {
	// Regular expression to match Google Sheets URLs
	var pattern = /^https:\/\/docs\.google\.com\/spreadsheets\/d\/([a-zA-Z0-9_-]+)/;

	// Test if the URL matches the pattern
	return pattern.test(url);
}


// Default setting once table create.
export function getDefaultSettings() {
	return {	
		table_title: false,
		default_rows_per_page: 10,
		show_info_block: true,
		responsive_table: false,
		show_x_entries: true,
		swap_filter_inputs: false,
		swap_bottom_options: false,
		allow_sorting:false,
		search_bar: true,
		table_export: [],
		vertical_scroll: null,
		cell_format: "expand",
		responsive_style: "default_style",
		redirection_type: "_blank",
		cursor_behavior: "left_right",
		table_cache: false,
		table_style: 'default-style',
		hide_column: [],
		merged_support: false,
		isvertical: false,
		hide_on_desktop:true,
		hide_on_mobile:false,
		import_styles: false,
		hide_rows: [],
		hide_cell: [],
		table_styles: false,
		table_img_support: false,
		table_link_support: false,
	};
}

export function convertToSlug(str) {
  return str.toLowerCase().replace(/\s+/g, '-');
}

export function getLicenseUrl() {
	return config.pro.license_url;
}

export function isProInstalled() {
	return config.pro.installed;
}

export function isProActive() {
	return config.pro.active;
}

export function isProLicenseActive() {
	return config.pro.license;
}

export const getSpreadsheetID = (url) => {
	if (!url || url == "") return;

	let sheetID = null;

	sheetID = url.split(/\//)[5];

	if (sheetID) return sheetID;

	return null;
}

export const getGridID = (url) => {
	if (!url || url == "") return;

	let gridID = null;

	gridID = url.match(/gid=(\w+)/);
	
	if ( ! gridID ) {
		return null;
	}

	gridID = gridID[1];

	if (gridID) return gridID;

	return null;
}

export const setPdfUrl = (url) => {
	const spreadsheetID = getSpreadsheetID(url);
	const gridID = getGridID(url);
	const pdfUrl = "https://docs.google.com/spreadsheets/d/" + spreadsheetID + "/export?format=pdf&id=" + spreadsheetID + "&gid=" + gridID;

	const createTablesWrapper = document.getElementById("create_tables_wrapper");
	const dtButtons = createTablesWrapper.getElementsByClassName("dt-buttons")[0];

	if (!dtButtons.getElementsByClassName("pdf_btn").length) {
		const pdfBtn = document.createElement("a");
		// pdfBtn.className = "ui dt-button inverted red button transition hidden pdf_btn";
		pdfBtn.className = "ui dt-button inverted button transition hidden pdf_btn";
		pdfBtn.href = pdfUrl;
		pdfBtn.download = "";

		const span = document.createElement("span");
		pdfBtn.appendChild(span);

		const img = document.createElement("img");
		img.src = SWPTLS_APP.icons.filePdf;
		span.appendChild(img);
		//Tooltip for pdf. 
		pdfBtn.setAttribute("title", getStrings('export-pdf'));
		
		dtButtons.appendChild(pdfBtn);
	}
}

export const screenSize = () => {
	// Desktop screen size
	if (screen.width > 740) {
		return "desktop";
	} else {
		return "mobile";
	}
}

// Return an array that will define the columns to hide
export const hideColumnByScreen = (arrayValues) => {

	return [
		{
			targets: arrayValues,
			visible: false,
			searchable: false,
		},
	];
};


export function getExportButtonOptions(values) {
	return [
		{
			text: `<img src="${SWPTLS_APP.iconsURL.curlyBrackets}" />`,
			className:
				'ui inverted button transition hidden json_btn',
			action(e, dt, button, config) {
				const data = dt.buttons.exportData();

				$.fn.dataTable.fileSave(
					new Blob([JSON.stringify(data)]),
					`${values.table_name}.json`
				);
			},
		},
		{
			text: `<img src="${SWPTLS_APP.iconsURL.fileCSV}" />`,
			extend: 'csv',
			className:
				'ui inverted button transition hidden csv_btn',
			title: `${values.table_name}`,
		},
		{
			text: `<img src="${SWPTLS_APP.iconsURL.fileExcel}" />`,
			extend: 'excel',
			className:
				'ui inverted button transition hidden excel_btn',
			title: `${values.table_name}`,
		},
		{
			text: `<img src="${SWPTLS_APP.iconsURL.printIcon}" />`,
			extend: 'print',
			className:
				'ui inverted button transition hidden print_btn',
			title: `${values.table_name}`,
		},
		{
			text: `<img src="${SWPTLS_APP.iconsURL.copySolid}" />`,
			extend: 'copy',
			className:
				'ui inverted button transition hidden copy_btn',
			title: `${values.table_name}`,
		},
	]
}


export const export_buttons_row_revealer = (export_btns) => {
	if (export_btns) {
		export_btns.forEach((btn) => {
			setTimeout(() => {
				export_button_revealer_by_other_input(btn);
			}, 300);
		});
	}
};

const export_button_revealer_by_other_input = (btn) => {
	const button = document.querySelector('.' + btn + '_btn');
	if (button.classList.contains('hidden')) {
		button.classList.remove('hidden');
		button.classList.add('scale');
	}
};

export const swap_bottom_options = (state) => {
	let pagination_menu = document.querySelector("#bottom_options");

	if (state) {
		pagination_menu.classList.add( 'swap' );
	} else {
		pagination_menu.classList.remove( 'swap' );
	}
}

export const swap_top_options = (state) => {
	let pagination_menu = document.querySelector("#filtering_input");

	if (state) {
		pagination_menu.classList.add( 'swap' );
	} else {
		pagination_menu.classList.remove( 'swap' );
	}
}

const bottom_option_style = ($arg) => {
	document.querySelector("#bottom_options").style.flexDirection = $arg["flex_direction"];
	document.querySelector("#create_tables_info").style.marginLeft = $arg["table_info_style"]["margin_left"];
	document.querySelector("#create_tables_info").style.marginRight = $arg["table_info_style"]["margin_right"];
	document.querySelector("#create_tables_paginate").style.marginLeft = $arg["table_paginate_style"]["margin_left"];
	document.querySelector("#create_tables_paginate").style.marginRight = $arg["table_paginate_style"]["margin_right"];
}

export const changeCellFormat = (formatStyle, tableCell) => {
	tableCell = document.querySelectorAll( tableCell );
    switch (formatStyle) {
        case "wrap":
            tableCell.forEach(cell => {
                cell.classList.remove("clip_style");
                cell.classList.remove("expanded_style");
                cell.classList.add("wrap_style");
            });
            break;

        case "clip":
            tableCell.forEach(cell => {
                cell.classList.remove("wrap_style");
                cell.classList.remove("expanded_style");
                cell.classList.add("clip_style");
            });
            break;

        case "expand":
            tableCell.forEach(cell => {
                cell.classList.remove("clip_style");
                cell.classList.remove("wrap_style");
                cell.classList.add("expanded_style");
            });
            break;

        default:
            break;
    }
};

export const displayProPopup = () => {
    WPPOOL.Popup('sheets_to_wp_table_live_sync').show();
}

export const getSetupWizardStatus = () => {
	return config.ran_setup_wizard;
}


export function show_export_buttons(buttons) {
	if (buttons) {
		buttons.forEach((btn) => {
			if (document.querySelector("." + btn + "_btn")) {
				if ( ! buttons.includes(btn) ) {
					document.querySelector("." + btn + "_btn").style = 'display: block;';
				} else {
					document.querySelector("." + btn + "_btn").style = 'display: block;';	
				}
			}
		});
	}
}

// Hide table title instant 
export function handleTableAppearance(settings) {
	if (document.getElementById('swptls-table-title')) {
		if (!settings?.show_title) {
			document.getElementById('swptls-table-title').style = 'display: none;';
		} else {
			document.getElementById('swptls-table-title').style = 'display:block;';
		}
	}

	if (document.getElementById('create_tables_filter')) {
		if (!settings?.search_bar) {
			document.getElementById('create_tables_filter').style = 'display: none;';
		} else {
			document.getElementById('create_tables_filter').style = 'display: block;';
		}
	}

	// Here Paginate 
	if (document.getElementById('create_tables_paginate')) {
		if (!settings?.pagination) {
			document.getElementById('create_tables_paginate').style = 'display: none;';
		} else {
			document.getElementById('create_tables_paginate').style = 'display: block;';
		}
	}


	if (document.getElementById('create_tables_info')) {
		if (!settings?.show_info_block) {
			document.getElementById('create_tables_info').style = 'display: none;';
		} else {
			document.getElementById('create_tables_info').style = 'display: block;';
		}
	}

	if (document.getElementById('create_tables_length')) {
		if (!settings?.show_x_entries) {
			document.getElementById('create_tables_length').style = 'display: none;';
		} else {
			document.getElementById('create_tables_length').style = 'display: block;';
		}
	}

	/**
	 * Table Height and sorting fixing
	 */
		const selectElement2 = document.querySelector('#create_tables_length select');
		const selectElement = document.querySelector('#rows-per-page');
		const bottomOptions = document.querySelector('.gswpts_tables_container #bottom_options');
		const table_height = document.querySelector('#table_height');
		const scrollBodyElement = document.querySelector('.dataTables_scrollBody');
		const createTables = document.getElementById("create_tables");

		if (selectElement2) {
			if (selectElement) {
				selectElement.addEventListener('change', (event) => {
					const selectedValue = event.target.value;
					selectElement2.value = selectedValue;

					const changeEvent = new Event('change', { bubbles: true });
					selectElement2.dispatchEvent(changeEvent);
				});
			}
		}

		if (table_height) {
			table_height.addEventListener('change', (event) => {
				const selectedHeight = event.target.value;
				if (bottomOptions) {
					bottomOptions.style.position = "relative";
					bottomOptions.style.overflow = "auto";
					bottomOptions.style.maxHeight = "";
					bottomOptions.style.height = "";
					bottomOptions.style.width = "100%";
				}

				if (selectedHeight === 'default_height') {
					
					if (bottomOptions) {
						bottomOptions.style.maxHeight = 'auto';
                		bottomOptions.style.height = 'auto';

						if(scrollBodyElement){
							scrollBodyElement.style.maxHeight = 'auto';
							scrollBodyElement.style.height = 'auto';
						}
						
					}
				} else {

					if (bottomOptions) {
						bottomOptions.style.position = "relative";
						bottomOptions.style.overflow = "auto";
						bottomOptions.style.maxHeight = selectedHeight + 'px';
						bottomOptions.style.height = selectedHeight + 'px';
						bottomOptions.style.width = "100%";
					}
				}
			});
		}

	
	/**
	 * For table header merge feature
	 */

	var tableHeaders = document.querySelectorAll('.thead-item[data-merge]');

	tableHeaders.forEach(function (header) {
		// Check if the current header has the data-merge attribute.
		var dataMerge = header.getAttribute('data-merge');
		if (dataMerge) {
			dataMerge = JSON.parse(dataMerge);
			var startCol = dataMerge[0];
			var numCols = dataMerge[1];

			// Add parentCellstart class to the starting cell
			header.classList.add('parentCellstart');
			header.style.textAlign = 'center';
			header.style.verticalAlign = 'middle';

			// Update colspan attribute
			header.setAttribute('colspan', numCols);

			// Get the next cell in the row
			var nextCell = header.nextElementSibling;
			// Loop through numCols starting from 1
			for (var i = 1; i < numCols; i++) {
				if (nextCell) {
					// Add childCell class to subsequent cells
					nextCell.classList.add('childCell');
					// Hide subsequent cells
					nextCell.style.display = 'none';
					// Move to the next cell in the row
					nextCell = nextCell.nextElementSibling;
				}
			}
		}
	});
	
	/**
	 * Merge feature extra cell removed
	 */
	var tableRows = document.querySelectorAll('.gswpts_rows');

	tableRows.forEach(function (row) {
		var cells = row.querySelectorAll('td');

		cells.forEach(function (cell, index) {
			// Check if the current cell has the parentCellstart class.
			if (cell.classList.contains('parentCellstart')) {
			
				// Get the data-merge and data-index attributes.
				var dataMerge = JSON.parse(cell.getAttribute('data-merge'));
				var dataIndex = JSON.parse(cell.getAttribute('data-index'));

				// Loop through numRows and numCols.
				for (var i = 0; i < dataMerge[0]; i++) {
					for (var j = 0; j < dataMerge[1]; j++) {
						// Calculate the target data-index for each iteration.
						var targetIndex = [
							dataIndex[0] + i,
							dataIndex[1] + j
						];

						// Find the corresponding cell.
						var targetCell = document.querySelector('[data-index="[' + targetIndex.join(',') + ']"]');

						if (targetCell) {
							// Check if the cell has an empty cell_div.
							var cellDivContent = targetCell.querySelector('.cell_div').innerText.trim();
							if (cellDivContent === '') {
								// Add a class to hide the cell.
								targetCell.classList.add('merged-cell');
								targetCell.style.display = 'none';

								if (cell.classList.contains('parentCellstart')) {
									cell.style.textAlign = 'center';
									// cell.style.lineHeight = cell.offsetHeight + 'px';
    								cell.style.verticalAlign = 'middle';
								}
								
							} else {
								targetCell.classList.add('normal-cell');
							}
						}
					}
				}
			}
		});
	});

	/**
	 * 
	 * @param {Sorting disabled and show alert below the sorting} e 
	 */

	var tableRows = document.querySelectorAll('.gswpts_rows');
	var disableSortingCheckbox = document.getElementById('disable-sorting');
	var labelForCheckbox = document.querySelector('label[for="disable-sorting"]');


	if (disableSortingCheckbox && labelForCheckbox) {
		// Create a div for the message if it doesn't exist
		var messageDiv = document.getElementById('vertical-merge-message');

		if (!messageDiv) {
			messageDiv = document.createElement('div');
			messageDiv.id = 'vertical-merge-message';
			messageDiv.style.fontStyle = 'normal';
			messageDiv.style.fontWeight = 400;
			messageDiv.style.fontSize = '16px';
			messageDiv.style.lineHeight = '21px';
			messageDiv.style.margin = '20px 0 24px 0';
			messageDiv.style.padding = '10px 10px 10px 13px';
			messageDiv.style.color = '#ce6d26';
			messageDiv.style.backgroundColor = '#fff4ec';
			messageDiv.style.display = 'none';  // Change display to none initially
			messageDiv.style.alignItems = 'center';
			messageDiv.style.justifyContent = 'flex-start';
			messageDiv.style.borderRadius = '4px';

			// Find the label associated with the checkbox
			var labelForCheckbox = document.querySelector('label[for="disable-sorting"]');

			if (labelForCheckbox) {
				// Insert the message div after the label
				labelForCheckbox.parentNode.insertBefore(messageDiv, labelForCheckbox.nextSibling);
			} else {
				console.error('Label for checkbox not found!');
			}
		}

		var verticalMergeFound = false;
		tableRows.forEach(function (row) {
			var cells = row.querySelectorAll('td');
			cells.forEach(function (cell, index) {
				// Check if the cell has rowspan attribute
				var rowspan = cell.getAttribute('rowspan');
				if (rowspan && parseInt(rowspan) > 1) {
					// disableSortingCheckbox.disabled = true;
					messageDiv.textContent = getStrings('merge-cells-notice');
					var dataIndex = cell.getAttribute('data-index');
					verticalMergeFound = true;
				}
			});
		});

		if (verticalMergeFound) {
			// disableSortingCheckbox.click();
			if (!disableSortingCheckbox.checked) {
					// Trigger click only if the checkbox is unchecked
				disableSortingCheckbox.click();
				}

				// ajax to update the sorting and make it disabled for vertical merge.
				var currentURL = window.location.href;
				var match = currentURL.match(/\/edit\/(\d+)/);
				if (match) {
					var idValue = match[1];
					wp.ajax.send('swptls_update_sorting', {
						data: {
							nonce: getNonce(),
							id: idValue,
							allow_sorting: false,  
						},
						success(response) {
						},
						error(error) {
						},
					});
				} else {
					console.error("ID not found in the URL");
				}
				// END 

			messageDiv.style.display = verticalMergeFound ? 'block' : 'none';

			disableSortingCheckbox.checked = true; // Keep the checkbox checked
			disableSortingCheckbox.disabled = true; // Disable the checkbox
			labelForCheckbox.style.opacity = 0.5; // lable disaled
		} else {
			messageDiv.style.display = verticalMergeFound ? 'block' : 'none';

			disableSortingCheckbox.disabled = false; // Enable the checkbox
			labelForCheckbox.style.opacity = 1;  // Reset opcity
		}
		
	} else {
		// console.error('Checkbox element not found!');
	}

	/**
	 * Hide merge cell notice if vertical merge found.
	 */
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

	/**
	 * 
	 * @param {Sorting disabled} e 
	 */
	function handleDisableSorting(e) {
		e.stopPropagation();
		window.swptlsDataTable.order([0, 'asc']).draw();
	}
	
	if (!settings?.allow_sorting) {

		if (document.getElementsByClassName('thead-item sorting').length) {
			const headers = document.getElementsByClassName('thead-item sorting');

			for (let item of headers) {
				item.addEventListener('click', handleDisableSorting);
			}
		}
	} else {
		if (document.getElementsByClassName('thead-item').length) {
			const headers = document.getElementsByClassName('thead-item');

			var clicked = false;

			function reloadTable() {
				if ( ! clicked ) {
					window.swptlsDataTable.order([ 0, 'desc' ]).draw();
					clicked = true;
				} else {
					window.swptlsDataTable.order([ 0, 'asc' ]).draw();
					clicked = false;
				}
			}

			for (let item of headers) {
				item.removeEventListener('click', handleDisableSorting);
				item.addEventListener('click', reloadTable);
			}
		};
	}
	
	if ( document.querySelectorAll('.dt-button').length ) {
		const buttons = document.querySelectorAll('.dt-button');
		for( let btn of  buttons) {
			btn.classList.add( 'hidden');
		}
	}


	settings?.table_export.forEach((btn) => {
		if (document.querySelector("." + btn + "_btn")) {
			document.querySelector("." + btn + "_btn").classList.remove('hidden');
		}
	});

	if ( document.querySelectorAll('.swptls-table-link').length ) {
		const links = document.querySelectorAll('.swptls-table-link');

		for( let link of links ) {
			link.target = settings?.redirection_type || '_self';
		}
	}

	changeCellFormat(settings?.cell_format, '#create_tables_wrapper th, #create_tables_wrapper td');
	changeCellFormat(settings?.responsive_style, '#create_tables_wrapper th, #create_tables_wrapper td');
}