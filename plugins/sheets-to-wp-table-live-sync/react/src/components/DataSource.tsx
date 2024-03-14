import React, { useState, useEffect } from 'react';
import { getStrings } from './../Helpers';
//styles
import '../styles/_dataSource.scss';
import Tooltip from './Tooltip';


const DataSource = ({ tableSettings, setTableSettings, sheetUrlRef }) => {

	const [isTitleTooLong, setIsTitleTooLong] = useState(false);

	// Function to handle changes in the table name input
	const handleTableNameChange = (e) => {
		const newValue = e.target.value;
		if (newValue.length > 252) {
			setIsTitleTooLong(true);
		} else {
			setIsTitleTooLong(false);
		}

		setTableSettings({
			...tableSettings,
			table_name: newValue,
		});
	};

	return (
		<div>
			<div className="edit-data-source-wrap">
				<div className="edit-form-group">
					<label htmlFor="table-title">
						{getStrings('table-title')}
						{/* <Tooltip content={`This title will be shown on the top of your table`} /> */}
						<Tooltip content={getStrings('tooltip-0')} />
					</label>
					<input
						type="text"
						name="table_name"
						id="table-name"
						value={tableSettings.table_name}
						onChange={handleTableNameChange}
						className={isTitleTooLong ? 'error' : ''}
						style={{ border: isTitleTooLong ? '1px solid red' : '' }}
					/>

					{isTitleTooLong && (
						<div className="error-message">{getStrings('please-reduce-title-to-save')}</div>
					)}
				</div>

				<div className="edit-form-group">
					<label htmlFor="google-sheet-url">
						{getStrings('gsu')}
						<Tooltip content={getStrings('tooltip-2')} />
					</label>
					<input
						type="text"
						name="google_sheet_url"
						id="google-sheet"
						ref={sheetUrlRef}
						value={tableSettings.source_url}
						onChange={(e) =>
							setTableSettings({
								...tableSettings,
								source_url: e.target.value,
							})
						}
					/>
				</div>
			</div>
		</div>
	);
};

export default DataSource;
