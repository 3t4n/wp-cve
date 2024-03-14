import React, { useEffect, useState } from 'react';
import { Button, TextControl as Control } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import './LicenseControl.scss';
import classnames from 'classnames';

const LicenseControl = (props) => {
	const {
		default_value,
		value = default_value,
		id,
		desc,
		slug,
		activated,
		moduleId,
		group_level = 0,
		onChange
	} = props;

	const [currentValue, setCurrentValue] = useState(value);

	const [message, setMessage] = useState('');

	useEffect(() => {
		if (onChange) {
			onChange(currentValue);
		}
	}, [currentValue]);

	const handleRequest = async (type) => {
		let baseUrl = window.wpifyWooSettings.activateUrl;

		if ('deactivate' === type) {
			baseUrl = window.wpifyWooSettings.deactivateUrl;
		}

		const url = `${baseUrl}?license=${currentValue}&slug=${slug}&module_id=${moduleId}`;

		const response = await fetch(url, {
			method: 'POST',
			headers: {
				'Accept': 'application/json, text/plain, */*',
				'Content-Type': 'application/json',
				'X-WP-Nonce': window.wpifyWooSettings.nonce
			},
		});

		if (!response.ok) {
			const json = await response.json();

			return {
				success: false,
				message: json.message,
			};
		}

		return {
			success: true,
		};
	};

	const handleActivate = async () => {
		const result = await handleRequest('activate');

		if (result.success) {
			setMessage(__('Activated', 'wpify-woo'));
			const action = window.location.href + '&wpify-woo-license-activated=1';
			const form = document.getElementById('mainform');
			form.setAttribute('action', action);
			form.submit();
		} else {
			setMessage(result.message);
		}
	};

	const handleDeactivate = async () => {
		const result = await handleRequest('deactivate');
		if (result.success) {
			setMessage(__('Deactivated', 'wpify-woo'));
			const action = window.location.href + '&wpify-woo-license-deactivated=1';
			const form = document.getElementById('mainform');
			form.setAttribute('action', action);
			form.submit();
		} else {
			setMessage(result.message);
		}
	};

	const handleChange = (value) => {
		setCurrentValue(value);
	};

	const label = __('Status:', 'wpify-woo') + ' ' + (activated
			? __('Activated', 'wpify-woo')
			: __('Not active', 'wpify-woo')
	);

	return (
		<>
			{group_level === 0 && (
				<input type="hidden" name="id" value={currentValue}/>
			)}
			{activated ? (
				<div className={classnames('wpify-woo-license-control', { activated })}>
					{label}
				</div>
			) : (
				<Control
					id={id}
					value={currentValue}
					onChange={handleChange}
					label={<span dangerouslySetInnerHTML={{ __html: label }}/>}
					help={<span dangerouslySetInnerHTML={{ __html: desc }}/>}
					className={classnames('wpify-woo-license-control', { activated })}
				/>
			)}
			{activated
				? <Button onClick={handleDeactivate} isPrimary>{__('Deactivate license', 'wpify-woo')}</Button>
				: <Button onClick={handleActivate} isPrimary>{__('Activate license', 'wpify-woo')}</Button>
			}
			<p>{message}</p>
		</>
	);
};

export default LicenseControl;
