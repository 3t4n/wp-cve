const json = require('./controls.json')
const { blockManager } = gutenova

import { Style } from './style'

new blockManager('gutenova/deal-products')
    .controls(json)
    .css(Style)
    .register()