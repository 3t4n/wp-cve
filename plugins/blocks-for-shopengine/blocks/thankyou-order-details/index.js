const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/thankyou-order-details')
    .controls(json)
    .css(Style)
    .register()