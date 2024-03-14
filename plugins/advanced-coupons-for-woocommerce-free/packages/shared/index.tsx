// Import scripts.
import sharedComponents from './components';
import sharedWCBlocks from './wc-block';
import sharedUtils from './utils';

// Global variables.
declare var acfwfObj: any;

/**
 * Initiate shared components.
 *
 * @since 4.5.9
 * */
export default function () {
  // Register component into acfwfObj global object.
  acfwfObj.components = sharedComponents;
  acfwfObj.wc = sharedWCBlocks;
  acfwfObj.utils = sharedUtils;
}
