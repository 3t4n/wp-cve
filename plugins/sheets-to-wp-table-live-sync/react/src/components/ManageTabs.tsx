import React, { useState, useEffect, useRef } from 'react';
import Title from '../core/Title';
import { Link } from 'react-router-dom';
import { WhitePlusIcon, searchIcon, Cross, createTable } from '../icons';
import TabsList from './TabsList';
import Modal from '../core/Modal';
// import Tooltip from './Tooltip'; 
import Tooltip from './TooltipTab';

import { getStrings, getTabs, getNonce, isProActive } from './../Helpers';
import { infoIcon } from '../icons';

import './../styles/_manageTab.scss';

const ManageTabs = () => {
	const createTableModalRef = useRef();
	const [tabs, setTabs] = useState(getTabs() || []);
	const [copiedTabs, setCopiedTabs] = useState([]);
	const [tablesLength, setTablesLength] = useState(0);
	const [createTableModal, setCreateTableModal] = useState(false);
	const [searchKey, setSearchKey] = useState('');
	const [tabCount, setTabCount] = useState(0);

	useEffect(() => {
		if (isProActive()) {
			wp.ajax.send('swptls_get_tabs', {
				data: {
					nonce: getNonce(),
				},
				success(response) {
					setTabs(response.tabs);
					setCopiedTabs(response.tabs);
					setTablesLength(response.tables.length);
					setTabCount(response.tabs_count);
				},
				error(error) {
					console.error(error);
				},
			});
		} else {

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
		}
	}, []);

	const handleCreateTablePopup = (e) => {
		e.preventDefault();

		if (isProActive()) {
			setCreateTableModal(true);

		}

	};
	const handleClosePopup = () => {
		setCreateTableModal(false);
	};

	const handleMovetoDashboards = () => {
		// Remove the 'current' class from the "Manage Tab" li
		const manageTabLi = document.querySelector('#toplevel_page_gswpts-dashboard li.current');
		if (manageTabLi) {
			manageTabLi.classList.remove('current');
		}

		// Add the 'current' class to the "Dashboard" li with the class "wp-first-item"
		const dashboardLi = document.querySelector('#toplevel_page_gswpts-dashboard li.wp-first-item');
		if (dashboardLi) {
			dashboardLi.classList.add('current');
		}
	};

	// Reseting Tab 
	useEffect(() => {
		const currentHash = window.location.hash;
		if (!currentHash.startsWith('#/tabs/edit/')) {
			localStorage.setItem('manage-tabs-active_tab', 'manage_tab');
		}
	}, [window.location.hash]);

	/**
	 * Alert if clicked on outside of element
	 *
	 * @param  event
	 */
	function handleCancelOutside(event: MouseEvent) {
		if (
			createTableModalRef.current &&
			!createTableModalRef.current.contains(event.target)
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

	useEffect(() => {
		if (searchKey !== '') {
			const filtered = copiedTabs.filter(({ tab_name }: any) =>
				tab_name
					.toLowerCase()
					.includes(searchKey.toString().toLowerCase())
			);

			setTabs(filtered);
		} else {
			setTabs(copiedTabs);
		}
	}, [searchKey]);

	return (
		<div className={`create-tabs-wrap`}>
			{createTableModal && (
				<Modal>
					<div
						className="create-table-modal-wrap modal-content manage-modal-content"
						ref={createTableModalRef}
					>
						<div
							className="cross_sign"
							onClick={() => handleClosePopup()}
						>
							{Cross}
						</div>
						<div className="create-table-modal">
							<div className="modal-media">{createTable}</div>
							<h2>{getStrings('CTF')}</h2>
							<p>
								{getStrings('manage-tab-is-not-available')}
							</p>
							<Link
								to="/tables/create"
								className="create-table-popup-button btn"
								id='create-table-popup'
								onClick={handleMovetoDashboards}
							>
								{getStrings('create-table')}
							</Link>
						</div>
					</div>
				</Modal>
			)}
			<div className="create-table-intro">
				<Title tagName="h3">
					{getStrings('display-multiple')}
				</Title>
				<div className="btn-box">
					{tablesLength < 1 ? (
						<button
							className={`create-table btn btn-manage ${!isProActive() ? ` swptls-pro-settings` : ``} `}
							onClick={(e) => handleCreateTablePopup(e)}
						>
							{getStrings('manage-new-tabs')} {WhitePlusIcon}
						</button>
					) : (
						<Link className="create-table btn btn-manage" to="/tabs/create">
							{getStrings('manage-new-tabs')} {WhitePlusIcon}
						</Link>
					)}

					<Tooltip content={getStrings('tooltip-9')} content={``} />
					{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
				</div>
			</div>

			<div className={`table-header ${!isProActive() ? ` swptls-pro-settings` : ``}`}>

				<Title tagName="h4">
					<strong>{tabCount}</strong>&nbsp;{getStrings('tabs-created')}
				</Title>
				<div className="table-search-box">
					<input
						type="text"
						placeholder="Search tabs"
						onChange={(e) =>
							setSearchKey(e.target.value.trim())
						}
					/>
					<div className="icon">{searchIcon}</div>
				</div>
			</div>

			{searchKey !== '' && tabs.length < 1 ? (
				<h1>{getStrings('no-tabs-found')}`{searchKey}`</h1>
			) : (
				<TabsList tabs={tabs} setTabs={setTabs} setTabCount={setTabCount} />
			)}
		</div>
	);
};

export default ManageTabs;
