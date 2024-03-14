const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/product-size-charts')
    .controls(json)
    .css(Style)
    .register()