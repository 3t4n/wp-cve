// External Dependencies
import $ from 'jquery';

// Internal Dependencies
import modules from './modules';
import fields from './fields';

$(window).on('et_builder_api_ready', (event, API) => {

  // Register custom modules
  API.registerModules(modules);
  API.registerModalFields(fields);

});
