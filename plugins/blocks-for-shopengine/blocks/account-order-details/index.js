const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/account-order-details')
    .controls(json)
    .css(Style)
    .register()