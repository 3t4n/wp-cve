import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import Column from '../core/Column';
import Row from '../core/Row';
import Title from '../core/Title';
import { getNonce, getTabs, getStrings } from '../Helpers';
import { OrangeCopyIcon } from '../icons';

import DataTable from 'datatables.net-dt';
import './../../node_modules/datatables.net-dt/css/jquery.dataTables.min.css';

//styles
import '../styles/_editTable.scss';
import ManagingTabs from './ManagingTabs';
import TabSettings from './TabSettings';
import { toast } from 'react-toastify';

function EditTab() {
	const { id } = useParams();
	const [loader, setLoader] = useState<boolean>(true);
	const [activeTab, setActiveTab] = useState(
		localStorage.getItem('manage-tabs-active_tab') || 'manage_tab'
	);
	const [currentTab, setCurrentTab] = useState({});
	const [copySuccess, setCopySuccess] = useState(false);

	useEffect(() => {
		wp.ajax.send('swptls_get_tab', {
			data: {
				nonce: getNonce(),
				id,
			},
			success({ tab }) {
				setCurrentTab(tab);
			},
			error(error) { },
		});
	}, []);

	const handleActiveTab = (name) => {
		localStorage.setItem('manage-tabs-active_tab', name);
		setActiveTab(name);
	};

	const handleUpdateTab = () => {
		wp.ajax.send('swptls_save_tab', {
			data: {
				nonce: getNonce(),
				tab: JSON.stringify(currentTab),
			},
			success({ message }) {
				toast.success(message);
			},
			error({ message }) {
				toast.error(message);
			},
		});
	};

	const handleCopyShortcode = async (id) => {
		const shortcode = `[gswpts_tab id="${id}"]`;

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
			<div className="edit-header">
				<Row>
					<Column xs="12" sm="6">
						<Title tagName="h2">{getStrings('tab-settings')}</Title>
					</Column>

					<Column xs="12" sm="6" textSm="right">
						<div className={`shortcode-copy-wrap ${!copySuccess ? '' : 'btn-success'}`}>
							<p onClick={() => handleCopyShortcode(id)}>
								{getStrings('shortcode')}: <span>{`[gswpts_tab id="${id}"]`}</span>{' '}
								{OrangeCopyIcon}
							</p>
						</div>
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
								{getStrings('manage-tab')}
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
							onClick={handleUpdateTab}
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

export default EditTab;
