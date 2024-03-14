import React, { FC, useState, useEffect } from 'react';
import ReactTooltip from 'react-tooltip';
import { toast } from 'react-toastify';

import Title from '../core/Title';
import { getNonce } from './../Helpers';
import Icons from '../icons';

type Props = {
	settings: {
		theme: string;
		button_access: boolean;
		error_msg: string;
	};
	setSettings: any;
};

const Settings: FC<Props> = ({ settings, setSettings }) => {
	const [_, setThemeAppearance] = useState(settings?.theme);

	const handleChangeThemeAppearance = (e) => {
		const target = e.target;
		if (target.checked) {
			setThemeAppearance(target.value);
		}

		setSettings({
			...settings,
			theme: target.value,
		});
	};

	const handleChange = (e): void => {
		const data = {
			...settings,
			[e.target.name]:
				e.target.name === 'button_access'
					? e.target.checked
					: e.target.value,
		};

		setSettings(data);
	};

	const handleSubmit = (): void => {
		wp.ajax.send('save_settings', {
			data: {
				nonce: getNonce(),
				settings: JSON.stringify(settings),
				context: 'additional-settings',
			},
			success({ message }) {
				toast.success(message);
			},
			error(err: any) {
				console.error(err);
			},
		});
	};

	return (
		<div className="ect-additional-settings">
			<div className="ect-single-setting-item">
				<div className="ect-settings-control">
					<div className="theme_container">
						<Title>
							<h6>
								Theme{' '}
								<span data-tip="Choose the look and appearance of the turnstile widget">
									{Icons.tooltip_icon}
								</span>
							</h6>
						</Title>
						<div className="theme_options">
							<label
								className="radio_container"
								htmlFor="appearance-light"
							>
								Light
								<input
									type="radio"
									value="light"
									id="appearance-light"
									checked={settings.theme === 'light'}
									name="appearance"
									onChange={(e) =>
										handleChangeThemeAppearance(e)
									}
								/>
								<span className="radio_checkmark"></span>
							</label>
							<label
								className="radio_container"
								htmlFor="appearance-dark"
							>
								Dark
								<input
									type="radio"
									value="dark"
									name="appearance"
									id="appearance-dark"
									checked={settings.theme === 'dark'}
									onChange={(e) =>
										handleChangeThemeAppearance(e)
									}
								/>
								<span className="radio_checkmark"></span>
							</label>
							<label
								className="radio_container"
								htmlFor="appearance-auto"
							>
								Auto
								<input
									type="radio"
									value="auto"
									name="appearance"
									id="appearance-auto"
									checked={settings.theme === 'auto'}
									onChange={(e) =>
										handleChangeThemeAppearance(e)
									}
								/>
								<span className="radio_checkmark"></span>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div className="ect-single-setting-item">
				<div className="ect-settings-control">
					<Title>
						<h6>
							Submit button access{' '}
							<span data-tip="When enabled user can click on submit button without completing the Turnstile check. If disabled the submit button will not be clickable until the Turnstile check is completed. ">
								{Icons.tooltip_icon}
							</span>
						</h6>
					</Title>
					<div className="access-toggle-switch">
						<span
							className={`label ${
								settings.button_access
									? 'right'
									: !settings.button_access
									? 'left'
									: 'right'
							}`}
						></span>
						<div className={`btn-item`}>
							<input
								type="checkbox"
								name="button_access"
								id="submit-button-access"
								onChange={(e) => handleChange(e)}
								checked={settings?.button_access}
							/>
							<label
								htmlFor="submit-button-access"
								className={`${
									!settings.button_access
										? 'active_disable'
										: ''
								}`}
							>
								Disabled
							</label>
							<label
								htmlFor="submit-button-access"
								className={`${
									settings.button_access
										? 'active_enable'
										: ''
								}`}
							>
								Enabled
							</label>
						</div>
					</div>
				</div>

				{settings.button_access ? (
					<p className="ect-settings-desc">
						The user can click on submit button without completing
						the Turnstile check
					</p>
				) : (
					<p className="ect-settings-desc">
						The submit button is not clickable until the Turnstile
						check is completed
					</p>
				)}
			</div>

			<div className="ect-single-setting-item">
				<div className="ect-settings-control">
					<Title>
						<h6>
							Custom message{' '}
							<span data-tip="Type in the text that you want to show on the Turnstile widget">
								{Icons.tooltip_icon}
							</span>
						</h6>
					</Title>
					<input
						type="text"
						name="error_msg"
						id="error_msg"
						placeholder="Please verify that you are a human"
						onChange={(e) => handleChange(e)}
						value={settings?.error_msg}
					/>
				</div>

				<p className="ect-settings-desc">
					* Leaving it blank will show the default “Please verify you
					are human” message
				</p>
			</div>

			<div className="ect-single-setting-item submit_container">
				<div className="ect-settings-control ">
					<button type="submit" onClick={handleSubmit}>
						Save Changes
					</button>
				</div>
			</div>
			<ReactTooltip
				place="right"
				type="success"
				effect="solid"
				border={true}
				borderColor="#BFBFBF"
				backgroundColor="white"
				textColor="#616161"
				className="customECTClass"
			/>
		</div>
	);
};

export default Settings;
