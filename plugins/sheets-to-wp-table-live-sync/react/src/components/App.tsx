import React, { useEffect } from 'react';
import { Routes, Route, useNavigate } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Column from '../core/Column';
import Container from '../core/Container';
import Row from '../core/Row';
import CreateTable from './CreateTable';
import Dashboard from './Dashboard';
import Header from './Header';
import Settings from './Settings';
import Documentation from './Documentation';
import Recommendation from './Recommendation';
import ManageTabs from './ManageTabs';

import CreateTab from './CreateTab';
import EditTab from './EditTab';

import EditTable from './EditTable';

import SetupWizard from './SetupWizard';

import { getLicenseUrl, getSetupWizardStatus, isProActive, isProInstalled, isProLicenseActive, getStrings } from '../Helpers';

// Default Styles
import '../styles/main.scss';

function App() {
	const navigate = useNavigate();

	return (
		<>
			{isProActive() && !isProLicenseActive() ? (
				<div className="wppoolsbe-elementor-notice">
					<p>{getStrings('please-activate-your-license')}{' '}
						<a href={getLicenseUrl()}>{getStrings('activate')}</a>
					</p>
				</div>
			) : (
				''
			)}
			<Container>
				<Row>
					<Column xs="12">
						<Header />

						<Routes>
							<Route path="/wizard" element={<SetupWizard />} />
							<Route path="/" element={<Dashboard />} />
							<Route
								path="/tables/create"
								element={<CreateTable />}
							/>
							<Route
								path="/tables/edit/:id"
								element={<EditTable />}
							/>

							<Route path="/tabs" element={<ManageTabs />} />
							<Route
								path="/tabs/create"
								element={<CreateTab />}
							/>
							<Route
								path="/tabs/edit/:id"
								element={<EditTab />}
							/>

							<Route path="/settings" element={<Settings />} />
							<Route path="/doc" element={<Documentation />} />

							<Route path="/recommendation" element={<Recommendation />} />
						</Routes>

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
					</Column>
				</Row>
			</Container>
		</>
	);
}

export default App;
