import { render } from '@wordpress/element';
import Catalog from './admin/catalog';

/**
 * Import the stylesheet for the plugin.
 */
//import './style/main.scss';
// Render the App component into the DOM
render( <Catalog />, document.getElementById( 'mvx-admin-catalog' ) );
