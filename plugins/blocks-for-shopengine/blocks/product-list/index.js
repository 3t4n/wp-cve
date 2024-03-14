const json = require('./controls.json')
const { blockManager } = gutenova

import { Style } from './style'

new blockManager('gutenova/product-list')
    .controls(json)
    .css(Style)
    .register()