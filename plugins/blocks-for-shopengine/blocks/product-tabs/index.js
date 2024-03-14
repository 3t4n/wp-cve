const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/product-tabs')
    .controls(json)
    .css(Style)
    .register()