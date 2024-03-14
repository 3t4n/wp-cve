const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/checkout-form-billing')
    .controls(json)
    .css(Style)
    .register()