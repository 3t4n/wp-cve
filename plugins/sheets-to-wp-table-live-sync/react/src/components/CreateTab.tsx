import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import Column from '../core/Column';
import Row from '../core/Row';
import Title from '../core/Title';
import { getNonce, getStrings } from '../Helpers';

import DataTable from 'datatables.net-dt';
import './../../node_modules/datatables.net-dt/css/jquery.dataTables.min.css';

//styles
import '../styles/_editTable.scss';
import ManagingTabs from './ManagingTabs';
import TabSettings from './TabSettings';

function CreateTab() {
	const [loader, setLoader] = useState<boolean>(true);
	const [activeTab, setActiveTab] = useState(
		localStorage.getItem('manage-tabs-active_tab') || 'manage_tab'
	);
	const [tables, setTables] = useState([]);

	const defaultTab = {
		id: -1,
		tab_name: 'Untitled',
		show_name: true,
		tab_settings: [
			{ id: 1, name: 'Tab 1', tableId: 0 },
		]
	};

	const [currentTab, setCurrentTab] = useState({ ...defaultTab });

	const handleActiveTab = (name) => {
		localStorage.setItem('manage-tabs-active_tab', name);
		setActiveTab(name);
	};

	const navigate = useNavigate();

	const handleCreateTab = () => {
		wp.ajax.send('swptls_create_tab', {
			data: {
				nonce: getNonce(),
				tab: JSON.stringify(currentTab),
			},
			success({ id, url, message }) {
				navigate(`/tabs/edit/${id}`);
			},
			error(error) {
				console.error(error);
			},
		});
	};

	useEffect(() => {
		wp.ajax.send('swptls_get_tables', {
			data: {
				nonce: getNonce(),
			},
			success(response) {
				setTables(response.tables);
			},
			error(error) {
				console.error(error);
			},
		});
	}, []);

	return (
		<div>
			<div className="edit-header">
				<Row>
					<Column xs="12" sm="6">
						<Title tagName="h2">{getStrings('table-settings')}</Title>
					</Column>
				</Row>
			</div>

			<div className="edit-body">
				<div className="tab-card">
					<div className="tab-and-save-btns">
						<div className="edit-tabs">
							<button
								className={`edit-tab ${activeTab === 'manage_tab' ? 'active' : ''
									}`}
								onClick={() =>
									handleActiveTab('manage_tab')
								}
							>
								{getStrings('managing-tabs')}
							</button>
							<button
								className={`edit-tab ${activeTab === 'tab_settings' ? 'active' : ''
									}`}
								onClick={() =>
									handleActiveTab('tab_settings')
								}
							>
								{getStrings('tab-settings')}
							</button>
						</div>
						<button
							className="save-button"
							onClick={handleCreateTab}
						>
							{getStrings('save-changes')}
						</button>
					</div>
					<div
						className={`edit-tab-content ${activeTab === 'manage_tab'
							? 'manage-tab'
							: activeTab === 'tab_settings'
								? 'tab-settings'
								: ''
							}`}
					>
						{'manage_tab' === activeTab && (
							<ManagingTabs
								currentTab={currentTab}
								setCurrentTab={setCurrentTab}
								tables={tables}
							/>
						)}

						{'tab_settings' === activeTab && (
							<TabSettings
								currentTab={currentTab}
								setCurrentTab={setCurrentTab}
							/>
						)}
					</div>
				</div>
			</div>
		</div>
	);
}

export default CreateTab;
