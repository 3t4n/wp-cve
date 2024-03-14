import { __ } from '@wordpress/i18n';
import React from 'react';

import SettingsPage from './components/SettingsPage';

const App = () => {
    return (
        <div id="weather-widget-wp-settings-page" className="wrap">
            <h2>{ __( 'Weather Widget Settings', 'weather-widget-wp' ) }</h2>
            <SettingsPage />
        </div>
    )
}
export default App;
