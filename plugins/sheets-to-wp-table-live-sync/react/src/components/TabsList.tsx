import React, { useState, useEffect, useRef } from 'react';

import { Link } from 'react-router-dom';
import Card from '../core/Card';
import TabItem from './TabItem';
import { GrayPlusIcon, Cross, createTable } from '../icons';
import Modal from '../core/Modal';
import { getTables, getStrings, getNonce, isProActive } from './../Helpers';

const TabsList = ({ tabs, setTabs, setTabCount }) => {
	const [loader, setLoader] = useState<boolean>(false);
	const [createTableModal, setCreateTableModal] = useState(false);
	const [tablesLength, setTablesLength] = useState(0);
	const createTableModalRef = useRef();
	const handleCreateTablePopup = (e) => {
		e.preventDefault();

		setCreateTableModal(true);

	};

	const handleClosePopup = () => {
		setCreateTableModal(false);
	};
	const handleMovetoDashboard = () => {
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
		setLoader(true);
		if (isProActive()) {
			wp.ajax.send('swptls_get_tabs', {
				data: {
					nonce: getNonce(),
				},
				success(response) {
					setTablesLength(response.tables.length);
					setLoader(false);
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


	return (
		<Card customClass={`table-item-card manage-table-card ${!isProActive() ? ` swptls-pro-settings` : ``}`}>
			{loader && isProActive() ? (
				<Card>
					<h1>{getStrings('loading')}</h1>
				</Card>
			) : tabs.length ? (
				// If tabs exist, display them
				tabs.map((tab) => (
					<TabItem key={tab.id} tab={tab} setTabs={setTabs} setTabCount={setTabCount} />
				))
			) : (
				// If no tabs exist, display the "No tab groups have been created yet" part
				<>
					<h2 id='no-tab-group'>{getStrings('no-tab-grp-created')}</h2>
					<h4>{getStrings('tab-groups-will-appear-here')}</h4>
				</>
			)}

			{<div className="add-new-wrapper">
				{tablesLength < 1 ? (
					<button
						className={`add-new-table btn add-new-table-btn ${!isProActive() ? ` swptls-pro-settings` : ``} `}
						onClick={(e) => handleCreateTablePopup(e)}
					>
						{GrayPlusIcon}
						{getStrings('manage-new-tabs')}
					</button>
				) : (
					<Link className="add-new-table btn add-new-table-btn" to="/tabs/create">
						{GrayPlusIcon}
						{getStrings('manage-new-tabs')}
					</Link>
				)}
			</div>}

			{/* Modal */}
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
							<h2>{getStrings('create-table-to-manage')}</h2>
							<p>
								{getStrings('do-not-have-table')}
							</p>
							<Link
								to="/tables/create"
								className="create-table-popup-button btn" id='create-table-popup'
								onClick={handleMovetoDashboard}
							>
								{getStrings('create-table')}
							</Link>
						</div>
					</div>
				</Modal>
			)}
		</Card>
	);
};

export default TabsList;
