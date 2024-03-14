const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/best-selling-product')
    .controls(json)
    .css(Style)
    .register()