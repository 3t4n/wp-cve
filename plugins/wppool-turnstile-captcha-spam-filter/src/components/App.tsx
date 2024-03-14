import React, { useState } from 'react';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

import Container from './../core/Container';
import Header from './Header';
import Dashboard from './Dashboard';
import ControlBox from './ControlBox';

import { getStore, getSettings, getValidationStatus } from '../Helpers';

function App() {
	const [store, setStore] = useState(getStore());
	const [settings, setSettings] = useState(getSettings());
	const [siteKey, setSiteKey] = useState<string>(settings.site_key || '');
	const [secretKey, setSecretKey] = useState<string>(
		settings.secret_key || ''
	);
	const [validation, setValidation] = useState<boolean>(
		getValidationStatus()
	);

	return (
		<Container>
			<ToastContainer
				position="top-right"
				autoClose={2000}
				hideProgressBar={true}
				newestOnTop={false}
				closeOnClick={false}
				rtl={false}
				pauseOnFocusLoss={true}
				pauseOnHover={true}
				theme="colored"
			/>
			<Header validation={validation} siteKey={siteKey}  secretKey={secretKey} />
			<Dashboard
				store={store}
				settings={settings}
				siteKey={siteKey}
				setSiteKey={setSiteKey}
				secretKey={secretKey}
				setSecretKey={setSecretKey}
				validation={validation}
				setValidation={setValidation}
			/>
			<ControlBox
				store={store}
				setStore={setStore}
				siteKey={siteKey}
				setSiteKey={setSiteKey}
				secretKey={secretKey}
				setSecretKey={setSecretKey}
				validation={validation}
				setValidation={setValidation}
			/>
		</Container>
	);
}

export default App;
