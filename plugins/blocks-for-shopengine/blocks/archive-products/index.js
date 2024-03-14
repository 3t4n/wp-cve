const json = require('./controls.json')
const { blockManager } = gutenova

import { Style } from './style';

new blockManager('gutenova/archive-products')
    .controls(json)
    .css(Style)
    .register()