const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/checkout-payment')
    .controls(json)
    .css(Style)
    .register()