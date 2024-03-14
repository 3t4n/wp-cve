const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/checkout-coupon-form')
    .controls(json)
    .css(Style)
    .register()