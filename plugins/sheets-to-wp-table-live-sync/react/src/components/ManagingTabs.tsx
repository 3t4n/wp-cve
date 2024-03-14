import React, { useState, useEffect } from 'react';
import { getStrings, getNonce } from '../Helpers';
import { BluePlusIcon } from '../icons';
import Tooltip from './TooltipTab';
import Select from 'react-select';
import './../styles/_managingTabs.scss';

const ManagingTabs = ({ currentTab, setCurrentTab }) => {

	const [activeTab, setActiveTab] = useState(localStorage.getItem('swptls_managing_active_tab') || 1);
	const [tables, setTables] = useState();
	const [selectedTables, setSelectedTables] = useState();

	const handleActiveTab = (index) => {
		localStorage.setItem('swptls_managing_active_tab', index);
		setActiveTab(index);
	};

	const handleAddNewCollection = () => {
		const tabId = currentTab.tab_settings.length + 1;

		setCurrentTab({
			...currentTab,
			tab_settings: [
				...currentTab.tab_settings,
				{ id: tabId, name: `Untitled`, tableId: [] }
			]
		});

		setActiveTab(currentTab.tab_settings.length);
		setSelectedTables([]);
	};

	const handleRemoveTab = (index) => {
		const newTabs = currentTab?.tab_settings?.filter(((tab, cIndex) => parseInt(cIndex) != parseInt(index)));

		setCurrentTab({
			...currentTab,
			tab_settings: [
				...newTabs
			]
		});
	}

	useEffect(() => {
		wp.ajax.send('swptls_get_tables', {
			data: {
				nonce: getNonce(),
			},
			success(response: any) {
				setTables(response?.tables.map((table: any) => ({ value: table.id, label: table.table_name })));
			},
			error(error: any) {
				console.error(error);
			},
		});
	}, []);

	// New tab and other Select table for this tab.
	useEffect(() => {
		const newCollection = { ...currentTab?.tab_settings };
		const collection = newCollection[activeTab];
		const result = collection?.tableID && collection?.tableID.map((tableID: any) => {
			return tables?.find((table: any) => table.value === tableID);
		});

		// Reset to an empty array if result is falsy
		setSelectedTables(result || []); // Fix empty selection if new tab added. 
	}, [currentTab.tab_settings, activeTab, tables]);
	// }, [currentTab.tab_settings, activeTab]);

	return (
		<div className="managing-tabs-wrap">
			<div className="tab-group-title">
				<label htmlFor="tab-group-title">{getStrings('tab-group-title')}
					<span>
						<Tooltip content={getStrings('tooltip-10')} />
					</span>
				</label>

				<input
					type="text"
					name="tab_name"
					id="tab-group-title"
					value={currentTab.tab_name}
					onChange={(e) => {
						setCurrentTab({
							...currentTab,
							tab_name: e.target.value,
						});
					}}
				/>

			</div>

			<div className="managing-tab-head">
				{currentTab?.tab_settings &&
					currentTab?.tab_settings?.map((tab, index) => (
						<div
							key={tab.selected_tab}
							data-tab-index={index}
							onClick={() => handleActiveTab(index)}
							className={`tab-button${index == activeTab ? ` active` : ``
								}`}
						>
							{tab.name || '\u00A0'}
							<span className="tab-close-btn" onClick={() => handleRemoveTab(index)}>x</span>
						</div>
					))}
				<button className='add-tab' onClick={handleAddNewCollection}>{BluePlusIcon}{getStrings('add-tab')}</button>
			</div>
			<div className="managing-tab-body">
				{currentTab?.tab_settings &&
					currentTab.tab_settings?.map((tab, index) => (
						<div
							className={`single-tab-body${index == activeTab ? ` active` : ` hidden`}`}
							key={index}
							data-tab-id={index}
						>
							<div className="tab-title">
								<label htmlFor="tab-title">
									{getStrings('tab-title')}
									<span>
										<Tooltip content={getStrings('tooltip-11')} />
									</span>
								</label>
								<input
									type="text"
									name="tab_title"
									id="tab-title"
									value={tab.name}
									onBlur={(e) => {
										if ('' == e.target.value) {
											const newCollection = [
												...currentTab?.tab_settings,
											];

											newCollection[index] = {
												...newCollection[index],
												name: 'Untitled',
											};

											setCurrentTab({
												...currentTab,
												tab_settings: [
													...newCollection,
												]
											});
										}
									}}
									onChange={(e) => {
										const newCollection = [
											...currentTab?.tab_settings,
										];

										newCollection[index] = {
											...newCollection[index],
											name: e.target.value,
										};

										setCurrentTab({
											...currentTab,
											tab_settings: [
												...newCollection,
											]
										});
									}}
								/>
							</div>
							<div className="select-table-for-tab-wrap">
								<label htmlFor="select-table-for-tab">
									{getStrings('select-table-for-tab')}
									<span>
										<Tooltip content={getStrings('tooltip-12')} />
									</span>
								</label>
								<Select
									name="select_table_for_tab"
									id="select-table-for-tab"
									isMulti
									options={tables}
									value={selectedTables}

									onChange={(e) => {
										const newCollection = [
											...currentTab?.tab_settings,
										];

										newCollection[index] = {
											...newCollection[index],
											tableID: [...[...e].map(({ value }) => value)]
										}

										setCurrentTab({
											...currentTab,
											tab_settings: [
												...newCollection,
											]
										});
									}}
									menuShouldBlockScroll={false}
									menuPortalTarget={document.body}
									className="tab-select-listbox"
								/>
							</div>
						</div>
					))}
			</div>
		</div>
	);
};

export default ManagingTabs;
