const json = require('./controls.json')
const { blockManager } = gutenova

import { Style } from './style'

new blockManager('gutenova/product-category-lists')
    .controls(json)
    .css(Style)
    .register()