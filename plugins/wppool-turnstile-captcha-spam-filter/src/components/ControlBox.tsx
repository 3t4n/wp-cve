import React, { FC, useState, useRef, useEffect } from 'react';

import Integrations from './Integrations';
import Settings from './Settings';
import Card from '../core/Card';
import Modal from './Modal';

import { getSettings } from './../Helpers';

const INTEGRATIONS = 'integrations';
const ADDITIONAL_SETTINGS = 'additional-settings';

type Props = {
	store: Object;
	setStore: any;
	siteKey: string;
	secretKey: string;
	validation: boolean;
};

const ControlBox: FC<Props> = ({
	store,
	setStore,
	siteKey,
	secretKey,
	validation,
}) => {
	const alertModalRef = useRef(null);
	const [saveChangesAlert, setSaveChangesAlert] = useState<boolean>(false);
	const [settings, setSettings] = useState<Object>(getSettings());
	const [activeTab, setActiveTab] = useState<string>(
		localStorage.getItem('ect_active_tab') || INTEGRATIONS
	);

	/**
	 * Alert if clicked on outside of element
	 *
	 * @param  event
	 */
	const handleCancelOutside = (event: MouseEvent) => {
		if (
			alertModalRef.current &&
			!alertModalRef.current.contains(event.target)
		) {
			setSaveChangesAlert(false);
		}
	};

	const handleTab = (value): void => {
		setActiveTab(value);
		localStorage.setItem('ect_active_tab', value);
	};

	useEffect(() => {
		document.addEventListener('mousedown', handleCancelOutside);
		return () => {
			document.removeEventListener('mousedown', handleCancelOutside);
		};
	}, [handleCancelOutside]);

	return (
		<div
			className={`control-box-wrap${
				!siteKey || !secretKey || !validation ? ' not-validated' : ''
			}`}
		>
			{saveChangesAlert && (
				<Modal>
					<div
						className="bulk-blacklist-modal-wrap"
						ref={alertModalRef}
					>
						<div
							className="cross_sign"
							onClick={() => setSaveChangesAlert(false)}
						>
							<span className="bar bar1"></span>
							<span className="bar bar2"></span>
						</div>

						<div className="leaveModal">
							<h3>Are you sure to reset settings?</h3>
							<p>
								Your changes will be lost if you leave the page
							</p>
							<div className="action-buttons">
								<button
									onClick={() => setSaveChangesAlert(false)}
								>
									Cancel
								</button>
							</div>
						</div>
					</div>
				</Modal>
			)}

			<div className="control-box-header">
				<div className="control-tabs">
					<button
						className={`control-tab additional-integrations${
							activeTab === INTEGRATIONS ? ' active' : ''
						}`}
						onClick={() => handleTab(INTEGRATIONS)}
					>
						Integrations
					</button>
					<button
						className={`control-tab additional-integrations${
							activeTab === ADDITIONAL_SETTINGS ? ' active' : ''
						}`}
						onClick={() => handleTab(ADDITIONAL_SETTINGS)}
					>
						Additional settings
					</button>
				</div>
			</div>
			<div className={`control-box-body`}>
				<Card customClass="tab_content_container">
					{activeTab === INTEGRATIONS && (
						<Integrations store={store} setStore={setStore} />
					)}
					{activeTab === ADDITIONAL_SETTINGS && (
						<Settings
							settings={settings}
							setSettings={setSettings}
						/>
					)}
				</Card>
			</div>
		</div>
	);
};

export default ControlBox;
