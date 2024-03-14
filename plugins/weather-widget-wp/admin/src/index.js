import App from './App';
import { render } from 'react-dom';

import './assets/scss/admin.scss';

const settingsPage = document.getElementById('weather-widget-wp-settings-page');
if (settingsPage) render(<App />, settingsPage);
