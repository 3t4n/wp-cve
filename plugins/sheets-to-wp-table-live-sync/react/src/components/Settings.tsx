import React, { useState, useEffect } from 'react';
import Container from '../core/Container';
import { Route } from 'react-router-dom';
import Row from '../core/Row';
import Card from '../core/Card';
import Column from '../core/Column';
import { toast } from 'react-toastify';
import Tooltip from './Tooltip';
import { infoIcon } from '../icons';

import '../styles/_code.scss';
import { getNonce, isProActive, getStrings } from './../Helpers';

import CodeEditor from '@uiw/react-textarea-code-editor';

function Settings() {
	const [settings, setSettings] = useState({
		css_code_value: '',
		async_loading: 'on',
		link_support: 'smart_link',
		script_support: 'global_loading'
	});

	useEffect(() => {
		const handleClick = () => {
			WPPOOL.Popup('sheets_to_wp_table_live_sync').show();
		};

		const proSettings = document.querySelectorAll('.swptls-pro-settings, .btn-pro-lock');
		proSettings.forEach(item => {
			item.addEventListener('click', handleClick);
		});

		wp.ajax.send('swptls_get_settings', {
			data: {
				nonce: getNonce(),
			},
			success({ css, async, link_support, script_support }) {
				setSettings({
					css_code_value: css,
					async_loading: async,
					link_support: link_support,
					script_support: script_support
				});
			},
			error(error) {
				console.log(error);
			},
		});


		return () => {
			proSettings.forEach(item => {
				item.removeEventListener('click', handleClick);
			});
		};
	}, []);

	const handleSaveSettings = (e) => {
		e.preventDefault();

		wp.ajax.send('swptls_save_settings', {
			data: {
				nonce: getNonce(),
				settings: JSON.stringify(settings),
			},
			success({ message, css, async, link_support, script_support }) {
				setSettings({
					css_code_value: css,
					async_loading: async,
					link_support: link_support,
					script_support: script_support
				});

				toast.success(message);
			},
			error(error) {
				console.log(error);
			},
		});
	};

	return (
		<Container>
			<Row>
				<Column sm="12">
					<div className="swptls-settings-wrap">
						<div className="asynchronous-loading-setting">
							<Card customClass='async_loading-card'>
								<div className='swptls-async-settings'>
									<div className="async_loading">
										<input
											type="checkbox"
											name="async_loading"
											id="async-loading"
											checked={settings.async_loading === 'on'}
											onChange={(e) =>
												setSettings({
													...settings,
													async_loading: e.target.checked
														? 'on'
														: '',
												})
											}
										/>
										<label htmlFor="async-loading">
											{getStrings('asynchronous-loading')}
										</label>

									</div>
									<p>{getStrings('async-content')}
									</p>
								</div>

								{/* Link support new*/}
								<div className={`swptls-link-support${!isProActive() ? ` swptls-pro-settings` : ``}`}>
									<div className="title">
										<label htmlFor="link-support">{getStrings('choose-link-support')}</label>
										{<button className='btn-pro btn-new'>{getStrings('new')}</button>}
										{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
									</div>
									<div className='link-modes'>
										<input
											type="radio"
											name="link_support"
											id="smart_link"
											value="smart_link"
											checked={settings.link_support === 'smart_link'}
											onChange={() => setSettings({ ...settings, link_support: 'smart_link' })}
										/>
										<label htmlFor="smart_link">{getStrings('with-smart-link')}</label>
										<Tooltip content={getStrings('tooltip-18')} />
										{<button className='btn-pro recommended-pro'>{getStrings('recommended')}</button>}
									</div>
									<div className='link-modes'>
										<input
											type="radio"
											name="link_support"
											id="pretty_link"
											value="pretty_link"
											checked={settings.link_support === 'pretty_link'}
											onChange={() => setSettings({ ...settings, link_support: 'pretty_link' })}
										/>
										<label htmlFor="pretty_link">{getStrings('with-pretty-link')}</label>
										<Tooltip content={getStrings('tooltip-19')} />
									</div>
								</div>
							</Card>
						</div>

						{/* Script loading support new*/}
						<div className="asynchronous-loading-setting">
							<Card customClass='async_loading-card'>
								<div className='swptls-scripts-support'>
									<div className="title">
										<label htmlFor="scripts_loading">{getStrings('performance')}</label>
										{<button className='btn-pro btn-new'>{getStrings('new')}</button>}
									</div>
									<p>{getStrings('script-content')}</p>
									<div className='scripts-modes'>
										<input
											type="radio"
											name="script_support"
											className='global_loading_field'
											id="global_loading"
											value="global_loading"
											checked={settings.script_support === 'global_loading'}
											onChange={() => setSettings({ ...settings, script_support: 'global_loading' })}
										/>
										<label htmlFor="global_loading">
											{getStrings('global-loading')}
										</label>

									</div>
									<p className='tooltip-content'>
										{getStrings('global-loading-details')}
									</p>
									<div className={`scripts-modes${!isProActive() ? ` swptls-pro-settings` : ``}`}>
										{/* <div className='scripts-modes'> */}
										<input
											type="radio"
											name="script_support"
											className='optimized_loading_field'
											id="optimized_loading"
											value="optimized_loading"
											checked={settings.script_support === 'optimized_loading'}
											onChange={() => setSettings({ ...settings, script_support: 'optimized_loading' })}
										/>
										<label htmlFor="optimized_loading">
											{getStrings('optimized-loading')}
										</label>
										{/* {<button className='btn-pro recommended-pro'>{getStrings('recommended')}</button>} */}
										{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
									</div>
									<p className='tooltip-content'>
										{getStrings('optimized-loading-details')}
									</p>
								</div>
							</Card>

						</div>


						{/* CSS Support  */}
						<div className={`swptls-custom-css-settings${!isProActive() ? ` swptls-pro-settings` : ``}`}>
							<Card>
								<div className="title">
									<label htmlFor="custom-css">{getStrings('custom-css')}</label>
									{/* <span className='info'>{infoIcon}</span> */}
									{!isProActive() && (<button className='btn-pro'>{getStrings('pro')}</button>)}
								</div>
								<p>{getStrings('write-own-css')}

								</p>
								<CodeEditor
									value={settings.css_code_value}
									language="css"
									placeholder="Please enter CSS code."
									onChange={(evn) =>
										setSettings({
											...settings,
											css_code_value: evn.target.value,
										})
									}
									padding={15}
									minHeight={200}
									style={{
										fontFamily:
											'ui-monospace,SFMono-Regular,SF Mono,Consolas,Liberation Mono,Menlo,monospace',
										fontSize: 12,
									}}
								/>
							</Card>
						</div>


					</div>
				</Column>
			</Row>
			<Row>
				<Column sm='12'>
					<div className='btn-box text-center'>
						<button
							className="btn"
							onClick={(e) => handleSaveSettings(e)}
						>
							{getStrings('save-settings')}
						</button>
					</div>
				</Column>
			</Row>
		</Container>
	);
}

export default Settings;
