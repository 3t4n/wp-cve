const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/checkout-order-pay')
    .controls(json)
    .css(Style)
    .register()