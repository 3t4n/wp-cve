import React from 'react';
import { HashRouter } from 'react-router-dom';
import App from './components/App';

import './scss/index.scss';

wp.element.render(
	<HashRouter>
		<App />
	</HashRouter>,
	document.getElementById('ect-app-root')
);
