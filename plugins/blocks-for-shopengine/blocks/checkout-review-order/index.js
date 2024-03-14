const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/checkout-review-order')
    .controls(json)
    .css(Style)
    .register()