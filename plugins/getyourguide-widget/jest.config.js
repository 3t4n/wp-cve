const jestConfig = require('kcd-scripts/jest');

module.exports = { ...jestConfig, globals: { ...jestConfig.globals, wp: true } };
