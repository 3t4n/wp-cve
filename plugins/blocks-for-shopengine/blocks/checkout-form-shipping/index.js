const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/checkout-form-shipping')
    .controls(json)
    .css(Style)
    .register()