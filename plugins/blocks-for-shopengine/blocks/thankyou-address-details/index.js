const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/thankyou-address-details')
    .controls(json)
    .css(Style)
    .register()