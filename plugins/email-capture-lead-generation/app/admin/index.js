import { render } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';

import Settings from './Components/Settings';
import Integration from './Components/Integration';

const settingsPage = document.getElementById('eclg-settings');
const integrationPage = document.getElementById('eclg-integration');

if ( 'undefined' !== typeof settingsPage && null !== settingsPage ) {
    domReady(function () {
        render(<Settings />, settingsPage);
    });
}

if ( 'undefined' !== typeof integrationPage && null !== integrationPage ) {
    domReady(function () {
        render(<Integration />, integrationPage);
    });
}
