const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/thankyou-order-confirm')
    .controls(json)
    .css(Style)
    .register()