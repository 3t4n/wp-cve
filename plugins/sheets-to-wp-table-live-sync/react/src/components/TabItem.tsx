import React, { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import Modal from './../core/Modal';
import { CopyIcon, Cross, DeleteIcon, EditIcon, TrashCan } from '../icons';
import { getNonce, getStrings } from '../Helpers';
//styles
import '../styles/_table_item.scss';
import Title from '../core/Title';

function TabItem({ tab, setTabs, setTabCount }) {
	const confirmDeleteRef = useRef();
	const [copySuccess, setCopySuccess] = useState(false);
	const [deleteModal, setDeleteModal] = useState<boolean>(false);

	const handleCopyShortcode = async (id) => {
		const shortcode = `[gswpts_tab id="${id}"]`;

		try {
			await navigator.clipboard.writeText(shortcode);
			setCopySuccess(true);

			setTimeout(() => {
				setCopySuccess(false);
			}, 1000); // 1000 milliseconds = 1 second

		} catch (err) {
			setCopySuccess(false);
		}
	};

	const handleClosePopup = () => {
		setDeleteModal(false);
	};

	const handleDeleteTable = () => {
		setDeleteModal(true);
	};

	const ConfirmDeleteTab = (id) => {
		wp.ajax.send('swptls_delete_tab', {
			data: {
				nonce: getNonce(),
				id,
			},
			success({ updated_tabs }) {
				setTabs(updated_tabs);
				setTabCount(updated_tabs.length);
				setDeleteModal(false);
			},
			error(error) {
				console.error(error);
			},
		});
	};

	/**
	 * Alert if clicked on outside of element
	 *
	 * @param  event
	 */
	function handleCancelOutside(event: MouseEvent) {
		if (
			confirmDeleteRef.current &&
			!confirmDeleteRef.current.contains(event.target)
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

	const maxLength = 80;
	const truncatedTabText = tab.tab_name.length > maxLength
		? tab.tab_name.substring(0, maxLength) + "..."
		: tab.tab_name;

	return (
		<div className="table_info-action_box_wrapper">
			{deleteModal && (
				<Modal>
					<div
						className="delete-table-modal-wrap modal-content"
						ref={confirmDeleteRef}
					>
						<div
							className="cross_sign"
							onClick={() => handleClosePopup()}
						>
							{Cross}
						</div>
						<div className="delete-table-modal">
							<div className="modal-media">{TrashCan}</div>
							<h2>{getStrings('confirmation-delete')}</h2>
							<p>
								{getStrings('tab-group-delete')}
							</p>
							<div className="action-buttons">
								<button
									className="swptls-button cancel-button"
									onClick={handleClosePopup}
								>
									{getStrings('Cancel')}
								</button>
								<button
									className="swptls-button confirm-button"
									onClick={() => ConfirmDeleteTab(tab.id)}
								>
									{getStrings('Delete')}
								</button>
							</div>
						</div>
					</div>
				</Modal>
			)}

			<div className="table_info-action_box">
				<div className="table-info-box">
					<div className="table-info">
						<Link to={`/tabs/edit/${tab.id}`} className="table-edit">
							<Title tagName="h4">{truncatedTabText}</Title>
						</Link>

						<Title tagName="p">ID: TB_{tab.id}</Title>
					</div>
				</div>
				<div className="table-action-box">
					<button
						className={`copy-shortcode btn-shortcode ${!copySuccess ? '' : 'btn-success'}`}
						onClick={() => handleCopyShortcode(tab.id)}
					>
						{!copySuccess ? (
							<svg
								width="14"
								height="14"
								viewBox="0 0 14 14"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M12.6 0H5.6C4.8279 0 4.2 0.6279 4.2 1.4V4.2H1.4C0.6279 4.2 0 4.8279 0 5.6V12.6C0 13.3721 0.6279 14 1.4 14H8.4C9.1721 14 9.8 13.3721 9.8 12.6V9.8H12.6C13.3721 9.8 14 9.1721 14 8.4V1.4C14 0.6279 13.3721 0 12.6 0ZM1.4 12.6V5.6H8.4L8.4014 12.6H1.4ZM12.6 8.4H9.8V5.6C9.8 4.8279 9.1721 4.2 8.4 4.2H5.6V1.4H12.6V8.4Z"
									fill="#1E1E1E"
								/>
							</svg>
						) : (
							<svg
								width="14"
								height="14"
								viewBox="0 0 14 14"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M12.6 0H5.6C4.8279 0 4.2 0.6279 4.2 1.4V4.2H1.4C0.6279 4.2 0 4.8279 0 5.6V12.6C0 13.3721 0.6279 14 1.4 14H8.4C9.1721 14 9.8 13.3721 9.8 12.6V9.8H12.6C13.3721 9.8 14 9.1721 14 8.4V1.4C14 0.6279 13.3721 0 12.6 0ZM1.4 12.6V5.6H8.4L8.4014 12.6H1.4ZM12.6 8.4H9.8V5.6C9.8 4.8279 9.1721 4.2 8.4 4.2H5.6V1.4H12.6V8.4Z"
									fill="#FF7E47"
								/>
							</svg>
						)}
						{getStrings('copy-shortcode')}
					</button>
					<Link
						to={`/tabs/edit/${tab.id}`}
						className="table-edit"
					>
						{EditIcon}
					</Link>
					<button
						className="table-delete"
						onClick={handleDeleteTable}
					>
						{DeleteIcon}
					</button>
				</div>
			</div>
		</div>
	);
}

export default TabItem;
