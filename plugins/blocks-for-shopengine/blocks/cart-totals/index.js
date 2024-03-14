const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/cart-totals')
    .controls(json)
    .css(Style)
    .register()