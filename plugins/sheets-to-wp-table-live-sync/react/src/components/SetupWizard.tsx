import React, { useState, useRef, useEffect } from 'react';
import { Desktop, infoIcon, arrowRightIcon, success } from '../icons';
import { toast } from 'react-toastify';
import CtaAdd from './CtaAdd';
import Title from '../core/Title';

import { useNavigate } from 'react-router-dom';

import {
	isValidGoogleSheetsUrl,
	getDefaultSettings,
	getNonce,
	getStrings
} from './../Helpers';

import theme_one_default_style from '../images//theme-one-default-style.png';
import theme_two_stripped_table from '../images/theme-two-stripped-table.png';
import theme_three_dark_table from '../images/theme-three-dark-table.png';
import theme_four_tailwind_style from '../images/theme-four-tailwind-style.png';
import theme_five_colored_column from '../images/theme-five-colored-column.png';
import theme_six_hovered_style from '../images/theme-six-hovered-style.png';

import DataTable from 'datatables.net-dt';
import './../../node_modules/datatables.net-dt/css/jquery.dataTables.min.css';
import { Link } from 'react-router-dom';

//styles
import "../styles/_wizard.scss";
import Tooltip from './Tooltip';

const SetupWizard = () => {
	const [step, setStep] = useState('get_started');
	const sheetUrlRef = useRef(null);
	const [loader, setLoader] = useState<boolean>(false);
	const [tableId, setTableId] = useState(0);
	const [sheetUrl, setSheetUrl] = useState<string>('');
	const [tableSettings, setTableSettings] = useState({});
	const [preview, setPreview] = useState('');
	const [copySuccess, setCopySuccess] = useState(false);
	const [previewLoader, setPreviewLoader] = useState(false);

	const handleCopyShortcode = async (id) => {
		const shortcode = `[gswpts_table id="${id}"]`;

		try {
			await navigator.clipboard.writeText(shortcode);
			setCopySuccess(true);
		} catch (err) {
			setCopySuccess(false);
		}
	};

	const handleSheetUrl = (e) => {
		const url = e.target.value.trim();

		setSheetUrl(url);
	};

	const generateTablePreview = (e) => {
		e.preventDefault();

		if (!isValidGoogleSheetsUrl(sheetUrl)) {
			sheetUrlRef.current.style.borderColor = 'red';

			return false;
		}
		sheetUrlRef.current.style.borderColor = '';

		setLoader(true);

		wp.ajax.send('swptls_get_table_preview', {
			data: {
				nonce: getNonce(),
				table_name: 'Untitled',
				source_url: sheetUrl,
				settings: JSON.stringify(getDefaultSettings()),
			},
			success({ html, settings }) {
				setLoader(false);
				setStep('edit_table');
				setPreview(html);
				new DataTable('#create_tables', {
					// pagingType: 'full_numbers',
					pageLength: parseInt(settings.default_rows_per_page)
				});
			},
			error(error) {
				console.error(error);
			},
		});
	};

	const handleCreateTable = (e) => {
		e.preventDefault();

		wp.ajax.send('swptls_create_table', {
			data: {
				nonce: getNonce(),
				sheet_url: sheetUrl,
				settings: JSON.stringify({
					...tableSettings,
					...getDefaultSettings(),
				}),
				context: 'wizard'
			},
			success({ id }) {
				setStep('complete');
				setTableId(id);
			},
			error({ message, type }) {
				toast.warn(message);
				setLoader(false);
			},
		});
	};

	useEffect(() => {
		if (step !== 'edit_table') {
			return;
		}

		setPreviewLoader(true);
		wp.ajax.send('swptls_get_table_preview', {
			data: {
				nonce: getNonce(),
				table_name: 'Untitled',
				source_url: sheetUrl,
				settings: JSON.stringify(getDefaultSettings()),
			},
			success({ html, settings }) {
				setStep('edit_table');
				setPreview(html);
				setPreviewLoader(false);
				new DataTable('#create_tables', {
					// pagingType: 'full_numbers',
					pageLength: parseInt(settings.default_rows_per_page)
				});
			},
			error(error) {
				console.error(error);
			},
		});
	}, [tableSettings?.table_settings?.table_style]);

	const handleGoToDashboard = () => {
		const navigate = useNavigate();
		navigate(0);
	}

	return (
		<div className="swptls-setup-wizard-wrap">
			{step === 'get_started' && (
				<div className="swptls-setup-screen step-one">
					<div className="setup-screen-img">{Desktop}</div>
					<h2>Let’s make your first table</h2>
					<p>
						Let us help you with your first table creation. You are
						just a step away from creating beautiful tables from
						your Google Sheets
					</p>
					<button className='btn btn-lg' onClick={() => setStep('create_table')}>
						Get Started
					</button>
					<div className="btn-box">
						<button className='btn-skip'>Skip</button>
					</div>
				</div>
			)}

			{step === 'create_table' && (
				<>
					<div className="create-table-form">
						<Title tagName="h4">
							Add Google Sheet URL{' '}
							<Tooltip content={getStrings('tooltip-20')} />
						</Title>
						<Title tagName="p">
							{/* Copy the URL of your Google Sheet and paste it here. */}{getStrings('tooltip-21')}
						</Title>

						<input
							type="text"
							name=""
							placeholder="Enter your google sheet URL"
							id="sheet-url"
							onChange={(e) => handleSheetUrl(e)}
							ref={sheetUrlRef}
						/>

						<div className="create-table-btn-wrapper text-center">
							<button
								className="btn "
								onClick={(e) => generateTablePreview(e)}
							>
								Create table{' '}
								{loader ? '....' : arrowRightIcon}
							</button>
						</div>
					</div>

					<CtaAdd />
				</>
			)}

			{step === 'edit_table' && (
				<div className="setup-wizard-edit-table-wrap text-center">
					<h4>Change theme and table style</h4>
					<p>
						You can change table theme below. You will find more
						customization in the dashboard
					</p>

					<div className="table-customization-theme-btns">
						<div className="item-wrapper">
							<button
								className={`single-theme-button${tableSettings?.table_settings
									?.table_style === 'default-style'
									? ' active'
									: ''
									}`}
								onClick={() =>
									setTableSettings({
										...tableSettings,
										table_settings: {
											...tableSettings.table_settings,
											table_style: 'default-style',
										},
									})
								}
							>
								<img
									src={theme_one_default_style}
									alt="theme_one_default_style"
								/>
							</button>
							<span>Default Style</span>
						</div>
						<div className="item-wrapper">
							<button
								className={`single-theme-button${tableSettings?.table_settings
									?.table_style === 'style-2'
									? ' active'
									: ''
									}`}
								onClick={() =>
									setTableSettings({
										...tableSettings,
										table_settings: {
											...tableSettings.table_settings,
											table_style: 'style-2',
										},
									})
								}
							>
								<img
									src={theme_two_stripped_table}
									alt="theme_two_stripped_table"
								/>
							</button>
							<span>Stripped Table</span>
						</div>
						<div className="item-wrapper">
							<button
								className={`single-theme-button${tableSettings?.table_settings
									?.table_style === 'style-4'
									? ' active'
									: ''
									}`}
								onClick={() =>
									setTableSettings({
										...tableSettings,
										table_settings: {
											...tableSettings.table_settings,
											table_style: 'style-4',
										},
									})
								}
							>
								<img
									src={theme_three_dark_table}
									alt="theme_three_dark_table"
								/>
							</button>
							<span>Dark Table</span>
						</div>
						<div className="item-wrapper">
							<button
								className={`single-theme-button${tableSettings?.table_settings
									?.table_style === 'style-5'
									? ' active'
									: ''
									}`}
								onClick={() =>
									setTableSettings({
										...tableSettings,
										table_settings: {
											...tableSettings.table_settings,
											table_style: 'style-5',
										},
									})
								}
							>
								<img
									src={theme_four_tailwind_style}
									alt="theme_four_tailwind_style"
								/>
							</button>
							<span>Taliwind Style</span>
						</div>
						<div className="item-wrapper">
							<button
								className={`single-theme-button${tableSettings?.table_settings
									?.table_style === 'style-1'
									? ' active'
									: ''
									}`}
								onClick={() =>
									setTableSettings({
										...tableSettings,
										table_settings: {
											...tableSettings.table_settings,
											table_style: 'style-1',
										},
									})
								}
							>
								<img
									src={theme_five_colored_column}
									alt="theme_five_colored_column"
								/>
							</button>
							<span>Colored Column</span>
						</div>
						<div className="item-wrapper">
							<button
								className={`single-theme-button${tableSettings?.table_settings
									?.table_style === 'style-3'
									? ' active'
									: ''
									}`}
								onClick={() =>
									setTableSettings({
										...tableSettings,
										table_settings: {
											...tableSettings.table_settings,
											table_style: 'style-3',
										},
									})
								}
							>
								<img
									src={theme_six_hovered_style}
									alt="theme_six_hovered_style"
								/>
							</button>
							<span>Hover Style</span>
						</div>
					</div>

					<div className="setup-nav-buttons">
						<button className='btn btn-back' onClick={() => setStep('create_table')}>
							Back
						</button>
						<button className='btn btn-next' onClick={(e) => handleCreateTable(e)}>
							Next
						</button>
					</div>

					<div className="table-preview wrapper">
						{previewLoader ? (
							<h1>Loading...</h1>
						) : (
							preview && (
								<div
									className={`table-preview`}
									id="table-preview"
									dangerouslySetInnerHTML={{
										__html: preview,
									}}
								></div>
							)
						)}
					</div>
				</div>
			)}

			{'complete' === step && (
				<div className="setup-complete-wrap text-center">
					<div className="setup-success-img">{success}</div>
					<h3>Table creation successfull</h3>
					<button
						className={`copy-shortcode btn-shortcode ${!copySuccess ? '' : 'btn-success'}`}
						onClick={() => handleCopyShortcode(tableId)}
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
									fill="#FF7E47"
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
									fill="#2BB885"
								/>
							</svg>
						)}
						Copy Shortcode
					</button>

					<p>
						Copy the shortcode to use in in any page or post.
						Gutenberg and Elementor blocks are also supported
					</p>

					<button className='btn btn-lg' onClick={handleGoToDashboard}>Go to Dashboard</button>
				</div>
			)}
		</div>
	);
};

export default SetupWizard;
