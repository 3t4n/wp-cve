import { addFilter } from '@wordpress/hooks';
import GenerateFeedControl from './components/GenerateFeedControl';
import LicenseControl from './components/LicenseControl';

addFilter('wcf_field_generate_feed', 'wpify-woo', Component => GenerateFeedControl);
addFilter('wcf_field_license', 'wpify-woo', Component => LicenseControl);
