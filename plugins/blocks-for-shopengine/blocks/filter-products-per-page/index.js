const json = require('./controls.json')
const { blockManager } = gutenova

import { Style } from './style'

new blockManager('gutenova/filter-products-per-page')
    .controls(json)
    .css(Style)
    .register()