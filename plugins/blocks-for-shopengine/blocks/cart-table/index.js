const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/cart-table')
    .controls(json)
    .css(Style)
    .register()